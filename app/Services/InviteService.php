<?php

namespace App\Services;

use App\Models\Invite;
use App\Models\User;
use App\Models\Company;

class InviteService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    
    public function getCompany($id, $name)
    {
        if ($id === 'new') {
            
            $company = Company::firstOrCreate(['name' => $name]);
            
            if (!auth()->user()->companies->contains($company->id)) {
                auth()->user()->companies()->attach($company->id);
            }
            
            return $company;
        }
        
        return Company::find($id);
    }
    
    public function validateRequest(User $user, Company $company, $sendInviteMode)
    {
        if (!$user) {
            return 'User was not found in our records.';
        }
        
        if (!$company) {
            return 'User company was not found in our records.';
        }
        
        if ($user->hasRole('SuperAdmin')) {
            return 'User does not have permission to use this feature.';
        }
        
        if ($user->companies()->where('companies.id', $company->id)->exists()) {
            return 'User is already a member of this company.';
        }
        
        if ($sendInviteMode) {
            
            if (Invite::where('receiver_id', $user->id)->where('company_id', $company->id)->exists()) {
                return 'User has already been invited to this company.';
            }
            
            if (auth()->id() === $user->id) {
                return 'User cannot send an invite to themselves.';
            }
            
        } else {
            
            if (auth()->id() !== $user->id) {
                return 'User is not authorized to accept this invite.';
            }
            
        }
        
        return null;
    }
    
    public function getInvites($foreign_key)
    {
        return Invite::with(['company', 'sender'])
            ->where($foreign_key, auth()->id())
            ->latest()
            ->get();
    }
}
    