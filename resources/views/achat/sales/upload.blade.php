@extends('layouts.app')

@section('content')
@can("view sales upload")
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Upload File') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-12">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg px-5 py-2">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-center mb-4 text-3xl font-bold">importer le fichier Excel des ventes</h2>

                    <form action="{{ route('sales.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-7">
                            <label for="file" class="block text-xl font-medium text-gray-900 dark:text-white mb-2">Choisir le fichier Excel</label>
                            <input type="file" class="block w-50 text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" id="file" name="file" required>
                            @error('file')
                                <small class="text-red-600">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-7">
                            <label for="action" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Action</label>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input type="radio" id="add" name="action" value="add" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" required checked>
                                    <label for="add" class="ml-2 text-lg text-gray-900 dark:text-gray-300">Ajouter</label>
                                </div>
                                <div class="flex items-center">
                                    <input type="radio" id="replace" name="action" value="replace" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800">
                                    <label for="replace" class="ml-2 text-lg text-gray-900 dark:text-gray-300">Remplacer</label>
                                </div>
                            </div>
                            @error('action')
                                <small class="text-red-600">{{ $message }}</small>
                            @enderror
                        </div>

                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Importer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endcan
@endsection