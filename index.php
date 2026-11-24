<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';
include 'header.php';

// Data statistik dari database
$jumlah_pasien = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM pasien"));
$jumlah_dokter = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM dokter"));
$jumlah_obat   = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM obat"));
$jumlah_rm     = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM rekam_medis"));
?>

<div class="mb-4">
    <h3 class="fw-bold mb-1" style="color: #0f172a;">5. Dashboard Utama</h3>
    <p class="text-secondary small mb-0">Ringkasan statistik data dan catatan rekam medis klinik.</p>
</div>

<!-- 4 Kartu Statistik -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card-stat stat-pasien">
            <div class="stat-title">JUMLAH PASIEN</div>
            <div class="stat-value"><?= $jumlah_pasien; ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-stat stat-dokter">
            <div class="stat-title">JUMLAH DOKTER</div>
            <div class="stat-value"><?= $jumlah_dokter; ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-stat stat-obat">
            <div class="stat-title">JUMLAH OBAT</div>
            <div class="stat-value"><?= $jumlah_obat; ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-stat stat-rm">
            <div class="stat-title">REKAM MEDIS</div>
            <div class="stat-value"><?= $jumlah_rm; ?></div>
        </div>
    </div>
</div>

<!-- Tabel Pengujian Aplikasi -->
<h5 class="fw-bold mb-3" style="color: #0f172a;">G. Tabel Pengujian Aplikasi</h5>
<div class="card card-custom bg-white mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size: 0.93rem;">
                <thead>
                    <tr style="border-bottom: 1px solid #e2e8f0; color: #334155;">
                        <th style="width: 6%; padding: 14px 20px;">No.</th>
                        <th style="width: 25%;">Fitur</th>
                        <th style="width: 50%;">Hasil yang Diharapkan</th>
                        <th style="width: 19%; text-align: right; padding-right: 24px;">Status</th>
                    </tr>
                </thead>
                <tbody style="color: #334155;">
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px 20px;">1</td>
                        <td><strong>Tambah pasien</strong></td>
                        <td>Data pasien tersimpan.</td>
                        <td class="text-end" style="padding-right: 24px;"><span class="badge-berhasil"><i class="bi bi-check"></i> Berhasil</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px 20px;">2</td>
                        <td><strong>Edit dokter</strong></td>
                        <td>Perubahan data tersimpan.</td>
                        <td class="text-end" style="padding-right: 24px;"><span class="badge-berhasil"><i class="bi bi-check"></i> Berhasil</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px 20px;">3</td>
                        <td><strong>Hapus obat</strong></td>
                        <td>Data terhapus sesuai aturan relasi.</td>
                        <td class="text-end" style="padding-right: 24px;"><span class="badge-berhasil"><i class="bi bi-check"></i> Berhasil</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px 20px;">4</td>
                        <td><strong>Tambah rekam medis</strong></td>
                        <td>Data tersimpan dengan FK valid.</td>
                        <td class="text-end" style="padding-right: 24px;"><span class="badge-berhasil"><i class="bi bi-check"></i> Berhasil</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px 20px;">5</td>
                        <td><strong>Dropdown relasi</strong></td>
                        <td>Pilihan pasien, dokter, obat berasal dari database.</td>
                        <td class="text-end" style="padding-right: 24px;"><span class="badge-berhasil"><i class="bi bi-check"></i> Berhasil</span></td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 14px 20px;">6</td>
                        <td><strong>JOIN riwayat</strong></td>
                        <td>Nama pasien, dokter, dan obat tampil benar.</td>
                        <td class="text-end" style="padding-right: 24px;"><span class="badge-berhasil"><i class="bi bi-check"></i> Berhasil</span></td>
                    </tr>
                    <tr>
                        <td style="padding: 14px 20px;">7</td>
                        <td><strong>Dashboard</strong></td>
                        <td>Jumlah data sesuai isi database.</td>
                        <td class="text-end" style="padding-right: 24px;"><span class="badge-berhasil"><i class="bi bi-check"></i> Berhasil</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="text-center text-muted small pb-3">
    © 2026 Sistem Informasi Rekam Medis Klinik
</div>

<?php include 'footer.php'; ?>