@extends('layouts.app')

@section('content')

<style>
    .test{
        border-radius: 3px;
    }
    #mesure{
        /* border: 1px solid gray; */
    }
    #code_client{
        width: 500px;
        /* font-size: 20px; */
        /* padding: 5px; */
        font-size: 20px ;
    }
    .selec2{
        height: 200px !important;
        border-color:  gray !important;
        margin-top: 5px;
    }
    .select2-selection{
        height: 38px !important;
        background-color: rgb(249 250 251) !important;
        border-radius: 20px;
        padding: 5px;
        border: 1px solid gray !important;
    }
    .select2-selection__placeholder{
        /* height: 30px !important; */
        /* color: red !important; */
        color: black !important;
        padding-top: 20px !important;
    }

    fieldset.border-none {
        border: none;
    }

    legend {
        font-size: 1.25rem;
        font-weight: 600;
        color: #4B5563; /* Custom color for legend text */
    }
    #el03 {background:url(/i/icon-info.gif) no-repeat 100% 50%}

    /* Increase dropdown height */
    .custom-select-dropdown .select2-results__options {
        max-height: 300px !important; /* Increase dropdown height */
        overflow-y: auto !important;
    }

    /* Ensure dropdown is visible */
    .select2-container--default .select2-results__option {
        padding: 10px;
        font-size: 13px; /* Adjust text size */
    }

    .select2-container .select2-results__options {
        min-height: 350px !important;
        overflow-y: auto !important;
    }


    /* button ajoute client */
    .btnA svg{
            width: 50px;
            height: 40px;
            background-color:#909090;
            float: left;
            /* margin-right: 3px; */
            /* margin: 3px; */
            transition: .3s;
            border-radius: 10%;
        }
        .btnA svg:hover{
            background-color:#ffffff;
            color: #909090;
        }
    
</style>

@can("create sales")

    <div class="container mx-auto md:px-36 py-12">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center uppercase">Créer une Nouvelle Vente</h1>
            <form action="{{ route('sales.store') }}" method="POST" class="space-y-6">
                @csrf
                <!-- Client Selection -->
                <div>
                    <label for="client" class="block text-sm font-medium text-gray-700">Choisir un Client</label>
                    <select name="code_client" id="code_client" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                        <!-- Options will be populated via Select2 -->
                    </select>

                    @can('create clients')
                        <a href="#" onclick="openModal()" class="btnA float-right">
                            <svg class="w-7 h-6 text-gray-200 dark:text-white " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    @endcan
                </div>

                <!-- Products Container -->
                <div id="products-container" class="space-y-4 ">
                    <div class="product-item relative overflow-hidden bg-gray-100 flex flex-wrap gap-4 items-center border border-gray-300 p-4 rounded-md">
                        <div class="w-full md:w-3/4">
                            <label for="produit" class="block text-sm font-medium text-gray-700 mb-1">Produit</label>
                            <select name="products[0][produit]" id="product_select" class="product-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <!-- Options will be loaded dynamically -->
                            </select>
                            
                            {{-- <select name="products[0][produit]" class="product-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="" disabled selected>~~ choisir le produit ~~</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->name }}"  data-unit-price="{{ $product->unit_price }}" >{{ $product->name }} -- {{$product->quantity}} Disponible ({{ $product->unit_price }})</option>
                                @endforeach
                            </select> --}}
                        </div>
                        <div class="w-full md:w-1/5 ">
                            <label for="prix_unitaire" class="block text-sm font-medium text-gray-700">Prix Unitaire</label>
                            <input type="number" step="0.01" name="products[0][prix_unitaire]" class="prix-unitaire mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
    
                        <div id="mesures-container-0" class="w-full w-4/4 flex flex-wrap gap-4 items-center">
                            <div id="mesure-item" class="mesure-item w-full w-4/4 flex flex-wrap gap-4 items-center">

                                <div class="w-full md:w-1/5">
                                    <label for="longueur" class="block text-sm font-medium text-gray-700">Longueur</label>
                                    <input type="number" step="0.001" name="products[0][mesures][0][longueur]" class="longueur mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                </div>
            
                                <div class="w-full md:w-1/5">
                                    <label for="largeur" class="block text-sm font-medium text-gray-700">Largeur</label>
                                    <input type="number" step="0.001" name="products[0][mesures][0][largeur]" class="largeur mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                </div>
            
                                <div class="w-full md:w-1/5">
                                    <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité</label>
                                    <input type="number" step="0.001" name="products[0][mesures][0][quantite]" class="quantite mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                </div>
                                
                                <div class="w-full md:w-1/5">
                                    <label for="mode" class="block text-sm font-medium text-gray-700">Mode</label>
                                    <select name="products[0][mesures][0][mode]" id="mode" class="mode mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="M2">M2</option>
                                        <option value="ML">ML</option>
                                        <option value="Unité">Unité</option>
                                    </select>
                                </div>
                                <button type="button" onclick="removeMesure(this)" class=" -mb-6 bg-red-600 text-white  rounded-full p-1 shadow-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
    
                        <button type="button" onclick="removeProduct(this)" id="btn-remove" style="margin-right:-3px; border-radius: 28px 0px 0px 1px; transition:.3s; " class="absolute bottom-2 right-0 -mb-4 bg-red-600 text-white p-4 rounde shadow-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                            <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                            </svg>
                        </button>

                        <button type="button" onclick="addMesure(0)" class="block w-full md:w-auto text-center bg-gray-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Ajouter un mesure
                        </button>
                    </div>
                </div>

                <!-- Add Product Button -->
                <button type="button" onclick="addProduct()" class="block w-full md:w-auto text-center bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Ajouter un produit
                </button>

                <!-- Services Container -->
                <div id="services-container" class="space-y-4 hidden">
                </div>

                    <!-- Add Service Button -->
                    <button type="button" onclick="addService()" class="block w-full md:w-auto text-center bg-purple-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        Ajouter un service
                    </button>

                    
                <div class="w-full">
                    <label for="total" class="block text-lg font-bold text-gray-500">Total</label>
                    <input type="number" id="total" class="mt-1 block w-36 text-xl font-bold text-red-400  border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" readonly>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="block w-full text-center bg-green-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                    Valider
                </button>
            </form>
        </div>
    </div>
@endcan

    {{-- modal for create new user --}}
    <div id="clientModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h3 class="text-xl font-semibold mb-4">New Client</h3>
            <form id="clientForm" method="POST" action="{{ route('clients.store') }}">
                @csrf
                <div class="mb-4">
                    <label for="code_clientFormCreate" class="block text-gray-700 font-medium">Client Code</label>
                    <input type="text" id="code_clientFormCreate" name="code_client"  readonly 
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

<script>
$(document).ready(function () {
    $('#code_client').select2({
        placeholder: "Choisir le client",
        ajax: {
            url: '{{ route('reglements.search') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.map(function (client) {
                        return {
                            id: client.code_client,
                            text: client.code_client + " - " + client.name
                        };
                    })
                };
            },
            cache: true
        },
        dropdownParent: $("#code_client").parent(), // Fix for dropdown styling
        templateResult: function (data) {
            if (!data.id) {
                return $('<span>' + data.text + '</span>'); 
            }
            return $('<div style="padding: 0; font-size: 12px;">' + data.text + '</div>');
        },
        templateSelection: function (data) {
            return data.text || "Choisir le client";
        }
    });
});



        
        // search products select 
    $(document).ready(function () {
        $('#product_select').select2({
            placeholder: "~~ choisir le produit ~~",
                width: '100%',
            ajax: {
                url: '{{ route('products.search') }}', // Adjust this route
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data.map(function (product) {
                            return {
                                id: product.name,
                                text: product.name + " -- " + product.quantity + " Disponible (" + product.unit_price + " MAD)",
                                unit_price: product.unit_price, // Include unit price in the response
                                quantity: product.quantity,
                            };
                        })
                    };
                },
                cache: true
            },
            templateResult: function (product) {
                if (!product.id) {
                    return product.text;
                }
                return $('<option>', {
                    value: product.id,
                    text: product.text,
                    'data-unit-price': product.unit_price // Set data attribute
                });
            }
        });

        
        
        // Handle selection and set data-unit-price
        $('#product_select').on('select2:select', function (e) {
            const selectedOption = e.params.data;
            const productItem = $(this).closest('.product-item');
            const unitPriceInput = productItem.find('.prix-unitaire');

            if (unitPriceInput.length) {
                unitPriceInput.val(selectedOption.unit_price || '');
            }

            // Show SweetAlert if quantity is 5 or less
            if (selectedOption.quantity <= 50) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock Faible!',
                    text: `Seulement ${selectedOption.quantity} M2 disponibles.`,
                    confirmButtonColor: '#d33'
                });
            }

            calculateTotal();
        });
    });

    let productIndex = 1;
    function addProduct() {
    const container = document.getElementById('products-container');

    const newProduct = `
        <div class="product-item relative overflow-hidden bg-gray-100 flex flex-wrap gap-4 items-center border border-gray-300 p-4 rounded-md">
            <div class="w-full md:w-3/4">
                <label for="produit" class="block text-sm font-medium text-gray-700">Produit</label>
                <select id="product_select_${productIndex}" name="products[${productIndex}][produit]" class="product-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    <!-- Options will be loaded dynamically -->
                </select>
            </div>
            <div class="w-full md:w-1/5 ">
                <label for="prix_unitaire" class="block text-sm font-medium text-gray-700">Prix Unitaire</label>
                <input type="number" step="0.01" name="products[${productIndex}][prix_unitaire]" class="prix-unitaire mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div id="mesures-container-${productIndex}" class="w-full w-4/4 flex flex-wrap gap-4 items-center">
                <div id="mesure-item" class="mesure-item w-full w-4/4 flex flex-wrap gap-4 items-center">
                    <div class="w-full md:w-1/5">
                        <label for="longueur" class="block text-sm font-medium text-gray-700">Longueur</label>
                        <input type="number" step="0.001" name="products[${productIndex}][mesures][0][longueur]" class="longueur mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    <div class="w-full md:w-1/5">
                        <label for="largeur" class="block text-sm font-medium text-gray-700">Largeur</label>
                        <input type="number" step="0.001" name="products[${productIndex}][mesures][0][largeur]" class="largeur mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    <div class="w-full md:w-1/5">
                        <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité</label>
                        <input type="number" step="0.001" name="products[${productIndex}][mesures][0][quantite]" class="quantite mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    <div class="w-full md:w-1/5">
                        <label for="mode" class="block text-sm font-medium text-gray-700">Mode</label>
                        <select name="products[${productIndex}][mesures][0][mode]" class="mode mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="M2">M2</option>
                            <option value="ML">ML</option>
                            <option value="Unité">Unité</option>
                        </select>
                    </div>
                    <button type="button" onclick="removeMesure(this)" class=" -mb-6 bg-red-600 text-white rounded-full p-1 shadow-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
            <button type="button" onclick="removeProduct(this)" id="btn-remove" style="margin-right:-3px; border-radius: 28px 0px 0px 1px; transition:.3s; " class="absolute bottom-2 right-0 -mb-4 bg-red-600 text-white p-4 rounde shadow-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0="clip-rule="evenodd"/>
                </svg>
            </button>
            <button type="button" onclick="addMesure(${productIndex})" class="block w-full md:w-auto text-center bg-gray-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                Ajouter un mesure
            </button>
        </div>`;

    container.insertAdjacentHTML('beforeend', newProduct);
    productIndex++;

    // Initialize Select2 for the newly added product select input
    $(`#product_select_${productIndex - 1}`).select2({
        placeholder: "~~ choisir le produit ~~",
        width: '100%',
        ajax: {
            url: '{{ route('products.search') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data.map(function (product) {
                        return {
                            id: product.name,
                            text: product.name + " -- " + product.quantity + " Disponible (" + product.unit_price + " MAD)",
                            unit_price: product.unit_price,
                            quantity: product.quantity,
                        };
                    })
                };
            },
            cache: true
        },
        templateResult: function (product) {
            if (!product.id) {
                return product.text;
            }
            return $('<option>', {
                value: product.id,
                text: product.text,
                'data-unit-price': product.unit_price
            });
        }
    });

    // Add the event listener to update the 'prix_unitaire' input when a product is selected
    $(`#product_select_${productIndex - 1}`).on('select2:select', function (e) {
        const selectedOption = e.params.data;
        const productItem = $(this).closest('.product-item');
        const unitPriceInput = productItem.find('.prix-unitaire');

        if (unitPriceInput.length) {
            unitPriceInput.val(selectedOption.unit_price || '');
        }

        // Show SweetAlert if quantity is 5 or less
        if (selectedOption.quantity <= 10) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock Faible!',
                text: `Seulement ${selectedOption.quantity} unités disponibles.`,
                confirmButtonColor: '#d33'
            });
        }

        calculateTotal();
    });
}



    function calculateTotal() {
        const products = document.querySelectorAll(".product-item");
        let grandTotal = 0;

        products.forEach((product) => {
            const measures = product.querySelectorAll(".mesure-item");
            let productTotal = 0;

            measures.forEach((measure) => {
                const longueur = parseFloat(measure.querySelector(".longueur")?.value || 0);
                const largeur = parseFloat(measure.querySelector(".largeur")?.value || 0);
                const quantite = parseFloat(measure.querySelector(".quantite")?.value || 0);
                const mode = measure.querySelector(".mode")?.value;
                const prixUnitaire = parseFloat(product.querySelector(".prix-unitaire")?.value || 0);



                let mesureTotal = 0;

                if (mode === "M2") {
                    mesureTotal = longueur * largeur * quantite * prixUnitaire;
                } else if (mode === "ML") {
                    mesureTotal = (longueur + largeur) * quantite * prixUnitaire;
                } else if (mode === "Unité") {
                    mesureTotal = quantite * prixUnitaire;
                }

                productTotal += mesureTotal;
            });

            grandTotal += productTotal;
        });

        const services = document.querySelectorAll(".service-item");
        services.forEach((service) => {
            const montant = parseFloat(service.querySelector(".montant")?.value || 0);
            const quantite = parseFloat(service.querySelector(".quantite")?.value || 0);
            grandTotal += montant * quantite;
        });
        
        document.getElementById("total").value = grandTotal.toFixed(2);
    }

    document.addEventListener('input', calculateTotal);




        function removeProduct(button) {
            const productItem = button.closest('.product-item');
            productItem.remove();
            calculateTotal();
        }
        function addMesure(productIndex) {
            // const mesuresContainer = document.getElementById(`mesures-container-${productIndex}`);
            const container = document.getElementById(`mesures-container-${productIndex}`);
            const mesureIndex = container.querySelectorAll('.mesure-item').length;
            const newMesure = `
                <div  class="mesure-item w-full w-4/4 flex flex-wrap gap-4 items-center">
                    <div class="w-full md:w-1/5">
                        <input type="number" step="0.001" name="products[${productIndex}][mesures][${mesureIndex}][longueur]" class="longueur mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    <div class="w-full md:w-1/5">
                        <input type="number" step="0.001" name="products[${productIndex}][mesures][${mesureIndex}][largeur]" class="largeur mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    <div class="w-full md:w-1/5">
                        <input type="number" step="0.001" name="products[${productIndex}][mesures][${mesureIndex}][quantite]" class="quantite mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    <div class="w-full md:w-1/5">
                        <select name="products[${productIndex}][mesures][${mesureIndex}][mode]" class="mode mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="M2">M2</option>
                            <option value="ML">ML</option>
                            <option value="Unité">Unité</option>
                        </select>
                    </div>
                    <button type="button" onclick="removeMesure(this)" class="-mb- bg-red-600 text-white rounded-full p-1 shadow-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', newMesure);
        }

        function removeMesure(button) {
            const mesureItem = button.closest('.mesure-item');
            mesureItem.remove();
            calculateTotal();
        }


        // --------------- service ----------
        let serviceIndex = 1;

        function addService() {
            const serviceContainer = document.getElementById('services-container');

            if (serviceContainer.classList.contains('hidden')) {
                serviceContainer.classList.remove('hidden');
            }

            const serviceItem = `
                <div class="service-item relative overflow-hidden bg-gray-100 flex flex-wrap gap-4 items-center border border-gray-300 p-4 rounded-md">
                    <div class="w-full md:w-2/4">
                        <label for="service-type" class="block text-sm font-medium text-gray-700">Type de Service</label>
                        <select name="services[${serviceIndex}][type]" class="service-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <option value="" disabled selected>~~ Choisir le type de service ~~</option>
                            <option value="Finition">Finition</option>
                            <option value="Transport">Transport</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/5">
                        <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité</label>
                        <input type="number" step="0.01" name="services[${serviceIndex}][quantite]" class="quantite mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                    <div class="w-full md:w-1/5">
                        <label for="montant" class="block text-sm font-medium text-gray-700">Montant</label>
                        <input type="number" step="0.01" name="services[${serviceIndex}][montant]" class=" montant mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    </div>
                        
                        <input type="text" step="0.01"  value="service" name="services[${serviceIndex}][mode]" class="hidden mode mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                    <button type="button" onclick="removeService(this)" style="margin-right:-3px; border-radius: 28px 0px 0px 1px; transition:.3s;" class="absolute bottom-2 right-0 -mb-4 bg-red-600 text-white p-4 rounded-full shadow-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            `;
            serviceContainer.insertAdjacentHTML('beforeend', serviceItem);
            serviceIndex++;
        }

        function removeService(button) {
            const serviceItem = button.closest('.service-item');
            serviceItem.remove();
        }

        // CLIENTS DETAILS MDOAL FOR CREATE NEW CLIENT 
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
            document.getElementById('code_clientFormCreate').value = data.code_client; // Populate the input
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
             // Add the new client to Select2
             let $clientSelect = $('#code_client');
             console.log("hejejfgejg");
             

            // Create a new option and append it to Select2
            let newOption = new Option(
                data.client.code_client + " - " + data.client.name, 
                data.client.code_client, 
                true, // Marks as selected
                true  // Ensures it is selected
            );
            console.log(clientSelect);
            

            $clientSelect.append(newOption).trigger('change'); // Append & trigger chang
        })
        .catch(error => {
            console.error('Error:', error);
            // Swal.fire('Error!', 'Échec de la création du client', 'error');
            Swal.fire('Success!', 'Client créé avec succès!', 'success');
            closeModal(); // Close the modal
        });
    });
</script>
@endsection
