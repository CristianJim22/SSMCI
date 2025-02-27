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

if(isset($_POST['B1']) && isset($_POST['B2']) && isset($_POST['B3']) && isset($_POST['B4']) && isset($_POST['B5']) && isset($_POST['B6']) && isset($_POST['B7']) && isset($_POST['B8']) && isset($_POST['B9']) && isset($_POST['B10']) && isset($_POST['B11']) && isset($_POST['B12']) && isset($_POST['B13']) && isset($_POST['B14']) ){
   $db = new Databaseocs();
   try{
         $pdo = $db->connect();
         $pdo->beginTransaction();
         $pdo->exec("INSERT INTO sites_mdf2 (periodo, df, MDF2_1, MDF2_1o, MDF2_2, MDF2_2o, MDF2_3, MDF2_3o, MDF2_4, MDF2_4o, MDF2_5, MDF2_5o, MDF2_6, MDF2_6o, MDF2_7, MDF2_7o, MDF2_8, MDF2_8o, MDF2_9, MDF2_9o, MDF2_10, MDF2_10o, MDF2_11, MDF2_11o, MDF2_12, MDF2_12o, MDF2_13, MDF2_13o, MDF2_14, MDF2_14o) VALUES (".$_POST['periodo'].",".$_POST['df'].",".$_POST['B1'].",'".$_POST['O1']."',".$_POST['B2'].",'".$_POST['O2']."',".$_POST['B3'].",'".$_POST['O3']."',".$_POST['B4'].",'".$_POST['O4']."',".$_POST['B5'].",'".$_POST['O5']."',".$_POST['B6'].",'".$_POST['O6']."',".$_POST['B7'].",'".$_POST['O7']."',".$_POST['B8'].",'".$_POST['O8']."',".$_POST['B9'].",'".$_POST['O9']."',".$_POST['B10'].",'".$_POST['O10']."',".$_POST['B11'].",'".$_POST['O11']."',".$_POST['B12'].",'".$_POST['O12']."',".$_POST['B13'].",'".$_POST['O13']."',".$_POST['B14'].",'".$_POST['O14']."' ) ");
         $pdo->commit();
         header('location: sites-mdf3.php?p='.$_POST['periodo'].'&df='.$_POST['df']);
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
      <title>SSMCI - MDF</title>
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
                           <li class="breadcrumb-item active">MDF 2. APLICACIÓN DE CONTROLES DE SEGURIDAD</li>
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
                     MDF
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
                                    <th>2</th>
                                    <th>APLICACIÓN DE CONTROLES DE SEGURIDAD</th>
                                    <th>CUMPLE</th>
                                    <th>OBSERVACIONES</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr class="font-weight-normal">
                                    <td>2.1</td>
                                    <td>TIENE UNA BITÁCORA QUE REGISTRE EL ACCESO AL MDF</td>
                                    <td><label><input type="radio" name="B1" value="0" required> NO</label> <label><input type="radio" name="B1" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O1"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>2.2</td>
                                    <td>UPS FUNCIONANDO ADECUADAMENTE</td>
                                    <td><label><input type="radio" name="B2" value="0" required> NO</label> <label><input type="radio" name="B2" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O2"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>2.3</td>
                                    <td>CONEXIÓN DE SERVIDORES A UPS</td>
                                    <td><label><input type="radio" name="B3" value="0" required> NO</label> <label><input type="radio" name="B3" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O3"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>2.4</td>
                                    <td>CONEXIÓN DE SWITCHES A UPS</td>
                                    <td><label><input type="radio" name="B4" value="0" required> NO</label> <label><input type="radio" name="B4" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O4"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>2.5</td>
                                    <td>TODOS LOS EQUIPOS ACTIVOS EN RACK</td>
                                    <td><label><input type="radio" name="B5" value="0" required> NO</label> <label><input type="radio" name="B5" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O5"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>2.6</td>
                                    <td>UBICACIÓN DE SERVIDORES EN GABINETES O RACKS</td>
                                    <td><label><input type="radio" name="B6" value="0" required> NO</label> <label><input type="radio" name="B6" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O6"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>2.7</td>
                                    <td>HA GESTIONADO RESTRINGIR EL AREA Y ACCESO</td>
                                    <td><label><input type="radio" name="B7" value="0" required> NO</label> <label><input type="radio" name="B7" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O7"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>2.8</td>
                                    <td>EL CLIMA ES MENOR DE 23 GRADOS (APROX)</td>
                                    <td><label><input type="radio" name="B8" value="0" required> NO</label> <label><input type="radio" name="B8" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O8"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>2.9</td>
                                    <td>HA GESTIONADO UNA REVISION DE CARGAS ELECTRICAS</td>
                                    <td><label><input type="radio" name="B9" value="0" required> NO</label> <label><input type="radio" name="B9" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O9"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>2.10</td>
                                    <td>EL MDF ESTA UBICADO EN UN AREA CORRECTA</td>
                                    <td><label><input type="radio" name="B10" value="0" required> NO</label> <label><input type="radio" name="B10" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O10"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>2.11</td>
                                    <td>HA GESTIONADO SEÑALETICA Y EXTINTORES EN EL AREA</td>
                                    <td><label><input type="radio" name="B11" value="0" required> NO</label> <label><input type="radio" name="B11" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O11"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>2.12</td>
                                    <td>CUENTA CON UN PLAN ANTE UNA CONTINGENCIA</td>
                                    <td><label><input type="radio" name="B12" value="0" required> NO</label> <label><input type="radio" name="B12" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O12"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>2.13</td>
                                    <td>PARTICIPA EN LOS SIMULCROS DE LA UNIDAD</td>
                                    <td><label><input type="radio" name="B13" value="0" required> NO</label> <label><input type="radio" name="B13" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O13"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>2.14</td>
                                    <td>CUENTA CON LA INFORMACIÓN DE PROTECCION CIVIL</td>
                                    <td><label><input type="radio" name="B14" value="0" required> NO</label> <label><input type="radio" name="B14" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O14"></textarea></td>
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