<?php namespace Neomerx\LimoncelloIlluminate\Listeners;

use Neomerx\LimoncelloIlluminate\Events\PostUpdatedEvent;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class PostUpdatedListener
{
    /**
     * Handle the event.
     *
     * @param PostUpdatedEvent $event
     *
     * @return void
     */
    public function handle(PostUpdatedEvent $event)
    {
        $event ?: null;
    }
}
