<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pesanan Anda Dikirim&nbsp; {{ $order->invoice }}</title>
</head>
<body>

    <h2>Hai, {{ $order->customer->name }}</h2>
    <p>Terima kasih telah melakukan transaksi pada aplikasi kami, nomor resi dari pesanan Anda: <strong>{{ $order->tracking_number }}</strong></p>
    
</body>
</html>