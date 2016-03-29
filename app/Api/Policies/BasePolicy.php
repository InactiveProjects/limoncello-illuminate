<?php namespace Neomerx\LimoncelloIlluminate\Api\Policies;

use Illuminate\Contracts\Auth\Access\Gate as GateInterface;
use Neomerx\Limoncello\Auth\Anonymous;
use Neomerx\Limoncello\Contracts\Auth\AccountInterface;
use Neomerx\LimoncelloIlluminate\Database\Models\Model;
use Neomerx\LimoncelloIlluminate\Database\Models\Role;
use Neomerx\LimoncelloIlluminate\Database\Models\User;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
abstract class BasePolicy
{
    /** Policy name */
    const CAN_CREATE = 'create';

    /** Policy name */
    const CAN_READ = 'read';

    /** Policy name */
    const CAN_UPDATE = 'update';

    /** Policy name */
    const CAN_DELETE = 'delete';

    /** Policy name */
    const CAN_SET_RELATIONSHIP_ON_CREATE = 'setRelationshipOnCreate';

    /** Policy name */
    const CAN_SET_RELATIONSHIP_ON_UPDATE = 'setRelationshipOnUpdate';

    /**
     * @var GateInterface
     */
    private $guard;

    /**
     * @param AccountInterface $current
     * @param Model            $resource
     *
     * @return bool
     */
    abstract public function create(AccountInterface $current, Model $resource);

    /**
     * @param AccountInterface $current
     * @param Model            $resource
     *
     * @return bool
     */
    abstract public function read(AccountInterface $current, Model $resource);

    /**
     * @param AccountInterface $current
     * @param Model            $resource
     *
     * @return bool
     */
    abstract public function update(AccountInterface $current, Model $resource);

    /**
     * @param AccountInterface $current
     * @param Model            $resource
     *
     * @return bool
     */
    abstract public function delete(AccountInterface $current, Model $resource);

    /**
     * @param AccountInterface $current
     * @param Model            $model
     * @param string           $relationshipName
     * @param string           $idx
     * @param string           $relModelClass
     *
     * @return bool
     */
    public function setRelationshipOnCreate(
        AccountInterface $current,
        Model $model,
        $relationshipName,
        $idx,
        $relModelClass
    ) {
        $current && $model && $relationshipName ?: null;

        $isAllowed = true;
        if ($idx !== null) {
            // find model by id and check if it exists
            /** @noinspection PhpUndefinedMethodInspection */
            $relModel  = $relModelClass::find($idx);
            $isAllowed = $relModel !== null && $this->getGuard()->allows(self::CAN_READ, [$relModel]);
        }

        return $isAllowed;
    }

    /**
     * @param AccountInterface $current
     * @param Model            $model
     * @param string           $relationshipName
     * @param string           $idx
     * @param string           $relModelClass
     *
     * @return bool
     */
    public function setRelationshipOnUpdate(
        AccountInterface $current,
        Model $model,
        $relationshipName,
        $idx,
        $relModelClass
    ) {
        $current && $model && $relationshipName ?: null;

        $isAllowed = true;
        if ($idx !== null) {
            // find model by id and check if it exists
            /** @noinspection PhpUndefinedMethodInspection */
            $relModel  = $relModelClass::find($idx);
            $isAllowed = $relModel !== null && $this->getGuard()->allows(self::CAN_READ, [$relModel]);
        }

        return $isAllowed;
    }

    /**
     * @return GateInterface
     */
    protected function getGuard()
    {
        if ($this->guard === null) {
            $this->guard = app(GateInterface::class);
        }

        return $this->guard;
    }

    /**
     * @param AccountInterface $account
     *
     * @return bool
     */
    protected function isAuthenticated(AccountInterface $account)
    {
        $isAuthenticated = ($account instanceof Anonymous) === false;

        return $isAuthenticated;
    }

    /**
     * @param AccountInterface $account
     *
     * @return bool
     */
    protected function isAdmin(AccountInterface $account)
    {
        /** @var Model $resource */
        if ($this->isAuthenticated($account) === true) {
            /** @var User $user */
            $user    = $account->user();
            $isAdmin = $user->hasRole(Role::ENUM_ROLE_ADMIN_ID);

            return $isAdmin;
        }

        return false;
    }
}
