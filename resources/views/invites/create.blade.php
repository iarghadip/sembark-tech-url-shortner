@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto mt-10 p-6 bg-white shadow rounded">
    <h2 class="text-xl font-bold mb-4">Send Invite</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <p class="text-red-600 mt-1">{{ session('error') }}</p>
    @endif


    <form action="{{ route('invites.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Receiver Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded" value="{{ old('email') }}" required>
            @error('email')<p class="text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Company</label>
            @if($can_see_all)
            <select name="company_id" class="w-full border p-2 rounded mb-2" id="company-id">
                <option value="">-- Select Company --</option>
                
                @foreach($companies as $company)
                <option value="{{ $company->id }}"
                    data-user-count="{{ $company->users->count() }}"
                    {{ old('company_id') == $company->id ? 'selected' : '' }}>
                    {{ $company->name }}
                </option>
                @endforeach
                
                <option value="new" data-user-count="0">+ Create New Company</option>
            </select>
            @else
            <select class="w-full border p-2 rounded mb-2 bg-gray-100 cursor-not-allowed" disabled>
                @foreach($companies as $company)
                <option value="{{ $company->id }}" selected>{{ $company->name }}</option>
                @endforeach
            </select>
            <input type="hidden" name="company_id" value="{{ $companies->first()->id ?? '' }}">
            @endif
            
            <input type="text" name="company_name" id="company-name" class="w-full border p-2 rounded mt-2 hidden" placeholder="Enter new company name">
            @error('company_id')<p class="text-red-600 mt-1">{{ $message }}</p>@enderror
            @error('company_name')<p class="text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1">Message</label>
            <textarea name="message" class="w-full border p-2 rounded">{{ old('message') }}</textarea>
            @error('message')<p class="text-red-600 mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="make_admin" id="admin-checkbox" checked disabled class="form-checkbox">
                <input type="hidden" name="make_admin" id="make-admin-hidden" value="1">
                <span class="ml-2">Make user an admin</span>
            </label>
        </div>
        
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Send Invite
        </button>
    </form>
</div>
<script>
    const companySelect = document.getElementById('company-id');
    const newCompanyInput = document.getElementById('company-name');
    const makeAdminCheckbox = document.getElementById('admin-checkbox');

    function updateAdminCheckbox() {
        const selectedOption = companySelect.options[companySelect.selectedIndex];
        const userCount = parseInt(selectedOption.dataset.userCount) || 0;

        if (companySelect.value === 'new' || userCount === 0) {
            makeAdminCheckbox.checked = true;
            makeAdminCheckbox.disabled = true;
            document.getElementById('make-admin-hidden').value = 1;
        } else {
            makeAdminCheckbox.checked = false;
            makeAdminCheckbox.disabled = false;
            document.getElementById('make-admin-hidden').value = 0;
        }
    }

    companySelect.addEventListener('change', function () {
        if (this.value === 'new') {
            newCompanyInput.classList.remove('hidden');
        } else {
            newCompanyInput.classList.add('hidden');
        }
        updateAdminCheckbox();
    });

    updateAdminCheckbox();
</script>
@endsection