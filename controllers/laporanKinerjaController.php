<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("connection.php");

// Jika user saat ini adalah asesor D3 IT, maka set users_id ke prodi D3 IT
if ($_SESSION['id'] == ID_ASESOR_D3_IT)
    $users_id = ID_PRODI_D3_IT;
else
    $users_id = $_SESSION['id'];

$result = mysqli_query(
    $conn,
    "SELECT *
    FROM `nilai`
    WHERE users_id = $users_id
    "
);

function inisialisasiListNilaiLaporan()
{
    for ($i = 0; $i < 45; $i++) {
        $list_nilai_laporan[$i] = 0;
    }

    return $list_nilai_laporan;
}

function getListNilaiLaporan()
{
    global $result;
    $data = mysqli_fetch_array($result);

    if ($data['list_nilai_laporan'] != NULL) {
        // Ambil list_nilai dari database dan konversikan ke array
        $list_nilai_laporan = unserialize($data['list_nilai_laporan']);

        return $list_nilai_laporan;
    } else
        return inisialisasiListNilaiLaporan();
}

if (isset($_POST['1'])) {
    for ($i = 1; $i <= 45; $i++) {
        $list_nilai_laporan[$i - 1] = isset($_POST["$i"]) ? $_POST["$i"] : 0;
    }

    $data = mysqli_fetch_array($result);
    $input = serialize($list_nilai_laporan);

    mysqli_query(
        $conn,
        "UPDATE nilai 
        SET list_nilai_laporan = '$input'
        WHERE users_id = $users_id
        "
    ) or die(mysqli_error($conn));

    messageAlert("Nilai laporan kinerja berhasil disubmit!");
}
