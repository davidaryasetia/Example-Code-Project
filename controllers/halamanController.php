<?php
function halamanSebelumnya($halaman) {
    if ($halaman == 1)
        echo "../../index.php";
    else
        echo "pertanyaan_akreditasi.php?halaman=" . ($halaman - 1);
}

function halamanSelanjutnya($halaman) {
    if ($halaman == 78)
        echo "../tables/simulasi_akreditasi.php";
    else
        echo "pertanyaan_akreditasi.php?halaman=" . ($halaman + 1);
}