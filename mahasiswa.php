<?php
require 'koneksi.php';
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'read';

switch ($aksi) {
    case 'read':

?>
        <div class="container">
            <a href="index.php?page=mahasiswa&aksi=form&proses=create" class="btn btn-primary">Tambah Data</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>NIM</th>
                        <th>Gender</th>
                        <th>Hobi</th>
                        <th>Alamat</th>
                        <th>Prodi</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $selectQuery = mysqli_query($db, "SELECT mahasiswa.*, prodi.nama_prodi FROM mahasiswa LEFT JOIN prodi ON mahasiswa.prodi_id = prodi.id");
                    $no = 1;
                    while ($values = mysqli_fetch_assoc($selectQuery)) {
                    ?>
                        <tr>
                            <td></td>
                            <td><?= $values['nama'] ?></td>
                            <td><?= $values['email'] ?></td>
                            <td><?= $values['nim'] ?></td>
                            <td><?= $values['gender'] ?></td>
                            <td><?= $values['hobi'] ?></td>
                            <td><?= $values['alamat'] ?></td>
                            <td><?= $values['nama_prodi'] == null ? 'prodi belum dipilih' : $values['nama_prodi']; ?></td>
                            <td>
                                <a class="btn btn-warning" href="index.php?page=mahasiswa&aksi=form&proses=update&id=<?= $values['id']; ?>">Edit</a>
                                <?php if (getLevel() == 'admin'): ?>
                                    <a class="btn btn-danger" href="prosesMahasiswa.php?proses=hapus&id=<?= $values['id']; ?>" onclick="return confirm('data akan dihapus, anda yakin?')">Hapus</a>
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
                header("Location: index.php?page=mahasiswa&aksi=form&proses=create");
            }
            $url = 'prosesMahasiswa.php?proses=update';
            $getData = mysqli_query($db, "SELECT * FROM mahasiswa WHERE mahasiswa.id = " . $_GET['id']);
            $data = mysqli_fetch_assoc($getData);
            if ($data == null) {
                header("Location: index.php?page=mahasiswa&aksi=form&proses=create");
            }
            $judul = "Edit Data " . $data['nama'];
            $hobi = explode(', ', $data['hobi']);
        } else {
            $url = 'prosesMahasiswa.php?proses=create';
            $data = null;
            $judul = "Form";
        }
    ?>
        <h1><?= $judul; ?></h1>
        <form action="<?= $url; ?>" method="POST">
            <?php if ($data != null): ?>
                <input type="hidden" class="form-control" name="id" value="<?= $data['id']; ?>">
            <?php endif; ?>
            <div class="mb-3">
                <label for="name" class="form-label">Nama </label>
                <input type="text" class="form-control" id="name" name="name" value="<?= ($data != null) ? $data['nama'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email </label>
                <input type="email" class="form-control" id="email" name="email" value="<?= ($data != null) ? $data['email'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="nim" class="form-label">NIM </label>
                <input type="number" class="form-control" id="nim" name="nim" value="<?= ($data != null) ? $data['nim'] : ''; ?>" <?= $data != null ? 'readonly' : ''; ?>>
            </div>
            <div class="mb-3">
                <label for="prodi" class="form-label">Prodi </label>
                <select name="prodi" id="prodi" class="form-control">
                    <?php
                    $queryProdi = mysqli_query($db, "SELECT * FROM prodi");
                    while ($prodi = mysqli_fetch_assoc($queryProdi)) {
                    ?>
                        <option value="<?= $prodi["id"]; ?>" <?= $data != null && $data['prodi_id'] == $prodi["id"] ? 'selected' : ''; ?>><?= $prodi["nama_prodi"]; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="nim" class="form-label">Gender </label>
                <input class="form-check-input" type="radio" value="L" name="gender" id="laki-laki" <?= $data != null && $data['gender'] == 'Laki-laki' ? 'checked' : ''; ?>>
                <label class="form-check-label" for="laki-laki">
                    Laki-Laki
                </label>
                <input class="form-check-input" type="radio" value="P" name="gender" id="perempuan" <?= $data != null && $data['gender'] == 'Perempuan' ? 'checked' : ''; ?>>
                <label class="form-check-label" for="perempuan">
                    Perempuan
                </label>
            </div>
            <div class="mb-3">
                <label for="nim" class="form-label">Hobi </label>
                <input class="form-check-input" type="checkbox" value="membaca" name="hobi[]" id="hobi1" <?= $data != null && in_array('membaca', $hobi) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="hobi1">
                    Membaca
                </label>
                <input class="form-check-input" type="checkbox" value="olahraga" name="hobi[]" id="hobi2" <?= $data != null && in_array('olahraga', $hobi) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="hobi2">
                    Olahraga
                </label>
                <input class="form-check-input" type="checkbox" value="bermain" name="hobi[]" id="hobi3" <?= $data != null && in_array('bermain', $hobi) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="hobi3">
                    Bermain
                </label>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat </label>
                <textarea name="alamat" id="alamat" class="form-control"><?= ($data != null) ? $data['alamat'] : ''; ?></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
<?php
        break;
} ?>