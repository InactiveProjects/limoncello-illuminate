<?php namespace Neomerx\LimoncelloIlluminate\Http\Controllers\Api;

use Illuminate\Http\Response;
use Neomerx\LimoncelloIlluminate\Api\PostsApi as Api;
use Neomerx\LimoncelloIlluminate\Http\Requests\PostsRequest as JsonApiRequest;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class PostsController extends Controller
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
     * @param string $postId
     *
     * @return Response
     */
    public function indexComments($postId)
    {
        $parameters = $this->getRequest()->getParameters();

        $pagedData = $this->getApi()->indexComments($postId, $parameters);

        return $this->getResponses()->getPagedDataResponse($pagedData);
    }
}
