<?php
session_start();

include_once("../../controllers/pemisahController.php");
include_once("../../controllers/elemenController.php");
include_once("../../controllers/nilaiAkreditasiController.php");

$list_nilai = getListNilai();
$list_deskripsi = getListDeskripsi();
$list_elemen = getListElemen($list_nilai);
$list_akreditasi = getListAkreditasi(getListBobot($list_elemen), $list_elemen, $list_nilai);

$result = mysqli_query(
  $conn,
  "SELECT *
  FROM `penilaian`
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
              <a class="nav-link" href="../forms/pertanyaan_akreditasi.php<?php if (isset($_SESSION['halaman'])) echo "?halaman=" . $_SESSION['halaman'] ?>">
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
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 mb-4 mb-xl-0">
                  <h3 class="font-weight-bold">Welcome, <?= $_SESSION['name'] ?>!</h3>
                  <h6 class="font-weight-normal mb-0">Selamat datang di Simulasi Akreditasi LAM</h6>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 grid-margin transparent">
              <!-- Modal deskripsi -->
              <div class="modal fade" id="modalDeskripsi" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title"><b>Deskripsi</b></h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <p style="white-space: pre-wrap;" id="isiModalDeskripsi"></p>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Modal deskripsi -->
              <div class="row text-center">
                <div class="col-md stretch-card transparent">
                  <div class="card card-tale">
                    <div class="card-body">
                      <p class="fs-20 mt-3 mb-3"><b><?= $list_akreditasi['status'] ?></b></p>
                      <small class="mb-0">Status<br><b>Akreditasi</b></small>
                    </div>
                  </div>
                </div>
                <div class="col-md stretch-card transparent">
                  <div class="card card-dark-blue">
                    <div class="card-body">
                      <p class="fs-20 mt-3 mb-2"><b><?= is_float($list_akreditasi['total_nilai']) ? round($list_akreditasi['total_nilai'], 2) : $list_akreditasi['total_nilai'] ?></b></p>
                      <small class="mb-0">Nilai<br><b>Akreditasi</b></small>
                    </div>
                  </div>
                </div>
                <div class="col-md stretch-card transparent">
                  <div class="card card-light-blue">
                    <div class="card-body">
                      <p class="fs-20 mt-3 mb-2"><b><?= $list_akreditasi['syarat_perlu_akreditasi'] ? "Terpenuhi" : "Tidak Terpenuhi" ?></b></p>
                      <small class="mb-0">Syarat<br><b>Perlu Akreditasi</b></small>
                    </div>
                  </div>
                </div>
                <div class="col-md stretch-card transparent">
                  <div class="card card-light-danger">
                    <div class="card-body">
                      <p class="fs-20 mt-3 mb-2"><b><?= $list_akreditasi['syarat_perlu_unggul'] ? "Terpenuhi" : "Tidak Terpenuhi" ?></b></p>
                      <small class="mb-0">Syarat Perlu<br><b>Peringkat Unggul</b></small>
                    </div>
                  </div>
                </div>
                <div class="col-md stretch-card transparent">
                  <div class="card card-dark">
                    <div class="card-body">
                      <p class="fs-20 mt-3 mb-2"><b><?= $list_akreditasi['syarat_perlu_baik_sekali'] ? "Terpenuhi" : "Tidak Terpenuhi" ?></b></p>
                      <small class="mb-0">Syarat Perlu<br><b>Peringkat Baik Sekali</b></small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="card-title mb-0">
                    <h4>Simulasi Skor Akreditasi</h4>
                  </div>
                  <?php
                  if (hitungBelumTerisi($list_nilai) != 0) {
                    echo "
                    <div class='card-subtitle mb-0'>
                      <h5>Peringatan! Masih ada " . hitungBelumTerisi($list_nilai) . " indikator yang belum terisi.</h5>
                    </div>
                    ";
                  }
                  ?>
                  <div class="table-responsive pt-3">
                    <table style="table-layout: fixed; width: 100%" class="table-bordered">
                      <thead>
                        <tr>
                          <th class="text-center">
                            No.
                          </th>
                          <th class="text-center" style="word-wrap: break-word;padding: 20px;width: 70%;">
                            Elemen/Kriteria/Indikator
                          </th>
                          <th class="text-center">
                            Terisi
                          </th>
                          <th class="text-center">
                            Skor
                          </th>
                          <th class="text-center">
                            Deskripsi
                          </th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php while ($data = mysqli_fetch_array($result)) { ?>
                          <tr>
                            <td class="text-center">
                              <?= $data['id'] ?>
                            </td>
                            <td style="padding: 20px;width: 70%;">
                              <b>
                                <?php
                                // Jika ini data pertama, maka langsung cetak
                                if ($data['id'] == 1)
                                  echo $data['elemen'] . "<br>";
                                else {
                                  // Jika elemen saat ini sama dengan elemen sebelumnya, maka tidak perlu dicetak
                                  // Selain itu, maka akan masuk pada if di bawah ini
                                  if (!($data['elemen'] == $elemen_sebelumnya)) {
                                    // Elemen bisa saja lebih dari satu baris, sehingga harus dipisah
                                    if (perluPemisah($data['elemen'])) {
                                      cetakElemen($data['elemen']);
                                      echo "<br>";
                                    } else
                                      echo $data['elemen'] . "<br>";
                                  }
                                }
                                // Diperlukan untuk mengecek jika elemen sama seperti sebelumnya
                                $elemen_sebelumnya = $data['elemen'];
                                ?>
                              </b>
                              <?php
                              // Elemen bisa saja lebih dari satu baris, sehingga harus dipisah
                              if (perluPemisah($data['indikator']))
                                cetakElemen($data['indikator']);
                              else
                                echo $data['indikator'];
                              ?>
                            </td>
                            <td class="text-center">
                              <a <?= $_SESSION['role'] == 'asesor' ? "href='../forms/pertanyaan_akreditasi.php?halaman=<?= $data[id] ?>'" : "" ?>>
                                <?php
                                if ($list_nilai[$data['id'] - 1] == -1)
                                  echo "Belum terisi";
                                else {
                                  if (strpos($list_nilai[$data['id'] - 1], '.'))
                                    echo round(floatval($list_nilai[$data['id'] - 1]), 4) * 25 . "%";
                                  else
                                    echo $list_nilai[$data['id'] - 1] * 25 . "%";
                                }
                                ?>
                              </a>
                            </td>
                            <td class="text-center">
                              <b>
                                <?php
                                if ($list_nilai[$data['id'] - 1] == -1)
                                  echo "Belum terisi";
                                else
                                  echo strpos($list_nilai[$data['id'] - 1], '.') ? round(floatval($list_nilai[$data['id'] - 1]), 4) : $list_nilai[$data['id'] - 1];
                                ?>
                              </b>
                            </td>
                            <td class="text-center">
                              <button type="button" class="btn btn-primary" onclick="modal_deskripsi(<?= $data['id'] - 1 ?>)" data-toggle="modal" data-target="#modalDeskripsi"><i class="ti-zoom-in"></i></button>
                            </td>
                          </tr>
                        <?php } // Akhir dari while 
                        ?>
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
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/hoverable-collapse.js"></script>
  <script src="../../js/template.js"></script>
  <script src="../../js/settings.js"></script>
  <script src="../../js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script>
    // Dipakai di button submit skor saat setelah menghitung rumus
    function modal_deskripsi(element) {
      let arr = <?= json_encode($list_deskripsi) ?>;
      if (arr[element] == -1)
        deskripsi = `Tidak ada deskripsi untuk nomor ${element + 1}.`;
      else
        deskripsi = arr[element];

      document.getElementById("isiModalDeskripsi").innerHTML = deskripsi;
    }
  </script>
  <!-- End custom js for this page-->
</body>

</html>