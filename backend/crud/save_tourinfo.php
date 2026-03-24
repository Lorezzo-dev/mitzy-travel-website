<?php
// backend/crud/save_tourinfo.php
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);

$id   = $input["id"] ?? "";
$data = $input["data"] ?? [];

if (!$id || !is_array($data)) {
    echo json_encode(["success" => false, "msg" => "Invalid input"]);
    exit;
}

$jsonPath = __DIR__ . "/../../assets/data/tours/$id.json";

if (!file_exists($jsonPath)) {
    echo json_encode(["success" => false, "msg" => "Tour JSON not found"]);
    exit;
}

// Load existing tour data
$existing = json_decode(file_get_contents($jsonPath), true);
if (!is_array($existing)) $existing = [];

// Merge (preserve all other keys)
// IMPORTANT: recursive merge so nested arrays remain intact
function deepMerge($old, $new) {
    foreach ($new as $key => $value) {

        // ARRAYS SHOULD OVERWRITE COMPLETELY
        if (is_array($value)) {
            $old[$key] = $value;
            continue;
        }

        // NORMAL SCALARS MERGE
        $old[$key] = $value;
    }
    return $old;
}

$merged = deepMerge($existing, $data);

// Safety: ensure required fields exist
$required = [
  "title" => "",
  "folder" => ($existing["folder"] ?? ""),
  "details" => "",
  "duration" => "",
  "low" => "",
  "flyer" => "",
  "travel_dates" => [],
  "itinerary" => [],
  "inclusions" => [],
  "exclusions" => [],
  "extra_details" => [],
  "images" => [],
  "flight_details" => "",
  "contact" => ""
];

$final = array_merge($required, $merged);

// Save back to file
file_put_contents($jsonPath, json_encode($final, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

echo json_encode(["success" => true]);
