<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';
include 'header.php';
?>

<!-- Pop-up Notifikasi Sukses Hapus di Tengah Layar -->
<?php if (isset($_GET['hapus'])): 
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['hapus']);
    $del = mysqli_query($koneksi, "DELETE FROM rekam_medis WHERE id_rekam='$id_hapus'");
    if ($del): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil Dihapus!',
                    text: 'Data riwayat pemeriksaan telah dihapus.',
                    icon: 'success',
                    confirmButtonColor: '#198754',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'riwayat.php';
                    }
                });
            });
        </script>
    <?php endif; ?>
<?php endif; ?>

<?php
$query = "SELECT 
            rm.id_rekam,
            rm.tanggal_periksa,
            p.nama_pasien,
            d.nama_dokter,
            d.spesialisasi,
            o.nama_obat,
            rm.keluhan,
            rm.diagnosa,
            rm.tindakan,
            rm.resep
          FROM rekam_medis rm
          LEFT JOIN pasien p ON rm.id_pasien = p.id_pasien
          LEFT JOIN dokter d ON rm.id_dokter = d.id_dokter
          LEFT JOIN obat o ON rm.id_obat = o.id_obat
          ORDER BY rm.id_rekam DESC";

$result = mysqli_query($koneksi, $query);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1">4. Riwayat Pemeriksaan Pasien</h3>
        <p class="text-muted mb-0">Daftar rekam medis pasien yang terhubung dari database.</p>
    </div>
    <a href="rekam_medis.php" class="btn btn-primary fw-semibold">
        <i class="bi bi-plus-lg me-1"></i> Tambah Rekam Medis
    </a>
</div>

<div class="table-responsive table-custom bg-white shadow-sm mb-4">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 12%">Tanggal</th>
                <th style="width: 15%">Nama Pasien</th>
                <th style="width: 15%">Nama Dokter</th>
                <th style="width: 15%">Keluhan</th>
                <th style="width: 12%">Diagnosa</th>
                <th style="width: 11%">Tindakan</th>
                <th style="width: 15%">Resep & Obat</th>
                <th style="width: 10%" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td>
                        <span class="badge bg-light text-dark border">
                            <?= date('d/m/Y', strtotime($row['tanggal_periksa'])); ?>
                        </span>
                    </td>
                    <td><strong class="text-dark"><?= htmlspecialchars($row['nama_pasien'] ?? '-'); ?></strong></td>
                    <td>
                        <?= htmlspecialchars($row['nama_dokter'] ?? '-'); ?>
                        <?php if (!empty($row['spesialisasi'])): ?>
                            <br><small class="text-muted">(<?= htmlspecialchars($row['spesialisasi']); ?>)</small>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['keluhan']); ?></td>
                    <td><span class="badge bg-info text-dark"><?= htmlspecialchars($row['diagnosa']); ?></span></td>
                    <td><?= htmlspecialchars($row['tindakan']); ?></td>
                    <td>
                        <strong class="text-primary"><?= htmlspecialchars($row['nama_obat'] ?? ''); ?></strong><br>
                        <small class="text-muted"><?= htmlspecialchars($row['resep']); ?></small>
                    </td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="rekam_medis.php?edit=<?= $row['id_rekam']; ?>" class="btn btn-outline-warning" title="Edit">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <button onclick="konfirmasiHapus('<?= $row['id_rekam']; ?>')" class="btn btn-outline-danger" title="Hapus">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            <?php 
                }
            } else {
                echo "<tr><td colspan='9' class='text-center py-4 text-muted'>Belum ada riwayat pemeriksaan pasien. Silakan isi form rekam medis terlebih dahulu.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script>
function konfirmasiHapus(id) {
    Swal.fire({
        title: 'Apakah kamu yakin?',
        text: "Data riwayat pemeriksaan ini akan dihapus secara permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'riwayat.php?hapus=' + id;
        }
    });
}
</script>

<?php include 'footer.php'; ?>