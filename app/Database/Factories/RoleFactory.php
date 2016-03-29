<?php namespace Neomerx\LimoncelloIlluminate\Database\Factories;

use Faker\Generator;
use Neomerx\LimoncelloIlluminate\Database\Models\Role as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class RoleFactory extends Factory
{
    /**
     * @inheritdoc
     */
    public function getDefinition()
    {
        return function (Generator $generator) {
            return [
                Model::FIELD_NAME => $generator->word,
            ];
        };
    }
}
