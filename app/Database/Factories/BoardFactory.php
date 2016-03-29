<?php namespace Neomerx\LimoncelloIlluminate\Database\Factories;

use Faker\Generator;
use Neomerx\LimoncelloIlluminate\Database\Models\Board as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class BoardFactory extends Factory
{
    /**
     * @inheritdoc
     */
    public function getDefinition()
    {
        return function (Generator $generator) {
            return [
                Model::FIELD_TITLE => ucfirst($generator->words(4, true)),
            ];
        };
    }
}
