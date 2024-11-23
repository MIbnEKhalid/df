<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Directory to scan
$dir = __DIR__ . '/data/';
$videoExtensions = ['mkv', 'mp4', 'avi', 'mov', 'wmv'];

// Validate video file
$videoFile = $_GET['file'] ?? '';
$filePath = $dir . basename($videoFile);

if (is_file($filePath) && in_array(pathinfo($filePath, PATHINFO_EXTENSION), $videoExtensions)) {
    header('Content-Type: video/' . pathinfo($filePath, PATHINFO_EXTENSION));
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
    exit;
} else {
    http_response_code(404);
    echo 'File not found.';
}

