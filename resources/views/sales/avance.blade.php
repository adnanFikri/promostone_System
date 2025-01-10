@extends('layouts.app')

@section('content')
<style>
    .div-selectBl{
        display: flex;
        /* justify-content: space-between; */
        gap: 100px;
    }
    .div-selectBl #bls{
        width: 550px;
        padding: 10px;
        border-radius: 5px;
    }
    .div-selectBl #montant{
        width: 500px;
        padding: 10px;
        border-radius: 5px;
    }
    .div-selectBl #destination{
        width: 480px;
        padding: 10px;
        border-radius: 5px;
        
    }
    .div-selectBl #tel-Commercant{
        width: 480px;
        padding: 10px;
        border-radius: 5px;
        
    }
    .div-selectBl #Commercant{
        width: 500px;
        padding: 10px;
        border-radius: 5px;
    }
    .div-selectBl #date-echeance{
        width: 480px;
        padding: 10px;
        border-radius: 5px;
    }
    .div-selectBl #chef-atelier{
        width: 500px;
        padding: 10px;
        border-radius: 5px;
    }


    /* mode payment */
    .div-modePyamnet{
        display: flex;
        /* justify-content: space-between; */
        gap: 100px;
    }
    .div-modePyamnet #mode_reglement{
        width: 500px;
        padding: 10px;
        border-radius: 5px;
    }
    /* for cheque */
    #div-chq{
        display: flex;
        gap: 20px;
    }
    #div-chq #reference_chq{
        width: 235px;
        padding: 10px;
        border-radius: 5px;
    }
    #div-chq #date_chq{
        width: 225px;
        padding: 10px;
        border-radius: 5px;
    }
    #search_bls{
        width: 500px;
    }
    .hiddena {
        display: none;
    }
</style>
@can("create avance")
<div class="py-5 ">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h2 class="text-center mb-4 text-2xl font-bold">Ajouter nouveau Règlement</h2>

                
                <div class="bg-white p-4 mb-4 rounded-lg shadow-md flex items-center justify-between border border-gray-200">
                    <div>
                        <p class="font-medium text-gray-500">No BL:</p>
                        <p class="font-bold text-lg">{{ $no_bl }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-500">Montant Total:</p>
                        <p class="font-bold text-red-400 text-lg">{{ $total_amount }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-gray-500">Code Client:</p>
                        <p class="font-bold text-lg">{{ $code_client }}</p>
                    </div>
                </div>
                

                <form id="reglement-form" class="bg-white p-6 mb-6 rounded-lg shadow-md border border-gray-200">
                    @csrf

                    <div class="div-selectBl">
                        <div class="mb-4">
                            <label for="montant" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Montant d'Avance</label>
                            <input type="number" name="montant" id="montant" placeholder="Entrez le montant de l'avance" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                        </div>

                        <div class="mb-4 dateInput">
                            <label for="destination" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Destination</label>
                            <input type="text" name="destination" id="destination" placeholder="Destination" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                        </div>
                    </div>

                    <div class="div-modePyamnet">
                        <div class="mb-4">
                            <label for="mode_reglement" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Mode de Paiement</label>
                            <select name="type_pay" id="mode_reglement" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                                <option value="Chèque">chèque</option>
                                <option value="Espèce ">espèce </option>
                                <option value="Virement">virement</option>
                            </select>
                        </div>
                        <div id="div-chq">
                            <div class="mb-4">
                                <label for="reference_chq" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">N Reference </label>
                                <input type="number" name="reference_chq" id="reference_chq" placeholder="Référence du chèque" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                            </div>
                            <div class="mb-4 dateInput">
                                <label for="date_chq" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Date Expiration</label>
                                <input type="date" name="date_chq" id="date_chq" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="div-selectBl">
                        <div class="mb-4">
                            <label for="Commerçant" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Commerçant</label>
                            <input type="text" name="Commercant" id="Commercant" placeholder="Entrez le nom du commerçant" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                            <x-input-error :messages="$errors->get('Commercant')" class="mt-2" />
                        </div>

                        <div class="mb-4 dateInput">
                            <label for="tel-Commercant" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Téléphone Commerçant</label>
                            <input type="text" name="tel-Commercant" id="tel-Commercant" placeholder="Entrez le téléphone du commerçant" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                            <x-input-error :messages="$errors->get('tel-Commercant')" class="mt-2" />
                        </div>
                    </div>

                    <div class="div-selectBl">
                        <div class="mb-4 dateInput">
                            <label for="chef-atelie" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Chef d'Atelier</label>
                            <input type="text" name="chefAtelier" id="chef-atelier" placeholder="Entrez le nom du chef d'atelier" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                        </div>
                        <div class="mb-4 dateInput">
                            <label for="date-echeance" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Date D'échéance</label>
                            <input type="date" name="date-echeance" id="date-echeance" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                        </div>
                    </div>

                    <button type="submit" id="save-button" class="w-full px-4 py-2 mt-4 text-white bg-blue-600 rounded hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500">Save</button>
                    <button type="button" id="finish-button" class="w-full mt-2 px-4 py-2 text-white bg-green-600 rounded hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500">Finish</button>
                </form>

                <div id="status-updated" class="mt-6 hidden bg-green-100 text-green-800 px-4 py-2 rounded">
                    <p id="update-message"></p>
                    <p>Solde restant mis à jour: <span id="updated-remaining-balance"></span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modeReglement = document.getElementById('mode_reglement');
        const divChq = document.getElementById('div-chq');

        // Initially hide the div if the default option is not "Chèque"
        if (modeReglement.value !== 'Chèque') {
            divChq.style.display = 'hidden';
        }

        // Add an event listener to handle changes to the dropdown
        modeReglement.addEventListener('change', function () {
            if (this.value === 'Chèque') {
                divChq.style.visibility = 'visible'; 
            } else {
                divChq.style.visibility = 'hidden'; 
            }
        });
    });

// Save form data
$('#save-button').on('click', function (e) {
    e.preventDefault();

    const formData = {
        no_bl: '{{ $no_bl }}', // From your backend view
        code_client: '{{ $code_client }}', // From your backend view
        montant: $('#montant').val(),
        destination: $('#destination').val(),
        type_pay: $('#mode_reglement').val(),
        reference_chq: $('#reference_chq').val(),
        date_chq: $('#date_chq').val(),
        commerçant: $('#Commercant').val(),
        tel_commerçant: $('#tel-Commercant').val(),
        date_echeance: $('#date-echeance').val(),
        _token: '{{ csrf_token() }}'
    };
    console.log(formData);
    
    // Validate if any of the required fields are empty
    let hasError = false;
    let errorMessage = "Veuillez remplir tous les champs obligatoires :";

    // List of required fields
    const requiredFields = [
        { field: 'montant', name: 'Montant' },
        { field: 'destination', name: 'Destination' },
        { field: 'type_pay', name: 'Type de paiement' },
        { field: 'commerçant', name: 'Commerçant' },
        { field: 'tel_commerçant', name: 'Téléphone du commerçant' },
        { field: 'date_echeance', name: 'Date d\'échéance' }
    ];

    // Check each required field
    requiredFields.forEach(function(field) {
        if (!formData[field.field]) {
            hasError = true;
            errorMessage += "\n- " + field.name;
        }
    });

    // If there's an error, show SweetAlert and stop the form submission
    if (hasError) {
        Swal.fire({
            icon: 'error',
            title: 'Erreur!',
            text: errorMessage,
            confirmButtonText: 'Ok',
            background: '#f8d7da',
            confirmButtonColor: '#d33'
        });
        return; 
    }


    // Send data to the store function
    fetch('{{ route('reglements.store') }}', {
            method: 'POST',
            body: new URLSearchParams(formData),
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#status-updated').removeClass('hidden');
                $('#update-message').text(data.message);
                $('#updated-remaining-balance').text(parseFloat(data.updatedPaymentStatus.montant_restant).toFixed(2));
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });

    $('#finish-button').on('click', function () {
        const no_bl = '{{ $no_bl }}';  // Use the no_bl from the backend
        window.location.href = '{{ route('bon_livraison', ['no_bl' => ':no_bl']) }}'.replace(':no_bl', no_bl);
    });


</script>

    @endsection