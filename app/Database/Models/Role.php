<?php namespace Neomerx\LimoncelloIlluminate\Database\Models;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class Role extends Model
{
    /** @inheritdoc */
    const TABLE_NAME = 'roles';

    /** @inheritdoc */
    const FIELD_ID = 'id_role';

    /** @inheritdoc */
    public $incrementing = false;

    /** @inheritdoc */
    protected $table = self::TABLE_NAME;

    /** @inheritdoc */
    protected $primaryKey = self::FIELD_ID;

    /** Field name */
    const FIELD_NAME = 'name';

    /** Field length */
    const LENGTH_NAME = 255;

    /** Enumeration value */
    const ENUM_ROLE_ADMIN = 'admin';

    /** Enumeration value */
    const ENUM_ROLE_USER = 'user';

    /** Role id */
    const ENUM_ROLE_ADMIN_ID = 1;

    /** Role id */
    const ENUM_ROLE_USER_ID = 2;

    /** Role id */
    const ENUM_ROLE_ANONYMOUS_ID = -1;

    /**
     * @inheritdoc
     */
    protected $casts = [
        self::FIELD_CREATED_AT => self::CAST_DATE,
        self::FIELD_UPDATED_AT => self::CAST_DATE,
    ];

    /**
     * @return string[]
     */
    public static function getRoleValues()
    {
        return [
            self::ENUM_ROLE_ADMIN,
            self::ENUM_ROLE_USER,
        ];
    }

    /**
     * @param string $role
     *
     * @return int
     */
    public static function getRoleId($role)
    {
        $map = [
            self::ENUM_ROLE_ADMIN => self::ENUM_ROLE_ADMIN_ID,
            self::ENUM_ROLE_USER  => self::ENUM_ROLE_USER_ID,
        ];

        // do not check if key exist deliberately. If wrong role is given it will cause an error.
        return $map[$role];
    }
}
