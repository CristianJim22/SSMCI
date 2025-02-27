<?php
function mesesLetra($mesnum){
	$mesletra="";
	if ($mesnum == 1) {
	    $mesletra="ENERO";
	} elseif ($mesnum == 2) {
	    $mesletra="FEBRERO";
	} elseif ($mesnum == 3) {
	    $mesletra="MARZO";
	} elseif ($mesnum == 4) {
	    $mesletra="ABRIL";
	} elseif ($mesnum == 5) {
	    $mesletra="MAYO";
	} elseif ($mesnum == 6) {
	    $mesletra="JUNIO";
	} elseif ($mesnum == 7) {
	    $mesletra="JULIO";
	} elseif ($mesnum == 8) {
	    $mesletra="AGOSTO";
	} elseif ($mesnum == 9) {
	    $mesletra="SEPTIEMBRE";
	} elseif ($mesnum == 10) {
	    $mesletra="OCTUBRE";
	} elseif ($mesnum == 11) {
	    $mesletra="NOVIEMBRE";
	} elseif ($mesnum == 12) {
	    $mesletra="DICIEMBRE";
	}else{
		$mesletra = "NOT FOUND";
	}

	return $mesletra;
}
?>