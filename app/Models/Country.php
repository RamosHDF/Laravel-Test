<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    // Relationships
    public function Country() {
        return $this->hasMany(Company::class);
    }
}
