@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow rounded">

    @if(!$company)
        <p class="text-gray-600">You are not part of any company.</p>
    @else

        <div class="flex justify-between items-center mb-6">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-blue-600">{{ $company->name }}</h3>
                <p class="text-gray-700">{{ $company->address ?? '(Address not Provided)' }}</p>
                <p class="text-gray-600 mt-1">
                    <strong>Account Type:</strong>
                    {{ auth()->user()->roles->pluck('name')->first() ?? 'Member' }}
                </p>
                <p class="text-gray-600 mt-1">
                    <strong>User Count:</strong>
                    {{ $company->users->count() }}
                </p>
            </div>

            <div class="flex gap-2">
                
                @if(auth()->user()->can('can-send-invite') && (auth()->user()->company || auth()->user()->hasRole('SuperAdmin')))
                <a href="{{ route('invites.create') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                    Invite
                </a>
                @endif
                
                @if(auth()->user()->hasRole('Admin'))
                    <a href="{{ route('company.edit', $company) }}"
                       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                        Edit
                    </a>
                @endif

                {{-- Leave button --}}
                <form action="{{ route('company.remove', [$company, auth()->user()]) }}" method="POST">
                    @csrf
                    <button
                        onclick="return confirm('Leave this company?')"
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-600 text-sm">
                        Leave
                    </button>
                </form>

                @if(auth()->user()->hasRole('Admin'))
                    <form action="{{ route('company.destroy', $company) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button
                            onclick="return confirm('Delete this company?')"
                            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">
                            Delete
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(auth()->user()->roles->pluck('name')->first() === 'Admin')
            <div class="mb-4">
                <h4 class="text-md font-semibold text-gray-800 mb-2">Users in this Company:</h4>

                <table class="w-full border border-collapse text-center">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">Name</th>
                            <th class="p-2 border">Email</th>
                            <th class="p-2 border">Role</th>
                            <th class="p-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($company->users as $user)
                            <tr class="border">
                                <td class="p-2 border">{{ $user->name }}</td>
                                <td class="p-2 border">{{ $user->email }}</td>
                                <td class="p-2 border">{{ $user->roles->pluck('name')->first() }}</td>
                                <td class="p-2 border">
                                    <form action="{{ route('company.remove', [$company, $user]) }}" method="POST">
                                        @csrf
                                        <button
                                            onclick="return confirm('Remove this user from company?')"
                                            class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 text-xs">
                                            {{ $user->id === auth()->user()->id ? 'Leave' : 'Remove' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    @endif
</div>
@endsection