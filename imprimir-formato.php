<?php
   include_once 'databaseocs.php';
   if(!isset($_SESSION)){ 
        session_start(); 
    } 
   if(!isset($_SESSION['SSMCI']['rol'])){
        header('location: index.php');
    }else{
      if($_SESSION['SSMCI']['rol'] != 3){
         header('location: index.php');
      }
   }
   ?>

<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>SSMCI - IMPRIMIR CHECKLIST</title>
      <!-- Google Font: Source Sans Pro
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> -->
      <!-- Font Awesome -->
      <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
      <!-- Ionicons -->
      <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
      <!-- Tempusdominus Bootstrap 4 -->
      <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
      <!-- iCheck -->
      <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
      <!-- JQVMap -->
      <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="dist/css/adminlte.css">
      <!-- overlayScrollbars -->
      <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
      <!-- Daterange picker -->
      <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
      <!-- summernote -->
      <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">

      <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
      <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
      <link href="dist/img/logo-cdi.png" rel="icon">
   </head>
   <body class="hold-transition sidebar-mini layout-fixed" id="pagina" data-value="imprimir">
      <div class="wrapper">
         <?php include_once('page-format/header.php'); ?>
         <!-- /.navbar -->
         <!-- Main Sidebar Container -->
         <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index.php" class="brand-link">
            <img src="dist/img/imsslogo.png" alt="IMSS" class="brand-image img-rounded elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">SSMCI</span>
            </a>
            <a href="index.php" class="brand-link text-center text-warning" style="font-size: 17px;">
            <span class="brand-text font-weight-light"><?php echo $_SESSION['SSMCI']['unidadnombre'];?></span>
            </a>
            <!-- Sidebar -->
               <!-- Sidebar user panel (optional) -->
               <?php include_once('page-format/sidebar.php') ?>
               <!-- /.sidebar-menu -->
            <!-- /.sidebar -->
         </aside>
         <!-- Content Wrapper. Contains page content -->
         <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
               <div class="container-fluid">
                  <div class="row mb-2">
                     <div class="col-sm-6">
                        <h1 class="m-0"></h1>
                     </div>
                     <!-- /.col -->
                     <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                           <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                           <li class="breadcrumb-item active">Imprimir checklist</li>
                        </ol>
                     </div>
                     <!-- /.col -->
                  </div>
                  <!-- /.row -->
               </div>
               <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
            <!-- Main content -->


            <section class="col-lg-12 connectedSortable mt-3">
               <!-- Map card -->
               <div class="card" style="overflow-x: scroll;">
                  <div class="card-header border-0">
                     <h3 class="card-title">
                        <i class="fas fa-file-alt mr-1"></i>
                        Control de tornos y tambores
                     </h3>
                     <!-- card tools -->
                     <div class="card-tools">
                        <button type="button" class="btn btn-secondary btn-sm" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                     </div>
                     <!-- /.card-tools -->
                  </div>
                  <div class="card-body">
                     <div class="row form-group">
                        <div class="col-lg-4">
                           <label>Selecciona un periodo de mantenimiento</label>
                           <select class="form-control form-control-sm" id="periodos">
                              
                           </select>
                        </div>
                     </div>
                     <div class="row d-flex justify-content-center" id="chartsdinamicos">
                        
                     </div>
                     <div class="row">
                        <div class="col-12">
                           <table id="example" class="display table table-sm table-active table-hover" >
                              <thead>
                                 <tr>
                                    <th>FOLIO MTTO</th>
                                    <th>ID OCS</th>
                                    <th>NOMBRE DEL EQUIPO</th>
                                    <th>DIRECCIÓN MAC</th>
                                    <th>DESCRIPCIÓN</th>
                                    <th>NÚMERO DE SERIE</th>
                                    <th>OPCIONES</th>
                                 </tr>
                              </thead>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
               <!-- /.card -->
            </section>

            <!-- Modal validación -->
            <div class="modal fade" id="validacion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Validar mantenimiento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form id="validar" autocomplete="off" method="post">
                  <div class="modal-body form-group">
                     <div class="row m-2">
                        <div class="col-lg-2">
                           <label class="font-weight-bold">Folio:</label>
                           <input type="text" id="folio" class="form-control" readonly>
                        </div>
                        <div class="col-lg-10 d-flex justify-content-center align-items-center">
                           <label class="font-weight-normal"><input type="checkbox" id="garantia" value="1">ESTE EQUIPO CUENTA CON GARANTIA</label>
                        </div>
                     </div>
                     <div id="divInformacion">
                        <div class="row m-2">
                           <div class="col-12 bg-primary rounded-pill text-center">
                              PRE-CONFIGURACIONES
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-lg-8 font-weight-bold">
                              BORRADO SEGURO
                           </div>
                           <div class="col-lg-4">
                              <label class="font-weight-normal"><input type="radio" name="borrado" value="1" required>SI</label>
                              <label class="font-weight-normal" class="mr-4"><input type="radio" name="borrado" value="0">NO</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-lg-8 font-weight-bold">
                              WINDOWS ACTIVADO
                           </div>
                           <div class="col-lg-4">
                              <label class="font-weight-normal"><input type="radio" name="windows" value="1" required>SI</label>
                              <label class="font-weight-normal" class="mr-4"><input type="radio" name="windows" value="0">NO</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-lg-8 font-weight-bold">
                              NOMBRAMIENTO CORRECTO  
                              <small>NTE”XXXXX” WS”XXX” REFERENCIA  INICIALES</small>
                           </div>
                           <div class="col-lg-4">
                              <label class="font-weight-normal"><input type="radio" name="nombramiento" value="1" required>SI</label>
                              <label class="font-weight-normal" class="mr-4"><input type="radio" name="nombramiento" value="0">NO</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-lg-8 font-weight-bold">
                              DNS CORRECTOS
                              <small>172.16.0.35  - 172.16.0.15 10.102.10.15 – 10.102.10.10</small>
                           </div>
                           <div class="col-lg-4">
                              <label class="font-weight-normal"><input type="radio" name="dns" value="1" required>SI</label>
                              <label class="font-weight-normal" class="mr-4"><input type="radio" name="dns" value="0">NO</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-12 bg-primary rounded-pill text-center">
                              CREACIÓN DE USUARIO Y VERIFICACIÓN DE CONTENIDO
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-lg-8 font-weight-bold">
                              Crear Usuario Personal o Genérico (dominio) y depuración de usuarios
                           </div>
                           <div class="col-lg-4">
                              <label class="font-weight-normal"><input type="checkbox" id="usuario" value="1">REALIZADO</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-lg-8 font-weight-bold">
                              Verificar respaldo No música/No fotos personales/No juegos/No material XXX
                           </div>
                           <div class="col-lg-4">
                              <label class="font-weight-normal"><input type="checkbox" id="respaldo" value="1">REALIZADO</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-lg-8 font-weight-bold">
                              Revisión de MAAGTIC
                           </div>
                           <div class="col-lg-4">
                              <label class="font-weight-normal"><input type="checkbox" id="maagtic" value="1">REALIZADO</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-lg-8 font-weight-bold">
                              Instalar en Escritorio DAME IP
                           </div>
                           <div class="col-lg-4">
                              <label class="font-weight-normal"><input type="checkbox" id="dameip" value="1">REALIZADO</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-12 bg-primary rounded-pill text-center">
                              NUEVO PWD DE CDI
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-lg-8 font-weight-bold">
                              SOPORTE: S0porte*2024
                           </div>
                           <div class="col-lg-4">
                              <label class="font-weight-normal"><input type="checkbox" id="password" value="1">REALIZADO</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-lg-8 font-weight-bold">
                              ADMINISTRADOR : *@dm1n2o24@
                           </div>
                           <div class="col-lg-4">
                              <label class="font-weight-normal"><input type="checkbox" id="administrador" value="1">REALIZADO</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-lg-8 font-weight-bold">
                              VNC: “cdiags”
                           </div>
                           <div class="col-lg-4">
                              <label class="font-weight-normal"><input type="checkbox" id="vnc" value="1">REALIZADO</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-12 bg-primary rounded-pill text-center">
                              VERIFICAR PAQUETERÍA INSTITUCIONAL 
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-3 font-weight-bold">
                              PROGRAMA
                           </div>
                           <div class="col-3 font-weight-bold">
                              INSTALADO
                           </div>
                           <div class="d-none d-lg-block col-3 font-weight-bold">
                              PROGRAMA
                           </div>
                           <div class="d-none d-lg-block col-3 font-weight-bold">
                              INSTALADO
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-3 font-weight-bold">
                              ADOBE  READER
                           </div>
                           <div class="col-3">
                              <label class="font-weight-normal"><input type="radio" name="adobe" value="1" required>SI</label>
                              <label class="font-weight-normal" class="mr-4"><input type="radio" name="adobe" value="0">NO</label>
                           </div>
                           <div class="col-3 font-weight-bold">
                              WINRAR
                           </div>
                           <div class="col-3">
                              <label class="font-weight-normal"><input type="radio" name="winrar" value="1" required>SI</label>
                              <label class="font-weight-normal" class="mr-4"><input type="radio" name="winrar" value="0">NO</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-3 font-weight-bold">
                              IE/EDGE    
                           </div>
                           <div class="col-3">
                              <label class="font-weight-normal"><input type="radio" name="ieedge" value="1" required>SI</label>
                              <label class="font-weight-normal" class="mr-4"><input type="radio" name="ieedge" value="0">NO</label>
                              
                           </div>
                           <div class="col-3 font-weight-bold">
                              JAVA
                           </div>
                           <div class="col-3">
                              <label class="font-weight-normal"><input type="radio" name="java" value="1" required>SI</label>
                              <label class="font-weight-normal" class="mr-4"><input type="radio" name="java" value="0">NO</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-3 font-weight-bold">
                              FLASH PLAYER
                           </div>
                           <div class="col-3">
                              <label class="font-weight-normal"><input type="radio" name="flashplayer" value="1" required>SI</label>
                              <label class="font-weight-normal" class="mr-4"><input type="radio" name="flashplayer" value="0">NO</label>
                           </div>
                           <div class="col-3 font-weight-bold">
                              VERSIÓN WINDOWS 7 O 10
                           </div>
                           <div class="col-3">
                              <label class="font-weight-normal"><input type="radio" name="windowsv" value="32" required>32 bits</label>
                              <label class="font-weight-normal" class="mr-4"><input type="radio" name="windowsv" value="64">64 bits</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-3 font-weight-bold">
                              GOOGLE CHROME
                           </div>
                           <div class="col-3">
                              <label class="font-weight-normal"><input type="radio" name="chrome" value="1" required>SI</label>
                              <label class="font-weight-normal" class="mr-4"><input type="radio" name="chrome" value="0">NO</label>
                           </div>
                           <div class="col-3 font-weight-bold">
                              LIGAS SIMF, ECE, RAYOS X, LABORATORIOS, ETC
                           </div>
                           <div class="col-3">
                              <label class="font-weight-normal"><input type="radio" name="ligas" value="1" required>SI</label>
                              <label class="font-weight-normal" class="mr-4"><input type="radio" name="ligas" value="0">NO</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-6 font-weight-bold">
                              DIAGNOSTICO PC GENERAL HARDWARE
                           </div>
                           <div class="col-6">
                              <label class="font-weight-normal"><input type="radio" name="diagnostico" value="BIEN" required>BIEN</label>
                              <label class="font-weight-normal" class="mr-4"><input type="radio" name="diagnostico" value="CON FALLAS">CON FALLAS</label>
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-12">
                              <label class="font-weight-bold">SOPLETEO Y LIMPIEZA DE EQUIPO</label>
                              <label class="font-weight-normal"><input type="checkbox" id="sopleteo" value="1">REALIZADO</label>
                           </div>
                           <div class="col-12">
                              <label class="font-weight-bold">FECHA DE MANTENIMIENTO DEL EQUIPO</label>
                              <input type="date" id="fechasopleteo" class="form-control form-control-sm" style="width: 140px;">
                           </div>
                        </div>
                        <div class="row m-2">
                           <div class="col-12 font-weight-bold">
                              OBSERVACIONES
                              <textarea class="form-control" id="observaciones"></textarea>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" id="guardar" class="btn btn-primary">Guardar</button>
                  </div>

                  </form>
                </div>
              </div>
            </div>

            <!-- /.content -->
      
         </div>
         <!-- /.content-wrapper -->
         <?php include_once('page-format/footer.php') ?>
         <!-- Control Sidebar -->
         <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
         </aside>
         <!-- /.control-sidebar -->
      </div>
      <!-- ./wrapper -->
      <!-- jQuery -->
      <script src="plugins/jquery/jquery.min.js"></script>
      <!-- jQuery UI 1.11.4 -->
      <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
      <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
      <script>
         $.widget.bridge('uibutton', $.ui.button)
      </script>
      <!-- Bootstrap 4 -->
      <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
      <!-- jQuery Knob Chart -->
      <script src="plugins/jquery-knob/jquery.knob.min.js"></script>
      <!-- daterangepicker -->
      <script src="plugins/moment/moment.min.js"></script>
      <script src="plugins/daterangepicker/daterangepicker.js"></script>
      <!-- Tempusdominus Bootstrap 4 -->
      <script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
      <!-- Summernote -->
      <script src="plugins/summernote/summernote-bs4.min.js"></script>
      <!-- overlayScrollbars -->
      <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
      <!-- AdminLTE App -->
      <script src="dist/js/adminlte.js"></script>
      <!-- AdminLTE dashboard demo (This is only for demo purposes) 
      <script src="dist/js/pages/dashboard.js"></script>-->

      <script src="dist/js/menu-activador.js"></script>

      <!-- DataTables  & Plugins -->
      <script src="plugins/datatables/jquery.dataTables.min.js"></script>
      <script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
      <script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
      <script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
      <script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
      <script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
      <script src="plugins/jszip/jszip.min.js"></script>
      <script src="plugins/pdfmake/pdfmake.min.js"></script>
      <script src="plugins/pdfmake/vfs_fonts.js"></script>
      <script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
      <script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
      <script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
      <script src="plugins/qr/qrcode.js"></script>
      <script src="plugins/html2canvas/html2canvas.min.js"></script><!-- Indicators JS -->
      <script src="plugins/html2canvas/html2canvas.js"></script><!-- Indicators JS -->
      <script src="plugins/chart.js/chart.js"></script>
      <script src="plugins/chart.js/chartjs-plugin.js"></script>
      <script src="plugins/chart.js/chart.js"></script>
      <script src="plugins/chart.js/chartjs-plugin.js"></script>
   </body>
</html>
<script type="text/javascript">
var seleccion=0;

$(document).ready(function () {
   fillPeriodo();

   // Setup - add a text input to each footer cell
    $('#example thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#example thead');
 
   var table = $('#example').DataTable({
      language: {
         url: 'plugins/datatables-language/es-ar.json' //Ubicacion del archivo con el json del idioma.
      },
      /*ajax:{
         url: 'fetch_imprimir.php',
         type: 'post',
         data: {listarEquipos:"mtto"+seleccion},
         dataType: 'json',
      },*/
      orderCellsTop: true,
      fixedHeader: true,
      initComplete: function() {
         var api = this.api();
         // For each column
         api.columns().eq(0).each(function(colIdx) {
            // Set the header cell to contain the input element
               var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
               var title = $(cell).text();
               $(cell).html( '<input type="text" placeholder="'+title+'" />' );
               // On every keypress in this input
               $('input', $('.filters th').eq($(api.column(colIdx).header()).index()) )
                  .off('keyup change')
                  .on('keyup change', function (e) {
                  e.stopPropagation();
                  // Get the search value
                  $(this).attr('title', $(this).val());
                  var regexr = '({search})'; //$(this).parents('th').find('select').val();
                  var cursorPosition = this.selectionStart;
                  // Search the column for that value
                  api
                     .column(colIdx)
                     .search((this.value != "") ? regexr.replace('{search}', '((('+this.value+')))') : "", this.value != "", this.value == "")
                     .draw();
                  $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
                  });
         });
      },
   });


   $(document).on('change', '#periodos', function () {
      $('#chartsdinamicos').empty();
      table.rows().remove().draw();

      seleccion = $(this).val();
      $.ajax({
         url: 'fetch_periodos.php',
         type: 'post',
         data: {tableroMtto:seleccion},
         dataType: 'json',
         success:function(data){
            if(data.state){
               var datos = data.data;
               datos.forEach(function(registro, index) {
                 //console.log("UNIDAD: " + registro.unidad );
                 $('#chartsdinamicos').append('<div class="col-lg-3"><canvas id="' + registro.unidad + '"></canvas></div>');

                 var ctx = document.getElementById(registro.unidad).getContext('2d');
           
                 var myChart = new Chart(ctx, {
                        plugins: [ChartDataLabels],
                        type: 'pie',
                        data:{
                           labels: ['Completado','Con garantia','Sin mantenimiento'],
                           datasets: [
                              {
                                 label: 'Dataset 1',
                                 data: [registro.completado, registro.congarantia,registro.pendiente],
                                 backgroundColor: ['rgba(41, 121, 255, 0.5)', 'rgba(61,250,64,0.5)','rgba(252, 94, 3, 0.5)'],
                              }
                           ]
                        },
                        options: {
                           responsive: true,
                           plugins: {
                              legend: {
                                position: 'top',
                              },
                              title: {
                                display: true,
                                text: ['Avance en ' + registro.unidad, registro.completado + ' mantenimientos de un total de ' + registro.total + ' (' + registro.porcentaje + '%)']
                              }
                           }
                          },
                         /*options: {
                             plugins: {
                                 title: {
                                     display: true,
                                     text: 'DESEMPEÑO PTDAM (DEL ' + desdef + ' AL ' + hastaf + ')'
                                 }
                             },
                             indexAxis: 'y',
                             scales: {
                                 y: {
                                     beginAtZero: true
                                 }
                             },
                             legend: {
                               display: false
                             },
                             responsive: true,
                             maintainAspectRatio : false                
                         }
                     */
                 });  
               });
               loadtable();
            }else{
               alert(data.message);
            }
         }
      });
   });


   function loadtable(){
      
      $.ajax({
            url: 'fetch_imprimir.php',
            type: 'post',
            data: {listarEquipos:seleccion},
            dataType: 'json',
           success: function (data) {
                  data.data.forEach(function(registro, index) {
                     table.row.add( [registro.foliomtto,registro.hwid,registro.nombreequipo,registro.macadd, registro.descripcion, registro.ssn, registro.completo]).draw();
                  });

                   
    
           }
       });
   }


$("#validar").submit(function(e){
      $('#guardar').prop('disabled', true);
      e.preventDefault();
      var validacion = {};
      validacion['periodos'] = $('#periodos').val();
      validacion['folio'] = $('#folio').val();

      validacion['borrado'] = $('input[name=borrado]:checked').val();
      validacion['windows'] = $('input[name=windows]:checked').val();
      validacion['nombramiento'] = $('input[name=nombramiento]:checked').val();
      validacion['dns'] = $('input[name=dns]:checked').val();

      if($('#usuario').is(':checked')){
         validacion['usuario'] = 1;
      }else{
         validacion['usuario'] = 0;
      }

      if($('#respaldo').is(':checked')){
         validacion['respaldo'] = 1;
      }else{
         validacion['respaldo'] = 0;
      }

      if($('#maagtic').is(':checked')){
         validacion['maagtic'] = 1;
      }else{
         validacion['maagtic'] = 0;
      }

      if($('#dameip').is(':checked')){
         validacion['dameip'] = 1;
      }else{
         validacion['dameip'] = 0;
      }

      if($('#password').is(':checked')){
         validacion['password'] = 1;
      }else{
         validacion['password'] = 0;
      }

      if($('#administrador').is(':checked')){
         validacion['administrador'] = 1;
      }else{
         validacion['administrador'] = 0;
      }

      if($('#vnc').is(':checked')){
         validacion['vnc'] = 1;
      }else{
         validacion['vnc'] = 0;
      }

      if($('#garantia').is(':checked')){
         validacion['garantia'] = 1;
      }else{
         validacion['garantia'] = 0;
      }

      validacion['adobe'] = $('input[name=adobe]:checked').val();
      validacion['winrar'] = $('input[name=winrar]:checked').val();
      validacion['ieedge'] = $('input[name=ieedge]:checked').val();
      validacion['java'] = $('input[name=java]:checked').val();
      validacion['flashplayer'] = $('input[name=flashplayer]:checked').val();
      validacion['windowsv'] = $('input[name=windowsv]:checked').val();
      validacion['chrome'] = $('input[name=chrome]:checked').val();
      validacion['ligas'] = $('input[name=ligas]:checked').val();
      validacion['diagnostico'] = $('input[name=diagnostico]:checked').val();
      if($('#sopleteo').is(':checked')){
         validacion['sopleteo'] = 1;
      }else{
         validacion['sopleteo'] = 0;
      }

      validacion['fechasopleteo'] = $('#fechasopleteo').val();
      if($('#fechasopleteo').val()==""){
         validacion['fechasopleteo']="0000-00-00";

      }
      validacion['observaciones'] = $('#observaciones').val();
      
      $.ajax({
         url: 'fetch_imprimir.php',
         type: 'post',
         data: {"validacion":validacion},
         dataType: 'json',
         success:function(data){
            alert(data.message);
            if(data.state){
               table.rows().remove().draw();
               //loadtable();
               $("#periodos").trigger("change");

               $('#validacion').modal('hide');
            }else{

            }
            $('#guardar').prop('disabled', false);
         }
      });
      
   });


   $(document).on('click', '.imprimir', function () {
      var idocs = $(this).parent().siblings().eq(1).text();
      var periodo = $('#periodos').val();
      $.ajax({
         url: 'fetch_imprimir.php',
         type: 'post',
         data: {generarFolio:idocs, periodo:periodo},
         dataType: 'json',
         success:function(data){
            if(data.state){
               descargar(data.foliomtto,periodo);
               //alert(data.state);
            }else{
               alert(data.message);
               window.location.reload();
            }
         }
      });
   
   });

   function descargar(folio, periodo){
      win = window.open("reportePDF.php?imprimir="+folio+"&periodo="+periodo,'_blank');
      $(win.document).ready(function(){
         table.rows().remove().draw();
         loadtable();
      });
   }

//fin onload
});

$(document).on('click', '.reimprimir', function () {
   var folio = $(this).parent().siblings().eq(0).text();
   var periodo = $('#periodos').val();
   win = window.open("reportePDF.php?imprimir="+folio+"&periodo="+periodo,'_blank');
   $(win.document).ready(function(){
      table.rows().remove().draw();
      loadtable();
   });
});

function fillPeriodo(){
   $.ajax({
      url: 'fetch_periodos.php',
      type: 'post',
      data: {listaPeriodo:""},
      dataType: 'json',
      success:function(data){
         $('#periodos').empty();
         if(data.state){
            $('#periodos').append('<option value="0">SELECCIONA UN PERIODO DE MANTENIMIENTO</option>');
            for (var i=0; i<data.data.length; i++) {
               $('#periodos').append('<option value="' + data.data[i][0] + '">' + data.data[i][1] + '</option>');
               }
         }else{
            alert(data.message);
         }
      }
   });
}



$(document).on('click', '.validar', function () {
   var folio = $(this).parent().siblings(":first").text();
   $("#folio").val(folio);
   $('#validacion').modal('show');
});

$('#validacion').on('hidden.bs.modal', function () {
   $('input[type=radio]').prop('checked', false);
   $('input[type=checkbox]').prop('checked', false);
   $('#folio').val("");
   $('#fechasopleteo').val("");
   $('#observaciones').val("");
   $('#garantia').prop('checked', false);
   $("#garantia").trigger("change");

});

$('#garantia').change(function(){
   if($(this).is(':checked')){
      $('#divInformacion').hide();
      $('input[type=radio]').prop('required', false);
   }else{
      $('#divInformacion').show();
      $('input[type=radio]').prop('required', true);
   }
});
</script>