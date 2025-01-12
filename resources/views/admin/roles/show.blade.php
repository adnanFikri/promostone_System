@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<style>
    #container-button-back{
        display: flex;
        justify-content: flex-end;
        transition: 0.3s;
    }
</style>
<div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <div id="container-button-back">
                <a href="/roles" class="bg-blue-400 rounded hover:bg-blue-700 hover:text-white  p-2 text-end">Back To Roles</a>
            </div>
            <h5 class="text-xl font-semibold mb-6">Permissions</h5> 
            <div class="bg-gray-50 rounded-lg shadow p-6">
                <form action="{{ route('addPermissions', $role->id) }}" method="post">
                    @csrf
                    @foreach ($groups as $group)
                        <h5 class="text-lg font-bold mb-4">{{ ucfirst($group->name) }}'s Permissions</h5>
                        <div class="flex flex-wrap gap-4">
                            <input type="hidden" name="role_id" value="{{ $role->id }}">
                            @foreach ($group->permissions as $permission)
                                <div class="flex items-center space-x-2">
                                    <input 
                                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" 
                                        type="checkbox" 
                                        name="permissions[{{ $group->name }}][]" 
                                        value="{{ $permission->name }}" 
                                        {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                    <label class="text-gray-700">{{ $permission->name }}</label>
                                </div>
                            @endforeach
                        </div>
                        <hr class="my-6 border-gray-300">
                    @endforeach
                    
                    <div class="text-center">
                        <button type="submit" 
                                class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
