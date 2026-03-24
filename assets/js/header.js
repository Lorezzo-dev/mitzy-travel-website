// ==========================================================
// HEADER NAVIGATION HIGHLIGHT + SHRINK ON SCROLL
// ==========================================================
const links = document.querySelectorAll("nav a");
links.forEach(link => {
  if (link.href.split("/").pop() === window.location.href.split("/").pop()) {
    link.classList.add("active");
  }
});

const header = document.querySelector("header");
const shrinkOn = 50;

window.addEventListener("scroll", () => {
  if (window.scrollY > shrinkOn) {
    header.classList.add("shrink");
  } else {
    header.classList.remove("shrink");
  }
});

// ==========================================================
// SEARCH TOGGLE BUTTON (Open/Close Animation)
// ==========================================================
document.addEventListener('DOMContentLoaded', () => {
  const searchBtn = document.getElementById('search-btn');
  const searchImg = searchBtn.querySelector('img');
  const searchNav = document.querySelector('.search-nav');

  const searchIcon = 'assets/images/icons/search.png';
  const closeIcon = 'assets/images/icons/close.png';

  let searchActive = false;

  searchBtn.addEventListener('click', () => {
    searchActive = !searchActive;

    // Animate icon transition
    searchImg.classList.add('icon-switch');
    setTimeout(() => {
      searchImg.src = searchActive ? closeIcon : searchIcon;
      searchImg.classList.remove('icon-switch');
    }, 200);

    // Toggle search bar visibility
    searchNav.classList.toggle('active');
  });
});

// ==========================================================
// LIVE SEARCH (Tours + Visa Services)
// ==========================================================
const searchInput = document.querySelector('.search-input');
const searchNav = document.querySelector('.search-nav');
let suggestionBox;

if (searchInput) {
  // Create suggestion container dynamically
  suggestionBox = document.createElement('div');
  suggestionBox.classList.add('search-suggestions');
  searchNav.appendChild(suggestionBox);

  // Listen for user input
  searchInput.addEventListener('input', async () => {
    const query = searchInput.value.trim();

    // Clear if too short
    if (query.length < 2) {
      suggestionBox.innerHTML = '';
      return;
    }

    try {
      const res = await fetch(`backend/live-search.php?q=${encodeURIComponent(query)}`);
      const results = await res.json();

      // ========================================
      // RESULTS FOUND
      // ========================================
      if (results.length > 0) {
        const tours = results.filter(r => r.type === 'tour');
        const visas = results.filter(r => r.type === 'visa');

        suggestionBox.innerHTML = `
          <div class="suggestion-columns">
            <div class="suggestion-col tours-col">
              <h5 class="suggestion-header">Tour Packages</h5>
              <div class="suggestion-list" id="tour-list"></div>
            </div>
            <div class="suggestion-col visa-col">
              <h5 class="suggestion-header">Visa Services</h5>
              <div class="suggestion-list" id="visa-list"></div>
            </div>
          </div>
        `;

        const tourList = suggestionBox.querySelector('#tour-list');
        const visaList = suggestionBox.querySelector('#visa-list');

        // --- Render Tour Results ---
        tours.forEach(r => {
          tourList.innerHTML += `
            <a href="${r.link}" class="suggestion-item">
              <img src="${r.image}" alt="${r.title}">
              <div class="suggestion-text">
                <h4>${r.title}</h4>
                <p>${r.subtitle}</p>
              </div>
            </a>`;
        });

        // --- Render Visa Results ---
        visas.forEach(r => {
          visaList.innerHTML += `
            <a href="${r.link}" class="suggestion-item visa-result">
              <img src="${r.image}" alt="${r.title}">
              <div class="suggestion-text">
                <h4>${r.title}</h4>
                <p>${r.subtitle}</p>
              </div>
            </a>`;
        });

      } else {
        // ========================================
        // NO RESULTS FOUND
        // ========================================
        suggestionBox.innerHTML = `<div class="no-results">No matches found</div>`;
      }

    } catch (e) {
      // ========================================
      // ERROR HANDLING
      // ========================================
      console.error("Live search error:", e);
      suggestionBox.innerHTML = `<div class="no-results">Error loading suggestions</div>`;
    }
  });

  // Hide suggestions when input loses focus
  searchInput.addEventListener('blur', () => {
    setTimeout(() => (suggestionBox.innerHTML = ''), 200);
  });
}

  // === Universal Weblink Tracking (Socials + Contact + Messenger) ===
  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('a[href^="http"], a[href^="mailto:"], a[href^="tel:"]').forEach(link => {
      link.addEventListener("click", () => {
        const href = link.getAttribute("href");

        let label = "External Link";
        if (href.includes("facebook.com")) label = "Facebook";
        else if (href.includes("instagram.com")) label = "Instagram";
        else if (href.includes("m.me") || href.includes("messenger.com")) label = "Messenger";
        else if (href.startsWith("mailto:")) label = "Email";
        else if (href.startsWith("tel:")) label = "Phone";

        if (typeof gtag === "function") {
          gtag("event", "weblink_click", {
            event_category: "Weblink",
            event_label: label
          });
          console.log(`GA4 Event Sent: weblink_click (${label})`);
        }
      });
    });
  });

  // === Share Button Tracking (Optional, Future-Proof) ===
  document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-share]").forEach(button => {
      button.addEventListener("click", () => {
        const platform = button.getAttribute("data-share") || "unknown";
        if (typeof gtag === "function") {
          gtag("event", "share_click", {
            event_category: "Share",
            event_label: platform
          });
          console.log(`GA4 Event Sent: share_click (${platform})`);
        }
      });
    });
  });
  
// ================= MOBILE SIDEBAR =================
document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.getElementById("menu-toggle");
  const navWrapper = document.querySelector(".nav-wrapper");

  if (menuToggle && navWrapper) {
    menuToggle.addEventListener("click", function () {
      navWrapper.classList.toggle("active");
    });
  }
});

document.addEventListener("DOMContentLoaded", function () {

  const mobileMenuBtn = document.getElementById("mobile-menu-btn");
  const mobileSearchBtn = document.getElementById("mobile-search-btn");
  const mobileMenuPanel = document.querySelector(".mobile-menu-panel");
  const mobileSearchPanel = document.querySelector(".mobile-search-panel");

  function closeAllPanels() {
    mobileMenuPanel?.classList.remove("active");
    mobileSearchPanel?.classList.remove("active");
  }

  // Toggle menu
  mobileMenuBtn?.addEventListener("click", function (e) {
    e.stopPropagation();
    mobileMenuPanel.classList.toggle("active");
    mobileSearchPanel?.classList.remove("active");
  });

  // Toggle search
  mobileSearchBtn?.addEventListener("click", function (e) {
    e.stopPropagation();
    mobileSearchPanel.classList.toggle("active");
    mobileMenuPanel?.classList.remove("active");
  });

  // CLOSE BUTTON (event delegation — safer)
  document.addEventListener("click", function (e) {
    if (e.target.classList.contains("mobile-close")) {
      closeAllPanels();
    }
  });

  // Click outside closes panels
  document.addEventListener("click", function (e) {
    if (
      !mobileMenuPanel?.contains(e.target) &&
      !mobileSearchPanel?.contains(e.target) &&
      !mobileMenuBtn?.contains(e.target) &&
      !mobileSearchBtn?.contains(e.target)
    ) {
      closeAllPanels();
    }
  });

});