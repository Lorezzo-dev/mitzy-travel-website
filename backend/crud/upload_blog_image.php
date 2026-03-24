<?php
// ensure no accidental output before JSON
ob_clean();
header('Content-Type: application/json; charset=utf-8');

try {
    // Upload folder: /assets/blog/uploads/
    $uploadDir = __DIR__ . '/../../assets/blog/uploads/';

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (!isset($_FILES['file'])) {
        echo json_encode(["error" => "No file uploaded"]);
        exit;
    }

    $file = $_FILES['file'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($ext, $allowed)) {
        echo json_encode(["error" => "Invalid file type"]);
        exit;
    }

    $newName = uniqid("blog_", true) . "." . $ext;
    $targetPath = $uploadDir . $newName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode(["error" => "Failed to save file"]);
        exit;
    }

    // path TinyMCE inserts into the editor
    $url = "/assets/blog/uploads/" . $newName;

    echo json_encode(["location" => $url]);
    exit;

} catch (Throwable $e) {
    echo json_encode(["error" => $e->getMessage()]);
    exit;
}
