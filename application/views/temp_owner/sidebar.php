<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-dark sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('dashboard') ?>">
        <div class="sidebar-brand-icon">
          <i class="fas fa-user-cog"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Program SPK</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="<?= base_url('Ownerpage/index') ?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li> 

      <!-- Nav Item - Tables -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          <i class="fab fa-fw fa-searchengin"></i>
          <span>Aturan Asosiasi</span>
        </a>
        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub-Menu :</h6>
            <!--<a class="collapse-item" href="<?= base_url('Apriori') ?>"><i class="fas fa-fw fa-calculator"></i> Hitung Aturan Asosiasi</a>-->
            <a class="collapse-item" href="<?= base_url('Ownerpage/indexhasil_apriori') ?>"><i class="fas fa-fw fa-poll"></i> Hasil Perhitungan</a>
          </div>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-search"></i>
          <span>SPK</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Sub-Menu :</h6>
            <!--<a class="collapse-item" href="<?= base_url('Kriteria') ?>"><i class="fas fa-fw fa-database"></i> Kriteria</a>
            <a class="collapse-item" href="<?= base_url('Alternatif') ?>"><i class="fas fa-fw fa-database"></i> Alternatif</a>
            <a class="collapse-item" href="<?= base_url('Perhitungan_spk') ?>"><i class="fas fa-fw fa-calculator"></i> Perhitungan SPK</a>-->
            <a class="collapse-item" href="<?= base_url('ownerpage/indexhasil_spk') ?>"><i class="fas fa-fw fa-poll"></i> Hasil SPK</a>
          </div>
        </div>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search 
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form> -->

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

          
            <div class="topbar-divider d-none d-sm-block"></div>

            <ul class="na navbar-nav navbar-right">

                <?php  if ($this->session->userdata('role_id')) { ?>
                  <li><div>Selamat Datang, Owner. </div></li>
                  <li class="ml-2"> <?= anchor('auth/logout', 'Logout'); ?></li>
                <?php } else { ?>
                  <li><?= anchor('auth/login', 'Login'); ?></li>
                <?php } ?>

            </ul>

          </ul>

        </nav>
        <!-- End of Topbar -->