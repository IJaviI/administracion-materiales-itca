<?php
require_once("cn.php");
require('../../recursos/fpdf/fpdf.php');
$cn = new cn;

// Data for based on range of dates
$fecha_from = $_POST["fecha_from"];
$fecha_to = $_POST["fecha_to"];
$stateFechas = $_POST["stateFechas"];
$deptoFechas = $_POST["deptoFechas"];

// Data for based on user
$user = $_POST["user"];
$stateUser = $_POST["stateUser"];

// Getting the data dinamically
if($fecha_from != "" && $fecha_to != ""){
    if($stateFechas == "Todos" && $deptoFechas == "Todos"){
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, 
        CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', 
        prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado
        FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.fecha_destino BETWEEN '$fecha_from' AND '$fecha_to' ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
    } else if ($stateFechas != "Todos" && $deptoFechas == "Todos") {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, 
        CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', 
        prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado
        FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.estado = '$stateFechas' AND prestamo.fecha_destino BETWEEN '$fecha_from' AND '$fecha_to' ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
    } else if ($stateFechas == "Todos" && $deptoFechas != "Todos") {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, prestamo.id_usuario,
        CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', 
        prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado
        FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.id_usuario IN (SELECT id_usuario FROM usuario WHERE id_depto = $deptoFechas) AND prestamo.fecha_destino BETWEEN '$fecha_from' AND '$fecha_to' ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
    } else if($stateFechas != "Todos" && $deptoFechas != "Todos") {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, prestamo.id_usuario,
        CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', 
        prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado
        FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE prestamo.estado = '$stateFechas' AND prestamo.id_usuario IN (SELECT id_usuario FROM usuario WHERE id_depto = $deptoFechas) AND prestamo.fecha_destino BETWEEN '$fecha_from' AND '$fecha_to' ORDER BY prestamo.estado DESC, prestamo.fecha_destino DESC";
    }
} else if($user != "") {
    if($stateUser == "Todos"){
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, 
        CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', 
        prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado
        FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE usuario.id_usuario = $user ORDER BY prestamo.estado DESC, prestamo.id_prestamo DESC";
    } else {
        $sql = "SELECT usuario.id_usuario, usuario.carnet, usuario.nom_usuario AS 'nombres', usuario.ape_usuario AS 'apellidos', prestamo.id_prestamo, 
        CONCAT(IF((SELECT tipo FROM aula WHERE id_aula = prestamo.id_aula) = 1, 'Computo', 'Aula'), ' ', (SELECT aula FROM aula WHERE id_aula = prestamo.id_aula)) AS 'aula', 
        prestamo.fecha_hecha, prestamo.fecha_destino, prestamo.estado
        FROM prestamo
        INNER JOIN usuario ON prestamo.id_usuario = usuario.id_usuario WHERE usuario.id_usuario = $user AND prestamo.estado = '$stateUser' ORDER BY prestamo.estado DESC, prestamo.id_prestamo DESC";;
    }
}

$result = $cn -> cn() -> query($sql);

class PDF extends FPDF
{
function ResizableCell($width, $height, $text, $border = 0, $ln = 0, $align = 'L', $fill = false) {
    $k = $this->k;
    if ($ln > 0) {
        $this->Cell($width, $height, $text, $border, $ln, $align, $fill);
    } else {
        $wmax = ($width - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $w = $wmax + 1;
        $textLength = strlen($text);
        while ($w > $wmax && $textLength > 0) {
            $this->FontSize--;
            $w = $textLength * $this->FontSize * $k / 1000;
        }
        $this->Cell($width, $height, $text, $border, 0, $align, $fill);
        $this->FontSize = $this->originalFontSize;
    }
}

// Cabecera de página
function Header()
{
    // Logo
    $this->Image('../../img/header.png',10,11,190);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    $this->SetTextColor(255,255,255);
    
    // Movernos a la derecha
    // $this->Cell(80);
    // // Título
    // $this->Cell(30,10,'Title',1,0,'C');

    // Salto de línea
    // $this->Ln(1);

    $this->SetFillColor(205, 144, 19);
    $this->SetFont( 'Arial', 'B', 8);
    $this->setXY(12,42);
    $this->Cell(100,8,"Realizado por",1,0,'C', 1);
    // $this->ResizableCell(100,8,"Realizado por",1,1,'C', 1);

    $this->setX(93);
    $this->Cell(103,8,"Detalles del prestamo",1,0,'C', 1);
    $this->setX(58);

    $this->SetFillColor(248, 244, 244);
    $this->setXY(12,42);
    $this->SetFont( 'Arial', 'B', 7);
    $this->SetTextColor(0, 0, 0);

    $this->setXY(12,50);
    $this->Cell(16,6,"Carnet",1,0,'L', 1);
    $this->setX(28);
    $this->Cell(30,6,"Nombres",1,0,'L', 1);
    $this->setX(58);
    $this->Cell(35,6,"Apellidos",1,0,'L', 1);
    $this->setX(93);
    $this->Cell(10,6,"ID",1,0,'L', 1);
    $this->setX(103);
    $this->Cell(23,6,"Aula o computo",1,0,'L', 1);
    $this->setX(126);
    $this->Cell(25,6,"Realizado en",1,0,'L', 1);
    $this->setX(151);
    $this->Cell(25,6,"Agendado para",1,0,'L', 1);
    $this->setX(176);
    $this->Cell(20,6,"Estado",1,1,'L', 1);

    $this->SetFont( 'Arial', '', 7);
}

// Cuerpo de pagina



// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','B', 7);
    $this->SetTextColor(0, 0, 0);
    // Número de página
    $this->Cell(0,10,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');
}
}

$pdf = new PDF;
$pdf->AliasNbPages();
$pdf->AddPage();

if(mysqli_num_rows($result) >= 1) {
    // If there is "prestamos" this happens
    
    $row = 0;
    foreach($result as $fila) {
        // Setting the color of each row dinamically
        if($row % 2 == 0) {    
            $pdf->SetFillColor(255, 255, 255);
        }
        
        if($row % 2 == 1) {    
            $pdf->SetFillColor(248, 244, 244);
        }

        // putting dinamic data in the columns en each iteration
        $pdf->setX(12);
        $pdf->Cell(16,6,$fila["carnet"],1,0,'L', 1);
        
        // $string = "Fernando Javier Romero";
        // $cellWidth = $pdf->GetStringWidth($string);
        $pdf->setX(28);
        $pdf->Cell(30, 6, $fila["nombres"], 1, 0, 'L', 1);
        
        $pdf->setX(58);
        $pdf->Cell(35, 6, $fila["apellidos"], 1, 0, 'L', 1);
        
        $pdf->setX(93);
        $pdf->Cell(10, 6, $fila["id_prestamo"], 1, 0, 'L', 1);
        
        $pdf->setX(103);
        $pdf->Cell(23, 6, $fila["aula"], 1, 0, 'L', 1);
        
        $pdf->setX(126);
        $pdf->Cell(25, 6, $fila["fecha_hecha"], 1, 0, 'L', 1);
        
        $pdf->setX(151);
        $pdf->Cell(25, 6, $fila["fecha_destino"], 1, 0, 'L', 1);
        
        $pdf->setX(176);
        $pdf->Cell(20, 6, $fila["estado"], 1, 1, 'L', 1);
          
        $row++;
    }
} else {
    // If there is no "prestamos" this happens
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFillColor(213, 48, 27);
    $pdf->setX(12);
    $pdf->SetFont( 'Arial', 'B', 7.2);
    $pdf->Cell(184,6.5,"No se ha encontrado ningun prestamo.",1,0,'C', 1);
}


$pdf->Output('D', 'Reporte de prestamos.pdf');
?>