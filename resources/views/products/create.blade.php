@extends('layouts.app')

@section('content')

<style>
#headerTitle{
    color: #4c4949;
    text-transform: uppercase;
}

select {
        appearance: none;
        display: block;
        width: 100%;
        background-color: #f0f0f0; /* Light gray */
        border: 1px solid #ccc;
        border-radius: 8px;
        padding: 10px;
        font-size: 16px;
        color: #000;
        outline: none;
    }

    /* Option hover with light background color */
    option:hover {
        background-color: rgba(0, 0, 0, 0.1); /* Light gray with opacity */
    }

    /* Option styling */
    option {
        color: black; /* Default option text color */
    }

    /* #blnc{
        text-shadow: red 1px 2px;
    } */

</style>
@can("create products")
<div class="max-w-4xl mx-auto py-8">

    <h1 class="text-2xl font-bold mb-6 text-center" id="headerTitle">Ajouter un nouveau produit</h1>
    @if($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg px-8 py-6 space-y-6">
        @csrf
        <!-- Product Name and Type -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label for="name" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Nom du produit</label>
                <input type="text" id="name" name="name" placeholder="Produit"
                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" id="grid-first-name" 
                    required>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label for="type" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Type du produit</label>
                <input type="text" id="type" name="type" placeholder="Type du produit"
                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" 
                    required>
            </div>
        </div>

        <!-- Category and Unit Price -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label for="edit-category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                    <select id="edit-category" name="category" class="appearance-none block w-full bg-gray-200 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                        <option value="DEBITGAE">DEBITGAE</option>
                        <option value="FINITION">FINITION</option>
                        <option value="GRANIT">GRANIT</option>
                        <option value="MARBRE">MARBRE</option>
                        <option value="PIERRE">PIERRE</option>
                        <option value="TRAVERTAIN">TRAVERTAIN</option>
                        <option value="ASCALE">ASCALE</option>
                        <option value="NEW CREMA">NEW CREMA</option>
                        <option value="MARBRE LOCAL">MARBRE LOCAL</option>
                    </select>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label for="unit_price" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Prix unitaire</label>
                <input type="number" step="0.01" id="unit_price" name="unit_price" placeholder="Prix unitaire"
                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" 
                    required>
            </div>
        </div>

        <!-- Quantity and Color -->
        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label for="quantity" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Quantité</label>
                <input type="number" id="quantity" name="quantity" placeholder="Quantité"
                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" 
                    required>
            </div>
            <!-- Color Picker -->
            <div class="w-full md:w-1/2 px-3">
                <label for="color" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Couleur
                </label>
                <select id="color" name="color" 
                    class="appearance-none block w-full bg-gray-200 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                    <option value="" disabled selected>Choisissez une couleur</option>
                    <option value="Rouge" style="background-color: red; color: white;">Rouge</option>
                    <option value="Bleu" style="background-color: blue; color: white;">Bleu</option>
                    <option value="Vert" style="background-color: green; color: white;">Vert</option>
                    <option value="Jaune" style="background-color: yellow; color: black;">Jaune</option>
                    <option value="Beige" style="background-color: beige; color: black;">Beige</option>
                    <option value="Noir" style="background-color: black; color: white;">Noir</option>
                    <option value="Marron" style="background-color: brown; color: white;">Marron</option>
                    <option value="Blanc" id="blnc" style="background-color: white; color: black;">Blanc</option>
                    <option value="Orange" style="background-color: orange; color: black;">Orange</option>
                    <option value="Violet" style="background-color: purple; color: white;">Violet</option>
                    <option value="Rose" style="background-color: pink; color: black;">Rose</option>
                    <option value="Gris" style="background-color: gray; color: white;">Gris</option>
                </select>
                {{-- <span class="block mt-2 text-sm text-gray-600">Choisissez une couleur par nom</span> --}}
            </div>
        </div>

        <div class="flex flex-wrap -mx-3 mb-6">
            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                <label for="inventory_date" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Date et heure d'inventaire
                </label>
                <input type="datetime-local" id="inventory_date" name="inventory_date" 
                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" 
                    required>
            </div>
            <div class="w-full md:w-1/2 px-3">
                <label for="update_date" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    Date et heure de mise à jour
                </label>
                <input type="datetime-local" id="update_date" name="update_date" 
                    class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" 
                    required>
            </div>
        </div>

        <!-- Product Code -->
        <div class="mb-6">
            <label for="product_code" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Code du produit</label>
            <input type="text" id="product_code" name="product_code" 
                class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" 
                value="{{ old('product_code', $newProductCode) }}" readonly>
        </div>

        <!-- Image -->
        <div class="mb-6">
            <label for="image" class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">Image</label>
            <input type="file" id="image" name="image" 
                class="mt-1 block w-full text-sm text-gray-500 file:py-2 file:px-4 file:rounded-md file:border file:border-gray-300 file:text-gray-700 file:bg-gray-50 hover:file:bg-gray-100"
                
                >
        </div>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit" 
                class="px-6 py-2 bg-blue-500 text-white font-medium rounded-lg shadow-sm hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Valider
            </button>
        </div>
    </form>
</div>

@endcan

<script>
     const select = document.getElementById('color');

    select.addEventListener('change', function() {
        // Get the selected option
        const selectedOption = select.options[select.selectedIndex];
        console.log(selectedOption);
        

        // Apply the text color of the selected option to the select element
        select.style.color = selectedOption.style.backgroundColor;
        console.log(selectedOption.style.backgroundColor);
        
        if(selectedOption.style.backgroundColor === 'beige' || selectedOption.style.backgroundColor === 'white'){
            select.style.textShadow = '1px 1px 5px black';
        }else{
            select.style.textShadow = '';
        }
    });
</script>
@endsection
