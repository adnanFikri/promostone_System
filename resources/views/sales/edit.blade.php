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
        height: 100px !important;

    }
    .select2-selection{
        height: 39px !important;
        background-color: rgb(249 250 251) !important;
        border-radius: 20px;
        padding: 5px;
    }
    .select2-selection__rendered{
        height: 200% !important;
    }
    .select2-selection__placeholder{
        height: 30px !important;
        /* color: red !important; */
        color: black !important;
        padding-top: 8px !important;
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

    
    
    
</style>

@can("create sales")

<div class="container mx-auto md:px-36 py-12">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-4 text-center uppercase">Modifier la Vente N° <span class="text-black  rounded border bg-gray-200 px-1 shadow ">{{ $saleLines[0]->no_bl }} </span></h1>
        <div class=" relative overflow-hidden bg-gray-100 flex flex-wrap gap-4 items-center border border-gray-300 p-4 rounded-md">
            <span> <b class="text-lg">Client</b> : <span class="text-black rounded text-lg p-1 font-bold border bg-gray-200 px-1 shadow ">{{ $client->name }} ({{ $client->code_client }})</span></span>
        </div>
        <form action="{{ route('sales.update', $saleLines[0]->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Products Container (Loop through sale lines) -->
            <div id="products-container"  class="space-y-4">
                @foreach($saleLines as $index => $saleLine)
                    @if($saleLine->mode == 'service') 
                    {{-- <h> --}}
                        <!-- Service item -->
                        <div class="service-item relative overflow-hidden bg-gray-100 flex flex-wrap gap-4 items-center border border-gray-300 p-4 rounded-md">
                            <div class="w-full md:w-2/4">
                                <label for="service-type" class="block text-sm font-medium text-gray-700">Type de Service</label>
                                <select name="services[{{ $index }}][type]" class="service-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="" disabled selected>~~ Choisir le type de service ~~</option>
                                    <option value="Finition" {{ $saleLine->produit == 'Finition' ? 'selected' : '' }}>Finition</option>
                                    <option value="Transport" {{ $saleLine->produit == 'Transport' ? 'selected' : '' }}>Transport</option>
                                </select>
                            </div>
                            <div class="w-full md:w-1/5">
                                <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité</label>
                                <input type="number" step="0.01" name="services[{{ $index }}][quantite]" value="{{ old('services.'.$index.'.quantite', $saleLine->nbr) }}" class="quantite mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                            <div class="w-full md:w-1/5">
                                <label for="montant" class="block text-sm font-medium text-gray-700">Montant</label>
                                <input type="number" step="0.01" name="services[{{ $index }}][montant]" value="{{ old('services.'.$index.'.montant', $saleLine->prix_unitaire) }}" class="montant mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                            <input type="text" value="service" name="services[{{ $index }}][mode]" class="hidden mode mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            <button type="button" onclick="removeService(this)" style="margin-right:-3px; border-radius: 28px 0px 0px 1px; transition:.3s;" class="absolute bottom-2 right-0 -mb-4 bg-red-600 text-white p-4 rounded-full shadow-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <svg class="w-6 h-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    @else
                        <!-- Regular product item (Non-service) -->
                        <div class="product-item relative overflow-hidden bg-gray-100 flex flex-wrap gap-4 items-center border border-gray-300 p-4 rounded-md">
                            <div class="w-full md:w-3/4">
                                <label for="produit" class="block text-sm font-medium text-gray-700">Produit</label>
                                <select name="products[{{ $index }}][produit]" class="product-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                    <option value="" disabled selected>~~ choisir le produit ~~</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->name }}" data-unit-price="{{ $product->unit_price }}" {{ $product->name == $saleLine->produit ? 'selected' : '' }}>
                                            {{ $product->name }} -- {{$product->quantity}} Disponible ({{ $product->unit_price }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full md:w-1/5">
                                <label for="prix_unitaire" class="block text-sm font-medium text-gray-700">Prix Unitaire</label>
                                <input type="number" step="0.01" name="products[{{ $index }}][prix_unitaire]" value="{{ old('products.'.$index.'.prix_unitaire', $saleLine->prix_unitaire) }}" class="prix-unitaire mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                            </div>
                            <div class="w-full w-4/4 flex flex-wrap gap-4 items-center">
                                <div class="w-full md:w-1/5">
                                    <label for="longueur" class="block text-sm font-medium text-gray-700">Longueur</label>
                                    <input type="number" step="0.001" name="products[{{ $index }}][longueur]" value="{{ old('products.'.$index.'.longueur', $saleLine->longueur ?? 0.000) }}" class="longueur mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                </div>
                                <div class="w-full md:w-1/5">
                                    <label for="largeur" class="block text-sm font-medium text-gray-700">Largeur</label>
                                    <input type="number" step="0.001" name="products[{{ $index }}][largeur]" value="{{ old('products.'.$index.'.largeur', $saleLine->largeur ?? 0.000) }}" class="largeur mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                </div>
                                <div class="w-full md:w-1/5">
                                    <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité</label>
                                    <input type="number" step="0.001" name="products[{{ $index }}][quantite]" value="{{ old('products.'.$index.'.quantite', $saleLine->nbr ?? 0.000) }}" class="quantite mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                </div>
                                <div class="w-full md:w-1/5">
                                    <label for="mode" class="block text-sm font-medium text-gray-700">Mode</label>
                                    <select name="products[{{ $index }}][mode]" class="mode mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        <option value="M2" {{ $saleLine->mode == 'M2' ? 'selected' : '' }}>M2</option>
                                        <option value="ML" {{ $saleLine->mode == 'ML' ? 'selected' : '' }}>ML</option>
                                        <option value="Unité" {{ $saleLine->mode == 'Unité' ? 'selected' : '' }}>Unité</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" onclick="removeProduct(this)" id="btn-remove" style="margin-right:-3px; border-radius: 28px 0px 0px 1px; transition:.3s; " class="absolute bottom-2 right-0 -mb-4 bg-red-600 text-white p-4 rounde shadow-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Services Container -->
            <div id="services-container" class="space-y-4 hidden">
            </div>

            <!-- Add Product Button -->
            <button type="button" onclick="addProduct()" class="block w-full md:w-auto text-center bg-indigo-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Ajouter un produit
            </button>

            <!-- Add Service Button -->
            <button type="button" onclick="addService()" class="block w-full md:w-auto text-center bg-purple-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                Ajouter un service
            </button>
            

            <div class="w-full">
                <label for="total" class="block text-lg font-bold text-gray-500">Total</label>
                <input type="number" id="total" class="mt-1 block w-36 text-xl font-bold text-red-400  border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" readonly>
            </div>

            <button type="submit" class="block w-full text-center bg-green-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Valider
            </button>
            <button type="button" onclick="window.history.back()" class="block w-full text-center bg-blue-600 text-white px-4 py-2 rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Annuler
            </button>
        </form>
    </div>
</div>



@endcan
    <script>
        // search client select 
        $(document).ready(function () {
            $('#code_client').select2({
                placeholder: "Select Client",
                ajax: {
                    url: '{{ route('reglements.search') }}', // Adjust the route as needed
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        console.log(data);
                        
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
                }
            });
        });
        
        
    // let productIndex = 2;

    function addProduct() {
        const container = document.getElementById('products-container');
        // Calculate the next index based on the current number of product items
    const productIndex = container.querySelectorAll('.product-item').length;

        const newProduct = `
            <div class="product-item relative overflow-hidden bg-gray-100 flex flex-wrap gap-4 items-center border border-gray-300 p-4 rounded-md">
                <div class="w-full md:w-3/4">
                    <label for="produit" class="block text-sm font-medium text-gray-700">Produit</label>
                    <select name="products[${productIndex}][produit]" class="product-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option>~~ choisir le produit ~~</option>
                        @foreach($products as $product)
                            <option value="{{ $product->name }}" data-unit-price="{{ $product->unit_price }}" >{{ $product->name }} -- {{$product->quantity}} Disponible ({{ $product->unit_price }})</option>
                        @endforeach
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
                            <input type="number" step="0.001" name="products[${productIndex}][longueur]" class="longueur mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                        <div class="w-full md:w-1/5">
                            <label for="largeur" class="block text-sm font-medium text-gray-700">Largeur</label>
                            <input type="number" step="0.001" name="products[${productIndex}][largeur]" class="largeur mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                        <div class="w-full md:w-1/5">
                            <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité</label>
                            <input type="number" step="0.001" name="products[${productIndex}][quantite]" class="quantite mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                        <div class="w-full md:w-1/5">
                            <label for="mode" class="block text-sm font-medium text-gray-700">Mode</label>
                            <select name="products[${productIndex}][mode]" class="mode mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="M2">M2</option>
                                <option value="ML">ML</option>
                                <option value="Unité">Unité</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="removeProduct(this)" id="btn-remove" style="margin-right:-3px; border-radius: 28px 0px 0px 1px; transition:.3s; " class="absolute bottom-2 right-0 -mb-4 bg-red-600 text-white p-4 rounde shadow-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>`;
        container.insertAdjacentHTML('beforeend', newProduct);
        // productIndex++;
    }

function calculateTotal() {
    const products = document.querySelectorAll(".product-item");
    let grandTotal = 0;

    products.forEach((product) => {
        const longueur = parseFloat(product.querySelector(".longueur")?.value || 0);
        const largeur = parseFloat(product.querySelector(".largeur")?.value || 0);
        const quantite = parseFloat(product.querySelector(".quantite")?.value || 0);
        const mode = product.querySelector(".mode")?.value;
        const prixUnitaire = parseFloat(product.querySelector(".prix-unitaire")?.value || 0);

        console.log("----------------------");
        console.log("longueur : ", longueur);
        console.log("largeur  : ", largeur);
        console.log("quantite : ", quantite);
        console.log("mode : ", mode);
        console.log("prixUnitaire : ", prixUnitaire);

        let mesureTotal = 0;

        if (mode === "M2") {
            mesureTotal = longueur * largeur * quantite * prixUnitaire;
        } else if (mode === "ML") {
            mesureTotal = (longueur + largeur) * quantite * prixUnitaire;
        } else if (mode === "Unité") {
            mesureTotal = quantite * prixUnitaire;
        }

        console.log("mesureTotal : ", mesureTotal);
        console.log("----------------------");
        grandTotal += mesureTotal;
    });

    console.log("____________________________________");
    
    const services = document.querySelectorAll(".service-item");
    services.forEach((service) => {
        const montant = parseFloat(service.querySelector(".montant")?.value || 0);
        const quantite = parseFloat(service.querySelector(".quantite")?.value || 0);
        grandTotal += montant * quantite;
    });

    document.getElementById("total").value = grandTotal.toFixed(2);
}

// Attach event listeners
calculateTotal();
document.addEventListener("input", calculateTotal);
document.getElementById("products-container").addEventListener("input", calculateTotal);




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


    // add the product price selected to input of prix-unitaire 
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.getElementById('products-container');

        container.addEventListener('change', function (event) {
            if (event.target.classList.contains('product-select')) {
                const selectedOption = event.target.options[event.target.selectedIndex];
                const unitPrice = selectedOption.getAttribute('data-unit-price');
                const productItem = event.target.closest('.product-item');
                const unitPriceInput = productItem.querySelector('.prix-unitaire');

                if (unitPriceInput) {
                    unitPriceInput.value = unitPrice || ''; // Set the value or clear if no product selected
                }
                calculateTotal();
            }
        });
    });


    // --------------- service ----------
    // let serviceIndex = 1;

    function addService() {
        const serviceContainer = document.getElementById('services-container');
        const serviceIndex = serviceContainer.querySelectorAll('.service-item').length;

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
        // serviceIndex++;
    }

    function removeService(button) {
        const serviceItem = button.closest('.service-item');
        serviceItem.remove();
    }

        
</script>
@endsection
