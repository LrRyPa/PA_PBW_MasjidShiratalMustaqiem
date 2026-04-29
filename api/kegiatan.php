<?php

header("Content-Type: application/json");

require_once __DIR__ . '/../controllers/KegiatanController.php';

$controller = new KegiatanController();
$controller->handle();