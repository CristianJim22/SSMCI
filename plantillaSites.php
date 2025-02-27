<?php
include_once 'databaseocs.php';
session_start();
//Plantilla HTML para mostrar el reporte en PDF 
ini_set("pcre.backtrack_limit", "5000000");
ini_set('max_execution_time', '30000000');

function getPlantilla($folio){
  $contenido ="";
  $db = new Databaseocs();
  try {
    //Datos de la tabla "sites_periodos"
    $querysp = $db->connect()->prepare('SELECT SP.id AS id_periodo, SP.unidad, DATE_FORMAT(SP.fecha_inicio, "%d/%m/%Y") AS fecha_inicio, DATE_FORMAT(SP.fecha_termino, "%d/%m/%Y") AS fecha_termino, 
    SP.completo, SC.id_df AS catalogo, SC.tipo_df, SC.ubicacion, SC.descripcion, M1.*, M2.*, M3.*, I1.*, I2.*, I3.*
    FROM sites_periodos SP JOIN sites_catalogo SC ON SP.unidad = SC.unidad
    LEFT JOIN sites_mdf1 M1 ON SP.id = M1.periodo AND SC.id_df = M1.df
    LEFT JOIN sites_mdf2 M2 ON SP.id = M2.periodo AND SC.id_df = M2.df
    LEFT JOIN sites_mdf3 M3 ON SP.id = M3.periodo AND SC.id_df = M3.df
    LEFT JOIN sites_idf1 I1 ON SP.id = I1.periodo AND SC.id_df = I1.df
    LEFT JOIN sites_idf2 I2 ON SP.id = I2.periodo AND SC.id_df = I2.df
    LEFT JOIN sites_idf3 I3 ON SP.id = I3.periodo AND SC.id_df = I3.df
    WHERE SP.id ='.$folio);
    $querysp->execute();
    $results = $querysp->fetchAll(PDO::FETCH_ASSOC);
    
    $queryinf = $db->connect()->prepare('SELECT id, DATE_FORMAT(SP.fecha_inicio, "%d/%m/%Y") AS fecha_inicio, DATE_FORMAT(SP.fecha_termino, "%d/%m/%Y") AS fecha_termino FROM sites_periodos SP WHERE id ='.$folio);
    $queryinf->execute();
    $info = $queryinf->fetch(PDO::FETCH_ASSOC);

        $countM = 0;
        $countI = 0;
        foreach ($results as $res) {
            if ($res['tipo_df'] == 'M') {
                $countM++;
            } elseif ($res['tipo_df'] == 'I') {
                $countI++;
            }
        }
            $contenido .= '
              <body>
        <table>
            <tr>
                <td>
                    <div id="logo">
                        <img style="right=30px" src="dist/img/GobMex.jpg" width="150" height="65">
                        
                    </div>
                </td>
                <td>
                    <div id="logo">
                        <img src="dist/img/imsslogo-grey.jpg" width="70" height="75">
                    </div>
                </td>
                <td>
                </td>
                <td>
                    <div style="position: fixed; right: 23px;">
                        COORDINACIÓN DE INFROMATICA
                    </div>      
                </td>
            </tr>
        </table>

        <table style="width: 100%; text-align: center; border-collapse: collapse">
            <tr>
                <td>
                    <div >
                        <img src="dist/img/logo-cdi.jpg" width="60" height="40">
                    </div>
                </td>
                <td>
                    <div style="font-size:18px">SUPERVISIÓN DE LA APLICACIÓN DE POLÍTICAS DE SEGURIDAD EN CUARTOS DE COMUNICACIÓN 2023</div>
                </td>
                <td>
                    <div id="logo">
                        <img src="dist/img/imsslogo-grey.jpg" width="60" height="65">
                    </div>
                </td>
            </tr>
        </table>

        <br>UNIDAD: '.$_SESSION['SSMCI']['unidadnombre'].'</br>
        <br>

        <table style="width: 100%; border-collapse: collapse" class="table-bordered">
            <tr>
                <th  colspan="6" class="text-center">SEGURIDAD FÍSICA Y AMBIENTAL</th>
            </tr>
            <tr>
                <td style="text-align: center" rowspan="2" colspan="2" class="small-text">
                    Cuarto de comunicación MDF<br>(site principal)
                </td>
                <td style="text-align: center" class="header">No De Cuartos</td>
                <td style="text-align: center" rowspan="2" colspan="2" class="small-text">
                    Cuarto de comunicaciones<br>IDF
                </td>
                <td style="text-align: center" colspan="1" class="header">No de cuartos</td>               
            </tr>
            <tr>
                <td style="text-align: center" class="small-text">' . $countM . '</td>
                <td style="text-align: center" class="small-text">' . $countI . '</td>
            </tr>
            <tr>
                <td style="text-align: center" colspan="2">FECHA DE INICIO:</td>
                <td style="text-align: center"> '. $info["fecha_inicio"].'</td>
                <td style="text-align: center" colspan="2">FECHA DE FINALIZACIÓN:</td>
                <td style="text-align: center"> '.($info["fecha_termino"] ? $info["fecha_termino"] : 'NO HA FINALIZADO'). '</td>
            </tr>
        </table>
        <br> ';

        foreach($results as $res){
        if($res['tipo_df'] == 'M'){
            $contenido .= '
        <table style="width: 100%; border-collapse: collapse" class="table-bordered">
        <tr>
            <th colspan="6" class="text-center">MDF</th>
        </tr>
        <tr>
            <th style="text-align: center">1</th>
            <th style="text-align: center">MEMORIA TECNICA</th>
            <th style="text-align: center">CUMPLE</th>
            <th style="text-align: center">OBSERVACIONES</th>
        </tr>
        <tr>
            <td style="text-align: center;">1.1</td>
            <td style="text-align: left; padding: 0 10px">NOMBRE DEL MDF CORRECTO</td>
            <td style="text-align: center">' . ($res["MDF1_1"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_1o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.2</td>
            <td style="text-align: left; padding: 0 10px">DEFINICIÓN DEL AREA CORRECTA</td>
            <td style="text-align: center">' . ($res["MDF1_2"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_2o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.3</td>
            <td style="text-align: left; padding: 0 10px">DEFINICIÓN DE PAREDES Y PISO</td>
            <td style="text-align: center">' . ($res["MDF1_3"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_3o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.4</td>
            <td style="text-align: left; padding: 0 10px">DEFINICION CORRECTA DE RACKS</td>
            <td style="text-align: center">' . ($res["MDF1_4"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_4o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.5</td>
            <td style="text-align: left; padding: 0 10px">DEFINICION CORRECTA DE SWITCHES</td>
            <td style="text-align: center">' . ($res["MDF1_5"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_5o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.6</td>
            <td style="text-align: left; padding: 0 10px">DEFINICION CORRECTA DE PANELES DE PARCHEO</td>
            <td style="text-align: center">' . ($res["MDF1_6"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_6o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.7</td>
            <td style="text-align: left; padding: 0 10px">DEFINICION CORRECTA DE TRAYECTORIAS</td>
            <td style="text-align: center">' . ($res["MDF1_7"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_7o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.8</td>
            <td style="text-align: left; padding: 0 10px">DEFINICION CORRECTA DE EQUIPOS NO IMSS</td>
            <td style="text-align: center">' . ($res["MDF1_8"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_8o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.9</td>
            <td style="text-align: left; padding: 0 10px">CANALIZACION ADECUADA</td>
            <td style="text-align: center">' . ($res["MDF1_9"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_9o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.10</td>
            <td style="text-align: left; padding: 0 10px">EN ORDEN EL CABLEADO FRONTAL AL RACK (NODOS Y PACHD CORD)</td>
            <td style="text-align: center">' . ($res["MDF1_10"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_10o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.11</td>
            <td style="text-align: left; padding: 0 10px">EN ORDEN EL CABLEADO POSTERIOR AL RACK (NODOS Y PACHD CORD)</td>
            <td style="text-align: center">' . ($res["MDF1_11"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_11o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.12</td>
            <td style="text-align: left; padding: 0 10px">ESTAN IDENTIFICADOS LOS PUERTOS EN LOS SW Y PP</td>
            <td style="text-align: center">' . ($res["MDF1_12"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_12o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.13</td>
            <td style="text-align: left; padding: 0 10px">ESTAN ORDENADOS LOS EQUIPOS EN EL MDF</td>
            <td style="text-align: center">' . ($res["MDF1_13"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_13o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.14</td>
            <td style="text-align: left; padding: 0 10px">SE CUENTA CON BITACORA DE MANTENIMIENTO DE LOS EQUIPOS</td>
            <td style="text-align: center">' . ($res["MDF1_14"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_14o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.15</td>
            <td style="text-align: left; padding: 0 10px">SE HA REALIZADO LA LIMPIEZA DE LOS EQUIPOS PERIODICAMENTE</td>
            <td style="text-align: center">' . ($res["MDF1_15"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_15o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.16</td>
            <td style="text-align: left; padding: 0 10px">LAS IPS ASIGNADAS A LOS EQUIPOS SON LAS CORRECTAS</td>
            <td style="text-align: center">' . ($res["MDF1_16"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF1_16o"] . '</td>
        </tr>
        <tr>
            <th style="text-align: center">2</th>
            <th style="text-align: left">APLICACIÓN DE CONTROLES DE SEGURIDAD</th>
            <th style="text-align: center">CUMPLE</th>
            <th style="text-align: center">OBSERVACIONES</th>
        </tr>
        <tr>
            <td style="text-align: center;">2.1</td>
            <td style="text-align: left; padding: 0 10px">TIENE UNA BITÁCORA QUE REGISTRE EL ACCESO AL MDF</td>
            <td style="text-align: center">' . ($res["MDF2_1"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF2_1o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.2</td>
            <td style="text-align: left; padding: 0 10px">UPS FUNCIONANDO ADECUADAMENTE</td>
            <td style="text-align: center">' . ($res["MDF2_2"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF2_2o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.3</td>
            <td style="text-align: left; padding: 0 10px">CONEXIÓN DE SERVIDORES A UPS</td>
            <td style="text-align: center">' . ($res["MDF2_3"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF2_3o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.4</td>
            <td style="text-align: left; padding: 0 10px">CONEXIÓN DE SWITCHES A UPS</td>
            <td style="text-align: center">' . ($res["MDF2_4"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF2_4o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.5</td>
            <td style="text-align: left; padding: 0 10px">TODOS LOS EQUIPOS ACTIVOS EN RACK</td>
            <td style="text-align: center">' . ($res["MDF2_5"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF2_5o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.6</td>
            <td style="text-align: left; padding: 0 10px">UBICACIÓN DE SERVIDORES EN GABINETES O RACKS</td>
            <td style="text-align: center">' . ($res["MDF2_6"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF2_6o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.7</td>
            <td style="text-align: left; padding: 0 10px">HA GESTIONADO RESTRINGIR EL AREA Y ACCESO</td>
            <td style="text-align: center">' . ($res["MDF2_7"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF2_7o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.8</td>
            <td style="text-align: left; padding: 0 10px">EL CLIMA ES MENOR DE 23 GRADOS (APROX)</td>
            <td style="text-align: center">' . ($res["MDF2_8"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF2_8o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.9</td>
            <td style="text-align: left; padding: 0 10px">HA GESTIONADO UNA REVISION DE CARGAS ELECTRICAS</td>
            <td style="text-align: center">' . ($res["MDF2_9"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF2_9o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.10</td>
            <td style="text-align: left; padding: 0 10px">EL MDF ESTA UBICADO EN UN AREA CORRECTA</td>
            <td style="text-align: center">' . ($res["MDF2_10"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF2_10o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.11</td>
            <td style="text-align: left; padding: 0 10px">HA GESTIONADO SEÑALETICA Y EXTINTORES EN EL AREA</td>
            <td style="text-align: center">' . ($res["MDF2_11"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF2_11o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.12</td>
            <td style="text-align: left; padding: 0 10px">CUENTA CON UN PLAN ANTE UNA CONTINGENCIA</td>
            <td style="text-align: center">' . ($res["MDF2_12"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF2_12o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.13</td>
            <td style="text-align: left; padding: 0 10px">PARTICIPA EN LOS SIMULCROS DE LA UNIDAD</td>
            <td style="text-align: center">' . ($res["MDF2_13"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF2_13o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.14</td>
            <td style="text-align: left; padding: 0 10px">CUENTA CON LA INFORMACIÓN DE PROTECCION CIVIL</td>
            <td style="text-align: center">' . ($res["MDF2_14"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF2_14o"] . '</td>
        </tr>
        <tr>
            <th style="text-align: center">3</th>
            <th style="text-align: left">APLICACIÓN DE CONTROLES DE SEGURIDAD</th>
            <th style="text-align: center">CUMPLE</th>
            <th style="text-align: center">OBSERVACIONES</th>
        </tr>
        <tr>
            <td style="text-align: center;">3.1</td>
            <td style="text-align: left; padding: 0 10px">LOS TECHOS Y PAREDES (VENTANAS) ESTAN LIMPIOS</td>
            <td style="text-align: center">' . ($res["MDF3_1"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF3_1o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.2</td>
            <td style="text-align: left; padding: 0 10px">EL MDF ESTA INCLUIDA EN EL PROGRAMA DE LIMPIEZA DE LA UNIDAD</td>
            <td style="text-align: center">' . ($res["MDF3_2"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF3_2o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.3</td>
            <td style="text-align: left; padding: 0 10px">EL AREA ES EXCLUSIVA PARA EL MDF</td>
            <td style="text-align: center">' . ($res["MDF3_3"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF3_3o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.4</td>
            <td style="text-align: left; padding: 0 10px">LA ILUMINACION ES ACEPTABLE</td>
            <td style="text-align: center">' . ($res["MDF3_4"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF3_4o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.5</td>
            <td style="text-align: left; padding: 0 10px">EXISTE SEÑALETICA Y EXTINTORES EN EL AREA</td>
            <td style="text-align: center">' . ($res["MDF3_5"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF3_5o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.6</td>
            <td style="text-align: left; padding: 0 10px">EXISTE DETECTOR DE HUMO</td>
            <td style="text-align: center">' . ($res["MDF3_6"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF3_6o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.7</td>
            <td style="text-align: left; padding: 0 10px">LA CLIMATIZACIÓN ES ACEPTABLE (< 23 GRADOS)</td>
            <td style="text-align: center">' . ($res["MDF3_7"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF3_7o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.8</td>
            <td style="text-align: left; padding: 0 10px">CONDICIONES ELECTRICAS ACEPTABLES</td>
            <td style="text-align: center">' . ($res["MDF3_8"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF3_8o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.9</td>
            <td style="text-align: left; padding: 0 10px">ESTA EL CENTRO DE DATOS EN EL PLAN DE CONTINGENCIA DE LA UNIDAD</td>
            <td style="text-align: center">' . ($res["MDF3_9"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["MDF3_9o"] . '</td>
        </tr>
    </table>
    <br><br> 

    <table style="width: 100%; border-collapse: collapse" >
        <tr>
            <th style="border: 1px solid black" colspan="6" class="text-center"> ÁREAS DE MEJORA Y ACUERDOS MDF </th>
        </tr>
        <tr style="width: 100%; border: 1px solid black">
            <td style="width: 5%; border: 1px solid black"></td>
            <td style="padding: 20px;border: 1px solid black; width: 100%"></td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="padding: 20px; border: 1px solid black; width: 100%"></td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="padding: 20px; border: 1px solid black; width: 100%"></td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="padding: 20px; border: 1px solid black; width: 100%"></td>
        </tr>
    </table>
    <br> ';
        }

        elseif ($res['tipo_df'] == 'I') {
            $contenido .= '
        <table style="width: 100%; border-collapse: collapse" class="table-bordered">
        <tr>
            <th colspan="6" class="text-center">IDF</th>
        </tr>
         <tr>
            <th style="text-align: center;"><br>No.</th>
            <th style="text-align: center;"><br>EVALUACIÓN AL PERSONAL OPERATIVO Y COORDINADOR</th>
            <th style="text-align: center;" colspan="2"><br>IDF PLANTA ALTA</th>
        </tr>
        <tr>
            <th style="text-align: center">1</th>
            <th style="text-align: center">MEMORIA TECNICA</th>
            <th style="text-align: center">CUMPLE</th>
            <th style="text-align: center">OBSERVACIONES</th>
        </tr>
        <tr>
            <td style="text-align: center;">1.1</td>
            <td style="text-align: left; padding: 0 10px">NOMBRE DEL IDF CORRECTO</td>
            <td style="text-align: center">' . ($res["IDF1_1"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_1o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.2</td>
            <td style="text-align: left; padding: 0 10px">DEFINICIÓN DEL AREA CORRECTA</td>
            <td style="text-align: center">' . ($res["IDF1_2"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_2o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.3</td>
            <td style="text-align: left; padding: 0 10px">DEFINICIÓN DE PAREDES Y PISO</td>
            <td style="text-align: center">' . ($res["IDF1_3"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_3o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.4</td>
            <td style="text-align: left; padding: 0 10px">DEFINICION CORRECTA DE RACKS</td>
            <td style="text-align: center">' . ($res["IDF1_4"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_4o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.5</td>
            <td style="text-align: left; padding: 0 10px">DEFINICION CORRECTA DE SWITCHES</td>
            <td style="text-align: center">' . ($res["IDF1_5"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_5o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.6</td>
            <td style="text-align: left; padding: 0 10px">DEFINICION CORRECTA DE PANELES DE PARCHEO</td>
            <td style="text-align: center">' . ($res["IDF1_6"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_6o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.7</td>
            <td style="text-align: left; padding: 0 10px">DEFINICION CORRECTA DE TRAYECTORIAS</td>
            <td style="text-align: center">' . ($res["IDF1_7"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_7o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.8</td>
            <td style="text-align: left; padding: 0 10px">DEFINICION CORRECTA DE EQUIPOS NO IMSS</td>
            <td style="text-align: center">' . ($res["IDF1_8"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_8o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.9</td>
            <td style="text-align: left; padding: 0 10px">CANALIZACION ADECUADA</td>
            <td style="text-align: center">' . ($res["IDF1_9"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_9o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.10</td>
            <td style="text-align: left; padding: 0 10px">EN ORDEN EL CABLEADO FRONTAL AL RACK (NODOS Y PACHD CORD)</td>
            <td style="text-align: center">' . ($res["IDF1_10"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_10o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.11</td>
            <td style="text-align: left; padding: 0 10px">EN ORDEN EL CABLEADO POSTERIOR AL RACK (NODOS Y PACHD CORD)</td>
            <td style="text-align: center">' . ($res["IDF1_11"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_11o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.12</td>
            <td style="text-align: left; padding: 0 10px">ESTAN IDENTIFICADOS LOS PUERTOS EN LOS SW Y PP</td>
            <td style="text-align: center">' . ($res["IDF1_12"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_12o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.13</td>
            <td style="text-align: left; padding: 0 10px">	¿LA UBICACION DEL IDF ES CORRECTA?</td>
            <td style="text-align: center">' . ($res["IDF1_13"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_13o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.14</td>
            <td style="text-align: left; padding: 0 10px">SE CUENTA CON LOS MAPAS DE TRAYECTORIAS DEL IDF</td>
            <td style="text-align: center">' . ($res["IDF1_14"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_14o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.15</td>
            <td style="text-align: left; padding: 0 10px">SE HA REALIZADO LA LIMPIEZA DE LOS EQUIPOS PERIODICAMENTE</td>
            <td style="text-align: center">' . ($res["IDF1_15"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_15o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">1.16</td>
            <td style="text-align: left; padding: 0 10px">DIRECTORIO ACTUALIZADO DE IPS</td>
            <td style="text-align: center">' . ($res["IDF1_16"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF1_16o"] . '</td>
        </tr>
        <tr>
            <th style="text-align: center">2</th>
            <th style="text-align: left">APLICACIÓN DE CONTROLES DE SEGURIDAD</th>
            <th style="text-align: center">CUMPLE</th>
            <th style="text-align: center">OBSERVACIONES</th>
        </tr>
        <tr>
            <td style="text-align: center;">2.1</td>
            <td style="text-align: left; padding: 0 10px">TIENE UNA BITÁCORA QUE REGISTRE EL ACCESO AL IDF</td>
            <td style="text-align: center">' . ($res["IDF2_1"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF2_1o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.2</td>
            <td style="text-align: left; padding: 0 10px">UPS FUNCIONANDO ADECUADAMENTE</td>
            <td style="text-align: center">' . ($res["IDF2_2"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF2_2o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.3</td>
            <td style="text-align: left; padding: 0 10px">CONEXIÓN DE SERVIDORES A UPS</td>
            <td style="text-align: center">' . ($res["IDF2_3"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF2_3o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.4</td>
            <td style="text-align: left; padding: 0 10px">CONEXIÓN DE SWITCHES A UPS</td>
            <td style="text-align: center">' . ($res["IDF2_4"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF2_4o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.5</td>
            <td style="text-align: left; padding: 0 10px">TODOS LOS EQUIPOS ACTIVOS EN RACK</td>
            <td style="text-align: center">' . ($res["IDF2_5"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF2_5o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.6</td>
            <td style="text-align: left; padding: 0 10px">UBICACIÓN DE SERVIDORES EN GABINETES O RACKS</td>
            <td style="text-align: center">' . ($res["IDF2_6"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF2_6o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.7</td>
            <td style="text-align: left; padding: 0 10px">HA GESTIONADO RESTRINGIR EL AREA Y ACCESO</td>
            <td style="text-align: center">' . ($res["IDF2_7"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF2_7o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.8</td>
            <td style="text-align: left; padding: 0 10px">EL CLIMA ES MENOR DE 23 GRADOS (APROX)</td>
            <td style="text-align: center">' . ($res["IDF2_8"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF2_8o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.9</td>
            <td style="text-align: left; padding: 0 10px">HA GESTIONADO UNA REVISION DE CARGAS ELECTRICAS</td>
            <td style="text-align: center">' . ($res["IDF2_9"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF2_9o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.10</td>
            <td style="text-align: left; padding: 0 10px">EL IDF ESTA UBICADO EN UN AREA CORRECTA</td>
            <td style="text-align: center">' . ($res["IDF2_10"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF2_10o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.11</td>
            <td style="text-align: left; padding: 0 10px">HA GESTIONADO SEÑALETICA Y EXTINTORES EN EL AREA</td>
            <td style="text-align: center">' . ($res["IDF2_11"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF2_11o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">2.12</td>
            <td style="text-align: left; padding: 0 10px">CUENTA CON UN PLAN ANTE UNA CONTINGENCIA</td>
            <td style="text-align: center">' . ($res["IDF2_12"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF2_12o"] . '</td>
        </tr>
        <tr>
            <th style="text-align: center">3</th>
            <th style="text-align: left">APOYOS OBTENIDOS DE LA UNIDAD</th>
            <th style="text-align: center">CUMPLE</th>
            <th style="text-align: center">OBSERVACIONES</th>
        </tr>
        <tr>
            <td style="text-align: center;">3.1</td>
            <td style="text-align: left; padding: 0 10px">SE CUENTA CON UN CONTROL DE ACCESO</td>
            <td style="text-align: center">' . ($res["IDF3_1"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF3_1o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.2</td>
            <td style="text-align: left; padding: 0 10px">LOS TECHOS Y PAREDES (VENTANAS) ESTAN LIMPIOS</td>
            <td style="text-align: center">' . ($res["IDF3_2"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF3_2o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.3</td>
            <td style="text-align: left; padding: 0 10px">EL IDF ESTA INCLUIDO EN EL PROG.DE LIMPIEZA DE LA UNIDAD</td>
            <td style="text-align: center">' . ($res["IDF3_3"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF3_3o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.4</td>
            <td style="text-align: left; padding: 0 10px">EL AREA ES EXCLUSIVA PARA EL IDF</td>
            <td style="text-align: center">' . ($res["IDF3_4"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF3_4o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.5</td>
            <td style="text-align: left; padding: 0 10px">LA ILUMINACION ES ACEPTABLE</td>
            <td style="text-align: center">' . ($res["IDF3_5"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF3_5o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.6</td>
            <td style="text-align: left; padding: 0 10px">EXISTE SEÑALETICA Y EXTINTORES EN EL AREA</td>
            <td style="text-align: center">' . ($res["IDF3_6"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF3_6o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.7</td>
            <td style="text-align: left; padding: 0 10px">LA CLIMATIZACION ES ACEPTABLE ( < 20 GRADOS)</td>
            <td style="text-align: center">' . ($res["IDF3_7"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF3_7o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.8</td>
            <td style="text-align: left; padding: 0 10px">CONDICIONES ELECTRICAS ACEPTABLES</td>
            <td style="text-align: center">' . ($res["IDF3_8"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF3_8o"] . '</td>
        </tr>
        <tr>
            <td style="text-align: center;">3.9</td>
            <td style="text-align: left; padding: 0 10px">ESTA EL IDF EN EL PLAN DE CONTINGENCIA DE LA UNIDAD</td>
            <td style="text-align: center">' . ($res["IDF3_9"] ? '✔️' : 'X') . '</td>
            <td style="text-align: center;">' . $res["IDF3_9o"] . '</td>
        </tr>
    </table>
    <br><br> 

    <table style="width: 100%; border-collapse: collapse" >
        <tr>
            <th style="border: 1px solid black" colspan="6" class="text-center"> ÁREAS DE MEJORA Y ACUERDOS IDF </th>
        </tr>
        <tr style="width: 100%; border: 1px solid black">
            <td style="width: 5%; border: 1px solid black"></td>
            <td style="padding: 20px;border: 1px solid black; width: 100%"></td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="padding: 20px; border: 1px solid black; width: 100%"></td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="padding: 20px; border: 1px solid black; width: 100%"></td>
        </tr>
        <tr>
            <td style="border: 1px solid black"></td>
            <td style="padding: 20px; border: 1px solid black; width: 100%"></td>
        </tr>
    </table>
    <br> ';
        }
    }
    $contenido .= '
    <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
    <tr>
        <td style="width: 33.33%; vertical-align: top; padding: 5px;">
            <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                <tr>
                    <th style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top; height: 150px;">
                        REALIZÓ SUPERVISIÓN
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black; padding: 10px; text-align: center;">NOMBRE Y FIRMA</th>
                </tr>
            </table>
        </td>

        <td style="width: 33.33%; vertical-align: top; padding: 5px;">
            <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                <tr>
                    <th style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top; height: 150px;">
                        SOPORTE DE LA UNIDAD
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black; padding: 10px; text-align: center;">NOMBRE Y FIRMA</th>
                </tr>
            </table>
        </td>

        <td style="width: 33.33%; vertical-align: top; padding: 5px;">
            <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                <tr>
                    <th style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top; height: 150px;">
                        DIRECTOR DE LA UNIDAD
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black; padding: 10px; text-align: center;">NOMBRE Y FIRMA</th>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br>

<table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
    <tr>
        <td style="width: 33.33%; vertical-align: top; padding: 5px;">
            <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                <tr>
                    <th style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top; height: 150px;">
                        ADMINISTRADOR DE LA UNIDAD
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black; padding: 10px; text-align: center;">NOMBRE Y FIRMA</th>
                </tr>
            </table>
        </td>

        <td style="width: 33.33%; vertical-align: top; padding: 5px;">
            <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                <tr>
                    <th style="border: 1px solid black; padding: 5px; text-align: center; vertical-align: top; height: 150px;">
                        REVISIÓN DEL DOCUMENTO
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black; padding: 10px; text-align: center;">NOMBRE Y FIRMA</th>
                </tr>
            </table>
        </td>

        <td style="width: 33.33%; vertical-align: top; padding: 5px;">
            <table style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                <tr>
                    <th style="border: 1px solid black; padding: 20px; text-align: center; height: 150px;">
                        &nbsp;
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black; padding: 10px; text-align: center;">NOMBRE Y FIRMA</th>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>';

    } catch (PDOException $e) {
      $contenido ="OCURRIÓ UN ERROR EN EL SERVIDOR, INTENTA MÁS TARDE O CONSULTA CON EL ADMINISTRADOR".$e;
    } 
  return $contenido;
}
  ?>