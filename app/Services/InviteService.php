<?php

namespace App\Services;

use App\Models\Invite;

class InviteService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    
    public function getInvites($foreign_key)
    {
        return Invite::with(['company', 'sender'])
            ->where($foreign_key, auth()->id())
            ->latest()
            ->get();
    }
}
