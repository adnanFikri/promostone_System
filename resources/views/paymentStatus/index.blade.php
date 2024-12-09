<x-app-layout>
            <style>
                /* Custom DataTable styles */
                .dataTables_wrapper .dataTables_length select {
                    background-color: #5560b64e;
                    width: 90px;
                    padding: 8px;
                    border-radius: 5px;
                    border: 1px solid #e2e2ff;
                    margin-bottom: 2px;
                }

                .dataTables_wrapper .dataTables_paginate .paginate_button {
                    background-color: #5560b64e;
                    color: white;
                    border: none;
                    padding: 5px 10px;
                    border-radius: 5px;
                    margin-left: 5px;
                }

                /* .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                    background-color: #eddcd7 !important; 
                } */
                div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover {
                    background-color: #e4e6f8 !important;
                }

                #payment-status-table {
                    width: 100%;
                    border-collapse: collapse;
                }

                #payment-status-table th, #payment-status-table td {
                    padding: 12px;
                    border-bottom: 1px solid #f6e8e8;
                    transition: .5s;
                }

                #payment-status-table th {
                    background-color: #6572cef6;
                    color: white;
                    font-size: 15px;
                    text-transform: uppercase;
                }


                #payment-status-table tbody tr:nth-child(even) {
                    background-color: #f2f2f2;
                }

                #payment-status-table tbody tr:hover {
                    background-color: #5560b64e;
                }

                .dataTables_filter input {
                    padding: 8px;
                    margin-left: 10px;
                    border-radius: 5px;
                    border: 1px solid #ddd;
                }

                .btnA svg{
                    width: 50px;
                    height: 40px;
                    background-color:#4CAF50;
                    float: left;
                    /* margin-right: 3px; */
                    margin: 3px;
                    border-radius: 14px;
                }
                .btnA svg:hover{
                    background-color:#ffffff;
                    color: #45a049;
                }

                /* model css */
                #salesModal .dataTables_filter, 
                #reglementsModal .dataTables_filter {
                    display: none !important;
                }

                #salesModal .dataTables_length, 
                #reglementsModal .dataTables_length {
                    display: none !important;
                }

                #salesModal #modal-client-name, #reglementsModal #reglements-client-name{
                    margin-left: 300px;
                }
                #reglementsModal #reglementsContainer {
                   width: 70%;
                }

                #searchBl{
                    /* background: #a5a2a2; */
                }
                #searchBl #search-no-bl, #searchBl #code_client_search{
                    /* margin-bottom: -30px; */
                    /* background: #abaaaa; */
                    width: 183px;
                    border-color: gray;
                    border-width:1px; 
                }

                .dateInputsSearch{

                }
                #parent1{
                    /* background: #777474; */
                    display: flex;
                    justify-content: space-between;
                }
                #parent2{
                    /* background: #777474; */
                    display: flex;
                    justify-content: space-between;
                }
                #parent1 input[type=date]{
                    /* width:300px; */
                }
                #parent2 input[type=date]{
                    /* width:300px; */
                }

                #div-chq{
                    display: flex;
                    justify-content: center;
                    gap: 100px;
                }
                #div-chq div{
                    width: 40%;
                }

                #close-modal{
                    font-size: 30px;
                    transition: .3s;
                }
                #close-modal:hover{
                    color: #e87a7a;
                    transform: scale(110%);
                }

                #dt-inpts div input{
                    /* width: fit-content;   */
                    background-color: #f5eaeaa5;
                    border-radius: 3px
                }
                #dt-inpts div:nth-child(1){
                    width: 130px;
                }
                #dt-inpts div:nth-child(1) input{
                    width: 130px;
                }
                #dt-inpts div:nth-child(2) input{
                    width: 250px;
                }
                #dt-inpts div:nth-child(3) input{
                    width: 102%;
                    /* margin-left: 10px; */
                }
                #dt-inpts div:nth-child(4) input{
                    width: 150px;
                    /* margin-left: 10px; */
                }

                #sum-montant-restant{
                    padding: 2px 5px;
                    width: fit-content;
                    position: relative;
                    top: 150px;
                    left: 500px;
                    background: #e7e1e1ac;
                    color: #06600a;
                    border-radius: 3px;
                    font-weight: 900;
                }

                #date_reglement{
                    width: 220px;
                }
            </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('PAIEMENT STATUS') }}
            <button class="relative inline-flex items-center justify-center ms-96 p-0.5  me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-cyan-200 dark:focus:ring-cyan-800">
                <span class="relative px-5 py-1.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                <a href="{{route('reglements.create')}}"> Reglement</a>
                </span>
            </button>
        </h2>
    </x-slot>

    <div class="py-2 px-9">
        <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 pt-4 text-gray-900 dark:text-gray-100 ">
                    <h2 class="font-serif text-center rounded-lg text-gray-600 bg-gray-10 p-2 underline mb-2 text-2xl font-bold ">BL STATUS PAIEMENT</h2>

                    {{-- <div class="dateInputsSearch">
                        
                        
                    </div> --}}
                    <div id="sum-montant-restant" class="font-bold text-lg mb-4">Total Montant Restant: 0</div>



                    <div id="parent1">
                        <!-- Custom search input for from date -->
                        <div id="div-search-date-from" class="mb-1 bg-gray-00 text-right">
                            <label for="search-date-from" class="mr-">DE :</label>
                            <input type="date" id="search-date-f" class="border px-2 py-2 rounded" placeholder="From Date">
                        </div>

                        <div id="searchBl" class="mb-1 bg-gray-00 text-right">
                            <label for="code_client_search" class="mr-">Search:</label>
                            <input type="text" id="code_client_search" class="border px-2 py-2 rounded" placeholder="code Client">
                        </div>
                    </div>

                    <div id="parent2" class="mb-1">
                        <!-- Custom search input for To date -->
                        <div id="search-date-to" class="mb-1 bg-gray-00 text-right">
                            <label for="search-date-to" class="mr-">AU :</label>
                            <input type="date" id="search-date-t" class="border px-2 py-2 rounded" placeholder="To Date">
                        </div>
                        
                        <!-- Custom search input for No BL -->
                        <div id="searchBl" class="mb-1 bg-gray-00 text-right">
                            <label for="search-no-bl" class="mr-">Search:</label>
                            <input type="text" id="search-no-bl" class="border px-2 py-2 rounded" placeholder="No BL">
                        </div>

                    </div>
                    
                    <table id="payment-status-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-sm text-gray-500 dark:text-gray-400 border">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <tr>
                                {{-- <th scope="col" class="px-6 py-3">ID</th> --}}
                                <th scope="col" class="px-6 py-3">No BL</th>
                                <th scope="col" class="px-6 py-3">CODE CLIENT</th>
                                <th scope="col" class="px-6 py-3">RAISON</th>
                                <th scope="col" class="px-6 py-3">DATE</th>
                                <th scope="col" class="px-6 py-3">MONTANT TOTAL ($)</th>
                                <th scope="col" class="px-6 py-3">MONTAT PAYé ($)</th>
                                <th scope="col" class="px-6 py-3">Solde Restant ($)</th>
                                <th scope="col" class="px-6 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800">
                            <!-- DataTables will populate this -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- modal add regelement --}}
    <div id="reglement-modal" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white dark:bg-gray-800 p-6 py-4 rounded-lg shadow-lg w-2/4">
            <div class="flex justify-end items-center mb-1 ">
                <button id="close-modal" class="text-red-500 text-xl">&times;</button>
            </div>
            <h2 class="text-xl font-bold ml-12 text-center font-serif text-gray-600 mb-4">Ajouter nouveau Règlement</h2>
            <div id="modal-content mb-4">

                <div class="flex justify-start mb-2" id="dt-inpts">
                    <div class="text-center">
                        <label for="no_bl"> No BL</label>
                        <input type="text"  id="no_bl" disabled>
                    </div>
                    <div class="text-center">
                        <label for="no_bl">Raison</label>
                        <input type="text"  id="name_client" disabled>
                    </div>
                    <div class="text-center">
                        <label for="no_bl">code client</label>
                        <input type="text"  id="code_client" disabled>
                    </div>
                    <div class="text-center">
                        <label for="montant_rest">Montant Rest</label>
                        <input type="text"  id="montant_rest" disabled>
                    </div>
                </div>

                <div class="div-selectBl">
                    <div class="mb-2">
                        <label for="montant" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Montant Reglment</label>
                        <input type="number" name="montant" id="montant" placeholder="Montant" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                    </div>

                    <div class="mb-2 dateInput">
                        <label for="date_reglement" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Date Regelment</label>
                        <input type="date" name="date" id="date_reglement" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                    </div>
                </div>

                <div class="div-modePyamnet">
                    <div class="mb-2">
                        <label for="mode_reglement" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Mode</label>
                        {{-- <input type="text" name="type_pay" id="mode_reglement" class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600"> --}}
                        <select name="type_pay" id="mode_reglement" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                            <option value="Espèce ">espèce </option>
                            <option value="Chèque">chèque</option>
                            <option value="Virement">virement</option>
                        </select>
                    </div>

                    <div id="div-chq" >
                        <div class="mb-2 ">
                            <label for="reference_chq" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">N Reference </label>
                            <input type="number" name="reference_chq" id="reference_chq" placeholder="Montant" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                        </div>
                        <div class="mb-2">
                            <label for="date_chq" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Date Expiration</label>
                            <input type="date" name="date_chq" id="date_chq" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                        </div>
                    </div>
                </div>

                <button type="button" id="save-button" class="w-full px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500">Save</button>
                {{-- <button type="button" id="finish-button" class="w-full mt-2 px-4 py-2 text-white bg-green-600 rounded hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500">Finish</button> --}}
            </form>

            <div id="status-updated" class="mt-6 hidden bg-green-100 text-green-800 px-4 py-2 rounded">
                <p id="update-message"></p>
                <p>Solde restant mis à jour: <span id="updated-remaining-balance"></span></p>
            </div>
            </div>
        </div>
    </div>
    
    
   <!-- Modal -->
<div id="salesModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-500 bg-opacity-75">
    <div class="bg-white p-6 rounded-lg max-w-15xl max-h-90vh overflow-auto">
        <div class="flex justify-between items-center bg-gray-100 px-1">
            <h5 class="text-xl font-semibold bg-gray-100 w-full" id="salesModalLabel">
                Ventes de BL :
                <span id="bl-number" class="text-2xl"></span> 
                <span id="modal-client-name" class="text-center text-2xl text-gray-500"></span> <span id="modal-code-client" class="text-center text-2xl text-gray-500"></span>
                
            </h5>
            <button type="button" class="text-gray-500  hover:text-gray-700" onclick="closeModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="mt-4 overflow-auto max-h-[60vh]">
            <table id="sales-modal-table" class="min-w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">No BL</th>
                        <th class="px-4 py-2 border">Annee</th>
                        <th class="px-4 py-2 border">Date de BL</th>
                        {{-- <th class="px-4 py-2 border">Code Client</th> --}}
                        {{-- <th class="px-4 py-2 border">Client</th> --}}
                        <th class="px-4 py-2 border">Ref Produit</th>
                        <th class="px-4 py-2 border">Produit</th>
                        <th class="px-4 py-2 border">Long</th>
                        <th class="px-4 py-2 border">Large</th>
                        <th class="px-4 py-2 border">Nombre</th>
                        <th class="px-4 py-2 border">Quantité</th>
                        <th class="px-4 py-2 border">Prix Unitaire</th>
                        <th class="px-4 py-2 border">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Sales data will populate here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="reglementsModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-500 bg-opacity-75">
    <div id="reglementsContainer" class="bg-white p-6 rounded-lg max-w-15xl max-h-90vh overflow-auto">
        <div class="flex justify-between items-center bg-gray-100 px-1">
            <h5 class="text-xl font-semibold bg-gray-100 w-full" id="reglementsModalLabel">
                Règlements de BL :
                <span id="reglements-bl-number" class="text-2xl"></span> 
                <span id="reglements-client-name" class="text-center text-2xl text-gray-500"></span>
                <span id="reglements-code-client" class="text-center text-2xl text-gray-500"></span>
            </h5>
            <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeReglementsModal()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="mt-4 overflow-auto max-h-[60vh]">
            <table id="reglements-modal-table" class="min-w-full table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">ID</th>
                        <th class="px-4 py-2 border">No BL</th>
                        <th class="px-4 py-2 border">Code Client</th>
                        <th class="px-4 py-2 border">Nom Client</th>
                        <th class="px-4 py-2 border">Montant</th>
                        <th class="px-4 py-2 border">Date</th>
                        <th class="px-4 py-2 border">Type Pay</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Reglement data will populate here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

    <script>

$(document).ready(function () {
    var table = $('#payment-status-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('paymentStatus.index') }}',
            data: function (d) {
                d.date_from = $('#search-date-f').val(); // Add date_from to the request
                d.date_to = $('#search-date-t').val();   // Add date_to to the request
            }
        },
        columns: [
            // { data: 'id', name: 'id' },
            { data: 'no_bl', name: 'no_bl' },
            { data: 'code_client', name: 'code_client' },
            { data: 'name_client', name: 'name_client' },
            { data: 'date_bl', name: 'date_bl' },
            {
                data: 'montant_total',
                name: 'montant_total',
                render: function (data) {
                    return formatNumberWithSpaces(" " + data);
                }
            },
            {
                data: 'montant_payed',
                name: 'montant_payed',
                render: function (data) {
                    return formatNumberWithSpaces(data);
                }
            },
            {
                data: 'montant_restant',
                name: 'montant_restant',
                render: function (data) {
                    return formatNumberWithSpaces(data);
                }
            },
            {
                data: null,
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function (data, type, row) {
                    return `
                    <button class="reglement-btn bg-orange-400 text-white px-4 py-2  rounded  hover:bg-orange-600" 
                       data-bl-id="${row.no_bl}" 
                       data-client-id="${row.code_client}" 
                       data-client-name="${row.name_client}"
                       data-montant-restant="${row.montant_restant}">
                       <svg class="w-6 h-5  text-light-800 dark:text-white hover:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                           <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7h-1M8 7h-.688M13 5v4m-2-2h4"/>
                       </svg>
                   </button>
                        <button class="bg-blue-400 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded view-sales" data-bl="${row.no_bl}">BL</button>
                        <button class="bg-green-400 hover:bg-green-600 text-white font-bold py-2 px-4 rounded view-reglements" data-bl="${row.no_bl}">RG</button>
                    `;
                }
            }
        ],
        responsive: true,
        lengthMenu: [10, 5, 25, 50,100,1000],
        language: {
            paginate: {
                previous: "&laquo;",
                next: "&raquo;"
            }
        },
        drawCallback: function () {
            // Update the sum whenever the table is redrawn
            updateSum();
        }
    });

    // Function to calculate and display the sum of montant_restant
    function updateSum() {
        var total = 0;
        table.rows({ filter: 'applied' }).every(function () {
            var data = this.data();
            total += parseFloat(data.montant_restant) || 0; // Ensure numeric calculation
        });

        // Update the sum in the div
        $('#sum-montant-restant').text('Total Montant Restant: ' + formatNumberWithSpaces(total.toFixed(2)));
    }

    // Add event listeners for filters
    $('#search-no-bl').on('keyup', function () {
        let value = this.value.trim();
        if (value === "") {
            table.column(0).search("").draw();
        } else {
            table.column(0).search("^" + value + "$", true, false).draw();
        }
    });

    $('#code_client_search').on('keyup', function () {
        let value = this.value.trim();
        if (value === "") {
            table.column(1).search("").draw();
        } else {
            table.column(1).search("^" + value + "$", true, false).draw();
        }
    });

    // Listen to changes in date inputs
    $('#search-date-f, #search-date-t').on('change', function () {
        table.draw(); // Redraw the table with the new filter values
    });
});
// 000000000000000000000000000000000000000000000000000
// 0000000 ------------------------ ADD NEW REGELEMENT ---------------------------------
$(document).ready(function () {
    // Open Modal
    $(document).on('click', '.reglement-btn', function () {
        // Get the BL and Client details from the button's data attributes
        const noBL = $(this).data('bl-id');
        const clientCode = $(this).data('client-id');
        const clientName = $(this).data('client-name');
        const montantRestant = $(this).data('montant-restant');
        
        

        // Populate form fields with the fetched data
        $('#code_client').val(clientCode);
        $('#name_client').val(clientName); // If there's a field to display the client's name
        $('#no_bl').val(noBL);
        $('#montant_rest').val(formatNumberWithSpaces(montantRestant));

        // Show the modal
        $('#reglement-modal').removeClass('hidden');
    });

    // Close Modal
    $('#close-modal').on('click', function () {
        $('#reglement-modal').addClass('hidden');
        $('#status-updated').addClass('hidden');
        
    });
    const modal = document.getElementById("reglement-modal");
    // Hide the modal if clicking outside
    modal.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.classList.add("hidden");
            $('#status-updated').addClass('hidden');
        }
    });
    

    // Save Règlement
    $('#save-button').on('click', function (e) {
        e.preventDefault();

        // Prepare form data
        const formData = {
            no_bl: $('#no_bl').val(),
            code_client: $('#code_client').val(),
            montant: $('#montant').val(),
            date: $('#date_reglement').val(),
            type_pay: $('#mode_reglement').val(),
            reference_chq: $('#reference_chq').val(),
            date_chq: $('#date_chq').val(),
            _token: '{{ csrf_token() }}'
        };

        if (!formData.no_bl || !formData.code_client || !formData.montant) {
            alert('Please fill in all required fields.');
            return;
        }

        console.log(formData);
        
        // if(false){
            // Send AJAX request
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
    
                        // Hide the modal
                        // $('#reglement-modal').addClass('hidden');

                        // Optionally, refresh the table
                    $('#payment-status-table').DataTable().ajax.reload();
    
                        
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        // }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const modeReglement = document.getElementById('mode_reglement');
    const divChq = document.getElementById('div-chq');

    // Initially hide the div if the default option is not "Chèque"
    if (modeReglement.value !== 'Chèque') {
        divChq.style.display = 'none';
    }

    // Add an event listener to handle changes to the dropdown
    modeReglement.addEventListener('change', function () {
        if (this.value === 'Chèque') {
            divChq.style.display = 'flex'; 
        } else {
            divChq.style.display = 'none'; 
        }
    });
});

// ==============================================================================

// Function to format numbers with spaces as thousands separators
function formatNumberWithSpaces(number) {
    if (number == null) return '';
    return number
        .toString()
        .replace(/\B(?=(\d{3})+(?!\d))/g, " ");
}

    // 00000 00000 00000 0000 0000 00 0 0 000 00 0 
    // 00000 00000 00000 0000 0000 00 0 0 000 00 0 
    // 00000 00000 00000 0000 0000 00 0 0 000 00 0 

    // Open modal
    $(document).on('click', '.view-sales', function () {
        var blNumber = $(this).data('bl'); // Get the BL number
        $('#bl-number').text(blNumber); // Set the BL number in the header
        $('#salesModal').removeClass('hidden'); // Show the modal

        // Make the AJAX request to get the sales data for this BL
        $.ajax({
            url: '/sales/get-by-bl', // The route to get sales by BL
            method: 'GET',
            data: { no_bl: blNumber },
            success: function (response) {
                var salesTableBody = $('#sales-modal-table tbody');
                salesTableBody.empty(); // Clear previous data
                console.log(response);

                if (response.sales.length > 0) {
                    // Get client details from the first sale entry
                    var clientName = response.sales[0].client_name || 'N/A';
                    var codeClient = "("+response.sales[0].code_client + ")" || 'N/A';

                    // Update the modal header with client information
                    $('#modal-client-name').text(clientName);
                    $('#modal-code-client').text(codeClient);
                } else {
                    // Fallback if no sales are found
                    $('#modal-client-name').text('pas de regelement').css('color', 'red');;
                    // $('#modal-code-client').text('N/A');
                }

                // Populate the table with the sales data
                response.sales.forEach(function (sale) {
                    salesTableBody.append(`
                        <tr>
                            <td class="px-4 py-2 border">${sale.id}</td>
                            <td class="px-4 py-2 border">${sale.no_bl}</td>
                            <td class="px-4 py-2 border">${sale.annee}</td>
                            <td class="px-4 py-2 border">${sale.date}</td>
                            <td class="px-4 py-2 border">${sale.ref_produit}</td>
                            <td class="px-4 py-2 border">${sale.produit}</td>
                            <td class="px-4 py-2 border">${sale.longueur}</td>
                            <td class="px-4 py-2 border">${sale.largeur}</td>
                            <td class="px-4 py-2 border">${sale.nbr}</td>
                            <td class="px-4 py-2 border">${sale.qte}</td>
                            <td class="px-4 py-2 border">${sale.prix_unitaire}</td>
                            <td class="px-4 py-2 border">${sale.montant}</td>
                        </tr>
                    `);
                });

                // Re-initialize DataTable for the modal table
                $('#sales-modal-table').DataTable();
            },
            error: function () {
                alert('Error fetching sales data');
            },
        });
    });

    // Close modal
    function closeModal() {
        $('#salesModal').addClass('hidden'); // Hide the modal
    }




    // £££ modal regelemtns 
    $(document).on('click', '.view-reglements', function () {
        var blNumber = $(this).data('bl'); // Get the BL number
        $('#reglements-bl-number').text(blNumber); // Set the BL number in the header
        $('#reglementsModal').removeClass('hidden'); // Show the modal

        // Make the AJAX request to get the reglements data for this BL
        $.ajax({
            url: '/reglements/get-by-bl', // The route to get reglements by BL
            method: 'GET',
            data: { no_bl: blNumber },
            success: function (response) {
                var reglementsTableBody = $('#reglements-modal-table tbody');
                reglementsTableBody.empty(); // Clear previous data
                console.log(response);

                if (response.reglements.length > 0) {
                    // Get client details from the first reglement entry
                    var clientName = response.reglements[0].nom_client || 'N/A';
                    var codeClient = "(" + response.reglements[0].code_client + ")" || 'N/A';

                    // Update the modal header with client information
                    $('#reglements-client-name').text(clientName);
                    $('#reglements-code-client').text(codeClient);
                } else {
                    // Fallback if no reglements are found
                    $('#reglements-client-name').text('N/A');
                    $('#reglements-code-client').text('N/A');
                }

                // Populate the table with the reglements data
                response.reglements.forEach(function (reglement) {
                    reglementsTableBody.append(`
                        <tr>
                            <td class="px-4 py-2 border">${reglement.id}</td>
                            <td class="px-4 py-2 border">${reglement.no_bl}</td>
                            <td class="px-4 py-2 border">${reglement.code_client}</td>
                            <td class="px-4 py-2 border">${reglement.nom_client}</td>
                            <td class="px-4 py-2 border">${reglement.montant}</td>
                            <td class="px-4 py-2 border">${reglement.date}</td>
                            <td class="px-4 py-2 border">${reglement.type_pay}</td>
                        </tr>
                    `);
                });

                // Re-initialize DataTable for the modal table
                $('#reglements-modal-table').DataTable();
            },
            error: function () {
                alert('Error fetching reglements data');
            },
        });
    });

    // Close modal
    function closeReglementsModal() {
        $('#reglementsModal').addClass('hidden'); // Hide the modal
    }


    </script>
    
</x-app-layout>
