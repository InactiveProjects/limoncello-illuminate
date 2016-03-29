<?php namespace Neomerx\LimoncelloIlluminate\Database\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @package Neomerx\LimoncelloIlluminate
 *
 * @property Collection posts
 */
class Board extends Model
{
    use SoftDeletes;

    /** @inheritdoc */
    const TABLE_NAME = 'boards';

    /** @inheritdoc */
    const FIELD_ID = 'id_board';

    /** @inheritdoc */
    protected $table = self::TABLE_NAME;

    /** @inheritdoc */
    protected $primaryKey = self::FIELD_ID;

    /** Relationship name */
    const REL_POSTS = 'posts';

    /** Field name */
    const FIELD_TITLE = 'title';

    /** Field length */
    const LENGTH_TITLE = 255;

    /**
     * @return HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, Post::FIELD_ID_BOARD, self::FIELD_ID);
    }
}
