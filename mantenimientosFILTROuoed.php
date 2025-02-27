<?php
   include_once 'databaseocs.php';
   if(!isset($_SESSION)){ 
        session_start(); 
    } 
   if(!isset($_SESSION['SSMCI']['rol'])){
        header('location: index.php');
    }else{
      if($_SESSION['SSMCI']['rol'] != 1){
         header('location: index.php');
      }
   }
   ?>

<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>SSMCI - MANTENIMIENTOS</title>
      <!-- Font Awesome -->
      <link rel="stylesheet" media="all" href="plugins/fontawesome-free/css/all.min.css">
      <!-- Tempusdominus Bootstrap 4 -->
      <link rel="stylesheet" media="all" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
      <!-- iCheck -->
      <link rel="stylesheet" media="all" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
      <!-- JQVMap -->
      <link rel="stylesheet" media="all" href="plugins/jqvmap/jqvmap.min.css">
      <!-- Theme style -->
      <link rel="stylesheet" media="all" href="dist/css/adminlte.css">
      <link rel="stylesheet" media="all" href="dist/css/helpers.css">
      <!-- overlayScrollbars -->
      <link rel="stylesheet" media="all" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
      <!-- Daterange picker -->
      <link rel="stylesheet" media="all" href="plugins/daterangepicker/daterangepicker.css">
      <!-- summernote -->
      <link rel="stylesheet" media="all" href="plugins/summernote/summernote-bs4.min.css">
      <link rel="stylesheet" media="all" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
      <link rel="stylesheet" media="all" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
      <link href="dist/img/logo-cdi.png" rel="icon">
   </head>
   <body class="hold-transition sidebar-mini layout-fixed" id="pagina" data-value="mantenimientos">
      <div class="wrapper">
         <!-- Preloader -->
         <!-- Navbar -->
         <?php include_once('page-format/header.php') ?>
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
            <?php include_once('page-format/sidebar.php') ?>
               
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
                           <li class="breadcrumb-item active">Mantenimientos</li>
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
                        <i class="fas fa-tools mr-1"></i>
                        Mantenimientos
                     </h3>
                     <!-- card tools -->
                     <div class="card-tools">
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal">REGISTRAR NUEVO PERIODO DE MANTENIMIENTO</button>
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
                     <div class="row d-flex justify-content-center">
                        <div class="col-lg-8" id="buttondiv">
                        </div>
                     </div>
                     <div id="invoice" style="flex: 1 1 auto;min-height: 1px;padding: 1.25rem;">
                        <div class="row d-flex justify-content-center">
                           <div class="col-8" id="tablaInformacion">
                           </div>
                        </div>
                        <div class="row d-flex justify-content-center" id="chartsdinamicos">
                           
                        </div>
                     </div>
                     
                  </div>
               </div>
               <!-- /.card -->
            </section>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registrar nuevo periodo de mantenimiento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <form id="solicitud" autocomplete="off" method="post">
                     <div class="modal-body form-group">
                        <div class="row">
                           <div class="col-12">
                              <label>Nombre del periodo</label>
                              <input type="text" class="form-control form-control-sm" id="nombrep" required>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-lg-6">
                              <label>Desde:</label>
                              <input type="date" class="form-control form-control-sm" id="desde" required>
                           </div>
                           <div class="col-lg-6">
                              <label>Hasta</label>
                              <input type="date" class="form-control form-control-sm" id="hasta" required>
                           </div>
                        </div>  
                        <div class="row mt-3">
                           <div class="col-lg-12">
                              <div class="" role="alert" style="display:none;" id="mensajeUsuario">
                                     
                              </div>
                           </div>
                        </div>                 
                     </div>
                     <div class="modal-footer">
                       <button type="submit" class="btn btn-primary" id="guardar">Guardar</button>
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
      <script src="plugins/html2pdf/html2pdf.bundle2.js"></script>
      <script src="plugins/printThis/printThis.js"></script>
   </body>
</html>
<script type="text/javascript">

$(document).ready(function () {
   fillPeriodo();
});

$("#solicitud").submit(function(e){
   $('#guardar').prop('disabled', true);
   e.preventDefault();
   var nuevoPeriodo = {};
   nuevoPeriodo['nombre'] = $('#nombrep').val();
   nuevoPeriodo['desde'] = $('#desde').val();
   nuevoPeriodo['hasta'] = $('#hasta').val();
   $.ajax({
            url: 'fetch_periodosFILTROuoed.php',
            type: 'post',
            data: {nuevoPeriodo:nuevoPeriodo},
            dataType: 'json',
            success:function(data){
               $('#mensajeUsuario').show();
               $('#mensajeUsuario').html(data.message);
               if(data.state){
                  $('#mensajeUsuario').addClass('alert alert-success text-center');
                  setTimeout(function(){
                     fillPeriodo();
                    $('#exampleModal').modal('hide');
                  }, 500);
               }else{
                  $('#mensajeUsuario').addClass('alert alert-danger text-center');
               }
               $('#guardar').prop('disabled', false);
            }
   });

});

$('#exampleModal').on('hidden.bs.modal', function () {
   $('#nombrep').val('');
   $('#desde').val('');
   $('#hasta').val('');
   $('#mensajeUsuario').removeClass().hide();
});

function fillPeriodo(){
   $.ajax({
      url: 'fetch_periodosFILTROuoed.php',
      type: 'post',
      data: {listaPeriodo:""},
      dataType: 'json',
      success:function(data){
         $('#periodos').empty();
         if(data.state){
            $('#periodos').append('<option value="">SELECCIONA UN PERIODO DE MANTENIMIENTO</option>');
            for (var i=0; i<data.data.length; i++) {
               $('#periodos').append('<option value="' + data.data[i][0] + '">' + data.data[i][1] + '</option>');
               }
         }else{
            alert(data.message);
         }
      }
   });
}

$(document).on('change', '#periodos', function () {
   $('#chartsdinamicos').empty();
   $('#tablaInformacion').empty();
   var periodo = $(this).val();
   $.ajax({
      url: 'fetch_periodosFILTROuoed.php',
      type: 'post',
      data: {tableroMtto:periodo},
      dataType: 'json',
      success:function(data){
         if(data.state){
            var datos = data.data;
            datos.forEach(function(registro, index) {
              console.log("Persona " + registro.unidad );
              $('#chartsdinamicos').append('<canvas class="m-1 p-1" style="max-width:300px;max-height:400px;" id="' + registro.unidad + '"></canvas>');

              var ctx = document.getElementById(registro.unidad).getContext('2d');
        
              var myChart = new Chart(ctx, {
                     plugins: [ChartDataLabels],
                     type: 'pie',
                     data:{
                        labels: ['Completado','Completado (Equipos nuevos)','Sin mantenimiento'],
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
                      }*/
                  
              });  
            });

            printtable(data.data);
         }else{
            alert(data.message);
         }
      }
   });
});

function printtable(datos){
   $('#tablaInformacion').empty();
   $('#buttondiv').empty();
   var tabla="";
    
   $('#buttondiv').append('<button class="btn btn-sm btn-primary float-right mb-2" id="descargar">DESCARGAR INFORME <i class="fas fa-download"></i></button>');
   tabla='<table class="table table-sm table-bordered table-hover font-15"><thead><tr><th class="col-2">UNIDAD</th><th class="col-2">TOTAL DE EQUIPOS</th><th class="col-2">EQUIPOS NUEVOS CON MANTENIMIENTO REALIZADO</th><th class="col-2">EQUIPOS CON MANTENIMIENTO REALIZADO</th><th class="col-2">EQUIPOS PENDIENTES DE MANTENIMIENTO</th></tr></thead><tbody>';
   datos.forEach(function(registro, index) {
      tabla+='<tr><td>'+registro.unidad+'</td><td>'+registro.total+'</td><td>'+registro.congarantia+'</td><td>'+registro.completado+'</td><td>'+registro.pendiente+'</td></tr>';
   });
   tabla+='</tbody></table>';
   $('#tablaInformacion').append(tabla);
}

$(document).on('click', '#descargar', function(){
   //$('#tablaInformacion, #chartsdinamicos').printThis();
   var periodo = $('#periodos option:selected').text();
        $('#invoice').printThis({
            header: '<table style="width: 100%;" class="mb-4"><tr><td><div id="logo"><img src="/SSMCI/dist/img/imsslogo-grey.jpg"  width= "70" height="75"></div></td><td>O.O.A.D AGUASCALIENTES<br>COORDINACIÓN DELEGACIONAL DE INFORMÁTICA<br>AVANCE DE MANTENIMIENTOS A EQUIPOS  DE CÓMPUTO<br>PERIODO: <i>'+ periodo +'</i></td></tr></table>',
            importStyle: true,//thrown in for extra measure
            importCSS: true,
            loadCSS: true,
            canvas: true,
            loadCSS: "/SSMCI/dist/css/adminlte.css"

        });

            /*
            let invoice = document.querySelector('#tablaInformacion');
            console.log(invoice);
            console.log(window);
            let html = document.documentElement
              let height = Math.max(invoice.scrollHeight, invoice.offsetHeight,
                               html.clientHeight, html.scrollHeight, html.offsetHeight)
              let heightCM = height / 35.35
            var opt = {
                margin: 1,
                filename: 'export.pdf',
                html2canvas: { dpi: 192, letterRendering: true},
                jsPDF: {
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4',
                }
            };

            html2pdf().from(invoice).set(opt).save();
            */
      
/*
   let invoice = document.querySelector('#tablaInformacion')
   console.log(invoice);
   console.log(window);
   let html = document.documentElement
   let height = Math.max(invoice.scrollHeight, invoice.offsetHeight,
                   html.clientHeight, html.scrollHeight, html.offsetHeight)
   
   let heightCM = height / 35.35
   var opt = {
            

                margin: 10,
                filename: 'export.pdf',
                html2canvas: { dpi: 192, letterRendering: true},
                jsPDF: {orientation: 'portrait', unit: 'mm',format: 'a4',}
   };
   html2pdf().from(invoice).set(opt).save();
*/
});
</script>