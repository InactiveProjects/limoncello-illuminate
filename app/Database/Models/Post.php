<?php namespace Neomerx\LimoncelloIlluminate\Database\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @package Neomerx\LimoncelloIlluminate
 *
 * @property Board      board
 * @property User       user
 * @property Collection comments
 */
class Post extends Model
{
    use SoftDeletes;

    /** @inheritdoc */
    const TABLE_NAME = 'posts';

    /** @inheritdoc */
    const FIELD_ID = 'id_post';

    /** @inheritdoc */
    protected $table = self::TABLE_NAME;

    /** @inheritdoc */
    protected $primaryKey = self::FIELD_ID;

    /** Field name */
    const FIELD_ID_BOARD = Board::FIELD_ID;

    /** Field name */
    const FIELD_ID_USER = User::FIELD_ID;

    /** Relationship name */
    const REL_BOARD = 'board';

    /** Relationship name */
    const REL_USER = 'user';

    /** Relationship name */
    const REL_COMMENTS = 'comments';

    /** Field name */
    const FIELD_TITLE = 'title';

    /** Field name */
    const FIELD_TEXT = 'text';

    /** Field length */
    const LENGTH_TITLE = 255;

    /**
     * @inheritdoc
     */
    protected $casts = [
        self::FIELD_ID_USER  => self::CAST_INT,
        self::FIELD_ID_BOARD => self::CAST_INT,
    ];

    /**
     * @return BelongsTo
     */
    public function board()
    {
        return $this->belongsTo(Board::class, self::FIELD_ID_BOARD, Board::FIELD_ID);
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, self::FIELD_ID_USER, User::FIELD_ID);
    }

    /**
     * @return HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, Comment::FIELD_ID_POST, self::FIELD_ID);
    }
}
