<?php namespace Neomerx\LimoncelloIlluminate\Database\Factories;

use Illuminate\Database\Eloquent\Factory as Registry;
use Neomerx\LimoncelloIlluminate\Database\Factories\Factory as ModelFactory;
use Neomerx\LimoncelloIlluminate\Database\Models\Board;
use Neomerx\LimoncelloIlluminate\Database\Models\Comment;
use Neomerx\LimoncelloIlluminate\Database\Models\Post;
use Neomerx\LimoncelloIlluminate\Database\Models\Role;
use Neomerx\LimoncelloIlluminate\Database\Models\User;

/**
 * @package Neomerx\LimoncelloIlluminate
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Runner
{
    /**
     * @var Factory
     */
    private $registry;

    /**
     * @var string[]
     */
    private $factoryClasses = [
        Role::class    => RoleFactory::class,
        User::class    => UserFactory::class,
        Board::class   => BoardFactory::class,
        Post::class    => PostFactory::class,
        Comment::class => CommentFactory::class,
    ];

    /**
     * Runner constructor.
     *
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @return void
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function run()
    {
        foreach ($this->factoryClasses as $modelClass => $factoryClass) {
            /** @var ModelFactory $instance */
            $instance = new $factoryClass;
            $this->registry->define($modelClass, $instance->getDefinition());
        }
    }
}
