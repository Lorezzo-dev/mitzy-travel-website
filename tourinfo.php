<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/images/icons/mtntlogo.png" type="image/png">
  <link rel="stylesheet" href="assets/css/mtzy.css">
  <link rel="stylesheet" href="assets/css/tourinfo.css">
  <title>Mitzy Travel and Tours Inc.</title>
</head>

<body class="tourinfo-page">

<?php include 'components/header.php'; ?>

<!-- ==================== MAIN TOUR INFO ==================== -->
<section class="tour-info-container">
  <?php
    $id = isset($_GET['id']) ? $_GET['id'] : '';

    if ($id) {
      $jsonPath = "assets/data/tours/$id.json";

      if (file_exists($jsonPath)) {
        $data = json_decode(file_get_contents($jsonPath), true);

        echo "<div class='tour-details'>";
          echo "<h2>{$data['title']}</h2>";
          if (!empty($data['low'])) echo "<h3 class='low-price'><strong>As low as:</strong> {$data['low']}</h3>";
          if (!empty($data['duration'])) echo "<p><strong>Duration:</strong> {$data['duration']}</p>";
          if (!empty($data['details'])) echo "<p>{$data['details']}</p>";

          // === Travel Dates ===
          if (!empty($data['travel_dates'])) {
            echo "<div class='info-section'><h3>Travel Dates</h3><ul>";
            foreach ($data['travel_dates'] as $date) echo "<li>$date</li>";
            echo "</ul></div>";
          }

          // === Flight Details (optional) ===
          if (!empty($data['flight_details'])) {
            echo "<div class='info-section'><h3>Flight Details</h3><p>{$data['flight_details']}</p></div>";
          }

          // === Inclusions ===
          if (!empty($data['inclusions'])) {
            echo "<div class='info-section'><h3>Inclusions</h3><ul>";
            foreach ($data['inclusions'] as $item) echo "<li>$item</li>";
            echo "</ul></div>";
          }

          // === Exclusions ===
          if (!empty($data['exclusions'])) {
            echo "<div class='info-section'><h3>Exclusions</h3><ul>";
            foreach ($data['exclusions'] as $item) echo "<li>$item</li>";
            echo "</ul></div>";
          }

          // === Extra Notes ===
          if (!empty($data['extra_details'])) {
            echo "<div class='info-section'><h3>Extra Notes</h3><ul>";
            foreach ($data['extra_details'] as $note) echo "<li>$note</li>";
            echo "</ul></div>";
          }

        echo "</div>";

        // === GALLERY + INQUIRY FORM RIGHT SIDE ===
        if (!empty($data['images'])) {
          echo "<div class='tour-gallery'>";
          echo "<img src='{$data['images'][0]}' alt='Main image' class='main-image' id='mainImage'>";
          echo "<div class='thumb-gallery'>";
          foreach ($data['images'] as $img) echo "<img src='$img' alt='Thumbnail'>";
          echo "</div>";

// === Inquiry Form ===
echo "<div class='inquiry-form-container'>";
  echo "<h3 class='inquiry-heading'>📝 Send an Inquiry for this Tour</h3>";
  echo "<form class='inquiry-form' action='backend/send_tour_inquiry.php' method='POST'>";

  // Hidden tour ID
  echo "<input type='hidden' name='id' value='" . htmlspecialchars($id) . "'>";

  // Tour name
  echo "<label>🏝️ Tour Package:</label>";
  echo "<input type='text' name='tour' value='" . htmlspecialchars($data['title']) . "' readonly>";

  // Travel Date dropdown (if available)
  if (!empty($data['travel_dates'])) {
    echo "<label for='travel_date'>📅 Select Travel Date*</label>";
    echo "<select id='travel_date' name='travel_date' required>";
    echo "<option value='' disabled selected>Select your preferred date</option>";
    foreach ($data['travel_dates'] as $date) {
      $safeDate = htmlspecialchars(trim(strip_tags($date)));
      echo "<option value='$safeDate'>$safeDate</option>";
    }
    echo "</select>";
  }

  // Standard Fields
  echo "<label for='name'>👤 Your Name*</label>";
  echo "<input type='text' id='name' name='name' placeholder='Enter your name' required>";

  echo "<label for='email'>📧 Your Email*</label>";
  echo "<input type='email' id='email' name='email' placeholder='Enter your email address' required>";

  echo "<label for='contact'>📞 Contact Number*</label>";
  echo "<input type='text' id='contact' name='contact' placeholder='Enter your contact number' required>";

  echo "<label for='pax'>👨‍👩‍👧‍👦 No. of Pax*</label>";
  echo "<input type='number' id='pax' name='pax' min='1' value='1' required>";

  echo "<div id='group-fields'>";
    echo "<label for='adults'>🧑‍🦳 No. of Adults</label>";
    echo "<input type='number' id='adults' name='adults' min='1' value='1' class='small-input'>";
    echo "<label for='children'>🧒 No. of Children</label>";
    echo "<input type='number' id='children' name='children' min='0' value='0' class='small-input'>";
  echo "</div>";

  echo "<label for='message'>💬 Your Message*</label>";
  echo "<textarea id='message' name='message' rows='5' placeholder='Enter your message or questions here' required></textarea>";

  // Honeypot (anti-bot)
  echo "<input type='text' name='website' style='display:none;'>";

  // ✅ Invisible reCAPTCHA v2 Button
  echo "<button 
          class='g-recaptcha inquiry-btn' 
          data-sitekey='6LfsivkrAAAAAELiWBl-NdiMRFKAOQvB3I1dQnGd' 
          data-callback='onTourSubmit' 
          data-action='submit'>
          Send Inquiry
        </button>";

  echo "<script src='https://www.google.com/recaptcha/api.js' async defer></script>";

  echo "</form>";

  // ✅ Status message feedback
  if (isset($_GET['status'])) {
    $statusClass = $_GET['status'] === 'success' ? 'success' : 'error';
    $statusMsg = $_GET['status'] === 'success'
        ? '✅ Your inquiry has been sent successfully! We’ll get back to you soon.'
        : '❌ Sorry, something went wrong. Please try again.';
    echo "<div class='form-status $statusClass'>$statusMsg</div>";
  }

echo "</div>"; // inquiry-form-container
          echo "<script>
function onTourSubmit(token) {
  document.querySelector('.inquiry-form').submit();
}
</script>";
          echo "</div>"; // tour-gallery
        }

      } else {
        echo "<p>Tour information not found for ID: <strong>$id</strong></p>";
      }
    } else {
      echo "<p>No tour ID provided.</p>";
    }
  ?>
</section>

<!-- ==================== ITINERARY + FLYER ==================== -->
<div class="itinerary-flyer-container">
  <div class="itinerary-section">
    <div class="info-section">
      <h3>Itinerary</h3>
    </div>

<div class="accordion">
  <?php
    if (!empty($data['itinerary'])) {
      foreach ($data['itinerary'] as $day) {
        $dayTitle = $day['day_title'] ?? 'Day';
        $dayDetails = $day['day_details'] ?? '';
        $dayImage = $day['day_image'] ?? ''; // NEW

        echo "<div class='accordion-item'>
                <div class='accordion-header'>$dayTitle</div>
                <div class='accordion-content'>
                  <p>$dayDetails</p>";

        // Show image only if it exists
        if (!empty($dayImage)) {
          $safeImg = htmlspecialchars($dayImage);
          echo "<img src='$safeImg' alt='Itinerary Image' class='itinerary-image'>";
        }

        echo "    </div>
              </div>";
      }
    }
  ?>
</div>
  </div>

<?php
// Normalize old format ("flyer") into new format ("flyers")
$flyers = [];

// 1. New format: array
if (!empty($data['flyers']) && is_array($data['flyers'])) {
  $flyers = $data['flyers'];
}

// 2. Old format: single 'flyer' string
else if (!empty($data['flyer'])) {
  $flyers = [ $data['flyer'] ];
}
?>

<?php if (!empty($flyers)): ?>
<div class="flyer-image">

  <?php foreach ($flyers as $index => $flyer): ?>
    <?php 
      $ext = strtolower(pathinfo($flyer, PATHINFO_EXTENSION));
      $flyerId = "flyerPreview_" . $index;
    ?>

    <?php if ($ext === 'pdf'): ?>
      <!-- PDF PREVIEW CANVAS -->
      <canvas class="flyer-canvas" id="<?php echo $flyerId; ?>"></canvas>

      <script>
        document.addEventListener("DOMContentLoaded", () => {
          const pdfUrl = "<?php echo $flyer; ?>";
          const canvas = document.getElementById("<?php echo $flyerId; ?>");
          const ctx = canvas.getContext("2d");

          pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
            pdf.getPage(1).then(page => {
              const viewport = page.getViewport({ scale: 1.25 });
              canvas.height = viewport.height;
              canvas.width = viewport.width;

              page.render({ canvasContext: ctx, viewport }).promise.then(() => {
                canvas.dataset.fullImage = canvas.toDataURL("image/png");
              });
            });
          });

          // click → open lightbox
          canvas.addEventListener("click", () => {
            flyerLightboxImg.src = canvas.dataset.fullImage;
            flyerLightbox.style.display = "flex";
            flyerLightbox.classList.add("active");
          });
        });
      </script>

    <?php else: ?>
      <!-- IMAGE FLYER -->
      <img 
        src="<?php echo $flyer; ?>" 
        class="flyer-thumb"
        style="max-width:100%; border-radius:8px; cursor:pointer; margin-bottom:16px;"
        onclick="
          flyerLightboxImg.src = '<?php echo $flyer; ?>';
          flyerLightbox.style.display = 'flex';
          flyerLightbox.classList.add('active');
        "
      >
    <?php endif; ?>

  <?php endforeach; ?>

</div>
<?php endif; ?>
</div>

<!-- ==================== FLYER LIGHTBOX ==================== -->
<div id="flyerLightbox" class="flyer-lightbox">
  <span class="flyer-close">&times;</span>
  <div class="flyer-scroll-area">
    <img class="flyer-lightbox-img" src="" alt="Flyer Preview">
  </div>
  <div class="flyer-controls">
    <div class="zoom-controls">
      <button id="zoomInBtn">+</button>
      <button id="zoomOutBtn">−</button>
      <button id="resetZoomBtn">⟳</button>
    </div>
    <button id="downloadFlyerPDF" class="download-btn">Download Flyer</button>
  </div>
</div>

<?php include 'components/footer.php'; ?>

<!-- ==================== JS LIBRARIES ==================== -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
/* =========================================================
   DOM READY
========================================================= */
document.addEventListener('DOMContentLoaded', () => {

  /* ================= GA4 SUCCESS EVENT ================= */
  const urlParams = new URLSearchParams(window.location.search);
  if (urlParams.get('status') === 'success') {
    setTimeout(() => {
      if (typeof gtag === 'function') {
        gtag('event', 'form_submit', {
          event_category: 'Contact',
          event_label: 'Tour Inquiry'
        });
      }
    }, 1000);
  }

  /* ================= GALLERY ================= */
  const mainImage = document.getElementById('mainImage');
  document.querySelectorAll('.thumb-gallery img').forEach(img => {
    img.addEventListener('click', () => {
      if (mainImage) mainImage.src = img.src;
    });
  });

  /* ================= ACCORDION ================= */
  document.querySelectorAll('.accordion-item').forEach(item => {
    const header = item.querySelector('.accordion-header');
    const content = item.querySelector('.accordion-content');

    item.classList.add('active');
    content.style.maxHeight = content.scrollHeight + 'px';

    header.addEventListener('click', () => {
      item.classList.toggle('active');
      content.style.maxHeight = item.classList.contains('active')
        ? content.scrollHeight + 'px'
        : '0';
    });
  });

  document.querySelectorAll('.accordion-content img').forEach(img => {
    img.addEventListener('load', () => {
      const content = img.closest('.accordion-content');
      if (content) content.style.maxHeight = content.scrollHeight + 'px';
    });
  });

  /* ================= PAX LOGIC ================= */
  const paxInput = document.getElementById('pax');
  const groupFields = document.getElementById('group-fields');
  if (paxInput && groupFields) {
    const toggleGroupFields = () =>
      paxInput.value > 1
        ? groupFields.classList.add('active')
        : groupFields.classList.remove('active');

    toggleGroupFields();
    paxInput.addEventListener('input', toggleGroupFields);
  }

});

/* =========================================================
   reCAPTCHA
========================================================= */
function onTourSubmit(token) {
  const form = document.querySelector('.inquiry-form');
  if (!form) return;

  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'g-recaptcha-response';
  input.value = token;
  form.appendChild(input);
  form.submit();
}

/* =========================================================
   FLYER LIGHTBOX + ZOOM SYSTEM
========================================================= */

function isMobileView() {
  return window.innerWidth <= 768;
}

const flyerLightbox = document.getElementById('flyerLightbox');
const flyerLightboxImg = document.querySelector('.flyer-lightbox-img');
const flyerCloseBtn = document.querySelector('.flyer-close');
const downloadBtn = document.getElementById('downloadFlyerPDF');

/* --- Messenger Visibility --- */
function hideMessenger() {
  const chat = document.getElementById('custom-messenger-chat');
  if (chat) {
    chat.style.opacity = '0';
    chat.style.visibility = 'hidden';
    chat.style.pointerEvents = 'none';
  }
}
function showMessenger() {
  const chat = document.getElementById('custom-messenger-chat');
  if (chat) {
    chat.style.opacity = '1';
    chat.style.visibility = 'visible';
    chat.style.pointerEvents = 'auto';
  }
}

/* ================= ZOOM STATE ================= */
let zoomLevel = 1;
let fitZoom = 1;

/* ================= FIT TO SCREEN ================= */
function calculateFitZoom() {
  if (!flyerLightboxImg.naturalWidth) return 1;

  const scaleX = flyerLightbox.clientWidth / flyerLightboxImg.naturalWidth;
  const scaleY = flyerLightbox.clientHeight / flyerLightboxImg.naturalHeight;
  fitZoom = Math.min(scaleX, scaleY);
  return fitZoom;
}

function applyZoom() {
  flyerLightboxImg.style.transform = `scale(${zoomLevel})`;
  flyerLightboxImg.style.transition = 'transform 0.25s ease';
  flyerLightboxImg.style.cursor = zoomLevel > fitZoom ? 'grab' : 'default';
}

/* ================= OPEN FLYER ================= */
function openFlyer(src) {
  // ❌ Do nothing on mobile
  if (isMobileView()) return;

  flyerLightboxImg.src = src;
  flyerLightbox.style.display = 'flex';
  flyerLightbox.classList.add('active');
  hideMessenger();

  flyerLightboxImg.onload = () => {
    zoomLevel = calculateFitZoom();
    applyZoom();
    flyerLightbox.scrollTo(0, 0);
  };
}

/* ================= CLOSE FLYER ================= */
function closeFlyer() {
  flyerLightbox.style.display = 'none';
  flyerLightbox.classList.remove('active');
  flyerLightboxImg.src = '';
  showMessenger();
}

flyerCloseBtn?.addEventListener('click', closeFlyer);
flyerLightbox?.addEventListener('click', e => {
  if (e.target === flyerLightbox) closeFlyer();
});

/* ================= ZOOM CONTROLS ================= */
document.getElementById('zoomInBtn')?.addEventListener('click', () => {
  zoomLevel = Math.min(zoomLevel + 0.2, 4);
  applyZoom();
});

document.getElementById('zoomOutBtn')?.addEventListener('click', () => {
  zoomLevel = Math.max(zoomLevel - 0.2, fitZoom);
  applyZoom();
});

document.getElementById('resetZoomBtn')?.addEventListener('click', () => {
  zoomLevel = fitZoom;
  applyZoom();
  flyerLightbox.scrollTo({ top: 0, left: 0, behavior: 'smooth' });
});

/* ================= DRAG TO PAN ================= */
let isDragging = false, startX, startY, scrollLeft, scrollTop;

flyerLightbox?.addEventListener('mousedown', e => {
  if (zoomLevel <= fitZoom) return;
  isDragging = true;
  startX = e.pageX;
  startY = e.pageY;
  scrollLeft = flyerLightbox.scrollLeft;
  scrollTop = flyerLightbox.scrollTop;
});

document.addEventListener('mouseup', () => isDragging = false);

flyerLightbox?.addEventListener('mousemove', e => {
  if (!isDragging) return;
  flyerLightbox.scrollLeft = scrollLeft - (e.pageX - startX);
  flyerLightbox.scrollTop = scrollTop - (e.pageY - startY);
});

/* ================= CTRL / ALT SCROLL ZOOM ================= */
flyerLightbox?.addEventListener('wheel', e => {
  if (!e.ctrlKey && !e.altKey) return;
  e.preventDefault();
  zoomLevel += e.deltaY < 0 ? 0.1 : -0.1;
  zoomLevel = Math.min(Math.max(zoomLevel, fitZoom), 4);
  applyZoom();
}, { passive: false });

/* ================= DOWNLOAD ================= */
downloadBtn?.addEventListener('click', () => {
  const link = document.createElement('a');
  link.href = flyerLightboxImg.src;
  link.download = link.href.split('/').pop();
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
});

function isMobileView() {
  return window.innerWidth <= 768;
}

document.querySelectorAll('.flyer-thumb, .flyer-canvas').forEach(el => {
  el.addEventListener('click', e => {
    if (isMobileView()) {
      e.preventDefault();
      e.stopImmediatePropagation();
      return false;
    }
  }, true); // capture phase
});
</script>
</body>
</html>
