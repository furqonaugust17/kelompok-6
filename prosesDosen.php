<?php
require "koneksi.php";

if (isset($_GET['proses']) && $_GET['proses'] == 'create' && isset($_POST['submit'])) {


    $nip = $_POST['nip'];
    $namaDosen = $_POST['nama_dosen'];
    $prodi = $_POST['prodi_id'];

    $gambar = upload();
    if (!$gambar) {
        echo "<script>alert('Data gagal ditambahkan');window.location='index.php?page=dosen&aksi=form&proses=create';</script>";
        return;
    }
    $query = "INSERT INTO dosen(nip, nama_dosen, prodi_id, foto) VALUES('$nip', '$namaDosen', '$prodi', '$gambar')";
    if (mysqli_query($db, $query)) {
        echo "<script>alert('Data berhasil ditambahkan');window.location='index.php?page=dosen';</script>";
    } else {
        unlink("img/" . $gambar);
        echo "<script>alert('Data gagal ditambahkan');window.location='index.php?page=dosen&aksi=form&proses=create';</script>";
    }
}

if (isset($_GET['proses']) && $_GET['proses'] == 'update' && isset($_POST['submit'])) {
    $id = $_POST['nip'];

    $namaDosen = $_POST['nama_dosen'];
    $prodi = $_POST['prodi_id'];
    $getData = mysqli_query($db, "SELECT * FROM dosen WHERE nip = $id");
    $data = mysqli_fetch_array($getData);

    $query = "UPDATE dosen SET nama_dosen = '$namaDosen', prodi_id = '$prodi'";
    if ($_FILES['foto']['size'] != 0) {
        $gambar = upload();
        if (!$gambar) {
            echo "<script>alert('Data gagal ditambahkan');window.location='index.php?page=dosen&aksi=form&proses=create';</script>";
            return;
        }
        $query .= " ,foto = '$gambar'";
    }

    $query .= " WHERE nip = $id";
    if (mysqli_query($db, $query)) {
        if ($_FILES['foto']['size'] != 0) {
            unlink("img/" . $data['foto']);
        }
        echo "<script>alert('Data berhasil diupdate');window.location='index.php?page=dosen';</script>";
    } else {
        echo "<script>alert('Data gagal diupdate');window.location='index.php?page=dosen&aksi=form&proses=update&id=$id';</script>";
    }
}

if (isset($_GET['proses']) && $_GET['proses'] == 'hapus' && $_GET['id'] != null) {
    if (getLevel() != 'admin') {
        echo "<script>alert('Anda tidak ada hak akses');window.location='index.php?page=dosen';</script>";
    }
    $query = mysqli_query($db, "SELECT * FROM dosen WHERE nip = " . $_GET['id']);
    $data = mysqli_fetch_array($query);
    $hapusQuery = mysqli_query($db, "DELETE FROM dosen WHERE nip = " . $_GET['id']);
    if ($hapusQuery) {
        unlink("img/" . $data['foto']);
        echo "<script>alert('Data berhasil dihapus');window.location='index.php?page=dosen';</script>";
    } else {
        echo "<script>alert('Data gagal dihapus');window.location='index.php?page=dosen&aksi=form&proses=create';</script>";
    }
}


function upload()
{
    $namaFile = $_FILES['foto']['name'];
    $ukuranFile = $_FILES['foto']['size'];
    $error = $_FILES['foto']['error'];
    $tmpName = $_FILES['foto']['tmp_name'];

    // cek apakah tidak ada gambar yang diupload
    if ($error === 4) {
        echo "<script>
            alert('pilih gambar terlebih dahulu')
            </script>";
        return false;
    }

    // cek apakah yang diupload adalah gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));

    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>
        alert('yang anda upload bukan gambar')
        </script>";
        return false;
    }

    // cek jika ukuran terlalu besar
    // if ($ukuranFile > 1000000) {
    //     echo "<script>
    //     alert('ukuran gambar terlalu besar')
    //     </script>";
    //     return false;
    // }

    // lolos pengecekan, gambar lolos di upload
    //generate nama gambar
    $namaFileBaru = uniqid();
    $namaFileBaru .= '.';
    $namaFileBaru .= $ekstensiGambar;


    move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

    return $namaFileBaru;
}
