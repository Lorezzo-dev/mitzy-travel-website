<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: ../admin-login.php");
  exit();
}

// Path to blogs.json inside /assets/blog/
$blogFile = __DIR__ . "/../assets/blog/blogs.json";

if (!file_exists($blogFile)) {
  file_put_contents($blogFile, json_encode([]));
}

$blogs = json_decode(file_get_contents($blogFile), true);
if (!is_array($blogs)) $blogs = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Manage Blog Posts</title>
  <meta name="viewport" content="width=device-width,initial-scale=1" />

  <link rel="icon" href="../assets/images/icons/mtntlogo.png" type="image/png">

  <!-- TinyMCE -->
  <script src="https://cdn.tiny.cloud/1/u1vnrob8ba2jqpfdhquugmltrynr7qbdzehra6bs4wpofwhv/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

  <style>
    :root{
      --pink-300: #ffb7c5;
      --pink-400: #ff9eb8;
      --pink-600: #d1007e;
      --muted-1: #f5f6f8;
      --muted-2: #e9edf2;
      --text-1: #212428;
      --text-2: #53606f;
      --card-shadow: 0 10px 30px rgba(20,20,30,0.06);
      --radius-lg: 14px;
    }
    * { box-sizing: border-box; }
    body {
      background: linear-gradient(180deg, #faf9fb 0%, #f6fbff 100%);
      font-family: Inter, "Poppins", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      margin: 0;
      padding: 24px;
      color: var(--text-1);
      -webkit-font-smoothing:antialiased;
    }

    .admin-container {
      width: 96%;
      max-width: 1200px;
      margin: 10px auto 60px;
      background: #fff;
      padding: 26px;
      border-radius: var(--radius-lg);
      box-shadow: var(--card-shadow);
      border: 1px solid var(--muted-2);
    }

    header.admin-head {
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom: 18px;
    }
    header.admin-head h1 {
      font-size: 1.35rem;
      margin: 0;
      color: var(--pink-600);
      letter-spacing: -0.2px;
    }
    .controls {
      display:flex;
      gap:10px;
      align-items:center;
    }

    .btn {
      padding: 10px 14px;
      background: var(--pink-400);
      color: #fff; border: none;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 600;
      transition: transform .08s ease, box-shadow .12s ease;
      box-shadow: 0 6px 18px rgba(255,126,160,0.08);
    }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(255,126,160,0.12); }
    .btn.ghost {
      background: transparent;
      color: var(--pink-600);
      border: 1px solid var(--pink-200);
      box-shadow: none;
    }
    .btn-delete {
      background: #ffecec;
      color: #b20000;
      border-radius: 10px;
      padding: 8px 12px;
      font-weight: 700;
    }

    .table-wrap { overflow-x:auto; margin-top: 8px; border-radius: 10px; }
    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 720px;
    }
    table thead th {
      text-align:left;
      padding: 12px 16px;
      background: linear-gradient(90deg,var(--pink-300),var(--pink-400));
      color: white;
      font-weight: 700;
      font-size: 0.95rem;
      position: sticky; top:0;
    }
    table tbody td {
      padding: 12px 16px;
      border-bottom: 1px solid #f1f3f5;
      vertical-align: middle;
      font-size: 0.95rem;
      color: var(--text-2);
    }
    .thumb-cell img { width:72px; height:48px; object-fit:cover; border-radius:8px; border:1px solid #fff; box-shadow:0 6px 18px rgba(20,20,30,0.03); }

    .actions { display:flex; gap:8px; align-items:center; }

    /* Modal */
    .blog-modal {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width:100%; height:100%;
      background: rgba(10,10,12,0.48);
      justify-content:center;
      align-items:center;
      z-index: 9999;
      padding: 24px;
    }
    .blog-modal.active { display:flex; }

    .blog-modal-content {
      width: 95%;
      max-width: 1100px;
      height: 90vh;
      background: #fff;
      border-radius: 12px;
      padding: 18px;
      overflow: hidden;
      display:flex;
      flex-direction:column;
      box-shadow: 0 30px 70px rgba(20,20,30,0.15);
      position: relative;
      border: 1px solid #f0f0f3;
    }

    .modal-top {
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:12px;
      margin-bottom:12px;
    }

    .modal-title {
      font-weight:700;
      color:var(--pink-600);
      font-size:1.05rem;
    }

    .modal-body {
      display:flex;
      flex-direction:column;
      gap:12px;
      flex:1 1 auto;
      min-height:0;
    }

    .blog-top-row {
      display:flex;
      gap: 14px;
      align-items: flex-start;
      margin-bottom:6px;
    }

    .blog-field {
      flex: 1;
      min-width: 0;
      display:flex;
      flex-direction:column;
      gap:6px;
    }

    .blog-field.small {
      flex: 0 0 220px;
    }

    .blog-field label {
      font-weight:600;
      color: #444;
      font-size:0.92rem;
    }

    .blog-field input[type="text"],
    .blog-field input[type="date"] {
      height:40px;
      padding:8px 10px;
      border-radius:10px;
      border:1px solid #e6e9ee;
      font-size:0.95rem;
      background:#fff;
    }

    .thumb-preview {
      display:flex;
      gap:10px;
      align-items:center;
    }
    .thumb-preview img {
      width:84px;
      height:56px;
      object-fit:cover;
      border-radius:8px;
      border:1px solid #f1f3f5;
      box-shadow: 0 8px 20px rgba(20,20,30,0.04);
    }

    .editor-wrapper {
      display:flex;
      flex-direction:column;
      gap:8px;
      flex:1 1 auto;
      min-height:0;
    }

    /* Make TinyMCE take remaining space */
    .editor-wrapper .tox, .editor-wrapper .tox-tinymce, .editor-wrapper .tox .tox-editor-container {
      height: 100% !important;
      min-height: 0 !important;
    }
    
    /* Make TinyMCE dialogs appear above your modal */
.tox-tinymce-aux,
.tox-dialog {
  z-index: 100000 !important;
}

.tox {
  z-index: 100001 !important;
}
    .editor-wrapper iframe { height:100% !important; }

    .modal-footer {
      display:flex;
      gap:8px;
      justify-content:flex-end;
      padding-top:8px;
      border-top:1px dashed #f2f2f4;
    }

    @media (max-width:880px) {
      .blog-top-row { flex-direction:column; }
      .blog-field.small { flex:1; }
      table { min-width: 600px; }
      .thumb-cell img { width:56px; height:42px; }
    }
    
    .top-nav {
  display: flex;
  gap: 18px;
  margin: 0 auto 20px;
  width: 96%;
  max-width: 1200px;
  padding-left: 4px;
}

.top-nav a {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--pink-600);
  text-decoration: none;
  transition: 0.15s ease;
}

.top-nav a:hover {
  text-decoration: underline;
  color: var(--pink-400);
}
  </style>
</head>
<body>
    <!-- Top Navigation Buttons -->
<div class="top-nav">
    <a href="admin.php">← Back to Admin</a>
    <a href="../blog.php" target="_blank">View Site</a>
</div>
<div class="admin-container">
  <header class="admin-head">
    <h1>Manage Blog Posts</h1>
    <div class="controls">
      <button class="btn" onclick="openNewPostModal()">+ New Blog Post</button>
    </div>
  </header>

  <div class="table-wrap">
    <table>
      <thead>
        <tr>
          <th style="width:38%">Title</th>
          <th style="width:18%">Date</th>
          <th style="width:18%">Thumbnail</th>
          <th style="width:26%">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($blogs) === 0): ?>
          <tr><td colspan="4" style="padding:22px; text-align:center; color:var(--text-2)">No blog posts yet.</td></tr>
        <?php else: ?>
          <?php foreach ($blogs as $b): ?>
            <tr>
              <td><?= htmlspecialchars($b['title'] ?? '') ?></td>
              <td><?= htmlspecialchars($b['date'] ?? '') ?></td>
              <td class="thumb-cell">
                <?php if (!empty($b['thumb'])): ?>
                  <img src="<?= htmlspecialchars($b['thumb']) ?>" alt="thumb">
                <?php else: ?>
                  <span style="color:var(--text-2); font-size:0.92rem">— none —</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="actions">
                  <a class="btn" href="admin_blog.php?edit=<?= urlencode($b['id']) ?>">Edit</a>
                  <a class="btn btn-delete" href="crud/blog_delete.php?id=<?= urlencode($b['id']) ?>"
                     onclick="return confirm('Delete this blog?')">Delete</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
$edit = null;
if (isset($_GET['edit'])) {
  foreach ($blogs as $b) {
    if ((string)($b['id'] ?? '') === (string)$_GET['edit']) {
      $edit = $b;
      break;
    }
  }
}
?>

<div class="blog-modal <?= $edit ? 'active' : '' ?>" id="blogModal">
  <div class="blog-modal-content" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-top">
      <div class="modal-title" id="modalTitle"><?= $edit ? 'Edit Blog Post' : 'Create New Blog Post' ?></div>
      <div>
        <button class="btn ghost" type="button" onclick="closeModal()">Close</button>
      </div>
    </div>

    <form id="blogForm" action="crud/blog_save.php" method="post" style="display:flex;flex-direction:column;height:100%;">
      <input type="hidden" name="id" value="<?= htmlspecialchars($edit['id'] ?? '') ?>">

      <div class="modal-body">
        <div class="blog-top-row">
          <div class="blog-field" style="flex:2;">
            <label for="titleInput">Title</label>
            <input id="titleInput" type="text" name="title" required value="<?= htmlspecialchars($edit['title'] ?? '') ?>">
          </div>

          <div class="blog-field small">
            <label for="dateInput">Date</label>
            <input id="dateInput" type="date" name="date" required value="<?= htmlspecialchars($edit['date'] ?? date('Y-m-d')) ?>">
          </div>

          <div class="blog-field small" style="flex:0 0 320px;">
            <label for="thumbInput">Thumbnail Image URL</label>
<div style="display:flex;gap:10px;align-items:center">
    <input id="thumbUpload" type="file" accept="image/*">
    <input type="hidden" name="thumb" id="thumbHidden" value="<?= htmlspecialchars($edit['thumb'] ?? '') ?>">
</div>
            <div class="thumb-preview" style="margin-top:8px">
              <div style="font-size:0.85rem;color:var(--text-2)">Preview</div>
              <div id="previewWrap">
                <?php if (!empty($edit['thumb'])): ?>
                  <img id="thumbPreview" src="<?= htmlspecialchars($edit['thumb']) ?>" alt="thumb preview">
                <?php else: ?>
                  <img id="thumbPreview" src="/assets/images/icons/mtntlogo.png" alt="thumb preview">
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <div class="editor-wrapper" style="min-height:0;">
          <label style="font-weight:600; color:#444; font-size:0.92rem;">Content</label>
          <!-- keep textarea but we will init TinyMCE only when modal opens (or is active on load) -->
          <textarea id="blog_content" name="content"><?= htmlspecialchars($edit['content'] ?? '') ?></textarea>
        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-delete" type="button" onclick="closeModal()">Cancel</button>
        <button class="btn" type="submit">Save</button>
      </div>
    </form>
  </div>
</div>

<script>
  // ---------- Utility: ensure we don't init TinyMCE more than once ----------
  function isEditorReady() {
    try {
      return typeof tinyMCE !== 'undefined' && tinyMCE.get('blog_content');
    } catch (e) { return false; }
  }

  // ---------- Modal controls ----------
  function openModal() {
    document.getElementById('blogModal').classList.add('active');
    // If editor isn't initialized yet, init now.
    setTimeout(() => {
      initEditorIfNeeded();
      // focus the editor quickly after showing
      setTimeout(()=> {
        try { tinyMCE.get('blog_content') && tinyMCE.get('blog_content').focus(); } catch(e){}
      }, 120);
    }, 80);
  }
  function closeModal() {
    document.getElementById('blogModal').classList.remove('active');
    // do NOT destroy editor to keep content while modal open/closed — but you can call tinyMCE.remove() if you prefer
  }

// ---------- Thumbnail Upload (New) ----------
const thumbUpload = document.getElementById("thumbUpload");
const thumbHidden = document.getElementById("thumbHidden");
const thumbPreview = document.getElementById("thumbPreview");

thumbUpload.addEventListener("change", async function () {
    const file = this.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append("file", file);

    const res = await fetch("crud/upload_blog_image.php", {
        method: "POST",
        body: formData
    });

    const data = await res.json();

    if (data.location) {
        thumbHidden.value = data.location; 
        thumbPreview.src = data.location; 
    } else {
        alert("Failed to upload thumbnail: " + (data.error || "Unknown error"));
    }
});

  // ---------- TinyMCE initialization (on-demand, robust) ----------
function initEditorIfNeeded() {
  if (typeof tinyMCE !== 'undefined' && tinyMCE.get('blog_content')) return;

  tinymce.init({
    selector: '#blog_content',
    menubar: false,
    plugins: 'image table lists link media code advlist autolink',
    toolbar: 'undo redo | formatselect | bold italic underline | ' +
             'alignleft aligncenter alignright | bullist numlist | ' +
             'table | image media link | code',

    height: 500,
    resize: true,

    /* BUILT-IN TINYMCE UPLOADER (NO CUSTOM HANDLER) */
    images_upload_url: 'crud/upload_blog_image.php',
    automatic_uploads: true,
    paste_data_images: false,

    /* Optional: force paste to upload instead of base64 */
    images_reuse_filename: false,

    content_style: 'body { font-family: Inter, Poppins, Arial; font-size:14px }',

    setup: function(editor) {
      editor.on('init', function() {
        setTimeout(() => {
          try {
            editor.theme.resizeTo('100%', '100%');
          } catch(e) {}
        }, 150);
      });
    }
  });
}

document.addEventListener('DOMContentLoaded', function() {
  if (document.querySelector('.blog-modal.active')) {
    setTimeout(initEditorIfNeeded, 100);
  }
});

  // If modal is active on page load (editing), initialize immediately
  (function ensureEditorOnLoad(){
    if (document.querySelector('.blog-modal.active')) {
      // short delay to allow DOM & CSS to settle
      setTimeout(initEditorIfNeeded, 80);
    }
  })();

  // ---------- Form submit: ensure content from editor is synced to textarea ----------
  const blogForm = document.getElementById('blogForm');
  if (blogForm) {
    blogForm.addEventListener('submit', function (e) {
      if (isEditorReady()) {
        try {
          tinyMCE.get('blog_content').save(); // writes editor content back to textarea
        } catch (err) {
          console.warn('Could not save editor to textarea before submit', err);
        }
      }
      // let the form submit normally to crud/blog_save.php
    });
  }
  
  function resetNewBlogFields() {
    // Reset PHP-filled fields
    document.querySelector('input[name=id]').value = "";
    document.querySelector('input[name=title]').value = "";
    document.querySelector('input[name=date]').value = new Date().toISOString().split("T")[0];
    document.querySelector('input[name=thumb]').value = "";

    // Reset thumbnail preview (optional)
    const preview = document.getElementById("thumbPreview");
    if (preview) preview.src = "/assets/images/icons/mtntlogo.png";

    // Reset TinyMCE
    if (tinyMCE.get('blog_content')) {
        tinyMCE.get('blog_content').setContent("");
    }
}

function openNewPostModal() {
    resetNewBlogFields();
    openModal();
}
</script>

</body>
</html>
