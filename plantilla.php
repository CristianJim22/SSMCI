<?php
include_once 'databaseocs.php';
session_start();
//Plantilla HTML para mostrar el reporte en PDF 
ini_set("pcre.backtrack_limit", "5000000");
ini_set('max_execution_time', '30000000');

function getPlantilla($folio, $periodo){
  $contenido ="";
  $periodonum = $periodo;
  $periodo = "mtto".$periodo;
  $db = new Databaseocs();
  try { 
    /*
        $periodonum = $periodo;
        $periodo = "mtto".$periodo;
        $db = new Databaseocs();
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
        $queryp = $db->connect()->prepare('SELECT GROUP_CONCAT(MACADDR) FROM networks WHERE HARDWARE_ID = '.$folio.' AND ('.$condicion.')');
        $queryp->execute();
        $macaddresses = $queryp->fetch(PDO::FETCH_NUM);

        //Insertar mac para generar folio de mantenimiento
        $queryp = $db->connect()->prepare('INSERT INTO '.$periodo.' (macadd) VALUES ("'.$macaddresses[0].'")');
        $queryp->execute();
        //recuperar el folio del manetnimiento
        $folmant = $db->connect()->prepare('SELECT id FROM '.$periodo.' WHERE macadd = "'.$macaddresses[0].'"');
        $folmant->execute();
        $folioMtto = $folmant->fetch(PDO::FETCH_NUM);*/
        /*IMPRESION DE DOCUMENTO*/
        $querymac = $db->connect()->prepare('SELECT macadd FROM '.$periodo.' WHERE id = "'.$folio.'"');
        $querymac->execute();
        $mac = $querymac->fetch(PDO::FETCH_NUM);
        $query = $db->connect()->prepare('SELECT networks.HARDWARE_ID, HW.NAME, HW.IPADDR, C.MANUFACTURER, C.TYPE, C.CPUARCH, B.SMANUFACTURER, B.SMODEL, B.SSN, HW.ARCH, HW.OSNAME, HW.USERID, networks.MACADDR
          FROM networks
          LEFT JOIN hardware HW ON networks.HARDWARE_ID = HW.id
          LEFT JOIN cpus C ON networks.HARDWARE_ID = C.HARDWARE_ID
          LEFT JOIN bios B ON networks.HARDWARE_ID = B.HARDWARE_ID
          WHERE FIND_IN_SET (networks.MACADDR,"'.$mac[0].'")  ORDER BY HW.LASTCOME DESC LIMIT 1');
        $query->execute();

        //datos del periodo
        $queryp = $db->connect()->prepare('SELECT nombre, DATE_FORMAT(desde,"%d/%m/%Y"),  DATE_FORMAT(hasta,"%d/%m/%Y") FROM mtto_periodos WHERE id_periodo = '.$periodonum);
        $queryp->execute();
        $nombresp = $queryp->fetch(PDO::FETCH_NUM);

        foreach ($query as $row){

          $NOMBRE = array('NO','SI');
          $regex = '/^NTE[0-9]{5}WS/i';
          if (preg_match($regex, $row[1]) == 1) {
            $NOMBRE[1] = '(SI*)';
          } else {
            $NOMBRE[0] = '(NO*)';
          }

          //verificar la arquitectura 32 o 64 bits
          $ARQ = array('32 BITS','64 BITS');
          if(strpos($row[9], '64')){
              $ARQ[1] = '(64 BITS*)';
          }elseif(strpos($row[9], '32')){
              $ARQ[0] = '(32 BITS*)';
          }

          //buscar los programas instalados por nombre
          $querysw = $db->connect()->prepare('SELECT software.HARDWARE_ID, UPPER(GROUP_CONCAT(software_name.NAME)) softwareNames FROM software INNER JOIN software_name ON software.NAME_ID = software_name.ID WHERE software.HARDWARE_ID='.$row[0].' GROUP BY software.HARDWARE_ID');
          $querysw->execute();
          $nombressw = $querysw->fetch(PDO::FETCH_NUM);
          
          $flashplayer = array('NO','SI');
          $edge = array('NO','SI');
          $winrar = array('NO','SI');
          $adobe = array('NO','SI');
          $chrome = array('NO','SI');

          if($nombressw){
            if(strpos($nombressw[1], 'FLASH PLAYER') || strpos($nombressw[2], 'FLASH PLAYER')){
              $flashplayer[1] = '(SI*)';
            }else{
              $flashplayer[0] = '(NO*)';
            }

            
            if(strpos($nombressw[1], 'MICROSOFT EDGE') || strpos($nombressw[2], 'MICROSOFT EDGE')){
              $edge[1] = '(SI*)';
            }else{
              $edge[0] = '(NO*)';
            }

            
            if(strpos($nombressw[1], 'WINRAR') || strpos($nombressw[2], 'WINRAR')){
              $winrar[1] = '(SI*)';
            }else{
              $winrar[0] = '(NO*)';
            }

            
            if(strpos($nombressw[1], 'ADOBE ACROBAT READER') || strpos($nombressw[1], 'ADOBE READER')  || strpos($nombressw[2], 'ADOBE ACROBAT READER') || strpos($nombressw[2], 'ADOBE READER')){
              $adobe[1] = '(SI*)';
            }else{
              $adobe[0] = '(NO*)';
            }

            
            if(strpos($nombressw[1], 'GOOGLE CHROME') || strpos($nombressw[2], 'GOOGLE CHROME')){
              $chrome[1] = '(SI*)';
            }else{
              $chrome[0] = '(NO*)';
            }
          }
          
            $contenido = '
              <body class="cuerpo">
                <main>
                    <table style="width: 100%;">
                      <tr>
                          <td>
                            <div id="logo">
                                <img src="dist/img/imsslogo-grey.jpg"  width= "70" height="75">
                            </div>
                          </td>
                          <td>
                            UNIDAD: '.$_SESSION['SSMCI']['unidadnombre'].'<br>
                            DELEGACIÓN ESTATAL DE AGUASCALIENTES<br>
                            COORDINACIÓN DELEGACIONAL DE INFORMÁTICA<br>
                            CHECKLIST DE EQUIPO  DE CÓMPUTO<br>
                          </td>
                          <td> 
                            FOLIO: '.$folio.'<br>PERIODO: '.$nombresp[0].'<br>DEL '.$nombresp[1].' AL '.$nombresp[2].'
                          </td>
                      </tr>
                    </table>
                    <table style="width: 100%;" class="font-10">
                      <tr>
                        <td style="width: 50%; padding: 6px;">
                          <table style="width: 100%; vertical-align: top;" class="table-bordered">
                            <tr>
                              <th style="width: 50%;"></th>
                              <th style="width: 50%;" class="text-center">DATOS</th>
                            </tr>
                            <tr>
                              <td>ANOTAR NOMBRE DEL ÁREA EN <br>“DESCRIPCIÓN DEL EQUIPO”</td>
                              <td>'.$_SESSION['SSMCI']['unidadnombre'].'</td>
                            </tr>
                            <tr>
                              <td>NOMBRE DEL EQUIPO</td>
                              <td>'.$row[1].'</td>
                            </tr>
                            <tr>
                              <td>MARCA</td>
                              <td>'.$row[6].'</td>
                            </tr>
                            <tr>
                              <td>MODELO</td>
                              <td>'.$row[7].'</td>
                            </tr>
                            <tr>
                              <td>N/SERIE</td>
                              <td>'.$row[8].'</td>
                            </tr>
                            <tr>
                              <td>N/INVENTARIO</td>
                              <td></td>
                            </tr>
                            <tr>
                              <td>DIRECCIÓN IP</td>
                              <td>'.$row[2].'</td>
                            </tr>
                            <tr>
                              <td>NOMBRE DEL USUARIO</td>
                              <td>'.$row[11].'</td>
                            </tr>
                          </table>
                          <br>
                          <table style="width: 100%;" class="table-bordered">
                            <tr>
                              <th colspan="6" class="text-center">PRE-CONFIGURACIONES</th>
                            </tr>
                            <tr>
                              <td style="width: 30%;">BORRADO SEGURO</td>
                              <td style="width: 10%;">SI</td>
                              <td style="width: 10%;">NO</td>
                              <td style="width: 30%;">WINDOWS ACTIVADO</td>
                              <td style="width: 10%;">SI</td>
                              <td style="width: 10%;">NO</td>
                            </tr>
                            <tr>
                              <td>NOMBRAMIENTO CORRECTO<br>NTE”XXXXX” WS”XXX”<br>REFERENCIA  INICIALES</td>
                              <td>'.$NOMBRE[1].'</td>
                              <td>'.$NOMBRE[0].'</td>
                              <td>DNS CORRECTOS<br>-172.16.0.____<br>-172.16.0.____<br>-10.102.10.____ <br>-10.102.10.____</td>
                              <td>SI</td>
                              <td>NO</td>
                            </tr>
                          </table>
                          <br>
                          <table style="width: 100%;" class="table-bordered">
                            <tr>
                              <th style="width: 45%;">SOPLETEO Y LIMPIEZA DE EQUIPO</th>
                              <th style="width: 10%;"></th>
                              <th style="width: 45%;">Fecha del Mantenimiento del Equipo:<br> _______/_______/_______</th>
                            </tr>
                          </table>
                          <br>
                          <table style="width: 100%;" class="table-bordered">
                            <tr>
                              <th colspan="6" class="text-center">NUEVO PWD DE CDI</th>
                            </tr>
                            <tr>
                              <td class="text-center">SOPORTE: S0porte*2024 </td>
                              <td class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                              <td class="text-center">ADMINISTRADOR : *@dm1n2o24@*</td>
                              <td class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                              <td class="text-center">VNC: “cdiags”</td>
                              <td class="text-center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            </tr>
                          </table>
                        </td>

                        <td style="width: 50%; padding: 6px;">
                          <table style="width: 100%;" class="table-bordered">
                            <tr>
                              <th colspan="4" class="text-center">CREACIÓN DE USUARIO Y VERIFICACIÓN DE CONTENIDO</th>
                            </tr>
                            <tr>
                              <td style="width: 35%;">Crear Usuario Personal o Genérico<br>(dominio) y depuración de usuarios</td>
                              <td style="width: 15%;"></td>
                              <td style="width: 35%;">Verificar respaldo No música/<br>No fotos personales/No juegos/<br>No material XXX</td>
                              <td style="width: 15%;"></td>
                            </tr>
                            <tr>
                              <td style="width: 35%;">Revisión de MAAGTIC</td>
                              <td style="width: 15%;"></td>
                              <td style="width: 35%;">Instalar en Escritorio DAME IP</td>
                              <td style="width: 15%;"></td>
                            </tr>
                          </table>
                          <br>
                          
                          <table style="width: 100%;" class="table-bordered">
                            <tr>
                              <th colspan="6" class="text-center">VERIFICAR PAQUETERÍA INSTITUCIONAL</th>
                            </tr>
                            <tr>
                              <th class="text-center">PROGRAMA</th>
                              <th colspan="2" class="text-center">INSTALADO</th>
                              <th class="text-center">PROGRAMA</th>
                              <th colspan="2" class="text-center">INSTALADO</th>
                            </tr>
                            <tr>
                              <td style="width: 20%;">ADOBE READER</td>
                              <td style="width: 15%;">'.$adobe[0].'</td>
                              <td style="width: 15%;">'.$adobe[1].'</td>
                              <td style="width: 20%;">JAVA</td>
                              <td style="width: 15%;">NO</td>
                              <td style="width: 15%;">SI</td>
                            </tr>
                            <tr>
                              <td style="width: 20%;">WINRAR</td>
                              <td style="width: 15%;">'.$winrar[0].'</td>
                              <td style="width: 15%;">'.$winrar[1].'</td>
                              <td style="width: 20%;">FLASH PLAYER</td>
                              <td style="width: 15%;">'.$flashplayer[0].'</td>
                              <td style="width: 15%;">'.$flashplayer[1].'</td>
                            </tr>
                            <tr>
                              <td style="width: 20%;">IE/EDGE</td>
                              <td style="width: 15%;">'.$edge[0].'</td>
                              <td style="width: 15%;">'.$edge[1].'</td>
                              <td style="width: 20%;">VERSIÓN WINDOWS 7 O 10</td>
                              <td style="width: 15%;">'.$ARQ[0].'</td>
                              <td style="width: 15%;">'.$ARQ[1].'</td>
                            </tr>
                            <tr rowspan="1">
                              <td colspan="4"></td>
                              <td colspan="2" class="text-center">'.$row[10].'</td>
                            </tr>
                            <tr>
                              <th colspan="6" class="text-center">OTROS</th>
                            </tr>
                            <tr>
                              <td style="width: 20%;">GOOGLE CHROME</td>
                              <td style="width: 15%;">'.$chrome[0].'</td>
                              <td style="width: 15%;">'.$chrome[1].'</td>
                              <td style="width: 20%;">LIGAS SIMF, ECE, RAYOS X, LABORATORIOS, ETC</td>
                              <td style="width: 15%;">NO</td>
                              <td style="width: 15%;">SI</td>
                            </tr>
                            <tr>
                              <td colspan="4">DIAGNOSTICO PC GENERAL HARDWARE</td>
                              <td>BIEN</td>
                              <td>CON FALLAS</td>
                            </tr>
                          </table>
                          <br>
                          <table style="width: 100%;" class="table-bordered">
                            <tr>
                              <th style="width: 30%">OBSERVACIONES</th>
                              <th style="width: 70%"><br><br><br><br><br></th>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                    <br>
                    <table style="width: 100%;" class="table-bordered">
                      <tr>
                        <th style="width: 40%" class="text-center">SOPORTE QUE REALIZÓ EL CHECKLIST<br>NOMBRE, MATRICULA Y FIRMA</th>
                        <th style="width: 20%" class="text-center">FECHA DE REALIZACIÓN</th>
                        <th style="width: 40%" class="text-center">USUARIO QUE RECIBE<BR>NOMBRE, MATRICULA Y FIRMA</th>
                      <tr>
                      <tr>
                        <td><br><br><br></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </table>
                </main>
            </body>';

        }
      

    } catch (PDOException $e) {
      $contenido ="OCURRIÓ UN ERROR EN EL SERVIDOR, INTENTA MÁS TARDE O CONSULTA CON EL ADMINISTRADOR".$e;
    }



  return $contenido;

}

  ?>
        
