<?php namespace Neomerx\LimoncelloIlluminate\Api\Policies;

use Neomerx\Limoncello\Contracts\Auth\AccountInterface;
use Neomerx\LimoncelloIlluminate\Database\Models\Comment as Model;
use Neomerx\LimoncelloIlluminate\Database\Models\Model as BaseModel;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class CommentPolicy extends BasePolicy
{
    /**
     * @inheritDoc
     */
    public function create(AccountInterface $current, BaseModel $resource)
    {
        /** @var Model $resource */
        return $this->isAuthenticated($current);
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
        return $this->isOwner($current, $resource) === true || $this->isAdmin($current) === true;
    }

    /**
     * @inheritDoc
     */
    public function delete(AccountInterface $current, BaseModel $resource)
    {
        /** @var Model $resource */
        return $this->isAdmin($current);
    }

    /**
     * @param AccountInterface $account
     * @param Model            $resource
     *
     * @return bool
     */
    private function isOwner(AccountInterface $account, Model $resource)
    {
        /** @var Model $resource */
        if ($this->isAuthenticated($account) === true) {
            $userId  = $account->getAuthIdentifier();
            $ownerId = $resource->{Model::FIELD_ID_USER};
            $isOwner = $userId === $ownerId;

            return $isOwner;
        }

        return false;
    }
}
