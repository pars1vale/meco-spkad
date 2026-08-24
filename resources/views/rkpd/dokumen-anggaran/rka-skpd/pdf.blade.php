<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>RKA SKPD</title>

  <style>
    @page {
      size: A4;
      margin: 1mm 3mm 10mm 3mm;
    }

    * {
      box-sizing: border-box;
    }

    body {
      width: 210mm;
      min-height: 297mm;
      margin: 0 auto;
      padding: 12mm;
      font-family: "Arial", sans-serif;
      font-size: 11px;
      background: #fff;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    td,
    th {
      border: 1px solid #000;
      padding: 4px 6px;
    }

    .print-btn {
      position: fixed;
      top: 16px;
      right: 16px;
      padding: 10px 16px;
      font-family: Arial, sans-serif;
      font-size: 16px;
      background: #2563eb;
      color: #fff;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    @media print {
      .print-btn {
        display: none;
      }
    }

    .center {
      text-align: center;
    }

    .right {
      text-align: right;
    }

    .bold {
      font-weight: bold;
    }

    .no-border {
      border: none !important;
    }

    .header td {
      height: 28px;
    }

    .title {
      font-size: 18px;
      font-weight: bold;
    }

    .subtitle {
      font-size: 18px;
      font-weight: bold;
    }

    .section {
      margin-top: 10px;
      margin-bottom: 10px;
    }

    .lv1 {
      font-weight: bold;
    }

    .lv2 {
      font-weight: bold;
    }

    .empty-state {
      text-align: center;
      padding: 40px 0;
      font-size: 14px;
    }

    /* =====================================================
       TABEL AKUN
       ===================================================== */

    .account-table {
      width: 100%;
      table-layout: fixed;
      border-collapse: collapse;
    }

    .account-table td,
    .account-table th {
      border: 1px solid #000;
      padding: 4px 6px;
    }

    .account-code-1,
    .account-code-2,
    .account-code-3 {
      width: 6%;
    }

    .description {
      width: 60%;
    }

    .amount {
      width: 22%;
    }

    .account-code {
      text-align: center;
      vertical-align: middle;
      white-space: nowrap;
    }

    .account-table thead th {
      text-align: center;
      vertical-align: middle;
    }

    .account-table .amount-cell {
      text-align: right;
      white-space: nowrap;
    }

    .sign-cell {
      vertical-align: top;
      padding: 12px 8px;
    }

    .sign-box {
      display: inline-block;
      width: 260px;
      max-width: 100%;
      text-align: center;
      line-height: 1.5;
    }

    .sign-date {
      margin-bottom: 4px;
    }

    .sign-title {
      margin-bottom: 4px;
    }

    .sign-space {
      height: 70px;
    }

    .sign-name {
      font-weight: bold;
      text-decoration: underline;
      white-space: nowrap;
    }

    .sign-nip {
      margin-top: 2px;
    }
  </style>
</head>

<body>

  <button class="print-btn" onclick="window.print()">Cetak / Simpan PDF</button>
  <table class="header">
    <tr>
      <td colspan="2" class="center">
        <div class="title">RENCANA KERJA DAN ANGGARAN</div>
        <div class="subtitle">SATUAN KERJA PERANGKAT DAERAH</div>
      </td>
      <td rowspan="2" class="center" style="width:160px"><b>Formulir</b><br><b>RKA SKPD</b></td>
    </tr>
    <tr>
      <td colspan="2" class="center">Pemerintahan Kab. {{ $kabupaten }} Tahun Anggaran {{ $tahunAnggaran }}</td>
    </tr>
  </table>

  <table class="section">
    <tr>
      <td class="no-border" style="width:90px">Organisasi</td>
      <td class="no-border" style="width:10px">:</td>
      <td class="no-border">{{ $organisasi }}</td>
    </tr>
  </table>

  <table class="section">
    <tr>
      <td class="center">
        <div style="font-size:16px;font-weight:bold">Ringkasan Anggaran Pendapatan dan Belanja</div>
        <div style="font-size:16px;font-weight:bold">Satuan Kerja Perangkat Daerah</div>
      </td>
    </tr>
  </table>
  @if ($semuaKosong)
    <table>
      <tr>
        <td class="empty-state">Tidak ada data sama sekali untuk SKPD ini</td>
      </tr>
    </table>
  @else
    <table class="account-table">
      <colgroup>
        <col class="account-code-1">
        <col class="account-code-2">
        <col class="account-code-3">
        <col class="description">
        <col class="amount">
      </colgroup>
      <thead>
        <tr>
          <th colspan="3">Kode Rekening</th>
          <th>Uraian</th>
          <th>Jumlah (Rp)</th>
        </tr>
      </thead>
      <tbody>
        @unless ($pendapatan['kosong'])
          <tr class="lv1">
            <td class="account-code">{{ $pendapatan['kode'] }}</td>
            <td class="account-code"></td>
            <td class="account-code"></td>
            <td>{{ $pendapatan['label'] }}</td>
            <td></td>
          </tr>
          @foreach ($pendapatan['groups'] as $group)
            <tr class="lv2">
              <td class="account-code">{{ $group['kodeParts'][0] ?? '' }}</td>
              <td class="account-code">{{ $group['kodeParts'][1] ?? '' }}</td>
              <td class="account-code"></td>
              <td>{{ $group['nama'] }}</td>
              <td class="amount-cell">{{ $service->formatRupiah($group['jumlah']) }}</td>
            </tr>
            @foreach ($group['children'] as $child)
              <tr>
                <td class="account-code">{{ $child->kodeParts[0] ?? '' }}</td>
                <td class="account-code">{{ $child->kodeParts[1] ?? '' }}</td>
                <td class="account-code">{{ $child->kodeParts[2] ?? '' }}</td>
                <td>{{ $child->nama_akun }}</td>
                <td class="amount-cell">{{ $service->formatRupiah($child->jumlah) }}</td>
              </tr>
            @endforeach
          @endforeach
          <tr>
            <td colspan="4" class="right bold">Jumlah Pendapatan</td>
            <td class="amount-cell bold">{{ $service->formatRupiah($pendapatan['total']) }}</td>
          </tr>
        @endunless
        @unless ($belanja['kosong'])
          <tr class="lv1">
            <td class="account-code">{{ $belanja['kode'] }}</td>
            <td class="account-code"></td>
            <td class="account-code"></td>
            <td>{{ $belanja['label'] }}</td>
            <td></td>
          </tr>
          @foreach ($belanja['groups'] as $group)
            <tr class="lv2">
              <td class="account-code">{{ $group['kodeParts'][0] ?? '' }}</td>
              <td class="account-code">{{ $group['kodeParts'][1] ?? '' }}</td>
              <td class="account-code"></td>
              <td>{{ $group['nama'] }}</td>
              <td class="amount-cell">{{ $service->formatRupiah($group['jumlah']) }}</td>
            </tr>
            @foreach ($group['children'] as $child)
              <tr>
                <td class="account-code">{{ $child->kodeParts[0] ?? '' }}</td>
                <td class="account-code">{{ $child->kodeParts[1] ?? '' }}</td>
                <td class="account-code">{{ $child->kodeParts[2] ?? '' }}</td>
                <td>{{ $child->nama_akun }}</td>
                <td class="amount-cell">{{ $service->formatRupiah($child->jumlah) }}</td>
              </tr>
            @endforeach
          @endforeach
          <tr>
            <td colspan="4" class="right bold">Jumlah Belanja</td>
            <td class="amount-cell bold">{{ $service->formatRupiah($belanja['total']) }}</td>
          </tr>
        @endunless
        @if ($tampilkanSurplusDefisit)
          <tr>
            <td colspan="4" class="right bold">Total Surplus / (Defisit)</td>
            <td class="amount-cell bold">{{ $service->formatRupiah($surplusDefisit) }}</td>
          </tr>
        @endif
        @unless ($pembiayaan['kosong'])
          <tr class="lv1">
            <td class="account-code">{{ $pembiayaan['kode'] }}</td>
            <td class="account-code"></td>
            <td class="account-code"></td>
            <td>{{ $pembiayaan['label'] }}</td>
            <td></td>
          </tr>
          @foreach ($pembiayaan['groups'] as $group)
            <tr class="lv2">
              <td class="account-code">{{ $group['kodeParts'][0] ?? '' }}</td>
              <td class="account-code">{{ $group['kodeParts'][1] ?? '' }}</td>
              <td class="account-code"></td>
              <td>{{ $group['nama'] }}</td>
              <td class="amount-cell">{{ $service->formatRupiah($group['jumlah']) }}</td>
            </tr>
            @foreach ($group['children'] as $child)
              <tr>
                <td class="account-code">{{ $child->kodeParts[0] ?? '' }}</td>
                <td class="account-code">{{ $child->kodeParts[1] ?? '' }}</td>
                <td class="account-code">{{ $child->kodeParts[2] ?? '' }}</td>
                <td>{{ $child->nama_akun }}</td>
                <td class="amount-cell">{{ $service->formatRupiah($child->jumlah) }}</td>
              </tr>
            @endforeach
          @endforeach
        @endunless
        @if ($tampilkanPembiayaanTotal)
          <tr>
            <td colspan="4" class="right bold">Jumlah Pengeluaran Pembiayaan</td>
            <td class="amount-cell bold">{{ $service->formatRupiah($totalPengeluaranPembiayaan) }}</td>
          </tr>
          <tr>
            <td colspan="4" class="right bold">Pembiayaan Netto</td>
            <td class="amount-cell bold">{{ $service->formatRupiah($pembiayaanNetto) }}</td>
          </tr>
        @endif
      </tbody>
    </table>

    <table class="ttd-table" style="border-top: none;">
      <tr>
        <td style="border-right: none; width:60%">
        </td>
        <td style="border-left: none;" class="center sign-cell">
          <div class="sign-box">
            <div class="sign-date">Kabupaten {{ $kabupaten }}, {{ $tanggalTtd }}</div>
            <div class="sign-title">Kepala {{ $namaUnit }}</div>
            <div class="sign-space"></div>
            <div class="sign-name">{{ $namaTtd }}</div>
            <div class="sign-nip">NIP. {{ $nipTtd }}</div>
          </div>
        </td>
      </tr>
    </table>
  @endif
</body>

</html>
