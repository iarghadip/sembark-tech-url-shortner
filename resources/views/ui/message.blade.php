@if(session('success'))
    <div class="bg-green-100 text-green-800 p-2 rounded mb-4">{{ session('success') }}</div>
@endif

@if(session('error'))
    <p class="text-red-600 mt-1">{{ session('error') }}</p>
@endif