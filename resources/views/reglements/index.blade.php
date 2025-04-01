@extends('layouts.app')

@section('content')
    <style>
        /* Custom DataTable styles */
        .dataTables_wrapper .dataTables_length select {
            background-color: #f8a4a396;
            width: 90px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #e2e2ff;
            margin-bottom: 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #f8a4a3b9;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            margin-left: 5px;
        }

        div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover {
            background-color: #f8a4a3b9 !important;
        }

        #reglements-table {
            width: 100%;
            border-collapse: collapse;
        }

        #reglements-table th, #reglements-table td {
            padding: 12px;
            border-bottom: 1px solid #f6e8e8;
            transition: .5s;
        }

        #reglements-table th {
            background-color: #f17d7b;
            color: white;
            font-size: 15px;
            text-transform: uppercase;
        }

        #reglements-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #reglements-table tbody tr:hover {
            background-color: #f8a4a343;
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
            background-color:#f17d7b;
            float: left;
            /* margin-right: 3px; */
            margin: 3px;
            border-radius: 10%;
        }
        .btnA svg:hover{
            background-color:#ffffff;
            color: #f17d7b;
        }
    </style>

@can("view reglements")
    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 overflow-x-auto">
            <button onclick="fetchBanks()" class="bg-blue-600 text-white px-4 py-2 rounded mb-2">Gérer les Banques</button>

            <div class="bg-white dark:bg-gray-800 overflow-x-auto shadow-sm sm:rounded-lg">

                <!-- Modal -->
                <div id="bankModal" class="fixed inset-0 z-50 bg-gray-900 bg-opacity-50 flex justify-center items-center hidden">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-bold text-gray-800">Liste des Banques</h2>
                            <button onclick="closeBankModal()" class="text-gray-600 hover:text-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9l4-4a1 1 0 1 1 1.414 1.414L11.414 10l4 4a1 1 0 1 1-1.414 1.414l-4-4-4 4a1 1 0 1 1-1.414-1.414l4-4L5.293 5.293A1 1 0 1 1 6.707 3.88L10 7.17z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>

                        <div id="bankList" class="space-y-3 max-h-[60vh] overflow-y-auto">
                            <!-- Banks will be listed here -->
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button onclick="openBankForm()" class="bg-green-500 text-white mx-1 px-4 py-2 rounded-md hover:bg-green-600 transition">Ajouter</button>
                            <button onclick="closeBankModal()" class="bg-gray-500 text-white mx-1 px-4 py-2 rounded-md hover:bg-gray-600 transition">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Create/Edit Bank Modal -->
                <div id="bankFormModal" class="hidden fixed z-50 inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center">
                    <div class="bg-white p-6 rounded shadow-lg w-96">
                        <h2 class="text-xl font-semibold mb-4">Ajouter/Modifier une Banque</h2>
                        
                        <input type="hidden" id="bank_id">

                        <label>Banque:</label>
                        <input type="text" id="bank_name" class="w-full p-2 border rounded">

                        <label>Agence:</label>
                        <input type="text" id="bank_agency" class="w-full p-2 border rounded">

                        <label>RIB:</label>
                        <input type="text" id="bank_rib" class="w-full p-2 border rounded">

                        <label>Titulaire du compte:</label>
                        {{-- <input type="text" id="bank_holder" class="w-full p-2 border rounded"> --}}
                        <select id="bank_holder" class="w-full p-2 border rounded">
                            <option value="PROMOSTONE">PROMOSTONE</option>
                            <option value="DISTRISTONE">DISTRISTONE</option>
                        </select>

                        <button onclick="saveBank()" class="bg-blue-600 text-white px-4 py-2 rounded mt-2">Enregistrer</button>
                        <button onclick="closeBankForm()" class="bg-gray-500 text-white px-4 py-2 rounded mt-2">Annuler</button>
                    </div>
                </div>


                @can("create reglements")
                    <a href="{{ route('reglements.create') }}" class="btnA">
                        <svg class="w-7 h-6 text-gray-200 dark:text-white " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                @endcan
                
                <div class="p-4 text-gray-900 dark:text-gray-100 overflow-x-auo">
                    <h2 class="font-serif uppercase underline text-center text-gray-600  mb-12 text-2xl font-bold">Reglements Table</h2>

                    <!-- Filters -->
                    <div class="flex justify-between items-center mb-6 bg-gray-00 p-4 rounded-lg ">
                        <!-- Date Range Filter -->
                        <div class="flex flex-col">
                            <label for="date_range" class="text-sm font-medium text-gray-600">Plage de dates</label>
                            <div class="flex gap-4">
                                <input type="date" id="date_from" class="border rounded p-2 w-30">
                                <input type="date" id="date_to" class="border rounded p-2 w-30">
                            </div>
                        </div>
            
                        <!-- Payment Mode Filter -->
                        <div class="flex flex-col">
                            <label for="payment_mode" class="text-sm font-medium text-gray-600">Mode de paiement</label>
                            <select id="payment_mode" class="border rounded p-2 w-48">
                                <option value="">All</option>
                                <option value="Espèce ">Espèce </option>
                                <option value="Chèque">Chèque</option>
                                <option value="Virement">Virement</option>
                                <option value="ChequeVirement">Chèque & Virement</option>
                            </select>
                        </div>
            
                        <!-- Bank Account Filter -->
                        <div class="flex flex-col">
                            <label for="bank_account" class="text-sm font-medium text-gray-600">Compte bancaire</label>
                            <select id="bank_account" class="border rounded p-2 w-48">
                                <option value="">All</option>
                                <!-- Options should be populated dynamically -->
                            </select>
                        </div>
                    </div>

                    <table id="reglements-table" class="overflow-x-auto min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-sm text-gray-500 dark:text-gray-400 border">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <tr>
                                {{-- <th scope="col" class="px-6 py-3">ID</th> --}}
                                <th scope="col" class="px-6 py-3">No BL</th>
                                <th scope="col" class="px-6 py-3">Code Client</th>
                                <th scope="col" class="px-6 py-3">RAISON</th>
                                <th scope="col" class="px-6 py-3">Montant</th>
                                <th scope="col" class="px-6 py-3">mode</th>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3">Mode Paiement</th>
                                <th scope="col" class="px-6 py-3">échéance</th>
                                <th scope="col" class="px-6 py-3">Créé par</th>
                                <th scope="col" class="px-6 py-3">Encaissement</th>
                                <th scope="col" class="px-6 py-3">Action</th>
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


    <div id="chequeModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50 transition-opacity">
        <div class="bg-white p-6 rounded-xl max-w-lg w-full shadow-lg">
            <!-- Header -->
            <div class="flex justify-between items-center border-b pb-3">
                <h5 class="text-2xl font-bold text-gray-800">Détails du Paiement</h5>
                <button type="button" class="text-gray-500 hover:text-gray-700 transition" onclick="closeChequeModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
    
            <!-- Modal Body -->
            <div class="mt-4 space-y-3 text-gray-700">
                <div class="cheque-field">
                    <p class="font-semibold">Référence: <span class="font-normal" id="cheque-ref"></span></p>
                </div>
                <div class="cheque-field">
                    <p class="font-semibold">Date d'échéance: <span class="font-normal" id="cheque-date"></span></p>
                </div>
                <p class="font-semibold">Date d'encaissement: <span class="font-normal" id="cheque-date-encaissement"></span></p>
                <p class="font-semibold">Type de banque: <span class="font-normal" id="cheque-type-bank"></span></p>
            </div>
    
            <!-- Footer -->
            <div class="mt-6 flex justify-end">
                <button class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition" onclick="closeChequeModal()">
                    Fermer
                </button>
            </div>
        </div>
    </div>
    
    

        <div id="multiReglementModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-500 bg-opacity-75">
            <div class="bg-white p-6 rounded-lg max-w-lg w-full shadow-lg">
                <div class="flex justify-between items-center mb-4">
                    <h5 class="text-xl font-semibold">Détails Multi Règlement</h5>
                    <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeMultiReglementModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div>
                    <p><strong>Nombre de BLs :</strong> <span id="multi-bls-count"></span></p>
                    <p><strong>Montant Total :</strong> <span id="multi-montant-total"></span> DH</p>
                    <div class="mt-4">
                        <p class="font-semibold">Liste des BLs :</p>
                        <ul id="multi-bls-list" class="mt-2 space-y-2"></ul>
                    </div>
                </div>
            </div>
        </div>
    

        <!-- Modal -->
        <div id="chequeModalEncaisse" class="hidden fixed inset-0 z-50 overflow-auto bg-gray-800 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded shadow-lg w-96">
                <h2 class="text-xl font-semibold mb-4">Encaisser le Chèque</h2>
                
                <input type="hidden" id="cheque_id">
                
                <div class="mb-4">
                    <label for="encaissement_date" class="block text-sm font-medium">Date d'encaissement</label>
                    <input type="date" id="encaissement_date" class="w-full p-2 border rounded">
                </div>

                <div class="mb-4">
                    <label for="type_bank" class="block text-sm font-medium">Banque</label>
                    <select id="type_bank" class="w-full p-2 border rounded">
                        <option value="" hidden>Sélectionnez une banque</option>
                    </select>
                </div>

                <div class="flex justify-end">
                    <button id="closeModal" class="px-4 py-2 bg-gray-500 text-white rounded mr-2">Annuler</button>
                    <button id="submitEncaissement" class="px-4 py-2 bg-blue-600 text-white rounded">Valider</button>
                </div>
            </div>
        </div>

    
    
    @endcan

<script>

// $(document).ready(function() {
//     let table = $('#reglements-table').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: {
//             url: '{{ route('reglements.index') }}',
//             data: function(d) {
//                 d.date_from = $('#date_from').val();
//                 d.date_to = $('#date_to').val();
//                 d.payment_mode = $('#payment_mode').val();
//                 d.bank_account = $('#bank_account').val();
//             }
//         },
//         columns: [
//             // { data: 'id', name: 'id' },
//             { data: 'no_bl', name: 'no_bl',
//                 render: function(data, type, row) {
//                     if (row.bls_count > 0) {
//                         // console.log(row.montant_total);
//                         // console.log(row.bls_list);
//                         return `<span class="bg-green-200" font-weight: bold;">${data}</span>`;
                        
//                     }
//                     return data;
//                 }
//              },
//             { data: 'code_client', name: 'code_client' },
//             { data: 'name_client', name: 'name_client' },
//             { data: 'montant', name: 'montant' },
//             { 
//                 data: 'mode', 
//                 name: 'mode',
//                 render: function(data, type, row) {
//                     if (type === 'display' || type === 'filter') {
//                         if (!data) return ''; // Handle null or undefined values
//                         let modeUpper = data.toUpperCase();
//                         let color = (modeUpper === 'AVANCE') ? 'green' : 'blue';
//                         return `<span style="color:${color}; font-weight: bold;">${modeUpper}</span>`;
//                     }
//                     return data;
//                 }
//             },
//             { data: 'date', name: 'date' },
//             { data: 'type_pay', name: 'type_pay' },
//             { data: 'date_chq', name: 'date_chq' ,
//                 render: function(data, type, row) {
//                     if (row.date_encaissement && row.date_chq) {
//                         // console.log(row.montant_total);
//                         return `<span class="bg-green-200" font-weight: bold;">${data}</span>`;
//                     }
//                     return data;
//                 }
//             },
//             { data: 'user-name', name: 'user-name' },
//             { data: 'date_encaissement', name: 'date_encaissement' },
//             {
//                 data: 'actions',
//                 name: 'actions',
//                 orderable: false,
//                 searchable: false
//             }
//         ],
//         responsive: true,
//         lengthMenu: [10, 5, 15, 25, 50],
//         order: [[0, 'desc']],
//         language: {
//             paginate: {
//                 previous: "&laquo;",
//                 next: "&raquo;"
//             }
//         }
//     });

//     // Reload table on filter change
//     $('#date_from, #date_to, #payment_mode, #bank_account').on('change', function() {
//         console.log( $('#payment_mode').val());
        
//         table.ajax.reload();
//     });

// });
$(document).ready(function() {
    let table = $('#reglements-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('reglements.index') }}',
            data: function(d) {
                d.date_from = $('#date_from').val();
                d.date_to = $('#date_to').val();
                d.payment_mode = $('#payment_mode').val();
                d.bank_account = $('#bank_account').val();
            }
        },
        dom: '<"row"<"col-md-4"l><"col-md-4"f><"col-md-4 text-end"B>>rtip',
        buttons: [
            // {
            //     extend: 'pdfHtml5',
            //     text: 'Export PDF',
            //     className: 'btn btn-danger',
            //     orientation: 'landscape',
            //     pageSize: 'A4',
            //     title: 'Liste des règlements',
            //     exportOptions: {
            //         columns: ':visible:not(:last-child)' // Exclude last column (Actions)
            //     }
            // }
        ]
        ,
        columns: [
            { data: 'no_bl', name: 'no_bl' },
            { data: 'code_client', name: 'code_client' },
            { data: 'name_client', name: 'name_client' },
            { data: 'montant', name: 'montant' },
            { data: 'mode', name: 'mode' },
            { data: 'date', name: 'date' },
            { data: 'type_pay', name: 'type_pay' },
            { data: 'date_chq', name: 'date_chq' },
            { data: 'user-name', name: 'user-name' },
            { data: 'date_encaissement', name: 'date_encaissement' },
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false 
            }
        ],
        responsive: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        order: [[0, 'desc']],
        language: {
            paginate: {
                previous: "&laquo;",
                next: "&raquo;"
            }
        }
    });

    // Reload table on filter change
    $('#date_from, #date_to, #payment_mode, #bank_account').on('change', function() {
        table.ajax.reload();
    });
});



    $(document).on('click', '.view-cheque, .view-virment', function () {
        const isCheque = $(this).hasClass('view-cheque'); // Check if it's a cheque
        const ref = $(this).data('ref') || 'N/A';
        const date = $(this).data('date') || 'N/A';
        const dateEncaissement = $(this).data('date_encaissement') || 'Pas Encore';
        const typeBank = $(this).data('type_bank') || 'Pas Encore';

        // Show cheque-specific fields if it's a cheque, otherwise hide them
        if (isCheque) {
            $('#cheque-ref').text(ref).closest('.cheque-field').show();
            $('#cheque-date').text(date).closest('.cheque-field').show();
        } else {
            $('.cheque-field').hide(); // Hide cheque fields for virement
        }

        $('#cheque-date-encaissement').text(dateEncaissement);
        $('#cheque-type-bank').text(typeBank);

        $('#chequeModal').removeClass('hidden'); // Show modal
    });

    function closeChequeModal() {
        $('#chequeModal').addClass('hidden'); // Hide modal
    }



    $(document).on('click', '.view-multi-reglement', function () {
        const blsCount = $(this).data('bls-count');
        const montantTotal = $(this).data('montant-total');
        // const blsList = $(this).data('bls-list').split(', ');
        const blsList = $(this).data('bls-list').replace(/^"|"$/g, '').split(', ');

        $('#multi-bls-count').text(blsCount);
        $('#multi-montant-total').text(montantTotal);

        let listHTML = '';
        blsList.forEach(bl => {
            listHTML += `<li class="bg-gray-100 p-2 rounded-md shadow-sm">${bl}</li>`;
        });

        $('#multi-bls-list').html(listHTML);
        $('#multiReglementModal').removeClass('hidden');
    });

    function closeMultiReglementModal() {
        $('#multiReglementModal').addClass('hidden');
    }

// ____________________________________________________________________________
//=-0=-0=-0 get banks option to select bank for enciassement part 0=-0=-0=-0
    document.addEventListener("DOMContentLoaded", function () {
        fetch('/banks')
            .then(response => response.json())
            .then(data => {
                const selectBank = document.getElementById('type_bank');
                const selectBankFilter = document.getElementById('bank_account'); // Filter Select

                // Clear existing options (except "All")
                selectBankFilter.innerHTML = '<option value="">All</option>';
                // selectBankEncaisse.innerHTML = '';

                // Add bank options first
                data.forEach(bank => {
                    let option = document.createElement('option');
                    option.value = bank.name + " - " + bank.titulaire;
                    option.textContent = bank.name + " (" + bank.titulaire + ")";
                    selectBank.appendChild(option);

                    let option2 = document.createElement('option');
                    option2.value = bank.name + " - " + bank.titulaire;
                    option2.textContent = bank.name + " (" + bank.titulaire + ")";
                    selectBankFilter.appendChild(option2); // Append to filter select
                });

                

                // Add "TIRER EN CASH" option at the end
                let cashOption = document.createElement('option');
                cashOption.value = "TIRER EN CASH";
                cashOption.textContent = "TIRER EN CASH";
                selectBank.appendChild(cashOption);

                let cashOption2 = document.createElement('option');
                cashOption2.value = "TIRER EN CASH";
                cashOption2.textContent = "TIRER EN CASH";
                selectBankFilter.appendChild(cashOption2);
            })
            .catch(error => console.error('Error loading banks:', error));
    });


    $(document).ready(function () {
        // Open Modal When Clicking the Button
        $(document).on('click', '.encaisse-cheque', function () {
            let chequeId = $(this).data('id');
            $('#cheque_id').val(chequeId);
            $('#chequeModalEncaisse').removeClass('hidden');
        });

        // Close Modal
        $('#closeModal').click(function () {
            $('#chequeModalEncaisse').addClass('hidden');
        });

        // Submit Encashment Data
        $('#submitEncaissement').click(function () {
            let chequeId = $('#cheque_id').val();
            let encaissementDate = $('#encaissement_date').val();
            let typeBank = $('#type_bank').val();

            $.ajax({
                url: '/encaisser-cheque/' + chequeId,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    date_encaissement: encaissementDate,
                    type_bank: typeBank
                },
                success: function (response) {
                    alert('Chèque encaissé avec succès!');
                    $('#chequeModalEncaisse').addClass('hidden');
                    location.reload(); // Refresh DataTable
                },
                error: function () {
                    alert('Erreur lors de l\'encaissement du chèque.');
                }
            });
        });
    });




// GESTION DE BANK GESTION DE BANK GESTION DE BANK GESTION DE BANK GESTION DE BANK GESTION DE BANK GESTION DE BANK GESTION DE BANK GESTION DE BANK GESTION DE BANK 
    function fetchBanks() {
        fetch('/banks')
            .then(response => response.json())
            .then(data => {
                let list = document.getElementById('bankList');
                list.innerHTML = '';
                data.forEach(bank => {
                    list.innerHTML += `
                        <div class="flex justify-between items-center bg-white shadow-md rounded-lg p-4 border">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">${bank.name}</h3>
                                <p class="text-gray-600 text-md">Agence: <span class="font-bold">${bank.agence}</span></p>
                                <p class="text-gray-600 text-md">RIB: <span class="font-bold">${bank.rib}</span></p>
                                <p class="text-gray-600 text-md">Titulaire: <span class="font-bold">${bank.titulaire}</span></p>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="editBank(${bank.id}, '${bank.name}', '${bank.agence}', '${bank.rib}', '${bank.titulaire}')" 
                                    class="bg-blue-500 text-white px-3 py-2 rounded-md hover:bg-blue-600 transition flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.293 2.293a1 1 0 0 1 1.414 0l4 4a1 1 0 0 1 0 1.414l-9 9a1 1 0 0 1-.293.217l-3 1a1 1 0 0 1-1.217-1.217l1-3a1 1 0 0 1 .217-.293l9-9a1 1 0 0 1 0-1.414l-4-4a1 1 0 0 1 0-1.414zM13 3L16 6l-8 8-2 1 1-2 8-8z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                                <button onclick="deleteBank(${bank.id})" 
                                    class="bg-red-500 text-white px-3 py-2 rounded-md hover:bg-red-600 transition flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1h3a1 1 0 1 1 0 2H4a1 1 0 1 1 0-2h3V2zm2 6a1 1 0 0 1 1 1v5a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm-4 0a1 1 0 0 1 1 1v5a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1zm8 0a1 1 0 0 1 1 1v5a1 1 0 0 1-2 0V9a1 1 0 0 1 1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>`;
                });

                document.getElementById('bankModal').classList.remove('hidden');
            });
    }

    function openBankForm() {
        document.getElementById('bankFormModal').classList.remove('hidden');
    }

    function closeBankModal() {
        document.getElementById('bankModal').classList.add('hidden');
    }

    function closeBankForm() {
        document.getElementById('bankFormModal').classList.add('hidden');
        document.getElementById('bank_id').value = '';
        document.getElementById('bank_name').value = '';
        document.getElementById('bank_agency').value = '';
        document.getElementById('bank_rib').value = '';
        document.getElementById('bank_holder').value = '';
    }

    function saveBank() {
        let id = document.getElementById('bank_id').value;
        let name = document.getElementById('bank_name').value;
        let agence = document.getElementById('bank_agency').value;
        let rib = document.getElementById('bank_rib').value;
        let titulaire = document.getElementById('bank_holder').value;

        let url = id ? `/banks/${id}` : '/banks';
        let method = id ? 'PUT' : 'POST';

        
        fetch(url, {
            method: method,
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Include CSRF token
            },
            body: JSON.stringify({ name, agence, rib, titulaire }),
            
        })
        .then(response => response.json())
        .then(() => {
            closeBankForm();
            fetchBanks();
        });
        document.getElementById('bank_id').value = '';
        document.getElementById('bank_name').value = '';
        document.getElementById('bank_agency').value = '';
        document.getElementById('bank_rib').value = '';
        document.getElementById('bank_holder').value = '';
    }

    function editBank(id, name, agence, rib, titulaire) {
        document.getElementById('bank_id').value = id;
        document.getElementById('bank_name').value = name;
        document.getElementById('bank_agency').value = agence;
        document.getElementById('bank_rib').value = rib;
        document.getElementById('bank_holder').value = titulaire;

        openBankForm();
    }

    function deleteBank(id) {
        Swal.fire({
            title: "Êtes-vous sûr?",
            text: "Cette action est irréversible!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Oui, supprimer!",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/banks/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur lors de la suppression');
                    }
                    return response.json();
                })
                .then(() => {
                    Swal.fire("Supprimé!", "La banque a été supprimée avec succès.", "success");
                    fetchBanks(); // Refresh the bank list
                })
                .catch(error => {
                    Swal.fire("Erreur!", "Un problème est survenu.", "error");
                    console.error('Error:', error);
                });
            }
        });
    }

</script>
@endsection