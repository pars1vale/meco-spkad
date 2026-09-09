<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Rekap RKA Belanja SKPD</title>
  <style>
    /* @page {
      size: landscape;
      margin: 4mm 6mm 10mm 6mm;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      line-height: 1.2;
      margin: 4mm 6mm 10mm 6mm;
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
    } */

    @page {
      size: A4 landscape;
      margin: 4mm 6mm 10mm 6mm;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      font-size: 8pt;
      line-height: 1.15;
      margin: 0;
      background: #fff;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 2px 3px;
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
      border: none !important;
    }

    .no-border-top td {
      border-top: none !important;
    }

    /* .f14 {
      font-size: 14px;
    }

    .f16 {
      font-size: 16px;
    }

    .f18 {
      font-size: 18px;
    }

    .bold {
      font-weight: bold;
    } */

    .f14 {
      font-size: 10pt;
    }

    .f16 {
      font-size: 12pt;
    }

    .f18 {
      font-size: 14pt;
    }

    .bold {
      font-weight: bold;
    }

    .text-center {
      text-align: center;
    }

    .text-right {
      text-align: right;
    }

    .bg-gray {
      background: #e5e5e5;
    }

    .header td {
      padding: 4mm 6mm;
    }

    .skpd-identity {
      margin-top: 8mm;
    }

    .skpd-identity td {
      padding: 0;
    }

    .sub-header {
      margin-top: 8mm;
    }

    .sub-header td {
      padding: 4mm 6mm;
    }

    .rincian-belanja {
      margin-top: 5mm;
    }

    .rincian-belanja thead {
      font-weight: bold;
    }
  </style>
</head>

<body>
  @if (!($isDownload ?? false))
    <a class="print-btn"
      href="{{ route('rka-rekap-belanja.unduh', $idSkpd) }}?tanggal_ttd={{ urlencode($tanggalTtdRaw) }}&nama_ttd={{ urlencode($namaTtdRaw) }}&nip_ttd={{ urlencode($nipTtdRaw) }}"
      target="_blank">
      Unduh PDF
    </a>
  @endif

  <table class="header">
    <tr>
      <td colspan="2" class="text-center f18 bold" style="width:80%;">
        RENCANA KERJA DAN ANGGARAN <br>
        SATUAN KERJA PERANGKAT DAERAH
      </td>
      <td rowspan="2" class="text-center f16 bold" style="width:20%;">
        REKAPITULASI<br>
        RKA-BELANJA<br>SKPD
      </td>
    </tr>
    <tr>
      <td colspan="2" class="text-center f14">
        Pemerintahan Kab. {{ $kabupaten }} Tahun Anggaran {{ $tahunAnggaran }}
      </td>
    </tr>
  </table>

  <table class="skpd-identity no-border">
    <tr>
      <td style="width: 9%;">Organisasi</td>
      <td style="width: 1%;">:</td>
      <td style="width: 90%;">{{ $organisasi }}</td>
    </tr>
  </table>

  <table class="sub-header">
    <tr>
      <td class="text-center f16 bold">
        Rincian Anggaran Belanja<br>Berdasarkan Program dan Kegiatan
      </td>
    </tr>
  </table>

  @php
    $rp = fn($v) => 'Rp' . number_format((float) $v, 2, ',', '.');
  @endphp

  <table class="rincian-belanja">
    <colgroup>
      <col style="width: 2%;">
      <col style="width: 2%;">
      <col style="width: 2%;">
      <col style="width: 2%;">
      <col style="width: 2%;">
      <col style="width: 19%;">
      <col style="width: 8%;">
      <col style="width: 8%;">
      <col style="width: 7%;">
      <col style="width: 8%;">
      <col style="width: 8%;">
      <col style="width: 9%;">
      <col style="width: 8%;">
      <col style="width: 8%;">
      <col style="width: 7%;">
    </colgroup>
    <thead>
      <tr class="text-center">
        <td colspan="5" rowspan="3">Kode</td>
        <td rowspan="3">Uraian</td>
        <td rowspan="3">Sumber Dana</td>
        <td rowspan="3">Lokasi</td>
        <td colspan="7">Jumlah</td>
      </tr>
      <tr class="text-center">
        <td rowspan="2">Tahun - 1</td>
        <td colspan="5">Tahun N</td>
        <td rowspan="2">Tahun + 1</td>
      </tr>
      <tr class="text-center">
        <td>Belanja Operasi</td>
        <td>Belanja Modal</td>
        <td>Belanja Tidak Terduga</td>
        <td>Belanja Transfer</td>
        <td>Jumlah (Rp)</td>
      </tr>
    </thead>
    <tbody>
      <tr class="lvl-skpd">
        <td colspan="15">{{ $organisasi }}</td>
      </tr>

      @foreach ($rows as $row)
        @php
          $segments = explode('.', (string) $row['kode']);
          $displaySegments = match ((int) $row['level']) {
              0 => [$segments[0] ?? '', '', '', '', ''],
              1 => [$segments[0] ?? '', $segments[1] ?? '', '', '', ''],
              2 => [$segments[0] ?? '', $segments[1] ?? '', $segments[2] ?? '', '', ''],
              3 => [
                  $segments[0] ?? '',
                  $segments[1] ?? '',
                  $segments[2] ?? '',
                  ($segments[3] ?? '') . (isset($segments[4]) ? '.' . $segments[4] : ''),
                  '',
              ],
              4 => [
                  $segments[0] ?? '',
                  $segments[1] ?? '',
                  $segments[2] ?? '',
                  ($segments[3] ?? '') . (isset($segments[4]) ? '.' . $segments[4] : ''),
                  $segments[5] ?? '',
              ],
              default => ['', '', '', '', ''],
          };
        @endphp

        <tr class="lvl-{{ ['urusan', 'bidang', 'program', 'kegiatan', 'subkeg'][$row['level']] }}">
          @foreach ($displaySegments as $seg)
            <td class="text-center kode-cell">{{ $seg }}</td>
          @endforeach

          <td>{{ $row['nama'] }}</td>

          @if ($row['level'] == 4)
            <td>{{ $row['sumber_dana'] ?? '' }}</td>
            <td>{{ $row['lokasi'] ?? '' }}</td>
            <td class="text-right">{{ $rp($row['tahun_min_1']) }}</td>
            <td class="text-right">{{ $rp($row['belanja_operasi']) }}</td>
            <td class="text-right">{{ $rp($row['belanja_modal']) }}</td>
            <td class="text-right">{{ $rp($row['belanja_tidak_terduga']) }}</td>
            <td class="text-right">{{ $rp($row['belanja_transfer']) }}</td>
            <td class="text-right">{{ $rp($row['total_keseluruhan']) }}</td>
            <td class="text-right">{{ $rp($row['tahun_plus_1']) }}</td>
          @else
            <td colspan="9"></td>
          @endif
        </tr>
      @endforeach

      <tr class="bold bg-gray text-right">
        <td colspan="8">Jumlah</td>
        <td>{{ $rp($grandTotal['tahun_min_1']) }}</td>
        <td>{{ $rp($grandTotal['belanja_operasi']) }}</td>
        <td>{{ $rp($grandTotal['belanja_modal']) }}</td>
        <td>{{ $rp($grandTotal['belanja_tidak_terduga']) }}</td>
        <td>{{ $rp($grandTotal['belanja_transfer']) }}</td>
        <td>{{ $rp($grandTotal['total_keseluruhan']) }}</td>
        <td>{{ $rp($grandTotal['tahun_plus_1']) }}</td>
      </tr>
    </tbody>
  </table>

  <table class="no-border-top">
    <tr>
      <td style="width: 50%;"></td>
      <td class="text-center">
        Kab. {{ $kabupaten }}, {{ $tanggalTtd }}<br>
        Kepala {{ $namaUnit }}
        <br><br><br><br><br><br><br>
        {{ $namaTtd }}<br>
        NIP. {{ $nipTtd }}
      </td>
    </tr>
  </table>
</body>

</html>
