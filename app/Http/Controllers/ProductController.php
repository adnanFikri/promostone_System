<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
// use Illuminate\Routing\Controller;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        
        $this->middleware('permission:view products')->only(['index']);
        $this->middleware('permission:create products')->only(['create']);
        $this->middleware('permission:store products')->only(['store']);
        $this->middleware('permission:view product details')->only(['show']);
        $this->middleware('permission:edit products')->only(['edit']);
        $this->middleware('permission:update products')->only(['update']);
        $this->middleware('permission:delete products')->only(['destroy']);
        $this->middleware('permission:generate product code')->only(['generateProductCode']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::select([
                'id',
                'name',
                'type',
                'category',
                'unit_price',
                'quantity',
                'color',
                'inventory_date',
                'update_date',
                'product_code',
                'image_path'
            ]);

            return DataTables::of($products)
                ->addColumn('actions', function ($product) {
                    return '<a href="' . route('products.edit', $product->id) . '" class="text-blue-500">Edit</a>
                            <form action="' . route('products.destroy', $product->id) . '" method="POST" style="display:inline;">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="text-red-500">Delete</button>
                            </form>';
                })
                ->editColumn('image_path', function ($product) {
                    return '<img src="' . asset('storage/' . $product->image_path) . '" alt="' . $product->name . '" style="width:50px; height:50px; object-fit:cover;">';
                })
                ->rawColumns(['actions', 'image_path'])
                ->make(true);
        }

        return view('products.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate the product code for the new product
        $lastProduct = Product::orderBy('product_code', 'desc')->first();
        $lastProductCode = $lastProduct ? $lastProduct->product_code : null;
        $newProductCode = $this->generateProductCode($lastProductCode);

        // Return the view with the generated product code
        return view('products.create', compact('newProductCode'));
    }

    private function generateProductCode($lastProductCode)
    {
        // Default code if there is no last product code (start from A1)
        if (!$lastProductCode) {
            return 'A1';
        }

        // Get the letter and number parts of the last product code
        $letter = substr($lastProductCode, 0, 1);
        $number = (int) substr($lastProductCode, 1);

        // If the number is less than 9, increment it
        if ($number < 9) {
            $newNumber = $number + 1;
            return $letter . $newNumber;
        }

        // If the number is 9, move to the next letter (A -> B -> C -> ...)
        $newLetter = chr(ord($letter) + 1);
        return $newLetter . '1';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'unit_price' => 'required|numeric',
            'quantity' => 'required|integer',
            'color' => 'required|string|max:50',
            'inventory_date' => 'required|date',
            'update_date' => 'required|date',
            'product_code' => 'required|string|unique:products',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

    
    
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // dd($imagePath);
    
        Product::create([
            'name' => $request->name,
            'type' => $request->type,
            'category' => $request->category,
            'unit_price' => $request->unit_price,
            'quantity' => $request->quantity,
            'color' => $request->color,
            'inventory_date' => $request->inventory_date,
            'update_date' => $request->update_date,
            'product_code' => $request->product_code,
            'image_path' => $imagePath,
        ]);
    
        return redirect()->route('products.index')->with('success', 'Produit créé avec succès !');
    
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the input fields
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'color' => 'nullable|string|max:255',
            'inventory_date' => 'required|date',
            'update_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Optional image upload
        ]);

        // Find the product by ID
        $product = Product::findOrFail($id);

        // Update the product attributes
        $product->name = $request->name;
        $product->type = $request->type;
        $product->category = $request->category;
        $product->unit_price = $request->unit_price;
        $product->quantity = $request->quantity;
        $product->color = $request->color;
        $product->inventory_date = $request->inventory_date;
        $product->update_date = $request->update_date;

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($product->image && Storage::exists($product->image)) {
                Storage::delete($product->image);
            }

            // Store the new image and save its path
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image_path = $imagePath;
        }

        // Save the updated product
        $product->save();

        // Return a success response
        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
    
        return response()->json(['message' => 'Produit supprimé avec succès !']);
    }
    
}
