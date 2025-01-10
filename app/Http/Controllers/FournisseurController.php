<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FournisseurController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        if (request()->ajax()) {
            $fournisseurs = Fournisseur::all();

            return DataTables::of($fournisseurs)
                ->addColumn('actions', function ($fournisseur) {
                    $deleteUrl = route('fournisseurs.destroy', $fournisseur->id);
                    return '
                        <div style="display:flex;">
                            <a href="#" onclick="openUpdateFournisseurModal(' . $fournisseur->id . ')" class="text-blue-500 hover:underline">
                                <svg class="w-6 h-6 text-blue-800 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
                                </svg>
                            </a>
                            
                            <form action="' . $deleteUrl . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="text-red-500 hover:underline">
                                    <svg class="w-6 h-6 text-red-400 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M8.586 2.586A2 2 0 0 1 10 2h4a2 2 0 0 1 2 2v2h3a1 1 0 1 1 0 2v12a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V8a1 1 0 0 1 0-2h3V4a2 2 0 0 1 .586-1.414ZM10 6h4V4h-4v2Zm1 4a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Zm4 0a1 1 0 1 0-2 0v8a1 1 0 1 0 2 0v-8Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('fournisseurs.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'raison' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        Fournisseur::create($request->all());

        return redirect()->back()->with('success', 'Fournisseur added successfully.');
    }

    public function edit(Fournisseur $fournisseur)
    {
        return response()->json($fournisseur);
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $request->validate([
            'raison' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $fournisseur->update($request->all());

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur updated successfully.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();
        // return response()->json(['success' => 'User deleted successfully.']);
        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur a été supprimé avec succès.');
    }

    // // -= - =- = -= - =- = -= -= - =- = -=- =-= - =-= -= -= - =- =- = -
    public function search(Request $request)
    {
        $search = $request->input('q');

        $fournisseurs = Fournisseur::query()
            ->select(['id', 'raison'])
            ->where(function ($query) use ($search) {
                $query->where('raison', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            })
            ->distinct()
            ->get()
            ->map(function ($fournisseur) {
                return [
                    'id' => $fournisseur->id,
                    'name' => $fournisseur->raison,
                ];
            });

        return response()->json($fournisseurs);
    }
}
