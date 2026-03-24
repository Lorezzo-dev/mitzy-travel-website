<?php
// backend/crud/import_tour_json.php
header("Content-Type: application/json");

$id = $_POST['id'] ?? '';

if (!$id) {
    echo json_encode(["success" => false, "msg" => "Missing tour ID"]);
    exit;
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== 0) {
    echo json_encode(["success" => false, "msg" => "No JSON file uploaded"]);
    exit;
}

// Save path of tour JSON
$tourPath = __DIR__ . "/../../assets/data/tours/{$id}.json";
if (!file_exists($tourPath)) {
    echo json_encode(["success" => false, "msg" => "Tour not found"]);
    exit;
}

// Read uploaded JSON file
$jsonString = file_get_contents($_FILES['file']['tmp_name']);
$newData = json_decode($jsonString, true);

if (!is_array($newData)) {
    echo json_encode(["success" => false, "msg" => "Invalid JSON file"]);
    exit;
}

// Allowed keys — everything else will be removed
$allowedKeys = [
    "title", "details", "duration", "low",
    "flyers", "flyer",
    "travel_dates", "itinerary",
    "inclusions", "exclusions", "extra_details",
    "images", "flight_details", "contact", "region"
];

// Filter keys to only allowed ones
$cleanData = [];
foreach ($allowedKeys as $k) {
    if (isset($newData[$k])) {
        $cleanData[$k] = $newData[$k];
    }
}

// Write to tour JSON
file_put_contents($tourPath, json_encode($cleanData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo json_encode(["success" => true]);
