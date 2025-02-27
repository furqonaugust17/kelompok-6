<?php
require "koneksi.php";

if (isset($_GET['proses']) && $_GET['proses'] == 'create' && isset($_POST['submit'])) {
    $namaProdi = $_POST['nama_prodi'];
    $jenjang = $_POST['jenjang'];
    $keterangan = $_POST['keterangan'];

    $query = "INSERT INTO prodi(nama_prodi, jenjang, keterangan) VALUES('$namaProdi', '$jenjang', '$keterangan')";
    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data berhasil ditambahkan');window.location='index.php?page=prodi';</script>";
    } else {
        echo "<script>alert('Data gagal ditambahkan');window.location='index.php?page=prodi&aksi=form&proses=create';</script>";
    }
}

if (isset($_GET['proses']) && $_GET['proses'] == 'update' && isset($_POST['submit'])) {
    $id = $_POST['id'];

    $namaProdi = $_POST['nama_prodi'];
    $jenjang = $_POST['jenjang'];
    $keterangan = $_POST['keterangan'];

    $query = "UPDATE prodi SET nama_prodi = '$namaProdi', jenjang = '$jenjang', keterangan = '$keterangan' WHERE id = $id";
    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data berhasil diupdate');window.location='index.php?page=prodi';</script>";
    } else {
        echo "<script>alert('Data gagal diupdate');window.location='index.php?page=prodi&aksi=form&proses=update&id=$id';</script>";
    }
}

if (isset($_GET['proses']) && $_GET['proses'] == 'hapus' && $_GET['id'] != null) {
    if (getLevel() != 'admin') {
        echo "<script>alert('Anda tidak ada hak akses');window.location='index.php?page=prodi';</script>";
    }
    $hapusQuery = mysqli_query($db, "DELETE FROM prodi WHERE id = " . $_GET['id']);
    if ($hapusQuery) {
        echo "<script>alert('Data berhasil dihapus');window.location='index.php?page=prodi';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus');window.location='index.php?page=prodi&aksi=form&proses=create';</script>";
    }
}
