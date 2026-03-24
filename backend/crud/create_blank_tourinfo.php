<?php
// backend/crud/create_blank_tourinfo.php
header('Content-Type: application/json; charset=utf-8');
$in = json_decode(file_get_contents('php://input'), true);
if (!$in || empty($in['id'])) {
  echo json_encode(['success' => false, 'msg' => 'No id provided']);
  exit;
}
$id = basename($in['id']); // just in case

// Map prefix to package region folder
$regionMap = [
  'A' => 'asia',
  'B' => 'korea_japan',
  'C' => 'europe',
  'D' => 'oceania'
];

$prefix = strtoupper(explode('.', $id)[0] ?? '');
$region = $regionMap[$prefix] ?? 'asia';

// Default blank tour data (includes folder)
$defaultTitle = 'New Tour Title';
$folderName = preg_replace('/[^A-Za-z0-9 _-]/', '', $defaultTitle);
$blank = [
  'title' => $defaultTitle,
  'folder' => $folderName,
  'details' => '',
  'duration' => '',
  'low' => '',
  'flyer' => '',
  'travel_dates' => [],
  'itinerary' => [],
  'inclusions' => [],
  'exclusions' => [],
  'extra_details' => [],
  'images' => [],
  'flight_details' => '',
  'contact' => ''
];

// Save JSON in assets/data/tours/{id}.json
$toursDir = __DIR__ . "/../../assets/data/tours";
if (!is_dir($toursDir)) mkdir($toursDir, 0777, true);
$jsonPath = $toursDir . "/$id.json";

file_put_contents($jsonPath, json_encode($blank, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

// Create package folder for new tour
$packageFolder = __DIR__ . "/../../assets/data/packages/$region/$folderName";
if (!is_dir($packageFolder)) {
  mkdir($packageFolder, 0777, true);
  // create subfolders for clarity
  @mkdir($packageFolder . '/images', 0777, true);
  @mkdir($packageFolder . '/itinerary', 0777, true);
}

echo json_encode(['success' => true, 'id' => $id, 'folder' => "$region/$folderName"]);
