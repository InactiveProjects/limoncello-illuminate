<?php namespace Neomerx\LimoncelloIlluminate\Api\Authorizations;

use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateInterface;
use Illuminate\Contracts\Auth\Factory as AuthManagerInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Neomerx\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use Neomerx\Limoncello\Contracts\Api\CrudAuthorizationsInterface;
use Neomerx\Limoncello\Contracts\Auth\AccountInterface;
use Neomerx\Limoncello\Contracts\JsonApi\SchemaContainerInterface;
use Neomerx\Limoncello\Errors\ErrorCollection;
use Neomerx\Limoncello\I18n\Translate as T;
use Neomerx\Limoncello\JsonApi\Decoder\RelationshipsObject;
use Neomerx\Limoncello\JsonApi\Schema;
use Neomerx\LimoncelloIlluminate\Api\Policies\BasePolicy;

/**
 * @package Neomerx\LimoncelloIlluminate
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class Authorizations implements CrudAuthorizationsInterface
{
    /**
     * @var Gate
     */
    private $guard;

    /**
     * @var bool|AccountInterface
     */
    private $curAccount = false;

    /**
     * @var SchemaContainerInterface
     */
    private $schemaContainer;

    /**
     * @inheritdoc
     */
    public function canCreateNewInstance(
        ErrorCollection $errors,
        Model $model
    ) {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function canSetAttributesOnCreate(
        ErrorCollection $errors,
        Model $model,
        Schema $schema,
        array $attributes
    ) {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function canSetAttributeOnCreate(
        ErrorCollection $errors,
        Model $model,
        Schema $schema,
        $key,
        $value
    ) {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function canSetBelongsToOnCreate(
        ErrorCollection $errors,
        Model $model,
        Schema $schema,
        array $belongsTo
    ) {
        return true;
    }

    /** @noinspection PhpTooManyParametersInspection
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function canSetBelongToOnCreate(
        ErrorCollection $errors,
        Model $model,
        Schema $schema,
        $resRelationshipName,
        $modRelationshipName,
        $idx = null
    ) {
        $relModelClass = $this->getBelongToRelationshipModelClass($schema, $resRelationshipName);
        $arguments     = [$model, $modRelationshipName, $idx, $relModelClass];
        $allowed       = $this->getGate()->allows(BasePolicy::CAN_SET_RELATIONSHIP_ON_CREATE, $arguments);
        if ($allowed === false) {
            $errors->addRelationshipError($resRelationshipName, T::trans(T::KEY_ERR_UNAUTHORIZED));
        }

        return $allowed;
    }

    /**
     * @inheritdoc
     */
    public function canSaveNewInstance(
        ErrorCollection $errors,
        Model $model,
        Schema $schema
    ) {
        $this->authorize(BasePolicy::CAN_CREATE, $model, $errors);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function canSetBelongsToManyOnCreate(
        ErrorCollection $errors,
        Model $model,
        Schema $schema,
        array $belongsToMany
    ) {
        return true;
    }

    /** @noinspection PhpTooManyParametersInspection
     * @inheritdoc
     */
    public function canSetBelongToManyRelationshipOnCreate(
        ErrorCollection $errors,
        Model $model,
        Schema $schema,
        $resRelationshipName,
        $modRelationshipName,
        RelationshipsObject $relationship
    ) {
        return true;
    }

    /** @noinspection PhpTooManyParametersInspection
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function canSetBelongToManyOnCreate(
        ErrorCollection $errors,
        Model $model,
        Schema $schema,
        $resRelationshipName,
        $modRelationshipName,
        $idx = null
    ) {
        $relModelClass = $this->getBelongToManyRelationshipModelClass($schema, $resRelationshipName);
        $arguments     = [$model, $modRelationshipName, $idx, $relModelClass];
        $allowed       = $this->getGate()->allows(BasePolicy::CAN_SET_RELATIONSHIP_ON_CREATE, $arguments);
        if ($allowed === false) {
            $errors->addRelationshipError($resRelationshipName, T::trans(T::KEY_ERR_UNAUTHORIZED));
        }

        return $allowed;
    }

    /**
     * @inheritdoc
     */
    public function canRead(
        ErrorCollection $errors,
        Model $model
    ) {
        $this->authorize(BasePolicy::CAN_READ, $model, $errors);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function canUpdateExistingInstance(
        ErrorCollection $errors,
        Model $model
    ) {
        $this->authorize(BasePolicy::CAN_UPDATE, $model, $errors);

        return true;
    }

    /**
     * @inheritdoc
     */
    public function canSetAttributesOnUpdate(
        ErrorCollection $errors,
        Model $model,
        Schema $schema,
        array $attributes
    ) {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function canSetAttributeOnUpdate(
        ErrorCollection $errors,
        Model $model,
        Schema $schema,
        $key,
        $value
    ) {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function canSetBelongsToOnUpdate(
        ErrorCollection $errors,
        Model $model,
        Schema $schema,
        array $belongsTo
    ) {
        return true;
    }

    /** @noinspection PhpTooManyParametersInspection
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function canSetBelongToOnUpdate(
        ErrorCollection $errors,
        Model $model,
        Schema $schema,
        $resRelationshipName,
        $modRelationshipName,
        $idx = null
    ) {
        $relModelClass = $this->getBelongToRelationshipModelClass($schema, $resRelationshipName);
        $arguments     = [$model, $modRelationshipName, $idx, $relModelClass];
        $allowed       = $this->getGate()->allows(BasePolicy::CAN_SET_RELATIONSHIP_ON_UPDATE, $arguments);
        if ($allowed === false) {
            $errors->addRelationshipError($resRelationshipName, T::trans(T::KEY_ERR_UNAUTHORIZED));
        }

        return $allowed;
    }

    /**
     * @inheritdoc
     */
    public function canSaveExistingInstance(
        ErrorCollection $errors,
        Model $model,
        Schema $schema
    ) {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function canSetBelongsToManyOnUpdate(
        ErrorCollection $errors,
        Model $model,
        Schema $schema,
        array $belongsToMany
    ) {
        return true;
    }

    /** @noinspection PhpTooManyParametersInspection
     * @inheritdoc
     */
    public function canSetBelongToManyRelationshipOnUpdate(
        ErrorCollection $errors,
        Model $model,
        Schema $schema,
        $resRelationshipName,
        $modRelationshipName,
        RelationshipsObject $relationship
    ) {
        return true;
    }

    /** @noinspection PhpTooManyParametersInspection
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function canSetBelongToManyOnUpdate(
        ErrorCollection $errors,
        Model $model,
        Schema $schema,
        $resRelationshipName,
        $modRelationshipName,
        $idx = null
    ) {
        $relModelClass = $this->getBelongToManyRelationshipModelClass($schema, $resRelationshipName);
        $arguments     = [$model, $modRelationshipName, $idx, $relModelClass];
        $allowed       = $this->getGate()->allows(BasePolicy::CAN_SET_RELATIONSHIP_ON_UPDATE, $arguments);
        if ($allowed === false) {
            $errors->addRelationshipError($resRelationshipName, T::trans(T::KEY_ERR_UNAUTHORIZED));
        }

        return $allowed;
    }

    /**
     * @inheritdoc
     */
    public function canDelete(
        ErrorCollection $errors,
        Model $model
    ) {
        $this->authorize(BasePolicy::CAN_DELETE, $model, $errors);

        return true;
    }

    /**
     * @param string               $ability
     * @param mixed                $arguments
     * @param ErrorCollection|null $errors
     */
    public function authorize($ability, $arguments, ErrorCollection $errors = null)
    {
        $errors = $errors !== null ? $errors : new ErrorCollection();
        if ($this->checkAllow($ability, $arguments, $errors) === false) {
            throw new JsonApiException($errors->getArrayCopy(), Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * @return AccountInterface|null
     */
    public function account()
    {
        $this->curAccount !== false ?: $this->curAccount = app(AuthManagerInterface::class)->guard()->user();

        return $this->curAccount;
    }

    /**
     * @param string          $ability
     * @param mixed           $arguments
     * @param ErrorCollection $errors
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function checkAllow($ability, $arguments, ErrorCollection $errors)
    {
        $isAllowed = $this->getGate()->allows($ability, $arguments);
        if ($isAllowed === false) {
            $title = T::trans(T::KEY_ERR_UNAUTHORIZED);
            $errors->add(new Error(null, null, null, null, $title));
        }

        return $isAllowed;
    }

    /**
     * @return Gate
     */
    private function getGate()
    {
        if ($this->guard === null) {
            $this->guard = app(GateInterface::class);
        }

        return $this->guard;
    }

    /**
     * @param Schema $schema
     * @param string $resRelationshipName
     */
    private function getBelongToRelationshipModelClass(Schema $schema, $resRelationshipName)
    {
        list($relSchemaType) = $schema->getBelongsToRelationshipsMap()[$resRelationshipName];
        $relSchema  = $this->getSchemaContainer()->getSchemaByResourceType($relSchemaType);
        $modelClass = $relSchema::MODEL;

        return $modelClass;
    }

    /**
     * @param Schema $schema
     * @param string $resRelationshipName
     */
    private function getBelongToManyRelationshipModelClass(Schema $schema, $resRelationshipName)
    {
        list($relSchemaType) = $schema->getBelongsToManyRelationshipsMap()[$resRelationshipName];
        $relSchema  = $this->getSchemaContainer()->getSchemaByResourceType($relSchemaType);
        $modelClass = $relSchema::MODEL;

        return $modelClass;
    }

    /**
     * @return SchemaContainerInterface
     */
    private function getSchemaContainer()
    {
        if ($this->schemaContainer === null) {
            $this->schemaContainer = app(SchemaContainerInterface::class);
        }

        return $this->schemaContainer;
    }
}
