@extends('layouts.app')

@section('content')

<style>
    .date-cont{
        display: flex;
        justify-content: space-between;
    }
</style>

<div class="container mx-auto px-60 py-6">
    <h1 class="text-2xl font-bold mb-6">Ajouter un nouveau produit</h1>
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 bg-white p-6 rounded shadow">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Nom du produit</label>
            <input type="text" id="name" name="name" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                   required>
        </div>
        
        <div>
            <label for="type" class="block text-sm font-medium text-gray-700">Type du produit</label>
            <input type="text" id="type" name="type" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                   required>
        </div>
        
        <div>
            <label for="category" class="block text-sm font-medium text-gray-700">Catégorie</label>
            <input type="text" id="category" name="category" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                   required>
        </div>
        
        <div>
            <label for="unit_price" class="block text-sm font-medium text-gray-700">Prix unitaire</label>
            <input type="number" step="0.01" id="unit_price" name="unit_price" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                   required>
        </div>
        
        <div>
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantité</label>
            <input type="number" id="quantity" name="quantity" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                   required>
        </div>
        
        <div>
            <label for="color" class="block text-sm font-medium text-gray-700">Couleur</label>
            <input type="text" id="color" name="color" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                   required>
        </div>
        <div class="date-cont">

            <div class="w-96 bg-gray-0">
                <label for="inventory_date" class="block text-sm font-medium text-gray-700">Date inventaire</label>
                <input type="date" id="inventory_date" name="inventory_date" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                       required>
            </div>
            
            <div class="w-96 bg-gray-00">
                <label for="update_date" class="block text-sm font-medium text-gray-700">Date mise à jour</label>
                <input type="date" id="update_date" name="update_date" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                       required>
            </div>
        </div>
        
        <div>
            <label for="product_code" class="block text-sm font-medium text-gray-700">Code du produit</label>
            <input type="text" id="product_code" name="product_code" 
                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                   required>
        </div>
        
        <div>
            <label for="image" class="block text-sm font-medium text-gray-700">Image</label>
            <input type="file" id="image" name="image" 
                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-300 file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100">
        </div>
        
        <div class="flex justify-end">
            <button type="submit" 
                    class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Valider
            </button>
        </div>
    </form>
</div>
@endsection
