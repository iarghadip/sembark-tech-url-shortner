@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10 p-6 bg-white shadow rounded" x-data="{ tab: 'received' }">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Your Invites</h2>
        <a href="{{ route('invites.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Create Invite
        </a>
    </div>

    <!-- Tabs -->
    <div class="flex border-b mb-4">
        <button
            @click="tab='received'"
            :class="tab==='received' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'"
            class="px-4 py-2 font-semibold">
            Received Invites
        </button>

        <button
            @click="tab='sent'"
            :class="tab==='sent' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-600'"
            class="px-4 py-2 font-semibold ml-4">
            Sent Invites
        </button>
    </div>

    <!-- Received Invites -->
    <div x-show="tab === 'received'">
        @if($receivedInvites->isEmpty())
            <p class="text-gray-600">No received invites.</p>
        @else
            <table class="w-full border border-collapse">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">User</th>
                    <th class="p-2 border">Company</th>
                    <th class="p-2 border">Date</th>
                    <th class="p-2 border">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($receivedInvites as $invite)
                    <!-- Main row -->
                    <tr class="text-center">
                        <td class="p-2 border">{{ $invite->sender->name }}</td>
                        <td class="p-2 border">{{ $invite->company->name }}</td>
                        <td class="p-2 border">{{ $invite->created_at->format('d/m/Y') }}</td>
                        <td class="p-2 border">
                            <form method="POST" action="{{ route('invites.accept', $invite) }}">
                                @csrf
                                <button class="bg-blue-600 text-white px-3 py-1 rounded">
                                    Accept
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Message row -->
                    @if($invite->message)
                        <tr class="bg-gray-50">
                            <td colspan="4" class="p-2 border text-sm text-gray-700 text-left">
                                {{ $invite->message }}
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <!-- Sent Invites -->
    <div x-show="tab === 'sent'">
        @if($sentInvites->isEmpty())
            <p class="text-gray-600">No sent invites.</p>
        @else
            <table class="w-full border border-collapse">
                <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">User</th>
                    <th class="p-2 border">Company</th>
                    <th class="p-2 border">Date</th>
                    <th class="p-2 border">Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($sentInvites as $invite)
                    <!-- Main row -->
                    <tr class="text-center">
                        <td class="p-2 border">{{ $invite->receiver->name }}</td>
                        <td class="p-2 border">{{ $invite->company->name }}</td>
                        <td class="p-2 border">{{ $invite->created_at->format('d/m/Y') }}</td>
                        <td class="p-2 border">Pending</td>
                    </tr>

                    <!-- Message row -->
                    @if($invite->message)
                        <tr class="bg-gray-50">
                            <td colspan="4" class="p-2 border text-sm text-gray-700 text-left">
                                {{ $invite->message }}
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
@endsection