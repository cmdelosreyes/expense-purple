<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = "roles";

    protected $fillable = [
        'name',
        'description'
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function Users()
    {
        return $this->hasMany(User::class, 'id', 'role_id');
    }
}
