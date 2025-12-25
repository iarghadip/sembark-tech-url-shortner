<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Invite;
use App\Models\Company;

class InviteController extends Controller
{
    public function __construct()
    {
        // Role-based middleware (commented for now)
        // $this->middleware('permission:can-send-invite')->only(['create', 'store']);
        // $this->middleware('permission:can-accept-invite')->only(['index', 'accept']);
    }

    // Show the invite creation form
    public function create()
    {
        $companies = Company::with('users')->orderBy('name')->get();
        return view('invites.create', compact('companies'));
    }

    // Store a new invite
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'company_id' => 'required',
            'message' => 'nullable|string|max:255',
            'new_company_name' => 'nullable|string|max:255',
        ]);
        
        $receiver = User::where('email', $request->email)->first();
        
        if (!$receiver) {
            return back()->with('error', 'User not found.');
        }
        
        if ($receiver->id === auth()->id()) {
            return back()->with('error', 'You cannot send an invite to yourself.');
        }
        
        if ($receiver->hasRole('SuperAdmin')) {
            return back()->with('error', 'You cannot send an invite to a SuperAdmin.');
        }
        
        $companyId = $request->company_id;
        
        if ($companyId === 'new') {
            $existingCompany = Company::where('name', $request->new_company_name)->first();
            if ($existingCompany) {
                return back()->with('error', 'A company with this name already exists.');
            }
            
            $company = Company::create([
                'name' => $request->new_company_name
            ]);
            
            auth()->user()->companies()->attach($company->id);
            $companyId = $company->id;
        }
        
        // Prevent duplicate invite or existing membership
        if ($receiver->companies()->where('companies.id', $companyId)->exists()) {
            return back()->with('error', 'User is already a member of this company.');
        }
        
        if (Invite::where('receiver_id', $receiver->id)
            ->where('company_id', $companyId)
            ->exists()) {
                return back()->with('error', 'User has already been invited to this company.');
            }
        
        Invite::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
            'company_id' => $companyId,
            'message' => $request->message,
        ]);
        
        return back()->with('success', 'Invite was sent.');
    }

    // Show invites for the logged-in user (tab view)
    public function index()
    {
        $receivedInvites = Invite::with(['company', 'sender'])
        ->where('receiver_id', auth()->id())
        ->latest()
        ->get();
        
        $sentInvites = Invite::with(['company', 'receiver'])
        ->where('sender_id', auth()->id())
        ->latest()
        ->get();

        return view('invites.index', compact('receivedInvites', 'sentInvites'));
    }

    // Accept an invite and add user to the company
    public function accept(Invite $invite)
    {
        if ($invite->receiver_id !== auth()->id()) {
            abort(403);
        }

        $receiver = $invite->receiver;
        $company = $invite->company;

        if (!$receiver->companies->contains($company->id)) {
            $receiver->companies()->attach($company->id);
        }

        $invite->delete();

        return back()->with('success', 'Invite accepted and you have been added to the company.');
    }
}