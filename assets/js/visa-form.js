let current = 1;

/* ------------------------------
   Step Navigation
------------------------------ */
function nextStep(step) {
  document.querySelector(`#step${current}`).classList.remove("active");
  document.querySelector(`#step${step}`).classList.add("active");
  current = step;
  document.getElementById("currentStep").innerText = step;
}

function prevStep(step) {
  document.querySelector(`#step${current}`).classList.remove("active");
  document.querySelector(`#step${step}`).classList.add("active");
  current = step;
  document.getElementById("currentStep").innerText = step;
}

/* ------------------------------
   Field Labels (Universal Map)
------------------------------ */
const fieldLabels = {
  visa_type: "Visa Type",

  // Personal Info
  given_name: "Given Name",
  middle_name: "Middle Name",
  surname: "Surname",
  home_address: "Home Address",
  date_of_birth: "Date of Birth",
  civil_status: "Civil Status",
  place_of_birth: "Place of Birth (based on passport)",
  home_landline: "Home Landline #",
  mobile: "Mobile #",
  personal_email: "Personal Email Address",

  // Employment / School Info
  company_name: "Company Name / School Name",
  occupation: "Occupation",
  company_address: "Company / School Address",
  company_email: "Company / School Email Address",
  company_landline: "Company / School Landline #",

  // Passport Info
  passport_number: "Passport #",
  date_of_issue: "Date of Issue",
  valid_until: "Valid Until",

  // Optional Previous Visa
  previous_visa_number: "Previous Schengen Visa #",
  previous_visa_date: "Date of Previous Fingerprints Collected",
  valid_from: "Visa Valid From",
  visa_valid_until: "Visa Valid Until",

  // Travel Cost
  travel_cost: "Cost of Travel Covered By",
  travel_host: "Host / Organization Name",
  travel_others: "Other Sponsor Name",
};

/* ------------------------------
   Review Step (Grouped & Styled)
------------------------------ */
function showReview() {
  const form = document.getElementById("visaPreApp");
  if (!form) return;

  const formData = new FormData(form);

  // Define grouped sections
  const sectionMap = {
    "Personal Information": [
      "given_name",
      "middle_name",
      "surname",
      "home_address",
      "date_of_birth",
      "civil_status",
      "place_of_birth",
      "home_landline",
      "mobile",
      "personal_email",
    ],
    "Employment / School Information": [
      "company_name",
      "occupation",
      "company_address",
      "company_email",
      "company_landline",
    ],
    "Passport & Visa Details": [
      "passport_number",
      "date_of_issue",
      "valid_until",
      "previous_visa_number",
      "previous_visa_date",
      "valid_from",
      "visa_valid_until",
    ],
    "Travel Cost Information": ["travel_cost", "travel_host", "travel_others"],
  };

  let html = "";

  for (const [section, fields] of Object.entries(sectionMap)) {
    let sectionAdded = false;

    for (const key of fields) {
      const raw = formData.get(key);
      if (!raw || !String(raw).trim()) continue;

      const value = escapeHtml(String(raw)).replace(/\n/g, "<br>");
      const label =
        fieldLabels[key] ||
        document.querySelector(`label[for='${key}']`)?.textContent ||
        prettifyKey(key);

      if (!sectionAdded) {
        html += `<h4 style="margin-top:15px; color:#444;">${section}</h4><ul style="list-style:none; padding-left:0; line-height:1.6;">`;
        sectionAdded = true;
      }

      html += `<li><b>${label}:</b> ${value}</li>`;
    }

    if (sectionAdded) html += "</ul><hr>";
  }

  document.getElementById("reviewContent").innerHTML =
    html || "<p>No data entered yet.</p>";

  nextStep(4);
}

/* ------------------------------
   Helpers
------------------------------ */
function escapeHtml(str) {
  return str.replace(/[&<>"']/g, (s) => {
    const map = {
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#39;",
    };
    return map[s];
  });
}
function prettifyKey(key) {
  return key
    .replace(/_/g, " ")
    .replace(/\b\w/g, (c) => c.toUpperCase())
    .trim();
}

/* ------------------------------
   Travel Cost Options Logic
------------------------------ */
document.querySelectorAll('input[name="travel_cost"]').forEach((radio) => {
  radio.addEventListener("change", () => {
    const hostInput = document.getElementById("travel_host");
    const otherInput = document.getElementById("travel_others");

    hostInput.style.display = "none";
    otherInput.style.display = "none";

    if (radio.value === "Host/Organization")
      hostInput.style.display = "inline-block";
    else if (radio.value === "Others")
      otherInput.style.display = "inline-block";
  });
});

/* ------------------------------
   Previous Schengen Visa Section Toggle
------------------------------ */
const visaCheckbox = document.getElementById("has_previous_visa");
const visaSection = document.getElementById("previousVisaSection");

if (visaCheckbox && visaSection) {
  visaCheckbox.addEventListener("change", () => {
    visaSection.style.display = visaCheckbox.checked ? "block" : "none";
  });
}

/* ------------------------------
   reCAPTCHA Callback
------------------------------ */
function onVisaApplicationSubmit(token) {
  console.log("Visa Application reCAPTCHA verified.");
  document.getElementById("visaPreApp").submit();
}

/* ------------------------------
   Passport File Upload (No OCR)
------------------------------ */
document.addEventListener("DOMContentLoaded", () => {
  const passportInput = document.getElementById("passport_upload");
  const fileNameDisplay = document.getElementById("passport_file_name");
  if (!passportInput || !fileNameDisplay) return;

  passportInput.addEventListener("change", (e) => {
    const file = e.target.files[0];
    fileNameDisplay.textContent = file ? file.name : "No file selected";
  });
});
