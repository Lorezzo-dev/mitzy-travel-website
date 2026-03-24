<?php
header("Content-Type: application/json");

// Inputs
$region = $_POST['region'] ?? '';
$id     = $_POST['id'] ?? '';      // e.g. "C.delightsofeu"
$title  = $_POST['title'] ?? '';   // human-friendly title
$file   = $_FILES['image'] ?? null;

if (!$region || !$id || !$file) {
    echo json_encode(["success" => false, "msg" => "Missing region, id or file"]);
    exit;
}

// Normalize helpers
function safe_folder_name($s) {
    // keep letters, numbers, dash, underscore and spaces (we'll also offer underscored)
    $s = trim($s);
    $s = preg_replace('/[^\p{L}\p{N}\-\_\s]+/u', '', $s);
    return $s;
}
function underscored($s) {
    return str_replace(' ', '_', $s);
}
function nospace_lower($s) {
    return strtolower(preg_replace('/\s+/', '', $s));
}

// map region file -> folder name
$regionFolder = match($region) {
    "tours-asia.json"    => "asia",
    "tours-korjap.json"  => "korea_japan",
    "tours-europe.json"  => "europe",
    "tours-oceania.json" => "oceania",
    default => preg_replace('/[^a-z0-9_\-]/', '', strtolower($region)),
};

// where packages live (relative to this script)
$basePackages = __DIR__ . "/../../assets/data/packages/{$regionFolder}";

// ensure base region folder exists
if (!is_dir($basePackages)) mkdir($basePackages, 0775, true);

// Candidate folder names (priority order)
// 1) use full id (canonical) e.g. "C.delightsofeu"
// 2) human title exactly as created (safe chars)
// 3) human title with underscores
// 4) human title no spaces lowercase (your 'oceaniaadventure' case)
// 5) slug part after dot (e.g. "delightsofeu")
// 6) underscored slug
$candidates = [];

$cleanTitle = safe_folder_name($title);
$slug = (strpos($id, '.') !== false) ? explode('.', $id, 2)[1] : $id;

$candidates[] = $id;
if ($cleanTitle !== '') $candidates[] = $cleanTitle;
$candidates[] = underscored($cleanTitle);
$candidates[] = nospace_lower($cleanTitle);
$candidates[] = $slug;
$candidates[] = underscored($slug);

// check existing folders first
$foundFolder = null;
foreach ($candidates as $cand) {
    $cand = trim($cand);
    if ($cand === '') continue;
    $path = $basePackages . "/" . $cand;
    if (is_dir($path)) {
        $foundFolder = $cand;
        break;
    }
}

// If no existing folder found, *do not* try smart guessing: create canonical folder using the ID
if (!$foundFolder) {
    // canonical: use the ID as folder name (safe)
    $folderName = preg_replace('/[^A-Za-z0-9\._-]/', '_', $id);
    $targetFolder = $basePackages . "/" . $folderName;
    if (!is_dir($targetFolder)) {
        mkdir($targetFolder, 0775, true);
    }
    $foundFolder = $folderName;
}

// Now $foundFolder is the folder we will use (either preexisting or newly created canonical id-folder)
$targetFolder = $basePackages . "/" . $foundFolder;

// Validate incoming file extension
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$allowed = ["jpg","jpeg","png","webp"];
if (!in_array($ext, $allowed)) {
    echo json_encode(["success" => false, "msg" => "Invalid file type"]);
    exit;
}

// Save thumbnail as thumbnail.<ext> (overwrites)
$filename = "thumbnail." . $ext;
$savePath = $targetFolder . "/" . $filename;

if (!move_uploaded_file($file['tmp_name'], $savePath)) {
    echo json_encode(["success" => false, "msg" => "Move failed"]);
    exit;
}

// Return the relative path to be stored in JSON (frontend expects this)
$relative = "assets/data/packages/{$regionFolder}/{$foundFolder}/{$filename}";

echo json_encode([
    "success" => true,
    "path" => $relative,
    "used_folder" => $foundFolder
]);
