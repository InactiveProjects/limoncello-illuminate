<?php namespace Neomerx\LimoncelloIlluminate\Database\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Neomerx\LimoncelloIlluminate\Database\Models\Comment as Model;
use Neomerx\LimoncelloIlluminate\Database\Models\Post;
use Neomerx\LimoncelloIlluminate\Database\Models\User;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class MigrateComments extends Migration
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
            $table->unsignedInteger(Model::FIELD_ID_USER);
            $table->unsignedInteger(Model::FIELD_ID_POST);
            $table->text(Model::FIELD_TEXT);
            $table->softDeletes();
            $table->timestamps();

            /** @noinspection PhpUndefinedMethodInspection */
            $table->foreign(Model::FIELD_ID_POST)
                ->references(Post::FIELD_ID)->on(Post::TABLE_NAME)->onDelete('cascade');

            /** @noinspection PhpUndefinedMethodInspection */
            $table->foreign(Model::FIELD_ID_USER)
                ->references(User::FIELD_ID)->on(User::TABLE_NAME)->onDelete('cascade');
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
