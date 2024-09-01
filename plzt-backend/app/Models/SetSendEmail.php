<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetSendEmail extends Model
{
    use HasFactory;

    protected $table = 'set_send_emails';
    protected $fillable = [
        'email',
    ];


}
