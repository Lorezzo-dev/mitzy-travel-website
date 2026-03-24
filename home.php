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
<body>


<?php include 'components/header.php'; ?>
<!------------------------------------------------------------------------------------------------>
<main class="home-page">
<section class="hero-carousel">
  <div class="hero-slide">
    <img src="assets/images/HeroCarousel/Korea.png" alt="Korea">
    <img src="assets/images/HeroCarousel/Japan.png" alt="Japan">
    <img src="assets/images/HeroCarousel/Singapore.png" alt="Singapore">
    <img src="assets/images/HeroCarousel/Paris.png" alt="Paris">
    <img src="assets/images/HeroCarousel/Dubai.png" alt="Dubai">
    <img src="assets/images/HeroCarousel/Korea.png" alt="Korea2">
  </div>
  <div class="hero-text">
    <h1>Welcome to Mitzy Travel and Tours Inc.</h1>
    <p>Say Yes to a New Adventure!</p>
  </div>
</section>
<!------------------------------------------------------------------------------------------------>
<section class="main">

  <!-- Text content -->
  <div class="main-content">
    <h1>Mitzy Travel and Tours Inc.</h1>
    <p>
      You’ve arrived at Mitzy Travel & Tours Inc! We are a DOT-certified, PH-based travel agency. We’ve been crafting unforgettable travels since 2014. We offer tour packages, customized itineraries, visa & ticket assistance, & corporate travel services.
    </p>
  </div>
</section>

<!------------------------------------------------------------------------------------------------>
<section class="tour-packages">
<h2>Featured Tour Packages</h2>
<div class="carousel">
  <button class="prev">&#10094;</button>
  <div class="carousel-track">
    <?php
      // Paths to the main directory JSON files
      $jsonFiles = [
        'assets/data/tours-asia.json',
        'assets/data/tours-europe.json',
        'assets/data/tours-korjap.json',
        'assets/data/tours-oceania.json'
      ];

      $allTours = [];

      // Loop through each JSON file and merge their contents
      foreach ($jsonFiles as $file) {
        if (file_exists($file)) {
          $jsonData = json_decode(file_get_contents($file), true);
          if (is_array($jsonData)) {
            $allTours = array_merge($allTours, $jsonData);
          }
        }
      }

      // Shuffle and pick a few (optional — remove array_slice to show all)
      shuffle($allTours);
      $featuredTours = array_slice($allTours, 0, 12); // show 9 random tours

      // Output the images as clickable links
      foreach ($featuredTours as $tour) {
        if (isset($tour['image']) && isset($tour['link'])) {
          echo '<a href="'. htmlspecialchars($tour['link']) .'">
                  <img src="'. htmlspecialchars($tour['image']) .'" alt="'. htmlspecialchars($tour['title'] ?? 'Tour') .'">
                </a>';
        }
      }
    ?>
  </div>
  <button class="next">&#10095;</button>
</div>

<a href="tours.php" class="view-tour-btn">
  View All Tours <img src="assets/images/icons/right-arrow.png" alt="arrow" class="btn-arrow">
</a>
</section>

<!------------------------------------------------------------------------------------------------>
<section class="why-choose">
  <h2 class="why-title">Why Choose Mitzy Travel & Tours Inc.?</h2>
  <div class="why-grid">
    
    <div class="why-item">
      <img src="assets/images/icons/standard.png" alt="Accredited Icon">
      <h3>DOT, PTAA & TBP Accredited</h3>
      <p>Officially accredited by the Department of Tourism and trusted travel associations — your assurance of safe, reliable, and recognized service.</p>
    </div>

    <div class="why-item">
      <img src="assets/images/icons/globe.png" alt="One Stop Icon">
      <h3>One-Stop Travel Solutions</h3>
      <p>From local tours to international packages, flights, hotels, passports, visa and customized tours — everything you need for your trip in one place.</p>
    </div>

    <div class="why-item">
      <img src="assets/images/icons/like.png" alt="Affordable Icon">
      <h3>Affordable & Flexible Packages</h3>
      <p>Custom itineraries designed to fit your budget while ensuring unforgettable experiences every time.</p>
    </div>

    <div class="why-item">
      <img src="assets/images/icons/partnership.png" alt="Trusted Icon">
      <h3>Trusted by Travelers Nationwide</h3>
      <p>A growing list of happy clients and repeat travelers proves our dedication to excellent service.</p>
    </div>

    <div class="why-item">
      <img src="assets/images/icons/telephone.png" alt="Support Icon">
      <h3>Personalized Support</h3>
      <p>Our friendly team is with you every step of the way — from booking to boarding and beyond.</p>
    </div>

      <div class="why-item">
      <img src="assets/images/icons/exclusive.png" alt="Deals Icon">
      <h3>Exclusive Deals & Perks</h3>
      <p>Enjoy special promos, group discounts, and insider travel deals you won’t find anywhere else.</p>
    </div>

  </div>
</section>

<!------------------------------------------------------------------------------------------------>
<!-- ==================== TESTIMONIAL + GROUP TOUR SECTION ==================== -->
<section class="testimonials-section">
  <div class="group-left">
    <h2 class="group-caption">Our Happy Travellers</h2>
    <div class="group-slide">
      <?php
        $groupTourDir = 'assets/images/GroupTours/';
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (is_dir($groupTourDir)) {
          $images = array_filter(scandir($groupTourDir), function($file) use ($allowedExtensions) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            return in_array($ext, $allowedExtensions);
          });

          foreach ($images as $img) {
            $imgPath = htmlspecialchars($groupTourDir . $img);
            $placeName = htmlspecialchars(pathinfo($img, PATHINFO_FILENAME));
            echo "
              <div class='group-slide-item'>
                <img src='$imgPath' alt='$placeName'>
                <div class='place-caption'>$placeName</div>
              </div>
            ";
          }
        } else {
          echo "<p style='color:#555; text-align:center;'>No group tour images found.</p>";
        }
      ?>
    </div>
  </div>

  <div class="testimonial-right">
    <h2 class="testimonial-title">What Our Travellers Say</h2>
    <div class="testimonial-card">
      <p class="testimonial-quote">“Hi everyone. Just got home from our Korea trip Oct. 14–19. Thank you so much Mitzy Travel and Tours for giving us the best tour guides in the name of Ms. Sharmaine Cristobal and Ms. Sharon Shawie Korea. They are the best in looking after our tour needs. Looking forward to our next trip with our tour-mates who became an instant family while we were away from our respective families and the company of Ms. Sharmaine and Ms. Shawie. Thank you, God bless, and more power Mitzy Travel and Tours. This is actually my second time with you. ”</p>
      <p class="testimonial-name">– Mary Grace V Santizas</p>
    </div>


  </div>
</section>



<!-- ==================== QUICK INQUIRY SECTION ==================== -->
<section class="home-contact" id="home-contact">
  <div class="home-contact-inner">
    <h2>Have Questions?</h2>
    <p>Our team is ready to help you plan your next unforgettable journey. Send us a quick message and we’ll get in touch soon!</p>

    <!-- ✅ Updated: Invisible reCAPTCHA v2 Integration -->
    <form action="backend/send_quick_inquiry.php" method="POST" class="home-inquiry-form">
      <div class="form-row">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
      </div>

      <textarea name="message" rows="4" placeholder="Write your message here..." required></textarea>

      <!-- Honeypot (anti-bot) -->
      <input type="text" name="website" style="display:none;">

      <!-- ✅ Invisible reCAPTCHA button -->
      <button 
        class="g-recaptcha home-inquiry-btn"
        data-sitekey="6LfsivkrAAAAAELiWBl-NdiMRFKAOQvB3I1dQnGd" 
        data-callback="onHomeSubmit"
        data-action="submit">
        Send Inquiry
      </button>

      <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </form>

    <!-- ✅ Message feedback section -->
    <?php if (isset($_GET['status'])): ?>
      <div class="form-status <?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?>">
        <?php if ($_GET['status'] === 'success'): ?>
          ✅ Your inquiry has been sent successfully! We’ll get back to you soon.
        <?php else: ?>
          ❌ Sorry, something went wrong. Please try again later.
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>

  <!-- ✅ Define callback specifically for home form -->
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    // --- Smooth scroll if status message appears ---
    const status = document.querySelector('.form-status');
    if (status) {
      status.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // --- ✅ Google Analytics tracking: detect ?status=success redirect ---
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'success') {
      setTimeout(() => {
        if (typeof gtag === 'function') {
          gtag('event', 'form_submit', {
            'event_category': 'Contact',
            'event_label': 'Home Quick Inquiry'
          });
          console.log('GA4 Event Sent: form_submit (Home Quick Inquiry)');
        }
      }, 1000);
    }
  });

  // --- ✅ reCAPTCHA callback (unchanged but improved safety) ---
  function onHomeSubmit(token) {
    const form = document.querySelector('.home-inquiry-form');

    // Add hidden input for reCAPTCHA token (ensures backend receives it)
    const recaptchaInput = document.createElement('input');
    recaptchaInput.type = 'hidden';
    recaptchaInput.name = 'g-recaptcha-response';
    recaptchaInput.value = token;
    form.appendChild(recaptchaInput);

    // Submit the form normally (PHP will redirect to ?status=success)
    form.submit();
  }
  
  </script>
</section>
</main>
<!------------------------------------------------------------------------------------------------>
<?php include 'components/footer.php'; ?>

<script src="assets/js/home.js"></script>

</body>
</html>
