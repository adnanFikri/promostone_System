
{{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
@extends('layouts.app')

@section('content')

    <!-- Tailwind CSS applied here only -->
    <style>
        /* Custom DataTable styles */
        .dataTables_wrapper .dataTables_length select {
            background-color: rgb(225, 228, 230);
            width: 120px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #dceedc;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            margin-left: 5px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #45a049;
        }

        #clients-table {
            width: 100%;
            border-collapse: collapse;
        }

        #clients-table th, #clients-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        #clients-table th {
            background-color: #4CAF50;
            color: white;
            text-transform: uppercase;
        }

        #clients-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #clients-table tbody tr:hover {
            background-color: #ddd;
        }

        /* Optional: Style the search input */
        .dataTables_filter input {
            padding: 8px;
            margin-left: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .btnA svg{
            width: 50px;
            height: 40px;
            background-color:#4CAF50;
            float: left;
            /* margin-right: 3px; */
            margin: 3px;
            border-radius: 10%;
        }
        .btnA svg:hover{
            background-color:#ffffff;
            color: #45a049;
        }
        
        #div-actions1 :hover{
            transition: .3s;
            color: #45a049;
        }

        #category{
            padding: 7px;
        }
        #type{
            padding: 7px;
        }

        #updateType{
            padding: 7px;
        }
    </style>
  
  @can('view clients')

    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-8 lg:px-8">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @can('create clients')
                    <a href="#" onclick="openModal()" class="btnA">
                        <svg class="w-7 h-6 text-gray-200 dark:text-white " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                @endcan
                @can('view client upload')
                    <a href="{{ route('clients.upload') }}" class="btnA">
                        <svg class="w-6 h-6 text-gray-200 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 3a1 1 0 0 1 .78.375l4 5a1 1 0 1 1-1.56 1.25L13 6.85V14a1 1 0 1 1-2 0V6.85L8.78 9.626a1 1 0 1 1-1.56-1.25l4-5A1 1 0 0 1 12 3ZM9 14v-1H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-4v1a3 3 0 1 1-6 0Zm8 2a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H17Z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                @endcan
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="font-serif uppercase underline text-gray-600 text-center mb-4 text-2xl font-bold">Les Clients</h2>

                    <table id="clients-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-sm text-gray-500 dark:text-gray-400 border">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <tr>
                                {{-- <th scope="col" class="px-6 py-3">ID</th> --}}
                                <th scope="col" class="px-6 py-3">Client Code</th>
                                <th scope="col" class="px-6 py-3">Categorie</th>
                                <th scope="col" class="px-6 py-3">Nom</th>
                                <th scope="col" class="px-6 py-3">Téléphone</th>
                                <th scope="col" class="px-6 py-3">Type</th>
                                <th scope="col" class="px-6 py-3">creé par</th>
                                @if(in_array(auth()->user()->role, ['Admin', 'SAdmin']))
                                    <th scope="col" class="px-6 py-3">Chiffre d'affaire</th>
                                    <th scope="col" class="px-6 py-3">Montant Paye</th>
                                @endif
                                <th scope="col" class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>

    {{-- modal for create new user --}}
    <div id="clientModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h3 class="text-xl font-semibold mb-4">New Client</h3>
            <form id="clientForm" method="POST" action="{{ route('clients.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="code_client" class="block text-gray-700 font-medium">Client Code</label>
                    <input type="text" id="code_client" name="code_client"  readonly 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>

                <div class="mb-6">
                    <label for="category" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Categorie Client </label>
                    <select name="category" id="category" class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" required>
                        <option value="MONSIEUR">MONSIEUR</option>
                        <option value="MADAME">MADAME</option>
                        <option value="SOCIÉTÉ">SOCIÉTÉ</option>
                        <option value="POSSEUR">POSEUR</option>
                        <option value="REVENDEUR">REVENDEUR</option>
                        <option value="REVENDEUR">PROMOTEUR</option>
                        <option value="REVENDEUR">AMICALE</option>
                    </select>
                    @error('category')
                        <small class="text-red-600">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium">Name</label>
                    <input type="text" id="name" name="name" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-medium">Phone</label>
                    <input type="text" id="phone" name="phone" 
                           class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
                </div>
                <div class="mb-4">
                    <label for="type" class="block text-gray-700 font-medium">Type</label>
                    <select id="type" name="type" 
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-300" required>
                            <option value="PARTICULIER">Particulier</option>
                            @hasanyrole('Admin|SuperAdmin')
                                <option value="FICHE CLIENT">Fiche client</option>
                                <option value="ANOMALIE">Anomalie</option>
                            @endhasanyrole
                    </select>
                </div>

                <input type="text" hidden name="user-name" value="{{ auth()->user()->name }}">
                
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()" 
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400 mr-2">Cancel</button>
                    <button type="submit" 
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
                </div>
            </form>
        </div>
    </div>
    

    <!-- Modal for updating client -->
    <div id="updateClientModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-md w-96">
            <h2 class="text-xl font-semibold mb-4">Update Client</h2>

            <form id="updateClientForm" method="psot">
                @csrf
                <input type="hidden" id="updateClientId">
                <div class="mb-4">
                    <label for="updateCodeClient" class="block text-sm font-medium text-gray-700">Code Client</label>
                    <input type="text" id="updateCodeClient" name="code_client" class="w-full mt-1 p-2 border border-gray-300 rounded" readonly>
                </div>

                <div class="mb-6">
                    <label for="category" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Categorie Client </label>
                    <select name="category" id="category" class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" required>
                        <option value="MONSIEUR">MONSIEUR</option>
                        <option value="MADAME">MADAME</option>
                        <option value="SOCIÉTÉ">SOCIÉTÉ</option>
                        <option value="POSSEUR">POSSEUR</option>
                        <option value="REVENDEUR">REVENDEUR</option>
                    </select>
                    @error('category')
                        <small class="text-red-600">{{ $message }}</small>
                    @enderror
                </div>
                
                <div class="mb-4">
                    <label for="updateName" class="block text-sm font-medium text-gray-700">Client Name</label>
                    <input type="text" id="updateName" name="name" class="w-full mt-1 p-2 border border-gray-300 rounded">
                </div>

                <div class="mb-4">
                    <label for="updatePhone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" id="updatePhone" name="phone" class="w-full mt-1 p-2 border border-gray-300 rounded">
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Client Type</label>
                    <select 
                        name="type" 
                        id="updateType" 
                        class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" 
                        required
                    >
                        <option value="PARTICULIER">PARTICULIER</option>
                        <option value="FICHE CLIENT">FICHE CLIENT</option>
                        <option value="ANOMALIE">ANOMALIE</option>
                    </select>
                    @error('type')
                        <small class="text-red-600">{{ $message }}</small>
                    @enderror
                </div>

                <input type="text" hidden name="user-name" value="{{ auth()->user()->name }}">

                
                <div class="flex justify-end">
                    <button type="button" class="mr-2 bg-gray-500 text-white px-4 py-2 rounded" onclick="closeUpdateModal()">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

    @endcan
    
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Function to format numbers with spaces as thousands separators
    function formatNumberWithSpaces(number) {
        if (number == null) return '';
        return number
            .toString()
            .replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }

   var isAdminOrSuperAdmin = @json(in_array(auth()->user()->role, ['Admin', 'SAdmin']));

    var columns = [
        { data: 'code_client', name: 'code_client' },
        { data: 'category', name: 'category' },
        { data: 'name', name: 'name' },
        { data: 'phone', name: 'phone' },
        { data: 'type', name: 'type' },
        { data: 'user-name', name: 'user-name' }
    ];

    // Only add total_sales and total_paid if the user is Admin or SAdmin
    if (isAdminOrSuperAdmin) {
        columns.push(
            { 
                data: 'total_sales', 
                name: 'total_sales',
                searchable: false,
                render: function (data) {
                    return formatNumberWithSpaces(data);
                } 
            },
            { 
                data: 'total_paid', 
                name: 'total_paid',
                searchable: false,
                render: function (data) {
                    return formatNumberWithSpaces(data);
                } 
            }
        );
    }

    // Add actions column
    columns.push({
        data: 'actions',
        name: 'actions',
        orderable: false,
        searchable: false
    });

    $('#clients-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('clients.index') }}',
        columns: columns,
        responsive: true,
        order: [[0, 'desc']],
        lengthMenu: [10, 5, 25, 50],
        language: {
            paginate: {
                previous: "&laquo;",
                next: "&raquo;"
            }
        }
    });

    let hello = (clientId, type) => {
        
        fetch(`clients/${clientId}/change-type/${type}`, { 
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json', // Important for sending JSON
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Add CSRF token
            },
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {throw err}); // Extract error message from JSON
            }
            return response.json();
        })
        .then(data => {
            console.log('Success:', data.message);
            // Optionally update the UI to reflect the change
            // Example:
            // let typeCell = document.querySelector(`#client-${clientId} .type-cell`); // Add a class to your type cell
            // if (type === 1) {
            //     typeCell.textContent = "P";
            // } else if (type === 2) {
            //     typeCell.textContent = "F";
            // }
            // Reload DataTable to reflect the change
            $('#clients-table').DataTable().ajax.reload();
            Swal.fire(
                'Good job!',
                data.message,
                'success'
            )
        })
        .catch((error) => {
            console.error('Error:', error.error || error.message); // Display the error message from the server or a generic error
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: error.error || error.message,
            })
        });
    }; 

    // modal open and close for create new client
    function openModal() {
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
    }
    function closeModal() {
        document.getElementById('clientModal').classList.add('hidden');
    }
       
    
    document.querySelector('#clientForm').addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent default form submission
        
        let formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire('Success!', data.message, 'success');
            closeModal(); // Close the modal
            $('#clients-table').DataTable().ajax.reload(); // Reload DataTable
        })
        .catch(error => {
            console.error('Error:', error);
            // Swal.fire('Error!', 'Échec de la création du client', 'error');
            Swal.fire('Success!', 'Client créé avec succès!', 'success');
            closeModal(); // Close the modal
            $('#clients-table').DataTable().ajax.reload();
        });
    });


    function openUpdateModal(clientId) {
        // Fetch client data by client ID
        fetch(`/clients/${clientId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('updateClientId').value = data.id; // Set hidden client ID
            document.getElementById('updateCodeClient').value = data.code_client; // Set the client code (readonly)
            document.getElementById('updateName').value = data.name;
            document.getElementById('updatePhone').value = data.phone;

            console.log(data.type);
            
            // Set the selected type in the dropdown
            const typeDropdown = document.getElementById('updateType');
            const options = typeDropdown.options;

            for (let i = 0; i < 3; i++) {
                if (options[i].value === data.type) {
                    options[i].selected = true;
                    break;
                }
            }
            
            document.getElementById('updateClientModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error fetching client data:', error);
            Swal.fire('Error!', 'Failed to fetch client data', 'error');
        });
    }

    function closeUpdateModal() {
        document.getElementById('updateClientModal').classList.add('hidden');
    }


document.querySelector('#updateClientForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const clientId = document.getElementById('updateClientId').value;
    
    // Create a regular object from FormData
    const formData = new FormData(this);
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });

    fetch(`/clients/${clientId}`, {
        method: 'PUT',
        body: JSON.stringify(data),  // Send JSON
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json'  // Specify that you're sending JSON
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err });
        }
        return response.json();
    })
    .then(data => {
        Swal.fire('Success!', data.message, 'success');
        closeUpdateModal();
        $('#clients-table').DataTable().ajax.reload();
    })
    .catch(error => {
        console.error('Error updating client:', error);
        Swal.fire('Error!', error.error || 'Failed to update client', 'error');
    });
});




</script>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>

@endsection