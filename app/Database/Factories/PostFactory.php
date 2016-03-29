<?php namespace Neomerx\LimoncelloIlluminate\Database\Factories;

use Faker\Generator;
use Illuminate\Database\Eloquent\Collection;
use Neomerx\LimoncelloIlluminate\Database\Models\Board;
use Neomerx\LimoncelloIlluminate\Database\Models\Post as Model;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class PostFactory extends Factory
{
    /**
     * @var Collection|null
     */
    private $boards;

    /**
     * @inheritdoc
     */
    public function getDefinition()
    {
        return function (Generator $generator) {
            return [
                Model::FIELD_ID_BOARD => $this->getRandomBoard()->getKey(),
                Model::FIELD_ID_USER  => $this->getRandomUser()->getKey(),
                Model::FIELD_TITLE    => $generator->text(20),
                Model::FIELD_TEXT     => $generator->text(),
            ];
        };
    }

    /**
     * @return Board
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function getRandomBoard()
    {
        if ($this->boards === null) {
            $this->boards = Board::all();
        }

        return $this->boards->random();
    }
}
