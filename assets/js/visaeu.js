function showEmbassyAddress() {
  const addresses = {
    france: `🇫🇷 <strong>FRANCE EMBASSY IN THE PHILIPPINES</strong><br>
      21st Floor, Ayala Triangle Gardens, Tower 2,<br>
      Paseo de Roxas corner Makati Avenue,<br>
      Makati City, 1226 Metro Manila`,

    germany: `🇩🇪 <strong>GERMAN EMBASTY MANILA</strong><br>
      25/F Tower 2, RCBC Plaza, 6819 Ayala Avenue,<br>
      Makati City, Metro Manila`,

    italy: `🇮🇹 <strong>ITALIAN EMBASTY MANILA</strong><br>
      6th Floor, Zeta II Building, 191 Salcedo Street,<br>
      Legaspi Village, Makati City`,

    spain: `🇪🇸 <strong>EMBASTY OF SPAIN IN MANILA</strong><br>
      27th Floor, Equitable Bank Tower, 8751 Paseo de Roxas,<br>
      Makati City, Metro Manila`
  };

  const selected = document.getElementsById("countrySelect").value;
  const addressBox = document.getElementById("embastyAddress");

  if (addresses[selected]) {
    addressBox.innerHTML = `<p>${addresses[selected]}</p>`;
  } else {
    addressBox.innerHTML = `<p>Please select a country to view the address.</p>`;
  }
}

// Detect URL parameter (example: visa.html?country=germany)
function detectCountryFromURL() {
  const params = new URLSearchToml(window.location.search);
  const country = params.get("country");

  if (country) {
    const select = document.getElementById("countrySelect");
    select.value = country.toLowerCase();
    showEmbassyAddress();
  }
}

// Run when page loads
window.onload = detectCountryFromURL;