@extends('layouts.app')

@section('content')

    <style>
        /* Same styles as your client page */
        .dataTables_wrapper .dataTables_length select {
            background-color: rgb(216, 233, 244);
            width: 120px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: rgb(216, 233, 244);
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            margin-left: 5px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #47acb1;
        }

        #sales-table {
            width: 100%;
            border-collapse: collapse;
        }

        #sales-table th, #sales-table td {
            padding: 12px;
            border-bottom: 1px solid #fdffff;
        }

        #sales-table th {
            background-color: #2b939f;
            color: white;
        }

        #sales-table tbody tr:nth-child(even) {
            background-color: #e5f3f8;
        }

        #sales-table tbody tr:hover {
            background-color: #ddd;
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
            background-color:#3ba5b0;
            float: left;
            /* margin-right: 3px; */
            margin: 3px;
            border-radius: 10%;
        }
        .btnA svg:hover{
            background-color:#ffffff;
            color: #3ba5b0;
        }
    </style>

@can("view sales")

    <div class="py-4">
        <div class="max-w-15xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                {{-- <a href="{{ route('sales.upload') }}" class="btnA">
                    <svg class="w-6 h-6 text-gray-200 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M12 3a1 1 0 0 1 .78.375l4 5a1 1 0 1 1-1.56 1.25L13 6.85V14a1 1 0 1 1-2 0V6.85L8.78 9.626a1 1 0 1 1-1.56-1.25l4-5A1 1 0 0 1 12 3ZM9 14v-1H5a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-4v1a3 3 0 1 1-6 0Zm8 2a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H17Z" clip-rule="evenodd"/>
                      </svg>
                </a> --}}
                
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="font-serif uppercase underline text-gray-600  text-center mb-4 text-2xl font-bold">Les achats</h2>

                    <table id="sales-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-sm text-gray-500 dark:text-gray-400 border">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase">
                            <tr>
                                {{-- <th>ID</th> --}}
                                <th>No BL</th>
                                <th>Date de BL</th>
                                {{-- <th>temps</th> --}}
                                {{-- <th>Code Client</th> --}}
                                <th>Fournisseur</th>
                                <th>Ref Produit</th>
                                <th>Produit</th>
                                <th>Long</th>
                                <th>Large</th>
                                <th>Nombre</th>
                                <th>Quantité</th>
                                <th>Prix Unitaire</th>
                                <th>Montant</th>
                                {{-- <th>Actions</th> --}}
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
@endcan
    <script>
         $(document).ready(function () {
            $('#sales-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('achat.index') }}',
                columns: [
                    // { data: 'id', name: 'achats.id' },
                    { data: 'no_bl', name: 'achats.no_bl' },
                    // { data: 'annee', name: 'sales.annee' },
                    { data: 'date', name: 'achats.date' }, 
                    // { data: 'morocco_time', name: 'morocco_time' },
                    // { data: 'id_fournisseur', name: 'achats.id_fournisseur' },
                    { data: 'client_name', name: 'clients.name' },
                    { data: 'ref_produit', name: 'achats.ref_produit' },
                    { data: 'produit', name: 'achats.produit' },
                    { data: 'longueur', name: 'achats.longueur' },
                    { data: 'largeur', name: 'achats.largeur' },
                    { data: 'nbr', name: 'achats.nbr' },
                    { data: 'qte', name: 'achats.qte' },
                    {
                        data: 'prix_unitaire',
                        name: 'achats.prix_unitaire',
                        render: function (data) {
                            return formatNumberWithSpaces(data);
                        }
                    },
                    {
                        data: 'montant',
                        name: 'achats.montant',
                        render: function (data) {
                            return formatNumberWithSpaces(data);
                        }
                    }
                    // {
                    //     data: 'actions',
                    //     name: 'actions',
                    //     orderable: false,
                    //     searchable: false
                    // }
                ],
                responsive: true,
                lengthMenu: [10, 5, 15, 25, 50],
                order: [[0, 'asc']], // Default sorting by 'no_bl' column
                language: {
                    paginate: {
                        previous: "&laquo;",
                        next: "&raquo;"
                    }
                }
            });
        });
        function formatNumberWithSpaces(number) {
            if (number == null) return '';
            return number
                .toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }

    </script>

@endsection