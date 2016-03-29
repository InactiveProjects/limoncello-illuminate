<?php namespace Neomerx\LimoncelloIlluminate\Database\Seeds;

use Neomerx\LimoncelloIlluminate\Database\Models\Comment as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class SeedComments extends Seeder
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        factory(Model::class, 400)->create();
    }
}
