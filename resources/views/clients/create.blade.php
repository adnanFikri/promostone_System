
@extends('layouts.app')

@section('content')
    <style>
        .selec2{
            height: 40px !important;

        }
        .select2-selection{
            height: 45px !important;
            background-color: rgb(249 250 251) !important;
            border-radius: 20px;
            padding: 5px;
        }
        .select2-selection__rendered{
            height: 100% !important;
        }
        .select2-selection__placeholder{
            height: 40px !important;
            /* color: red !important; */
            color: black !important;
            padding-top: 8px !important;
            font-weight: bold !important;
        }
        .dropdown-wrapper{
            background-color: gray !important;
        }

        .cinpt{
            display: flex;
        }
    </style>

    @can("create clients")

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-12">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-center mb-4 text-2xl font-bold">Ajouter nouveau client</h2>

                    @if(session('success'))
                        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('clients.store') }}" method="POST">
                        @csrf

                        <div class="mb-6" >
                            <label for="code_client" class="block text-gray-700 font-medium">Client Code</label>
                            <input type="text" id="code_client" name="code_client"  readonly 
                                   class="w-full border border-gray-200 rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
                        </div>

                        <div class="mb-6">
                            <label for="name" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Nom Client </label>
                            <input type="text" name="name" id="name" class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg focus:outline-none bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" value="{{ old('name') }}" required>
                            @error('name')
                                <small class="text-red-600">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="phone" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Téléphon Client </label>
                            <input type="text" name="phone" id="phone" class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg focus:outline-none bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" value="{{ old('phone') }}">
                            @error('phone')
                                <small class="text-red-600">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="type" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Type Client </label>
                            <select name="type" id="type" class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" required>
                                <option value="PARTICULIER">Particulier</option>
                                <option value="FICHE CLIENT">Fiche client</option>
                                <option value="ANOMALIE">Anomalie</option>
                            </select>
                            @error('type')
                                <small class="text-red-600">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="w-full px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Enregistrer le client</button>
                        <button onclick="window.location='{{ route('clients.index') }}';" class="w-full mt-2 px-4 py-2 text-white bg-blue-400 rounded hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">Annuler</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan
    <script>
        fetch('/clients/next-code', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('code_client').value = data.code_client; // Populate the input
            document.getElementById('clientModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error fetching client code:', error);
            Swal.fire('Error!', 'Failed to fetch client code', 'error');
        });


    </script>

@endsection