<?php namespace Neomerx\LimoncelloIlluminate\Database\Factories;

use Closure;
use Illuminate\Database\Eloquent\Collection;
use Neomerx\LimoncelloIlluminate\Database\Models\User;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
abstract class Factory
{
    /**
     * @return Closure
     */
    abstract public function getDefinition();

    /**
     * @var Collection|null
     */
    private $users;

    /**
     * @return User
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    protected function getRandomUser()
    {
        if ($this->users === null) {
            $this->users = User::all();
        }

        return $this->users->random();
    }
}
