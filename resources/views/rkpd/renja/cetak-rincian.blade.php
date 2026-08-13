<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>RKA Belanja SKPD - {{ $subKegiatan->nama_sub_giat }}</title>
<style>
  :root{
    --border:#000; --label:#000; --value:#000;
    --grp-title:#000; --grp-sumber:#000; --sub-label:#000;
  }
  *{box-sizing:border-box;}
  body{
    font-family:Arial, Helvetica, sans-serif; color:#111;
    max-width:940px; margin:0 auto; padding:20px;
    font-size:12px; line-height:1.35; background:#fff;
  }
  .box{ border:1.5px solid var(--border); margin-bottom:16px; }
  .box-title{ text-align:center; font-weight:bold; padding:6px 8px; border-bottom:1.5px solid var(--border); font-size:13px; }
  .kop{ display:flex; }
  .kop .kop-left{ flex:1; text-align:center; padding:14px 10px; font-weight:bold; font-size:14px; line-height:1.5; border-right:1.5px solid var(--border); }
  .kop .kop-right{ width:170px; text-align:center; padding:10px; font-weight:bold; font-size:13px; line-height:1.5; }
  .kop-sub{ text-align:center; padding:6px 8px; border-top:1.5px solid var(--border); }
  table.fields{ width:100%; border-collapse:collapse; }
  table.fields td{ padding:5px 10px; vertical-align:top; border-top:1px solid #999; }
  table.fields tr:first-child td{ border-top:none; }
  table.fields td.label{ width:190px; color:var(--label); font-weight:bold; border-right:1.5px solid var(--border); white-space:nowrap; }
  table.fields td.value{ color:var(--value); }
  table.fields td.value .line{ display:block; }
  table.kinerja{ width:100%; border-collapse:collapse; }
  table.kinerja th, table.kinerja td{ border:1px solid var(--border); padding:6px 8px; font-size:12px; vertical-align:top; }
  table.kinerja th{ text-align:center; font-weight:bold; color:var(--value); background:#fafafa; }
  table.kinerja td:nth-child(1){ color:var(--label); font-weight:bold; }
  table.kinerja td:nth-child(2), table.kinerja td:nth-child(3){ color:var(--value); }
  table.rincian{ width:100%; border-collapse:collapse; }
  table.rincian th, table.rincian td{ border:1px solid var(--border); padding:5px 7px; font-size:11.5px; vertical-align:top; }
  table.rincian th{ text-align:center; font-weight:bold; background:#fafafa; color:var(--value); }
  table.rincian td.num{ text-align:right; }
  table.rincian td.center{ text-align:center; white-space:nowrap; }
  table.rincian tr.header-row td{ font-weight:bold; }
  table.rincian tr.header-row td.num{ font-weight:bold; }
  table.rincian tr.group-row .grp-title{ color:var(--grp-title); font-weight:bold; }
  table.rincian tr.group-row .grp-sumber{ color:var(--grp-sumber); }
  table.rincian tr.group-row td.num{ color:var(--grp-title); font-weight:bold; }
  table.rincian tr.detail-row td{ color:#111; }
  .spesifikasi{ font-style:italic; color:#444; }
  table.rincian tr.total-row td{ font-weight:bold; border-top:2px solid var(--border); }
  .box.rincian-box{ margin-bottom:0; }
  .box.ttd-box{ padding:0; border-top:none; }
  table.ttd-table{ width:100%; border-collapse:collapse; }
  table.ttd-table td{ vertical-align:top; padding:14px; height:190px; }
  table.ttd-table td.ttd-empty{ width:54%; border-right:1.5px solid var(--border); }
  table.ttd-table td.ttd-content{ text-align:center; font-size:12px; }
  .ttd-content .space{ height:95px; }
  .ttd-content .nama{ font-weight:bold; text-decoration:underline; }
  table.catatan{ width:100%; border-collapse:collapse; margin-bottom:16px; }
  table.catatan td{ border:1px solid var(--border); padding:5px 8px; font-size:12px; vertical-align:top; }
  table.catatan td.c-label{ width:120px; font-weight:bold; }
  table.catatan td.c-colon{ width:16px; }
  table.tim{ width:100%; border-collapse:collapse; }
  table.tim caption{ border:1px solid var(--border); border-bottom:none; padding:5px; font-weight:bold; color:var(--grp-sumber); caption-side:top; font-size:12.5px; }
  table.tim th, table.tim td{ border:1px solid var(--border); padding:5px 8px; font-size:12px; }
  table.tim th{ background:#fafafa; text-align:center; font-weight:bold; color:var(--value); }
  table.tim td.kosong{ text-align:center; font-style:italic; color:var(--value); }
  .print-btn{ position:fixed; top:16px; right:16px; padding:8px 14px; font-family:Arial, sans-serif; font-size:13px; background:#2563eb; color:#fff; border:none; border-radius:6px; cursor:pointer; }
  @media print{ .print-btn{ display:none; } body{ padding:0; } }
</style>
</head>
<body>

<button class="print-btn" onclick="window.print()">Cetak / Simpan PDF</button>

<div class="box">
  <div class="kop">
    <div class="kop-left">RENCANA KERJA DAN ANGGARAN<br>SATUAN KERJA PERANGKAT DAERAH</div>
    <div class="kop-right">Formulir<br>RKA-BELANJA<br>SKPD</div>
  </div>
  <div class="kop-sub">Pemerintahan Kab. {{ $kabupaten }} Tahun Anggaran {{ $tahunAnggaran }}</div>
</div>

<div class="box">
  <div class="box-title">Rincian Anggaran Belanja Menurut Program, Kegiatan dan Sub Kegiatan</div>
  <table class="fields">
    <tr><td class="label">Urusan Pemerintahan</td><td class="value">: {{ $subKegiatan->kode_urusan }} {{ $subKegiatan->nama_urusan }}</td></tr>
    <tr><td class="label">Bidang Urusan</td><td class="value">: {{ $subKegiatan->kode_bidang_urusan }} {{ $subKegiatan->nama_bidang_urusan }}</td></tr>
    <tr><td class="label">Unit Organisasi</td><td class="value">: {{ $subKegiatan->kode_skpd }} {{ $subKegiatan->nama_skpd }}</td></tr>
    <tr><td class="label">Program</td><td class="value">: {{ $subKegiatan->kode_program }} {{ $subKegiatan->nama_program }}</td></tr>
    <tr><td class="label">Kegiatan</td><td class="value">: {{ $subKegiatan->kode_giat }} {{ $subKegiatan->nama_giat }}</td></tr>
    <tr><td class="label">Sub Kegiatan</td><td class="value">: {{ $subKegiatan->kode_sub_giat }} {{ $subKegiatan->nama_sub_giat }}</td></tr>
    <tr><td class="label">SPM</td><td class="value">: {{ $spm }}</td></tr>
    <tr><td class="label">Jenis Layanan</td><td class="value">: {{ $jenisLayanan }}</td></tr>
    <tr>
      <td class="label">Sumber Pendanaan</td>
      <td class="value">
        @forelse($sumberDana as $dana)
          <span class="line">{{ $dana->namadana }}</span>
        @empty
          : -
        @endforelse
      </td>
    </tr>
    <tr><td class="label">Waktu Pelaksanaan</td><td class="value">: {{ $waktuPelaksanaan }}</td></tr>
  </table>
</div>

<div class="box">
  <div class="box-title">Indikator dan Tolak Ukur Kinerja Kegiatan</div>
  <table class="kinerja">
    <thead><tr><th style="width:14%;">Indikator</th><th style="width:56%;">Tolok Ukur Kinerja</th><th style="width:30%;">Target Kinerja</th></tr></thead>
    <tbody>
      @forelse($indikator as $ind)
        <tr>
          <td>{{ $ind->output_teks }}</td>
          <td>{{ $ind->output_teks }}</td>
          <td>{{ $ind->target_output }} {{ $ind->satuan_output }}</td>
        </tr>
      @empty
        <tr><td colspan="3" style="text-align:center;">-</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

<div class="box">
  <div class="box-title">Rincian Anggaran Belanja Kegiatan<br><span style="font-weight:normal;">Satuan Kerja Perangkat Daerah</span></div>
  <table class="fields">
    <tr><td class="label">Sub Kegiatan</td><td class="value">: {{ $subKegiatan->kode_sub_giat }} {{ $subKegiatan->nama_sub_giat }}</td></tr>
    <tr>
      <td class="label">Sumber Pendanaan</td>
      <td class="value">
        @foreach($sumberDana as $dana)
          <span class="line">{{ $dana->namadana }}</span>
        @endforeach
      </td>
    </tr>
    <tr><td class="label">Waktu Pelaksanaan</td><td class="value">: {{ $waktuPelaksanaan }}</td></tr>
  </table>
</div>

<div class="box rincian-box">
  <div class="box-title">Rincian Anggaran Belanja Kegiatan<br><span style="font-weight:normal;">Satuan Kerja Perangkat Daerah</span></div>
  <table class="rincian">
    <thead>
      <tr>
        <th rowspan="2" style="width:9%;">Kode Rekening</th>
        <th rowspan="2" style="width:37%;">Uraian</th>
        <th colspan="4">Rinci Perhitungan</th>
        <th rowspan="2" style="width:15%;">Jumlah</th>
      </tr>
      <tr>
        <th style="width:8%;">Koefisien</th>
        <th style="width:8%;">Satuan</th>
        <th style="width:13%;">Harga</th>
        <th style="width:6%;">PPN</th>
      </tr>
    </thead>
   <tbody>
  @foreach($rincianRows as $row)
    @if($row['type'] === 'header')
      <tr class="header-row">
        <td>{{ $row['kode'] }}</td>
        <td colspan="5">{!! $row['uraian'] !!}</td>
        <td class="num">Rp. {{ number_format($row['jumlah'], 2, ',', '.') }}</td>
      </tr>
    @elseif($row['type'] === 'paket')
      <tr class="group-row">
        <td></td>
        <td colspan="5">
          <span class="grp-title">[ # ] {{ $row['label'] }}</span><br>
          <span class="grp-sumber">Sumber Dana : {!! $row['sumberDana'] !!}</span>
        </td>
        <td class="num">Rp. {{ number_format($row['jumlah'], 2, ',', '.') }}</td>
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

  <tr class="total-row">
    <td colspan="6" style="text-align:right;">Jumlah :</td>
    <td class="num">Rp. {{ number_format($grandTotal, 2, ',', '.') }}</td>
  </tr>
</tbody>
  </table>
</div>

<div class="box ttd-box">
  <table class="ttd-table">
    <tr>
      <td class="ttd-empty"></td>
      <td class="ttd-content">
        <div>{{ $ttd['tempat'] }}, ..............................</div>
        <div>{{ $ttd['jabatan'] }}</div>
        <div class="space"></div>
        <div class="nama">{!! $ttd['nama'] !!}</div>
        <div>NIP. {!! $ttd['nip'] !!}</div>
      </td>
    </tr>
  </table>
</div>

<table class="catatan">
  <tr><td class="c-label">Pembahasan</td><td class="c-colon">:</td><td></td></tr>
  <tr><td class="c-label">Tanggal</td><td class="c-colon">:</td><td></td></tr>
  <tr><td class="c-label">Catatan</td><td class="c-colon">:</td><td></td></tr>
  <tr><td colspan="2">1.</td><td></td></tr>
  <tr><td colspan="2">2.</td><td></td></tr>
  <tr><td colspan="2">Dst</td><td></td></tr>
</table>

<table class="tim">
  <caption>Tim Anggaran Pemerintahan Daerah</caption>
  <thead>
    <tr><th style="width:6%;">No</th><th style="width:26%;">Nama</th><th style="width:20%;">NIP</th><th style="width:24%;">Jabatan</th><th style="width:24%;">Tanda Tangan</th></tr>
  </thead>
  <tbody>
    <tr><td colspan="5" class="kosong">Data Kosong</td></tr>
  </tbody>
</table>

</body>
</html>