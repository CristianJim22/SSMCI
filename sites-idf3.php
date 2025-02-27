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

if(isset($_POST['C1']) && isset($_POST['C2']) && isset($_POST['C3']) && isset($_POST['C4']) && isset($_POST['C5']) && isset($_POST['C6']) && isset($_POST['C7']) && isset($_POST['C8']) && isset($_POST['C9']) ){
   $db = new Databaseocs();
   try{
         $pdo = $db->connect();
         $pdo->beginTransaction();
         $pdo->exec("INSERT INTO sites_idf3 (periodo, df, IDF3_1, IDF3_1o, IDF3_2, IDF3_2o, IDF3_3, IDF3_3o, IDF3_4, IDF3_4o, IDF3_5, IDF3_5o, IDF3_6, IDF3_6o, IDF3_7, IDF3_7o, IDF3_8, IDF3_8o, IDF3_9, IDF3_9o) VALUES (".$_POST['periodo'].",".$_POST['df'].",".$_POST['C1'].",'".$_POST['O1']."',".$_POST['C2'].",'".$_POST['O2']."',".$_POST['C3'].",'".$_POST['O3']."',".$_POST['C4'].",'".$_POST['O4']."',".$_POST['C5'].",'".$_POST['O5']."',".$_POST['C6'].",'".$_POST['O6']."',".$_POST['C7'].",'".$_POST['O7']."',".$_POST['C8'].",'".$_POST['O8']."',".$_POST['C9'].",'".$_POST['O9']."' ) ");
         $pdo->commit();
         header('location: mantenimiento-sites.php');
   } catch (PDOException $e) {
         $pdo->rollback();
         echo'
            <script>
            window.onload = function() {
               alert("OCURRIÓ UN ERROR, VUELVA A INTENTAR O CONSULTE CON EL ADMINISTRADOR");
               location.href = "mantenimiento-sites.php";  
            }
            </script>
         ';
         die();
   }
}
?>

<!DOCTYPE html>
<html lang="es">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>SSMCI - IDF</title>
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
                           <li class="breadcrumb-item active">IDF 3. APOYOS OBTENIDOS DE LA UNIDAD</li>
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


            <section class="col-lg-9 connectedSortable mt-3 mx-auto">
               <!-- Calendar -->
               <div class="card bg-gradient-white">
                 <div class="card-header border-0">

                   <h3 class="card-title">
                     <i class="fas fa-tasks"></i>
                     IDF
                   </h3>
                 </div>
                 <!-- /.card-header -->
                 <div class="card-body pt-0">
                   <!--The calendar -->
                  <div class="row">
                     <div class="col-12">
                        <div class="info-box elevation-2">
                           <form method="post" action="">
                              <input type="hidden" name="periodo" value="<?php echo $_GET['p'];?>">
                              <input type="hidden" name="df" value="<?php echo $_GET['df'];?>">
                           <table class="table table-sm table-hover form-group">
                               <thead>
                                 <tr>
                                    <th>3</th>
                                    <th width="60%">APOYOS OBTENIDOS DE LA UNIDAD</th>
                                    <th>CUMPLE</th>
                                    <th>OBSERVACIONES</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr class="font-weight-normal">
                                    <td>3.1</td>
                                    <td>SE CUENTA CON UN CONTROL DE ACCESO</td>
                                    <td><label><input type="radio" name="C1" value="0" required> NO</label> <label><input type="radio" name="C1" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O1"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>3.2</td>
                                    <td>LOS TECHOS Y PAREDES (VENTANAS) ESTAN LIMPIOS</td>
                                    <td><label><input type="radio" name="C2" value="0" required> NO</label> <label><input type="radio" name="C2" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O2"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>3.3</td>
                                    <td>EL IDF ESTA INCLUIDO EN EL PROG.DE LIMPIEZA DE LA UNIDAD</td>
                                    <td><label><input type="radio" name="C3" value="0" required> NO</label> <label><input type="radio" name="C3" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O3"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>3.4</td>
                                    <td>EL AREA ES EXCLUSIVA PARA EL IDF</td>
                                    <td><label><input type="radio" name="C4" value="0" required> NO</label> <label><input type="radio" name="C4" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O4"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>3.5</td>
                                    <td>LA ILUMINACION ES ACEPTABLE</td>
                                    <td><label><input type="radio" name="C5" value="0" required> NO</label> <label><input type="radio" name="C5" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O5"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>3.6</td>
                                    <td>EXISTE SEÑALETICA Y EXTINTORES EN EL AREA</td>
                                    <td><label><input type="radio" name="C6" value="0" required> NO</label> <label><input type="radio" name="C6" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O6"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>3.7</td>
                                    <td>LA CLIMATIZACION ES ACEPTABLE ( < 20 GRADOS)</td>
                                    <td><label><input type="radio" name="C7" value="0" required> NO</label> <label><input type="radio" name="C7" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O7"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>3.8</td>
                                    <td>CONDICIONES ELECTRICAS ACEPTABLES</td>
                                    <td><label><input type="radio" name="C8" value="0" required> NO</label> <label><input type="radio" name="C8" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O8"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>3.9</td>
                                    <td>ESTA EL IDF EN EL PLAN DE CONTINGENCIA DE LA UNIDAD</td>
                                    <td><label><input type="radio" name="C9" value="0" required> NO</label> <label><input type="radio" name="C9" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O9"></textarea></td>
                                 </tr>
                                 
                                 
                                 <tr class="font-weight-normal">
                                    <td colspan="4">
                                       <button type="submit" class="btn btn-sm btn-primary float-right"> Guardar avance <i class="fas fa-arrow-right"></i></button>
                                    </td>
                                 </tr>
                              </tbody>
                           </table>
                           </form>
                        </div>
                     </div>
                  </div>
                 <!-- /.card-body -->
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
   </body>
</html>
<script type="text/javascript">

</script>