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

    <!-- Bon de Livraison Card -->
    <div id="bon-de-livraison" class="borde border-gray-00 rounded-lg p-6 shado bg-white">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-9 text-center">
            <span class="text-gray-500">Rabat le {{ $paymentStatus['date-echeance'] }} - <span class="text-gray-400 font-bold" >Impression: <span {{$print_nbr->print_nbr > 1 ? 'class=text-red-400' : ""}}>{{$print_nbr->print_nbr + 1}}</span></span></span>
            <h2 class="text-2xl font-bold">BON DE COUPE N° {{ $paymentStatus->no_bl }} /{{ date('Y') }}</h2>
        </div>

        <!-- Client Info -->
        <div class="border border-gray-400 rounded-lg p-4 bg-gray-200 m-auto mt-6 mb-12 text-center w-96">
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
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600">Unité</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600">Qté</th>
                        <th class="border-2 border-gray-300 p-2 font-bold text-left text-gray-600">Surface</th>
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
                            printBonLivraison();
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

    
    function printBonLivraison() {
        const printContent = document.getElementById('bon-de-livraison');
        const originalContent = document.body.innerHTML;

         document.body.innerHTML = printContent.outerHTML;
        window.print();
        // Restore original content
        document.body.innerHTML = originalContent;
        window.onafterprint = function() {
            location.reload();
        };
    }
</script>

@endsection
