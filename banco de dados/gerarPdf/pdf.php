<?php
require('fpdf186/fpdf.php');

$nome_administrador = 'ANDERSON'; // Obtém o nome do usuário autenticado

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Relatório Gerado por:', 0, 1, 'C');
$pdf->Cell(0, 10, $nome_administrador, 0, 1, 'C');

$pdf->Output('1.pdf', 'f');
?>