<?php namespace Neomerx\Tests\LimoncelloIlluminate;

use Illuminate\Http\Response;
use Neomerx\LimoncelloIlluminate\Database\Models\Post as Model;
use Neomerx\LimoncelloIlluminate\Database\Models\Role;
use Neomerx\LimoncelloIlluminate\Database\Models\User;
use Neomerx\LimoncelloIlluminate\Schemas\PostSchema as ModelSchema;

/**
 * @package Neomerx\LimoncelloIlluminate\Tests
 */
trait PostsTestTrait
{
    use TestCaseTrait;

    /**
     * @return void
     */
    public function testIndex()
    {
        /** @var Response $response */
        $response = $this->callGet($this->user());
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
        $this->assertNotNull($post = Model::first());

        /** @var Response $response */
        $response = $this->callGet($this->user(), $post->getKey());
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
        $user = $this->user();
        /** @var Model $post */
        $this->assertNotNull($post = $user->{User::REL_POSTS}->first());

        $this->beginDatabaseTransaction();

        /** @var Response $response */
        $response = $this->callDelete($user, $post->getKey());
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
        $this->assertEquals('Post title', $model->{Model::FIELD_TITLE});
    }

    /**
     * @return void
     */
    public function testCreateAnonymousUnauthorized()
    {
        $this->beginDatabaseTransaction();

        $body = $this->getCreateRequestBody();

        /** @var Response $response */
        $response = $this->callPost(null, $body);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testUpdateByAdmin()
    {
        $this->beginDatabaseTransaction();

        /** @var Model $model */
        $this->assertNotNull($model = factory(Model::class)->make());
        $model->{Model::FIELD_TITLE} = 'Title';
        $model->saveOrFail();
        $idx = $model->getKey();

        $body = $this->getUpdateRequestBody($idx);

        /** @var Response $response */
        $response = $this->callPatch($this->admin(), $idx, $body);
        $this->assertResponseOk();
        $this->assertNotEmpty($resource = json_decode($response->getContent())->data);

        $this->assertNotNull($model = Model::find($resource->id));
        $this->assertEquals('New title', $model->{Model::FIELD_TITLE});
    }

    /**
     * @return void
     */
    public function testUpdateByOwner()
    {
        $user = $this->user();
        /** @var Model $post */
        $this->assertNotNull($post = $user->{User::REL_POSTS}->first());

        $this->beginDatabaseTransaction();

        $idx = $post->getKey();

        $body = $this->getUpdateRequestBody($idx);

        /** @var Response $response */
        $response = $this->callPatch($user, $idx, $body);
        $this->assertResponseOk();
        $this->assertNotEmpty($resource = json_decode($response->getContent())->data);

        $this->assertNotNull($model = Model::find($resource->id));
        $this->assertEquals('New title', $model->{Model::FIELD_TITLE});
    }

    /**
     * @return void
     */
    public function testUpdateByNonOwnerUnauthorized()
    {
        $allUsers = User::query()->where(User::FIELD_ID_ROLE, '=', Role::ENUM_ROLE_USER_ID)->get();
        $this->assertGreaterThan(2, count($allUsers));

        $user1 = $allUsers[0];
        $user2 = $allUsers[1];
        /** @var Model $post */
        $this->assertNotNull($post = $user1->{User::REL_POSTS}->first());

        $this->beginDatabaseTransaction();

        $idx = $post->getKey();

        $body = $this->getUpdateRequestBody($idx);

        /** @var Response $response */
        $response = $this->callPatch($user2, $idx, $body);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testUpdateByAnonymousUnauthorized()
    {
        /** @var Model $post */
        $this->assertNotNull($post = Model::first());

        $this->beginDatabaseTransaction();

        $idx = $post->getKey();

        $body = $this->getUpdateRequestBody($idx);

        /** @var Response $response */
        $response = $this->callPatch(null, $idx, $body);
        $this->assertEquals(Response::HTTP_FORBIDDEN, $response->getStatusCode());
    }

    /**
     * @return void
     */
    public function testUpdateBoard()
    {
        $this->beginDatabaseTransaction();

        /** @var Model $model */
        $this->assertNotNull($model = factory(Model::class)->make());
        $model->{Model::FIELD_ID_BOARD} = 2;
        $model->saveOrFail();
        $idx = $model->getKey();

        $body = <<<EOT
        {
            "data" : {
                "type"  : "posts",
                "id"    : "$idx",
                "relationships": {
                    "board": {
                        "data": { "type": "boards", "id": "1" }
                    }
                }
            }
        }
EOT;

        /** @var Response $response */
        $response = $this->callPatch($this->admin(), $idx, $body);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
        $this->assertNotEmpty($errors = json_decode($response->getContent())->errors);
        $this->assertCount(1, $errors);
    }

    /**
     * @return string
     */
    private function getCreateRequestBody()
    {
        $body = <<<EOT
        {
            "data": {
                "type": "posts",
                "id"  : null,
                "attributes": {
                    "title" : "Post title",
                    "text"  : "Post text",

                    "extra" : "field to ignore"
                },
                "relationships": {
                    "board": {
                        "data": { "type": "boards", "id": "1" }
                    }
                }
            }
        }
EOT;

        return $body;
    }

    /**
     * @param $idx
     *
     * @return string
     */
    private function getUpdateRequestBody($idx)
    {
        $body = <<<EOT
        {
            "data" : {
                "type"  : "posts",
                "id"    : "$idx",
                "attributes" : {
                    "title" : "New title",

                    "extra" : "field to ignore"
                }
            }
        }
EOT;
        return $body;
    }
}
