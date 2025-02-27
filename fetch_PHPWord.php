<?php 
session_start();
require_once 'plugins/PHPWord/Autoloader.php';
include_once 'databaseocs.php';
error_reporting(E_ALL);
use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Style;

if(isset($_GET['imprimir']) && isset($_GET['periodo'])){
 	$periodo = "mtto".$_GET['periodo'];
 	$folio = $_GET['imprimir'];
	
 	Autoloader::register();
	Settings::loadConfig();
	$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('dist/formatos/Checklist.docx');

	try {
		$db = new Databaseocs();
			$query = $db->connect()->prepare('SELECT mttoperiodo.id_hardware, HW.NAME, HW.IPADDR, C.MANUFACTURER, C.TYPE, C.CPUARCH, B.SMANUFACTURER, B.SMODEL, B.SSN, HW.ARCH, HW.OSNAME, HW.USERID
				FROM '.$periodo.' mttoperiodo 
				LEFT JOIN hardware HW ON mttoperiodo.id_hardware = HW.id
				LEFT JOIN cpus C ON mttoperiodo.id_hardware = C.HARDWARE_ID
				LEFT JOIN bios B ON mttoperiodo.id_hardware = B.HARDWARE_ID
 				WHERE mttoperiodo.id = '.$folio);
			$query->execute();
			foreach ($query as $row){
				$templateProcessor->setValue('unidad', htmlspecialchars($_SESSION['SSMCI']['unidadnombre']));
				$templateProcessor->setValue('equipo', htmlspecialchars($row[1]));
				$templateProcessor->setValue('marca', htmlspecialchars($row[6]));
				$templateProcessor->setValue('modelo', htmlspecialchars($row[7]));
				$templateProcessor->setValue('nserie', htmlspecialchars($row[8]));
				$templateProcessor->setValue('ip', htmlspecialchars($row[2]));
				$templateProcessor->setValue('user', htmlspecialchars($row[11]));

				//verificar si el nombre del equipo tiene la nomentclatura correctga NTEXXXXXWSXXX...
				$regex = '/^NTE[0-9]{5}WS/i';
				if (preg_match($regex, $row[1]) == 1) {
					$templateProcessor->setValue('NS',htmlspecialchars("(SI*)"));
				    $templateProcessor->setValue('NN', htmlspecialchars("no"));
				} else {
				    $templateProcessor->setValue('NS', htmlspecialchars("(SI*)"));
				    $templateProcessor->setValue('NN', htmlspecialchars("no"));
				}

				//verificar la arquitectura 32 o 64 bits
				if(strpos($row[9], '64')){
					$templateProcessor->setValue('32',htmlspecialchars("32 BITS"));
				    $templateProcessor->setValue('64', htmlspecialchars("(64 BITS*)"));
				}elseif(strpos($row[9], '32')){
					$templateProcessor->setValue('32',htmlspecialchars("(32 BITS*)"));
				    $templateProcessor->setValue('64', htmlspecialchars("64 BITS"));
				}else{
					$templateProcessor->setValue('32',htmlspecialchars("32 BITS"));
				    $templateProcessor->setValue('64', htmlspecialchars("64 BITS"));
				}

				$templateProcessor->setValue('windowsname', htmlspecialchars($row[10]));

				//buscar los programas instalados por nombre
				$querysw = $db->connect()->prepare('SELECT software.HARDWARE_ID, UPPER(GROUP_CONCAT(software_name.NAME)) softwareNames FROM software INNER JOIN software_name ON software.NAME_ID = software_name.ID WHERE software.HARDWARE_ID='.$row[0].' GROUP BY software.HARDWARE_ID');
				$querysw->execute();
				$nombressw = $querysw->fetch(PDO::FETCH_NUM);

				if(strpos($nombressw[1], 'FLASH PLAYER')){
					$templateProcessor->setValue('flashplayern',htmlspecialchars("no"));
				    $templateProcessor->setValue('flashplayers', htmlspecialchars("(SI*)"));
				}else{
					$templateProcessor->setValue('flashplayern',htmlspecialchars("(NO*)"));
				    $templateProcessor->setValue('flashplayers', htmlspecialchars("si"));
				}

				if(strpos($nombressw[1], 'MICROSOFT EDGE')){
					$templateProcessor->setValue('edgen',htmlspecialchars("no"));
				    $templateProcessor->setValue('edges', htmlspecialchars("(SI*)"));
				}else{
					$templateProcessor->setValue('edgen',htmlspecialchars("(NO*)"));
				    $templateProcessor->setValue('edges', htmlspecialchars("si"));
				}

				if(strpos($nombressw[1], 'WINRAR')){
					$templateProcessor->setValue('winrarn',htmlspecialchars("no"));
				    $templateProcessor->setValue('winrars', htmlspecialchars("(SI*)"));
				}else{
					$templateProcessor->setValue('winrarn',htmlspecialchars("(NO*)"));
				    $templateProcessor->setValue('winrars', htmlspecialchars("si"));
				}

				if(strpos($nombressw[1], 'ADOBE ACROBAT READER') || strpos($nombressw[1], 'ADOBE READER')){
					$templateProcessor->setValue('adobern',htmlspecialchars("no"));
				    $templateProcessor->setValue('adobers', htmlspecialchars("(SI*)"));
				}else{
					$templateProcessor->setValue('adobern',htmlspecialchars("(NO*)"));
				    $templateProcessor->setValue('adobers', htmlspecialchars("si"));
				}

				if(strpos($nombressw[1], 'GOOGLE CHROME')){
					$templateProcessor->setValue('chromen',htmlspecialchars("no"));
				    $templateProcessor->setValue('chromes', htmlspecialchars("(SI*)"));
				}else{
					$templateProcessor->setValue('chromen',htmlspecialchars("(NO*)"));
				    $templateProcessor->setValue('chromes', htmlspecialchars("si"));
				}

				$queryp = $db->connect()->prepare('SELECT nombre, DATE_FORMAT(desde,"%d/%m/%Y"),  DATE_FORMAT(hasta,"%d/%m/%Y") FROM mtto_periodos WHERE id_periodo = '.$_GET['periodo']);
				$queryp->execute();
				$nombresp = $queryp->fetch(PDO::FETCH_NUM);

				$templateProcessor->setValue('folio',htmlspecialchars($folio));
				$templateProcessor->setValue('periodo',htmlspecialchars($nombresp[0]));
				$templateProcessor->setValue('del',htmlspecialchars($nombresp[1]));
				$templateProcessor->setValue('al',htmlspecialchars($nombresp[2]));
			}

			$templateProcessor->saveAs('dist/formatos/temp/Sample_07_TemplateCloneRow.docx');
			header("Content-Disposition: attachment; filename=Folio ".$folio." Checklist.docx");
			readfile('dist/formatos/temp/Sample_07_TemplateCloneRow.docx'); // or echo file_get_contents($temp_file);
			unlink('dist/formatos/temp/Sample_07_TemplateCloneRow.docx');
	} catch (PDOException $e) {

	}

	
	

	


	 
}


?>