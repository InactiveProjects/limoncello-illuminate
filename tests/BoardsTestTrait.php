<?php namespace Neomerx\Tests\LimoncelloIlluminate;

use Illuminate\Http\Response;
use Neomerx\LimoncelloIlluminate\Database\Models\Board as Model;
use Neomerx\LimoncelloIlluminate\Schemas\BoardSchema as ModelSchema;

/**
 * @package Neomerx\LimoncelloIlluminate\Tests
 */
trait BoardsTestTrait
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
    public function testIndexWithInclude()
    {
        /** @var Response $response */
        $response = $this->callGet(null, '', $this->getIncludeParams([
            ModelSchema::REL_POSTS,
        ]));
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
    public function testShow()
    {
        $this->assertNotNull($board = Model::first());

        /** @var Response $response */
        $response = $this->callGet($this->user(), $board->getKey());
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
        $model->{Model::FIELD_ID} = 1000;
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
        $this->assertNotNull($model = Model::first());

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

        $body = $this->getCreateRequestBody();

        /** @var Response $response */
        $response = $this->callPost($this->admin(), $body);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertNotEmpty($resource = json_decode($response->getContent())->data);

        $this->assertNotNull($model = Model::find($resource->id));
        $this->assertEquals('New board', $model->{Model::FIELD_TITLE});
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
        $model->{Model::FIELD_TITLE} = 'Title';
        $model->saveOrFail();
        $idx = $model->getKey();

        $body = $this->getUpdateRequestBody($idx);

        /** @var Response $response */
        $response = $this->callPatch($this->admin(), $idx, $body);
        $this->assertResponseOk();
        $this->assertNotEmpty($resource = json_decode($response->getContent())->data);

        $this->assertNotNull($model = Model::find($resource->id));
        $this->assertEquals('New value', $model->{Model::FIELD_TITLE});
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
            "data" : {
                "type"  : "boards",
                "id"    : null,
                "attributes" : {
                    "title"  : "New board",

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
                "type"  : "boards",
                "id"    : "$idx",
                "attributes" : {
                    "title"  : "New value",

                    "extra" : "field to ignore"
                }
            }
        }
EOT;
        return $body;
    }
}
