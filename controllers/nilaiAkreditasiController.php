<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("connection.php");

if ($_SESSION['id'] == ID_PRODI_D3_IT)
    $users_id = ID_ASESOR_D3_IT;
else
    $users_id = $_SESSION['id'];


$result = mysqli_query(
    $conn,
    "SELECT *
    FROM `nilai`
    WHERE users_id = $users_id
    ORDER BY id
    "
);

if (isset($_POST['submit'])) {
    $halaman = $_POST['halaman'];
    $nilai = $_POST['nilai'];
    $deskripsi = $_POST['deskripsi'];

    // Jika list_nilai sudah ada pada database, maka akan masuk ke if, selain itu akan masuk ke else
    if ($result->num_rows > 0) {
        $data = mysqli_fetch_array($result);

        // Ambil list_nilai dari database dan konversikan ke array
        $list_nilai = unserialize($data['list_nilai']);
        $list_deskripsi = unserialize($data['list_deskripsi']);

        $list_nilai[$halaman - 1] = $nilai;
        $list_deskripsi[$halaman - 1] = $deskripsi;

        // Konversikan ke string untuk dimasukkan ke database
        $input_nilai = serialize($list_nilai);
        $input_deskripsi = serialize($list_deskripsi);

        $result = mysqli_query(
            $conn,
            "UPDATE nilai 
            SET list_nilai = '$input_nilai', list_deskripsi = '$input_deskripsi'
            WHERE users_id = $users_id
            "
        ) or die(mysqli_error($conn));

        messageAlert("Nilai berhasil disubmit!");
    } else {
        inputPertamaListNilai($halaman, $nilai, $deskripsi);
        messageAlert("Nilai berhasil disubmit!");
    }
}

function inisialisasiListNilai()
{
    // Jika list_nilai belum ada (pada database), maka buat array list_nilai dengan nilai -1 terlebih dahulu
    // NOTE: -1 untuk menunjukkan bahwa nilai belum ada
    for ($i = 0; $i < 78; $i++) {
        $list_nilai[$i] = -1;
    }

    return $list_nilai;
}

function inisialisasiListDeskripsi()
{
    // Jika list_deskripsi belum ada (pada database), maka buat array list_deskripsi dengan nilai -1 terlebih dahulu
    // NOTE: -1 untuk menunjukkan bahwa nilai belum ada
    for ($i = 0; $i < 78; $i++) {
        $list_deskripsi[$i] = -1;
    }

    return $list_deskripsi;
}

function inputPertamaListNilai($halaman = NULL, $nilai = NULL, $deskripsi = NULL)
{
    global $conn, $users_id;
    $list_nilai = inisialisasiListNilai();
    $list_deskripsi = inisialisasiListDeskripsi();

    // NULL jika mendapatkan input pertama dari Dashboard atau Simulasi Akreditasi
    if ($halaman == NULL && $nilai == NULL && $deskripsi == NULL) {
        $list_nilai[$halaman - 1] = $nilai;
        $list_deskripsi[$halaman - 1] = $deskripsi;
    }

    // Konversikan ke string untuk dimasukkan ke database
    $input_nilai = serialize($list_nilai);
    $input_deskripsi = serialize($list_deskripsi);

    $result = mysqli_query(
        $conn,
        "INSERT INTO nilai (users_id, list_nilai, list_deskripsi) 
        VALUES ('$users_id', '$input_nilai', '$input_deskripsi')
        "
    ) or die(mysqli_error($conn));
}

function getListNilai()
{
    global $result;
    if (!isset($_SESSION['username']) && $_SESSION['id'] <= 0) {
        return NULL;
    }
    if ($result->num_rows > 0) {
        $data = mysqli_fetch_array($result);

        // Ambil list_nilai dari database dan konversikan ke array
        $list_nilai = unserialize($data['list_nilai']);

        return $list_nilai;
    } else
        inputPertamaListNilai();
    return inisialisasiListNilai();
}

function getListDeskripsi()
{
    if ($_SESSION['id'] == ID_PRODI_D3_IT)
        $users_id = ID_ASESOR_D3_IT;
    else
        $users_id = $_SESSION['id'];

    global $conn;
    $result = mysqli_query(
        $conn,
        "SELECT *
        FROM `nilai`
        WHERE users_id = $users_id
        ORDER BY id
        "
    );

    if (!isset($_SESSION['username']) && $_SESSION['id'] <= 0) {
        return NULL;
    }
    if ($result->num_rows > 0) {
        $data = mysqli_fetch_array($result);

        // Ambil list_deskripsi dari database dan konversikan ke array
        $list_deskripsi = unserialize($data['list_deskripsi']);

        return $list_deskripsi;
    } else
        inputPertamaListNilai();
    return inisialisasiListDeskripsi();
}

function hitungBelumTerisi($list_nilai)
{
    $jumlah_belum_terisi = 0;

    for ($i = 0; $i < 78; $i++) {
        if ($list_nilai[$i] == -1)
            $jumlah_belum_terisi++;
    }

    return $jumlah_belum_terisi;
}

function getListBobot($list_elemen)
{
    $total_bobot = 400;
    $bobot['a'] = 0.02;
    $bobot['b'] = 0.015;
    $bobot['c'] = 0.885;
    $bobot['d'] = 0.05;
    $bobot['e'] = 0.03;

    $list_bobot['a'] = ($bobot['a'] * $total_bobot) / $list_elemen['jumlah_a'] / 4;
    $list_bobot['b'] = ($bobot['b'] * $total_bobot) / $list_elemen['jumlah_b'] / 4;
    $list_bobot['c'] = ($bobot['c'] * $total_bobot) / $list_elemen['jumlah_c'] / 4;
    $list_bobot['d'] = ($bobot['d'] * $total_bobot) / $list_elemen['jumlah_d'] / 4;
    $list_bobot['e'] = ($bobot['e'] * $total_bobot) / $list_elemen['jumlah_e'] / 4;

    return $list_bobot;
}

function getListAkreditasi($list_bobot, $list_elemen, $list_nilai)
{
    $nilai_a = $list_elemen['a'] * $list_bobot['a'];
    $nilai_b = $list_elemen['b'] * $list_bobot['b'];
    $nilai_c = $list_elemen['c'] * $list_bobot['c'];
    $nilai_d = $list_elemen['d'] * $list_bobot['d'];
    $nilai_e = $list_elemen['e'] * $list_bobot['e'];

    $total_nilai = $nilai_a + $nilai_b + $nilai_c + $nilai_d + $nilai_e;

    $list_akreditasi['total_nilai'] = $total_nilai;
    $list_akreditasi['syarat_perlu_akreditasi'] = false;
    $list_akreditasi['syarat_perlu_unggul'] = false;
    $list_akreditasi['syarat_perlu_baik_sekali'] = false;
    $list_akreditasi['status'] = "TMSP";

    // Pengecekan syarat perlu
    if ($list_nilai[15] >= 2 && $list_nilai[39] >= 2 && $list_nilai[71] >= 2) {
        $list_akreditasi['syarat_perlu_akreditasi'] = true;

        if ($list_nilai[68] >= 3 && $list_nilai[18] >= 3.5)
            $list_akreditasi['syarat_perlu_unggul'] = true;

        if ($list_nilai[68] >= 2.5 && $list_nilai[18] >= 3)
            $list_akreditasi['syarat_perlu_baik_sekali'] = true;
    }

    // Perhitungan peringkat akreditasi
    if ($total_nilai >= 361) {
        if ($list_akreditasi['syarat_perlu_akreditasi'] == true)
            if ($list_akreditasi['syarat_perlu_unggul'] == true)
                $list_akreditasi['status'] = 'Unggul';
            else
                $list_akreditasi['status'] == 'baik_sekali';
    } else if ($total_nilai >= 301 && $total_nilai <= 361) {
        if ($list_akreditasi['syarat_perlu_akreditasi'] == true)
            if ($list_akreditasi['syarat_perlu_baik_sekali'] == true)
                $list_akreditasi['status'] == 'Baik Sekali';
            else
                $list_akreditasi['status'] == 'Baik';
    } else if ($total_nilai >= 200 && $total_nilai <= 301) {
        if ($list_akreditasi['syarat_perlu_akreditasi'] == true)
            $list_akreditasi['status'] == 'Baik';
    }

    return $list_akreditasi;
}
