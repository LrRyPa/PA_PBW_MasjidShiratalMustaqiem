<?php
session_start();
require_once "../config/koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Admin — Masjid Shiratal Mustaqiem</title>
  <link rel="icon" href="../assets/images/favicon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../assets/css/admin.css" />
  <link rel="stylesheet" href="../assets/css/dashboard.css" />
  <script src="../assets/script/dashboard.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js" defer></script>
</head>
<body>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<aside class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="sidebar-brand-icon"><i class="fas fa-mosque"></i></div>
    <div class="sidebar-brand-name">Shiratal Mustaqiem</div>
    <div class="sidebar-brand-sub">Admin Panel </div>
    <button class="sidebar-close-btn" id="sidebar-close" aria-label="Tutup sidebar">
      <i class="fas fa-times"></i>
    </button>
  </div>

  <nav class="sidebar-nav">
    <div class="nav-section-label">Menu Utama</div>
    <div class="nav-item active" data-panel="dashboard">
      <i class="fas fa-th-large"></i> Dashboard
    </div>

    <div class="nav-section-label">Kelola Konten</div>
    <div class="nav-item" data-panel="kegiatan">
      <i class="fas fa-calendar-alt"></i> Kegiatan
    </div>
    <div class="nav-item" data-panel="galeri">
      <i class="fas fa-images"></i> Galeri
    </div>
    <div class="nav-item" data-panel="ulasan">
      <i class="fas fa-star"></i> Ulasan
    </div>
  </nav>

  <div class="sidebar-footer">
    <div class="admin-info">
      <div class="admin-avatar"><i class="fas fa-user-shield"></i></div>
      <div>
        <div class="admin-name" id="admin-nama-display">Administrator</div>
        <div class="admin-role">Super Admin</div>
      </div>
    </div>
    <a href="logout.php" class="btn-logout">
      <i class="fas fa-sign-out-alt"></i> Keluar
    </a>
  </div>
</aside>

<main class="main">

  <div class="topbar">
    <button class="sidebar-toggle" id="sidebar-toggle" aria-label="Buka menu">
      <i class="fas fa-bars"></i>
    </button>
    <div>
      <div class="topbar-title" id="topbar-title">Dashboard</div>
      <div class="topbar-meta" id="topbar-meta">Selamat datang di panel administrasi</div>
    </div>
    <button class="topbar-action" id="btn-tambah" style="display:none">
      <i class="fas fa-plus"></i> <span>Tambah Baru</span>
    </button>
  </div>

  <div class="content">

    <div id="panel-dashboard" class="panel active">

      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-icon green"><i class="fas fa-calendar-check"></i></div>
          <div>
            <div class="stat-num" id="stat-kegiatan">–</div>
            <div class="stat-label">Total Kegiatan</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon gold"><i class="fas fa-images"></i></div>
          <div>
            <div class="stat-num" id="stat-galeri">–</div>
            <div class="stat-label">Total Galeri</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon blue"><i class="fas fa-star"></i></div>
          <div>
            <div class="stat-num" id="stat-ulasan">–</div>
            <div class="stat-label">Total Ulasan</div>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon purple"><i class="fas fa-clock"></i></div>
          <div>
            <div class="stat-num" id="stat-pending">–</div>
            <div class="stat-label">Ulasan Pending</div>
          </div>
        </div>
      </div>

      <div class="table-card chart-card">
        <div class="table-header">
          <div class="table-title"><i class="fas fa-chart-line" style="color:var(--emerald);margin-right:.5rem"></i>Tren Upload Konten (6 Bulan Terakhir)</div>
        </div>
        <div class="chart-body">
          <canvas id="tren-chart"></canvas>
        </div>
      </div>

      <div class="dash-tables-grid">
        <div class="table-card">
          <div class="table-header">
            <div class="table-title">
              <span class="badge upcoming" style="margin-right:.5rem">Event</span>
              Kegiatan Event Terbaru
            </div>
          </div>
          <div class="table-responsive">
            <table>
              <thead>
                <tr>
                  <th>Foto</th><th>Judul</th><th>Tanggal</th><th>Status</th>
                </tr>
              </thead>
              <tbody id="dash-event-tbody">
                <tr><td colspan="4" class="empty-state"><i class="fas fa-spinner fa-spin"></i></td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="table-card">
          <div class="table-header">
            <div class="table-title">
              <span class="badge ongoing" style="margin-right:.5rem">Rutin</span>
              Kegiatan Rutin Terbaru
            </div>
          </div>
          <div class="table-responsive">
            <table>
              <thead>
                <tr>
                  <th>Judul</th><th>Hari</th><th>Waktu</th>
                </tr>
              </thead>
              <tbody id="dash-rutin-tbody">
                <tr><td colspan="4" class="empty-state"><i class="fas fa-spinner fa-spin"></i></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>
    <div id="panel-kegiatan" class="panel">
      <div class="table-card">
        <div class="table-header">
          <div class="table-title">Daftar Kegiatan</div>
        </div>
        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th>#</th><th>Foto</th><th>Judul</th><th>Tipe</th>
                <th>Tanggal</th><th>Lokasi</th><th>Status</th><th>Aksi</th>
              </tr>
            </thead>
            <tbody id="kegiatan-tbody">
              <tr><td colspan="8" class="empty-state"><i class="fas fa-spinner fa-spin"></i></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div id="panel-galeri" class="panel">
      <div class="table-card">
        <div class="table-header">
          <div class="table-title">Daftar Foto Galeri</div>
        </div>
        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th>#</th><th>Foto</th><th>Judul</th><th>Kategori</th><th>Urutan</th><th>Aksi</th>
              </tr>
            </thead>
            <tbody id="galeri-tbody">
              <tr><td colspan="6" class="empty-state"><i class="fas fa-spinner fa-spin"></i></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div id="panel-ulasan" class="panel">
      <div class="table-card">
        <div class="table-header">
          <div class="table-title">Daftar Ulasan</div>
          <div class="ulasan-filter-group">
            <span id="ulasan-filter-label">Menampilkan: Semua</span>
            <label class="toggle-switch">
              <input type="checkbox" id="ulasan-toggle" />
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>
        <div class="table-responsive">
          <table>
            <thead>
              <tr>
                <th>#</th><th>Nama</th><th>Asal</th><th>Bintang</th>
                <th>Komentar</th><th>Status</th><th>Aksi</th>
              </tr>
            </thead>
            <tbody id="ulasan-tbody">
              <tr><td colspan="7" class="empty-state"><i class="fas fa-spinner fa-spin"></i></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</main>

<div id="modal-kegiatan" class="modal-overlay">
  <div class="modal modal-compact">
    <div class="modal-header">
      <div class="modal-title" id="modal-kegiatan-title">Tambah Kegiatan</div>
      <button class="modal-close" data-close="modal-kegiatan"><i class="fas fa-times"></i></button>
    </div>

    <div class="modal-body compact-body">
      <input type="hidden" id="k-id" />

      <div class="form-group form-full">
        <label class="form-label">Judul Kegiatan *</label>
        <input type="text" id="k-judul" class="form-control" placeholder="Judul kegiatan" />
      </div>

      <div class="compact-row">
        
        <div class="form-group" id="group-tanggal">
          <label class="form-label">Tanggal</label>
          <input type="date" id="k-tanggal" class="form-control" />
        </div>

        <div class="form-group" id="group-hari" style="display:none;">
          <label class="form-label">Hari</label>

          <div class="hari-container" id="hari-list">
            <label class="hari-item">
              <input type="checkbox" value="Senin"> 
              <span>Senin</span>
            </label>

            <label class="hari-item">
              <input type="checkbox" value="Selasa"> 
              <span>Selasa</span>
            </label>

            <label class="hari-item">
              <input type="checkbox" value="Rabu"> 
              <span>Rabu</span>
            </label>

            <label class="hari-item">
              <input type="checkbox" value="Kamis"> 
              <span>Kamis</span>
            </label>

            <label class="hari-item">
              <input type="checkbox" value="Jumat"> 
              <span>Jumat</span>
            </label>

            <label class="hari-item">
              <input type="checkbox" value="Sabtu"> 
              <span>Sabtu</span>
            </label>

            <label class="hari-item">
              <input type="checkbox" value="Minggu"> 
              <span>Minggu</span>
            </label>

            <label class="hari-item highlight">
              <input type="checkbox" value="Setiap Hari" id="hari-semua"> 
              <span>Setiap Hari</span>
            </label>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Tipe</label>
          <select id="k-tipe" class="form-control">
            <option value="event">Event</option>
            <option value="rutin">Rutin</option>
          </select>
        </div>

      </div>

      <div class="compact-row">
        <div class="form-group">
          <label class="form-label">Waktu Mulai</label>
          <input type="time" id="k-waktu-mulai" class="form-control" />
        </div>
        <div class="form-group">
          <label class="form-label">Waktu Selesai</label>
          <input type="time" id="k-waktu-selesai" class="form-control" />
        </div>
      </div>

      <div class="compact-row">
        <div class="form-group">
          <label class="form-label">Lokasi</label>
          <input type="text" id="k-lokasi" class="form-control" placeholder="Lokasi" />
        </div>
          <div class="form-group" id="group-status">
          <label class="form-label">Status</label>
          <select id="k-status" class="form-control">
            <option value="upcoming">Akan Datang</option>
            <option value="ongoing">Berlangsung</option>
            <option value="selesai">Selesai</option>
          </select>
        </div>
      </div>

      <div class="compact-row compact-row-media">
        <div class="form-group">
          <label class="form-label">Deskripsi</label>
          <textarea id="k-deskripsi" class="form-control compact-textarea" placeholder="Deskripsi singkat…"></textarea>
        </div>
          <div class="form-group" id="group-foto">
          <label class="form-label">Foto</label>
          <div class="upload-wrapper-compact">
            <input type="file" id="k-gambar" accept="image/*" hidden />
            <div class="upload-box-compact" onclick="document.getElementById('k-gambar').click()">
              <i class="fas fa-image"></i>
              <span>Klik upload</span>
              <small>JPG · PNG · WEBP</small>
            </div>
            <img id="preview-kegiatan" class="preview-img-compact" />
          </div>
        </div>
      </div>
    </div>

    <div class="modal-footer">
      <button class="btn btn-secondary" data-close="modal-kegiatan">Batal</button>
      <button class="btn btn-primary" id="btn-save-kegiatan">
        <i class="fas fa-save"></i> Simpan
      </button>
    </div>
  </div>
</div>

<div id="modal-galeri" class="modal-overlay">
  <div class="modal modal-compact">
    <div class="modal-header">
      <div class="modal-title" id="modal-galeri-title">Tambah Foto</div>
      <button class="modal-close" data-close="modal-galeri"><i class="fas fa-times"></i></button>
    </div>

    <div class="modal-body compact-body">
      <input type="hidden" id="g-id" />

      <div class="form-group form-full">
        <label class="form-label">Judul Foto *</label>
        <input type="text" id="g-judul" class="form-control" placeholder="Judul foto" />
      </div>

      <div class="compact-row">
        <div class="form-group">
          <label class="form-label">Kategori</label>
          <select id="g-kategori" class="form-control">
            <option value="eksterior">Eksterior</option>
            <option value="interior">Interior</option>
            <option value="kegiatan">Kegiatan</option>
            <option value="arsitektur">Arsitektur</option>
            <option value="sejarah">Sejarah</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Urutan Tampil</label>
          <input type="number" id="g-urutan" class="form-control" placeholder="0" min="0" />
        </div>
      </div>

      <div class="compact-row compact-row-media">
        <div class="form-group">
          <label class="form-label">Deskripsi</label>
          <textarea id="g-deskripsi" class="form-control compact-textarea" placeholder="Deskripsi foto…"></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">File Gambar</label>
          <div class="upload-wrapper-compact">
            <input type="file" id="g-gambar" accept="image/*" hidden />
            <div class="upload-box-compact" onclick="document.getElementById('g-gambar').click()">
              <i class="fas fa-image"></i>
              <span>Klik upload</span>
              <small>JPG · PNG · WEBP</small>
            </div>
            <img id="preview-galeri" class="preview-img-compact" />
          </div>
        </div>
      </div>
    </div>

    <div class="modal-footer">
      <button class="btn btn-secondary" data-close="modal-galeri">Batal</button>
      <button class="btn btn-primary" id="btn-save-galeri">
        <i class="fas fa-save"></i> Simpan
      </button>
    </div>
  </div>
</div>

<div id="modal-hapus" class="modal-overlay">
  <div class="modal" style="max-width:400px">
    <div class="modal-header">
      <div class="modal-title" style="color:var(--red)">
        <i class="fas fa-trash"></i> Konfirmasi Hapus
      </div>
      <button class="modal-close" data-close="modal-hapus"><i class="fas fa-times"></i></button>
    </div>
    <div class="modal-body">
      <p id="hapus-msg" style="font-size:0.9rem;color:var(--gray-500)">
        Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.
      </p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-close="modal-hapus">Batal</button>
      <button class="btn btn-danger" id="btn-konfirm-hapus">
        <i class="fas fa-trash"></i> Hapus
      </button>
    </div>
  </div>
</div>

<div class="toast" id="toast">
  <i class="fas fa-check-circle"></i>
  <span id="toast-msg"></span>
</div>


</body>
</html>
