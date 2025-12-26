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

        if (auth()->user()->can('can-see-all-url')) {

            return null;

        } else if (auth()->user()->can('can-see-org-url')) {
            
            if ($link->company_id !== auth()->user()->company_id) {
                return 'User is not authorized to use this feature.';
            }

            return null;

        } else if (auth()->user()->can('can-see-self-url')) {

            if ($link->user_id !== auth()->user()->id) {
                return 'User is not authorized to use this feature.';
            }

            return null;
            
        }
        
        return 'User is not authorized to use this feature.';
    }
    
}
    