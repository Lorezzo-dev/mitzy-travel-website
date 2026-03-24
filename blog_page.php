<?php
$blogs = json_decode(file_get_contents(__DIR__ . "/assets/blog/blogs.json"), true);

// Ensure array
if (!is_array($blogs)) $blogs = [];

// Sort blogs by date (newest first)
usort($blogs, function($a, $b) {
    return strtotime($b['date'] ?? '0') <=> strtotime($a['date'] ?? '0');
});

$id = $_GET['id'] ?? '';
$blog = null;

foreach ($blogs as $b) {
  if ($b['id'] === $id) {
    $blog = $b;
    break;
  }
}

if (!$blog) die("Blog not found.");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($blog['title']) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/images/icons/mtntlogo.png" type="image/png">
  <link rel="stylesheet" href="assets/css/mtzy.css">

  <style>
    /* === MAIN 2-COLUMN LAYOUT === */
    .blog-layout {
      display: flex;
      gap: 40px;
      padding: 50px 6%;
      box-sizing: border-box;
      align-items: flex-start;
    }

    .blog-main {
      flex: 1;
      max-width: 820px;
    }

    /* === SIDEBAR === */
    .blog-sidebar {
      width: 280px;
      flex-shrink: 0;
      border-left: 2px solid #f0b7c8;
      padding-left: 20px;
    }

    /* Sidebar cards (Recent Posts) */
    .blog-side-card {
      display: flex;
      gap: 10px;
      margin-bottom: 14px;
      text-decoration: none;
      color: inherit;
      padding: 8px;
      border-radius: 8px;
      background: #fff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.07);
      transition: transform 0.15s ease, box-shadow 0.15s ease;
      align-items: center;
    }

    .blog-side-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Thumbnail size */
    .blog-side-thumb {
      width: 80px;
      height: 60px;
      object-fit: cover;
      border-radius: 6px;
    }

    /* Title text */
    .blog-side-title {
      font-size: 0.88rem;
      font-weight: 600;
      color: var(--pink-dark2);
      line-height: 1.25;
      margin-bottom: 3px;
    }

    /* Date text */
    .blog-side-date {
      font-size: 0.75rem;
      color: #777;
    }

    /* === Responsive === */
    @media (max-width: 900px) {
      .blog-layout {
        flex-direction: column;
        padding: 30px 4%;
      }
      .blog-sidebar {
        width: 100%;
        border-left: none;
        padding-left: 0;
        margin-top: 40px;
      }
    }
  </style>

</head>
<body>

<?php include 'components/header.php'; ?>

<!-- HERO SECTION -->
<section class="page-hero">
  <img src="<?= htmlspecialchars($blog['thumb']) ?>" alt="Blog Banner">
  <div class="page-hero-text">
    <h1><?= htmlspecialchars($blog['title']) ?></h1>
    <p><?= htmlspecialchars($blog['date']) ?></p>
  </div>
</section>

<!-- MAIN 2-COLUMN LAYOUT -->
<div class="blog-layout">

  <!-- LEFT: FULL BLOG ARTICLE -->
  <div class="blog-main">
    
    <h1 style="
      font-family:'Bevan', serif;
      font-size: 2.3rem;
      color: var(--pink-dark2);
      -webkit-text-stroke: 0.5px white;
      margin-bottom: 5px;
    ">
      <?= htmlspecialchars($blog['title']) ?>
    </h1>

    <p style="color:#777; font-size:0.95rem; margin-bottom:25px;">
      <?= htmlspecialchars($blog['date']) ?>
    </p>

    <div class="blog-content"
      style="font-size:1.1rem; line-height:1.85; color:#444;">
      <?= $blog['content'] ?>
    </div>

  </div>

  <!-- RIGHT: SIDEBAR (RECENT POSTS) -->
  <aside class="blog-sidebar">
    <h3 style="color:var(--pink-dark2); font-family:'Bevan', serif; margin-bottom:15px;">
      Recent Posts
    </h3>

    <?php foreach ($blogs as $b): ?>
      <?php if ($b['id'] !== $blog['id']): ?>

        <a href="blog_page.php?id=<?= $b['id'] ?>" class="blog-side-card">

          <img src="<?= htmlspecialchars($b['thumb']) ?>" class="blog-side-thumb">

          <div>
            <div class="blog-side-title"><?= htmlspecialchars($b['title']) ?></div>
            <div class="blog-side-date"><?= htmlspecialchars($b['date']) ?></div>
          </div>

        </a>

      <?php endif; ?>
    <?php endforeach; ?>

  </aside>

</div>

<?php include 'components/footer.php'; ?>

</body>
</html>
