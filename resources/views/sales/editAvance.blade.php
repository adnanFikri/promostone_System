@extends('layouts.app')

@section('content')
<style>
    .div-selectBl{
        display: flex;
        /* justify-content: space-between; */
        gap: 50px;
        /* background: #848383; */
    }
    .div-selectBl #bls{
        width: 100%;
        padding: 10px;
        border-radius: 5px;
    }
    .div-selectBl #montant{
        width: 400px;
        padding: 10px;
        border-radius: 5px;
    }
    .div-selectBl #destination{
        width: 400px;
        padding: 10px;
        border-radius: 5px;
        
    }
    .div-selectBl #tel-Commercant{
        width: 400px;
        padding: 10px;
        border-radius: 5px;
        
    }
    .div-selectBl #Commercant{
        width: 400px;
        padding: 10px;
        border-radius: 5px;
    }
    .div-selectBl #date-echeance{
        width: 400px;
        padding: 10px;
        border-radius: 5px;
    }
    .div-selectBl #chef-atelier{
        width: 400px;
        padding: 10px;
        border-radius: 5px;
    }


    /* mode payment */
    .div-modePyamnet{
        display: flex;
        /* justify-content: space-between; */
        gap: 50px;
    }
    .div-modePyamnet #mode_reglement{
        width: 400px;
        padding: 10px;
        border-radius: 5px;
    }
    /* for cheque */
    #div-chq{
        display: flex;
        gap: 20px;
    }
    #div-chq #reference_chq{
        width: 190px;
        padding: 10px;
        border-radius: 5px;
    }
    #div-chq #date_chq{
        width: 195px;
        padding: 10px;
        border-radius: 5px;
    }
    #search_bls{
        width: 500px;
    }
    .hiddena {
        display: none;
    }

    /* button of alert */
    .my-alert-button {
        background-color: #28a745 !important; /* Green button color */
        color: white !important; /* Text color */
        border: none !important;
        padding: 10px 20px !important;
        border-radius: 5px !important;
        font-size: 16px !important;
        cursor: pointer !important;
    }
    .my-alert-button:hover {
        background-color: #218838 !important; /* Slightly darker green on hover */
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
                
                    <!-- Row 1 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="montant" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Montant d'Avance</label>
                            <input type="number" disabled name="montant" id="montant" value="{{ old('montant', $oldPayedAmount ?? '') }}" placeholder="Entrez le montant de l'avance" required
                                class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" />
                        </div>
                
                        <div class="mb-4">
                            <label for="destination" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Destination</label>
                            <input type="text" name="destination" id="destination" value="{{ old('montant', $paymentStatus->destination ?? '') }}" placeholder="Destination" required
                                class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" />
                        </div>
                    </div>
                
                    <!-- Row 2 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="mode_reglement" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Mode de Paiement</label>
                            {{-- <select name="type_pay" id="mode_reglement" required
                                class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                                <option value="Chèque">Chèque</option>
                                <option value="Espèce">Espèce</option>
                                <option value="Virement">Virement</option>
                            </select> --}}
                            <select name="type_pay" id="mode_reglement" required disabled
                                class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                                <option value="Crédit" {{ old('type_pay', $regelement->type_pay ?? '') == 'Crédit' ? 'selected' : '' }}>Crédit</option>
                                <option value="Chèque" {{ old('type_pay', $regelement->type_pay ?? '') == 'Chèque' ? 'selected' : '' }}>Chèque</option>
                                <option value="Espèce" {{ old('type_pay', $regelement->type_pay ?? '') == 'Espèce' ? 'selected' : '' }}>Espèce</option>
                                <option value="Virement" {{ old('type_pay', $regelement->type_pay ?? '') == 'Virement' ? 'selected' : '' }}>Virement</option>
                            </select>
                        </div>
                    
                        <div id="cheque_fields" class="grid grid-cols-2 gap-6">
                            <div class="mb-4">
                                <label for="reference_chq"  class="block text-lg font-medium text-gray-900 dark:text-white mb-2">N Référence</label>
                                <input type="text" name="reference_chq" disabled id="reference_chq" value="{{ old('montant', $regelement->reference_chq ?? '') }}"  placeholder="Référence du chèque" required
                                    class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" />
                            </div>
                            <div class="mb-4">
                                <label for="date_chq" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Date Expiration</label>
                                <input type="date" name="date_chq" id="date_chq" required disabled
                                    class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" 
                                    value="{{ old('date-echeance', isset($regelement['date_chq']) ? \Carbon\Carbon::parse($regelement['date_chq'])->format('Y-m-d') : '') }}"
                                    />
                            </div>
                        </div>
                    </div>
                
                    <!-- Row 3 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="mb-4">
                            <label for="chef-atelier" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Chef d'Atelier</label>
                            <select name="chefAtelier" id="chef-atelier" value="{{ old('montant', $paymentStatus->chefAtelier ?? '') }}" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                                <option value="Mounir">Mounir</option>
                            </select>
                        </div>
                
                        <div class="mb-4">
                            <label for="date-echeance" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Date de Chargement</label>
                            <input type="date" name="date-echeance" id="date-echeance" required
                            value="{{ old('date-echeance', isset($paymentStatus['date-echeance']) ? \Carbon\Carbon::parse($paymentStatus['date-echeance'])->format('Y-m-d') : '') }}"
                            class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" />
                        
                        </div>
                    </div>

                    {{-- for mode of paye avance or reglement   --}}
                    <input type="hidden" id="mode" name="mode" value="avance">
                
                    <button type="submit" id="save-button"
                        class="w-full px-4 py-2 mt-4 text-white bg-blue-600 rounded hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Save
                    </button>
                </form>
                
            </div>
        </div>
    </div>
</div>
@endcan
<script>
  
    const modeReglement = document.getElementById('mode_reglement');
    const chequeFields = document.getElementById('cheque_fields');

    modeReglement.addEventListener('change', () => {
        if (modeReglement.value === 'Chèque') {
            chequeFields.style.display = 'grid'; // Show the fields
        } else {
            chequeFields.style.display = 'none'; // Hide the fields
        }
    });

    // Initialize the state based on the default selection
    if (modeReglement.value !== 'Chèque') {
        chequeFields.style.display = 'none';
    }

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
        chefAtelier: $('#chef-atelier').val(),
        date_echeance: $('#date-echeance').val(),
        mode : $('#mode').val(),
        _token: '{{ csrf_token() }}'
    };

    console.log(formData);
    
    let hasError = false;
    let errorMessage = "Veuillez remplir tous les champs obligatoires :";

    const requiredFields = [
        { field: 'montant', name: 'Montant' },
        { field: 'destination', name: 'Destination' },
        { field: 'type_pay', name: 'Type de paiement' },
        { field: 'chefAtelier', name: 'chef atelier' },
        { field: 'date_echeance', name: 'Date d\'échéance' }
    ];

    requiredFields.forEach(function(field) {
        if (!formData[field.field]) {
            hasError = true;
            errorMessage += "\n- " + field.name;
        }
    });

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

    fetch('{{ route('avance.update') }}', {
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
            const updatedBalance = parseFloat(data.updatedPaymentStatus.montant_restant).toFixed(2);
            $('#updated-remaining-balance').text(updatedBalance);

            // Show SweetAlert with the updated balance and a "Finish" button
            Swal.fire({
                title: 'Solde restant mis à jour',
                text: `Le solde restant mis à jour est : ${updatedBalance}`,
                icon: 'success',
                confirmButtonText: 'suivant',
                customClass: {
                    confirmButton: 'my-alert-button' // Applique votre classe personnalisée
                }
            }).then((result) => {
                if (result.isConfirmed) {
                        const noBl = '{{ $no_bl }}'; // Dynamically retrieve the no_bl
                        const nextRoute = '{{ route('bon_livraison', ['no_bl' => ':no_bl']) }}'.replace(':no_bl', noBl);
                        window.location.href = nextRoute;
                    }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erreur!',
                text: data.message,
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erreur!',
            text: 'Une erreur s\'est produite lors de la sauvegarde.',
            confirmButtonText: 'OK'
        });
    });
});

</script>

    @endsection