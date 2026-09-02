(function () {
  'use strict';
  const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

  // Confirm dialogs
  document.querySelectorAll('form[data-confirm]').forEach(f => f.addEventListener('submit', e => { if (!confirm(f.dataset.confirm)) e.preventDefault(); }));
  document.querySelectorAll('button[data-confirm]').forEach(b => b.addEventListener('click', e => { if (!confirm(b.dataset.confirm)) e.preventDefault(); }));

  // Uploader
  const up = document.querySelector('[data-uploader]');
  if (up) {
    const input = up.querySelector('input[type=file]'), bar = up.querySelector('[data-progress]'), fill = bar.firstElementChild;
    const send = async files => {
      const list = Array.from(files).filter(f => f.type.startsWith('image/'));
      if (!list.length) return;
      bar.classList.remove('hidden'); let done = 0, errors = [];
      for (const f of list) {
        const fd = new FormData(); fd.append('photo', f); fd.append('_token', csrf);
        try {
          const r = await fetch(up.dataset.url, { method: 'POST', body: fd, headers: { 'X-CSRF-Token': csrf } });
          const j = await r.json(); if (!r.ok || j.error) errors.push(f.name + ': ' + (j.error || r.status));
        } catch (e) { errors.push(f.name + ': ağ hatası'); }
        done++; fill.style.width = Math.round(done / list.length * 100) + '%';
      }
      if (errors.length) alert('Bazı dosyalar yüklenemedi:\n' + errors.join('\n'));
      location.reload();
    };
    up.addEventListener('click', e => { if (e.target !== input) input.click(); });
    input.addEventListener('change', () => send(input.files));
    ['dragenter', 'dragover'].forEach(ev => up.addEventListener(ev, e => { e.preventDefault(); up.classList.add('border-copper', 'bg-copper/5'); }));
    ['dragleave', 'drop'].forEach(ev => up.addEventListener(ev, e => { e.preventDefault(); up.classList.remove('border-copper', 'bg-copper/5'); }));
    up.addEventListener('drop', e => send(e.dataTransfer.files));
  }

  // Sortable images
  const list = document.querySelector('[data-sortable]');
  if (list) {
    let dragging = null;
    list.querySelectorAll('li').forEach(li => {
      li.addEventListener('dragstart', () => { dragging = li; li.classList.add('opacity-40'); });
      li.addEventListener('dragend', () => { li.classList.remove('opacity-40'); dragging = null; save(); });
      li.addEventListener('dragover', e => {
        e.preventDefault(); if (!dragging || dragging === li) return;
        const r = li.getBoundingClientRect(); const after = (e.clientX - r.left) > r.width / 2;
        li.parentNode.insertBefore(dragging, after ? li.nextSibling : li);
      });
    });
    const save = () => {
      const ids = Array.from(list.querySelectorAll('li')).map(li => li.dataset.id);
      fetch(list.dataset.url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-Token': csrf }, body: JSON.stringify({ ids }) });
    };
  }
})();
