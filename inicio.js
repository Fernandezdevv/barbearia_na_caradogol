// Animações de Scroll
window.sr = ScrollReveal({ reset: true });

sr.reveal('.inicio', {
    duration: 2000,
    origin: 'top',
    distance: '50px'
});

sr.reveal('#serv', {
    duration: 2000,
    origin: 'right',
    distance: '50px'
});

const fadeInElements = document.querySelectorAll('.fade-in');

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('mostrar');
        }
    });
}, { threshold: 0.3 });

fadeInElements.forEach(el => observer.observe(el));


// Elementos do menu
const menuButton = document.getElementById('menu-button');
const navLinks = document.getElementById('nav-links');

// Alternar menu mobile
if (menuButton && navLinks) {
    menuButton.addEventListener('click', () => {
        navLinks.classList.toggle('open');
        menuButton.classList.toggle('open');
    });

    // Fechar o menu ao clicar em um item
    const navItems = document.querySelectorAll('.nav-links a');
    navItems.forEach(item => {
        item.addEventListener('click', (e) => {
            e.stopPropagation();
            navLinks.classList.remove('open');
            menuButton.classList.remove('open');
        });
    });

    // Fechar o menu ao clicar fora
    document.addEventListener('click', (e) => {
        if (!menuButton.contains(e.target) && !navLinks.contains(e.target)) {
            navLinks.classList.remove('open');
            menuButton.classList.remove('open');
        }
    });
}

// Outra animação (repetida?) — se quiser manter separado
const elementos = document.querySelectorAll('.fade-in');

const observerSobre = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('mostrar');
        }
    });
}, { threshold: 0.3 });

elementos.forEach(el => observerSobre.observe(el));


const closeMenu = document.getElementById('close-menu');

if (closeMenu) {
  closeMenu.addEventListener('click', () => {
    navLinks.classList.remove('open');
    menuButton.classList.remove('open');
  });
}
document.addEventListener("DOMContentLoaded", () => {
  const elements = document.querySelectorAll('.fade-top, .fade-delay, .fade-img, .fade-button');
  elements.forEach((el, i) => {
    el.style.opacity = 0;
    el.style.transform = "translateY(20px)";
    el.style.transition = `all 0.6s ease ${i * 0.2}s`;
    setTimeout(() => {
      el.style.opacity = 1;
      el.style.transform = "translateY(0)";
    }, 200);
  });
});
