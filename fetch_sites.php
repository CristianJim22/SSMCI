<?php
date_default_timezone_set('America/Mexico_City');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start(); 
include_once 'databaseocs.php';
include_once 'page-format/meses.php';
if(isset($_POST['cumplimiento'])){
	$mesActual = date('m');
	$db = new Databaseocs();
	$query = $db->connect()->prepare('SELECT id, unidad, fecha_inicio, fecha_termino, completo FROM sites_periodos WHERE unidad = :identifier AND MONTH(fecha_inicio) = :mesActual AND completo = 1');
	$query->execute(['identifier' => $_SESSION['SSMCI']['unidad'], 'mesActual' => $mesActual]);
	$nrows = $query->rowCount();
 	$row = $query->fetch(PDO::FETCH_ASSOC);

 	$fondo = "";
 	$icono = "";
 	$texto = "";
 	$boton = "";
 	if($nrows>0){
 		$mesesLetra = mesesLetra(date('m'));
 		$fondo = "info-box-icon bg-success elevation-2";
	 	$icono = "fas fa-check";
	 	$texto = "SE CUMPLIÓ CON EL MANTENIMIENTO DE ".$mesesLetra." DE ".date('Y')."<br><button class='btn btn-sm btn-success' onclick='descargar(this.value);' value='".$row['id']."'>Descarga el formato de Seguridad Física y Ambiental</button>";
 	}else{
 		$hoy = date('d');
 		$ultimo = date('t');
 		$restantes = $ultimo - $hoy;
 		$texto = "QUEDAN ".$restantes." DÍAS PARA REGISTRAR EL MANTENIMIENTO DE SU UNIDAD";
 		$boton = "<button class='btn btn-info' id='regMttoSites'>CONTINUAR CON REGISTRO DE MANTENIMIENTO</button>";
 		if($restantes < 7){
 			$fondo = "info-box-icon bg-danger elevation-2";
		 	$icono = "fas fa-exclamation-triangle";
 		}else{
 			$fondo = "info-box-icon bg-warning elevation-2";
	 		$icono = "fas fa-exclamation";
 		}
 
 	}

	$data = array (
	'msgbackground' => $fondo,
	'msgicon' => $icono,
	'msgtext' => $texto,
	'boton' => $boton
	);

	echo json_encode ($data);
	exit;
}

if(isset($_POST['historial'])){
	$output = array();
	$db = new Databaseocs();
	$query = $db->connect()->prepare('SELECT id, unidad, DATE_FORMAT(fecha_inicio, "%d/%m/%Y") AS fecha_inicio, DATE_FORMAT(fecha_termino, "%d/%m/%Y") AS fecha_termino, completo FROM sites_periodos 
	'); 
	$query -> execute();

	foreach ($query as $row) {
		$rutaComprobante = 'dist/comprobantes_sites/' . $row['id'];
		
		if(file_exists($rutaComprobante)){
			$botonImprimir =
			'<a class="btn btn-sm btn-info" href="' . $rutaComprobante . '" target="_blank"><i class="fas fa-file-alt"></i></button>';
		}else{
			$botonImprimir = array('<button class="btn btn-sm btn-danger comprobarSites" data-value="' . $row['id'] . '">CARGAR COMPROBANTE</button>
			<button class="btn btn-sm btn-warning reimprimir" data-value="' . $row['id'] . '"><i class="fas fa-print"></i></button>');			
		}
		$output[] = array(
			$row['unidad'],
			 $row['fecha_inicio'],
			 $row['fecha_termino'] == Null ? 'No ha finalizado' : $row['fecha_termino'],
			 $row['completo'] == 1 ? 'Si' : 'No',
			$botonImprimir
		);
	};
	$data['data'] = $output;
	echo json_encode($data);
}

if (isset($_POST['idEntrega']) &&  $_FILES['archivo']) {
	$output = array();
	$filename = $_FILES['archivo']['name'];
	$tmp_name = $_FILES["archivo"]["tmp_name"];
	$ext = "." . pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);

	$ruta = "dist/comprobantes_sites/";
	$idEntrega = $_POST['idEntrega'];
	$db = new Databaseocs();
	try {
		$pdo = $db->connect();
		$pdo->beginTransaction();
		$pdo->commit();

		if (move_uploaded_file($tmp_name, $ruta . $idEntrega )) {
			$output['state'] = true;
			$output['message'] = 'COMPROBACIÓN REALIZADA CON ÉXITO.';
		} else {
			$pdo->rollback();
			$output['state'] = false;
			$output['message'] = 'OCURRIO UN ERROR AL GUARDAR EL COMPROBANTE. INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR.';
		}
	} catch (PDOException $e) {
		$pdo->rollback();
		$output['state'] = false;
		$output['message'] = 'OCURRIO UN ERROR AL GUARDAR EL COMPROBANTE. INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR.' . $e;
	}
	echo json_encode($output);
}

if(isset($_POST['cantSites'])){
	$output = array();
	$db = new Databaseocs();
	$query = $db->connect()->prepare(
		'SELECT unidad, COUNT(*) AS total_sites,
            SUM(CASE WHEN completo = 1 THEN 1 ELSE 0 END) AS sites_completados
         FROM sites_periodos
         ' . $condicion . ' 
         GROUP BY unidad'
	);
	$query -> execute();

	$output = array();

	while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
		$output[] = array(
			'unidad' => $row['unidad'],
			'total_sites' => $row['total_sites'], 
			'sites_completados' => $row['sites_completados'],
		);
	}

	echo json_encode($output);
	exit;
}
?>