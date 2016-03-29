<?php namespace Neomerx\Tests\LimoncelloIlluminate;

use Illuminate\Http\Response;
use Neomerx\LimoncelloIlluminate\Database\Models\Role;
use Neomerx\LimoncelloIlluminate\Database\Models\Role as Model;

/**
 * @package Neomerx\LimoncelloIlluminate\Tests
 */
trait RolesTestTrait
{
    use TestCaseTrait;

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
        $model->{Model::FIELD_ID} = 10000000;
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
        $this->assertNotNull($model = factory(Model::class)->make());
        $model->{Model::FIELD_ID} = 1000;
        $model->saveOrFail();

        /** @var Response $response */
        $response = $this->callDelete($this->user(), $model->getKey());
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testCreate()
    {
        $this->beginDatabaseTransaction();

        $body = $this->getCreateRequestBody(1000);

        /** @var Response $response */
        $response = $this->callPost($this->admin(), $body);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertNotEmpty($resource = json_decode($response->getContent())->data);

        $this->assertNotNull($model = Model::find($resource->id));
        $this->assertEquals('New role', $model->{Model::FIELD_NAME});
    }

    /**
     * @return void
     */
    public function testCreateForNonAdminsUnauthorized()
    {
        $this->beginDatabaseTransaction();

        $body = $this->getCreateRequestBody(1000);

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
        $model->{Model::FIELD_ID}   = 10000000;
        $model->saveOrFail();

        $body = $this->getUpdateRequestBody($model->{Model::FIELD_ID});

        /** @var Response $response */
        $response = $this->callPatch($this->admin(), $model->getKey(), $body);
        $this->assertResponseOk();
        $this->assertNotEmpty($resource = json_decode($response->getContent())->data);

        $this->assertNotNull($model = Model::find($resource->id));
        $this->assertEquals('New value', $model->{Model::FIELD_NAME});
    }

    /**
     * @return void
     */
    public function testUpdateNonAdminsUnauthorized()
    {
        $this->beginDatabaseTransaction();

        $body = $this->getUpdateRequestBody(Role::ENUM_ROLE_USER_ID);

        /** @var Response $response */
        $response = $this->callPatch($this->user(), Role::ENUM_ROLE_USER_ID, $body);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testUpdateAnonymousUnauthorized()
    {
        $this->beginDatabaseTransaction();

        $body = $this->getUpdateRequestBody(Role::ENUM_ROLE_USER_ID);

        /** @var Response $response */
        $response = $this->callPatch(null, Role::ENUM_ROLE_USER_ID, $body);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @param int|string $idx
     *
     * @return string
     */
    private function getCreateRequestBody($idx)
    {
        $body = <<<EOT
        {
            "data" : {
                "type"  : "roles",
                "id"    : "$idx",
                "attributes" : {
                    "name"  : "New role",

                    "extra" : "field to ignore"
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
                "type"  : "roles",
                "id"    : "$idx",
                "attributes" : {
                    "name"  : "New value",

                    "extra" : "field to ignore"
                }
            }
        }
EOT;

        return $body;
    }
}
