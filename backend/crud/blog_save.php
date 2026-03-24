<?php

// Path to blogs.json
$blogFile = __DIR__ . "/../../assets/blog/blogs.json";

// Load existing blogs
$blogs = json_decode(file_get_contents($blogFile), true);
if (!is_array($blogs)) $blogs = [];

// Determine blog ID
$id = !empty($_POST['id']) ? $_POST['id'] : uniqid("blog_");

// Sanitize fields
$title = trim($_POST['title'] ?? '');
$date  = trim($_POST['date'] ?? '');
$thumb = trim($_POST['thumb'] ?? '');
$content = $_POST['content'] ?? '';

/* -------------------------------------------
   APPLY DEFAULT THUMBNAIL PLACEHOLDER
-------------------------------------------- */
if ($thumb === '' || $thumb === null) {
    $thumb = "/assets/blog/uploads/example.jpg"; // your placeholder
}

// Build new blog array
$new = [
    "id"      => $id,
    "title"   => $title,
    "date"    => $date,
    "thumb"   => $thumb,
    "content" => $content
];

// Update existing or append new
$found = false;
foreach ($blogs as &$b) {
    if ($b['id'] === $id) {
        $b = $new;
        $found = true;
        break;
    }
}

if (!$found) {
    $blogs[] = $new;
}

// Save JSON
file_put_contents($blogFile, json_encode($blogs, JSON_PRETTY_PRINT));

// Redirect back
header("Location: ../admin_blog.php");
exit;
