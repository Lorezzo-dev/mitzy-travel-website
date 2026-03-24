<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/images/icons/mtntlogo.png" type="image/png">
  <link rel="stylesheet" href="assets/css/mtzy.css">
  <title>Mitzy Travel and Tours Inc.</title>
</head>
<body>

<?php include 'components/header.php'; ?>

<section class="page-hero">
  <img src="assets/images/Hero/boracay.png" alt="Local Tours">
  <div class="page-hero-text">
    <h1>Local Tour Packages</h1>
    <p>Discover the beauty of the Philippines</p>
  </div>
</section>

<section class="tours-section">
  <div class="container">
    <h2 class="section-title">Our Local Packages</h2>
    <div class="tours-grid">

      <!-- Example Poster Card -->
      <a href="tour-details.php?id=boracay" class="tour-card">
        <img src="assets/images/posters/boracay-poster.png" alt="Boracay Tour">
        <div class="tour-card-body">
          <h3>Boracay Island Getaway</h3>
          <p>3D2N beach escape with activities & hotel stay.</p>
        </div>
      </a>

      <a href="tour-details.php?id=palawan" class="tour-card">
        <img src="assets/images/posters/palawan-poster.png" alt="Palawan Tour">
        <div class="tour-card-body">
          <h3>Palawan Adventure</h3>
          <p>Explore El Nido, Coron, and underground river tours.</p>
        </div>
      </a>

    </div>
  </div>
</section>

<?php include 'components/footer.php'; ?>
<script src="assets/js/mtzy.js"></script>
</body>
</html>