<?php
// backend/crud/upload_tour_media.php
header("Content-Type: application/json");

if (!isset($_FILES['file'])) {
    echo json_encode(["success" => false, "msg" => "No file"]);
    exit;
}

$id = $_POST['id'] ?? '';
$type = $_POST['type'] ?? '';
$region = $_POST['region'] ?? '';
$title = $_POST['title'] ?? '';

if (!$id || !$region || !$title) {
    echo json_encode(["success" => false, "msg" => "Missing parameters"]);
    exit;
}

// region resolve
$map = [
    "A" => "asia",
    "B" => "korea_japan",
    "C" => "europe",
    "D" => "oceania"
];
$regionFolder = $map[strtoupper($id[0])] ?? "asia";

$folder = "../../assets/data/packages/$regionFolder/$title/";
if (!is_dir($folder)) mkdir($folder, 0777, true);

// sanitize filename
$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
$basename = preg_replace("/[^A-Za-z0-9_-]/", "_", pathinfo($_FILES['file']['name'], PATHINFO_FILENAME));

if ($type === "flyer") {
    $filename = "Flyer_" . time() . "." . $ext;
} elseif ($type === "itinerary") {
    $dayIndex = $_POST['day_index'] ?? 0;
    $filename = "Day" . ($dayIndex+1) . "_" . time() . "." . $ext;
} else {
    // gallery
    $filename = $basename . "_" . time() . "." . $ext;
}

$filepath = $folder . $filename;
move_uploaded_file($_FILES['file']['tmp_name'], $filepath);
$relativePath = "assets/data/packages/$regionFolder/$title/$filename";

// update tourinfo
$tourJson = "../../assets/data/tours/$id.json";
$data = json_decode(file_get_contents($tourJson), true);

if ($type === "flyer") {
    $data["flyer"] = $relativePath;
}
elseif ($type === "image") {
    $data["images"][] = $relativePath;
}
elseif ($type === "itinerary") {
    $idx = (int)($_POST['day_index'] ?? 0);
    $data["itinerary"][$idx]["day_image"] = $relativePath;
}

file_put_contents($tourJson, json_encode($data, JSON_PRETTY_PRINT));

echo json_encode(["success" => true, "path" => $relativePath]);
