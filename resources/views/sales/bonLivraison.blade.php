@extends('layouts.app')

@section('content')

<style>
    @media print {
        .print:hidden {
            display: none !important;
        }
    }
</style>


<div class="container mx-auto my-8 p-4">
    <!-- Header Buttons (hidden when printing) -->
    <div class="flex justify-center gap-4 mb-6 print:hidden">
        <button onclick="printBonLivraison()" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
            Imprimer
        </button>
        <button class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
            Générer Bon de Coupe
        </button>
        <button class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
            Générer Bon de Sortie
        </button>
    </div>

    <!-- Bon de Livraison Card -->
    <div id="bon-de-livraison" class="border border-gray-300 rounded-lg p-6 shadow-lg bg-white">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-4">
            <span class="text-gray-500">Rabat le {{ date('d/m/Y') }}</span>
            <h2 class="text-2xl font-bold">BON DE LIVRAISON N° 267/2024</h2>
        </div>

        <!-- Client Info -->
        <div class="border border-orange-400 rounded-lg p-4 bg-orange-100 mb-4">
            <p class="font-bold text-orange-500 text-lg">TEST TEST</p>
            <p><strong>Code Client:</strong> C187</p>
            <p><strong>Téléphone:</strong> 0000000000000</p>
        </div>

        <!-- Agent Info -->
        <p class="mb-4"><strong>Agent:</strong> testing</p>

        <!-- Table Section -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border border-gray-300 p-2 text-left">Désignation</th>
                        <th class="border border-gray-300 p-2 text-left">Mesures</th>
                        <th class="border border-gray-300 p-2 text-left">Unité</th>
                        <th class="border border-gray-300 p-2 text-left">Qté</th>
                        <th class="border border-gray-300 p-2 text-left">Surface</th>
                        <th class="border border-gray-300 p-2 text-left">P.U HT</th>
                        <th class="border border-gray-300 p-2 text-left">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 p-2">ML NOIR KHENIFRA TRANCHE</td>
                        <td class="border border-gray-300 p-2">5.000 x 1.000</td>
                        <td class="border border-gray-300 p-2">M2</td>
                        <td class="border border-gray-300 p-2">1</td>
                        <td class="border border-gray-300 p-2">5.000</td>
                        <td class="border border-gray-300 p-2">280.00</td>
                        <td class="border border-gray-300 p-2">1,400.00</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 p-2">finition</td>
                        <td class="border border-gray-300 p-2">1000 x 1.000</td>
                        <td class="border border-gray-300 p-2">1</td>
                        <td class="border border-gray-300 p-2">1</td>
                        <td class="border border-gray-300 p-2">1.000</td>
                        <td class="border border-gray-300 p-2">100.00</td>
                        <td class="border border-gray-300 p-2">100.00</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="bg-gray-100">
                        <td colspan="6" class="border border-gray-300 p-2 text-right font-bold">Total GLOBAL H.T:</td>
                        <td class="border border-gray-300 p-2 font-bold">1,500.00</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Payment Section -->
        <div class="mt-6">
            <p><strong>Montant de BL:</strong> 1,500.00</p>
            <p><strong>AVANCE:</strong> 1,000.00</p>
            <p><strong>Reste à Payer:</strong> 500.00</p>
        </div>

        <!-- Footer Section -->
        <div class="mt-6 text-center text-gray-500 text-sm">
            <p>📞 0537643290 - 0537643615</p>
            <p>✉️ promotion2021@gmail.com</p>
        </div>
    </div>
</div>

<script>
    function printBonLivraison() {
        const printContent = document.getElementById('bon-de-livraison');
        const originalContent = document.body.innerHTML;

        // Show only Bon de Livraison content
        document.body.innerHTML = printContent.outerHTML;
        window.print();

        // Restore original content
        document.body.innerHTML = originalContent;
        location.reload(); // Refresh to reload scripts/styles if needed
    }

</script>

@endsection