<?php namespace Neomerx\LimoncelloIlluminate\Database\Migrations;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class Runner
{
    /**
     * @var string[]
     */
    private static $migrationClasses = [
        MigrateRoles::class,
        MigrateUsers::class,
        MigratePasswordResets::class,
        MigrateBoards::class,
        MigratePosts::class,
        MigrateComments::class,
    ];

    /**
     * Apply application migrations.
     *
     * @return void
     */
    public static function apply()
    {
        foreach (self::$migrationClasses as $migrationClass) {
            /** @var Migration $instance */
            $instance = new $migrationClass;
            $instance->apply();
        }
    }

    /**
     * Rollback application migrations.
     *
     * @return void
     */
    public static function rollback()
    {
        foreach (array_reverse(self::$migrationClasses) as $migrationClass) {
            /** @var Migration $instance */
            $instance = new $migrationClass;
            $instance->rollback();
        }
    }
}
