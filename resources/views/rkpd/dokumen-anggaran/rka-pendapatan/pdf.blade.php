<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>RKA Pendapatan SKPD</title>
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

    .download-btn {
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
    }

    .no-boder {
      border: none !important;
    }

    .no-border-right {
      border-right: none !important;
    }

    .no-border-left {
      border-left: none !important;
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
    }

    .table-ringkasan {
      margin-top: 0;
      border-top: none;
    }

    .table-ringkasan td {
      border-top: none;
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

    @media print {
      .download-btn {
        display: none;
      }

      table.table-rincian thead {
        display: table-header-group;
      }

      table.table-rincian tbody tr {
        page-break-inside: avoid;
        break-inside: avoid;
      }

      .table-ringkasan {
        page-break-inside: avoid;
        break-inside: avoid;
      }

      .keep-together {
        page-break-inside: avoid;
        break-inside: avoid;
      }
    }
  </style>
</head>

<body>
  @if (!($isDownload ?? false))
    <a class="download-btn"
      href="{{ route('rka-pendapatan.unduh', [
          'idSkpd' => $idSkpd,
          'tanggal_ttd' => $tanggalTtdRaw,
          'nama_ttd' => $namaTtdRaw,
          'nip_ttd' => $nipTtdRaw,
      ]) }}">
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
        Formulir<br>RKA-PENDAPATAN<br>SKPD
      </td>
    </tr>
    <tr>
      <td colspan="2" class="text-center" style="padding: 3mm;">
        Pemerintahan Kab. {{ $kabupaten }} Tahun Anggaran {{ $tahunAnggaran }}
      </td>
    </tr>
  </table>

  <table class="skpd-identity keep-together">
    <tr>
      <td class="no-boder" style="width: 15%;">Organisasi</td>
      <td class="no-boder" style="width: 3%;">:</td>
      <td class="no-boder" style="width: 82%;" colspan="2">{{ $organisasi }}</td>
    </tr>
  </table>

  <table class="keep-together">
    <tr>
      <td class="text-center bold" style="font-size: 16px; padding: 3mm;">
        Rincian Anggaran Pendapatan<br>Satuan Kerja Perangkat Daerah
      </td>
    </tr>
  </table>

  @if ($isEmpty ?? true)
    <table class="keep-together">
      <tr>
        <td class="text-center italic-note" style="padding: 5mm;">
          Tidak ada data pendapatan untuk SKPD ini pada tahun anggaran {{ $tahunAnggaran }}.
        </td>
      </tr>
    </table>
  @else
    <table class="table-rincian">
      <colgroup>
        <col style="width: 15%;">
        <col style="width: 30%;">
        <col style="width: 14%;">
        <col style="width: 11%;">
        <col style="width: 15%;">
        <col style="width: 15%;">
      </colgroup>
      <thead>
        <tr class="text-center">
          <td rowspan="2">Kode Rekening</td>
          <td rowspan="2">Uraian</td>
          <td colspan="3">Rincian Perhitungan</td>
          <td rowspan="2">Jumlah (Rp)</td>
        </tr>
        <tr class="text-center">
          <td>Volume / Koefisien</td>
          <td>Satuan</td>
          <td>Tarif / Harga</td>
        </tr>
      </thead>
      <tbody>
        @foreach ($rows as $row)
          @if ($row['type'] === 'header')
            <tr>
              <td>{{ $row['kode'] }}</td>
              <td>{{ $row['nama'] }}</td>
              <td class="text-right"></td>
              <td class="text-right"></td>
              <td class="text-right"></td>
              <td class="text-right">{{ $service->formatRupiah($row['jumlah']) }}</td>
            </tr>
          @else
            {{-- Baris keterangan (mengikuti pola mockup: sengaja duplikasi uraian) --}}
            <tr class="tr-multiline">
              <td></td>
              <td>::{{ $row['uraian'] }}<br>[:::{{ $row['keterangan'] }}:::]</td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
            <tr>
              <td>{{ $row['kode'] }}</td>
              <td>{{ $row['uraian'] }}<br>[:::{{ $row['keterangan'] }}:::]</td>
              <td class="text-center">{{ $row['koefisien'] !== '' ? $row['koefisien'] : $row['volume'] }}</td>
              <td class="text-center">{{ $row['satuan'] }}</td>
              <td class="text-right">{{ $service->formatRupiah($row['nilaimurni']) }}</td>
              <td class="text-right">{{ $service->formatRupiah($row['total']) }}</td>
            </tr>
          @endif
        @endforeach
      </tbody>
    </table>

    <table class="table-rincian table-ringkasan">
      <colgroup>
        <col style="width: 60%;">
        <col style="width: 40%;">
      </colgroup>
      <tbody>
        <tr class="bold bg-gray text-right">
          <td style="padding: 2.5mm;">Jumlah Pendapatan</td>
          <td>{{ $service->formatRupiah($totalPendapatan) }}</td>
        </tr>
        <tr>
          <td style="border-right: none; border-top: none;"></td>
          <td class="text-center" style="border-top: none;">
            Kab. {{ $kabupaten }}, {{ $tanggalTtd }}<br>
            Kepala {{ $namaUnit }}
            <br><br><br><br>
            {{ $namaTtd }}<br>
            NIP. {{ $nipTtd }}
          </td>
        </tr>
      </tbody>
    </table>
  @endif

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
  <br>

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
  </table>

</body>

</html>
