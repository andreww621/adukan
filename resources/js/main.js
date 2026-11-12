(()=>{
const B=document.body,P=B.dataset.page||'',$=(s,r=document)=>r.querySelector(s),$$=(s,r=document)=>[...r.querySelectorAll(s)];
const av=$('.ava button'),mn=$('.menu');
if(av){av.onclick=e=>{e.stopPropagation();av.setAttribute('aria-expanded',mn.classList.toggle('open'))};document.onclick=()=>mn.classList.remove('open')}
const dg=document.createElement('dialog');
dg.innerHTML=`<h2>Yakin ingin keluar?</h2><p>Anda perlu masuk lagi untuk membuat atau memantau pengaduan.</p><button class="btn" id="lgo">Keluar</button> <button class="btn light" style="border-color:var(--line)" onclick="this.closest('dialog').close()">Batal</button>`;
B.append(dg);$$('[data-logout]').forEach(b=>b.onclick=()=>dg.showModal());
window.toast=m=>{const t=document.createElement('div');t.className='toast';t.setAttribute('role','status');t.textContent=m;B.append(t);requestAnimationFrame(()=>t.classList.add('show'));setTimeout(()=>{t.classList.remove('show');setTimeout(()=>t.remove(),500)},2600)};
const rm=matchMedia('(prefers-reduced-motion:reduce)').matches,h=$('.hero'),a=$('#alur');
const on=()=>{const y=scrollY,H=innerHeight;
if(h&&!rm&&y<H){$('img',h).style.transform=`translate3d(0,${y*.25}px,0) scale(${1+y/H*.12})`;const t=$('.hero-txt');t.style.transform=`translate3d(0,${-y*.2}px,60px) rotateX(${y/H*14}deg)`;t.style.opacity=Math.max(0,1-y/H*1.3)}
if(a){const r=a.getBoundingClientRect(),p=Math.min(1,Math.max(0,-r.top/(r.height-H))),i=Math.min(2,Math.floor(p*3));window.__p=p;window.__i=i;$$('.step').forEach((s,k)=>s.classList.toggle('on',k==i))}};
addEventListener('scroll',()=>requestAnimationFrame(on),{passive:true});on();
if(!rm)$$('.dot').forEach(e=>{e.onmousemove=v=>{const b=e.getBoundingClientRect(),x=(v.clientX-b.left)/b.width-.5,y=(v.clientY-b.top)/b.height-.5;e.style.transform=`rotateY(${x*30}deg) rotateX(${-y*30}deg)`};e.onmouseleave=()=>e.style.transform=''});
$$('[data-msg]').forEach(b=>b.addEventListener('click',()=>{toast(b.dataset.msg);if('rm' in b.dataset)b.closest('.item')?.remove()}));

/* ===== Suara "ting" untuk semua tombol submit/kirim =====
   Dibuat dengan Web Audio (tanpa file audio). Submit ditahan sebentar supaya bunyinya sempat terdengar sebelum halaman berpindah. */
let ac;
const ting=()=>{try{ac=ac||new(window.AudioContext||window.webkitAudioContext)();if(ac.state==='suspended')ac.resume();
 const t=ac.currentTime,g=ac.createGain();g.connect(ac.destination);
 g.gain.setValueAtTime(.0001,t);g.gain.exponentialRampToValueAtTime(.3,t+.008);g.gain.exponentialRampToValueAtTime(.0001,t+.6);
 [[1568,1],[2349,.4],[3136,.18]].forEach(([f,v])=>{const o=ac.createOscillator(),og=ac.createGain();o.type='sine';o.frequency.value=f;og.gain.value=v;o.connect(og);og.connect(g);o.start(t);o.stop(t+.65)})}catch(e){}};
window.ting=ting;
const DELAY=260;
document.addEventListener('click',e=>{
 const b=e.target.closest('button,input[type=submit]');
 if(!b||b.disabled||!b.form||b.type!=='submit')return;
 const f=b.form;
 if(!f.noValidate&&!f.checkValidity())return;      // biarkan browser menampilkan pesan validasi, tanpa bunyi
 e.preventDefault();if(f.dataset.busy)return;f.dataset.busy=1;ting();
 setTimeout(()=>{f.requestSubmit?f.requestSubmit(b):f.submit();setTimeout(()=>delete f.dataset.busy,1200)},DELAY)});
const lgo=$('#lgo');if(lgo)lgo.onclick=()=>{ting();setTimeout(()=>document.getElementById('lo').submit(),DELAY)};

/* ===== Pemutar video bukti ===== */
const vd=document.createElement('dialog');vd.id='vdlg';
vd.innerHTML='<video controls playsinline></video><p style="text-align:center;margin:0"><button type="button" class="btn light" style="border-color:var(--line)">Tutup</button></p>';
B.append(vd);const vv=$('video',vd);
$('button',vd).onclick=()=>vd.close();vd.onclose=()=>{vv.pause();vv.removeAttribute('src');vv.load()};
vd.addEventListener('click',e=>{if(e.target===vd)vd.close()});
document.addEventListener('click',e=>{const b=e.target.closest('[data-video]');if(!b)return;vv.src=b.dataset.video;vd.showModal();vv.play().catch(()=>{})});

/* ===== Teks bergerak (tanpa library) ===== */
const io=(cb,opt)=>'IntersectionObserver' in window?new IntersectionObserver(cb,opt):null;
/* 1. Judul section muncul kata demi kata saat di-scroll */
$$('[data-reveal="words"]').forEach(h=>{const txt=h.textContent.trim().replace(/\s+/g,' ');h.setAttribute('aria-label',txt);h.textContent='';
 txt.split(' ').forEach((w,i,a)=>{const s=document.createElement('span');s.className='w';s.setAttribute('aria-hidden','true');s.style.setProperty('--i',i);s.textContent=w;h.append(s);if(i<a.length-1)h.append(' ')})});
const rv=io((es,o)=>es.forEach(en=>{if(en.isIntersecting){en.target.classList.add('in');o.unobserve(en.target)}}),{threshold:.35,rootMargin:'0px 0px -8% 0px'});
$$('[data-reveal]').forEach(h=>rv?rv.observe(h):h.classList.add('in'));
/* 2. Angka menghitung naik saat terlihat */
const nf=n=>n.toLocaleString('id-ID');
const count=el=>{const to=+el.dataset.count||0;if(rm||!to){el.textContent=nf(to);return}
 const t0=performance.now(),d=1400,step=t=>{const p=Math.min(1,(t-t0)/d);el.textContent=nf(Math.round(to*(1-Math.pow(1-p,3))));if(p<1)requestAnimationFrame(step)};requestAnimationFrame(step)};
const co=io((es,o)=>es.forEach(en=>{if(en.isIntersecting){count(en.target);o.unobserve(en.target)}}),{threshold:.6});
$$('[data-count]').forEach(el=>{if(co&&!rm){el.textContent='0';co.observe(el)}else count(el)});
/* 3. Teks berganti di bawah headline hero */
const ro=$('[data-rot]');
if(ro){const box=$('.rot-box',ro),cat=$('.rot-cat',ro),word=$('.rot-word',ro);
 const L=[['Listrik','Lampu mati.','#F5A623'],['Sanitasi','Keran bocor.','#4FB3E8'],['Bangunan','Atap bocor.','#F08A5D']];let i=0;
 const put=k=>{cat.textContent=L[k][0];word.textContent=L[k][1];box.style.setProperty('--c',L[k][2])};put(0);
 if(rm)box.textContent=L.map(x=>x[1]).join(' ');
 else setInterval(()=>{if(document.hidden)return;box.classList.add('out');
  setTimeout(()=>{i=(i+1)%L.length;put(i);box.style.transition='none';box.classList.remove('out');box.classList.add('pre');void box.offsetWidth;box.style.transition='';box.classList.remove('pre')},380)},2800)}

/* ===== Akses kamera: pesan error yang jelas + beberapa percobaan konfigurasi ===== */
window.AdukanCam = {
  usable(){ return !!(window.isSecureContext && navigator.mediaDevices && navigator.mediaDevices.getUserMedia); },
  msg(e){
    if(!window.isSecureContext) return 'Browser memblokir kamera karena halaman ini dibuka lewat alamat yang tidak aman ('+location.protocol+'//'+location.host+'). Buka lewat https:// atau http://localhost, atau pakai kamera bawaan perangkat / unggah dari galeri.';
    if(!navigator.mediaDevices||!navigator.mediaDevices.getUserMedia) return 'Browser ini tidak mendukung akses kamera langsung. Pakai kamera bawaan perangkat atau unggah dari galeri.';
    const n=e&&e.name;
    if(n==='NotAllowedError'||n==='SecurityError'||n==='PermissionDeniedError') return 'Izin kamera ditolak. Klik ikon gembok/kamera di samping alamat situs, ubah Kamera menjadi "Izinkan", lalu muat ulang halaman. Cek juga pengaturan privasi kamera di sistem operasi.';
    if(n==='NotFoundError'||n==='DevicesNotFoundError'||n==='OverconstrainedError') return 'Kamera tidak ditemukan di perangkat ini.';
    if(n==='NotReadableError'||n==='TrackStartError'||n==='AbortError') return 'Kamera sedang dipakai aplikasi lain. Tutup aplikasi itu lalu coba lagi.';
    return 'Kamera tidak bisa dibuka'+(n?' ('+n+')':'')+'.';
  },
  /* Mengembalikan MediaStream, atau melempar error asli. Izin ditolak tidak dicoba ulang. */
  async open(audio){
    if(!this.usable()) throw new Error('unsupported');
    const md=navigator.mediaDevices, tries=[{video:{facingMode:{ideal:'environment'}},audio:!!audio},{video:true,audio:!!audio}];
    if(audio) tries.push({video:true,audio:false});
    let last;
    for(const c of tries){
      try{ return await md.getUserMedia(c); }
      catch(e){ last=e; if(e&&(e.name==='NotAllowedError'||e.name==='SecurityError'||e.name==='PermissionDeniedError')&&!audio) throw e; }
    }
    throw last;
  }
};
})();
