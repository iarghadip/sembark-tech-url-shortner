<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Link;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address'
    ];
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    public function links()
    {
        return $this->hasMany(Link::class);
    }

}
