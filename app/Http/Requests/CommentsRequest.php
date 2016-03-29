<?php namespace Neomerx\LimoncelloIlluminate\Http\Requests;

use Neomerx\LimoncelloIlluminate\Schemas\CommentSchema as Schema;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class CommentsRequest extends Request
{
    /** Related schema class */
    const SCHEMA = Schema::class;
}
