<x-app-layout>
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

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reglements') }}
        </h2>
    </x-slot>

    <div class="py-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <a href="{{ route('reglements.create') }}" class="btnA">
                    <svg class="w-7 h-6 text-gray-200 dark:text-white " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                      </svg>
                </a>
                
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-center mb-4 text-2xl font-bold">Reglements Table</h2>

                    <table id="reglements-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-sm text-gray-500 dark:text-gray-400 border">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">Code Client</th>
                                <th scope="col" class="px-6 py-3">Client</th>
                                <th scope="col" class="px-6 py-3">Montant</th>
                                <th scope="col" class="px-6 py-3">Date</th>
                                <th scope="col" class="px-6 py-3">Type de Paiement</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    {{-- <script>
        $(document).ready(function() {
            $('#reglements-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('reglements.index') }}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'code_client', name: 'code_client' },
                    { data: 'name_client', name: 'name_client' },
                    { data: 'montant', name: 'montant' },
                    { data: 'date', name: 'date' },
                    { data: 'type_pay', name: 'type_pay' },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }
                ],
                responsive: true,
                lengthMenu: [5, 10, 25, 50],
                order: [[0, 'desc']], // Default sorting by the 'id' column in descending order
                language: {
                    paginate: {
                        previous: "&laquo;",
                        next: "&raquo;"
                    }
                }
            });
        });



        // delete action 
        $('.delete').on('click', function (e) {
            e.preventDefault();
            var form = $(this).next('form');
            if (confirm('Are you sure you want to delete this Règlement?')) {
                form.submit();
            }
        });

    </script> --}}

    <script>
       $(document).ready(function() {
    $('#reglements-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('reglements.index') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'code_client', name: 'code_client' },
            { data: 'name_client', name: 'name_client' },
            { data: 'montant', name: 'montant' },
            { data: 'date', name: 'date' },
            { data: 'type_pay', name: 'type_pay' },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false
            }
        ],
        responsive: true,
        lengthMenu: [5, 10, 25, 50],
        order: [[0, 'desc']],
        language: {
            paginate: {
                previous: "&laquo;",
                next: "&raquo;"
            }
        }
    });

    // Delete action with SweetAlert confirmation
    // $(document).on('click', '.delete', function(e) {
    //     e.preventDefault(); // Prevent default form submission
    //     var formId = $(this).data('form-id'); // Get the form ID dynamically
    //     var form = $('#delete-form-' + formId); // Find the form using the id

    //     if(confirm("are you sure")){
    //         form.submit();
    //     }
    //     // // Show SweetAlert confirmation dialog
    //     // Swal.fire({
    //     //     title: 'Are you sure?',
    //     //     text: 'You won\'t be able to revert this!',
    //     //     icon: 'warning',
    //     //     showCancelButton: true,
    //     //     confirmButtonText: 'Yes, delete it!',
    //     //     cancelButtonText: 'No, cancel!',
    //     //     reverseButtons: true
    //     // }).then((result) => {
    //     //     if (result.isConfirmed) {
    //     //         form.submit(); // Submit the form if confirmed
    //     //     }
    //     //     // No need for else block because if canceled, nothing happens
    //     // });
    // });
});

    </script>
</x-app-layout>
