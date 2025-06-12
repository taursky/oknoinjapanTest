<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Apartment extends Controller
{
    public function index()
    {
//        $csvFile = $_SERVER['DOCUMENT_ROOT'] . '/files/data.csv';
//        $file = fopen($csvFile, 'r');
//        fgetcsv($file, 0, ';');
//        dd($file);
//        $apartments =
        return view('apartment', [

        ]);
    }
}
