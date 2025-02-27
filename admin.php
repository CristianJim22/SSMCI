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
      <title>SSMCI - ADMINISTRADOR</title>
      <!-- Font Awesome -->
      <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
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
   <body class="hold-transition sidebar-mini layout-fixed" id="pagina" data-value="usuarios">
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
                           <li class="breadcrumb-item active">Usuarios</li>
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
                        <i class="fas fa-user mr-1"></i>
                        Usuarios
                     </h3>
                     <!-- card tools -->
                     <div class="card-tools">
                        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal">REGISTRAR USUARIO NUEVO</button>
                        <button type="button" class="btn btn-secondary btn-sm" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                     </div>
                     <!-- /.card-tools -->
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-lg-12" id="usersDiv">
                           <table id="example" class="display table table-sm table-active table-hover" >
                              <thead>
                                 <tr>
                                    <th>Usuario</th>
                                    <th>Nombre</th>
                                    <th>Unidad</th>
                                    <th>Rol</th>
                                    <th>Activo</th>
                                    <th>Opciones</th>
                                 </tr>
                              </thead>
                           </table>
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
                    <h5 class="modal-title" id="exampleModalLabel">Registrar usuario nuevo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body form-group">
                     <div class="row">
                        <div class="col-lg-6">
                           <label>USUARIO</label>
                           <input type="text" id="matricula" class="form-control">
                        </div>
                        <div class="col-lg-6">
                           <label>TIPO DE USUARIO</label>
                           <select id="tipoUsuario" class="form-control">
                              <option value="">SELECCIONE UNA OPCIÓN</option>
                              <option value="3">VERIFICADOR</option>
                              <!--<option value="2">SUPERVISOR</option>-->
                              <option value="1">ADMINISTRADOR</option>
                           </select>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-12">
                           <label>NOMBRE</label>
                           <input type="text" id="nombre" class="form-control">
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-lg-6">
                           <label>ADSCRIPCION</label>
                           <select class="form-control" id="adscripcion"> 
                              <option value="">SELECCIONE UNA OPCIÓN</option>
                              <?php 
                                 $db = new Databaseocs();
                                 $query = $db->connect()->prepare('SELECT id, nombre FROM mtto_unidades');
                                 $query->execute();

                                 while ($user = $query->fetch(PDO::FETCH_ASSOC)) {
                                    echo ("<option value='".$user['id']."'>".$user['nombre']."</option>\n");
                                 }
                              ?>
                           </select>
                        </div>
                        <div class="col-lg-6">
                           <label>ACTIVO</label>
                           <select id="activo" class="form-control">
                              <option value="">SELECCIONE UNA OPCIÓN</option>
                              <option value="1">SI</option>
                              <option value="0">NO</option>
                           </select>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-12">
                           <label>Contraseña</label>
                           <input type="password" class="form-control" id="password">
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
                    <button type="button" class="btn btn-primary" id="guardar">Guardar</button>
                  </div>
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
   </body>
</html>
<script type="text/javascript">

$(document).ready(function () {
    // Setup - add a text input to each footer cell
    $('#example thead tr')
        .clone(true)
        .addClass('filters')
        .appendTo('#example thead');
 
   var table = $('#example').DataTable({
      language: {
         url: 'plugins/datatables-language/es-ar.json' //Ubicacion del archivo con el json del idioma.
      },
      ajax:{
         url: 'fetch_usuarios.php',
         type: 'post',
         data: {tablaUsuarios:""},
         dataType: 'json',
      },
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



   /*setInterval( function () {
      //alert($('#example').DataTable().column(0).search() + " - " + $('#example').DataTable().column(1).search() + " - " + $('#example').DataTable().column(2).search());

      $.ajax({
         url: 'fetch_usuarios.php',
         type: 'post',
         data: {tablaUsuarios:""},
            success:function(data){
                     alert(data);
            }
      });
      table.ajax.reload();
   }, 3000 );*/

   $(document).on('click', '#guardar', function () {
      $('#guardar').prop('disabled', true);
      var datosUsuario = {};
      datosUsuario['matricula'] = $('#matricula').val();
      datosUsuario['tipoUsuario'] = $('#tipoUsuario').val();
      datosUsuario['nombre'] = $('#nombre').val();
      datosUsuario['adscripcion'] = $('#adscripcion').val();
      datosUsuario['activo'] = $('#activo').val();
      datosUsuario['password'] = $('#password').val();

      if($('#matricula').attr("readonly")){
         //modificacion
         $.ajax({
               url: 'fetch_usuarios.php',
               type: 'post',
               data: {datosUsuario:datosUsuario},
               dataType: 'json',
               success:function(data){
                  $('#mensajeUsuario').show();
                  $('#mensajeUsuario').html(data.message);
                  if(data.state){
                     $('#mensajeUsuario').addClass('alert alert-success text-center');
                     table.ajax.reload();
                     setTimeout(function(){
                       $('#exampleModal').modal('hide');
                     }, 50000);
                  }else{
                     $('#mensajeUsuario').addClass('alert alert-danger text-center');
                  }
               }
         });
      } else {
         //nuevo
         $.ajax({
               url: 'fetch_usuarios.php',
               type: 'post',
               data: {nuevoUsuario:datosUsuario},
               dataType: 'json',
               success:function(data){
                  $('#mensajeUsuario').show();
                  $('#mensajeUsuario').html(data.message);
                  if(data.state){
                     $('#mensajeUsuario').addClass('alert alert-success text-center');
                     table.ajax.reload();
                     setTimeout(function(){
                       $('#exampleModal').modal('hide');
                     }, 500);
                  }else{
                     $('#mensajeUsuario').addClass('alert alert-danger text-center');
                  }
               }
         });
      }
      $('#guardar').prop('disabled', false);
   });

   $(document).on('click', '.baja', function(){
      var username = $(this).parent().siblings(":first").text();
      $.ajax({
         url: 'fetch_usuarios.php',
         type: 'post',
         data: {activo:username},
         dataType: 'json',
         success:function(data){
            alert(data.message)
            if(data.state){
               table.ajax.reload();
            }
         }
      });
   });
   
});

$(document).on('click', '.opciones', function(){
   $('#exampleModalLabel').text('Modificar Usuario');
   $("#matricula").prop("readonly", true);
   $('#exampleModal').modal('show');
   var usuario = $(this).attr('data-value');
   $.ajax({
         url: 'fetch_usuarios.php',
         method: 'post',
         data: {busqUsuario:usuario},
         success:function(data){
               var json = data,
               obj = JSON.parse(json);
               $("#matricula").val(obj.matricula),
               $("#tipoUsuario").val(obj.tipoUsuario),
               $("#nombre").val(obj.nombre),
               $("#adscripcion").val(obj.adscripcion),
               $("#activo").val(obj.activo);
               $("#password").val(obj.password);
         }
   });
});

$('#exampleModal').on('hidden.bs.modal', function () {
   $('#exampleModalLabel').text('Registrar usuario nuevo');
   $("#matricula").prop("readonly", false);
   $('#mensajeUsuario').removeClass().hide();
   $('#matricula').val('');
   $('#tipoUsuario').val('');
   $('#nombre').val('');
   $('#adscripcion').val('');
   $('#activo').val('');
});
</script>