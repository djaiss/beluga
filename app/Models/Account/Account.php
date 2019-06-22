<?php

namespace App\Models\Account;

use App\Models\User\User;
use App\Models\Contact\Contact;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    use LogsActivity;

    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the user records associated with the account.
     *
     * @return hasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the contact records associated with the account.
     *
     * @return hasMany
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * Get the audit log records associated with the account.
     *
     * @return hasMany
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
