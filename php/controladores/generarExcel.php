<?php
require_once("cn.php");
require '../../recursos/vendor/autoload.php';
$cn = new cn;

use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};
use \PhpOffice\PhpSpreadsheet\Style\{Border, Color, Fill};
$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

$spreadsheet = new Spreadsheet();
// $spreadsheet->setActiveSheetIndex(0);

$hojaActiva = $spreadsheet->getActiveSheet();
$hojaActiva->setTitle("Reporte de prestamos");


// General styling of the page ---------------------------------

$hojaActiva->getPageSetup()->setFitToWidth(1); // The entire page (all columns being used) will be printed
$hojaActiva->getPageSetup()->setFitToHeight(0); // Basically putting like breaks in auto

// Setting the orientation and size for printing
$hojaActiva->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);
$hojaActiva->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);

// Centering content to the center for printing (only horizontal centered this time)
$hojaActiva->getPageSetup()->setHorizontalCentered(true);
// $hojaActiva->getPageSetup()->setVerticalCentered(false);

// Margins (Not configured this time)
// $spreadsheet->getActiveSheet()->getPageMargins()->setTop(1);
// $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.75);
// $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.75);
// $spreadsheet->getActiveSheet()->getPageMargins()->setBottom(1);

// Font settings
$spreadsheet->getDefaultStyle()->getFont()->setName('Calibri');
$spreadsheet->getDefaultStyle()->getFont()->setSize(11);

// Setting header and footer
// $hojaActiva->getHeaderFooter()
//     ->setOddHeader('&C&HDocumento de registro de prestamos de equipos ITCA');
$hojaActiva->getHeaderFooter()
    ->setOddFooter('&L&B' . $spreadsheet->getActiveSheet()->getTitle() . '&RPagina &P de &N');

// Set font horizontal alignment to every column from A to I
$spreadsheet->getDefaultStyle('A:B:C:D:E:F:G:H:I')->getAlignment()->setHorizontal('left');

// Setting rows to repeat in each page when printing
$hojaActiva->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 6);


// Setting (false header that will repeat in every page when printing)

// Setting height for rows in the false header
$hojaActiva->getRowDimension(1)->setRowHeight(29);
$hojaActiva->getRowDimension(2)->setRowHeight(22);
$hojaActiva->getRowDimension(3)->setRowHeight(20);
$hojaActiva->getRowDimension(4)->setRowHeight(14);
$hojaActiva->getRowDimension(5)->setRowHeight(28);
$hojaActiva->getRowDimension(6)->setRowHeight(16);

$hojaActiva->mergeCells('A1:C3');

// Importing ITCA Logo at A1 columns
$drawing->setName('Logo itca');
$drawing->setDescription('Logo de ITCA');
$drawing->setPath('../../img/logo (ITCA - FEPADE).png'); // put your path and image here
$drawing->setCoordinates('A1');
$drawing->setWidth(270);
// $drawing->setHeight(40);
$drawing->setOffsetX(1);
$drawing->setOffsetY(20);
$drawing->setWorksheet($spreadsheet->getActiveSheet());

// Setting border left from D1 to D3
$hojaActiva
    ->getStyle('D1:D3')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_MEDIUM)
    ->setColor(new Color('cd9013'));

// Mergin from E1,E2,33 to F1,F2,F3
$hojaActiva->mergeCells('E1:F1');
$hojaActiva->mergeCells('E2:F2');
$hojaActiva->mergeCells('E3:F3');
$hojaActiva->setCellValue('E1', 'REPORTE');
$hojaActiva->setCellValue('E2', ' DE PRESTAMOS');
$hojaActiva->setCellValue('E3', ' DE EQUIPOS ITCA');
$hojaActiva->getStyle('E1:E3')->getFont()->setBold(1);
$hojaActiva->getStyle('E1')->getFont()->setSize(36);
$hojaActiva->getStyle('E2:E3')->getFont()->setSize(20);
$hojaActiva->getStyle('E1')->getAlignment()->setVertical('center');

// Background from A6 to H6 column
$hojaActiva
    ->getStyle('G1:I3')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('f8f4f4');

$hojaActiva->getStyle('G1:I3')->getAlignment()->setHorizontal('center');
$hojaActiva->getStyle('G1:I3')->getAlignment()->setVertical('center');
$hojaActiva->getStyle('G1:I3')->getFont()->setBold(1);
$hojaActiva->getStyle('G1:I3')->getFont()->setSize(14);
$hojaActiva->getStyle('A1:H3')->getFont()->getColor()->setARGB('cd9013');
$hojaActiva->mergeCells('G1:I1');
$hojaActiva->mergeCells('G2:I2');
$hojaActiva->mergeCells('G3:I3');
$hojaActiva->setCellValue('G1', '10a AVENIDA SUR, SANTA ANA');
$hojaActiva->setCellValue('G2', 'CALL CENTER:');
$hojaActiva->setCellValue('G3', '2668-4700');

// Border for G1 to I3 (To make the rectangle)
$hojaActiva
    ->getStyle('G1:G3')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_MEDIUM)
    ->setColor(new Color('cd9013'));
$hojaActiva
    ->getStyle('G1:I3')
    ->getBorders()
    ->getTop()
    ->setBorderStyle(Border::BORDER_MEDIUM)
    ->setColor(new Color('cd9013'));
$hojaActiva
    ->getStyle('G3:I3')
    ->getBorders()
    ->getBottom()
    ->setBorderStyle(Border::BORDER_MEDIUM)
    ->setColor(new Color('cd9013'));
$hojaActiva
    ->getStyle('I1:I3')
    ->getBorders()
    ->getRight()
    ->setBorderStyle(Border::BORDER_MEDIUM)
    ->setColor(new Color('cd9013'));

// Border from A5 to I5 columns
$hojaActiva
    ->getStyle('A5:I5')
    ->getBorders()
    ->getBottom()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('A5:I5')
    ->getBorders()
    ->getTop()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('A5:C5')
    ->getBorders()
    ->getRight()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));

$hojaActiva->getStyle('A5:I5')->getFont()->setSize(13);

// Setting columns from A to H to auto-size themselves
$hojaActiva->getColumnDimension('A')->setAutoSize(true);
$hojaActiva->getColumnDimension('B')->setAutoSize(true);
$hojaActiva->getColumnDimension('C')->setAutoSize(true);
$hojaActiva->getColumnDimension('D')->setAutoSize(true);
$hojaActiva->getColumnDimension('E')->setAutoSize(true);
$hojaActiva->getColumnDimension('F')->setWidth(22);
$hojaActiva->getColumnDimension('G')->setAutoSize(true);
$hojaActiva->getColumnDimension('H')->setAutoSize(true);
$hojaActiva->getColumnDimension('I')->setAutoSize(true);



// Background since A5 to I5 column
$hojaActiva
    ->getStyle('A5:I5')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('cd9013'); // TODO: MAKE THIS COLOR LIGHTER !!!

// Setting a new height to the second row in the active sheet
$hojaActiva->getStyle('A5:H5')->getAlignment()->setHorizontal('center');
$hojaActiva->getStyle('A5:H5')->getAlignment()->setVertical('center');
$hojaActiva->getStyle('A5:H5')->getFont()->setBold(1);
$hojaActiva->getStyle('A5:H5')->getFont()->getColor()->setARGB('FFFFFF');

// Body of the document (We could say, since will be displayed dinamically) 

// Background since A6 to H6 column
$hojaActiva
    ->getStyle('A6:H6')
    ->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('f8f4f4');

$hojaActiva->mergeCells('A5:C5');
$hojaActiva->mergeCells('D5:I5');
$hojaActiva->setCellValue('A5', 'Realizado por');
$hojaActiva->setCellValue('D5', 'Datos del prestamo');


$hojaActiva
    ->getStyle('A6:I6')
    ->getBorders()
    ->getBottom()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('D6')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva->getStyle('A6:I6')->getFont()->setBold(1);
$hojaActiva->setCellValue('A6', 'Carnet');
$hojaActiva->setCellValue('B6', 'Nombres');
$hojaActiva->setCellValue('C6', 'Apellidos');
$hojaActiva->setCellValue('D6', 'ID');
$hojaActiva->setCellValue('F6', 'Aula o computo');
$hojaActiva->setCellValue('G6', 'Prestamo realizado en');
$hojaActiva->setCellValue('H6', 'Prestamo agendado para');
$hojaActiva->setCellValue('I6', 'Estado');

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

// dinamic data starts printing from $row = Number of the row we want
$row = 7;

if(mysqli_num_rows($result) >= 1) {
    // If there is "prestamos" this happens
    
    foreach($result as $fila) {
        // putting dinamic data in the columns en each iteration
        $hojaActiva->setCellValue('A'. $row, $fila["carnet"]);
        $hojaActiva->setCellValue('B'. $row, $fila["nombres"]);
        $hojaActiva->setCellValue('C'. $row, $fila["apellidos"]);
        $hojaActiva->setCellValue('D'. $row, $fila["id_prestamo"]);
        $hojaActiva->setCellValue('F'. $row, $fila["aula"]);
        $hojaActiva->setCellValue('G'. $row, $fila["fecha_hecha"]);
        $hojaActiva->setCellValue('H'. $row, $fila["fecha_destino"]);
        $hojaActiva->setCellValue('I'. $row, $fila["estado"]);

        // Borders
        $hojaActiva
            ->getStyle('A'.$row.':I'.$row)
            ->getBorders()
            ->getBottom()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000'));

        $hojaActiva
            ->getStyle('C'.$row)
            ->getBorders()
            ->getRight()
            ->setBorderStyle(Border::BORDER_THIN)
            ->setColor(new Color('000'));
        
        if($row % 2 == 0) {
        $hojaActiva
            ->getStyle('A'.$row.':I'.$row)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('f8f4f4');   
        }

        $row++;
        
        // Setting Line break manually (no longer needed as it is auto)
        // if(($row % 64) == 0) {
        //     // Setting the page breaks
        //     $hojaActiva->setBreak('A'.$row.'', Worksheet::BREAK_ROW);            
        // }
    }
} else {
    // If there is no "prestamos" this happens
    $hojaActiva->getColumnDimension('C')->setAutoSize(false);
    $hojaActiva->getColumnDimension('C')->setWidth(26);

    $hojaActiva->getRowDimension($row)->setRowHeight(20);
    $hojaActiva->getStyle('A'.$row)->getAlignment()->setHorizontal('center');
    $hojaActiva->getStyle('A'.$row)->getAlignment()->setVertical('center');

    $hojaActiva
    ->getStyle('A'.$row.':I'.$row)
    ->getBorders()
    ->getTop()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));

    $hojaActiva
    ->getStyle('A'.$row.':I'.$row)
    ->getBorders()
    ->getBottom()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));

    $hojaActiva->getStyle('A'.$row)->getFont()->getColor()->setARGB('FFFFFF');

    $hojaActiva
    ->getStyle('A'.$row)
    ->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('d5301b');

    $hojaActiva->setCellValue('A'. $row, 'No se han encontrado prestamos');   
    $hojaActiva->mergeCells('A'. $row.':I'.$row);
}

// Setting config to dowload the seet to the user's client
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte de prestamos.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;

?>