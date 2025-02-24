
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

.bg-light-blue {
    background-color: #dee2e531; 
    /* border: 1px solid #ddd; */
}


    </style>
  
  {{-- @can('view bons_livraison') --}}
    <div class="py-5">
        <div class="max-w-12xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg shadow-md">
                {{-- @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('ChefAtelier') || auth()->user()->hasRole('SuperAdmin'))
                    <button id="openModalBtn" 
                        class="px-2 py-1 bg-blue-50 text-white font-semibold rounded-md hover:bg-blue-100 focus:outline-none">
                        <svg class="w-[24px] h-[24px] text-blue-800 hover:text-blue-400 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>
                        </svg>
                    </button>
                @endif --}}
                <h2 class="text-2xl font-bold font-mono mb-6 text-center pb-4 border-b-4 mx-12">List des Bons de Coupe</h2>
                <!-- Button to open modal -->

                <!-- Modal -->
                <div id="modal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg shadow-lg w-96">
                        <!-- Modal Header -->
                        <div class="px-4 py-3 bg-gray-100 border-b flex justify-between">
                            <h2 class="text-lg font-semibold">Détails des Coupeurs</h2>
                            <button id="closeModalBtn" class="text-gray-500 hover:text-red-500">&times;</button>
                        </div>

                        <!-- Modal Body -->
                        <div class="p-4 max-h-64 overflow-y-auto">
                            <ul id="coupeursList" class="space-y-2"></ul>
                        </div>

                        <!-- Modal Footer -->
                        <div class="px-4 py-3 bg-gray-100 text-right">
                            <button id="closeFooterBtn" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
                
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
                                <th>coupeur</th>
                                <th>Coupe</th>
                                <th>Finition</th>
                                <th>Date Coupe</th>
                                <th>Coupe Par</th>
                                {{-- <th>N°print</th> --}}
                                <th>print date</th>
                                <th>delay-1</th>
                                <th>delay-2</th>
                                <th>DUREE-T</th>
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
    var userRoles = @json(auth()->user()->getRoleNames());
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
                data: 'coupeur',
                name: 'coupeur',
                render: function(data, type, row) {
                    // let disabled = (!row.isAdmin || !canModifCoupe) ? 'disabled' : ''; 
                    let userHasRole = userRoles.includes('Admin') || userRoles.includes('ChefAtelier') || userRoles.includes('SuperAdmin');
                    let bgColor = data ? 'bg-blue-300' : 'bg-blue-100'; // Blue-200 if null, Blue-400 if not null
                    
                    // Disabled condition based on user role
                    let disabled = userHasRole ? '' : 'disabled';
                    return `
                        <select onchange="updateCoupeur(${row.no_bl}, this.value)" class="rounded-md w-md border border-gray-300 px-2 py-1 ${bgColor}"  ${disabled}
                            title="${data ? data : 'aucun coupeur'}">
                            <option class="bg-gray-100 text-gray-500" value="" ${!data ? 'selected' : ''}>choisir</option>
                            <option class="bg-gray-100 text-black" value="Zghaid Lahcen" ${data === 'Zghaid Lahcen' ? 'selected' : ''}>Zghaid Lahcen</option>
                            <option class="bg-gray-100 text-black" value="Rachid Saadan" ${data === 'Rachid Saadan' ? 'selected' : ''}>Rachid Saadan</option>
                            <option class="bg-gray-100 text-black" value="Blaise" ${data === 'Blaise' ? 'selected' : ''}>Blaise</option>
                            <option class="bg-gray-100 text-black" value="Jamal" ${data === 'Jamal' ? 'selected' : ''}>Jamal</option>
                            <option class="bg-gray-100 text-black" value="Samba" ${data === 'Samba' ? 'selected' : ''}>Samba</option>
                            <option class="bg-gray-100 text-black" value="Kamely Mbarek" ${data === 'Kamely Mbarek' ? 'selected' : ''}>Kamely Mbarek</option>
                        </select>
                    `;
                }
            },
            { 
                data: 'coupe', 
                name: 'coupe', 
                render: function(data, type, row) {
                    let disabled = ((data === 'Oui' && !row.isAdmin) || !canModifCoupe) ? 'disabled' : '';

                    return `
                        <select onchange="updateCoupe(${row.id}, this.value)" class="rounded-md w-md border border-gray-300 px-2 py-1 
                            ${data === 'Oui' ? 'bg-green-300' : data === 'Non' ? 'bg-red-300' : data === 'En cours' ? 'bg-orange-300' : data === 'Sans' ? 'bg-blue-300' : ''}" ${disabled}
                                title="${data === 'En cours' && row.date_encours ? new Date(row.date_encours).toLocaleString('fr-FR', { 
                                    year: 'numeric', 
                                    month: '2-digit', 
                                    day: '2-digit', 
                                    hour: '2-digit', 
                                    minute: '2-digit', 
                                    second: '2-digit', 
                                    hour12: false 
                                }) : ''}"
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
                data: 'finition', 
                name: 'finition', 
                render: function(data, type, row) {
                    let disabled = (( (data === 'Oui' || data === 'Sans') && !row.isAdmin) || !canModifCoupe) ? 'disabled' : '';
                    

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
            
            
            // { data: 'print_nbr', name: 'print_nbr' }, // Commercant column
            { data: 'print_date', name: 'print_date',
                render: function(data, type, row) {
                    return `<span title="nombre d'impression : ${row.print_nbr}" >${data ? data : 'pas encore'}</span>`;
                }
             }, // Commercant column
            { 
                data: 'dureeBeforeCommence', 
                name: 'dureeBeforeCommence',
                createdCell: function(td, cellData, rowData, row, col) {
                    // Add a CSS class to this column
                    $(td).addClass('bg-light-blue'); // Use a CSS class for background color
                }
            },
            { 
                data: 'delayCoupe', 
                name: 'delayCoupe',
                createdCell: function(td, cellData, rowData, row, col) {
                    // Add a background color to this column
                    $(td).addClass('bg-light-blue'); // Use a CSS class for background color
                }
            },
            { 
                data: 'time_difference', 
                name: 'time_difference',
                createdCell: function(td, cellData, rowData, row, col) {
                    // Add a background color to this column
                    $(td).addClass('bg-light-blue'); // Use a CSS class for background color
                }
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


    function updateCoupeur(no_bl, value) {
        // Show confirmation dialog
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: 'Voulez-vous vraiment modifier le Coupeur de ce bon ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, confirmer',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with the update if the user confirms
                fetch(`/bonCoupe/${no_bl}/update-coupeur`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ coupeur: value })
                }).then(response => {
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Mis à jour avec succès !',
                            text: 'Le Coupeur a été mis à jour.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3085d6',
                        });
                        $('#bons-table').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur lors de la mise à jour.',
                            text: 'Un problème est survenu lors de la mise à jour du Coupeur.',
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
                $('#bons-table').DataTable().ajax.reload();
            }
        });
    }

    
 
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
                $('#bons-table').DataTable().ajax.reload();
            }
        });
    }


    // 00000000000000000000000000000000000
    // 0000 coupeur details 00000
    // 000000000000000000000000000000000000
    
    document.getElementById('openModalBtn').addEventListener('click', function () {
    document.getElementById('modal').classList.remove('hidden');
    fetch('/bonCoupe/coupeurs-stats')
        .then(response => response.json())
        .then(data => {
            const list = document.getElementById('coupeursList');
            list.innerHTML = '';
            data.forEach(coupeur => {
                let li = document.createElement('li');
                li.classList.add("flex", "justify-between", "border-b", "py-2", "px-2");
                li.innerHTML = `<span class="font-medium">${coupeur.coupeur}</span>
                                <span class="text-blue-600 font-bold">${coupeur.total_m2} m²</span>`;
                list.appendChild(li);
            });
        })
        .catch(error => console.error('Erreur lors de la récupération des coupeurs:', error));
});

// Close modal
['closeModalBtn', 'closeFooterBtn'].forEach(id => {
    document.getElementById(id).addEventListener('click', function () {
        document.getElementById('modal').classList.add('hidden');
    });
});

</script>


@endsection