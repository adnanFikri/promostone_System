@extends('layouts.app')

@section('content')
<div class="container mx-auto p-9">
    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Journal de Caisse</h2>

     <!-- Filter Form -->
     <form method="POST" action="{{ route('journal.caisse.filter') }}" class="mb-6 bg-gray-100 p-4 rounded-lg shadow-md">
        @csrf
        <div class="flex space-x-4">
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700">Date Début</label>
                <input type="date" id="date_from" name="date_from" required class="mt-1 p-2 w-full border rounded-lg" value="{{ request('date_from') }}">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700">Date Fin</label>
                <input type="date" id="date_to" name="date_to" required class="mt-1 p-2 w-full border rounded-lg" value="{{ request('date_to') }}">
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Filtrer</button>
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

    <div class="overflow-x-auto">
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
                    @endphp

                    @foreach ($salesByProduit as $produit => $sales)
                        @php
                            $totalProductRows = count($sales);
                            $firstRowProduit = true;
                        @endphp
                        
                        @foreach ($sales as $index => $sale)
                            
                            <tr class=" ">
                                @if ($firstRowBl)
                                    @php $totalBlRows = collect($salesByProduit)->flatten(1)->count(); @endphp
                                    <td class="border border-gray-300 p-2 align-middle  font-medium text-gray-800" rowspan="{{ $totalBlRows }}">{{ $detail['no_bl'] }}</td>
                                    <td class="border border-gray-300 p-2 align-middle " rowspan="{{ $totalBlRows }}">{{ $sale->code_client }}</td>
                                    <td class="border border-gray-300 p-2 align-middle " rowspan="{{ $totalBlRows }}">{{ $paymentStatus->name_client ?? 'N/A' }}</td>
                                @endif

                                @if ($firstRowProduit)
                                    <td class="border border-gray-300 p-2 font-medium text-blue-600 align-middle" rowspan="{{ $totalProductRows }}">{{ $produit }}</td>
                                    @php $firstRowProduit = false; @endphp
                                @endif

                                <td class="border border-gray-300 p-2 text-center">
                                    @if ($sale->mode == 'Unité' || $sale->mode == 'service')
                                        {{ $sale->nbr }}
                                    @else
                                        {{ $sale->qte }}
                                    @endif
                                </td>
                                <td class="border border-gray-300 p-2 text-center">{{ number_format($sale->prix_unitaire, 2) }}</td>
                                <td class="border border-gray-300 p-2 text-center font-medium">{{ number_format($sale->montant, 2) }}</td>

                                @if ($firstRowBl)
                                <td class="border border-gray-300 p-2 align-middle text-blue-700 text-center font-bold" rowspan="{{ $totalBlRows }}">{{ $paymentStatus->montant_total }}</td>
                                    <td class="border border-gray-300 p-2 align-middle text-gray-800 font-medium" rowspan="{{ $totalBlRows }}">
                                        {{ $reglements->type_pay ?? 'N/A' }}
                                        @if ($reglements->type_pay == 'Chèque')
                                            <br><span class="text-sm text-gray-500">Ref: {{ $reglements->reference_chq ?? 'N/A' }}</span>
                                            <br><span class="text-sm text-gray-500">Date: {{ $reglements->date_chq ?? 'N/A' }}</span>
                                        @endif
                                    </td>
                                    <td class="border border-gray-300 p-2 text-center align-middle font-semibold text-green-600" rowspan="{{ $totalBlRows }}">
                                        {{ number_format($reglements->montant ?? 0, 2) }}
                                    </td>
                                    <td class="border border-gray-300 p-2 text-center align-middle font-semibold text-red-600" rowspan="{{ $totalBlRows }}">
                                        {{ number_format($paymentStatus->montant_restant ?? 0, 2) }}
                                    </td>
                                    @php $firstRowBl = false; @endphp
                                @endif
                            </tr>
                        @endforeach
                    @endforeach
                @endforeach
                <tr >
                    <td class="border border-gray-300 p-3 bg-gray-200  text-orange-500 text-center align-middle font-semibold  text-right" colspan="7">TOTAUX</td>
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
                            <th class="border border-gray-300 p-2 text-center font-semibold uppercase">Montant</th>
                            <th class="border border-gray-300 p-2 text-left font-semibold uppercase">Type Paiement</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($filteredReglements as $detail)
                                <tr>
                                    <td class="border border-gray-300 p-2 text-gray-800">{{ $detail['no_bl'] }}</td>
                                    <td class="border border-gray-300 p-2 text-center font-medium text-green-600">
                                        {{ number_format($detail->montant ?? 0, 2) }}
                                    </td>
                                    <td class="border border-gray-300 p-2 text-gray-800">
                                        {{-- {{ $detail->type_pay === 'Chèque' ?  $detail->type_pay . ', N ' . $detail->reference_chq . ' - date '. $detail->date_chq : $detail->type_pay }} --}}
                                        {{ $detail->type_pay ?? 'N/A' }}
                                        @if ($detail->type_pay == 'Chèque')
                                            <span class="text-sm text-gray-500"> - Ref: {{ $detail->reference_chq ?? 'N/A' }}</span>
                                            <span class="text-sm text-gray-500">- Date: {{ $detail->date_chq ?? 'N/A' }}</span>
                                        @endif
                                    </td>
                                </tr>
                        @endforeach
                        <tr>
                            <td class="border border-gray-300 p-2 bg-gray-200 text-gray-800 text-right font-bold text-orange-500 uppercase">Total solde de bon</td>
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
@endif 
</div>
@endsection
