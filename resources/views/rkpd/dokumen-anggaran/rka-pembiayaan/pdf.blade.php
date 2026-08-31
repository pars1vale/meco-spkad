<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>RKA Pembiayaan - {{ $namaUnit }}</title>
  <style>
    @page {
      size: A4 portrait;
      margin: 3mm 3mm 10mm 3mm;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      font-size: 11px;
      line-height: 1.3;
      margin: 0;
      padding: 0 3mm;
      background: #fff;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 4px 6px;
      vertical-align: middle;
      word-wrap: break-word;
      overflow-wrap: break-word;
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
      text-decoration: none;
      display: inline-block;
    }

    .no-border,
    .no-border td {
      border: none;
    }

    .no-border-right {
      border-right: none;
    }

    .no-border-left {
      border-left: none;
    }

    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }

    .bold {
      font-weight: bold;
    }

    .bg-gray {
      background: #e6e6e6;
    }

    .italic-note {
      font-style: italic;
      color: #333;
    }

    table+table {
      margin-top: 5mm;
    }

    .skpd-identity tr td {
      padding: 0;
    }

    .table-rincian thead {
      font-weight: bold;
      display: table-header-group;
    }

    .table-rincian tbody tr {
      page-break-inside: avoid;
      break-inside: avoid;
    }

    table.catatan td.c-label {
      width: 120px;
    }

    table.catatan td.c-colon {
      width: 10px;
    }

    .tim-anggaran {
      font-size: 12px;
    }

    .keep-together {
      page-break-inside: avoid;
      break-inside: avoid;
    }

    @media print {
      .print-btn {
        display: none;
      }
    }
  </style>
</head>

<body>

  @if (!($isDownload ?? false))
    <a class="print-btn"
      href="{{ route('rka-pembiayaan.unduh', $idSkpd) }}?tanggal_ttd={{ urlencode($tanggalTtdRaw) }}&nama_ttd={{ urlencode($namaTtdRaw) }}&nip_ttd={{ urlencode($nipTtdRaw) }}"
      target="_blank">
      Unduh PDF
    </a>
  @endif

  <table class="keep-together">
    <tr>
      <td colspan="2" class="text-center bold" style="width:75%; font-size: 16px;">
        RENCANA KERJA DAN ANGGARAN<br>
        SATUAN KERJA PERANGKAT DAERAH
      </td>
      <td rowspan="2" class="text-center bold" style="width:25%; font-size: 14px;">
        Formulir<br>
        RKA-PEMBIAYAAN<br>
        SKPD
      </td>
    </tr>
    <tr>
      <td colspan="2" class="text-center" style="padding: 3mm;">
        Pemerintahan Kab. {{ $kabupaten }} Tahun Anggaran {{ $tahunAnggaran }}
      </td>
    </tr>
  </table>

  <table class="skpd-identity keep-together no-border">
    <tr>
      <td style="width: 15%;">Organisasi</td>
      <td style="width: 3%;">:</td>
      <td style="width: 82%;" colspan="2">{{ $organisasi }}</td>
    </tr>
  </table>

  <table class="keep-together">
    <tr>
      <td class="text-center bold" style="font-size: 16px; padding: 3mm;">
        Rincian Anggaran Pembiayaan<br>
        Satuan Kerja Perangkat Daerah
      </td>
    </tr>
  </table>

  <table class="table-rincian">
    <colgroup>
      <col style="width: 15%;">
      <col style="width: 55%;">
      <col style="width: 30%;">
    </colgroup>
    <thead>
      <tr class="text-center">
        <td>Kode Rekening</td>
        <td>Uraian</td>
        <td>Jumlah (Rp)</td>
      </tr>
    </thead>
    <tbody>
      @foreach ($rowsPenerimaan as $row)
        <tr @if ($row['type'] === 'header') class="bold" @endif>
          <td>{{ $row['kode'] }}</td>
          <td>{{ $row['nama'] }}</td>
          <td class="text-right">{{ $service->formatRupiah($row['jumlah']) }}</td>
        </tr>
      @endforeach
      @if (count($rowsPenerimaan) > 0)
        <tr class="bold bg-gray text-right">
          <td style="padding: 2mm;" colspan="2">Jumlah Penerimaan Pembiayaan</td>
          <td>{{ $service->formatRupiah($totalPenerimaan) }}</td>
        </tr>
      @endif
      @foreach ($rowsPengeluaran as $row)
        <tr @if ($row['type'] === 'header') class="bold" @endif>
          <td>{{ $row['kode'] }}</td>
          <td>{{ $row['nama'] }}</td>
          <td class="text-right">{{ $service->formatRupiah($row['jumlah']) }}</td>
        </tr>
      @endforeach
      @if (count($rowsPengeluaran) > 0)
        <tr class="bold bg-gray text-right">
          <td style="padding: 2mm;" colspan="2">Jumlah Pengeluaran Pembiayaan</td>
          <td>{{ $service->formatRupiah($totalPengeluaran) }}</td>
        </tr>
      @endif
      <tr class="bold bg-gray text-right">
        <td style="padding: 2mm;" colspan="2">Jumlah Pembiayaan Netto</td>
        <td>{{ $service->formatRupiah($totalNetto) }}</td>
      </tr>
      <tr>
        <td colspan="2"></td>
        <td class="text-center">
          Kab. {{ $kabupaten }}, {{ $tanggalTtd }}<br>
          Kepala {{ $namaUnit }}
          <br><br><br><br>
          {{ $namaTtd }}<br>
          NIP. {{ $nipTtd }}
        </td>
      </tr>
    </tbody>
  </table>

  <br>

  <table class="catatan">
    <tbody>
      <tr>
        <td class="c-label bold">Pembahasan</td>
        <td class="c-colon">:</td>
        <td></td>
      </tr>
      <tr>
        <td class="c-label bold">Tanggal</td>
        <td class="c-colon">:</td>
        <td></td>
      </tr>
      <tr>
        <td class="c-label bold">Catatan</td>
        <td class="c-colon">:</td>
        <td></td>
      </tr>
      <tr>
        <td colspan="2">1.</td>
        <td></td>
      </tr>
      <tr>
        <td colspan="2">2.</td>
        <td></td>
      </tr>
      <tr>
        <td colspan="2">Dst</td>
        <td></td>
      </tr>
    </tbody>
  </table>

  <table class="tim-anggaran keep-together">
    <tr>
      <td colspan="5" class="bold text-center">Tim Anggaran Pemerintahan Daerah</td>
    </tr>
    <tr class="bold text-center">
      <td style="width:5%;">No</td>
      <td style="width:33%;">Nama</td>
      <td style="width:27%;">NIP</td>
      <td style="width:20%;">Jabatan</td>
      <td style="width:15%;">Tanda Tangan</td>
    </tr>
    <tr>
      <td class="text-center">1</td>
      <td>[ NAMA ]</td>
      <td>[ NIP ]</td>
      <td>Ketua TAPD</td>
      <td></td>
    </tr>
  </table>

</body>

</html>
