<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Lot;
use Exception;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $lotes = Lot::where('lot.product_id', 3)->withQualityStats()->get();
            return view('home.home', compact('lotes'));
        } catch (Exception $th) {
            return redirect()->back()->with('error', 'Error grave al acceder a la vista.' . $th->getMessage());
        }
    }
}