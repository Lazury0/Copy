document.addEventListener('DOMContentLoaded', () => {
  // Gestion Menu Mobile
  const toggleBtn = document.getElementById('navToggle');
  const nav = document.getElementById('nav');

  if (toggleBtn && nav) {
    toggleBtn.addEventListener('click', () => {
      const isOpen = nav.classList.toggle('open');
      toggleBtn.setAttribute('aria-expanded', isOpen);
      // Change l'icône si FontAwesome est utilisé (optionnel)
      const icon = toggleBtn.querySelector('i');
      if(icon) {
        icon.classList.toggle('fa-bars');
        icon.classList.toggle('fa-xmark');
      }
    });
  }

  // Navigation fluide lors du clic sur les ancres
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      e.preventDefault();
      nav.classList.remove('open'); // Ferme le menu mobile au clic
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        target.scrollIntoView({ behavior: 'smooth' });
      }
    });
  });
});