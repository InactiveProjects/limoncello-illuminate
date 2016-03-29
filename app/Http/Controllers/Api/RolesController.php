<?php namespace Neomerx\LimoncelloIlluminate\Http\Controllers\Api;

use Neomerx\LimoncelloIlluminate\Api\RolesApi as Api;
use Neomerx\LimoncelloIlluminate\Http\Requests\RolesRequest as JsonApiRequest;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class RolesController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(app(JsonApiRequest::class), new Api());
    }
}
