<?php
session_start();

include_once("../../controllers/connection.php");
include_once("../../controllers/laporanKinerjaController.php");

if ($_SESSION['id'] == ID_ASESOR_D3_IT || $_SESSION['id'] == ID_PRODI_D3_IT) {
  $list_nilai_laporan = getListNilaiLaporan();
}

$result = mysqli_query(
  $conn,
  "SELECT *
  FROM `laporan_kinerja`
  ORDER BY id
  "
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Skydash Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../../vendors/feather/feather.css">
  <link rel="stylesheet" href="../../vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="../../vendors/select2/select2.min.css">
  <link rel="stylesheet" href="../../vendors/select2-bootstrap-theme/select2-bootstrap.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <!-- partial:../../partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="../../index.php"><img src="../../images/logo.svg" class="mr-2" alt="logo" /></a>
        <a class="navbar-brand brand-logo-mini" href="../../index.php"><img src="../../images/logo-mini.svg" alt="logo" /></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">

          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <?= "User: " . $_SESSION['name'] ?>
              <i class="icon-arrow-down ml-1 mr-1 pb-1"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item" href="../../controllers/logoutController.php">
                <i class="ti-power-off text-primary"></i>
                Logout
              </a>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:../../partials/_settings-panel.html -->
      <div class="theme-setting-wrapper">
        <div id="settings-trigger"><i class="ti-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close ti-close"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme">
            <div class="img-ss rounded-circle bg-light border mr-3"></div>Light
          </div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme">
            <div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark
          </div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
      <!-- partial -->
      <!-- partial:../../partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="../../index.php">
              <i class="icon-grid menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          <?php if ($_SESSION['role'] == 'asesor') { // Jika bukan asesor, jangan tunjukkan pertanyaan akreditasi 
          ?>
            <li class="nav-item">
              <a class="nav-link" href="pertanyaan_akreditasi.php<?php if (isset($_SESSION['halaman'])) echo "?halaman=" . $_SESSION['halaman'] ?>">
                <i class="icon-columns menu-icon"></i>
                <span class="menu-title">Pertanyaan Akreditasi</span>
              </a>
            </li>
          <?php } // Akhir dari if 
          ?>
          <li class="nav-item">
            <a class="nav-link" href="../forms/laporan_kinerja.php">
              <i class="icon-content-left menu-icon"></i>
              <span class="menu-title">Laporan Kinerja</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../tables/simulasi_akreditasi.php">
              <i class="icon-layout menu-icon"></i>
              <span class="menu-title">Simulasi Akreditasi</span>
            </a>
          </li>
        </ul>
      </nav>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="card-title mb-0">
                    <h4>Laporan Kinerja Program Studi</h4>
                  </div>
                  <div class="table-responsive pt-3">
                    <table style="table-layout: fixed; width: 100%" class="table-bordered">
                      <thead>
                        <tr>
                          <th class="text-center">
                            No.
                          </th>
                          <th class="text-center" style="word-wrap: break-word;padding: 20px;width: 70%;">
                            Nomor dan Judul Tabel
                          </th>
                          <th class="text-center">
                            Nilai
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <form method="POST" action="../../controllers/laporanKinerjaController.php">
                          <?php while ($data = mysqli_fetch_array($result)) { ?>
                            <tr>
                              <td class="text-center">
                                <?= $data['id'] ?>
                              </td>
                              <td style="padding: 20px;width: 70%;">
                                <b>
                                  <?= $data['judul'] ?>
                                </b>
                              </td>
                              <td class="text-center">
                                <?php if ($_SESSION['role'] == 'prodi') { ?>
                                  <input name="<?= $data['id'] ?>" value="<?= $list_nilai_laporan[$data['id'] - 1] ?>" type="number" step="0.1" class="form-control" style="border: none; text-align: center;">
                                <?php } else echo $list_nilai_laporan[$data['id'] - 1]; ?>
                              </td>
                            </tr>
                          <?php } // Akhir dari while 
                          echo "<input type='submit' style='visibility: hidden; display: none;'>"
                          ?>                          
                        </form>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash.
              All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="../../vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="../../vendors/typeahead.js/typeahead.bundle.min.js"></script>
  <script src="../../vendors/select2/select2.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/template.js"></script>
  <script src="../../js/settings.js"></script>
  <script src="../../js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="../../js/file-upload.js"></script>
  <script src="../../js/typeahead.js"></script>
  <script src="../../js/select2.js"></script>
  <!-- End custom js for this page-->
</body>

</html>