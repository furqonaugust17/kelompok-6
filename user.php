<?php
require 'koneksi.php';
$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : 'read';

switch ($aksi) {
    case 'read':

?>
        <div class="container">
            <a href="index.php?page=user&aksi=form&proses=create" class="btn btn-primary">Tambah Data</a>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th>Level</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $selectQuery = mysqli_query($db, "SELECT * FROM user");
                    $no = 1;
                    while ($values = mysqli_fetch_assoc($selectQuery)) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $values['nama_lengkap'] ?></td>
                            <td><?= $values['email'] ?></td>
                            <td><?= $values['level'] ?></td>
                            <td>
                                <?php if (getLevel() == 'admin'): ?>
                                    <a class="btn btn-warning" href="index.php?page=user&aksi=form&proses=update&id=<?= $values['id']; ?>">Edit</a>
                                    <a class="btn btn-danger" href="prosesUser.php?proses=hapus&id=<?= $values['id']; ?>" onclick="return confirm('data akan dihapus, anda yakin?')">Hapus</a>
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
                header("Location: index.php?page=user&aksi=form&proses=create");
            }
            $url = 'prosesUser.php?proses=update';
            $getData = mysqli_query($db, "SELECT * FROM user WHERE id = " . $_GET['id']);
            $data = mysqli_fetch_assoc($getData);
            if ($data == null) {
                header("Location: index.php?page=user&aksi=form&proses=create");
            }
            $judul = "Edit Data " . $data['nama_lengkap'];
        } else {
            $url = 'prosesUser.php?proses=create';
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
                <label for="nama_lengkap" class="form-label">Nama Lengkap </label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= ($data != null) ? $data['nama_lengkap'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email </label>
                <input type="email" class="form-control" id="email" name="email" value="<?= ($data != null) ? $data['email'] : ''; ?>">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">password </label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <div class="mb-3">
                <label for="level" class="form-label">Level </label>
                <select name="level" id="" class="form-control">
                    <option value="teknisi">teknisi</option>
                    <option value="operator">operator</option>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
        </form>
<?php
        break;
} ?>