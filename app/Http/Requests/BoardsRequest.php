<?php namespace Neomerx\LimoncelloIlluminate\Http\Requests;

use Neomerx\Limoncello\Errors\ErrorCollection;
use Neomerx\LimoncelloIlluminate\Database\Models\Board as Model;
use Neomerx\LimoncelloIlluminate\Schemas\BoardSchema as Schema;
use Neomerx\LimoncelloIlluminate\Schemas\PostSchema;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class BoardsRequest extends Request
{
    /** Related schema class */
    const SCHEMA = Schema::class;

    /**
     * @inheritDoc
     */
    protected function getParameterRules()
    {
        $rules = parent::getParameterRules();
        $rules = [
                self::RULE_ALLOWED_INCLUDE_PATHS => [
                    Schema::REL_POSTS,
                    Schema::REL_POSTS . '.' . PostSchema::REL_COMMENTS,
                ]
            ] + $rules;

        return $rules;
    }

    /**
     * Validate input for 'store' action.
     *
     * @param ErrorCollection $errors
     *
     * @return void
     */
    protected function validateOnPost(ErrorCollection $errors)
    {
        $this->validateAttributes([
            Schema::ATTR_TITLE => 'required|max:' . Model::LENGTH_TITLE,
        ], $errors);
    }

    /**
     * Validate input for 'update' action.
     *
     * @param ErrorCollection $errors
     *
     * @return void
     */
    protected function validateOnPatch(ErrorCollection $errors)
    {
        $this->validateAttributes([
            Schema::ATTR_TITLE => 'required|max:' . Model::LENGTH_TITLE,
        ], $errors);
    }
}
