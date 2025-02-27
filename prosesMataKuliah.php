<?php
require "koneksi.php";
if (getLevel() != 'admin') {
    echo "<script>alert('Anda tidak punya akses');window.location='index.php?page=mata-kuliah';</script>";
}


if (isset($_GET['proses']) && $_GET['proses'] == 'create' && isset($_POST['submit'])) {
    $kode_mk = $_POST['kode_mk'];
    $nama_mk = $_POST['nama_mk'];
    $sks = $_POST['sks'];
    $dosen_nip = $_POST['dosen_nip'];
    $semester = $_POST['semester'];

    $cekKode_mk = mysqli_query($db, "SELECT kode_mk FROM mata_kuliah WHERE kode_mk = '$kode_mk'");
    if (mysqli_num_rows($cekKode_mk) > 0) {
        echo "<script>alert(`Data gagal ditambahkan \nkode mata kuliah sudah terdaftar`);window.location='index.php?page=mata-kuliah&aksi=form&proses=create';</script>";
        exit;
    }
    $query = "INSERT INTO mata_kuliah(kode_mk, nama_mk, sks, dosen_nip, semester) VALUES('$kode_mk', '$nama_mk', '$sks', '$dosen_nip', '$semester')";
    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data berhasil ditambahkan');window.location='index.php?page=mata-kuliah';</script>";
    } else {
        echo "<script>alert('Data gagal ditambahkan');window.location='index.php?page=mata-kuliah&aksi=form&proses=create';</script>";
    }
}

if (isset($_GET['proses']) && $_GET['proses'] == 'update' && isset($_POST['submit'])) {
    $kode_mk = $_POST['kode_mk'];
    $nama_mk = $_POST['nama_mk'];
    $sks = $_POST['sks'];
    $dosen_nip = $_POST['dosen_nip'];
    $semester = $_POST['semester'];

    $query = "UPDATE mata_kuliah SET nama_mk = '$nama_mk', sks = '$sks', dosen_nip = '$dosen_nip', semester = '$semester' WHERE kode_mk = '$kode_mk'";
    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data berhasil diupdate');window.location='index.php?page=mata-kuliah';</script>";
    } else {
        echo "<script>alert('Data gagal diupdate');window.location='index.php?page=mata-kuliah&aksi=form&proses=update&id=$id';</script>";
    }
}

if (isset($_GET['proses']) && $_GET['proses'] == 'hapus' && $_GET['id'] != null) {
    if (getLevel() != 'admin') {
        echo "<script>alert('Anda tidak ada hak akses');window.location='index.php?page=mata-kuliah';</script>";
    }
    $hapusQuery = mysqli_query($db, "DELETE FROM mata_kuliah WHERE kode_mk = '" . $_GET['id'] . "'");
    if ($hapusQuery) {
        echo "<script>alert('Data berhasil dihapus');window.location='index.php?page=mata-kuliah';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus');window.location='index.php?page=mata-kuliah&aksi=form&proses=create';</script>";
    }
}
