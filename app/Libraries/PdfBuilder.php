<?php

namespace App\Libraries;

use TCPDF;

class PdfBuilder
{
    public function create($title = '')
    {
        $pdf = new TCPDF();

        $pdf->SetCreator('Lev West');
        $pdf->SetAuthor('Lev West');
        $pdf->SetTitle($title);

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->SetMargins(10, 25, 10);
        $pdf->SetAutoPageBreak(true, 10);

        $pdf->SetFont('dejavusans', '', 8);

        // Página inicial
        $pdf->AddPage();

        // Logo automático
        $logo = FCPATH . 'images/logo.png';

        if (is_file($logo)) {
            $pdf->Image($logo, 10, 8, 30);
        }

        return $pdf;
    }

    public function createWithHeader(string $title, string $subtitle = ''): VciPDF
    {
        $pdf = new VciPDF();
        $pdf->setVciHeaderInfo($title, $subtitle);

        $pdf->SetCreator('Lev West');
        $pdf->SetAuthor('Lev West');
        $pdf->SetTitle($title);

        $pdf->setPrintFooter(false);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
        $pdf->SetFont('dejavusans', '', 8);

        $pdf->AddPage();

        return $pdf;
    }
}

class VciPDF extends TCPDF
{
    private string $vciTitle    = '';
    private string $vciSubtitle = '';

    public function setVciHeaderInfo(string $title, string $subtitle): void
    {
        $this->vciTitle    = $title;
        $this->vciSubtitle = $subtitle;
    }

    public function Header(): void
    {
        $logo = FCPATH . 'images/logo.png';
        if (is_file($logo)) {
            $this->Image($logo, $this->original_lMargin, $this->header_margin, 30);
        }

        $this->SetFont('dejavusans', 'B', 11);
        $this->SetTextColor(0, 64, 255);
        $this->SetXY($this->original_lMargin + 35, $this->header_margin + 2);
        $this->Cell(0, 6, $this->vciTitle, 0, 2, 'L');

        $this->SetFont('dejavusans', '', 8);
        $this->SetTextColor(0, 64, 128);
        foreach (explode("\n", $this->vciSubtitle) as $line) {
            $this->SetX($this->original_lMargin + 35);
            $this->Cell(0, 4, $line, 0, 2, 'L');
        }

        $this->SetTextColor(0, 0, 0);
    }
}
