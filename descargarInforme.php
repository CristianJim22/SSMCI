<?php
set_time_limit(500000000000);
ini_set('memory_limit', '250M');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

require_once 'vendor/autoload.php';
include_once 'databaseocs.php';

$db = new Databaseocs();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $desde = $_POST['desde'];
    $hasta = $_POST['hasta'];
    $idPeriodo = $_POST['idPeriodo'];

    $tableMtto = "mtto" . $idPeriodo;

    if (empty($desde) || empty($hasta)) {
        exit("Error: Las fechas de filtro son requeridas.");
    }

    $query = $db->connect()->prepare("
        SELECT M1.id, M1.nserie, M1.macadd, M1.ip, M1.observaciones, M1.fecha, MU.nombre, MU.unidad, MU.nombre
        FROM $tableMtto M1
        LEFT JOIN mtto_usuarios MU ON (M1.hecho_por = MU.username COLLATE utf8_spanish_ci)
        WHERE M1.completo = 1 AND M1.fecha BETWEEN :desde AND :hasta");
    $query->bindParam(':desde', $desde);
    $query->bindParam(':hasta', $hasta);
    $query->execute();
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);

    $queryCount = $db->connect()->prepare("
        SELECT MU.nombre, COUNT(M1.id) AS total_mantenimientos
        FROM $tableMtto M1
        LEFT JOIN mtto_usuarios MU ON (M1.hecho_por = MU.username COLLATE utf8_spanish_ci)
        WHERE M1.completo = 1 AND M1.fecha BETWEEN :desde AND :hasta
        GROUP BY MU.nombre");
    $queryCount->bindParam(':desde', $desde);
    $queryCount->bindParam(':hasta', $hasta);
    $queryCount->execute();
    $countRows = $queryCount->fetchAll(PDO::FETCH_ASSOC);

    $queryCount2 = $db->connect()->prepare("
        SELECT MA.adscripcion, COUNT(M1.unidad) AS total_unidad
        FROM $tableMtto M1
        LEFT JOIN mtto_usuarios MU ON (M1.hecho_por = MU.username COLLATE utf8_spanish_ci)
        LEFT JOIN mtto_areas MA ON MU.unidad = MA.id
        WHERE M1.completo = 1 AND M1.fecha BETWEEN :desde AND :hasta
        GROUP BY MA.adscripcion");
    $queryCount2->bindParam(':desde', $desde);
    $queryCount2->bindParam(':hasta', $hasta);
    $queryCount2->execute();
    $countRows2 = $queryCount2->fetchAll(PDO::FETCH_ASSOC);
    
    $spreadsheet = new Spreadsheet();

    // Primera hoja: Informe de Productividad
    $spreadsheet->setActiveSheetIndex(0);
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Informe de Productividad');

    $headers = [
        'A1' => 'Id',
        'B1' => 'Numero de serie',
        'C1' => 'Macadd',
        'D1' => 'Ip',
        'E1' => 'Observaciones',
        'F1' => 'Fecha',
        'G1' => 'Hecho por',
    ];
    foreach ($headers as $cell => $value) {
        $sheet->setCellValue($cell, $value);
    }

    $headerStyle = [
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => '056800']
        ],
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF']
        ],
        'alignment' => [
            'horizontal' => Alignment::HORIZONTAL_CENTER,
            'vertical' => Alignment::VERTICAL_CENTER
        ]
    ];
    $sheet->getStyle('A1:G1')->applyFromArray($headerStyle);
    $sheet->getStyle('A2:G' . (count($rows) + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A2:G' . (count($rows) + 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    foreach (range('A', 'G') as $columnID) {
        if ($columnID !== 'C' && $columnID !== 'E') {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }

    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getStyle('C')->getAlignment()->setWrapText(true);
    $sheet->getColumnDimension('E')->setWidth(50);
    $sheet->getStyle('E')->getAlignment()->setWrapText(true);

    $row = 2;
    foreach ($rows as $dato) {
        $sheet->setCellValue('A' . $row, $dato['id']);
        $sheet->setCellValue('B' . $row, $dato['nserie']);
        $sheet->setCellValue('C' . $row, $dato['macadd']);
        $sheet->setCellValue('D' . $row, $dato['ip']);
        $sheet->setCellValue('E' . $row, $dato['observaciones']);
        $sheet->setCellValue('F' . $row, $dato['fecha']);
        $sheet->setCellValue('G' . $row, $dato['nombre']);
        $row++;
    }

    // Segunda hoja: Mantenimientos realizados
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('Mantenimientos realizados');

    $sheet2->setCellValue('A1', 'Nombre');
    $sheet2->setCellValue('B1', 'Total de mantenimientos');
    $sheet2->getStyle('A1:B1')->applyFromArray($headerStyle);

    $sheet2->getStyle('A2:B' . (count($countRows) + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet2->getStyle('A2:B' . (count($countRows) + 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet2->getColumnDimension('A')->setAutoSize(true);
    $sheet2->getColumnDimension('B')->setAutoSize(true);

    $row2 = 2;
    foreach ($countRows as $dato) {
        $sheet2->setCellValue('A' . $row2, $dato['nombre']);
        $sheet2->setCellValue('B' . $row2, $dato['total_mantenimientos']);
        $row2++;
    }

    // Tercera hoja: Mantenimientos por unidad.
    $sheet3 = $spreadsheet->createSheet();
    $sheet3->setTitle('Mantenimientos por unidad');

    $sheet3->setCellValue('A1', 'Area');
    $sheet3->setCellValue('B1', 'Total de mantenimientos');
    $sheet3->getStyle('A1:B1')->applyFromArray($headerStyle);

    $sheet3->getStyle('A2:B' . (count($countRows2) + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet3->getStyle('A2:B' . (count($countRows2) + 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    $sheet3->getColumnDimension('A')->setAutoSize(true);
    $sheet3->getColumnDimension('B')->setAutoSize(true);

    $row3 = 2;
    foreach ($countRows2 as $dato) {
        $sheet3->setCellValue('A' . $row3, $dato['adscripcion']);
        $sheet3->setCellValue('B' . $row3, $dato['total_unidad']);
        $row3++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="informe_productividad.xlsx"');
    header('Cache-Control: max-age=0');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
