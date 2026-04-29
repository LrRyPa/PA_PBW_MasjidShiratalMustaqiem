<?php

require_once __DIR__ . '/../models/AdminModel.php';

class AuthController
{
    private $model;

    public function __construct()
    {
        $this->model = new AdminModel();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function handle(): void
    {
        header('Content-Type: application/json; charset=UTF-8');

        $method = $_SERVER['REQUEST_METHOD'];
        $aksi   = $_GET['aksi'] ?? '';

        switch ($aksi) {
            case 'login':
                if ($method !== 'POST') {
                    $this->respond(['status'=>'error','message'=>'Method tidak didukung'],405);
                    return;
                }
                $this->login();
                break;

            case 'logout':
                $this->logout();
                break;

            case 'check':
                $this->checkSession();
                break;

            default:
                $this->respond(['status'=>'error','message'=>'Aksi tidak dikenali'],400);
        }
    }

    private function login(): void
    {
        $input = $this->getJsonInput();

        if (!is_array($input)) {
            $this->respond(['status'=>'error','message'=>'Format JSON tidak valid'],400);
            return;
        }

        $username = trim($input['username'] ?? '');
        $password = $input['password'] ?? '';

        if (!$username || !$password) {
            $this->respond(['status'=>'error','message'=>'Username & password wajib'],422);
            return;
        }

        $admin = $this->model->login($username, $password);

        if (!$admin) {
            $this->respond(['status'=>'error','message'=>'Login gagal'],401);
            return;
        }

        session_regenerate_id(true);

        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_nama'] = $admin['nama'];

        $this->respond([
            'status'=>'success',
            'message'=>'Login berhasil',
            'admin'=>[
                'id'=>$admin['id'],
                'nama'=>$admin['nama'],
                'username'=>$admin['username']
            ]
        ]);
    }

    private function logout(): void
    {
        $_SESSION = [];
        session_destroy();

        $this->respond(['status'=>'success','message'=>'Logout berhasil']);
    }

    private function checkSession(): void
    {
        if (!empty($_SESSION['admin_id'])) {
            $this->respond([
                'status'=>'success',
                'logged_in'=>true,
                'admin_nama'=>$_SESSION['admin_nama']
            ]);
        } else {
            $this->respond([
                'status'=>'success',
                'logged_in'=>false
            ]);
        }
    }

    private function getJsonInput()
    {
        $raw = file_get_contents('php://input');
        return json_decode($raw, true);
    }

    private function respond(array $data, int $code = 200): void
    {
        http_response_code($code);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}