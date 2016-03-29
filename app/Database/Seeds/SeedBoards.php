<?php namespace Neomerx\LimoncelloIlluminate\Database\Seeds;

use Neomerx\LimoncelloIlluminate\Database\Models\Board as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class SeedBoards extends Seeder
{
    /**
     * @inheritdoc
     */
    public function run()
    {
        factory(Model::class, 10)->create();
    }
}
