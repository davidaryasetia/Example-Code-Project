<?php
session_start();

include_once("../../controllers/connection.php");
include_once("../../controllers/halamanController.php");
include_once("../../controllers/elemenController.php");
include_once("../../controllers/pemisahController.php");
include_once("../../controllers/nilaiAkreditasiController.php");

$list_nilai = getListNilai();
$list_deskripsi = getListDeskripsi();

// Jika query GET untuk halaman tidak ada, maka ambil nilai default 1 (halaman pertama)
if (!isset($_GET['halaman'])) {
  $halaman = 1;
} else {
  $halaman = $_GET['halaman'];
}

// Masukkan halaman ke dalam SESSION agar bisa mengingat halaman terakhir
$_SESSION['halaman'] = $halaman;

$result = mysqli_query(
  $conn,
  "SELECT *
  FROM `penilaian`
  WHERE id = $halaman
  "
);

$data = mysqli_fetch_array($result);

// Jika tidak ada SESSION untuk hasil rumus, berarti belum dihitung
if (!isset($_SESSION['hasil_rumus'])) {
  $hasil_rumus = "(belum submit variabel input)";
} else {
  // Lalu cek terlebih dahulu apakah hasil rumus adalah untuk halaman ini atau bukan
  if ($_SESSION['halaman_hasil_rumus'] == $data['id'])
    $hasil_rumus = $_SESSION['hasil_rumus'];
  else
    $hasil_rumus = "(belum submit variabel input)";
}

// Karena ada beberapa entitas yang bisa saja lebih dari satu, maka kita asumsikan bahwa
// entitas saat ini berjumlah satu. Selanjutnya akan dicek apakah entitas lebih dari satu atau tidak
$jumlah = 1;

// Indikator bisa saja lebih dari satu, sehingga harus dipisah
if (perluPemisah($data['indikator'])) {
  $indikator = pemisahEntitas($data['indikator']);
  $jumlah = count($indikator);

  // Jika indikator perlu dipisah, maka secara otomatis nilai juga perlu dipisah juga
  $nilai_4 = pemisahEntitas($data['nilai_4']);
  $nilai_3 = pemisahEntitas($data['nilai_3']);
  $nilai_2 = pemisahEntitas($data['nilai_2']);
  $nilai_1 = pemisahEntitas($data['nilai_1']);
  $nilai_0 = pemisahEntitas($data['nilai_0']);
}
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
            <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">

                  <h4 class="card-title">
                    <?php
                    // Elemen bisa saja lebih dari satu baris, sehingga harus dipisah
                    if (perluPemisah($data['elemen']))
                      cetakElemen($data['elemen']);
                    else
                      echo $data['elemen'];
                    ?>
                  </h4>
                  <p>
                    <b>Indikator :<br>
                      <?php
                      if ($jumlah == 1)
                        echo $data['indikator'];
                      else {
                        for ($i = 0; $i < count($indikator); $i++)
                          echo $indikator[$i] . "<br>";
                      }
                      ?>
                    </b>
                  </p>
                  <form method="POST" action="../../controllers/nilaiAkreditasiController.php">
                    <div class="row">
                      <div class="col-md">
                        <p class="">
                          <b>Inputkan Perkiraan Nilai yang Akan Didapatkan :</b><br>
                          <span class="card-description">Berikan Nilai 0-4 berdasarkan pedoman dibawah</span>
                        </p>
                      </div>
                      <div class="col-md row justify-content-end">
                        <div class="col-auto">
                          <input type="number" class="form-control" id="input-nilai" name="nilai" placeholder="Nilai" step='any' min=0 max=4 style="margin-left: 10px; margin-right: 10px;" value="<?php if ($list_nilai[$halaman - 1] != -1) echo $list_nilai[$halaman - 1] ?>">
                        </div>
                        <input type="text" hidden value="<?= $halaman  ?>" name="halaman">
                        <!-- Agar tidak error saat submit menggunakan JS -->
                        <input type="text" hidden name="deskripsi" id="input-deskripsi" value="-1">
                        <input type="text" hidden name="submit">
                        <div class="col-auto">
                          <button id="submit-skor" type="submit" class="btn btn-primary">Submit</button>
                        </div>
                      </div>
                    </div>
                  </form>
                  <div class="row">
                    <!-- Cek jumlah, jika 1 maka tidak perlu looping untuk mencetak -->
                    <div class="col-6">
                      <?php if ($jumlah == 1) { ?>
                        <p class="mt-4 mb-0">
                          <b>Panduan Nilai :</b>
                        </p>
                        <div class="table-responsive">
                          <table style="table-layout: fixed; width: 100%" class="table-bordered">
                            <tbody>
                              <tr>
                                <td style="word-wrap: break-word;padding: 20px;width: 85%;">
                                  <?= $data['nilai_4'] ?>
                                </td>
                                <td class="text-center">
                                  <b>4</b>
                                </td>
                              </tr>
                              <tr>
                                <td style="word-wrap: break-word;padding: 20px;width: 85%;">
                                  <?= $data['nilai_3'] ?>
                                </td>
                                <td class="text-center">
                                  <b>3</b>
                                </td>
                              </tr>
                              <tr>
                                <td style="word-wrap: break-word;padding: 20px;width: 85%;">
                                  <?= $data['nilai_2'] ?>
                                </td>
                                <td class="text-center">
                                  <b>2</b>
                                </td>
                              </tr>
                              <tr>
                                <td style="word-wrap: break-word;padding: 20px;width: 85%;">
                                  <?= $data['nilai_1'] ?>
                                </td>
                                <td class="text-center">
                                  <b>1</b>
                                </td>
                              </tr>
                              <tr>
                                <td style="word-wrap: break-word;padding: 20px;width: 85%;">
                                  <?= $data['nilai_0'] ?>
                                </td>
                                <td class="text-center">
                                  <b>0</b>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      <?php } // Akhir dari if ($jumlah == 1) 
                      ?>

                      <!-- Jika jumlah lebih dari 1, maka harus dicetak menggunakan looping -->
                      <?php if ($jumlah > 1) {
                        for ($i = 0; $i < $jumlah; $i++) {
                      ?>
                          <p>
                            <b>Indikator <?= $i + 1 ?> :<br>
                              <?= $indikator[$i] ?>
                            </b>
                          </p>
                          <div class="table-responsive pt-3">
                            <table style="table-layout: fixed; width: 100%" class="table-bordered">
                              <tbody>
                                <tr>
                                  <td style="word-wrap: break-word;padding: 20px;width: 85%;">
                                    <?= $nilai_4[$i] ?>
                                  </td>
                                  <td class="text-center">
                                    <b>4</b>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="word-wrap: break-word;padding: 20px;width: 85%;">
                                    <?= $nilai_3[$i] ?>
                                  </td>
                                  <td class="text-center">
                                    <b>3</b>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="word-wrap: break-word;padding: 20px;width: 85%;">
                                    <?= $nilai_2[$i] ?>
                                  </td>
                                  <td class="text-center">
                                    <b>2</b>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="word-wrap: break-word;padding: 20px;width: 85%;">
                                    <?= $nilai_1[$i] ?>
                                  </td>
                                  <td class="text-center">
                                    <b>1</b>
                                  </td>
                                </tr>
                                <tr>
                                  <td style="word-wrap: break-word;padding: 20px;width: 85%;">
                                    <?= $nilai_0[$i] ?>
                                  </td>
                                  <td class="text-center">
                                    <b>0</b>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                      <?php
                          // Jika belum sampai entitas terakhir, maka cetak <br> sebagai pemisah antar entitas
                          if ($i < $jumlah - 1)
                            echo "<br>";
                        } // Akhir dari for loop 
                      } // Akhir dari if ($jumlah > 1) 
                      ?>
                      <p class="mt-4" id="catatan">
                        <b>Catatan :</b><br>
                        <?php
                        // Catatan bisa saja lebih dari satu baris, sehingga harus dipisah
                        if (perluPemisah($data['catatan']))
                          cetakElemen($data['catatan']);
                        else
                          echo $data['catatan'];
                        ?>
                      </p>
                    </div>
                    <div class="col-6">

                      <p class="mt-4">
                        <b>Deskripsi :</b><br>
                        <textarea placeholder="Bisa dikosongkan apabila tidak diperlukan." onchange="update_deskripsi(this)" class="form-control" name="deskripsi" style="resize: vertical; height: 200px; white-space: pre-wrap;"><?php if ($list_deskripsi[$halaman - 1] != -1) echo $list_deskripsi[$halaman - 1] ?></textarea>
                      </p>

                      <form method='POST' action='../../controllers/elemenController.php'>
                        <p class="mt-4">
                          <b id='rumus'>Penghitung Rumus :</b>
                          <?php
                          // Akan dicek apakah indikator saat ini ada pada list indikator yang ada rumusnya (scroll ke atas sendiri)
                          if (in_array($data['id'], getListIndikatorDenganRumus())) {
                            // Ambil nama output dan list variabelnya
                            $nama_output = getVariabelOutputByIndikator($data['id'])['nama_output'];
                            $list_variabel = getVariabelOutputByIndikator($data['id'])['list_variabel'];
                            // Jika terdapat hasil rumus, cetak juga button untuk submit skor
                            echo "<br>";
                            // Setelah pengecekan, lakukan cetak input rumus
                            cetakInputRumus($list_variabel, $halaman);
                            // Cetak semua nama output beserta hasil 
                            for ($i = 0; $i < count($nama_output); $i++) {
                              if ($hasil_rumus == '(belum submit variabel input)')
                                echo "<span>$nama_output[$i] = $hasil_rumus</span><br>";
                              else
                                echo "<span>$nama_output[$i] = $hasil_rumus[$i]</span><br>";
                            }
                            echo "<input type='text' hidden value='$halaman' name='halaman'>";
                            echo "<button type='submit' class='btn btn-warning mt-2' name='submit_rumus'>Hitung</button>";
                            if ($hasil_rumus != '(belum submit variabel input)')
                              echo " <button type='button' class='btn btn-primary mt-2' onclick='submit_skor()'>Submit</button>";
                          } else
                            echo "<br>Tidak terdapat rumus pada indikator ini.";
                          ?>
                        </p>
                      </form>
                    </div>

                  </div>

                  <div class="row mt-5">
                    <div class="col-md">
                      <a href="<?php halamanSebelumnya($halaman) ?>"><button type="submit" class="btn btn-outline-light">Sebelumnya</button></a>
                    </div>
                    <div class="col-md">
                      <span>Elemen: </span>
                      <select name="elemen" onchange="pindah_halaman(this)">
                        <option value="1" <?= $data['id'] == 1 ? "selected" : "" ?>>A</option>
                        <option value="2" <?= $data['id'] == 2 ? "selected" : "" ?>>B</option>
                        <option value="3" <?= $data['id'] >= 3 && $data['id'] <= 71 ? "selected" : "" ?>>C</option>
                        <option value="72" <?= $data['id'] >= 72 && $data['id'] <= 75 ? "selected" : "" ?>>D</option>
                        <option value="76" <?= $data['id'] >= 76 && $data['id'] <= 78 ? "selected" : "" ?>>E</option>
                      </select>
                      <span> ~ Halaman: </span>
                      <select name="halaman" onchange="pindah_halaman(this)">
                        <?php
                        $result = mysqli_query($conn, "SELECT id FROM `penilaian` ORDER BY id");
                        while ($data = mysqli_fetch_array($result)) {
                        ?>
                          <option value="<?= $data['id'] ?>" <?= $data['id'] == $halaman ? "selected" : "" ?>>
                            <?= $data['id'] ?>
                          </option>
                        <?php } //Akhir dari while 
                        ?>
                      </select>
                    </div>
                    <div class="col-md">
                      <a href="<?php halamanSelanjutnya($halaman) ?>"><button type="submit" class="btn btn-primary float-right">Selanjutnya</button></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
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
  <script>
    function pindah_halaman(element) {
      document.location.href = "pertanyaan_akreditasi.php?halaman=" + element.value;
    }
    // Dipakai di button submit skor saat setelah menghitung rumus
    function submit_skor() {
      document.getElementById("input-nilai").value = <?php if (is_array($hasil_rumus)) echo count($hasil_rumus) == 1 ? $hasil_rumus[0] : $hasil_rumus[count($hasil_rumus) - 1];
                                                      else echo "f" ?>;
      document.getElementById("submit-skor").click();
    }

    function update_deskripsi(element) {
      document.getElementById("input-deskripsi").value = element.value.replace(/\n\r?/g, '&#13;&#10;');
      console.log(document.getElementById("input-deskripsi").value);
    }
  </script>
  <!-- End custom js for this page-->
</body>

</html>