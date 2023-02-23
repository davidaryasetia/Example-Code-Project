<?php
// Cek terlebih dahulu apakah perlu dipisah atau tidak
function perluPemisah($entitas)
{
    if (strpos($entitas, '*') == false)
        return false;
    else
        return true;
}

function pemisahEntitas($entitas)
{
    return explode("*", $entitas);
}
