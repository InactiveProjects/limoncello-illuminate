<?php namespace Neomerx\LimoncelloIlluminate\Database\Factories;

use Faker\Generator;
use Neomerx\LimoncelloIlluminate\Database\Models\Role;
use Neomerx\LimoncelloIlluminate\Database\Models\User as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class UserFactory extends Factory
{
    /** Password used for all users */
    const TEST_PASSWORD = 'password';

    /**
     * @inheritdoc
     */
    public function getDefinition()
    {
        return function (Generator $generator) {
            return [
                Model::FIELD_ID_ROLE    => Role::ENUM_ROLE_USER_ID,
                Model::FIELD_TITLE      => $generator->title,
                Model::FIELD_FIRST_NAME => $generator->firstName,
                Model::FIELD_LAST_NAME  => $generator->lastName,
                Model::FIELD_EMAIL      => $generator->email,
                Model::FIELD_PASSWORD   => self::TEST_PASSWORD,
                Model::FIELD_LANGUAGE   => $generator->languageCode,
            ];
        };
    }
}
