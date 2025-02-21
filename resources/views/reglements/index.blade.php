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
                <p><strong>Date:</strong> <span id="cheque-date"></span></p>
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
            { data: 'no_bl', name: 'no_bl' },
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
            { data: 'date_chq', name: 'date_chq' },
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
    $('#cheque-ref').text(ref || 'N/A');
    $('#cheque-date').text(date || 'N/A');
    $('#chequeModal').removeClass('hidden'); // Show modal
});

function closeChequeModal() {
    $('#chequeModal').addClass('hidden'); // Hide modal
}


</script>
@endsection