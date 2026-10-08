@extends('layouts.admin-dashboard')

@section('title', config('app.name') . ' | Tambah Laporan Anggaran')

@section('content')
<div class="space-y-6">
    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm md:p-8">
        <h1 class="text-3xl font-bold tracking-[1px] text-slate-800">Tambah Laporan Anggaran</h1>
        <p class="mt-2 text-sm text-slate-600">Isi identitas laporan dan rincian pendapatan, belanja, atau pembiayaan.</p>
    </div>
    @include('admin.anggaran.form', ['action' => route('admin.anggaran.store'), 'method' => 'POST'])
</div>
@endsection
