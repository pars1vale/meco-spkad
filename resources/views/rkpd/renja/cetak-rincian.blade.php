<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>RKA Belanja SKPD - {{ $subKegiatan->nama_sub_giat }}</title>
  <style>
    * {
      box-sizing: border-box;
    }

    @page {
      size: 297mm 420mm;
      margin: 1mm 3mm 10mm 3mm
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
      color: #111;
      max-width: 940px;
      margin: 0 auto;
      padding: 20px;
      font-size: 11px;
      line-height: 1.2;
      background: #fff;
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

    /* ---------- Box: HANYA jarak antar tabel, tidak ada border di sini lagi ---------- */
    .box {
      margin-bottom: 16px;
    }

    /* kop surat bukan tabel (layout flex), jadi bingkainya berdiri sendiri */
    .kop-box {
      border: 1px solid #000;
    }

    /* box rincian: selalu mulai di halaman baru saat cetak */
    .rincian-section {
      page-break-before: always;
      break-before: page;
    }

    /* ---------- Judul tabel: baris pertama thead, gantikan div.box-title lama ---------- */
    thead tr.title-row th {
      font-size: 14px;
      font-weight: normal;
      text-align: center;
      padding: 6px 8px;
      background: #fff;
      border-bottom: 1px solid #000;
    }

    thead tr.title-row th .sub {
      display: block;
      font-size: 12px;
      font-weight: normal;
    }

    .kop {
      display: flex;
    }

    .kop .kop-left {
      flex: 1;
      text-align: center;
      padding: 14px 10px;
      font-weight: bold;
      font-size: 16px;
      line-height: 1.15;
      border-right: 1px solid #000;
    }

    .kop .kop-right {
      width: 170px;
      text-align: center;
      padding: 10px;
      font-weight: bold;
      font-size: 16px;
      line-height: 1.15;
    }

    .kop-sub {
      font-size: 14px;
      text-align: center;
      padding: 6px 8px;
      border-top: 1px solid #000;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    table th,
    table td {
      padding: 6px 8px;
      font-size: 11px;
      vertical-align: middle;
    }

    /* ---------- Tabel bergrid penuh: kinerja, rincian, tim, catatan ---------- */
    table.grid th,
    table.grid td {
      border: 1px solid #000;
    }

    table.grid tr {
      page-break-inside: avoid;
      break-inside: avoid;
    }

    table.grid th {
      text-align: center;
      background: #fafafa;
    }

    table.grid thead tr.title-row th {
      background: #fff;
    }

    table.grid td.num {
      text-align: right;
    }

    table.grid td.center {
      text-align: center;
      white-space: nowrap;
    }

    /* ---------- Tabel label:value (fields) ---------- */
    table.fields {
      border: 1px solid #000;
    }

    table.fields td {
      border-top: 1px solid #999;
    }

    table.fields tbody tr:first-child td {
      border-top: none;
    }

    table.fields td.label {
      width: 190px;
      font-weight: bold;
      border-right: 1px solid #000;
      white-space: nowrap;
    }

    table.fields td.value .line {
      display: block;
    }

    /* ---------- Header ringkas sebelum tabel rincian ---------- */
    table.header-rincian {
      border: 1px solid #000;
    }

    table.header-rincian td.header {
      border: 0.5px solid #000;
      width: 30%;
      font-weight: bold;
    }

    table.header-rincian td.data {
      border: 0.5px solid #000;
      width: 70%;
    }

    /* ---------- Rincian: spesifikasi & garis total ---------- */
    table.rincian .spesifikasi {
      font-style: italic;
      color: #444;
    }

    table.rincian tr.total-row td {
      border-top: 2px solid #000;
    }

    /* ---------- Catatan ---------- */
    table.catatan td.c-label {
      width: 120px;
    }

    table.catatan td.c-colon {
      width: 16px;
    }

    /* ---------- Tim ---------- */
    table.tim td.kosong {
      text-align: center;
      font-style: italic;
    }

    /* ---------- TTD ---------- */
    table.ttd-table {
      border: 1px solid #000;
    }

    table.ttd-table td {
      vertical-align: top;
      padding: 14px;
      height: 190px;
    }

    table.ttd-table td.ttd-empty {
      width: 54%;
      border-right: 1px solid #000;
    }

    table.ttd-table td.ttd-content {
      text-align: center;
      font-size: 11px;
    }

    .ttd-content .space {
      height: 95px;
    }

    .ttd-content .nama {
      font-weight: bold;
      text-decoration: underline;
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

      body {
        padding: 0;
      }
    }
  </style>
</head>

<body>

  <button class="print-btn" onclick="window.print()">Cetak / Simpan PDF</button>

  <div class="box kop-box">
    <div class="kop">
      <div class="kop-left">RENCANA KERJA DAN ANGGARAN<br>SATUAN KERJA PERANGKAT DAERAH</div>
      <div class="kop-right">Formulir<br>RKA-BELANJA<br>SKPD</div>
    </div>
    <div class="kop-sub">Pemerintahan Kab. {{ $kabupaten }} Tahun Anggaran {{ $tahunAnggaran }}</div>
  </div>

  <div class="box">
    <table class="fields">
      <thead>
        <tr class="title-row">
          <th colspan="2">Rincian Anggaran Belanja Menurut Program, Kegiatan dan Sub Kegiatan</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="label">Urusan Pemerintahan</td>
          <td class="value">: {{ $subKegiatan->kode_urusan }} {{ $subKegiatan->nama_urusan }}</td>
        </tr>
        <tr>
          <td class="label">Bidang Urusan</td>
          <td class="value">: {{ $subKegiatan->kode_bidang_urusan }} {{ $subKegiatan->nama_bidang_urusan }}</td>
        </tr>
        <tr>
          <td class="label">Unit Organisasi</td>
          <td class="value">: {{ $subKegiatan->kode_skpd }} {{ $subKegiatan->nama_skpd }}</td>
        </tr>
        <tr>
          <td class="label">Program</td>
          <td class="value">: {{ $subKegiatan->kode_program }} {{ $subKegiatan->nama_program }}</td>
        </tr>
        <tr>
          <td class="label">Kegiatan</td>
          <td class="value">: {{ $subKegiatan->kode_giat }} {{ $subKegiatan->nama_giat }}</td>
        </tr>
        <tr>
          <td class="label">Sub Kegiatan</td>
          <td class="value">: {{ $subKegiatan->kode_sub_giat }} {{ $subKegiatan->nama_sub_giat }}</td>
        </tr>
        <tr>
          <td class="label">SPM</td>
          <td class="value">: {{ $spm }}</td>
        </tr>
        <tr>
          <td class="label">Jenis Layanan</td>
          <td class="value">: {{ $jenisLayanan }}</td>
        </tr>
        <tr>
          <td class="label">Sumber Pendanaan</td>
          <td class="value">
            @forelse($sumberDana as $dana)
              <span class="line">: {{ $dana->namadana }}</span>
            @empty
              : -
            @endforelse
          </td>
        </tr>
        <tr>
          <td class="label">Lokasi</td>
          <td class="value">
            @forelse($lokasi as $lok)
              <span class="line">: {{ $lok->lokasi }}</span>
            @empty
              : -
            @endforelse
          </td>
        </tr>
        <tr>
          <td class="label">Waktu Pelaksanaan</td>
          <td class="value">: {{ $waktuPelaksanaan }}</td>
        </tr>
        <tr>
          <td class="label">Kelompok Sasaran</td>
          <td class="value">: {{ $kelompokSasaran }}</td>
        </tr>
        <tr>
          <td class="label">Alokasi {{ $tahunAnggaran - 1 }}</td>
          <td class="value">: Rp. {{ number_format($subKegiatan->pagumurni, 2, ',', '.') }}</td>
        </tr>
        <tr>
          <td class="label">Alokasi {{ $tahunAnggaran }}</td>
          <td class="value">: Rp. {{ number_format($subKegiatan->pagu, 2, ',', '.') }}</td>
        </tr>
        <tr>
          <td class="label">Alokasi {{ $tahunAnggaran + 1 }}</td>
          <td class="value">: Rp. {{ number_format($subKegiatan->pagu_n_depan, 2, ',', '.') }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="box">
    <table class="grid kinerja">
      <thead>
        <tr class="title-row">
          <th colspan="3">Indikator dan Tolak Ukur Kinerja Kegiatan</th>
        </tr>
        <tr>
          <th style="width:21%;">Indikator</th>
          <th style="width:63%;">Tolok Ukur Kinerja</th>
          <th style="width:16%;">Target Kinerja</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="bold">Capaian Program</td>
          <td>{{ $capaianProgram }}</td>
          <td>{{ $targetCapaianProgram }}</td>
        </tr>
        <tr>
          <td class="bold">Masukan</td>
          <td>Dana yang dibutuhkan</td>
          <td>Rp. {{ number_format($subKegiatan->pagu, 2, ',', '.') }}</td>
        </tr>
        @forelse($indikator as $ind)
          <tr>
            <td class="bold">Keluaran</td>
            <td>{{ $ind->outputteks }}</td>
            <td>{{ $ind->targetoutput }} {{ $ind->satuanoutput }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="3" class="text-center">-</td>
          </tr>
        @endforelse
        <tr>
          <td class="bold">Hasil</td>
          <td>{{ $hasil }}</td>
          <td>{{ $targetHasil }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="box">
    <table class="header-rincian">
      <thead>
        <tr class="title-row">
          <th colspan="2">Rincian Anggaran Belanja Kegiatan <span class="sub">Satuan Perangkat Kerja Daerah</span></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="header">Sub Kegiatan</td>
          <td class="data">: {{ $subKegiatan->kode_sub_giat }} {{ $subKegiatan->nama_sub_giat }}</td>
        </tr>
        <tr>
          <td class="header">Sumber Dana</td>
          <td class="data">
            @forelse($sumberDana as $dana)
              <span class="line">: {{ $dana->namadana }}</span>
            @empty
              : -
            @endforelse
          </td>
        </tr>
        <tr>
          <td class="header">Lokasi</td>
          <td class="data">
            @if ($lokasi->isNotEmpty())
              <span class="line">: {{ $lokasi->first()->lokasi }}</span>
            @else
              : -
            @endif
          </td>
        </tr>
        <tr>
          <td class="header">Keluaran Sub Kegiatan</td>
          <td class="data">
            @forelse($indikator as $ind)
              <span class="line">: {{ $ind->outputteks }}</span>
            @empty
              : -
            @endforelse
          </td>
        </tr>
        <tr>
          <td class="header">Waktu Pelaksanaan</td>
          <td class="data">: {{ $waktuPelaksanaan }}</td>
        </tr>
        <tr>
          <td class="header">Keterangan</td>
          <td class="data">:</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="box rincian-section">
    <table class="grid rincian">
      <thead>
        <tr class="title-row">
          <th colspan="7">Rincian Anggaran Belanja Kegiatan <span class="sub">Satuan Kerja Perangkat Daerah</span></th>
        </tr>
        <tr>
          <th rowspan="2" style="width:9%;">Kode Rekening</th>
          <th rowspan="2" style="width:36%;">Uraian</th>
          <th colspan="4">Rinci Perhitungan</th>
          <th rowspan="2" style="width:16%;">Jumlah</th>
        </tr>
        <tr>
          <th style="width:8%;">Koefisien</th>
          <th style="width:8%;">Satuan</th>
          <th style="width:13%;">Harga</th>
          <th style="width:6%;">PPN</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($rincianRows as $row)
          @if ($row['type'] === 'header')
            <tr class="header-row bold">
              <td>{{ $row['kode'] }}</td>
              <td colspan="5">{!! $row['uraian'] !!}</td>
              <td class="num">Rp. {{ number_format($row['jumlah'], 2, ',', '.') }}</td>
            </tr>
          @elseif($row['type'] === 'paket')
            <tr class="group-row">
              <td></td>
              <td colspan="5">
                <span class="grp-title bold">[ # ] {{ $row['label'] }}</span><br>
                <span class="grp-sumber">Sumber Dana : {!! $row['sumberDana'] !!}</span>
              </td>
              <td class="num bold">Rp. {{ number_format($row['jumlah'], 2, ',', '.') }}</td>
            </tr>
          @elseif($row['type'] === 'mintag')
            <tr class="sub-row">
              <td></td>
              <td colspan="5">[ - ] {{ $row['label'] }}</td>
              <td class="num">Rp. {{ number_format($row['jumlah'], 2, ',', '.') }}</td>
            </tr>
          @else
            <tr class="detail-row">
              <td></td>
              <td>{{ $row['uraian'] }}<br><span class="spesifikasi">Spesifikasi : {{ $row['spesifikasi'] }}</span></td>
              <td class="center">{{ $row['koefisien'] }}</td>
              <td class="center">{{ $row['satuan'] }}</td>
              <td class="num">Rp. {{ number_format($row['harga'], 2, ',', '.') }}</td>
              <td class="center">{{ $row['ppn'] }} %</td>
              <td class="num">Rp. {{ number_format($row['jumlah'], 2, ',', '.') }}</td>
            </tr>
          @endif
        @endforeach
      </tbody>
      <tfoot>
        <tr class="total-row bold">
          <td colspan="6" class="text-right">Jumlah :</td>
          <td class="num">Rp. {{ number_format($grandTotal, 2, ',', '.') }}</td>
        </tr>
      </tfoot>
    </table>
  </div>

  <div class="box">
    <table class="ttd-table">
      <tbody>
        <tr>
          <td class="ttd-empty"></td>
          <td class="ttd-content">
            <div>{{ $ttd['tempat'] }}, {{ $ttd['tanggal'] }}</div>
            <div>{{ $ttd['jabatan'] }}</div>
            <div class="space"></div>
            <div class="nama">{!! $ttd['nama'] !!}</div>
            <div>NIP. {!! $ttd['nip'] !!}</div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="box">
    <table class="grid catatan">
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
  </div>

  <div class="box">
    <table class="grid tim">
      <thead>
        <tr class="title-row">
          <th colspan="5">Tim Anggaran Pemerintahan Daerah</th>
        </tr>
        <tr>
          <th style="width:6%;">No</th>
          <th style="width:26%;">Nama</th>
          <th style="width:20%;">NIP</th>
          <th style="width:24%;">Jabatan</th>
          <th style="width:24%;">Tanda Tangan</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="5" class="kosong">Data Kosong</td>
        </tr>
      </tbody>
    </table>
  </div>

</body>

</html>
