<?php namespace Neomerx\LimoncelloIlluminate\Api;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Neomerx\JsonApi\Contracts\Http\Parameters\ParametersInterface;
use Neomerx\Limoncello\Contracts\JsonApi\PagedDataInterface;
use Neomerx\Limoncello\Errors\ErrorCollection;
use Neomerx\Limoncello\JsonApi\Schema;
use Neomerx\LimoncelloIlluminate\Api\Authorizations\UsersAuthorizations;
use Neomerx\LimoncelloIlluminate\Database\Models\Role;
use Neomerx\LimoncelloIlluminate\Database\Models\User;
use Neomerx\LimoncelloIlluminate\Logs\LoggerTrait;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class UsersApi extends Crud
{
    use LoggerTrait;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(new User(), new UsersAuthorizations());
    }

    /**
     * @inheritdoc
     */
    public function index(ParametersInterface $parameters = null, array $relations = [])
    {
        $relations = [
            User::REL_ROLE,
            User::REL_POSTS,
            User::REL_COMMENTS,
        ];

        return parent::index($parameters, $relations);
    }

    /**
     * @param int|string          $userId
     * @param ParametersInterface $parameters
     *
     * @return PagedDataInterface
     */
    public function indexPosts($userId, ParametersInterface $parameters)
    {
        $this->getLogger()->debug('Index user posts started.', [User::FIELD_ID => $userId]);

        $result = $this->indexRelationship($userId, User::REL_POSTS, $parameters);

        $this->getLogger()->debug('Index user posts completed.');

        return $result;
    }

    /**
     * @param int|string          $userId
     * @param ParametersInterface $parameters
     *
     * @return PagedDataInterface
     */
    public function indexComments($userId, ParametersInterface $parameters)
    {
        $this->getLogger()->debug('Index user comments started.', [User::FIELD_ID => $userId]);

        $result = $this->indexRelationship($userId, User::REL_COMMENTS, $parameters);

        $this->getLogger()->debug('Index user comments completed.');

        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function setAttributes(
        Model $model,
        array $attributes,
        Schema $schema,
        Closure $policy,
        ErrorCollection $errors
    ) {
        if (array_key_exists(User::FIELD_EMAIL, $attributes) === true) {
            $attributes[User::FIELD_EMAIL] = strtolower($attributes[User::FIELD_EMAIL]);
        }

        parent::setAttributes($model, $attributes, $schema, $policy, $errors);
    }

    /**
     * @inheritDoc
     */
    protected function validateModelOnCreate(Model $model, Schema $schema, ErrorCollection $errors)
    {
        /** @var User $model */

        parent::validateModelOnCreate($model, $schema, $errors);

        $this->validateAttributes($model->getAttributes(), [
            User::FIELD_TITLE      => 'max:' . User::LENGTH_TITLE,
            User::FIELD_FIRST_NAME => 'required|max:' . User::LENGTH_FIRST_NAME,
            User::FIELD_LAST_NAME  => 'max:' . User::LENGTH_LAST_NAME,
            User::FIELD_EMAIL      => 'required|email|max:' . User::LENGTH_EMAIL .
                '|unique:' . User::TABLE_NAME . ',' . User::FIELD_EMAIL,
            User::FIELD_LANGUAGE   => 'max:' . User::LENGTH_LANGUAGE,
        ], $schema, $errors);

        $this->validateBelongsToRelationships([
            User::REL_ROLE => $model->{User::FIELD_ID_ROLE},
        ], [
            User::REL_ROLE => 'required|exists:' . Role::TABLE_NAME . ',' . Role::FIELD_ID,
        ], $schema, $errors);
    }

    /**
     * @inheritDoc
     */
    protected function validateModelOnUpdate(Model $model, Schema $schema, ErrorCollection $errors)
    {
        /** @var User $model */

        parent::validateModelOnUpdate($model, $schema, $errors);

        $this->validateAttributes($model->getDirty(), [
            User::FIELD_TITLE      => 'max:' . User::LENGTH_TITLE,
            User::FIELD_FIRST_NAME => 'sometimes|required|max:' . User::LENGTH_FIRST_NAME,
            User::FIELD_LAST_NAME  => 'max:' . User::LENGTH_LAST_NAME,
            User::FIELD_EMAIL      => 'sometimes|required|email|max:' . User::LENGTH_EMAIL .
                '|unique:' . User::TABLE_NAME . ',' . User::FIELD_EMAIL,
            User::FIELD_LANGUAGE   => 'max:' . User::LENGTH_LANGUAGE,
        ], $schema, $errors);

        $this->validateBelongsToRelationships([
            User::REL_ROLE => $model->{User::FIELD_ID_ROLE},
        ], [
            User::REL_ROLE => 'sometimes|required|exists:' . Role::TABLE_NAME . ',' . Role::FIELD_ID,
        ], $schema, $errors);
    }
}
