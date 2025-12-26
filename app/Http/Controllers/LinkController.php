<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

use App\Models\Link;

use App\Services\LinkService;

class LinkController extends Controller
{
    protected $service;

    public function __construct()
    {
        $this->service = new LinkService();
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->can('can-see-all-url')) {

            $links = Link::latest()->get();

        } elseif ($user->can('can-see-org-url')) {

            $links = Link::where('company_id', $user->company_id)->latest()->get();

        } elseif ($user->can('can-see-self-url')) {

            $links = Link::where('user_id', $user->id)->latest()->get();

        } else {

            $links = collect();

        }
        
        return view('links.index', compact('links'));
    }

    public function create()
    {
        $this->authorize('can-short-url');
        
        return view('links.create');
    }
    
    public function store(Request $request)
    {
        $this->authorize('can-short-url');
        
        $validator = Validator::make($request->all(), [
            'source' => 'required|url',
            'desciption' => 'nullable|string'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        do {
            $slug = Str::random(5);
        } while (Link::where('slug', $slug)->exists());
        
        Link::create([
            'source' => $request->source,
            'slug' => $slug,
            'clicks' => 0,
            'desciption' => $request->desciption,
            'user_id' => auth()->id(),
            'company_id' => auth()->user()->company_id ?? null
        ]);
        
        return back()->with('success', 'Link was created.');
    }

    public function edit(Link $link)
    {
        $validation = $this->service->validateUser($link);

        if ($validation) {
            return back()->with('error', $validation);
        }

        return view('links.edit', compact('link'));
    }

    public function update(Request $request, Link $link)
    {
        $validation = $this->service->validateUser($link);

        if ($validation) {
            return back()->with('error', $validation);
        }

        $validator = Validator::make($request->all(), [
            'source' => 'required|url',
            'desciption' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $link->update([
            'source' => $request->source,
            'desciption' => $request->desciption
        ]);

        return back()->with('success', 'Link was updated.');
    }

    public function destroy(Link $link)
    {
        $validation = $this->service->validateUser($link);

        if ($validation) {
            return back()->with('error', $validation);
        }

        $link->delete();

        return back()->with('success', 'Link deleted successfully.');
    }

    public function forward($slug)
    {
        $link = Link::where('slug', $slug)->firstOrFail();
        $link->increment('clicks');

        return redirect()->away($link->source);
    }
}