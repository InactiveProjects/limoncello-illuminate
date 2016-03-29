<?php namespace Neomerx\LimoncelloIlluminate\Http\Controllers\Api;

use Neomerx\LimoncelloIlluminate\Api\CommentsApi as Api;
use Neomerx\LimoncelloIlluminate\Http\Requests\CommentsRequest as JsonApiRequest;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class CommentsController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(app(JsonApiRequest::class), new Api());
    }
}
