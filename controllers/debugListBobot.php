<?php
include_once("elemenController.php");
include_once("nilaiAkreditasiController.php");

$list_bobot = getListBobot(getListElemen());

echo "<pre>";
print_r($list_bobot);
echo "<pre>";