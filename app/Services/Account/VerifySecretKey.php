<?php

namespace App\Services\Account;

use App\Models\User\User;
use App\Services\BaseService;
use App\Models\Account\Account;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\NotTheRightSecretKeyException;

class VerifySecretKey extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'account_id' => 'required|integer|exists:accounts,id',
            'secret_key' => 'required|string|size:32',
        ];
    }

    /**
     * Verify the secret key associated with the user account.
     * From the secret key, we generate a hash (that is encrypted as well) and
     * store this hash in the database.
     * Then we compare this hash with the hash we have in the database.
     * If the two hashes are different, the secret key is not the right one and
     * we throw an Exception, as we don't want to corrupt data.
     *
     * @param array $data
     * @return bool
     */
    public function execute(array $data) : bool
    {
        $this->validate($data);

        $account = Account::findOrFail($data['account_id']);

        if (!Hash::check($data['secret_key'], $account->secret_key_hash)) {
            throw new NotTheRightSecretKeyException();
        }

        return true;
    }
}
