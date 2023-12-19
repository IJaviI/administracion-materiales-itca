<?php
session_start();
require_once("cn.php");
require('../../recursos/fpdf/fpdf.php');
$cn = new cn;

// Data for based on range of dates
$fecha = $_POST["fecha_ingreso"];
$depto = $_POST["depto"];
$created_by = $_SESSION['userProfile']['id'];

$sql = "SELECT 
            m.id_material AS 'codigo_material',
            u.unidad,
            m.descripcion, 
            CONCAT(ma.cantidad_ingresada, '.00') AS 'cantidad', 
            m.id_depto AS 'codigo_departamento'
        FROM material m
        JOIN depto d USING (id_depto)
        JOIN unidad u USING (id_unidad)
        JOIN material_archivado ma USING (id_material)
        WHERE ma.fecha_ingreso = '$fecha' AND m.id_depto = $depto AND m.created_by = $created_by";

$result = $cn -> cn() -> query($sql);

$sql = "SELECT 
            id_depto,
            depto
        FROM depto
        WHERE id_depto = $depto";

$depto_data = $cn -> cn() -> query($sql);
$depto_column = $depto_data -> fetch_assoc();

$_SESSION["depto_column"]["id"] = $depto_column["id_depto"];
$_SESSION["depto_column"]["depto"] = $depto_column["depto"];

$date = date_create($fecha);
$_SESSION["fecha"] = $date;

class PDF extends FPDF
{

// function ResizableCell($width, $height, $text, $border = 0, $ln = 0, $align = 'L', $fill = false) {
//     $k = $this->k;
//     if ($ln > 0) {
//         $this->Cell($width, $height, $text, $border, $ln, $align, $fill);
//     } else {
//         $wmax = ($width - 2 * $this->cMargin) * 1000 / $this->FontSize;
//         $w = $wmax + 1;
//         $textLength = strlen($text);
//         while ($w > $wmax && $textLength > 0) {
//             $this->FontSize--;
//             $w = $textLength * $this->FontSize * $k / 1000;
//         }
//         $this->Cell($width, $height, $text, $border, 0, $align, $fill);
//         $this->FontSize = $this->originalFontSize;
//     }
// }

// Cabecera de página
function Header()
{
    // Logo
    $this->Image('../../img/logo.png',12,9,30);

    $this->SetFillColor(255, 255, 255);
    $this->SetTextColor(0, 0, 0);
    $this->SetFont( 'Arial', '', 6);

    $this->setXY(85,9);
    $this->Cell(50,5, date("d M Y"),0,1,'C', 0);

    $this->SetFont( 'Arial', 'B', 8);

    $this->setXY(50,12);
    $this->Cell(125,8,"INGRESO DE MATERIALES Y SUMINISTRO EN DEPOSITO BODEGA REGIONAL",0,1,'C', 0);
    $this->setXY(50,16.5);
    $this->Cell(125,8,"SANTA ANA",0,1,'C', 0);

    $this->SetFont( 'Arial', 'B', 6);
    $this->setXY(12, 25);
    $this->Cell(60,8,"CENTRO DE COSTO: ",0,1,'L', 0);
    $this->setXY(12, 30);
    $this->Cell(60,8,"DEPARTAMENTO: ",0,1,'L', 0);
    
    $this->SetFont( 'Arial', '', 6);
    $this->setXY(38, 25);
    $this->Cell(60,8, "4002 - EDUCACION TECNICA REGIONAL SANTA ANA",0,1,'L', 0);
    $this->setXY(35, 30);
    $this->Cell(60,8, $_SESSION["depto_column"]["id"],0,1,'L', 0);
    $this->setXY(38, 30);
    $this->Cell(62,8, " - ",0,1,'L', 0);
    $this->setXY(41, 30);
    $this->Cell(60,8, strtoupper($_SESSION["depto_column"]["depto"]),0,1,'L', 0);

    $this->SetFont( 'Arial', 'B', 6);
    $this->setXY(112, 30);
    $this->Cell(60,8, "TIPO DOCUMENTO: ",0,1,'L', 0);

    $this->SetFont( 'Arial', '', 6);
    $this->setXY(137, 30);
    $this->Cell(60,8, "CREDITO FISCAL",0,1,'L', 0);
    
    $this->SetFont( 'Arial', 'B', 6);
    $this->setXY(168, 30);
    $this->Cell(60,8, "FECHA: ",0,1,'L', 0);

    $this->SetFont( 'Arial', '', 6);
    $this->setXY(180, 30);
    $this->Cell(60,8, date_format($_SESSION["fecha"], 'd/m/Y'),0,1,'L', 0);



    $this->SetFont( 'Arial', 'B', 6);

    $this->setXY(12,42);
    $this->Cell(16,8,"CODIGO",1,0,'C', 1);
    $this->setX(28);
    $this->Cell(28,8,"UNIDAD",1,0,'C', 1);
    $this->setX(56);
    $this->Cell(75,8,"DESCRIPCION",1,0,'C', 1);
    $this->setX(131);
    $this->Cell(20,8,"CANTIDAD",1,0,'C', 1);
    $this->setX(151);
    $this->Cell(46,8,"DEPARTAMENTO",1,0,'C', 1);
    $this->setX(126);

    $this->SetFont( 'ARIAL', '', 5);
}

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

// Cuerpo de pagina

$pdf = new PDF;
$pdf->AliasNbPages();
$pdf->AddPage();

if(mysqli_num_rows($result) >= 1) {
    // If there is "prestamos" this happens
    $pdf->setY(51.5);
    $row = 0;
    foreach($result as $fila) {
        // Setting the color of each row dinamically
        if($row % 2 == 0) {    
            $pdf->SetFillColor(255, 255, 255);
        }
        
        if($row % 2 == 1) {    
            $pdf->SetFillColor(255, 255, 255);
        }

        // putting dinamic data in the columns en each iteration
        $pdf->setX(12);
        $pdf->Cell(17, 3,$fila["codigo_material"],0,0,'C', 1);

        $pdf->setX(29);
        $pdf->Cell(27, 3, strtoupper($fila["unidad"]), 0, 0, 'C', 1);
        
        $pdf->setX(58);
        $pdf->Cell(73, 3, strtoupper($fila["descripcion"]), 0, 0, 'L', 1);
        
        $pdf->setX(131);
        $pdf->Cell(20, 3, $fila["cantidad"], 0, 0, 'C', 1);
        
        $pdf->setX(151);
        $pdf->Cell(46, 3, $fila["codigo_departamento"], 0, 1, 'C', 1);
          
        $row++;
    }
} else {
    // If there is no records this happens
    $pdf->setY(50);
    $pdf->SetTextColor(255,255,255);
    $pdf->SetFillColor(213, 48, 27);
    $pdf->setX(12);
    $pdf->SetFont( 'Arial', 'B', 6);
    $pdf->Cell(185,6.5,"No se ha encontrado ningun ingreso de material en la fecha y departamento especificados.",1,0,'C', 1);
}


$pdf->Output('D', 'Ingreso de materiales.pdf');
?>