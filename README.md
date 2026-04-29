# Proyek Akhir Praktikum Pemrograman Berbasis Web 2026

Kelas: A'2024

1. Larry Polin Anugrah (2409116026)
2. Muhammad Fachri (2409116017)
3. Syauqi Etna Lazhuardhy (2409116030)
4. Fathinatuz Zaina (2409116016)

# Deskripsi Website
Website **Masjid Shiratal Mustaqiem Samarinda** merupakan platform digital yang dirancang untuk menyajikan informasi mengenai Masjid Shiratal Mustaqiem Samarinda sebagai salah satu warisan budaya Islam di Kalimantan Timur. Website ini bertujuan untuk memperkenalkan sejarah, arsitektur, serta aktivitas keagamaan dan budaya yang berlangsung di masjid kepada masyarakat luas.

Website ini juga menyediakan dokumentasi visual, informasi lokasi, serta fitur interaktif berupa ulasan pengunjung. Selain itu, terdapat sistem admin yang memungkinkan pengelolaan data secara dinamis, seperti kegiatan, galeri, dan ulasan.

# Tujuan
1.	Mengembangkan website Masjid Shiratal Mustaqiem sebagai media penyampaian informasi yang mudah diakses oleh masyarakat.
2.	Menyediakan informasi lengkap mengenai Masjid Shiratal Mustaqiem, meliputi sejarah, kegiatan, galeri foto, lokasi, dan kontak.
3.	Membangun fitur interaktif berupa sistem rating dan ulasan pengunjung sebagai sarana partisipasi pengguna.
4.	Mempermudah pengelola dalam mengelola data dan informasi masjid melalui fitur CRUD (Create, Read, Update, Delete).
5.	Meningkatkan efektivitas promosi digital Masjid Shiratal Mustaqiem sebagai destinasi wisata religi dan cagar budaya di Samarinda.

# Fitur Website

## A. Pengguna (User)

### 1. Beranda
- Menampilkan hero section dengan informasi utama masjid
- Highlight informasi penting (tahun berdiri, kapasitas, dll)
- Ringkasan sejarah, lokasi, dan status cagar budaya
- Preview kegiatan unggulan
- Preview galeri foto
- Navigasi cepat ke halaman lain

### 2. Sejarah
- Informasi lengkap asal-usul dan pendirian masjid
- Penjelasan arsitektur dan keunikan bangunan
- Detail material dan nilai historis
- Timeline perjalanan sejarah (linimasa interaktif)
- Perbandingan kondisi masjid dulu dan sekarang

### 3. Kegiatan
- Menampilkan kegiatan unggulan (featured event)
- Daftar semua kegiatan (event, rutin, dll)
- Filter kegiatan berdasarkan status (akan datang, berlangsung, selesai)
- Detail kegiatan (tanggal, waktu, lokasi, deskripsi)
- Tabel program rutin mingguan

### 4. Galeri
- Menampilkan foto-foto masjid dalam bentuk grid responsif
- Kategori foto (eksterior, interior, sejarah, kegiatan, dll)
- Filter berdasarkan kategori
- Tampilan visual yang dinamis dan terstruktur

### 5. Lokasi
- Integrasi peta (Google Maps)
- Informasi alamat lengkap dan koordinat
- Tombol navigasi ke Google Maps / Waze
- Jam operasional masjid
- Informasi tempat terdekat
- Panduan menuju lokasi (kendaraan pribadi, umum, dll)
- Tips kunjungan bagi pengunjung

### 6. Ulasan
- Menampilkan rating rata-rata pengunjung
- Statistik distribusi rating
- Daftar ulasan pengguna
- Form input ulasan (nama, asal, rating, komentar)
- Sistem penilaian berbasis bintang (star rating)

## B. Admin

### 1. Login Admin
- Sistem autentikasi username & password
- Pembatasan akses hanya untuk admin

### 2. Dashboard
- Ringkasan data (total kegiatan, galeri, ulasan)
- Informasi ulasan pending
- Daftar kegiatan terbaru
- Monitoring kondisi website secara cepat

### 3. Manajemen Kegiatan
- Menampilkan daftar kegiatan dalam tabel
- Tambah kegiatan baru (judul, deskripsi, tanggal, waktu, lokasi, tipe, status, gambar)
- Edit data kegiatan
- Hapus kegiatan
- Pengelompokan tipe (event / rutin)
- Pengaturan status kegiatan

### 4. Manajemen Galeri
- Menampilkan daftar foto galeri
- Tambah foto (judul, deskripsi, kategori, urutan, upload gambar)
- Edit data foto
- Hapus foto
- Pengelompokan berdasarkan kategori
- Pengaturan urutan tampil

### 5. Manajemen Ulasan
- Menampilkan daftar ulasan pengguna
- Moderasi ulasan (setujui / tidak)
- Hapus ulasan yang tidak sesuai
- Menjaga kualitas dan validitas konten

# Struktur Folder

```
project/
│
├── admin/
│   ├── dashboard.php
│   ├── login.php
│   └── logout.php
│
├── api/
│   ├── auth.php
│   ├── galeri.php
│   ├── kegiatan.php
│   └── ulasan.php
│
├── assets/
│   ├── css/
│   ├── images/
│   ├── script/
│   ├── script.js
│   └── style.css
│
├── config/
│   ├── database.php
│   └── koneksi.php
│
├── controllers/
│   ├── AuthController.php
│   ├── GaleriController.php
│   ├── KegiatanController.php
│   └── UlasanController.php
│
├── models/
│   ├── AdminModel.php
│   ├── GaleriModel.php
│   ├── KegiatanModel.php
│   └── UlasanModel.php
│
├── view/
│   ├── galeri.html
│   ├── index.html
│   ├── kegiatan.html
│   ├── lokasi.html
│   ├── sejarah.html
│   └── ulasan.html
```
# Dokumentasi Tampilan Website

---

### Login Page

> *Login Page Admin*
>
> <img width="764" height="370" alt="image" src="https://github.com/user-attachments/assets/b00d1812-7bd2-4b36-acc0-ba71e7c64a24" />
>

---

### Beranda (Home)

> *Beranda — Hero Section*
>
> <img width="810" height="384" alt="image" src="https://github.com/user-attachments/assets/ef90a3ce-874f-4b0a-b6fa-b78a0ad7e209" />
>
> *Beranda — Sekilas Info*
>
> <img width="814" height="389" alt="image" src="https://github.com/user-attachments/assets/46dcbd68-4bfd-465f-9570-ea8d1bbe8b4d" />
>
> *Beranda — Artikel*
>
> <img width="800" height="382" alt="image" src="https://github.com/user-attachments/assets/a821c9fa-9788-48c7-9888-ec4c5e5e6e8a" />
>
> *Beranda — Kegiatan*
>
> <img width="799" height="380" alt="image" src="https://github.com/user-attachments/assets/3048cce8-8526-4145-bff3-caf57428a7a3" />
>
> *Beranda — Galeri Foto*
>
> <img width="794" height="379" alt="image" src="https://github.com/user-attachments/assets/4841e49e-314e-49b2-8a7f-0184abebd609" />
>

---

### Sejarah

> *Sejarah Masjid*
>
> <img width="790" height="377" alt="image" src="https://github.com/user-attachments/assets/735d5e71-a4f8-4af4-a94f-17ef4d637bb6" />
>
> *Sejarah — Asal-Usul*
>
> <img width="796" height="379" alt="image" src="https://github.com/user-attachments/assets/c91a5bb4-ccce-4bd1-9a56-ef68d92f8150" />
>
> *Sejarah — Arsitektur dan Perkembangan*
>
> <img width="787" height="375" alt="image" src="https://github.com/user-attachments/assets/ffcb9e84-86b7-4349-8436-d3fbee10f8ed" />
>
> *Sejarah — Status Cagar dan Peran*
>
> <img width="747" height="356" alt="image" src="https://github.com/user-attachments/assets/2fad7db3-dc8c-454f-9cd8-faf8eac1d828" />
> -
> <img width="757" height="360" alt="image" src="https://github.com/user-attachments/assets/be0717ba-249e-4343-8520-20be16b81c47" />
>
> *Sejarah — Perjalanan Sejarah Lanjutan*
>
> <img width="746" height="354" alt="image" src="https://github.com/user-attachments/assets/8f67a28e-44f1-4b4e-8890-a685bac15f6d" />
>
> *Sejarah — Dulu dan Kini*
>
> <img width="763" height="365" alt="image" src="https://github.com/user-attachments/assets/d7a911bc-e767-415b-bc8f-edb376a7e534" />
> 

---

### Kegiatan

> *Kegiatan*
>
> <img width="756" height="360" alt="image" src="https://github.com/user-attachments/assets/20a940fa-d230-42e2-894b-4809554e5dda" />
>
> *Kegiatan — Outing Class*
>
> <img width="760" height="364" alt="image" src="https://github.com/user-attachments/assets/b6accb9b-0740-40b0-9cab-1cde04af3eaf" />
>
> *Kegiatan — Jadwal Program*
>
> <img width="760" height="364" alt="image" src="https://github.com/user-attachments/assets/e4d32df5-d999-4a2d-8df6-232844711aa9" />
>
> *Kegiatan — Program Rutin Mingguan*
>
> <img width="772" height="300" alt="image" src="https://github.com/user-attachments/assets/1f7641e9-d180-4c00-985c-5cc3f06c674b" />
>

---

### Galeri

> *Galeri — Tampilan Grid Foto*
>
> <img width="766" height="365" alt="image" src="https://github.com/user-attachments/assets/9c43d916-1ae2-4cd8-a7e9-776ee369e1ff" />
> -
> <img width="770" height="369" alt="image" src="https://github.com/user-attachments/assets/5b2ff91f-1f39-444b-bfac-123f0cdf436d" />
>

---

### Lokasi

> <img width="749" height="350" alt="image" src="https://github.com/user-attachments/assets/3b28b645-846f-4498-98b3-7f0ac9551cb3" />
> -
> <img width="737" height="347" alt="image" src="https://github.com/user-attachments/assets/68dd1a36-98bf-483f-8f21-629391f1d0aa" />

---

### Ulasan

> *Ulasan — Daftar Komentar*
>
> <img width="754" height="354" alt="image" src="https://github.com/user-attachments/assets/e296a1f7-c827-4646-baec-3420fca4ff4a" />
>
> *Ulasan — Form Input*
>
> <img width="756" height="360" alt="image" src="https://github.com/user-attachments/assets/363b8c70-a87a-4056-a76b-588204fe3272" />
>

---

### Dashboard Admin

> *Dashboard — Halaman Utama*
>
> <img width="763" height="312" alt="image" src="https://github.com/user-attachments/assets/12ea64d9-f30b-4c2c-ab53-05df68076d94" />
> -
> <img width="746" height="328" alt="image" src="https://github.com/user-attachments/assets/73852b3c-60c9-45b1-92a7-bed19745142b" />
>
> *Dashboard — Kelola Data*
>
> <img src="link_dashboard_2" />

---
### Admin — Kelola Kegiatan 

> *Tabel Data Kegiatan*
>
> <img width="784" height="366" alt="image" src="https://github.com/user-attachments/assets/15783d6d-d4f3-4127-92ba-785c5cb0518a" />
>
> *Form Tambah Kegiatan*
>
> <img width="781" height="355" alt="image" src="https://github.com/user-attachments/assets/58f01c4f-527a-451b-af36-0e86a043ba3c" />
>
> *Form Edit Kegiatan*
>
> <img width="761" height="356" alt="image" src="https://github.com/user-attachments/assets/be556d85-8abf-494a-92c7-f2dca85b1766" />
>
> *Aksi Hapus Kegiatan*
>
> <img width="754" height="356" alt="image" src="https://github.com/user-attachments/assets/2328d3d3-0ec0-4f23-b1fa-2e53f655d52f" />

---
### Admin — Kelola Galeri 

> *Tabel Data Galeri*
>
> <img width="773" height="363" alt="image" src="https://github.com/user-attachments/assets/2103d02c-6e32-4ef9-a4e4-e15f599fa903" />
>
> *Form Tambah Galeri*
>
> <img width="775" height="369" alt="image" src="https://github.com/user-attachments/assets/13c2cbfa-8445-4ab2-80ba-08c2552f162b" />
>
> *Form Edit Galeri*
>
> <img width="775" height="366" alt="image" src="https://github.com/user-attachments/assets/f56b970a-7367-49e0-b77c-613de35c5cfc" />
>
> *Aksi Hapus Galeri*
>
> <img width="753" height="359" alt="image" src="https://github.com/user-attachments/assets/e3345307-56bd-4871-b742-67067a614f72" />

---
### Admin — Kelola Ulasan 

> *Tabel Data Ulasan*
>
> <img width="749" height="353" alt="image" src="https://github.com/user-attachments/assets/301a6928-04c4-4730-b041-661a014078af" />
>
> *Ulasan Disetujui*
>
> <img width="750" height="355" alt="image" src="https://github.com/user-attachments/assets/e9af8908-10b2-494d-b6d1-eca8834dd7c3" />
>
> *Hapus Ulasan*
> 
> <img width="745" height="349" alt="image" src="https://github.com/user-attachments/assets/b39c8cdb-784d-4495-a7e5-8ebf736371c2" />
>
---
