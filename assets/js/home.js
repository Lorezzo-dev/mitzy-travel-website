
//-----------------------------------------------------------------------------------
//Hero Auto-Carousel
const heroSlide = document.querySelector('.hero-slide');
const slidesH = document.querySelectorAll('.hero-slide img');
let currentHeroIndex = 0;
function updateHeroSlide() {
  if (currentHeroIndex === slidesH.length - 1) {
    heroSlide.style.transition = 'transform 1s ease-in-out';
    heroSlide.style.transform = `translateX(-${currentHeroIndex * 100}%)`;
    setTimeout(() => {
      heroSlide.style.transition = 'none'; 
      heroSlide.style.transform = `translateX(0%)`;
      currentHeroIndex = 0; 
    }, 1000); 
  } else {
    heroSlide.style.transition = 'transform 1s ease-in-out';
    heroSlide.style.transform = `translateX(-${(currentHeroIndex + 1) * 100}%)`;
    currentHeroIndex++;
  }
}
setInterval(updateHeroSlide, 7000);

//-----------------------------------------------------------------------------------
// Carousel logic
const track = document.querySelector(".carousel-track");
const slides = Array.from(track.children);
const prevBtn = document.querySelector(".prev");
const nextBtn = document.querySelector(".next");
let currentIndex = 0;
const slidesPerView = 3; 

function updateCarousel() {
  const maxIndex = slides.length - slidesPerView; 
  if (currentIndex < 0) {
    currentIndex = maxIndex; 
  } else if (currentIndex > maxIndex) {
    currentIndex = 0; 
  }
  track.style.transform = `translateX(-${(currentIndex * 100) / slidesPerView}%)`;
}
nextBtn.addEventListener("click", () => {
  currentIndex += slidesPerView; 
  updateCarousel();
});
prevBtn.addEventListener("click", () => {
  currentIndex -= slidesPerView; 
  updateCarousel();
});

//-----------------------------------------------------------------------------------

document.addEventListener("DOMContentLoaded", async () => {
  const toursGrid = document.querySelector(".tours-grid");
  if (!toursGrid) return;

  try {
    const response = await fetch("/mitzytravelandtours/assets/data/tours-asia.json");
    const tours = await response.json();

    toursGrid.innerHTML = ""; // clear placeholder

    tours.forEach(tour => {
      const card = document.createElement("a");
      card.href = tour.link;
      card.className = "tour-card";

      card.innerHTML = `
        <img src="${tour.image}" alt="${tour.title}">
        <div class="tour-card-body">
          <h3>${tour.title}</h3>
          <p>${tour.subtitle}</p>
        </div>
      `;

      toursGrid.appendChild(card);
    });
  } catch (error) {
    console.error("Failed to load tours:", error);
    toursGrid.innerHTML = "<p>Failed to load tour data.</p>";
  }
});

//-----------------------------------------------------------------------------------
// Group Tour Auto-Carousel
const groupSlide = document.querySelector('.group-slide');
const groupImages = document.querySelectorAll('.group-slide img');
let currentGroupIndex = 0;

function updateGroupSlide() {
  if (!groupSlide || groupImages.length === 0) return;

  if (currentGroupIndex === groupImages.length - 1) {
    groupSlide.style.transition = 'transform 1s ease-in-out';
    groupSlide.style.transform = `translateX(-${currentGroupIndex * 100}%)`;
    setTimeout(() => {
      groupSlide.style.transition = 'none';
      groupSlide.style.transform = `translateX(0%)`;
      currentGroupIndex = 0;
    }, 1000);
  } else {
    groupSlide.style.transition = 'transform 1s ease-in-out';
    groupSlide.style.transform = `translateX(-${(currentGroupIndex + 1) * 100}%)`;
    currentGroupIndex++;
  }
}
setInterval(updateGroupSlide, 6000);