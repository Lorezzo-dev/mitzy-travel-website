  document.addEventListener("DOMContentLoaded", async () => {
  const toursGrid = document.querySelector(".tours-grid");
  if (!toursGrid) return;

  try {
    const response = await fetch("assets/data/tours-asia.json");
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