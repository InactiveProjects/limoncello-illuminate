<?php namespace Neomerx\LimoncelloIlluminate\Database\Seeds;

use Neomerx\LimoncelloIlluminate\Database\Models\Role as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class SeedRoles extends Seeder
{
    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function run()
    {
        /**
         * @param string $roleName
         */
        $createRole = function ($roleName) {
            (new Model([
                Model::FIELD_ID   => Model::getRoleId($roleName),
                Model::FIELD_NAME => $roleName,
            ]))->saveOrFail();
        };

        $createRole(Model::ENUM_ROLE_ADMIN);
        $createRole(Model::ENUM_ROLE_USER);
    }
}
