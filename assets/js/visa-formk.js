// visa-formk.js
let current = 1;

/* ------------------------------
   Step Navigation (Shared Logic)
------------------------------ */
function nextStep(step) {
  document.querySelector(`#step${current}`).classList.remove('active');
  document.querySelector(`#step${step}`).classList.add('active');
  current = step;
  const cs = document.getElementById("currentStep");
  if (cs) cs.innerText = step;
}

function prevStep(step) {
  document.querySelector(`#step${current}`).classList.remove('active');
  document.querySelector(`#step${step}`).classList.add('active');
  current = step;
  const cs = document.getElementById("currentStep");
  if (cs) cs.innerText = step;
}

/* ------------------------------
   Field Labels (Korea form map)
------------------------------ */
const fieldLabels = {
  visa_type: "Visa Type",

  // STEP 1 - Personal
  familyname: "Family Name",
  givennames: "Given Names",
  sex: "Sex",
  dob: "Date of Birth",
  nationality: "Nationality",
  countryOfBirth: "Country of Birth",
  nationalId: "National ID No.",
  otherNamesUsed: "Used other names to enter/depart Korea?",
  otherNamesDetails: "Other names details",

  // STEP 2 - Passport
  passportType: "Passport Type",
  otherPassportTypeDetails: "Other Passport Type (details)",
  passportNumber: "Passport Number",
  passportCountry: "Country of Passport",
  placeOfIssue: "Place of Issue",
  dateOfIssue: "Date of Issue",
  dateOfExpiry: "Date of Expiry",

  otherPassportSelect: "Has other valid passport?",
  otherPassportType: "Other Passport Type",
  otherPassportNumber: "Other Passport Number",
  otherPassportCountry: "Other Passport Country",
  otherPassportExpiry: "Other Passport Expiry",

  // STEP 3 - Contact & Family
  homeAddress: "Home Country Address",
  currentAddress: "Current Residential Address",
  cellPhone: "Cell Phone No.",
  telephoneNo: "Telephone No.",
  email: "Email",

  emergencyName: "Emergency Contact - Full Name",
  emergencyCountry: "Emergency Contact - Country of Residence",
  emergencyPhone: "Emergency Contact - Telephone No.",
  emergencyRelationship: "Emergency Contact - Relationship",

  maritalStatus: "Marital Status",
  spouseFamilyName: "Spouse - Family Name",
  spouseGivenNames: "Spouse - Given Names",
  spouseDOB: "Spouse - Date of Birth",
  spouseNationality: "Spouse - Nationality",
  spouseAddress: "Spouse - Residential Address",
  spouseContact: "Spouse - Contact No.",

  hasChildren: "Has Children?",
  numberOfChildren: "Number of Children",

  // STEP 4 - Education & Employment
  highestDegree: "Highest Education",
  otherEducation: "Other Education (details)",
  schoolName: "Name of School",
  schoolLocation: "Location of School",

  personalCircumstances: "Personal Circumstances",
  otherEmployment: "Other Employment (details)",
  companyName: "Company / Institute / School",
  positionCourse: "Position / Course",
  companyAddress: "Company Address",
  companyPhone: "Company Phone No.",

  // STEP 5 - Visit / Invitation / Funding
  purposeOfVisit: "Purpose of Visit",
  otherPurpose: "Other Purpose (details)",
  periodOfStay: "Intended Period of Stay",
  intendedDate: "Intended Date of Entry",
  koreaAddress: "Address in Korea",
  koreaContact: "Contact No. in Korea",

  travelKorea5yrs: "Travelled to Korea in last 5 years?",
  travelKoreaNotes: "Details of visits to Korea (last 5 yrs)",

  travelOther: "Travelled outside country of residence (last 5 yrs)?",
  travelOtherNotes: "Details of trips outside country of residence",

  familyInKorea: "Family members staying in Korea?",
  familyInKoreaNotes: "Details of family members in Korea",

  otherFamilyInKorea: "Travelling with family members?",
  otherFamilyInKoreaNotes: "Details of family members travelling with applicant",

  invitedBy: "Is there an inviter?",
  inviterName: "Inviter - Name / Organization",
  inviterDOB: "Inviter - DOB / Business Reg. No.",
  inviterRelationship: "Inviter - Relationship",
  inviterAddress: "Inviter - Address",
  inviterPhone: "Inviter - Phone No.",

  travelCostUSD: "Estimated Travel Costs (USD)",
  sponsorName: "Sponsor - Name / Organization",
  sponsorRelationship: "Sponsor - Relationship",
  supportType: "Type of Support",
  sponsorContact: "Sponsor - Contact No."
};

/* ------------------------------
   Review Step (Auto-grouped Summary)
------------------------------ */
function showReview() {
  const form = document.getElementById('visaPreAppKorea');
  if (!form) return;

  const formData = new FormData(form);
  const sectionMap = {
    "Personal Information": [
      "familyname", "givennames", "sex", "dob", "nationality",
      "countryOfBirth", "nationalId", "otherNamesUsed", "otherNamesDetails"
    ],
    "Passport Information": [
      "passportType", "otherPassportTypeDetails", "passportNumber",
      "passportCountry", "placeOfIssue", "dateOfIssue", "dateOfExpiry",
      "otherPassportSelect", "otherPassportType", "otherPassportNumber",
      "otherPassportCountry", "otherPassportExpiry"
    ],
    "Contact & Family Details": [
      "homeAddress", "currentAddress", "cellPhone", "telephoneNo", "email",
      "emergencyName", "emergencyCountry", "emergencyPhone", "emergencyRelationship",
      "maritalStatus", "spouseFamilyName", "spouseGivenNames", "spouseDOB",
      "spouseNationality", "spouseAddress", "spouseContact", "hasChildren", "numberOfChildren"
    ],
    "Education & Employment": [
      "highestDegree", "otherEducation", "schoolName", "schoolLocation",
      "personalCircumstances", "otherEmployment", "companyName", "positionCourse",
      "companyAddress", "companyPhone"
    ],
    "Visit & Invitation Details": [
      "purposeOfVisit", "otherPurpose", "periodOfStay", "intendedDate", "koreaAddress",
      "koreaContact", "travelKorea5yrs", "travelKoreaNotes", "travelOther", "travelOtherNotes",
      "familyInKorea", "familyInKoreaNotes", "otherFamilyInKorea", "otherFamilyInKoreaNotes",
      "invitedBy", "inviterName", "inviterDOB", "inviterRelationship", "inviterAddress", "inviterPhone",
      "travelCostUSD", "sponsorName", "sponsorRelationship", "supportType", "sponsorContact"
    ]
  };

  let html = "";

  for (const [section, fields] of Object.entries(sectionMap)) {
    let sectionAdded = false;

    // Conditional skipping for clarity
    if (section === "Passport Information" && formData.get("otherPassportSelect") === "No") {
      html += `<h4 style="margin-top:15px; color:#444;">Passport Information</h4><ul><li><b>Other Passport:</b> No</li></ul><hr>`;
      continue;
    }

    for (const key of fields) {
      const raw = formData.get(key);
      if (!raw || !String(raw).trim()) continue;

      // Skip empty travel/family details if user said No
      if (key === "travelKoreaNotes" && formData.get("travelKorea5yrs") === "No") continue;
      if (key === "travelOtherNotes" && formData.get("travelOther") === "No") continue;
      if (key === "familyInKoreaNotes" && formData.get("familyInKorea") === "No") continue;
      if (key === "otherFamilyInKoreaNotes" && formData.get("otherFamilyInKorea") === "No") continue;
      if (key.startsWith("inviter") && formData.get("invitedBy") === "No") continue;

      const value = escapeHtml(String(raw)).replace(/\n/g, "<br>");
      const label = fieldLabels[key] ||
        (document.querySelector(`label[for='${key}']`)?.textContent) ||
        prettifyKey(key);

      if (!sectionAdded) {
        html += `<h4 style="margin-top:15px; color:#444;">${section}</h4><ul style="list-style:none; padding-left:0; line-height:1.6;">`;
        sectionAdded = true;
      }

      html += `<li><b>${label}:</b> ${value}</li>`;
    }

    if (sectionAdded) html += "</ul><hr>";
  }

  const target = document.getElementById('reviewData') || document.getElementById('reviewContent');
  if (target) target.innerHTML = html || "<p>No data entered yet.</p>";

  nextStep(6);
}

/* ------------------------------
   Small helpers & toggles
------------------------------ */
function escapeHtml(str) {
  return str.replace(/[&<>"']/g, s => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[s]));
}
function prettifyKey(key) {
  return key.replace(/_/g, " ").replace(/\b\w/g, c => c.toUpperCase());
}

/* ------------------------------
   Dynamic Field Toggles (Korea Specific)
------------------------------ */
function toggleOtherNames() {
  const el = document.getElementById('otherNamesDetails');
  el.style.display = (document.querySelector('select[name="otherNamesUsed"]').value === 'Yes') ? 'block' : 'none';
}
function toggleSpouseFields() {
  const status = document.getElementById('maritalStatus')?.value;
  const el = document.getElementById('spouseFields');
  if (el) el.style.display = (status === 'Married') ? 'block' : 'none';
}
function toggleChildrenNumber() {
  const el = document.getElementById('childrenNumberField');
  el.style.display = (document.getElementById('hasChildren')?.value === 'Yes') ? 'block' : 'none';
}
function toggleOtherEducation() {
  const el = document.getElementById('otherEducationField');
  el.style.display = (document.getElementById('highestDegree')?.value === 'Other') ? 'block' : 'none';
}
function toggleOtherEmployment() {
  const el = document.getElementById('otherEmploymentField');
  el.style.display = (document.getElementById('personalCircumstances')?.value === 'Other') ? 'block' : 'none';
}
function toggleOtherPurpose() {
  const el = document.getElementById('otherPurposeField');
  el.style.display = (document.getElementById('purposeOfVisit')?.value === 'Other') ? 'block' : 'none';
}
function toggleTravelKoreaDetails() {
  const el = document.getElementById('travelKoreaDetails');
  el.style.display = (document.getElementById('travelKorea5yrs')?.value === 'Yes') ? 'block' : 'none';
}
function toggleOtherTravel() {
  const el = document.getElementById('otherTravelDetails');
  el.style.display = (document.querySelector('select[name="travelOther"]').value === 'Yes') ? 'block' : 'none';
}
function toggleFamilyInKorea() {
  const el = document.getElementById('familyInKoreaDetails');
  el.style.display = (document.querySelector('select[name="familyInKorea"]').value === 'Yes') ? 'block' : 'none';
}
function toggleOtherFamilyInKorea() {
  const el = document.getElementById('otherFamilyInKoreaDetails');
  el.style.display = (document.querySelector('select[name="otherFamilyInKorea"]').value === 'Yes') ? 'block' : 'none';
}
function toggleInviterDetails() {
  const el = document.getElementById('inviterDetails');
  el.style.display = (document.querySelector('select[name="invitedBy"]').value === 'Yes') ? 'block' : 'none';
}
function checkPassportType() {
  const el = document.getElementById('otherPassportTypeDetails');
  el.style.display = (document.getElementById('passportTypeSelect')?.value === 'Other') ? 'block' : 'none';
}
function checkOtherPassport() {
  const el = document.getElementById('otherPassportDetails');
  el.style.display = (document.getElementById('otherPassportSelect')?.value === 'Yes') ? 'block' : 'none';
}

/* ------------------------------
   reCAPTCHA Callback
------------------------------ */
function onVisaApplicationSubmit(token) {
  console.log("Korean Visa Application reCAPTCHA verified.");
  const form = document.getElementById("visaPreAppKorea");
  if (form) form.submit();
}

/* ------------------------------
   Initialize on Load
------------------------------ */
document.addEventListener("DOMContentLoaded", () => {
  for (let i = 2; i <= 6; i++) {
    const step = document.getElementById(`step${i}`);
    if (step) step.classList.remove('active');
  }

  const firstStep = document.getElementById("step1");
  if (firstStep) firstStep.classList.add('active');

  const cs = document.getElementById("currentStep");
  if (cs) cs.innerText = 1;

  const ids = [
    'otherNamesDetails','otherPassportDetails','spouseFields','childrenNumberField',
    'otherEducationField','otherEmploymentField','otherPurposeField','travelKoreaDetails',
    'otherTravelDetails','familyInKoreaDetails','otherFamilyInKoreaDetails','inviterDetails'
  ];
  ids.forEach(id => {
    const el = document.getElementById(id);
    if (el) el.style.display = 'none';
  });
});
