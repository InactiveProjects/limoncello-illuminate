<?php namespace Neomerx\LimoncelloIlluminate\Http\Requests;

use Neomerx\Limoncello\Errors\ErrorCollection;
use Neomerx\LimoncelloIlluminate\Database\Models\Role as Model;
use Neomerx\LimoncelloIlluminate\Schemas\RoleSchema as Schema;

/**
 * @package Neomerx\LimoncelloIlluminate
 */
class RolesRequest extends Request
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
        $this->validateData([
            Schema::KEYWORD_ID => $this->getId(),
        ], [
            Schema::KEYWORD_ID => 'required|unique:' . Model::TABLE_NAME . ',' . Model::FIELD_ID,
        ], $errors);

        $this->validateAttributes([
            Schema::ATTR_NAME => 'required|max:' . Model::LENGTH_NAME,
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
        $this->validateData([
            Schema::KEYWORD_ID => $this->getId(),
        ], [
            Schema::KEYWORD_ID => 'required|exists:' . Model::TABLE_NAME . ',' . Model::FIELD_ID,
        ], $errors);

        $this->validateAttributes([
            Schema::ATTR_NAME => 'required|max:' . Model::LENGTH_NAME,
        ], $errors);
    }
}
