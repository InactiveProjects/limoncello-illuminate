<?php namespace Neomerx\LimoncelloIlluminate\Schemas;

use Neomerx\LimoncelloIlluminate\Database\Models\Board as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class BoardSchema extends Schema
{
    /** Type */
    const TYPE = 'boards';

    /** Model class name */
    const MODEL = Model::class;

    /** Attribute name */
    const ATTR_TITLE = 'title';

    /** Relationship name */
    const REL_POSTS = 'posts';

    /**
     * @inheritdoc
     */
    protected function getSchemaMappings()
    {
        $attributes = [
            self::ATTR_TITLE      => Model::FIELD_TITLE,
            self::ATTR_CREATED_AT => Model::FIELD_CREATED_AT,
            self::ATTR_UPDATED_AT => Model::FIELD_UPDATED_AT,
        ];
        $hasMany    = [
            self::REL_POSTS => [Model::REL_POSTS, self::TYPE_PAGINATED, 10],
        ];

        return [
            self::IDX_TYPE       => self::TYPE,
            self::IDX_ATTRIBUTES => $attributes,
            self::IDX_HAS_MANY   => $hasMany,
        ];
    }
}
