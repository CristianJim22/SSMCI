<?php
    include_once 'databaseocs.php';
    $message = "";
    
    /*
    $db = new Databaseocs();
    $pass="311010201";
    $passwd = password_hash($pass, PASSWORD_BCRYPT); 
    $query = $db->connect()->prepare("INSERT INTO mtto_usuarios (username, password, nombre, unidad, rol, activo) VALUES ('311010201','$passwd','SERGIO GONZALEZ RODRIGUEZ',1, 1, 1) ")->execute();
    */
    session_start();

    if(isset($_GET['cerrar_sesion'])){
        session_unset(); 

        // destroy the session 
        session_destroy(); 
    }
    
    if(isset($_SESSION['SSMCI']['rol'])){
        switch($_SESSION['SSMCI']['rol']){
            case 1:
                header('location: admin.php');
            break;

            case 2:
                header('location: admin.php');
            break;

            case 3:
                header('location: imprimir-formato.php');
            break;

            default:
        }
    }

    if(isset($_POST['username']) && isset($_POST['password'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $db = new Databaseocs();
        $query = $db->connect()->prepare('SELECT U.username, U.nombre, U.unidad, U.rol, U.activo, U.password, UD.nombre FROM mtto_usuarios U 
            LEFT JOIN mtto_unidades UD ON U.unidad = UD.id 
            WHERE username = :username');
        $query->execute(['username' => $username]);

        $row = $query->fetch(PDO::FETCH_NUM);
            if($row == true){
                if(password_verify($password,$row[5]) && $row[4] == 1) {
                    $_SESSION['SSMCI']['username'] = $row[0];
                    $_SESSION['SSMCI']['nombre'] = $row[1];
                    $_SESSION['SSMCI']['unidad'] = $row[2];
                    $_SESSION['SSMCI']['unidadnombre'] = $row[6];
                    $_SESSION['SSMCI']['rol'] = $row[3];
                    $_SESSION['SSMCI']['activo'] = $row[4];
                    $rol = $row[3];
                    switch($rol){
                        case 1:
                            header('location: admin.php');
                        break;

                        case 2:
                            header('location: admin.php');
                        break;

                        case 3:
                            header('location: imprimir-formato.php');
                        break;

                        default:
                    }
                }else{
                    $message.= '<div class="alert alert-danger text-center" role="alert">';
                    if(!password_verify($password,$row[1])){
                        $message.= 'CONTRASEÑA INCORRECTA O USUARIO DADO DE BAJA';
                    }
                    $message.= '</div>';
                }

            }else{
                $message.= '<div class="alert alert-danger text-center" role="alert">NO SE ENCONTRO EL USUARIO INGRESADO</div>';
            }
    }
?>
<!DOCTYPE html>
<html>
   <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SSMCI</title>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/adminlte.css">
        <link rel="stylesheet" href="dist/css/helpers.css">
        <link href="dist/img/logo-cdi.png" rel="icon">

   </head>
   <body style="margin: 0; padding: 0; background-color: #f7f7ff;">
      <!-- Preloader 
      <div class="preloader flex-column justify-content-center align-items-center">
         <img class="animation__shake" src="dist/img/imsslogo-grey.png" alt="AdminLTELogo" height="60" width="60">
      </div>-->
      <!-- Main content -->
      <div class="container-fluid vh-100 d-flex">
      <!-- Main row -->
         <div class="container justify-content-center align-self-center">
            <div class="row m-5 no-gutters shadow-lg rounded">
               <div class="col-md-8 d-none d-md-block">
                <div class="card bg-dark text-white mb-0">
                      <img class="card-img img-blur" src="dist/img/mantenimiento.png" alt="Epidemiologia">
                      <div class="card-img-overlay">
                        <h5 class="card-title text-shadow-black font-35 text-center">SISTEMA PARA EL SEGUIMIENTO DE MANTENIMIENTOS DE LA COORDINACIÓN DE INFORMÁTICA</h5>
                        <div class="d-flex align-items-end h-75">
                            <p class="card-text alignt-text-bottom my-bottom">Para consultar al administrador llame a la extensión 41151<br>
                            <strong>Copyright &copy; <?php echo date("Y");?> <a class="text-warning" href="//11.1.1.227:82/imsscdiags/">CI Aguascalientes</a>.</strong> Derechos Reservados.
                            </p>
                        </div>
                      </div>
                </div>
               </div>
               <div class="col-md-4 bg-white p-5">
                  <div class="text-center mb-4">
                     <span class="text-shadow-black font-25 bold text-secondary">Iniciar Sesión</span>
                  </div>
                  <div class="form-style">
                     <form action="#" method="POST">
                     <div class="form-group pb-3">
                        <input type="text" class="form-control bg-transparent" id="username" name="username"  placeholder="Usuario">
                     </div>
                     <div class="form-group pb-3">
                        <input type="password" placeholder="Password" class="form-control" id="exampleInputPassword1" name="password">
                     </div>
                     <div class="pb-2">
                        <button type="submit" class="btn btn-dark w-100 rounded-pill">Iniciar Sesión</button>
                     </div>
                     </form>
                    <div class="form-group pb-3">
                        <?php  echo $message;?>
                    </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <!--Chrome Validator -->
      <script src="plugins/chrome-validator/chromeLock.js"></script>
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
    
      <!-- AdminLTE App -->
      <script src="dist/js/adminlte.js"></script>
   </body>
</html>


<script type="text/javascript">
(function() {
    var browser = (function (agent) {
        switch (true) {
            case agent.indexOf("edge") > -1: return "MS Edge (EdgeHtml)";
            case agent.indexOf("edg") > -1: return "MS Edge Chromium";
            case agent.indexOf("opr") > -1 && !!window.opr: return "opera";
            case agent.indexOf("chrome") > -1 && !!window.chrome: return "chrome";
            case agent.indexOf("trident") > -1: return "Internet Explorer";
            case agent.indexOf("firefox") > -1: return "firefox";
            case agent.indexOf("safari") > -1: return "safari";
            default: return "other";
        }
    })(window.navigator.userAgent.toLowerCase());
    if(browser == "chrome"){
    }else{
        window.location.replace("notificacion.php");
    }
})();
</script>

