<?php
date_default_timezone_set('America/Mexico_City');
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
session_start(); 
include_once 'databaseocs.php';

if(isset($_GET['periodo']) && isset($_GET['df'])){
	$periodo = $_GET['periodo'];
	$df = $_GET['df'];

	if($periodo == "" || $df == ""){
		header('Location: mantenimiento-sites.php');
		die();
	}
   $db = new Databaseocs();
	/*A*/
   $query = $db->connect()->prepare('SELECT id FROM sites_idf1 WHERE df = :df and periodo = :periodo');
   $query->execute(['df' => $df,'periodo' => $periodo]);
   $row = $query->fetch(PDO::FETCH_NUM);
   $rowsA = $query->rowCount();
   if($rowsA == 0){
      header('location: sites-idf1.php?p='.$periodo.'&df='.$df);
      exit;
      //echo "IDF1";
   }

   /*B*/
   $query = $db->connect()->prepare('SELECT id FROM sites_idf2 WHERE df = :df and periodo = :periodo');
   $query->execute(['df' => $df,'periodo' => $periodo]);
   $row = $query->fetch(PDO::FETCH_NUM);
   $rowsB = $query->rowCount();
   if($rowsB == 0){
      header('location: sites-idf2.php?p='.$periodo.'&df='.$df);
      exit;
      //echo "IDF2";
   }

   /*C*/
   $query = $db->connect()->prepare('SELECT id FROM sites_idf3 WHERE df = :df and periodo = :periodo');
   $query->execute(['df' => $df,'periodo' => $periodo]);
   $row = $query->fetch(PDO::FETCH_NUM);
   $rowsC = $query->rowCount();
   if($rowsC == 0){
      header('location: sites-idf3.php?p='.$periodo.'&df='.$df);
      exit;
      //echo "IDF2";
   }

   /*FIN DE CHECKLIST
   $query = $db->connect()->prepare('UPDATE a_datos_generales SET fecha_fin = CURDATE() WHERE matricula = :matricula and id_encuesta = :idencuesta AND fecha_fin IS NULL');
   $query->execute(['matricula' => $matricula,'idencuesta' => $idencuesta]);*/

   /*FIN DE CHECKLIST*/

}else{
	header('Location: mantenimiento-sites.php');
	die();
}
?>