<?php namespace Neomerx\LimoncelloIlluminate\Database\Seeds;

use Illuminate\Contracts\Hashing\Hasher;
use Neomerx\LimoncelloIlluminate\Database\Models\Role;
use Neomerx\LimoncelloIlluminate\Database\Models\User as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class SeedUsers extends Seeder
{
    /** Test admin email */
    const TEST_ADMIN_EMAIL = 'admin@admins.tld';

    /** Test admin email */
    const TEST_USER_EMAIL = 'user@users.tld';

    /**
     * @var Hasher
     */
    private $hasher;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->hasher = app(Hasher::class);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        /** @var Model $admin */
        $admin                         = factory(Model::class)->make();
        $admin->{Model::FIELD_ID_ROLE} = Role::ENUM_ROLE_ADMIN_ID;
        $admin->{Model::FIELD_EMAIL}   = self::TEST_ADMIN_EMAIL;
        $admin->saveOrFail();

        /** @var Model $user */
        $user                         = factory(Model::class)->make();
        $user->{Model::FIELD_ID_ROLE} = Role::ENUM_ROLE_USER_ID;
        $user->{Model::FIELD_EMAIL}   = self::TEST_USER_EMAIL;
        $user->saveOrFail();

        factory(Model::class, 10)->make()->each(function (Model $user) {
            $user->{Model::FIELD_ID_ROLE} = Role::ENUM_ROLE_USER_ID;
            $user->saveOrFail();
        });
    }
}
