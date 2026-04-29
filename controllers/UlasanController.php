<?php

require_once __DIR__ . '/../models/UlasanModel.php';

class UlasanController
{
    private UlasanModel $model;

    public function __construct()
    {
        $this->model = new UlasanModel();
    }

    public function handle(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $id     = isset($_GET['id']) ? (int) $_GET['id'] : null;
        $aksi   = $_GET['aksi'] ?? null; 

        match (true) {
            $method === 'GET'  && !$id           => $this->index(),
            $method === 'GET'  && $id > 0        => $this->show($id),
            $method === 'POST' && !$aksi         => $this->store(),
            $method === 'POST' && $aksi === 'setujui' => $this->setujui($id, true),
            $method === 'POST' && $aksi === 'tolak'   => $this->setujui($id, false),
            $method === 'DELETE'                 => $this->destroy($id),
            default => $this->respond(['status' => 'error', 'message' => 'Method tidak didukung.'], 405),
        };
    }

    private function index(): void
    {
        $adminMode = isset($_GET['admin']) && $_GET['admin'] === '1';

        $data = $adminMode
            ? $this->model->getAll()
            : $this->model->getApproved();

        $this->respond(['status' => 'success', 'data' => $data]);
    }

    private function show(int $id): void
    {
        $row = $this->model->getById($id);

        if (!$row) {
            $this->respond(['status' => 'error', 'message' => 'Ulasan tidak ditemukan.'], 404);
            return;
        }

        $this->respond(['status' => 'success', 'data' => $row]);
    }

    private function store(): void
    {
        $input = $this->getJsonInput();

        if (empty($input['nama']) || empty($input['komentar'])) {
            $this->respond(['status' => 'error', 'message' => 'Nama dan komentar wajib diisi.'], 422);
            return;
        }

        $bintang = (int) ($input['bintang'] ?? 5);
        if ($bintang < 1 || $bintang > 5) {
            $this->respond(['status' => 'error', 'message' => 'Bintang harus antara 1–5.'], 422);
            return;
        }

        $id = $this->model->create($input);
        $this->respond([
            'status'  => 'success',
            'message' => 'Ulasan berhasil dikirim. Menunggu persetujuan admin.',
            'id'      => $id
        ], 201);
    }

    private function setujui(?int $id, bool $disetujui): void
    {
        if (!$id) {
            $this->respond(['status' => 'error', 'message' => 'ID wajib disertakan.'], 400);
            return;
        }

        $ok = $this->model->setPersetujuan($id, $disetujui);
        $label = $disetujui ? 'disetujui' : 'ditolak';

        $this->respond($ok
            ? ['status' => 'success', 'message' => "Ulasan berhasil {$label}."]
            : ['status' => 'error',   'message' => 'Ulasan tidak ditemukan.'], $ok ? 200 : 404
        );
    }

    private function destroy(?int $id): void
    {
        if (!$id) {
            $this->respond(['status' => 'error', 'message' => 'ID wajib disertakan.'], 400);
            return;
        }

        $ok = $this->model->delete($id);
        $this->respond($ok
            ? ['status' => 'success', 'message' => 'Ulasan berhasil dihapus.']
            : ['status' => 'error',   'message' => 'Ulasan tidak ditemukan.'], $ok ? 200 : 404
        );
    }

    private function getJsonInput(): array
    {
        $raw = file_get_contents('php://input');
        return json_decode($raw, true) ?? [];
    }

    private function respond(array $payload, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
