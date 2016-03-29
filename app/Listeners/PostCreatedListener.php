<?php namespace Neomerx\LimoncelloIlluminate\Listeners;

use Neomerx\LimoncelloIlluminate\Events\PostCreatedEvent;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class PostCreatedListener
{
    /**
     * Handle the event.
     *
     * @param PostCreatedEvent $event
     *
     * @return void
     */
    public function handle(PostCreatedEvent $event)
    {
        $event ?: null;
    }
}
