<?php namespace Neomerx\LimoncelloIlluminate\Events;

use Neomerx\LimoncelloIlluminate\Database\Models\Board;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class BoardCreatedEvent extends Event
{
    /**
     * @var Board
     */
    public $board;

    /**
     * @param Board $board
     */
    public function __construct(Board $board)
    {
        $this->board = $board;
    }
}
