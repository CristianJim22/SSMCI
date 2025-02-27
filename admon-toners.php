<?php
include_once 'databaseocs.php';
if (!isset($_SESSION)) {
   session_start();
}
if (!isset($_SESSION['SSMCI']['rol'])) {
   header('location: index.php');
} else {
   if ($_SESSION['SSMCI']['rol'] != 1) {
      header('location: index.php');
   }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>SSMCI - CONTROL DE TONERS Y TAMBORES</title>
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

<body class="hold-transition sidebar-mini layout-fixed" id="pagina" data-value="toners">
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
            <span class="brand-text font-weight-light"><?php echo $_SESSION['SSMCI']['unidadnombre']; ?></span>
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
                        <li class="breadcrumb-item active">Control de toners y tambores</li>
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
                     <i class="fas fa-tint mr-1"></i>
                     Control de toners y tambores
                  </h3>

                  <!-- Filtro de fecha -->
                  <div class="mt-3">
                     <label for="daterange" style="font-weight: bold; font-size: 16px;">Filtrar por fecha:</label>
                     <div class="d-flex flex-column flex-md-row mt-2">
                        <div class="mr-md-3">
                           <label for="fechaDesde" class="d-block text-black">Desde:</label>
                           <input type="date" id="fechaDesde" class="form-control">
                        </div>
                        <div>
                           <label for="fechaHasta" class="d-block text-black">Hasta:</label>
                           <input type="date" id="fechaHasta" class="form-control">
                        </div>
                     </div>
                  </div>
                  <br>

                  <!-- card tools -->
                  <div class="card-tools d-flex">
                     <button class="btn btn-sm btn-primary mr-2" id="descargarReporte">
                        REPORTE DE CONSUMO TONERS <i class="fas fa-download"></i>
                     </button>
                     <button class="btn btn-sm btn-primary mr-2" id="descargar">
                        DESCARGAR INFORME <i class="fas fa-download"></i>
                     </button>
                     <button type="button" class="btn btn-secondary btn-sm" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                     </button>
                  </div>
                  <!-- /.card-tools -->
               </div>
               <div class="card-body">
                  <div class="row d-flex justify-content-center" id="invoice" style="flex: 1 1 auto;min-height: 1px;padding: 1.25rem;">
                     <div class="col-lg-4 col-md-6" id="tablaInformacion">
                     </div>
                     <div class="col-lg-8 col-md-6">
                        <div class="row" id="divcharUnidades">
                        </div>
                        <div class="row" id="divchartAreas">
                        </div>
                     </div>
                  </div>
                  <!--<div class="row" id="chartsdinamicos">
                     </div>-->
               </div>
            </div>
            <!-- /.card -->
         </section>



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
   <script src="plugins/html2pdf/html2pdf.bundle2.js"></script>
   <script src="plugins/printThis/printThis.js"></script>
</body>

</html>
<script type="text/javascript">
   var colorRelleno = ['rgba(124,252,0,0.2)',
      'rgba(0,255,255,0.2)', 'rgba(138,43,226,0.2)', 'rgba(255,20,147,0.2)',
      'rgba(112,128,144,0.2)', 'rgba(255,127,80,0.2)', 'rgba(255,215,0,0.2)',
      'rgba(152,251,152,0.2)', 'rgba(255,0,255,0.2)', 'rgba(188,143,143,0.2)', 'rgba(176,196,222,0.2)', 'rgba(255,0,0,0.2)', 'rgba(255,165,0,0.2)', 'rgba(255,255,0,0.2)',
      'rgba(0,128,0,0.2)', 'rgba(0,0,255,0.2)', 'rgba(128,0,128,0.2)', 'rgba(255,192,203,0.2)',
      'rgba(139,69,19,0.2)', 'rgba(128,0,0,0.2)', 'rgba(255,69,0,0.2)'
   ];

   var colorBorde = ['rgba(124,252,0,1)',
      'rgba(0,255,255,1)', 'rgba(138,43,226,1)', 'rgba(255,20,147,1)',
      'rgba(112,128,144,1)', 'rgba(255,127,80,1)', 'rgba(255,215,0,1)',
      'rgba(152,251,152,1)', 'rgba(255,0,255,1)', 'rgba(188,143,143,1)', 'rgba(176,196,222,1)', 'rgba(255,0,0,1)', 'rgba(255,165,0,1)', 'rgba(255,255,0,1)',
      'rgba(0,128,0,1)', 'rgba(0,0,255,1)', 'rgba(128,0,128,1)', 'rgba(255,192,203,1)',
      'rgba(139,69,19,1)', 'rgba(128,0,0,1)', 'rgba(255,69,0,1)'
   ];

   $(document).ready(function() {
      fillContent();

      $('#fechaDesde, #fechaHasta').on('change', function() {
         fillContent();
      });
   });

   function fillContent() {
      var desde = $('#fechaDesde').val();
      var hasta = $('#fechaHasta').val();
      $.ajax({
         url: 'fetch_toners.php',
         type: 'POST',
         data: {
            tableInsumos: "",
            desde: desde || '',
            hasta: hasta || '',
         },
         dataType: 'json',
         success: function(data) {
            if (data.state) {
               $('#tablaInformacion').html(data.content);
               graficaExistencias(data.Eunidades, data.Etoners, data.Etambores);
               graficarConsumo(data.Cunidades, data.Ctoners, data.Ctambores);
            } else {
               alert(data.message);
            }
         },
         error: function(xhr, status, error) {
            console.error('Error en la petición:', error);
            console.log('Respuesta del servidor:', xhr.responseText);
            alert('Ocurrió un error al cargar los datos.');
         }
      });
   }

   function graficaExistencias(unidades, toners, tambores) {
      $('#divcharUnidades').empty();

      $('#divcharUnidades').append('<canvas class="m-1 p-1" style="max-width:1000px;max-height:400px;" id="chartUnidades"></canvas>');
      const canvas = document.getElementById('chartUnidades');
      var ctx = canvas.getContext('2d');
      var myChart = new Chart(ctx, {
         plugins: [ChartDataLabels],
         type: 'bar',
         data: {
            labels: unidades,
            datasets: [{
                  label: 'Tóners',
                  data: toners,
                  backgroundColor: 'rgba(252, 173, 3, 0.2)',
                  borderColor: 'rgba(252, 173, 3, 1)',
                  borderWidth: 1,
                  borderRadius: 5,
                  datalabels: {
                     anchor: 'end',
                     align: 'start',
                     offset: -14
                  }
               },
               {
                  label: 'Tambores',
                  data: tambores,
                  backgroundColor: 'rgba(34, 189, 206, 0.2)',
                  borderColor: 'rgba(34, 189, 206, 1)',
                  borderWidth: 1,
                  borderRadius: 5,
                  datalabels: {
                     anchor: 'end',
                     align: 'start',
                     offset: -14
                  }
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
                  text: 'TÓNER Y TAMBORES EN EXISTENCIA'
               }
            }
         }

      });

   }

   function graficarConsumo(unidades, toners, tambores) {
      $('#divchartAreas').empty();

      $('#divchartAreas').append('<canvas class="m-1 p-1" style="max-width:1000px;max-height:400px;" id="chartConsumo"></canvas>');
      const canvas = document.getElementById('chartConsumo');
      var ctx = canvas.getContext('2d');
      var myChart = new Chart(ctx, {
         plugins: [ChartDataLabels],
         type: 'bar',
         data: {
            labels: unidades,
            datasets: [{
                  label: 'Tóners',
                  data: toners,
                  backgroundColor: 'rgba(0, 159, 0, 0.2)',
                  borderColor: 'rgba(0, 159, 0, 1)',
                  borderWidth: 1,
                  borderRadius: 5,
                  datalabels: {
                     anchor: 'end',
                     align: 'start',
                     offset: -14
                  }
               },
               {
                  label: 'Tambores',
                  data: tambores,
                  backgroundColor: 'rgba(10, 46, 54, 0.2)',
                  borderColor: 'rgba(10, 46, 54, 1)',
                  borderWidth: 1,
                  borderRadius: 5,
                  datalabels: {
                     anchor: 'end',
                     align: 'start',
                     offset: -14
                  }
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
                  text: 'CONSUMO DE TÓNER Y TAMBORES'
               }
            }
         }

      });

      // Make sure to attach `onclick` to the canvas, **not** the chart instance
      canvas.onclick = (evt) => {
         const res = myChart.getElementsAtEventForMode(
            evt,
            'nearest', {
               intersect: true
            },
            true
         );
         // If didn't click on a bar, `res` will be an empty array
         if (res.length === 0) {
            return;
         }
         // Alerts "You clicked on A" if you click the "A" chart
         alert('Hice click en ' + myChart.data.labels[res[0].index]);
      };
   }

   $(document).on('click', '#descargar', function() {
      //$('#tablaInformacion, #chartsdinamicos').printThis();
      $('#invoice').printThis({
         header: '<table style="width: 100%;" class="mb-4"><tr><td><div id="logo"><img src="/SSMCI/dist/img/imsslogo-grey.jpg"  width= "70" height="75"></div></td><td>O.O.A.D AGUASCALIENTES<br>COORDINACIÓN DELEGACIONAL DE INFORMÁTICA<br>REPORTE DE CONSUMO Y EXISTENCIAS DE INSUMOS PARA IMPRESIÓN<br></td></tr></table>',
         importStyle: true, //thrown in for extra measure
         importCSS: true,
         loadCSS: true,
         canvas: true,
         loadCSS: "/SSMCI/dist/css/adminlte.css"

      });
   });

   $(document).on('click', '#descargarReporte', function() {
      var desde = $('#fechaDesde').val();
      var hasta = $('#fechaHasta').val();

      $.ajax({
         url: 'descargarReporteToners.php',
         type: 'post',
         data: {
            desde: desde || '',
            hasta: hasta || '',
         },
         xhrFields: {
            responseType: 'blob'
         },
         success: function(response) {
            var link = document.createElement('a');
            var url = window.URL.createObjectURL(response);
            link.href = url;
            link.download = "reporte_consumo.xlsx";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            window.URL.revokeObjectURL(url);
         },
         error: function() {
            alert("Hubo un problema generando el archivo.");
         }
      });
   });
</script>