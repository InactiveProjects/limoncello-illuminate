<?php namespace Neomerx\LimoncelloIlluminate\Database\Factories;

use Faker\Generator;
use Illuminate\Database\Eloquent\Collection;
use Neomerx\LimoncelloIlluminate\Database\Models\Comment as Model;
use Neomerx\LimoncelloIlluminate\Database\Models\Post;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class CommentFactory extends Factory
{
    /**
     * @var Collection|null
     */
    private $posts;

    /**
     * @inheritdoc
     */
    public function getDefinition()
    {
        return function (Generator $generator) {
            return [
                Model::FIELD_ID_POST => $this->getRandomPost()->getKey(),
                Model::FIELD_ID_USER => $this->getRandomUser()->getKey(),
                Model::FIELD_TEXT    => $generator->text(),
            ];
        };
    }

    /**
     * @return Post
     *
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function getRandomPost()
    {
        if ($this->posts === null) {
            $this->posts = Post::all();
        }

        return $this->posts->random();
    }
}
