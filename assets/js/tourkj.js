document.addEventListener("DOMContentLoaded", async () => {
  try {
    const toursGrid = document.querySelector(".tours-grid");
    if (!toursGrid) return;

    const response = await fetch("assets/data/tours-korjap.json");
    if (!response.ok) throw new Error("Failed to fetch JSON");

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
    console.error("Tours generation failed:", error);
    const toursGrid = document.querySelector(".tours-grid");
    if (toursGrid) toursGrid.innerHTML = "<p>Failed to load tour data.</p>";
  }
});