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

if(isset($_POST['A1']) && isset($_POST['A2']) && isset($_POST['A3']) && isset($_POST['A4']) && isset($_POST['A5']) && isset($_POST['A6']) && isset($_POST['A7']) && isset($_POST['A8']) && isset($_POST['A9']) && isset($_POST['A10']) && isset($_POST['A11']) && isset($_POST['A12']) && isset($_POST['A13']) && isset($_POST['A14']) && isset($_POST['A15']) && isset($_POST['A16'])){
   $db = new Databaseocs();
   try{
         $pdo = $db->connect();
         $pdo->beginTransaction();
         $pdo->exec("INSERT INTO sites_idf1 (periodo, df, IDF1_1, IDF1_1o, IDF1_2, IDF1_2o, IDF1_3, IDF1_3o, IDF1_4, IDF1_4o, IDF1_5, IDF1_5o, IDF1_6, IDF1_6o, IDF1_7, IDF1_7o, IDF1_8, IDF1_8o, IDF1_9, IDF1_9o, IDF1_10, IDF1_10o, IDF1_11, IDF1_11o, IDF1_12, IDF1_12o, IDF1_13, IDF1_13o, IDF1_14, IDF1_14o, IDF1_15, IDF1_15o, IDF1_16, IDF1_16o) VALUES (".$_POST['periodo'].",".$_POST['df'].",".$_POST['A1'].",'".$_POST['O1']."',".$_POST['A2'].",'".$_POST['O2']."',".$_POST['A3'].",'".$_POST['O3']."',".$_POST['A4'].",'".$_POST['O4']."',".$_POST['A5'].",'".$_POST['O5']."',".$_POST['A6'].",'".$_POST['O6']."',".$_POST['A7'].",'".$_POST['O7']."',".$_POST['A8'].",'".$_POST['O8']."',".$_POST['A9'].",'".$_POST['O9']."',".$_POST['A10'].",'".$_POST['O10']."',".$_POST['A11'].",'".$_POST['O11']."',".$_POST['A12'].",'".$_POST['O12']."',".$_POST['A13'].",'".$_POST['O13']."',".$_POST['A14'].",'".$_POST['O14']."',".$_POST['A15'].",'".$_POST['O15']."',".$_POST['A16'].",'".$_POST['O16']."' ) ");
         $pdo->commit();
         header('location: sites-idf2.php?p='.$_POST['periodo'].'&df='.$_POST['df']);
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
                           <li class="breadcrumb-item active">IDF 1. MEMORIA TÉCNICA</li>
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
                                    <th>1</th>
                                    <th width="60%">MEMORIA TÉCNICA</th>
                                    <th>CUMPLE</th>
                                    <th>OBSERVACIONES</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <tr class="font-weight-normal">
                                    <td>1.1</td>
                                    <td>NOMBRE DEL IDF CORRECTO</td>
                                    <td><label><input type="radio" name="A1" value="0" required> NO</label> <label><input type="radio" name="A1" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O1"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.2</td>
                                    <td>DEFINICION DEL AREA CORRECTA</td>
                                    <td><label><input type="radio" name="A2" value="0" required> NO</label> <label><input type="radio" name="A2" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O2"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.3</td>
                                    <td>DEFINICION DE PAREDES Y PISO</td>
                                    <td><label><input type="radio" name="A3" value="0" required> NO</label> <label><input type="radio" name="A3" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O3"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.4</td>
                                    <td>DEFINICION CORRECTA DE RACKS</td>
                                    <td><label><input type="radio" name="A4" value="0" required> NO</label> <label><input type="radio" name="A4" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O4"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.5</td>
                                    <td>DEFINICION CORRECTA DE SWITCHES</td>
                                    <td><label><input type="radio" name="A5" value="0" required> NO</label> <label><input type="radio" name="A5" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O5"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.6</td>
                                    <td>DEFINICION CORRECTA DE PANELES DE PARCHEO</td>
                                    <td><label><input type="radio" name="A6" value="0" required> NO</label> <label><input type="radio" name="A6" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O6"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.7</td>
                                    <td>DEFINICION CORRECTA DE TRAYECTORIAS</td>
                                    <td><label><input type="radio" name="A7" value="0" required> NO</label> <label><input type="radio" name="A7" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O7"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.8</td>
                                    <td>DEFINICION CORRECTA DE EQUIPOS NO IMSS</td>
                                    <td><label><input type="radio" name="A8" value="0" required> NO</label> <label><input type="radio" name="A8" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O8"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.9</td>
                                    <td>CANALIZACION ADECUADA</td>
                                    <td><label><input type="radio" name="A9" value="0" required> NO</label> <label><input type="radio" name="A9" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O9"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.10</td>
                                    <td>ESTA EN ORDEN EL CABLEADO FRONTAL AL RACK (NODOS Y PACHD CORD)</td>
                                    <td><label><input type="radio" name="A10" value="0" required> NO</label> <label><input type="radio" name="A10" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O10"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.11</td>
                                    <td>ESTA EN ORDEN EL CABLEADO POSTERIOR AL RACK </td>
                                    <td><label><input type="radio" name="A11" value="0" required> NO</label> <label><input type="radio" name="A11" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O11"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.12</td>
                                    <td>ESTAN IDENTIFICADOS LOS PUERTOS EN LOS SW Y PP</td>
                                    <td><label><input type="radio" name="A12" value="0" required> NO</label> <label><input type="radio" name="A12" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O12"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.13</td>
                                    <td>¿LA UBICACION DEL IDF ES CORRECTA?</td>
                                    <td><label><input type="radio" name="A13" value="0" required> NO</label> <label><input type="radio" name="A13" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O13"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.14</td>
                                    <td>SE CUENTA CON LOS MAPAS DE TRAYECTORIAS DEL IDF</td>
                                    <td><label><input type="radio" name="A14" value="0" required> NO</label> <label><input type="radio" name="A14" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O14"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.15</td>
                                    <td>SE HA REALIZADO LA LIMPIEZA DE LOS EQUIPOS PERIODICAMENTE</td>
                                    <td><label><input type="radio" name="A15" value="0" required> NO</label> <label><input type="radio" name="A15" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O15"></textarea></td>
                                 </tr>
                                 <tr class="font-weight-normal">
                                    <td>1.16</td>
                                    <td>DIRECTORIO ACTUALIZADO DE IPS</td>
                                    <td><label><input type="radio" name="A16" value="0" required> NO</label> <label><input type="radio" name="A16" value="1" required> SI</label></td>
                                    <td><textarea class="form-control form-control-sm" rows="1" name="O16"></textarea></td>
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