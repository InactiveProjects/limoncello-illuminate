<?php namespace Neomerx\LimoncelloIlluminate\Listeners;

use Neomerx\LimoncelloIlluminate\Events\BoardCreatedEvent;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class BoardCreatedListener
{
    /**
     * Handle the event.
     *
     * @param BoardCreatedEvent $event
     *
     * @return void
     */
    public function handle(BoardCreatedEvent $event)
    {
        $event ?: null;
    }
}
