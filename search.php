<?php
// =============================================
// search.php (root folder)
// =============================================
$query = strtolower(trim($_GET['q'] ?? ''));
$results = [];

if ($query !== '') {
  $tourFiles = [
    'assets/data/tours-asia.json',
    'assets/data/tours-korjap.json',
    'assets/data/tours-europe.json',
    'assets/data/tours-oceania.json'
  ];

  foreach ($tourFiles as $file) {
    if (!file_exists($file)) continue;

    $data = json_decode(file_get_contents($file), true);
    if (!is_array($data)) continue;

    foreach ($data as $tour) {
      $title = strtolower($tour['title'] ?? '');
      $subtitle = strtolower($tour['subtitle'] ?? '');
      $location = strtolower($tour['location'] ?? '');

      if (
        strpos($title, $query) !== false ||
        strpos($subtitle, $query) !== false ||
        strpos($location, $query) !== false
      ) {
        $results[] = $tour;
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/images/icons/mtntlogo.png" type="image/png">
  <link rel="stylesheet" href="assets/css/mtzy.css">
  <link rel="stylesheet" href="assets/css/mobile.css?v=20260219">
  <title>Search Results | Mitzy Travel and Tours</title>
  <meta name="description" content="Search for tours, destinations, and visa services at Mitzy Travel and Tours. Find your dream getaway.">
</head>
<body>

<?php include 'components/header.php'; ?>

<!-- ===== HERO SECTION ===== -->
<section class="page-hero">
  <img src="assets/images/Hero/Airport.png" alt="Search Results">
  <div class="page-hero-text">
    <h1>Search Results</h1>
    <p>Find your perfect destination</p>
  </div>
</section>

<!-- ===== RESULTS SECTION ===== -->
<main class="search-results-page">
  <div class="container">
    <?php if ($query === ''): ?>
      <h2 class="section-title">Please enter a search term</h2>
      <p class="no-results">Use the search bar above to find tours or visa services.</p>

    <?php elseif (count($results) > 0): ?>
      <h2 class="section-title">Results for “<?= htmlspecialchars($query) ?>”</h2>
      <div class="search-results-grid">
        <?php foreach ($results as $tour): ?>
          <div class="search-card">
            <img src="<?= htmlspecialchars($tour['image']) ?>" alt="<?= htmlspecialchars($tour['title']) ?>">
            <div class="search-card-body">
              <h3><?= htmlspecialchars($tour['title']) ?></h3>
              <?php if (!empty($tour['subtitle'])): ?>
                <p><?= htmlspecialchars($tour['subtitle']) ?></p>
              <?php endif; ?>
              <?php if (!empty($tour['location'])): ?>
                <p>📍 <?= htmlspecialchars($tour['location']) ?></p>
              <?php endif; ?>
              <a href="<?= htmlspecialchars($tour['link'] ?? ('tourinfo.php?id=' . $tour['id'])) ?>" class="btn">View Details</a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="no-results">
        <h2>No results found for “<?= htmlspecialchars($query) ?>”</h2>
        <p>Try using different keywords or check our tours section.</p>
        <a href="tours.php" class="try-again">Back to Tours</a>
      </div>
    <?php endif; ?>
  </div>
</main>

<?php include 'components/footer.php'; ?>

</body>
</html>
