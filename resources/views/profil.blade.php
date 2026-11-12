@extends('layouts.app')
@section('title','Profil')
@section('page','profil')
@section('content')
<main class="page"><div class="prof">
<div class="side">
<<<<<<< HEAD
<div class="bigava" id="ava">
    @if (auth()->user()->foto)
        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Foto profil {{ auth()->user()->name }}">
    @else
        {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
    @endif
</div>
=======
<div class="bigava" id="ava">@if (auth()->user()->foto_url)<img src="{{ auth()->user()->foto_url }}" alt="Foto profil {{ auth()->user()->name }}">@else{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}@endif</div>
>>>>>>> d2ba4ff (Initial commit adukan vercel)
<form id="ff" method="POST" action="{{ route('profil.foto') }}" enctype="multipart/form-data">@csrf
<input type="file" id="fi" name="foto" accept="image/jpeg,image/png,image/webp" hidden>
<div class="err" id="fe" style="text-align:center" role="alert">@error('foto'){{ $message }}@enderror</div>
</form>
<div class="actions"><button type="button" class="btn" id="fb">{{ auth()->user()->foto ? 'Ganti foto' : 'Unggah foto' }}</button>
@if (auth()->user()->foto)<form method="POST" action="{{ route('profil.foto.hapus') }}" style="margin:0">@csrf @method('DELETE')<button class="btn light" style="width:100%;border-color:var(--line)">Hapus foto</button></form>@endif
<<<<<<< HEAD
<button class="btn light" style="border-color:var(--line)" data-logout>Keluar</button></div></div>
<div class="card"><h1>{{ auth()->user()->name }}</h1><p style="color:var(--mut)">{{ auth()->user()->email }}</p>
=======
<button type="button" class="btn light" style="border-color:var(--line)" id="pwb">Ganti kata sandi</button>
<button type="button" class="btn light" style="border-color:var(--line)" data-logout>Keluar</button></div></div>
<div class="card"><h1>{{ auth()->user()->name }}</h1><p style="color:var(--mut)">{{ auth()->user()->login_id }}@if (auth()->user()->info) &middot; {{ auth()->user()->info }}@endif</p>
@if (auth()->user()->role === 'pelapor')
>>>>>>> d2ba4ff (Initial commit adukan vercel)
<h2 style="margin:22px 0 4px">Nilai layanan</h2>
<p class="sub" style="margin-bottom:6px">Penilaian Anda tersimpan dan dapat dilihat oleh admin.</p>
<form id="rf" method="POST" action="{{ route('rating.simpan') }}">@csrf
<input type="hidden" name="nilai" id="nilai" value="{{ $rating->nilai ?? 0 }}">
<div class="stars" id="st" role="group" aria-label="Nilai 1 sampai 5">@for ($i = 1; $i <= 5; $i++)<button type="button" aria-label="{{ $i }} bintang">&#9733;</button>@endfor</div>
<label for="kom">Komentar (opsional)</label><textarea id="kom" name="komentar" rows="3" maxlength="300">{{ $rating->komentar ?? '' }}</textarea>
@error('nilai')<div class="err">Pilih jumlah bintang terlebih dahulu.</div>@enderror
<div class="pbtn"><button class="btn" id="sub">Submit</button><button type="button" class="btn light" id="ed" style="border-color:var(--line)" hidden>Edit</button></div>
<<<<<<< HEAD
</form></div></div></main>
@endsection
@push('scripts')
<script>
=======
</form>
@endif
</div></div></main>
<dialog id="pdlg"><h2>Ganti kata sandi</h2>
<form method="POST" action="{{ route('profil.sandi') }}" style="text-align:left" autocomplete="off">@csrf @method('PUT')
@if ($errors->sandi->any())<div class="err" role="alert">{{ $errors->sandi->first() }}</div>@endif
<label for="pw0">Kata sandi saat ini</label><span class="pw"><input id="pw0" name="current_password" type="password" required autocomplete="current-password"></span>
<label for="pw1">Kata sandi baru</label><span class="pw"><input id="pw1" name="password" type="password" minlength="8" required autocomplete="new-password"></span>
<p class="sub" style="margin-top:4px">Minimal 8 karakter.</p>
<label for="pw2">Ulangi kata sandi baru</label><span class="pw"><input id="pw2" name="password_confirmation" type="password" minlength="8" required autocomplete="new-password"></span>
<p style="margin-top:18px;display:flex;gap:10px;justify-content:center;flex-wrap:wrap"><button class="btn">Simpan</button><button type="button" class="btn light" style="border-color:var(--line)" onclick="pdlg.close()">Batal</button></p>
</form></dialog>
@endsection
@push('scripts')
<script>
pwb.onclick=()=>pdlg.showModal();
const eye='<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>';
document.querySelectorAll('#pdlg .pw').forEach(w=>{const i=w.firstElementChild,b=document.createElement('button');b.type='button';b.className='pwt';b.setAttribute('aria-label','Tampilkan kata sandi');b.innerHTML=eye;
 b.onclick=()=>{const t=i.type==='password';i.type=t?'text':'password';b.setAttribute('aria-label',t?'Sembunyikan kata sandi':'Tampilkan kata sandi')};w.append(b)});
@if ($errors->sandi->any())pdlg.showModal();@endif
>>>>>>> d2ba4ff (Initial commit adukan vercel)
fb.onclick=()=>fi.click();
fi.onchange=()=>{const f=fi.files[0];if(!f)return;
 if(!/^image\/(jpeg|png|webp)$/.test(f.type)||f.size>5242880){fe.textContent='Pilih foto JPG, PNG, atau WEBP dengan ukuran maksimal 5 MB.';fi.value='';return}
 fe.textContent='';ava.innerHTML='';const im=new Image();im.src=URL.createObjectURL(f);im.alt='Pratinjau foto profil';ava.append(im);fb.disabled=true;fb.textContent='Mengunggah...';ff.submit()};
<<<<<<< HEAD
=======
@if (auth()->user()->role === 'pelapor')
>>>>>>> d2ba4ff (Initial commit adukan vercel)
const bs=[...st.children],has={{ $rating ? 'true' : 'false' }};
const paint=n=>bs.forEach((b,i)=>b.classList.toggle('on',i<n));
const lock=v=>{bs.forEach(b=>b.disabled=v);kom.readOnly=v;sub.hidden=v;ed.hidden=!v;if(!v)sub.disabled=!+nilai.value};
bs.forEach((b,i)=>b.onclick=()=>{nilai.value=i+1;paint(i+1);sub.disabled=false});
ed.onclick=()=>lock(false);
paint(+nilai.value);lock(has);
<<<<<<< HEAD
=======
@endif
>>>>>>> d2ba4ff (Initial commit adukan vercel)
</script>
@endpush
