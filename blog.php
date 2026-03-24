<?php
// Load blogs.json
$blogs = json_decode(file_get_contents(__DIR__ . "/assets/blog/blogs.json"), true);

// Ensure array
if (!is_array($blogs)) $blogs = [];

/* ------------------------------------------
   CLEAN + UTF-8 SAFE BLOG PREVIEW FUNCTION
------------------------------------------- */
function blog_preview($html, $limit = 150) {

    // Decode HTML entities (turn &nbsp; into normal spaces)
    $text = html_entity_decode($html, ENT_QUOTES, 'UTF-8');

    // Remove HTML tags
    $text = strip_tags($text);

    // Remove non-breaking spaces (unicode & actual entities)
// Convert all non-breaking spaces to normal spaces
    $text = str_replace("\xc2\xa0", " ", $text); // UTF-8 NBSP
    $text = str_replace("\xA0", " ", $text);     // Some encodings use 0xA0
    $text = str_replace(["&nbsp;", "&#160;"], " ", $text);

    // Normalize whitespace
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);

    // UTF-8 safe trimming
    if (mb_strlen($text, 'UTF-8') > $limit) {
        return mb_substr($text, 0, $limit, 'UTF-8') . "...";
    }

    return $text;
}

/* ------------------------------------------
   SORT: NEWEST → OLDEST
------------------------------------------- */
usort($blogs, function($a, $b) {
    return strtotime($b['date'] ?? '0') <=> strtotime($a['date'] ?? '0');
});

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/images/icons/mtntlogo.png" type="image/png">
  <link rel="stylesheet" href="assets/css/mtzy.css">
  <title>Travel Blog - Mitzy Travel</title>

  <style>
    /* Blog preview text style */
    .blog-preview {
      color: #555;
      font-size: 0.9rem;
      margin-top: 8px;
      line-height: 1.4;
      max-height: 3.7em;       
      overflow: hidden;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
    }
  </style>
</head>
<body>

<?php include 'components/header.php'; ?>

<!-- HERO SECTION -->
<section class="page-hero">
  <img src="assets/images/Hero/IcelandHero.png" alt="Travel Blog">
  <div class="page-hero-text">
    <h1>Travel Blog</h1>
    <p>“Stories, Guides, and Travel Inspiration”</p>
  </div>
</section>

<!-- BLOG LIST -->
<main class="tours-page">
<section class="tours-section">
  <div class="container">

    <h2 class="section-title">Latest Articles</h2>

    <div class="tours-grid-select">

      <?php if (count($blogs) === 0): ?>
        <p style="text-align:center; width:100%; color:#555; font-size:1rem;">
          No blog posts available yet.
        </p>
      <?php else: ?>
        <?php foreach ($blogs as $b): ?>

          <a href="blog_page.php?id=<?= htmlspecialchars($b['id']) ?>" class="tour-card-select">

            <img src="<?= htmlspecialchars($b['thumb']) ?>" alt="<?= htmlspecialchars($b['title']) ?>">

            <div class="tour-card-select-body">

              <h3><?= htmlspecialchars($b['title']) ?></h3>

              <p style="color:#777; font-size:0.9rem; margin-top:4px;">
                <?= htmlspecialchars($b['date']) ?>
              </p>

              <!-- Clean text preview -->
              <p class="blog-preview">
                <?= htmlspecialchars(blog_preview($b['content'], 150)) ?>
              </p>

              <p style="color:var(--pink-dark); font-weight:600; margin-top:6px;">
                Read More →
              </p>

            </div>

          </a>

        <?php endforeach; ?>
      <?php endif; ?>

    </div>

  </div>
</section>
</main>

<?php include 'components/footer.php'; ?>

</body>
</html>
