<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Company;

class Link extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'source',
        'slug',
        'clicks',
        'desciption',
        'user_id',
        'company_id'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}