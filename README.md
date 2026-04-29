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
> <img width="800" height="377" alt="image" src="https://github.com/user-attachments/assets/3f420c9f-40f2-4d75-a3d1-c52d28e805dc" />
>

---

### Beranda (Home)

> *Beranda — Hero Section*
>
> <img width="797" height="380" alt="image" src="https://github.com/user-attachments/assets/ef17cf75-32f1-485d-99a1-6c1044012dcf" />
>
> *Beranda — Sekilas Info*
>
> <img width="800" height="381" alt="image" src="https://github.com/user-attachments/assets/f7af28cd-67c3-4ece-82f9-8c5b6a5b11ca" />

>
> *Beranda — Artikel*
>
> <img width="800" height="382" alt="image" src="https://github.com/user-attachments/assets/f8023b4e-91d9-48bb-b8d6-e581de046e0e" />
>
> *Beranda — Kegiatan*
>
> <img width="798" height="381" alt="image" src="https://github.com/user-attachments/assets/6c6b10a5-838b-4e2a-8867-08554ae20930" />
>
> *Beranda — Galeri Foto*
>
> <img width="802" height="382" alt="image" src="https://github.com/user-attachments/assets/e2811c7b-68f5-45e8-9764-4dc88da9c3ca" />
>

---

### Sejarah

> *Sejarah Masjid*
>
> <img width="793" height="379" alt="image" src="https://github.com/user-attachments/assets/d13ee087-1639-4c10-a9f8-06d846cd4050" />
>
> *Sejarah — Asal-Usul*
>
> <img width="793" height="378" alt="image" src="https://github.com/user-attachments/assets/2f3fd02c-1ab1-44b6-9e40-454639569202" />
>
> *Sejarah — Arsitektur dan Perkembangan*
>
> <img width="793" height="379" alt="image" src="https://github.com/user-attachments/assets/0e46942d-4e5e-41cd-80bb-a68d62887992" />
>
> *Sejarah — Status Cagar dan Peran*
>
><img width="795" height="379" alt="image" src="https://github.com/user-attachments/assets/ce51baae-b649-4ec2-9916-1fd280a1ef74" />
>
> *Sejarah — Perjalanan Sejarah*
> 
> <img width="795" height="379" alt="image" src="https://github.com/user-attachments/assets/f0cc4b5f-fbea-4daa-b37c-c8e3a00b4121" />
>
> *Sejarah — Perjalanan Sejarah Lanjutan*
>
> <img width="794" height="377" alt="image" src="https://github.com/user-attachments/assets/61dca36a-4e8c-4d25-b76a-a2e23febec4c" />
>
> *Sejarah — Dulu dan Kini*
>
> <img width="795" height="379" alt="image" src="https://github.com/user-attachments/assets/407f37dd-5d5d-4760-b922-1d6776eb911e" />
> 

---

### Kegiatan

> *Kegiatan*
>
> <img width="797" height="379" alt="image" src="https://github.com/user-attachments/assets/ac4197cb-10f9-4335-a51b-031f34ec002d" />
>
> *Kegiatan — Outing Class*
>
> <img width="794" height="378" alt="image" src="https://github.com/user-attachments/assets/d374a89f-fa29-4327-8fdf-38e451eda9f5" />
>
> *Kegiatan — Jadwal Program*
>
> <img width="797" height="379" alt="image" src="https://github.com/user-attachments/assets/10cab7d6-dd7d-423a-b5c4-d1ea4178d662" />
>
> *Kegiatan — Program Rutin Mingguan*
>
> <img width="829" height="358" alt="image" src="https://github.com/user-attachments/assets/bc8d2829-a30e-460e-a1a2-1fe75667e9a8" />
>

---

### Galeri

> *Galeri — Tampilan Grid Foto*
>
> <img width="794" height="379" alt="image" src="https://github.com/user-attachments/assets/48882601-c511-4324-87a8-b3148460bea8" />
> -
> <img width="794" height="380" alt="image" src="https://github.com/user-attachments/assets/1b303c0c-2952-438f-8eef-815952ba7cb6" />
>

---

### Lokasi

> <img width="794" height="378" alt="image" src="https://github.com/user-attachments/assets/7fc77333-e941-46a5-8052-fcc8bce8f537" />
> -
> <img width="799" height="382" alt="image" src="https://github.com/user-attachments/assets/38d66f5e-1b75-4853-b8ac-cfce907991af" />

---

### Ulasan

> *Ulasan — Daftar Komentar*
>
> <img width="798" height="381" alt="image" src="https://github.com/user-attachments/assets/7510c160-fd8d-4060-a55f-b29494a89597" />
>
> *Ulasan — Form Input*
>
> <img width="794" height="378" alt="image" src="https://github.com/user-attachments/assets/95a71c55-0524-4a13-8fe6-68160532b8bf" />
>

---

### Dashboard Admin

> *Dashboard — Halaman Utama*
>
> <img width="798" height="311" alt="image" src="https://github.com/user-attachments/assets/5ef9a65d-bf4e-4f9b-b000-69225a8d6488" />
> -
> <img width="798" height="381" alt="image" src="https://github.com/user-attachments/assets/17abe817-67e6-47d5-9a89-d05630fc2b99" />
>

---

### Admin — Kelola Kegiatan 

> *Tabel Data Kegiatan*
>
> <img width="798" height="375" alt="image" src="https://github.com/user-attachments/assets/7be44bef-2ea3-48d9-9dc7-a8153ef2422e" />
>
> *Form Tambah Kegiatan*
>
> <img width="798" height="375" alt="image" src="https://github.com/user-attachments/assets/013a3476-3f68-438c-9abc-c4cc36fa78d8" />
>
> *Form Edit Kegiatan*
>
> <img width="797" height="374" alt="image" src="https://github.com/user-attachments/assets/584bf10b-595a-4e84-b381-85f3e5e57f97" />
>
> *Aksi Hapus Kegiatan*
>
> <img width="797" height="374" alt="image" src="https://github.com/user-attachments/assets/c9a1f5e5-619f-4bbf-8ba9-49eb77cf754e" />

---
### Admin — Kelola Galeri 

> *Tabel Data Galeri*
>
> <img width="796" height="379" alt="image" src="https://github.com/user-attachments/assets/d895fd52-52b0-4c54-94fc-9e3a98dd6a51" />
>
> *Form Tambah Galeri*
>
> <img width="795" height="377" alt="image" src="https://github.com/user-attachments/assets/c1a5429e-987d-4736-a848-13e9f3690c46" />
>
> *Form Edit Galeri*
>
> <img width="794" height="378" alt="image" src="https://github.com/user-attachments/assets/cb02868f-2c4f-41f5-81de-5757fd37063e" />
>
> *Aksi Hapus Galeri*
>
> <img width="794" height="378" alt="image" src="https://github.com/user-attachments/assets/47291b5a-cc0c-4355-93e3-b171d9061f74" />
>

---
### Admin — Kelola Ulasan 

> *Tabel Data Ulasan*
>
> <img width="797" height="375" alt="image" src="https://github.com/user-attachments/assets/82088314-f2a1-483a-92f7-80d7147597fe" />
>
> *Ulasan Disetujui*
>
> <img width="795" height="375" alt="image" src="https://github.com/user-attachments/assets/850ec288-6944-413e-ba81-f901532a7fc3" />
>
> *Hapus Ulasan*
> 
> <img width="797" height="374" alt="image" src="https://github.com/user-attachments/assets/6132a006-7a5a-43bd-bbdf-3e3d80e0110f" />
>
---
