<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <link rel="stylesheet" href="{{ asset('assets/css/errors/403.css') }}">
</head>

<body>
  <main class="bsod container">
    <h1 class="neg title"><span class="bg">Error - 403</span></h1>
    <p>Akses Ditolak: Anda tidak memiliki izin untuk mengakses MENU yang anda pilih diserver ini</p>
    <p>Silahkan kembali ke halaman sebelumnya....</p>
    <nav class="nav">
      <a href="{{ url('/home') }}" class="link">kembali</a>
    </nav>
  </main>
</body>

</html>
