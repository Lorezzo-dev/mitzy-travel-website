<?php
// backend/admin_tourinfo.phpmap
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
  header("Location: admin-login.php");
  exit();
}

$id = $_GET['id'] ?? '';
if (!$id) die("No tour ID provided.");

$jsonPath = __DIR__ . "/../assets/data/tours/{$id}.json";
if (!file_exists($jsonPath)) die("Tour JSON not found: " . htmlspecialchars($id));

$data = json_decode(file_get_contents($jsonPath), true);
if (!is_array($data)) $data = [];

$defaultKeys = [
  'title' => '', 'details' => '', 'duration' => '', 'low' => '',
  'flyer' => '', 'travel_dates' => [], 'itinerary' => [],
  'inclusions' => [], 'exclusions' => [], 'extra_details' => [], 'images' => [],
  'flight_details' => '', 'contact' => ''
];

$data = array_merge($defaultKeys, $data);

$jsData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Tour — <?php echo htmlspecialchars($id); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="../assets/css/mtzy.css">
<link rel="stylesheet" href="../assets/css/tourinfo.css">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="icon" href="../assets/images/icons/mtntlogo.png" type="image/png">

<style>
body { background:#fff; }
.admin-wrap { max-width:1300px; margin:25px auto; padding:20px; }

/* toolbar */
.admin-toolbar {
  position: sticky; top:10px; z-index:99;
  display:flex; gap:10px; padding:12px;
  background:#fff; border:1px solid #f1dce5;
  border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

.btn { padding:8px 14px; border-radius:8px; border:none; font-weight:600; cursor:pointer; transition:0.2s; }
.btn.save { background:var(--pink-dark2); color:white; }
.btn.save:hover { background:var(--pink-medium); }
.btn.cancel { background:white; border:1px solid #ccc; }
.btn.cancel:hover { background:#f9f9f9; }
.btn.back { background:#fff2f8; border:1px solid var(--pink-dark2); color:var(--pink-dark2); }
.btn.back:hover { background:var(--pink-dark2); color:white; }

.editable { outline:2px dashed rgba(255,105,180,0.3); padding:4px; border-radius:6px; }
.info-section { margin-bottom:20px; }
input[type="text"], textarea { width:100%; padding:8px; border-radius:6px; border:1px solid #ccc; margin-bottom:10px; }

.gallery-row { display:flex; gap:14px; flex-wrap:wrap; margin-top:10px; }
.thumb-edit { width:130px; height:90px; object-fit:cover; border-radius:8px; border:1px solid #ddd; }

.itinerary-card { border:1px solid #e6cfe0; border-radius:10px; padding:12px; margin-bottom:12px; background:white; }
.small-muted { color:#666; font-size:13px; }
.flex-row { display:flex; gap:8px; align-items:center; }
.itin-image-preview { width:160px; height:100px; object-fit:cover; border-radius:8px; border:1px solid #ddd; margin-top:8px; }
.hidden-file { display:none; }

/* ================================
   FIX: CLEAN GALLERY BUTTON LAYOUT
   ================================ */

#galleryRow > div {
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 150px;        /* keeps consistent tile size */
  margin-bottom: 20px;
}

#galleryRow .thumb-edit {
  width: 140px;
  height: 90px;
  object-fit: cover;
  border-radius: 8px;
  border: 1px solid #ddd;
}

#galleryRow .button-row, 
#galleryRow div > div {
  display: flex;
  flex-wrap: wrap;     /* allows wrapping of buttons */
  justify-content: center;
  gap: 6px;
  margin-top: 6px;
}

#galleryRow button {
  padding: 6px 10px;
  font-size: 13px;
  white-space: nowrap; /* prevent breaking text inside buttons */
}

</style>

</head>

<body class="admin-dashboard">

<div class="admin-wrap">

  <!-- Toolbar -->
  <div class="admin-toolbar">
    <button id="saveBtn" class="btn save">💾 Save</button>
    <button id="cancelBtn" class="btn cancel">✖ Cancel</button>
    <a href="admin_tours.php" class="btn back">← Back</a>
    <input type="file" id="importJsonFile" accept="application/json" style="display:none;">
    <button id="importJsonBtn" class="btn back">📥 Import JSON</button>
    <button id="exportJsonBtn" class="btn back">📤 Export JSON</button>
    <div style="margin-left:auto;color:#666;">
      Editing: <strong><?php echo htmlspecialchars($id); ?></strong>
    </div>
  </div>

  <section style="margin-top:20px; display:flex; gap:24px; align-items:flex-start;">

    <!-- LEFT SIDE -->
    <div style="flex:1; min-width:540px;">

      <h2 id="titleField" class="editable" contenteditable="true" style="font-size:28px; margin-bottom:6px;">
        <?php echo htmlspecialchars($data['title']); ?>
      </h2>

      <h3 class="small-muted">
        <strong>As low as:</strong>
        <span id="lowField" class="editable" contenteditable="true">
          <?php echo htmlspecialchars($data['low']); ?>
        </span>
      </h3>

      <p><strong>Duration:</strong>
        <span id="durationField" class="editable" contenteditable="true">
          <?php echo htmlspecialchars($data['duration']); ?>
        </span>
      </p>

      <!-- DETAILS -->
      <div class="info-section">
        <h3>Details</h3>
        <div id="detailsEditor" style="background:white; min-height:140px; padding:8px; border-radius:8px;">
          <?php echo $data['details']; ?>
        </div>
      </div>

      <!-- DATES -->
      <div class="info-section">
        <h3>Travel Dates</h3>
        <div id="datesList"></div>
        <button id="addDateBtn" class="btn back" style="margin-top:10px;">+ Add Date</button>
      </div>

      <!-- FLIGHT -->
      <div class="info-section">
        <h3>Flight Details</h3>
        <div id="flightField" class="editable" contenteditable="true">
          <?php echo htmlspecialchars($data['flight_details']); ?>
        </div>
      </div>

      <!-- INCLUSIONS -->
      <div class="info-section">
        <h3>Inclusions</h3>
        <div id="inclusionsList"></div>
        <button id="addInclusionBtn" class="btn back">+ Add Inclusion</button>
      </div>

      <!-- EXCLUSIONS -->
      <div class="info-section">
        <h3>Exclusions</h3>
        <div id="exclusionsList"></div>
        <button id="addExclusionBtn" class="btn back">+ Add Exclusion</button>
      </div>

      <!-- EXTRA -->
      <div class="info-section">
        <h3>Extra Notes</h3>
        <div id="extraList"></div>
        <button id="addExtraBtn" class="btn back">+ Add Note</button>
      </div>

    </div>

    <!-- RIGHT -->
    <aside style="width:360px;">
      <h3>Gallery</h3>
<img id="mainImage"
     src="<?php 
        echo '../' . htmlspecialchars(
            $data['images'][0] ?? 'assets/images/icons/mtntlogo.png'
        ); 
     ?>"
     style="width:100%; border-radius:8px; border:1px solid #eee;">

      <div id="galleryRow" class="gallery-row"></div>

<h3>Upload Images</h3>

<!-- Drag & Drop Zone -->
<div id="dropZone"
     style="border:2px dashed #c2185b; padding:20px; border-radius:10px;
            text-align:center; color:#c2185b; cursor:pointer;">
    <strong>Drag & Drop Images Here</strong><br>
    <span class="small-muted">(or click to select multiple files)</span>
</div>

<input type="file" id="uploadGalleryFile" accept="image/*" multiple style="display:none;">
<div id="uploadGalleryStatus" class="small-muted" style="margin-top:10px;"></div>

      <hr style="margin:20px 0;">

<h3>Flyers</h3>

<?php
// Normalize old --> new
if (!empty($data['flyer']) && empty($data['flyers'])) {
  $data['flyers'] = [ $data['flyer'] ];
}
if (empty($data['flyers'])) $data['flyers'] = [];
?>

<div id="flyerList"></div>

<hr style="margin:15px 0;">

<h3>Upload Flyers</h3>

<!-- Flyer Drag & Drop Zone -->
<div id="flyerDropZone"
     style="border:2px dashed #c2185b; padding:20px; border-radius:10px;
            text-align:center; color:#c2185b; cursor:pointer; margin-bottom:10px;">
    <strong>Drag & Drop Flyers (PDF / Images)</strong><br>
    <span class="small-muted">(or click to select multiple files)</span>
</div>

<input type="file" id="uploadFlyerFile" accept="application/pdf,image/*" multiple style="display:none;">
<div id="uploadFlyerStatus" class="small-muted" style="margin-top:10px;"></div>

    </aside>

  </section>

  <!-- ITINERARY -->
  <div style="margin-top:25px;">
    <h3>Itinerary</h3>
    <div id="itineraryList"></div>
    <button id="addDayBtn" class="btn back">+ Add Day</button>
  </div>

</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<!-- ✅ Global Quill auto-focus disable (fixes auto scroll-to-bottom) -->
<script>
/* ===============================
   GENERAL FIXES & BOOTSTRAP
================================ */
Quill.prototype.focus = function(){};

document.addEventListener("DOMContentLoaded", () => {
  // prevent Quill from auto-focusing / jumping
  setTimeout(() => {
    document.querySelectorAll("[contenteditable]").forEach(el => el.blur());
    window.scrollTo(0, 0);
  }, 30);

  // run main init
  initAdminTourEditor();
});

/* ===============================
   MAIN INIT
================================ */
function initAdminTourEditor() {
  const TOUR_ID = <?php echo json_encode($id); ?>;
  let data = JSON.parse(<?php echo json_encode($jsData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);

  function detectRegionFromId(id) {
    const map = { 'A': 'asia', 'B': 'korea_japan', 'C': 'europe', 'D': 'oceania' };
    if (!id || typeof id !== 'string') return 'asia';
    return map[id.charAt(0).toUpperCase()] || 'asia';
  }
  data.region = data.region || detectRegionFromId(TOUR_ID);

  function uid(prefix){ return prefix + '_' + Math.random().toString(36).slice(2,9); }
  function keepScroll(fn){ const y = window.scrollY; fn(); window.scrollTo(0,y); }
  function freezeScrollWhile(fn){
      const x = window.scrollX, y = window.scrollY;
      fn();
      requestAnimationFrame(() => window.scrollTo(x, y));
  }

function moveItem(arr, i, dir) {
  const ni = i + dir;
  if (ni < 0 || ni >= arr.length) return;
  [arr[i], arr[ni]] = [arr[ni], arr[i]];
}




  /* ===============================
     QUILL DETAILS EDITOR
  ================================= */
  let quillDetails = new Quill('#detailsEditor', {
    theme: 'snow',
    modules: { toolbar:[['bold','italic','underline'],[{list:'bullet'},{list:'ordered'}],['link']] }
  });
  if (data.details) quillDetails.clipboard.dangerouslyPasteHTML(data.details);

  let quillDates = [];
  let quillItins = [];

  /* ===============================
     PATH NORMALIZER (FLEXIBLE)
     Returns a path safe to use as an `src` / `href`
  ================================= */
  function normalizePathToAssetHref(path) {
    if (!path || typeof path !== 'string') return path;

    // Trim whitespace
    path = path.trim();

    // If it's an absolute URL (http/https) — return untouched
    if (/^https?:\/\//i.test(path)) return path;

    // If it already starts with ../assets/ -> keep as-is
    if (path.startsWith('../assets/')) return path;

    // If it starts with 'assets/' -> prepend ../
    if (path.startsWith('assets/')) return '../' + path;

    // If it already starts with '/assets/' -> remove leading / and prepend ../
    if (path.startsWith('/assets/')) return '..' + path;

    // If it looks like it already contains 'assets/data/packages' with or without ../
    if (path.includes('assets/data/')) {
      // remove any leading ../ or ./ to avoid duplication, then prepend ../
      const cleaned = path.replace(/^(\.\.\/|\.\/)+/, '');
      return '../' + cleaned;
    }

    // If it is just a filename or relative path under packages -> attempt to place under expected folder
    // expected: assets/data/packages/<regionFolder>/<title>/<filename>
    // But we don't know title here, so fallback to ../assets/data/<path>
    return '../assets/data/' + path.replace(/^(\.\/|\/)+/, '');
  }

  /* ===============================
     FLYERS RENDER
  ================================= */
  function renderFlyers(){
      const list = document.getElementById("flyerList");
      if (!list) return;
      list.innerHTML = "";

      if (!Array.isArray(data.flyers)) data.flyers = [];

      if (data.flyers.length === 0) {
          list.innerHTML = `<div class="small-muted">No flyers uploaded yet.</div>`;
          return;
      }

      data.flyers.forEach((path,i)=>{
          const ext = (path.split(".").pop() || "").toLowerCase();
          const wrap = document.createElement("div");
          wrap.style = "border:1px solid #ddd;padding:10px;margin-bottom:12px;border-radius:8px;background:#fafafa;";

          wrap.innerHTML = `<strong>Flyer #${i+1}</strong><br>`;

          const preview = document.createElement("div");
          preview.style = "margin-top:8px;";

          const href = normalizePathToAssetHref(path);

          if (["jpg","jpeg","png","webp"].includes(ext)) {
              preview.innerHTML = `<img src="${href}" style="max-width:100%;max-height:150px;border-radius:6px;">`;
          } else {
              // treat as PDF / other document - show link
              preview.innerHTML = `<a href="${href}" target="_blank" style="color:#c2185b;font-weight:bold;">Open Flyer</a>`;
          }
          wrap.appendChild(preview);

          const controls = document.createElement("div");
          controls.style = "margin-top:10px;display:flex;gap:6px;";
          controls.innerHTML += `
              <button class="btn cancel" data-i="${i}" data-dir="-1">↑</button>
              <button class="btn cancel" data-i="${i}" data-dir="1">↓</button>
              <button class="btn cancel" data-i="${i}" data-action="delete">Delete</button>
              <button class="btn back" data-i="${i}" data-action="copy">Copy Path</button>
          `;
          wrap.appendChild(controls);

          // attach delegated handlers
          controls.querySelectorAll("button").forEach(btn=>{
              btn.addEventListener("click", (ev)=>{
                  const idx = Number(btn.getAttribute("data-i"));
                  const dir = Number(btn.getAttribute("data-dir") || 0);
                  const action = btn.getAttribute("data-action") || null;
                  if (action === "delete") return deleteFlyer(idx);
                  if (action === "copy") {
                      navigator.clipboard.writeText(path).catch(()=>{});
                      return;
                  }
                  if (dir !== 0) moveFlyer(idx, dir);
              });
          });

          list.appendChild(wrap);
      });
  }

  function moveFlyer(i,dir){
      const ni = i + dir;
      if (ni < 0 || ni >= data.flyers.length) return;
      freezeScrollWhile(()=>{
          [data.flyers[i], data.flyers[ni]] = [data.flyers[ni], data.flyers[i]];
          renderFlyers();
      });
  }
  function deleteFlyer(i){
      if (!confirm("Delete flyer?")) return;
      freezeScrollWhile(()=>{
          data.flyers.splice(i,1);
          renderFlyers();
      });
  }

  /* ===============================
     MAIN RENDER: dates, lists, itins, gallery
  ================================= */
  function renderLists(){
      renderFlyers();
      renderDates();
      renderSimpleLists();
      renderItinerary();
      renderGallery();
  }

  /* ----------------------------
     Travel Dates
  ---------------------------- */
  function renderDates(){
      const dl = document.getElementById("datesList");
      if (!dl) return;
      dl.innerHTML = "";
      quillDates = [];

      (data.travel_dates || []).forEach((d,i)=>{
          const wrap = document.createElement("div");
          wrap.style = "padding:8px;margin-bottom:12px;border:1px solid #eee;border-radius:6px;background:#fafafa;";

          wrap.innerHTML = `
            <div class="flex-row">
              <strong style="flex:1;">Date #${i+1}</strong>
              <button class="btn cancel" data-i="${i}">Delete</button>
            </div>
          `;

          const deleteBtn = wrap.querySelector("button[data-i]");
          deleteBtn.addEventListener("click", ()=> deleteDate(i));

          const eid = uid("date");
          const holder = document.createElement("div");
          holder.id = eid;
          holder.style = "min-height:60px;margin-top:8px;";
          wrap.appendChild(holder);
          dl.appendChild(wrap);

          const q = new Quill("#"+eid,{
              theme:'snow',
              modules:{ toolbar:[['bold','italic','underline'],[{list:'bullet'},{list:'ordered'}],['link']] }
          });
          q.clipboard.dangerouslyPasteHTML(d || "");
          q.on("text-change",()=> data.travel_dates[i] = q.root.innerHTML);
          quillDates.push(q);
      });
  }
  function deleteDate(i){
      freezeScrollWhile(()=>{
          data.travel_dates.splice(i,1);
          renderLists();
      });
  }

  /* ----------------------------
     Simple Text Lists (inclusions, exclusions, extra)
  ---------------------------- */
  function renderSimpleLists(){
      const map = [
          ["inclusionsList","inclusions"],
          ["exclusionsList","exclusions"],
          ["extraList","extra_details"]
      ];

      map.forEach(([el,id])=>{
          const root = document.getElementById(el);
          if (!root) return;
          root.innerHTML = "";
          (data[id] || []).forEach((val,i)=>{
              const row = document.createElement("div");
              row.style = "display:flex;gap:6px;margin-bottom:6px;";
              const inp = document.createElement("input");
              inp.style.flex = "1";
              inp.value = val || "";
              inp.addEventListener("change", ()=> data[id][i] = inp.value);
const up = document.createElement("button");
up.className = "btn cancel";
up.textContent = "↑";
up.onclick = () => {
  freezeScrollWhile(() => {
    moveItem(data[id], i, -1);
    renderLists();
  });
};

const down = document.createElement("button");
down.className = "btn cancel";
down.textContent = "↓";
down.onclick = () => {
  freezeScrollWhile(() => {
    moveItem(data[id], i, 1);
    renderLists();
  });
};

const del = document.createElement("button");
del.className = "btn cancel";
del.textContent = "Delete";
del.onclick = () => deleteTextItem(id, i);

row.appendChild(inp);
row.appendChild(up);
row.appendChild(down);
row.appendChild(del);
root.appendChild(row);
          });
      });
  }
  function deleteTextItem(id,i){
      freezeScrollWhile(()=>{
          data[id].splice(i,1);
          renderLists();
      });
  }

  /* ----------------------------
     Itinerary (with per-day image upload)
  ---------------------------- */
  function renderItinerary(){
      const it = document.getElementById("itineraryList");
      if (!it) return;
      it.innerHTML = "";
      quillItins = [];

      (data.itinerary || []).forEach((day,i)=>{
          const card = document.createElement("div");
          card.className = "itinerary-card";

          const top = document.createElement("div");
          top.className = "flex-row";
          const titleInput = document.createElement("input");
          titleInput.type = "text";
          titleInput.value = day.day_title || "";
          titleInput.style.flex = "1";
          titleInput.addEventListener("input", ()=> data.itinerary[i].day_title = titleInput.value);
          const delBtn = document.createElement("button");
          delBtn.className = "btn cancel";
          delBtn.textContent = "Delete Day";
          delBtn.addEventListener("click", ()=> deleteDay(i));
          top.appendChild(titleInput);
          top.appendChild(delBtn);
          card.appendChild(top);

          const eid = uid("itin");
          const details = document.createElement("div");
          details.id = eid;
          details.style = "min-height:90px;border:1px solid #ddd;margin-top:8px;padding:6px;border-radius:6px;";
          card.appendChild(details);

          // image preview + upload controls
          const imgWrap = document.createElement("div");
          imgWrap.style = "margin-top:8px;display:flex;gap:8px;align-items:flex-start;";

          const preview = document.createElement("img");
          preview.className = "itin-image-preview";

          if (day.day_image && String(day.day_image).trim() !== "") {
              preview.src = normalizePathToAssetHref(day.day_image);
          } else {
              preview.src = "data:image/svg+xml;charset=UTF-8," + encodeURIComponent(`
                  <svg xmlns='http://www.w3.org/2000/svg' width='320' height='200'>
                      <rect width='100%' height='100%' fill='#f3f3f3'/>
                      <text x='50%' y='50%' fill='#bbb' font-size='14' text-anchor='middle'>No Image</text>
                  </svg>
              `);
          }
          imgWrap.appendChild(preview);

          const imgControls = document.createElement("div");
          imgControls.style = "display:flex;flex-direction:column;gap:6px;";

          const file = document.createElement("input");
          file.type = "file";
          file.accept = "image/*";
          file.className = "hidden-file";
          imgControls.appendChild(file);

          file.addEventListener("change", ()=> {
              if (file.files && file.files[0]) uploadDayImg(i, file.files[0]);
          });

          const uploadBtn = document.createElement("button");
          uploadBtn.className = "btn back";
          uploadBtn.textContent = "Upload Day Image";
          uploadBtn.addEventListener("click", ()=> file.click());
          imgControls.appendChild(uploadBtn);

          const removeBtn = document.createElement("button");
          removeBtn.className = "btn cancel";
          removeBtn.textContent = "Remove";
          removeBtn.addEventListener("click", ()=> removeDayImg(i));
          imgControls.appendChild(removeBtn);

          imgWrap.appendChild(imgControls);
          card.appendChild(imgWrap);
          it.appendChild(card);

          const q = new Quill("#"+eid,{
              theme:'snow',
              modules:{ toolbar:[['bold','italic','underline'],[{list:'bullet'},{list:'ordered'}],['link']] }
          });
          q.clipboard.dangerouslyPasteHTML(day.day_details || "");
          q.on("text-change", ()=> data.itinerary[i].day_details = q.root.innerHTML);
          quillItins.push(q);
      });
  }
  function deleteDay(i){
      freezeScrollWhile(()=>{
          data.itinerary.splice(i,1);
          renderLists();
      });
  }
  async function uploadDayImg(i, f){
      if (!f) return;
      const fd = new FormData();
      fd.append("file", f);
      fd.append("id", TOUR_ID);
      fd.append("type", "itinerary");
      fd.append("day_index", i);
      fd.append("region", data.region || "");
      fd.append("title", document.getElementById("titleField") ? document.getElementById("titleField").innerText.trim() : "");

      try {
          const res = await fetch("crud/upload_tour_media.php",{method:"POST",body:fd});
          const j = await res.json();
          if (j.success && j.path) {
              data.itinerary[i].day_image = j.path;
              freezeScrollWhile(()=> renderLists());
          } else {
              console.error("Day image upload failed", j);
              alert("Day image upload failed.");
          }
      } catch(err){
          console.error(err);
          alert("Network error during day image upload.");
      }
  }
  function removeDayImg(i){
      if (!confirm("Remove image?")) return;
      delete data.itinerary[i].day_image;
      freezeScrollWhile(()=> renderLists());
  }

  /* ----------------------------
     GALLERY
  ---------------------------- */
  function renderGallery(){
      const root = document.getElementById("galleryRow");
      if (!root) return;
      root.innerHTML = "";

      (data.images || []).forEach((src,i)=>{
          const item = document.createElement("div");
          item.style = "width:140px;text-align:center;";

          const imgSrc = normalizePathToAssetHref(src);
          item.innerHTML = `<img class="thumb-edit" src="${imgSrc}">`;

          const buttons = document.createElement("div");
          buttons.style = "display:flex;gap:6px;justify-content:center;margin-top:6px;";
          buttons.innerHTML = `
             <button class="btn cancel" data-i="${i}" data-dir="-1">↑</button>
             <button class="btn cancel" data-i="${i}" data-dir="1">↓</button>
             <button class="btn cancel" data-i="${i}" data-action="main">Main</button>
             <button class="btn cancel" data-i="${i}" data-action="del">Delete</button>
          `;
          item.appendChild(buttons);
          root.appendChild(item);

          buttons.querySelectorAll("button").forEach(b=>{
              b.addEventListener("click", ()=>{
                  const idx = Number(b.getAttribute("data-i"));
                  const dir = Number(b.getAttribute("data-dir") || 0);
                  const action = b.getAttribute("data-action") || null;
                  if (action === "main") return makeMainImage(idx);
                  if (action === "del") return deleteImage(idx);
                  if (dir !== 0) return moveImage(idx, dir);
              });
          });
      });

      const main = document.getElementById("mainImage");
      if (main && (data.images || []).length > 0) main.src = normalizePathToAssetHref(data.images[0]);
  }

  function moveImage(i,d){
      const ni = i+d;
      if (ni<0 || ni>=data.images.length) return;
      freezeScrollWhile(()=>{
          [data.images[i],data.images[ni]]=[data.images[ni],data.images[i]];
          renderLists();
      });
  }
  function makeMainImage(i){
      freezeScrollWhile(()=>{
          const img = data.images.splice(i,1)[0];
          data.images.unshift(img);
          renderLists();
      });
  }
  function deleteImage(i){
      if (!confirm("Delete image?")) return;
      freezeScrollWhile(()=>{
          data.images.splice(i,1);
          renderLists();
      });
  }

  /* ===============================
     DRAG & DROP MULTI UPLOAD (IMAGES)
  ================================= */
  const dropZone = document.getElementById("dropZone");
  const fileInputMulti = document.getElementById("uploadGalleryFile");
  const galleryStatus = document.getElementById("uploadGalleryStatus");

  if (dropZone && fileInputMulti) {
      dropZone.addEventListener("click", ()=> fileInputMulti.click());
      dropZone.addEventListener("dragover", e=>{
          e.preventDefault(); e.stopPropagation();
          dropZone.style.background="#ffe6f1";
      });
      dropZone.addEventListener("dragleave", e=>{
          e.preventDefault(); e.stopPropagation();
          dropZone.style.background="transparent";
      });
      dropZone.addEventListener("drop", e=>{
          e.preventDefault(); e.stopPropagation();
          dropZone.style.background="transparent";
          const files = e.dataTransfer && e.dataTransfer.files ? e.dataTransfer.files : [];
          handleMultipleUploads(files);
      });
      fileInputMulti.addEventListener("change", ()=> handleMultipleUploads(fileInputMulti.files));
  }

  async function handleMultipleUploads(files){
      if (!files || !files.length) return;
      galleryStatus && (galleryStatus.textContent = "Uploading " + files.length + " images...");
      for (let f of files){
          if (!f.type || !f.type.startsWith("image/")) continue;
          const fd = new FormData();
          fd.append("file", f);
          fd.append("id", TOUR_ID);
          fd.append("type", "image");
          fd.append("region", data.region || "");
          fd.append("title", document.getElementById("titleField") ? document.getElementById("titleField").innerText.trim() : "");

          try {
              const r = await fetch("crud/upload_tour_media.php",{method:"POST",body:fd});
              const j = await r.json();
              if (j.success && j.path) {
                  data.images.push(j.path);
                  freezeScrollWhile(()=> renderLists());
              } else {
                  console.error("Image upload failed", j);
              }
          } catch(err){
              console.error(err);
          }
      }
      galleryStatus && (galleryStatus.textContent = "Upload complete!");
  }

  /* ===============================
     FLYER UPLOAD + DRAG & DROP (MULTI)
  ================================= */
  // There may or may not be a dedicated upload button — attach only if exists
  const uploadFlyerBtn = document.getElementById("uploadFlyerBtn");
  const flyerDropZone = document.getElementById("flyerDropZone");
  const flyerInput = document.getElementById("uploadFlyerFile");
  const flyerStatus = document.getElementById("uploadFlyerStatus");

  if (uploadFlyerBtn) {
      uploadFlyerBtn.addEventListener("click", async ()=>{
          if (!flyerInput) return alert("No flyer input found.");
          const f = flyerInput.files[0];
          if (!f) return alert("Select a file first.");
          await uploadSingleFlyerFile(f);
      });
  }

  if (flyerDropZone && flyerInput) {
      flyerDropZone.addEventListener("click", ()=> flyerInput.click());
      flyerDropZone.addEventListener("dragover", e=>{
          e.preventDefault(); e.stopPropagation();
          flyerDropZone.style.background = "#ffe6f1";
      });
      flyerDropZone.addEventListener("dragleave", e=>{
          e.preventDefault(); e.stopPropagation();
          flyerDropZone.style.background = "transparent";
      });
      flyerDropZone.addEventListener("drop", e=>{
          e.preventDefault(); e.stopPropagation();
          flyerDropZone.style.background = "transparent";
          const files = e.dataTransfer && e.dataTransfer.files ? e.dataTransfer.files : [];
          handleFlyerUploads(files);
      });
      flyerInput.addEventListener("change", ()=> handleFlyerUploads(flyerInput.files));
  }

  async function uploadSingleFlyerFile(f){
      if (!f) return;
      const s = flyerStatus;
      s && (s.textContent = "Uploading...");
      const fd = new FormData();
      fd.append("file", f);
      fd.append("id", TOUR_ID);
      fd.append("type", "flyer");
      fd.append("region", data.region || "");
      fd.append("title", document.getElementById("titleField") ? document.getElementById("titleField").innerText.trim() : "");

      try {
          const r = await fetch("crud/upload_tour_media.php",{method:"POST",body:fd});
          const j = await r.json();
          if (j.success && j.path) {
              data.flyers = data.flyers || [];
              data.flyers.push(j.path);
              freezeScrollWhile(()=> renderFlyers());
              s && (s.textContent = "Uploaded!");
          } else {
              s && (s.textContent = "Upload failed");
              console.error("Flyer upload failed", j);
          }
      } catch(err){
          s && (s.textContent = "Network error");
          console.error(err);
      }
  }

  async function handleFlyerUploads(files) {
      if (!files || !files.length) return;
      flyerStatus && (flyerStatus.textContent = `Uploading ${files.length} flyer(s)...`);
      for (let f of files) {
          const ext = (f.name.split('.').pop() || '').toLowerCase();
          if (!(ext === "pdf" || f.type.startsWith("image/"))) continue;
          await uploadSingleFlyerFile(f);
      }
      flyerStatus && (flyerStatus.textContent = "Flyer upload complete!");
  }

  /* ===============================
     SAVE / IMPORT / CANCEL
  ================================= */
  const saveBtn = document.getElementById("saveBtn");
  if (saveBtn) saveBtn.addEventListener("click", async ()=>{
      data.title = document.getElementById("titleField") ? document.getElementById("titleField").innerText.trim() : data.title;
      data.low = document.getElementById("lowField") ? document.getElementById("lowField").innerText.trim() : data.low;
      data.duration = document.getElementById("durationField") ? document.getElementById("durationField").innerText.trim() : data.duration;
      data.flight_details = document.getElementById("flightField") ? document.getElementById("flightField").innerText.trim() : data.flight_details;
      data.details = quillDetails.root.innerHTML;

      data.images = Array.from(new Set((data.images || []).filter(x=>x && x.trim() !== "")));

      try {
          const res = await fetch("crud/save_tourinfo.php",{
              method:"POST",
              headers:{"Content-Type":"application/json"},
              body:JSON.stringify({id:TOUR_ID,data:data})
          });
          const j = await res.json();
          if (j.success){
              alert("Saved!");
              location.reload();
          } else {
              alert("Save failed: " + (j.msg || JSON.stringify(j)));
          }
      } catch(err){
          alert("Save failed (network error)");
      }
  });

  const importBtn = document.getElementById("importJsonBtn");
  const importFile = document.getElementById("importJsonFile");
  if (importBtn && importFile) {
      importBtn.addEventListener("click", ()=> importFile.click());
      importFile.addEventListener("change", async (e)=>{
          const f = e.target.files[0];
          if (!f) return;
          if (!confirm("This will overwrite all tour data. Continue?")) { e.target.value = ""; return; }
          const fd = new FormData();
          fd.append("file", f);
          fd.append("id", TOUR_ID);
          try {
              const r = await fetch("crud/import_tour_json.php",{method:"POST",body:fd});
              const j = await r.json();
              if (j.success){ alert("Imported!"); location.reload(); }
              else alert("Import failed: " + (j.msg || JSON.stringify(j)));
          } catch(err){ alert("Network error importing JSON"); }
      });
  }
  
  // Export JSON
const exportBtn = document.getElementById("exportJsonBtn");
if (exportBtn) {
  exportBtn.addEventListener("click", () => {
    // sync latest editable fields before export
    data.title = document.getElementById("titleField")?.innerText.trim() || data.title;
    data.low = document.getElementById("lowField")?.innerText.trim() || data.low;
    data.duration = document.getElementById("durationField")?.innerText.trim() || data.duration;
    data.flight_details = document.getElementById("flightField")?.innerText.trim() || data.flight_details;
    data.details = quillDetails.root.innerHTML;

    const jsonStr = JSON.stringify(data, null, 2);
    const blob = new Blob([jsonStr], { type: "application/json" });

    const a = document.createElement("a");
    a.href = URL.createObjectURL(blob);
    a.download = `${TOUR_ID}.json`; // 👈 matches Editing: label
    document.body.appendChild(a);
    a.click();

    URL.revokeObjectURL(a.href);
    a.remove();
  });
}

  const cancelBtn = document.getElementById("cancelBtn");
  if (cancelBtn) cancelBtn.addEventListener("click", ()=> {
      if (confirm("Discard changes?")) location.reload();
  });
  
  // Add Travel Date
document.getElementById("addDateBtn")?.addEventListener("click", () => {
  data.travel_dates.push("");
  freezeScrollWhile(renderLists);
});

// Add Inclusion
document.getElementById("addInclusionBtn")?.addEventListener("click", () => {
  data.inclusions.push("");
  freezeScrollWhile(renderLists);
});

// Add Exclusion
document.getElementById("addExclusionBtn")?.addEventListener("click", () => {
  data.exclusions.push("");
  freezeScrollWhile(renderLists);
});

// Add Extra Note
document.getElementById("addExtraBtn")?.addEventListener("click", () => {
  data.extra_details.push("");
  freezeScrollWhile(renderLists);
});

// Add Itinerary Day
document.getElementById("addDayBtn")?.addEventListener("click", () => {
  data.itinerary.push({
    day_title: "",
    day_details: "",
    day_image: ""
  });
  freezeScrollWhile(renderLists);
});



  // initial render
  renderLists();
} // end initAdminTourEditor
</script>



</body>
</html>
