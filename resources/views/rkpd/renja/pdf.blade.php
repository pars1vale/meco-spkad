<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>RENJA OPD - {{ $skpd->nama_skpd }} - {{ $tahunAnggaran }}</title>
  <style>
    @page {
      margin: 15px 12px;
    }

    body {
      font-family: 'Helvetica', sans-serif;
      font-size: 8px;
      color: #000;
    }

    h1 {
      font-size: 12px;
      text-align: center;
      margin: 0;
    }

    h2 {
      font-size: 11px;
      text-align: center;
      margin: 2px 0;
    }

    .header-info {
      text-align: center;
      margin-bottom: 8px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #333;
      padding: 2px 3px;
      vertical-align: top;
    }

    thead th {
      background-color: #e5e5e5;
      text-align: center;
      font-weight: bold;
      font-size: 7px;
      line-height: 1.3;
    }

    .col-no {
      width: 18px;
      text-align: center;
    }

    .col-kode {
      width: 55px;
    }

    .text-right {
      text-align: right;
    }

    .text-center {
      text-align: center;
    }

    .row-urusan td {
      background-color: #d0d0d0;
      font-weight: bold;
    }

    .row-bidang td {
      background-color: #e0e0e0;
      font-weight: bold;
    }

    .row-program td {
      background-color: #eeeeee;
      font-weight: bold;
    }

    .row-kegiatan td {
      background-color: #f5f5f5;
      font-style: italic;
    }

    .row-subkegiatan td {
      font-weight: normal;
    }

    .row-grandtotal td {
      background-color: #cccccc;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="header-info">
    <h1>RENCANA KERJA (RENJA) ORGANISASI PERANGKAT DAERAH</h1>
    <h2>{{ strtoupper($skpd->nama_skpd) }}</h2>
    <h2>TAHUN {{ $tahunAnggaran }}</h2>
  </div>

  <table>
    <thead>
      <tr>
        <th class="col-no" rowspan="3">NO</th>
        <th class="col-kode" rowspan="3">KODE</th>
        <th rowspan="3">URUSAN / BIDANG URUSAN / PROGRAM / OUTCOME / KEGIATAN / SUB KEGIATAN</th>
        <th rowspan="3">INDIKATOR PROGRAM / KEGIATAN / SUB KEGIATAN</th>
        <th rowspan="3">TARGET AKHIR PERIODE RENSTRA OPD</th>
        <th rowspan="3">REALISASI CAPAIAN RENJA OPD TAHUN {{ $tahunAnggaran - 2 }}</th>
        <th rowspan="3">PRAKIRAAN CAPAIAN TARGET RENJA OPD TAHUN {{ $tahunAnggaran - 1 }}</th>
        <th colspan="6">CAPAIAN KINERJA DAN KERANGKA PENDANAAN</th>
        <th rowspan="3">KELOMPOK SASARAN</th>
        <th colspan="2">PRAKIRAAN MAJU RENCANA TAHUN {{ $tahunAnggaran + 1 }}</th>
        <th rowspan="3">PERANGKAT DAERAH PENANGGUNG JAWAB</th>
      </tr>
      <tr>
        <th rowspan="2">TARGET {{ $tahunAnggaran }}</th>
        <th rowspan="2">PAGU INDIKATIF (Rp)</th>
        <th rowspan="2">LOKASI</th>
        <th rowspan="2">SUMBER DANA</th>
        <th colspan="2">PRIORITAS</th>
        <th rowspan="2">TARGET</th>
        <th rowspan="2">PAGU INDIKATIF (Rp)</th>
      </tr>
      <tr>
        <th>NASIONAL</th>
        <th>DAERAH</th>
      </tr>
      <tr>
        <th>1</th>
        <th>2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6</th>
        <th>7</th>
        <th>8</th>
        <th>9</th>
        <th>10</th>
        <th>11</th>
        <th>12</th>
        <th>13</th>
        <th>14</th>
        <th>15</th>
        <th>16</th>
        <th>17</th>
      </tr>
    </thead>
    <tbody>
      @php $noProgram = 1; @endphp
      @forelse ($grouped as $urusan)
        <tr class="row-urusan">
          <td class="col-no"></td>
          <td>{{ $urusan['kode'] }}</td>
          <td colspan="6">{{ $urusan['nama'] }}</td>
          <td class="text-right">{{ number_format($urusan['total_pagu'], 2, ',', '.') }}</td>
          <td colspan="8"></td>
        </tr>

        @foreach ($urusan['bidang'] as $bidang)
          <tr class="row-bidang">
            <td></td>
            <td>{{ $bidang['kode'] }}</td>
            <td colspan="6">{{ $bidang['nama'] }}</td>
            <td class="text-right">{{ number_format($bidang['total_pagu'], 2, ',', '.') }}</td>
            <td colspan="8"></td>
          </tr>

          @foreach ($bidang['program'] as $program)
            <tr class="row-program">
              <td class="col-no">
                @if (strlen($program['kode']) === 7)
                  {{ $noProgram++ }}
                @endif
              </td>
              <td>{{ $program['kode'] }}</td>
              <td colspan="6">{{ $program['nama'] }}</td>
              <td class="text-right">{{ number_format($program['total_pagu'], 2, ',', '.') }}</td>
              <td colspan="8"></td>
            </tr>

            @foreach ($program['kegiatan'] as $kegiatan)
              <tr class="row-kegiatan">
                <td></td>
                <td>{{ $kegiatan['kode'] }}</td>
                <td colspan="6">{{ $kegiatan['nama'] }}</td>
                <td class="text-right">{{ number_format($kegiatan['total_pagu'], 2, ',', '.') }}</td>
                <td colspan="8"></td>
              </tr>

              @foreach ($kegiatan['sub_kegiatan'] as $sk)
                <tr class="row-subkegiatan">
                  <td></td>
                  <td>{{ $sk['kode_sub_giat'] }}</td>
                  <td>{{ $sk['nama_sub_giat'] }}</td>
                  <td>{!! $sk['indikator'] !!}</td>
                  <td class="text-center">{!! $sk['target_akhir_renstra'] !!}</td>
                  <td class="text-center">{!! $sk['pagu_n_lalu'] !!}</td>
                  <td class="text-center">{!! $sk['prakiraan_capaian'] !!}</td>
                  <td class="text-center">-<!-- UNMAPPED: target_tahun_berjalan --></td>
                  <td class="text-right">{{ number_format($sk['pagu'], 2, ',', '.') }}</td>
                  <td>{!! $sk['lokasi'] !!}</td>
                  <td>{!! $sk['sumber_dana'] !!}</td>
                  <td class="text-center">{!! $sk['prioritas_nasional'] !!}</td>
                  <td class="text-center">{!! $sk['prioritas_daerah'] !!}</td>
                  <td>{!! $sk['kelompok_sasaran'] !!}</td>
                  <td class="text-center">{!! $sk['target_maju_2027'] !!}</td>
                  <td class="text-right">{!! $sk['pagu_n_depan'] !!}</td>
                  <td>{{ $sk['pd_penanggung_jawab'] }}</td>
                </tr>
              @endforeach
            @endforeach
          @endforeach
        @endforeach
      @empty
        <tr>
          <td colspan="17" class="text-center">Tidak ada data RENJA untuk SKPD dan tahun anggaran ini.</td>
        </tr>
      @endforelse

      @if (count($grouped) > 0)
        <tr class="row-grandtotal">
          <td colspan="8" class="text-right">JUMLAH</td>
          <td class="text-right">{{ number_format($grandTotalPagu, 2, ',', '.') }}</td>
          <td colspan="8"></td>
        </tr>
      @endif
    </tbody>
  </table>
</body>

</html>
