
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('navToggle');
  const nav = document.getElementById('nav');
  if(!btn || !nav) return;
  btn.addEventListener('click', () => {
    const open = nav.classList.toggle('open');
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
});
