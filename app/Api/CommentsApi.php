<?php namespace Neomerx\LimoncelloIlluminate\Api;

use Illuminate\Database\Eloquent\Model;
use Neomerx\Limoncello\Errors\ErrorCollection;
use Neomerx\Limoncello\Http\JsonApiRequest;
use Neomerx\Limoncello\JsonApi\Schema;
use Neomerx\LimoncelloIlluminate\Api\Authorizations\CommentsAuthorizations;
use Neomerx\LimoncelloIlluminate\Database\Models\Comment;
use Neomerx\LimoncelloIlluminate\Database\Models\Post;
use Neomerx\LimoncelloIlluminate\Events\CommentCreatedEvent;
use Neomerx\LimoncelloIlluminate\Events\CommentUpdatedEvent;
use Neomerx\LimoncelloIlluminate\Schemas\CommentSchema;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class CommentsApi extends Crud
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(new Comment(), new CommentsAuthorizations());
    }

    /**
     * @inheritdoc
     */
    public function create(JsonApiRequest $request)
    {
        /** @var Comment $model */
        $model = parent::create($request);

        event(new CommentCreatedEvent($model));

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function update(JsonApiRequest $request)
    {
        /** @var Comment $model */
        $model = parent::update($request);

        event(new CommentUpdatedEvent($model));

        return $model;
    }

    /**
     * @inheritdoc
     */
    protected function createInstance(JsonApiRequest $request, ErrorCollection $errors)
    {
        $model = parent::createInstance($request, $errors);

        /** @var Comment $model */

        $model->{Comment::FIELD_ID_USER} = $this->account()->getAuthIdentifier();

        return $model;
    }

    /**
     * @inheritDoc
     */
    protected function validateModelOnCreate(Model $model, Schema $schema, ErrorCollection $errors)
    {
        /** @var Comment $model */

        parent::validateModelOnCreate($model, $schema, $errors);

        $this->validateAttributes($model->getAttributes(), [
            Comment::FIELD_TEXT  => 'required',
        ], $schema, $errors);

        $this->validateBelongsToRelationships([
            Comment::REL_POST => $model->{Comment::FIELD_ID_POST},
        ], [
            Comment::REL_POST => 'required|exists:' . Post::TABLE_NAME . ',' . Post::FIELD_ID,
        ], $schema, $errors);
    }

    /**
     * @inheritDoc
     */
    protected function validateModelOnUpdate(Model $model, Schema $schema, ErrorCollection $errors)
    {
        /** @var Comment $model */

        parent::validateModelOnUpdate($model, $schema, $errors);

        $this->validateAttributes($model->getAttributes(), [
            Comment::FIELD_TEXT  => 'required',
        ], $schema, $errors);

        if ($model->isDirty(Comment::FIELD_ID_POST) === true) {
            $errors->addRelationshipError(CommentSchema::REL_POST, 'Changing is not allowed.');
        }
    }
}
