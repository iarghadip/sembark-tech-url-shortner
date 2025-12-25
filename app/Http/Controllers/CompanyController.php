<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\Company;

class CompanyController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        return view('company.index', compact('company'));
    }

    public function edit(Company $company)
    {
        return view('company.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $company->update($validator->validated());
        
        return redirect()->route('company.index');
    }

    public function destroy(Company $company)
    {
        $company->users()->update(['company_id' => null]);
        $company->delete();
        return redirect()->route('company.index');
    }
    
    public function remove(Company $company, User $user)
    {
        $user->company_id = null;
        $user->save();
        
        return back()->with('success', "{$user->name} has been removed from the company.");
    }

}