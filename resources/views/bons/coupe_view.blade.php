
@extends('layouts.app')

@section('content')

    <!-- Tailwind CSS applied here only -->
    <style>
        /* Custom DataTable styles */
        .dataTables_wrapper .dataTables_length select {
            background-color: rgb(225, 228, 230);
            width: 120px;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
            margin-bottom: 3px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: #dceedc;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            margin-left: 5px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #45a049;
        }

        #bons-table {
            width: 100%;
            border-collapse: collapse;
        }

        #bons-table th, #users-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        #bons-table th {
            background-color: #28597b;
            color: white;
            text-transform: uppercase;
        }

        #bons-table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #bons-table tbody tr:hover {
            background-color: #ddd;
        }

        /* Optional: Style the search input */
        .dataTables_filter input {
            padding: 8px;
            margin-left: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .btnA svg{
            width: 40px;
            height: 35px;
            /* background-color:#4c86af; */
            float: left;
            /* margin-right: 3px; */
            margin: 3px;
            border-radius: 10%;
        }
        .btnA svg:hover{
            /* background-color:#ffffff; */
            color: #4c86af;
        }
        
        #div-actions1 :hover{
            transition: .3s;
            color: #45a049;
        }

select.rounded-md {
    padding-top: 0.25rem; /* Adjust padding on top to bring the options lower */
    padding-bottom: 0.25rem; /* Adjust padding on bottom if needed */
    font-size: 1rem; /* Adjust font size for better alignment */
    line-height: 1.5; /* Ensure proper line height */
}

/* Optional: Adjust the options for better visual alignment */
select.rounded-md option {
    padding: 8px 10px; /* Add some padding to the options for better spacing */
}

/* Adjust the width of the select input */
select.rounded-md.w-md {
    width: 100px; /* You can change this as needed */
}

#bons-table {
    width: 100%;
    table-layout: auto;
}

/* For small screens (max-width 768px), show scrollbar when needed */
@media (max-width: 768px) {
    .overflow-x-aut {
        overflow-x: auto;
    }
}
#container-searchBL{
    display: flex;
    justify-content: end;
    margin-bottom: 3px;
}
#container-searchBL #search-no-bl{
    max-width: 183px;
    height: 40px;
    border: rgb(173, 170, 170) 1px solid;
}


    </style>
  
  {{-- @can('view bons_livraison') --}}
    <div class="py-5">
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h2 class="text-2xl font-bold font-mono mb-6 text-center pb-4 border-b-4 mx-12">List des Bons de Coupe</h2>
                
                <!-- Bons Table -->
                <div id="container-searchBL" >
                    <input type="text" id="search-no-bl" class="border px-2 py-2 rounded" placeholder="No BL">
                </div>
                <div class="overflow-x-aut ">
                    <table id="bons-table" class=" min-w-full text-sm">
                        <thead>
                            <tr>
                                {{-- <th>ID</th> --}}
                                <th>No BL</th>
                                <th>Raison</th>
                                <th>Produits</th>
                                <th>date</th>
                                <th>commercant</th>
                                <th>Coupe</th>
                                <th>Finition</th>
                                <th>Date Coupe</th>
                                <th>Coupe Par</th>
                                <th>N°print</th>
                                <th>print date</th>
                                <th>DUREE</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
{{-- @endcan --}}

<script>
    var canModifCoupe = @json(auth()->user()->can('update coupe bon coup'));
    var table;
    table = $('#bons-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("listBonCoupe.index") }}',
        responsive: true,  // Add this line to enable responsive table
        order: [
            // [5, 'asc'],  // Sort by Coupe column first
            // [6, 'asc'],  // Then by Finition column
            // [3, 'asc']    // Then order by created_at column (index 7), oldest first.
        ],
        columns: [
            //  { data: 'id', name: 'id' }, 
            { data: 'no_bl', name: 'no_bl' },
            { data: 'client', name: 'client' },
            { data: 'products', name: 'products' }, // This column corresponds to products
            { data: 'date', name: 'date' }, // Sale date column
            { data: 'commercant', name: 'commercant' }, // Commercant column
            { 
                data: 'coupe', 
                name: 'coupe', 
                render: function(data, type, row) {
                    let disabled = ((data === 'Oui' && !row.isAdmin) || !canModifCoupe) ? 'disabled' : '';

                    return `
                        <select onchange="updateCoupe(${row.id}, this.value)" class="rounded-md w-md border border-gray-300 px-2 py-1 
                            ${data === 'Oui' ? 'bg-green-300' : data === 'Non' ? 'bg-red-300' : 'bg-orange-300'}" ${disabled}
                        >
                            <option class="bg-gray-100 text-red-400" value="Non" ${data === 'Non' ? 'selected' : ''}>Non</option>
                            <option class="bg-gray-100 text-orange-400" value="En cours" ${data === 'En cours' ? 'selected' : ''}>En cours</option>
                            <option class="bg-gray-100 text-green-400" value="Oui" ${data === 'Oui' ? 'selected' : ''}>Oui</option>
                            
                        </select>
                    `;
                }
            },
            { 
                data: 'finition', 
                name: 'finition', 
                render: function(data, type, row) {
                    let disabled = (( (data === 'Oui' || data === 'Sans') && !row.isAdmin) || !canModifCoupe) ? 'disabled' : '';
                    console.log(data);
                    

                    return `
                        <select onchange="updateFinition(${row.id}, this.value)" class="rounded-md w-md border border-gray-300 px-2 py-1 
                            ${data === 'Oui' ? 'bg-green-300' : data === 'Non' ? 'bg-red-300' : data === 'En cours' ? 'bg-orange-300' : data === 'Sans' ? 'bg-blue-300' : ''}" ${disabled}
                        >
                            <option class="bg-gray-100 text-red-400" value="Non" ${data === 'Non' ? 'selected' : ''}>Non</option>
                            <option class="bg-gray-100 text-orange-400" value="En cours" ${data === 'En cours' ? 'selected' : ''}>En cours</option>
                            <option class="bg-gray-100 text-green-400" value="Oui" ${data === 'Oui' ? 'selected' : ''}>Oui</option>
                            <option class="bg-gray-100 text-blue-400" value="Sans" ${data === 'Sans' ? 'selected' : ''}>Sans</option>
                        </select>
                    `;
                }
            },
            { 
                data: 'date_coupe', 
                name: 'date_coupe',
                render: function(data, type, row) {
                    return data ? data : 'pas encore';
                }
            },
            { data: 'userName', name: 'userName' }, // Commercant column
            
            
            { data: 'print_nbr', name: 'print_nbr' }, // Commercant column
            { data: 'print_date', name: 'print_date' }, // Commercant column
            { 
                data: 'time_difference', 
                name: 'time_difference', 
            },
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <a href="{{ url('/bon-coup/') }}/${row.no_bl}" class="btnA" title="Voir Bon de Livraison">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="print-icon">
                                <path d="M19 8H5v9h14V8zM5 6h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm7 2H8v4h4V8zm0 6H8v4h4v-4z"/>
                            </svg>
                        </a>
                    `;
                }
            },
        ],
        
    });

    $('#search-no-bl').on('keyup', function () {
        let value = this.value.trim();
        if (value === "") {
            table.column(0).search("").draw();
        } else {
            table.column(0).search("^" + value + "$", true, false).draw();
        }
    });
 
    function updateCoupe(id, value) {
        // Show confirmation dialog
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: 'Voulez-vous vraiment modifier le statut de Coupe ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, confirmer',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with the update if the user confirms
                fetch(`/bonCoupe/${id}/update-coupe`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ coupe: value })
                }).then(response => {
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Mis à jour avec succès !',
                            text: 'Le statut de Coupe a été mis à jour.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3085d6',
                        });
                        $('#bons-table').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur lors de la mise à jour.',
                            text: 'Un problème est survenu lors de la mise à jour du statut de Coupe.',
                            confirmButtonText: 'Réessayer',
                            confirmButtonColor: '#d33',
                        });
                        $('#bons-table').DataTable().ajax.reload();
                    }
                }).catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur réseau',
                        text: 'Il y a eu un problème avec la requête. Veuillez réessayer plus tard.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#d33',
                    });
                    $('#bons-table').DataTable().ajax.reload();
                });
            } else {
                // User canceled, no action needed
                console.log('Modification annulée');
                $('#bons-table').DataTable().ajax.reload();
            }
        });
    }


    function updateFinition(id, value) {
        // Show confirmation dialog
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: 'Voulez-vous vraiment modifier le statut de Finition ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, confirmer',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with the update if the user confirms
                fetch(`/bonCoupe/${id}/update-finition`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ finition: value })
                }).then(response => {
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Mis à jour avec succès !',
                            text: 'Le statut de Finition a été mis à jour.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3085d6',
                        });
                        $('#bons-table').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur lors de la mise à jour.',
                            text: 'Un problème est survenu lors de la mise à jour du statut de Finition.',
                            confirmButtonText: 'Réessayer',
                            confirmButtonColor: '#d33',
                        });
                        $('#bons-table').DataTable().ajax.reload();
                    }
                }).catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur réseau',
                        text: 'Il y a eu un problème avec la requête. Veuillez réessayer plus tard.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#d33',
                    });
                    $('#bons-table').DataTable().ajax.reload();
                });
            } else {
                // User canceled, no action needed
                console.log('Modification annulée');
                $('#bons-table').DataTable().ajax.reload();
            }
        });
    }


</script>


@endsection