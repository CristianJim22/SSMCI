<?php
include_once 'databaseocs.php';
if (!isset($_SESSION)) {
   session_start();
}
if (!isset($_SESSION['SSMCI']['rol'])) {
   header('location: index.php');
} else {
   if ($_SESSION['SSMCI']['rol'] != 3) {
      header('location: index.php');
   }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>SSMCI - MANTENIMIENTO DE SITES</title>
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

<body class="hold-transition sidebar-mini layout-fixed" id="pagina" data-value="sites">
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
                        <li class="breadcrumb-item active">Mantenimiento de sites</li>
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


         <section class="col-lg-8 connectedSortable mt-3 mx-auto">
            <!-- Calendar -->
            <div class="card bg-gradient-white">
               <div class="card-header border-0">

                  <h3 class="card-title">
                     <i class="fas fa-sitemap"></i>
                     Mantenimiento de sites
                  </h3>
                  <!-- tools card -->
                  <div class="card-tools">
                     <!-- button with a dropdown -->
                     <div class="btn-group dropleft">
                     </div>
                     <button type="button" class="btn btn-light btn-sm" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                     </button>
                  </div>
                  <!-- /. tools -->
               </div>
               <!-- /.card-header -->
               <div class="card-body pt-0">
                  <!--The calendar -->
                  <div class="row">
                     <div class="col-12">
                        <div class="info-box">
                           <span class="info-box-icon bg-info elevation-2" id="msgbackground"><i class="fas fa-tint" id="msgicon"></i></span>
                           <div class="info-box-content">
                              <span class="info-box-text text-weight-bold" id="msgtext">Mensaje del administrador</span>
                              <span class="info-box-number text-center">
                                 <i style="font-size: 25px;"></i>
                                 <small></small>
                              </span>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- /.card-body -->
               </div>
               <!-- /.card -->
         </section>

         <!-- hisotrial -->
         <section class="col-lg-8 connectedSortable mt-3 mx-auto">
            <!-- Calendar -->
            <div class="card bg-gradient-white">
               <div class="card-header border-0">

                  <h3 class="card-title">
                     <i class="fas fa-history"></i>
                     <small>Historial</small>
                  </h3>
               </div>
               <!-- /.card-header -->
               <div class="card-body pt-0" style="overflow-x: scroll;">
                  <table id="cumplimiento" class="display table table-sm table-active table-hover">
                     <thead>
                        <tr>
                           <th>UNIDAD</th>
                           <th>FECHA DE INICIO</th>
                           <th>FECHA DE TERMINACION</th>
                           <th>COMPLETO</th>
                           <th>OPCIONES</th>
                        </tr>
                     </thead>
                  </table>
               </div>
            </div>
            <!-- /.card -->
         </section>
         <!-- fin historial-->

         <!-- Modal -->
         <div class="modal fade" id="modalDFS" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <div class="modal-body" id="listadoDFS">

                  </div>
                  <div class="modal-footer">
                  </div>
               </div>
            </div>
         </div>

         <!-- Modal comprobar-->
         <div class="modal fade" id="modalComprobarSites" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel">Guardar comprobante de insumo recibido</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                     </button>
                  </div>
                  <form id="comprobarSites" autocomplete="off" method="post">
                     <input type="hidden" id="idEntrega" name="idEntrega" />
                     <div class="modal-body form-group">
                        <div class="row">
                           <div class="col-12" id="confirmacionText">
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-12 p-3">
                              <input type="file" class="form-control" id="archivo" name="archivo" required />
                           </div>
                        </div>
                     </div>
                     <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnComprobar">Guardar</button>
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
   $(document).ready(function() {
      registroFaltante();
      cumplimiento();
      $('#cumplimiento thead tr')
         .clone(true)
         .addClass('filters')
         .appendTo('#cumplimiento thead');

      var table = $('#cumplimiento').DataTable({
         leguaje: {
            url: 'plugins/datatables-language/es-ar.json' //Ubicacion del archivo con el json del idioma.
         },
         ajax: {
            url: 'fetch_sites.php',
            type: 'POST',
            data: {
               historial: ""
            },
            dataType: 'json',
         },
         columns: [{
               title: "UNIDAD"
            },
            {
               title: "FECHA DE INICIO"
            },
            {
               title: "FECHA DE FINALIZACIÓN"
            },
            {
               title: "COMPLETO"
            },
            {
               title: "OPCIONES"
            }
         ],
         orderCellsTop: true,
         fixedHeader: true,
         searching: true,
         order: [
            [1, 'desc']
         ],

         initComplete: function() {
            cargarContadores();
            var api = this.api();
            // For each column
            api.columns().eq(0).each(function(colIdx) {
               // Set the header cell to contain the input element
               var cell = $('.filters th').eq($(api.column(colIdx).header()).index());
               var title = $(cell).text();
               $(cell).html('<input type="text" placeholder="' + title + '" />');
               // On every keypress in this input
               $('input', $('.filters th').eq($(api.column(colIdx).header()).index()))
                  .off('keyup change')
                  .on('keyup change', function(e) {
                     e.stopPropagation();
                     // Get the search value
                     $(this).attr('title', $(this).val());
                     var regexr = '({search})'; //$(this).parents('th').find('select').val();
                     var cursorPosition = this.selectionStart;
                     // Search the column for that value
                     api
                        .column(colIdx)
                        .search((this.value != "") ? regexr.replace('{search}', '(((' + this.value + ')))') : '', this.value != '', this.value != '')
                        .draw();
                     $(this).focus()[0].setSelectionRange(cursorPosition, cursorPosition);
                  });
            });
         },
      });

      //aqui
      $(document).on('click', '.reimprimir', function() {
         var id = $(this).data('value');
         descargar(id);
      });

      $(document).on('click', '.comprobarSites', function() {
         $('#modalComprobarSites').modal('show');
         var texto = '<b>UNIDAD: </b>' + $(this).parent().siblings().eq(0).text() + '<br>';
         texto += '<b>FECHA DE INICIO: </b>' + $(this).parent().siblings().eq(1).text() + '<br>';
         texto += '<b>FECHA DE FINALIZACIÖN: </b>' + $(this).parent().siblings().eq(2).text() + '<br>';
         texto += '<b>COMPLETO: </b>' + $(this).parent().siblings().eq(3).text() + '<br>';
         $('#confirmacionText').html(texto);
         $('#idEntrega').val($(this).attr('data-value'));
      });

      $("#comprobarSites").on('submit', (function(e) {
         e.preventDefault();
         $('#btnComprobar').prop('disabled', true);
         $.ajax({
            url: "fetch_sites.php",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
               alert(data.message);
               if (data.state) {
                  $('#modalComprobarSites').modal('hide');
                  recargarDatos();
                  $('#btnComprobar').prop('disabled', false);
               }
            }
         });
      }));

      function recargarDatos() {
         cargarContadores();
         table.clear().draw();
         $('#cumplimiento').DataTable().ajax.reload();
      }

   });

   function cargarContadores() {
      $.ajax({
         url: 'fetch_sites.php',
         method: 'post',
         data: {
            cantSites: ""
         },
         dataType: 'json',
         success: function(data) {
            $("#cantSites").text(data.cantSites);
         }
      });
   }

   function cumplimiento() {
      $.ajax({
         url: 'fetch_sites.php',
         method: 'post',
         data: {
            cumplimiento: ""
         },
         dataType: 'json',
         success: function(data) {
            $("#msgbackground").removeClass().addClass(data.msgbackground),
               $("#msgicon").removeClass().addClass(data.msgicon),
               $("#msgtext").html(data.msgtext);
            $("#msgtext").append("<br>" + data.boton);

         }
      });
   }

   $(document).on('click', '#regMttoSites', function() {
      $('#modalDFS').modal('show');
      registroFaltante();
   });

   function registroFaltante() {
      $.ajax({
         url: 'fetch_sites_nav.php',
         method: 'post',
         data: {
            registroFaltante: ""
         },
         dataType: 'json',
         success: function(data) {
            $("#listadoDFS").html(data.result);
         }
      });
   }

   function registroFaltante() {
      $.ajax({
         url: 'fetch_sites_nav.php',
         method: 'post',
         data: {
            registroFaltante: ""
         },
         dataType: 'json',
         success: function(data) {
            $("#listadoDFS").html(data.result);
         }
      });
   }


   function descargar(folio) {
      alert(folio);
      win = window.open("reporteSites.php?imprimirSites=" + folio, '_blank');
      $(win.document).ready(function() {});
   }

   /*
   //DISABLE INSPECT 
   document.addEventListener('contextmenu', (e) => e.preventDefault());

   function ctrlShiftKey(e, keyCode) {
     return e.ctrlKey && e.shiftKey && e.keyCode === keyCode.charCodeAt(0);
   }

   document.onkeydown = (e) => {
     // Disable F12, Ctrl + Shift + I, Ctrl + Shift + J, Ctrl + U
     if (
       event.keyCode === 123 ||
       ctrlShiftKey(e, 'I') ||
       ctrlShiftKey(e, 'J') ||
       ctrlShiftKey(e, 'C') ||
       (e.ctrlKey && e.keyCode === 'U'.charCodeAt(0))
     )
       return false;
   };
   */
</script>