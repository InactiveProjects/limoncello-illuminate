<?php namespace Neomerx\LimoncelloIlluminate\Api\Policies;

use Neomerx\Limoncello\Contracts\Auth\AccountInterface;
use Neomerx\LimoncelloIlluminate\Database\Models\Model as BaseModel;
use Neomerx\LimoncelloIlluminate\Database\Models\User as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class UserPolicy extends BasePolicy
{
    /**
     * @inheritDoc
     */
    public function create(AccountInterface $current, BaseModel $resource)
    {
        /** @var Model $resource */
        return $this->isAdmin($current);
    }

    /**
     * @inheritDoc
     */
    public function read(AccountInterface $current, BaseModel $resource)
    {
        /** @var Model $resource */
        return true;
    }

    /**
     * @inheritDoc
     */
    public function update(AccountInterface $current, BaseModel $resource)
    {
        /** @var Model $resource */
        return $this->isAdmin($current);
    }

    /**
     * @inheritDoc
     */
    public function delete(AccountInterface $current, BaseModel $resource)
    {
        /** @var Model $resource */
        $idx = $resource->getKey();

        // if admin, not build-in admin and can't delete himself
        return $this->isAdmin($current) && ($idx !== 1) && ($idx !== $current->getAuthIdentifier());
    }
}
