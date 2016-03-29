<?php namespace Neomerx\LimoncelloIlluminate\Database\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Neomerx\LimoncelloIlluminate\Database\Models\Board as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class MigrateBoards extends Migration
{
    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function apply()
    {
        Schema::create(Model::TABLE_NAME, function (Blueprint $table) {
            $table->increments(Model::FIELD_ID);
            $table->string(Model::FIELD_TITLE, Model::LENGTH_TITLE);
            $table->softDeletes();
            $table->timestamps();
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
