@extends('layouts.admin-dashboard')

@section('title', config('app.name') . ' | Edit Laporan Anggaran')

@section('content')
<div class="space-y-6">
    <div class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm md:p-8">
        <h1 class="text-3xl font-bold tracking-[1px] text-slate-800">Edit Laporan Anggaran</h1>
        <p class="mt-2 text-sm text-slate-600">Perbarui informasi laporan dan rincian anggaran.</p>
    </div>
    @include('admin.anggaran.form', ['action' => route('admin.anggaran.update', $laporan), 'method' => 'PUT'])
</div>
@endsection
