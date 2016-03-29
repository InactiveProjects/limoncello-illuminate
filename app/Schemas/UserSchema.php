<?php namespace Neomerx\LimoncelloIlluminate\Schemas;

use Neomerx\JsonApi\Contracts\Schema\ContainerInterface;
use Neomerx\Limoncello\Contracts\JsonApi\FactoryInterface;
use Neomerx\LimoncelloIlluminate\Database\Models\User as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class UserSchema extends Schema
{
    /** Type */
    const TYPE = 'users';

    /** Model class name */
    const MODEL = Model::class;

    /** Attribute name */
    const ATTR_TITLE = 'title';

    /** Attribute name */
    const ATTR_FIRST_NAME = 'first-name';

    /** Attribute name */
    const ATTR_LAST_NAME = 'last-name';

    /** Attribute name */
    const ATTR_INITIALS = 'initials';

    /** Attribute name */
    const ATTR_EMAIL = 'email';

    /** Attribute name */
    const ATTR_PASSWORD = 'password';

    /** Attribute name */
    const ATTR_LANGUAGE = 'language';

    /** Relationship name */
    const REL_ROLE = 'role';

    /** Relationship name */
    const REL_POSTS = 'posts';

    /** Relationship name */
    const REL_COMMENTS = 'comments';

    /**
     * @param FactoryInterface $factory
     * @param ContainerInterface     $container
     */
    public function __construct(FactoryInterface $factory, ContainerInterface $container)
    {
        parent::__construct($factory, $container);

        $this->addReadOnly([self::ATTR_INITIALS]);
        $this->addWriteOnly([self::ATTR_PASSWORD]);
    }

    /**
     * @inheritdoc
     */
    protected function getSchemaMappings()
    {
        $attributes = [
            self::ATTR_TITLE      => Model::FIELD_TITLE,
            self::ATTR_FIRST_NAME => Model::FIELD_FIRST_NAME,
            self::ATTR_LAST_NAME  => Model::FIELD_LAST_NAME,
            self::ATTR_INITIALS   => Model::FIELD_INITIALS,
            self::ATTR_EMAIL      => Model::FIELD_EMAIL,
            self::ATTR_PASSWORD   => Model::FIELD_PASSWORD,
            self::ATTR_LANGUAGE   => Model::FIELD_LANGUAGE,
            self::ATTR_CREATED_AT => Model::FIELD_CREATED_AT,
            self::ATTR_UPDATED_AT => Model::FIELD_UPDATED_AT,
        ];
        $belongsTo  = [
            self::REL_ROLE => [RoleSchema::TYPE, Model::REL_ROLE],
        ];
        $hasMany    = [
            self::REL_POSTS    => [Model::REL_POSTS, self::TYPE_PAGINATED, 10],
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
