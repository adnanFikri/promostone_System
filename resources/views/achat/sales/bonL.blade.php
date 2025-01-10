@extends('layouts.app')

@section('content')

<style>
    @media print {
        body {
            -webkit-print-color-adjust: exact !important; /* Ensures background colors are included */
            print-color-adjust: exact !important; /* Ensures background colors are included */
        }
        .print:hidden {
            display: none !important;
        }
        #headertb {
            background-color: rgb(158, 155, 155) !important; /* Background color for header */
        }
        table th {
            background-color: rgb(158, 155, 155) !important; /* Ensure background for table headers */
        }
        .bg-gray-100 {
            background-color: rgb(229, 231, 235) !important; /* Set a default background color for 'bg-gray-100' in print */
        }
        .bg-gray-200 {
            background-color: rgb(203, 213, 225) !important; /* Set a default background color for 'bg-gray-200' in print */
        }
        .bg-blue-500 {
            background-color: rgb(59, 130, 246) !important; /* Ensure background for button in print */
        }
        
    }
    #main-content{
    background-color: white
    }

    
    
</style>
@can("view bon livraison")
<div class="container mx-auto my-8 p-4">
    <!-- Header Buttons (hidden when printing) -->
    <div class="flex justify-center gap-4 mb-6 print:hidden">
        <button onclick="printBonLivraison()" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
            Imprimer
        </button>
        @can("view bon coup")
            <a href="{{ route('bon_coup', ['no_bl' => $paymentStatus->no_bl]) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                Générer Bon de Coupe
            </a>
        @endcan
        @can("view bon sortie")
            <a href="{{ route('bon_sortie', ['no_bl' => $paymentStatus->no_bl]) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                Générer Bon de Sortie
            </a>
        @endcan
    </div>

    <!-- Bon de Livraison Card -->
    <div id="bon-de-livraison" class="borde bordr-gray-300 rounded-lg p-6  bg-white">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-4 text-center">
            <span class="text-gray-500">Rabat le {{ $paymentStatus['date-echeance'] }}</span>
            <h2 class="text-2xl font-bold">BON DE LIVRAISON N° {{ $paymentStatus->no_bl }}/{{ date('Y') }}</h2>
        </div>

        <!-- Client Info -->
        <div class="border border-gray-400 rounded-lg p-4 bg-gray-200 m-auto mb-4 text-center w-96">
            <p class="font-bold text-orange-500 text-lg">{{ $paymentStatus->name_client }}</p>
            <p><strong>Code Client:</strong> {{ $paymentStatus->code_client }}</p>
            <p><strong>Téléphone:</strong> {{ $client->phone }}</p>
        </div>

        <!-- Agent Info -->
        <p class="mb-4 border p-4 border-gray-300 rounded-md"><strong>Agent:</strong> {{ $paymentStatus->commerçant }} {{ $paymentStatus['tel-commerçant'] ? '- '. $paymentStatus['tel-commerçant'] .' ' : ''}}</p>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-100" id="headertb"> 
                    <tr>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600">Désignation</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600">Mesures</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600">Surface</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600">Unité</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600">Qté</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600">P.U HT</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600">Montant</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($groupedSales as $produit => $sales)
                    @foreach($sales as $index => $sale)
                        @if ($sale->mode == "service")
                            <!-- Row for services -->
                            <tr>
                                <td class="border border-gray-300 p-2 font-bold text-blue-600" colspan="4">
                                    {{ $sale->produit }}
                                </td>
                                <td class="border border-gray-300 p-2 text-center">{{ $sale->nbr ?? '-' }}</td>
                                <td class="border border-gray-300 p-2 text-center">{{ $sale->prix_unitaire ?? '-' }}</td>
                                <td class="border border-gray-300 p-2 text-center">{{ $sale->montant }}</td>
                            </tr>
                        @else
                            <!-- Row for products -->
                            <tr>
                                @if ($index === 0)
                                    <td class="border border-gray-300 p-2" rowspan="{{ count($sales) }}">
                                        {{ $produit }}
                                    </td>
                                @endif
                                @if ($sale->mode =="Unite")
                                    <td class="border border-gray-300 p-2 text-center" colspan="2">{{ $sale->longueur }} - {{ $sale->largeur }}</td>
                                    {{-- <td class="border border-gray-300 p-2 text-center">{{ $sale->qte }}</td> --}}
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->mode }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->nbr }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->prix_unitaire }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->montant }}</td>
                                @else
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->longueur }} x {{ $sale->largeur }} </td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->qte }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->mode }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->nbr }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->prix_unitaire }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->montant }}</td>
                                @endif
                            </tr>
                        @endif
                    @endforeach
                @endforeach
                   
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100">
                        <td colspan="6" class="border border-gray-300 p-2 text-right font-bold">Total GLOBAL H.T:</td>
                        <td class="border border-gray-300 p-2 font-bold text-blue-700 text-center">{{ $paymentStatus->montant_total }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

         <!-- Payment Section -->
         <div class="mt-4">
            <h3 class="text-lg font-bold">Status Paiement :</h3>
            <table class="min-w-96 border-collapse border border-gray-300 mt-4 ">
                <thead class="">
                    <tr>
                        <th class="bg-gray-100 border border-gray-300 p-2 text-left text-gray-600">Montant de Bon</th>
                        <td class="border border-gray-300 p-2">{{ $paymentStatus->montant_total }}</td>
                    </tr>
                    <tr>
                        <th class="bg-gray-100 border border-gray-300 p-2 text-left text-gray-600">Montant Payé</th>
                        <td class="border border-gray-300 p-2">{{ $paymentStatus->montant_payed }}</td>
                    </tr>
                    <tr>
                        <th class="bg-gray-100 border border-gray-300 p-2 text-left text-gray-600">Reste à Payer</th>
                        <td class="border border-gray-300 p-2 text-red-400">{{ $paymentStatus->montant_restant }}</td>
                        {{-- <th class="border border-gray-300 p-2 text-left">Date</th> --}}
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        {{-- <td class="border border-gray-300 p-2">{{ $reglement->date }}</td> --}}
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment Details -->
        <div class="mt-4">
            <h3 class="text-lg font-bold">Détails des Réglements:</h3>
            <table class="w-full border-collapse border border-gray-300 mt-4 ">
                <thead class="bg-gray-100 ">
                    <tr>
                        <th class="border-2 border-gray-300 p-2 text-left text-gray-600">Type de Paiement</th>
                        <th class="border-2 border-gray-300 p-2 text-left text-gray-600">Date Paye</th>
                        <th class="border-2 border-gray-300 p-2 text-left text-gray-600">Montant</th>
                        {{-- <th class="border border-gray-300 p-2 text-left">Date</th> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach($reglements as $reglement)
                    <tr>
                        @if ($reglement->type_pay == 'Chèque')
                            <td class="border border-gray-300 p-2"><span class="font-bold">{{ $reglement->type_pay }}</span> <span> - <span class="text-gray-400">N Reference : </span>{{$reglement->reference_chq}} - <span class="text-gray-400">Date d'expiration : </span>{{$reglement->date_chq}} </span></td>
                        @else
                            <td class="border border-gray-300 p-2"><span class="font-bold">{{ $reglement->type_pay }}</span></td>
                        @endif
                        <td class="border border-gray-300 p-2">{{ $reglement->date }}</td>
                        <td class="border border-gray-300 p-2">{{ $reglement->montant }}</td>
                        {{-- <td class="border border-gray-300 p-2">{{ $reglement->date }}</td> --}}
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer Section -->
        <div class="mt-12 text-center text-gray-500 text-sm">
            <p>📞 0537643290 - 0537643615</p>
            <p>📱 0637643290</p>
            <p>✉️ promostone2021@gmail.com</p>
        </div>
    </div>
</div>
@endcan
<script>
    function printBonLivraison() {
        const printContent = document.getElementById('bon-de-livraison');
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent.outerHTML;
        window.print();
        // Restore original content
        document.body.innerHTML = originalContent;
        location.reload();
    }
</script>

@endsection
