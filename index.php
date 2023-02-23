<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: pages/forms/login.php");
}

// Jika query GET untuk filter elemen tidak ada
if (!isset($_GET['filter'])) {
    $filter = 'none';
} else {
    $filter = $_GET['filter'];

    // Jika ada filter maka mulai menghitung, akan digunakan saat memfilter elemen
    // Karena hanya ada nomor-nomor/indikator-indikator tertentu yang akan keluar
    $i = 0;
}

include_once("./controllers/elemenController.php");
include_once("./controllers/pemisahController.php");

$elemen_list = getListElemen();

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
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" type="text/css" href="js/select.dataTables.min.css">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.html -->
        <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
            <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
                <a class="navbar-brand brand-logo mr-5" href="index.php"><img src="images/logo.svg" class="mr-2" alt="logo" /></a>
                <a class="navbar-brand brand-logo-mini" href="index.php"><img src="images/logo-mini.svg" alt="logo" /></a>
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
                            <a class="dropdown-item" href="./controllers/logoutController.php">
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
            <!-- partial:partials/_settings-panel.html -->
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
            <!-- partial:partials/_sidebar.html -->
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="icon-grid menu-icon"></i>
                            <span class="menu-title">Dashboard</span>
                        </a>
                    </li>
                    <?php if ($_SESSION['role'] == 'asesor') { // Jika bukan asesor, jangan tunjukkan pertanyaan akreditasi 
                    ?>
                        <li class="nav-item">
                            <a class="nav-link" href="pages/forms/pertanyaan_akreditasi.php<?php if (isset($_SESSION['halaman'])) echo "?halaman=" . $_SESSION['halaman'] ?>">
                                <i class="icon-columns menu-icon"></i>
                                <span class="menu-title">Pertanyaan Akreditasi</span>
                            </a>
                        </li>
                    <?php } // Akhir dari if 
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/forms/laporan_kinerja.php">
                            <i class="icon-content-left menu-icon"></i>
                            <span class="menu-title">Laporan Kinerja</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pages/tables/simulasi_akreditasi.php">
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
                            <div class="row text-center">
                                <div class="col-md stretch-card transparent">
                                    <div class="card card-tale">
                                        <div class="card-body">
                                            <p class="fs-40 mt-3 mb-2"><b><?= is_float($elemen_list['a']) ? round($elemen_list['a'], 2) : $elemen_list['a'] ?></b><span class="fs-20">/<?= $elemen_list['jumlah_a'] * 4 ?></span></p>
                                            <medium class="mb-0"><b>Elemen A</b></medium>
                                            <p>Skor</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md stretch-card transparent">
                                    <div class="card card-dark-blue">
                                        <div class="card-body">
                                            <p class="fs-40 mt-3 mb-2"><b><?= is_float($elemen_list['b']) ? round($elemen_list['b'], 2) : $elemen_list['b'] ?></b><span class="fs-20">/<?= $elemen_list['jumlah_b'] * 4 ?></span></p>
                                            <medium class="mb-0"><b>Elemen B</b></medium>
                                            <p>Skor</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md stretch-card transparent">
                                    <div class="card card-light-blue">
                                        <div class="card-body">
                                            <p class="fs-40 mt-3 mb-2"><b><?= is_float($elemen_list['c']) ? round($elemen_list['c'], 2) : $elemen_list['c'] ?></b><span class="fs-20">/<?= $elemen_list['jumlah_c'] * 4 ?></span></p>
                                            <medium class="mb-0"><b>Elemen C</b></medium>
                                            <p>Skor</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md stretch-card transparent">
                                    <div class="card card-light-danger">
                                        <div class="card-body">
                                            <p class="fs-40 mt-3 mb-2"><b><?= is_float($elemen_list['d']) ? round($elemen_list['d'], 2) : $elemen_list['d'] ?></b><span class="fs-20">/<?= $elemen_list['jumlah_d'] * 4 ?></span></p>
                                            <medium class="mb-0"><b>Elemen D</b></medium>
                                            <p>Skor</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md stretch-card transparent">
                                    <div class="card card-dark">
                                        <div class="card-body">
                                            <p class="fs-40 mt-3 mb-2"><b><?= is_float($elemen_list['e']) ? round($elemen_list['e'], 2) : $elemen_list['e'] ?></b><span class="fs-20">/<?= $elemen_list['jumlah_e'] * 4 ?></span></p>
                                            <medium class="mb-0"><b>Elemen E</b></medium>
                                            <p>Skor</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <p class="card-title">Elemen & Indikator Akreditasi <?= $filter != 'none' ? "(Hanya Elemen " . strtoupper($filter) . ")" : "" ?></p>
                                    <p>Filter Elemen: <a href="index.php">Tidak ada</a> | <a href="?filter=a">A</a> | <a href="?filter=b">B</a> | <a href="?filter=c">C</a> | <a href="?filter=d">D</a> | <a href="?filter=e">E</a></p>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="display expandable-table" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>No. </th>
                                                            <th>Elemen</th>
                                                            <th>Indikator</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        while ($data = mysqli_fetch_array($result)) {
                                                            if ($filter != 'none') {
                                                                // Hitung nomor/indikator saat ini
                                                                $i++;

                                                                // Elemen A - hanya nomor/indikator 1
                                                                if ($filter == 'a') {
                                                                    if ($i > 1)
                                                                        break;
                                                                }
                                                                // Elemen B - hanya nomor/indikator 2
                                                                if ($filter == 'b') {
                                                                    if ($i != 2)
                                                                        continue;
                                                                    if ($i > 2)
                                                                        break;
                                                                }
                                                                // Elemen C - nomor/indikator 3 s.d. 71
                                                                if ($filter == 'c') {
                                                                    if (!($i >= 3 && $i <= 71))
                                                                        continue;
                                                                    if ($i > 70)
                                                                        break;
                                                                }
                                                                // Elemen D - nomor/indikator 72 s.d. 75
                                                                if ($filter == 'd') {
                                                                    if (!($i >= 72 && $i <= 75))
                                                                        continue;
                                                                    if ($i > 75)
                                                                        break;
                                                                }
                                                                // Elemen E - nomor/indikator 76 s.d. 78
                                                                if ($filter == 'e') {
                                                                    if (!($i >= 76 && $i <= 78))
                                                                        continue;
                                                                    if ($i > 78)
                                                                        break;
                                                                }
                                                            }
                                                        ?>
                                                            <tr>
                                                                <td>
                                                                    <?= $data['id'] ?>
                                                                </td>
                                                                <td>
                                                                    <b>
                                                                        <?php
                                                                        // Jika ini nomor/indikator pertama pada suatu elemen, maka langsung cetak
                                                                        // Nomor/indikator pertama untuk Elemen A = 1, B = 2, C = 3, D = 72, E = 76
                                                                        if ($data['id'] == 1 || $data['id'] == 2 || $data['id'] == 3 || $data['id'] == 72 || $data['id'] == 76)
                                                                            // Elemen bisa saja lebih dari satu baris, sehingga harus dipisah
                                                                            if (perluPemisah($data['elemen'])) {
                                                                                cetakElemen($data['elemen']);
                                                                                echo "<br>";
                                                                            } else
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
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    // Elemen bisa saja lebih dari satu baris, sehingga harus dipisah
                                                                    if (perluPemisah($data['indikator']))
                                                                        cetakElemen($data['indikator']);
                                                                    else
                                                                        echo $data['indikator'];
                                                                    ?>
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
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:partials/_footer.html -->
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021. Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin template</a> from BootstrapDash. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i></span>
                    </div>
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by <a href="https://www.themewagon.com/" target="_blank">Themewagon</a></span>
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
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="vendors/chart.js/Chart.min.js"></script>
    <script src="vendors/datatables.net/jquery.dataTables.js"></script>
    <script src="vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
    <script src="js/dataTables.select.min.js"></script>

    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
    <!-- endinject -->
    <!-- Custom js for this page-->
    <script src="js/dashboard.js"></script>
    <script src="js/Chart.roundedBarCharts.js"></script>
    <!-- End custom js for this page-->
</body>

</html>