'use strict';

const API = '../api';

const FALLBACK_IMG_KEGIATAN = '../assets/images/kegiatan/placeholder-kegiatan.jpg';
const FALLBACK_IMG_GALERI   = '../assets/images/placeholder-galeri.jpg';

let currentPanel    = 'dashboard';
let deleteCallback  = null;
let ulasanAllData   = [];      
let trenChart       = null;     
/* ─────────────────────────────────────────────────────────────
   INIT
   ───────────────────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
  const tipeSelect = document.getElementById('k-tipe');

  const groupTanggal = document.getElementById('group-tanggal');
  const groupHari = document.getElementById('group-hari');
  const groupStatus = document.getElementById('group-status');
  const groupFoto = document.getElementById('group-foto');

  if (!tipeSelect) return;

  const updateFormByTipe = () => {
    const tipe = tipeSelect.value;

    const elTanggal = document.getElementById('k-tanggal');
    const previewImg = document.getElementById('preview-kegiatan');
    const fileInput = document.getElementById('k-gambar');

    if (tipe === 'rutin') {
      if (groupTanggal) groupTanggal.style.display = 'none';
      if (groupHari) groupHari.style.display = 'block';
      if (groupStatus) groupStatus.style.display = 'none';
      if (groupFoto) groupFoto.style.display = 'none';

      if (elTanggal) elTanggal.value = '';

      if (fileInput) fileInput.value = '';
      if (previewImg) {
        previewImg.src = '';
        previewImg.style.display = 'none';
      }

    } else {
      if (groupTanggal) groupTanggal.style.display = 'block';
      if (groupHari) groupHari.style.display = 'none';
      if (groupStatus) groupStatus.style.display = 'block';
      if (groupFoto) groupFoto.style.display = 'block';

      document.querySelectorAll('#hari-list input[type="checkbox"]')
        .forEach(cb => cb.checked = false);

      document.querySelectorAll('.hari-item')
        .forEach(item => item.classList.remove('active'));
    }
  };

  const sidebar   = document.getElementById('sidebar');
  const overlay   = document.getElementById('sidebar-overlay');
  const toggleBtn = document.getElementById('sidebar-toggle');
  const closeBtn  = document.getElementById('sidebar-close');

  const closeSidebar = () => {
    sidebar.classList.remove('open');
    overlay.classList.remove('active');
  };

  toggleBtn.addEventListener('click', () => {
    sidebar.classList.add('open');
    overlay.classList.add('active');
  });
  closeBtn.addEventListener('click', closeSidebar);
  overlay.addEventListener('click', closeSidebar);

  const nama = sessionStorage.getItem('admin_nama') || 'Administrator';
  document.getElementById('admin-nama-display').textContent = nama;

  setupImagePreview('k-gambar', 'preview-kegiatan');
  setupImagePreview('g-gambar', 'preview-galeri');

  document.querySelectorAll('.nav-item[data-panel]').forEach(item => {
    item.addEventListener('click', () => {
      switchPanel(item.dataset.panel);
      closeSidebar();
    });
  });

  document.getElementById('btn-tambah')?.addEventListener('click', () => {
    if (currentPanel === 'kegiatan') {
      openKegiatanModal();

      tipeSelect.dispatchEvent(new Event('change'));
    }

    if (currentPanel === 'galeri') openGaleriModal();
  });

  document.querySelectorAll('[data-close]').forEach(btn => {
    btn.addEventListener('click', () => closeModal(btn.dataset.close));
  });
  document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) closeModal(m.id); });
  });

  document.getElementById('btn-save-kegiatan')?.addEventListener('click', saveKegiatan);
  document.getElementById('btn-save-galeri')?.addEventListener('click', saveGaleri);

  document.getElementById('ulasan-toggle')?.addEventListener('change', function () {
    renderUlasanRows(ulasanAllData, this.checked);
  });

  tipeSelect.addEventListener('change', updateFormByTipe);
  updateFormByTipe();
  loadDashboard();
});



function switchPanel(panel) {
  currentPanel = panel;

  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
  document.querySelector(`.nav-item[data-panel="${panel}"]`).classList.add('active');

  document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
  document.getElementById(`panel-${panel}`).classList.add('active');

  const titles = {
    dashboard : 'Dashboard',
    kegiatan  : 'Kegiatan',
    galeri    : 'Galeri',
    ulasan    : 'Ulasan',
  };
  document.getElementById('topbar-title').textContent = titles[panel] ?? panel;
  document.getElementById('topbar-meta').textContent  = `Kelola data ${panel}`;

  const showAdd = ['kegiatan', 'galeri'].includes(panel);
  document.getElementById('btn-tambah').style.display = showAdd ? 'flex' : 'none';

  if (panel === 'kegiatan') loadKegiatan();
  if (panel === 'galeri')   loadGaleri();
  if (panel === 'ulasan')   loadUlasan();
}

async function loadDashboard() {
  try {
    const [k, g, u] = await Promise.all([
      fetch(`${API}/kegiatan.php`).then(r => r.json()),
      fetch(`${API}/galeri.php`).then(r => r.json()),
      fetch(`${API}/ulasan.php?admin=1`).then(r => r.json()),
    ]);

    const kegiatan = k.data || [];
    const galeri   = g.data || [];
    const ulasan   = u.data || [];
    const pending  = ulasan.filter(u => !u.disetujui);

    document.getElementById('stat-kegiatan').textContent = kegiatan.length;
    document.getElementById('stat-galeri').textContent   = galeri.length;
    document.getElementById('stat-ulasan').textContent   = ulasan.length;
    document.getElementById('stat-pending').textContent  = pending.length;

    const events = kegiatan.filter(k => k.tipe === 'event').slice(0, 3);
    const rutins = kegiatan
      .filter(k => k.tipe === 'rutin')
      .sort((a, b) => b.id - a.id) 
      .slice(0, 4);

    renderDashTable('dash-event-tbody', events, 4);
    renderDashRutinTable('dash-rutin-tbody', rutins, 4);

    buildTrenChart(kegiatan, galeri);

  } catch (err) {
    console.error('Dashboard error:', err);
  }
}

function renderDashTable(tbodyId, rows, colspan = 4) {
  const tbody = document.getElementById(tbodyId);
  if (!rows.length) {
    tbody.innerHTML = `<tr><td colspan="${colspan}">
      <div class="empty-state"><i class="fas fa-inbox"></i><br>Belum ada data.</div>
    </td></tr>`;
    return;
  }

  tbody.innerHTML = rows.map(k => `
    <tr>
      <td>
        <img src="${imgSrc(k.gambar, 'kegiatan')}"
             alt="${esc(k.judul)}"
             class="thumb-sm"
             onerror="this.src='${FALLBACK_IMG_KEGIATAN}'" />
      </td>
      <td><strong>${esc(k.judul)}</strong></td>
      <td>${formatTanggalIndo(k.tanggal)}</td>
      <td>${badgeStatus(k.status)}</td>
    </tr>
  `).join('');
}

function renderDashRutinTable(tbodyId, rows, colspan = 4) {
  const tbody = document.getElementById(tbodyId);

  if (!rows.length) {
    tbody.innerHTML = `<tr><td colspan="${colspan}">
      <div class="empty-state"><i class="fas fa-inbox"></i><br>Belum ada data.</div>
    </td></tr>`;
    return;
  }

tbody.innerHTML = rows.map(k => {

  let hariList = [];

  if (!k.hari) {
    hariList = ['-'];
  } 
  else if (k.hari.includes(',')) {
    hariList = k.hari.split(',').map(h => h.trim());
  } 
  else if (k.hari === 'Setiap Hari') {
    hariList = ['Setiap Hari'];
  } 
  else {
    hariList = k.hari.match(/Senin|Selasa|Rabu|Kamis|Jumat|Sabtu|Minggu/g) || ['-'];
  }

  return `
    <tr>
      <td><strong>${esc(k.judul)}</strong></td>
      <td>${hariList.join(', ')}</td>
      <td>${formatWaktuRange(k.waktu_mulai, k.waktu_selesai)}</td>
    </tr>
  `;

}).join('');
}

function buildTrenChart(kegiatan, galeri) {
  const canvas = document.getElementById('tren-chart');
  if (!canvas) return;

  const months     = [];
  const kCounts    = [];
  const gCounts    = [];
  const now        = new Date();
  const bulanNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

  for (let i = 5; i >= 0; i--) {
    const d  = new Date(now.getFullYear(), now.getMonth() - i, 1);
    const yr = d.getFullYear();
    const mo = d.getMonth();   

    months.push(`${bulanNames[mo]} ${yr}`);

    kCounts.push(kegiatan.filter(item => {
      const t = new Date(item.tanggal ?? item.created_at ?? '');
      return t.getFullYear() === yr && t.getMonth() === mo;
    }).length);

    gCounts.push(galeri.filter(item => {
      const t = new Date(item.created_at ?? '');
      return t.getFullYear() === yr && t.getMonth() === mo;
    }).length);
  }

  if (trenChart) trenChart.destroy();

  trenChart = new Chart(canvas, {
    type: 'line',
    data: {
      labels: months,
      datasets: [
        {
          label: 'Kegiatan',
          data: kCounts,
          borderColor: '#064E3B',
          backgroundColor: 'rgba(6,78,59,0.08)',
          borderWidth: 2.5,
          pointBackgroundColor: '#064E3B',
          pointRadius: 5,
          tension: 0.4,
          fill: true,
        },
        {
          label: 'Galeri',
          data: gCounts,
          borderColor: '#FACC15',
          backgroundColor: 'rgba(250,204,21,0.10)',
          borderWidth: 2.5,
          pointBackgroundColor: '#FACC15',
          pointRadius: 5,
          tension: 0.4,
          fill: true,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'top',
          labels: {
            font: { family: "'Lato', sans-serif", size: 12 },
            usePointStyle: true,
            pointStyleWidth: 12,
            boxWidth: 8,
            boxHeight: 8,
          },
        },
        tooltip: {
          mode: 'index',
          intersect: false,
          callbacks: {
            label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y} item`,
          },
        },
      },
      scales: {
        x: {
          grid: { display: false },
          ticks: { font: { family: "'Lato', sans-serif", size: 11 } },
        },
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            font: { family: "'Lato', sans-serif", size: 11 },
          },
          grid: { color: 'rgba(0,0,0,0.05)' },
        },
      },
    },
  });
}

async function loadKegiatan() {
  const tbody = document.getElementById('kegiatan-tbody');
  tbody.innerHTML = spinnerRow(8);

  try {
    const res  = await fetch(`${API}/kegiatan.php`);
    const json = await res.json();
    const rows = json.data || [];

    if (!rows.length) {
      tbody.innerHTML = emptyRow(8, 'Belum ada kegiatan.', 'fa-calendar-times');
      return;
    }

    const sorted = [
      ...rows.filter(k => k.tipe === 'event').sort(byTanggalDesc),
      ...rows.filter(k => k.tipe === 'rutin').sort(byTanggalDesc),
    ];

    tbody.innerHTML = sorted.map((k, i) => `
      <tr>
        <td>${i + 1}</td>
        <td>
          <img src="${imgSrc(k.gambar, 'kegiatan')}"
               alt="${esc(k.judul)}"
               class="thumb-sm"
               onerror="this.src='${FALLBACK_IMG_KEGIATAN}'" />
        </td>
        <td><strong>${esc(k.judul)}</strong></td>
        <td><span class="badge ${k.tipe === 'event' ? 'upcoming' : 'ongoing'}">${esc(k.tipe)}</span></td>
        <td>
          ${formatTanggalIndo(k.tanggal)}<br>
          <small style="color:var(--gray-500)">${formatWaktuRange(k.waktu_mulai, k.waktu_selesai)}</small>
        </td>
        <td>${esc(k.lokasi || '–')}</td>
        <td>${badgeStatus(k.status)}</td>
        <td>
          <div class="action-btns">
            <button class="btn-icon edit"
              onclick='openKegiatanModal(${JSON.stringify(k).replace(/"/g, '&quot;')})'>
              <i class="fas fa-pencil-alt"></i>
            </button>
            <button class="btn-icon delete"
              onclick="confirmHapus('kegiatan', ${k.id}, '${esc(k.judul)}')">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>
    `).join('');

  } catch (err) {
    console.error('loadKegiatan error:', err);
    tbody.innerHTML = errorRow(8);
  }
}

function openKegiatanModal(data = null) {
  document.getElementById('modal-kegiatan-title').textContent =
    data ? 'Edit Kegiatan' : 'Tambah Kegiatan';

  document.getElementById('k-id').value          = data?.id           || '';
  document.getElementById('k-judul').value       = data?.judul        || '';
  document.getElementById('k-deskripsi').value   = data?.deskripsi    || '';
  document.getElementById('k-tanggal').value     = data?.tanggal      || '';
  document.getElementById('k-waktu-mulai').value = data?.waktu_mulai  ? data.waktu_mulai.slice(0, 5)  : '';
  document.getElementById('k-waktu-selesai').value = data?.waktu_selesai ? data.waktu_selesai.slice(0, 5) : '';
  document.getElementById('k-lokasi').value      = data?.lokasi       || '';
  document.getElementById('k-tipe').value        = data?.tipe         || 'event';
  document.getElementById('k-status').value      = data?.status       || 'upcoming';
  document.getElementById('k-gambar').value      = '';

  const prev = document.getElementById('preview-kegiatan');
  if (data?.gambar) {
    prev.src = imgSrc(data.gambar, 'kegiatan');
    prev.style.display = 'block';
  } else {
    prev.src = '';
    prev.style.display = 'none';
  }
  const tipeSelect = document.getElementById('k-tipe');
  if (tipeSelect) {
    tipeSelect.dispatchEvent(new Event('change'));
  }
  openModal('modal-kegiatan');
}

async function saveKegiatan() {
  const id      = document.getElementById('k-id').value;
  const judul   = document.getElementById('k-judul').value.trim();
  const tanggal = document.getElementById('k-tanggal').value;
  const tipe    = document.getElementById('k-tipe').value;

  if (!judul) {
    showToast('Judul wajib diisi', true);
    return;
  }

  let hari = '';

  if (tipe === 'event') {
    if (!tanggal) {
      showToast('Tanggal wajib diisi untuk event', true);
      return;
    }

    const hariMap = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const tgl = new Date(tanggal);
    hari = isNaN(tgl) ? '' : hariMap[tgl.getDay()];
  }

  if (tipe === 'rutin') {

    const hariChecked = Array.from(
      document.querySelectorAll('#hari-list input[type="checkbox"]:checked')
    ).map(cb => cb.value);

    const adaSemua = hariChecked.includes('Setiap Hari');

    if (hariChecked.length === 0) {
      showToast('Pilih minimal satu hari', true);
      return;
    }

    if (adaSemua && hariChecked.length > 1) {
      showToast('Pilih "Setiap Hari" ATAU hari lain saja', true);
      return;
    }

    hari = adaSemua ? 'Setiap Hari' : hariChecked.join(', ');
  }

  const fd = new FormData();
  fd.append('judul', judul);
  fd.append('deskripsi', document.getElementById('k-deskripsi').value);
  fd.append('tanggal', tipe === 'event' ? tanggal : '');
  fd.append('hari', hari);
  fd.append('waktu_mulai', convertTime(document.getElementById('k-waktu-mulai').value));
  fd.append('waktu_selesai', convertTime(document.getElementById('k-waktu-selesai').value));
  fd.append('lokasi', document.getElementById('k-lokasi').value);
  fd.append('tipe', tipe.toLowerCase());
  fd.append('status', document.getElementById('k-status').value);

  const fileInput = document.getElementById('k-gambar');
  if (fileInput.files.length > 0) {
    const file = fileInput.files[0];
    if (!file.type.startsWith('image/')) {
      showToast('File harus berupa gambar!', true);
      return;
    }
    fd.append('gambar', file);
  }

  if (id) {
    fd.append('_method', 'PUT');
    fd.append('id', id);
  }

  try {
    const res  = await fetch(`${API}/kegiatan.php`, { method: 'POST', body: fd });
    if (!res.ok) throw new Error('Server ' + res.status);

    const data = await res.json();

    closeModal('modal-kegiatan');
    showToast(data.message);
    loadKegiatan();
    loadDashboard();

  } catch (err) {
    console.error(err);
    showToast('Gagal menyimpan data!', true);
  }
}

async function loadGaleri() {
  const tbody = document.getElementById('galeri-tbody');
  tbody.innerHTML = spinnerRow(6);

  try {
    const res  = await fetch(`${API}/galeri.php`);
    const json = await res.json();
    const rows = json.data || [];

    if (!rows.length) {
      tbody.innerHTML = emptyRow(6, 'Belum ada foto.', 'fa-images');
      return;
    }

    tbody.innerHTML = rows.map((g, i) => `
      <tr>
        <td>${i + 1}</td>
        <td>
          <img src="${imgSrc(g.gambar, 'galeri')}"
               alt="${esc(g.judul)}"
               class="thumb-sm"
               onerror="this.src='${FALLBACK_IMG_GALERI}'" />
        </td>
        <td><strong>${esc(g.judul)}</strong></td>
        <td><span class="badge upcoming">${esc(g.kategori)}</span></td>
        <td>${g.urutan}</td>
        <td>
          <div class="action-btns">
            <button class="btn-icon edit"
              onclick='openGaleriModal(${JSON.stringify(g).replace(/"/g, '&quot;')})'>
              <i class="fas fa-pencil-alt"></i>
            </button>
            <button class="btn-icon delete"
              onclick="confirmHapus('galeri', ${g.id}, '${esc(g.judul)}')">
              <i class="fas fa-trash"></i>
            </button>
          </div>
        </td>
      </tr>
    `).join('');

  } catch (err) {
    console.error(err);
    tbody.innerHTML = errorRow(6);
  }
}

function openGaleriModal(data = null) {
  document.getElementById('modal-galeri-title').textContent = data ? 'Edit Foto' : 'Tambah Foto';
  document.getElementById('g-id').value        = data?.id        || '';
  document.getElementById('g-judul').value     = data?.judul     || '';
  document.getElementById('g-deskripsi').value = data?.deskripsi || '';
  document.getElementById('g-kategori').value  = data?.kategori  || 'eksterior';
  document.getElementById('g-urutan').value    = data?.urutan    ?? 0;
  document.getElementById('g-gambar').value    = '';

  const prev = document.getElementById('preview-galeri');
  if (data?.gambar) {
    prev.src = imgSrc(data.gambar, 'galeri');
    prev.style.display = 'block';
  } else {
    prev.src = '';
    prev.style.display = 'none';
  }

  openModal('modal-galeri');
}

async function saveGaleri() {
  const id = document.getElementById('g-id').value;
  const fd = new FormData();
  fd.append('judul',     document.getElementById('g-judul').value);
  fd.append('deskripsi', document.getElementById('g-deskripsi').value);
  fd.append('kategori',  document.getElementById('g-kategori').value);
  fd.append('urutan',    document.getElementById('g-urutan').value);

  const fileInput = document.getElementById('g-gambar');
  if (fileInput.files.length > 0) {
    const file = fileInput.files[0];
    if (!file.type.startsWith('image/')) { showToast('File harus berupa gambar!', true); return; }
    fd.append('gambar', file);
  }

  if (id) { fd.append('_method', 'PUT'); fd.append('id', id); }

  try {
    const res  = await fetch(`${API}/galeri.php`, { method: 'POST', body: fd });
    const data = await res.json();
    closeModal('modal-galeri');
    showToast(data.message);
    loadGaleri();
    loadDashboard();
  } catch (err) {
    console.error(err);
    showToast('Gagal menyimpan foto!', true);
  }
}

async function loadUlasan() {
  const tbody = document.getElementById('ulasan-tbody');
  tbody.innerHTML = spinnerRow(7);

  try {
    const res  = await fetch(`${API}/ulasan.php?admin=1`);
    const json = await res.json();
    ulasanAllData  = json.data || [];

    const filterOn = document.getElementById('ulasan-toggle')?.checked ?? false;
    renderUlasanRows(ulasanAllData, filterOn);

  } catch (err) {
    console.error(err);
    document.getElementById('ulasan-tbody').innerHTML = errorRow(7);
  }
}

const label = document.getElementById('ulasan-filter-label');

if (approvedOnly) {
  label.textContent = 'Menampilkan: Disetujui';
} else {
  label.textContent = 'Menampilkan: Semua';
}

function renderUlasanRows(rows, approvedOnly) {
  const tbody = document.getElementById('ulasan-tbody');
  const label = document.getElementById('ulasan-filter-label');

  const filtered = approvedOnly ? rows.filter(u => u.disetujui == 1) : rows;
  if (label) label.textContent = approvedOnly ? 'Menampilkan: Disetujui' : 'Menampilkan: Semua';

  if (!filtered.length) {
    tbody.innerHTML = emptyRow(7,
      approvedOnly ? 'Belum ada ulasan yang disetujui.' : 'Belum ada ulasan.',
      'fa-star');
    return;
  }

  tbody.innerHTML = filtered.map((u, i) => `
    <tr>
      <td>${i + 1}</td>
      <td><strong>${esc(u.nama)}</strong></td>
      <td>${esc(u.asal || '–')}</td>
      <td>${'★'.repeat(parseInt(u.bintang))}${'☆'.repeat(5 - parseInt(u.bintang))}</td>
      <td class="td-komentar">${esc(u.komentar)}</td>
      <td>${u.disetujui == 1
        ? '<span class="badge approved">Disetujui</span>'
        : '<span class="badge pending">Pending</span>'}</td>
      <td>
        <div class="action-btns">
          ${u.disetujui == 0
            ? `<button class="btn-icon check" onclick="updateStatus(${u.id},'setujui')" title="Setujui">
                <i class="fas fa-check"></i></button>`
            : `<button class="btn-icon pending" onclick="updateStatus(${u.id},'tolak')" title="Pending">
                <i class="fas fa-undo"></i></button>`}
          <button class="btn-icon delete" onclick="confirmHapus('ulasan',${u.id},'${esc(u.nama)}')">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      </td>
    </tr>
  `).join('');
}

async function updateStatus(id, aksi) {
  try {
    const res  = await fetch(`${API}/ulasan.php?id=${id}&aksi=${aksi}`, { method: 'POST' });
    const data = await res.json();
    showToast(data.message);
    loadUlasan();
    loadDashboard();
  } catch (err) {
    showToast('Gagal update status!', true);
  }
}

function confirmHapus(resource, id, nama) {
  document.getElementById('hapus-msg').textContent =
    `Apakah Anda yakin ingin menghapus "${nama}"? Tindakan ini tidak dapat dibatalkan.`;

  deleteCallback = async () => {
    try {
      const res  = await fetch(`${API}/${resource}.php?id=${id}`, { method: 'DELETE' });
      const data = await res.json();
      closeModal('modal-hapus');
      showToast(data.message);
      if (resource === 'kegiatan') loadKegiatan();
      if (resource === 'galeri')   loadGaleri();
      if (resource === 'ulasan')   loadUlasan();
      loadDashboard();
    } catch (err) {
      showToast('Gagal menghapus data!', true);
    }
  };

  document.getElementById('btn-konfirm-hapus').onclick = deleteCallback;
  openModal('modal-hapus');
}

function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function showToast(msg, isError = false) {
  const toast = document.getElementById('toast');
  document.getElementById('toast-msg').textContent = msg;
  toast.style.background = isError ? 'var(--red)' : 'var(--emerald)';
  toast.classList.add('show');
  setTimeout(() => toast.classList.remove('show'), 3000);
}

function esc(str) {
  return String(str ?? '')
    .replace(/&/g,'&amp;').replace(/</g,'&lt;')
    .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function imgSrc(filename, type) {
  if (!filename) return type === 'kegiatan' ? FALLBACK_IMG_KEGIATAN : FALLBACK_IMG_GALERI;
  return `../assets/images/${type}/${filename}`;
}

function byTanggalDesc(a, b) {
  return new Date(b.tanggal || 0) - new Date(a.tanggal || 0);
}

function formatTanggalIndo(tanggal) {
  if (!tanggal) return '–';
  const bulan = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
  const d = new Date(tanggal);
  if (isNaN(d)) return '–';
  return `${d.getDate()} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
}

function formatWaktuRange(mulai, selesai) {
  if (!mulai || !selesai) return '–';
  return `${mulai.slice(0,5)} – ${selesai.slice(0,5)}`;
}

function convertTime(t) {
  if (!t) return '';
  return t.length === 5 ? t + ':00' : t;
}

function badgeStatus(s) {
  const cls   = { upcoming: 'upcoming', ongoing: 'ongoing', selesai: 'selesai' };
  const label = { upcoming: 'Akan Datang', ongoing: 'Berlangsung', selesai: 'Selesai' };
  return `<span class="badge ${cls[s] || ''}">${label[s] || esc(s)}</span>`;
}

function spinnerRow(colspan) {
  return `<tr><td colspan="${colspan}" style="text-align:center;padding:2rem;color:#9CA3AF">
    <i class="fas fa-spinner fa-spin"></i>
  </td></tr>`;
}

function emptyRow(colspan, msg, icon = 'fa-inbox') {
  return `<tr><td colspan="${colspan}">
    <div class="empty-state"><i class="fas ${icon}"></i><br>${msg}</div>
  </td></tr>`;
}

function errorRow(colspan) {
  return `<tr><td colspan="${colspan}" style="text-align:center;color:var(--red);padding:1.5rem">
    <i class="fas fa-exclamation-triangle"></i> Gagal memuat data. Coba refresh.
  </td></tr>`;
}

function setupImagePreview(inputId, previewId) {
  const input   = document.getElementById(inputId);
  const preview = document.getElementById(previewId);
  if (!input || !preview) return;

  input.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
      preview.src = URL.createObjectURL(file);
      preview.style.display = 'block';
    }
  });
}
