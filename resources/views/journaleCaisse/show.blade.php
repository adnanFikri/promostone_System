@extends('layouts.app')

@section('content')
<style>
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
</style>


<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-4 text-gray-800 text-center uppercase">Journal de Caisse</h2>

     <!-- Filter Form -->
     <form method="POST" action="{{ route('journal.caisse.filter') }}" class="mb-6 bg-gray-100 p-4 rounded-lg shadow-md">
        @csrf
        <div class="flex space-x-4">
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700">Date Début</label>
                <input type="date" id="date_from" name="date_from" required class="mt-1 p-2 w-full border rounded-lg"  value="{{ request('date_from', now()->toDateString()) }}">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700">Date Fin</label>
                <input type="date" id="date_to" name="date_to" required class="mt-1 p-2 w-full border rounded-lg" value="{{ request('date_from', now()->toDateString()) }}">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Filtrer</button>
            </div>
            <div class="flex items-end">
                <button onclick="printContent()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                    Imprimer
                </button>
            </div>
          
        </div>
    </form>
    
    @if (empty($salesDetails))
        <tr>
            <td colspan="11" class="text-center text-red-500 font-semibold p-4">
                Aucun enregistrement trouvé. Veuillez choisir une plage de dates valide.
            </td>
        </tr>
    @else
        <div id="printable-content">
             <!-- Date Range Title -->
            <h2 class="text-xl font-bold mb-4 text-center uppercase ">
                Journal de Caisse - Du {{ $dateFrom }} au {{ $dateTo }}
            </h2>
            <div class="overflow-x-auto ">
                <table class="min-w-full border border-gray-300 rounded-lg shadow-md ">
                    <thead class="bg-gray-600 text-gray-100">
                        <tr>
                            <th class="border border-gray-300 p-2 text-left font-semibold uppercase">No BL</th>
                            <th class="border border-gray-300 p-2 text-left font-semibold uppercase">Code Client</th>
                            <th class="border border-gray-300 p-2 text-left font-semibold uppercase">Nom Client</th>
                            <th class="border border-gray-300 p-2 text-left font-semibold uppercase">Produit</th>
                            <th class="border border-gray-300 p-2 text-center font-semibold uppercase">Nombre</th>
                            <th class="border border-gray-300 p-2 text-center font-semibold uppercase">Prix Unitaire</th>
                            <th class="border border-gray-300 p-2 text-center font-semibold uppercase">Montant</th>
                            <th class="border border-gray-300 p-2 text-center font-semibold uppercase">Total</th>
                            <th class="border border-gray-300 p-2 text-left font-semibold uppercase">Type Paiement</th>
                            <th class="border border-gray-300 p-2 text-center font-semibold uppercase">Montant Payé</th>
                            <th class="border border-gray-300 p-2 text-center font-semibold uppercase">Restant</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salesDetails as $detail)
                            @php
                                $paymentStatus = $detail['paymentStatus'];
                                $reglements = $detail['reglements'];
                                $salesByProduit = $detail['salesByProduit'];
                                $firstRowBl = true;
                                $totalBlRows = count($salesByProduit);
                                $currentRow = 0;
                            @endphp

                            @foreach ($salesByProduit as $produit => $sale)
                                @php
                                    $totalBlRows = count($salesByProduit);
                                    $currentRow++;
                                    $isLastRow = ($currentRow == $totalBlRows); // Check if it's the last row
                                @endphp

                                <tr class="{{ $isLastRow ? 'border-b-2 border-gray-400' : '' }}">
                                    @if ($firstRowBl)
                                        <td class="border border-gray-300 p-2 align-middle font-medium text-gray-800 " rowspan="{{ $totalBlRows }}"><a href="#" class="view-sales" data-bl="{{ $detail['no_bl'] }}">{{ $detail['no_bl'] }}</a> </td>
                                        <td class="border border-gray-300 b p-2 align-middle" rowspan="{{ $totalBlRows }}"><a href="#" class="view-sales" data-bl="{{ $detail['no_bl'] }}">{{ $paymentStatus->name_client ?? 'N/A' }}</a></td>
                                        <td class="border border-gray-300 b p-2 align-middle" rowspan="{{ $totalBlRows }}">{{ $paymentStatus->code_client ?? 'N/A' }}</td>
                                    @endif

                                    <td class="border border-gray-300 p-2 font-medium text-blue-600 align-middle">{{ $sale['produit'] }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale['qte'] }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ number_format($sale['prix_unitaire'], 2) }}</td>
                                    <td class="border border-gray-300 p-2 text-center font-medium">{{ number_format($sale['montant'], 2) }}</td>

                                    @if ($firstRowBl)
                                        <td class="border border-gray-300  p-2 align-middle text-blue-700 text-center font-bold" rowspan="{{ $totalBlRows }}">{{ number_format($paymentStatus->montant_total, 2) }}</td>
                                        <td class="border border-gray-300  p-2 align-middle text-gray-800 font-medium" rowspan="{{ $totalBlRows }}">
                                            @if(($reglements->montant ?? 0) > 0)
                                                {{$reglements->type_pay ??  $reglements->type_pay ?? 'N/A' }}
                                                @if (($reglements->type_pay ?? '') == 'Chèque')
                                                    <br><span class="text-sm text-gray-500">Ref: {{ $reglements->reference_chq ?? 'N/A' }}</span>
                                                    <br><span class="text-sm text-gray-500">Date: {{ $reglements->date_chq ?? 'N/A' }}</span>
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="border border-gray-300  p-2 text-center align-middle font-semibold text-green-600" rowspan="{{ $totalBlRows }}">
                                            {{ number_format($reglements->montant ?? 0, 2) }}
                                        </td>
                                        <td class="border border-gray-300  p-2 text-center align-middle font-semibold text-red-600" rowspan="{{ $totalBlRows }}">
                                            {{ number_format($paymentStatus->montant_total - ($reglements->montant ?? 0), 2) }}
                                        </td>
                                        @php $firstRowBl = false; @endphp
                                    @endif
                                </tr>
                            @endforeach

                        @endforeach

                        <tr >
                            {{-- <td class="border border-gray-300 p-3 bg-gray-200  text-orange-500 text-center align-middle font-semibold  text-right" colspan="7">TOTAUX</td> --}}
                            <td class="border border-gray-300 p-3 bg-gray-200 text-left align-middle font-semibold text-blue-800" colspan="4">
                                <table class="w-full">
                                    <tr>
                                        <tr>
                                            <td class="border-b border-gray-300 p-2 bg-gray-200 text-left align-middle font-semibold">Distristone / Caisse espèce <span style="color: transparent;">...</span>: <span class="text-green-600"> {{number_format($reglementsDistristoneMontant ?? 0, 2)}} </span> </td>
                                        </tr>
                                        <tr>
                                            <td class="border- border-gray-300 p-2 bg-gray-200 text-left align-middle font-semibold">Promostone / Caisse espèce <span style="color: transparent;">.</span> : <span class="text-green-600">  {{ number_format(($totalByType['Espèce'] ?? 0) - ($reglementsDistristoneMontant ?? 0), 2) }} </span></td>
                                        </tr>                                                                                                                                                                                               
                                    </tr>
                                </table>
                            </td>
                            <td class="border border-gray-300 p-3 bg-gray-200  text-orange-500 text-center align-middle font-semibold  text-right" colspan="3">TOTAUX</td>
                            <td class="border border-gray-300 p-3 bg-gray-200 text-center align-middle font-semibold text-blue-800">{{ number_format($totalAllSales ?? 0, 2) }}</td>
                            <td class="border border-gray-300  bg-gray-200 text-center align-middle font-semibold " colspan="2">
                                <table class="w-full">
                                    <tr>
                                        <tr>
                                            <td class="border-b border-gray-300 p-2 bg-gray-200 text-center align-middle font-semibold">Espèce : <span class="text-green-600"> {{number_format($totalSalesReglementEspece ?? 0, 2)}} </span> </td>
                                        </tr>
                                        <tr>
                                            <td class="border-b border-gray-300 p-2 bg-gray-200 text-center align-middle font-semibold">Chèque : <span class="text-green-600"> {{number_format($totalSalesReglementCheque ?? 0, 2)}} </span></td>
                                        </tr>
                                        <tr>
                                            <td class=" p-2 bg-gray-200 text-center align-middle font-semibold">Virement : <span class="text-green-600"> {{number_format($totalSalesReglementVirement ?? 0, 2)}} </span></td>
                                        </tr>
                                    </tr>
                                </table>
                            </td>
                            <td class="border border-gray-300 p-3 text-center bg-gray-200  align-middle font-semibold text-red-600" >{{number_format($totalSalesMontantRestant ?? 0, 2)}}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="grid grid-cols-2 gap-4 mt-6">
                    <div class="overflow-x-auto mt-6">
                        <h3 class="text-xl font-semibold mb-2 text-gray-800 uppercase">sold de bon</h3>
                        <table class="min-w-full borde border-gray-300 rounded-lg ">
                            <thead class="bg-gray-600 text-gray-100">
                                <tr>
                                    <th class="border border-gray-300 p-2 text-left font-semibold uppercase">No BL</th>
                                    <th class="border border-gray-300 p-2 text-left font-semibold uppercase">Client</th>
                                    <th class="border border-gray-300 p-2 text-center font-semibold uppercase">Montant</th>
                                    <th class="border border-gray-300 p-2 text-left font-semibold uppercase">Type Paiement</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($filteredReglements as $detail)
                                        <tr>
                                            <td class="border border-gray-300 p-2 text-gray-800">  <a href="#" id="sale-number" class="view-reglements-link" data-bl="{{ $detail['no_bl'] }}"> {{ $detail['no_bl'] }}</a></td>
                                            <td class="border border-gray-300 p-2 text-gray-800"> <a href="#" class="view-reglements-link" data-bl="{{ $detail['no_bl'] }}">{{ $detail['nom_client'] }}</a> </td>
                                            <td class="border border-gray-300 p-2 text-center font-medium text-green-600">
                                                {{ number_format($detail->montant ?? 0, 2) }}
                                            </td>
                                            <td class="border border-gray-300 p-2 text-gray-800">
                                                {{-- {{ $detail->type_pay === 'Chèque' ?  $detail->type_pay . ', N ' . $detail->reference_chq . ' - date '. $detail->date_chq : $detail->type_pay }} --}}
                                                {{ $detail->type_pay ?? 'N/A' }}
                                                @if (($detail->type_pay ?? '') == 'Chèque')
                                                    <span class="text-sm text-gray-500"> - Ref: {{ $detail->reference_chq ?? 'N/A' }}</span>
                                                    <span class="text-sm text-gray-500">- Date: {{ $detail->date_chq ?? 'N/A' }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                @endforeach
                                <tr>
                                    <td class="border border-gray-300 p-2 bg-gray-200 text-gray-800 text-right font-bold text-orange-500 uppercase" colspan="2">Total solde de bon</td>
                                    <td class="border border-gray-300 p-2 bg-gray-200 text-green-600 font-bold text-center" colspan="1"> {{ number_format($filteredReglementsSum ?? 0, 2)  }} </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Recap de Journée Table -->
                    <div class="overflow-x-auto mt-6">
                        <h3 class="text-xl font-semibold mb-2 text-gray-800 uppercase">Récap de Journée {{ $dateFrom }} / {{ $dateTo }}</h3>
                        <table class="min-w-full border border-gray-300 rounded-lg shadow-md">
                            <thead class="bg-gray-600 text-gray-100">
                                <tr>
                                    <th class="border border-gray-300 p-2 text-center font-semibold uppercase" colspan="2">Total Chiffre d'Affaires</th>
                                    {{-- <th class="border border-gray-300 p-2 text-center font-semibold">Espèce</th>
                                    <th class="border border-gray-300 p-2 text-center font-semibold">Chèque</th>
                                    <th class="border border-gray-300 p-2 text-center font-semibold">Virement</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-300 p-3 text-right text-orange-500 font-bold uppercase">
                                        TOTAL CHIFFRE D'AFFAIRES
                                    </td>
                                    <td class="border border-gray-300 p-3 text-center text-blue-800 font-bold">
                                        {{ number_format($totalChiffreAffaire, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 p-3 text-right text-orange-500 font-bold uppercase">
                                        TOTAL montant Espèce
                                    </td>
                                    <td class="border border-gray-300 p-3 text-center text-green-600 font-semibold">
                                        {{ number_format($totalByType['Espèce'] ?? 0, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 p-3 text-right text-orange-500 font-bold uppercase">
                                        TOTAL montant Chèque
                                    </td>
                                    <td class="border border-gray-300 p-3 text-center text-green-600 font-semibold">
                                        {{ number_format($totalByType['Chèque'] ?? 0, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 p-3 text-right text-orange-500 font-bold uppercase">
                                        TOTAL montant Virement
                                    </td>
                                    <td class="border border-gray-300 p-3 text-center text-green-600 font-semibold">
                                        {{ number_format($totalByType['Virement'] ?? 0, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif 
</div>
{{-- //00000000000000 modal of sales 00000000000000 --}}
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
            <table id="sales-modal-table" class="min-w-full table-auto">
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

{{-- //00000000000000 modal of Reglements 00000000000000 --}}
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


<script>
 function printContent() {
    var content = document.getElementById("printable-content").innerHTML;

    // Get the sale number (no_bl) from an element in the document
    var no_bl = document.getElementById("sale-number").textContent.trim();

    // Get the current date in YYYY-MM-DD format
    var now = new Date();
    var formattedDate = now.getFullYear() + "-" + 
                        String(now.getMonth() + 1).padStart(2, '0') + "-" + 
                        String(now.getDate()).padStart(2, '0');

    // Construct the filename
    var filename = `journaleCaisse-${formattedDate}`;

    // Open a new print window
    var printWindow = window.open("", "_blank");

    if (!printWindow) {
        alert("Pop-ups are blocked! Allow them to print the document.");
        return;
    }

    // Add Tailwind CSS via CDN and styles
    var styles = `
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <style>
            @page {
                size: A3;
                margin: 5; /* No margins */
            }
            body {
                font-family: Arial, sans-serif;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        </style>
    `;

    // Write the printable content
    printWindow.document.open();
    printWindow.document.write(`
        <html>
            <head>
                <title>${filename}</title> <!-- This helps set the default name -->
                ${styles}
            </head>
            <body class="p-6">
                ${content}
            </body>
        </html>
    `);
    printWindow.document.close();

    // Print and close window
    printWindow.onload = function () {
        printWindow.print();
        printWindow.onafterprint = function () {
            printWindow.close();
        };
    };
}








    //00000000000000 modal of sales 00000000000000
    $(document).on('click', '.view-sales', function () { 
        var blNumber = $(this).data('bl'); // Get the BL number
        $('#bl-number').text(blNumber); // Set the BL number in the modal header
        $('#salesModal').removeClass('hidden'); // Show the modal

        // Make the AJAX request to get the sales data for this BL
        $.ajax({
            url: '{{ route('sales.get-by-bl') }}',
            method: 'GET',
            data: { no_bl: blNumber },
            success: function (response) {
                var salesTableBody = $('#sales-modal-table tbody');
                salesTableBody.empty(); // Clear previous data
                console.log(response);

                if (response.sales.length > 0) {
                    // Get client details from the first sale entry
                    var clientName = response.sales[0].client_name || 'N/A';
                    var codeClient = "("+response.sales[0].code_client + ")" || 'N/A';

                    // Update the modal header with client information
                    $('#modal-client-name').text(clientName);
                    $('#modal-code-client').text(codeClient);
                } else {
                    // Fallback if no sales are found
                    $('#modal-client-name').text('pas de réglement').css('color', 'red');
                }

                // Populate the table with the sales data
                response.sales.forEach(function (sale) {
                    salesTableBody.append(`
                        <tr>
                            <td class="px-4 py-2 border">${sale.id}</td>
                            <td class="px-4 py-2 border">${sale.no_bl}</td>
                            <td class="px-4 py-2 border">${sale.annee}</td>
                            <td class="px-4 py-2 border">${sale.date}</td>
                            <td class="px-4 py-2 border">${sale.ref_produit}</td>
                            <td class="px-4 py-2 border">${sale.produit}</td>
                            <td class="px-4 py-2 border">${sale.longueur}</td>
                            <td class="px-4 py-2 border">${sale.largeur}</td>
                            <td class="px-4 py-2 border">${sale.nbr}</td>
                            <td class="px-4 py-2 border">${sale.qte}</td>
                            <td class="px-4 py-2 border">${sale.prix_unitaire}</td>
                            <td class="px-4 py-2 border">${sale.montant}</td>
                        </tr>
                    `);
                });

                // Re-initialize DataTable for the modal table
                $('#sales-modal-table').DataTable();
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



    // =-=-000000=-=-=000000=-=-=-=000000=-=-=-0000
    $(document).on('click', '.view-reglements-link', function () {
        var blNumber = $(this).data('bl'); // Get the BL number from the data attribute
        $('#reglements-bl-number').text(blNumber); // Set the BL number in the modal header
        $('#reglementsModal').removeClass('hidden'); // Show the modal

        // Make the AJAX request to get the reglements data for this BL
        $.ajax({
            url: '{{ route('reglements.get-by-bl') }}',
            method: 'GET',
            data: { no_bl: blNumber },
            success: function (response) {
                var reglementsTableBody = $('#reglements-modal-table tbody');
                reglementsTableBody.empty(); // Clear previous data

                if (response.reglements.length > 0) {
                    // Get client details from the first reglement entry
                    var clientName = response.reglements[0].nom_client || 'N/A';
                    var codeClient = "(" + response.reglements[0].code_client + ")" || 'N/A';

                    // Update the modal header with client information
                    $('#reglements-client-name').text(clientName);
                    $('#reglements-code-client').text(codeClient);
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
                            <td class="px-4 py-2 border">${reglement.code_client}</td>
                            <td class="px-4 py-2 border">${reglement.nom_client}</td>
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


</script>
@endsection
