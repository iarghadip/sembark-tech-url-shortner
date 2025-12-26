<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        $can_see_all = $user->can('can-see-all-org');
        
        if ($can_see_all) {
            $companies = Company::with('users')->orderBy('name')->get();
        } elseif ($user->can('can-see-self-org')) {
            $companies = collect($user->company ? [$user->company->load('users')] : []);
        } else {
            $companies = collect();
        }
        
        return view('invites.create', compact('companies', 'can_see_all'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'company_id' => 'required',
            'company_name' => 'nullable|required_if:company_id,new|string|max:100',
            'message' => 'nullable|string|max:300'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $validation = $this->service->validateSender(Auth::user(), $request->company_id);
        
        if ($validation) {
            return back()->with('error', $validation);
        }
        
        $receiver = User::where('email', $request->email)->first();
        $company = $this->service->getCompany($request->company_id, $request->company_name);
        
        $validation = $this->service->validateReceiver($receiver, $company, true);
        
        if ($validation) {
            return back()->with('error', $validation);
        }

        Invite::create([
            'make_admin' => $request->has('make_admin') && $request->make_admin,
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'company_id' => $company->id,
            'message' => $request->message
        ]);
        
        return back()->with('success', 'Invite was sent.');
    }
    
    public function index()
    {
        $invites = [
            'received' => $this->service->getInvites('receiver_id'),
            'sent' => $this->service->getInvites('sender_id')
        ];
        
        $can_send_invite = Auth::user()->can('can-send-invite');
        
        return view('invites.index', compact('invites', 'can_send_invite'));
    }
    
    public function accept(Invite $invite)
    {
        $receiver = $invite->receiver;
        $company = $invite->company;
        
        $validation = $this->service->validateReceiver($receiver, $company, false);
        
        if ($validation) {
            return back()->with('error', $validation);
        }

        if (!$receiver->company_id) {
            $receiver->update(['company_id' => $company->id]);
        }
        
        if ($invite->make_admin) {
            $receiver->refresh()->syncRoles('Admin');
        }

        $invite->delete();

        return back()->with('success', 'Invite accepted and you have been added to the company.');
    }
    
    public function destroy(Invite $invite)
    {
        $id = Auth::id();

        if ($invite->sender_id !== $id && $invite->receiver_id !== $id) {
            return back()->with('error', 'You are not authorized to delete this invite.');
        }
        
        $invite->delete();
        
        return back()->with('success', 'Invite deleted successfully.');
    }
}