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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                @can("create reglements")
                    <a href="{{ route('reglements.create') }}" class="btnA">
                        <svg class="w-7 h-6 text-gray-200 dark:text-white " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                        </svg>
                    </a>
                @endcan
                
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="font-serif uppercase underline text-center text-gray-600  mb-12 text-2xl font-bold">Reglements Table</h2>

                    <table id="reglements-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-sm text-gray-500 dark:text-gray-400 border">
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

    
    <div id="chequeModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-gray-500 bg-opacity-75">
        <div class="bg-white p-6 rounded-lg max-w-lg">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-semibold mr-6">Cheque Details</h5>
                <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeChequeModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mt-4">
                <p><strong>Reference:</strong> <span id="cheque-ref"></span></p>
                <p><strong>Date d'échéance:</strong> <span id="cheque-date"></span></p>
                <p><strong>Date d'encaissement:</strong> <span id="cheque-date-encaissement"></span></p>
                <p><strong>Type de banque:</strong> <span id="cheque-type-bank"></span></p>
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
                        <option value="">Sélectionnez une banque</option>
                        <option value="BMCE Bank">BMCE Bank</option>
                        <option value="Al Chaabi Bank">Al Chaabi Bank</option>
                        <option value="CIH Bank">CIH Bank</option>
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
$(document).ready(function() {
    $('#reglements-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('reglements.index') }}',
        columns: [
            // { data: 'id', name: 'id' },
            { data: 'no_bl', name: 'no_bl',
                render: function(data, type, row) {
                    if (row.bls_count > 0) {
                        // console.log(row.montant_total);
                        console.log(row.bls_list);
                        return `<span class="bg-green-200" font-weight: bold;">${data}</span>`;
                        
                    }
                    return data;
                }
             },
            { data: 'code_client', name: 'code_client' },
            { data: 'name_client', name: 'name_client' },
            { data: 'montant', name: 'montant' },
            { 
                data: 'mode', 
                name: 'mode',
                render: function(data, type, row) {
                    if (type === 'display' || type === 'filter') {
                        if (!data) return ''; // Handle null or undefined values
                        let modeUpper = data.toUpperCase();
                        let color = (modeUpper === 'AVANCE') ? 'green' : 'blue';
                        return `<span style="color:${color}; font-weight: bold;">${modeUpper}</span>`;
                    }
                    return data;
                }
            },
            { data: 'date', name: 'date' },
            { data: 'type_pay', name: 'type_pay' },
            { data: 'date_chq', name: 'date_chq' ,
                render: function(data, type, row) {
                    if (row.date_encaissement) {
                        // console.log(row.montant_total);
                        return `<span class="bg-green-200" font-weight: bold;">${data}</span>`;
                    }
                    return data;
                }
            },
            { data: 'user-name', name: 'user-name' },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ],
        responsive: true,
        lengthMenu: [10, 5, 15, 25, 50],
        order: [[0, 'desc']],
        language: {
            paginate: {
                previous: "&laquo;",
                next: "&raquo;"
            }
        }
    });

});



    $(document).on('click', '.view-cheque', function () {
        const ref = $(this).data('ref');
        const date = $(this).data('date');
        const dateEncaissement = $(this).data('date_encaissement');
        const typeBank = $(this).data('type_bank');

        $('#cheque-ref').text(ref || 'N/A');
        $('#cheque-date').text(date || 'N/A');
        $('#cheque-date-encaissement').text(dateEncaissement || 'N/A');
        $('#cheque-type-bank').text(typeBank || 'N/A');

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


</script>
@endsection