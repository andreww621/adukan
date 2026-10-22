@extends('layouts.app')
@section('title','Pembayaran')
@section('page','dash')
@section('content')
@php $rp = fn ($n) => 'Rp ' . number_format($n, 0, ',', '.'); $cukup = $aktif && $p->dana <= $sisa; @endphp
<main class="page" style="max-width:520px">
<header><h1>Pembayaran</h1><a href="{{ route('dashboard', 'kepsek') }}" style="color:var(--red)">Kembali ke dashboard</a></header>
<form id="pay" class="card" method="POST" action="{{ route('kepsek.bayar', $p) }}" novalidate>
@csrf
<div class="items" style="padding:0">
    <div class="item"><div class="thumb">{{ $p->kategori[0] }}</div><div><b>{{ $p->kategori }} - {{ $p->lokasi }}</b><small>{{ $p->deskripsi }}</small></div></div>
    <div class="item"><div><small>Dana yang dikirim ke teknisi</small><b>{{ $p->dana_rp }}</b><small>Sisa anggaran {{ $rp($sisa) }}</small></div></div>
</div>
@if (! $aktif)<div class="err" style="margin-top:10px">Anggaran belum tersedia. Minta admin mengaktifkannya.</div>
@elseif (! $cukup)<div class="err" style="margin-top:10px">Sisa anggaran tidak cukup untuk dana ini.</div>@endif
@if ($errors->any())<div class="err" style="margin-top:10px">{{ $errors->first() }}</div>@endif
<label for="tk">Teknisi penerima</label>
<select id="tk" name="teknisi_id" required><option value="">Pilih teknisi</option>@foreach ($teknisis as $t)<option value="{{ $t->id }}" @selected(old('teknisi_id') == $t->id)>{{ $t->name }}</option>@endforeach</select>
<label for="rek">Nomor rekening teknisi</label><input id="rek" name="norek" inputmode="numeric" autocomplete="off" value="{{ old('norek') }}" placeholder="Hanya angka">
<label>{{ $punyaPin ? 'PIN (6 digit)' : 'Buat PIN pembayaran (6 digit)' }}</label>
@unless ($punyaPin)<p class="sub">Ini pembayaran pertama Anda. PIN yang diisi sekarang akan dipakai untuk pembayaran berikutnya.</p>@endunless
<div class="pins" id="pins">@for ($i = 0; $i < 6; $i++)<input type="password" inputmode="numeric" maxlength="1" autocomplete="off" aria-label="PIN digit {{ $i + 1 }}">@endfor</div>
<input type="hidden" name="pin" id="pin">
<button class="btn" id="go" style="width:100%" disabled>Kirim dana</button>
</form></main>
@endsection
@push('scripts')
<script>
const okAng={{ $cukup ? 'true' : 'false' }},ds=[...document.querySelectorAll('#pins input')];
const chk=()=>{go.disabled=!(okAng&&tk.value&&rek.value.replace(/\D/g,'').length>=8&&ds.every(d=>d.value))};
rek.oninput=()=>{rek.value=rek.value.replace(/\D/g,'');chk()};tk.onchange=chk;
ds.forEach((d,i)=>{d.oninput=()=>{d.value=d.value.replace(/\D/g,'');if(d.value&&ds[i+1])ds[i+1].focus();chk()};
 d.onkeydown=e=>{if(e.key=='Backspace'&&!d.value&&ds[i-1])ds[i-1].focus()};
 d.onpaste=e=>{e.preventDefault();const t=(e.clipboardData.getData('text')||'').replace(/\D/g,'').slice(0,6);[...t].forEach((c,k)=>ds[k].value=c);chk()}});
pay.onsubmit=()=>{pin.value=ds.map(d=>d.value).join('');go.disabled=true;go.textContent='Mengirim...'};
chk();
</script>
@endpush
