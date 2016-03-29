<?php namespace Neomerx\LimoncelloIlluminate\Listeners;

use Neomerx\LimoncelloIlluminate\Events\BoardUpdatedEvent;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class BoardUpdatedListener
{
    /**
     * Handle the event.
     *
     * @param BoardUpdatedEvent $event
     *
     * @return void
     */
    public function handle(BoardUpdatedEvent $event)
    {
        $event ?: null;
    }
}
