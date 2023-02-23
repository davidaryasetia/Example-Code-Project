<?php
require_once("connection.php");

session_start();

if (isset($_SESSION['username'])) {
    header("Location: ../index.php");
} else {
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $result = mysqli_query(
            $conn,
            "SELECT * 
            FROM users 
            WHERE username = '$username'
            "
        );

        if ($result->num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
            if (password_verify($password, $row['password'])) {
                $_SESSION['name'] = $row['name'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['role'] = $row['role'];
                header("Location: ../index.php");
            } else {
                echo "<script>
                        alert('Password Anda salah. Silahkan coba lagi!');
                        window.history.go(-1);
                    </script>";
            }
        } else {
            echo "<script>
                    alert('Username atau password Anda salah. Silahkan coba lagi!');
                    window.history.go(-1);
                </script>";
        }
    }
}

messageAlert("Anda seharusnya tidak berada di sini. Harap kembali ke halaman sebelumnya.");
