<?php
session_start();
require_once("cn.php");
require '../../recursos/vendor/autoload.php';
$cn = new cn;

$fecha_desde = $_POST["fecha_ingreso_desde"];
$fecha_hasta = $_POST["fecha_ingreso_hasta"];
$depto = $_POST["depto"];
$created_by = $_SESSION['userProfile']['id'];

$sql = "SELECT 
            m.id_material AS 'codigo_material',
            un.unidad,
            CONCAT(m.cantidad, '.00') AS 'cantidad_actual',
            m.descripcion,
            m.precio AS 'precio_unidad',
            CONCAT(ma.cantidad_ingresada, '.00') AS 'cantidad_entregada',
            d.depto,
            ma.fecha_ingreso,
            us.nom_usuario AS 'nombres',
            us.ape_usuario AS 'apellidos'
        FROM material m
        JOIN depto d USING (id_depto)
        JOIN unidad un USING (id_unidad)
        JOIN material_archivado ma USING (id_material)
        JOIN usuario us ON m.created_by = us.id_usuario
        WHERE ma.fecha_ingreso BETWEEN '$fecha_desde' AND '$fecha_hasta' AND m.id_depto = $depto AND m.created_by = $created_by";

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

use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};
use \PhpOffice\PhpSpreadsheet\Style\{Border, Color, Fill};
$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

$spreadsheet = new Spreadsheet();

$hojaActiva = $spreadsheet->getActiveSheet();
$hojaActiva->setTitle("Control de entrega");

$hojaActiva->getPageSetup()->setFitToWidth(1); // The entire page (all columns being used) will be printed
$hojaActiva->getPageSetup()->setFitToHeight(0); // Basically putting like breaks in auto

// Setting the orientation and size for printing
$hojaActiva->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
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
// $spreadsheet->getDefaultStyle('A:B:C:D:E:F:G:H:I')->getAlignment()->setHorizontal('left');

// Setting rows to repeat in each page when printing
$hojaActiva->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 6);


// Setting (false header that will repeat in every page when printing)

// Setting height for rows in the false header
$hojaActiva->getRowDimension(1)->setRowHeight(22);
$hojaActiva->getRowDimension(2)->setRowHeight(22);
$hojaActiva->getRowDimension(3)->setRowHeight(22);
$hojaActiva->getRowDimension(4)->setRowHeight(22);
$hojaActiva->getRowDimension(5)->setRowHeight(10);
$hojaActiva->getRowDimension(6)->setRowHeight(29);

$hojaActiva->mergeCells('A1:C2');

// Importing ITCA Logo at A1 columns
$drawing->setName('Logo itca');
$drawing->setDescription('Logo de ITCA');
$drawing->setPath('../../img/logo2.png'); // put your path and image here
$drawing->setCoordinates('A1');
$drawing->setWidth(240);
$drawing->setOffsetX(1);
$drawing->setOffsetY(1);
$drawing->setWorksheet($spreadsheet->getActiveSheet());

$hojaActiva->getStyle('D1:L2')->getAlignment()->setHorizontal('center');
$hojaActiva->getStyle('D1:L1')->getAlignment()->setVertical('bottom');
$hojaActiva->getStyle('D2:L2')->getAlignment()->setVertical('center');
$hojaActiva->getStyle('D1:L2')->getFont()->setBold(1);
$hojaActiva->getStyle('D1:L2')->getFont()->setSize(14);
$hojaActiva->mergeCells('D1:L1');
$hojaActiva->mergeCells('D2:L2');
$hojaActiva->setCellValue('D1', 'INGRESO DE MATERIALES Y SUMINISTRO EN DEPOSITO BODEGA REGIONAL');   
$hojaActiva->setCellValue('D2', 'SANTA ANA');

$hojaActiva->getStyle('A3:L4')->getAlignment()->setHorizontal('center');
$hojaActiva->getStyle('A3:L3')->getAlignment()->setVertical('bottom');
$hojaActiva->getStyle('A4:L4')->getAlignment()->setVertical('center');

$hojaActiva->getStyle('A3:L4')->getFont()->setBold(1);
$hojaActiva->getStyle('A3:L4')->getFont()->setSize(14);
$hojaActiva->mergeCells('A3:L3');
$hojaActiva->mergeCells('A4:L4');
$hojaActiva->setCellValue('A3', 'CONTROL DE ENTREGA DE INVENTARIO DE CARRERA TECNICA');   
$hojaActiva->setCellValue('A4', 'ENTREGA DE MATERIALES Y HERRAMIENTAS');

// date_format($date, 'd/m/Y')

// Border from A6 to L6 columns
$hojaActiva
    ->getStyle('A6:L6')
    ->getBorders()
    ->getBottom()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('A6:L6')
    ->getBorders()
    ->getTop()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('A6')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('B6')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('C6')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('D6')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('E6')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('F6')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('G6')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('H6')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('I6')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('J6')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('K6')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('L6')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('L6')
    ->getBorders()
    ->getRight()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));

// Setting columns from A to H to auto-size themselves
$hojaActiva->getColumnDimension('A')->setAutoSize(true);
$hojaActiva->getColumnDimension('B')->setAutoSize(true);
$hojaActiva->getColumnDimension('C')->setAutoSize(true);
$hojaActiva->getColumnDimension('D')->setAutoSize(true);
$hojaActiva->getColumnDimension('E')->setWidth(27);
$hojaActiva->getColumnDimension('F')->setAutoSize(true);
$hojaActiva->getColumnDimension('G')->setWidth(23);
$hojaActiva->getColumnDimension('H')->setWidth(15);
$hojaActiva->getColumnDimension('I')->setWidth(23);
$hojaActiva->getColumnDimension('J')->setWidth(15);
$hojaActiva->getColumnDimension('K')->setAutoSize(true);
$hojaActiva->getColumnDimension('L')->setAutoSize(true);

// $hojaActiva->mergeCells('A5:C5');
// $hojaActiva->mergeCells('D5:I5');
// $hojaActiva->setCellValue('A5', 'Realizado por');
// $hojaActiva->setCellValue('D5', 'Datos del prestamo');

$hojaActiva->getStyle('A6:L6')->getAlignment()->setHorizontal('center');
$hojaActiva->getStyle('A6:L6')->getAlignment()->setVertical('center');
$hojaActiva->getStyle('A6:L6')->getFont()->setBold(1);
$hojaActiva->setCellValue('A6', 'FECHA');
$hojaActiva->setCellValue('B6', 'DPTO.');
$hojaActiva->setCellValue('C6', 'CODIGO');
$hojaActiva->setCellValue('D6', 'UNIDAD');
$hojaActiva->setCellValue('E6', 'DESCRIPCION');
$hojaActiva->setCellValue('F6', 'CANT. ENTREGADA');
$hojaActiva->setCellValue('G6', 'NOMBRE DE QUIEN ENTREGA');
$hojaActiva->setCellValue('H6', 'FIRMA');
$hojaActiva->setCellValue('I6', 'NOMBRE DE QUIEN RECIBE');
$hojaActiva->setCellValue('J6', 'FIRMA');
$hojaActiva->setCellValue('K6', 'CANT. ACTUAL');
$hojaActiva->setCellValue('L6', 'PRECIO UNIDAD');

$row = 7;
$initial_row=$row;
$final_row = $initial_row;

$hojaActiva
->getStyle("E".$row - 1)
->getAlignment()
->setWrapText(true);
$hojaActiva
->getStyle("G".$row - 1)
->getAlignment()
->setWrapText(true);
$hojaActiva
->getStyle("I".$row - 1)
->getAlignment()
->setWrapText(true);

if(mysqli_num_rows($result) >= 1) {
    foreach($result as $fila) {
        // Borders
        $hojaActiva
        ->getStyle("A".$row)
        ->getBorders()
        ->getLeft()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000'));
        $hojaActiva
        ->getStyle("A".$row)
        ->getBorders()
        ->getRight()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000'));
        $hojaActiva
        ->getStyle("B".$row)
        ->getBorders()
        ->getRight()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000'));
        $hojaActiva
        ->getStyle("C".$row)
        ->getBorders()
        ->getRight()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000'));
        $hojaActiva
        ->getStyle("D".$row)
        ->getBorders()
        ->getRight()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000'));
        $hojaActiva
        ->getStyle("E".$row)
        ->getBorders()
        ->getRight()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000'));
        $hojaActiva
        ->getStyle("F".$row)
        ->getBorders()
        ->getRight()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000'));
        $hojaActiva
        ->getStyle("G".$row)
        ->getBorders()
        ->getRight()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000'));
        $hojaActiva
        ->getStyle("H".$row)
        ->getBorders()
        ->getRight()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000'));
        $hojaActiva
        ->getStyle("I".$row)
        ->getBorders()
        ->getRight()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000'));
        $hojaActiva
        ->getStyle("J".$row)
        ->getBorders()
        ->getRight()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000'));
        $hojaActiva
        ->getStyle("K".$row)
        ->getBorders()
        ->getRight()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000'));
        $hojaActiva
        ->getStyle("L".$row)
        ->getBorders()
        ->getRight()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000'));
        $hojaActiva
        ->getStyle("A".$row.":"."L".$row)
        ->getBorders()
        ->getBottom()
        ->setBorderStyle(Border::BORDER_THIN)
        ->setColor(new Color('000'));

        $hojaActiva
        ->getStyle('L'.$row)
        ->getNumberFormat()
        ->setFormatCode(PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_CURRENCY_USD);
        
        $hojaActiva->getStyle('A'.$row.':'.'L'.$row)->getAlignment()->setHorizontal('center');
        $hojaActiva->getStyle('A'.$row.':'.'L'.$row)->getAlignment()->setVertical('center');
        $hojaActiva->getStyle('E'.$row)->getAlignment()->setHorizontal('left');
        $hojaActiva->getStyle('G'.$row)->getAlignment()->setHorizontal('left');
        $hojaActiva->getStyle('I'.$row)->getAlignment()->setHorizontal('left');

        $hojaActiva->setCellValue('A'. $row, $fila["fecha_ingreso"]);
        $hojaActiva->setCellValue('B'. $row, strtoupper($fila["depto"]));
        $hojaActiva->setCellValue('C'. $row, $fila["codigo_material"]);
        $hojaActiva->setCellValue('D'. $row, strtoupper($fila["unidad"]));
        $hojaActiva->setCellValue('E'. $row, strtoupper($fila["descripcion"]));
        $hojaActiva->setCellValue('F'. $row, $fila["cantidad_entregada"]);
        $hojaActiva->setCellValue('G'. $row, strtoupper(""));
        $hojaActiva->setCellValue('H'. $row, "");
        $hojaActiva->setCellValue('I'. $row, strtoupper($fila["nombres"])."".strtoupper($fila["apellidos"]));
        $hojaActiva->setCellValue('J'. $row, "");
        $hojaActiva->setCellValue('K'. $row, $fila["cantidad_actual"]);
        $hojaActiva->setCellValue('L'. $row, $fila["precio_unidad"]);

        $hojaActiva
        ->getStyle("E".$row)
        ->getAlignment()
        ->setWrapText(true);
        $hojaActiva
        ->getStyle("G".$row)
        ->getAlignment()
        ->setWrapText(true);
        $hojaActiva
        ->getStyle("I".$row)
        ->getAlignment()
        ->setWrapText(true);

        $hojaActiva
        ->getStyle("B".$row)
        ->getAlignment()
        ->setTextRotation(90);

        $row++;
        $final_row++;
    }

    $hojaActiva->mergeCells('B'.$initial_row.':B'.$final_row - 1);
} else {
    $hojaActiva->getRowDimension($row-1)->setRowHeight(0);

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

    $hojaActiva
    ->getStyle('A'.$row.':I'.$row)
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));

    $hojaActiva
    ->getStyle('A'.$row.':I'.$row)
    ->getBorders()
    ->getRight()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));

    $hojaActiva->getStyle('A'.$row)->getFont()->getColor()->setARGB('FFFFFF');

    $hojaActiva
    ->getStyle('A'.$row)
    ->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()
    ->setARGB('d5301b');

    $hojaActiva->setCellValue('A'. $row, 'No se ha encontrado ninguna entrega del material en el rango de fechas y departamento especificado.');   
    $hojaActiva->mergeCells('A'. $row.':I'.$row);
}

// Setting config to dowload the seet to the user's client
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Control de entrega de inventario.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;

?>