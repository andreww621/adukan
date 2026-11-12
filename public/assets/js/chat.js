/* Chat pelapor <-> admin (tanpa library).
   - Pelapor: ikon pesawat kertas tetap di pojok kanan bawah + badge jumlah pesan baru; klik membuka panel chat.
   - Admin: tombol "Buka chat" di daftar komentar dashboard membuka panel yang sama; badge "pesan baru" ikut diperbarui.
   Data lewat polling ke ChatController (JSON) setiap 5 detik saat tab terlihat. */
(() => {
'use strict';
const C = window.CHAT;
if (!C || !window.fetch) return;

const $ = (s, r = document) => r.querySelector(s);
const $$ = (s, r = document) => [...r.querySelectorAll(s)];
const admin = C.role === 'admin';
const el = (t, c, txt) => { const e = document.createElement(t); if (c) e.className = c; if (txt != null) e.textContent = txt; return e; };
const csrf = () => (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

const api = (path, body) => fetch(C.base + path, {
  method: body ? 'POST' : 'GET',
  credentials: 'same-origin',
  headers: Object.assign({ Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    body ? { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf() } : {}),
  body: body ? JSON.stringify(body) : undefined,
}).then(async r => {
  if (!r.ok) { const e = new Error('http ' + r.status); e.status = r.status; try { e.data = await r.json(); } catch (_) {} throw e; }
  return r.json();
});

/* ---------- state ---------- */
let threads = [], total = 0, active = null, isOpen = false, trigger = null;
const msgs = new Map();
const COLORS = ['#e5484d', '#3e63dd', '#30a46c', '#f5a623', '#8e4ec6', '#0ea5b7', '#d6409f', '#6b7280'];
const color = i => COLORS[Math.abs(i) % COLORS.length];
const two = n => String(n).padStart(2, '0');
const stamp = d => {
  const hm = two(d.getHours()) + ':' + two(d.getMinutes());
  return d.toDateString() === new Date().toDateString() ? hm : d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) + ', ' + hm;
};

/* ---------- DOM ---------- */
const I = {
  plane: '<svg viewBox="0 0 64 64" aria-hidden="true"><path d="M59 5 5 25.5l21.5 8.5L35 59z" fill="#E02535" stroke="#fff" stroke-width="3" stroke-linejoin="round"/><path d="M26.5 34 59 5" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" opacity=".55"/></svg>',
  back: '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 5-7 7 7 7"/></svg>',
  expand: '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 4h6v6M10 20H4v-6M20 4l-7 7M4 20l7-7"/></svg>',
  close: '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M5 5l14 14M19 5 5 19"/></svg>',
  emoji: '<svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="12" cy="12" r="9.2"/><path d="M8.2 14.2a4.6 4.6 0 0 0 7.6 0"/><circle cx="9" cy="9.7" r=".9" fill="currentColor"/><circle cx="15" cy="9.7" r=".9" fill="currentColor"/></svg>',
  send: '<svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor"><path d="M3.4 20.4 21 12 3.4 3.6l.1 6.5L15 12 3.5 13.9z"/></svg>',
};
const EMOJI = ['😀', '😂', '😊', '😍', '🙏', '👍', '👏', '🙌', '😢', '😡', '🎉', '❤️', '🔥', '✅', '⚠️', '🛠️', '💡', '🚰', '🔌', '🏫', '📷', '😅', '🤝', '👌', '😴', '🤔', '😎', '🥰', '😭', '💪', '⭐', '👀'];

const fab = el('button', 'cfab');
fab.type = 'button'; fab.id = 'cfab'; fab.hidden = true;
fab.innerHTML = I.plane + '<span class="cnum" hidden>0</span>';

const win = el('section', 'cwin');
win.id = 'cwin'; win.hidden = true; win.setAttribute('role', 'dialog'); win.setAttribute('aria-label', 'Chat');
win.innerHTML =
  '<aside class="cside"><button type="button" class="cbtn cback" aria-label="Kecilkan chat">' + I.back + '</button><div class="clist" id="clist"></div></aside>' +
  '<div class="cmain">' +
    '<header class="chead"><div class="ctitle"><b id="ctt">Chat</b><small id="cts"></small></div>' +
      '<button type="button" class="cbtn cexp" aria-label="Perbesar atau perkecil">' + I.expand + '</button>' +
      '<button type="button" class="cbtn ccl" aria-label="Tutup chat">' + I.close + '</button></header>' +
    '<div class="cmsgs" id="cmsgs" aria-live="polite"></div>' +
    '<div class="cinput"><div class="cemoji" id="cemoji" hidden></div>' +
      '<div class="cbox"><textarea id="cta" rows="1" maxlength="1000" placeholder="Kirim pesan..." aria-label="Tulis pesan"></textarea>' +
      '<button type="button" class="cic cemo" aria-label="Emoji">' + I.emoji + '</button>' +
      '<button type="button" class="cic csend" aria-label="Kirim pesan" disabled>' + I.send + '</button></div></div>' +
  '</div>';
document.body.append(fab, win);

const box = $('#cmsgs', win), list = $('#clist', win), ta = $('#cta', win), sendBtn = $('.csend', win), emo = $('#cemoji', win);
EMOJI.forEach(e => { const b = el('button', null, e); b.type = 'button'; b.setAttribute('aria-label', 'Emoji ' + e); emo.append(b); });

/* ---------- painting ---------- */
const thread = id => threads.find(t => t.id === id);
const unreadOf = t => (isOpen && t.id === active) ? 0 : t.belum_dibaca;

const paintFab = () => {
  const n = threads.reduce((s, t) => s + unreadOf(t), 0);
  fab.hidden = admin || !threads.length || isOpen;
  const num = $('.cnum', fab);
  num.hidden = !n; num.textContent = n > 99 ? '99+' : n;
  fab.classList.toggle('pulse', n > 0);
  fab.setAttribute('aria-label', n ? 'Buka chat, ' + n + ' pesan baru' : 'Buka chat');
};

const avatarSmall = t => {
  const a = el('span', 'cas');
  if (!admin) { const i = new Image(); i.src = C.logo; i.alt = ''; a.append(i); return a; }
  if (t && t.foto) { const i = new Image(); i.src = t.foto; i.alt = ''; a.append(i); }
  else { a.textContent = ((t && t.nama) || '?').trim().charAt(0).toUpperCase(); a.style.background = color(t ? t.id : 0); }
  return a;
};

const paintRail = () => {
  const f = document.activeElement && document.activeElement.closest ? document.activeElement.closest('.cav') : null;
  const keep = f && list.contains(f) ? f.dataset.id : null;     // pertahankan fokus keyboard saat daftar dibangun ulang
  list.textContent = '';
  threads.forEach(t => {
    const b = el('button', 'cav' + (t.id === active ? ' on' : ''));
    b.type = 'button'; b.dataset.id = t.id;
    b.title = admin ? t.nama + ' - ' + t.judul : t.judul;
    b.setAttribute('aria-label', 'Percakapan: ' + b.title + (unreadOf(t) ? ', ' + unreadOf(t) + ' pesan baru' : ''));
    if (admin && t.foto) { const i = new Image(); i.src = t.foto; i.alt = ''; b.append(i); }
    else { b.textContent = (admin ? t.nama : t.judul).trim().charAt(0).toUpperCase(); b.style.background = color(t.id); }
    const n = unreadOf(t);
    if (n) b.append(el('i', 'cbd', n > 9 ? '9+' : n));
    b.onclick = () => select(t.id);
    list.append(b);
    if (keep && String(t.id) === keep) b.focus();
  });
};

const paintHead = () => {
  const t = thread(active);
  $('#ctt', win).textContent = admin ? (t ? t.nama : 'Chat') : 'Petugas Sarpras';
  $('#cts', win).textContent = t ? t.judul : '';
};

// Badge di halaman (daftar komentar admin / halaman Kontak pelapor).
const paintBadges = () => {
  $$('[data-nb]').forEach(b => {
    const t = thread(+b.dataset.nb), n = t ? unreadOf(t) : 0;
    b.textContent = n; b.hidden = !n;
    const row = b.closest('.kom'); if (row) row.classList.toggle('has-new', n > 0);
  });
  const n = threads.reduce((s, t) => s + unreadOf(t), 0);
  $$('[data-nb-total]').forEach(b => { b.textContent = n; b.hidden = !n; });
};

const render = force => {
  const near = box.scrollHeight - box.scrollTop - box.clientHeight < 90;
  box.textContent = '';
  const items = msgs.get(active) || [], t = thread(active);
  items.forEach((m, i) => {
    const prev = items[i - 1], next = items[i + 1], d = new Date(m.waktu);
    if (!prev || d - new Date(prev.waktu) > 600000) box.append(el('div', 'ctime', stamp(d)));
    const endOfGroup = !next || next.mine !== m.mine || new Date(next.waktu) - d > 600000;
    const row = el('div', 'crow ' + (m.mine ? 'me' : 'them') + (m.pending ? ' pend' : '') + (m.failed ? ' fail' : ''));
    if (!m.mine) row.append(endOfGroup ? avatarSmall(t) : el('span', 'cas ghost'));
    const b = el('div', 'cbub', m.isi);
    if (m.failed) { b.tabIndex = 0; b.setAttribute('role', 'button'); b.onclick = () => retry(m); b.onkeydown = e => { if (e.key === 'Enter') retry(m); }; }
    row.append(b); box.append(row);
    if (m.failed) box.append(el('div', 'cerr', (m.error || 'Gagal terkirim.') + ' Ketuk pesan untuk mencoba lagi.'));
  });
  if (!admin && items.length && items.every(m => m.mine)) box.append(el('div', 'cnote', 'Komentar Anda sudah terkirim. Balasan petugas akan muncul di sini.'));
  if (force || near) box.scrollTop = box.scrollHeight;
};

const paintAll = () => { paintFab(); paintRail(); paintHead(); paintBadges(); };

/* ---------- data ---------- */
const refresh = async () => {
  try {
    const d = await api('');
    const prev = threads;
    threads = d.threads;
    if (active && !thread(active)) { const old = prev.find(t => t.id === active); if (old) threads.unshift(old); }
    total = d.total;
    paintAll();
  } catch (e) { /* offline / maintenance: abaikan */ }
};

const load = async (id, inc) => {
  const cur = msgs.get(id) || [];
  const real = cur.filter(m => m.id > 0), pend = cur.filter(m => m.id < 0);
  const last = real.length ? real[real.length - 1].id : 0;
  const d = await api('/' + id + (inc && last ? '?after=' + last : ''));
  const have = new Set(real.map(m => m.id));
  const fresh = inc && last ? d.pesan.filter(m => !have.has(m.id)) : d.pesan;
  msgs.set(id, (inc && last ? real : []).concat(fresh, pend));
  const t = thread(id);
  if (t && t.belum_dibaca) { t.belum_dibaca = 0; paintAll(); }   // server menandai dibaca saat percakapan dibuka
  if (id === active) render(!inc || fresh.length > 0 && fresh.some(m => !m.mine));
};

const select = id => {
  active = id; paintRail(); paintHead(); render(true);
  load(id).catch(() => {});
};

const push = (id, m) => { const l = msgs.get(id) || []; l.push(m); msgs.set(id, l); };

const post = async (id, m) => {
  try {
    const real = await api('/' + id, { isi: m.isi });
    const l = msgs.get(id) || [], i = l.indexOf(m);
    if (l.some(x => x.id === real.id)) { if (i > -1) l.splice(i, 1); }
    else if (i > -1) l[i] = real; else l.push(real);
    if (id === active) render(true);
    refresh();
  } catch (e) {
    m.pending = false; m.failed = true;
    m.error = e.status === 422 && e.data && e.data.errors && e.data.errors.isi ? e.data.errors.isi[0] : (e.status === 503 ? 'Situs sedang dalam perawatan.' : null);
    if (id === active) render(true);
  }
};
const retry = m => { m.failed = false; m.pending = true; render(true); post(active, m); };

const send = () => {
  const txt = ta.value.trim();
  if (!txt || !active) return;
  if (window.ting) window.ting();
  ta.value = ''; fit(); updSend();
  const m = { id: -Date.now(), mine: true, dari: C.role, isi: txt, waktu: new Date().toISOString(), pending: true };
  push(active, m); render(true); post(active, m);
};

/* ---------- open / close ---------- */
const openChat = async id => {
  trigger = document.activeElement;
  if (!threads.length) await refresh();
  if (id && !thread(id)) threads.unshift({ id, judul: 'Komentar', nama: 'Pelapor', foto: null, belum_dibaca: 0, terakhir: null });
  const t = id ? thread(id) : (threads.find(x => x.belum_dibaca) || threads[0]);
  if (!t) return;
  isOpen = true; win.hidden = false;
  select(t.id); paintAll();
  if (matchMedia('(min-width:601px)').matches) setTimeout(() => ta.focus(), 60);
};
const closeChat = () => {
  isOpen = false; win.hidden = true; emo.hidden = true;
  paintAll();
  if (trigger && trigger.focus && document.contains(trigger)) trigger.focus(); else if (!fab.hidden) fab.focus();
};

/* ---------- input ---------- */
const fit = () => { ta.style.height = 'auto'; ta.style.height = Math.min(ta.scrollHeight, 96) + 'px'; };
const updSend = () => { sendBtn.disabled = !ta.value.trim(); };
ta.addEventListener('input', () => { fit(); updSend(); });
ta.addEventListener('keydown', e => { if (e.key === 'Enter' && !e.shiftKey && !e.isComposing) { e.preventDefault(); send(); } });
sendBtn.onclick = send;
$('.cemo', win).onclick = () => { emo.hidden = !emo.hidden; };
emo.addEventListener('click', e => {
  const b = e.target.closest('button'); if (!b) return;
  ta.setRangeText(b.textContent, ta.selectionStart, ta.selectionEnd, 'end');
  ta.dispatchEvent(new Event('input')); ta.focus();
});
$('.ccl', win).onclick = closeChat;
$('.cback', win).onclick = closeChat;
$('.cexp', win).onclick = () => win.classList.toggle('big');
document.addEventListener('keydown', e => { if (e.key === 'Escape' && isOpen) { if (!emo.hidden) emo.hidden = true; else closeChat(); } });
fab.onclick = () => openChat();
document.addEventListener('click', e => {
  const b = e.target.closest('[data-chat-open]');
  if (b) { e.preventDefault(); openChat(+b.dataset.chatOpen); }
});

/* ---------- polling ---------- */
const poll = async () => {
  if (document.hidden) return;
  if (isOpen && active) { try { await load(active, true); } catch (e) {} }
  await refresh();
};
setInterval(poll, 5000);
document.addEventListener('visibilitychange', () => { if (!document.hidden) poll(); });

window.AdukanChat = { open: openChat, refresh };
refresh().then(() => { if (C.open) openChat(+C.open); });
})();
