@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10 p-6 bg-white shadow rounded">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Short Links</h2>

        <a href="{{ route('links.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
            Create Link
        </a>
    </div>

    <table class="w-full border border-collapse text-center">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 border">Short URL</th>
                <th class="p-2 border">Source</th>
                <th class="p-2 border">Clicks</th>
                <th class="p-2 border">User</th>
                <th class="p-2 border">Company</th>
                <th class="p-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($links as $link)
                <tr class="border">
                    <td class="p-2 border text-blue-600">
                        <a href="{{ url('forward/' . $link->slug) }}" target="_blank">
                            {{ url('forward/' . $link->slug) }}
                        </a>
                    </td>
                    <td class="p-2 border text-left">{{ $link->source }}</td>
                    <td class="p-2 border">{{ $link->clicks }}</td>
                    <td class="p-2 border">{{ $link->user->email ?? 'N/A' }}</td>
                    <td class="p-2 border">{{ $link->company->name ?? 'N/A' }}</td>
                    <td class="p-2 border flex justify-center gap-2">
                        <a href="{{ route('links.edit', $link) }}"
                           class="bg-blue-600 text-white px-3 py-1 rounded text-xs hover:bg-blue-700">
                            Edit
                        </a>

                        <form action="{{ route('links.destroy', $link) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button
                                onclick="return confirm('Delete this link?')"
                                class="bg-red-600 text-white px-3 py-1 rounded text-xs hover:bg-red-700">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection