<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';
include 'header.php';

$pesan = "";
$status = "";
$swal_script = "";

// 1. PROSES SIMPAN / UPDATE DATA
if (isset($_POST['simpan'])) {
    $id_rm           = $_POST['id_rekam'] ?? '';
    $id_pasien       = $_POST['id_pasien'] ?? '';
    $id_dokter       = $_POST['id_dokter'] ?? '';
    $id_obat         = $_POST['id_obat'] ?? '';
    $tanggal_periksa = !empty($_POST['tanggal_periksa']) ? $_POST['tanggal_periksa'] : date('Y-m-d');
    $keluhan         = mysqli_real_escape_string($koneksi, $_POST['keluhan'] ?? '');
    $diagnosa        = mysqli_real_escape_string($koneksi, $_POST['diagnosa'] ?? '');
    $tindakan        = mysqli_real_escape_string($koneksi, $_POST['tindakan'] ?? '');
    $resep           = mysqli_real_escape_string($koneksi, $_POST['resep'] ?? '');

    if (empty($id_rm)) {
        $query = "INSERT INTO rekam_medis (id_pasien, id_dokter, id_obat, tanggal_periksa, keluhan, diagnosa, tindakan, resep) 
                  VALUES ('$id_pasien', '$id_dokter', '$id_obat', '$tanggal_periksa', '$keluhan', '$diagnosa', '$tindakan', '$resep')";
        $teks_sukses = "Data Rekam Medis Berhasil Disimpan!";
    } else {
        $query = "UPDATE rekam_medis SET 
                  id_pasien='$id_pasien', id_dokter='$id_dokter', id_obat='$id_obat', 
                  tanggal_periksa='$tanggal_periksa', keluhan='$keluhan', diagnosa='$diagnosa', 
                  tindakan='$tindakan', resep='$resep' WHERE id_rekam='$id_rm'";
        $teks_sukses = "Data Rekam Medis Berhasil Diperbarui!";
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
                        window.location.href = 'riwayat.php';
                    }
                });
            });
        </script>";
    } else {
        $pesan = "Gagal menyimpan rekam medis: " . mysqli_error($koneksi);
        $status = "danger";
    }
}

// 2. DATA EDIT HANDLERS
$edit_data = [
    'id_rekam'        => '', 
    'id_pasien'       => '', 
    'id_dokter'       => '', 
    'id_obat'         => '', 
    'tanggal_periksa' => date('Y-m-d'), 
    'keluhan'         => '', 
    'diagnosa'        => '', 
    'tindakan'        => '', 
    'resep'           => ''
];

if (isset($_GET['edit'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['edit']);
    $res = mysqli_query($koneksi, "SELECT * FROM rekam_medis WHERE id_rekam='$id'");
    if ($res && mysqli_num_rows($res) > 0) {
        $edit_data = mysqli_fetch_assoc($res);
    }
}
?>

<?= $swal_script; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">Form Input & Edit Rekam Medis</h3>
        <p class="text-muted mb-0">Isi catatan rekam medis pasien sesuai hasil pemeriksaan dokter.</p>
    </div>
</div>

<?php if ($pesan): ?>
    <div class="alert alert-<?= $status; ?> alert-dismissible fade show" role="alert">
        <?= $pesan; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-3 mb-4">
    <div class="card-header bg-dark text-white fw-bold py-3">
        <?= !empty($edit_data['id_rekam']) ? 'Edit Catatan Rekam Medis' : 'Tambah Catatan Rekam Medis'; ?>
    </div>
    <div class="card-body p-4 bg-white">
        <form method="POST" action="rekam_medis.php">
            <input type="hidden" name="id_rekam" value="<?= htmlspecialchars($edit_data['id_rekam']); ?>">
            
            <div class="row">
                <!-- Dropdown Pasien -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Pasien</label>
                    <select name="id_pasien" class="form-select" required>
                        <option value="">-- Pilih Pasien --</option>
                        <?php
                        $pasien_q = mysqli_query($koneksi, "SELECT * FROM pasien");
                        if ($pasien_q) {
                            while ($p = mysqli_fetch_assoc($pasien_q)) {
                                $selected = ($p['id_pasien'] == $edit_data['id_pasien']) ? 'selected' : '';
                                echo "<option value='{$p['id_pasien']}' $selected>{$p['nama_pasien']}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- Dropdown Dokter -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Dokter</label>
                    <select name="id_dokter" class="form-select" required>
                        <option value="">-- Pilih Dokter --</option>
                        <?php
                        $dokter_q = mysqli_query($koneksi, "SELECT * FROM dokter");
                        if ($dokter_q) {
                            while ($d = mysqli_fetch_assoc($dokter_q)) {
                                $selected = ($d['id_dokter'] == $edit_data['id_dokter']) ? 'selected' : '';
                                echo "<option value='{$d['id_dokter']}' $selected>{$d['nama_dokter']} ({$d['spesialisasi']})</option>";
                            }
                        }
                        ?>
                    </select>
                </div>

                <!-- Dropdown Obat -->
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-semibold">Obat</label>
                    <select name="id_obat" class="form-select" required>
                        <option value="">-- Pilih Obat --</option>
                        <?php
                        $obat_q = mysqli_query($koneksi, "SELECT * FROM obat");
                        if ($obat_q) {
                            while ($o = mysqli_fetch_assoc($obat_q)) {
                                $selected = ($o['id_obat'] == $edit_data['id_obat']) ? 'selected' : '';
                                echo "<option value='{$o['id_obat']}' $selected>{$o['nama_obat']} (Stok: {$o['stok']})</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Tanggal Pemeriksaan</label>
                    <input type="date" name="tanggal_periksa" class="form-control" value="<?= htmlspecialchars($edit_data['tanggal_periksa']); ?>" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Keluhan</label>
                    <input type="text" name="keluhan" class="form-control" value="<?= htmlspecialchars($edit_data['keluhan']); ?>" required placeholder="Contoh: Demam, Sakit Kepala">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Diagnosa</label>
                    <input type="text" name="diagnosa" class="form-control" value="<?= htmlspecialchars($edit_data['diagnosa']); ?>" required placeholder="Contoh: Flu Febris">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-semibold">Tindakan</label>
                    <input type="text" name="tindakan" class="form-control" value="<?= htmlspecialchars($edit_data['tindakan']); ?>" required placeholder="Contoh: Pemeriksaan fisik">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Resep Obat</label>
                <textarea name="resep" class="form-control" rows="3" required placeholder="Contoh: Paracetamol 3x1 tablet sesudah makan"><?= htmlspecialchars($edit_data['resep']); ?></textarea>
            </div>

            <button type="submit" name="simpan" class="btn btn-success fw-bold px-4">Simpan Rekam Medis</button>
            <?php if (!empty($edit_data['id_rekam'])): ?>
                <a href="rekam_medis.php" class="btn btn-secondary">Batal Edit</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>