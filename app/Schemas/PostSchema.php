<?php namespace Neomerx\LimoncelloIlluminate\Schemas;

use Neomerx\JsonApi\Contracts\Schema\ContainerInterface;
use Neomerx\Limoncello\Contracts\JsonApi\FactoryInterface;
use Neomerx\LimoncelloIlluminate\Database\Models\Post as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class PostSchema extends Schema
{
    /** Type */
    const TYPE = 'posts';

    /** Model class name */
    const MODEL = Model::class;

    /** Attribute name */
    const ATTR_TITLE = 'title';

    /** Attribute name */
    const ATTR_TEXT = 'text';

    /** Relationship name */
    const REL_BOARD = 'board';

    /** Relationship name */
    const REL_USER = 'user';

    /** Relationship name */
    const REL_COMMENTS = 'comments';

    /**
     * @param FactoryInterface   $factory
     * @param ContainerInterface $container
     */
    public function __construct(FactoryInterface $factory, ContainerInterface $container)
    {
        parent::__construct($factory, $container);

        $this->addReadOnly([self::REL_USER]);
    }

    /**
     * @inheritdoc
     */
    protected function getSchemaMappings()
    {
        $attributes = [
            self::ATTR_TITLE      => Model::FIELD_TITLE,
            self::ATTR_TEXT       => Model::FIELD_TEXT,
            self::ATTR_CREATED_AT => Model::FIELD_CREATED_AT,
            self::ATTR_UPDATED_AT => Model::FIELD_UPDATED_AT,
        ];
        $belongsTo  = [
            self::REL_BOARD => [BoardSchema::TYPE, Model::REL_BOARD],
            self::REL_USER  => [BoardSchema::TYPE, Model::REL_USER],
        ];
        $hasMany    = [
            self::REL_COMMENTS => [Model::REL_COMMENTS, self::TYPE_PAGINATED, 10],
        ];

        return [
            self::IDX_TYPE       => self::TYPE,
            self::IDX_ATTRIBUTES => $attributes,
            self::IDX_BELONGS_TO => $belongsTo,
            self::IDX_HAS_MANY   => $hasMany,
        ];
    }
}
