<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;
use App\Models\Invite;
use App\Models\Company;
use App\Models\Link;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed'
        ];
    }
    
    protected static function booted()
    {
        static::created(function ($user) {
            $user->assignRole('Member');
        });
    }
    
    public function links()
    {
        return $this->hasMany(Link::class);
    }
    
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sentInvites()
    {
        return $this->hasMany(Invite::class, 'sender_id');
    }

    public function receivedInvites()
    {
        return $this->hasMany(Invite::class, 'receiver_id');
    }

}
