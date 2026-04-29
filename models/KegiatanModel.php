<?php

require_once __DIR__ . '/../config/database.php';

class KegiatanModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM kegiatan ORDER BY tanggal DESC, waktu_mulai DESC"
        );
        return $stmt->fetchAll();
    }

    public function getByStatus(string $status): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM kegiatan WHERE status = :status ORDER BY tanggal ASC"
        );
        $stmt->execute([':status' => $status]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM kegiatan WHERE id = :id"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO kegiatan 
            (judul, deskripsi, tanggal, hari, waktu_mulai, waktu_selesai, lokasi, tipe, status, gambar)
            VALUES 
            (:judul, :deskripsi, :tanggal, :hari, :waktu_mulai, :waktu_selesai, :lokasi, :tipe, :status, :gambar)
        ");
        $stmt->execute([
            ':judul'     => $data['judul'],
            ':deskripsi' => $data['deskripsi'] ?? null,
            ':tanggal'   => $data['tanggal'],
            ':hari'          => $data['hari'] ?? null,
            ':waktu_mulai'   => $data['waktu_mulai'] ?? null,
            ':waktu_selesai' => $data['waktu_selesai'] ?? null,
            ':lokasi'        => $data['lokasi'] ?? '',
            ':tipe'          => $data['tipe'] ?? 'event',
            ':status'    => $data['status']    ?? 'upcoming',
            ':gambar'    => $data['gambar']    ?? null,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE kegiatan SET
                judul = :judul,
                deskripsi = :deskripsi,
                tanggal = :tanggal,
                hari = :hari,
                waktu_mulai = :waktu_mulai,
                waktu_selesai = :waktu_selesai,
                lokasi = :lokasi,
                tipe = :tipe,
                status = :status,
                gambar = :gambar
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id'            => $id,
            ':judul'         => $data['judul'],
            ':deskripsi'     => $data['deskripsi'] ?? '',
            ':tanggal'       => $data['tanggal'],
            ':hari'          => $data['hari'] ?? null,
            ':waktu_mulai'   => $data['waktu_mulai'] ?? null,
            ':waktu_selesai' => $data['waktu_selesai'] ?? null,
            ':lokasi'        => $data['lokasi'] ?? '',
            ':tipe'          => $data['tipe'] ?? 'event',
            ':status'        => $data['status'] ?? 'upcoming',
            ':gambar'        => $data['gambar'] ?? null,
        ]);
    }

    public function delete(int $id): bool
    {
        $uploadDir = __DIR__ . "/../assets/images/kegiatan";

        $stmt = $this->db->prepare("SELECT gambar FROM kegiatan WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if ($row && !empty($row['gambar'])) {
            $file = $uploadDir . $row['gambar'];
            if (file_exists($file)) {
                unlink($file);
            }
        }

        $stmt = $this->db->prepare("DELETE FROM kegiatan WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
