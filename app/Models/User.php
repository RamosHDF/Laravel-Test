<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function Companies() {
        return $this->belongsToMany(Company::class, 'company_user', 'company_id','user_id')
        ->withTimestamps();
    }
}
