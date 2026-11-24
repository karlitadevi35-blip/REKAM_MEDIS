<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik HealthCare</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #f8fafc; font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; }
        
        /* Sidebar Presisi Gambar Contoh */
        .sidebar { 
            min-height: 100vh; 
            background-color: #0d1527; 
            color: #fff; 
            padding: 32px 20px;
        }
        .sidebar .brand-title {
            color: #38bdf8;
            font-weight: 700;
            font-size: 1.35rem;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar .menu-header {
            color: #475569;
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 16px;
            padding-left: 12px;
        }
        .sidebar a { 
            color: #cbd5e1; 
            text-decoration: none; 
            padding: 12px 16px; 
            display: flex; 
            align-items: center;
            gap: 14px;
            border-radius: 10px; 
            margin-bottom: 8px; 
            font-weight: 500;
            font-size: 0.98rem;
            transition: all 0.2s ease;
        }
        .sidebar a i {
            font-size: 1.15rem;
        }
        .sidebar a:hover {
            color: #ffffff;
        }
        .sidebar a.active { 
            background-color: #172a46; 
            color: #ffffff; 
            font-weight: 600;
            box-shadow: inset 0 0 0 1px rgba(56, 189, 248, 0.1);
        }
        
        /* Kartu Statistik Gambar 2 */
        .card-stat {
            background: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 20px 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border-left: 4px solid transparent;
        }
        .card-stat.stat-pasien { border-left-color: #0284c7; }
        .card-stat.stat-dokter { border-left-color: #10b981; }
        .card-stat.stat-obat { border-left-color: #f59e0b; }
        .card-stat.stat-rm { border-left-color: #6366f1; }
        
        .stat-title { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase; }
        .stat-pasien .stat-title { color: #0284c7; }
        .stat-dokter .stat-title { color: #10b981; }
        .stat-obat .stat-title { color: #d97706; }
        .stat-rm .stat-title { color: #6366f1; }
        
        .stat-value { font-size: 2.2rem; font-weight: 700; margin-top: 4px; line-height: 1; }
        .stat-pasien .stat-value { color: #0369a1; }
        .stat-dokter .stat-value { color: #047857; }
        .stat-obat .stat-value { color: #b45309; }
        .stat-rm .stat-value { color: #4338ca; }

        /* Tabel & Badges */
        .card-custom { border-radius: 12px; border: none; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .badge-berhasil { background-color: #d1fae5; color: #059669; font-weight: 600; font-size: 0.78rem; padding: 4px 10px; border-radius: 6px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Navigation Kiri -->
        <div class="col-md-3 col-lg-2 sidebar">
            <div class="brand-title">
                <i class="bi bi-building-add fs-3"></i> Klinik HealthCare
            </div>
            <div class="menu-header">MENU UTAMA</div>
            <nav class="nav flex-column">
                <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="pasien.php" class="<?= basename($_SERVER['PHP_SELF']) == 'pasien.php' ? 'active' : ''; ?>">
                    <i class="bi bi-people"></i> Pasien
                </a>
                <a href="dokter.php" class="<?= basename($_SERVER['PHP_SELF']) == 'dokter.php' ? 'active' : ''; ?>">
                    <i class="bi bi-person-badge"></i> Dokter
                </a>
                <a href="obat.php" class="<?= basename($_SERVER['PHP_SELF']) == 'obat.php' ? 'active' : ''; ?>">
                    <i class="bi bi-capsule"></i> Obat
                </a>
                <a href="rekam_medis.php" class="<?= basename($_SERVER['PHP_SELF']) == 'rekam_medis.php' ? 'active' : ''; ?>">
                    <i class="bi bi-file-earmark-medical"></i> Form Rekam Medis
                </a>
                <a href="riwayat.php" class="<?= basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'active' : ''; ?>">
                    <i class="bi bi-journal-text"></i> Riwayat<br>Pemeriksaan
                </a>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="col-md-9 col-lg-10 p-4">