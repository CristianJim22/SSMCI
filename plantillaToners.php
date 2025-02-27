<?php
include_once 'databaseocs.php';
session_start();
//Plantilla HTML para mostrar el reporte en PDF 
ini_set("pcre.backtrack_limit", "5000000");
ini_set('max_execution_time', '30000000');

function getPlantilla($folio){
  $contenido ="";
  $periodonum = $periodo;
  $db = new Databaseocs();
  try { 
        $queryp = $db->connect()->prepare('SELECT TS.id, TC.nombre, U.nombre unidad, DATE_FORMAT(TS.fecha_asignado,"%d/%m/%Y"), TS.cantidad, UPPER(A.adscripcion), UPPER(TS.observaciones), UPPER(TS.destinootro)
          FROM toner_salidas TS 
          LEFT JOIN toner_catalogo TC ON TS.tipo = TC.id
          LEFT JOIN mtto_unidades U ON TS.unidad = U.id
          LEFT JOIN mtto_areas A ON TS.destino = A.id
          WHERE TS.id  = '.$folio);
        $queryp->execute();
        $res = $queryp->fetch(PDO::FETCH_NUM);
        $destino="";

            if($res[5] == "OTRO"){
              $destino = $res[7];
            }elseif($res[5] == "CONSULTORIO"){
              $destino = $res[5] ." ".$res[7];
            }else{
              $destino = $res[5];
            }
            $contenido.= '
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
                            UNIDAD: '.$res[2].'<br>
                            DELEGACIÓN ESTATAL DE AGUASCALIENTES<br>
                            COORDINACIÓN DELEGACIONAL DE INFORMÁTICA<br>
                            FORMATO DE RECIBO PARA INSUMOS DE IMPRESIÓN<br>
                          </td>
                          <td> 
                            FOLIO: '.$res[0].'<BR>
                          </td>
                      </tr>
                    </table>
                    <table style="width: 100%; margin-top: 12px;" class="font-10" class="table-bordered">
                      <tr>
                        <td style="padding: 6px;">
                          EL ÁREA DE <u>'.$destino.'</u> RECIBIÓ LA CANTIDA DE <u>'.$res[4]. " ".$res[1].'</u> EL DÍA <u>'.$res[3].'</u>, ADEMÁS, HAGO CONSTAR QUE LOS MATERIALES ANTERIORMENTE REFERIDOS ESTÁN EN PERFECTO ESTADO. 
                        </td>

                      </tr>
                    </table>';

                    if($res[6]!=""){
                      $contenido.= '
                      <table style="width: 100%; margin-top: 12px;" class="font-10" class="table-bordered">
                        <tr>
                          <td style="padding: 6px;">
                            <b>OBSERVACIONES: </b>'.$res[6].'
                          </td>
                        </tr>
                      </table>';
                    }

                    $contenido.= '
                    <br>
                    <table style="width: 100%;" class="table-bordered">
                      <tr>
                        <th style="width: 40%" class="text-center">SOPORTE QUE ENTREGA LOS INSUMOS<br>NOMBRE, MATRICULA Y FIRMA</th>
                        <th style="width: 20%" class="text-center">FECHA DE ENTREGA DE INSUMOS</th>
                        <th style="width: 40%" class="text-center">USUARIO QUE RECIBE LOS INSUMOS<BR>NOMBRE, MATRICULA Y FIRMA</th>
                      <tr>
                      <tr>
                        <td><br><br><br><br><br></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </table>
                </main>
            </body>';

        
      

    } catch (PDOException $e) {
      $contenido ="OCURRIÓ UN ERROR EN EL SERVIDOR, INTENTA MÁS TARDE O CONSULTA CON EL ADMINISTRADOR".$e;
    }



  return $contenido;

}

  ?>
        
