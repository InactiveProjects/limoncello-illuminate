<?php namespace Neomerx\LimoncelloIlluminate\Database\Migrations;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
abstract class Migration
{
    /**
     * Migration model name.
     */
    const MODEL_NAME = null;

    /**
     * Apply migration changes.
     *
     * @return void
     */
    abstract public function apply();

    /**
     * Rollback migration changes.
     *
     * @return void
     */
    abstract public function rollback();
}
