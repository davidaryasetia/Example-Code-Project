<?php
const ID_ASESOR_D3_IT = 14;
const ID_PRODI_D3_IT = 15;

$hostname = 'localhost';
$db = 'akreditasi';
$user = 'root';
$pass = '';

$conn = mysqli_connect($hostname, $user, $pass, $db);
if (!$conn) {
   die("Connection error");
}

function messageAlert($message, $back_count = NULL, $url = NULL)
{
   if ($back_count == NULL && $url == NULL) {
      echo "
      <script>
         alert('$message');
         window.history.go(-1);
      </script>";
   } if ($back_count != NULL) {
      echo "
      <script>
         alert('$message');
         window.history.go(-$back_count);
      </script>";
   } if ($url != NULL) {
      echo "
      <script>
         alert('$message');
         document.location.href = '$url';
      </script>";
   }
}
