<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin-login.php");
  exit();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin — Tours Management</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="../assets/css/mtzy.css">
  <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
  <link rel="icon" href="../assets/images/icons/mtntlogo.png" type="image/png">

  <style>
    /* tiny modal tweaks + layout cleanup */
    .modal.small .modal-content { max-width:560px; }
    .form-row { display:flex; gap:8px; align-items:center; }
    .form-row input { flex:1; padding:8px; border-radius:6px; border:1px solid #ddd; }
    .note-small { font-size:13px; color:#666; }

    /* ===== Modal center system (isolated, no conflicts) ===== */
    .modal-center {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      display: none; /* remain hidden until activated */
      justify-content: center;
      align-items: center;
      background: rgba(0,0,0,0.45);
      z-index: 9999;
      padding: 12px; /* give some breathing room on mobile */
    }

    .modal-center.active {
      display: flex !important;
      animation: modal-fade-in 160ms ease;
    }

    @keyframes modal-fade-in {
      from { opacity: 0; transform: translateY(6px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .modal-center .modal-content {
      background: #fff;
      border-radius: 10px;
      padding: 18px;
      max-height: 90vh;
      overflow-y: auto;
      width: 100%;
      box-shadow: 0 8px 30px rgba(0,0,0,0.12);
      /* responsive width */
      max-width: 720px;
    }

    /* small modal variant */
    .modal-center.modal-small .modal-content { max-width: 560px; }

    /* mobile-friendly tweaks */
    @media (max-width: 640px) {
      .modal-center .modal-content { padding: 14px; width: 100%; max-width: 98%; border-radius: 8px; }
      .modal-center { padding: 8px; }
    }

    /* image preview */
    .img-preview { height: auto; max-height: 200px; object-fit: contain; }
    .cards-grid { display:flex; flex-wrap:wrap; gap:12px; }
    .card-body { padding:10px; }
    .card-controls { display:flex; gap:6px; padding:10px; justify-content:flex-end; }
    .tab-btn { padding:8px 12px; margin-right:8px; border-radius:8px; border:1px solid #eee; cursor:pointer; }
    .tab-btn.active { background:var(--pink-dark2); color:#fff; border-color:var(--pink-dark2); }
    
.admin-tour-card {
  width: 200px;              /* was 220px */
  border: 1px solid #eee;
  border-radius: 8px;
  overflow: hidden;
  background: #fff;
  box-shadow: 0 4px 12px rgba(0,0,0,0.04);
}

.admin-tour-card img {
  width: 100%;
  height: 160px;             /* was 120px */
  object-fit: contain;
  display: block;
}

.admin-tour-card.over {
  border: 2px dashed var(--pink-dark2);
}
  </style>
</head>

<body class="admin-dashboard">
  <div class="admin-wrap">
    <h1 style="font-family:Bevan,serif;color:var(--pink-dark2);">Tours Management</h1>
    <p class="small-muted">Manage tour cards</p>

    <div class="tabs" id="tabsContainer"></div>
    <div id="regionsContainer" style="margin-top:16px;"></div>

    <div style="margin-top:22px;">
      <a href="admin.php" class="btn open">← Back to Admin</a>
    </div>
  </div>

  <!-- CREATE TOUR MODAL -->
<div id="createModal" class="modal modal-center modal-small" aria-hidden="true" style="display:none;">
  <div class="modal-content">
    <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;">
      <h3 style="margin:0">Create New Tour</h3>
      <button id="closeCreateModal" class="btn open">Close</button>
    </div>

    <div style="padding-top:12px;">
      <label><strong>Region</strong></label>
      <select id="createRegion"
              style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;margin-bottom:10px;">
        <option value="tours-asia.json" data-prefix="A">Asia (A.)</option>
        <option value="tours-korjap.json" data-prefix="B">Korean & Japan (B.)</option>
        <option value="tours-europe.json" data-prefix="C">Europe & America (C.)</option>
        <option value="tours-oceania.json" data-prefix="D">Oceania (D.)</option>
      </select>

      <label><strong>Title (display)</strong></label>
      <input id="createTitle" type="text" placeholder="New Tour Name" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">

      <label style="margin-top:8px;"><strong>ID Slug (must have no space)</strong></label>
      <input id="createId" type="text" placeholder="NewTourID" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">
      <div class="note-small">Allowed chars: <code>A-Z a-z 0-9 _ -</code><br>Prefix added automatically.</div>

      <div id="fullIdPreview" style="margin-top:8px;font-weight:bold;color:#333;">
         Full ID: <span id="previewIdValue">A.undefined</span>
      </div>

      <div style="margin-top:12px; display:flex; gap:8px; justify-content:flex-end;">
        <button id="cancelCreate" class="btn cancel">Cancel</button>
        <button id="submitCreate" class="btn save">Create Tour</button>
      </div>

      <div id="createStatus" style="margin-top:10px;" class="small-muted"></div>
    </div>
  </div>
</div>

  <!-- EDIT MODAL -->
  <div id="editModal" class="modal modal-center" aria-hidden="true" style="display:none;">
    <div class="modal-content">
      <div class="modal-header" style="display:flex;justify-content:space-between;align-items:center;">
        <h3 id="modalTitle" style="margin:0">Edit Tour</h3>
        <button id="closeModal" class="btn open">Close</button>
      </div>

      <div id="modalNotice" class="notice" style="display:none;margin-top:8px;"></div>

      <div style="display:flex;gap:14px;flex-wrap:wrap;margin-top:12px;">
        <div style="flex:1; min-width:300px;">
          <label><strong>Title (plain text)</strong></label>
          <input id="inputTitle" type="text" placeholder="Tour title" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">

          <label style="margin-top:10px;"><strong>Subtitle (rich text)</strong></label>
          <div id="editorSubtitle" style="height:90px; background:#fff;"></div>
          <label style="margin-top:10px;"><strong>Location (plain text)</strong></label>
          <input id="inputLocation" type="text" placeholder="Location info" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">

          <label style="margin-top:10px;"><strong>Link (tourinfo.php?id=...)</strong></label>
          <input id="inputLink" type="text" placeholder="tourinfo.php?id=A.new_id" style="width:100%;padding:8px;border-radius:6px;border:1px solid #ddd;">
        </div>

        <div style="width:300px; display:flex; flex-direction:column; gap:10px;">
          <label><strong>Image Preview</strong></label>
          <img id="imgPreview" class="img-preview" src="" alt="preview">

          <form id="imageUploadForm" enctype="multipart/form-data" method="post" style="width:100%;">
              <input type="hidden" id="upload_title" name="title" value="">
            <input type="file" name="image" id="inputImageFile" accept="image/*" style="width:100%;">
            <input type="hidden" id="upload_region" name="region" value="">
            <div class="meta-row" style="display:flex;align-items:center;gap:8px;margin-top:6px;width:100%;">
              <button type="button" id="uploadImageBtn" class="btn edit">Upload Image</button>
              <span id="uploadStatus" class="small-muted"></span>
            </div>
          </form>

          <label><strong>ID (slug)</strong></label>
          <input id="inputId" type="text" placeholder="lapland" style="width:100%;padding:6px;border-radius:6px;border:1px solid #ddd;">
          <div class="small-muted">Changing ID may update the link if using default format.</div>
        </div>
      </div>

      <div class="actions" style="display:flex;gap:8px;justify-content:flex-end;margin-top:12px;">
        <button id="deleteBtn" class="btn delete danger">Delete</button>
        <button id="saveBtn" class="btn edit">Save Changes</button>
      </div>
    </div>
  </div>

  <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
  <script>
    // region definitions
    const regionFiles = [
      { key: 'tours-asia.json', label: 'Asia', prefix: 'A' },
      { key: 'tours-europe.json', label: 'Europe & America', prefix: 'C' },
      { key: 'tours-korjap.json', label: 'Korean & Japan', prefix: 'B' },
      { key: 'tours-oceania.json', label: 'Oceania', prefix: 'D' }
    ];

    let toursData = {};
    let currentEdit = { regionFile: null, index: null };

    // Quill
    const quillSubtitle = new Quill('#editorSubtitle', { theme: 'snow', placeholder: 'Subtitle (rich text)' });

    // DOM refs
    const tabsContainer = document.getElementById('tabsContainer');
    const regionsContainer = document.getElementById('regionsContainer');

    const createModal = document.getElementById('createModal');
    const closeCreateModal = document.getElementById('closeCreateModal');
    const createRegion = document.getElementById('createRegion');
    const createTitle = document.getElementById('createTitle');
    const createId = document.getElementById('createId');
    const submitCreate = document.getElementById('submitCreate');
    const cancelCreate = document.getElementById('cancelCreate');
    const createStatus = document.getElementById('createStatus');

    // Edit modal elements
    const editModal = document.getElementById('editModal');
    const imgPreview = document.getElementById('imgPreview');
    const inputLink = document.getElementById('inputLink');
    const inputTitle = document.getElementById('inputTitle');
    const inputLocation = document.getElementById('inputLocation');
    const inputId = document.getElementById('inputId');
    const uploadRegionInput = document.getElementById('upload_region');
    const inputImageFile = document.getElementById('inputImageFile');
    const saveBtn = document.getElementById('saveBtn');
    const deleteBtn = document.getElementById('deleteBtn');
    const closeModal = document.getElementById('closeModal');
    const uploadImageBtn = document.getElementById('uploadImageBtn');
    const uploadStatus = document.getElementById('uploadStatus');

    // helper: show/hide modal (keeps backward compatible with inline style)
    function showModal(modalEl) {
      if (!modalEl) return;
      modalEl.style.display = 'block';
      modalEl.classList.add('active');
      modalEl.setAttribute('aria-hidden', 'false');
      // prevent background body scrolling while modal open
      document.body.style.overflow = 'hidden';
    }
    function hideModal(modalEl) {
      if (!modalEl) return;
      modalEl.style.display = 'none';
      modalEl.classList.remove('active');
      modalEl.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = ''; // restore
    }

    // close on ESC
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        hideModal(createModal);
        hideModal(editModal);
      }
    });

    document.addEventListener('DOMContentLoaded', () => {
      buildTabs();
      loadAllTours();
    });

    function buildTabs() {
      tabsContainer.innerHTML = '';
      regionFiles.forEach((r, i) => {
        const btn = document.createElement('button');
        btn.className = 'tab-btn' + (i === 0 ? ' active' : '');
        btn.textContent = r.label;
        btn.dataset.file = r.key;
        btn.onclick = () => switchTab(r.key);
        tabsContainer.appendChild(btn);
      });

      // Quick create button pinned to top-right
      const header = document.querySelector('.admin-wrap');
      const createQuick = document.createElement('div');
      createQuick.style = 'position:fixed; right:18px; top:18px; z-index:999';
      createQuick.innerHTML = `<button id="openCreate" class="btn save">+ New Tour</button>`;
      header.appendChild(createQuick);

      document.getElementById('openCreate').onclick = () => {
        createTitle.value = '';
        createId.value = '';
        createStatus.textContent = '';
        // default region
        createRegion.value = regionFiles[0].key;
        updateFullIdPreview();
        showModal(createModal);
      };

      // create modal close actions
      cancelCreate.onclick = () => hideModal(createModal);
      closeCreateModal.onclick = () => hideModal(createModal);
    }

    async function loadAllTours() {
      const res = await fetch('crud/load_tours.php');
      try {
        toursData = await res.json();
      } catch (e) {
        toursData = {};
        console.error('Failed to parse tours JSON', e);
      }
      renderRegions();

      const savedTab = localStorage.getItem('lastRegionTab');
      const defaultTab = savedTab && regionFiles.find(r => r.key === savedTab) ? savedTab : regionFiles[0].key;
      switchTab(defaultTab);
    }

    function renderRegions() {
      regionsContainer.innerHTML = '';
      for (const region of regionFiles) {
        const panel = document.createElement('div');
        panel.className = 'region-panel';
        panel.id = `panel-${region.key}`;

        const header = document.createElement('div');
        header.style.display = 'flex';
        header.style.justifyContent = 'space-between';
        header.style.alignItems = 'center';
        header.style.marginBottom = '12px';

        const h = document.createElement('h2');
        h.textContent = region.label;
        header.appendChild(h);

        const addBtn = document.createElement('button');
        addBtn.className = 'btn add';
        addBtn.textContent = 'Add New Tour';
        addBtn.onclick = () => {
          createRegion.value = region.key;
          updateFullIdPreview();
          showModal(createModal);
        };
        header.appendChild(addBtn);
        panel.appendChild(header);

        const grid = document.createElement('div');
        grid.className = 'cards-grid';
        const items = toursData[region.key] || [];

        items.forEach((t, idx) => grid.appendChild(createCardElement(region.key, t, idx)));
        panel.appendChild(grid);
        regionsContainer.appendChild(panel);
      }
    }

    function createCardElement(regionFile, item, idx) {
      const el = document.createElement('div');
      el.className = 'admin-tour-card';
      el.draggable = true;
      el.dataset.index = idx;
      el.dataset.region = regionFile;

      const img = document.createElement('img');
      img.src = item.image?.startsWith('assets/')
        ? '../' + item.image
        : (item.image || '../assets/images/icons/mtntlogo.png');
      el.appendChild(img);

      const body = document.createElement('div');
      body.className = 'card-body';
      body.innerHTML = `
        <h3>${escapeHtml(stripTags(item.title || ''))}</h3>
        <p>${item.subtitle || ''}</p>
        <div class="small-muted">${escapeHtml(stripTags(item.location || ''))}</div>`;
      el.appendChild(body);

      const ctrl = document.createElement('div');
      ctrl.className = 'card-controls';
      ctrl.innerHTML = `
        <button class="btn edit">Edit</button>
        <button class="btn open">Open</button>
        <button class="btn delete">Delete</button>
      `;

      ctrl.querySelector('.edit').onclick = () => openEditModal(regionFile, idx);

      ctrl.querySelector('.open').onclick = () => {
const fullId = item.link && item.link.includes('id=')
  ? item.link.split('id=')[1]
  : item.id;
window.location.href = 'admin_tourinfo.php?id=' + encodeURIComponent(fullId);
        
      };

      ctrl.querySelector('.delete').onclick = () => doDelete(regionFile, idx);

      el.appendChild(ctrl);
      el.addEventListener('dragstart', handleDragStart);
      el.addEventListener('dragenter', handleDragEnter);
      el.addEventListener('dragover', handleDragOver);
      el.addEventListener('dragleave', handleDragLeave);
      el.addEventListener('drop', handleDrop);
      el.addEventListener('dragend', handleDragEnd);

      return el;
    }

    function switchTab(fileKey) {
      document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
      document.querySelectorAll('.region-panel').forEach(p => p.classList.remove('active'));
      const btn = document.querySelector(`[data-file="${fileKey}"]`);
      if (btn) btn.classList.add('active');
      const panel = document.getElementById(`panel-${fileKey}`);
      if (panel) panel.classList.add('active');
      localStorage.setItem('lastRegionTab', fileKey);
      // scroll to regions container when switching
      panel && panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    // --- CREATE NEW TOUR (modal submit) ---
    function isValidId(id) {
      // expects A.slug or B.slug etc. slug: a-z0-9_- (lowercase or uppercase allowed when creating)
      return /^[A-D]\.[A-Za-z0-9\-_]+$/.test(id);
    }

    submitCreate.onclick = async () => {
      const region = createRegion.value;
      const prefix = createRegion.options[createRegion.selectedIndex].dataset.prefix;
      const title = createTitle.value.trim();
      let slug = createId.value.trim();

      if (!title) { createStatus.textContent = 'Enter a title.'; return; }
      if (!slug) { createStatus.textContent = 'Enter an ID slug.'; return; }

      if (!/^[A-Za-z0-9_-]+$/.test(slug)) {
        createStatus.textContent = 'Slug must contain only A-Z, a-z, 0-9, _ or -';
        return;
      }

      const fullId = prefix + "." + slug;

      createStatus.textContent = 'Creating...';
      submitCreate.disabled = true;

      const payload = { region: region, id: fullId, title: title };
      try {
        const resp = await fetch('crud/create_tour.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });
        const j = await resp.json();
        submitCreate.disabled = false;
        if (j.success) {
          createStatus.textContent = 'Created successfully. Reloading...';
          setTimeout(() => location.reload(), 500);
        } else {
          createStatus.textContent = 'Error: ' + (j.msg || 'unknown');
        }
      } catch (err) {
        submitCreate.disabled = false;
        createStatus.textContent = 'Network error';
        console.error(err);
      }
    };

    // cancel/close handled earlier by buildTabs

    function openEditModal(regionFile, index) {
      currentEdit = { regionFile, index };
      const item = (toursData[regionFile] || [])[index];
      if (!item) return alert('Tour not found');

      inputTitle.value = stripTags(item.title || '');
      quillSubtitle.clipboard.dangerouslyPasteHTML(item.subtitle || '');
      inputLocation.value = stripTags(item.location || '');
      inputLink.value = item.link || '';
      inputId.value = item.id || '';
      document.getElementById('upload_title').value = item.title || '';
      uploadRegionInput.value = regionFile;

      imgPreview.src = item.image?.startsWith('assets/')
        ? '../' + item.image
        : (item.image || '../assets/images/icons/mtntlogo.png');

      showModal(editModal);
    }

    closeModal.onclick = () => hideModal(editModal);

    saveBtn.onclick = async () => {
      const { regionFile, index } = currentEdit;
      if (!regionFile) return alert('Nothing to save');

      const item = toursData[regionFile][index];
      if (!item) return alert('Item missing');

      const oldId = (item.link && item.link.includes('id=')) ? item.link.split('id=')[1] : item.id;

      item.title = inputTitle.value.trim();
      item.subtitle = quillSubtitle.root.innerHTML;
      item.location = inputLocation.value.trim();
      item.link = inputLink.value.trim();
      item.id = inputId.value.trim();

      const newId = (item.link && item.link.includes('id=')) ? item.link.split('id=')[1] : item.id;

      try {
        if (oldId !== newId) {
          await fetch('crud/rename_tourinfo.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ old_id: oldId, new_id: newId })
          });
        }

        await saveWholeRegion(regionFile);

        // ALSO update individual tour JSON minimally
        await fetch('crud/save_tourinfo.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            id: item.link.includes("id=") ? item.link.split("id=")[1] : item.id,
            data: { title: item.title }
          })
        });

        location.reload();
      } catch (err) {
        console.error(err);
        alert('Save failed');
      }
    };

    async function saveWholeRegion(regionFile) {
      const payload = { region: regionFile, tours: toursData[regionFile] };
      const r = await fetch('crud/save_tours.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(payload)
      });
      return await r.json();
    }

    async function doDelete(regionFile, idx) {
      if (!confirm('Delete this tour permanently?')) return;
      const item = (toursData[regionFile] || [])[idx];
      if (!item) return alert('Item missing');
      let fullId = item.id;
      if (item.link && item.link.includes("id=")) {
        fullId = item.link.split("id=")[1];
      }
      await fetch('crud/delete_tour.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ region: regionFile, id: fullId })
      });
      location.reload();
    }

    // image preview for edit modal
inputImageFile.addEventListener('change', e => {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = ev => imgPreview.src = ev.target.result;
  reader.readAsDataURL(file);
});

    // modal delete button — same as card delete
    deleteBtn.onclick = async () => {
      if (!confirm("Delete this tour permanently?")) return;

      const regionFile = currentEdit.regionFile;
      const index = currentEdit.index;
      const item = (toursData[regionFile] || [])[index];
      if (!item) return alert('Item missing');

      let fullId = item.id;
      if (item.link && item.link.includes("id=")) {
        fullId = item.link.split("id=")[1];
      }

      await fetch("crud/delete_tour.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          region: regionFile,
          id: fullId
        })
      });

      location.reload();
    };

    // upload image within edit modal (calls existing PHP endpoint)
    uploadImageBtn.onclick = async () => {
      const file = inputImageFile.files[0];
      if (!file) return alert('Choose an image first.');
      const fd = new FormData();
      fd.append('image', file);
      fd.append('region', uploadRegionInput.value || '');
      fd.append('title', document.getElementById('upload_title').value);
      // optionally include id if present
      const item = toursData[uploadRegionInput.value]?.[currentEdit.index];
      if (item && item.id) fd.append('id', item.id);

      uploadStatus.textContent = 'Uploading...';
      try {
        const res = await fetch('crud/upload_card_image.php', { method: 'POST', body: fd });
        const j = await res.json();
        if (j.success) {
          uploadStatus.textContent = 'Uploaded';
          // update preview and local data
          imgPreview.src = '../' + j.path;
          if (item) item.image = j.path;
          
        } else {
          uploadStatus.textContent = 'Upload failed';
          alert('Upload failed: ' + (j.msg || 'unknown'));
        }
      } catch (err) {
        console.error(err);
        uploadStatus.textContent = 'Network error';
      }
    };

    function stripTags(str) {
      const tmp = document.createElement('div');
      tmp.innerHTML = str;
      return tmp.textContent || tmp.innerText || '';
    }

    function escapeHtml(unsafe) {
      return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
    }

    function updateFullIdPreview() {
      const regionSelect = document.querySelector('#createRegion');
      const prefix = regionSelect.options[regionSelect.selectedIndex].dataset.prefix;
      const slug = document.querySelector('#createId').value.trim();
      document.querySelector('#previewIdValue').textContent = prefix + "." + slug;
    }

    document.querySelector('#createRegion').onchange = updateFullIdPreview;
    document.querySelector('#createId').oninput = updateFullIdPreview;

// ===== DRAG AND DROP =====
let dragSrcEl = null;

function handleDragStart(e) {
  dragSrcEl = this;
  this.style.opacity = '0.4';
  e.dataTransfer.effectAllowed = 'move';
}

function handleDragEnd() {
  this.style.opacity = '1';
  document.querySelectorAll('.admin-tour-card').forEach(card => {
    card.classList.remove('over');
  });
}

function handleDragOver(e) {
  e.preventDefault();
  return false;
}

function handleDragEnter() {
  this.classList.add('over');
}

function handleDragLeave() {
  this.classList.remove('over');
}

async function handleDrop(e) {
  e.stopPropagation();

  if (dragSrcEl === this) return false;

  const parent = this.parentNode;
  parent.insertBefore(dragSrcEl, this);

  const region = dragSrcEl.dataset.region;
  const newOrder = Array.from(parent.children)
    .filter(c => c.classList.contains("admin-tour-card"))
    .map(c => toursData[region][c.dataset.index]);

  toursData[region] = newOrder;

  // Save updated region ordering
  await saveWholeRegion(region);

  location.reload();
  return false;
}

  </script>
</body>
</html>
