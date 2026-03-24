<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../../admin-login.php");
    exit();
}

$blogFile = __DIR__ . "/../../assets/blog/blogs.json";
$id = $_GET['id'] ?? null;

if (!$id || !file_exists($blogFile)) {
    header("Location: ../admin_blog.php");
    exit();
}

$blogs = json_decode(file_get_contents($blogFile), true);
if (!is_array($blogs)) $blogs = [];

$blogToDelete = null;

/* ---------------------------------------
   FIND and REMOVE the blog entry
--------------------------------------- */
foreach ($blogs as $index => $b) {
    if ($b['id'] === $id) {
        $blogToDelete = $b;
        unset($blogs[$index]);
        break;
    }
}

file_put_contents($blogFile, json_encode(array_values($blogs), JSON_PRETTY_PRINT));

/* ---------------------------------------
   SAFELY DELETE THUMBNAIL IMAGE
--------------------------------------- */
if (!empty($blogToDelete['thumb'])) {

    // Do NOT delete placeholder image
    if (basename($blogToDelete['thumb']) !== "example.jpg") {

        // Only delete if located inside uploads/
        if (strpos($blogToDelete['thumb'], "/assets/blog/uploads/") === 0) {

            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $blogToDelete['thumb'];

            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
}

/* ---------------------------------------------------------
   OPTIONAL: DELETE ALL IMAGES EMBEDDED IN BLOG CONTENT
   (Only deletes those inside /assets/blog/uploads/)
   Uncomment to enable
--------------------------------------------------------- */
/*
if (!empty($blogToDelete['content'])) {
    preg_match_all('/src="([^"]+)"/', $blogToDelete['content'], $matches);

    foreach ($matches[1] as $src) {

        // Do NOT delete example.jpg anywhere
        if (basename($src) === "example.jpg") continue;

        if (strpos($src, "/assets/blog/uploads/") === 0) {

            $path = $_SERVER['DOCUMENT_ROOT'] . $src;

            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
*/

header("Location: ../admin_blog.php");
exit();
