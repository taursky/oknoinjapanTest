<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Apartment;

class ApartmentController extends Controller
{
    public function index()
    {
        $apartments = Apartment::limit(10)
            ->with('city', 'options')
            ->get()
            ->toArray();

        return view('apartment', [
            'apartments' => $apartments,
        ]);
    }
}
