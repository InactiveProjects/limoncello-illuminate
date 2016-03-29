<?php namespace Neomerx\LimoncelloIlluminate\Http\Controllers\Api;

use Illuminate\Http\Response;
use Neomerx\LimoncelloIlluminate\Api\UsersApi as Api;
use Neomerx\LimoncelloIlluminate\Http\Requests\UsersRequest as JsonApiRequest;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class UsersController extends Controller
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
     * @param string $userId
     *
     * @return Response
     */
    public function indexPosts($userId)
    {
        $parameters = $this->getRequest()->getParameters();

        $pagedData = $this->getApi()->indexPosts($userId, $parameters);

        return $this->getResponses()->getPagedDataResponse($pagedData);
    }

    /**
     * @param string $userId
     *
     * @return Response
     */
    public function indexComments($userId)
    {
        $parameters = $this->getRequest()->getParameters();

        $pagedData = $this->getApi()->indexComments($userId, $parameters);

        return $this->getResponses()->getPagedDataResponse($pagedData);
    }
}
