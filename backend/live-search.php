<?php
// =============================================
// backend/live-search.php (Smart Search)
// =============================================
header('Content-Type: application/json');

$q = strtolower(trim($_GET['q'] ?? ''));
if ($q === '') {
  echo json_encode([]);
  exit;
}

// ✅ Load tour data
$tourFiles = [
  '../assets/data/tours-asia.json',
  '../assets/data/tours-korjap.json',
  '../assets/data/tours-europe.json',
  '../assets/data/tours-oceania.json'
];

$matches = [];

// ---------------------------------------------
// 🔍 Search Tours
// ---------------------------------------------
foreach ($tourFiles as $file) {
  if (!file_exists($file)) continue;
  $data = json_decode(file_get_contents($file), true);
  if (!is_array($data)) continue;

  foreach ($data as $tour) {
    $title = strtolower($tour['title'] ?? '');
    $subtitle = strtolower($tour['subtitle'] ?? '');
    $location = strtolower($tour['location'] ?? '');

    if (
      strpos($title, $q) !== false ||
      strpos($subtitle, $q) !== false ||
      strpos($location, $q) !== false
    ) {
      $matches[] = [
        'type' => 'tour',
        'title' => $tour['title'],
        'subtitle' => $tour['subtitle'] ?? '',
        'image' => $tour['image'],
        'link' => $tour['link']
      ];
    }
  }
}

// ---------------------------------------------
// 🌍 Smart Visa Services Dataset
// ---------------------------------------------
$visaServices = [
  // --- Asia ---
  ['title' => 'Korea Visa', 'region' => 'asia', 'keywords' => ['south korea', 'republic of korea', 'seoul'], 'link' => 'visakorea.php', 'image' => '../assets/images/icons/countries/korea.png'],
  ['title' => 'Japan Visa', 'region' => 'asia', 'keywords' => ['nippon', 'tokyo'], 'link' => 'visajapan.php', 'image' => '../assets/images/icons/countries/japan.png'],
  ['title' => 'China Visa', 'region' => 'asia', 'keywords' => ['prc', 'beijing'], 'link' => 'visachina.php', 'image' => '../assets/images/icons/countries/china.png'],
  ['title' => 'India Visa', 'region' => 'asia', 'keywords' => ['india', 'new delhi'], 'link' => 'visaindia.php', 'image' => '../assets/images/icons/countries/india.png'],


  // --- Europe ---
  ['title' => 'Schengen Visa (EU)', 'region' => 'europe', 'keywords' => ['eu', 'schengen', 'multiple europe'], 'link' => 'visaeu.php', 'image' => '../assets/images/icons/countries/eu.png'],
  ['title' => 'Austria Visa', 'region' => 'europe', 'keywords' => [], 'link' => 'visaeu.php?country=austria', 'image' => '../assets/images/icons/countries/austria.png'],
  ['title' => 'Belgium Visa', 'region' => 'europe', 'keywords' => [], 'link' => 'visaeu.php?country=belgium', 'image' => '../assets/images/icons/countries/belgium.png'],
  ['title' => 'France Visa', 'region' => 'europe', 'keywords' => ['paris'], 'link' => 'visaeu.php?country=france', 'image' => '../assets/images/icons/countries/france.png'],
  ['title' => 'Germany Visa', 'region' => 'europe', 'keywords' => ['deutschland', 'berlin'], 'link' => 'visaeu.php?country=germany', 'image' => '../assets/images/icons/countries/germany.png'],
  ['title' => 'Greece Visa', 'region' => 'europe', 'keywords' => ['athens'], 'link' => 'visaeu.php?country=greece', 'image' => '../assets/images/icons/countries/greece.png'],
  ['title' => 'Italy Visa', 'region' => 'europe', 'keywords' => ['rome', 'italia'], 'link' => 'visaeu.php?country=italy', 'image' => '../assets/images/icons/countries/italy.png'],
  ['title' => 'Netherlands Visa', 'region' => 'europe', 'keywords' => ['holland', 'amsterdam'], 'link' => 'visaeu.php?country=netherlands', 'image' => '../assets/images/icons/countries/netherlands.png'],
  ['title' => 'Spain Visa', 'region' => 'europe', 'keywords' => ['madrid', 'barcelona'], 'link' => 'visaeu.php?country=spain', 'image' => '../assets/images/icons/countries/spain.png'],
  ['title' => 'Switzerland Visa', 'region' => 'europe', 'keywords' => ['zurich'], 'link' => 'visaeu.php?country=switzerland', 'image' => '../assets/images/icons/countries/switzerland.png'],
  ['title' => 'United Kingdom Visa (London)', 'region' => 'europe', 'keywords' => ['uk', 'britain', 'england', 'london'], 'link' => 'visauk.php', 'image' => '../assets/images/icons/countries/uk.png'],

  // --- Americas ---
  ['title' => 'United States Visa', 'region' => 'america', 'keywords' => ['usa', 'us', 'america', 'washington', 'new york'], 'link' => 'visaus.php', 'image' => '../assets/images/icons/countries/us.png'],
  ['title' => 'Canada Visa', 'region' => 'america', 'keywords' => ['canadian', 'toronto'], 'link' => 'visacanada.php', 'image' => '../assets/images/icons/countries/canada.png'],

  // --- Oceania ---
  ['title' => 'Australia Visa', 'region' => 'oceania', 'keywords' => ['aussie', 'sydney', 'melbourne'], 'link' => 'visaaus.php', 'image' => '../assets/images/icons/countries/australia.png'],
  ['title' => 'New Zealand Visa', 'region' => 'oceania', 'keywords' => ['nz', 'auckland'], 'link' => 'visanz.php', 'image' => '../assets/images/icons/countries/nz.png'],

  // --- Others ---
  ['title' => 'Turkey Visa', 'region' => 'europe/asia', 'keywords' => ['turkiye', 'istanbul'], 'link' => 'visaturk.php', 'image' => '../assets/images/icons/countries/turkey.png'],
  ['title' => 'South Africa Visa', 'region' => 'africa', 'keywords' => ['cape town'], 'link' => 'visasa.php', 'image' => '../assets/images/icons/countries/south-africa.png']
];

// ---------------------------------------------
// 🔍 Smarter Visa Matching
// ---------------------------------------------
foreach ($visaServices as $visa) {
  $title = strtolower($visa['title']);
  $region = strtolower($visa['region']);
  $keywords = array_map('strtolower', $visa['keywords']);

  // Match query to title, region, or any keyword
  if (
    strpos($title, $q) !== false ||
    strpos($region, $q) !== false ||
    in_array($q, $keywords)
  ) {
    $matches[] = [
      'type' => 'visa',
      'title' => $visa['title'],
      'subtitle' => 'Visa Service',
      'image' => $visa['image'],
      'link' => $visa['link']
    ];
  }
}

// ---------------------------------------------
// 🧩 Return up to 15 matches
// ---------------------------------------------
echo json_encode(array_slice($matches, 0, 15));
