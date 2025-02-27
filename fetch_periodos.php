<?php
session_start(); 
include_once 'databaseocs.php';
if(isset($_POST['nuevoPeriodo'])){
	$db = new Databaseocs();
	$pdo = $db->connect();
	$datos = $_POST['nuevoPeriodo'];
	$mensaje = array();

	try {
		$pdo->beginTransaction();
		$pdo->exec("INSERT INTO mtto_periodos (nombre, desde, hasta) VALUES ('".$datos['nombre']."', '".$datos['desde']."', '".$datos['hasta']."') ");
		$last_insert_id = $pdo->lastInsertId();
		$pdo->exec("CREATE TABLE IF NOT EXISTS mtto".$last_insert_id." (id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
		  /*id_hardware int(11) NOT NULL,*/
		  macadd varchar(500) DEFAULT NULL,
		  ip varchar(500) DEFAULT NULL,
		  fecha date DEFAULT NULL,
		  completo tinyint(1) DEFAULT 0,
		  borrado tinyint(1) DEFAULT NULL,
		  windows tinyint(1) DEFAULT NULL,
		  nombramiento tinyint(1) DEFAULT NULL,
		  dns tinyint(1) DEFAULT NULL,
		  usuario tinyint(1) DEFAULT NULL,
		  respaldo tinyint(1) DEFAULT NULL,
		  maagtic tinyint(1) DEFAULT NULL,
		  dameip tinyint(1) DEFAULT NULL,
		  password tinyint(1) DEFAULT NULL,
		  administrador tinyint(1) DEFAULT NULL,
		  vnc tinyint(1) DEFAULT NULL,
		  adobe tinyint(1) DEFAULT NULL,
		  winrar tinyint(1) DEFAULT NULL,
		  ieedge tinyint(1) DEFAULT NULL,
		  java tinyint(1) DEFAULT NULL,
		  flashplayer tinyint(1) DEFAULT NULL,
		  windowsv varchar(10) DEFAULT NULL,
		  chrome tinyint(1) DEFAULT NULL,
		  ligas tinyint(1) DEFAULT NULL,
		  diagnostico varchar(15) DEFAULT NULL,
		  sopleteo tinyint(1) DEFAULT NULL,
		  fechasopleteo date DEFAULT NULL,
		  observaciones text COLLATE utf8_spanish_ci,
		  hecho_por varchar(50) DEFAULT NULL,
		  unidad int(11) NOT NULL,
		nserie varchar(100) DEFAULT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;");
		$stmt = $pdo->prepare("SELECT id, nombre, ip FROM mtto_unidades ORDER BY id ASC");
		$stmt->setFetchMode(PDO::FETCH_ASSOC);
		$stmt->execute();

		/*foreach ($stmt as $row) {
			$condicion ="";
			$splitip = explode('/',$row['ip']);
			foreach ($splitip as $ip) {
				$condicion.=' IPSUBNET LIKE "%'.$ip.'%" OR';
			}
			$condicion=substr($condicion, 0, -2);
			//$pdo->exec("INSERT INTO mtto".$last_insert_id." (id_hardware) SELECT DISTINCT hardware.ID FROM hardware INNER JOIN networks on hardware.id = networks.hardware_id WHERE (".$condicion.")");
			$pdo->exec("INSERT INTO mtto".$last_insert_id." (id_hardware, macadd) SELECT DISTINCT hardware.ID, GROUP_CONCAT(networks.MACADDR) FROM networks
				LEFT JOIN hardware ON networks.HARDWARE_ID = hardware.ID
				WHERE (".$condicion.") GROUP BY hardware.ID, hardware.NAME ORDER BY networks.MACADDR ASC");
		}*/

		//$pdo->commit();
		$mensaje['state'] = true;
	   	$mensaje['message'] = 'PERIODO CREADO CON ÉXITO';
	} catch (PDOException $e) {
		$pdo->rollback();
		$mensaje['state'] = false; 
		$mensaje['message'] = 'OCURRIO UN ERROR AL REGISTRAR EL PERIODO . INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR.'.$e;
	}

	echo json_encode($mensaje);
}

if(isset($_POST['listaPeriodo'])){
	$data = array();
	try {
		$output = array();
		$db = new Databaseocs();
		$query = $db->connect()->prepare('SELECT id_periodo, nombre, desde, hasta FROM mtto_periodos ORDER BY hasta DESC LIMIT 1');
		$query->execute();

		foreach ($query as $row ) {
			$output[] = array($row['id_periodo'], $row['nombre']);
		}
		$data['state'] = true;
		$data['data'] = $output;
	} catch (PDOException $e) {
		$data['state'] = false;
		$data['data'] = $e;
		$data['message'] = "OCURRIO UN ERROR AL CONSULTAR LOS PERIODOS DE MANTENIMIENTO, INTENTE MÁS TARDE O CONSULTE CON EL ADMINISTRADOR";
	}
	echo json_encode($data);
}

if(isset($_POST['tableroMtto'])){
	
	$data = array();
	$output = array();
	$outputByMonth = array();
	$unidad ="";
	if($_SESSION['SSMCI']['rol']!=1){
		$unidad = ' WHERE id='.$_SESSION['SSMCI']['unidad'];
	}

	try {
		$db = new Databaseocs();
		$query = $db->connect()->prepare('SELECT id, nombre, ip FROM mtto_unidades '.$unidad.' ORDER BY id ASC');
		$query->execute();

		//totales
		$totalags = 0;
		$completadosags = 0;
		$pendientesags = 0;
		$congarantiaags = 0;
		//totales por mes
		$ene = 0; $feb = 0; $mar = 0;
		$abr = 0; $may = 0; $jun = 0;
		$jul = 0; $ago = 0; $sep = 0;
		$oct = 0; $nov = 0; $dic = 0;
		foreach ($query as $row ) {
			$condicion ="";
			$splitip = explode('/',$row['ip']);
			foreach ($splitip as $ip) {
				$condicion.=' EXISTS ( SELECT 1 FROM networks WHERE hardware.ID = networks.HARDWARE_ID AND networks.IPADDRESS LIKE "%'.$ip.'%") OR';
			}
			$condicion=substr($condicion, 0, -2);

			$queryc = $db->connect()->prepare("SELECT COUNT(DISTINCT hardware.ID) FROM hardware INNER JOIN networks on hardware.id = networks.hardware_id WHERE (".$condicion.")");
			$queryc->execute();
			$totales = $queryc->fetch(PDO::FETCH_NUM);
			//$queryc = $db->connect()->prepare("SELECT COUNT(DISTINCT hardware.ID) FROM networks LEFT JOIN hardware ON networks.HARDWARE_ID = hardware.ID LEFT JOIN mtto".$_POST['tableroMtto']." mtto ON mtto.macadd LIKE CONCAT('%',networks.MACADDR,'%') WHERE (".$condicion.") AND mtto.completo = 1 AND mtto.observaciones != 'EQUIPO CON GARANTIA' ORDER BY networks.MACADDR ASC");
			$queryc = $db->connect()->prepare("SELECT COUNT(mtto.id) FROM mtto".$_POST['tableroMtto']." mtto WHERE mtto.unidad = ".$row['id']." AND mtto.completo = 1 AND mtto.observaciones != 'EQUIPO CON GARANTIA'");
			$queryc->execute();
			$completados = $queryc->fetch(PDO::FETCH_NUM);
			//$queryc = $db->connect()->prepare("SELECT COUNT(DISTINCT hardware.ID) FROM networks LEFT JOIN hardware ON networks.HARDWARE_ID = hardware.ID LEFT JOIN mtto".$_POST['tableroMtto']." mtto ON mtto.macadd LIKE CONCAT('%',networks.MACADDR,'%') WHERE (".$condicion.") AND mtto.completo = 1 AND mtto.observaciones = 'EQUIPO CON GARANTIA' ORDER BY networks.MACADDR ASC");
			$queryc = $db->connect()->prepare("SELECT COUNT(mtto.id) FROM mtto".$_POST['tableroMtto']." mtto WHERE mtto.unidad = ".$row['id']." AND mtto.completo = 1 AND mtto.observaciones = 'EQUIPO CON GARANTIA'");
			$queryc->execute();
			$congarantia = $queryc->fetch(PDO::FETCH_NUM);

			/*POR MES*/
			$queryMES = $db->connect()->prepare("SELECT COUNT(mtto.id),  
			COUNT(CASE WHEN MONTH(fecha) = 1 THEN 1 END) AS '01',
			COUNT(CASE WHEN MONTH(fecha) = 2 THEN 1 END) AS '02',
			COUNT(CASE WHEN MONTH(fecha) = 3 THEN 1 END) AS '03',
			COUNT(CASE WHEN MONTH(fecha) = 4 THEN 1 END) AS '04',
			COUNT(CASE WHEN MONTH(fecha) = 5 THEN 1 END) AS '05',
			COUNT(CASE WHEN MONTH(fecha) = 6 THEN 1 END) AS '06',
			COUNT(CASE WHEN MONTH(fecha) = 7 THEN 1 END) AS '07',
			COUNT(CASE WHEN MONTH(fecha) = 8 THEN 1 END) AS '08',
			COUNT(CASE WHEN MONTH(fecha) = 9 THEN 1 END) AS '09',
			COUNT(CASE WHEN MONTH(fecha) = 10 THEN 1 END) AS '10',
			COUNT(CASE WHEN MONTH(fecha) = 11 THEN 1 END) AS '11',
			COUNT(CASE WHEN MONTH(fecha) = 12 THEN 1 END) AS '12'
			FROM mtto".$_POST['tableroMtto']." mtto WHERE mtto.unidad = ".$row['id']." AND mtto.completo = 1");
			$queryMES->execute();
			$porMes = $queryMES->fetch(PDO::FETCH_NUM);

			$pendientes= (int)$totales[0]-(int)$completados[0]-$congarantia[0];
			$porcentaje = number_format((100/(int)$totales[0]) * ((int)$completados[0]+$congarantia[0]),2);
			$output[] = array("id" => $row['id'],"unidad" => $row['nombre'], "ip" => $row['ip'], "total" => $totales[0], "completado" => $completados[0], "pendiente" => $pendientes, "congarantia" => $congarantia[0],"porcentaje" => $porcentaje, "ene" => $porMes[1], "feb" => $porMes[2], "mar" => $porMes[3], "abr" => $porMes[4], "may" => $porMes[5], "jun" => $porMes[6], "jul" => $porMes[7], "ago" => $porMes[8], "sep" => $porMes[9], "oct" => $porMes[10], "nov" => $porMes[11], "dic" => $porMes[12]);
			$outputByMonth = array("unidad" => $row['nombre']);

			$totalags+= $totales[0];
			$completadosags+= $completados[0];
			$pendientesags+= $pendientes;
			$congarantiaags+= $congarantia[0];

			$ene+= $porMes[1]; $feb+= $porMes[2]; $mar+= $porMes[3];
			$abr+= $porMes[4]; $may+= $porMes[5]; $jun+= $porMes[6];
			$jul+= $porMes[7]; $ago+= $porMes[8]; $sep+= $porMes[9];
			$oct+= $porMes[10]; $nov+= $porMes[11]; $dic+= $porMes[12];
		}
		
		$porcentajesags = number_format((100/$totalags)*($completadosags+$congarantiaags),2);
		if($_SESSION['SSMCI']['rol'] == 1){
			$output[] = array("id" => 1000000,"unidad" => "TOTAL", "ip" => "AGS", "total" => $totalags, "completado" => $completadosags, "pendiente" => $pendientesags, "congarantia" => $congarantiaags,"porcentaje" => $porcentajesags, "ene" => $ene, "feb" => $feb, "mar" => $mar, "abr" => $abr, "may" => $may, "jun" => $jun, "jul" => $jul, "ago" => $ago, "sep" => $sep, "oct" => $oct, "nov" => $nov, "dic" => $dic);
		}

		$data['state'] = true;
		$data['data'] = $output;
	
	} catch (PDOException $e) {
		$pdo->rollback();
		$data['state'] = false;
		$data['message'] = $e;
	}
	
	$data['state'] = true;
	header('Content-type: application/json');
	echo json_encode($data);
}

//query para sacar hardware que no pertenee al grupo corecto
//SELECT x FROM (SELECT DISTINCT hardware.ID x FROM hardware INNER JOIN networks on hardware.id = networks.hardware_id WHERE ( EXISTS ( SELECT 1 FROM networks WHERE hardware.ID = networks.HARDWARE_ID AND networks.IPADDRESS LIKE '%172.26.36.%') OR EXISTS ( SELECT 1 FROM networks WHERE hardware.ID = networks.HARDWARE_ID AND networks.IPADDRESS LIKE '%11.1.1.%')) GROUP BY hardware.id) nw WHERE x not IN (SELECT hardware.id FROM hardware WHERE IPADDR LIKE '%172.26.36.%' or IPADDR LIKE '%11.1.1.%')

?>