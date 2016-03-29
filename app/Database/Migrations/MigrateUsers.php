<?php namespace Neomerx\LimoncelloIlluminate\Database\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Neomerx\LimoncelloIlluminate\Database\Models\Role;
use Neomerx\LimoncelloIlluminate\Database\Models\User as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class MigrateUsers extends Migration
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
            $table->unsignedInteger(Model::FIELD_ID_ROLE);
            /** @noinspection PhpUndefinedMethodInspection */
            $table->string(Model::FIELD_TITLE, Model::LENGTH_TITLE)->nullable();
            $table->string(Model::FIELD_FIRST_NAME, Model::LENGTH_FIRST_NAME);
            /** @noinspection PhpUndefinedMethodInspection */
            $table->string(Model::FIELD_LAST_NAME, Model::LENGTH_LAST_NAME)->nullable();
            /** @noinspection PhpUndefinedMethodInspection */
            $table->string(Model::FIELD_EMAIL, Model::LENGTH_EMAIL)->unique();
            /** @noinspection PhpUndefinedMethodInspection */
            $table->string(Model::FIELD_LANGUAGE, Model::LENGTH_LANGUAGE)->nullable();
            $table->string(Model::FIELD_PASSWORD_HASH, Model::LENGTH_PASSWORD_HASH);
            /** @noinspection PhpUndefinedMethodInspection */
            $table->string(Model::FIELD_API_TOKEN, Model::LENGTH_API_TOKEN)->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();

            /** @noinspection PhpUndefinedMethodInspection */
            $table->foreign(Model::FIELD_ID_ROLE)->references(Role::FIELD_ID)->on(Role::TABLE_NAME);
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
