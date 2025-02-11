@extends('layouts.app')

@section('content')
<style>
    /* Custom DataTable styles */
    .dataTables_wrapper .dataTables_length select {
        background-color: #0b0f284e;
        width: 90px;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #e2e2ff;
        margin-bottom: 2px;
        color: rgb(255, 254, 254)
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
        background-color: #20233da2;
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

    #sum-montant-total{
        padding: 2px 5px;
        width: fit-content;
        position: absolute;
        top: 120px;
        left: 540px;
        background: #e7e1e1ac;
        color: #600f06;
        border-radius: 3px;
        font-weight: 900;
        text-transform: uppercase;
    }
    #sum-montant-payed{
        padding: 2px 5px;
        width: fit-content;
        position: absolute;
        top: 155px;
        left: 540px;
        background: #e7e1e1ac;
        color: #06600a;
        border-radius: 3px;
        font-weight: 900;
        text-transform: uppercase;
    }
    #sum-montant-restant{
        padding: 2px 5px;
        width: fit-content;
        position: absolute;
        top: 190px;
        left: 540px;
        background: #e7e1e1ac;
        color: #080660;
        border-radius: 3px;
        font-weight: 900;
        text-transform: uppercase;
    }

    #date_reglement{
        width: 220px;
    }
    /* Add these styles to your CSS file */
    .text-blue {
        color: blue;
    }
    
    .text-orange {
        color: orange;
    }
    
    .text-red {
        color: red;
    }
    
    .text-yellow {
        color: #22cd09;
    }

    #mode_reglement{
        text-transform: uppercase;
    }
    .reglement-btn{
        background-color: #6572ced6;
        padding:4px;
        padding-top:5px;
        /*height:130%;*/
        /*margin-bottom:-20px !important;*/
        
        
    }
    .reglement-btn:hover{
        background-color:#6572cef6;
    }
    .reglement-btn svg{
        width: 43px;
        height: 27px;
        display: inline-block;
        vertical-align: middle;
        
    }
    .pb{
        display: flex;
        align-items: center; /* Align items vertically */
        justify-content: space-between;
        gap:4px;
        
    }
    /* container buttons of download */
    .dt-buttons{ 
        margin-left: 10px !important;
    }

    /* button bon  */
    
</style>

        {{-- <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('PAIEMENT STATUS') }}
            <button class="relative inline-flex items-center justify-center ms-96 p-0.5  me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-cyan-200 dark:focus:ring-cyan-800">
                <span class="relative px-5 py-1.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-opacity-0">
                <a href="{{route('reglements.create')}}"> Reglement</a>
                </span>
            </button>
        </h2> --}}
    @can('view payment statuses')
        
    
        <div class="py-2 px-9">
            <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 pt-4 text-gray-900 dark:text-gray-100 ">
                        <h2 class="font-serif text-center rounded-lg text-gray-600 bg-gray-10 p-2 underline mb-2 text-2xl font-bold uppercase">Achats STATUS PAIEMENT</h2>
    
                        {{-- <div class="dateInputsSearch">
                            
                            
                        </div> --}}
                        <div id="sum-montant-total" class="font-bold text-lg mb-4"> Total : </div>
                        <div id="sum-montant-payed" class="font-bold text-lg mb-4"> Payé : </div>
                        <div id="sum-montant-restant" class="font-bold text-lg mb-4">  Restant : </div>
    
    
    
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
                                {{-- <select id="clientType" name="clientType" class="rounded" onchange="fetchDataByClientType()">
                                    <option value="all">All</option>
                                    <option value="fiche client">Fiche Client</option>
                                    <option value="anomalie">Anomalie</option>
                                    <option value="particulier">Particulier</option>
                                </select> --}}
                                <label for="search-no-bl" class="mr-">Search:</label>
                                <input type="text" id="search-no-bl" class="border px-2 py-2 rounded" placeholder="No BL">
                            </div>
    
                        </div>
                        
                        <table id="payment-status-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-sm text-gray-500 dark:text-gray-400 border">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                <tr>
                                    {{-- <th scope="col" class="px-6 py-3">ID</th> --}}
                                    <th scope="col" class="px-6 py-3">No BL</th>
                                    {{-- <th scope="col" class="px-6 py-3">No RAISON</th> --}}
                                    <th scope="col" class="px-6 py-3">RAISON</th>
                                    <th scope="col" class="px-6 py-3">DATE de BL</th>
                                    <th scope="col" class="px-6 py-3">MONTANT TOTAL ($)</th>
                                    <th scope="col" class="px-6 py-3">MONTAT PAYé ($)</th>
                                    <th scope="col" class="px-6 py-3">Solde Rest ($)</th>
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
            <div class="bg-white dark:bg-gray-800 p-6 py-6 pt-1 rounded-lg shadow-lg w-2/4">
                <div class="flex justify-end items-center mb-1 ">
                    <button id="close-modal" class="text-red-500 text-xl">&times;</button>
                </div>
                <h2 class="text-xl font-bold ml-12 text-center font-serif text-gray-600 mb-2">Ajouter nouveau Règlement</h2>
                <div id="modal-content mb-4">
    
                    <div class="flex justify-start mb-2" id="dt-inpts">
                        <div class="text-center">
                            <label for="no_bl"> No BL</label>
                            <input type="text"  id="no_bl" disabled>
                        </div>
                        <div class="text-center">
                            <label for="no_bl">Raison</label>
                            <input type="text"  id="name_fournisseur" disabled>
                        </div>
                        <div class="text-center">
                            <label for="no_bl">code fournisseur</label>
                            <input type="text"  id="id_fournisseur" disabled>
                        </div>
                        <div class="text-center">
                            <label for="montant_rest">Montant Rest</label>
                            <input type="text"  id="montant_rest" disabled>
                        </div>
                    </div>
    
                    <div class="div-selectBl">
                        <div class="mb-2">
                            <label for="montant" class="block text-lg font-medium text-gray-900 dark:text-white mb-1">Montant Reglment</label>
                            <input type="number" name="montant" id="montant" placeholder="Montant" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                        </div>
    
                        <div class="mb-2 dateInput">
                            <label for="date_reglement" class="block text-lg font-medium text-gray-900 dark:text-white mb-1">Date Regelment</label>
                            <input type="date" name="date" id="date_reglement" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                        </div>
                    </div>
    
                    <div class="div-modePyamnet">
                        <div class="mb-1">
                            <label for="mode_reglement" class="block text-lg font-medium text-gray-900 dark:text-white mb-1">Mode</label>
                            {{-- <input type="text" name="type_pay" id="mode_reglement" class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600"> --}}
                            <select name="type_pay" id="mode_reglement" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                                <option value="Espèce ">espèce </option>
                                <option value="Chèque">chèque</option>
                                <option value="Virement">virement</option>
                                <option value="remise">remise</option>
                            </select>
                        </div>
    
                        <div id="div-chq" >
                            <div class="mb-1 ">
                                <label for="reference_chq" class="block text-lg font-medium text-gray-900 dark:text-white mb-1">N Reference </label>
                                <input type="number" name="reference_chq" id="reference_chq" placeholder="Montant" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                            </div>
                            <div class="mb-1">
                                <label for="date_chq" class="block text-lg font-medium text-gray-900 dark:text-white mb-1">Date Expiration</label>
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
                    <table id="achats-modal-table" class="min-w-full table-auto">
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
        
        {{-- modal for show client details phone and type  --}}
        <!-- Modal for Client Details -->
        <div id="client-modal" class="hidden fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg p-6 w-96">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="client-modal-header" class="text-xl font-semibold">Client Details</h3>
                    <button onclick="closeClientModal()" class="text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div id="client-modal-body">
                    <p><strong>Phone:</strong> <span id="client-phone">Loading...</span></p>
                    <p><strong>Type:</strong> <span id="client-type">Loading...</span></p>
                </div>
                <div class="mt-4 text-right">
                    <button onclick="closeClientModal()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Close</button>
                </div>
            </div>
        </div>
    
    
    @endcan
    
        <script>
    var table;
    $(document).ready(function () {
        var table = $('#payment-status-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ route('achatStatus.index') }}',
                data: function (d) {
                    d.date_from = $('#search-date-f').val(); // Add date_from to the request
                    d.date_to = $('#search-date-t').val();   // Add date_to to the request
                    d.client_type = $('#clientType').val();
                }
            },
            columns: [
                // { data: 'id', name: 'id' },
                { data: 'no_bl', name: 'no_bl' },
                // { data: 'id_fournisseur', name: 'id_fournisseur' },
                // { data: 'name_client', name: 'name_client' },
                {
                    data: 'name_fournisseur',
                    name: 'name_fournisseur',
                    // render: function (data, type, row) {
                    //     return `<a href="#" class="client-name" data-client-code="${row.code_client}">${data}</a>`;
                    // }
                },
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
                        var formattedData = formatNumberWithSpaces(data);
    
                        // Determine the color based on the montant_restant value
                        var colorClass = '';
                        if ((data >= 1 && data < 5)) {
                            colorClass = 'text-blue'; 
                        } else if (data >= 5 && data < 10) {
                            colorClass = 'text-yellow'; 
                        } else if (data >= 10 && data < 25) {
                            colorClass = 'text-orange';
                        } else if (data >= 25 ) {
                            colorClass = 'text-red';
                        }
    
                        // Return the formatted number with the appropriate color class
                        return `<span class="${colorClass}">${formattedData}</span>`;
                    }
                },
                {
                    data: null,
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        // const bonLivraisonUrl = bonLivraisonRoute.replace(':no_bl', row.no_bl);
                        const bonLivraisonUrl = `{{ route('bon_livraison', ['no_bl' => ':no_bl']) }}`.replace(':no_bl', row.no_bl);
                        const bonCoupUrl = `{{ route('bon_coup', ['no_bl' => ':no_bl']) }}`.replace(':no_bl', row.no_bl);
                        return `
                        @can('create reglements')
                            <button class="reglement-btn bg-orange-400 text-white px-2 py-2  rounded  hover:bg-orange-600" 
                            data-bl-id="${row.no_bl}" 
                            data-fournisseur-id="${row.id_fournisseur}" 
                            data-fournisseur-name="${row.name_fournisseur}"
                            data-montant-restant="${row.montant_restant}">
                                <svg width="30" height="30" viewBox="0 0 24 24" fill="#343C54" xmlns="http://www.w3.org/2000/svg">
                                    <path  fill-rule="evenodd" clip-rule="evenodd" d="M18.4125 3.30938C17.4281 3 16.5281 3 14.7281 3H9.27187C7.47187 3 6.57187 3 5.5875 3.30938C5.06492 3.50054 4.59035 3.80342 4.19688 4.19688C3.80342 4.59035 3.50054 5.06492 3.30938 5.5875C3 6.56063 3 7.46625 3 9.27187V14.7253C3 16.5281 3 17.4281 3.30938 18.4125C3.50054 18.9351 3.80342 19.4097 4.19688 19.8031C4.59035 20.1966 5.06492 20.4995 5.5875 20.6906C6.56063 21 7.46625 21 9.27187 21H14.7281C16.5281 21 17.4281 21 18.4125 20.685C18.9351 20.4938 19.4097 20.191 19.8031 19.7975C20.1966 19.404 20.4995 18.9295 20.6906 18.4069C21 17.4337 21 16.5281 21 14.7225V9.27469C21 7.47187 21 6.57187 20.6906 5.5875C20.4995 5.06492 20.1966 4.59035 19.8031 4.19688C19.4097 3.80342 18.9351 3.50054 18.4125 3.30938ZM15.4505 9.69387C15.3183 9.83448 15.0849 9.83449 14.9443 9.69389L14.9499 9.69949C14.3564 9.16512 13.448 8.77418 12.503 8.77418C11.7605 8.77418 11.0208 9.04418 11.0208 9.7248C11.0208 10.3725 11.6992 10.6208 12.5264 10.9237C12.5936 10.9483 12.6619 10.9733 12.7308 10.9989C14.3339 11.5529 15.6558 12.2336 15.6558 13.8479C15.6558 15.6001 14.3283 16.8011 12.1492 16.9473L11.9523 17.9007C11.9354 17.9851 11.8896 18.061 11.823 18.1153C11.7563 18.1697 11.6727 18.1992 11.5867 18.1989L10.2226 18.1876C9.9892 18.1848 9.81763 17.9682 9.86545 17.7348L10.0736 16.7307C9.22082 16.4973 8.4727 16.0754 7.88207 15.4932C7.81063 15.4215 7.77051 15.3245 7.77051 15.2232C7.77051 15.122 7.81063 15.0249 7.88207 14.9532L8.64145 14.1939C8.67541 14.1599 8.71574 14.1329 8.76014 14.1145C8.80454 14.096 8.85213 14.0866 8.9002 14.0866C8.94826 14.0866 8.99586 14.096 9.04026 14.1145C9.08465 14.1329 9.12499 14.1599 9.15895 14.1939C9.8902 14.9279 10.8352 15.2289 11.7352 15.2289C12.7252 15.2289 13.3946 14.8182 13.3946 14.1179C13.3946 13.5027 12.8453 13.2965 11.8027 12.9052C11.692 12.8636 11.5757 12.82 11.4539 12.7736C10.1124 12.2926 8.84395 11.5923 8.84395 9.96105C8.84395 8.07668 10.3796 7.15699 12.1908 7.06418L12.3877 6.11355C12.4045 6.03013 12.4494 5.955 12.5149 5.90072C12.5804 5.84645 12.6626 5.81634 12.7477 5.81543H14.1089C14.3424 5.81543 14.5168 6.0348 14.4689 6.26824L14.2468 7.33699C14.9419 7.56868 15.5842 7.93574 16.1368 8.41699C16.2943 8.56887 16.3027 8.82199 16.1536 8.96262L15.4505 9.69387Z" 
                                    fill="#fafafa"/>
                                </svg>
                            </button>
                        @endcan
                        @can('view sales by bl')
                            <button class="bg-blue-400 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded view-sales" data-bl="${row.no_bl}">BL</button>
                        @endcan
                        @can('view reglements by bl')
                            <button class="bg-green-400 hover:bg-green-600 text-white font-bold py-2 px-4 rounded view-reglements" data-bl="${row.no_bl}">RG</button>
                        @endcan
                        `;
                    }
                }
            ],
            order: [[0, 'desc']],
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
            },
            dom: '<"row mb-3"<"col-md-6"l><"col-md-6 text-end"B>>' +
                '<"row mb-3"<"col-md-6"f>>' +
                 '<"row"<"col-md-12"tr>>' +
                 '<"row mt-3"<"col-md-5"i><"col-md-7"p>>', 
            buttons: [
                // {
                //     extend: 'csvHtml5',
                //     title: 'Payment Status',
                //     text: 'CSV'
                // },
                {
                    extend: 'pdfHtml5',
                    title: 'Payment Status',
                    text: 'PDF',
                    exportOptions: {
                        columns: ':not(:last-child)' 
                    },
                    customize: function (doc) {
                        // Prepare content for the header
                        var dateFrom = $('#search-date-f').val();
                        var dateTo = $('#search-date-t').val();
                        var dateRange = dateFrom && dateTo ? `Plage de dates : ${dateFrom} au ${dateTo}` : 'Aucune plage de dates sélectionnée';
    
                        var currentDate = new Date();
                        var dateString = `${currentDate.toLocaleDateString('fr-FR')}`;
    
                        var montantTotal = $('#sum-montant-total').text().split(': ')[1];
                        var montantPayed = $('#sum-montant-payed').text().split(': ')[1];
                        var montantRestant = $('#sum-montant-restant').text().split(': ')[1];
    
                        // Clear and rebuild content array in the desired order
                        doc.content = [
                            // Add date range and date of download in the same line
                            {
                                columns: [
                                    {
                                        text: dateRange,
                                        alignment: 'left', // Align to the left
                                        style: 'subheader',
                                        margin: [0, 0, 0, 10],
                                        color : '#656965'
    
                                    },
                                    {
                                        text: dateString,
                                        alignment: 'right', // Align to the right
                                        style: 'subheader',
                                        margin: [0, 0, 0, 10],
                                        color : 'gray'
                                    }
                                ]
                            },
                            // Add the sums
                            {
                                text: `Montant Total : ${montantTotal}`,
                                style: 'subheader',
                                margin: [0, 0, 0, 5]
                            },
                            {
                                text: `Montant Payé : ${montantPayed}`,
                                style: 'subheader',
                                margin: [0, 0, 0, 5]
                            },
                            {
                                text: [
                                    { text: 'Montant Rest : ', style: 'subheader' },
                                    { text: montantRestant, style: 'subheader', color: 'red' } // Number in red
                                ],
                                margin: [0, 0, 0, 10]
                            },
                            // Include the table content (ensure this stays in place)
                            ...doc.content
                        ];
    
                        // Pagination (page number)
                        doc.pageMargins = [40, 60, 40, 60]; // Optional: Add space for footer
                        doc.footer = function (currentPage, pageCount) {
                            return {
                                text: `Page ${currentPage} sur ${pageCount}`,
                                alignment: 'center',
                                style: 'footer'
                            };
                        };
    
                        // Adjust header row font size
                        if (doc.content && doc.content[1] && doc.content[1].table && doc.content[1].table.header) {
                            doc.content[1].table.header.forEach(function (headerCell) {
                                headerCell.style = {
                                    fontSize: 8,
                                    bold: true
                                };
                            });
                        }
                    }
                },
    
                {
                    extend: 'excelHtml5',
                    title: 'Payment Status',
                    text: 'Excel',
                    exportOptions: {
                        columns: ':not(:last-child)' 
                    }
                },
                {
                    extend: 'print',
                    title: 'Payment Status',
                    text: 'Print',
                    customize: function (win) {
                        $(win.document.body)
                            .css('font-size', '10pt')
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                }
            ]
        });

        
    
        // Function to calculate and display the sum of montant_restant
        function updateSum() {
            var montantRestantSum = 0;
            var montantTotalSum = 0;
            var montantPayedSum = 0;
    
            table.rows({ filter: 'applied' }).every(function () {
                var data = this.data();
                montantRestantSum += parseFloat(data.montant_restant) || 0;
                montantTotalSum += parseFloat(data.montant_total) || 0;
                montantPayedSum += parseFloat(data.montant_payed) || 0;
            });
    
            $('#sum-montant-total').text(`Total : ${formatNumberWithSpaces(Math.floor(montantTotalSum))} DH`);
            $('#sum-montant-payed').text(`Payé : ${formatNumberWithSpaces(Math.floor(montantPayedSum))} DH`);
            $('#sum-montant-restant').text(`Restant : ${formatNumberWithSpaces(Math.floor(montantRestantSum))} DH`);
        }
        window.filterByType = function () {
            var clientType = $('#clientType').val();
            if (clientType === 'all') {
                table.ajax.reload(); // Reload the table
            } else {
                table.draw(); // Fetch filtered data
            }
        };
    
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
    
        $('#clientType').on('change', function () {
            table.ajax.reload(); // Reload data based on the selected client type
        });
    
    });
    // 000000000000000000000000000000000000000000000000000
    // 0000000 ------------------------ ADD NEW REGELEMENT ---------------------------------
    $(document).ready(function () {
        // Open Modal
        $(document).on('click', '.reglement-btn', function () {
            // Get the BL and Client details from the button's data attributes
            const noBL = $(this).data('bl-id');
            const fournisseurCode = $(this).data('fournisseur-id');
            const fournisseurName = $(this).data('fournisseur-name');
            const montantRestant = $(this).data('montant-restant');
            
            
    
            // Populate form fields with the fetched data
            $('#id_fournisseur').val(fournisseurCode);
            $('#name_fournisseur').val(fournisseurName); // If there's a field to display the client's name
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
                id_fournisseur: $('#id_fournisseur').val(),
                montant: $('#montant').val(),
                date: $('#date_reglement').val(),
                type_pay: $('#mode_reglement').val(),
                reference_chq: $('#reference_chq').val(),
                date_chq: $('#date_chq').val(),
                _token: '{{ csrf_token() }}'
            };
    
            if (!formData.no_bl || !formData.id_fournisseur || !formData.montant) {
                alert('Please fill in all required fields.');
                return;
            }
    
            console.log(formData);
            
            // if(false){
                // Send AJAX request
                fetch('{{ route('achat.reglements.store') }}', {
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
    
    function toggleDropdown(button) {
        const menu = button.nextElementSibling;
        menu.classList.toggle('hidden');
    }
        // 00000 00000 00000 0000 0000 00 0 0 000 00 0 
        // 00000 00000 00000 0000 0000 00 0 0 000 00 0 
        // 00000 00000 00000 0000 0000 00 0 0 000 00 0 
    
        // Open modal
        // $(document).on('click', '.view-sales', function () {
        //     var blNumber = $(this).data('bl'); // Get the BL number
        //     $('#bl-number').text(blNumber); // Set the BL number in the header
        //     $('#salesModal').removeClass('hidden'); // Show the modal
    
        //     // Make the AJAX request to get the sales data for this BL
        //     $.ajax({
        //         url: '/sales/get-by-bl', // The route to get sales by BL
        //         method: 'GET',
        //         data: { no_bl: blNumber },
        //         success: function (response) {
        //             var achatsTableBody = $('#achats-modal-table tbody');
        //             achatsTableBody.empty(); // Clear previous data
        //             console.log(response);
    
        //             if (response.sales.length > 0) {
        //                 // Get client details from the first sale entry
        //                 var clientName = response.sales[0].client_name || 'N/A';
        //                 var codeClient = "("+response.sales[0].code_client + ")" || 'N/A';
    
        //                 // Update the modal header with client information
        //                 $('#modal-client-name').text(clientName);
        //                 $('#modal-code-client').text(codeClient);
        //             } else {
        //                 // Fallback if no sales are found
        //                 $('#modal-client-name').text('pas de regelement').css('color', 'red');;
        //                 // $('#modal-code-client').text('N/A');
        //             }
    
        //             // Populate the table with the sales data
        //             response.sales.forEach(function (sale) {
        //                 achatsTableBody.append(`
        //                     <tr>
        //                         <td class="px-4 py-2 border">${sale.id}</td>
        //                         <td class="px-4 py-2 border">${sale.no_bl}</td>
        //                         <td class="px-4 py-2 border">${sale.annee}</td>
        //                         <td class="px-4 py-2 border">${sale.date}</td>
        //                         <td class="px-4 py-2 border">${sale.ref_produit}</td>
        //                         <td class="px-4 py-2 border">${sale.produit}</td>
        //                         <td class="px-4 py-2 border">${sale.longueur}</td>
        //                         <td class="px-4 py-2 border">${sale.largeur}</td>
        //                         <td class="px-4 py-2 border">${sale.nbr}</td>
        //                         <td class="px-4 py-2 border">${sale.qte}</td>
        //                         <td class="px-4 py-2 border">${sale.prix_unitaire}</td>
        //                         <td class="px-4 py-2 border">${sale.montant}</td>
        //                     </tr>
        //                 `);
        //             });
    
        //             // Re-initialize DataTable for the modal table
        //             $('#achats-modal-table').DataTable();
        //         },
        //         error: function () {
        //             alert('Error fetching sales data');
        //         },
        //     });
        // });
    
    $(document).on('click', '.view-sales', function () { 
        var blNumber = $(this).data('bl'); // Get the BL number
        $('#bl-number').text(blNumber); // Set the BL number in the header
        $('#salesModal').removeClass('hidden'); // Show the modal
    
        // Make the AJAX request to get the sales data for this BL
        $.ajax({
            url: '{{ route('achat.get-by-bl') }}',
            method: 'GET',
            data: { no_bl: blNumber },
            success: function (response) {
                var achatsTableBody = $('#achats-modal-table tbody');
                achatsTableBody.empty(); // Clear previous data
                console.log(response);
    
                if (response.achats.length > 0) {
                    // Get client details from the first sale entry
                    var fournisseurName = response.achats[0].name_fournisseur || 'N/A';
                    var fournissuerId = "("+response.achats[0].id_fournisseur + ")" || 'N/A';
    
                    // Update the modal header with client information
                    $('#modal-client-name').text(fournisseurName);
                    $('#modal-code-client').text(fournissuerId);
                } else {
                    // Fallback if no sales are found
                    $('#modal-client-name').text('pas de regelement').css('color', 'red');
                }
    
                // Populate the table with the sales data
                response.achats.forEach(function (achat) {
                    achatsTableBody.append(`
                        <tr>
                            <td class="px-4 py-2 border">${achat.id}</td>
                            <td class="px-4 py-2 border">${achat.no_bl}</td>
                            <td class="px-4 py-2 border">${achat.annee}</td>
                            <td class="px-4 py-2 border">${achat.date}</td>
                            <td class="px-4 py-2 border">${achat.ref_produit}</td>
                            <td class="px-4 py-2 border">${achat.produit}</td>
                            <td class="px-4 py-2 border">${achat.longueur}</td>
                            <td class="px-4 py-2 border">${achat.largeur}</td>
                            <td class="px-4 py-2 border">${achat.nbr}</td>
                            <td class="px-4 py-2 border">${achat.qte}</td>
                            <td class="px-4 py-2 border">${achat.prix_unitaire}</td>
                            <td class="px-4 py-2 border">${achat.montant}</td>
                        </tr>
                    `);
                });
    
                // Re-initialize DataTable for the modal table
                $('#achats-modal-table').DataTable();
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
                // url: '/reglements/get-by-bl', // The route to get reglements by BL
                url: '{{ route('achat.reglements.get-by-bl') }}',
                method: 'GET',
                data: { no_bl: blNumber },
                success: function (response) {
                    var reglementsTableBody = $('#reglements-modal-table tbody');
                    reglementsTableBody.empty(); // Clear previous data
                    console.log(response);
    
                    if (response.reglements.length > 0) {
                        // Get client details from the first reglement entry
                        var clientFournisseur = response.reglements[0].nom_fournisseur || 'N/A';
                        var codeFournisseur = "(" + response.reglements[0].id_fournisseur + ")" || 'N/A';
    
                        // Update the modal header with client information
                        $('#reglements-client-name').text(clientFournisseur);
                        $('#reglements-code-client').text(codeFournisseur);
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
                                <td class="px-4 py-2 border">${reglement.id_fournisseur}</td>
                                <td class="px-4 py-2 border">${reglement.nom_fournisseur}</td>
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
    
    // 0000000 get client data phone and type 00000000
    $(document).on('click', '.client-name', function (e) {
        e.preventDefault();
        const clientCode = $(this).data('client-code');
        console.log(clientCode);
    
        // Fetch client data using the correct URL with 'code_client' parameter
        $.ajax({
            url: '{{ route('clients.ByCode', ['code_client' => '__CODE_CLIENT__']) }}'.replace('__CODE_CLIENT__', clientCode), // Use route with parameter
            method: 'GET',
            success: function (data) {
                console.log(data); // Log the response to check it
    
                // Check if phone or type is null
                if (data && data.phone && data.type) {
                    // Populate the modal with client details
                    $('#client-modal-body').html(`
                        <p><strong>Phone:</strong> ${data.phone || 'N/A'}</p>
                        <p><strong>Type:</strong> ${data.type || 'N/A'}</p>
                    `);
                    // Show the modal
                    $('#client-modal').removeClass('hidden');
                } else {
                    // If phone or type is null, show SweetAlert for 3 seconds and do not show modal
                    Swal.fire({
                        icon: 'warning',
                        title: 'Données client incomplètes',
                        text: 'pas de telephone et catégorie pour cette client',
                        timer: 3000, // Display alert for 3 seconds
                        showConfirmButton: false,
                    });
                }
            },
            error: function () {
                alert('Error fetching client data.');
            },
        });
    });
    
    
    // Close the client modal
    function closeClientModal() {
        $('#client-modal').addClass('hidden');
    }
    
    
    
        </script>
        
    
@endsection
