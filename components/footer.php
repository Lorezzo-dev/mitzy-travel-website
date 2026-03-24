<footer class="site-footer">
  <div class="footer-left">
    <p>&copy; 2025 Mitzy Travel and Tours Inc. All Rights Reserved</p>
  </div>

  <div class="footer-right">
    <p>Accreditations:</p>
    <div class="accreditation-logos">
      <img src="assets/images/icons/PTAA.png" alt="PTAA Logo">
      <img src="assets/images/icons/DOT.png" alt="DOT Logo" style="height:40px; width:auto;">
      <img src="assets/images/icons/TBP.png" alt="TBP Logo" style="height:25px; width:auto;">
    </div>
  </div>
</footer>

<!-- ===== Messenger Chat (Floating Button) ===== -->
<div id="custom-messenger-chat">
  <div id="chat-button">
    <img src="assets/images/icons/messenger.png" alt="Messenger">
  </div>

  <div id="chat-popup">
    <button class="chat-close">&times;</button>
    <p>👋 Hi! Need help planning your trip?<br>Message us on Messenger.</p>
    <a href="https://m.me/465770226859221" target="_blank">Open Messenger</a>
  </div>
</div>

<script>
  // === Messenger Popup Open/Close ===
  const chatButton = document.getElementById('chat-button');
  const chatPopup = document.getElementById('chat-popup');
  const chatClose = document.querySelector('.chat-close');

  chatButton.addEventListener('click', () => {
    chatPopup.style.display = chatPopup.style.display === 'block' ? 'none' : 'block';
  });

  chatClose.addEventListener('click', () => {
    chatPopup.style.display = 'none';
  });

  // === Footer Dodge (Smart version) ===
  document.addEventListener("DOMContentLoaded", () => {
    const chatButtonContainer = document.getElementById('custom-messenger-chat');
    const footer = document.querySelector('.site-footer');

    if (!chatButtonContainer || !footer) return;

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            // Footer visible → lift Messenger above footer height + 20px
            chatButtonContainer.style.bottom = `${entry.boundingClientRect.height + 20}px`;
          } else {
            // Footer not visible → keep Messenger near bottom right
            chatButtonContainer.style.bottom = '20px';
          }
        });
      },
      { threshold: 0.1 }
    );

    observer.observe(footer);
  });
  

</script>

