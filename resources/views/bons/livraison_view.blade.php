
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
            background-color: #4c86af;
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

        #openCommercantModalBtn{
            background-color: rgba(130, 46, 130, 0.122);
        }
        #openCommercantModalBtn:hover{
            background-color: rgba(92, 91, 92, 0);
        }
        #openCommercantModalBtn svg{
            color: rgba(186, 16, 186, 0.992);
            transition: .3s;
        }
        #openCommercantModalBtn svg:hover{
            color: rgba(247, 6, 247, 0.992);
        }

        .select_com{
            background-color: rgba(195, 70, 195, 0.388);
        }
    </style>
    
  
  {{-- @can('view bons_livraison') --}}
<div class="py-5">
    <div class="max-w-12xl mx-auto sm:px-6 lg:px-8">
        <div class="p-6 bg-white rounded-lg shadow-md">

            @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('SuperAdmin'))
                <button id="openCommercantModalBtn" 
                        class="px-2 py-1 bg-blue-50 text-white font-semibold rounded-md hover:bg-blue-100 focus:outline-none">
                    <svg class="w-[24px] h-[24px] text-blue-800 hover:text-blue-400 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-3 5h3m-6 0h.01M12 16h3m-6 0h.01M10 3v4h4V3h-4Z"/>
                    </svg>
                </button>
            @endif

            <!-- Modal -->
            {{-- <div id="commercantModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg w-[95%] max-w-4xl p-6">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 bg-gray-100 border-b flex justify-between items-center rounded-t-md">
                        <h2 class="text-xl font-semibold text-gray-800">Détails des Commercants</h2>
                        <button id="closeCommercantModalBtn" class="text-gray-500 hover:text-red-500 text-3xl">&times;</button>
                    </div>

                    <!-- Modal Body -->
                    <div class="p-4 space-y-4 max-h-[60vh] overflow-y-auto">
                        <ul id="commercantsList" class="space-y-4">
                            <!-- Dynamic content will be inserted here -->
                        </ul>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-3 bg-gray-100 rounded-b-md text-right">
                        <button id="closeFooterBtn" class="px-6 py-2 bg-red-500 text-white text-md rounded-md hover:bg-red-600">
                            Fermer
                        </button>
                    </div>
                </div>
            </div> --}}

            <div id="commercantModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-60 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg shadow-lg w-[95%] max-w-4xl p-6">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 bg-gray-100 border-b flex justify-between items-center rounded-t-md">
                        <h2 class="text-xl font-semibold text-gray-800">Détails des Commerçants</h2>
                        <button id="closeCommercantModalBtn" class="text-gray-500 hover:text-red-500 text-3xl">&times;</button>
                    </div>
            
                    <!-- Filter Section -->
                    <div class="p-4 bg-gray-50 rounded-md flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label for="fromDate" class="text-sm font-medium text-gray-700">Date De</label>
                            <input type="date" id="fromDate" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-400 focus:border-blue-400">
                        </div>
                        <div class="flex items-center gap-2">
                            <label for="toDate" class="text-sm font-medium text-gray-700">Date À</label>
                            <input type="date" id="toDate" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-400 focus:border-blue-400">
                        </div>
                        <button id="filterBtn" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                            Filtrer
                        </button>
                    </div>
            
                    <!-- Modal Body -->
                    <div class="p-4 space-y-4 max-h-[60vh] overflow-y-auto">
                        <ul id="commercantsList" class="space-y-4">
                            <!-- Dynamic content will be inserted here -->
                        </ul>
                    </div>
            
                    <!-- Modal Footer -->
                    <div class="px-6 py-3 bg-gray-100 rounded-b-md text-right">
                        <button id="closeFooterBtn" class="px-6 py-2 bg-red-500 text-white text-md rounded-md hover:bg-red-600">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
            



            
            <h2 class="text-2xl font-bold font-mono mb-6 text-center pb-4 border-b-4 mx-12">List des Bons de Livraison</h2>
            
            <div id="container-searchBL" >
                <input type="text" id="search-no-bl" class="border px-2 py-2 rounded" placeholder="No BL">
            </div>
            
            <!-- Bons Table -->
            <div class="overflow-x-aut">
                <table id="bons-table" class="min-w-full text-sm">
                    <thead>
                        <tr>
                            {{-- <th>ID</th> --}}
                            <th>No BL</th>
                            {{-- <th>idclient</th> --}}
                            <th>Raison</th>
                            <th>Produits</th>
                            <th>date</th>
                            <th>commercant</th>
                            <th>Livrée</th>
                            <th>Saisi par</th>
                            {{-- <th>Crée par</th> --}}
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
    var canModiflivree = @json(auth()->user()->can('update livree bon livraison'));
    var userRoles = @json(auth()->user()->getRoleNames());
    var table;
    table = $('#bons-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("listBonLivraison.index") }}',
        responsive: true,  // Add this line to enable responsive table
        order: [[0, 'desc']],
        columns: [
            //  { data: 'id', name: 'id' }, 
            { data: 'no_bl', name: 'no_bl' },
            { data: 'client', name: 'client' },
            //  { data: 'name_client', name: 'name_client' },
            { data: 'products', name: 'products' }, // This column corresponds to products
            { data: 'date', name: 'date' }, // Sale date column
            // { data: 'commercant', name: 'commercant' }, // Commercant column
            { 
                data: 'commercant', 
                name: 'commercant',
                render: function(data, type, row) {
                    let userHasRole = userRoles.includes('Admin') ||  userRoles.includes('SuperAdmin');
                    let bgColor = data ? 'bg-blue-300' : 'bg-blue-100';
                    let disabled = userHasRole ? '' : 'disabled';

                    return `
                        <select onchange="updateCommercant(${row.no_bl}, this.value)" 
                            class="select_com rounded-md w-md border border-gray-300 px-2 py-1 text-xl ${bgColor}" ${disabled} title="${data}">
                            <option class="bg-gray-100 text-gray-500" value="" ${!data ? 'selected' : ''}>Choisir</option>
                            <option class="bg-gray-100 text-black" value="Nourddine" ${data === 'Nourddine' ? 'selected' : ''}>Nourddine</option>
                            <option class="bg-gray-100 text-black" value="Badr" ${data === 'Badr' ? 'selected' : ''}>Badr</option>
                            <option class="bg-gray-100 text-black" value="Mahmoud" ${data === 'Mahmoud' ? 'selected' : ''}>Mahmoud</option>
                            <option class="bg-gray-100 text-black" value="Laila Ettair" ${data === 'Laila Ettair' ? 'selected' : ''}>Laila Ettair</option>
                            <option class="bg-gray-100 text-black" value="Hafida Ech Chaaraouy" ${data === 'Hafida Ech Chaaraouy' ? 'selected' : ''}>Hafida Ech Chaaraouy</option>
                            <option class="bg-gray-100 text-black" value="Hidaya Arahmani" ${data === 'Hidaya Arahmani' ? 'selected' : ''}>Hidaya Arahmani</option>
                            <option class="bg-gray-100 text-black" value="Fatima" ${data === 'Fatima' ? 'selected' : ''}>Fatima</option>
                            <option class="bg-gray-100 text-black" value="Nawal Abli" ${data === 'Nawal Abli' ? 'selected' : ''}>Nawal Abli</option>
                            <option class="bg-gray-100 text-black" value="Aya" ${data === 'Aya' ? 'selected' : ''}>Aya</option>
                            <option class="bg-gray-100 text-black" value="Distristone_Hafida Ech" ${data === 'Distristone_Hafida Ech' ? 'selected' : ''}>Distristone_Hafida Ech</option>
                            <option class="bg-gray-100 text-black" value="Admin" ${data === 'Admin' ? 'selected' : ''}>Admin</option>
                        </select>
                    `;
                }
            },
            { 
                data: 'livree', 
                name: 'livree', 
                render: function(data, type, row) {
                    
                    let disabled = (!canModiflivree) ? 'disabled' : '';
                    return `
                        <select onchange="updateLivree(${row.id}, this.value)" class="rounded-md w-md border border-gray-300 px-2 py-1 
                            ${data === 'Oui' ? 'bg-green-300' : data === 'Non' ? 'bg-red-300' : 'bg-orange-300'}" ${disabled}>
                            <option value="Non" class="text-red-400" ${data === 'Non' ? 'selected' : ''}>Non</option>
                            <option value="Oui" class="text-green-400" ${data === 'Oui' ? 'selected' : ''}>Oui</option>
                        </select>
                    `;
                }
            },
             { data: 'userName', name: 'userName' }, // Commercant column
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false,
                render: function(data, type, row) {
                    return `
                        <div class="flex items-center space-x-2" >
                            <a href="{{ url('/bon-livraison/') }}/${row.no_bl}" class="btnA" title="Voir Bon de Livraison">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="print-icon">
                                    <path d="M19 8H5v9h14V8zM5 6h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2zm7 2H8v4h4V8zm0 6H8v4h4v-4z"/>
                                </svg>
                            </a>
                            
                            @can('update livree bon livraison')
                                <a href="/sales/edit/${row.no_bl}" class="btn btn-primary" title="Edit Bon">
                                    <svg class="w-9 h-9 text-blue-800 dark:text-white mt-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                    </svg>
                                </a>
                            @endcan

                            @if(auth()->user()->hasRole('Admin') || auth()->user()->hasRole('SuperAdmin'))
                                <button class="btn btn-danger" onclick="deleteBon(${row.no_bl})" title="Delete Bon">
                                    <svg class="w-6 h-6 text-red-600 dark:text-white mt-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            @endif
                        </div>
                        
                    `;
                }
            },
        ]
    });

    $('#search-no-bl').on('keyup', function () {
            let value = this.value.trim();
            if (value === "") {
                table.column(0).search("").draw();
            } else {
                table.column(0).search("^" + value + "$", true, false).draw();
            }
        });

    function updateLivree(id, value) {
        fetch(`/bonLivraison/${id}/update-livree`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ livree: value })
        }).then(response => {
            if (response.ok) {
                // Success alert in French
                Swal.fire({
                    icon: 'success',
                    title: 'Mis à jour avec succès !',
                    text: 'Le statut de livraison a été mis à jour.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                    background: '#f8f9fa',
                    iconColor: '#4BB543',
                });
                $('#bons-table').DataTable().ajax.reload();
            } else {
                // Error alert in French
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur lors de la mise à jour.',
                    text: 'Un problème est survenu lors de la mise à jour du statut de livraison.',
                    confirmButtonText: 'Réessayer',
                    confirmButtonColor: '#d33',
                    background: '#f8f9fa',
                    iconColor: '#FF5733',
                });
                $('#bons-table').DataTable().ajax.reload();
            }
        }).catch(error => {
            // If there's a network error or other issue with the fetch request
            Swal.fire({
                icon: 'error',
                title: 'Erreur réseau',
                text: 'Il y a eu un problème avec la requête. Veuillez réessayer plus tard.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#d33',
                background: '#f8f9fa',
                iconColor: '#FF5733',
            });
            $('#bons-table').DataTable().ajax.reload();
        });
    }

    function deleteBon(no_bl) {
        Swal.fire({
            title: "Êtes-vous sûr ?",
            text: "Voulez-vous vraiment supprimer ce Bon de Livraison ?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Oui, supprimer",
            cancelButtonText: "Annuler"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Confirmation requise",
                    text: "Veuillez entrer le mot de passe pour confirmer la suppression.",
                    input: "password",
                    inputPlaceholder: "Entrez le mot de passe",
                    inputAttributes: {
                        autocapitalize: "off"
                    },
                    showCancelButton: true,
                    confirmButtonText: "Confirmer",
                    cancelButtonText: "Annuler",
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    preConfirm: (password) => {
                        if (password !== "pr222") {
                            Swal.showValidationMessage("Mot de passe incorrect !");
                        }
                        return password;
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value === "pr222") {
                        $.ajax({
                            url: '/bon-livraison/' + no_bl,  // Adjust the route as needed
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'  // Ensure CSRF token is included
                            },
                            success: function(response) {
                                Swal.fire({
                                    title: "Supprimé !",
                                    text: response.message,
                                    icon: "success"
                                });
                                table.ajax.reload();  // Reload DataTable after deletion
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    title: "Erreur",
                                    text: xhr.responseJSON.message,
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            }
        });
    }

    function updateCommercant(no_bl, value) {
        console.log(value);
        
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: `Voulez-vous vraiment modifier le Commerçant de ce bon à "${value}" ?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Oui, confirmer',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/update-commercant/${no_bl}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ commercant: value })
                    
                }).then(response => {
                    if (response.ok) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Mis à jour avec succès !',
                            text: 'Le Commerçant a été mis à jour.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#3085d6',
                        });
                        $('#bons-table').DataTable().ajax.reload();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur lors de la mise à jour.',
                            text: 'Un problème est survenu lors de la mise à jour du Commerçant.',
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
                $('#bons-table').DataTable().ajax.reload();
            }
        });
    }

    // Function to format numbers with spaces as thousands separators
    function formatNumberWithSpaces(number) {
        if (number == null) return '';
        return number
            .toString()
            .replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    }

    document.getElementById('openCommercantModalBtn').addEventListener('click', function () {
        document.getElementById('commercantModal').classList.remove('hidden');
        fetchCommercants(); // Fetch initial data
    });

    document.getElementById('filterBtn').addEventListener('click', function () {
        fetchCommercants(); // Fetch filtered data
    });

    function fetchCommercants() {
        const fromDate = document.getElementById('fromDate').value;
        const toDate = document.getElementById('toDate').value;

        let url = `/bonLivraison/commercants-stats`;
        if (fromDate && toDate) {
            url += `?fromDate=${fromDate}&toDate=${toDate}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const list = document.getElementById('commercantsList');
                list.innerHTML = '';  // Clear previous list
                
                data.forEach(commercant => {
                    let li = document.createElement('li');
                    li.classList.add("flex", "justify-between", "bg-gray-50", "p-4", "rounded-xl", "shadow-md", "items-center", "gap-4");

                    // Construct the URL with commercant, fromDate, and toDate parameters
                    let commercantUrl = `/payment-status?commercant=${encodeURIComponent(commercant.commercant)}`;
                    if (fromDate && toDate) {
                        commercantUrl += `&fromDate=${fromDate}&toDate=${toDate}`;
                    } ////////////fefke fefefjepfjepfjepifjeijf stoping here and will continue , add fromDate and toDate 
                    
                    console.log(commercantUrl);
                    
                    li.innerHTML = `
                        <div class="flex-1">
                            <a href="${commercantUrl}" 
                            class="text-lg font-semibold text-gray-800 hover:underline text-blue-600">
                                ${commercant.commercant}
                            </a>
                            <div class="text-sm font-bold text-blue-700 mt-1">
                                ${formatNumberWithSpaces(commercant.total_chiffre_affaire)} MAD
                            </div>
                        </div>
                        <div class="flex flex-col items-end w-52">
                            <input type="number" class="commission-input px-4 py-2 w-full border border-gray-300 rounded-lg text-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400"
                                placeholder="Comm. %" data-chiffre="${commercant.total_chiffre_affaire}" />
                            <span class="text-sm text-green-600 font-semibold commission-result mt-2">Commission: 0 MAD</span>
                        </div>
                    `;

                    const input = li.querySelector('.commission-input');
                    const result = li.querySelector('.commission-result');

                    input.addEventListener('input', function () {
                        const commissionPercentage = parseFloat(input.value);
                        if (!isNaN(commissionPercentage)) {
                            const chiffreAffaire = parseFloat(input.dataset.chiffre);
                            const commission = (chiffreAffaire * commissionPercentage) / 100;
                            result.textContent = `Commission: ${formatNumberWithSpacesCommission(commission)} MAD`;
                        } else {
                            result.textContent = `Commission: 0 MAD`;
                        }
                    });

                    list.appendChild(li);
                });
            })
            .catch(error => console.error('Erreur lors de la récupération des commerçants:', error));
    }


    // Format number with spaces (e.g., 1000 -> 1 000)
    function formatNumberWithSpacesCommission(number) {
        return number.toLocaleString('fr-FR');
    }

    // Close modal when clicking the close button or footer button
    ['closeCommercantModalBtn', 'closeFooterBtn'].forEach(id => {
        document.getElementById(id).addEventListener('click', function () {
            document.getElementById('commercantModal').classList.add('hidden');
        });
    });




    // function deleteBon(no_bl) {
    //     Swal.fire({
    //         title: "Êtes-vous sûr ?",
    //         text: "Voulez-vous vraiment supprimer ce Bon de Livraison ?",
    //         icon: "warning",
    //         showCancelButton: true,
    //         confirmButtonColor: "#d33",
    //         cancelButtonColor: "#3085d6",
    //         confirmButtonText: "Oui, supprimer",
    //         cancelButtonText: "Annuler"
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 url: '/bon-livraison/' + no_bl,  // Adjust the route as needed
    //                 type: 'DELETE',
    //                 data: {
    //                     _token: '{{ csrf_token() }}'  // Ensure CSRF token is included
    //                 },
    //                 success: function(response) {
    //                     Swal.fire({
    //                         title: "Supprimé !",
    //                         text: response.message,
    //                         icon: "success"
    //                     });
    //                     table.ajax.reload();  // Reload DataTable after deletion
    //                 },
    //                 error: function(xhr) {
    //                     Swal.fire({
    //                         title: "Erreur",
    //                         text: xhr.responseJSON.message,
    //                         icon: "error"
    //                     });
    //                 }
    //             });
    //         }
    //     });
    // }



</script>


@endsection