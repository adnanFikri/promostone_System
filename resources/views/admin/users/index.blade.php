
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

        #users-table {
            width: 100%;
            border-collapse: collapse;
        }

        #users-table th, #users-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        #users-table th {
            background-color: #4CAF50;
            color: white;
        }

        #users-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #users-table tbody tr:hover {
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
  
  @can('view users')
  <div class="py-5">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="p-6 bg-white rounded-lg shadow-md">
              <h2 class="text-2xl font-bold mb-6 text-center pb-4 border-b-4 mx-12">Gestion des Utilisateurs</h2>
              
              <!-- Add User Button -->
              <div class="text-center">
                  <button onclick="openModal()" class="bg-blue-700 text-center text-white px-4 py-2 rounded-md hover:bg-blue-600 mb-4">
                      + Ajouter un utilisateur
                  </button>
              </div>
              
              <!-- Users Table -->
              <table id="users-table" class="w-full text-sm">
                  <thead>
                      <tr>
                          <th>ID</th>
                          <th>Name</th>
                          <th>Email</th>
                          <th>Role</th>
                          <th>Actions</th>
                      </tr>
                  </thead>
                  <tbody></tbody>
              </table>
          </div>
      </div>
  </div>
  @endcan
  @can('create users')
    <!-- Modal -->
    <div id="userModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-1/2">
            <h3 class="text-xl font-bold mb-4 border-b-2 p-2 text-center uppercase">Créer un nouvel utilisateur</h3>
            
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-gray-700">Nom</label>
                    <input type="text" id="name" name="name" class="w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <!-- Role -->
                <div class="mb-4">
                    <label for="role" class="block text-gray-700">Role</label>
                    <select id="role" name="role" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        <option value="">choisir Role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="w-full border-gray-300 rounded-md shadow-sm" required>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end border-t-2 pt-4">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 mr-2">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
@endcan
@can('update users')
  <!-- Update Modal -->
<div id="updateUserModal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 w-1/2">
        <h3 class="text-xl font-bold mb-4 border-b-2 p-2 text-center uppercase">Modifier l'utilisateur</h3>
        
        <form id="updateUserForm" method="POST">
            @csrf
            @method('PUT')
            <!-- Name -->
            <div class="mb-4">
                <label for="update_name" class="block text-gray-700">Nom</label>
                <input type="text" id="update_name" name="name" class="w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="update_email" class="block text-gray-700">Email</label>
                <input type="email" id="update_email" name="email" class="w-full border-gray-300 rounded-md shadow-sm" required>
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="update_password" class="block text-gray-700">Mot de passe</label>
                <input type="password" id="update_password" name="password" class="w-full border-gray-300 rounded-md shadow-sm">
                <small class="text-gray-500">Laissez vide pour ne pas changer le mot de passe</small>
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label for="update_role" class="block text-gray-700">Role</label>
                <select id="update_role" name="role" class="w-full border-gray-300 rounded-md shadow-sm" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end border-t-2 pt-4">
                <button type="button" onclick="closeUpdateModal()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 mr-2">
                    Annuler
                </button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Sauvegarder
                </button>
            </div>
        </form>
    </div>
</div>
@endcan

  <script>
      // DataTable Initialization
      $('#users-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("users.index") }}',
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'roles', name: 'roles' }, // This now displays real role names
                { data: 'actions', name: 'actions', orderable: false, searchable: false },
            ]
        });
  
      // Modal Functions
      function openModal() {
          document.getElementById('userModal').classList.remove('hidden');
      }
  
      function closeModal() {
          document.getElementById('userModal').classList.add('hidden');
      }

    //   update user 
    function openUpdateModal(userId) {
        fetch(`/users/${userId}/edit`)
            .then(response => response.json())
            .then(data => {
                // Populate form fields with user data
                document.getElementById('update_name').value = data.name;
                document.getElementById('update_email').value = data.email;
                document.getElementById('update_role').value = data.roles[0]?.id || '';
                document.getElementById('update_password').value = '';

                // Set form action URL dynamically
                document.getElementById('updateUserForm').action = `/users/${userId}`;

                // Show the modal
                document.getElementById('updateUserModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error fetching user data:', error);
            });
    }

    function closeUpdateModal() {
        document.getElementById('updateUserModal').classList.add('hidden');
    }

  </script>
  


@endsection