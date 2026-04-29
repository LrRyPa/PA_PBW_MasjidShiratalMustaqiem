<?php

require_once __DIR__ . '/../config/database.php';

class AdminModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db();
    }

    public function login(string $username, string $password): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM admin WHERE username = :username LIMIT 1"
        );

        $stmt->execute([':username' => $username]);

        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin) {
            return false;
        }

        if (!password_verify($password, $admin['password'])) {
            return false;
        }

        return $admin;
    }

    public function getById(int $id): array|false
    {
        $stmt = $this->db->prepare(
            "SELECT id, nama, username FROM admin WHERE id = :id"
        );

        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function ubahPassword(int $id, string $passwordBaru): bool
    {
        $hash = password_hash($passwordBaru, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare(
            "UPDATE admin SET password = :password WHERE id = :id"
        );

        return $stmt->execute([
            ':password' => $hash,
            ':id'       => $id,
        ]);
    }
}