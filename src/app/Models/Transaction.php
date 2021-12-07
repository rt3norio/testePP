<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Transaction extends Model
{

    protected $hidden = [
        'updated_at',
    ];

    public function scopeFromUser($query, $user)
    {
        $query->where('payer_user_id', $user->id)->orWhere('payee_user_id', $user->id);
    }

    public function payer()
    {
        return $this->hasOne(User::class, 'id', 'payer_user_id');
    }

    public function payee()
    {
        return $this->hasOne(User::class, 'id', 'payee_user_id');
    }
}
