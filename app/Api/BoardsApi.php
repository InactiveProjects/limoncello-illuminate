<?php namespace Neomerx\LimoncelloIlluminate\Api;

use Neomerx\JsonApi\Contracts\Http\Parameters\ParametersInterface;
use Neomerx\Limoncello\Contracts\JsonApi\PagedDataInterface;
use Neomerx\Limoncello\Http\JsonApiRequest;
use Neomerx\LimoncelloIlluminate\Api\Authorizations\BoardsAuthorizations;
use Neomerx\LimoncelloIlluminate\Database\Models\Board;
use Neomerx\LimoncelloIlluminate\Events\BoardCreatedEvent;
use Neomerx\LimoncelloIlluminate\Events\BoardUpdatedEvent;
use Neomerx\LimoncelloIlluminate\Logs\LoggerTrait;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class BoardsApi extends Crud
{
    use LoggerTrait;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(new Board(), new BoardsAuthorizations());
    }

    /**
     * @inheritdoc
     */
    public function index(ParametersInterface $parameters = null, array $relations = [])
    {
        $relations = [
            Board::REL_POSTS,
        ];

        return parent::index($parameters, $relations);
    }

    /**
     * @inheritdoc
     */
    public function create(JsonApiRequest $request)
    {
        /** @var Board $model */
        $model = parent::create($request);

        event(new BoardCreatedEvent($model));

        return $model;
    }

    /**
     * @inheritdoc
     */
    public function update(JsonApiRequest $request)
    {
        /** @var Board $model */
        $model = parent::update($request);

        event(new BoardUpdatedEvent($model));

        return $model;
    }

    /**
     * @param int|string          $boardId
     * @param ParametersInterface $parameters
     *
     * @return PagedDataInterface
     */
    public function indexPosts($boardId, ParametersInterface $parameters)
    {
        $this->getLogger()->debug('Index board posts started.', [Board::FIELD_ID => $boardId]);

        $result = $this->indexRelationship($boardId, Board::REL_POSTS, $parameters);

        $this->getLogger()->debug('Index board posts completed.');

        return $result;
    }
}
