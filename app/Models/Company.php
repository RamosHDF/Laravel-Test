<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // Relationships
    public function Country() {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function Users() {
        return $this->belongsToMany(User::class, 'campany_user', 'company_id','user_id')
        ->withTimestamps();
    }
}
