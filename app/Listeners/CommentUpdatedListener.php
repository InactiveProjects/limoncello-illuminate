<?php namespace Neomerx\LimoncelloIlluminate\Listeners;

use Neomerx\LimoncelloIlluminate\Events\CommentUpdatedEvent;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class CommentUpdatedListener
{
    /**
     * Handle the event.
     *
     * @param CommentUpdatedEvent $event
     *
     * @return void
     */
    public function handle(CommentUpdatedEvent $event)
    {
        $event ?: null;
    }
}
