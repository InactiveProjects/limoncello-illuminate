<?php namespace Neomerx\LimoncelloIlluminate\Database\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;

/**
 * @package Neomerx\LimoncelloIlluminate
 *
 * @property Role       role
 * @property Collection comments
 * @property Collection posts
 *
 * @method static Builder inRole($roleId)
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract, AuthorizableContract
{
    use Authenticatable, CanResetPassword, SoftDeletes, Authorizable;

    /** @inheritdoc */
    const TABLE_NAME = 'users';

    /** @inheritdoc */
    const FIELD_ID = 'id_user';

    /** @inheritdoc */
    protected $table = self::TABLE_NAME;

    /** @inheritdoc */
    protected $primaryKey = self::FIELD_ID;

    /** Field name */
    const FIELD_ID_ROLE = Role::FIELD_ID;

    /** Relationship name */
    const REL_ROLE = 'role';

    /** Relationship name */
    const REL_POSTS = 'posts';

    /** Relationship name */
    const REL_COMMENTS = 'comments';

    /** Field name */
    const FIELD_TITLE = 'title';

    /** Field name */
    const FIELD_FIRST_NAME = 'first_name';

    /** Field name */
    const FIELD_LAST_NAME = 'last_name';

    /** Field name */
    const FIELD_INITIALS = 'initials';

    /** Field name */
    const FIELD_NAME = 'name';

    /**  */
    const FIELD_EMAIL = 'email';

    /** Field name */
    const FIELD_PASSWORD = 'password';

    /** Field name */
    const FIELD_PASSWORD_HASH = 'password_hash';

    /** Field name */
    const FIELD_LANGUAGE = 'language';

    /** Field name */
    const FIELD_API_TOKEN = 'api_token';

    /** Field name */
    const FIELD_REMEMBER_TOKEN = 'remember_token';

    /** Field length */
    const LENGTH_PASSWORD_HASH = 60;

    /** Field length */
    const LENGTH_TITLE = 255;

    /** Field length */
    const LENGTH_FIRST_NAME = 255;

    /** Field length */
    const LENGTH_LAST_NAME = 255;

    /** Field length */
    const LENGTH_EMAIL = 255;

    /** Field length */
    const LENGTH_LANGUAGE = 255;

    /** Field length */
    const LENGTH_TWITTER = 255;

    /** Field length */
    const LENGTH_API_TOKEN = 64;

    /** Field limit */
    const MIN_FIELD_PASSWORD = 6;

    /**
     * @inheritdoc
     */
    protected $appends = [
        self::FIELD_NAME,
        self::FIELD_INITIALS,
    ];

    /**
     * @inheritdoc
     */
    protected $fillable = [
        self::FIELD_TITLE,
        self::FIELD_FIRST_NAME,
        self::FIELD_LAST_NAME,
        self::FIELD_EMAIL,
        self::FIELD_PASSWORD,
        self::FIELD_PASSWORD_HASH,
        self::FIELD_LANGUAGE,
    ];

    /**
     * @inheritdoc
     */
    protected $hidden = [
        self::FIELD_PASSWORD_HASH,
        self::FIELD_REMEMBER_TOKEN,
    ];

    /**
     * @inheritdoc
     */
    protected $casts = [
        self::FIELD_ID_ROLE    => self::CAST_INT,
        self::FIELD_CREATED_AT => self::CAST_DATE,
        self::FIELD_UPDATED_AT => self::CAST_DATE,
    ];

    /**
     * @return BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class, self::FIELD_ID_ROLE, Role::FIELD_ID);
    }

    /**
     * Authored posts.
     *
     * @return hasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, Post::FIELD_ID_USER, self::FIELD_ID);
    }

    /**
     * Authored comments.
     *
     * @return hasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, Comment::FIELD_ID_USER, self::FIELD_ID);
    }

    /**
     * @param int $roleId
     *
     * @return bool
     */
    public function hasRole($roleId)
    {
        $hasRole = $this->getAttributeValue(self::FIELD_ID_ROLE) === $roleId;

        return $hasRole;
    }

    /**
     * @param string $role
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function hasRoleName($role)
    {
        return $this->hasRole(Role::getRoleId($role));
    }

    /**
     * @return string
     */
    public function getNameAttribute()
    {
        $name = ucwords($this->{self::FIELD_FIRST_NAME} . ' ' . $this->{self::FIELD_LAST_NAME});

        return $name;
    }

    /**
     * @return string
     */
    public function getInitialsAttribute()
    {
        $getFirstSymbolUC = function ($fieldName) {
            $result = null;
            if (array_key_exists($fieldName, $this->attributes) === true) {
                $value = $this->attributes[$fieldName];
                if (strlen($value) > 0) {
                    $result = strtoupper($value[0]);
                }
            }

            return $result;
        };

        $initials = $getFirstSymbolUC(self::FIELD_FIRST_NAME) . $getFirstSymbolUC(self::FIELD_LAST_NAME);

        return $initials;
    }

    /**
     * Set password (hashes automatically).
     *
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
        /** @var Hasher $hasher */
        $hasher = app(Hasher::class);
        $hash   = $hasher->make($value);

        $this->attributes[self::FIELD_PASSWORD_HASH] = $hash;
    }

    /**
     * @param Builder $query
     * @param int     $roleId
     *
     * @return Builder
     */
    public function scopeInRole(Builder $query, $roleId)
    {
        return $query->where(self::FIELD_ID_ROLE, '=', $roleId);
    }
}
