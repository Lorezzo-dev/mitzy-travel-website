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

<body class="visa-page">

<?php include 'components/header.php'; ?>

<!-- Hero -->
<section class="page-hero">
  <img src="assets/images/Hero/albertacanada.png" alt="Canada Hero">
  <div class="page-hero-text">
    <h1>Canadian Visa Requirements</h1>
  </div>
</section>



<iframe id="pdfFrame" src="" style="display:none;"></iframe>

<!-- Short Intro Section -->
<section id="visaIntro">
  <h2>Canada Visa Application for Filipino Travelers</h2>
  <p>
    Filipino citizens planning to visit Canada are required to obtain a Canadian Visitor Visa, also known as a Temporary Resident Visa (TRV), before traveling. 
    This visa allows short-term stays for purposes such as tourism, visiting relatives or friends, business meetings, or short-term study. 
    Our agency is here to guide you through every step — review the guidelines, fill in the pre-application form below, 
    and prepare the required documents for a smooth and successful visa application process.
  </p>
  
  <div class="visa-countries">
    <h3>Country this visa applies to</h3>
    <p>
      A Canadian visa grants entry into Canada, including all provinces and territories such as Ontario, British Columbia, Alberta, Quebec, 
      and others across the country. Please note that travelers transiting through Canada may also need a visa or an Electronic Travel Authorization (eTA), depending on their itinerary.
    </p>

    <div class="country-grid">
      <a href="visacanada.php?country=canada" class="country-item">
        <img src="assets/images/icons/countries/canada.png" alt="Canada"> Canada
      </a>
    </div>
  </div>
</section>

<!-- Tabs Section -->
<section id="visaTabsSection">
  <div class="tab-buttons">
    <button class="tab-button active" data-tab="checklist">Checklist & Requirements</button>
    <button class="tab-button" data-tab="guidelines">Visa Guidelines</button>
    <button class="tab-button" data-tab="contact">Contact</button>
    <button class="tab-button" data-tab="form">Pre-Application Form</button>
  </div>
  
 <!-- =============== TAB 1 =============== -->
  
  <div id="guidelines" class="tab-content">
    <h3>Pre-Visa Application Guidelines</h3>
    <ul class="mini-section">
      <li><b>Attention:</b> Due to high volume of applications, visa processing may take several weeks. Apply early.</li>
      <li>Applications must be submitted by the applicant or an authorized representative with proper authorization letter.</li>
      <li>Ensure all documents are complete before visiting any office for submission.</li>
      <li>Missing or incomplete requirements may result in rejection or delays.</li>
      <li>Do not finalize flight or hotel bookings until visa approval.</li>
    </ul>
    <ul>
    <b>Other attachments needed for visa application: </b>
         <li>Copy of Hotel Confirmation </li>
         <li>Copy of Itinerary</li>
         <li>Copy of Ticket/Airline Booking</li>
         <li>Copy of Travel Insurance covered the whole travel period</li>
    </ul>
    <ol>
        <li>ALL PHOTOCOPIES SHOULD BE PRINTED IN A4 SIZED PAPER</li>
        <li>IF HAVE PRIOR TRAVEL PLEASE INFORM US AND SUBMIT A COPY OF YOUR ETICKETS </li>
        <li>PERSONAL APPEARANCE </li>
    </ol>
  </div>
  </div>
  
 <!-- =============== TAB 2 =============== -->
  <div id="checklist" class="tab-content mini-section active">
  <h1>Downloadable Forms</h1>
  <ol>
    <li><a href="assets/docs/Canadian_Tourist_Visa_requirements.pdf" target="_blank">Canadian Tourist Requirements</a></li>
  </ol>
    <h1>Canadian Embassy Tourist Visa</h1>
    <ul>
        <li><p>Application forms (IMM5257-Visitor visa form; IMM5654E-Family info form)</p></li>
        <li><p>Supplementary information form</p></li>
        <li><p>Consent form</p></li>
        <li><p>Two (2) passport size photos, with white background, in formal wear</p></li>
        <li><p>Bank Certificate</p></li>
        <li><p>Bank statement for the last 3 months</p></li>
        <li><p>Proof of assets (copy of land titles)</p></li>
        <li><p>Original Employment Certificate</p></li>
        <li><p>Copy of ITR (2316- <i>for employed /</i> 1701- <i>for self-employed or businessman Page 1-4)</i></p></li>
        <li><p>Business Registration – DTI/COR (if self-employed)</p></li>
        <li><p>For Students: School Certificate and copy of school ID</p></li>
        <li><p>PSA Birth Certificate</p></li>
        <li><p>PSA Marriage Contract</p></li>
    </ul>
       <h3>Additional Requirements</h3>
    <h2>If you are going to visit Family or friends</h2>
    <ul>
        <li><p>INVITATION LETTER</p></li>
        <li><p>Copy of citizenship or immigration status document (to demonstrate that they are legally staying in Canada) of the person inviting you. </p></li>
        <li><p>For the person who will pay for your trip, please supply supporting financial documents to prove that they can afford to support this visit, including reliable and verifiable information from a third-party source of your host’s income, employment letter and proof of funds</p></li>
    </ul>
    <h2>If you are going on a business visit, you may provide:</h2>
    <ul>
        <li><p>INVITATION LETTER from a Canadian company</p></li>
        <li><p>Guarantee Letter from the local Company </p></li>
    </ul>
    <ul>
        <p><b>For Incentive Trips:</b></p>
        <li><p>Guarantee Letter of the Company</p></li>
        <li><p>Bank Certificate and Statement for the last 3 months</p></li>
        <li><p>ITR(1701), Business Permit, SEC page 1-4</p></li>
    </ul>
  </div>

<!-- =============== TAB 3 =============== -->
<div id="form" class="tab-content">
  <h3>Canada Visa Pre-Application Form</h3>
  <p class="form-intro">
    You can now fill out our Passenger Information form directly below.
  </p>
<hr>
<form id="visaPreApp" action="backend/send_visa_application.php" method="POST" enctype="multipart/form-data" novalidate>
  <input type="hidden" name="visa_type" value="Canada Visa">

  <!-- Progress -->
  <div id="formProgress" style="margin-bottom: 20px; font-weight:600; color: var(--pink-dark2);">
    Step <span id="currentStep">1</span> of 4
  </div>

  <!-- STEP 1 -->
  <div class="form-step active" id="step1">

    <h3>Section 1: Personal Information</h3>
    <div class="form-grid">
      <div class="form-group"><label for="given_name">Given Name:</label><input type="text" id="given_name" name="given_name" placeholder="Enter Name" required></div>
      <div class="form-group"><label for="middle_name">Middle Name:</label><input type="text" id="middle_name" name="middle_name" placeholder="Enter Middlename"></div>
      <div class="form-group"><label for="surname">Surname:</label><input type="text" id="surname" name="surname" placeholder="Enter Surname" required></div>
      <div class="form-group full"><label for="home_address">Home Address:</label><input type="text" id="home_address" name="home_address" placeholder="Enter Your Home Address" required></div>
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
      <div class="form-group"><label for="place_of_birth">Place of Birth (based on passport):</label><input type="text" id="place_of_birth" name="place_of_birth" placeholder="Enter Your Place of Birth" required></div>
      <div class="form-group"><label for="home_landline">Home Landline #:</label><input type="text" id="home_landline" name="home_landline" placeholder="Enter Landline No."></div>
      <div class="form-group"><label for="mobile">Mobile #:</label><input type="text" id="mobile" name="mobile" placeholder="Enter Mobile No." required></div>
      <div class="form-group"><label for="personal_email">Personal Email Address:</label><input type="email" id="personal_email" name="personal_email" placeholder="Enter Your Personal Email" required></div>
    </div>



    <div class="form-floating-buttons">
      <button type="button" class="circle-btn" onclick="nextStep(2)"><img src="assets/images/icons/right-arroww.png" alt="Next"></button>
    </div>
  </div>

  <!-- STEP 2 -->
  <div class="form-step" id="step2">
    <h3>Section 2: Employment / School Information</h3>
    <div class="form-grid">
      <div class="form-group"><label for="company_name">Company Name / School Name:</label><input type="text" id="company_name" name="company_name" placeholder="Enter Your Organization Name"></div>
      <div class="form-group"><label for="occupation">Occupation:</label><input type="text" id="occupation" name="occupation" placeholder="Enter Your Occupation"></div>
      <div class="form-group full"><label for="company_address">Company / School Address:</label><input type="text" id="company_address" name="company_address" placeholder="Enter Your Organization's Address"></div>
      <div class="form-group"><label for="company_email">Company / School Email Address:</label><input type="email" id="company_email" name="company_email" placeholder="Enter Your Organization Email"></div>
      <div class="form-group"><label for="company_landline">Company / School Landline #:</label><input type="text" id="company_landline" name="company_landline" placeholder="Enter Your Organization's No."></div>
    </div>
    <div class="form-floating-buttons">
      <button type="button" class="circle-btn" onclick="prevStep(1)"><img src="assets/images/icons/left-arrow.png" alt="Back"></button>
      <button type="button" class="circle-btn" onclick="nextStep(3)"><img src="assets/images/icons/right-arroww.png" alt="Next"></button>
    </div>
  </div>

  <!-- STEP 3 -->
  <div class="form-step" id="step3">
    <h3>Section 3: Passport & Visa Details</h3>
    <div class="form-grid">
      <div class="form-group"><label for="passport_number">Passport #:</label><input type="text" id="passport_number" name="passport_number" placeholder="Enter Your Passport No." required></div>
      <div class="form-group"><label for="date_of_issue">Date of Issue:</label><input type="date" id="date_of_issue" name="date_of_issue"></div>
      <div class="form-group"><label for="valid_until">Valid Until:</label><input type="date" id="valid_until" name="valid_until"></div>
    </div>


    <!-- cost of travel -->
    <div class="form-group full" style="margin-top:18px;">
      <h4 style="margin-bottom:10px;">Cost of Travel Covered By:</h4>
      <div class="travel-cost-options">
        <label><input type="radio" name="travel_cost" value="Myself"> Myself</label>

        <label style="display:flex; align-items:center; gap:8px;">
          <input type="radio" name="travel_cost" value="Host/Organization"> Host / Organization:
          <input type="text" id="travel_host" name="travel_host" placeholder="Enter host/organization name" style="flex:1; min-width:220px; display:none;">
        </label>

        <label style="display:flex; align-items:center; gap:8px;">
          <input type="radio" name="travel_cost" value="Others"> Others (Specify):
          <input type="text" id="travel_others" name="travel_others" placeholder="Enter name of sponsor" style="flex:1; min-width:220px; display:none;">
        </label>
      </div>
    </div>

    <div class="form-floating-buttons">
      <button type="button" class="circle-btn" onclick="prevStep(2)"><img src="assets/images/icons/left-arrow.png" alt="Back"></button>
      <button type="button" class="circle-btn" onclick="showReview()"><img src="assets/images/icons/right-arroww.png" alt="Next"></button>
    </div>
  </div>

  <!-- STEP 4 REVIEW -->
  <div class="form-step" id="step4">
    <h3>Section 4: Review Your Details</h3>
    <p>Please review all the details below before submitting your pre-application.</p>
    <div id="reviewContent" style="background:#f9f9f9; border:1px solid #eee; border-radius:10px; padding:20px; line-height:1.7;"></div>

    <div class="form-floating-buttons">
      <button type="button" class="circle-btn" onclick="prevStep(3)"><img src="assets/images/icons/left-arrow.png" alt="Back"></button>

      <!-- reCAPTCHA submit button --- callback should be `onVisaApplicationSubmit` defined in JS -->
      <button class="circle-btn g-recaptcha"
              data-sitekey="6LfsivkrAAAAAELiWBl-NdiMRFKAOQvB3I1dQnGd"
              data-callback="onVisaApplicationSubmit"
              data-action="submit"
              type="button"
              title="Submit Application">
        <img src="assets/images/icons/check-mark.png" alt="Submit">
      </button>
    </div>
  </div>

  <!-- Honeypot -->
  <input type="text" name="website" style="display:none;">
</form>


</div>




 <!-- =============== TAB 4 =============== -->
<div id="contact" class="tab-content">
  <h3>Contact our Visa Assistance Team</h3>


  <p>If you have any inquiries or need help with your Canada visa process, please send us a message below.</p>

  <!-- =================== CONTACT FORM =================== -->
  <section class="contact-form" id="visa-contact-form">
    <h2>✉️ Get in Touch</h2>
    <p class="form-intro">
      Have questions or need assistance with your visa application? Send us a message and we’ll get back to you as soon as possible.
    </p>


<form action="backend/send_email_visa.php" method="POST" class="visa-inquiry-form">
  <input type="hidden" name="visa_type" value="Canada Visa">
  <input type="hidden" name="current_page" id="current_page">

  <div class="form-row">
    <div class="form-group">
      <label for="name"><i class="fa-solid fa-user"></i> Full Name</label>
      <input type="text" id="name" name="name" placeholder="Your full name" required>
    </div>
    <div class="form-group">
      <label for="email"><i class="fa-solid fa-envelope"></i> Email Address</label>
      <input type="email" id="email" name="email" placeholder="you@example.com" required>
    </div>
  </div>

  <div class="form-group">
    <label for="message"><i class="fa-solid fa-comment-dots"></i> Message</label>
    <textarea id="message" name="message" rows="6" placeholder="Write your message here..." required></textarea>
  </div>

  <!-- ✅ Honeypot (hidden from real users) -->
  <input type="text" name="website" style="display:none;">

  <!-- ✅ Invisible reCAPTCHA button -->
<button
  class="btn-submit g-recaptcha"
  data-sitekey="6LfsivkrAAAAAELiWBl-NdiMRFKAOQvB3I1dQnGd"
  data-callback="onVisaSubmit"
  data-action="submit"
  type="button">
  <i class="fa-solid fa-paper-plane"></i> Send Message
</button>
  <!-- ✅ reCAPTCHA script -->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</form>
  </section>
</div>
</section>


<?php if (isset($_GET['status'])): ?>
  <div id="popup-message"
       class="popup-message <?php echo $_GET['status'] === 'success' ? 'success' : 'error'; ?>">
    <div class="popup-content">
      <?php if ($_GET['status'] === 'success'): ?>
        <h3>✅ Message Sent</h3>
        <p>We’ll get back to you soon. Thank you!</p>
      <?php else: ?>
        <h3>❌ Something Went Wrong</h3>
        <p>Please try again later.</p>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<?php include 'components/footer.php'; ?>

<!-- JS -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="assets/js/visa-form.js"></script>
<script>
 // ======= Embassy Address Switching =======


const selectedOption = document.querySelector('#embassyDropdown');
const selectedFlag = document.getElementById('selected-flag');
const selectedText = document.getElementById('selected-text');
const addressBox = document.getElementById('embassyAddress');


    
    
// ======= Tabs Switching =======
document.querySelectorAll('#visaTabsSection .tab-button').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('#visaTabsSection .tab-button').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('#visaTabsSection .tab-content').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById(btn.dataset.tab).classList.add('active');
  });
});


function printVisaForm() {
  const pdfUrl = 'assets/docs/SCHENGEN_VISA_FORM_PAGE1_ONLY.pdf';
  const iframe = document.createElement('iframe');
  iframe.style.display = 'none';
  iframe.src = pdfUrl;
  document.body.appendChild(iframe);
  iframe.onload = function() {
    iframe.contentWindow.focus();
    iframe.contentWindow.print();
  };
}

  // Run after DOM is ready
  document.addEventListener("DOMContentLoaded", () => {
    const pageField = document.getElementById("current_page");
    if (pageField) pageField.value = window.location.href;
  });

  // Callback fired when token is generated
  function onVisaSubmit(token) {
    document.querySelector('.visa-inquiry-form').submit();
  }

document.addEventListener("DOMContentLoaded", () => {
  const popup = document.getElementById("popup-message");
  if (popup) {
    // Auto-close after 4 seconds
    setTimeout(() => {
      popup.classList.add("fade-out");
      setTimeout(() => popup.remove(), 400);
    }, 4000);

    // Also close on click
    popup.addEventListener("click", () => popup.remove());
  }
});

//------------------------------------------------------------//
document.addEventListener('DOMContentLoaded', () => {
  const urlParams = new URLSearchParams(window.location.search);

  // --- ✅ Visa Application success ---
  if (urlParams.get('status') === 'success' && window.location.href.includes('send_visa_application') ||
      window.location.href.includes('send_kvisa_application')) {
    setTimeout(() => {
      if (typeof gtag === 'function') {
        gtag('event', 'form_submit', {
          'event_category': 'Application',
          'event_label': 'Visa Application'
        });
        console.log('GA4 Event Sent: form_submit (Visa Application)');
      }
    }, 1000);
  }

  // --- ✅ Visa Contact success ---
  if (urlParams.get('status') === 'success' && window.location.href.includes('send_email_visa')) {
    setTimeout(() => {
      if (typeof gtag === 'function') {
        gtag('event', 'form_submit', {
          'event_category': 'Contact',
          'event_label': 'Visa Contact Inquiry'
        });
        console.log('GA4 Event Sent: form_submit (Visa Contact Inquiry)');
      }
    }, 1000);
  }
});

// --- ✅ reCAPTCHA callbacks (universal for all visa pages) ---
function onVisaApplicationSubmit(token) {
  const form = document.querySelector('form[id^="visaPreApp"]'); // works for visaPreAppKorea, visaPreAppJapan, etc.
  if (form) {
    const recaptchaInput = document.createElement('input');
    recaptchaInput.type = 'hidden';
    recaptchaInput.name = 'g-recaptcha-response';
    recaptchaInput.value = token;
    form.appendChild(recaptchaInput);
    form.submit();
  }
}

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
</script>

</body>
</html>
