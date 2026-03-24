<?php
// backend/crud/upload_image.php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    http_response_code(403);
    echo json_encode(['success'=>false,'message'=>'Unauthorized']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

if (empty($_FILES['image']) || empty($_POST['region'])) {
    echo json_encode(['success'=>false,'message'=>'Missing image or region']);
    exit;
}

$region = basename($_POST['region']);
$allowedRegions = ['tours-asia.json','tours-europe.json','tours-korjap.json','tours-oceania.json'];
if (!in_array($region, $allowedRegions)) {
    echo json_encode(['success'=>false,'message'=>'Region not allowed']);
    exit;
}

// map region file to folder name (simple heuristic)
$folderMap = [
  'tours-asia.json' => 'asia',
  'tours-europe.json' => 'europe',
  'tours-korjap.json' => 'koreajapan',
  'tours-oceania.json' => 'oceania'
];

$folder = isset($folderMap[$region]) ? $folderMap[$region] : 'misc';

$basePublic = realpath(__DIR__ . '/../../');
$uploadDir = $basePublic . '/assets/data/packages/' . $folder;

// ensure folder exists
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        echo json_encode(['success'=>false,'message'=>'Failed to create upload dir']);
        exit;
    }
}

$file = $_FILES['image'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success'=>false,'message'=>'Upload error code: '.$file['error']]);
    exit;
}

// basic validation
$allowedTypes = ['image/jpeg','image/png','image/webp','image/gif'];
$finfoType = mime_content_type($file['tmp_name']);
if (!in_array($finfoType, $allowedTypes)) {
    echo json_encode(['success'=>false,'message'=>'Invalid file type']);
    exit;
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'tour_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
$destination = $uploadDir . '/' . $filename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    echo json_encode(['success'=>false,'message'=>'Move failed']);
    exit;
}

// build web path relative to public root (assuming public_html is project root)
$webPath = 'assets/data/packages/' . $folder . '/' . $filename;

echo json_encode(['success'=>true,'path'=>$webPath]);