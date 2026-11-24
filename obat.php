<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'koneksi.php';

// Proses Simpan & Edit Data Obat
if (isset($_POST['simpan'])) {
    $id_obat   = $_POST['id_obat'];
    $nama_obat = $_POST['nama_obat'];
    $satuan    = $_POST['satuan'];
    $stok      = $_POST['stok'];

    if ($id_obat == '') {
        mysqli_query($koneksi, "INSERT INTO obat (nama_obat, satuan, stok) VALUES ('$nama_obat', '$satuan', '$stok')");
        header("Location: obat.php?status=simpan_sukses");
    } else {
        mysqli_query($koneksi, "UPDATE obat SET nama_obat='$nama_obat', satuan='$satuan', stok='$stok' WHERE id_obat='$id_obat'");
        header("Location: obat.php?status=edit_sukses");
    }
    exit;
}

// Proses Hapus Data Obat
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    try {
        mysqli_query($koneksi, "DELETE FROM obat WHERE id_obat='$id'");
        header("Location: obat.php?status=hapus_sukses");
    } catch (mysqli_sql_exception $e) {
        header("Location: obat.php?status=hapus_gagal");
    }
    exit;
}

// Ambil Data jika sedang mode Edit
$edit = ['id_obat' => '', 'nama_obat' => '', 'satuan' => '', 'stok' => ''];
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $edit = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM obat WHERE id_obat='$id'"));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Data Obat - Klinik HealthCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        /* Sidebar Gelap Klinik HealthCare */
        .sidebar {
            width: 240px;
            min-height: 100vh;
            background-color: #0f172a;
            color: #94a3b8;
            position: fixed;
            top: 0; left: 0;
            padding: 24px 16px;
        }
        .sidebar-brand {
            color: #38bdf8;
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 32px;
            padding-left: 8px;
            line-height: 1.2;
        }
        .menu-label {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #475569;
            margin-bottom: 12px;
            padding-left: 12px;
        }
        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #cbd5e1;
            text-decoration: none;
            padding: 11px 14px;
            border-radius: 8px;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 500;
            transition: 0.2s;
        }
        .nav-link-custom:hover { background-color: #1e293b; color: #fff; }
        .nav-link-custom.active {
            background-color: #1e293b;
            color: #38bdf8;
            border: 1px solid #334155;
        }

        /* Konten Utama */
        .main-wrapper { margin-left: 240px; padding: 32px 40px; }
        .page-heading { font-size: 26px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
        .page-sub { color: #64748b; font-size: 14px; margin-bottom: 24px; }

        /* Card Form Atas */
        .card-custom { border: none; border-radius: 10px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 32px; background: #fff; }
        .card-header-dark { background-color: #1e293b; color: #fff; font-weight: 600; padding: 14px 24px; font-size: 15px; }
        .card-body-custom { padding: 24px; }
        .form-label { font-weight: 600; font-size: 13.5px; color: #334155; }
        .btn-hijau { background-color: #15803d; color: #fff; font-weight: 600; padding: 9px 22px; border-radius: 6px; border: none; }
        .btn-hijau:hover { background-color: #166534; color: #fff; }

        /* Tabel Bawah */
        .table-custom th { color: #0f172a; font-weight: 700; font-size: 14px; border-bottom: 2px solid #e2e8f0; padding: 14px 12px; background: transparent; }
        .table-custom td { padding: 14px 12px; vertical-align: middle; font-size: 14px; border-bottom: 1px solid #e2e8f0; }
        .badge-id { border: 1px solid #cbd5e1; background: #f8fafc; color: #334155; padding: 3px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; }
    </style>
</head>
<body>

<!-- SIDEBAR KIRI -->
<div class="sidebar">
    <div class="sidebar-brand">
        <i class="fa-solid fa-hospital fa-lg"></i>
        <div>Klinik<br>HealthCare</div>
    </div>
    <div class="menu-label">MENU UTAMA</div>
    <a href="index.php" class="nav-link-custom"><i class="fa-solid fa-gauge-high"></i> Dashboard</a>
    <a href="pasien.php" class="nav-link-custom"><i class="fa-solid fa-users"></i> Pasien</a>
    <a href="dokter.php" class="nav-link-custom"><i class="fa-solid fa-id-card-clip"></i> Dokter</a>
    <a href="obat.php" class="nav-link-custom active"><i class="fa-solid fa-capsules"></i> Obat</a>
    <a href="rekam_medis.php" class="nav-link-custom"><i class="fa-solid fa-file-medical"></i> Form Rekam Medis</a>
    <a href="riwayat.php" class="nav-link-custom"><i class="fa-solid fa-clipboard-list"></i> Riwayat Pemeriksaan</a>
</div>

<!-- KONTEN KANAN -->
<div class="main-wrapper">
    <h1 class="page-heading">Kelola Data Obat</h1>
    <p class="page-sub">Kelola daftar nama obat, satuan kemasan, dan ketersediaan stok obat.</p>

    <!-- CARD FORM TAMBAH / EDIT OBAT (DI ATAS) -->
    <div class="card-custom">
        <div class="card-header-dark">
            <?= $edit['id_obat'] ? 'Edit Data Obat' : 'Tambah Obat Baru' ?>
        </div>
        <div class="card-body-custom">
            <form method="POST" action="">
                <input type="hidden" name="id_obat" value="<?= $edit['id_obat'] ?>">
                <div class="row g-3 mb-3">
                    <div class="col-md-5">
                        <label class="form-label">Nama Obat</label>
                        <input type="text" name="nama_obat" class="form-control" placeholder="Contoh: Paracetamol 500mg" value="<?= $edit['nama_obat'] ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="satuan" class="form-control" placeholder="Contoh: Tablet / Kapsul / Sirup" value="<?= $edit['satuan'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Stok</label>
                        <input type="number" name="stok" class="form-control" placeholder="0" value="<?= $edit['stok'] ?>" required>
                    </div>
                </div>
                <button type="submit" name="simpan" class="btn btn-hijau">Simpan Obat</button>
                <?php if ($edit['id_obat']): ?>
                    <a href="obat.php" class="btn btn-outline-secondary ms-2">Batal</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- TABEL DATA OBAT (DI BAWAH) -->
    <div class="table-responsive">
        <table class="table table-custom">
            <thead>
                <tr>
                    <th width="60">No</th>
                    <th width="130">ID Obat</th>
                    <th>Nama Obat</th>
                    <th>Satuan</th>
                    <th>Stok</th>
                    <th width="120" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $q = mysqli_query($koneksi, "SELECT * FROM obat ORDER BY id_obat DESC");
                while ($r = mysqli_fetch_assoc($q)):
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><span class="badge-id">O-<?= str_pad($r['id_obat'], 3, '0', STR_PAD_LEFT) ?></span></td>
                    <td><strong><?= $r['nama_obat'] ?></strong></td>
                    <td><?= $r['satuan'] ?></td>
                    <td><?= $r['stok'] ?></td>
                    <td class="text-center">
                        <div class="btn-group btn-group-sm">
                            <a href="obat.php?edit=<?= $r['id_obat'] ?>" class="btn btn-outline-warning" title="Edit">
                                <i class="fa-regular fa-pen-to-square"></i>
                            </a>
                            <a href="javascript:void(0)" onclick="hapusData('obat.php?hapus=<?= $r['id_obat'] ?>')" class="btn btn-outline-danger" title="Hapus">
                                <i class="fa-regular fa-trash-can"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function hapusData(url) {
    Swal.fire({
        title: 'Yakin Hapus Data?',
        text: "Data obat yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#15803d',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

<?php if (isset($_GET['status'])): ?>
    <?php if ($_GET['status'] == 'simpan_sukses'): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Disimpan!',
            text: 'Data obat baru telah ditambahkan ke database.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#15803d'
        });
    <?php elseif ($_GET['status'] == 'edit_sukses'): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Diperbarui!',
            text: 'Perubahan data obat telah disimpan.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#15803d'
        });
    <?php elseif ($_GET['status'] == 'hapus_sukses'): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Dihapus!',
            text: 'Data obat telah dihapus dari database.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#15803d'
        });
    <?php elseif ($_GET['status'] == 'hapus_gagal'): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Menghapus!',
            text: 'Data obat ini masih digunakan pada riwayat pemeriksaan.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#15803d'
        });
    <?php endif; ?>
    window.history.replaceState(null, null, window.location.pathname);
<?php endif; ?>
</script>

</body>
</html>