<?php namespace Neomerx\LimoncelloIlluminate\Schemas;

use Neomerx\JsonApi\Contracts\Schema\ContainerInterface;
use Neomerx\Limoncello\Contracts\JsonApi\FactoryInterface;
use Neomerx\LimoncelloIlluminate\Database\Models\Comment as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class CommentSchema extends Schema
{
    /** Type */
    const TYPE = 'comments';

    /** Model class name */
    const MODEL = Model::class;

    /** Attribute name */
    const ATTR_TEXT = 'text';

    /** Relationship name */
    const REL_USER = 'user';

    /** Relationship name */
    const REL_POST = 'post';

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
        return [
            self::IDX_TYPE => self::TYPE,

            self::IDX_ATTRIBUTES => [
                self::ATTR_TEXT       => Model::FIELD_TEXT,
                self::ATTR_CREATED_AT => Model::FIELD_CREATED_AT,
                self::ATTR_UPDATED_AT => Model::FIELD_UPDATED_AT,
            ],

            self::IDX_BELONGS_TO => [
                self::REL_USER => [UserSchema::TYPE, Model::REL_USER],
                self::REL_POST => [PostSchema::TYPE, Model::REL_POST],
            ],

        ];
    }
}
