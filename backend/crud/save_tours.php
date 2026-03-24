<?php
// backend/crud/save_tours.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    echo json_encode(['success'=>false,'message'=>'Unauthorized']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || empty($input['region']) || !isset($input['tours'])) {
    echo json_encode(['success'=>false,'message'=>'Invalid payload']);
    exit;
}

$region = basename($input['region']); // sanitize filename
$allowed = ['tours-asia.json','tours-europe.json','tours-korjap.json','tours-oceania.json'];
if (!in_array($region, $allowed)) {
    echo json_encode(['success'=>false,'message'=>'Region not allowed']);
    exit;
}

$base = realpath(__DIR__ . '/../../assets/data');
$path = $base . DIRECTORY_SEPARATOR . $region;

// validate tours is array
$tours = $input['tours'];
if (!is_array($tours)) $tours = [];

foreach ($tours as &$t) {
    // minimal sanitization: ensure id and link exist
    if (isset($t['id'])) $t['id'] = preg_replace('/[^a-zA-Z0-9_\-\.]/','', $t['id']);
    if (!isset($t['title'])) $t['title'] = '';
    if (!isset($t['subtitle'])) $t['subtitle'] = '';
    if (!isset($t['image'])) $t['image'] = '';
    if (!isset($t['link'])) $t['link'] = '';
    if (!isset($t['location'])) $t['location'] = '';
}
unset($t);

$json = json_encode($tours, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

if ($json === false) {
    echo json_encode(['success'=>false,'message'=>'Encoding error']);
    exit;
}

// atomic write with lock
$tmp = $path . '.tmp';
if (file_put_contents($tmp, $json) === false) {
    echo json_encode(['success'=>false,'message'=>'Write failed']);
    exit;
}
if (!rename($tmp, $path)) {
    echo json_encode(['success'=>false,'message'=>'Rename failed']);
    exit;
}

echo json_encode(['success'=>true,'message'=>'Saved']);