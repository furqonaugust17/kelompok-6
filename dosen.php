<?php
require 'koneksi.php';
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'read';

switch ($aksi) {
    case 'read':

?>
        <div class="container">
            <a href="index.php?page=dosen&aksi=form&proses=create" class="btn btn-primary">Tambah Data</a>
            <table class="table">
                <thead>
                    <tr>
                        <th class ="text-center">No</th>
                        <th class ="text-start">NIP</th>
                        <th class ="text-start">Nama Dosen</th>
                        <th class ="text-start">Prodi</th>
                        <th>Foto</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $selectQuery = mysqli_query($db, "SELECT A.*, B.nama_prodi FROM dosen A LEFT JOIN prodi B ON A.prodi_id = B.id");
                    $no = 1;
                    while ($values = mysqli_fetch_assoc($selectQuery)) {
                    ?>
                        <tr>
                            <td class ="text-center"><?= $no++ ?></td>
                            <td class ="text-start"><?= $values['nip'] ?></td>
                            <td><?= $values['nama_dosen'] ?></td>
                            <td><?= $values['nama_prodi'] ?></td>
                            <td class ="text-center"><img src="img/<?= $values['foto'] ?>" height="100" width="200" alt="" style="object-fit: contain;"></td>
                            <td>
                                <a class="btn btn-warning" href="index.php?page=dosen&aksi=form&proses=update&id=<?= $values['nip']; ?>">Edit</a>
                                <?php if (getLevel() == 'admin'): ?>
                                    <a class="btn btn-danger" href="prosesDosen.php?proses=hapus&id=<?= $values['nip']; ?>" onclick="return confirm('data akan dihapus, anda yakin?')">Hapus</a>
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
                header("Location: index.php?page=dosen&aksi=form&proses=create");
            }
            $url = 'prosesDosen.php?proses=update';
            $getData = mysqli_query($db, "SELECT * FROM dosen WHERE nip = " . $_GET['id']);
            $data = mysqli_fetch_assoc($getData);
            if ($data == null) {
                header("Location: index.php?page=dosen&aksi=form&proses=create");
            }
            $judul = "Edit Data " . $data['nama_dosen'];
        } else {
            $url = 'prosesDosen.php?proses=create';
            $data = null;
            $judul = "Form";
        }
    ?>
        <h1><?= $judul; ?></h1>
        <form action="<?= $url; ?>" method="POST" enctype="multipart/form-data">
            <?php if ($data != null): ?>
                <input type="hidden" class="form-control" name="nip" value="<?= $data['nip']; ?>">
            <?php endif; ?>
            <div class="mb-3">
                <label for="nip" class="form-label">NIP </label>
                <input type="number" class="form-control" id="nip" name="nip" value="<?= ($data != null) ? $data['nip'] : ''; ?>" <?= $data != null ? 'disabled' : 'required'; ?>>
            </div>
            <div class="mb-3">
                <label for="nama_dosen" class="form-label">Nama Dosen </label>
                <input type="text" class="form-control" id="nama_dosen" name="nama_dosen" value="<?= ($data != null) ? $data['nama_dosen'] : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="prodi" class="form-label">Prodi </label>
                <select name="prodi_id" id="prodi" class="form-control">
                    <?php
                    $queryProdi = mysqli_query($db, "SELECT * FROM prodi");
                    while ($prodi = mysqli_fetch_assoc($queryProdi)) {
                    ?>
                        <option value="<?= $prodi["id"]; ?>" <?= $data != null && $data['prodi_id'] == $prodi["id"] ? 'selected' : ''; ?>><?= $prodi["nama_prodi"]; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="foto" class="form-label">Foto </label>
                <input type="file" class="form-control" id="foto" name="foto" accept="image/png, image/jpeg">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
<?php
        break;
} ?>