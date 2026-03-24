<?php
// backend/crud/create_tour.php
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);
$regionFile = $input['region'] ?? '';
$fullId = $input['id'] ?? '';
$title = trim($input['title'] ?? '');

if (!$regionFile || !$fullId || !$title) {
    echo json_encode(["success" => false, "msg" => "Missing data"]);
    exit;
}

// id format: C.delightsofeu
list($prefix, $slug) = explode('.', $fullId);

// region resolve
$map = [
    "A" => "asia",
    "B" => "korea_japan",
    "C" => "europe",
    "D" => "oceania"
];
$regionName = $map[$prefix] ?? "asia";

// Create tour folder (Title-based)
$folder = "../../assets/data/packages/$regionName/$title";
if (!is_dir($folder)) mkdir($folder, 0777, true);

// Create empty tourinfo JSON
$tourJsonPath = "../../assets/data/tours/$fullId.json";
$defaultJson = [
    "title" => $title,
    "details" => "",
    "duration" => "",
    "low" => "",
    "flyer" => "",
    "travel_dates" => [],
    "itinerary" => [],
    "inclusions" => [],
    "exclusions" => [],
    "images" => [],
    "flight_details" => "",
    "contact" => "",
    "extra_details" => []
];

file_put_contents($tourJsonPath, json_encode($defaultJson, JSON_PRETTY_PRINT));

// Update tours list JSON
$listPath = "../../assets/data/$regionFile";
$list = json_decode(file_get_contents($listPath), true);

$list[] = [
    "id" => $slug,
    "title" => $title,
    "subtitle" => "",
    "image" => "", // no image uploaded yet
    "link" => "tourinfo.php?id=$fullId",
    "location" => ""
];

file_put_contents($listPath, json_encode($list, JSON_PRETTY_PRINT));

echo json_encode(["success" => true]);
