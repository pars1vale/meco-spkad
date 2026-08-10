<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Ringkasan Paket / Pengelompokan Belanja</title>
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: Arial, sans-serif;
    font-size: 11.5px;
    color: #000;
    background: #e0e0e0;
    padding: 30px 20px;
  }

  .page {
    max-width: 820px;
    margin: 0 auto;
    background: #fff;
    border: 1px solid #888;
    padding: 0 0 30px 0;
  }

  /* ══ HEADER ══ */
  .doc-header {
    border-bottom: 1px solid #888;
    padding: 12px 20px 10px;
    text-align: center;
  }
  .doc-header h1 {
    font-size: 13.5px;
    font-weight: bold;
    text-transform: uppercase;
    line-height: 1.6;
  }

  /* ══ SUBHEADER ══ */
  .doc-subheader {
    border-bottom: 1px solid #888;
    padding: 7px 20px;
    text-align: center;
    font-size: 11.5px;
  }

  /* ══ INFO FIELDS ══ */
  .info-section {
    padding: 10px 24px 6px;
  }
  table.info {
    width: 100%;
    border-collapse: collapse;
    font-size: 11.5px;
  }
  table.info td {
    padding: 2.5px 4px;
    vertical-align: top;
    line-height: 1.5;
  }
  table.info td.lbl { width: 148px; white-space: nowrap; }
  table.info td.col { width: 14px; text-align: center; }
  table.info td.val { }

  /* ══ SECTION WRAPPER ══ */
  .section-block {
    margin: 12px 20px 0;
    border: 1px solid #888;
  }

  .section-title {
    padding: 8px 14px;
    text-align: center;
    font-weight: bold;
    font-size: 12px;
    border-bottom: 1px solid #888;
    background: #fff;
  }

  /* ══ KINERJA TABLE ══ */
  table.kinerja {
    width: 100%;
    border-collapse: collapse;
    font-size: 11.5px;
  }
  table.kinerja th {
    border: 1px solid #888;
    padding: 5px 10px;
    font-weight: bold;
    text-align: center;
    background: #fff;
  }
  table.kinerja td {
    border: 1px solid #888;
    padding: 5px 10px;
    vertical-align: top;
    line-height: 1.5;
  }
  table.kinerja td:first-child { width: 14%; }
  table.kinerja td:last-child  { width: 18%; }

  /* ══ PAKET TABLE ══ */
  table.paket {
    width: 100%;
    border-collapse: collapse;
    font-size: 11.5px;
  }
  table.paket th {
    border: 1px solid #888;
    padding: 6px 10px;
    font-weight: bold;
    text-align: center;
    background: #fff;
  }
  table.paket th:last-child { width: 24%; }
  table.paket td {
    border: 1px solid #888;
    padding: 5px 10px;
    vertical-align: top;
    line-height: 1.5;
  }
  table.paket td.amt {
    text-align: right;
    white-space: nowrap;
  }

  tr.grp td { font-weight: bold; }
  tr.sub td.uraian {
    font-weight: normal;
    padding-left: 22px;
  }
  tr.sub td.amt { font-weight: normal; }
  tr.total td {
    font-weight: bold;
    text-align: right;
  }

  @media print {
    body { background: #fff; padding: 0; }
    .page { border: none; max-width: 100%; }
  }
</style>
</head>
<body>
<div class="page">

  {{-- ══ HEADER ══ --}}
  <div class="doc-header">
    <h1>RINGKASAN PAKET / PENGELOMPOKAN BELANJA<br>SATUAN KERJA PERANGKAT DAERAH</h1>
  </div>

  {{-- ══ SUBHEADER ══ --}}
  <div class="doc-subheader">
    Pemerintahan {{ $subKegiatan->nama_daerah ?? 'Kab. Yahukimo' }} Tahun Anggaran {{ $subKegiatan->tahun_anggaran ?? 2025 }}
  </div>

  {{-- ══ INFO FIELDS ══ --}}
  <div class="info-section">
    <table class="info">
      <tr>
        <td class="lbl">Urusan</td>
        <td class="col">:</td>
        <td class="val">{{ $subKegiatan->kode_urusan ?? '-' }} {{ $subKegiatan->nama_urusan ?? '' }}</td>
      </tr>
      <tr>
        <td class="lbl">Bidang Urusan</td>
        <td class="col">:</td>
        <td class="val">{{ $subKegiatan->kode_bidang ?? '-' }} {{ $subKegiatan->nama_bidang ?? '' }}</td>
      </tr>
      <tr>
        <td class="lbl">Unit Organisasi</td>
        <td class="col">:</td>
        <td class="val">{{ $subKegiatan->kode_skpd ?? '-' }} {{ $subKegiatan->nama_unit ?? $subKegiatan->nama_skpd ?? '' }}</td>
      </tr>
      <tr>
        <td class="lbl">Sub Unit Organisasi</td>
        <td class="col">:</td>
        <td class="val">{{ $subKegiatan->nama_sub_unit ?? '-' }}</td>
      </tr>
      <tr>
        <td class="lbl">Program</td>
        <td class="col">:</td>
        <td class="val">{{ $subKegiatan->kode_program ?? '-' }} {{ $subKegiatan->nama_program ?? '' }}</td>
      </tr>
      <tr>
        <td class="lbl">Kegiatan</td>
        <td class="col">:</td>
        <td class="val">{{ $subKegiatan->kode_giat ?? '-' }} {{ $subKegiatan->nama_giat ?? '' }}</td>
      </tr>
      <tr>
        <td class="lbl">Sub Kegiatan</td>
        <td class="col">:</td>
        <td class="val">{{ $subKegiatan->kode_sub_giat ?? $subKegiatan->kode_sbl ?? '-' }} {{ $subKegiatan->nama_sub_giat ?? '' }}</td>
      </tr>
      <tr>
        <td class="lbl">SPM</td>
        <td class="col">:</td>
        <td class="val">{{ $subKegiatan->spm ?? '-' }}</td>
      </tr>
      <tr>
        <td class="lbl">Jenis Layanan</td>
        <td class="col">:</td>
        <td class="val">{{ $subKegiatan->jenis_layanan ?? '-' }}</td>
      </tr>
      <tr>
        <td class="lbl">Sumber Pendanaan</td>
        <td class="col">:</td>
        <td class="val">
          @forelse($sumberDana as $dana)
            {{ $dana->namadana ?? $dana->nama_dana ?? '-' }}<br>
          @empty
            -
          @endforelse
        </td>
      </tr>
      <tr>
        <td class="lbl">Lokasi</td>
        <td class="col">:</td>
        <td class="val">{{ $subKegiatan->nama_lokasi ?? '-' }}</td>
      </tr>
      <tr>
        <td class="lbl">Waktu Pelaksanaan</td>
        <td class="col">:</td>
        <td class="val">
          @if($subKegiatan->waktu_awal && $subKegiatan->waktu_akhir)
            {{ $subKegiatan->waktu_awal }} s.d {{ $subKegiatan->waktu_akhir }}
          @else
            -
          @endif
        </td>
      </tr>
      <tr>
        <td class="lbl">Kelompok Sasaran</td>
        <td class="col">:</td>
        <td class="val">{{ $subKegiatan->kelompok_sasaran ?? '-' }}</td>
      </tr>
      <tr>
        <td class="lbl">Jumlah {{ ($subKegiatan->tahun_anggaran ?? 2025) - 1 }}</td>
        <td class="col">:</td>
        <td class="val">Rp. {{ number_format($subKegiatan->pagumurni ?? 0, 2, ',', '.') }}</td>
      </tr>
      <tr>
        <td class="lbl">Jumlah {{ $subKegiatan->tahun_anggaran ?? 2025 }}</td>
        <td class="col">:</td>
        <td class="val">Rp. {{ number_format($subKegiatan->pagu ?? 0, 2, ',', '.') }}</td>
      </tr>
      <tr>
        <td class="lbl">Jumlah {{ ($subKegiatan->tahun_anggaran ?? 2025) + 1 }}</td>
        <td class="col">:</td>
        <td class="val">Rp. {{ number_format($subKegiatan->pagu_n_depan ?? 0, 2, ',', '.') }}</td>
      </tr>
    </table>
  </div>

  {{-- ══ APBD SECTION ══ --}}
  <div class="section-block">
    <div class="section-title">APBD &amp; Pergeseran/Perubahan APBD</div>
    <table class="kinerja">
      <thead>
        <tr>
          <th>Indikator</th>
          <th>Tolok Ukur Kinerja</th>
          <th>Target Kinerja</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Capaian Program</td>
          <td>{{ $subKegiatan->tolok_ukur_capaian ?? $subKegiatan->nama_program ?? '-' }}</td>
          <td>{{ $subKegiatan->target_capaian ?? '100 %' }}</td>
        </tr>
        <tr>
          <td>Masukan</td>
          <td>Dana yang dibutuhkan</td>
          <td>Rp. {{ number_format($subKegiatan->pagu ?? 0, 2, ',', '.') }}</td>
        </tr>
        @forelse($indikator as $ind)
        <tr>
          <td>Keluaran</td>
          <td>{{ $ind->output_teks ?? $ind->tolok_ukur ?? '-' }}</td>
          <td>{{ $ind->target_output ?? '-' }} {{ $ind->satuan_output ?? '' }}</td>
        </tr>
        @empty
        <tr>
          <td>Keluaran</td>
          <td>-</td>
          <td>-</td>
        </tr>
        @endforelse
        <tr>
          <td>Hasil</td>
          <td>{{ $subKegiatan->tolok_ukur_hasil ?? '-' }}</td>
          <td>{{ $subKegiatan->target_hasil ?? '100 %' }}</td>
        </tr>
      </tbody>
    </table>
  </div>

  {{-- ══ RINGKASAN PAKET SECTION ══ --}}
  <div class="section-block">
    <div class="section-title">Ringkasan Paket / Pengelompokan Belanja</div>
    <table class="paket">
      <thead>
        <tr>
          <th>Uraian</th>
          <th>Jumlah</th>
        </tr>
      </thead>
      <tbody>

        @forelse($paketGroup as $paket)

          {{-- PAKET HEADER ROW --}}
          <tr class="grp">
            <td>
              [ # ] {{ $paket['title'] }}<br>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sumber Dana : {{ $paket['nama_dana'] }}
            </td>
            <td class="amt">Rp. {{ number_format($paket['total'], 2, ',', '.') }}</td>
          </tr>

          {{-- MINTAG ROWS --}}
          @foreach($paket['mintag'] as $mintag)
          <tr class="sub">
            <td class="uraian">{{ $mintag['title'] }}</td>
            <td class="amt">Rp. {{ number_format($mintag['total'], 2, ',', '.') }}</td>
          </tr>
          @endforeach

        @empty
          <tr>
            <td colspan="2" style="text-align:center; padding: 20px;">Tidak ada data paket belanja</td>
          </tr>
        @endforelse

        {{-- TOTAL ROW --}}
        <tr class="total">
          <td style="text-align:right; padding-right:12px;">Jumlah :</td>
          <td class="amt">Rp. {{ number_format($totalKeseluruhan, 2, ',', '.') }}</td>
        </tr>

        {{-- SIGNATURE ROW --}}
        <tr>
          <td style="height: 160px; vertical-align: bottom;"></td>
          <td style="vertical-align: top; text-align: center; padding: 10px 14px;">
            {{ $subKegiatan->nama_daerah ?? 'Kab. Yahukimo' }},..............................<br>
            Kepala {{ $subKegiatan->nama_unit ?? $subKegiatan->nama_skpd ?? 'SKPD' }}<br>
            <br><br><br><br><br>
            <strong>{{ $subKegiatan->nama_kepala ?? '.....................................' }}</strong><br>
            NIP. {{ $subKegiatan->nip_kepala ?? '.....................................' }}
          </td>
        </tr>

      </tbody>
    </table>
  </div>

</div>{{-- /.page --}}
</body>
</html>