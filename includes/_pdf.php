<?php
require_once ("_db.php");
require('fpdf181/fpdf.php');
date_default_timezone_set('america/sao_paulo');

class PDF extends FPDF
{
    function Header()
    {
        global $db;
        $resp_query = "SELECT * FROM resp WHERE active_resp = 1 LIMIT 1";
        $resp_result = mysqli_query($db, $resp_query);
        $resp_name = mysqli_fetch_assoc($resp_result)['name_resp'];
        $org = 'EEEP MANOEL MANO';

        $this->Image('../assets/pdf/logo.png', 30, 15, 50);
        $this->Image('../assets/pdf/background.png', 0, 0, $this->w, $this->h);

        $this->SetFont('Arial','B',11);   
        $this->Text(110,20, utf8_decode('Órgão: '));
        $this->SetFont('Arial','',11);  
        $this->Text(123,20, utf8_decode($org));
        
        $this->SetFont('Arial','B',11);   
        $this->Text(110,24, utf8_decode('Responsável Autenticado: '));
        $this->SetFont('Arial','',11);  
        $this->Text(159,24, utf8_decode($resp_name));

        $this->SetFont('Arial','B',11);   
        $this->Text(110,28, utf8_decode('Data do inventário: '));
        $this->SetFont('Arial','',11);  
        $this->Text(145,28, date('d/m/Y'));

        $this->SetFont('Arial','B',12);

        $textWidth = $this->GetStringWidth('INVENTÁRIO DE BENS EM ALMOXARIFADO (ESTOQUE)');
        $xPosition = ($this->GetPageWidth() - $textWidth) / 2;
        $this->Text($xPosition, 55, utf8_decode('INVENTÁRIO DE BENS EM ALMOXARIFADO (ESTOQUE)'));
        $this->Ln(45);
    }
    function Footer()
    {
        $this->SetFont('Arial', 'I', 8);
        $this->SetY(-15);
        $this->Cell(95,5,utf8_decode('Página ').$this->PageNo().' / {nb}',0,0,'L');
        $this->Cell(95,5,date('d/m/Y | G:i') ,00,1,'R');
        
    }
}
$session_query = "SELECT * FROM sessions";
$session_result = mysqli_query($db, $session_query);

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(true, 20);
$pdf->SetTopMargin(15);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);


while ($row_session = mysqli_fetch_assoc($session_result)) {
    $session_p = $row_session['session_id'];
    $session_name = $row_session['name_session'];

    $product_query = "SELECT * FROM products WHERE session_id = $session_p";
    $product_result = mysqli_query($db, $product_query);

    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 11);
    $pdf->Cell(0, 10, utf8_decode('CATEGORIA - ' . mb_strtoupper($session_name, 'UTF-8')), 0, 1, 'C');

    $pdf->SetFillColor(0, 0, 0);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetTextColor(255, 255, 255);

    $pdf->Ln();
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(10, 7, utf8_decode('ID'), 1, 0, 'C', 1);
    $pdf->Cell(80, 7, utf8_decode('ITEM'), 1, 0, 'C', 1);
    $pdf->Cell(30, 7, utf8_decode('COD'), 1, 0, 'C', 1);
    $pdf->Cell(25, 7, utf8_decode('VALIDADE'), 1, 0, 'C', 1);
    $pdf->Cell(30, 7, utf8_decode('ADICIONADOR'), 1, 0, 'C', 1);
    $pdf->Cell(15, 7, utf8_decode('QTDE'), 1, 1, 'C', 1);

    $pdf->SetFont('Arial', '', 10);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetDrawColor(65, 61, 61);
    $bgColor = true;

    if (mysqli_num_rows($product_result) == 0) {
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->Cell(10 + 80 + 30 + 25 + 30 + 15, 10, utf8_decode('SEM ITENS'), 1, 1, 'C', 1);
    } else {
        while ($row = mysqli_fetch_assoc($product_result)) {
            $product_resp_id = $row['resp_id'];
            $resp_query = "SELECT * FROM resp WHERE resp_id = $product_resp_id";
            $resp_data = mysqli_fetch_assoc(mysqli_query($db, $resp_query));
            $resp_name = $resp_data['name_resp'];

            $pdf->SetFillColor($bgColor ? 255 : 231, $bgColor ? 255 : 231, $bgColor ? 255 : 231);

            $pdf->Cell(10, 10, $row['product_id'], 1, 0, 'C', 1);
            $pdf->Cell(80, 10, utf8_decode(substr($row['name_product'], 0, 37)), 1, 0, 'L', 1);
            $pdf->Cell(30, 10, utf8_decode($row['ci_product']), 1, 0, 'C', 1);
            $pdf->Cell(25, 10, utf8_decode($row['validity_product'] == '0000-00-00' ? "" : date("d/m/Y", strtotime($row['validity_product']))), 1, 0, 'C', 1);
            $pdf->Cell(30, 10, utf8_decode($resp_name), 1, 0, 'C', 1);
            $pdf->Cell(15, 10, utf8_decode($row['amount_product']), 1, 0, 'C', 1);
            $pdf->Ln();

            $bgColor = !$bgColor;
        }
    }
}

$filename = "LabStock_" . date("d.m.Y") . ".pdf";
$pdf->Output($filename, 'D');

header("Location: ../views/index.php");
?>