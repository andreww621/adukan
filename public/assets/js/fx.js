(()=>{
const $=(s,r=document)=>r.querySelector(s),$$=(s,r=document)=>[...r.querySelectorAll(s)];
const rm=matchMedia('(prefers-reduced-motion:reduce)').matches;

/* ===== 1. Tombol tampil/sembunyikan kata sandi di semua kolom password ===== */
const EYE='<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M1.5 12S5.5 5 12 5s10.5 7 10.5 7-4 7-10.5 7S1.5 12 1.5 12z"/><circle cx="12" cy="12" r="3"/></svg>';
const EYEOFF='<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 3l18 18M10.6 6.1A10 10 0 0 1 12 5c6.5 0 10.5 7 10.5 7a17 17 0 0 1-3.2 3.9M6.6 6.7A16.6 16.6 0 0 0 1.5 12S5.5 19 12 19a9.8 9.8 0 0 0 4.4-1M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>';
const mk=()=>{const b=document.createElement('button');b.type='button';b.className='pwt';b.innerHTML=EYE;b.setAttribute('aria-label','Tampilkan kata sandi');b.setAttribute('aria-pressed','false');return b};
const flip=(b,inputs)=>{const show=b.getAttribute('aria-pressed')!=='true';
 inputs.forEach(i=>{i.type=show?'text':'password'});
 b.setAttribute('aria-pressed',show);b.innerHTML=show?EYEOFF:EYE;b.setAttribute('aria-label',show?'Sembunyikan kata sandi':'Tampilkan kata sandi')};
$$('input[type=password]').forEach(i=>{
 if(i.closest('.pins'))return;
 const w=document.createElement('span');w.className='pw';i.parentNode.insertBefore(w,i);w.append(i);
 const b=mk();w.append(b);b.onclick=()=>flip(b,[i])});
$$('.pins').forEach(g=>{const b=mk();b.classList.add('pwt-pin');g.append(b);b.onclick=()=>flip(b,$$('input',g))});

/* ===== 2. Navbar: bayangan saat di-scroll + kilau (glint) ada di CSS ===== */
const top=$('.top');
if(top){const f=()=>top.classList.toggle('scrolled',scrollY>8);addEventListener('scroll',f,{passive:true});f()}

/* ===== 3. Teks geser loop (marquee). Kecepatan ikut kecepatan scroll; scroll naik membalik arahnya sebentar ===== */
let sv=0,lastY=scrollY;
addEventListener('scroll',()=>{const y=scrollY;sv+=Math.max(-70,Math.min(70,y-lastY));lastY=y},{passive:true});
const ms=$$('.marq').map(m=>{
 const tr=$('.marq-track',m),set=$('.marq-set',tr);
 const o={m,tr,set,dir:m.dataset.dir==='right'?-1:1,base:+m.dataset.speed||.7,x:0,w:1,vis:true};
 const fill=()=>{$$('.marq-set[data-c]',tr).forEach(n=>n.remove());o.w=set.getBoundingClientRect().width||1;
  const need=Math.ceil((m.clientWidth*2)/o.w)+1;for(let k=0;k<need;k++){const c=set.cloneNode(true);c.dataset.c=1;c.setAttribute('aria-hidden','true');tr.append(c)}};
 fill();addEventListener('resize',fill);
 if('IntersectionObserver' in window)new IntersectionObserver(es=>es.forEach(e=>o.vis=e.isIntersecting)).observe(m);
 return o});
if(ms.length&&!rm){
 let v=0;
 const loop=()=>{sv*=.9;
  ms.forEach(o=>{if(!o.vis||document.hidden)return;
   const speed=o.dir*o.base+o.dir*sv*.12;           // sv>0 (scroll turun) mempercepat; sv<0 (scroll naik) membalik
   o.x=((o.x+speed)%o.w+o.w)%o.w;
   o.tr.style.transform='translate3d('+(-o.x)+'px,0,0)'});
  requestAnimationFrame(loop)};
 requestAnimationFrame(loop);
}
})();
