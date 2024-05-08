<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThirdpartyOrganisation extends Model
{
    use HasFactory;

    protected $table = 'alchemy_thirdparty_organisation';

    protected $fillable = [
        'organisation_name',
        'primary_contact_first_name',
        'primary_contact_last_name',
        'primary_contact_role',
        'primary_contact_phone',
        'primary_contact_email',
        'email_for_billing',
        'email_for_confirmations',
        'comment',
    ];
}
