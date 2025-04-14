<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Notifikasi Jatuh Tempo Pembayaran ' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        .header {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .content {
            font-size: 16px;
            line-height: 1.5;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">{{ $title ?? 'Notifikasi Notifikasi Jatuh Tempo Pembayaran' }}</div>
        <div class="content">
            Selamat pagi Admin, sewa atas nama {{$nama}} akan berakhir dalam 7 hari lagi pada tanggal {{$tanggal}} silahkan lakukan penagihan pada penyewa sebelum jatuh tempo 
        </div>
        <div class="footer">Terima kasih,<br>Sistem Manajemen Kost</div>
    </div>
</body>
</html>