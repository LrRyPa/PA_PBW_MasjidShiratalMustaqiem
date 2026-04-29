document.addEventListener('DOMContentLoaded', function () {

  const navbar = document.getElementById('navbar');
  if (navbar) {
    const handleScroll = () => {
      navbar.classList.toggle('scrolled', window.scrollY > 60);
    };
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
  }

  const toggler   = document.getElementById('nav-toggler');
  const drawer    = document.getElementById('nav-drawer');
  const drawerClose = document.getElementById('nav-drawer-close');
  const drawerLinks = drawer ? drawer.querySelectorAll('a') : [];

  function openDrawer() {
    drawer.classList.add('open');
    toggler.classList.add('open');
    toggler.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  function closeDrawer() {
    drawer.classList.remove('open');
    toggler.classList.remove('open');
    toggler.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  if (toggler && drawer) {
    toggler.addEventListener('click', () => {
      drawer.classList.contains('open') ? closeDrawer() : openDrawer();
    });
  }

  if (drawerClose) {
    drawerClose.addEventListener('click', closeDrawer);
  }

  drawerLinks.forEach(link => {
    link.addEventListener('click', closeDrawer);
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && drawer && drawer.classList.contains('open')) {
      closeDrawer();
    }
  });

  const currentPage = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-menu a, .nav-drawer a').forEach(link => {
    const href = link.getAttribute('href');
    if (href === currentPage || (currentPage === '' && href === 'index.html')) {
      link.classList.add('active');
    }
  });

  const backToTop = document.getElementById('back-to-top');
  if (backToTop) {
    window.addEventListener('scroll', () => {
      backToTop.classList.toggle('visible', window.scrollY > 400);
    }, { passive: true });
    backToTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
  }

  const fadeEls = document.querySelectorAll('.fade-up');
  if (fadeEls.length > 0) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible');
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.10, rootMargin: '0px 0px -30px 0px' });
    fadeEls.forEach(el => observer.observe(el));
  }

  const lightbox = document.getElementById('lightbox');
  if (lightbox) {
    const lightboxImg     = lightbox.querySelector('.lightbox-img');
    const lightboxCaption = lightbox.querySelector('.lightbox-caption');
    const lightboxClose   = lightbox.querySelector('.lightbox-close');
    const lightboxPrev    = lightbox.querySelector('.lightbox-prev');
    const lightboxNext    = lightbox.querySelector('.lightbox-next');

    let galleryImages = [];
    let currentIndex  = 0;

    function openLightbox(index) {
      currentIndex = index;
      const item = galleryImages[index];
      lightboxImg.src = item.src;
      if (lightboxCaption) lightboxCaption.textContent = item.caption || '';
      lightbox.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
      lightbox.classList.remove('active');
      document.body.style.overflow = '';
      setTimeout(() => { if (lightboxImg) lightboxImg.src = ''; }, 300);
    }

    function showPrev() { currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length; openLightbox(currentIndex); }
    function showNext() { currentIndex = (currentIndex + 1) % galleryImages.length; openLightbox(currentIndex); }

    document.querySelectorAll('[data-lightbox]').forEach((item, i) => {
      galleryImages.push({
        src:     item.getAttribute('data-src') || item.querySelector('img')?.src,
        caption: item.getAttribute('data-caption') || ''
      });
      item.addEventListener('click', () => openLightbox(i));
    });

    if (lightboxClose) lightboxClose.addEventListener('click', closeLightbox);
    if (lightboxPrev)  lightboxPrev.addEventListener('click', showPrev);
    if (lightboxNext)  lightboxNext.addEventListener('click', showNext);
    lightbox.addEventListener('click', (e) => { if (e.target === lightbox) closeLightbox(); });

    document.addEventListener('keydown', (e) => {
      if (!lightbox.classList.contains('active')) return;
      if (e.key === 'Escape')     closeLightbox();
      if (e.key === 'ArrowLeft')  showPrev();
      if (e.key === 'ArrowRight') showNext();
    });
  }

  const filterBtns    = document.querySelectorAll('.filter-btn');
  const kegiatanCards = document.querySelectorAll('.kegiatan-card[data-status]');

  if (filterBtns.length > 0) {
    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const filter = btn.getAttribute('data-filter');
        kegiatanCards.forEach(card => {
          const show = filter === 'all' || card.getAttribute('data-status') === filter;
          if (show) {
            card.style.display = '';
            requestAnimationFrame(() => { card.style.opacity = '1'; card.style.transform = ''; });
          } else {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.96)';
            setTimeout(() => { card.style.display = 'none'; }, 280);
          }
        });
      });
    });
  }

  const ulasanForm = document.getElementById('ulasan-form');
  if (ulasanForm) {
    ulasanForm.addEventListener('submit', function (e) {
      e.preventDefault();
      const btn        = ulasanForm.querySelector('button[type="submit"]');
      const successMsg = document.getElementById('form-success');
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
      setTimeout(() => {
        ulasanForm.style.display = 'none';
        if (successMsg) successMsg.classList.add('visible');
      }, 1500);
    });
  }

  const counters = document.querySelectorAll('[data-count]');
  if (counters.length > 0) {
    const countObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const el      = entry.target;
          const target  = parseInt(el.getAttribute('data-count'));
          const suffix  = el.getAttribute('data-suffix') || '';
          let   start   = 0;
          const step    = Math.ceil(target / (1800 / 16));
          const update  = () => {
            start = Math.min(start + step, target);
            el.textContent = start.toLocaleString('id-ID') + suffix;
            if (start < target) requestAnimationFrame(update);
          };
          requestAnimationFrame(update);
          countObserver.unobserve(el);
        }
      });
    }, { threshold: 0.5 });
    counters.forEach(el => countObserver.observe(el));
  }

  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const offset = 80; // navbar height offset
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top, behavior: 'smooth' });
      }
    });
  });

});
