<?php
// backend/crud/load_tours.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$base = realpath(__DIR__ . '/../../assets/data');
$files = [
    'tours-asia.json',
    'tours-europe.json',
    'tours-korjap.json',
    'tours-oceania.json'
];

$out = [];
foreach ($files as $f) {
    $path = $base . DIRECTORY_SEPARATOR . $f;
    if (file_exists($path)) {
        $txt = file_get_contents($path);
        $arr = json_decode($txt, true);
        if (!is_array($arr)) $arr = [];
        $out[$f] = $arr;
    } else {
        $out[$f] = [];
    }
}

echo json_encode($out, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);