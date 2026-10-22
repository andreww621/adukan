@extends('layouts.app')
@section('title','Profil')
@section('page','profil')
@section('content')
<main class="page"><div class="prof">
<div class="side">
<div class="bigava" id="ava">
    @if (auth()->user()->foto)
        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Foto profil {{ auth()->user()->name }}">
    @else
        {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
    @endif
</div>
<form id="ff" method="POST" action="{{ route('profil.foto') }}" enctype="multipart/form-data">@csrf
<input type="file" id="fi" name="foto" accept="image/jpeg,image/png,image/webp" hidden>
<div class="err" id="fe" style="text-align:center" role="alert">@error('foto'){{ $message }}@enderror</div>
</form>
<div class="actions"><button type="button" class="btn" id="fb">{{ auth()->user()->foto ? 'Ganti foto' : 'Unggah foto' }}</button>
@if (auth()->user()->foto)<form method="POST" action="{{ route('profil.foto.hapus') }}" style="margin:0">@csrf @method('DELETE')<button class="btn light" style="width:100%;border-color:var(--line)">Hapus foto</button></form>@endif
<button class="btn light" style="border-color:var(--line)" data-logout>Keluar</button></div></div>
<div class="card"><h1>{{ auth()->user()->name }}</h1><p style="color:var(--mut)">{{ auth()->user()->email }}</p>
<h2 style="margin:22px 0 4px">Nilai layanan</h2>
<p class="sub" style="margin-bottom:6px">Penilaian Anda tersimpan dan dapat dilihat oleh admin.</p>
<form id="rf" method="POST" action="{{ route('rating.simpan') }}">@csrf
<input type="hidden" name="nilai" id="nilai" value="{{ $rating->nilai ?? 0 }}">
<div class="stars" id="st" role="group" aria-label="Nilai 1 sampai 5">@for ($i = 1; $i <= 5; $i++)<button type="button" aria-label="{{ $i }} bintang">&#9733;</button>@endfor</div>
<label for="kom">Komentar (opsional)</label><textarea id="kom" name="komentar" rows="3" maxlength="300">{{ $rating->komentar ?? '' }}</textarea>
@error('nilai')<div class="err">Pilih jumlah bintang terlebih dahulu.</div>@enderror
<div class="pbtn"><button class="btn" id="sub">Submit</button><button type="button" class="btn light" id="ed" style="border-color:var(--line)" hidden>Edit</button></div>
</form></div></div></main>
@endsection
@push('scripts')
<script>
fb.onclick=()=>fi.click();
fi.onchange=()=>{const f=fi.files[0];if(!f)return;
 if(!/^image\/(jpeg|png|webp)$/.test(f.type)||f.size>5242880){fe.textContent='Pilih foto JPG, PNG, atau WEBP dengan ukuran maksimal 5 MB.';fi.value='';return}
 fe.textContent='';ava.innerHTML='';const im=new Image();im.src=URL.createObjectURL(f);im.alt='Pratinjau foto profil';ava.append(im);fb.disabled=true;fb.textContent='Mengunggah...';ff.submit()};
const bs=[...st.children],has={{ $rating ? 'true' : 'false' }};
const paint=n=>bs.forEach((b,i)=>b.classList.toggle('on',i<n));
const lock=v=>{bs.forEach(b=>b.disabled=v);kom.readOnly=v;sub.hidden=v;ed.hidden=!v;if(!v)sub.disabled=!+nilai.value};
bs.forEach((b,i)=>b.onclick=()=>{nilai.value=i+1;paint(i+1);sub.disabled=false});
ed.onclick=()=>lock(false);
paint(+nilai.value);lock(has);
</script>
@endpush
