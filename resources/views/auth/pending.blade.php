<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menunggu Persetujuan</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 24px;">
    <h2>Akun Anda masih menunggu persetujuan</h2>
    <p>
        Data pendaftaran Anda sedang diverifikasi oleh admin/petugas.
        Silakan tunggu sampai akun disetujui untuk dapat mengakses dashboard.
    </p>

    @if(auth()->check() && auth()->user()->approval_status === 'rejected')
        <div style="margin-top: 12px; padding: 12px; border: 1px solid #f0c2c2; background: #fff5f5;">
            <strong>Pendaftaran ditolak.</strong><br>
            Alasan: {{ auth()->user()->rejection_reason ?? '-' }}
        </div>
    @endif

    <form method="POST" action="{{ route('logout') }}" style="margin-top: 16px;">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
