<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Invite;
use App\Models\Company;

use App\Services\InviteService;

class InviteController extends Controller
{
    
    protected $service;
    
    public function __construct()
    {
        $this->service = new InviteService();
        $this->middleware('permission:can-send-invite')->only(['create', 'store']);
        $this->middleware('permission:can-accept-invite')->only(['accept']);
    }
    
    public function create()
    {
        $companies = Company::with('users')->orderBy('name')->get();
        return view('invites.create', compact('companies'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'company_id' => 'required',
            'company_name' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:255'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $receiver = User::where('email', $request->email)->first();
        $company = $this->service->getCompany($request->company_id, $request->company_name);
        
        $validation = $this->service->validateRequest($receiver, $company, true);
        
        if ($validation) {
            return back()->with('error', $validation);
        }

        Invite::create([
            'make_admin' => $request->has('make_admin') && $request->make_admin,
            'sender_id' => auth()->id(),
            'receiver_id' => $receiver->id,
            'company_id' => $company->id,
            'message' => $request->message
        ]);
        
        return back()->with('success', 'Invite was sent.');
    }
    
    public function index()
    {
        return view('invites.index', [
            'invites' => [
                'received' => $this->service->getInvites('receiver_id'),
                'sent' => $this->service->getInvites('sender_id')
            ]
        ]);
    }
    
    public function accept(Invite $invite)
    {
        $receiver = $invite->receiver;
        $company = $invite->company;
        
        $validation = $this->service->validateRequest($receiver, $company, false);
        
        if ($validation) {
            return back()->with('error', $validation);
        }

        if (!$receiver->companies->contains($company->id)) {
            $receiver->companies()->attach($company->id);
        }
        
        if ($invite->make_admin) {
            $receiver->refresh()->syncRoles('Admin');
        }

        $invite->delete();

        return back()->with('success', 'Invite accepted and you have been added to the company.');
    }
}