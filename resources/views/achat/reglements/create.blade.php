@extends('layouts.app')

@section('content')
    <style>
        #payment-status{
            font-size: 20px;
            color: gray;
            font-weight: bold;
        }
        #remaining-balance{
            /* color: red; */
        }
        #code_client{
            width: 500px;
            /* font-size: 20px; */
            height: 40px;
            /* padding: 5px; */
            font-size: 20px ;
        }
        .dateInput{
            width: 300px;
        }
        .selec2{
            height: 40px !important;

        }
        .select2-selection{
            height: 45px !important;
            background-color: rgb(249 250 251) !important;
            border-radius: 20px;
            padding: 5px;
        }
        .select2-selection__rendered{
            height: 100% !important;
        }
        .select2-selection__placeholder{
            height: 40px !important;
            /* color: red !important; */
            color: black !important;
            padding-top: 8px !important;
        }
        .dropdown-wrapper{
            background-color: gray !important;
        }
        #bls option{
            height:40px; 
            font-size:20px; 
            padding:5px !important; 
            background-color:rgb(235, 234, 234);
            color:rgb(24, 23, 23);
            font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif ;
        }
        .div-selectBl{
            display: flex;
            /* justify-content: space-between; */
            gap: 120px;
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
        .div-selectBl #date_reglement{
            width: 260px;
            padding: 10px;
            border-radius: 5px;
            
        }

        /* mode payment */
        .div-modePyamnet{
            display: flex;
            /* justify-content: space-between; */
            gap: 120px;
        }
        .div-modePyamnet #mode_reglement{
            width: 500px;
            padding: 10px;
            border-radius: 5px;
        }
        /* for cheque */
        #div-chq{
            display: flex;
            /* background-color: #a9a8a8; */
            gap: 20px;
            visibility: hidden;
        }
        #div-chq #reference_chq{
            width: 265px;
            padding: 10px;
            border-radius: 5px;
        }
        #div-chq #date_chq{
            width: 265px;
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
@can("create reglements")
    <div class="py-5 ">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-center mb-4 text-2xl font-bold">Ajouter nouveau Règlement</h2>

                    <form id="reglement-form">
                        @csrf
                            {{--start -=-=-= -= = -  --}}
                        <div class="mb-6">
                            <div class="flex gap-4">
                                <label>
                                    <input type="radio" name="selection_mode" value="client" checked>
                                    Select by Client
                                </label>
                                <label>
                                    <input type="radio" name="selection_mode" value="bl">
                                    Select by BL
                                </label>
                            </div>
                        </div>
                        {{-- end -=-=-= -= = -  --}}

                        <!-- New Select for BL Search -->
                        <div id="bl-search" class="hiddena">
                            <div class="mb-6">
                                <label for="search_bls" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Search BL</label>
                                <select name="search_bls" id="search_bls" required class="block w-full text-lg border rounded-lg">
                                    <!-- Populated dynamically -->
                                </select>
                            </div>
                        </div>

                        {{-- existiiiiiiiing select --}}
                        <div class="div-selectBl " id="ha">
                            <div class="mb-6 ">
                                <label for="code_client" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Client</label>
                                <select name="code_client" id="code_client" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                                    <!-- Select2 will dynamically populate options via AJAX -->
                                </select>
                            </div>
                            <div class="mb-6">
                                <label for="bls" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Bon de Livraison (BL)</label>
                                <select name="no_bl" id="bls" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                                    <option value="">Select a BL</option>
                                </select>
                            </div>
                        </div>

                        <div class="div-selectBl">
                            <div class="mb-6">
                                <label for="montant" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Montant Reglment</label>
                                <input type="number" name="montant" id="montant" placeholder="Montant" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                            </div>

                            <div class="mb-6 dateInput">
                                <label for="date_reglement" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Date Regelment</label>
                                <input type="date" name="date" id="date_reglement" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                            </div>
                        </div>

                        <div class="div-modePyamnet">
                            <div class="mb-6">
                                <label for="mode_reglement" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Mode</label>
                                {{-- <input type="text" name="type_pay" id="mode_reglement" class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600"> --}}
                                <select name="type_pay" id="mode_reglement" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                                    <option value="Espèce ">espèce </option>
                                    <option value="Chèque">chèque</option>
                                    <option value="Virement">virement</option>
                                </select>
                            </div>
                            <div id="div-chq">
                                <div class="mb-6">
                                    <label for="reference_chq" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">N Reference </label>
                                    <input type="number" name="reference_chq" id="reference_chq" placeholder="Montant" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                                </div>
                                <div class="mb-6 dateInput">
                                    <label for="date_chq" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Date Expiration</label>
                                    <input type="date" name="date_chq" id="date_chq" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                                </div>
                            </div>
                        </div>

                        <button type="button" id="save-button" class="w-full px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500">Save</button>
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
    $(document).ready(function () {
    // Initialize Select2 for the 'code_client' field
    $('#code_client').select2({
        placeholder: "Select Client",
        ajax: {
            url: '{{ route('reglements.search') }}',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                
                return {
                    results: data.map(function (sale) {
                        return {
                            id: sale.code_client,
                            name: sale.name,
                            text: sale.code_client + " - " + sale.name
                        };
                    })
                };
            },
            cache: true
        }
    });

    // Handle payment status and BL fetching when a client is selected
    $('#code_client').on('change', function () {
        const selectedClient = $(this).val();
        $('#bls').html('<option>Loading...</option>');

        // Fetch BLs for the selected client
        fetch(`/client-bls/${selectedClient}`)
            .then(response => response.json())
            .then(data => {
                $('#bls').empty();
                if (!data.length) {
                    $('#bls').append('<option disabled>No BLs available</option>');
                    return;
                }
                data.forEach(bl => {
                    $('#bls').append(
                        `<option value="${bl.no_bl}">BL: ${bl.no_bl} --- Restant: ${bl.montant_restant} DH</option>`
                    );
                });
            })
            .catch(error => {
                console.error('Error fetching BLs:', error);
                $('#bls').html('<option disabled>Error fetching BLs</option>');
            });

        // Fetch payment status for the selected client
        fetch(`/payment-status/${selectedClient}`)
            .then(response => response.json())
            .then(data => {
                const balanceElement = $('#remaining-balance');
                balanceElement.text(data.montant_restant || 0);
                balanceElement.css('color', updateBalanceColor(data.montant_restant || 0));
            })
            .catch(error => console.error('Error:', error));
    });

    // Save form data
    $('#save-button').on('click', function (e) {
    e.preventDefault();

    const selectionMode = $('input[name="selection_mode"]:checked').val();
    let bl, client;

    if (selectionMode === 'client') {
        client = $('#code_client').val();
        bl = $('#bls').val();
    } else if (selectionMode === 'bl') {
        const selectedBL = $('#search_bls').select2('data')[0]; // Get the selected option's data
        if (selectedBL) {
            bl = selectedBL.id;
            client = selectedBL.code_client; // Access code_client from the custom data
        }
    }

    if (!bl) {
        alert('Please select a BL.');
        return;
    }

    const formData = {
        no_bl: bl,
        code_client: client,
        montant: $('#montant').val(), // Replace with your input ID
        date: $('#date_reglement').val(), // Replace with your input ID
        type_pay: $('#mode_reglement').val(), // Replace with your input ID
        reference_chq: $('#reference_chq').val(), // Replace with your input ID
        date_chq: $('#date_chq').val(), // Replace with your input ID
        _token: '{{ csrf_token() }}'
    };

    console.log(formData);

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
// -=-==-=-= end save data -=-=-==- 
// 0-=-= 0=-== 0==0 

    // Redirect on Finish button click
    $('#finish-button').on('click', function () {
        window.location.href = '{{ route('reglements.index') }}';
    });

    function updateBalanceColor(balance) {
        if (balance > 100) return 'red';
        if (balance <= 100 && balance > 20) return 'orange';
        return 'green';
    }
});


document.addEventListener('DOMContentLoaded', function () {
    const modeReglement = document.getElementById('mode_reglement');
    const divChq = document.getElementById('div-chq');

    // Initially hide the div if the default option is not "Chèque"
    if (modeReglement.value !== 'Chèque') {
        divChq.style.visibility = 'hidden';
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



$(document).ready(function () {
    // Toggle between modes
    $('input[name="selection_mode"]').on('change', function () {
        if ($(this).val() === 'client') {
            $('#ha').removeClass('hiddena');
            $('#bl-search').addClass('hiddena');
        } else {
            $('#ha').addClass('hiddena');
            $('#bl-search').removeClass('hiddena');
        }
    });

    // Initialize Select2 for BL Search
    $('#search_bls').select2({
    placeholder: "Search BL",
    ajax: {
        // url: '/get-all-bls',
        url: '{{ route('reglements.get-all-bls') }}',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
                results: data.map(function (bl) {
                    return {
                        id: bl.no_bl,
                        text: `${bl.no_bl} - ${bl.name_client} (${bl.code_client}) -- Rest: ${bl.montant_restant}DH`,
                        code_client: bl.code_client // Attach code_client to the result
                    };
                })
            };
        },
        cache: true
    },
    templateSelection: function (bl) {
        if (bl.code_client) {
            // Add the data-code-client attribute to the DOM element
            const $option = $('<span>', {
                text: bl.text,
                'data-code-client': bl.code_client,
            });
            return $option;
        }
        return bl.text;
    }
});

   
});

</script>
@endsection
