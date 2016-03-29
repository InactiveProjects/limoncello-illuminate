<?php namespace Neomerx\LimoncelloIlluminate\Http\Controllers\Api;

use Neomerx\Limoncello\Contracts\Api\CrudInterface;
use Neomerx\Limoncello\Contracts\Http\ResponsesInterface;
use Neomerx\Limoncello\Http\CrudController;
use Neomerx\Limoncello\Http\JsonApiRequest;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
abstract class Controller extends CrudController
{
    /** URL prefix */
    const API_URL_PREFIX = 'api/v1';

    /**
     * @param JsonApiRequest $request
     * @param CrudInterface  $crudApi
     */
    public function __construct(JsonApiRequest $request, CrudInterface $crudApi)
    {
        /** @var ResponsesInterface $responses */
        $responses = app(ResponsesInterface::class);

        parent::__construct($request, $responses, $crudApi);
    }
}
