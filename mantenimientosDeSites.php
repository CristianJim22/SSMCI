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


            <section class="col-lg-12 connectedSortable mt-3">
                <!-- Map card -->
                <div class="card" style="overflow-x: scroll;">
                    <div class="card-header border-0">
                        <h3 class="card-title">
                            <i class="nav-icon fas fa-sitemap"></i>
                            Mantenimiento de sites
                        </h3>

                        <!-- Filtro de fecha -->
                        <div class="mt-3">
                            <label for="daterange" style="font-weight: bold; font-size: 16px;">Filtrar por año:</label>
                            <div class="d-flex flex-column flex-md-row mt-2">
                                <div class="mr-md-3">
                                    <label for="fecha" class="d-block text-black">Año:</label>
                                    <select id="fecha" class="form-control" onchange="fillContent();">
                                        <option value="">Seleccione un año</option>
                                        <?php
                                        $currentYear = date("Y");
                                        for ($i = $currentYear; $i >= 2000; $i--) {
                                            echo "<option value='$i'>$i</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- card tools -->
                        <div class="card-tools d-flex">
                            <button type="button" class="btn btn-secondary btn-sm" data-card-widget="collapse" title="Collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                        <!-- /.card-tools -->
                    </div>
                    <div class="card-body">
                        <div id="buttondiv">
                        </div>
                        <br></br>
                        <div id="invoice" style="flex: 1 1 auto;min-height: 1px;padding: 1.25rem;">
                            <div class="row d-flex justify-content-center">
                                <div class="col-12" id="tablaInformacion">
                                </div>
                            </div>
                            <div class="row d-flex justify-content-center" id="chartsdinamicos">
                            </div>
                        </div>
                    </div>

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
    $(document).ready(function() {
        fillContent();

        $('#fecha').on('change', function() {
            fillContent();
        });
    });

    function fillContent() {
        var year = $('#fecha').val();
        if (year) {
            $.ajax({
                url: "fetch_mantenimientos.php",
                type: "POST",
                dataType: "json",
                data: {
                    year: year
                },
                success: function(response) {
                    if (response.state) {
                        printtable(response.data);
                    } else {
                        alert("Error al obtener los datos: " + response.message);
                    }
                },
                error: function() {
                    alert("Error en la comunicación con el servidor.");
                }
            });
        } else {

        }
    }

    function printtable(datos) {
        $('#tablaInformacion').empty();
        $('#buttondiv').empty();
        $('#buttondiv').append('<button class="btn btn-sm btn-primary float-right mb-2" id="descargar">DESCARGAR INFORME <i class="fas fa-download"></i></button>');
        var tabla = "";
        tabla = `
        <table class="font-12 table-hover" style="max-width:100% !important; border: 1px solid; margin: auto">
            <thead>
                <tr>
                    <th style="width:5.6%; border: 1px solid; text-align: center; vertical-align: middle;">UNIDAD</th>
                    <th style="width:5.6%; border: 1px solid; text-align: center; vertical-align: middle;">ENE</th>
                    <th style="width:5.6%; border: 1px solid; text-align: center; vertical-align: middle;">FEB</th>
                    <th style="width:5.6%; border: 1px solid; text-align: center; vertical-align: middle;">MAR</th>
                    <th style="width:5.6%; border: 1px solid; text-align: center; vertical-align: middle;">ABR</th>
                    <th style="width:5.6%; border: 1px solid; text-align: center; vertical-align: middle;">MAY</th>
                    <th style="width:5.6%; border: 1px solid; text-align: center; vertical-align: middle;">JUN</th>
                    <th style="width:5.6%; border: 1px solid; text-align: center; vertical-align: middle;">JUL</th>
                    <th style="width:5.6%; border: 1px solid; text-align: center; vertical-align: middle;">AGO</th>
                    <th style="width:5.6%; border: 1px solid; text-align: center; vertical-align: middle;">SEP</th>
                    <th style="width:5.6%; border: 1px solid; text-align: center; vertical-align: middle;">OCT</th>
                    <th style="width:5.6%; border: 1px solid; text-align: center; vertical-align: middle;">NOV</th>
                    <th style="width:5.6%; border: 1px solid; text-align: center; vertical-align: middle;">DIC</th>
                </tr>
            </thead>
            <tbody>
    `;

        datos.forEach(function(registro, index) {
            tabla += `
            <tr>
                <td style="border: 1px solid; text-align: center; vertical-align: middle;">${registro.unidad}</td>
                <td ${getCellStyle(registro.ene)}>${registro.ene}</td>
                <td ${getCellStyle(registro.feb)}>${registro.feb}</td>
                <td ${getCellStyle(registro.mar)}>${registro.mar}</td>
                <td ${getCellStyle(registro.abr)}>${registro.abr}</td>
                <td ${getCellStyle(registro.may)}>${registro.may}</td>
                <td ${getCellStyle(registro.jun)}>${registro.jun}</td>
                <td ${getCellStyle(registro.jul)}>${registro.jul}</td>
                <td ${getCellStyle(registro.ago)}>${registro.ago}</td>
                <td ${getCellStyle(registro.sep)}>${registro.sep}</td>
                <td ${getCellStyle(registro.oct)}>${registro.oct}</td>
                <td ${getCellStyle(registro.nov)}>${registro.nov}</td>
                <td ${getCellStyle(registro.dic)}>${registro.dic}</td>
            </tr>
        `;
        });

        tabla += '</tbody></table>';
        $('#tablaInformacion').append(tabla);
        $('#tablaInformacion tr:last').addClass('table-primary');
    }

    function getCellStyle(value) {
        if (value === 'SI') {
            return 'style="background-color:rgb(106, 235, 136); color: black; border: 1px solid; text-align: center; vertical-align: middle;"';
        } else if (value === 'NO') {
            return 'style="background-color:rgb(255, 131, 143); color: black; border: 1px solid; text-align: center; vertical-align: middle;"';
        } else if (value === '') {
            return 'style="background-color:rgb(255, 249, 159); color: black; border: 1px solid; text-align: center; vertical-align: middle;"';
        } else {
            return 'style="border: 1px solid;"';
        }
    }

    $(document).on('click', '#descargar', function() {
        //$('#tablaInformacion, #chartsdinamicos').printThis();
        var fecha = $('#fecha option:selected').text();
        $('#invoice').printThis({
            header: '<table style="width: 100%;" class="mb-4"><tr><td><div id="logo"><img src="/SSMCI/dist/img/imsslogo-grey.jpg"  width= "70" height="75"></div></td><td>O.O.A.D AGUASCALIENTES<br>COORDINACIÓN DELEGACIONAL DE INFORMÁTICA<br>REPORTE DE MANTENIMIENTOS DE SITES<br>AÑO: ' + fecha + ' <i id="fechaPrint"></i></td></tr></table>',
            importStyle: true, //thrown in for extra measure
            importCSS: true,
            loadCSS: true,
            canvas: true,
            loadCSS: "/SSMCI/dist/css/adminlte.css"
        });
    });
</script>