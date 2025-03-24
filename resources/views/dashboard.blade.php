@extends('layouts.app')

@section('content')

@can('create users')
    
    <div class="grid grid-cols-8 gap-4 mx-4 mt-6 bg-gray-00 p-2 rounded-lg border-b-8 border-gray-300">
        {{-- <div class="div-journalCaisse  "> --}}
            <div class=" col-span-3 ">
                <div class="mt-6 overflow-x-auto bg-white p-4 shadow-md rounded-lg">
                    <a href="{{ route('journal.caisse') }}">
                        <h3 class="text-lg font-semibold mb-2 text-gray-800 uppercase border-l-4 border-green-500 pl-3">sold de bon</h3>
                    </a>
                    <table class="min-w-full borde border-gray-300 rounded-lg text-sm">
                        <thead class="bg-gray-600 text-gray-100 text-sm" >
                            <tr>
                                <th class="border border-gray-300 p-2 text-left font-semibold uppercase">BL</th>
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
                                            {{ $detail->type_pay ?? 'N/A' }}
                                            @if (($detail->type_pay ?? '') == 'Chèque')
                                                <span class="text-sm text-gray-500"> - Ref: {{ $detail->reference_chq ?? 'N/A' }}</span>
                                                <span class="text-sm text-gray-500">- Date: {{ $detail->date_chq ?? 'N/A' }}</span>
                                            @endif
                                        </td>
                                    </tr>
                            @endforeach
                            <tr>
                                <td class="border border-gray-300 p-2 bg-gray-100 text-gray-800 text-right font-bold text-orange-500 uppercase" colspan="2">Total solde de bon</td>
                                <td class="border border-gray-300 p-2 bg-gray-100 text-green-600 font-bold text-center" colspan="1"> {{ number_format($filteredReglementsSum ?? 0, 2)  }} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

{{-- ------------------ table of impayed out sales ---------------------------  --}}
                <div class="mt-6 overflow-x-auto bg-white p-4 shadow-md rounded-lg" id="SalesImpayedOut">
                    <a >
                        <h3 class="text-lg font-semibold mb-2 text-red-900 uppercase border-l-4 border-red-900 pl-3">ventes Sortie Impayés</h3>
                    </a>
                    <table id="paymentsTable" class="min-w-full border border-gray-300 rounded-lg text-sm">
                        <thead class="bg-gray-300 text-gray-900 text-sm">
                            <tr>
                                <th class="border border-gray-50 p-2 text-left font-semibold uppercase">Client</th>
                                <th class="border border-gray-50 p-2 text-left font-semibold uppercase">Montant Rest</th>
                                <th class="border border-gray-50 p-2 text-left font-semibold uppercase">date Sortie</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>

            <!-- Recap de Journée Table -->
            <div class="col-span-2 ">
                <div class="mt-6 overflow-x-auto bg-white p-4 shadow-md rounded-lg">
                <a href="{{ route('journal.caisse') }}">
                    <h3 class="text-lg font-semibold mb-2 text-gray-800 uppercase border-l-4 border-green-500 pl-3">Récap de Journée {{ \Carbon\Carbon::parse($dateFrom)->format('d-m-Y') }} </h3>
                </a>
                <table class="min-w-full border border-gray-300 rounded-lg shadow-md text-sm">
                    <thead class="bg-gray-600 text-gray-100">
                        <tr>
                            <th class="border border-gray-300 p-2 text-center font-semibold uppercase" colspan="2">Total Chiffre d'Affaires</th>
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
        {{-- </div> --}}


        <div class="container mx-auto mt-6 pl- col-span-3 mb-4">
            <!-- Reglements within 24h -->
            <div class="mb-2 bg-white p-4 shadow-md rounded-lg">
                <a href="{{ route('reglements.index') }}">
                    <h2 class="text-lg font-bold text-gray-800 mb-2 uppercase border-l-4 border-blue-500 pl-3">échéance chèque <span class="text-sm text-gray-600">(24h avant ou aujourd'hui)</span></h2>
                </a>
                <div class="overflow-x-auto bg-white shadow-md">
                    <table class="w-full border-collapse border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-2 py-1 text-left">NB</th>
                                <th class="border border-gray-300 px-2 py-1 text-left">Raison</th>
                                <th class="border border-gray-300 px-2 py-1 text-left">Date</th>
                                <th class="border border-gray-300 px-2 py-1 text-left">Montant</th>
                                <th class="border border-gray-300 px-2 py-1 text-left">Date Chq</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reglementsWithin24h as $reglement)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-2 py-2">{{ $reglement->no_bl }}</td>
                                    <td class="border border-gray-300 px-2 py-2">{{ $reglement->name_client }}</td>
                                    <td class="border border-gray-300 px-2 py-2">{{ $reglement->created_at->format('d-m-Y') }}</td>
                                    <td class="border border-gray-300 px-2 py-2 text-green-600 font-semibold">{{ number_format($reglement->montant, 2) }} DH</td>
                                    {{-- <td class="border border-gray-300 px-4 py-2">{{ $reglement->date_chq->format('d-m-Y') }}</td> --}}
                                    <td class="border border-gray-300 px-2 py-2 bg-orange-100">{{ \Carbon\Carbon::parse($reglement->date_chq)->format('d-m-Y') }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        
            <!-- AchatReglements within 48h -->
            <div class="bg-white p-4 shadow-md rounded-lg">
                <h2 class="text-lg font-bold text-gray-800 mb-2 uppercase border-l-4 border-blue-500 pl-3">
                    Achats échéance chèque 
                    <span class="text-sm text-gray-600">(48h avant ou aujourd'hui)</span>
                </h2>
                {{-- <h2 class="text-lg font-semibold mb-4 uppercase">Achats échéance chèque <span class="text-sm">(48h avant ou aujourd'hui)</span></h2> --}}
                <div class="overflow-x-auto bg-white shadow-md ">
                    <table class="w-full border-collapse border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2 text-left">NB</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Raison</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Date</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Montant</th>
                                <th class="border border-gray-300 px-4 py-2 text-left">Date Chq</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($achatReglementsWithin48h as $achatReglement)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-2">{{ $achatReglement->no_bl }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $reglement->name_fournisseur }}</td>
                                    <td class="border border-gray-300 px-4 py-2">{{ $achatReglement->created_at->format('d-m-Y') }}</td>
                                    <td class="border border-gray-300 px-4 py-2 text-blue-600 font-semibold">{{ number_format($achatReglement->montant, 2) }} DH</td>
                                    <td class="border border-gray-300 px-4 py-2 bg-orange-100">{{ $achatReglement->date_chq->format('d-m-Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
    </div>

{{-- -------------------------------------------------------
------------------------------------------------------- --}}

    {{-- ||||||||| SITUATION ATELIER PART ||||||||| --}}
    <div class="flex justify-center mt-8">
        <a href="{{ route('listBonCoupe.index') }}">
            <h2 class="text-lg font-bold text-gray-800 mb-2 uppercase border-b-4 border-orange-300 px-3 pb-1 shadow-md rounded-xl">SITUATION D'ATELIER <span class="text-sm text-gray-600">(En cours de coupe et finition)</span></h2>
        </a>
    </div>

    <div class="grid grid-cols-2 gap-4 mt-2 mx-4 border-b- border-gray-300 pb-8 rounded-lg">
        <!-- Table for Bons with Coupe 'En cours' -->
        <div class="bg-white overflow-x-auto shadow-md rounded-lg p-4">
            <h2 class="text-xl font-semibold text-gray-700 mb-3">Bons En Cours de Coupe</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2 text-left">NB</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Commencé</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Produit</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Nom Client</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Coupe</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bonsCoupe as $bon)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $bon['no_bl'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($bon['dateCommence'])->format('d-m-Y H:i:s') }} </td>
                            <td class="border border-gray-300 px-4 py-2">{{ $bon['produit'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $bon['nom_client'] }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-orange-500 font-semibold">{{ $bon['coupe'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Table for Bons with Finition 'En cours' -->
        <div class="bg-white overflow-x-auto shadow-md rounded-lg p-4">
            <h2 class="text-xl font-semibold text-gray-700 mb-3">Bons En Cours de Finition</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2 text-left">NB</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Produit</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Nom Client</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Finition</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bonsFinition as $bon)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $bon['no_bl'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $bon['produit'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $bon['nom_client'] }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-orange-500 font-semibold">{{ $bon['finition'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


{{-- -------------------------------------------------------
------------------------------------------------------- --}}

    {{-- ||||||||| SITUATION Sortie PART ||||||||| --}}

    <div class="flex justify-center mt-2">
        <a href="{{ route('listBonSortie.index') }}">
            <h2 class="text-lg font-bold text-gray-800 mb-2 uppercase border-b-4 border-blue-300 px-3 pb-1 shadow-sm rounded-lg">
                SITUATION DES SORTIES <span class="text-sm text-gray-600">(Bons Sortie Validés)</span>
            </h2>
        </a>
    </div>
    
    <div class="flex justify-center mt-2 mx-4 border-b border-gray-300 pb-8 rounded-lg">
        <!-- Table for Bons with Sortie 'Oui' -->
        <div class="bg-white overflow-x-auto shadow-md rounded-lg p-4 w-full max-w-4xl">
            <h2 class="text-xl font-semibold text-gray-700 mb-3 text-center">Bons de Sortie Confirmés</h2>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2 text-left">NB</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Date Sortie</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Produit</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Nom Client</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Sortie</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bonsSortie as $bon)
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2">{{ $bon['no_bl'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ \Carbon\Carbon::parse($bon['dateSortie'])->format('d-m-Y H:i:s') }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $bon['produit'] }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $bon['nom_client'] }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-blue-500 font-semibold">{{ $bon['sortie'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
    

    <div class="mx-12 mt-6 bg-white p-4 rounded-lg shadow-md">
        <div class="flex justify-between">
            <a href="{{ route('paymentStatus.index') }}">
                <h2 class="text-lg font-bold text-gray-800  uppercase border-l-4 border-b-4 border-blue-300 px-3 pb-1 rounded-xl shadow-md pt-2 mt-1">
                    SITUATION DES ventes <span class="text-sm text-gray-600">(ventes aujourd'hui)</span>
                </h2>
            </a>
            @if($lastSaleCheck)
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4 inline-block " role="alert">
                    <b>Dernière modif, BL:</b> {{ $lastSaleCheck->no_bl }} => {{ $lastSaleCheck->created_at->format('d-m-Y H:i:s') }}, par <span class="font-bold">{{ $lastSaleCheck->user_name }}</span>
                </div>
            @endif
        </div>
        
        <div class="overflow-x-auto rounded-lg shadow-lg border border-gray-200" id="paymentStatusContainer">
            <table id="dashboard-payment-status-table" class="min-w-full bg-white">
                <thead class="bg-gradient-to-r from-blue-500 to-blue-800 sticky top-0">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">No BL</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Code Client</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Nom Client</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Date BL</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Coupe</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Sortie</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Temps Différence</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Montant Total</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Montant Payé</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase tracking-wider">Montant Restant</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Rows will be populated dynamically by DataTables -->
                </tbody>
            </table>
        </div>
        
    </div>

@endcan

<script>
    // display paymentStatus table
    $(document).ready(function () {
        $('#dashboard-payment-status-table').DataTable({
            processing: true,
            serverSide: true,
            // pageLength: 5, // Set default number of entries to show
            lengthMenu : [5,10,25,50,100],
            ajax: {
                url: '{{ route('dashboard.paymentStatusData') }}',
                type: 'GET',
            },
            order : [[0,'desc']],
            columns: [
                { data: 'no_bl', name: 'no_bl' },
                { data: 'code_client', name: 'code_client' },
                { 
                    data: 'name_client', 
                    name: 'name_client',
                    render: function (data, type, row) {
                        return `<a href="#" class="client-name" data-client-code="${row.code_client}">${data}</a>`;
                    }
                },
                { data: 'date_bl', name: 'date_bl' },
                { 
                    data: 'coupe', 
                    name: 'coupe',
                    render: function (data) {
                        return `<p class="rounded-md px-2 py-1 border ${getStatusClass(data)}">${data}</p>`;
                    }
                },
                { 
                    data: 'sortie', 
                    name: 'sortie',
                    render: function (data) {
                        return `<p class="rounded-md px-2 py-1 border ${getStatusClass(data)}">${data}</p>`;
                    }
                },
                { data: 'time_difference', name: 'time_difference' },
                { 
                    data: 'montant_total', 
                    name: 'montant_total', 
                    render: function (data) {
                        return formatNumberWithSpaces(data);
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
                        return `<span class="${getColorClass(data)}">${formatNumberWithSpaces(data)}</span>`;
                    }
                },
                
                
            ],
            createdRow: function (row, data, dataIndex) {
            // Check if changeCount > 0 and add a red background
            if (data.changeCount > 0) {
                $('td', row).eq(0).css('background-color', '#ffd0d0'); // Change the background of the first cell
            }
        }
            
            
        });

        function getStatusClass(value) {
            if (value === 'Oui') return 'bg-green-400 text-gray-200';
            if (value === 'Non') return 'bg-red-400 text-gray-200';
            if (value === 'En cours') return 'bg-orange-400 text-gray-200';
            if (value === 'Sans') return 'bg-blue-400 text-gray-200';
            return '';
        }

        function getColorClass(value) {
            if (value >= 1 && value < 5) return 'text-blue';
            if (value >= 5 && value < 10) return 'text-yellow';
            if (value >= 10 && value < 25) return 'text-orange';
            if (value >= 25) return 'text-red';
            return '';
        }

        function formatNumberWithSpaces(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }
    });

// ===============================================================
// PART OF IMPAYED SALES OUT WITH BON SORTIE STATUS 'OUI'

    $(document).ready(function() {
        $('#paymentsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('payments.data') }}",
            lengthMenu: [5, 10, 25, 50, 100],
            columns: [
                { data: 'name_client', name: 'name_client' },
                { data: 'montant_restant', name: 'montant_restant', render: function(data, type, row) {
                    return `<a href="#" class="text-red-600 font-bold">${data}</a>`;
                }},
                { 
                    data: 'date_sortie', name: 'date_sortie', 
                    render: function(data, type, row) {
                        return row.date_sortie_formatted ? row.date_sortie_formatted : ''; 
                    }
                }
            ],
            order: [[2, 'desc']], // Sort by `date_sortie` (raw value) in descending order
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/French.json'
            }
        });
    });

</script>

<style>
    /* Custom CSS for the table */
    #dashboard-payment-status-table {
        border-collapse: separate;
        border-spacing: 0;
    }

    #dashboard-payment-status-table th {
        position: sticky;
        top: 0;
        z-index: 10;
        backdrop-filter: blur(20px); /* Adds a blur effect to the sticky header */
        font-size: 12px;
    }

    #dashboard-payment-status-table td {
        transition: background-color 0.2s ease; /* Smooth hover transition */
        font-size: 12px;

    }

    #dashboard-payment-status-table tbody tr:hover td {
        background-color: #f3f4f6; /* Light gray background on hover */
    }

    #dashboard-payment-status-table tbody tr:nth-child(odd) {
        background-color: #f9fafb; /* Light gray for odd rows */
    }
    #paymentStatusContainer .dataTables_wrapper .dataTables_length select {
        background-color: #d6d5d54e;
        width: 90px;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #6b6b77;
        margin-bottom: 2px;
    }

/* --------------------------==========------------------- */
    /* table impayed bons was out   */

    #SalesImpayedOut .dataTables_wrapper .dataTables_length select {
        background-color: #e5e7eb;
        width: 50px;
        padding: 4px;
        border-radius: 5px;
        border: 1px solid #6b6b77;
        margin-bottom: 2px;
    }

    #SalesImpayedOut #paymentsTable {
        border-collapse: separate;
        border-spacing: 0;
    }

    #SalesImpayedOut #paymentsTable th {
        position: sticky;
        top: 0;
        z-index: 10;
        backdrop-filter: blur(20px); /* Adds a blur effect to the sticky header */
        font-size: 12px;
    }

    #SalesImpayedOut #paymentsTable td {
        transition: background-color 0.2s ease; /* Smooth hover transition */
        font-size: 12px;

    }

    #SalesImpayedOut #paymentsTable tbody tr:hover td {
        background-color: #ccced2; /* Light gray background on hover */
    }

    #SalesImpayedOut #paymentsTable tbody tr:nth-child(odd) {
        background-color: #eaeff1e3; /* Light gray for odd rows */
    }

    #SalesImpayedOut .dataTables_wrapper .dataTables_paginate .paginate_button {
        background-color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        margin-left: 5px;
    }
    #SalesImpayedOut .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #c5c8cfce;
    }
</style>

@endsection
