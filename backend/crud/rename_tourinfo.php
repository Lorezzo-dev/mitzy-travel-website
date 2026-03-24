<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$oldId = $data['old_id'] ?? '';
$newId = $data['new_id'] ?? '';

if (!$oldId || !$newId) {
    echo json_encode(['success' => false, 'error' => 'Missing IDs']);
    exit();
}

$base = __DIR__ . "/../../assets/data/tours/";

$oldPath = $base . $oldId . ".json";
$newPath = $base . $newId . ".json";

if (!file_exists($oldPath)) {
    echo json_encode(['success' => false, 'error' => 'Old JSON missing']);
    exit();
}

if (file_exists($newPath)) {
    echo json_encode(['success' => false, 'error' => 'New JSON already exists']);
    exit();
}

rename($oldPath, $newPath);

echo json_encode(['success' => true]);