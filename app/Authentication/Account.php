<?php namespace Neomerx\LimoncelloIlluminate\Authentication;

use Neomerx\LimoncelloIlluminate\Database\Models\User;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class Account extends \Neomerx\Limoncello\Auth\Account
{
    /**
     * @param User  $user
     * @param array $attributes
     */
    public function __construct(User $user, array $attributes)
    {
        parent::__construct(User::class, $attributes);

        $this->user = $user;
    }

    // App specific properties could be added here
}
