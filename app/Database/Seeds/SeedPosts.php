<?php namespace Neomerx\LimoncelloIlluminate\Database\Seeds;

use Neomerx\LimoncelloIlluminate\Database\Models\Post as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class SeedPosts extends Seeder
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        factory(Model::class, 100)->create();
    }
}
