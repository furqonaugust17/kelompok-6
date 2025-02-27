<?php
require "koneksi.php";

if (isset($_GET['proses']) && $_GET['proses'] == 'create' && isset($_POST['submit'])) {
    $namaLengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $level = $_POST['level'];

    $query = "INSERT INTO user(nama_lengkap, email, password, level) VALUES('$namaLengkap', '$email', '$password','$level')";
    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data berhasil ditambahkan');window.location='index.php?page=user';</script>";
    } else {
        echo "<script>alert('Data gagal ditambahkan');window.location='index.php?page=user&aksi=form&proses=create';</script>";
    }
}

if (isset($_GET['proses']) && $_GET['proses'] == 'update' && isset($_POST['submit'])) {
    $id = $_POST['id'];

    $namaLengkap = $_POST['nama_lengkap'];
    $email = $_POST['email'];
    $level = $_POST['level'];
    $password = (isset($_POST['password']) && $_POST['password'] != '') ? ",password = '" . md5($_POST['password']) . "'" : '';

    $query = "UPDATE user SET nama_lengkap = '$namaLengkap', email = '$email', level = '$level' " . $password . " WHERE id = $id";
    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data berhasil diupdate');window.location='index.php?page=user';</script>";
    } else {
        echo "<script>alert('Data gagal diupdate');window.location='index.php?page=user&aksi=form&proses=update&id=$id';</script>";
    }
}

if (isset($_GET['proses']) && $_GET['proses'] == 'hapus' && $_GET['id'] != null) {
    if (getLevel() != 'admin') {
        echo "<script>alert('Anda tidak ada hak akses');window.location='index.php?page=user';</script>";
    }
    $hapusQuery = mysqli_query($db, "DELETE FROM user WHERE id = " . $_GET['id']);
    if ($hapusQuery) {
        echo "<script>alert('Data berhasil dihapus');window.location='index.php?page=user';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus');window.location='index.php?page=user&aksi=form&proses=create';</script>";
    }
}
