<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Monitoring</title>
</head>
<body>
    <h2>Laporan Monitoring</h2>
    <p><strong>Nama:</strong> {{ $nama }}</p>
    <p><strong>Unit:</strong> {{ $unit }}</p>
    <p><strong>Email Tujuan:</strong> {{ $email }}</p>
    <p><strong>Isi:</strong><br>{{ $isi }}</p>

    @if (!empty($file))
        <p><strong>Lampiran:</strong> 
            <a href="{{ asset('storage/' . $file) }}" target="_blank">
                Klik di sini untuk melihat file
            </a>
        </p>
    @endif
</body>
</html>
