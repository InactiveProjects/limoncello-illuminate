<?php namespace Neomerx\LimoncelloIlluminate\Events;

use Neomerx\LimoncelloIlluminate\Database\Models\Comment;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class CommentCreatedEvent extends Event
{
    /**
     * @var Comment
     */
    public $comment;

    /**
     * @param Comment $comment
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }
}
