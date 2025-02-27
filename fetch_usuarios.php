<?php
include_once 'databaseocs.php';

if(isset($_POST['tablaUsuarios'])){
	$activo= array("NO","SI");
	$toggle = array('<button class="btn btn-sm btn-danger baja"><i class="fas fa-toggle-off"></i></button>','<button class="btn btn-sm btn-success baja"><i class="fas fa-toggle-on"></i></button>');
	$tipo_usuarios = array("0","ADMINISTRADOR", "SUPERVISOR", "VERIFICADOR");
	$output = array();
	$db = new Databaseocs();
	$query = $db->connect()->prepare('SELECT US.username usuario, US.nombre nombre, UN.nombre unidad, US.rol rol, US.activo activo FROM mtto_usuarios US LEFT JOIN mtto_unidades UN ON US.unidad = UN.id
	');
	$query->execute();
	$rows = $query->rowCount();

	foreach ($query as $row ) {
		$output[] = array($row['usuario'], $row['nombre'], $row['unidad'], $tipo_usuarios[$row['rol']], $activo[$row['activo']],'<button class="btn btn-sm btn-primary opciones" data-value="'.$row['usuario'].'"><b class="fas fa-edit"></b></button> '. $toggle[$row['activo']]);
	}
	$data['data'] = $output;
	echo json_encode($data);
}

if(isset($_POST['busqUsuario'])){
	$identificador= $_POST['busqUsuario'];
	$db = new Databaseocs();
	$query = $db->connect()->prepare('SELECT username, nombre, unidad, rol, activo, password FROM mtto_usuarios WHERE username = :identifier ');
	$query->execute(['identifier' => $identificador]);
	$row = $query->fetch(PDO::FETCH_NUM);

	$data = array (
	'matricula' => $row[0],
	'tipoUsuario' => $row[3],
	'nombre' => $row[1],
	'adscripcion' => $row[2],
	'activo' => $row[4],
	'password' => $row[5]
	);

	echo json_encode ($data);
	exit;
}

if(isset($_POST['datosUsuario'])){
	$igual="";
	$db = new Databaseocs();
	$datos = $_POST['datosUsuario'];
	$mensaje = array();
	try {
		$queryP = $db->connect()->prepare('SELECT password FROM mtto_usuarios where username = :identifier ');
		$queryP->execute(['identifier' => $datos['matricula']]);
		$row = $queryP->fetch(PDO::FETCH_NUM);
		$passwd = "";
		if($datos['password']!=$row[0]){
			$passwd = password_hash($datos['password'], PASSWORD_BCRYPT); 
			$igual="no";
		}else{
			$igual="igual";
			$passwd = $row[0];
		}

		$query = $db->connect()->prepare("UPDATE mtto_usuarios SET nombre = '".$datos['nombre']."', unidad = ".$datos['adscripcion'].", rol = ".$datos['tipoUsuario'].", activo = ".$datos['activo'].", password = '$passwd' WHERE username = '".$datos['matricula']."' ")->execute();
		$mensaje['state'] = true;
	   	$mensaje['message'] = 'DATOS GUARDADOS CON ÉXITO';
	    
		
	} catch (PDOException $e) {
		$mensaje['state'] = false; 
		$mensaje['message'] = 'OCURRIO UN ERROR AL GUARDAR LOS DATOS . INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR.'.$e;
	}
	echo json_encode($mensaje);
}

if(isset($_POST['nuevoUsuario'])){
	$db = new Databaseocs();
	$datos = $_POST['nuevoUsuario'];
	$mensaje = array();
	try {
		
		$passwd = password_hash($datos['password'], PASSWORD_BCRYPT); 
		$query = $db->connect()->prepare("INSERT INTO mtto_usuarios (username, password, nombre, unidad, rol, activo) VALUES ('".$datos['matricula']."', '".$passwd."', '".$datos['nombre']."', ".$datos['adscripcion'].", ".$datos['tipoUsuario'].", ".$datos['activo'].") ")->execute();
			$mensaje['state'] = true;
	   		$mensaje['message'] = 'DATOS GUARDADOS CON ÉXITO';
	    
		
	} catch (PDOException $e) {
		$mensaje['state'] = false; 
		$mensaje['message'] = 'OCURRIO UN ERROR AL GUARDAR LOS DATOS . INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR.';
	}
	echo json_encode($mensaje);
}

if(isset($_POST['activo'])){
	$db = new Databaseocs();
    try {
		//$query = $db->connect()->prepare("DELETE from usuarios WHERE username='$_POST['activo']'")->execute();
        $query = $db->connect()->prepare("UPDATE mtto_usuarios SET activo = NOT activo WHERE username = '".$_POST['activo']."'")->execute();
        $mensaje['state'] = true;
	   	$mensaje['message'] = 'DATOS GUARDADOS CON ÉXITO';
	} catch (PDOException $e) {
		$mensaje['state'] = false; 
		$mensaje['message'] = 'OCURRIO UN ERROR AL ACTUALIZAR LOS DATOS . INTENTE MAS TARDE O CONSULTE CON EL ADMINISTRADOR.';
	}
	echo json_encode($mensaje);
}
?>