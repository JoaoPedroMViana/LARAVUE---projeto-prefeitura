<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Protocolo;
use App\Models\Acompanhamento;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function downloadPdf() {
        $protocolos = Protocolo::with('pessoa')->get();

        $pdf = Pdf::loadView('pdfprotocolos', ['protocolos' => $protocolos]);
        return $pdf->download('protocolos.pdf');
    }

    public function downloadPdfIndividual($id) {
        $protocolo = Protocolo::with('pessoa', 'departamento')->where([['numero', '=', $id]])->get()->first();
        $acompanhamentos = Acompanhamento::where([['protocolo_id', '=', $protocolo->numero]])->get();

        $pdf = Pdf::loadView('pdfprotocoloindiviadual', ['protocolo' => $protocolo, 'acompanhamentos' => $acompanhamentos]);
        return $pdf->download('protocolos.pdf');
    }
}
