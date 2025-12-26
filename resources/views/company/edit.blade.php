@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 p-6 bg-white shadow rounded">

    <h2 class="text-xl font-bold mb-6">Edit Company</h2>

    <form action="{{ route('company.update', $company) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-semibold mb-1">Company Name <span class="text-red-500">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $company->name) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600">
        </div>

        <div class="mb-4">
            <label for="address" class="block text-gray-700 font-semibold mb-1">Address</label>
            <textarea id="address" name="address"
                      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600"
                      rows="3">{{ old('address', $company->address) }}</textarea>
        </div>

        <div class="flex justify-end gap-2">
            <a href="{{ route('company.index') }}" 
               class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">
                Cancel
            </a>
            <button type="submit" 
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Update Company
            </button>
        </div>
    </form>

</div>
@endsection