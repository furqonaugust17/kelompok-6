<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "web_trpl2d";

$db = mysqli_connect($host, $user, $password, $database);

if (!$db) {
    die("Koneksi ke database gagal : " . mysqli_connect_error());
} else {
}


function getLevel()
{
    if (!isset($_SESSION)) {
        session_start();
    }
    return $_SESSION['level'];
}
