@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10 p-6 bg-white shadow rounded" x-data="{ tab: 'received' }">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Your Invites</h2>
        @if($can_send_invite)
        <a href="{{ route('invites.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Create Invite
        </a>
        @endif
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

    @php
        $tabs = [
            'received' => [
                'data' => $invites['received'],
                'userColumn' => 'sender',
                'actionColumn' => true
            ],
            'sent' => [
                'data' => $invites['sent'],
                'userColumn' => 'receiver',
                'actionColumn' => false
            ]
        ];
    @endphp

    @foreach($tabs as $key => $tabConfig)
        <div x-show="tab === '{{ $key }}'">
            @if($tabConfig['data']->isEmpty())
                <p class="text-gray-600">No {{ $key }} invites.</p>
            @else
                <table class="w-full border border-collapse">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">User</th>
                            <th class="p-2 border">Company</th>
                            <th class="p-2 border">Date</th>
                            <th class="p-2 border">{{ $tabConfig['actionColumn'] ? 'Action' : 'Status' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tabConfig['data'] as $invite)
                            <tr class="text-center">
                                <td class="p-2 border">{{ $invite->{$tabConfig['userColumn']}->name }}</td>
                                <td class="p-2 border">{{ $invite->company->name }}</td>
                                <td class="p-2 border">{{ $invite->created_at->format('d/m/Y') }}</td>
                                <td class="p-2 border">
                                    @if($tabConfig['actionColumn'])
                                        <form method="POST" action="{{ route('invites.accept', $invite) }}">
                                            @csrf
                                            <button class="bg-blue-600 text-white px-3 py-1 rounded">
                                                Accept
                                            </button>
                                        </form>
                                    @else
                                        Pending
                                    @endif
                                </td>
                            </tr>

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
    @endforeach

</div>
@endsection