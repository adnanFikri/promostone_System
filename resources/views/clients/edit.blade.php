<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Client') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-center mb-4 text-2xl font-bold">Edit Client</h2>

                    @if(session('success'))
                        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('clients.update', $client->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="code_client" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Client Code</label>
                            <select name="code_client" id="code_client" class="block w-50 text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" required>
                                {{-- <option value="">Select Code Client</option> --}}
                                <option value="{{ $client->code_client }}"  selected >
                                    {{ $client->code_client }}
                                </option>
                                @foreach ($sales as $sale)
                                    <option value="{{ $sale->code_client }}" @if($sale->code_client == $client->code_client) selected @endif>
                                        {{ $sale->code_client }}
                                    </option>
                                @endforeach
                            </select>
                            @error('code_client')
                                <small class="text-red-600">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="name" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Client Name</label>
                            <input type="text" name="name" id="name" class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg focus:outline-none bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" value="{{ old('name', $client->name) }}" required>
                            @error('name')
                                <small class="text-red-600">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="phone" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Phone Number</label>
                            <input type="text" name="phone" id="phone" class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg focus:outline-none bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" value="{{ old('phone', $client->phone) }}" required>
                            @error('phone')
                                <small class="text-red-600">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="type" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Client Type</label>
                            <select name="type" id="type" class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" required>
                                <option value="Particulier" @if($client->type == 'Particulier') selected @endif>Particulier</option>
                                <option value="Fiche client" @if($client->type == 'Fiche client') selected @endif>Fiche client</option>
                                <option value="Anomalie" @if($client->type == 'Anomalie') selected @endif>Anomalie</option>
                            </select>
                            @error('type')
                                <small class="text-red-600">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="w-full px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Update Client</button>
                        {{-- <a class="w-full px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Cancel</a> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#code_client').select2({
                ajax: {
                    url: '{{ route('sales.search') }}',
                    dataType: 'json',
                    processResults: function (data) {
                        return {
                            results: data.map(function (sale) {
                                return {
                                    id: sale.code_client,
                                    text: sale.code_client
                                };
                            })
                        };
                    }
                }
            });
        });
    </script>
</x-app-layout>
