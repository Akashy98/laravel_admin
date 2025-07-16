<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AstrologerCategory extends Model
{
    protected $fillable = [
        'name', 'description', 'is_active'
    ];

    public function skills()
    {
        return $this->hasMany(AstrologerSkill::class, 'category_id');
    }
}
