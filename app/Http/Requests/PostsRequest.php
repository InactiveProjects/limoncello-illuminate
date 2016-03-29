<?php namespace Neomerx\LimoncelloIlluminate\Http\Requests;

use Neomerx\LimoncelloIlluminate\Schemas\PostSchema as Schema;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class PostsRequest extends Request
{
    /** Related schema class */
    const SCHEMA = Schema::class;
}
