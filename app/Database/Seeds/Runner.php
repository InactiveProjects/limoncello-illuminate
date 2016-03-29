<?php namespace Neomerx\LimoncelloIlluminate\Database\Seeds;

use Illuminate\Database\Seeder;
use Neomerx\LimoncelloIlluminate\Database\Models\Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class Runner
{
    /**
     * @var Seeder
     */
    private $parentSeeder;

    /**
     * @var string[]
     */
    private $seederClasses = [
        SeedRoles::class,
        SeedUsers::class,
        SeedBoards::class,
        SeedPosts::class,
        SeedComments::class,
    ];

    /**
     * Runner constructor.
     *
     * @param Seeder $seeder
     */
    public function __construct(Seeder $seeder)
    {
        $this->parentSeeder = $seeder;
    }

    /**
     * Run seeders.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function run()
    {
        Model::unguard();
        try {
            foreach ($this->seederClasses as $seederClass) {
                $this->parentSeeder->call($seederClass);
            }
        } finally {
            Model::reguard();
        }
    }
}
