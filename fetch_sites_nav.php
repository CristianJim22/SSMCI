<?php 
date_default_timezone_set('America/Mexico_City');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start(); 
include_once 'databaseocs.php';

if(isset($_POST['registroFaltante'])){
	$unidadCompleta = true;
	$response = array();
	$result ="";
	$miunidad = $_SESSION['SSMCI']['unidad'];
	$mesActual = date('m');
	$fechaInicio = date('Y-m-01');
	$db = new Databaseocs();
	$query = $db->connect()->prepare('SELECT completo, id 
			FROM sites_periodos
			WHERE unidad = :identifier AND MONTH(fecha_inicio) = :mes');
	$query->execute(['identifier' => $miunidad, "mes" => $mesActual]);
	$nrows = $query->rowCount();
	$periodo_id = 0;
	if($nrows<=0){
	 	try{
	 		$pdo = $db->connect();
			$pdo->beginTransaction();
			$pdo->exec("INSERT INTO sites_periodos (unidad, fecha_inicio, completo) VALUES (".$miunidad.", '".$fechaInicio."', 0)");
			$periodo_id = $pdo->lastInsertId();
			$pdo->commit();
	 	} catch (PDOException $e) {
	 		$pdo->rollback();
	 		echo'
			   <script>
			   window.onload = function() {
			      alert("OCURRIÓ UN ERROR, VUELVA A INTENTAR O CONSULTE CON EL ADMINISTRADOR");
			      location.href = "mantenimiento-sites.php";  
			   }
			   </script>
			';
			die();
	 	}
	}else{
		$row = $query->fetch(PDO::FETCH_ASSOC);
		$periodo_id = $row['id'];
	}

	//pendiente * validar su el periodo_id es cero, entonces no se encontreo nada // error

	//BUSCAR MDF'S
	$query = $db->connect()->prepare('SELECT C.unidad unidad, C.id_df id_df, C.ubicacion ubicacion, C.descripcion descripcion,  CONCAT(C.tipo_df , "DF") tipo_df, IF(MDF1.id IS NULL, 0, 1) mdf1, IF(MDF2.id IS NULL, 0, 1) mdf2, IF(MDF3.id IS NULL, 0, 1) mdf3
			FROM sites_catalogo	C
			LEFT JOIN sites_mdf1 MDF1 ON MDF1.df = C.id_df AND MDF1.periodo = :idperiodo1
			LEFT JOIN sites_mdf2 MDF2 ON MDF2.df = C.id_df AND MDF2.periodo = :idperiodo2
			LEFT JOIN sites_mdf3 MDF3 ON MDF3.df = C.id_df AND MDF3.periodo = :idperiodo3
			WHERE C.unidad = :identifier AND C.tipo_df = "M"');
	$query->execute(['identifier' => $miunidad, 'idperiodo1' => $periodo_id, 'idperiodo2' => $periodo_id, 'idperiodo3' => $periodo_id]);
	$nrows = $query->rowCount();
	if($nrows>0){
		$result.= '<div class="row"> <div class="col-3 font-weight-bold">TIPO DE DF</div><div class="col-3 font-weight-bold">UBICACIÓN</div><div class="col-3 font-weight-bold">DESCRIPCIÓN</div><div class="col-3 font-weight-bold">ESTADO</div> </div> ';
		
		foreach ($query as $key) {
			$result.= '<div class="row p-1">';
				$result.= '<div class="col-3">';
				$result.= $key['tipo_df'];
				$result.= '</div>';
				$result.= '<div class="col-3">';
				$result.= $key['ubicacion'];
				$result.= '</div>';
				$result.= '<div class="col-3">';
				$result.= $key['descripcion'];
				$result.= '</div>';
				
				//echo $key['unidad']. " - ". $key['id_df']. " - ". $key['tipo_df']. " - ". $key['mdf1']. " - ". $key['mdf2']. " - ". $key['mdf3']."-";
				if(($key['mdf1']+$key['mdf2']+$key['mdf3']) < 3){
					$result.= '<div class="col-3">';
					$result.= '<form action="sites_handlerM.php" method="GET">';
					$result.= '<input type="hidden" name="periodo" value="'.$periodo_id.'">';
					$result.= '<input type="hidden" name="df" value="'.$key['id_df'].'">';
					$result.= '<button type="submit" class="btn btn-sm btn-warning">PENDIENTE <i class="fas fa-arrow-right"></i></button>';
					$result.= '</form>';
					$result.= '</div>';
					$unidadCompleta = false; // variable se convierte falso cuando falta algun mdf o idf
				}else{
					$result.= '<div class="col-3">';
					$result.= '<i class="fas fa-check text-success"></i>';
					$result.= '</div>';
				}
			$result.= '</div>';
		}
		
	}else{
	}
	
	//BUSCAR IDF'S
	$query = $db->connect()->prepare('SELECT C.unidad unidad, C.id_df id_df, C.ubicacion ubicacion, C.descripcion descripcion, CONCAT(C.tipo_df, "DF") tipo_df, IF(IDF1.id IS NULL, 0, 1) idf1, IF(IDF2.id IS NULL, 0, 1) idf2, IF(IDF3.id IS NULL, 0, 1) idf3
			FROM sites_catalogo	C
			LEFT JOIN sites_idf1 IDF1 ON IDF1.df = C.id_df AND IDF1.periodo = :idperiodo1
			LEFT JOIN sites_idf2 IDF2 ON IDF2.df = C.id_df AND IDF2.periodo = :idperiodo2
			LEFT JOIN sites_idf3 IDF3 ON IDF3.df = C.id_df AND IDF3.periodo = :idperiodo3
			WHERE C.unidad = :identifier AND C.tipo_df = "I"');
	$query->execute(['identifier' => $miunidad, 'idperiodo1' => $periodo_id, 'idperiodo2' => $periodo_id, 'idperiodo3' => $periodo_id]);
	$nrows = $query->rowCount();
	if($nrows>0){
		
		foreach ($query as $key) {
			$result.= '<div class="row p-1">';
				$result.= '<div class="col-3">';
				$result.= $key['tipo_df'];
				$result.= '</div>';
				$result.= '<div class="col-3">';
				$result.= $key['ubicacion'];
				$result.= '</div>';
				$result.= '<div class="col-3">';
				$result.= $key['descripcion'];
				$result.= '</div>';
				
				//echo $key['unidad']. " - ". $key['id_df']. " - ". $key['tipo_df']. " - ". $key['mdf1']. " - ". $key['mdf2']. " - ". $key['mdf3']."-";
				if(($key['idf1']+$key['idf2']+$key['idf3']) < 3){
					$result.= '<div class="col-3">';
					$result.= '<form action="sites_handlerI.php" method="GET">';
					$result.= '<input type="hidden" name="periodo" value="'.$periodo_id.'">';
					$result.= '<input type="hidden" name="df" value="'.$key['id_df'].'">';
					$result.= '<button class="btn btn-sm btn-warning" data-periodo="'.$periodo_id.'" data-df="'.$key['id_df'].'">PENDIENTE <i class="fas fa-arrow-right"></i></button>';
					$result.= '</form>';
					$result.= '</div>';
					$unidadCompleta = false; // variable se convierte falso cuando falta algun mdf o idf
				}else{
					$result.= '<div class="col-3">';
					$result.= '<i class="fas fa-check text-success"></i>';
					$result.= '</div>';
				}
			$result.= '</div>';
		}
	}else{
	}
	
	if($unidadCompleta){
		$query = $db->connect()->prepare('UPDATE sites_periodos SET completo = 1, fecha_termino = NOW() WHERE id = '.$periodo_id);
		$query->execute();
	}

	$response['result'] = $result;
	echo json_encode ($response);
	exit;
}


?>
