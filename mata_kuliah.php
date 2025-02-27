<?php
require 'koneksi.php';
if (getLevel() != 'admin') {
    return header('Location: index.php');
}
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'read';

switch ($aksi) {
    case 'read':

?>
        <div class="container">
            <a href="index.php?page=mata-kuliah&aksi=form&proses=create" class="btn btn-primary">Tambah Data</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Mata Kuliah</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Dosen</th>
                        <th>Semester</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $selectQuery = mysqli_query($db, "SELECT A.*, B.nama_dosen FROM mata_kuliah A LEFT JOIN dosen B ON A.dosen_nip = B.nip");
                    $no = 1;
                    while ($values = mysqli_fetch_assoc($selectQuery)) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $values['kode_mk'] ?></td>
                            <td><?= $values['nama_mk'] ?></td>
                            <td><?= $values['sks'] ?></td>
                            <td><?= $values['nama_dosen'] ?></td>
                            <td><?= $values['semester'] ?></td>
                            <td>
                                <a class="btn btn-warning" href="index.php?page=mata-kuliah&aksi=form&proses=update&id=<?= $values['kode_mk']; ?>">Edit</a>
                                <?php if (getLevel() == 'admin'): ?>
                                    <a class="btn btn-danger" href="prosesMataKuliah.php?proses=hapus&id=<?= $values['kode_mk']; ?>" onclick="return confirm('data akan dihapus, anda yakin?')">Hapus</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php
        break;
    case 'form':
        if ($_GET['proses'] == 'update') {
            if (!isset($_GET['id'])) {
                header("Location: index.php?page=mata-kuliah&aksi=form&proses=create");
            }
            $url = 'prosesMataKuliah.php?proses=update';
            $getData = mysqli_query($db, "SELECT * FROM mata_kuliah WHERE kode_mk = '" . $_GET['id'] . "'");
            $data = mysqli_fetch_assoc($getData);
            if ($data == null) {
                header("Location: index.php?page=mata-kuliah&aksi=form&proses=create");
            }
            $judul = "Edit Data " . $data['nama_mk'];
        } else {
            $url = 'prosesMataKuliah.php?proses=create';
            $data = null;
            $judul = "Form";
        }
    ?>
        <h1><?= $judul; ?></h1>
        <form action="<?= $url; ?>" method="POST">
            <?php if ($data != null): ?>
                <input type="hidden" class="form-control" name="kode_mk" value="<?= $data['kode_mk']; ?>">
            <?php endif; ?>
            <div class="mb-3">
                <label for="kode_mk" class="form-label">Kode Mata Kuliah </label>
                <input type="text" class="form-control" id="kode_mk" name="kode_mk" value="<?= ($data != null) ? $data['kode_mk'] : ''; ?>" <?= $data != null ? 'disabled' : 'required'; ?> maxlength="10" size="10">
            </div>
            <div class="mb-3">
                <label for="nama_mk" class="form-label">Nama Mata Kuliah </label>
                <input type="text" class="form-control" id="nama_mk" name="nama_mk" value="<?= ($data != null) ? $data['nama_mk'] : ''; ?>" required maxlength="100">
            </div>
            <div class="mb-3">
                <label for="sks" class="form-label">SKS </label>
                <input type="number" class="form-control" id="sks" name="sks" value="<?= ($data != null) ? $data['sks'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="dosen_nip" class="form-label">Dosen </label>
                <select name="dosen_nip" id="dosen_nip" class="form-control" required>
                    <?php
                    $queryDosen = mysqli_query($db, "SELECT * FROM dosen");
                    while ($dosen = mysqli_fetch_assoc($queryDosen)) {
                    ?>
                        <option value="<?= $dosen["nip"]; ?>" <?= $data != null && $data['dosen_nip'] == $dosen["nip"] ? 'selected' : ''; ?>><?= $dosen["nama_dosen"]; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="semester" class="form-label">Semester </label>
                <input type="number" class="form-control" id="semester" name="semester" value="<?= ($data != null) ? $data['semester'] : ''; ?>" required>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
<?php
        break;
} ?>