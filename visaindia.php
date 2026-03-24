<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/images/icons/mtntlogo.png" type="image/png">
  <link rel="stylesheet" href="assets/css/mtzy.css">
    <link rel="stylesheet" href="assets/css/mobile.css?v=20260203">
  <title>India Visa Requirements – Mitzy Travel and Tours</title>
</head>

<body class="visa-page">

<?php include 'components/header.php'; ?>

<!-- Hero -->
<section class="page-hero">
  <img src="assets/images/Hero/tajmahal.png" alt="India Hero">
  <div class="page-hero-text">
    <h1>Indian Visa Requirements</h1>
  </div>
</section>

<iframe id="pdfFrame" src="" style="display:none;"></iframe>

<!-- INTRO -->
<section id="visaIntro">
  <h2>India Visa Application for Filipino Travelers</h2>
  <p>
    Filipino travelers visiting the Republic of India for tourism, business, cultural visits, spiritual retreats, 
    or medical purposes are required to secure an Indian Visa before departure. Our agency will guide you through 
    the entire visa application process to ensure a smooth and successful submission.
  </p>

  <div class="visa-countries">
    <h3>Country this visa applies to</h3>
    <p>
      This visa allows entry into the Republic of India, including major destinations such as New Delhi, Mumbai, 
      Jaipur, Agra, Goa, Kerala, Varanasi, Bangalore, Chennai, and more.
    </p>

    <div class="country-grid">
      <a href="visaindia.php?country=india" class="country-item">
        <img src="assets/images/icons/countries/india.png" alt="India">
        Republic of India
      </a>
    </div>
  </div>
</section>

<!-- TABS -->
<section id="visaTabsSection">
  <div class="tab-buttons">
    <button class="tab-button active" data-tab="checklist">Checklist & Requirements</button>
    <button class="tab-button" data-tab="guidelines">Visa Guidelines</button>
    <button class="tab-button" data-tab="contact">Contact</button>
    <button class="tab-button" data-tab="form">Pre-Application Form</button>
  </div>

<!-- =============== TAB 1 — CHECKLIST =============== -->
<div id="checklist" class="tab-content mini-section active">

  <h1>Downloadable Forms</h1>
  <ol>
    <li><a href="assets/docs/CHECKLIST_INDIA.pdf" target="_blank">India Visa Checklist (PDF)</a></li>
  </ol>

  <h1>India Visa Requirements</h1>

  <h2>Documents required for India Visa Application:</h2>
  <ul>
    <li><p>Valid Passport (minimum validity of 6 months, with 2 blank pages)</p></li>
    <li><p>Two (2) recent passport-sized photos (white background, formal attire)</p></li>
    <li><p>Accomplished Visa Application Form</p></li>
    <li><p>Copy of flight itinerary (not required to be ticketed)</p></li>
    <li><p>Copy of hotel reservation / sponsor invitation letter</p></li>
    <li><p>Bank Certificate (must include current balance & average daily balance)</p></li>
    <li><p>Latest 6 months Bank Statements</p></li>
    <li><p>PSA Birth Certificate</p></li>
    <li><p>PSA Marriage Certificate (if applicable)</p></li>
  </ul>

  <h2>For Employed Applicants</h2>
  <ul>
    <li><p>Certificate of Employment (COE)</p></li>
    <li><p>Company ID (photocopy)</p></li>
    <li><p>Approved Leave of Absence</p></li>
    <li><p>Latest Income Tax Return (ITR) – BIR Form 2316</p></li>
  </ul>

  <h2>For Business Owners / Self-Employed</h2>
  <ul>
    <li><p>DTI or SEC Registration</p></li>
    <li><p>Mayor’s Business Permit</p></li>
    <li><p>BIR Certificate of Registration (BIR 2303)</p></li>
    <li><p>Latest ITR (BIR 1701/1702)</p></li>
  </ul>

  <h2>For Students</h2>
  <ul>
    <li><p>School ID (photocopy)</p></li>
    <li><p>School Certificate of Enrollment (original)</p></li>
    <li><p>Affidavit of Support (if sponsored)</p></li>
  </ul>
</div>

<!-- =============== TAB 2 — GUIDELINES =============== -->
<div id="guidelines" class="tab-content">
  <h3>India Visa Guidelines</h3>

  <ul class="mini-section">
    <li><b>Processing time:</b> 7–15 working days.</li>
    <li>Ensure all documents are complete before submission.</li>
    <li>Incomplete or incorrect requirements may delay or reject your application.</li>
    <li>Do not finalize flight/hotel bookings until visa approval.</li>
    <li>Personal appearance may be required depending on visa type.</li>
  </ul>
</div>

<!-- =============== TAB 3 — PRE-APPLICATION FORM =============== -->
<div id="form" class="tab-content">
  <h3>India Visa Pre-Application Form</h3>
  <p class="form-intro">
    Please fill out the form below to begin your India visa application process.
  </p>
  <hr>

  <form id="visaPreApp" action="backend/send_visa_application.php" method="POST" enctype="multipart/form-data" novalidate>

    <input type="hidden" name="visa_type" value="India Visa">

    <!-- Progress -->
    <div id="formProgress" style="margin-bottom:20px; font-weight:600; color:var(--pink-dark2);">
      Step <span id="currentStep">1</span> of 4
    </div>

    <!-- STEP 1 -->
    <div class="form-step active" id="step1">
      <h3>Section 1: Personal Information</h3>
      <div class="form-grid">
        <div class="form-group"><label for="given_name">Given Name:</label><input type="text" id="given_name" name="given_name" required></div>
        <div class="form-group"><label for="middle_name">Middle Name:</label><input type="text" id="middle_name" name="middle_name"></div>
        <div class="form-group"><label for="surname">Surname:</label><input type="text" id="surname" name="surname" required></div>
        <div class="form-group full"><label for="home_address">Home Address:</label><input type="text" id="home_address" name="home_address" required></div>
        <div class="form-group"><label for="date_of_birth">Date of Birth:</label><input type="date" id="date_of_birth" name="date_of_birth" required></div>
        <div class="form-group">
          <label for="civil_status">Civil Status:</label>
          <select id="civil_status" name="civil_status" required>
            <option value="">-- Select --</option>
            <option value="single">Single</option>
            <option value="married">Married</option>
            <option value="widowed">Widowed</option>
            <option value="separated">Separated</option>
          </select>
        </div>
        <div class="form-group"><label for="place_of_birth">Place of Birth:</label><input type="text" id="place_of_birth" name="place_of_birth" required></div>
        <div class="form-group"><label for="home_landline">Home Landline #:</label><input type="text" id="home_landline" name="home_landline"></div>
        <div class="form-group"><label for="mobile">Mobile #:</label><input type="text" id="mobile" name="mobile" required></div>
        <div class="form-group"><label for="personal_email">Personal Email:</label><input type="email" id="personal_email" name="personal_email" required></div>
      </div>

      <div class="form-floating-buttons">
        <button type="button" class="circle-btn" onclick="nextStep(2)"><img src="assets/images/icons/right-arroww.png"></button>
      </div>
    </div>

    <!-- STEP 2 -->
    <div class="form-step" id="step2">
      <h3>Section 2: Employment / School</h3>
      <div class="form-grid">
        <div class="form-group"><label>Company / School Name:</label><input type="text" name="company_name"></div>
        <div class="form-group"><label>Occupation:</label><input type="text" name="occupation"></div>
        <div class="form-group full"><label>Company / School Address:</label><input type="text" name="company_address"></div>
        <div class="form-group"><label>Company / School Email:</label><input type="email" name="company_email"></div>
        <div class="form-group"><label>Company Landline:</label><input type="text" name="company_landline"></div>
      </div>

      <div class="form-floating-buttons">
        <button type="button" class="circle-btn" onclick="prevStep(1)"><img src="assets/images/icons/left-arrow.png"></button>
        <button type="button" class="circle-btn" onclick="nextStep(3)"><img src="assets/images/icons/right-arroww.png"></button>
      </div>
    </div>

    <!-- STEP 3 -->
    <div class="form-step" id="step3">
      <h3>Section 3: Passport Details</h3>
      <div class="form-grid">
        <div class="form-group"><label>Passport #:</label><input type="text" name="passport_number" required></div>
        <div class="form-group"><label>Date of Issue:</label><input type="date" name="date_of_issue"></div>
        <div class="form-group"><label>Valid Until:</label><input type="date" name="valid_until"></div>
      </div>

      <!-- Travel Cost -->
      <div class="form-group full" style="margin-top:18px;">
        <h4>Cost of Travel Covered By:</h4>
        <div class="travel-cost-options">
          <label><input type="radio" name="travel_cost" value="Myself"> Myself</label>

          <label style="display:flex; align-items:center; gap:8px;">
            <input type="radio" name="travel_cost" value="Host/Organization"> Host / Organization
            <input type="text" id="travel_host" name="travel_host" placeholder="Host/Organization Name" style="flex:1; display:none;">
          </label>

          <label style="display:flex; align-items:center; gap:8px;">
            <input type="radio" name="travel_cost" value="Others"> Others
            <input type="text" id="travel_others" name="travel_others" placeholder="Specify" style="flex:1; display:none;">
          </label>
        </div>
      </div>

      <div class="form-floating-buttons">
        <button type="button" class="circle-btn" onclick="prevStep(2)"><img src="assets/images/icons/left-arrow.png"></button>
        <button type="button" class="circle-btn" onclick="showReview()"><img src="assets/images/icons/right-arroww.png"></button>
      </div>
    </div>

    <!-- STEP 4 — Review -->
    <div class="form-step" id="step4">
      <h3>Section 4: Review Your Details</h3>
      <p>Please review all information before submitting.</p>
      <div id="reviewContent" style="background:#f9f9f9; padding:20px; border-radius:10px;"></div>

      <div class="form-floating-buttons">
        <button type="button" class="circle-btn" onclick="prevStep(3)"><img src="assets/images/icons/left-arrow.png"></button>

        <!-- reCAPTCHA -->
        <button class="circle-btn g-recaptcha"
          data-sitekey="6LfsivkrAAAAAELiWBl-NdiMRFKAOQvB3I1dQnGd"
          data-callback="onVisaApplicationSubmit"
          data-action="submit"
          type="button">
          <img src="assets/images/icons/check-mark.png">
        </button>
      </div>
    </div>

    <!-- Honeypot -->
    <input type="text" name="website" style="display:none;">
  </form>
</div>

<!-- =============== TAB 4 — CONTACT =============== -->
<div id="contact" class="tab-content">
  <h3>Contact Our Visa Team</h3>
  <p>Need assistance with your India visa application? Send us a message below.</p>

  <section class="contact-form" id="visa-contact-form">
    <h2>✉️ Get in Touch</h2>

    <form action="backend/send_email_visa.php" method="POST" class="visa-inquiry-form">
      <input type="hidden" name="visa_type" value="India Visa">
      <input type="hidden" name="current_page" id="current_page">

      <div class="form-row">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" name="name" required>
        </div>
        <div class="form-group">
          <label>Email Address</label>
          <input type="email" name="email" required>
        </div>
      </div>

      <div class="form-group">
        <label>Message</label>
        <textarea name="message" rows="6" required></textarea>
      </div>

      <input type="text" name="website" style="display:none;">

      <button
        class="btn-submit g-recaptcha"
        data-sitekey="6LfsivkrAAAAAELiWBl-NdiMRFKAOQvB3I1dQnGd"
        data-callback="onVisaSubmit"
        data-action="submit"
        type="button">
        Send Message
      </button>
    </form>
  </section>
</div>
</section>

<!-- Success/Error Popups -->
<?php if (isset($_GET['status'])): ?>
  <div id="popup-message" class="popup-message <?= $_GET['status'] === 'success' ? 'success' : 'error'; ?>">
    <div class="popup-content">
      <?php if ($_GET['status'] === 'success'): ?>
        <h3>✅ Message Sent</h3>
        <p>We’ll reply shortly. Thank you!</p>
      <?php else: ?>
        <h3>❌ Something Went Wrong</h3>
        <p>Please try again.</p>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<?php include 'components/footer.php'; ?>

<!-- JS -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="assets/js/visa-form.js"></script>

<script>
/* Tabs */
document.querySelectorAll('#visaTabsSection .tab-button').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('#visaTabsSection .tab-button').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('#visaTabsSection .tab-content').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById(btn.dataset.tab).classList.add('active');
  });
});

/* Load current page URL into hidden field */
document.addEventListener("DOMContentLoaded", () => {
  const pageField = document.getElementById("current_page");
  if (pageField) pageField.value = window.location.href;
});

/* Popup close animation */
document.addEventListener("DOMContentLoaded", () => {
  const popup = document.getElementById("popup-message");
  if (popup) {
    setTimeout(() => {
      popup.classList.add("fade-out");
      setTimeout(() => popup.remove(), 400);
    }, 4000);

    popup.addEventListener("click", () => popup.remove());
  }
});

/* reCAPTCHA Submit */
function onVisaSubmit(token) {
  const form = document.querySelector('.visa-inquiry-form');
  if (form) {
    const recaptchaInput = document.createElement('input');
    recaptchaInput.type = 'hidden';
    recaptchaInput.name = 'g-recaptcha-response';
    recaptchaInput.value = token;
    form.appendChild(recaptchaInput);
    form.submit();
  }
}

function onVisaApplicationSubmit(token) {
  const form = document.querySelector('#visaPreApp');
  if (form) {
    const recaptchaInput = document.createElement('input');
    recaptchaInput.type = 'hidden';
    recaptchaInput.name = 'g-recaptcha-response';
    recaptchaInput.value = token;
    form.appendChild(recaptchaInput);
    form.submit();
  }
}
</script>

</body>
</html>
