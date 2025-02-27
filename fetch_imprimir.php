<?php
session_start(); 
include_once 'databaseocs.php';
if(isset($_POST['listarEquipos'])){
	$unidad = ' WHERE id='.$_SESSION['SSMCI']['unidad'];
	$periodo = $_POST['listarEquipos'];
	$output = array();
	if($periodo !=0){
		$periodo = 'mtto'.$periodo;
		try {
			$db = new Databaseocs();
			$query = $db->connect()->prepare('SELECT ip FROM mtto_unidades '.$unidad.' ORDER BY id ASC');
			$query->execute();
			foreach ($query as $row ) {
				$condicion ="";
				$splitip = explode('/',$row['ip']);
				foreach ($splitip as $ip) {
					$condicion.=' networks.IPADDRESS LIKE "%'.$ip.'%" OR';
				}
				$condicion=substr($condicion, 0, -2);

				$queryc = $db->connect()->prepare("SELECT hardware.ID hwid, hardware.NAME nombreequipo, networks.MACADDR macadd, IF(ISNULL(mtto.completo),0, IF(mtto.completo = 0, 1,2)) completo, mtto.id folio, hardware.DESCRIPTION descrip, bios.SSN ssn
					FROM networks 
					LEFT JOIN hardware ON networks.HARDWARE_ID = hardware.ID 
					LEFT JOIN bios ON hardware.id = bios.HARDWARE_ID
					LEFT JOIN ".$periodo." mtto ON  mtto.macadd  LIKE CONCAT('%',networks.MACADDR,'%')
					WHERE ".$condicion."  GROUP BY networks.MACADDR
					ORDER BY networks.MACADDR ASC ");
				$queryc->execute();
				$completo = array(' <button class="btn btn-sm btn-warning imprimir"><i class="fas fa-print"></i></button>', '<button class="btn btn-sm btn-success validar"><i class="fas fa-check"></i></button> <button class="btn btn-sm btn-warning p-1 reimprimir"><i class="fas fa-print"></i></button>', 'VALIDADO');
				foreach ($queryc as $rowlist){
					$output[] = array("hwid" => $rowlist['hwid'], "nombreequipo" => $rowlist['nombreequipo'], "macadd" => $rowlist['macadd'], "completo" => $completo[$rowlist['completo']], "foliomtto" => $rowlist['folio'], "descripcion" => $rowlist['descrip'], "ssn" => $rowlist["ssn"]);
				}
			}

			$data['state'] = true;
			$data['data'] = $output;
		
		} catch (PDOException $e) {
			$pdo->rollback();
			$data['state'] = false;
			$data['message'] = $e;
		}
	}else{
		$data['state'] = true;
		$data['data'] = array("folio" => "", "ipaddr" => "", "equipo" => "");
	}
	
	echo json_encode($data);
}

if(isset($_POST['validacion'])){
	$validadopor = $_SESSION['SSMCI']['username'];
	$datos = $_POST['validacion'];
	$mensaje = array();
	$db = new Databaseocs();
	$pdo = $db->connect();

	try{
		$pdo->beginTransaction();
		if($datos['garantia']==0){
			$pdo->exec("UPDATE mtto".$datos['periodos']." SET borrado='".$datos['borrado']."', windows='".$datos['windows']."', nombramiento='".$datos['nombramiento']."', dns='".$datos['dns']."', usuario='".$datos['usuario']."', respaldo='".$datos['respaldo']."',maagtic='".$datos['maagtic']."',dameip='".$datos['dameip']."', password='".$datos['password']."',administrador='".$datos['administrador']."',vnc='".$datos['vnc']."',adobe='".$datos['adobe']."', winrar='".$datos['winrar']."', ieedge='".$datos['ieedge']."', java='".$datos['java']."', flashplayer='".$datos['flashplayer']."', windowsv='".$datos['windowsv']."', chrome='".$datos['chrome']."', ligas='".$datos['ligas']."', diagnostico='".$datos['diagnostico']."', sopleteo='".$datos['sopleteo']."', fechasopleteo='".$datos['fechasopleteo']."', observaciones='".addslashes($datos['observaciones'])."', hecho_por = '".$validadopor."', completo = 1, fecha=NOW() WHERE id='".$datos['folio']."'");
		}else{
			$pdo->exec("UPDATE mtto".$datos['periodos']." SET completo = 1, fecha=NOW(), observaciones='EQUIPO CON GARANTIA', hecho_por = '".$validadopor."' WHERE id='".$datos['folio']."'");
		}
		
		$pdo->commit();
		$mensaje['state'] = true;
	   	$mensaje['message'] = 'MANTENIMIENTO CERRADO CON ÉXITO.';
	} catch (PDOException $e) {
		$pdo->rollback();
		$mensaje['state'] = false; 
		$mensaje['message'] = 'OCURRIO UN ERROR AL CERRAR EL REGISTRO DE MANTENIMIENTO. INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR.'.$e;
	}
		
	echo json_encode($mensaje);
}

if(isset($_POST['generarFolio']) && isset($_POST['periodo'])){
	$folio = $_POST['generarFolio'];
	$periodo = $_POST['periodo'];
	$db = new Databaseocs();
	$pdo = $db->connect();
	try{
		$periodonum = $periodo;
        $periodo = "mtto".$periodo;
        $unidad = ' WHERE id='.$_SESSION['SSMCI']['unidad'];
        //buscar mac asociada al id del hardware
        $query = $db->connect()->prepare('SELECT ip FROM mtto_unidades '.$unidad.' ORDER BY id ASC');
        $query->execute();
        foreach ($query as $row ) {
	        $condicion ="";
	        $splitip = explode('/',$row['ip']);
	        foreach ($splitip as $ip) {
	            $condicion.=' IPSUBNET LIKE "%'.$ip.'%" OR';
	        }
          	$condicion=substr($condicion, 0, -2);
        	$queryp = $db->connect()->prepare('SELECT GROUP_CONCAT(MACADDR), IPADDRESS FROM networks WHERE HARDWARE_ID = '.$folio.' AND ('.$condicion.')');
        	$queryp->execute();
        	$macaddresses = $queryp->fetch(PDO::FETCH_NUM);
    	}
    	//rescatar nserie
    	$queryssn = $db->connect()->prepare('SELECT SSN FROM bios WHERE HARDWARE_ID = '.$_POST['generarFolio']);
        $queryssn->execute();
        $ssn = $queryssn->fetch(PDO::FETCH_NUM);

        //Insertar mac para generar folio de mantenimiento
        $pdo->exec('INSERT INTO '.$periodo.' (macadd, ip, unidad, nserie) VALUES ("'.$macaddresses[0].'","'.$macaddresses[1].'", '.$_SESSION['SSMCI']['unidad'].',"'.$ssn[0].'")');
        $last_insert_id = $pdo->lastInsertId();
       
		//$pdo->commit();
		$mensaje['state'] = true;
		$mensaje['foliomtto'] = $last_insert_id;
	   	//$mensaje['message'] = 'MANTENIMIENTO CERRADO CON ÉXITO.';
	} catch (PDOException $e) {
		$pdo->rollback();
		$mensaje['state'] = false; 
		$mensaje['message'] = 'OCURRIO UN ERROR AL CERRAR EL REGISTRO DE MANTENIMIENTO. INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR.'.$e;
	}
		
	echo json_encode($mensaje);
}
?>