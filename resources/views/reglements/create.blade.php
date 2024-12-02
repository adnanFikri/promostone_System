<x-app-layout>
    <style>
        #payment-status{
            font-size: 20px;
            color: gray;
            font-weight: bold;
        }
        #remaining-balance{
            /* color: red; */
        }
        #code_client{
            width: 300px;
            /* font-size: 20px; */
            height: 40px;
            /* padding: 5px; */
        }
        .selec2{
            height: 40px !important;

        }
        .select2-selection{
            height: 45px !important;
            background-color: rgb(249 250 251) !important;
            border-radius: 20px;
            padding: 5px;
        }
        .select2-selection__rendered{
            height: 100% !important;
        }
        .select2-selection__placeholder{
            height: 40px !important;
            /* color: red !important; */
            color: black !important;
            padding-top: 8px !important;
        }
        .dropdown-wrapper{
            background-color: gray !important;
        }
    </style>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Règlement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-center mb-4 text-2xl font-bold">Ajouter nouveau Règlement</h2>

                    <form id="reglement-form">
                        @csrf

                        <div class="mb-6 ">
                            <label for="code_client" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Client</label>
                            <select name="code_client" id="code_client" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                                <!-- Select2 will dynamically populate options via AJAX -->
                            </select>
                        </div>

                        <div id="payment-status" class="mb-4 hidden">
                            <p>Solde restant: <span id="remaining-balance"></span></p>
                        </div>

                        <div class="mb-6">
                            <label for="montant" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Montant</label>
                            <input type="number" name="montant" id="montant" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                        </div>

                        <div class="mb-6">
                            <label for="date_reglement" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Date</label>
                            <input type="date" name="date" id="date_reglement" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                        </div>

                        <div class="mb-6">
                            <label for="mode_reglement" class="block text-lg font-medium text-gray-900 dark:text-white mb-2">Mode</label>
                            {{-- <input type="text" name="type_pay" id="mode_reglement" class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600"> --}}
                            <select name="type_pay" id="mode_reglement" required class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600" required>
                                <option value="espèce ">espèce </option>
                                <option value="chèque">chèque</option>
                                <option value="virement">virement</option>
                            </select>
                        </div>

                        <button type="button" id="save-button" class="w-full px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">Save</button>
                        <button type="button" id="finish-button" class="w-full mt-2 px-4 py-2 text-white bg-green-600 rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">Finish</button>
                    </form>

                    <div id="status-updated" class="mt-6 hidden bg-green-100 text-green-800 px-4 py-2 rounded">
                        <p id="update-message"></p>
                        <p>Solde restant mis à jour: <span id="updated-remaining-balance"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Initialize Select2 for the 'code_client' field
            $('#code_client').select2({
                placeholder: "Select Client",
                ajax: {
                    url: '{{ route('reglements.search') }}', // Client search route
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                        return {
                            results: data.map(function (sale) {
                                return {
                                    id: sale.code_client,
                                    name : sale.name,
                                    text: sale.code_client + " - " + sale.name
                                };
                            })
                        };
                    },
                    cache: true
                }
            });


            // Handle payment status fetching
            $('#code_client').on('change', function () {
                const selectedClient = $(this).val();
                fetch(`/payment-status/${selectedClient}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error("Client not found");
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Show the payment status
                        $('#payment-status').removeClass('hidden');
                        
                        const remainingBalance = data.remaining_balance;
                        const balanceElement = $('#remaining-balance');

                        // Set the remaining balance text
                        balanceElement.text(remainingBalance);

                        // Change color based on the remaining balance
                        if (remainingBalance > 100) {
                            balanceElement.css('color', 'red');
                        } else if (remainingBalance <= 100 && remainingBalance > 20) {
                            balanceElement.css('color', 'orange');
                        } else if (remainingBalance <= 20) {
                            balanceElement.css('color', 'green');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });


    

            // Handle form submission for saving
            $('#save-button').on('click', function () {
                let formData = $('#reglement-form').serialize();

                console.log(formData);
                

                fetch('{{ route('reglements.store') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-Token': '{{ csrf_token() }}',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to save règlement.');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        $('#status-updated').removeClass('hidden');
                        $('#update-message').text(data.message);
                        $('#updated-remaining-balance').text(data.updatedPaymentStatus.remaining_balance);
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            // Handle redirect on Finish button click
            $('#finish-button').on('click', function () {
                window.location.href = '{{ route('reglements.index') }}';
            });
        });
    </script>
</x-app-layout>
