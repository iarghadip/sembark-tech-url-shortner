<?php

namespace App\Services;

use App\Models\Link;

class LinkService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    
    public static function validateUser(Link $link)
    {
        $user = auth()->user();

        if ($user->can('can-see-all-url')) {
            return null;
        }
        
        if ($user->can('can-see-org-url')) {
            
            if ($link->company_id !== $user->company_id) {
                return 'User is not authorized to use this feature.';
            }
            return null;
        }
        
        if ($user->can('can-see-self-url')) {

            if ($link->user_id !== $user->id) {
                return 'User is not authorized to use this feature.';
            }
            return null;
        }
        
        return 'User is not authorized to use this feature.';
    }
    
}
    