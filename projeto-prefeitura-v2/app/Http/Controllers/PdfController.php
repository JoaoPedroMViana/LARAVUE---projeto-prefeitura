<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Protocolo;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function downloadPdf() {
        $protocolos = Protocolo::with('pessoa')->get();

        $pdf = Pdf::loadView('invoice', ['protocolos' => $protocolos]);
        return $pdf->download('protocolos.pdf');
        // return response()->streamDownload(
        //     function () use ($pdf) {
        //         echo $pdf->output();
        //     },
        //     'protocolos.pdf'
        // );
    }
}
