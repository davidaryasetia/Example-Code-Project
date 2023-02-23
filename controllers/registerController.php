<?php
require_once("connection.php");

session_start();

if (isset($_SESSION['username'])) {
    header("Location: ../index.php");
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $result = mysqli_query(
        $conn,
        "INSERT INTO users (name, username, password, role) 
        VALUES('$name', '$username', '$password', '$role')
    "
    ) or die(mysqli_error($conn));

    messageAlert("Register berhasil! Silahkan login.", 2);
}

messageAlert("Anda seharusnya tidak berada di sini. Harap kembali ke halaman sebelumnya.");
