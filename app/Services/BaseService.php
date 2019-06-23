<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User\User;
use Illuminate\Support\Facades\Validator;

abstract class BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     * Validate an array against a set of rules.
     *
     * @param array $data
     * @return bool
     */
    public function validate(array $data) : bool
    {
        $validator = Validator::make($data, $this->rules())
            ->validate();

        return true;
    }

    /**
     * Checks if the user has the permission to do the action.
     *
     * @param int $userId
     * @param int $accountId
     * @return User
     */
    public function validatePermissions(int $userId, int $accountId) : User
    {
        return User::where('id', $userId)
            ->where('account_id', $accountId)
            ->firstOrFail();
    }

    /**
     * Checks if the value is empty or null.
     *
     * @param mixed $data
     * @param mixed $index
     * @return mixed
     */
    public function nullOrValue($data, $index)
    {
        if (empty($data[$index])) {
            return;
        }

        return $data[$index] == '' ? null : $data[$index];
    }

    /**
     * Checks if the value is empty or null and returns a date from a string.
     *
     * @param mixed $data
     * @param mixed $index
     * @return mixed
     */
    public function nullOrDate($data, $index)
    {
        if (empty($data[$index])) {
            return;
        }

        return $data[$index] == '' ? null : Carbon::parse($data[$index]);
    }

    /**
     * Returns the value if it's defined, or false otherwise.
     *
     * @param mixed $data
     * @param mixed $index
     * @return mixed
     */
    public function valueOrFalse($data, $index)
    {
        if (empty($data[$index])) {
            return false;
        }

        return $data[$index];
    }
}
