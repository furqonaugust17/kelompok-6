<?php
require "koneksi.php";

if (isset($_GET['proses']) && $_GET['proses'] == 'create' && isset($_POST['submit'])) {
    $nama = $_POST['name'];
    $email = $_POST['email'];
    $nim = $_POST['nim'];
    $gender = $_POST['gender'];
    $hobi = implode(", ", $_POST['hobi']);
    $alamat = $_POST['alamat'];
    $prodi = $_POST['prodi'];

    $cekNim = mysqli_query($db, "SELECT nim FROM mahasiswa WHERE nim = '$nim'");
    if (mysqli_num_rows($cekNim) > 0) {
        echo "<script>alert(`Data gagal ditambahkan \nNIM sudah terdaftar`);window.location='index.php?page=mahasiswa&aksi=form&proses=create';</script>";
        exit;
    }

    $query = "INSERT INTO mahasiswa(nama, email, nim, gender, hobi, alamat, prodi_id) VALUES('$nama', '$email', '$nim', '$gender', '$hobi', '$alamat', $prodi)";
    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data berhasil ditambahkan');window.location='index.php?page=mahasiswa';</script>";
    } else {
        echo "<script>alert('Data gagal ditambahkan');window.location='index.php?page=mahasiswa&aksi=form&proses=create';</script>";
    }
}

if (isset($_GET['proses']) && $_GET['proses'] == 'update' && isset($_POST['submit'])) {
    $id = $_POST['id'];

    if (!isset($_POST['gender'])) {
        echo "<script>alert('Data gagal diupdate');window.location='index.php?page=mahasiswa&aksi=form&proses=update&id=$id';</script>";
    }

    $nama = $_POST['name'];
    $email = $_POST['email'];
    $nim = $_POST['nim'];
    $gender = $_POST['gender'];
    $hobi = isset($_POST['hobi']) ? implode(", ", $_POST['hobi']) : '';
    $alamat = $_POST['alamat'];
    $prodi = $_POST['prodi'];

    $query = "UPDATE mahasiswa SET nama = '$nama', email = '$email', nim = '$nim', gender = '$gender', hobi = '$hobi', alamat = '$alamat', prodi_id = $prodi WHERE id = $id";
    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data berhasil diupdate');window.location='index.php?page=mahasiswa';</script>";
    } else {
        echo "<script>alert('Data gagal diupdate');window.location='index.php?page=mahasiswa&aksi=form&proses=update&id=$id';</script>";
    }
}

if (isset($_GET['proses']) && $_GET['proses'] == 'hapus' && $_GET['id'] != null) {
    if (getLevel() != 'admin') {
        echo "<script>alert('Anda tidak ada hak akses');window.location='index.php?page=mahasiswa';</script>";
    }
    $hapusQuery = mysqli_query($db, "DELETE FROM mahasiswa WHERE id = " . $_GET['id']);
    if ($hapusQuery) {
        echo "<script>alert('Data berhasil dihapus');window.location='index.php?page=mahasiswa';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus');window.location='index.php?page=mahasiswa&aksi=form&proses=create';</script>";
    }
}
