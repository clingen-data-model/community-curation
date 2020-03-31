<?php

namespace App\Http\Controllers;

use App\Gene;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class GeneProtocolController extends Controller
{
    public function show($symbol)
    {
        $gene = Gene::where('symbol', $symbol)->first();
        if (!$gene) {
            throw new ModelNotFoundException('The gene '.$symbol.' could not be found.');
        }

        if (!$gene->protocol_path) {
            session()->flash('error', 'Protocol file not found for gene '.$symbol.'.');
            return redirect()->back();
        }

        return response()->download(storage_path('app/public/'.$gene->protocol_path), $gene->protocol_filename);
    }
}
