<?php namespace Neomerx\LimoncelloIlluminate\Api;

use Illuminate\Contracts\Auth\Factory as AuthManagerInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request as IlluminateRequest;
use Illuminate\Validation\Factory;
use Illuminate\Validation\Validator;
use Neomerx\JsonApi\Contracts\Http\Parameters\ParametersInterface;
use Neomerx\Limoncello\Api\Crud as BaseCrud;
use Neomerx\Limoncello\Contracts\Api\CrudAuthorizationsInterface;
use Neomerx\Limoncello\Contracts\Auth\AccountInterface;
use Neomerx\Limoncello\Contracts\JsonApi\FactoryInterface;
use Neomerx\Limoncello\Contracts\JsonApi\PagedDataInterface;
use Neomerx\Limoncello\Errors\ErrorCollection;
use Neomerx\Limoncello\Http\JsonApiRequest;
use Neomerx\Limoncello\JsonApi\Schema;
use Neomerx\LimoncelloIlluminate\Http\Requests\Request;
use Neomerx\LimoncelloIlluminate\Logs\LoggerTrait;

/**
 * @package Neomerx\LimoncelloIlluminate
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class Crud extends BaseCrud
{
    use LoggerTrait;

    /**
     * Max number of resources in paging.
     */
    const MAX_PAGE_SIZE = 30;

    /**
     * @var bool|AccountInterface
     */
    private $curAccount = false;

    /**
     * @param Model                       $model
     * @param CrudAuthorizationsInterface $authorizations
     */
    public function __construct(Model $model, CrudAuthorizationsInterface $authorizations)
    {
        $container = app();
        /** @var FactoryInterface $factory */
        $factory   = $container->make(FactoryInterface::class);
        parent::__construct($container, $factory, $model, $authorizations);
    }

    /**
     * @inheritdoc
     */
    public function index(ParametersInterface $parameters = null, array $relations = [])
    {
        $this->getLogger()->debug('Index resources started.');

        $result = parent::index($parameters, $relations);

        $this->getLogger()->debug('Index resources completed.');

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function create(JsonApiRequest $request)
    {
        $this->getLogger()->debug('Create resource started.');

        $result = parent::create($request);

        $this->getLogger()->debug('Create resource completed.', ['result' => $result]);

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function read($index, ParametersInterface $parameters = null, array $relations = [])
    {
        $this->getLogger()->debug('Read resource started.');

        $result = parent::read($index, $parameters, $relations);

        $this->getLogger()->debug('Read resource completed.', ['result' => $result]);

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function update(JsonApiRequest $request)
    {
        $this->getLogger()->debug('Update resource started.');

        $result = parent::update($request);

        $this->getLogger()->debug('Update resource completed.', ['result' => $result]);

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function delete($index)
    {
        $this->getLogger()->debug('Delete resource started.', ['id' => $index]);

        parent::delete($index);

        $this->getLogger()->debug('Delete resource completed.');
    }

    /**
     * @return AccountInterface|null
     */
    public function account()
    {
        $this->curAccount !== false ?: $this->curAccount = app(AuthManagerInterface::class)->guard()->user();

        return $this->curAccount;
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     * @inheritdoc
     *
     * @return PagedDataInterface
     */
    protected function readOnIndex(Builder $builder, ParametersInterface $parameters = null)
    {
        return $this->paginateBuilder($builder, $parameters);
    }

    /**
     * @param int|string          $resourceId
     * @param string              $relationshipName
     * @param ParametersInterface $parameters
     *
     * @return PagedDataInterface
     */
    public function indexRelationship($resourceId, $relationshipName, ParametersInterface $parameters)
    {
        $resource = $this->read($resourceId);

        /** @var Relation $relation */
        $relation = $resource->{$relationshipName}();

        $result = $this->paginateBuilder($relation->getQuery(), $parameters);

        $this->applyIndexPolicy($result->getData());

        return $result;
    }

    /**
     * @param Builder             $builder
     * @param ParametersInterface $parameters
     *
     * @return PagedDataInterface
     */
    protected function paginateBuilder(Builder $builder, ParametersInterface $parameters)
    {
        $pageSize   = $this->getPageSize($parameters);
        $pageNumber = $this->getPageNumber($parameters);
        $paginator  = $builder->paginate($pageSize, ['*'], 'page', $pageNumber);

        /** @var IlluminateRequest $request */
        $request = $this->getContainer()->make(IlluminateRequest::class);
        $url     = $request->url();
        $query   = $request->query();

        $pagedData  = $this->getFactory()->createPagingStrategy()->createPagedData($paginator, $url, true, $query);

        return $pagedData;
    }

    /**
     * @param ParametersInterface|null $parameters
     *
     * @return int
     */
    protected function getPageSize(ParametersInterface $parameters = null)
    {
        return $this->getPagingParameter(Request::PARAM_PAGING_SIZE, static::MAX_PAGE_SIZE, $parameters);
    }

    /**
     * @param ParametersInterface|null $parameters
     *
     * @return int|null
     */
    protected function getPageNumber(ParametersInterface $parameters = null)
    {
        return $this->getPagingParameter(Request::PARAM_PAGING_NUMBER, null, $parameters);
    }

    /**
     * @param string                   $key
     * @param mixed                    $default
     * @param ParametersInterface|null $parameters
     *
     * @return mixed
     */
    protected function getPagingParameter($key, $default, ParametersInterface $parameters = null)
    {
        $value = $default;
        if ($parameters !== null) {
            $paging = $parameters->getPaginationParameters();
            if (empty($paging) === false && array_key_exists($key, $paging) === true) {
                $tmp   = (int)$paging[$key];
                $value = $tmp < 0 || $tmp > static::MAX_PAGE_SIZE ? static::MAX_PAGE_SIZE : $tmp;
            }
        }

        return $value;
    }

    /**
     * @param array           $data
     * @param array           $rules
     * @param Schema          $schema
     * @param ErrorCollection $errors
     */
    protected function validateAttributes(array $data, array $rules, Schema $schema, ErrorCollection $errors)
    {
        $validator = $this->createValidator($data, $rules);
        if ($validator->fails() === true) {
            $map = $schema->getModelAttributesToResourceMap();
            $errors->addAttributeErrorsFromMessageBag($validator->messages(), $map);
        }
    }

    /**
     * @param array           $data
     * @param array           $rules
     * @param Schema          $schema
     * @param ErrorCollection $errors
     */
    protected function validateBelongsToRelationships(
        array $data,
        array $rules,
        Schema $schema,
        ErrorCollection $errors
    ) {
        $validator = $this->createValidator($data, $rules);
        if ($validator->fails() === true) {
            $map = $schema->getModelBelongsToRelationshipsToResourceMap();
            $errors->addRelationshipErrorsFromMessageBag($validator->messages(), $map);
        }
    }

    /**
     * @param array $data
     * @param array $rules
     *
     * @return Validator
     */
    protected function createValidator(array $data, array $rules)
    {
        /** @var Factory $factory */
        $factory   = app('validator');
        $validator = $factory->make($data, $rules);

        return $validator;
    }
}
