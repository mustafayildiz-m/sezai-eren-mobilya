(function () {
  'use strict';
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // Header state
  const header = document.querySelector('[data-header]');
  const onScroll = () => header && header.classList.toggle('header-scrolled', window.scrollY > 40);
  onScroll(); window.addEventListener('scroll', onScroll, { passive: true });

  // Mobile menu
  const burger = document.querySelector('[data-menu-toggle]');
  const menu = document.querySelector('[data-menu]');
  if (burger && menu) {
    burger.addEventListener('click', () => {
      const open = menu.classList.toggle('open');
      burger.setAttribute('aria-expanded', String(open));
      document.body.classList.toggle('overflow-hidden', open);
    });
    menu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => { menu.classList.remove('open'); document.body.classList.remove('overflow-hidden'); }));
  }

  // Reveal on scroll
  const reveals = document.querySelectorAll('.reveal');
  if (reduced || !('IntersectionObserver' in window)) reveals.forEach(el => el.classList.add('in'));
  else {
    const io = new IntersectionObserver(entries => entries.forEach(en => { if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } }), { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
    reveals.forEach(el => io.observe(el));
  }

  // Counters
  const counters = document.querySelectorAll('[data-count]');
  if (counters.length) {
    const run = el => {
      const target = parseInt(el.dataset.count, 10) || 0; const suffix = el.dataset.suffix || '';
      if (reduced) { el.textContent = target + suffix; return; }
      const dur = 1600, start = performance.now();
      const tick = now => { const p = Math.min(1, (now - start) / dur); const e = 1 - Math.pow(1 - p, 3); el.textContent = Math.round(target * e) + suffix; if (p < 1) requestAnimationFrame(tick); };
      requestAnimationFrame(tick);
    };
    const cio = new IntersectionObserver(es => es.forEach(en => { if (en.isIntersecting) { run(en.target); cio.unobserve(en.target); } }), { threshold: 0.5 });
    counters.forEach(c => cio.observe(c));
  }

  // Lightbox
  const items = Array.from(document.querySelectorAll('[data-lightbox]'));
  if (items.length) {
    const lb = document.createElement('div'); lb.className = 'lb'; lb.setAttribute('role', 'dialog'); lb.setAttribute('aria-modal', 'true');
    lb.innerHTML = '<button class="lb-close" aria-label="Kapat">&#x2715;</button><button class="lb-prev" aria-label="Önceki">&#x2039;</button><img alt=""><button class="lb-next" aria-label="Sonraki">&#x203A;</button><div class="lb-count"></div>';
    document.body.appendChild(lb);
    const img = lb.querySelector('img'), count = lb.querySelector('.lb-count');
    let i = 0;
    const show = n => { i = (n + items.length) % items.length; img.src = items[i].dataset.lightbox; img.alt = items[i].dataset.alt || ''; count.textContent = (i + 1) + ' / ' + items.length; };
    const open = n => { show(n); lb.classList.add('open'); document.body.classList.add('overflow-hidden'); };
    const close = () => { lb.classList.remove('open'); document.body.classList.remove('overflow-hidden'); };
    items.forEach((el, n) => el.addEventListener('click', e => { e.preventDefault(); open(n); }));
    lb.querySelector('.lb-close').addEventListener('click', close);
    lb.querySelector('.lb-prev').addEventListener('click', () => show(i - 1));
    lb.querySelector('.lb-next').addEventListener('click', () => show(i + 1));
    lb.addEventListener('click', e => { if (e.target === lb) close(); });
    document.addEventListener('keydown', e => { if (!lb.classList.contains('open')) return; if (e.key === 'Escape') close(); if (e.key === 'ArrowLeft') show(i - 1); if (e.key === 'ArrowRight') show(i + 1); });
    let sx = 0; lb.addEventListener('touchstart', e => sx = e.touches[0].clientX, { passive: true });
    lb.addEventListener('touchend', e => { const dx = e.changedTouches[0].clientX - sx; if (Math.abs(dx) > 50) show(dx < 0 ? i + 1 : i - 1); });
  }

  // Parallax-ish hero tilt on mouse (subtle)
  const hero = document.querySelector('[data-hero-img]');
  if (hero && !reduced && window.matchMedia('(pointer:fine)').matches) {
    document.addEventListener('mousemove', e => {
      const x = (e.clientX / window.innerWidth - .5) * 10, y = (e.clientY / window.innerHeight - .5) * 10;
      hero.style.setProperty('--mx', x + 'px'); hero.style.setProperty('--my', y + 'px');
    });
  }
})();
