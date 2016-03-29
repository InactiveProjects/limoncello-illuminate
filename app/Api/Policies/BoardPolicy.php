<?php namespace Neomerx\LimoncelloIlluminate\Api\Policies;

use Neomerx\Limoncello\Contracts\Auth\AccountInterface;
use Neomerx\LimoncelloIlluminate\Database\Models\Board as Model;
use Neomerx\LimoncelloIlluminate\Database\Models\Model as BaseModel;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class BoardPolicy extends BasePolicy
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
        return $this->isAdmin($current);
    }
}
