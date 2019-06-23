<?php

namespace App\Services\Contact\Contact;

use Illuminate\Support\Str;
use App\Services\BaseService;
use App\Models\Contact\Contact;
use App\Helpers\EncryptionHelper;
use App\Services\Account\LogAuditAction;
use App\Services\Contact\Avatar\GenerateAvatar;

class CreateContact extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'secret' => 'required|string|size:32',
            'account_id' => 'required|integer|exists:accounts,id',
            'author_id' => 'required|integer|exists:users,id',
            'first_name' => 'required|string|max:255',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Create a contact.
     *
     * @param array $data
     * @return Contact
     */
    public function execute(array $data) : Contact
    {
        $this->validate($data);

        $this->validatePermissions($data['author_id'], $data['account_id']);

        $contact = $this->add($data);

        (new LogAuditAction)->execute([
            'account_id' => $data['account_id'],
            'action' => 'contact_created',
            'objects' => json_encode([
                'author_id' => $data['author_id'],
                'contact_id' => $contact->id,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $contact;
    }

    /**
     * Actually create the contact.
     *
     * @param array $data
     * @return Contact
     */
    private function add(array $data) : Contact
    {
        $uuid = Str::uuid()->toString();

        $avatar = (new GenerateAvatar)->execute([
            'uuid' => $uuid,
            'size' => 200,
        ]);

        return Contact::create([
            'account_id' => $data['account_id'],
            'enc_first_name' => EncryptionHelper::encrypt($data['first_name'], $data['secret']),
            'uuid' => Str::uuid()->toString(),
            'avatar' => $avatar,
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);
    }
}
