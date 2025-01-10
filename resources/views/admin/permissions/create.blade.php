<form action="{{ route('permissions.store') }}" method="post" class="max-w-2xl mx-auto p-6 bg-white shadow-lg rounded-lg">
    @csrf

    <div class="mb-6">
        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Name:</label>
        <input type="text" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" id="name" name="name">
    </div>

    <button type="submit" class="w-full py-3 bg-green-500 text-white font-semibold rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500">
        Save
    </button>
</form>
