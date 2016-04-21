<?php namespace Neomerx\LimoncelloIlluminate\Api;

use Illuminate\Database\Eloquent\Model;
use Neomerx\JsonApi\Contracts\Encoder\Parameters\EncodingParametersInterface;
use Neomerx\Limoncello\Contracts\JsonApi\PagedDataInterface;
use Neomerx\Limoncello\Errors\ErrorCollection;
use Neomerx\Limoncello\Http\JsonApiRequest;
use Neomerx\Limoncello\JsonApi\Schema;
use Neomerx\LimoncelloIlluminate\Api\Authorizations\PostsAuthorizations;
use Neomerx\LimoncelloIlluminate\Database\Models\Board;
use Neomerx\LimoncelloIlluminate\Database\Models\Post;
use Neomerx\LimoncelloIlluminate\Events\PostCreatedEvent;
use Neomerx\LimoncelloIlluminate\Events\PostUpdatedEvent;
use Neomerx\LimoncelloIlluminate\Logs\LoggerTrait;
use Neomerx\LimoncelloIlluminate\Schemas\PostSchema;

/**
 * @package Neomerx\LimoncelloIlluminate
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PostsApi extends Crud
{
    use LoggerTrait;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(new Post(), new PostsAuthorizations());
    }

    /**
     * @inheritdoc
     */
    public function index(EncodingParametersInterface $parameters = null, array $relations = [])
    {
        $relations = [
            Post::REL_USER,
            Post::REL_BOARD,
            Post::REL_COMMENTS,
        ];

        return parent::index($parameters, $relations);
    }

    /**
     * @inheritdoc
     */
    public function create(JsonApiRequest $request)
    {
        /** @var Post $model */
        $model = parent::create($request);

        event(new PostCreatedEvent($model));

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function update(JsonApiRequest $request)
    {
        /** @var Post $model */
        $model = parent::update($request);

        event(new PostUpdatedEvent($model));

        return $model;
    }

    /**
     * @param int|string                  $postId
     * @param EncodingParametersInterface $parameters
     *
     * @return PagedDataInterface
     */
    public function indexComments($postId, EncodingParametersInterface $parameters)
    {
        $this->getLogger()->debug('Index post comments started.', [Post::FIELD_ID => $postId]);

        $result = $this->indexRelationship($postId, Post::REL_COMMENTS, $parameters);

        $this->getLogger()->debug('Index post comments completed.');

        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function createInstance(JsonApiRequest $request, ErrorCollection $errors)
    {
        $model = parent::createInstance($request, $errors);

        /** @var Post $model */
        $model->{Post::FIELD_ID_USER} = $this->account()->getAuthIdentifier();

        return $model;
    }

    /**
     * @inheritDoc
     */
    protected function validateModelOnCreate(Model $model, Schema $schema, ErrorCollection $errors)
    {
        /** @var Post $model */

        parent::validateModelOnCreate($model, $schema, $errors);

        $this->validateAttributes($model->getAttributes(), [
            Post::FIELD_TITLE => 'required|max:' . Post::LENGTH_TITLE,
            Post::FIELD_TEXT  => 'required',
        ], $schema, $errors);

        $this->validateBelongsToRelationships([
            Post::REL_BOARD => $model->{Post::FIELD_ID_BOARD},
        ], [
            Post::REL_BOARD => 'required|exists:' . Board::TABLE_NAME . ',' . Board::FIELD_ID,
        ], $schema, $errors);
    }

    /**
     * @inheritDoc
     */
    protected function validateModelOnUpdate(Model $model, Schema $schema, ErrorCollection $errors)
    {
        /** @var Post $model */

        parent::validateModelOnUpdate($model, $schema, $errors);

        $this->validateAttributes($model->getAttributes(), [
            Post::FIELD_TITLE => 'sometimes|required|max:' . Post::LENGTH_TITLE,
            Post::FIELD_TEXT  => 'sometimes|required',
        ], $schema, $errors);

        if ($model->isDirty(Post::FIELD_ID_BOARD) === true) {
            $errors->addRelationshipError(PostSchema::REL_BOARD, 'Changing is not allowed.');
        }
    }
}
