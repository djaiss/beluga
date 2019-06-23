<?php

namespace App\Services\Search;

use Illuminate\Support\Str;
use App\Services\BaseService;
use App\Models\Account\Account;
use App\Helpers\EncryptionHelper;
use Illuminate\Database\Eloquent\Collection;

class SearchContactByName extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'secret_key' => 'required|string|size:32',
            'account_id' => 'required|integer|exists:accounts,id',
            'terms' => 'required|string|max:255',
        ];
    }

    /**
     * Search contacts by name.
     * It grabs all the contacts associated with the account, then locally
     * decrypt all the names, and perform the search on them.
     *
     * @param array $data
     * @return Collection
     */
    public function execute(array $data)
    {
        $this->validate($data);

        $account = Account::findOrFail($data['account_id']);
        $contacts = $this->filterCollection($account->contacts()->get(), $data);

        return $contacts;
    }

    /**
     * Find the data in the given collection.
     *
     * @param Collection $contacts
     * @param array $data
     * @return Collection
     */
    private function filterCollection(Collection $contacts, array $data)
    {
        $contacts = EncryptionHelper::removeEncryptionForClient($contacts, $data['secret_key']);

        $contacts = $contacts->filter(function ($contact) use ($data) {
            return Str::contains($contact->enc_first_name, $data['terms']);
        });

        return $contacts;
    }
}
