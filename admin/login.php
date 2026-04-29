<?php
session_start();
require_once "../config/koneksi.php";
$error = "";

if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_POST['login'])) {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([$username]);

    $user = $stmt->fetch();

    if ($user) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin'] = $user['username'];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Admin — Masjid Shiratal Mustaqiem</title>
  <link rel="icon" href="../assets/images/favicon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link rel="stylesheet" href="../assets/css/admin.css" />
  <link rel="stylesheet" href="../assets/css/login.css" />
</head>
<body>

<div class="login-card">
  <div class="login-logo">
    <div class="login-logo-icon"><i class="fas fa-mosque"></i></div>
    <h1 class="login-title">Panel Admin</h1>
    <p class="login-subtitle">Masjid Shiratal Mustaqiem · Warisan Sejarah Masjid</p>
    
  </div>

  <?php if (!empty($error)): ?>
    <div class="alert error">
      <?= $error ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <div class="form-group">
      <label class="form-label" for="username">Username</label>
      <div class="form-input-wrapper">
        <i class="fas fa-user form-input-icon"></i>
        <input type="text" id="username" name="username" class="form-input"
               placeholder="Masukkan username" autocomplete="username" required />
      </div>
    </div>

    <div class="form-group">
      <label class="form-label" for="password">Password</label>
      <div class="form-input-wrapper">
        <i class="fas fa-lock form-input-icon"></i>
        <input type="password" id="password" name="password" class="form-input"
               placeholder="Masukkan password" autocomplete="current-password" required />
        <button type="button" class="toggle-password" id="toggle-pw" title="Tampilkan password">
          <i class="fas fa-eye"></i>
        </button>
      </div>
    </div>

    <button type="submit" name="login" class="btn-login" id="btn-submit">
      <i class="fas fa-sign-in-alt"></i> Masuk ke Panel Admin
    </button>
  </form>

  <div class="login-footer">
    <a href="../view/index.html"><i class="fas fa-arrow-left"></i> Kembali ke Beranda</a>
  </div>
</div>

<script>
  document.getElementById('toggle-pw').addEventListener('click', function () {
    const pw  = document.getElementById('password');
    const ico = this.querySelector('i');
    const isHidden = pw.type === 'password';
    pw.type = isHidden ? 'text' : 'password';
    ico.className = isHidden ? 'fas fa-eye-slash' : 'fas fa-eye';
  });

  document.getElementById('login-form').addEventListener('submit', async function (e) {
    e.preventDefault();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const btn      = document.getElementById('btn-submit');
    const alertBox = document.getElementById('alert-error');
    const alertMsg = document.getElementById('alert-msg');

    alertBox.style.display = 'none';

    if (!username || !password) {
      alertMsg.textContent = 'Username dan password wajib diisi.';
      alertBox.style.display = 'flex';
      return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memverifikasi...';

    try {
      const res  = await fetch('../api/auth.php?aksi=login', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify({ username, password }),
      });
      const data = await res.json();

      if (data.status === 'success') {
        btn.innerHTML = '<i class="fas fa-check"></i> Login Berhasil!';
        sessionStorage.setItem('admin_nama', data.admin.nama);
        setTimeout(() => { window.location.href = 'dashboard.php'; }, 800);
      } else {
        alertMsg.textContent = data.message || 'Login gagal.';
        alertBox.style.display = 'flex';
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Masuk ke Panel Admin';
      }
    } catch (err) {
      alertMsg.textContent = 'Terjadi kesalahan koneksi. Coba lagi.';
      alertBox.style.display = 'flex';
      btn.disabled = false;
      btn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Masuk ke Panel Admin';
    }
  });
</script>

</body>
</html>
