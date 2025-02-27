<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start(); 
include_once 'databaseocs.php';
if(isset($_POST['cantInsumos'])){
	$output = array();
	$db = new Databaseocs();

	$condicion = '';
	if($_SESSION['SSMCI']['rol'] == 2 || $_SESSION['SSMCI']['rol'] == 3){
		$condicion = ' WHERE unidad = '.$_SESSION['SSMCI']['unidad']. ' ';
	}

	$query = $db->connect()->prepare('select unidad, sum(toners), sum(tambores) FROM (
	    select unidad,
	        IFNULL((case when tipo = 1 then sum(cant) end),0) as toners, 
	        IFNULL((case when tipo = 2 then sum(cant) end),0) as tambores
	    FROM
	    (select sum(cantidad) as cant, tipo, unidad
	          from toner_entradas
	          group by tipo, unidad
	        union all
	         select sum(cantidad*-1) as cant, tipo, unidad
	         from toner_salidas
	         group by tipo, unidad) tbl 
	    group by unidad, tipo 
	    ) tf '.$condicion.' 
	GROUP BY unidad');
	$query->execute();
	$row = $query->fetch(PDO::FETCH_NUM);

	$data = array (
	'canttoner' => $row[1],
	'canttamb' => $row[2]
	);

	echo json_encode ($data);
	exit;
}

if(isset($_POST['registrarInsumo'])){ 
	$data = $_POST['registrarInsumo'];
	$output = array();
	$db = new Databaseocs();
	$today = date('Y-m-d');
	try{
		$unidad = $_SESSION['SSMCI']['unidad'];
		$pdo = $db->connect();
		$pdo->beginTransaction();
		$pdo->exec("INSERT INTO toner_entradas (tipo, unidad, fecha, cantidad, observaciones, fecha_recibido) VALUES (".$data['tipoInsumo'].", ".$unidad.", '".$today."', ".$data['cantidad'].", '".$data['observacionesInsumo']."', '".$data['fechaRecibido']."')");
		$pdo->commit();
		$output['state'] = true;
	   	$output['message'] = 'INSUMO REGISTRADO CON ÉXITO.';
	} catch (PDOException $e) {
		$pdo->rollback();
		$output['state'] = false; 
		$output['message'] = 'OCURRIO UN ERROR AL REGISTRAR EL INSUMO. INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR.'.$e;
	}
	echo json_encode($output);
}

if(isset($_POST['asignarInsumo'])){ 
	$data = $_POST['asignarInsumo'];
	$output = array();
	$db = new Databaseocs();
	try{
		$unidad = $_SESSION['SSMCI']['unidad'];
		$pdo = $db->connect();
	    $pdo->beginTransaction();
	    $sql = $pdo->prepare("SELECT (ent.entradas - sal.salidas) existencias FROM 
	        (SELECT IFNULL(SUM(cantidad),0) entradas from toner_entradas WHERE unidad = ".$unidad." AND tipo = ".$data['AtipoInsumo'].") AS ent,
	        (SELECT IFNULL(SUM(cantidad),0) salidas from toner_salidas WHERE unidad = ".$unidad." AND tipo = ".$data['AtipoInsumo'].") AS sal");
	    $sql->execute();
	    $row = $sql->fetch(PDO::FETCH_NUM);
	    $today = date('Y-m-d');
	    if($row[0]>=$data['Acantidad']){
	    	$pdo->exec("INSERT INTO toner_salidas (tipo, unidad, fecha, cantidad, observaciones, destino, fecha_asignado, comprobante, destinootro) VALUES (".$data['AtipoInsumo'].", ".$unidad.", '".$today."', ".$data['Acantidad'].", '".$data['AobservacionesInsumo']."', '".$data['Adestino']."', '".$data['AfechaAsignado']."',0, '".$data['destinoOtro']."')");
	    	$last_insert_id = $pdo->lastInsertId();
	    	$output['message'] = 'INSUMO ASIGNADO CON ÉXITO.';
	    	$output['idAsignado'] = $last_insert_id;
	    	$output['state'] = true;
	    }else{
	    	$output['state'] = false; 
	    	$output['message'] = 'NO HAY SUFICIENTES EXISTENCIAS, INGRESE UNA CANTIDAD QUE SE PUEDA ASIGNAR.';
	    }
	    $pdo->commit();
	    
	} catch (PDOException $e) {
		$pdo->rollback();
		$output['state'] = false; 
		$output['message'] = 'OCURRIO UN ERROR AL ASIGNAR EL INSUMO. INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR.'.$e;
	}
	echo json_encode($output);
}

if(isset($_POST['listarMovimientos'])){ 
	$toggle = array('<button class="btn btn-sm btn-danger baja"><i class="bi bi-toggle2-off"></i></button>','<button class="btn btn-sm btn-success baja"><i class="bi bi-toggle2-on"></i></button>');
	$condicionE = '';
	$condicionS = '';
	if($_SESSION['SSMCI']['rol'] == 2 || $_SESSION['SSMCI']['rol'] == 3){
		$condicionE = ' WHERE E.unidad = '.$_SESSION['SSMCI']['unidad']. ' ';
		$condicionS = ' WHERE S.unidad = '.$_SESSION['SSMCI']['unidad']. ' ';
	}

	$output = array();
	$db = new Databaseocs();
	$queryE = $db->connect()->prepare('SELECT C.nombre insumo, E.cantidad, U.nombre, DATE_FORMAT(E.fecha_recibido,"%d/%m/%Y") fecha_recibido, E.observaciones,  DATE_FORMAT(E.fecha,"%d/%m/%Y") fecha FROM toner_entradas E 
	LEFT JOIN toner_catalogo C ON E.tipo = C.id
	LEFT JOIN mtto_unidades U ON E.unidad = U.id '.$condicionE.'
	');
	$queryE->execute();
	foreach ($queryE as $row ) {
		$output[] = array($row['insumo'], $row['cantidad'], $row['nombre'], $row['fecha_recibido'], $row['observaciones'], $row['fecha'], "");
	}
	
	$queryS = $db->connect()->prepare('SELECT S.id id,C.nombre insumo, (S.cantidad * -1) cantidad, U.nombre, DATE_FORMAT(S.fecha_asignado,"%d/%m/%Y") fecha_asignado, CONCAT("ENTREGADO A: ", A.adscripcion, " ", S.destinootro," ",S.observaciones) observaciones, DATE_FORMAT(S.fecha,"%d/%m/%Y") fecha, IF(S.comprobante IS NULL, 0, S.comprobante) comprobante FROM toner_salidas S 
		LEFT JOIN toner_catalogo C ON S.tipo =  C.id
		LEFT JOIN mtto_areas A ON S.destino = A.id
		LEFT JOIN mtto_unidades U ON S.unidad = U.id '.$condicionS.'
	');
	$queryS->execute();
	foreach ($queryS as $row ) {
		$toggle = "";
		if($row['comprobante'] == "0"){
			$toggle = '<button class="btn btn-sm btn-danger comprobar" data-value="'.$row['id'].'">CARGAR COMPROBANTE</button>
			<button class="btn btn-sm btn-warning reimprimir" data-value="'.$row['id'].'"><i class="fas fa-print"></i></button>
			';
		}else{
			$toggle = '<a class="btn btn-sm btn-info" href="dist/comprobantes_toners/'.$row['comprobante'].'" target="_blank"><i class="fas fa-file-alt"></i></button>';
		}
		$output[] = array($row['insumo'], $row['cantidad'], $row['nombre'], $row['fecha_asignado'], $row['observaciones'], $row['fecha'], $toggle);
	}

	$data['data'] = $output;
	echo json_encode($data);
}

if(isset($_POST['idEntrega']) &&  $_FILES['archivo']){ 
	$output = array();
	$filename = $_FILES['archivo']['name'];
	$tmp_name = $_FILES["archivo"]["tmp_name"];
	$ext = ".".pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION);

	$ruta = "dist/comprobantes_toners/";
	$idEntrega = $_POST['idEntrega'];
	$db = new Databaseocs();
	try{
		$pdo = $db->connect();
		$pdo->beginTransaction();
		$pdo->exec("UPDATE toner_salidas SET comprobante = '".$idEntrega.$ext."' WHERE id = ".$idEntrega);
		$pdo->commit();
		
	   	if (move_uploaded_file($tmp_name, $ruta.$idEntrega.$ext)) {
			$output['state'] = true;
	   		$output['message'] = 'COMPROBACIÓN REALIZADA CON ÉXITO.';
		}else{
			$pdo->rollback();
			$output['state'] = false;
	   		$output['message'] = 'OCURRIO UN ERROR AL GUARDAR EL COMPROBANTE. INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR.';
		}
	} catch (PDOException $e) {
		$pdo->rollback();
		$output['state'] = false; 
		$output['message'] = 'OCURRIO UN ERROR AL GUARDAR EL COMPROBANTE. INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR.'.$e;
	}
	echo json_encode($output);
}


/*Administrador*/
if (isset($_POST['tableInsumos'])) {
	$existencias = array();
	$consumidos = array();
	//Existencias
	$Eunidades = array();
	$Etoners = array();
	$Etambores = array();
	//Consumidos
	$Cunidades = array();
	$Ctoners = array();
	$Ctambores = array();

	$desde = $_POST['desde'] ?? null;
	$hasta = $_POST['hasta'] ?? null;
	$desde2 = $_POST['desde'] ?? null;
	$hasta2 = $_POST['hasta'] ?? null;

	$db = new Databaseocs();

	$result = '<table class="table table-sm table-bordered table-hover font-15">
	<thead>
		<tr>
			<th class="col-3">UNIDAD</th>
			<th class="col-3">TONERS EN EXISTENCIA</th>
			<th class="col-3">TAMBORES EN EXISTENCIA</th>
		</thead><tbody>';
	//existencias

	//echo json_encode($desde);
	//echo json_encode($hasta);

	$queryEx = '
	SELECT u.nombre, tf.unidad, IFNULL(sum(toners),0), IFNULL(sum(tambores),0) 
	FROM mtto_unidades  u
	LEFT JOIN 
        (select unidad,
	        IFNULL((case when tipo = 1 then sum(cant) end),0) as toners, 
	        IFNULL((case when tipo = 2 then sum(cant) end),0) as tambores
	    FROM
	    (select sum(cantidad) as cant, tipo, unidad
	          from toner_entradas te
			  ';
	if ($desde && $hasta) {
		$queryEx .= ' WHERE te.fecha BETWEEN "'.$desde.'" AND "'.$hasta.'" ';
	}
	$queryEx .= '
	          group by tipo, unidad
	        union all
	         select sum(cantidad*-1) as cant, tipo, unidad
	         from toner_salidas ts
			 ';
	if ($desde2 && $hasta2) {
		$queryEx .= ' WHERE ts.fecha BETWEEN "'.$desde2.'" AND "'.$hasta2.'" ';
	}
	$queryEx .= '
	         group by tipo, unidad) tbl 
	    group by unidad, tipo 
	    ) tf 
        ON u.id = tf.unidad
	GROUP BY u.unidad';
	$query = $db->connect()->prepare($queryEx);
	
	$query->execute();

	foreach ($query as $row) {
		$result .= '<tr>';
		$result .= '<td>' . $row[0] . '</td>';
		$result .= '<td>' . $row[2] . '</td>';
		$result .= '<td>' . $row[3] . '</td>';
		$result .= '</tr>';
		$Eunidades[] = $row[0];
		$Etoners[] = $row[2];
		$Etambores[] = $row[3];
	}
	$result .= '</tbody></table>';

	//consumidos
	$queryConsumidosSQL = '
	SELECT u.nombre, tf.unidad, IFNULL(sum(toners),0), IFNULL(sum(tambores),0) 
	FROM mtto_unidades  u
	LEFT JOIN 
        (select unidad,
	        IFNULL((case when tipo = 1 then sum(cant) end),0) as toners, 
	        IFNULL((case when tipo = 2 then sum(cant) end),0) as tambores
	    FROM
	    (select sum(cantidad) as cant, tipo, unidad
	         from toner_salidas tsc
			 ';
	if ($desde && $hasta) {
		$queryConsumidosSQL .= 'WHERE tsc.fecha BETWEEN :desde AND :hasta';
	}
	$queryConsumidosSQL .= '
	         group by tipo, unidad) tbl 
	    group by unidad, tipo 
	    ) tf 
        ON u.id = tf.unidad
	GROUP BY u.unidad';
	$queryConsumidos = $db->connect()->prepare($queryConsumidosSQL);
	if ($desde && $hasta) {
		$queryConsumidos->bindParam(':desde', $desde, PDO::PARAM_STR);
		$queryConsumidos->bindParam(':hasta', $hasta, PDO::PARAM_STR);
	}
	$queryConsumidos->execute();
	foreach ($queryConsumidos as $row) {
		$Cunidades[] = $row[0];
		$Ctoners[] = $row[2];
		$Ctambores[] = $row[3];
	}

	$data['state'] = true;
	$data['content'] = $result;
	$data['Eunidades'] = $Eunidades;
	$data['Etoners'] = $Etoners;
	$data['Etambores'] = $Etambores;
	$data['Cunidades'] = $Cunidades;
	$data['Ctoners'] = $Ctoners;
	$data['Ctambores'] = $Ctambores;
	echo json_encode($data);

	exit;
}

?>