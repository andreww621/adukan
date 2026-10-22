@extends('layouts.app')
@section('title','Buat pengaduan')
@section('page','laporan')
@section('nofooter','1')
@section('content')
<main class="page" style="max-width:640px"><h1>Buat pengaduan</h1>
<form id="f" class="card" style="margin-top:18px" method="POST" action="{{ route('pengaduan.simpan') }}" enctype="multipart/form-data" novalidate>
@csrf
@if ($errors->any())<div class="err">{{ $errors->first() }}</div>@endif
<label for="k">Kategori kerusakan</label>
<select id="k" name="kategori"><option value="">Pilih kategori</option>@foreach (['Listrik', 'Sanitasi', 'Bangunan'] as $kat)<option @selected(old('kategori') === $kat)>{{ $kat }}</option>@endforeach</select>
<label for="l">Lokasi</label>
<select id="l" name="lokasi"><option value="">Pilih ruang / kelas</option>@foreach (config('ruang') as $grup => $ruang)<optgroup label="{{ $grup }}">@foreach ($ruang as $r)<option @selected(old('lokasi') === $r)>{{ $r }}</option>@endforeach</optgroup>@endforeach</select><div class="err" id="el"></div>
<label for="d">Deskripsi</label><textarea id="d" name="deskripsi" rows="4" placeholder="Jelaskan kerusakan yang Anda lihat">{{ old('deskripsi') }}</textarea><div class="err" id="ed"></div>
<label>Foto bukti (opsional)</label>
<div class="drop" id="dz"><img id="pv" alt="Pratinjau foto bukti" hidden><span id="pt">Tarik foto ke sini, atau pilih salah satu cara di bawah</span></div>
<div class="pbtn"><button type="button" class="btn" id="cam">Ambil foto</button><button type="button" class="btn light" id="up">Unggah dari galeri</button><button type="button" class="btn light" id="rm" hidden>Hapus foto</button></div>
<div class="err" id="cer" role="alert"></div>
<input id="ph" name="foto" type="file" accept="image/*" hidden>
<input id="pc" type="file" accept="image/*" capture="environment" hidden>
<label>Video bukti (opsional)</label>
<div class="drop" id="vbox"><video id="vpv" controls playsinline preload="metadata" hidden></video><span id="vtx">Rekam video atau pilih dari galeri (maksimal 30 detik rekam, 20 MB)</span></div>
<div class="pbtn"><button type="button" class="btn" id="vrec">Rekam video</button><button type="button" class="btn light" id="vfile">Unggah video</button><button type="button" class="btn light" id="vdel" hidden>Hapus video</button></div>
<div class="err" id="vtxe" role="alert">@error('video'){{ $message }}@enderror</div>
<input id="vin" name="video" type="file" accept="video/mp4,video/webm,video/quicktime" hidden>
<input id="vcap" type="file" accept="video/*" capture="environment" hidden>
<p style="margin-top:20px"><button class="btn" id="s" disabled>Kirim</button></p></form></main>
<dialog id="rdlg"><h2>Rekam video</h2><video id="rlive" autoplay playsinline muted></video><p class="sub" id="rtime" aria-live="polite">00:00 / 00:30</p><div class="pbtn" style="justify-content:center"><button type="button" class="btn" id="rgo">Mulai rekam</button><button type="button" class="btn light" id="rstop" hidden>Berhenti</button><button type="button" class="btn light" id="rcancel">Batal</button></div></dialog>
<dialog id="cd"><h2>Ambil foto</h2><video id="vd" autoplay playsinline muted></video><div class="pbtn" style="justify-content:center"><button type="button" class="btn" id="snap">Ambil</button><button type="button" class="btn light" id="cx">Batal</button></div></dialog>
@endsection
@push('scripts')
<script>
const ok=()=>{el.textContent=l.value.trim()||!l.dataset.t?'':'Lokasi wajib dipilih.';ed.textContent=d.value.trim()||!d.dataset.t?'':'Deskripsi wajib diisi.';s.disabled=!(k.value&&l.value.trim()&&d.value.trim())};
[k,l,d].forEach(e=>{e.oninput=e.onchange=()=>{e.dataset.t=1;ok()}});
const hint=pt.textContent;
const show=f=>{if(!f)return;
 if(!f.type.startsWith('image/')||f.size>5242880){pt.textContent='Pilih file gambar dengan ukuran maksimal 5 MB.';return}
 const dt=new DataTransfer();dt.items.add(f);ph.files=dt.files;
 pv.src=URL.createObjectURL(f);pv.hidden=rm.hidden=false;pt.textContent=f.name};
up.onclick=dz.onclick=()=>ph.click();
ph.onchange=()=>show(ph.files[0]);pc.onchange=()=>show(pc.files[0]);
dz.ondragover=e=>{e.preventDefault();dz.classList.add('over')};dz.ondragleave=()=>dz.classList.remove('over');
dz.ondrop=e=>{e.preventDefault();dz.classList.remove('over');show(e.dataTransfer.files[0])};
rm.onclick=e=>{e.stopPropagation();ph.value=pc.value='';pv.hidden=rm.hidden=true;pt.textContent=hint};
let st;const stop=()=>{st&&st.getTracks().forEach(t=>t.stop());st=null};
const cer=document.getElementById('cer');
cam.onclick=async()=>{
 cer.textContent='';
 if(!AdukanCam.usable()){cer.textContent=AdukanCam.msg();pc.click();return}   // HP: membuka kamera bawaan lewat input capture
 try{st=await AdukanCam.open(false);vd.srcObject=st;cd.showModal();vd.play&&vd.play().catch(()=>{})}
 catch(e){cer.textContent=AdukanCam.msg(e)}};
snap.onclick=()=>{const c=document.createElement('canvas');c.width=vd.videoWidth;c.height=vd.videoHeight;c.getContext('2d').drawImage(vd,0,0);
 c.toBlob(b=>{show(new File([b],'foto-'+Date.now()+'.jpg',{type:'image/jpeg'}));cd.close()},'image/jpeg',.85)};
cx.onclick=()=>cd.close();cd.onclose=stop;
f.onsubmit=()=>{s.disabled=true;s.textContent='Mengirim...'};
ok();

(()=>{
const $=i=>document.getElementById(i),MAX=20*1024*1024,LIMIT=30;
const vin=$('vin'),vcap=$('vcap'),vpv=$('vpv'),vtx=$('vtx'),vdel=$('vdel'),vbox=$('vbox'),vrec=$('vrec'),vfile=$('vfile'),err=$('vtxe');
const hintV=vtx.textContent;let url=null;
const setV=f=>{if(!f)return;
 if(!f.type.startsWith('video/')){err.textContent='Pilih file video (MP4, WEBM, atau MOV).';return}
 if(f.size>MAX){err.textContent='Ukuran video maksimal 20 MB.';return}
 err.textContent='';const dt=new DataTransfer();dt.items.add(f);vin.files=dt.files;
 if(url)URL.revokeObjectURL(url);url=URL.createObjectURL(f);vpv.src=url;vpv.hidden=vdel.hidden=false;vtx.textContent=f.name+' ('+(f.size<1048576?Math.max(1,Math.round(f.size/1024))+' KB':(f.size/1048576).toFixed(1)+' MB')+')'};
vfile.onclick=vbox.onclick=e=>{if(e.target===vpv)return;vin.click()};
vin.onchange=()=>{const f=vin.files[0];if(!f)return;if(!f.type.startsWith('video/')||f.size>MAX)vin.value='';setV(f)};
vcap.onchange=()=>{setV(vcap.files[0]);vcap.value=''};
vbox.ondragover=e=>{e.preventDefault();vbox.classList.add('over')};vbox.ondragleave=()=>vbox.classList.remove('over');
vbox.ondrop=e=>{e.preventDefault();vbox.classList.remove('over');setV(e.dataTransfer.files[0])};
vdel.onclick=e=>{e.stopPropagation();vin.value=vcap.value='';vpv.pause();vpv.removeAttribute('src');vpv.load();vpv.hidden=vdel.hidden=true;vtx.textContent=hintV;err.textContent='';if(url){URL.revokeObjectURL(url);url=null}};
/* Rekam langsung lewat kamera (MediaRecorder); jika tidak didukung, pakai kamera bawaan perangkat lewat input capture. */
const dlg=$('rdlg'),live=$('rlive'),rgo=$('rgo'),rstop=$('rstop'),rcancel=$('rcancel'),rtime=$('rtime');
let stream,rec,chunks=[],tick,t0,cancelled=false;
const mm=()=>['video/webm;codecs=vp9,opus','video/webm;codecs=vp8,opus','video/webm','video/mp4'].find(t=>window.MediaRecorder&&MediaRecorder.isTypeSupported(t));
const fmt=n=>String(Math.floor(n/60)).padStart(2,'0')+':'+String(n%60).padStart(2,'0');
const release=()=>{clearInterval(tick);stream&&stream.getTracks().forEach(t=>t.stop());stream=null;live.srcObject=null};
vrec.onclick=async()=>{
 err.textContent='';
 if(!AdukanCam.usable()||!mm()){err.textContent=AdukanCam.usable()?'Browser ini belum mendukung rekam langsung. Memakai kamera bawaan perangkat.':AdukanCam.msg();vcap.click();return}
 try{stream=await AdukanCam.open(true)}
 catch(e){err.textContent=AdukanCam.msg(e);return}
 live.srcObject=stream;live.play&&live.play().catch(()=>{});rgo.hidden=false;rstop.hidden=true;rtime.textContent='00:00 / '+fmt(LIMIT);dlg.showModal()};
rgo.onclick=()=>{chunks=[];cancelled=false;const type=mm();
 rec=new MediaRecorder(stream,{mimeType:type,videoBitsPerSecond:1500000,audioBitsPerSecond:64000});
 rec.ondataavailable=e=>e.data.size&&chunks.push(e.data);
 rec.onstop=()=>{const keep=!cancelled,mt=(rec.mimeType||type).split(';')[0];release();dlg.close();
  if(keep&&chunks.length){const ext=mt.includes('mp4')?'mp4':'webm';setV(new File(chunks,'video-'+Date.now()+'.'+ext,{type:mt}))}};
 rec.start(500);t0=Date.now();rgo.hidden=true;rstop.hidden=false;
 tick=setInterval(()=>{const s=Math.floor((Date.now()-t0)/1000);rtime.textContent=fmt(s)+' / '+fmt(LIMIT);if(s>=LIMIT)rstop.click()},250)};
rstop.onclick=()=>{if(rec&&rec.state!=='inactive')rec.stop()};
rcancel.onclick=()=>{cancelled=true;if(rec&&rec.state!=='inactive')rec.stop();else{release();dlg.close()}};
dlg.oncancel=e=>{e.preventDefault();rcancel.click()};
})();
</script>
@endpush
