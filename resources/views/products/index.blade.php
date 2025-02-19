@extends('layouts.app')

@section('content')

<style>
    /* Custom DataTable styles */
    .dataTables_wrapper .dataTables_length select {
        background-color: #d0bdbc96;
        width: 90px;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #e2e2ff;
        margin-bottom: 2px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        background-color: #5d5353b9;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        margin-left: 5px;
    }

    div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover {
        background-color: #d0bdbc96 !important;
    }

    #products-table {
        width: 100%;
        border-collapse: collapse;
    }

    #products-table th, #products-table td {
        padding: 12px;
        border-bottom: 1px solid #f6e8e8;
        transition: .5s;
    }

    #products-table th {
        background-color: #898483;
        background-color:#706c6c;

        color: white;
        font-size: 15px;
        text-transform: uppercase;
    }

    #products-table tbody tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #products-table tbody tr:hover {
        background-color: #c1959443;
    }

    .dataTables_filter input {
        padding: 8px;
        margin-left: 10px;
        border-radius: 5px;
        border: 1px solid #ddd;
    }

    .btnA svg{
        width: 50px;
        height: 40px;
        background-color:#4c4949;
        float: left;
        /* margin-right: 3px; */
        margin: 3px;
        border-radius: 10%;
        transition: .3s;
    }
    .btnA svg:hover{
        background-color:#ffffff;
        color: #4c4949;
    }
    tr img:hover{
        transition: .3s;
        background-color: #de0000;
        border: 1px solid #4c4949;
        transform: scale(505%);
    }
</style>

@can("view products")

    <div class="py-5">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">

                <a href="{{ route('products.create') }}" class="btnA">
                    {{-- <svg class="w-7 h-6 text-gray-200 dark:text-white " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M9 4a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm-2 9a4 4 0 0 0-4 4v1a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2v-1a4 4 0 0 0-4-4H7Zm8-1a1 1 0 0 1 1-1h1v-1a1 1 0 1 1 2 0v1h1a1 1 0 1 1 0 2h-1v1a1 1 0 1 1-2 0v-1h-1a1 1 0 0 1-1-1Z" clip-rule="evenodd"/>
                    </svg> --}}
                    <button type="button" class="YRrCJSr_j5nopfm4duUc Q_jg_EPdNf9eDMn1mLI2 FJRldeiG2gFGZfuKgp88 t6gkcSf0Bt4MLItXvDJ_ d3C8uAdJKNl1jzfE9ynq _43MO1gcdi2Y0RJW1uHL __9sbu0yrzdhGIkLWNXl mveJTCIb2WII7J4sY22F bcsWqjK52oeyT6oeC2Az gZ3KuFw1JESHhOJhjT8j BpcA_ZTX79XDgSc71n2v _7KA5gD55t2lxf9Jkj20 duXR6Hcu_44X_243WcOl OPrb_iG5WDy_7F05BDOX" aria-controls="dropdown-pages" data-collapse-toggle="dropdown-pages">
                        {{-- <svg class="VQS2tmQ_zFyBOC2tkmto YIUegm7fh_CpJbivTu6B MnxxlQlR1H0xJuMEE8Yr PeR2JZ9BZHYIH8Ea3F36 bcsWqjK52oeyT6oeC2Az gZ3KuFw1JESHhOJhjT8j _Oyukq8JlN1X9w2FmPds XIIs8ZOri3wm8Wnj9N_y Lld6j9B1iilEqA6j31e4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd"></path>
                        </svg> --}}
                        <svg class="VQS2tmQ_zFyBOC2tkmto YIUegm7fh_CpJbivTu6B MnxxlQlR1H0xJuMEE8Yr PeR2JZ9BZHYIH8Ea3F36 bcsWqjK52oeyT6oeC2Az gZ3KuFw1JESHhOJhjT8j _Oyukq8JlN1X9w2FmPds XIIs8ZOri3wm8Wnj9N_y Lld6j9B1iilEqA6j31e4 bg-gray-100" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="_74lpPUMEtHf6F0_fjLe oA7zcT_42jVeFuWTXQnq upQp7iWehfaU8VTbfx_w BHrWGjM1Iab_fAz0_91H" sidebar-toggle-item="">Ajouter Produit</span>
                        {{-- <svg sidebar-toggle-item="" class="YIUegm7fh_CpJbivTu6B MnxxlQlR1H0xJuMEE8Yr" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg> --}}
                    </button>
                </a>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="font-serif uppercase underline text-center text-gray-600 mb-12 text-2xl font-bold">Products Table</h2>

                    <table id="products-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-left text-sm text-gray-500 dark:text-gray-400 border">
                        <thead class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                             <tr>
                                <th>Code</th>
                                <th>Nom</th> 
                                <th>Prix Unitaire</th>
                                @if(in_array(auth()->user()->role, ['Admin', 'SAdmin']))
                                    <th>Type</th>
                                    <th>Catégorie</th> 
                                    <th>Quantité</th>
                                    <th>Couleur</th> 
                                @endif
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@can("edit products")
<!-- Edit Product Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 w-full max-w-3xl rounded-lg shadow-lg">
        <div class="flex justify-between items-center px-6 py-4 border-b dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">Edit Product</h3>
            <button id="closeModal" class="text-gray-500 hover:text-gray-800 dark:hover:text-gray-300">
                &times;
            </button>
        </div>
        <form id="editForm" class="px-6 py-4" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" id="edit-id">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="edit-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                    <input type="text" id="edit-name" name="name" class="w-full mt-1 px-3 py-2 border rounded-md dark:bg-gray-900 dark:border-gray-600">
                </div>
                <div>
                    <label for="edit-type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                    <input type="text" id="edit-type" name="type" class="w-full mt-1 px-3 py-2 border rounded-md dark:bg-gray-900 dark:border-gray-600">
                </div>
                <div>
                    <label for="edit-category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
                    <select id="edit-category" name="category" class="w-full mt-1 px-3 py-2 border rounded-md dark:bg-gray-900 dark:border-gray-600">
                        <option value="DEBITGAE">DEBITGAE</option>
                        <option value="FINITION">FINITION</option>
                        <option value="GRANIT">GRANIT</option>
                        <option value="PIERRE">PIERRE</option>
                        <option value="MARBRE">MARBRE</option>
                        <option value="TRAVERTAIN">TRAVERTAIN</option>
                        <option value="ASCALE">ASCALE</option>
                        <option value="NEW CREMA">NEW CREMA</option>
                        <option value="MARBRE LOCAL">MARBRE LOCAL</option>
                    </select>
                </div>
                <div>
                    <label for="edit-unit-price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unit Price</label>
                    <input type="number" id="edit-unit-price" name="unit_price" class="w-full mt-1 px-3 py-2 border rounded-md dark:bg-gray-900 dark:border-gray-600">
                </div>
                <div>
                    <label for="edit-quantity" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                    <input type="number" id="edit-quantity" name="quantity" class="w-full mt-1 px-3 py-2 border rounded-md dark:bg-gray-900 dark:border-gray-600">
                </div>
                <div>
                    <label for="edit-color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Color</label>
                    <select id="edit-color" name="color" class="w-full mt-1 px-3 py-2 border rounded-md dark:bg-gray-900 dark:border-gray-600">
                        <option value="" disabled selected>Choisissez une couleur</option>
                        <option value="Rouge" style="background-color: red; color: white;">Rouge</option>
                        <option value="Bleu" style="background-color: blue; color: white;">Bleu</option>
                        <option value="Vert" style="background-color: green; color: white;">Vert</option>
                        <option value="Jaune" style="background-color: yellow; color: black;">Jaune</option>
                        <option value="Beige" style="background-color: beige; color: black;">Beige</option>
                        <option value="Noir" style="background-color: black; color: white;">Noir</option>
                        <option value="Marron" style="background-color: brown; color: white;">Marron</option>
                        <option value="Blanc" id="blnc" style="background-color: white; color: black;">Blanc</option>
                        <option value="Orange" style="background-color: orange; color: black;">Orange</option>
                        <option value="Violet" style="background-color: purple; color: white;">Violet</option>
                        <option value="Rose" style="background-color: pink; color: black;">Rose</option>
                        <option value="Gris" style="background-color: gray; color: white;">Gris</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <label for="edit-image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image</label>
                <input type="file" id="edit-image" name="image" class="w-full mt-1 px-3 py-2 border rounded-md dark:bg-gray-900 dark:border-gray-600">
            </div>
            <div class="mt-6 flex justify-end">
                <button type="button" id="cancelEdit" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">Cancel</button>
                <button type="submit" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save Changes</button>
            </div>
        </form>
        
    </div>
</div>
@endcan
@endcan


   
<script>
     var isAdminOrSuperAdmin = @json(in_array(auth()->user()->role, ['Admin', 'SAdmin']));

    $(document).ready(function() {
        let columns = [
            { data: 'product_code', name: 'product_code' },
            { data: 'name', name: 'name' },
            // { data: 'unit_price', name: 'unit_price' },
            { 
                data: 'unit_price', 
                name: 'unit_price',
                render: function(data, type, row) {
                    return `<span class="editable-unit-price" data-id="${row.id}">${data}</span>`;
                }
            }
        ];

        // Add Type, Category, Quantity, and Color columns only for Admin and SuperAdmin
        if (isAdminOrSuperAdmin) {
            columns.push({ data: 'type', name: 'type' });
            columns.push({ data: 'category', name: 'category' });
            columns.push({ data: 'quantity', name: 'quantity' });
            columns.push({ data: 'color', name: 'color' });
        }

        columns.push({ data: 'image_path', name: 'image_path', orderable: false, searchable: false });

        columns.push({ 
            data: 'actions', 
            name: 'actions', 
            orderable: false, 
            searchable: false,
            render: function(data, type, row) {
                return `
                                @can("edit products")
                                    <button class="edit-btn text-blue-600 hover:underline" 
                                        data-id="${row.id}" 
                                        data-name="${row.name}" 
                                        data-type="${row.type}" 
                                        data-category="${row.category}" 
                                        data-unit-price="${row.unit_price}" 
                                        data-quantity="${row.quantity}" 
                                        data-color="${row.color}" 
                                        data-inventory-date="${row.inventory_date}" 
                                        data-update-date="${row.update_date}">
                                        <svg class="w-6 h-6 text-blue-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                        </svg>
                                    </button>
                                @endcan
                                @can("delete products")
                                    <button class="delete-btn text-red-600 hover:underline ml-2" 
                                        data-id="${row.id}">
                                        <svg class="w-6 h-6 text-red-400 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                            <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                @endcan
                            `;
            }
        });


            $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('products.index') }}',
                columns: columns,
                responsive: true,
                order: [[0, 'desc']],
                language: {
                    paginate: {
                        previous: "&laquo;",
                        next: "&raquo;"
                    }
                }
            });

        if (isAdminOrSuperAdmin) {
            // Handle click event to turn the price into an input field
            $('#products-table tbody').on('click', '.editable-unit-price', function() {
                let span = $(this);
                let currentPrice = span.text();
                let productId = span.data('id');
        
                let input = $(`<input type="text" class="unit-price-input w-16" value="${currentPrice}">`);
                span.replaceWith(input);
                input.focus();
        
                input.on('blur keydown', function(event) {
                    if (event.type === 'blur' || event.key === 'Enter') {
                        let newPrice = input.val().trim();
                        
                        if (newPrice !== currentPrice && $.isNumeric(newPrice)) {
                            // Send AJAX request to update the price
                            $.ajax({
                                url: `/products/${productId}/update-price`, // Adjust route as needed
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    unit_price: newPrice
                                },
                                success: function(response) {
                                    input.replaceWith(`<span class="editable-unit-price" data-id="${productId}">${newPrice}</span>`);
                                },
                                error: function() {
                                    input.replaceWith(`<span class="editable-unit-price" data-id="${productId}">${currentPrice}</span>`);
                                }
                            });
                        } else {
                            input.replaceWith(`<span class="editable-unit-price" data-id="${productId}">${currentPrice}</span>`);
                        }
                    }
                });
            });
        }

});


        
        // $(document).ready(function() {
        //     $('#products-table').DataTable({
        //         processing: true,
        //         serverSide: true,
        //         ajax: '{{ route('products.index') }}',
        //         columns: [
        //             // { data: 'id', name: 'id' },
        //             { data: 'product_code', name: 'product_code' },
        //             { data: 'name', name: 'name' },
        //             { data: 'type', name: 'type' },
        //             { data: 'category', name: 'category' },
        //             { data: 'unit_price', name: 'unit_price' },
        //             { data: 'quantity', name: 'quantity' },
        //             { data: 'color', name: 'color' },
        //             { data: 'image_path', name: 'image_path', orderable: false, searchable: false },
        //             // { data: 'actions', name: 'actions', orderable: false, searchable: false },
        //             { 
        //                 data: 'actions', 
        //                 name: 'actions', 
        //                 orderable: false, 
        //                 searchable: false,
        //                 render: function(data, type, row) {
        //                     return `
        //                         @can("edit products")
        //                             <button class="edit-btn text-blue-600 hover:underline" 
        //                                 data-id="${row.id}" 
        //                                 data-name="${row.name}" 
        //                                 data-type="${row.type}" 
        //                                 data-category="${row.category}" 
        //                                 data-unit-price="${row.unit_price}" 
        //                                 data-quantity="${row.quantity}" 
        //                                 data-color="${row.color}" 
        //                                 data-inventory-date="${row.inventory_date}" 
        //                                 data-update-date="${row.update_date}">
        //                                 <svg class="w-6 h-6 text-blue-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        //                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
        //                                 </svg>
        //                             </button>
        //                         @endcan
        //                         @can("delete products")
        //                             <button class="delete-btn text-red-600 hover:underline ml-2" 
        //                                 data-id="${row.id}">
        //                                 <svg class="w-6 h-6 text-red-400 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
        //                                     <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
        //                                 </svg>
        //                             </button>
        //                         @endcan
        //                     `;
        //                 }
        //             }

        //         ],
        //         responsive: true,
        //         order: [[0, 'desc']],
        //         language: {
        //             paginate: {
        //                 previous: "&laquo;",
        //                 next: "&raquo;"
        //             }
        //         }
        //     });
        // });



        // handl update modal 
        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            $('#edit-id').val(id);
            $('#edit-name').val($(this).data('name'));
            $('#edit-type').val($(this).data('type'));

            // Set the category
            const category = $(this).data('category');
            $('#edit-category').val(category);

            // Set the color
            const colorMap = {
                Noir: 'Noir',
                Beige: 'Beige',
                Rouge: 'Rouge',
                Bleu: 'Bleu',
                Vert: 'Vert',
                Jaune: 'Jaune',
                Marron: 'Marron',
                Blanc: 'Blanc',
                Orange: 'Orange',
                Violet: 'Violet',
                Rose: 'Rose',
                Gris: 'Gris'
            };

            const color = $(this).data('color');
            const mappedColor = colorMap[color]; // Get the corresponding English value
            $('#edit-color').val(mappedColor); // Set the dropdown value
            console.log(mappedColor); // Check the mapped value in the console
            
            $('#edit-unit-price').val($(this).data('unit-price'));
            $('#edit-quantity').val($(this).data('quantity'));
            $('#edit-inventory-date').val($(this).data('inventory-date'));
            $('#edit-update-date').val($(this).data('update-date'));

            $('#editModal').removeClass('hidden');
        });

        // Close modal
        $('#closeModal, #cancelEdit').on('click', function() {
            $('#editModal').addClass('hidden');
        });

        // Form submission
        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#edit-id').val();
            const formData = new FormData(this);

            $.ajax({
                url: `/products/${id}`,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#editModal').addClass('hidden');
                    $('#products-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    alert('Something went wrong!');
                }
            });
        });


    // button delete product 
    // $(document).on('click', '.delete-btn', function() {
    //     const id = $(this).data('id');
    //     if (confirm('Are you sure you want to delete this product?')) {
    //         $.ajax({
    //             url: `/products/${id}`,
    //             type: 'DELETE',
    //             data: { _token: '{{ csrf_token() }}' },
    //             success: function(response) {
    //                 alert('Product deleted successfully!');
    //                 $('#products-table').DataTable().ajax.reload();
    //             },
    //             error: function(xhr) {
    //                 alert('Failed to delete product!');
    //             }
    //         });
    //     }
    // });

    $(document).on('click', '.delete-btn', function () {
        const id = $(this).data('id');

        Swal.fire({
            title: 'Es-tu sûr?',
            text: 'Cette action peut être annulée !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Oui, supprime-le !',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Perform the delete request
                $.ajax({
                    url: `/products/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // Swal.fire(
                        //     // title: 'Success!',
                        //     // icon: 'success',
                        //     'Supprimé!',
                        //     'Le produit a été supprimé.',
                        //     'succès'
                        // );
                        Swal.fire({
                            title: 'Success!',
                            text: 'Le produit a été supprimé.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            timer: 3000
                        });
                        $('#products-table').DataTable().ajax.reload();
                    },
                    error: function () {
                        Swal.fire(
                            'Error!',
                            "Une erreur s'est produite. Veuillez réessayer.",
                            'erreur'
                        );
                    }
                });
            }
        });
    });
        
        
    </script>
@endsection
