<?php
session_start();
require_once("cn.php");
require '../../recursos/vendor/autoload.php';
$cn = new cn;

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

use PhpOffice\PhpSpreadsheet\{Spreadsheet, IOFactory};
use \PhpOffice\PhpSpreadsheet\Style\{Border, Color, Fill};
$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
use \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

$spreadsheet = new Spreadsheet();

$hojaActiva = $spreadsheet->getActiveSheet();
$hojaActiva->setTitle("Ingreso de materiales");

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
$hojaActiva->getRowDimension(4)->setRowHeight(20);
$hojaActiva->getRowDimension(7)->setRowHeight(20);
$hojaActiva->getRowDimension(8)->setRowHeight(29);
$hojaActiva->getRowDimension(9)->setRowHeight(8);

$hojaActiva->mergeCells('A1:C3');

// Importing ITCA Logo at A1 columns
$drawing->setName('Logo itca');
$drawing->setDescription('Logo de ITCA');
$drawing->setPath('../../img/logo.png'); // put your path and image here
$drawing->setCoordinates('A1');
$drawing->setWidth(200);
$drawing->setOffsetX(1);
$drawing->setOffsetY(1);
$drawing->setWorksheet($spreadsheet->getActiveSheet());

$hojaActiva->getStyle('D1:I3')->getAlignment()->setHorizontal('center');
$hojaActiva->getStyle('D2:I2')->getAlignment()->setVertical('center');
$hojaActiva->getStyle('D3:I3')->getAlignment()->setVertical('top');
$hojaActiva->getStyle('D2:I3')->getFont()->setBold(1);
$hojaActiva->getStyle('D1:I1')->getFont()->setSize(9);
$hojaActiva->getStyle('D2:I3')->getFont()->setSize(14);
$hojaActiva->mergeCells('D1:I1');
$hojaActiva->mergeCells('D2:I2');
$hojaActiva->mergeCells('D3:I3');
$hojaActiva->setCellValue('D1', date('d M Y'));
$hojaActiva->setCellValue('D2', 'INGRESO DE MATERIALES Y SUMINISTRO EN DEPOSITO BODEGA REGIONAL');   
$hojaActiva->setCellValue('D3', 'SANTA ANA');

$hojaActiva->getStyle('A5:B6')->getFont()->setBold(1);
$hojaActiva->mergeCells('A5:B5');
$hojaActiva->mergeCells('A6:B6');
$hojaActiva->setCellValue('A5', 'CENTRO DE COSTO : ');
$hojaActiva->setCellValue('A6', 'DEPARTAMENTO : ');
$hojaActiva->mergeCells('C5:D5');
$hojaActiva->mergeCells('C6:D6');
$hojaActiva->setCellValue('C5', '4002 - EDUCACION TECNICA REGIONAL SANTA ANA');
$hojaActiva->setCellValue('C6', strtoupper($_SESSION["depto_column"]["id"].' - '.$_SESSION["depto_column"]["depto"]));

$hojaActiva->getStyle('F6')->getFont()->setBold(1);
$hojaActiva->getStyle('F6')->getAlignment()->setHorizontal('right');
$hojaActiva->setCellValue('F6', 'TIPO DOCUMENTO : ');
$hojaActiva->setCellValue('G6', 'CREDITO FISCAL');

$hojaActiva->getStyle('H6')->getFont()->setBold(1);
$hojaActiva->getStyle('H6')->getAlignment()->setHorizontal('right');
$hojaActiva->setCellValue('H6', 'FECHA :');
$hojaActiva->setCellValue('I6', ' '.date_format($date, 'd/m/Y'));


// Border from A8 to I8 columns
$hojaActiva
    ->getStyle('A8:I8')
    ->getBorders()
    ->getBottom()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('A8:I8')
    ->getBorders()
    ->getTop()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('A8')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('B8')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('C8')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('D8')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('E8')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('F8')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('G8')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('H8')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('I8')
    ->getBorders()
    ->getLeft()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));
$hojaActiva
    ->getStyle('I8')
    ->getBorders()
    ->getRight()
    ->setBorderStyle(Border::BORDER_THIN)
    ->setColor(new Color('000'));

// Setting columns from A to H to auto-size themselves
$hojaActiva->getColumnDimension('A')->setAutoSize(true);
$hojaActiva->getColumnDimension('B')->setAutoSize(true);
$hojaActiva->getColumnDimension('C')->setAutoSize(true);
$hojaActiva->getColumnDimension('D')->setWidth(33);
$hojaActiva->getColumnDimension('E')->setAutoSize(true);
$hojaActiva->getColumnDimension('F')->setWidth(30);
$hojaActiva->getColumnDimension('G')->setAutoSize(true);
$hojaActiva->getColumnDimension('H')->setAutoSize(true);
$hojaActiva->getColumnDimension('I')->setAutoSize(true);

// $hojaActiva->mergeCells('A5:C5');
// $hojaActiva->mergeCells('D5:I5');
// $hojaActiva->setCellValue('A5', 'Realizado por');
// $hojaActiva->setCellValue('D5', 'Datos del prestamo');

$hojaActiva->getStyle('A8:I8')->getAlignment()->setHorizontal('center');
$hojaActiva->getStyle('A8:I8')->getAlignment()->setVertical('center');
$hojaActiva->getStyle('A8:I8')->getFont()->setBold(1);
$hojaActiva->mergeCells('A8:B8');
$hojaActiva->mergeCells('D8:G8');
$hojaActiva->setCellValue('A8', 'CODIGO');
$hojaActiva->setCellValue('C8', 'UNIDAD');
$hojaActiva->setCellValue('D8', 'DESCRIPCION');
$hojaActiva->setCellValue('H8', 'CANTIDAD');
$hojaActiva->setCellValue('I8', 'DEPARTAMENTO');

$row = 10;

if(mysqli_num_rows($result) >= 1) {
    foreach($result as $fila) {
        $hojaActiva->mergeCells("A".$row.":"."B".$row);
        $hojaActiva->mergeCells('D'.$row.':'.'G'.$row);
        $hojaActiva->getStyle('A'.$row.':'.'C'.$row)->getAlignment()->setHorizontal('center');
        $hojaActiva->getStyle('D'.$row)->getAlignment()->setHorizontal('left');
        $hojaActiva->getStyle('H'.$row.':'.'I'.$row)->getAlignment()->setHorizontal('center');
        $hojaActiva->getStyle('A'.$row.':'.'I'.$row)->getAlignment()->setVertical('center');

        $hojaActiva->setCellValue('A'. $row, $fila["codigo_material"]);
        $hojaActiva->setCellValue('C'. $row, strtoupper($fila["unidad"]));
        $hojaActiva->setCellValue('D'. $row, strtoupper($fila["descripcion"]));
        $hojaActiva->setCellValue('H'. $row, $fila["cantidad"]);
        $hojaActiva->setCellValue('I'. $row, $fila["codigo_departamento"]);

        $row++;
    }
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

    $hojaActiva->setCellValue('A'. $row, 'No se ha encontrado ningun ingreso de material en la fecha y departamento especificados.');   
    $hojaActiva->mergeCells('A'. $row.':I'.$row);
}

// Setting config to dowload the seet to the user's client
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Ingreso de materiales.xlsx"');
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;

?>