<?php namespace Neomerx\LimoncelloIlluminate\Database\Migrations;

use Illuminate\Database\Schema\Blueprint;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class MigratePasswordResets extends Migration
{
    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function apply()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            /** @noinspection PhpUndefinedMethodInspection */
            $table->string('email')->index();
            /** @noinspection PhpUndefinedMethodInspection */
            $table->string('token')->index();
            $table->timestamp('created_at');
        });
    }

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function rollback()
    {
        Schema::dropIfExists('password_resets');
    }
}
