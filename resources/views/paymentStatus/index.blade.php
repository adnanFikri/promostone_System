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
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Payment Status') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-center mb-4 text-2xl font-bold">Payment Status Table</h2>

                    <table id="payment-status-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-sm text-gray-500 dark:text-gray-400 border">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">Code Client</th>
                                <th scope="col" class="px-6 py-3">Client</th>
                                <th scope="col" class="px-6 py-3">Nombre Bls</th>
                                <th scope="col" class="px-6 py-3">Montant totale $</th>
                                <th scope="col" class="px-6 py-3">Nombre Reglm</th>
                                <th scope="col" class="px-6 py-3">Montant Reglm $</th>
                                <th scope="col" class="px-6 py-3">Solde Restant $</th>
                                {{-- <th scope="col" class="px-6 py-3">Actions</th> --}}
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

    {{-- <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script> --}}
    {{-- <script>
        $(document).ready(function() {
            $('#payment-status-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('paymentStatus.index') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'code_client', name: 'code_client' },
                    { data: 'number_sales', name: 'number_sales' },
                    { data: 'montant_total', name: 'montant_total' },
                    { data: 'number_paid', name: 'number_paid' },
                    { data: 'payed_total', name: 'payed_total' },
                    { data: 'remaining_balance', name: 'remaining_balance' },
                    // {
                    //     data: 'actions',
                    //     name: 'actions',
                    //     orderable: false,
                    //     searchable: false
                    // }
                ],
                responsive: true,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    paginate: {
                        previous: "&laquo;",
                        next: "&raquo;"
                    }
                }
            });
        });
    </script> --}}

    <script>
        $(document).ready(function() {
            $('#payment-status-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('paymentStatus.index') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'code_client', name: 'code_client' },
                    { data: 'name_client', name: 'name_client' }, // Add this line
                    { data: 'number_sales', name: 'number_sales' },
                    { data: 'montant_total', name: 'montant_total' },
                    { data: 'number_paid', name: 'number_paid' },
                    { data: 'payed_total', name: 'payed_total' },
                    { data: 'remaining_balance', name: 'remaining_balance' },
                    // Uncomment if needed
                    // {
                    //     data: 'actions',
                    //     name: 'actions',
                    //     orderable: false,
                    //     searchable: false
                    // }
                ],
                responsive: true,
                lengthMenu: [5, 10, 25, 50],
                language: {
                    paginate: {
                        previous: "&laquo;",
                        next: "&raquo;"
                    }
                }
            });
        });
    </script>
</x-app-layout>
