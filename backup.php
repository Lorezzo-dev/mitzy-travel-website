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

// Inquiry Form directly below gallery image
echo "<div class='inquiry-form-container'>";
  echo "<h3 class='inquiry-heading'>📝 Send an Inquiry for this Tour</h3>";
  echo "<form class='inquiry-form' action='#' method='POST'>";

    echo "<label>🏝️ Tour Package:</label>";
    echo "<input type='text' name='tour' value='{$data['title']}' readonly>";

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

    echo "<button type='submit' class='inquiry-btn'>Send Inquiry</button>";
  echo "</form>";
echo "</div>";

echo "</div>"; // end of .tour-gallery
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
  <div class="accordion">
    <?php
      if (!empty($data['itinerary'])) {
        foreach ($data['itinerary'] as $day) {
          $dayTitle = $day['day_title'] ?? 'Day';
          $dayDetails = $day['day_details'] ?? '';
          echo "<div class='accordion-item'>
                  <div class='accordion-header'>$dayTitle</div>
                  <div class='accordion-content'><p>$dayDetails</p></div>
                </div>";
        }
      }
    ?>
  </div>

  <?php if (!empty($data['flyer'])): ?>
    <div class="flyer-image">
      <canvas id="pdfCanvas"></canvas>
    </div>
  <?php endif; ?>
</div>

<!-- ==================== LIGHTBOXES ==================== -->
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
    <button id="downloadFlyerPDF" class="download-btn">Download PDF</button>
  </div>
</div>


<?php include 'components/footer.php'; ?>

<!-- ==================== JS LIBRARIES ==================== -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
  // === Gallery Thumbnails ===
  const mainImage = document.getElementById('mainImage');
  document.querySelectorAll('.thumb-gallery img').forEach(img => {
    img.addEventListener('click', () => mainImage.src = img.src);
  });

  // === Lightbox for Gallery ===
  const lightbox = document.getElementById('lightbox');
  const lightboxImg = document.querySelector('.lightbox-img');
  mainImage?.addEventListener('click', () => {
    lightbox.style.display = 'flex';
    lightboxImg.src = mainImage.src;
  });
  document.querySelector('.lightbox .close')?.addEventListener('click', () => lightbox.style.display = 'none');
  lightbox?.addEventListener('click', e => { if (e.target === lightbox) lightbox.style.display = 'none'; });

  // === Accordion ===
  document.querySelectorAll('.accordion-header').forEach(header => {
    header.addEventListener('click', () => {
      const item = header.parentElement;
      const content = header.nextElementSibling;
      item.classList.toggle('active');
      content.style.maxHeight = item.classList.contains('active') ? content.scrollHeight + 'px' : '0';
    });
  });

  // === PDF Flyer Render ===
  const pdfUrl = "<?php echo $data['flyer'] ?? ''; ?>";
  const canvas = document.getElementById('pdfCanvas');
  const ctx = canvas?.getContext('2d');
  let flyerImageData = "";
  if (pdfUrl && canvas) {
    pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
      pdf.getPage(1).then(page => {
        const viewport = page.getViewport({ scale: 1.3 });
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        const renderContext = { canvasContext: ctx, viewport };
        page.render(renderContext).promise.then(() => flyerImageData = canvas.toDataURL('image/png'));
      });
    });
  }

  // === Flyer Lightbox ===
  const flyerLightbox = document.getElementById('flyerLightbox');
  const flyerCloseBtn = document.querySelector('.flyer-close');
  const flyerLightboxImg = document.querySelector('.flyer-lightbox-img');
  const downloadBtn = document.getElementById('downloadFlyerPDF');

  canvas?.addEventListener('click', () => {
    flyerLightbox.style.display = 'flex';
    flyerLightboxImg.src = flyerImageData;
    flyerLightbox.scrollTo(0, 0);
  });

  flyerCloseBtn?.addEventListener('click', () => flyerLightbox.style.display = 'none');
  flyerLightbox?.addEventListener('click', e => {
    if (e.target === flyerLightbox) flyerLightbox.style.display = 'none';
  });

  downloadBtn?.addEventListener('click', () => {
    const link = document.createElement('a');
    link.href = pdfUrl;
    link.download = pdfUrl.split('/').pop();
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  });

// === Pax Logic (Show Adults/Children if Pax > 1) ===
const paxInput = document.getElementById('pax');
const groupFields = document.getElementById('group-fields');

function toggleGroupFields() {
  if (paxInput.value > 1) {
    groupFields.classList.add('active');
  } else {
    groupFields.classList.remove('active');
  }
}

// Run once on page load
toggleGroupFields();

// Run again every time Pax changes
paxInput?.addEventListener('input', toggleGroupFields);

  // === Zoom + Scroll Flyer ===
  let zoomLevel = 1;
  const zoomInBtn = document.getElementById('zoomInBtn');
  const zoomOutBtn = document.getElementById('zoomOutBtn');
  const resetZoomBtn = document.getElementById('resetZoomBtn');

  function applyZoom() {
    flyerLightboxImg.style.transform = `scale(${zoomLevel})`;
    flyerLightboxImg.style.transition = 'transform 0.25s ease';
    flyerLightboxImg.style.cursor = zoomLevel > 1 ? 'grab' : 'default';
  }

  zoomInBtn?.addEventListener('click', () => {
    zoomLevel = Math.min(zoomLevel + 0.2, 4);
    applyZoom();
  });

  zoomOutBtn?.addEventListener('click', () => {
    zoomLevel = Math.max(zoomLevel - 0.2, 1);
    applyZoom();
  });

  resetZoomBtn?.addEventListener('click', () => {
    zoomLevel = 1;
    applyZoom();
    flyerLightbox.scrollTo({ top: 0, left: 0, behavior: 'smooth' });
  });

  // === Drag to Pan When Zoomed ===
  let isDragging = false;
  let startX, startY, scrollLeft, scrollTop;

  flyerLightbox.addEventListener('mousedown', e => {
    if (zoomLevel > 1) {
      isDragging = true;
      flyerLightbox.style.cursor = 'grabbing';
      startX = e.pageX - flyerLightbox.offsetLeft;
      startY = e.pageY - flyerLightbox.offsetTop;
      scrollLeft = flyerLightbox.scrollLeft;
      scrollTop = flyerLightbox.scrollTop;
    }
  });

  flyerLightbox.addEventListener('mouseup', () => {
    isDragging = false;
    flyerLightbox.style.cursor = 'grab';
  });

  flyerLightbox.addEventListener('mouseleave', () => isDragging = false);

  flyerLightbox.addEventListener('mousemove', e => {
    if (!isDragging || zoomLevel === 1) return;
    e.preventDefault();
    const x = e.pageX - flyerLightbox.offsetLeft;
    const y = e.pageY - flyerLightbox.offsetTop;
    flyerLightbox.scrollLeft = scrollLeft - (x - startX);
    flyerLightbox.scrollTop = scrollTop - (y - startY);
  });

  // === CTRL/ALT + Scroll Zoom ===
  flyerLightbox.addEventListener('wheel', e => {
    if (e.ctrlKey || e.altKey) {
      e.preventDefault();
      zoomLevel += e.deltaY < 0 ? 0.1 : -0.1;
      zoomLevel = Math.min(Math.max(zoomLevel, 1), 4);
      applyZoom();
    }
  }, { passive: false });
</script>


</body>
</html>