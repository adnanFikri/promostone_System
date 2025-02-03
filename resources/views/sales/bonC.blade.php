@extends('layouts.app')

@section('content')

<style>
    @media print {
        body {
            -webkit-print-color-adjust: exact !important; /* Ensures background colors are included */
            print-color-adjust: exact !important; /* Ensures background colors are included */
            background-color: white;
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
@can("view bon coup")
<div class="container mx-auto my-8 p-4">
    @can('print bons')
        <!-- Header Buttons (hidden when printing) -->
        <div class="flex justify-center gap-4 mb-6 print:hidden">
            <button onclick="incrementAndPrint({{ $paymentStatus->no_bl }})" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                Imprimer
            </button>
            @can("view bon livraison")
                <a href="{{ route('bon_livraison', ['no_bl' => $paymentStatus->no_bl]) }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                    Générer Bon de Livraison
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
    <div id="bon-de-coupe" class="borde border-gray-00 rounded-lg p-6 shado bg-white">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-4 text-center ">
            <span class="text-gray-500">le {{ $paymentStatus['date_bl'] }} - <span class="text-gray-400 font-bold" >Impression N° <span {{$print_nbr->print_nbr > 1 ? 'class=text-red-400' : ""}}>{{$print_nbr->print_nbr + 1}}</span></span> </span>
            <div class="border border-gray-400 rounded-lg p-2 bg-gray-200  text-center min-w-72">
                <p class="font-bold text-orange-500 text-lg">{{ $paymentStatus->name_client }}</p>
                <p><strong>Code Client:</strong> {{ $paymentStatus->code_client }}</p>
                <p><strong>Téléphone:</strong> {{ $client->phone }}</p>
            </div>
        </div>

        <!-- Client BON -->
        <h2 class="text-2xl mb-4 font-bold text-center text-gray-600">BON DE COUPE N° <span class="text-black border bg-gray-200 px-1 shadow " id="bc-number">{{ $paymentStatus->no_bl }}</span> / {{ date('Y') }}</h2>
        

        <!-- Agent Info -->
        <p class="mb-4 border p-4 border-gray-300 rounded-md"><strong>Agent:</strong> {{ $paymentStatus->commerçant }} {{ $paymentStatus['tel-commerçant'] ? '- '. $paymentStatus['tel-commerçant'] .' ' : ''}}</p>

        <!-- Table Section -->
        <div class="overflow-x-auto ">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-100" id="headertb"> 
                    <tr>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600 uppercase">Désignation</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600 uppercase">Mesures</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600 uppercase">Unité</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600 uppercase">Qté</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600 uppercase">Surface</th>
                        {{-- <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600">P.U HT</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600">Montant</th> --}}
                    </tr>
                </thead>
                <tbody>
                @foreach($groupedSales as $produit => $sales)
                    @php
                        $totalSurface = 0; // Initialize total surface for this product
                    @endphp
                    @foreach($sales as $index => $sale)
                        @if ($sale->mode == "service")
                            <!-- Row for services -->
                            <tr>
                                <td class="border border-gray-300 p-2 font-bold text-blue-600" colspan="3">
                                    {{ $sale->produit }}
                                </td>
                                <td class="border border-gray-300 p-2 text-center">{{ $sale->nbr ?? '-' }}</td>
                                <td class="border border-gray-300 p-2 text-center">{{ $sale->nbr ?? '-' }}</td>
                                @php
                                        // Calculate surface for this sale and add to total
                                        // $surface = longueur * $sale->largeur;
                                    $totalSurface += $sale->nbr;
                                @endphp
                                {{-- <td class="border border-gray-300 p-2 text-center">{{ $sale->montant }}</td> --}}
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
                                    {{-- <td class="border border-gray-300 p-2 text-center">{{ $sale->prix_unitaire }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->montant }}</td> --}}
                                @else
                                    @php
                                        // Calculate surface for this sale and add to total
                                        // $surface = longueur * $sale->largeur;
                                        $totalSurface += $sale->qte;
                                    @endphp
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->longueur }} x {{ $sale->largeur }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->mode }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->nbr }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->qte }}</td>
                                    {{-- <td class="border border-gray-300 p-2 text-center">{{ $sale->prix_unitaire }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $sale->montant }}</td> --}}
                                @endif
                            </tr>
                        @endif
                    @endforeach
                    <tr class="bg-gray-100">
                        <td colspan="4" class="border border-b-2 border-gray-300 p-2 text-center font-bold">TOTAL {{$produit}} ({{$sale->ref_produit}})</td>
                        <td class="border border-b-2 border-gray-300 p-2 font-bold text-blue-700 text-center"> {{ number_format($totalSurface, 3) }} {{ $sale->mode !== "service" ? $sale->mode : '' }}</td>
                    </tr>
                @endforeach
                   
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-10 gap-4 mt-4">

            <!-- Payment Details -->
            <div class="col-span-4">
                <table class="w-full border-collapse border border-gray-300 mt-1 ">
                    <thead class="bg-gray-100 ">
                        <tr>
                            <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600 uppercase">Destination</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reglements as $reglement)
                        <tr>
                            <td colspan="4" class="border border-b-2 border-gray-300 p-2 text-cente font-meduiem uppercase">{{ $paymentStatus['destination'] }}, le {{ $paymentStatus['date-echeance'] }}</td>
                            {{-- <td class="border border-gray-300 p-2">{{ $reglement->date }}</td> --}}
                        </tr>
                        @endforeach
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
<script>

    function incrementAndPrint(noBl) {
        // Use SweetAlert2 for confirmation
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            text: "Vous êtes sur le point d'imprimer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, imprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with increment and print
                fetch(`/bonCoupe/increment-print/${noBl}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        console.log(data.message);
                        // Show success alert
                        // Swal.fire(
                        //     'Imprimé!',
                        //     'Le bon de livraison a été imprimé avec succès.',
                        //     'success'
                        // ).then(() => {
                            // Proceed to print after user confirms success message
                            printBonCoup();
                        // });
                    } else {
                        // Show error alert
                        Swal.fire(
                            'Erreur!',
                            data.message || 'Une erreur s\'est produite.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Show error alert
                    Swal.fire(
                        'Erreur!',
                        'Impossible de compléter la demande.',
                        'error'
                    );
                });
            } else {
                console.log('Impression annulée par l\'utilisateur.');
            }
        });
    }

    
    // function printBonLivraison() {
    //     const printContent = document.getElementById('bon-de-coupe');
    //     const originalContent = document.body.innerHTML;

    //      document.body.innerHTML = printContent.outerHTML;
    //     window.print();
    //     // Restore original content
    //     document.body.innerHTML = originalContent;
    //     window.onafterprint = function() {
    //         location.reload();
    //     };
    // }

    function printBonCoup() {
    // Get the HTML content you want to print
    var content = document.getElementById("bon-de-coupe").innerHTML;

    // Get the Bon de Coup number (BC number) if available.
    // (Adjust the element ID if you use a different one on the BC page.)
    var bc_no = document.getElementById("bc-number") 
                  ? document.getElementById("bc-number").textContent.trim() 
                  : "unknown";

    // Get the current date in YYYY-MM-DD format
    var now = new Date();
    var formattedDate = now.getFullYear() + "-" + 
                        String(now.getMonth() + 1).padStart(2, '0') + "-" + 
                        String(now.getDate()).padStart(2, '0');

    // Construct the filename with bc_ prefix
    var filename = `bc_${bc_no}_${formattedDate}`;

    // Open a new print window
    var printWindow = window.open("", "_blank");
    if (!printWindow) {
        alert("Pop-ups are blocked! Please allow them to print the document.");
        return;
    }

    // Add Tailwind CSS via CDN and custom styles
    var styles = `
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <style>
            @page {
                size: A4;
                margin: 9mm; /* Adjust this value if needed to ensure content isn’t cut */
            }
            body {
                font-family: Arial, sans-serif;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                margin: 0;
                padding: 1cm; /* Padding to ensure content is not too close to the edges */
                zoom: 0.8; /* Scales content to 80% */
            }
        </style>
    `;

    // Write the printable content into the new window
    printWindow.document.open();
    printWindow.document.write(`
        <html>
            <head>
                <title>${filename}</title> <!-- This sets the default filename -->
                ${styles}
            </head>
            <body class="p-6">
                ${content}
            </body>
        </html>
    `);
    printWindow.document.close();

    // When the new window finishes loading, trigger the print dialog and close it afterward.
    printWindow.onload = function () {
        printWindow.print();
        printWindow.onafterprint = function () {
            printWindow.close();
        };
    };
}

</script>

@endsection
