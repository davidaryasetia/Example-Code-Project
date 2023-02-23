<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once("nilaiAkreditasiController.php");

// Elemen bisa saja lebih dari satu baris, sehingga harus dipisah
function cetakElemen($elemen)
{
    $elemen_pisah = explode("*", $elemen);
    for ($i = 0; $i < count($elemen_pisah); $i++) {
        echo $elemen_pisah[$i];
        // Jika belum sampai entitas terakhir, maka cetak <br> sebagai pemisah antar entitas
        if ($i < count($elemen_pisah) - 1)
            echo "<br>";
    }
}

function getListElemen($list_nilai = NULL)
{
    if ($list_nilai == NULL)
        $list_nilai = getListNilai();

    // Set ke 0 terlebih dahulu karena nanti akan ditotal
    $elemen_c = 0;
    $elemen_d = 0;
    $elemen_e = 0;

    // Ini juga, namun ini untuk dihitung jumlahnya
    $jumlah_c = 0;
    $jumlah_d = 0;
    $jumlah_e = 0;

    // Sekarang akan dilakukan pengecekan, jika ada nilai -1 maka akan diubah menjadi 0
    // Mengingat ini ada di dashboard, sehingga lebih baik jadi 0 saya daripada menjadi negatif
    for ($i = 0; $i < count($list_nilai) - 1; $i++) {
        if ($list_nilai[$i] == -1)
            $list_nilai[$i] = 0;

        // Range untuk elemen C
        if ($i >= 2 && $i <= 70) {
            $elemen_c += $list_nilai[$i];
            $jumlah_c++;
        }

        // Range untuk elemen D
        if ($i >= 71 && $i <= 74) {
            $elemen_d += $list_nilai[$i];
            $jumlah_d++;
        }

        // Range untuk elemen E
        if ($i >= 75 && $i <= 77) {
            $elemen_e += $list_nilai[$i];
            $jumlah_e++;
        }
    }

    $elemen_a = $list_nilai[0];
    $elemen_b = $list_nilai[1];

    return array(
        'a' => $elemen_a, 'b' => $elemen_b, 'c' => $elemen_c, 'd' => $elemen_d, 'e' => $elemen_e,
        'jumlah_a' => 1, 'jumlah_b' => 1, 'jumlah_c' => $jumlah_c, 'jumlah_d' => $jumlah_d, 'jumlah_e' => $jumlah_e,
        'total_elemen' => $elemen_a + $elemen_b + $elemen_c + $elemen_d + $elemen_e
    );
}

function getListIndikatorDenganRumus()
{
    return [7, 8, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 
    30, 31, 33, 34, 35, 36, 40, 42, 43, 44, 46, 50, 52, 54, 56, 58, 59, 60, 61, 62, 10];
}

function cetakInputRumus($list_variabel, $halaman)
{
    for ($i = 0; $i < count($list_variabel); $i++) {
        // Jika menemukan bintang, maka cetak pembatas variabel antar indikator
        if ($list_variabel[$i] == '*') {
            echo "-----------<br><br>";
            continue;
        }
        echo "<span>Input variabel $list_variabel[$i]</span>";
        echo "<input type='number' step='any'";
        // Pertama, cek apakah sudah ada hitungan rumus (sebelumnya tersimpan di POST, lalu dipindah ke SESSION, coba scroll ke bawah sendiri)
        // Jika true, maka masuk ke pengecekan kedua. Jika halaman hitungan rumus yang ada saat ini sama dengan halaman, maka masukkan ke value (agar tercetak di input box)
        if (isset($_SESSION['POST']['halaman']) && $_SESSION['POST']['halaman'] == $halaman) {
            $value = $_SESSION['POST'][strval($list_variabel[$i])];
            echo "value='$value'";
        }
        echo "placeholder='$list_variabel[$i]' class='form-control' name='$list_variabel[$i]'>";
        echo "<br>";
    }
}

function getVariabelOutputByIndikator($id_indikator)
{
    if ($id_indikator == 7 || $id_indikator == 8 || $id_indikator == 13 || $id_indikator == 14 || $id_indikator == 15 || $id_indikator == 33 || $id_indikator == 42 || $id_indikator == 52) {
        $nama_output = ['Skor'];
        $list_variabel = ['A', 'B'];
    }
    if ($id_indikator == 40 || $id_indikator == 46) {
        $nama_output = ['Skor'];
        $list_variabel = ['A', 'B', 'C'];
    }
    if ($id_indikator == 43) {
        $nama_output = ['Skor'];
        $list_variabel = ['A', 'B', 'C', 'D', 'E'];
    }
    if ($id_indikator == 16 || $id_indikator == 23) {
        $nama_output = ['NDTPS', 'PDTT', 'Skor'];
        $list_variabel = ['NDTPS', 'NDTT', 'NDT'];
    }
    if ($id_indikator == 17) {
        $nama_output = ['PDS3', 'Skor'];
        $list_variabel = ['NDS3', 'NDTPS'];
    }
    if ($id_indikator == 18) {
        $nama_output = ['PDSK', 'Skor'];
        $list_variabel = ['NDSK', 'NDTPS'];
    }
    if ($id_indikator == 19) {
        $nama_output = ['PGBLKL', 'Skor'];
        $list_variabel = ['NDGB', 'NDL', 'NDTPS', 'NDLK'];
    }
    if ($id_indikator == 20) {
        $nama_output = ['RMD', 'Skor'];
        $list_variabel = ['NDTPS', 'NM'];
    }
    if ($id_indikator == 21) {
        $nama_output = ['RDPU', 'Skor'];
        $list_variabel = ['RDPU'];
    }
    if ($id_indikator == 22) {
        $nama_output = ['Skor'];
        $list_variabel = ['EWMP'];
    }
    if ($id_indikator == 24) {
        $nama_output = ['PMKI', 'Skor'];
        $list_variabel = ['MKKI', 'MKK'];
    }
    if ($id_indikator == 25) {
        $nama_output = ['RRD', 'Skor'];
        $list_variabel = ['NRD', 'NDTPS'];
    }
    if ($id_indikator == 26 || $id_indikator == 27) {
        $nama_output = ['RI', 'RN', 'RL','Skor'];
        $list_variabel = ['NI', 'NN', 'NL', 'NDTPS'];
    }
    if ($id_indikator == 28) {
        $nama_output = ['RW', 'RN', 'RI', 'Skor'];
        $list_variabel = ['NA1', 'NA2', 'NA3', 'NA4', 'NB1', 'NB2', 'NB3', 'NC1', 'NC2', 'NC3', 'NDTPS'];
    }
    if ($id_indikator == 29 || $id_indikator == 30) {
        $nama_output = ['RS', 'Skor'];
        if ($id_indikator == 29)
            $list_variabel = ['NAS', 'NDTPS'];
        else
            $list_variabel = ['NAPJ', 'NDTPS'];
    }
    if ($id_indikator == 31) {
        $nama_output = ['RLP', 'Skor'];
        $list_variabel = ['NA', 'NB', 'NC', 'NDTPS', 'ND'];
    }
    if ($id_indikator == 34 || $id_indikator == 35 || $id_indikator == 36) {
        $nama_output = ['Skor'];
        if ($id_indikator == 34)
            $list_variabel = ['DOP'];
        else if ($id_indikator == 35)
            $list_variabel = ['DPD'];
        else if ($id_indikator == 36)
            $list_variabel = ['DPkMD'];
    }
    if ($id_indikator == 44) {
        $nama_output = ['PJP', 'Skor'];
        $list_variabel = ['JP', 'JB'];
    }
    if ($id_indikator == 50) {
        $nama_output = ['Skor'];
        $list_variabel = ['NMKI'];
    }
    if ($id_indikator == 54) {
        $nama_output = ['PPDM', 'Skor'];
        $list_variabel = ['NPM', 'NPD'];
    }
    if ($id_indikator == 56) {
        $nama_output = ['PPkDM', 'Skor'];
        $list_variabel = ['NPkMM', 'NPkMD'];
    }
    if ($id_indikator == 58) {
        $nama_output = ['Skor'];
        $list_variabel = ['RIPK'];
    }
    if ($id_indikator == 59 || $id_indikator == 60) {
        $nama_output = ['RI', 'RN', 'RW', 'Skor'];
        $list_variabel = ['NI', 'NN', 'NW', 'NM'];
    }
    if ($id_indikator == 61) {
        $nama_output = ['Skor'];
        $list_variabel = ['MS'];
    }
    if ($id_indikator == 62) {
        $nama_output = ['Skor'];
        $list_variabel = ['PTW'];
    }
    if ($id_indikator == 10) {
        $nama_output = ['Skor Indikator 1', 'Skor Indikator 2', 'Skor'];
        $list_variabel = ['N1', 'N2', 'N3', 'NDTPS', '*', 'NI', 'NN', 'NW'];
    }

    return array('nama_output' => $nama_output, 'list_variabel' => $list_variabel);
}

function hitungRumus($var)
{
    // Jika hanya satu hasil saja, jangan lupa beri index 0 agar dianggap array
    // Harus array agar nanti saat penampilan hasil tidak error

    if ($var['halaman'] == 7 || $var['halaman'] == 8 || $var['halaman'] == 13 || $var['halaman'] == 14 || $var['halaman'] == 15 || $var['halaman'] == 33 || $var['halaman'] == 42 || $var['halaman'] == 52) {
        $A = $var['A'];
        $B = $var['B'];
        if ($var['halaman'] == 13 || $var['halaman'] == 33)
            $hasil[0] = ($A + $B) / 2;
        else if ($var['halaman'] == 14)
            $hasil[0] = ((4 * $A) + $B) / 5;
        else
            $hasil[0] = ($A + (2 * $B)) / 3;
    }
    if ($var['halaman'] == 40 || $var['halaman'] == 46){
        $A = $var['A'];
        $B = $var['B'];
        $C = $var['C'];
        $hasil[0] = ($A + (2 * $B) + (2 * $C)) / 5;
    }
    if ($var['halaman'] == 43){
        $A = $var['A'];
        $B = $var['B'];
        $C = $var['C'];
        $D = $var['D'];
        $E = $var['E'];
        $hasil[0] = ($A + (2 * $B) + (2 * $C) + (2 * $D) + (2 * $E)) / 9;
    }
    if ($var['halaman'] == 16 || $var['halaman'] == 23){
        $NDTPS = $var['NDTPS'];
        $NDTT = $var['NDTT'];
        $NDT = $var['NDT'];
        $hasil[0] = $NDTPS;
        $hasil[1] = ($NDTT / ($NDT + $NDTT)) * 100;
        if ($var['halaman'] == 16){
            $A = (($NDTPS - 3)/9);
            $B = (40 - $hasil[1])/30;
            if ($NDTPS >= 12 && $hasil[1] <= 10)
                $hasil[2] = 4;
            else if ((3 <= $NDTPS && $NDTPS <= 12) && (10 < $hasil[1] && $hasil[1] <= 40))
                $hasil[2] = (2 + 2 * ($A * $B));
            else if (($NDTPS >= 12) && (10 < $hasil[1] && $hasil[1] <= 40))
                $hasil[2] = (2 + 2 * ($A * $B));
            else if ((3 <= $NDTPS && $NDTPS < 12) && ($hasil[1] > 40))
                $hasil[2] = 1;
            else if (($NDTPS <= 3) && ($hasil[1] == 0))
                $hasil[2] = 0;
        }
        if ($var['halaman'] == 23){
            if ($NDTPS >= 5 && $hasil[1] == 0)
                $hasil[2] = 4;
            else if ((5 <= $NDTPS) && (0 < $hasil[1] && $hasil[1] <= 40))
                $hasil[2] = (4 - (5 * $hasil[1]));
            else if ((5 <= $NDTPS) && (40 < $hasil[1] && $hasil[1] <= 60))
                $hasil[2] = 1;
            else if (60 < $hasil[1])
                $hasil[2] = 0;
        }
    }
    if ($var['halaman'] == 17){
        $NDS3 = $var['NDS3'];
        $NDTPS = $var['NDTPS'];
        $hasil[0] = ($NDS3/$NDTPS) * 100;
        if ($hasil[0] >= 15)
            $hasil[1] = 4;
        else if ($hasil[0] < 15)
            $hasil[1] = 2 + ((4 * ($hasil[0]/100)) / 1.5);
    }
    if ($var['halaman'] == 18){
        $NDSK = $var['NDSK'];
        $NDTPS = $var['NDTPS'];
        $hasil[0] = ($NDSK/$NDTPS) * 100;
        if ($hasil[0] >= 50)
            $hasil[1] = 4;
        else if ($hasil[0] < 50)
            $hasil[1] = 1 + (6 * $hasil[0]/100);
    }
    if ($var['halaman'] == 19){
        $NDL = $var['NDL'];
        $NDLK = $var['NDLK'];
        $NDGB = $var['NDGB'];
        $NDTPS = $var['NDTPS'];
        $hasil[0] = (($NDGB + $NDLK + $NDL) / $NDTPS) * 100;
        if ($hasil[0] >= 50)
            $hasil[1] = 4;
        else if ($hasil[0] < 50)
            $hasil[1] = 2 + ((20 * ($hasil[0]/100)) / 5);
    }
    if ($var['halaman'] == 20){
        $NDM = $var['NM'];
        $NDTPS = $var['NDTPS'];
        $hasil[0] = ($NDM / $NDTPS);
        if ($hasil[0] >= 15 && $hasil[0] <= 25)
            $hasil[1] = 4;
        else if (($hasil[0] < 15) && ($hasil[0] < 25 && $hasil[0] <=35))
            $hasil[1] = (4 * $hasil[0]) / 15;    
        else if (($hasil[0] < 25 && $hasil[0] <=35))
            $hasil[1] = (70 - (2 * $hasil[0])) / 5;
        else if ($hasil[0] > 35)
            $hasil[1] = 0;
    }
    if ($var['halaman'] == 21){
        $RDPU = $var['RDPU'];
        $hasil[0] = $RDPU;
        if ($hasil[0] <= 6)
            $hasil[1] = 4;
        else if (($hasil[0] > 6) && ($hasil[0] <=10))
            $hasil[1] = 7 - ($hasil[0] / 2);
        else if ($hasil[0] > 10)
            $hasil[1] = 0;
    }
    if ($var['halaman'] == 22){
        $EWMP = $var['EWMP'];
        if ($EWMP == 14)
            $hasil[0] = 4;
        else if ($EWMP >= 12 && $EWMP < 14)
            $hasil[0] = ((3 * $EWMP) - 34) / 2;
        else if ($EWMP > 14 && $EWMP <= 16)
            $hasil[0] = (50 - (3 * $EWMP)) / 2;
        else if ($EWMP > 16 || $EWMP < 12)
            $hasil[0] = 0;
    }
    if ($var['halaman'] == 24){
        $MKKI = $var['MKKI'];
        $MKK = $var['MKK'];
        $hasil[0] = ($MKKI / $MKK) * 100;
        if ($hasil[0] >= 20)
            $hasil[1] = 4;
        else if ($hasil[0] < 20)
            $hasil[1] = 2 + (10 * ($hasil[0]/100));
    }
    if ($var['halaman'] == 25){
        $NRD = $var['NRD'];
        $NDTPS = $var['NDTPS'];
        $hasil[0] = ($NRD / $NDTPS);
        if ($hasil[0] >= 0.5)
            $hasil[1] = 4;
        else if ($hasil[0] < 0.5)
            $hasil[1] = 2 + (4 * ($hasil[0]));
    }
    if ($var['halaman'] == 26 || $var['halaman'] == 27){
        $NI = $var['NI'];
        $NN = $var['NN'];
        $NL = $var['NL'];
        $NDTPS = $var['NDTPS'];
        $hasil[0] = $NI / 3 / $NDTPS;
        $hasil[1] = $NN / 3 / $NDTPS;
        $hasil[2] = $NL / 3 / $NDTPS;
        $A = $hasil[0]/0.05;
        $B = $hasil[1]/0.3;
        $C = $hasil[2]/1;
        if ($hasil[0] >= 0.05 && $hasil[1] >= 0.3)
            $hasil[3] = 4;
        else if ((0 < $hasil[0] && $hasil[0] < 0.05) || (0 < $hasil[1] && $hasil[1] < 0.3) || (0 < $hasil[2] && $hasil[2] < 1))
            $hasil[3] = 4 * (($A + $B + ($C / 2)) - ($A * $B) - (($A * $C) / 2) - (($A * $B * $C) / 2));
    }
    if ($var['halaman'] == 28){
        $NA1 = $var['NA1'];
        $NA2 = $var['NA2'];
        $NA3 = $var['NA3'];
        $NA4 = $var['NA4'];
        $NB1 = $var['NB1'];
        $NB2 = $var['NB2'];
        $NB3 = $var['NB3'];
        $NC1 = $var['NC1'];
        $NC2 = $var['NC2'];
        $NC3 = $var['NC3'];
        $NDTPS = $var['NDTPS'];
        $hasil[0] = ($NA1 + $NB1 + $NC1) / $NDTPS;
        $hasil[1] = ($NA2 + $NA3 + $NC2 + $NB2) / $NDTPS;
        $hasil[2] = ($NA4 + $NB3 + $NC3) / $NDTPS;
        $A = $hasil[2] / 0.1;
        $B = $hasil[1] / 1;
        $C = $hasil[0] / 2;
        if ($hasil[0] >= 0.1 && $hasil[1] >= 1)
            $hasil[3] = 4;
        else if ((0 < $hasil[0] && $hasil[0] < 0.1) || (0 < $hasil[1] && $hasil[1] < 1) || (0 < $hasil[2] && $hasil[2] < 2))
            $hasil[3] = 4 * (($A + $B + ($C / 2)) - ($A * $B) - (($A * $C) / 2) - (($A * $B * $C) / 2));
    }
    if ($var['halaman'] == 29 || $var['halaman'] == 30){
        $NDTPS = $var['NDTPS'];
        if ($var['halaman'] == 29){    
            $NAS = $var['NAS'];
            $hasil[0] = $NAS / $NDTPS;
            if ($hasil[0] >= 0.5)
                $hasil[1] = 4;
            else if ($hasil[0] < 0.5)
                $hasil[1] = 2 + (4 * $hasil[0]);
        }
        else if ($var['halaman'] == 30){    
            $NAPJ = $var['NAPJ'];
            $hasil[0] = $NAPJ / $NDTPS;
            if ($hasil[0] >= 1)
                $hasil[1] = 4;
            else if ($hasil[0] < 1)
                $hasil[1] = 2 + (2 * $hasil[0]);
        }
    }
    if ($var['halaman'] == 31){
        $NA = $var['NA'];
        $NB = $var['NB'];
        $NC = $var['NC'];
        $ND = $var['ND'];
        $NDTPS = $var['NDTPS'];
        $hasil[0] = (2 * ($NA + $NB + $NC) + $ND) / $NDTPS;
        if ($hasil[0] >= 1)
            $hasil[1] = 4;
        else if ($hasil[0] < 1)
            $hasil[1] = 2 + (2 * $hasil[0]);
    }
    if ($var['halaman'] == 34){
        $DOP = $var['DOP'];
        if ($DOP >= 20)
            $hasil[0] = 4;
        else if ($DOP < 20)
            $hasil[0] = $DOP / 5;
    }
    if ($var['halaman'] == 35){
        $DPD = $var['DPD'];
        if ($DPD >= 10)
            $hasil[0] = 4;
        else if ($DPD < 10)
            $hasil[0] = (2 * $DPD) / 5;
    }
    if ($var['halaman'] == 36){
        $DPkMD = $var['DPkMD'];
        if ($DPkMD >= 5)
            $hasil[0] = 4;
        else if ($DPkMD < 5)
            $hasil[0] = (4 * $DPkMD) / 5;
    }
    if ($var['halaman'] == 44){
        $JP = $var['JP'];
        $JB = $var['JB'];
        $hasil[0] = ($JP / $JB) * 100; 
        if ($hasil[0] >= 30)
            $hasil[1] = 4;
        else if ($hasil[0] < 30)
            $hasil[1] = (40 * $hasil[0]) / 3;
    }
    if ($var['halaman'] == 50){
        $NMKI = $var['NMKI']; 
        if ($NMKI > 3)
            $hasil[0] = 4;
        else if (2 <= $NMKI && $NMKI <= 3)
            $hasil[0] = 3;
        else if ($NMKI == 1)
            $hasil[0] = 2;
    }
    if ($var['halaman'] == 54){
        $NPM = $var['NPM'];
        $NPD = $var['NPD'];
        $hasil[0] = ($NPM/$NPD) * 100; 
        if ($hasil[0] >= 25)
            $hasil[1] = 4;
        else if ($hasil[0] < 25)
            $hasil[1] = 2 + (8 * $hasil[0]/100);
    }
    if ($var['halaman'] == 56){
        $NPkMM = $var['NPkMM'];
        $NPkMD = $var['NPkMD'];
        $hasil[0] = ($NPkMM/$NPkMD) * 100; 
        if ($hasil[0] >= 25)
            $hasil[1] = 4;
        else if ($hasil[0] < 25)
            $hasil[1] = 2 + (8 * $hasil[0]/100);
    }
    if ($var['halaman'] == 58){
        $RIPK = $var['RIPK'];
        if ($RIPK >= 3.25)
            $hasil[1] = 4;
        else if (2 < $RIPK && $RIPK < 3.25)
            $hasil[1] =((8 * $RIPK) - 6 ) / 5;
    }
    if ($var['halaman'] == 59 || $var['halaman'] == 60){
        $NI = $var['NI'];
        $NN = $var['NN'];
        $NW = $var['NW'];
        $NM = $var['NM'];
        if ($var['halaman'] == 59){
            $a = 0.1 / 100;
            $b = 1 / 100;
            $c = 2 / 100;
        }
        else if ($var['halaman'] == 60){
            $a = 0.2 / 100;
            $b = 2 / 100;
            $c = 4 / 100;
        }
        $hasil[0] = $NI / $NM;
        $hasil[1] = $NN / $NM;
        $hasil[2] = $NW / $NM;
        $A = $hasil[0]/$a;
        $B = $hasil[1]/$b;
        $C = $hasil[2]/$c;
        if ($hasil[0] >= $a && $hasil[1] >= $b)
            $hasil[3] = 4;
        else if ((0 < $hasil[0] && $hasil[0] < $a) || (0 < $hasil[1] && $hasil[1] < $b) || (0 < $hasil[2] && $hasil[2] < $c))
            $hasil[3] = 4 * (($A + $B + ($C / 2)) - ($A * $B) - (($A * $C) / 2) - (($B * $C) / 2) + (($A * $B * $C) / 2));
    }
    if ($var['halaman'] == 61){
        $MS = $var['MS'];
        if (3.5 < $MS && $MS <= 4.5)
            $hasil[0] = 4;
        else if (3 < $MS && $MS <= 3.5)
            $hasil[0] = (8 * $MS) - 24;
        else if (4.5 < $MS && $MS <= 7)
            $hasil[0] = (56 - (8 * $MS)) / 5;
        else if ($MS <= 3)
            $hasil[0] = 0;
    }
    if ($var['halaman'] == 62){
        $PTW = $var['PTW'];
        if ($PTW >= 70)
            $hasil[0] = 4;
        else if ($PTW < 70)
            $hasil[0] = 1 +((30 * $PTW / 100) / 7);
    }
    if ($var['halaman'] == 10) {
        $a = 3;
        $b = 1;
        $c = 2;
        $N1 = $var['N1'];
        $N2 = $var['N2'];
        $N3 = $var['N3'];
        $NDTPS = $var['NDTPS'];
        $hasil[0] = (($a * $N1) + ($b * $N2) + ($c * $N3)) / $NDTPS;
        if ($hasil[0] > 4)
            $hasil[0] = 4;


        $a = 2;
        $b = 6;
        $c = 8;
        $NI = $var['NI'];
        $NN = $var['NN'];
        $NW = $var['NW'];
        $A = $NI / $a;
        $B = $NN / $b;
        $C = $NW / $c;
        if ($NI >= $a && $NN >= $b)
            $hasil[1] = 4;
        else if ((0 < $NI && $NI < $a) || (0 < $NN && $NN < $b) || (0 < $NW && $NW <= $c)) {
            $hasil[1] = 4 * (($A + $B + ($C / 2)) - ($A * $B) - (($A * $C) / 2) - (($B * $C) / 2) + (($A * $B * $C) / 2));
            if ($hasil[1] > 4)
                $hasil[1] = 4;
        }

        $A = $hasil[0];
        $B = $hasil[1];
        $hasil[2] = ($A + (2 * $B)) / 3;
    }

    return $hasil;
}

if (isset($_POST['submit_rumus'])) {
    $_SESSION['POST'] = $_POST; // Disimpan untuk dipakai di cetakInputRumus()
    $_SESSION['hasil_rumus'] = hitungRumus($_POST);
    $_SESSION['halaman_hasil_rumus'] = $_POST['halaman'];
    header("Location: ../pages/forms/pertanyaan_akreditasi.php?halaman=" . $_SESSION['halaman_hasil_rumus'] . "#rumus");
}
