@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow rounded">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Your Invites</h2>
        <a href="{{ route('invites.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Create Invite
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($invites->isEmpty())
        <p class="text-gray-600">No invites available.</p>
    @else
        <table class="w-full border border-gray-200 rounded">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-2 border">From</th>
                    <th class="p-2 border">Company</th>
                    <th class="p-2 border">Message</th>
                    <th class="p-2 border">Sent At</th>
                    <th class="p-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invites as $invite)
                    <tr class="text-center">
                        <td class="p-2 border">{{ $invite->sender->name }}</td>
                        <td class="p-2 border">{{ $invite->company->name }}</td>
                        <td class="p-2 border">{{ $invite->message ?? '-' }}</td>
                        <td class="p-2 border">{{ $invite->created_at->format('Y-m-d H:i') }}</td>
                        <td class="p-2 border">
                            <form action="{{ route('invites.accept', $invite) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Accept
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection