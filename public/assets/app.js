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
  // ===== Fon müziği =====
  // Tarayıcılar (özellikle iOS Safari) dokunma olmadan sesli oynatmaya izin
  // vermez. Bu yüzden varsayılan kapalı; kullanıcı düğmeye bastığında başlar,
  // tercihi ve konumu saklanır — çok sayfalı site olduğu için her geçişte
  // baştan başlamasın.
  const bgm = document.querySelector('[data-bgm]');
  const sndBtn = document.querySelector('[data-sound-toggle]');
  if (bgm && sndBtn) {
    const K_ON = 'bgm-on', K_POS = 'bgm-pos';
    const iconOff = sndBtn.querySelector('[data-icon-off]');
    const iconOn = sndBtn.querySelector('[data-icon-on]');

    // Gizli sekmede / site verisi kapalıyken localStorage erişimi patlayabilir
    const store = {
      get(k, d) { try { return localStorage.getItem(k) ?? d; } catch (e) { return d; } },
      set(k, v) { try { localStorage.setItem(k, v); } catch (e) {} }
    };

    let wantsOn = store.get(K_ON, '0') === '1';

    const paint = on => {
      sndBtn.setAttribute('aria-pressed', String(on));
      sndBtn.setAttribute('aria-label', on ? 'Fon müziğini kapat' : 'Fon müziğini aç');
      if (iconOff) iconOff.hidden = on;
      if (iconOn) iconOn.hidden = !on;
    };

    const savePos = () => { if (!bgm.paused && bgm.currentTime > 0) store.set(K_POS, String(bgm.currentTime)); };

    // preload="none" olduğu için süre, oynatma başlayana kadar NaN.
    // Konumu ancak metadata geldiğinde uygulayabiliriz.
    const seekToSaved = () => {
      const pos = parseFloat(store.get(K_POS, '0'));
      if (!(pos > 0)) return;
      const apply = () => {
        if (isFinite(bgm.duration) && pos < bgm.duration - 1) {
          try { bgm.currentTime = pos; } catch (e) {}
        }
      };
      if (isFinite(bgm.duration) && bgm.duration > 0) apply();
      else bgm.addEventListener('loadedmetadata', apply, { once: true });
    };

    // Ses dosyası ~2 MB. Mobil veride ilk basışta birkaç saniye sürebilir;
    // geri bildirim olmazsa düğme bozukmuş gibi hissettiriyor.
    let busyTimer;
    const busy = on => {
      sndBtn.classList.toggle('is-loading', on);
      clearTimeout(busyTimer);
      // Emniyet: 'playing' hiç gelmezse yay sonsuza dek dönmesin
      if (on) busyTimer = setTimeout(() => sndBtn.classList.remove('is-loading'), 12000);
    };
    bgm.addEventListener('playing', () => busy(false));
    bgm.addEventListener('timeupdate', () => busy(false));
    bgm.addEventListener('waiting', () => busy(true));

    const start = () => {
      seekToSaved();
      // iOS'ta volume salt okunurdur; dosya zaten -26 LUFS'a indirildi.
      try { bgm.volume = 0.55; } catch (e) {}
      if (bgm.readyState < 3) busy(true);
      return bgm.play();
    };

    const fail = msg => {
      busy(false); wantsOn = false; store.set(K_ON, '0'); paint(false);
      sndBtn.classList.add('is-error');
      sndBtn.setAttribute('title', msg);
      setTimeout(() => sndBtn.classList.remove('is-error'), 2500);
    };

    // Dosya inmezse (404, kopan bağlantı) sessizce takılı kalmasın
    bgm.addEventListener('error', () => fail('Ses dosyası yüklenemedi.'));

    // Arayüzü hemen güncelle: play() sözü ses gerçekten başlayana kadar
    // çözülmüyor, beklersek düğme geç tepki veriyor. Reddedilirse geri alıyoruz.
    const enable = () => {
      wantsOn = true; store.set(K_ON, '1'); paint(true);
      return start().catch(err => fail(
        err && err.name === 'NotAllowedError'
          ? 'Tarayıcı sesi engelledi. Düğmeye tekrar dokunun.'
          : 'Ses başlatılamadı.'
      ));
    };

    const disable = () => {
      savePos(); bgm.pause(); busy(false);
      wantsOn = false; store.set(K_ON, '0'); paint(false);
      sndBtn.removeAttribute('title');
    };

    paint(false);
    sndBtn.addEventListener('click', () => (bgm.paused ? enable() : disable()));

    // Daha önce açmışsa devam ettirmeyi dene; tarayıcı reddederse ilk
    // dokunuşta tekrar dene — bu da geçerli bir kullanıcı hareketidir.
    if (wantsOn) {
      paint(true);
      start().catch(() => {
        paint(false);
        const resume = () => { if (store.get(K_ON, '0') === '1') enable(); };
        document.addEventListener('pointerdown', resume, { once: true });
        document.addEventListener('keydown', resume, { once: true });
      });
    }

    // Sekme arkaplana alınınca sustur — pil/veri tasarrufu ve sürpriz ses yok
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) { savePos(); bgm.pause(); }
      else if (wantsOn && bgm.paused) start().catch(() => {});
    });

    setInterval(savePos, 5000);
    window.addEventListener('pagehide', savePos);
  }

})();