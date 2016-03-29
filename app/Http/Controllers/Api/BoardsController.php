<?php namespace Neomerx\LimoncelloIlluminate\Http\Controllers\Api;

use Illuminate\Http\Response;
use Neomerx\LimoncelloIlluminate\Api\BoardsApi as Api;
use Neomerx\LimoncelloIlluminate\Http\Requests\BoardsRequest as JsonApiRequest;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class BoardsController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(app(JsonApiRequest::class), new Api());
    }

    /**
     * @return Api
     */
    protected function getApi()
    {
        return parent::getApi();
    }

    /**
     * @param string $boardId
     *
     * @return Response
     */
    public function indexPosts($boardId)
    {
        $parameters = $this->getRequest()->getParameters();

        $pagedData = $this->getApi()->indexPosts($boardId, $parameters);

        return $this->getResponses()->getPagedDataResponse($pagedData);
    }
}
