@extends('layouts.app')

@section('content')

<style>
    @media print {
        @page {
            margin: 0; /* Removes browser-added headers/footers */
        }
        body {
            -webkit-print-color-adjust: exact !important; /* Ensures background colors are included */
            print-color-adjust: exact !important; /* Ensures background colors are included */
            margin: none;
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
        @page {
    margin: 0; /* Removes default browser headers/footers */
    size: auto; /* Keeps the default paper size */
}
body {
    margin: 1cm; /* Adds space around the content */
    
}
        
    }
    #main-content{
    background-color: white
    }
    

    
    
</style>
@can("view bon livraison")
<div class="container mx-auto my-8 p-4">
    @can('print bons')
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
    @endcan

    <!-- Bon de Livraison Card -->
    <div id="bon-de-livraison" class="borde bordr-gray-300 rounded-lg p-6  bg-white">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-4 text-center ">
            <span class="text-gray-500">le {{ $paymentStatus['date_bl'] }}</span>
            <div class="border border-gray-400 rounded-lg p-2 bg-gray-200  text-center min-w-72">
                <p class="font-bold text-orange-500 text-lg">{{ $paymentStatus->name_client }}</p>
                <p><strong>Code Client:</strong> {{ $paymentStatus->code_client }}</p>
                <p><strong>Téléphone:</strong> {{ $client->phone }}</p>
            </div>
        </div>

        <!-- Client BON -->
        <h2 class="mb-4 text-2xl font-bold text-center text-gray-600">BON DE LIVRAISON N° <span class="text-black border bg-gray-200 px-1 shadow " id="bl-number">{{ $paymentStatus->no_bl }}</span> / {{ date('Y') }}</h2>
        

        <!-- Agent Info -->
        <p class="mb-4 border p-4 border-gray-300 rounded-md"><strong>Agent:</strong> {{ $paymentStatus->commerçant }} {{ $paymentStatus['tel-commerçant'] ? '- '. $paymentStatus['tel-commerçant'] .' ' : ''}}</p>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-100" id="headertb"> 
                    <tr>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600 uppercase">Désignation</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600 uppercase">Mesure</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600 uppercase">Qté</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600 uppercase">Surface</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600 uppercase">Unité</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600 uppercase">P.U HT</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600 uppercase">Montant</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($groupedSales as $produit => $sales)
                    @foreach($sales as $index => $sale)
                        @if ($sale->mode == "service")
                            <!-- Row for services -->
                            <tr>
                                <td class="border border-gray-300 p-2 font-bold text-blue-600" colspan="1">
                                    {{ $sale->produit }}
                                </td>
                                <td class="border border-gray-300 p-2 text-center" colspan="3">{{ $sale->nbr ?? '-' }}</td>
                                <td class="border border-gray-300 p-2 text-center">Service</td>
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
                                @if ($sale->mode =="Unité")
                                    {{-- <td class="border border-gray-300 p-2 text-center" > - </td> --}}
                                    {{-- <td class="border border-gray-300 p-2 text-center">{{ $sale->qte }}</td> --}}
                                    <td class="border border-gray-300 p-2 text-center" colspan="3">{{ $sale->nbr }}</td>
                                    <td class="border border-gray-300 p-2 text-center" >{{ $sale->mode }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->prix_unitaire }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->montant }}</td>
                                @else
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->longueur }} x {{ $sale->largeur }} </td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->nbr }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->qte }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->mode }}</td>
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

        <div class="grid grid-cols-10 gap-4 mt-4">

            <!-- Payment Details -->
            <div class="col-span-6">
                <h3 class="text-lg font-bold">Détails des Réglements:</h3>
                <table class="w-full border-collapse border border-gray-300 mt-1 ">
                    <thead class="bg-gray-100 ">
                        <tr>
                            <th class="border-2 border-gray-300 p-2 text-left text-gray-600 uppercase">Type de Paiement</th>
                            <th class="border-2 border-gray-300 p-2 text-left text-gray-600 uppercase">Date Paye</th>
                            <th class="border-2 border-gray-300 p-2 text-left text-gray-600 uppercase">Montant</th>
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

            <!-- Payment Section -->
            <div class="col-span-4">
                <h3 class="text-lg font-bold">Statut Paiement :</h3>
                <table class="w-full border-collapse border border-gray-300 mt-1 ">
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
                            <td class="border border-gray-300 p-2 text-red-400">{{ $paymentStatus->montant_restant > 0 ? $paymentStatus->montant_restant : 0 }}</td>
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
            
        </div>

        <!-- Footer Section -->
        <div class="mt-12 text-center text-gray-500 text-sm">
            <p>📞 0537643290 - 0537643615</p>
            <p>📱 +212 661 464 017</p>
            <p>✉️ promostone2021@gmail.com</p>
        </div>
    </div>
</div>
@endcan
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    // function printBonLivraison() {
    //     const printContent = document.getElementById('bon-de-livraison');
    //     const originalContent = document.body.innerHTML;

    //     document.body.innerHTML = printContent.outerHTML;
    //     window.print();
    //     // Restore original content
    //     document.body.innerHTML = originalContent;
    //     location.reload();
    // }
//     function printBonLivraison() {
//     const printContent = document.getElementById('bon-de-livraison');
//     const originalContent = document.body.innerHTML;

//     document.body.innerHTML = printContent.outerHTML;

//     // Force reflow to apply styles before print
//     setTimeout(() => {
//         window.print();
//         // Restore original content after printing
//         document.body.innerHTML = originalContent;
//         window.onafterprint = function() {
//             location.reload();
//         };
//     }, 100);
// }

function printBonLivraison() {
    var content = document.getElementById("bon-de-livraison").innerHTML;

    // Get the Bon de Livraison number (BL number) if available
    var bl_no = document.getElementById("bl-number") ? document.getElementById("bl-number").textContent.trim() : "unknown";

    // Get the current date in YYYY-MM-DD format
    var now = new Date();
    var formattedDate = now.getFullYear() + "-" + 
                        String(now.getMonth() + 1).padStart(2, '0') + "-" + 
                        String(now.getDate()).padStart(2, '0');

    // Construct the filename
    var filename = `bl_${bl_no}_${formattedDate}`;

    // Open a new print window
    var printWindow = window.open("", "_blank");

    if (!printWindow) {
        alert("Pop-ups are blocked! Allow them to print the document.");
        return;
    }

    // Add Tailwind CSS via CDN and custom styles
    var styles = `
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <style>
            @page {
                size: A4;
                margin: 9mm; /* No margins, removes browser headers/footers */
            }
            body {
                font-family: Arial, sans-serif;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                padding: 1cm; /* Add padding inside the page */
                zoom: 0.8; /* Scale content to 80% */
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


</script>

@endsection
