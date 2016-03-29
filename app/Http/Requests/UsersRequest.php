<?php namespace Neomerx\LimoncelloIlluminate\Http\Requests;

use Neomerx\Limoncello\Errors\ErrorCollection;
use Neomerx\LimoncelloIlluminate\Schemas\UserSchema as Schema;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class UsersRequest extends Request
{
    /** Related schema class */
    const SCHEMA = Schema::class;

    /**
     * Validate input for 'store' action.
     *
     * @param ErrorCollection $errors
     *
     * @return void
     */
    protected function validateOnPost(ErrorCollection $errors)
    {
        // Here we validate input data. In API we validate data after apply to User model.
        $this->validateAttributes([
            Schema::ATTR_PASSWORD => 'required',
        ], $errors);
    }
}
