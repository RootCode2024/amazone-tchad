<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Customer::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $customer = Customer::with(['flights', 'hotels', 'carLocations', 'flightHotels'])->findOrFail($id);
        
        return response()->json([
            'data' => $customer
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        DB::beginTransaction();
        
        try {
            $customer = Customer::findOrFail($id);
        
            $customer->flights()->delete();
            $customer->hotels()->delete();
            $customer->flightHotels()->delete();
            $customer->carLocations()->delete();
            $customer->delete();
        
            DB::commit();
            
            return response()->json(['message' => 'Client et ses réservations supprimés avec succès']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Échec de la suppression du client et de ses réservations', 'error' => $e->getMessage()], 500);
        }
    }
}
