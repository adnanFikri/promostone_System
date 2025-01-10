@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<section id="Roles" class="container mx-auto px-12 py-6">
    {{-- Show roles --}}
    @can('show roles')
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h5 class="text-2xl font-semibold text-gray-800">Roles</h5>
            <button type="button" class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700 transition duration-300" onclick="openCreateModal()">
                <span class="text-lg">+</span> Create Role
            </button>
        </div>

        <div class="overflow-x-auto ">
            <table id="myTable" class="min-w-full table-auto text-sm text-left text-gray-600 mb-32">
                <thead>
                    <tr class="border-b">
                        <th class="px-4 py-3">#ID</th>
                        <th class="px-4 py-3">Nom</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                    <tr class="border-b">
                        <td class="px-4 py-3">{{ $role->id }}</td>
                        <td class="px-4 py-3">{{ $role->name }}</td>
                        <td class="px-4 py-3">
                            <div class="relative inline-block text-left">
                                <button 
                                    type="button" 
                                    onclick="toggleDropdown(this)" 
                                    class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-gray-100 px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-200 active:bg-gray-600 focus:bg-gray-400 ">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12.25V1m0 11.25a2.25 2.25 0 0 0 0 4.5m0-4.5a2.25 2.25 0 0 1 0 4.5M4 19v-2.25m6-13.5V1m0 2.25a2.25 2.25 0 0 0 0 4.5m0-4.5a2.25 2.25 0 0 1 0 4.5M10 19V7.75m6 4.5V1m0 11.25a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5ZM16 19v-2"/>
                                    </svg>
                                    {{-- <i class='bx bx-menu'>:</i> --}}
                                </button>
                            
                                <div 
                                    class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black/5 hidden"
                                    role="menu"
                                >
                                    <div class="py-1">
                                        {{-- Show --}}
                                        @can('show role')
                                            <a class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" href="{{ route('roles.show', $role) }}">
                                                <i class='bx bx-show'></i> Show
                                            </a>
                                        @endcan
                                    </div>
                            
                                    <div class="py-1">
                                        {{-- Edit --}}
                                        @can('edit role')
                                            <a 
                                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 edit-role" 
                                                href="#" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal" 
                                                data-role-id="{{ $role->id }}"
                                                onclick="openEditModal({{ $role->id }})">
                                                <i class='bx bx-edit-alt'></i> Edit
                                            </a>
                                        @endcan
                                    </div>
                            
                                    <div class="py-1">
                                        {{-- Delete --}}
                                        @can('delete role')
                                            <form action="{{ route('roles.destroy', $role) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 delete-role">
                                                    <i class='bx bx-trash'></i> Delete
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endcan
</section>

{{-- Create Role Modal --}}
<div id="createRoleModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-md w-96">
        <h2 class="text-xl font-semibold mb-4">Create Role</h2>
        <form id="createRoleForm" method="POST" action="{{ route('roles.store') }}">
            @csrf
            <div class="mb-4">
                <label for="roleName" class="block text-sm font-medium text-gray-700">Role Name</label>
                <input type="text" id="roleName" name="name" class="w-full mt-1 p-2 border border-gray-300 rounded" required>
            </div>

            <div class="flex justify-end">
                <button type="button" class="mr-2 bg-gray-500 text-white px-4 py-2 rounded" onclick="closeCreateModal()">Cancel</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Role Modal -->
<div id="editRoleModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-500 bg-opacity-50">
    <div class="bg-white p-6 rounded-lg shadow-md w-96">
        <h2 class="text-xl font-semibold mb-4">Edit Role</h2>

        <form id="editRoleForm" method="post">
            @csrf
            @method('PUT') <!-- Use PUT for edit request -->
            <input type="hidden" id="roleId">

            <div class="mb-4">
                <label for="roleName" class="block text-sm font-medium text-gray-700">Role Name</label>
                <input type="text" id="roleName" name="name" class="w-full mt-1 p-2 border border-gray-300 rounded">
            </div>

            <div class="flex justify-end">
                <button type="button" class="mr-2 bg-gray-500 text-white px-4 py-2 rounded" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Changes</button>
            </div>
        </form>
    </div>
</div>


<script>

function toggleDropdown(button) {
    const dropdownMenu = button.nextElementSibling;
    dropdownMenu.classList.toggle('hidden');
    
    // Close the dropdown when clicking outside
    document.addEventListener('click', (event) => {
        if (!button.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });
}

    
    // Show Create Modal
    function openCreateModal() {
        document.getElementById('createRoleModal').classList.remove('hidden');
    }

    // Hide Create Modal
    function closeCreateModal() {
        document.getElementById('createRoleModal').classList.add('hidden');
    }

    // Show Edit Modal with pre-filled data
    function openEditModal(roleId) {
        // Fetch role data by role ID
        document.getElementById('editRoleModal').classList.remove('hidden');
        fetch(`/roles/${roleId}`)
            .then(response => response.json())
            .then(data => {
                // Set the form inputs with the fetched data
                document.getElementById('roleId').value = data.id;
                document.getElementById('roleName').value = data.name;

                // Show the modal
                document.getElementById('editRoleModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error fetching role data:', error);
                alert('Failed to fetch role data');
            });
    }

    function closeEditModal() {
        // Close the modal
        document.getElementById('editRoleModal').classList.add('hidden');
    }

</script>
@endsection

