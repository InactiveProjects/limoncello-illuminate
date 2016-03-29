<?php namespace Neomerx\LimoncelloIlluminate\Listeners;

use Neomerx\LimoncelloIlluminate\Events\CommentCreatedEvent;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class CommentCreatedListener
{
    /**
     * Handle the event.
     *
     * @param CommentCreatedEvent $event
     *
     * @return void
     */
    public function handle(CommentCreatedEvent $event)
    {
        $event ?: null;
    }
}
