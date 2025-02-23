<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use App\Models\BonCoupe;
use App\Models\Reglement;
use Illuminate\Http\Request;
use App\Models\PaymentStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class BonCoupeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); // Ensure the user is authenticated
        
        // Define permission-based middleware for each method
        $this->middleware('permission:index bon coup')->only(['index']);
        $this->middleware('permission:view bon coup')->only(['showBonCoup']);
        $this->middleware('permission:update coupe bon coup')->only(['updateCoupe']);
    }

    public function index()
    {
        if (request()->ajax()) {
            // $bons = BonCoupe::with(['sales', 'paymentStatuses'])
            //     ->get()
            //     ->groupBy('no_bl');
            $bons = BonCoupe::whereHas('sales', function ($query) {
                $query->join('products', 'sales.produit', '=', 'products.name') // Join with products table
                      ->whereNotIn('products.type', ['TRANCHE', 'CARREAUX','ESCALIER']); // Filter by attribute
            })
            ->with(['sales', 'paymentStatuses']) // Eager load relationships
            ->get()
            ->groupBy('no_bl');
            
            $bons = $bons->map(function ($group) {
                $bon = $group->first();  // Get the first bon for this group
                $isAdmin = auth()->user()->can('create users');

                // Retrieve client information
                $code_client = $group->first()->paymentStatuses->first()->code_client ?? null;
                $client_raison = 'N/A';
                
                if ($code_client) {
                    $client = Client::where('code_client', $code_client)->first();
                    $client_raison = $client ? ($client->category . ' ' . $client->name) : 'N/A';
                }
                
                // Loop through all sales and get the 'ref_produit' for each sale
                $products = $group->flatMap(function($bon) {
                    return $bon->sales->map(function($sale) {
                        return $sale->produit;  // Extract 'ref_produit' from each sale
                    });
                });

                // Convert the products to a unique, comma-separated string
                $productsString = $products->unique()->implode(', ');

                // $printDate = $bon->print_date ? Carbon::parse($bon->print_date) : null;
                // $dateCoupe = $bon->date_coupe ? Carbon::parse($bon->date_coupe) : null;
                // $timeDifference = 'pas encore';

                // if ($printDate && $dateCoupe) {
                //     $diffDays = floor($printDate->diffInDays($dateCoupe));
                //     $diffHours = $printDate->diffInHours($dateCoupe) % 24; // Get remaining hours after full days
                //     $diffMinutes = $printDate->diffInMinutes($dateCoupe) % 60;

                //     // Build the time difference string based on non-zero values
                //     if ($diffDays > 0) {
                //         if ($diffHours > 0) {
                //             $timeDifference = "{$diffDays}j {$diffHours}h";
                //         } else {
                //             $timeDifference = "{$diffDays}j"; // Show only days if hours are 0
                //         }

                //         if ($diffMinutes > 0) {
                //             $timeDifference .= " {$diffMinutes}min"; // Append minutes only if > 0
                //         }
                //     } elseif ($diffHours > 0) {
                //         $timeDifference = "{$diffHours}h";
                //         if ($diffMinutes > 0) {
                //             $timeDifference .= " {$diffMinutes}min"; // Append minutes if > 0
                //         }
                //     } elseif ($diffMinutes > 0) {
                //         $timeDifference = "{$diffMinutes}min"; // Show only minutes if hours are 0
                //     }
                // }

                $printDate = $bon->print_date ? Carbon::parse($bon->print_date) : null;
                $dateCoupe = $bon->date_coupe ? Carbon::parse($bon->date_coupe) : null;
                $dateEncours = $bon->date_encours ? Carbon::parse($bon->date_encours) : null;
                $createdAt = $bon->created_at ? Carbon::parse($bon->created_at) : null;

                $timeDifference = 'pas encore';
                $delayCoupe = 'pas encore';
                $dureeBeforeCommence = 'pas encore';

                // Calculate time difference from print_date to timeDifference
                if ($printDate && $dateCoupe) {
                    $diffDays = floor($printDate->diffInDays($dateCoupe));
                    $diffHours = $printDate->diffInHours($dateCoupe) % 24;
                    $diffMinutes = $printDate->diffInMinutes($dateCoupe) % 60;

                    if ($diffDays > 0) {
                        $timeDifference = "{$diffDays}j" . ($diffHours > 0 ? " {$diffHours}h" : "");
                        $timeDifference .= $diffMinutes > 0 ? " {$diffMinutes}min" : "";
                    } elseif ($diffHours > 0) {
                        $timeDifference = "{$diffHours}h" . ($diffMinutes > 0 ? " {$diffMinutes}min" : "");
                    } elseif ($diffMinutes > 0) {
                        $timeDifference = "{$diffMinutes}min";
                    }
                }

                // Calculate delay difference from date_encours to delayCoupe
                if ($dateEncours && $dateCoupe) {
                    $delayDays = floor($dateEncours->diffInDays($dateCoupe));
                    $delayHours = $dateEncours->diffInHours($dateCoupe) % 24;
                    $delayMinutes = $dateEncours->diffInMinutes($dateCoupe) % 60;

                    if ($delayDays > 0) {
                        $delayCoupe = "{$delayDays}j" . ($delayHours > 0 ? " {$delayHours}h" : "");
                        $delayCoupe .= $delayMinutes > 0 ? " {$delayMinutes}min" : "";
                    } elseif ($delayHours > 0) {
                        $delayCoupe = "{$delayHours}h" . ($delayMinutes > 0 ? " {$delayMinutes}min" : "");
                    } elseif ($delayMinutes > 0) {
                        $delayCoupe = "{$delayMinutes}min";
                    }
                }

                // Calculate duration from created_at to dureeBeforeCommence
                if ($createdAt && $dateEncours) {
                    $dureeDays = floor($createdAt->diffInDays($dateEncours));
                    $dureeHours = $createdAt->diffInHours($dateEncours) % 24;
                    $dureeMinutes = $createdAt->diffInMinutes($dateEncours) % 60;

                    if ($dureeDays > 0) {
                        $dureeBeforeCommence = "{$dureeDays}j" . ($dureeHours > 0 ? " {$dureeHours}h" : "");
                        $dureeBeforeCommence .= $dureeMinutes > 0 ? " {$dureeMinutes}min" : "";
                    } elseif ($dureeHours > 0) {
                        $dureeBeforeCommence = "{$dureeHours}h" . ($dureeMinutes > 0 ? " {$dureeMinutes}min" : "");
                    } elseif ($dureeMinutes > 0) {
                        $dureeBeforeCommence = "{$dureeMinutes}min";
                    }
                }

    
                return [
                    'id' => $bon->id,
                    'client' => $client->name, // Add client name
                    'no_bl' => $bon->no_bl,
                    'coupe' => $bon->coupe,
                    'print_nbr' => $bon->print_nbr,
                    'finition' => $bon->finition,
                    'print_date' => $bon->print_date,
                    'date_coupe' => $bon->date_coupe,
                    'date_encours' => $bon->date_encours,
                    'userName' => $bon->userName,
                    'products' => $productsString,  // Return the products as a string
                    'date' => $group->first()->sales->first()->date ?? 'N/A',  // Access the first item in the sales collection
                    'commercant' => $group->first()->paymentStatuses->first()->commerçant ?? 'N/A',  // Access the first item in the paymentStatuses collection
                    'created_at' => $bon->created_at, // Add created_at for sorting
                    'isAdmin' => $isAdmin,
                    'time_difference' => $timeDifference,
                    'delayCoupe' => $delayCoupe,
                    'dureeBeforeCommence' => $dureeBeforeCommence,
                ];
            });
            
            $bons = $bons->sort(function ($a, $b) {
                $coupeOrder = ['Non' => 0, 'En cours' => 1, 'Sans' => 3, 'Oui' => 2];
                $finitionOrder = ['Non' => 0, 'En cours' => 1, 'Oui' => 2, 'Sans' => 3];
            
                // Compare coupe order
                if ($coupeOrder[$a['coupe']] !== $coupeOrder[$b['coupe']]) {
                    return $coupeOrder[$a['coupe']] <=> $coupeOrder[$b['coupe']];
                }
            
                // Compare finition order
                if ($finitionOrder[$a['finition']] !== $finitionOrder[$b['finition']]) {
                    return $finitionOrder[$a['finition']] <=> $finitionOrder[$b['finition']];
                }
            
                // If coupe is "Oui", sort by created_at descending
                if ($a['coupe'] === 'Oui' && $b['coupe'] === 'Oui') {
                    return $b['created_at'] <=> $a['created_at']; // Newest first
                }
            
                // Default sorting by created_at ascending (oldest first)
                return $a['created_at'] <=> $b['created_at'];
            });
            
            
    
            return datatables()->of($bons)
                ->addColumn('actions', function ($bon) {
                    return '
                        <button class="bg-blue-500 text-white px-2 py-1 rounded-md" onclick="editBon('.$bon['id'].')">Edit</button>
                        <button class="bg-red-500 text-white px-2 py-1 rounded-md" onclick="deleteBon('.$bon['id'].')">Delete</button>
                    ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
    
        return view('bons.coupe_view');
    }
    
    
    public function showBonCoup($no_bl)
    {
        // Retrieve the payment status details
        $paymentStatus = PaymentStatus::where('no_bl', $no_bl)->first();

        if (!$paymentStatus) {
            return redirect()->back()->with('error', 'Bon de Livraison not found.');
        }

        // Retrieve the sales details for the specific Bon de Livraison
        $sales = Sale::where('no_bl', $no_bl)->get();

        // Retrieve payment details (e.g., advance payments)
        $reglements  =  Reglement::where('no_bl', $no_bl)->get();
        $client  =  Client::where('code_client', $paymentStatus->code_client)->first();
        $print_nbr  =  BonCoupe::where('no_bl', $paymentStatus->no_bl)->first();

        // Group sales data by product name
        $groupedSales = $sales->groupBy('produit');

        BonCoupe::firstOrCreate(
            ['no_bl' => $no_bl]
        );
        
        // Prepare data for the view
        $data = [
            'paymentStatus' => $paymentStatus,
            'groupedSales' => $groupedSales,
            'reglements' => $reglements,
            'client' => $client,
            'print_nbr' => $print_nbr,
        ];

        return view('sales.bonC', $data);
    }
    
    public function updateCoupe(Request $request, $id)
    {
        $bon = BonCoupe::findOrFail($id);

        if($request->input('coupe') === 'Oui' || $request->input('coupe') === 'Sans'){
            $bon->coupe = $request->input('coupe');
            $bon->date_coupe= now();
            $bon->userName = Auth::user()->name;
        }elseif($request->input('coupe') === 'En cours'){
            $bon->coupe = $request->input('coupe');
            $bon->date_encours = now();
        }
        else{
            $bon->coupe = $request->input('coupe');
            $bon->date_coupe= null;
            $bon->date_encours = null;
            $bon->userName = null;
        }
        $bon->save();

    
        return response()->json(['message' => 'Coupe status updated successfully.']);
    }
    public function updateFinition(Request $request, $id)
    {
        $bon = BonCoupe::findOrFail($id);
        $bon->finition = $request->input('finition');
        $bon->save();
        return response()->json(['message' => 'Finition status updated successfully.']);
    }
    
    
    public function incrementPrintNbr($no_bl)
    {
        // Retrieve the BonCoupe record based on the no_bl value
        $bonCoupe = BonCoupe::where('no_bl', $no_bl)->first();

        if ($bonCoupe) {
            // if (!auth()->user()->can('create users')) {
                // Increment the print_nbr field
                $bonCoupe->print_nbr = $bonCoupe->print_nbr + 1;
                // add other date for imprement and let this for select of coupr
                if (!$bonCoupe->print_date) {
                    $bonCoupe->print_date = now();
                }
                // $bonCoupe->print_date = now();
                $bonCoupe->save();
            // }
    
            // Return success response
            return response()->json(['success' => true, 'message' => 'Print number incremented successfully.']);
        }
    
        // Return error response if BonCoupe is not found
        return response()->json(['success' => false, 'message' => 'Bon Coupe not found.'], 404);
    }
        
}
