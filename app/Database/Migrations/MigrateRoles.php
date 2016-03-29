<?php namespace Neomerx\LimoncelloIlluminate\Database\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Neomerx\LimoncelloIlluminate\Database\Models\Role as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class MigrateRoles extends Migration
{
    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function apply()
    {
        Schema::create(Model::TABLE_NAME, function (Blueprint $table) {
            $table->unsignedInteger(Model::FIELD_ID);
            $table->string(Model::FIELD_NAME, Model::LENGTH_NAME);
            $table->timestamps();

            $table->primary(Model::FIELD_ID);
        });
    }

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function rollback()
    {
        Schema::dropIfExists(Model::TABLE_NAME);
    }
}
