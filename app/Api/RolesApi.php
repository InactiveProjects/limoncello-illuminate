<?php namespace Neomerx\LimoncelloIlluminate\Api;

use Neomerx\Limoncello\Errors\ErrorCollection;
use Neomerx\Limoncello\Http\JsonApiRequest;
use Neomerx\LimoncelloIlluminate\Api\Authorizations\RolesAuthorizations;
use Neomerx\LimoncelloIlluminate\Database\Models\Role;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class RolesApi extends Crud
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(new Role(), new RolesAuthorizations());
    }

    /** @noinspection PhpMissingParentCallCommonInspection
     *
     * @inheritDoc
     */
    protected function createInstance(JsonApiRequest $request, ErrorCollection $errors)
    {
        // for Roles allowed to define ID on creation that's why we don't use default (parent) method

        $instance = $this->getModel()->newInstance();

        $instance->setAttribute($instance->getKeyName(), $request->getId());

        return $instance;
    }
}
