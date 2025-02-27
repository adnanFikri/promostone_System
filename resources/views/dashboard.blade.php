@extends('layouts.app')

@section('content')

<style>
    /* Custom DataTable styles */
    .dataTables_wrapper .dataTables_length select {
        background-color: #d3d3d34e;
        width: 90px;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #6b6b77;
        margin-bottom: 2px;
    }
</style>

<div class="grid grid-cols-8 gap-4 mx-4 mt-6">
    {{-- <div class="div-journalCaisse  "> --}}
        <div class="overflow-x-auto mt-6 col-span-3 ">
            <h3 class="text-lg font-semibold mb-2 text-gray-800 uppercase border-l-4 border-green-500 pl-3">sold de bon</h3>
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
                        <td class="border border-gray-300 p-2 bg-gray-200 text-gray-800 text-right font-bold text-orange-500 uppercase" colspan="2">Total solde de bon</td>
                        <td class="border border-gray-300 p-2 bg-gray-200 text-green-600 font-bold text-center" colspan="1"> {{ number_format($filteredReglementsSum ?? 0, 2)  }} </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Recap de Journée Table -->
        <div class="overflow-x-auto mt-6 col-span-2">
            <h3 class="text-lg font-semibold mb-2 text-gray-800 uppercase border-l-4 border-green-500 pl-3">Récap de Journée {{ $dateFrom }} </h3>
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
    {{-- </div> --}}


    <div class="container mx-auto mt-6 px-4 col-span-3">
        <!-- Reglements within 24h -->
        <div class="mb-8">
            <h2 class="text-lg font-bold text-gray-800 mb-2 uppercase border-l-4 border-blue-500 pl-3">échéance chèque <span class="text-sm text-gray-600">(24h avant ou aujourd'hui)</span></h2>
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
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
                                <td class="border border-gray-300 px-2 py-2">{{ \Carbon\Carbon::parse($reglement->date_chq)->format('d-m-Y') }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    
        <!-- AchatReglements within 48h -->
        <div>
            <h2 class="text-lg font-bold text-gray-800 mb-2 uppercase border-l-4 border-blue-500 pl-3">
                Achats échéance chèque 
                <span class="text-sm text-gray-600">(48h avant ou aujourd'hui)</span>
            </h2>
            {{-- <h2 class="text-lg font-semibold mb-4 uppercase">Achats échéance chèque <span class="text-sm">(48h avant ou aujourd'hui)</span></h2> --}}
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
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
                                <td class="border border-gray-300 px-4 py-2">{{ $achatReglement->date_chq->format('d-m-Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</div>
{{-- 
<div class="mx-4 mt-6">
    @if($lastSaleCheck)
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4 inline-block " role="alert">
            <b>Dernière modif, BL:</b> {{ $lastSaleCheck->no_bl }} => {{ $lastSaleCheck->created_at }}, par <span class="font-bold">{{ $lastSaleCheck->user_name }}</span>
        </div>
    @endif
    <table id="dashboard-payment-status-table" class="table table-striped">
        <thead>
            <tr>
                <th>No BL</th>
                <th>Code Client</th>
                <th>Nom Client</th>
                <th>Date BL</th>
                <th>Coupe</th>
                <th>Sortie</th>
                <th>Temps Différence</th>
                <th>Montant Total</th>
                <th>Montant Payé</th>
                <th>Montant Restant</th>
            </tr>
        </thead>
    </table>
    
</div> --}}

<script>
    // display paymentStatus table
    // $(document).ready(function () {
    //     $('#dashboard-payment-status-table').DataTable({
    //         processing: true,
    //         serverSide: true,
    //         // pageLength: 5, // Set default number of entries to show
    //         lengthMenu : [5,10,25,50,100],
    //         ajax: {
    //             url: '{{ route('dashboard.paymentStatusData') }}',
    //             type: 'GET',
    //         },
    //         order : [[0,'desc']],
    //         columns: [
    //             { data: 'no_bl', name: 'no_bl' },
    //             { data: 'code_client', name: 'code_client' },
    //             { 
    //                 data: 'name_client', 
    //                 name: 'name_client',
    //                 render: function (data, type, row) {
    //                     return `<a href="#" class="client-name" data-client-code="${row.code_client}">${data}</a>`;
    //                 }
    //             },
    //             { data: 'date_bl', name: 'date_bl' },
    //             { 
    //                 data: 'coupe', 
    //                 name: 'coupe',
    //                 render: function (data) {
    //                     return `<p class="rounded-md px-2 py-1 border ${getStatusClass(data)}">${data}</p>`;
    //                 }
    //             },
    //             { 
    //                 data: 'sortie', 
    //                 name: 'sortie',
    //                 render: function (data) {
    //                     return `<p class="rounded-md px-2 py-1 border ${getStatusClass(data)}">${data}</p>`;
    //                 }
    //             },
    //             { data: 'time_difference', name: 'time_difference' },
    //             { 
    //                 data: 'montant_total', 
    //                 name: 'montant_total', 
    //                 render: function (data) {
    //                     return formatNumberWithSpaces(data);
    //                 }
    //             },
    //             { 
    //                 data: 'montant_payed', 
    //                 name: 'montant_payed', 
    //                 render: function (data) {
    //                     return formatNumberWithSpaces(data);
    //                 }
    //             },
    //             { 
    //                 data: 'montant_restant', 
    //                 name: 'montant_restant', 
    //                 render: function (data) {
    //                     return `<span class="${getColorClass(data)}">${formatNumberWithSpaces(data)}</span>`;
    //                 }
    //             },
                
                
    //         ],
    //         createdRow: function (row, data, dataIndex) {
    //         // Check if changeCount > 0 and add a red background
    //         if (data.changeCount > 0) {
    //             $('td', row).eq(0).css('background-color', '#ffd0d0'); // Change the background of the first cell
    //         }
    //     }
            
            
    //     });

    //     function getStatusClass(value) {
    //         if (value === 'Oui') return 'bg-green-400 text-gray-200';
    //         if (value === 'Non') return 'bg-red-400 text-gray-200';
    //         if (value === 'En cours') return 'bg-orange-400 text-gray-200';
    //         if (value === 'Sans') return 'bg-blue-400 text-gray-200';
    //         return '';
    //     }

    //     function getColorClass(value) {
    //         if (value >= 1 && value < 5) return 'text-blue';
    //         if (value >= 5 && value < 10) return 'text-yellow';
    //         if (value >= 10 && value < 25) return 'text-orange';
    //         if (value >= 25) return 'text-red';
    //         return '';
    //     }

    //     function formatNumberWithSpaces(number) {
    //         return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    //     }
    // });


</script>

@endsection
