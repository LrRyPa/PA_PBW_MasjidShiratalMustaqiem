<?php

require_once __DIR__ . '/../models/GaleriModel.php';

class GaleriController
{
    private GaleriModel $model;

    public function __construct()
    {
        $this->model = new GaleriModel();
    }

    public function handle(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = $_POST['_method'];
        }

        $id = $_POST['id'] ?? ($_GET['id'] ?? null);

        switch ($method) {
            case 'GET':
                $id ? $this->show($id) : $this->index();
                break;

            case 'POST':
                $this->store();
                break;

            case 'PUT':
                $this->update($id);
                break;

            case 'DELETE':
                $this->destroy($id);
                break;

            default:
                $this->respond(['status' => 'error', 'message' => 'Method tidak valid'], 405);
        }
    }

    private function index(): void
    {
        $data = $this->model->getAll();
        $this->respond(['status' => 'success', 'data' => $data]);
    }

    private function show(int $id): void
    {
        $data = $this->model->getById($id);

        if (!$data) {
            $this->respond(['status' => 'error', 'message' => 'Data tidak ditemukan'], 404);
        }

        $this->respond(['status' => 'success', 'data' => $data]);
    }

    private function store(): void
    {
        $input = $_POST;

        if (empty($input['judul'])) {
            $this->respond(['status' => 'error', 'message' => 'Judul wajib diisi'], 422);
            return;
        }

        $input['gambar'] = $this->uploadGambar();

        $id = $this->model->create($input);

        $this->respond([
            'status' => 'success',
            'message' => 'Galeri berhasil ditambahkan',
            'id' => $id
        ]);
    }

    private function update(?int $id): void
    {
        if (!$id) {
            $this->respond(['status' => 'error', 'message' => 'ID wajib'], 400);
        }

        $input = $_POST;

        $old = $this->model->getById($id);

        $input['gambar'] = $this->uploadGambar($old['gambar'] ?? null);

        $input['deskripsi'] = $input['deskripsi'] ?? '';
        $input['kategori']  = $input['kategori'] ?? 'eksterior';
        $input['urutan']    = $input['urutan'] ?? 0;

        $ok = $this->model->update($id, $input);

        $this->respond($ok
            ? ['status' => 'success', 'message' => 'Galeri berhasil diupdate']
            : ['status' => 'error', 'message' => 'Data tidak ditemukan'],
            $ok ? 200 : 404
        );
    }

    private function destroy(?int $id): void
    {
        if (!$id) {
            $this->respond(['status' => 'error', 'message' => 'ID wajib'], 400);
        }

        $old = $this->model->getById($id);

        $uploadDir = __DIR__ . "/../../assets/images/galeri";

        if ($old && !empty($old['gambar']) && file_exists($uploadDir . $old['gambar'])) {
            unlink($uploadDir . $old['gambar']);
        }

        $this->model->delete($id);

        $this->respond(['status' => 'success', 'message' => 'Galeri berhasil dihapus']);
    }

    private function uploadGambar($oldFile = null)
    {
        $uploadDir = __DIR__ . "/../../assets/images/galeri/";
        $allowed = ['jpg','jpeg','png','webp'];

        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {

            $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $this->respond(['status' => 'error', 'message' => 'Format gambar tidak valid'], 400);
            }
            $original = pathinfo($_FILES['gambar']['name'], PATHINFO_FILENAME);
            $original = preg_replace("/[^a-zA-Z0-9]/", "_", $original);

            $namaFile = time() . "_" . $original . "." . $ext;

            if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $uploadDir . $namaFile)) {
                $this->respond(['status' => 'error', 'message' => 'Upload gagal'], 500);
            }

            if ($oldFile && file_exists($uploadDir . $oldFile)) {
                unlink($uploadDir . $oldFile);
            }

            return $namaFile;
        }

        return $oldFile;
    }

    private function respond(array $payload, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($payload);
        exit;
    }
}
