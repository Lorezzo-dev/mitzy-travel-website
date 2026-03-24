<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/images/icons/mtntlogo.png" type="image/png">
  <link rel="stylesheet" href="assets/css/mtzy.css">
  <link rel="stylesheet" href="assets/css/mobile.css?v=20260203">
  <title>Mitzy Travel and Tours Inc.</title>
</head>
<body class="about-body">

<?php include 'components/header.php'; ?>

<!------------------------------------------------------------------------------------------------>
<!-- 🌸 Global Petal Layer -->
<div class="petal-container"></div>

<!-- MAIN WRAPPER -->
<div class="about-main-wrapper">

  <!-- Hero Section -->
  <section class="page-hero">
    <img src="assets/images/Hero/Japan.png" alt="About Mitzy Travel and Tours">
    <div class="page-hero-text">
      <h1>About Us</h1>
      <p>Experience the world worry-free with Mitzy Travel and Tours Inc.</p>
    </div>
  </section>

  <!-- About Page Content -->
  <main class="about-page">
    <section class="about-section">
      <h2>Our Story</h2>
      <p>Founded on April 24, 2014, Mitzy Travel and Tours Inc. is a full-service travel agency offering both international and local tour packages. With years of dedication and service excellence, we continue to help Filipinos and global travelers explore the world worry-free.</p>
    </section>

    <section class="about-section">
      <h2>Vision</h2>
      <p>To build one-stop travel services, support and assist our clients in all their travel needs, and become one of the largest travel agencies in the Philippines.</p>
    </section>

    <section class="about-section">
      <h2>Mission</h2>
      <ul class="about-servicelist">
        <li>Provide excellence and quality services at the lowest possible cost.</li>
        <li>Develop comprehensive travel products.</li>
        <li>Aggressively solicit new and growing target groups.</li>
        <li>Expand our relationships with airline partners, tour operators, hotels, and resorts.</li>
        <li>Develop better rapport with our clients.</li>
      </ul>
    </section>

    <section class="about-section">
      <h2>Our Services</h2>
      <ul class="about-servicelist">
        <li>International & Local Ticketing</li>
        <li>International & Local Packages</li>
        <li>Cruises</li>
        <li>Travel Insurance</li>
        <li>Meetings, Conventions, Seminars</li>
        <li>Passport Assistance</li>
        <li>Visa Assistance & Consultation</li>
        <li>Company Outings & Team Building</li>
      </ul>
    </section>

    <section class="about-section">
      <h2>Our Clients</h2>
      <p>Over the years, we have served a wide range of clients including:</p>
      <ul class="about-servicelist clients-list">
        <li>Pharmaceutical Companies</li>
        <li>Hospitals</li>
        <li>Government Organizations</li>
        <li>BPOs</li>
        <li>Networking Companies</li>
        <li>Telecommunication Companies</li>
        <li>Schools</li>
        <li>VIP Clients</li>
      </ul>
    </section>
  </main>
</div>

<!------------------------------------------------------------------------------------------------>
<?php include 'components/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const container = document.querySelector(".petal-container");
  if (!container) return;

  const numPetals = 100;

  for (let i = 0; i < numPetals; i++) {
    const petal = document.createElement("div");
    petal.classList.add("petal");
    petal.style.left = Math.random() * 100 + "vw";
    petal.style.animationDelay = Math.random() * 10 + "s";
    petal.style.animationDuration = 12 + Math.random() * 12 + "s";
    petal.style.opacity = 0.6 + Math.random() * 0.4;
    petal.style.transform = `rotate(${Math.random() * 360}deg)`;

    if (Math.random() > 0.5) {
      petal.style.background = "rgba(255, 240, 245, 0.9)";
    }

    container.appendChild(petal);
  }
});
</script>

</body>
</html>
