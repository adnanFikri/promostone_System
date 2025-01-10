@extends('layouts.app')

@section('title', 'Permissions')

@section('content')
<section id="permissions" class="container mx-auto py-6">
    {{-- Show Permissions --}}
    @can('show permissions')
    <div class="bg-white shadow-md rounded-lg p-4">
        <div class="flex items-center justify-between mb-6">
            <h5 class="text-xl font-semibold">Permissions</h5>
            <button type="button" data-bs-toggle="modal" data-bs-target="#createModal" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                <i class='bx bx-plus-circle'></i>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table id="myTable" class="min-w-full table-auto bg-white border-collapse">
                <thead>
                    <tr class="border-b">
                        <th class="px-4 py-2 text-left">#ID</th>
                        <th class="px-4 py-2 text-left">Nom</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $permission->id }}</td>
                        <td class="px-4 py-2">{{ $permission->name }}</td>
                        <td class="px-4 py-2">
                            <div class="relative inline-block text-left">
                                <button type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" data-bs-toggle="dropdown">
                                    <i class='bx bx-menu'></i>
                                </button>
                                <ul class="dropdown-menu absolute right-0 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                    <li>
                                        {{-- Edit --}}
                                        @can('edit permission')
                                        <a href="{{ route('permissions.edit', $permission) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class='bx bx-edit-alt'></i> Edit
                                        </a>
                                        @endcan
                                    </li>
                                    <li>
                                        {{-- Delete --}}
                                        @can('delete permission')
                                        <form action="{{ route('permissions.destroy', $permission) }}" method="post" class="block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                                <i class='bx bx-trash'></i> Delete
                                            </button>
                                        </form>
                                        @endcan
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endcan

    {{-- Create Permission Modal --}}
    @can('create permission')
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Create a permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @include('admin.permissions.create')
                </div>
            </div>
        </div>
    </div>
    @endcan

    {{-- Edit Permission Modal --}}
    @can('edit permission')
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit a permission</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="editModalContent"></div>
                </div>
            </div>
        </div>
    </div>
    @endcan
</section>
@endsection

@section('scripts')
<script>
    // Edit Permission
    $(document).ready(function () {
        $('.edit-permission').on('click', function (e) {
            e.preventDefault();

            var permissionId = $(this).data('permission-id');

            $.ajax({
                url: '/admin/permissions/' + permissionId + '/edit',
                type: 'GET',
                success: function (data) {
                    $('#editModalContent').html(data);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        });
    });
</script>
@endsection
