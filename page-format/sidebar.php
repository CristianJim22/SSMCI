            <div class="sidebar">
               <!-- Sidebar user panel (optional) -->
               <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                  <div class="image">
                     <img src="dist/img/users/letter-<?php echo substr(utf8_decode($_SESSION['SSMCI']['nombre']), 0,1); ?>.png" class="img-circle elevation-2" alt="User Image">
                  </div>
                  <div class="info">
                     <a href="#" class="d-block"><?php
                     $nombrec=explode(' ', $_SESSION['SSMCI']['nombre']);
                      echo utf8_decode($nombrec[0]);
                  ?></a>
                  </div>
               </div>
               <!-- Sidebar Menu -->
               <nav class="mt-2">
                  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                     <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->
                     <?php if($_SESSION['SSMCI']['rol'] == 1 || $_SESSION['SSMCI']['rol'] == 2){ ?>
                        <li class="nav-item">
                           <a href="index.php" class="nav-link active" data-value="usuarios">
                              <i class="nav-icon fas fa-user"></i>
                              <p>
                                 Usuarios
                                 <!--<span class="right badge badge-danger">New</span>-->
                              </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="mantenimientos.php" class="nav-link active" data-value="mantenimientos">
                              <i class="nav-icon fas fa-tools"></i>
                              <p>
                                 Mantenimientos
                                 <!--<span class="right badge badge-danger">New</span>-->
                              </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="admon-toners.php" class="nav-link active" data-value="toners">
                              <i class="nav-icon fas fa-tint"></i>
                              <p>
                                 Control de toners 
                                 <!--<span class="right badge badge-danger">New</span>-->
                              </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="mantenimientosDeSites.php" class="nav-link active" data-value="sites">
                              <i class="nav-icon fas fa-sitemap"></i>
                              <p>
                                 Mantenimiento de Sites
                              </p>
                           </a>
                        </li>
                     <?php } 
                     ?>
                     <?php if($_SESSION['SSMCI']['rol'] == 3){ ?>
                        <li class="nav-item">
                           <a href="imprimir-formato.php" class="nav-link active" data-value="imprimir">
                              <i class="nav-icon fas fa-file-alt"></i>
                              <p>
                                 Imprimir checklist
                                 <!--<span class="right badge badge-danger">New</span>-->
                              </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="control-toners.php" class="nav-link active" data-value="toners">
                              <i class="nav-icon fas fa-tint"></i>
                              <p>
                                 Control de toners 
                                 <!--<span class="right badge badge-danger">New</span>-->
                              </p>
                           </a>
                        </li>
                        <li class="nav-item">
                           <a href="mantenimiento-sites.php" class="nav-link active" data-value="sites">
                              <i class="nav-icon fas fa-sitemap"></i>
                              <p>
                                 Mantenimiento de Sites
                              </p>
                           </a>
                        </li>
                     <?php } ?>
                  </ul>
               </nav>
               <!-- /.sidebar-menu -->
            </div>