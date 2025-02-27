<?php
session_start();
include 'databaseocs.php';

$data = [];
$outputByMonth = [];
$yearFilter = isset($_POST['year']) ? $_POST['year'] : null;

try {
    $db = new Databaseocs();
    $queryStr = 'SELECT MU.nombre AS unidad, fecha_inicio, completo FROM sites_periodos SP 
                 LEFT JOIN mtto_unidades MU ON SP.unidad = MU.id';

    if ($yearFilter) {
        $queryStr .= ' WHERE YEAR(fecha_inicio) = :year';
    }
    $query = $db->connect()->prepare($queryStr);

    if ($yearFilter) {
        $query->bindParam(':year', $yearFilter, PDO::PARAM_INT);
    }
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $row) {
        $unidad = $row['unidad'];
        $fecha = $row['fecha_inicio'];
        $completo = $row['completo'] == 1 ? 'SI' : 'NO';
        $mes = date('n', strtotime($fecha));

        if (!isset($outputByMonth[$unidad])) {
            $outputByMonth[$unidad] = [
                'unidad' => $unidad,
                'ene' => '',
                'feb' => '',
                'mar' => '',
                'abr' => '',
                'may' => '',
                'jun' => '',
                'jul' => '',
                'ago' => '',
                'sep' => '',
                'oct' => '',
                'nov' => '',
                'dic' => '',
            ];
        }

        switch ($mes) {
            case 1:
                $outputByMonth[$unidad]['ene'] = $completo;
                break;
            case 2:
                $outputByMonth[$unidad]['feb'] = $completo;
                break;
            case 3:
                $outputByMonth[$unidad]['mar'] = $completo;
                break;
            case 4:
                $outputByMonth[$unidad]['abr'] = $completo;
                break;
            case 5:
                $outputByMonth[$unidad]['may'] = $completo;
                break;
            case 6:
                $outputByMonth[$unidad]['jun'] = $completo;
                break;
            case 7:
                $outputByMonth[$unidad]['jul'] = $completo;
                break;
            case 8:
                $outputByMonth[$unidad]['ago'] = $completo;
                break;
            case 9:
                $outputByMonth[$unidad]['sep'] = $completo;
                break;
            case 10:
                $outputByMonth[$unidad]['oct'] = $completo;
                break;
            case 11:
                $outputByMonth[$unidad]['nov'] = $completo;
                break;
            case 12:
                $outputByMonth[$unidad]['dic'] = $completo;
                break;
        }
    }

    $data['state'] = true;
    $data['data'] = array_values($outputByMonth);
} catch (PDOException $e) {
    $data["state"] = false;
    $data['message'] = $e->getMessage();
}

header('Content-type: application/json');
echo json_encode($data);
