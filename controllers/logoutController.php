<?php
include_once("connection.php");

session_start();

// Hapus SESSION beserta COOKIE-nya
unset($_SESSION);
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}
session_destroy();

messageAlert("Anda telah berhasil logout!", NULL, "../pages/forms/login.php");
