<?php
include_once("nilaiAkreditasiController.php");

$list_nilai = getListNilai();

echo "<pre>";
print_r($list_nilai);
echo "<pre>";