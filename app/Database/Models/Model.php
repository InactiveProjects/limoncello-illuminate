<?php namespace Neomerx\LimoncelloIlluminate\Database\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as EloquentModel;

/**
 * @package Neomerx\LimoncelloIlluminate
 *
 * @method static Model first()
 * @method static Model find($index)
 * @method static Model findOrFail($index)
 */
abstract class Model extends EloquentModel
{
    /** DateTime format used for input and output */
    const DATE_TIME_FORMAT = 'Y-m-d\TH:i:s.uO';

    /** Model's table name */
    const TABLE_NAME = null;

    /** Model's primary key field name */
    const FIELD_ID = null;

    /** Field name */
    const FIELD_CREATED_AT = self::CREATED_AT;

    /** Field name */
    const FIELD_UPDATED_AT = self::UPDATED_AT;

    /** Field name */
    const FIELD_DELETED_AT = 'deleted_at';

    /** Attribute casting type */
    const CAST_INT = 'integer';

    /** Attribute casting type */
    const CAST_BOOL = 'boolean';

    /** Attribute casting type */
    const CAST_FLOAT = 'float';

    /** Attribute casting type */
    const CAST_DATE = 'date';

    /** @inheritdoc */
    public $incrementing = true;

    /** @inheritdoc */
    public $timestamps = true;

    /**
     * @inheritdoc
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function setAttribute($key, $value)
    {
        // we want to be able to set dates in ISO8601. Laravel has an issue with
        // build-in format so we convert input manually.
        // For more see https://github.com/laravel/framework/issues/12203

        if (is_string($value) && (in_array($key, $this->getDates()) || $this->isDateCastable($key))) {
            $value = Carbon::createFromFormat(self::DATE_TIME_FORMAT, $value);
        }

        return parent::setAttribute($key, $value);
    }
}
