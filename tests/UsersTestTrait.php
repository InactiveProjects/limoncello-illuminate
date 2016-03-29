<?php namespace Neomerx\Tests\LimoncelloIlluminate;

use Illuminate\Http\Response;
use Neomerx\LimoncelloIlluminate\Authentication\TokenCodec;
use Neomerx\LimoncelloIlluminate\Database\Factories\UserFactory;
use Neomerx\LimoncelloIlluminate\Database\Models\User as Model;
use Neomerx\LimoncelloIlluminate\Database\Seeds\SeedUsers;
use Neomerx\LimoncelloIlluminate\Http\Controllers\Web\HomeController;
use Neomerx\LimoncelloIlluminate\Schemas\UserSchema as ModelSchema;

/**
 * @package Neomerx\LimoncelloIlluminate\Tests
 */
trait UsersTestTrait
{
    use TestCaseTrait;

    /**
     * Test getting token
     */
    public function testAuthenticate()
    {
        $response = $this->call('POST', '/authenticate', [
            HomeController::AUTH_PARAM_EMAIL    => SeedUsers::TEST_USER_EMAIL,
            HomeController::AUTH_PARAM_PASSWORD => UserFactory::TEST_PASSWORD,
        ]);
        $this->assertResponseOk();
        $this->assertNotEmpty(json_decode($response->getContent())->{TokenCodec::KEY_SECRET});
    }

    /**
     * @return void
     */
    public function testIndex()
    {
        /** @var Response $response */
        $response = $this->callGet($this->admin());
        $this->assertResponseOk();
        $this->assertNotEmpty(json_decode($response->getContent())->data);
    }

    /**
     * @return void
     */
    public function testIndexPostsRelationship()
    {
        /** @var Response $response */
        $response = $this->callGet(null, '1/relationships/' . ModelSchema::REL_POSTS, $this->getPageParams(1, 10));
        $this->assertResponseOk();
        $this->assertNotEmpty(json_decode($response->getContent())->data);
    }

    /**
     * @return void
     */
    public function testIndexCommentsRelationship()
    {
        /** @var Response $response */
        $response = $this->callGet(null, '1/relationships/' . ModelSchema::REL_COMMENTS, $this->getPageParams(1, 10));
        $this->assertResponseOk();
        $this->assertNotEmpty(json_decode($response->getContent())->data);
    }

    /**
     * @return void
     */
    public function testShow()
    {
        $user = $this->user();

        /** @var Response $response */
        $response = $this->callGet($user, $user->getKey());
        $this->assertResponseOk();
        $this->assertNotEmpty(json_decode($response->getContent())->data);
    }

    /**
     * @return void
     */
    public function testDelete()
    {
        $this->beginDatabaseTransaction();

        /** @var Model $model */
        $this->assertNotNull($model = factory(Model::class)->make());
        $model->saveOrFail();

        /** @var Response $response */
        $response = $this->callDelete($this->admin(), $model->getKey());
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());

        $this->assertNull(Model::find($model->getKey()));
    }

    /**
     * @return void
     */
    public function testDeleteNonAdminsUnauthorized()
    {
        $this->beginDatabaseTransaction();

        /** @var Model $model */
        $this->assertNotNull($model = $this->user());

        /** @var Response $response */
        $response = $this->callDelete($this->user(), $model->getKey());
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testDeleteBuiltInAdminCannotBeDeleted()
    {
        $this->beginDatabaseTransaction();

        $admin = $this->admin();

        /** @var Response $response */
        $response = $this->callDelete($admin, $admin->getKey());
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testCreate()
    {
        $this->beginDatabaseTransaction();

        $body = $this->getCreateRequestBody();

        /** @var Response $response */
        $response = $this->callPost($this->admin(), $body);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertNotEmpty($resource = json_decode($response->getContent())->data);

        $this->assertNotNull($model = Model::findOrFail($resource->id));
        $this->assertEquals('John Dow', $model->{Model::FIELD_NAME});
        $this->assertEquals('john@dow.tld', $model->{Model::FIELD_EMAIL});
    }

    /**
     * @return void
     */
    public function testCreateForNonAdminsUnauthorized()
    {
        $this->beginDatabaseTransaction();

        $body = $this->getCreateRequestBody();

        /** @var Response $response */
        $response = $this->callPost($this->user(), $body);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testUpdate()
    {
        $this->beginDatabaseTransaction();

        /** @var Model $model */
        $this->assertNotNull($model = factory(Model::class)->make());
        $model->{Model::FIELD_FIRST_NAME} = 'Jane';
        $model->saveOrFail();
        $idx = $model->getKey();

        $body = $this->getUpdateRequestBody($idx);

        /** @var Response $response */
        $response = $this->callPatch($this->admin(), $idx, $body);
        $this->assertResponseOk();
        $this->assertNotEmpty($resource = json_decode($response->getContent())->data);

        $this->assertNotNull($model = Model::find($resource->id));
        $this->assertEquals('John', $model->{Model::FIELD_FIRST_NAME});
    }

    /**
     * @return void
     */
    public function testUpdateNonAdminsUnauthorized()
    {
        $this->beginDatabaseTransaction();

        /** @var Model $model */
        $this->assertNotNull($model = factory(Model::class)->create());

        $body = $this->getUpdateRequestBody($model->getKey());

        /** @var Response $response */
        $response = $this->callPatch($this->user(), $model->getKey(), $body);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @return string
     */
    private function getCreateRequestBody()
    {
        $body = <<<EOT
        {
            "data": {
                "type": "users",
                "id"  : null,
                "attributes": {
                    "title"     : "Mr.",
                    "first-name": "John",
                    "last-name" : "Dow",
                    "initials"  : "JD",
                    "email"     : "JOHN@DOW.TLD",
                    "password"  : "secret",
                    "language"  : "en",

                    "extra"     : "field to ignore"
                },
                "relationships": {
                    "role": {
                        "data": { "type": "roles", "id": "2" }
                    }
                }
            }
        }
EOT;

        return $body;
    }

    /**
     * @param int|string $idx
     *
     * @return string
     */
    private function getUpdateRequestBody($idx)
    {
        $body = <<<EOT
        {
            "data" : {
                "type"  : "users",
                "id"    : "$idx",
                "attributes" : {
                    "first-name" : "John",

                    "extra"     : "field to ignore"
                }
            }
        }
EOT;
        return $body;
    }
}
