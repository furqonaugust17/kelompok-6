<?php
require 'koneksi.php';
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'read';

switch ($aksi) {
    case 'read':

?>
        <div class="container">
            <a href="index.php?page=prodi&aksi=form&proses=create" class="btn btn-primary">Tambah Data</a>
            <table class="table">
                <thead>
                    <tr>
                        <th class ="text-center">No</th>
                        <th>Nama Prodi</th>
                        <th>Jenjang</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $selectQuery = mysqli_query($db, "SELECT * FROM prodi");
                    $no = 1;
                    while ($values = mysqli_fetch_assoc($selectQuery)) {
                    ?>
                        <tr>
                            <td class ="text-center"><?= $no++ ?></td>
                            <td><?= $values['nama_prodi'] ?></td>
                            <td><?= $values['jenjang'] ?></td>
                            <td><?= $values['keterangan'] ?></td>
                            <td>
                                <a class="btn btn-warning" href="index.php?page=prodi&aksi=form&proses=update&id=<?= $values['id']; ?>">Edit</a>
                                <?php if (getLevel() == 'admin'): ?>
                                    <a class="btn btn-danger" href="prosesprodi.php?proses=hapus&id=<?= $values['id']; ?>" onclick="return confirm('data akan dihapus, anda yakin?')">Hapus</a>
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
                header("Location: index.php?page=prodi&aksi=form&proses=create");
            }
            $url = 'prosesProdi.php?proses=update';
            $getData = mysqli_query($db, "SELECT * FROM prodi WHERE id = " . $_GET['id']);
            $data = mysqli_fetch_assoc($getData);
            if ($data == null) {
                header("Location: index.php?page=prodi&aksi=form&proses=create");
            }
            $judul = "Edit Data " . $data['nama_prodi'];
        } else {
            $url = 'prosesProdi.php?proses=create';
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
                <label for="nama_prodi" class="form-label">Nama Prodi </label>
                <input type="text" class="form-control" id="nama_prodi" name="nama_prodi" value="<?= ($data != null) ? $data['nama_prodi'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="jenjang" class="form-label">Jenjang </label>
                <select name="jenjang" id="jenjang" class="form-control">
                    <option value="D2" <?= $data != null && $data['jenjang'] == 'D2' ? 'selected' : ''; ?>>D2</option>
                    <option value="D3" <?= $data != null && $data['jenjang'] == 'D3' ? 'selected' : ''; ?>>D3</option>
                    <option value="D4" <?= $data != null && $data['jenjang'] == 'D4' ? 'selected' : ''; ?>>D4</option>
                    <option value="S1" <?= $data != null && $data['jenjang'] == 'S1' ? 'selected' : ''; ?>>S1</option>
                    <option value="S2" <?= $data != null && $data['jenjang'] == 'S2' ? 'selected' : ''; ?>>S2</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan </label>
                <textarea name="keterangan" id="keterangan" class="form-control"><?= ($data != null) ? $data['keterangan'] : ''; ?></textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
<?php
        break;
} ?>