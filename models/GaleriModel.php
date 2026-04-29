<?php

require_once __DIR__ . '/../config/database.php';

class GaleriModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM galeri ORDER BY urutan ASC, created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function getByKategori(string $kategori): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM galeri WHERE kategori = :kategori ORDER BY urutan ASC"
        );
        $stmt->execute([':kategori' => $kategori]);
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM galeri WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO galeri (judul, deskripsi, kategori, gambar, urutan)
            VALUES (:judul, :deskripsi, :kategori, :gambar, :urutan)
        ");
        $stmt->execute([
            ':judul'     => $data['judul'],
            ':deskripsi' => $data['deskripsi'] ?? null,
            ':kategori'  => $data['kategori']  ?? 'eksterior',
            ':gambar'    => $data['gambar'],
            ':urutan'    => $data['urutan']    ?? 0,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE galeri
            SET judul = :judul, deskripsi = :deskripsi, kategori = :kategori,
                gambar = :gambar, urutan = :urutan
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id'        => $id,
            ':judul'     => $data['judul'],
            ':deskripsi' => $data['deskripsi'] ?? null,
            ':kategori'  => $data['kategori']  ?? 'eksterior',
            ':gambar'    => $data['gambar'],
            ':urutan'    => $data['urutan']    ?? 0,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("SELECT gambar FROM galeri WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        if ($row && !empty($row['gambar'])) {
            $file = __DIR__ . "/../../assets/images/galeri" . $row['gambar'];
            if (file_exists($file)) {
                unlink($file);
            }
        }

        $stmt = $this->db->prepare("DELETE FROM galeri WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
