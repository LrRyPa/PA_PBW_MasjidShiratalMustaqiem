<?php

require_once __DIR__ . '/../config/database.php';

class UlasanModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function getAll(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM ulasan ORDER BY created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function getApproved(): array
    {
        $stmt = $this->db->query(
            "SELECT * FROM ulasan WHERE disetujui = 1 ORDER BY created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM ulasan WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO ulasan (nama, asal, bintang, komentar, disetujui)
            VALUES (:nama, :asal, :bintang, :komentar, 0)
        ");
        $stmt->execute([
            ':nama'     => $data['nama'],
            ':asal'     => $data['asal']    ?? null,
            ':bintang'  => $data['bintang'] ?? 5,
            ':komentar' => $data['komentar'],
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function setPersetujuan(int $id, bool $disetujui): bool
    {
        $stmt = $this->db->prepare(
            "UPDATE ulasan SET disetujui = :disetujui WHERE id = :id"
        );
        return $stmt->execute([
            ':id'        => $id,
            ':disetujui' => (int) $disetujui,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM ulasan WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
