<?php

require_once __DIR__ . '/../models/KegiatanModel.php';

class KegiatanController
{
    private KegiatanModel $model;

    public function __construct()
    {
        $this->model = new KegiatanModel();
    }

    public function handle(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = $_POST['_method'];
        }

        $id = $_POST['id'] ?? ($_GET['id'] ?? null);

        match ($method) {
            'GET'    => $id ? $this->show($id)    : $this->index(),
            'POST'   => $this->store(),
            'PUT'    => $this->update($id),
            'DELETE' => $this->destroy($id),
            default  => $this->respond(['status' => 'error', 'message' => 'Method tidak didukung.'], 405),
        };
    }

    private function index(): void
    {
        $status = $_GET['status'] ?? null;

        $data = $status
            ? $this->model->getByStatus($status)
            : $this->model->getAll();

        $this->respond(['status' => 'success', 'data' => $data]);
    }

    private function show(int $id): void
    {
        $row = $this->model->getById($id);

        if (!$row) {
            $this->respond(['status' => 'error', 'message' => 'Kegiatan tidak ditemukan.'], 404);
            return;
        }

        $this->respond(['status' => 'success', 'data' => $row]);
    }

    private function store(): void
    {
        $input = $_POST;

        if (empty($input['judul'])) {
            $this->respond(['status' => 'error', 'message' => 'Judul wajib diisi.'], 422);
            return;
        }

        if (($input['tipe'] ?? 'event') === 'event' && empty($input['tanggal'])) {
            $this->respond(['status' => 'error', 'message' => 'Tanggal wajib untuk event.'], 422);
            return;
        }

        if (($input['tipe'] ?? 'event') === 'rutin' && empty($input['hari'])) {
            $this->respond(['status' => 'error', 'message' => 'Hari wajib untuk kegiatan rutin.'], 422);
            return;
        }
        
        $input['tanggal'] = ($input['tanggal'] ?? '') !== '' ? $input['tanggal'] : null;
        $input['gambar'] = $this->uploadGambar();
        $input['deskripsi']     = $input['deskripsi'] ?? '';
        $input['lokasi']        = $input['lokasi'] ?? '';
        $input['tipe']          = $input['tipe'] ?? 'event';
        $input['status']        = $input['status'] ?? 'upcoming';

        $input['hari']          = $input['hari'] ?? null;
        $input['waktu_mulai']   = $input['waktu_mulai'] ?? null;
        $input['waktu_selesai'] = $input['waktu_selesai'] ?? null;

        $id = $this->model->create($input);

        $this->respond([
            'status' => 'success',
            'message' => 'Kegiatan berhasil ditambahkan.',
            'id' => $id
        ], 201);
    }

    private function update(?int $id): void
    {
        if (!$id) {
            $this->respond(['status' => 'error', 'message' => 'ID wajib disertakan.'], 400);
            return;
        }

        $input = $_POST;

        if (empty($input['judul'])) {
            $this->respond(['status' => 'error', 'message' => 'Judul wajib diisi.'], 422);
            return;
        }

        if (($input['tipe'] ?? 'event') === 'event' && empty($input['tanggal'])) {
            $this->respond(['status' => 'error', 'message' => 'Tanggal wajib untuk event.'], 422);
            return;
        }

        if (($input['tipe'] ?? 'event') === 'rutin' && empty($input['hari'])) {
            $this->respond(['status' => 'error', 'message' => 'Hari wajib untuk kegiatan rutin.'], 422);
            return;
        }

        $input['tanggal'] = ($input['tanggal'] ?? '') !== '' ? $input['tanggal'] : null;

        $old = $this->model->getById($id);

        $input['gambar'] = $this->uploadGambar($old['gambar'] ?? null);
        $input['deskripsi'] = $input['deskripsi'] ?? '';
        $input['lokasi']    = $input['lokasi'] ?? '';
        $input['tipe']      = $input['tipe'] ?? 'event';
        $input['status']    = $input['status'] ?? 'upcoming';

        $input['hari']          = $input['hari'] ?? null;
        $input['waktu_mulai']   = $input['waktu_mulai'] ?? null;
        $input['waktu_selesai'] = $input['waktu_selesai'] ?? null;

        $ok = $this->model->update($id, $input);

        $this->respond($ok
            ? ['status' => 'success', 'message' => 'Kegiatan berhasil diperbarui.']
            : ['status' => 'error', 'message' => 'Kegiatan tidak ditemukan.'],
            $ok ? 200 : 404
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
            ? ['status' => 'success', 'message' => 'Kegiatan berhasil dihapus.']
            : ['status' => 'error',   'message' => 'Kegiatan tidak ditemukan.'], $ok ? 200 : 404
        );
    }

    private function respond(array $payload, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private function uploadGambar($oldFile = null) {
        $uploadDir = __DIR__ . "/../../assets/images/kegiatan/";
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
                $this->respond(['status' => 'error', 'message' => 'Gagal upload gambar'], 500);
            }

            if ($oldFile && file_exists($uploadDir . $oldFile)) {
                unlink($uploadDir . $oldFile);
            }

            return $namaFile;
        }

        return $oldFile;
    }
}
