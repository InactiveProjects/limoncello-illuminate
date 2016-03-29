<?php namespace Neomerx\LimoncelloIlluminate\Events;

use Neomerx\LimoncelloIlluminate\Database\Models\Post;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class PostCreatedEvent extends Event
{
    /**
     * @var Post
     */
    public $post;

    /**
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}
