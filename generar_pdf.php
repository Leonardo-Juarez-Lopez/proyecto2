<?php
require('fpdf/fpdf.php');
include('conexion.php');

class PDF extends FPDF {
    function Header() {
        // Título centrado
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(0);
        $this->Cell(0, 10, 'HORARIO DE LAS FUNCIONES', 0, 1, 'C');

        // Fecha actual a la derecha
        $this->SetFont('Arial', '', 12);
        $this->Cell(0, 10, 'Fecha: ' . date('d/m/Y'), 0, 1, 'R');
        $this->Ln(5);
    }

    function FancyTable($header, $data) {
        // Cabecera gris claro
        $this->SetFillColor(230,230,230);
        $this->SetTextColor(0);
        $this->SetDrawColor(0);
        $this->SetLineWidth(.3);
        $this->SetFont('Arial','B',12);

        $w = [30, 30, 30, 50, 25, 30]; // ancho de columnas

        // Encabezados
        for($i=0; $i<count($header); $i++) {
            $this->Cell($w[$i], 7, utf8_decode($header[$i]), 1, 0, 'C', true);
        }
        $this->Ln();

        // Contenido
        $this->SetFont('Arial','',10);
        $this->SetFillColor(255,255,255);

        foreach($data as $row) {
            $this->Cell($w[0], 6, $row['hora_inicio'], 1, 0, 'C', true);
            $this->Cell($w[1], 6, $row['hora_final'], 1, 0, 'C', true);
            $this->Cell($w[2], 6, utf8_decode($row['sala']), 1, 0, 'C', true);
            $this->Cell($w[3], 6, utf8_decode($row['pelicula']), 1, 0, 'C', true);
            $this->Cell($w[4], 6, $row['capacidad'], 1, 0, 'C', true);
            $this->Cell($w[5], 6, utf8_decode($row['tipo_sala']), 1, 0, 'C', true);
            $this->Ln();
        }
    }
}

$pdf = new PDF();
$pdf->AddPage();

// Consulta desde la vista, ordenada por hora
$sql = "SELECT 
    FORMAT(hora_inicio, 'HH:mm') AS hora_inicio,
    FORMAT(hora_final, 'HH:mm') AS hora_final,
    sala, pelicula, capacidad, tipo_sala
FROM vista_funciones_dia_actual
ORDER BY hora_inicio";

$result = sqlsrv_query($conn, $sql);

$data = [];
while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
    $data[] = $row;
}

$header = ['Hora de inicio', 'Hora final', 'Sala', 'Película', 'Capacidad', 'Tipo de sala'];
$pdf->FancyTable($header, $data);
$pdf->Output();
?>
