<?php
set_time_limit(5000);
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
    $desde = $_POST['desde'] ?? null;
    $hasta = $_POST['hasta'] ?? null;

    //Query 1
    $queryP1 = "
        SELECT MU.nombre AS MUnombre, TC.nombre, TE.fecha, TE.cantidad
        FROM toner_entradas TE
        LEFT JOIN mtto_unidades MU ON TE.unidad = MU.id
        LEFT JOIN toner_catalogo TC ON TE.tipo = TC.id
        WHERE TC.nombre = 'TÓNER'
        ";
    if($desde && $hasta) {
        $queryP1 .= "AND TE.fecha BETWEEN :desde AND :hasta";
    }
    $query = $db->connect()->prepare($queryP1);
    if($desde && $hasta) {
        $query->bindParam(':desde', $desde);
        $query->bindParam(':hasta', $hasta);
    }
    $query->execute();
    $rows = $query->fetchAll(PDO::FETCH_ASSOC);

    //Query 2
    $queryP2 = "
        SELECT MU.nombre AS MUnombre, TC.nombre, TE.fecha, TE.cantidad
        FROM toner_entradas TE
        LEFT JOIN mtto_unidades MU ON TE.unidad = MU.id
        LEFT JOIN toner_catalogo TC ON TE.tipo = TC.id
        WHERE TC.nombre = 'TAMBOR'
        ";
    if($desde && $hasta) {
        $queryP2 .= "AND TE.fecha BETWEEN :desde AND :hasta";
    }
    $query = $db->connect()->prepare($queryP2);
    if($desde && $hasta) {
        $query->bindParam(':desde', $desde);
        $query->bindParam(':hasta', $hasta);
    }
    $query->execute();
    $rows2 = $query->fetchAll(PDO::FETCH_ASSOC);

    //Query 3
    $queryP3 = "
        SELECT MU.nombre AS tec_unidad,
        SUM(CASE WHEN TE.tipo = 1 THEN TE.cantidad ELSE 0 END) AS tec_total_toners,
        SUM(CASE WHEN TE.tipo = 2 THEN TE.cantidad ELSE 0 END) AS tec_total_tambores
        FROM toner_entradas TE
        LEFT JOIN mtto_unidades MU ON TE.unidad = MU.id
        LEFT JOIN toner_catalogo TC ON TE.tipo = TC.id
    ";
    if ($desde && $hasta) {
        $queryP3 .= "WHERE TE.fecha BETWEEN :desde AND :hasta";
    }
    $queryP3 .= " GROUP BY MU.nombre";
    $query = $db->connect()->prepare($queryP3);
    if ($desde && $hasta) {
        $query->bindParam(':desde', $desde);
        $query->bindParam(':hasta', $hasta);
    }
    $query->execute();
    $rows3 = $query->fetchAll(PDO::FETCH_ASSOC);

    //Query 4
    $queryP4 = "
        SELECT MU.nombre AS ts_unidad, TC.nombre AS ts_nombre, TS.fecha AS ts_fecha, TS.cantidad AS ts_cantidad
        FROM toner_salidas TS
        LEFT JOIN mtto_unidades MU ON TS.unidad = MU.id
        LEFT JOIN toner_catalogo TC ON TS.tipo = TC.id
        WHERE TC.nombre = 'TÓNER'
        ";
    if($desde && $hasta) {
        $queryP4 .= "AND TS.fecha BETWEEN :desde AND :hasta";
    }
    $query = $db->connect()->prepare($queryP4);
    if($desde && $hasta) {
        $query->bindParam(':desde', $desde);
        $query->bindParam(':hasta', $hasta);
    }
    $query->execute();
    $rows4 = $query->fetchAll(PDO::FETCH_ASSOC);

    //Query 5
    $queryP5 = "
        SELECT MU.nombre AS ts_unidad2, TC.nombre AS ts_nombre2, TS.fecha AS ts_fecha2, TS.cantidad AS ts_cantidad2
        FROM toner_salidas TS
        LEFT JOIN mtto_unidades MU ON TS.unidad = MU.id
        LEFT JOIN toner_catalogo TC ON TS.tipo = TC.id
        WHERE TC.nombre = 'TAMBOR'
        ";
    if($desde && $hasta) {
        $queryP5 .= "AND TS.fecha BETWEEN :desde AND :hasta";
    }
    $query = $db->connect()->prepare($queryP5);
    if($desde && $hasta) {
        $query->bindParam(':desde', $desde);
        $query->bindParam(':hasta', $hasta);
    }
    $query->execute();
    $rows5 = $query->fetchAll(PDO::FETCH_ASSOC);

    //Query 6
    $queryP6 = "
        SELECT MU.nombre AS tsc_unidad, 
        SUM(CASE WHEN TS.tipo = 1 THEN TS.cantidad ELSE 0 END) AS tsc_total_toners,
        SUM(CASE WHEN TS.tipo = 2 THEN TS.cantidad ELSE 0 END) AS tsc_total_tambores
        FROM toner_salidas TS
        LEFT JOIN mtto_unidades MU ON TS.unidad = MU.id
        LEFT JOIN toner_catalogo TC ON TS.tipo = TC.id
    ";
    if ($desde && $hasta) {
        $queryP6 .= "WHERE TS.fecha BETWEEN :desde AND :hasta";
    }
    $queryP6 .= " GROUP BY MU.nombre";
    $query = $db->connect()->prepare($queryP6);
    if ($desde && $hasta) {
        $query->bindParam(':desde', $desde);
        $query->bindParam(':hasta', $hasta);
    }
    $query->execute();
    $rows6 = $query->fetchAll(PDO::FETCH_ASSOC);

    $spreadsheet = new Spreadsheet();

    // Primera hoja: Reporte de consumo en entradas de Tóners
    $spreadsheet->setActiveSheetIndex(0);
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Reporte de entrada de Tóners');

    $headers = [
        'A1' => 'Nombre de la unidad',
        'B1' => 'Tipo',
        'C1' => 'Fecha',
        'D1' => 'Cantidad'
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
    $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);
    $sheet->getStyle('A2:D' . (count($rows) + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('A2:D' . (count($rows) + 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    foreach (range('A', 'D') as $columnID) {
        if ($columnID !== 'C' && $columnID !== 'E') {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }

    $sheet->getColumnDimension('A')->setWidth(20);
    $sheet->getStyle('A')->getAlignment()->setWrapText(true);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getStyle('C')->getAlignment()->setWrapText(true);
    $row = 2;

    foreach ($rows as $dato) {
        $sheet->setCellValue('A' . $row, $dato['MUnombre'] );
        $sheet->setCellValue('B' . $row, $dato['nombre'] );
        $sheet->setCellValue('C' . $row, $dato['fecha'] );
        $sheet->setCellValue('D' . $row, $dato['cantidad'] );
        $row++;
    }

    //Segunda Hoja: Reporte de consumo en entradas de Tambores
    $sheet2 = $spreadsheet->createSheet();
    $sheet2->setTitle('Reporte de entradas de Tambores');
    $sheet2->setCellValue('A1', 'Nombre de la unidad');
    $sheet2->setCellValue('B1', 'Tipo');
    $sheet2->setCellValue('C1', 'Fecha');
    $sheet2->setCellValue('D1', 'Cantidad');

    $sheet2->getStyle('A1:D1')->applyFromArray($headerStyle);
    $sheet2->getStyle('A2:D' . (count($rows2) + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet2->getStyle('A2:D' . (count($rows2) + 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet2->getColumnDimension('A')->setAutoSize(true);
    $sheet2->getColumnDimension('B')->setAutoSize(true);
    $sheet2->getColumnDimension('C')->setAutoSize(true);
    $sheet2->getColumnDimension('D')->setAutoSize(true);
    $row2 = 2;

    foreach ($rows2 as $dato) {
        $sheet2->setCellValue('A' . $row2, $dato['MUnombre']);
        $sheet2->setCellValue('B' . $row2, $dato['nombre']);
        $sheet2->setCellValue('C' . $row2, $dato['fecha']);
        $sheet2->setCellValue('D' . $row2, $dato['cantidad']);
        $row2++;
    }

    //Tercera Hoja: Reporte de consumo en entradas totales
    $sheet3 = $spreadsheet->createSheet();
    $sheet3->setTitle('Reporte de entradas total');
    $sheet3->setCellValue('A1', 'Nombre de la unidad');
    $sheet3->setCellValue('B1', 'Total de toners');
    $sheet3->setCellValue('C1', 'Total de tambores');

    $sheet3->getStyle('A1:C1')->applyFromArray($headerStyle);
    $sheet3->getStyle('A2:C' . (count($rows3) + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet3->getStyle('A2:C' . (count($rows3) + 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
    
    $sheet3->getColumnDimension('A')->setAutoSize(true);
    $sheet3->getColumnDimension('B')->setAutoSize(true);
    $sheet3->getColumnDimension('C')->setAutoSize(true);
    $row3 = 2;

    foreach ($rows3 as $dato) {
        $sheet3->setCellValue('A' . $row3, $dato['tec_unidad']);
        $sheet3->setCellValue('B' . $row3, $dato['tec_total_toners']);
        $sheet3->setCellValue('C' . $row3, $dato['tec_total_tambores']);
        $row3++;
    }

    //Cuarta hoja: Reporte de consumo en salidas de Tóners
    $sheet4 = $spreadsheet->createSheet();
    $sheet4->setTitle('Reporte de salidas de Tóners');

    $sheet4->setCellValue('A1', 'Nombre de la unidad');
    $sheet4->setCellValue('B1', 'Tipo');
    $sheet4->setCellValue('C1', 'Fecha');
    $sheet4->setCellValue('D1', 'Cantidad');
   
    $sheet4->getStyle('A1:D1')->applyFromArray($headerStyle);
    $sheet4->getStyle('A2:D' . (count($rows4) + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet4->getStyle('A2:D' . (count($rows4) + 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    foreach (range('A', 'D') as $columnID) {
        if ($columnID !== 'C' && $columnID !== 'E') {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    }

    $sheet4->getColumnDimension('A')->setWidth(50);
    $sheet4->getStyle('A')->getAlignment()->setWrapText(true);
    $sheet4->getColumnDimension('C')->setWidth(20);
    $sheet4->getStyle('C')->getAlignment()->setWrapText(true);
    $row4 = 2;
    
    foreach ($rows4 as $dato) {
        $sheet4->setCellValue('A' . $row4, $dato['ts_unidad']);
        $sheet4->setCellValue('B' . $row4, $dato['ts_nombre']);
        $sheet4->setCellValue('C' . $row4, $dato['ts_fecha']);
        $sheet4->setCellValue('D' . $row4, $dato['ts_cantidad']);
        $row4++;
    }

    //Quinta hoja: Reporte de consumo en salidas de tambores
    $sheet5 = $spreadsheet->createSheet();
    $sheet5->setTitle('Reporte de salidas de Tambores');
    $sheet5->setCellValue('A1', 'Nombre de la unidad');
    $sheet5->setCellValue('B1', 'Tipo');
    $sheet5->setCellValue('C1', 'Fecha');
    $sheet5->setCellValue('D1', 'Cantidad');

    $sheet5->getStyle('A1:D1')->applyFromArray($headerStyle);
    $sheet5->getStyle('A2:D' . (count($rows5) + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet5->getStyle('A2:D' . (count($rows5) + 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet5->getColumnDimension('A')->setAutoSize(true);
    $sheet5->getColumnDimension('B')->setAutoSize(true);
    $sheet5->getColumnDimension('C')->setAutoSize(true);
    $sheet5->getColumnDimension('D')->setAutoSize(true);
    $row5 = 2;

    foreach ($rows5 as $dato) {
        $sheet5->setCellValue('A' . $row5, $dato['ts_unidad2']);
        $sheet5->setCellValue('B' . $row5, $dato['ts_nombre2']);
        $sheet5->setCellValue('C' . $row5, $dato['ts_fecha2']);
        $sheet5->setCellValue('D' . $row5, $dato['ts_cantidad2']);
        $row5++;
    }

    //Sexta hoja: Reporte de consumo en salidas totales
    $sheet6 = $spreadsheet->createSheet();
    $sheet6->setTitle('Reporte de salidas total');
    $sheet6->setCellValue('A1', 'Nombre de la unidad');
    $sheet6->setCellValue('B1', 'Total de toners');
    $sheet6->setCellValue('C1', 'Total de tambores');

    $sheet6->getStyle('A1:C1')->applyFromArray($headerStyle);
    $sheet6->getStyle('A2:C' . (count($rows6) + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet6->getStyle('A2:C' . (count($rows6) + 1))->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

    $sheet6->getColumnDimension('A')->setAutoSize(true);
    $sheet6->getColumnDimension('B')->setAutoSize(true);
    $sheet6->getColumnDimension('C')->setAutoSize(true);
    $row6 = 2;

    foreach ($rows6 as $dato) {
        $sheet6->setCellValue('A' . $row6, $dato['tsc_unidad']);
        $sheet6->setCellValue('B' . $row6, $dato['tsc_total_toners']);
        $sheet6->setCellValue('C' . $row6, $dato['tsc_total_tambores']);
        $row6++;
    }

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_consumo.xlsx"');
    header('Cache-Control: max-age=0');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
