
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
            background-color: #354a36;
        }

        #fournisseurs-table {
            width: 100%;
            border-collapse: collapse;
        }

        #fournisseurs-table th, #fournisseurs-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        #fournisseurs-table th {
            background-color: #4b524b;
            color: white;
        }

        #fournisseurs-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #fournisseurs-table tbody tr:hover {
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
    </style>
 {{-- @can('view fournisseurs') --}}
 <div class="py-5">
     <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
         <div class="p-6 bg-white rounded-lg shadow-md">
             <h2 class="text-2xl font-bold mb-6 text-center pb-4 border-b-4 mx-12" >Gestion des Fournisseurs</h2>
             
            <!-- Add Fournisseur Button -->
            <div class="text-center">
                <button onclick="openFournisseurModal()" class="text-center text-white px-4 py-2 rounded-md hover:bg-green-600 mb-4" style="background-color: #4b524b" >
                    + Ajouter un Fournisseur
                </button>
            </div>
             
            <!-- Fournisseurs Table -->
            <table id="fournisseurs-table" class="w-full text-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Raison</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
         </div>
     </div>
 </div>
 {{-- @endcan --}}
 
 {{-- @can('create fournisseurs') --}}
 <!-- Modal -->
 <div id="fournisseurModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
     <div class="bg-white rounded-lg shadow-lg p-6 w-1/2">
         <h3 class="text-xl font-bold mb-4 border-b-2 p-2 text-center uppercase">Créer un nouveau Fournisseur</h3>
         
         <form method="POST" action="{{ route('fournisseurs.store') }}">
             @csrf
             <!-- Raison -->
             <div class="mb-4">
                 <label for="raison" class="block text-gray-700">Raison</label>
                 <input type="text" id="raison" name="raison" class="w-full border-gray-300 rounded-md shadow-sm" required>
             </div>
 
             <!-- Phone -->
             <div class="mb-4">
                 <label for="phone" class="block text-gray-700">Phone</label>
                 <input type="text" id="phone" name="phone" class="w-full border-gray-300 rounded-md shadow-sm" required>
             </div>
 
             <!-- Address -->
             <div class="mb-4">
                 <label for="address" class="block text-gray-700">Address</label>
                 <input type="text" id="address" name="address" class="w-full border-gray-300 rounded-md shadow-sm" required>
             </div>
 
             <!-- Buttons -->
             <div class="flex justify-end border-t-2 pt-4">
                 <button type="button" onclick="closeFournisseurModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 mr-2">
                     Cancel
                 </button>
                 <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                     Save
                 </button>
             </div>
         </form>
     </div>
 </div>
 {{-- @endcan --}}

 <!-- Update Modal -->
<div id="updateFournisseurModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/2">
        <h3 class="text-xl font-bold mb-4 border-b-2 p-2 text-center uppercase">Modifier Fournisseur</h3>
        
        <form method="POST" id="updateFournisseurForm">
            @csrf
            @method('PUT')
            
            <!-- Raison -->
            <div class="mb-4">
                <label for="update_raison" class="block text-gray-700">Raison</label>
                <input type="text" id="update_raison" name="raison" class="w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            <!-- Phone -->
            <div class="mb-4">
                <label for="update_phone" class="block text-gray-700">Phone</label>
                <input type="text" id="update_phone" name="phone" class="w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            <!-- Address -->
            <div class="mb-4">
                <label for="update_address" class="block text-gray-700">Address</label>
                <input type="text" id="update_address" name="address" class="w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end border-t-2 pt-4">
                <button type="button" onclick="closeUpdateFournisseurModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 mr-2">
                    Cancel
                </button>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

 

  <script>
      $('#fournisseurs-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("fournisseurs.index") }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'raison', name: 'raison' },
                { data: 'phone', name: 'phone' },
                { data: 'address', name: 'address' },
                { data: 'actions', name: 'actions', orderable: false, searchable: false },
            ]
        });
  
    //   Modal Functions
      function openFournisseurModal() {
          document.getElementById('fournisseurModal').classList.remove('hidden');
      }
  
      function closeFournisseurModal() {
          document.getElementById('fournisseurModal').classList.add('hidden');
      }



      function openUpdateFournisseurModal(fournisseurId) {
        // Open the modal
        document.getElementById('updateFournisseurModal').classList.remove('hidden');
        
        // Fetch fournisseur data using AJAX
        $.ajax({
            url: `/fournisseurs/${fournisseurId}/edit`, // Ensure this route exists
            method: 'GET',
            success: function(data) {
                // Populate the modal fields
                document.getElementById('update_raison').value = data.raison;
                document.getElementById('update_phone').value = data.phone;
                document.getElementById('update_address').value = data.address;

                // Update the form action URL
                document.getElementById('updateFournisseurForm').action = `/fournisseurs/${fournisseurId}`;
            },
            error: function() {
                alert('Failed to fetch fournisseur data.');
            }
        });
    }

    function closeUpdateFournisseurModal() {
        document.getElementById('updateFournisseurModal').classList.add('hidden');
    }

    // //   update user 
    // function openUpdateModal(userId) {
    //     fetch(`/users/${userId}/edit`)
    //         .then(response => response.json())
    //         .then(data => {
    //             // Populate form fields with user data
    //             document.getElementById('update_name').value = data.name;
    //             document.getElementById('update_email').value = data.email;
    //             document.getElementById('update_role').value = data.roles[0]?.id || '';

    //             // Set form action URL dynamically
    //             document.getElementById('updateUserForm').action = `/users/${userId}`;

    //             // Show the modal
    //             document.getElementById('updateUserModal').classList.remove('hidden');
    //         })
    //         .catch(error => {
    //             console.error('Error fetching user data:', error);
    //         });
    // }

    // function closeUpdateModal() {
    //     document.getElementById('updateUserModal').classList.add('hidden');
    // }

  </script>
  


@endsection