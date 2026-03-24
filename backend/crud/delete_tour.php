<?php
header("Content-Type: application/json");

// Get input
$input = json_decode(file_get_contents("php://input"), true);
$jsonRegion = $input['region'] ?? '';
$id         = $input['id'] ?? '';

if (!$jsonRegion || !$id) {
    echo json_encode(["success" => false, "msg" => "Missing region or id"]);
    exit;
}

// 1️⃣ Map JSON region → lightweight identifier
$jsonRegionMap = [
    "tours-asia.json"    => "asia",
    "tours-korjap.json"  => "korjap",
    "tours-europe.json"  => "europe",
    "tours-oceania.json" => "oceania"
];

if (!isset($jsonRegionMap[$jsonRegion])) {
    echo json_encode(["success" => false, "msg" => "Invalid region"]);
    exit;
}

$regionKey = $jsonRegionMap[$jsonRegion];

// 2️⃣ Map regionKey → physical folder
$folderRegionMap = [
    "asia"       => "asia",
    "korjap"     => "korea_japan", // IMPORTANT FIX!
    "europe"     => "europe",
    "oceania"    => "oceania"
];

$folderRegion = $folderRegionMap[$regionKey];

// paths
$regionJsonPath = __DIR__ . "/../../assets/data/{$jsonRegion}";
$tourJsonPath   = __DIR__ . "/../../assets/data/tours/{$id}.json";
$packagesBase   = realpath(__DIR__ . "/../../assets/data/packages/{$folderRegion}");

// load region JSON
$list = json_decode(file_get_contents($regionJsonPath), true);
if (!is_array($list)) $list = [];

// find target item
$targetItem = null;
foreach ($list as $item) {
    $linkId = null;
    if (isset($item['link']) && strpos($item['link'], 'id=') !== false) {
        $linkId = explode('id=', $item['link'])[1];
    }
    if ($linkId === $id || $item['id'] === $id) {
        $targetItem = $item;
        break;
    }
}

if (!$targetItem) {
    echo json_encode(["success" => false, "msg" => "Tour not found"]);
    exit;
}

// Extract folder path
$imagePath = $targetItem['image'] ?? '';
$parts = explode("/", $imagePath);
array_pop($parts);
$folderRel = implode("/", $parts);
$tourFolder = realpath(__DIR__ . "/../../" . $folderRel);

// safety: ensure tourFolder inside correct folder
function isInside($base, $path) {
    if (!$base || !$path) return false;
    $base = rtrim($base, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    return strpos($path, $base) === 0;
}

// delete recursive
function deleteDirectoryRecursively($dir) {
    if (!is_dir($dir)) return false;
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === "." || $item === "..") continue;
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) deleteDirectoryRecursively($path);
        else @unlink($path);
    }
    return @rmdir($dir);
}

$folderDeleted = false;
if ($tourFolder && isInside($packagesBase, $tourFolder)) {
    $folderDeleted = deleteDirectoryRecursively($tourFolder);
}

// delete tourinfo json
if (file_exists($tourJsonPath)) @unlink($tourJsonPath);

// remove JSON entry
$list = array_values(array_filter($list, function($it) use ($id) {
    if (isset($it['link']) && strpos($it['link'], "id=") !== false) {
        $linkId = explode("id=", $it['link'])[1];
        return $linkId !== $id;
    }
    return $it['id'] !== $id;
}));

file_put_contents(
    $regionJsonPath,
    json_encode($list, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
);

echo json_encode([
    "success" => true,
    "json_region" => $jsonRegion,
    "folder_region" => $folderRegion,
    "folder_deleted" => $folderDeleted,
    "folder" => $tourFolder,
    "msg" => $folderDeleted
        ? "Tour fully deleted (folder + json)"
        : "JSON updated but folder could not be deleted"
]);
