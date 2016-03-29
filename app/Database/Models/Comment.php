<?php namespace Neomerx\LimoncelloIlluminate\Database\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @package Neomerx\LimoncelloIlluminate
 *
 * @property Post post
 * @property User user
 */
class Comment extends Model
{
    use SoftDeletes;

    /** @inheritdoc */
    const TABLE_NAME = 'comments';

    /** @inheritdoc */
    const FIELD_ID = 'id_comment';

    /** @inheritdoc */
    protected $table = self::TABLE_NAME;

    /** @inheritdoc */
    protected $primaryKey = self::FIELD_ID;

    /** Field name */
    const FIELD_ID_POST = Post::FIELD_ID;

    /** Field name */
    const FIELD_ID_USER = User::FIELD_ID;

    /** Relationship name */
    const REL_POST = 'post';

    /** Relationship name */
    const REL_USER = 'user';

    /** Field name */
    const FIELD_TEXT = 'text';

    /**
     * @inheritdoc
     */
    protected $casts = [
        self::FIELD_ID_USER    => self::CAST_INT,
        self::FIELD_ID_POST    => self::CAST_INT,
        self::FIELD_CREATED_AT => self::CAST_DATE,
        self::FIELD_UPDATED_AT => self::CAST_DATE,
    ];

    /**
     * @return BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class, self::FIELD_ID_POST, Post::FIELD_ID);
    }

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, self::FIELD_ID_USER, User::FIELD_ID);
    }
}
