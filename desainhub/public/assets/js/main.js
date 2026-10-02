/**
 *  Main JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {

  // ---------- AOS INIT ----------
  if (typeof AOS !== 'undefined') {
    AOS.init({
      duration: 800,
      once: true,
      easing: 'ease-out-cubic',
    });
  }

  // ---------- NAVBAR SCROLL ----------
  const navbar = document.getElementById('dhNavbar');
  if (navbar) {
    window.addEventListener('scroll', function () {
      navbar.classList.toggle('scrolled', window.scrollY > 20);
    });
  }

  // ---------- SWIPER (Testimoni) ----------
  if (typeof Swiper !== 'undefined') {
    const swiperEl = document.querySelector('.dh-testi-swiper');
    if (swiperEl) {
      new Swiper(swiperEl, {
        slidesPerView: 1,
        spaceBetween: 24,
        loop: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true },
        breakpoints: {
          768: { slidesPerView: 2 },
          992: { slidesPerView: 3 },
        },
      });
    }
  }

  // ---------- PORTFOLIO FILTER ----------
  const filterBtns = document.querySelectorAll('.dh-filter-btn');
  const galleryItems = document.querySelectorAll('.dh-gallery-item');

  if (filterBtns.length && galleryItems.length) {
    filterBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        filterBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        const filter = this.getAttribute('data-filter');
        galleryItems.forEach(function (item) {
          if (filter === 'all' || item.getAttribute('data-category') === filter) {
            item.style.display = 'block';
          } else {
            item.style.display = 'none';
          }
        });
      });
    });
  }

  // ---------- SMOOTH SCROLL ----------
  document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // ---------- FLASH MESSAGE AUTO HIDE ----------
  const flashMsg = document.querySelector('.alert-dismissible');
  if (flashMsg) {
    setTimeout(function () {
      flashMsg.style.transition = 'opacity 0.5s';
      flashMsg.style.opacity = '0';
      setTimeout(function () { flashMsg.remove(); }, 500);
    }, 5000);
  }

  // ---------- TOOLTIP INIT ----------
  if (typeof bootstrap !== 'undefined') {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (el) {
      return new bootstrap.Tooltip(el);
    });
  }

});