<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        return response()->json(Bank::all());
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'agence' => 'required',
        //     'rib' => 'required|unique:banks',
        //     'titulaire' => 'required',
        // ]);

        $bank = Bank::create($request->all());
        return response()->json($bank);
    }

    public function update(Request $request, Bank $bank)
    {
        $request->validate([
            'name' => 'required',
            'agence' => 'required',
            'rib' => 'required|unique:banks,rib,' . $bank->id,
            'titulaire' => 'required',
        ]);

        $bank->update($request->all());
        return response()->json($bank);
    }

    public function destroy(Bank $bank)
    {
        $bank->delete();
        return response()->json(['message' => 'Bank deleted successfully']);
    }
}
