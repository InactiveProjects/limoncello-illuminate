<?php namespace Neomerx\LimoncelloIlluminate\Schemas;

use Neomerx\JsonApi\Contracts\Schema\ContainerInterface;
use Neomerx\Limoncello\Contracts\JsonApi\FactoryInterface;
use Neomerx\Limoncello\JsonApi\Schema as BaseSchema;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
abstract class Schema extends BaseSchema
{
    /** Attribute name */
    const ATTR_CREATED_AT = 'created-at';

    /** Attribute name */
    const ATTR_UPDATED_AT = 'updated-at';

    /** Attribute name */
    const ATTR_DELETED_AT = 'deleted-at';

    /**
     * @param FactoryInterface   $factory
     * @param ContainerInterface $container
     */
    public function __construct(FactoryInterface $factory, ContainerInterface $container)
    {
        parent::__construct($factory, $container);

        $this->addReadOnly([
            self::ATTR_CREATED_AT,
            self::ATTR_UPDATED_AT,
            self::ATTR_DELETED_AT,
        ]);
    }
}
