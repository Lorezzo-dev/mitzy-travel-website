// visa-formj.js
let current = 1;

/* ------------------------------
   Step Navigation
------------------------------ */
function nextStep(step) {
  document.querySelector(`#step${current}`).classList.remove('active');
  document.querySelector(`#step${step}`).classList.add('active');
  current = step;
  document.getElementById("currentStep").innerText = step;
}

function prevStep(step) {
  document.querySelector(`#step${current}`).classList.remove('active');
  document.querySelector(`#step${step}`).classList.add('active');
  current = step;
  document.getElementById("currentStep").innerText = step;
}

/* ------------------------------
   Review Step (Auto-generated summary)
------------------------------ */
function showReviewJapan() {
  const form = document.getElementById('visaPreAppJapan');
  const formData = new FormData(form);

  const sectionMap = {
    "Personal Information": [
      "surname", "givenNames", "dob", "placeOfBirth", "nationality", "sex",
      "maritalStatus", "otherNames"
    ],
    "Passport Details": [
      "passportType", "passportNumber", "passportAuthority", "passportPlace",
      "passportIssueDate", "passportExpiryDate", "idNumber"
    ],
    "Contact & Employment": [
      "residentialAddress", "telephone", "mobile", "email",
      "occupation", "companyName", "companyAddress", "companyPhone", "partnerOccupation"
    ],
    "Visit Details": [
      "purposeOfVisit", "arrivalDate", "stayLength", "addressInJapan", "contactInJapan"
    ],
    "Guarantor": [
      "hasGuarantor", "guarantorName", "guarantorDOB", "guarantorSex",
      "guarantorRelation", "guarantorAddress", "guarantorPhone",
      "guarantorOccupation", "guarantorStatus"
    ],
    "Inviter": [
      "hasInviter", "inviterName", "inviterDOB", "inviterSex", "inviterRelation",
      "inviterAddress", "inviterPhone", "inviterOccupation", "inviterStatus"
    ],
    "Remarks": ["specialRemarks"]
  };

  let html = "";

  for (const [section, fields] of Object.entries(sectionMap)) {
    let sectionAdded = false;

    // Conditional skip: if Has Guarantor or Has Inviter = No, skip their subfields
    if (section === "Guarantor" && formData.get("hasGuarantor") === "No") {
      html += `<h4 style="margin-top:15px; color:#444;">Guarantor</h4><ul><li><b>Has Guarantor:</b> No</li></ul><hr>`;
      continue;
    }
    if (section === "Inviter" && formData.get("hasInviter") === "No") {
      html += `<h4 style="margin-top:15px; color:#444;">Inviter</h4><ul><li><b>Has Inviter:</b> No</li></ul><hr>`;
      continue;
    }

    for (const key of fields) {
      const value = formData.get(key);
      if (!value || value.trim() === "") continue;

      if (!sectionAdded) {
        html += `<h4 style="margin-top:15px; color:#444;">${section}</h4><ul style="list-style:none; padding-left:0;">`;
        sectionAdded = true;
      }

      const label =
        document.querySelector(`label[for='${key}']`)?.textContent ||
        prettifyKey(key);

      html += `<li><b>${label}:</b> ${value}</li>`;
    }
    if (sectionAdded) html += "</ul><hr>";
  }

  document.getElementById("reviewJapanData").innerHTML = html || "<p>No data entered yet.</p>";
  nextStep(5);
}

/* ------------------------------
   Helper
------------------------------ */
function prettifyKey(key) {
  return key
    .replace(/([A-Z])/g, " $1")
    .replace(/_/g, " ")
    .replace(/\b\w/g, c => c.toUpperCase());
}

/* ------------------------------
   Conditional Toggles
------------------------------ */
function toggleGuarantorFields() {
  const val = document.getElementById("hasGuarantor").value;
  document.getElementById("guarantorFields").style.display = val === "Yes" ? "block" : "none";
}

function toggleInviterFields() {
  const val = document.getElementById("hasInviter").value;
  document.getElementById("inviterFields").style.display = val === "Yes" ? "block" : "none";
}

/* ------------------------------
   reCAPTCHA Callback
------------------------------ */
function onVisaApplicationSubmit(token) {
  console.log("Japan Visa Application reCAPTCHA verified.");
  const form = document.getElementById("visaPreAppJapan");

  // Optional N/A filler for hidden fields
  ["guarantorFields", "inviterFields"].forEach(id => {
    const section = document.getElementById(id);
    if (section && section.style.display === "none") {
      section.querySelectorAll("input, textarea, select").forEach(el => {
        if (!el.value.trim()) el.value = "N/A";
      });
    }
  });

  form.submit();
}

/* ------------------------------
   Initialize
------------------------------ */
document.addEventListener("DOMContentLoaded", () => {
  for (let i = 2; i <= 5; i++) {
    const step = document.getElementById(`step${i}`);
    if (step) step.classList.remove("active");
  }
  const firstStep = document.getElementById("step1");
  if (firstStep) firstStep.classList.add("active");
});
