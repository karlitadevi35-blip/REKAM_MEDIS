<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';
include 'header.php';

$pesan = "";
$status = "";
$swal_script = "";

// 1. PROSES SIMPAN / UPDATE DATA PASIEN
if (isset($_POST['simpan'])) {
    $id_pasien     = $_POST['id_pasien'] ?? '';
    $no_rm         = mysqli_real_escape_string($koneksi, $_POST['no_rm'] ?? '');
    $nama_pasien   = mysqli_real_escape_string($koneksi, $_POST['nama_pasien'] ?? '');
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin'] ?? '');
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir'] ?? '');
    $alamat        = mysqli_real_escape_string($koneksi, $_POST['alamat'] ?? '');

    if (empty($id_pasien)) {
        $query = "INSERT INTO pasien (no_rm, nama_pasien, jenis_kelamin, tanggal_lahir, alamat) 
                  VALUES ('$no_rm', '$nama_pasien', '$jenis_kelamin', '$tanggal_lahir', '$alamat')";
        $teks_sukses = "Data Pasien Berhasil Disimpan!";
    } else {
        $query = "UPDATE pasien SET 
                  no_rm='$no_rm', nama_pasien='$nama_pasien', jenis_kelamin='$jenis_kelamin', 
                  tanggal_lahir='$tanggal_lahir', alamat='$alamat' 
                  WHERE id_pasien='$id_pasien'";
        $teks_sukses = "Data Pasien Berhasil Diperbarui!";
    }

    if (mysqli_query($koneksi, $query)) {
        $swal_script = "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: '$teks_sukses',
                    icon: 'success',
                    confirmButtonColor: '#198754',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'pasien.php';
                    }
                });
            });
        </script>";
    } else {
        $pesan = "Gagal menyimpan data pasien: " . mysqli_error($koneksi);
        $status = "danger";
    }
}

// 2. PROSES HAPUS DATA PASIEN
if (isset($_GET['hapus'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $del = mysqli_query($koneksi, "DELETE FROM pasien WHERE id_pasien='$id_hapus'");
    if ($del) {
        $swal_script = "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil Dihapus!',
                    text: 'Data pasien telah dihapus dari database.',
                    icon: 'success',
                    confirmButtonColor: '#198754',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'pasien.php';
                    }
                });
            });
        </script>";
    }
}

// 3. DATA EDIT HANDLERS
$edit_data = [
    'id_pasien'     => '', 
    'no_rm'         => '', 
    'nama_pasien'   => '', 
    'jenis_kelamin' => '', 
    'tanggal_lahir' => '', 
    'alamat'        => ''
];

if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['edit']);
    $res = mysqli_query($koneksi, "SELECT * FROM pasien WHERE id_pasien='$id'");
    if ($res && mysqli_num_rows($res) > 0) {
        $edit_data = mysqli_fetch_assoc($res);
    }
}

// Fetch data pasien
$query_pasien = "SELECT * FROM pasien ORDER BY id_pasien DESC";
$result_pasien = mysqli_query($koneksi, $query_pasien);
?>

<?= $swal_script; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1" style="color: #0f172a;">Kelola Data Pasien</h3>
        <p class="text-secondary small mb-0">Kelola informasi identitas, jenis kelamin, dan riwayat pendaftaran pasien.</p>
    </div>
</div>

<?php if ($pesan): ?>
    <div class="alert alert-<?= $status; ?> alert-dismissible fade show" role="alert">
        <?= $pesan; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Form Input / Edit Pasien -->
<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-dark text-white fw-bold py-3">
        <?= !empty($edit_data['id_pasien']) ? 'Edit Data Pasien' : 'Tambah Pasien Baru'; ?>
    </div>
    <div class="card-body p-4 bg-white">
        <form method="POST" action="pasien.php">
            <input type="hidden" name="id_pasien" value="<?= htmlspecialchars($edit_data['id_pasien']); ?>">
            
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">No. RM</label>
                    <input type="text" name="no_rm" class="form-control" value="<?= htmlspecialchars($edit_data['no_rm']); ?>" required placeholder="Contoh: RM-001">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Nama Pasien</label>
                    <input type="text" name="nama_pasien" class="form-control" value="<?= htmlspecialchars($edit_data['nama_pasien']); ?>" required placeholder="Contoh: Budi Santoso">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="L" <?= ($edit_data['jenis_kelamin'] == 'L') ? 'selected' : ''; ?>>Laki-laki (L)</option>
                        <option value="P" <?= ($edit_data['jenis_kelamin'] == 'P') ? 'selected' : ''; ?>>Perempuan (P)</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="<?= htmlspecialchars($edit_data['tanggal_lahir']); ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2" required placeholder="Alamat lengkap pasien..."><?= htmlspecialchars($edit_data['alamat']); ?></textarea>
            </div>

            <button type="submit" name="simpan" class="btn btn-success fw-bold px-4">Simpan Pasien</button>
            <?php if (!empty($edit_data['id_pasien'])): ?>
                <a href="pasien.php" class="btn btn-secondary">Batal Edit</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Tabel Daftar Pasien -->
<div class="card card-custom bg-white shadow-sm mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%; padding-left: 15px;">No</th>
                        <th style="width: 12%">No. RM</th>
                        <th style="width: 20%">Nama Pasien</th>
                        <th style="width: 15%">Jenis Kelamin</th>
                        <th style="width: 15%">Tgl Lahir</th>
                        <th style="width: 23%">Alamat</th>
                        <th style="width: 10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    if ($result_pasien && mysqli_num_rows($result_pasien) > 0) {
                        while ($row = mysqli_fetch_assoc($result_pasien)) {
                    ?>
                        <tr>
                            <td style="padding-left: 15px;"><?= $no++; ?></td>
                            <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($row['no_rm']); ?></span></td>
                            <td><strong class="text-dark"><?= htmlspecialchars($row['nama_pasien']); ?></strong></td>
                            <td>
                                <?php if ($row['jenis_kelamin'] == 'L'): ?>
                                    <span class="badge bg-primary"><i class="bi bi-gender-male me-1"></i>Laki-laki</span>
                                <?php else: ?>
                                    <span class="badge bg-danger"><i class="bi bi-gender-female me-1"></i>Perempuan</span>
                                <?php endif; ?>
                            </td>
                            <td><?= !empty($row['tanggal_lahir']) ? date('d/m/Y', strtotime($row['tanggal_lahir'])) : '-'; ?></td>
                            <td><?= htmlspecialchars($row['alamat']); ?></td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="pasien.php?edit=<?= $row['id_pasien']; ?>" class="btn btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                                    <button onclick="konfirmasiHapusPasien('<?= $row['id_pasien']; ?>')" class="btn btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php 
                        }
                    } else {
                        echo "<tr><td colspan='7' class='text-center py-4 text-muted'>Belum ada data pasien terdaftar.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function konfirmasiHapusPasien(id) {
    Swal.fire({
        title: 'Apakah kamu yakin?',
        text: "Data pasien ini akan dihapus dari sistem!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'pasien.php?hapus=' + id;
        }
    });
}
</script>

<?php include 'footer.php'; ?>