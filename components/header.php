<!-- components/header.php -->
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="https://fonts.googleapis.com/css2?family=Bevan&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/mtzy.css">
  <link rel="stylesheet" href="/assets/css/mobile.css">
  <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-R1K7NTVBC5"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-R1K7NTVBC5');
</script>
</head>
<body>
<header>
  <!-- Top Bar -->
  <div class="top-bar">
    <a href="https://www.facebook.com/mitzytravelandtours" target="_blank" class="fb-icon">
      <img src="assets/images/icons/facebook.png" alt="Facebook">
    </a>
    <a href="https://www.instagram.com/mitzytravelandtours/" target="_blank" class="ig-icon">
      <img src="assets/images/icons/instagram.png" alt="Instagram">
    </a>
    <a href="mailto:mitzytravelandtours@gmail.com" class="email-icon">
      <img src="assets/images/icons/email.png" alt="Email"><b>mitzytravelandtours@gmail.com</b>
    </a>

<a href="tel:+63272760923" class="landline-icon">
  <img src="assets/images/icons/telephonewhite.png" alt="Landline">
  <b>(02) 7276 0923</b>
</a>

<a href="tel:+63285243835" class="landline-icon">
  <img src="assets/images/icons/telephonewhite.png" alt="Landline">
  <b>(02) 8524 3835</b>
</a>
  </div>

  <!-- Header Container -->
  <div class="header-container">
    <a href="home.php" class="logo">
      <img src="assets/images/icons/mtntlogo.png" alt="Mitzy Travel and Tours Logo">
    </a>
    
    <!-- MOBILE NAV -->
<div class="mobile-nav">

  <button id="mobile-menu-btn" class="mobile-icon">
    ☰
  </button>
  
 <button id="mobile-search-btn" class="mobile-icon">
  Search
</button>
</div>

<!-- MOBILE MENU PANEL -->
<div class="mobile-menu-panel">
  <button class="mobile-close">&times;</button>
  <a href="home.php">Home</a>
  <a href="tours.php">Experiences</a>
  <a href="visa.php">Visa Services</a>
  <a href="about.php">About Us</a>
  <a href="contact.php">Contact Us</a>
</div>

<!-- MOBILE SEARCH PANEL -->
<div class="mobile-search-panel">
  <button class="mobile-close">&times;</button>
  <form action="search.php" method="GET">
    <input type="text" name="q" placeholder="Search tours or visas..." />
  </form>
</div>

    <!-- Navigation Wrapper -->
    <div class="nav-wrapper">
      <!-- Main Navigation -->
<nav class="main-nav">
  <a href="home.php">Home</a>

  <div class="dropdown">
    <a href="tours.php" class="dropbtn">Experiences</a>
    <div class="dropdown-content">
      <!--<a href="tourph.php">Local Tour Package</a> -->
      <a href="tourasia.php">Asian Experiences</a>
      <a href="tourkj.php">Japan / Korean Experiences</a>
      <a href="toureu.php">European & Western Experiences</a>
      <a href="touroc.php">Oceanian Experiences</a>
    </div>
  </div>

  <a href="visa.php">Visa Services</a>
  <a href="about.php">About Us</a>
  <a href="contact.php">Contact Us</a>

  <button id="search-btn" class="icon-btn">
    <img src="assets/images/icons/search.png" alt="Search">
  </button>
</nav>

<!-- Search Navbar (overlayed) -->
<div class="search-nav">
  <form action="search.php" method="GET" class="search-form">
    <input type="text" name="q" placeholder="Search tours or visas..." class="search-input" autocomplete="off" spellcheck="false" autocorrect="off" autocapitalize="off" />
    <button type="submit" class="icon-btn">
      <img src="assets/images/icons/search.png" alt="Search">
    </button>
  </form>
</div>
</header>

<script src="/assets/js/header.js?v=2"></script>

