@extends('layouts.master')

@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading text-dark fw-bold fs-3 m-0">Rincian Belanja Sub Kegiatan</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">Rkpd</li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('rkpd.renja.index') }}" class="text-muted text-hover-primary">Renja</a>
            </li>
            <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
            <li class="breadcrumb-item text-muted">Rincian Belanja</li>
          </ul>
        </div>
        <div class="d-flex align-items-center gap-2">
          <a href="{{ route('rkpd.renja.index') }}" class="btn btn-sm btn-light">
            <i class="ki-outline ki-arrow-left fs-3"></i>
            Kembali
          </a>
          <button type="button" class="btn btn-sm btn-primary" onclick="window.print()">
            <i class="ki-outline ki-printer fs-3"></i>
            Cetak
          </button>
        </div>
      </div>
    </div>
  </div>

  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
      <!-- Informasi Sub Kegiatan -->
      <div class="card mb-5">
        <div class="card-header">
          <h3 class="card-title">Informasi Sub Kegiatan</h3>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <table class="table table-borderless">
                <tr>
                  <td width="200" class="fw-bold text-gray-600">SKPD</td>
                  <td>:</td>
                  <td class="text-gray-800">{{ $subKegiatan->kode_skpd }} - {{ $subKegiatan->nama_skpd }}</td>
                </tr>
                <tr>
                  <td class="fw-bold text-gray-600">Urusan</td>
                  <td>:</td>
                  <td class="text-gray-800">{{ $subKegiatan->kode_urusan }} - {{ $subKegiatan->nama_urusan }}</td>
                </tr>
                <tr>
                  <td class="fw-bold text-gray-600">Bidang Urusan</td>
                  <td>:</td>
                  <td class="text-gray-800">{{ $subKegiatan->kode_bidang_urusan }} - {{ $subKegiatan->nama_bidang_urusan }}</td>
                </tr>
                <tr>
                  <td class="fw-bold text-gray-600">Program</td>
                  <td>:</td>
                  <td class="text-gray-800">{{ $subKegiatan->kode_program }} - {{ $subKegiatan->nama_program }}</td>
                </tr>
              </table>
            </div>
            <div class="col-md-6">
              <table class="table table-borderless">
                <tr>
                  <td width="200" class="fw-bold text-gray-600">Kegiatan</td>
                  <td>:</td>
                  <td class="text-gray-800">{{ $subKegiatan->kode_giat }} - {{ $subKegiatan->nama_giat }}</td>
                </tr>
                <tr>
                  <td class="fw-bold text-gray-600">Sub Kegiatan</td>
                  <td>:</td>
                  <td class="text-gray-800">{{ $subKegiatan->kode_sub_giat }} - {{ $subKegiatan->nama_sub_giat }}</td>
                </tr>
                <tr>
                  <td class="fw-bold text-gray-600">Kode SBL</td>
                  <td>:</td>
                  <td class="text-gray-800">{{ $subKegiatan->kode_sbl }}</td>
                </tr>
                <tr>
                  <td class="fw-bold text-gray-600">Tahun Anggaran</td>
                  <td>:</td>
                  <td class="text-gray-800">{{ $subKegiatan->tahun_anggaran }}</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
      <!-- Pagu dan Sumber Dana -->
      <div class="row g-5 mb-5">
        <div class="col-md-6">
          <div class="card card-flush h-100">
            <div class="card-header">
              <h3 class="card-title">Pagu Anggaran</h3>
            </div>
            <div class="card-body">
              <div class="d-flex flex-column gap-5">
                <div class="d-flex justify-content-between align-items-center p-4 bg-light-primary rounded">
                  <span class="fw-bold text-gray-800">Pagu Murni:</span>
                  <span class="fs-4 fw-bold text-primary">Rp {{ number_format($subKegiatan->pagumurni ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-4 bg-light-success rounded">
                  <span class="fw-bold text-gray-800">Pagu Setelah Perubahan:</span>
                  <span class="fs-4 fw-bold text-success">Rp {{ number_format($subKegiatan->pagu ?? 0, 0, ',', '.') }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card card-flush h-100">
            <div class="card-header">
              <h3 class="card-title">Sumber Dana</h3>
            </div>
            <div class="card-body">
              @if ($sumberDana->count() > 0)
                <div class="table-responsive">
                  <table class="table table-row-bordered align-middle gy-4">
                    <thead>
                      <tr class="fw-bold text-gray-700">
                        <th>Kode</th>
                        <th>Nama Sumber Dana</th>
                        <th class="text-end">Pagu</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($sumberDana as $dana)
                        <tr>
                          <td>{{ $dana->kodedana }}</td>
                          <td>{{ $dana->namadana }}</td>
                          <td class="text-end fw-bold">Rp {{ number_format($dana->pagudana ?? 0, 0, ',', '.') }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr class="fw-bold bg-light">
                        <td colspan="2" class="text-end">TOTAL:</td>
                        <td class="text-end text-primary">Rp {{ number_format($sumberDana->sum('pagudana'), 0, ',', '.') }}</td>
                      </tr>
                    </tfoot>
                  </table>
                </div>
              @else
                <div class="alert alert-warning">Tidak ada data sumber dana</div>
              @endif
            </div>
          </div>
        </div>
      </div>
      <!-- Indikator Kinerja -->
      @if ($indikator->count() > 0)
        <div class="card mb-5">
          <div class="card-header">
            <h3 class="card-title">Indikator Kinerja</h3>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-row-bordered align-middle gy-4">
                <thead>
                  <tr class="fw-bold text-gray-700">
                    <th width="50">No</th>
                    <th>Indikator</th>
                    <th width="150">Target</th>
                    <th width="100">Satuan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($indikator as $index => $ind)
                    <tr>
                      <td class="text-center">{{ $index + 1 }}</td>
                      <td>{{ $ind->outputteks }}</td>
                      <td class="text-center fw-bold">{{ number_format($ind->targetoutput ?? 0, 0, ',', '.') }}</td>
                      <td class="text-center">{{ $ind->satuanoutput }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      @endif
      <!-- Rincian Belanja -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Rincian Belanja</h3>
          <div class="card-toolbar">
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_rincian">
              <i class="ki-outline ki-plus fs-3"></i>
              Tambah Rincian
            </button>
          </div>
        </div>
        <div class="card-body">
          @if (count($dataTerkelompok) > 0)
            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle gy-4">
                <thead class="bg-light">
                  <tr class="fw-bold text-gray-700">
                    <th width="50">No</th>
                    <th width="150">Kode Rekening</th>
                    <th>Uraian</th>
                    <th width="100" class="text-center">Volume</th>
                    <th width="100" class="text-center">Satuan</th>
                    <th width="150" class="text-end">Harga Satuan</th>
                    <th width="150" class="text-end">Jumlah</th>
                    <th width="100" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @php $no = 1; @endphp

                  @foreach ($dataTerkelompok as $hashtag => $dataHashtag)
                    {{-- LEVEL 1: HASHTAG [#] - Paket/Kelompok --}}
                    <tr class="bg-light-info">
                      <td class="fw-bolder text-info">#</td>
                      <td colspan="5" class="fw-bolder text-info fs-6">
                        {{-- HAPUS [#] DARI TAMPILAN --}}
                        {{ preg_replace('/^\[\#\]\s*/', '', $dataHashtag['title']) }}
                      </td>
                      <td class="text-end fw-bolder text-info fs-6">Rp {{ number_format($dataHashtag['total'], 0, ',', '.') }}</td>
                      <td></td>
                    </tr>

                    @foreach ($dataHashtag['mintag'] as $mintag => $dataMintag)
                      {{-- LEVEL 2: MINTAG [-] - Kategori Belanja --}}
                      <tr class="bg-light-warning">
                        <td></td>
                        <td class="fw-bold text-warning">-</td>
                        <td colspan="4" class="fw-bold text-warning">{{ $dataMintag['title'] }}</td>
                        <td class="text-end fw-bold text-warning">Rp {{ number_format($dataMintag['total'], 0, ',', '.') }}</td>
                        <td></td>
                      </tr>

                      @foreach ($dataMintag['rekening'] as $kodeRekening => $dataRekening)
                        {{-- LEVEL 3: KODE REKENING --}}
                        <tr class="bg-light-primary">
                          <td class="fw-bold">{{ $no++ }}</td>
                          <td class="fw-bold">{{ $dataRekening['kode_akun'] }}</td>
                          <td class="fw-bold" colspan="4">{{ $dataRekening['nama_akun'] }}</td>
                          <td class="text-end fw-bold text-primary">Rp {{ number_format($dataRekening['total'], 0, ',', '.') }}</td>
                          <td></td>
                        </tr>

                        @foreach ($dataRekening['items'] as $item)
                          {{-- LEVEL 4: RINCIAN DETAIL --}}
                          <tr>
                            <td class="text-center text-muted">{{ $no++ }}</td>
                            <td class="ps-5 text-muted">-</td>
                            <td>{{ $item['nama_komponen'] }}</td>
                            <td class="text-center">{{ number_format($item['volume'] ?? 0, 2, ',', '.') }}</td>
                            <td class="text-center">{{ $item['satuan'] }}</td>
                            <td class="text-end">Rp {{ number_format($item['harga_satuan'] ?? 0, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($item['total_harga'], 0, ',', '.') }}</td>
                            <td class="text-center">
                              <button class="btn btn-icon btn-sm btn-light-primary btn-edit" data-id="{{ $item['id'] }}">
                                <i class="ki-outline ki-pencil fs-4"></i>
                              </button>
                              <button class="btn btn-icon btn-sm btn-light-danger btn-delete" data-id="{{ $item['id'] }}">
                                <i class="ki-outline ki-trash fs-4"></i>
                              </button>
                            </td>
                          </tr>
                        @endforeach
                      @endforeach
                    @endforeach
                  @endforeach

                </tbody>
                <tfoot>
                  <tr class="bg-light">
                    <td colspan="6" class="text-end fw-bold fs-5">TOTAL KESELURUHAN:</td>
                    <td class="text-end fw-bold fs-4 text-primary">
                      Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}
                    </td>
                    <td></td>
                  </tr>
                </tfoot>
              </table>
            </div>
          @else
            <div class="alert alert-warning d-flex align-items-center p-5">
              <i class="ki-outline ki-information-5 fs-2hx text-warning me-4"></i>
              <div class="d-flex flex-column">
                <h4 class="mb-1 text-warning">Belum ada rincian belanja</h4>
                <span>Klik tombol "Tambah Rincian" untuk menambahkan data rincian belanja</span>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Rincian - LENGKAP -->
  <div class="modal fade" id="modal_add_rincian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-1000px modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="fw-bold">Tambah Rincian Belanja</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>
        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
          <form id="form_add_rincian">
            <input type="hidden" name="id_rinci_sub_bl" value="{{ $subKegiatan->id }}">
            <input type="hidden" name="tahun_anggaran" value="{{ $subKegiatan->tahun_anggaran }}">
            <input type="hidden" name="kode_rekening" id="kode_rekening">
            <input type="hidden" name="nama_rekening" id="nama_rekening">
            <!-- SECTION 1: OBJEK & REKENING -->
            <div class="card mb-5 shadow-sm">
              <div class="card-header bg-light-primary">
                <h3 class="card-title text-primary">
                  <i class="ki-outline ki-category fs-3 me-2"></i>
                  Objek Belanja & Rekening
                </h3>
              </div>
              <div class="card-body">
                <!-- Objek Belanja -->
                <div class="mb-5">
                  <label class="required form-label fw-bold fs-6">Objek Belanja</label>
                  <select class="form-select form-select-solid" id="select_jenis_bl" name="jenis_bl" required>
                    <option value="">Pilih Objek Belanja</option>
                    <option value="BTL-GAJI">5.1.02 - Belanja Gaji dan Tunjangan ASN</option>
                    <option value="BARJAS-MODAL">5.1.03 - Belanja Barang Jasa dan Modal</option>
                    <option value="BUNGA">5.1.04 - Belanja Bunga</option>
                    <option value="SUBSIDI">5.1.05 - Belanja Subsidi</option>
                    <option value="HIBAH-BRG">5.1.06 - Belanja Hibah (Barang/Jasa)</option>
                    <option value="HIBAH">5.1.06 - Belanja Hibah (Uang)</option>
                    <option value="BANSOS-BRG">5.1.07 - Belanja Bantuan Sosial (Barang/Jasa)</option>
                    <option value="BANSOS">5.1.07 - Belanja Bantuan Sosial (Uang)</option>
                    <option value="BAGI-HASIL">5.1.08 - Belanja Bagi Hasil</option>
                    <option value="BANKEU">5.1.09 - Belanja Bantuan Keuangan Umum</option>
                    <option value="BANKEU-KHUSUS">5.1.10 - Belanja Bantuan Keuangan Khusus</option>
                    <option value="BTT">5.1.11 - Belanja Tidak Terduga (BTT)</option>
                    <option value="BOS">5.1.12 - Dana BOS (BOS Pusat)</option>
                    <option value="BLUD">5.1.13 - Belanja Operasional (BLUD)</option>
                    <option value="TANAH">5.1.14 - Pembebasan Tanah/Lahan</option>
                  </select>
                </div>

                <!-- Rekening -->
                <div id="wrapper_akun_rekening" style="display: none;">
                  <label class="required form-label fw-bold fs-6">Rekening Belanja</label>
                  <select class="form-select form-select-solid" id="select_akun_rekening" name="id_akun" required disabled>
                    <option value="">Pilih rekening belanja...</option>
                  </select>
                </div>
              </div>
            </div>
            <!-- SECTION 2: PAKET/KELOMPOK -->
            <div class="card mb-5 shadow-sm">
              <div class="card-header bg-light-warning">
                <h3 class="card-title text-warning">
                  <i class="ki-outline ki-package fs-3 me-2"></i>
                  Paket/Kelompok Belanja
                </h3>
              </div>
              <div class="card-body">
                <!-- Tipe Paket -->
                <div class="mb-5">
                  <label class="required form-label fw-bold fs-6">Pengelompokan Belanja / Paket Pekerjaan</label>
                  <select class="form-select form-select-solid" id="select_tipe_paket" name="tipe_paket" required>
                    <option value="">Pilih Paket/Kelompok...</option>
                    <option value="1">Pemaketan Kerja</option>
                    <option value="2">Pengelompokan Belanja</option>
                  </select>
                </div>

                <!-- Uraian Paket -->
                <div id="wrapper_uraian_paket" style="display: none;">
                  <label class="required form-label fw-bold fs-6">Uraian Pengelompokan Belanja / Paket Pekerjaan</label>
                  <div class="input-group">
                    <select class="form-select form-select-solid" id="select_uraian_paket" name="id_paket_belanja" required disabled>
                      <option value="">Pilih Paket Belanja...</option>
                    </select>
                    <button class="btn btn-primary" type="button" id="btn_open_modal_paket">
                      <i class="ki-outline ki-add-files fs-4"></i>
                      Tambah Baru
                    </button>
                  </div>
                </div>
              </div>
            </div>
            <!-- SECTION 3: DETAIL RINCIAN - UPDATED -->
            <div class="card mb-5 shadow-sm">
              <div class="card-header bg-light-info">
                <h3 class="card-title text-info">
                  <i class="ki-outline ki-document fs-3 me-2"></i>
                  Detail Rincian Belanja
                </h3>
              </div>
              <div class="card-body">

                <!-- Jenis Standar Harga -->
                <div class="mb-5">
                  <label class="form-label fw-bold">Jenis Standar Harga</label>
                  <select class="form-select form-select-solid" id="select_jenis_standar_harga" name="jenis_standar_harga">
                    <option value="">Pilih Standar Harga...</option>
                    <option value="1">SSH (Standar Satuan Harga)</option>
                    <option value="2">SBU (Standar Biaya Umum)</option>
                    <option value="3">HSPK (Harga Satuan Pokok Kegiatan)</option>
                    <option value="4">ASB (Analisa Standar Belanja)</option>
                  </select>
                </div>

                <!-- Komponen (Uraian) - DENGAN AUTOCOMPLETE -->
                <div class="mb-5">
                  <label class="required form-label fw-bold">Komponen</label>

                  <!-- Wrapper untuk toggle antara textarea manual dan select SSH -->
                  <div id="wrapper_komponen_manual">
                    <textarea class="form-control form-control-solid" id="textarea_uraian" name="uraian" rows="2"
                      placeholder="Masukkan uraian/nama komponen..." required></textarea>
                    <div class="form-text">
                      Atau <a href="#" id="btn_pilih_dari_ssh" class="text-primary">Pilih dari SSH Database</a>
                    </div>
                  </div>

                  <div id="wrapper_komponen_ssh" style="display: none;">
                    <select class="form-select form-select-solid" id="select_komponen_ssh" style="width: 100%;">
                      <option value="">Ketik untuk mencari komponen...</option>
                    </select>
                    <div class="form-text">
                      <a href="#" id="btn_input_manual" class="text-danger">Kembali ke input manual</a>
                    </div>
                  </div>

                  <!-- Hidden input untuk menyimpan ID SSH yang dipilih -->
                  <input type="hidden" id="id_standar_harga_selected" name="id_standar_harga">
                </div>

                <!-- TKDN (AUTO-FILLED) -->
                <div class="mb-5">
                  <label class="form-label fw-bold">TKDN</label>
                  <input type="text" class="form-control form-control-solid bg-light" id="input_tkdn" name="tkdn" placeholder="Contoh: 40%"
                    readonly>
                  <div class="form-text">Tingkat Komponen Dalam Negeri (otomatis dari SSH)</div>
                </div>

                <!-- Spesifikasi Komponen (AUTO-FILLED) -->
                <div class="mb-5">
                  <label class="form-label fw-bold">Spesifikasi Komponen</label>
                  <textarea class="form-control form-control-solid bg-light" id="textarea_spek" name="spesifikasi_komponen" rows="3"
                    placeholder="Masukkan spesifikasi detail komponen..." readonly></textarea>
                </div>

                <div class="separator separator-dashed my-7"></div>

                <!-- Koefisien (Perkalian) -->
                <div class="mb-5">
                  <label class="form-label fw-bold fs-5 text-primary">
                    <i class="ki-outline ki-calculator fs-3 me-2"></i>
                    Koefisien (Perkalian)
                  </label>
                  <div class="alert alert-light-primary d-flex align-items-center p-3 mb-4">
                    <i class="ki-outline ki-information-5 fs-2x text-primary me-3"></i>
                    <div class="text-gray-700">
                      <strong>Contoh:</strong> 12 Bulan × 20 Orang = 240 (Volume otomatis dihitung)
                    </div>
                  </div>

                  <div id="koefisien_container">
                    <!-- Koefisien 1 -->
                    <div class="row mb-3 koefisien-row">
                      <div class="col-md-1 d-flex align-items-center justify-content-center">
                        <span class="badge badge-light-primary fs-6">1</span>
                      </div>
                      <div class="col-md-5">
                        <input type="number" step="0.01" class="form-control form-control-solid koefisien-input" name="koefisien[]"
                          placeholder="Nilai koefisien 1">
                      </div>
                      <div class="col-md-5">
                        <input type="text" class="form-control form-control-solid" name="satuan_koefisien[]"
                          placeholder="Satuan (Bulan, Orang, Unit, dll)">
                      </div>
                      <div class="col-md-1 d-flex align-items-center">
                        <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-remove-koef" style="display: none;">
                          <i class="ki-outline ki-cross fs-3"></i>
                        </button>
                      </div>
                    </div>
                  </div>

                  <button type="button" class="btn btn-sm btn-light-primary" id="btn_add_koefisien">
                    <i class="ki-outline ki-plus fs-4"></i>
                    Tambah Koefisien
                  </button>
                </div>

                <div class="separator separator-dashed my-7"></div>

                <!-- Volume & Satuan (AUTO-FILLED dari SSH atau Calculate) -->
                <div class="row mb-5">
                  <div class="col-md-6">
                    <label class="required form-label fw-bold">Volume (Hasil Perkalian)</label>
                    <input type="text" class="form-control form-control-solid bg-light-success" name="volume" id="input_volume" placeholder="0"
                      readonly required>
                    <div class="form-text text-success fw-bold">Otomatis dihitung dari koefisien</div>
                  </div>
                  <div class="col-md-6">
                    <label class="required form-label fw-bold">Satuan</label>
                    <input type="text" class="form-control form-control-solid bg-light" id="input_satuan" name="satuan"
                      placeholder="Paket, Unit, Bulan, dll" readonly required>
                    <div class="form-text">Otomatis dari SSH (bisa diubah manual)</div>
                  </div>
                </div>

                <!-- Harga Satuan (AUTO-FILLED dari SSH) -->
                <div class="mb-5">
                  <label class="required form-label fw-bold">Harga Satuan</label>
                  <input type="text" class="form-control form-control-solid input-currency bg-light" id="input_harga_satuan" name="harga_satuan"
                    placeholder="0" readonly required>
                  <div class="form-text">Otomatis dari SSH (bisa diubah manual)</div>
                </div>

                <!-- KATEGORI BELANJA (MINTAG) - DINAMIS & WAJIB -->
                <div id="wrapper_kategori_belanja" style="display: none;">
                  <label class="required form-label fw-bold">Kategori Belanja</label>
                  <div class="input-group mb-3">
                    <select class="form-select form-select-solid" id="select_kategori_belanja" name="kategori_belanja" required disabled>
                      <option value="">Pilih Kategori Belanja...</option>
                    </select>
                    <button class="btn btn-primary" type="button" id="btn_open_modal_mintag">
                      <i class="ki-outline ki-add-files fs-4"></i>
                      Tambah Kategori
                    </button>
                  </div>
                  <div class="form-text">Kategori belanja untuk pengelompokan rincian (wajib diisi)</div>
                </div>
              </div>
            </div>
            <!-- TOTAL BELANJA -->
            <div class="card shadow-sm">
              <div class="card-body bg-light-success">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <span class="fw-bold fs-6 text-gray-800">Total Belanja:</span>
                    <div class="text-muted fs-7">Volume × Harga Satuan</div>
                  </div>
                  <span class="fw-bold fs-2 text-success" id="total_display">Rp 0</span>
                </div>
              </div>
            </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-3"></i>
            Batal
          </button>
          <button type="button" class="btn btn-success" id="btn_save_rincian">
            <span class="indicator-label">
              <i class="ki-outline ki-check fs-3"></i>
              Simpan Rincian
            </span>
            <span class="indicator-progress" style="display: none;">
              Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Paket Belanja (Nested Modal) -->
  <div class="modal fade" id="modal_add_paket" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="fw-bold">Tambah Paket Belanja</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>
        <div class="modal-body">
          <form id="form_add_paket">
            <input type="hidden" name="id_rinci_sub_bl" value="{{ $subKegiatan->id }}">
            <input type="hidden" name="tipe_paket_new" id="tipe_paket_new">
            <input type="hidden" name="jenis_bl_new" id="jenis_bl_new">
            <input type="hidden" name="id_akun_new" id="id_akun_new">

            <!-- Informasi Context (Read-only) -->
            <div class="alert alert-info d-flex align-items-center p-4 mb-7">
              <i class="ki-outline ki-information-5 fs-2x text-info me-4"></i>
              <div>
                <div class="text-info fw-bold">Paket/Kelompok akan dibuat untuk:</div>
                <div id="info_jenis_belanja" class="text-gray-800 mt-1"></div>
                <div id="info_rekening" class="text-gray-800"></div>
              </div>
            </div>

            <!-- Uraian Pemaketan Kerja/Pengelompokan Belanja -->
            <div class="mb-7">
              <label class="required form-label fw-bold fs-6">Uraian Pemaketan Kerja/Pengelompokan Belanja</label>
              <textarea class="form-control form-control-solid" name="uraian_paket" rows="4"
                placeholder="Masukkan uraian pemaketan kerja atau pengelompokan belanja..." required></textarea>
              <div class="form-text">
                Contoh: "Belanja Alat Tulis Kantor Semester I" atau "Pemaketan Pekerjaan Pembangunan Gedung Kantor"
              </div>
            </div>

            <div class="separator separator-dashed my-5"></div>

            <!-- Catatan -->
            <div class="notice d-flex bg-light-warning rounded border-warning border border-dashed p-4">
              <i class="ki-outline ki-information fs-2tx text-warning me-4"></i>
              <div class="d-flex flex-stack flex-grow-1">
                <div class="fw-semibold">
                  <h4 class="text-gray-900 fw-bold">Catatan Penting</h4>
                  <div class="fs-6 text-gray-700">
                    • Paket/kelompok ini akan tersimpan sebagai header grouping di RKA<br>
                    • Rincian belanja yang dibuat nanti akan terhubung ke paket ini<br>
                    • Gunakan nama yang jelas dan mudah diidentifikasi
                  </div>
                </div>
              </div>
            </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-success" id="btn_save_paket">
            <span class="indicator-label">
              <i class="ki-outline ki-check fs-3"></i>
              Simpan Paket
            </span>
            <span class="indicator-progress" style="display: none;">
              Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Mintag (Nested Modal) -->
  <div class="modal fade" id="modal_add_mintag" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered mw-650px">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="fw-bold">Tambah Kategori Belanja</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>
        <div class="modal-body">
          <form id="form_add_mintag">
            <input type="hidden" name="id_paket_belanja_mintag" id="id_paket_belanja_mintag">

            <!-- Informasi Context -->
            <div class="alert alert-info d-flex align-items-center p-4 mb-5">
              <i class="ki-outline ki-information-5 fs-2x text-info me-4"></i>
              <div>
                <div class="text-info fw-bold">Kategori untuk paket:</div>
                <div id="info_paket_mintag" class="text-gray-800 mt-1"></div>
              </div>
            </div>

            <!-- Nama Kategori -->
            <div class="mb-5">
              <label class="required form-label fw-bold fs-6">Nama Kategori Belanja</label>
              <textarea class="form-control form-control-solid" name="nama_mintag" rows="3"
                placeholder="Contoh: Belanja Meubeler, Jasa Perencanaan Kegiatan Kontraktual, dll" required></textarea>
              <div class="form-text">
                Kategori ini akan digunakan untuk mengelompokkan rincian belanja
              </div>
            </div>

            <!-- Contoh Kategori Umum -->
            <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-4">
              <i class="ki-outline ki-information fs-2tx text-primary me-4"></i>
              <div class="d-flex flex-stack flex-grow-1">
                <div class="fw-semibold">
                  <h4 class="text-gray-900 fw-bold">Contoh Kategori Umum</h4>
                  <div class="fs-6 text-gray-700">
                    • Belanja Meubeler<br>
                    • Pembangunan Ruang Guru<br>
                    • Jasa Perencanaan Kegiatan Kontraktual<br>
                    • Jasa Konsultan Pengawas Kegiatan Kontraktual<br>
                    • Belanja Modal Peralatan<br>
                    • Belanja Perjalanan Dinas<br>
                    • Belanja Pemeliharaan Gedung<br>
                    • Belanja Pengadaan Alat Tulis Kantor
                  </div>
                </div>
              </div>
            </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-success" id="btn_save_mintag">
            <span class="indicator-label">
              <i class="ki-outline ki-check fs-3"></i>
              Simpan Kategori
            </span>
            <span class="indicator-progress" style="display: none;">
              Menyimpan... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
        </div>
      </div>
    </div>
  </div>

  <style>
    @media print {

      .app-toolbar,
      .card-toolbar,
      .btn-edit,
      .btn-delete,
      .modal {
        display: none !important;
      }

      .card {
        border: none !important;
        box-shadow: none !important;
      }
    }

    .select2-container {
      width: 100% !important;
    }

    .select2-container--default .select2-selection--single {
      height: calc(1.5em + 1.3rem + 2px);
      padding: 0.65rem 1rem;
      border: 1px solid #e4e6ef;
      border-radius: 0.475rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: calc(1.5em + 1.3rem);
      padding-left: 0;
      color: #5e6278;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: calc(1.5em + 1.3rem);
      right: 10px;
    }

    .select2-dropdown {
      border: 1px solid #e4e6ef;
      border-radius: 0.475rem;
      z-index: 10000 !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: #009ef7;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
      border: 1px solid #e4e6ef;
      border-radius: 0.475rem;
      padding: 0.65rem 1rem;
    }

    #modal_add_paket {
      z-index: 1060 !important;
    }

    #modal_add_paket .modal-backdrop {
      z-index: 1055 !important;
    }

    .modal-dialog-scrollable .modal-body {
      overflow-y: auto;
    }

    #modal_add_mintag {
      z-index: 1070 !important;
    }

    #modal_add_mintag .modal-backdrop {
      z-index: 1065 !important;
    }
  </style>
@endsection

@section('scripts')
  <script>
    $(document).ready(function() {

      $('#btn_pilih_dari_ssh').on('click', function(e) {
        e.preventDefault();
        $('#wrapper_komponen_manual').hide();
        $('#wrapper_komponen_ssh').show();

        // Clear textarea manual
        $('#textarea_uraian').val('').prop('required', false);

        // Init Select2 untuk SSH
        initSelect2SSH();
      });

      $('#btn_input_manual').on('click', function(e) {
        e.preventDefault();
        $('#wrapper_komponen_ssh').hide();
        $('#wrapper_komponen_manual').show();

        // Clear select SSH
        $('#select_komponen_ssh').val(null).trigger('change');
        $('#id_standar_harga_selected').val('');

        // Reset textarea
        $('#textarea_uraian').prop('required', true);

        // Clear auto-filled fields
        clearAutoFilledFields();
      });

      // ============================================================
      // INIT SELECT2 UNTUK SSH AUTOCOMPLETE
      // ============================================================
      function initSelect2SSH() {
        if ($('#select_komponen_ssh').hasClass('select2-hidden-accessible')) {
          $('#select_komponen_ssh').select2('destroy');
        }

        $('#select_komponen_ssh').select2({
          dropdownParent: $('#modal_add_rincian'),
          placeholder: 'Ketik untuk mencari komponen...',
          allowClear: true,
          width: '100%',
          minimumInputLength: 2,
          ajax: {
            url: '{{ route('rincian.search-komponen') }}',
            dataType: 'json',
            delay: 300,
            data: function(params) {
              return {
                q: params.term,
                jenis_standar_harga: $('#select_jenis_standar_harga').val()
              };
            },
            processResults: function(response) {
              if (response.success) {
                return {
                  results: response.results.map(function(item) {
                    return {
                      id: item.id,
                      text: item.text,
                      kode: item.kode_standar_harga,
                      nama: item.nama_standar_harga,
                      satuan: item.satuan,
                      spek: item.spek,
                      harga: item.harga,
                      tkdn: item.nilai_tkdn,
                      tipe: item.tipe_standar_harga
                    };
                  })
                };
              }
              return {
                results: []
              };
            },
            cache: true
          },
          templateResult: formatSSHResult,
          templateSelection: formatSSHSelection
        });
      }

      // ============================================================
      // FORMAT TAMPILAN HASIL PENCARIAN SSH
      // ============================================================
      function formatSSHResult(item) {
        if (item.loading) {
          return item.text;
        }

        const hargaFormat = new Intl.NumberFormat('id-ID').format(item.harga || 0);

        return $(`
    <div class="d-flex flex-column py-2">
      <div class="fw-bold text-gray-800">${item.nama || item.text}</div>
      <div class="text-muted fs-7">
        <span class="badge badge-light me-2">${item.kode}</span>
        <span class="badge badge-light-success">${item.satuan}</span>
        <span class="text-primary ms-3">Rp ${hargaFormat}</span>
      </div>
      ${item.spek ? `<div class="text-muted fs-8 mt-1">${item.spek}</div>` : ''}
    </div>
  `);
      }

      function formatSSHSelection(item) {
        return item.nama || item.text;
      }

      // ============================================================
      // EVENT: KETIKA KOMPONEN SSH DIPILIH
      // ============================================================
      $('#select_komponen_ssh').on('select2:select', function(e) {
        const data = e.params.data;

        console.log('SSH Selected:', data);

        // Simpan ID SSH
        $('#id_standar_harga_selected').val(data.id);

        // Auto-fill fields
        fillFieldsFromSSH({
          nama: data.nama,
          satuan: data.satuan,
          spek: data.spek || '',
          harga: data.harga || 0,
          tkdn: data.tkdn || 0
        });

        toastr.success('Data komponen berhasil dimuat dari SSH', 'Berhasil');
      });

      // ============================================================
      // FUNCTION: FILL FIELDS FROM SSH DATA
      // ============================================================
      function fillFieldsFromSSH(data) {
        // Uraian (gunakan nama SSH)
        $('#textarea_uraian').val(data.nama);

        // TKDN
        const tkdnValue = data.tkdn ? `${data.tkdn}%` : '';
        $('#input_tkdn').val(tkdnValue);

        // Spesifikasi
        $('#textarea_spek').val(data.spek);

        // Satuan
        $('#input_satuan').val(data.satuan).prop('readonly', false);

        // Harga Satuan (format currency)
        const hargaFormatted = new Intl.NumberFormat('id-ID').format(data.harga);
        $('#input_harga_satuan').val(hargaFormatted).prop('readonly', false);

        // Hitung total jika volume sudah ada
        calculateTotal();
      }

      // ============================================================
      // FUNCTION: CLEAR AUTO-FILLED FIELDS
      // ============================================================
      function clearAutoFilledFields() {
        $('#input_tkdn').val('');
        $('#textarea_spek').val('');
        $('#input_satuan').val('').prop('readonly', false);
        $('#input_harga_satuan').val('').prop('readonly', false);
        $('#total_display').text('Rp 0');
      }

      // ============================================================
      // EVENT: KETIKA JENIS STANDAR HARGA BERUBAH
      // ============================================================
      $('#select_jenis_standar_harga').on('change', function() {
        const jenis = $(this).val();

        // Reset pilihan komponen SSH jika sudah dipilih
        if ($('#select_komponen_ssh').val()) {
          $('#select_komponen_ssh').val(null).trigger('change');
          $('#id_standar_harga_selected').val('');
          clearAutoFilledFields();

          toastr.info('Pilih ulang komponen sesuai jenis standar harga', 'Info');
        }

        // Re-init select2 dengan filter baru
        if ($('#wrapper_komponen_ssh').is(':visible')) {
          initSelect2SSH();
        }
      });

      // ============================================================
      // UPDATE CALCULATE TOTAL (sudah ada, pastikan dipanggil)
      // ============================================================
      function calculateTotal() {
        const volume = parseFloat($('#input_volume').val()) || 0;
        const harga = parseFloat($('#input_harga_satuan').val().replace(/[^\d]/g, '')) || 0;
        const total = volume * harga;
        $('#total_display').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
      }

      // Trigger calculate saat harga satuan diubah manual
      $(document).on('input', '#input_harga_satuan', function() {
        // Remove readonly jika user mulai mengetik
        $(this).prop('readonly', false);
        calculateTotal();
      });

      let koefisienCount = 1;
      const maxKoefisien = 4;

      // ============================================================
      // TAMBAH KOEFISIEN
      // ============================================================
      $('#btn_add_koefisien').on('click', function() {
        if (koefisienCount >= maxKoefisien) {
          toastr.warning('Maksimal 4 koefisien', 'Perhatian');
          return;
        }

        koefisienCount++;

        const newRow = `
      <div class="row mb-3 koefisien-row">
        <div class="col-md-1 d-flex align-items-center justify-content-center">
          <span class="badge badge-light-primary fs-6">${koefisienCount}</span>
        </div>
        <div class="col-md-5">
          <input type="number" step="0.01" class="form-control form-control-solid koefisien-input" name="koefisien[]" placeholder="Nilai koefisien ${koefisienCount}">
        </div>
        <div class="col-md-5">
          <input type="text" class="form-control form-control-solid" name="satuan_koefisien[]" placeholder="Satuan">
        </div>
        <div class="col-md-1 d-flex align-items-center">
          <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-remove-koef">
            <i class="ki-outline ki-cross fs-3"></i>
          </button>
        </div>
      </div>
    `;

        $('#koefisien_container').append(newRow);

        // Update button visibility
        if (koefisienCount >= maxKoefisien) {
          $('#btn_add_koefisien').prop('disabled', true).addClass('disabled');
        }

        // Show remove button on all rows if more than 1
        if (koefisienCount > 1) {
          $('.btn-remove-koef').show();
        }
      });

      // ============================================================
      // HAPUS KOEFISIEN
      // ============================================================
      $(document).on('click', '.btn-remove-koef', function() {
        $(this).closest('.koefisien-row').remove();
        koefisienCount--;

        // Re-number badges
        $('#koefisien_container .koefisien-row').each(function(index) {
          $(this).find('.badge').text(index + 1);
          $(this).find('input[name="koefisien[]"]').attr('placeholder', 'Nilai koefisien ' + (index + 1));
        });

        // Hide remove button if only 1 row left
        if (koefisienCount <= 1) {
          $('.btn-remove-koef').hide();
        }

        // Enable add button
        $('#btn_add_koefisien').prop('disabled', false).removeClass('disabled');

        // Recalculate volume
        calculateVolumeFromKoefisien();
      });

      // ============================================================
      // HITUNG VOLUME OTOMATIS DARI KOEFISIEN
      // ============================================================
      function calculateVolumeFromKoefisien() {
        let volume = 1;
        let hasValue = false;

        $('.koefisien-input').each(function() {
          const val = parseFloat($(this).val());
          if (val && !isNaN(val)) {
            volume *= val;
            hasValue = true;
          }
        });

        // Set volume (jika ada koefisien diisi)
        if (hasValue) {
          $('#input_volume').val(volume.toFixed(2));
        } else {
          $('#input_volume').val('');
        }

        // Calculate total
        calculateTotal();
      }

      // Listen to koefisien input changes
      $(document).on('input', '.koefisien-input', function() {
        calculateVolumeFromKoefisien();
      });

      // ============================================================
      // CURRENCY FORMATTING
      // ============================================================
      $(document).on('input', '.input-currency', function(e) {
        let value = e.target.value.replace(/[^\d]/g, '');
        if (value) {
          e.target.value = new Intl.NumberFormat('id-ID').format(value);
        }
      });

      // ============================================================
      // CALCULATE TOTAL
      // ============================================================
      function calculateTotal() {
        const volume = parseFloat($('#input_volume').val()) || 0;
        const harga = parseFloat($('input[name="harga_satuan"]').val().replace(/[^\d]/g, '')) || 0;
        const total = volume * harga;
        $('#total_display').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
      }

      $(document).on('input', 'input[name="harga_satuan"]', calculateTotal);

      // ============================================================
      // INITIALIZE SELECT2
      // ============================================================
      $('#modal_add_rincian').on('shown.bs.modal', function() {
        ['#select_jenis_bl', '#select_akun_rekening', '#select_tipe_paket', '#select_uraian_paket'].forEach(function(selector) {
          if ($(selector).hasClass('select2-hidden-accessible')) {
            $(selector).select2('destroy');
          }
        });

        $('#select_jenis_bl').select2({
          dropdownParent: $('#modal_add_rincian'),
          placeholder: 'Pilih Objek Belanja',
          allowClear: true,
          width: '100%'
        });

        $('#select_akun_rekening').select2({
          dropdownParent: $('#modal_add_rincian'),
          placeholder: 'Pilih rekening belanja...',
          allowClear: true,
          width: '100%'
        });

        $('#select_tipe_paket').select2({
          dropdownParent: $('#modal_add_rincian'),
          placeholder: 'Pilih Paket/Kelompok...',
          allowClear: true,
          width: '100%'
        });

        $('#select_uraian_paket').select2({
          dropdownParent: $('#modal_add_rincian'),
          placeholder: 'Pilih Paket Belanja...',
          allowClear: true,
          width: '100%'
        });
      });

      // ============================================================
      // EVENT: KETIKA OBJEK BELANJA DIPILIH
      // ============================================================
      $('#select_jenis_bl').on('change', function() {
        const jenisBelanja = $(this).val();
        const tahunAnggaran = $('input[name="tahun_anggaran"]').val();

        if (!jenisBelanja) {
          $('#wrapper_akun_rekening').hide();
          $('#select_akun_rekening').prop('disabled', true).html('<option value="">Pilih rekening belanja...</option>');
          return;
        }

        $('#select_akun_rekening').prop('disabled', true).html('<option value="">⏳ Memuat data rekening...</option>');
        $('#wrapper_akun_rekening').show();

        $.ajax({
          url: '{{ route('rincian.get-akun') }}',
          type: 'GET',
          data: {
            jenis_bl: jenisBelanja,
            tahun_anggaran: tahunAnggaran
          },
          success: function(response) {
            if (response.success) {
              $('#select_akun_rekening').empty().append('<option value="">Pilih rekening belanja...</option>');

              if (response.data && response.data.length > 0) {
                $.each(response.data, function(index, akun) {
                  $('#select_akun_rekening').append(
                    $('<option></option>')
                    .val(akun.id)
                    .text(akun.text)
                    .attr('data-kode', akun.kode_akun)
                    .attr('data-nama', akun.nama_akun)
                  );
                });

                $('#select_akun_rekening').prop('disabled', false);

                if ($('#select_akun_rekening').hasClass('select2-hidden-accessible')) {
                  $('#select_akun_rekening').select2('destroy').select2({
                    dropdownParent: $('#modal_add_rincian'),
                    placeholder: 'Pilih rekening belanja...',
                    allowClear: true,
                    width: '100%'
                  });
                }

                toastr.success(`✓ ${response.data.length} rekening tersedia`, 'Berhasil');
              }
            }
          },
          error: function() {
            $('#select_akun_rekening').html('<option value="">❌ Error memuat data</option>');
            toastr.error('Gagal memuat data rekening', 'Error');
          }
        });
      });

      // ============================================================
      // EVENT: KETIKA REKENING DIPILIH
      // ============================================================
      $('#select_akun_rekening').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        $('#kode_rekening').val(selectedOption.attr('data-kode') || '');
        $('#nama_rekening').val(selectedOption.attr('data-nama') || '');

        const tipePaket = $('#select_tipe_paket').val();
        if (tipePaket) {
          loadPaketList();
        }
      });

      // ============================================================
      // EVENT: KETIKA TIPE PAKET DIPILIH
      // ============================================================
      $('#select_tipe_paket').on('change', function() {
        if (!$(this).val()) {
          $('#wrapper_uraian_paket').hide();
          $('#select_uraian_paket').prop('disabled', true).html('<option value="">Pilih Paket Belanja...</option>');
          return;
        }
        loadPaketList();
      });

      // ============================================================
      // FUNCTION: LOAD PAKET LIST
      // ============================================================
      function loadPaketList() {
        const tipePaket = $('#select_tipe_paket').val();
        const idRinciSubBl = $('input[name="id_rinci_sub_bl"]').val();
        const jenisBelanja = $('#select_jenis_bl').val();

        if (!tipePaket || !idRinciSubBl) return;

        $('#select_uraian_paket').prop('disabled', true).html('<option value="">⏳ Memuat data paket...</option>');
        $('#wrapper_uraian_paket').show();

        $.ajax({
          url: '{{ route('paket.list') }}',
          type: 'GET',
          data: {
            id_rinci_sub_bl: idRinciSubBl,
            tipe_paket: tipePaket,
            jenis_bl: jenisBelanja
          },
          success: function(response) {
            $('#select_uraian_paket').empty().append('<option value="">Pilih Paket Belanja...</option>');

            if (response.success && response.data && response.data.length > 0) {
              $.each(response.data, function(index, paket) {
                $('#select_uraian_paket').append($('<option></option>').val(paket.id).text(paket.uraian_paket));
              });
              toastr.success(`✓ ${response.data.length} paket tersedia`, 'Berhasil');
            } else {
              toastr.info('Belum ada paket, silakan tambahkan', 'Info');
            }

            $('#select_uraian_paket').prop('disabled', false);

            if ($('#select_uraian_paket').hasClass('select2-hidden-accessible')) {
              $('#select_uraian_paket').select2('destroy').select2({
                dropdownParent: $('#modal_add_rincian'),
                placeholder: 'Pilih Paket Belanja...',
                allowClear: true,
                width: '100%'
              });
            }
          },
          error: function() {
            $('#select_uraian_paket').html('<option value="">❌ Error memuat data</option>').prop('disabled', false);
            toastr.error('Gagal memuat data paket', 'Error');
          }
        });
      }

      // ============================================================
      // SAVE RINCIAN BELANJA
      // ============================================================
      // ============================================================
      // SAVE RINCIAN BELANJA (UPDATE - DENGAN VALIDASI KATEGORI)
      // ============================================================
      $('#btn_save_rincian').on('click', function() {
        const form = $('#form_add_rincian');
        const btn = $(this);

        // Validasi form
        if (!form[0].checkValidity()) {
          form[0].reportValidity();
          return;
        }

        // Validasi dropdown
        if (!$('#select_jenis_bl').val()) {
          toastr.error('Pilih objek belanja terlebih dahulu', 'Validasi');
          return;
        }
        if (!$('#select_akun_rekening').val()) {
          toastr.error('Pilih rekening belanja terlebih dahulu', 'Validasi');
          return;
        }
        if (!$('#select_tipe_paket').val()) {
          toastr.error('Pilih tipe paket terlebih dahulu', 'Validasi');
          return;
        }
        if (!$('#select_uraian_paket').val()) {
          toastr.error('Pilih uraian paket terlebih dahulu', 'Validasi');
          return;
        }

        // VALIDASI KATEGORI BELANJA (WAJIB)
        if (!$('#select_kategori_belanja').val()) {
          toastr.error('Pilih kategori belanja terlebih dahulu', 'Validasi');
          return;
        }

        btn.find('.indicator-label').hide();
        btn.find('.indicator-progress').show();
        btn.prop('disabled', true);

        // Serialize form data
        const formData = form.serializeArray();
        const dataObj = {};

        // Convert to object, handle arrays properly
        formData.forEach(function(item) {
          if (item.name.includes('[]')) {
            const key = item.name.replace('[]', '');
            if (!dataObj[key]) dataObj[key] = [];
            dataObj[key].push(item.value);
          } else {
            dataObj[item.name] = item.value;
          }
        });

        // Clean harga_satuan (remove formatting)
        dataObj.harga_satuan = parseFloat(dataObj.harga_satuan.replace(/[^\d]/g, ''));

        console.log('Saving rincian:', dataObj);

        $.ajax({
          url: '{{ route('rincian.store') }}',
          type: 'POST',
          data: dataObj,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              toastr.success(response.message, 'Berhasil');
              $('#modal_add_rincian').modal('hide');
              setTimeout(() => location.reload(), 1000);
            } else {
              toastr.error(response.message, 'Error');
            }
          },
          error: function(xhr) {
            const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan';
            toastr.error(message, 'Error');
          },
          complete: function() {
            btn.find('.indicator-label').show();
            btn.find('.indicator-progress').hide();
            btn.prop('disabled', false);
          }
        });
      });

      // ============================================================
      // RESET MODAL (UPDATE - DENGAN RESET KATEGORI)
      // ============================================================
      $('#modal_add_rincian').on('hidden.bs.modal', function() {
        $('#form_add_rincian')[0].reset();

        // Reset kategori belanja
        $('#wrapper_kategori_belanja').hide();
        $('#select_kategori_belanja').html('<option value="">Pilih Kategori Belanja...</option>').prop('disabled', true);

        // Reset koefisien to 1 row only
        $('#koefisien_container').html(`
    <div class="row mb-3 koefisien-row">
      <div class="col-md-1 d-flex align-items-center justify-content-center">
        <span class="badge badge-light-primary fs-6">1</span>
      </div>
      <div class="col-md-5">
        <input type="number" step="0.01" class="form-control form-control-solid koefisien-input" name="koefisien[]" placeholder="Nilai koefisien 1">
      </div>
      <div class="col-md-5">
        <input type="text" class="form-control form-control-solid" name="satuan_koefisien[]" placeholder="Satuan (Bulan, Orang, Unit, dll)">
      </div>
      <div class="col-md-1 d-flex align-items-center">
        <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-remove-koef" style="display: none;">
          <i class="ki-outline ki-cross fs-3"></i>
        </button>
      </div>
    </div>
  `);

        koefisienCount = 1;
        $('#btn_add_koefisien').prop('disabled', false).removeClass('disabled');

        // Reset select2 (termasuk kategori belanja)
        ['#select_jenis_bl', '#select_akun_rekening', '#select_tipe_paket', '#select_uraian_paket', '#select_kategori_belanja'].forEach(
          function(selector) {
            if ($(selector).hasClass('select2-hidden-accessible')) {
              $(selector).val(null).trigger('change');
            }
          });

        $('#wrapper_akun_rekening').hide();
        $('#wrapper_uraian_paket').hide();
        $('#total_display').text('Rp 0');
        $('#input_volume').val('');
        $('#kode_rekening, #nama_rekening').val('');
      });

      // ============================================================
      // BUTTON: OPEN MODAL PAKET
      // ============================================================
      $('#btn_open_modal_paket').on('click', function() {
        const tipePaket = $('#select_tipe_paket').val();
        const jenisBelanja = $('#select_jenis_bl').val();
        const idAkun = $('#select_akun_rekening').val();

        if (!jenisBelanja) {
          toastr.error('Pilih objek belanja terlebih dahulu', 'Validasi');
          return;
        }

        if (!idAkun) {
          toastr.error('Pilih rekening belanja terlebih dahulu', 'Validasi');
          return;
        }

        if (!tipePaket) {
          toastr.error('Pilih tipe paket terlebih dahulu', 'Validasi');
          return;
        }

        $('#tipe_paket_new').val(tipePaket);
        $('#jenis_bl_new').val(jenisBelanja);
        $('#id_akun_new').val(idAkun);

        $('#modal_add_rincian').css('z-index', 1050);
        $('#modal_add_paket').modal('show');
      });

      // ============================================================
      // MODAL PAKET SHOWN
      // ============================================================
      $('#modal_add_paket').on('shown.bs.modal', function() {
        $('#modal_add_paket').css('z-index', 1060);
        $('.modal-backdrop').not(':first').css('z-index', 1055);

        const jenisBelanja = $('#select_jenis_bl option:selected').text();
        const rekening = $('#select_akun_rekening option:selected').text();

        $('#info_jenis_belanja').html('<strong>Objek Belanja:</strong> ' + jenisBelanja);
        $('#info_rekening').html('<strong>Rekening:</strong> ' + rekening);
      });

      // ============================================================
      // SAVE PAKET BELANJA
      // ============================================================
      $('#btn_save_paket').on('click', function() {
        const form = $('#form_add_paket');
        const btn = $(this);

        if (!form[0].checkValidity()) {
          form[0].reportValidity();
          return;
        }

        // ========================================
        // TAMBAHKAN [#] OTOMATIS
        // ========================================
        let uraianPaket = $('textarea[name="uraian_paket"]').val().trim();

        // Jika belum ada [#] di awal, tambahkan
        if (!uraianPaket.startsWith('[#]')) {
          uraianPaket = '[#] ' + uraianPaket;
        }

        const formData = {
          id_rinci_sub_bl: $('input[name="id_rinci_sub_bl"]').val(),
          tipe_paket: $('#tipe_paket_new').val(),
          jenis_bl: $('#jenis_bl_new').val(),
          id_akun: $('#id_akun_new').val(),
          uraian_paket: uraianPaket // ← KIRIM DENGAN [#]
        };

        if (!formData.tipe_paket || !formData.jenis_bl || !formData.id_akun || !formData.uraian_paket) {
          toastr.error('Lengkapi semua field', 'Validasi');
          return;
        }

        btn.find('.indicator-label').hide();
        btn.find('.indicator-progress').show();
        btn.prop('disabled', true);

        $.ajax({
          url: '{{ route('paket.store') }}',
          type: 'POST',
          data: formData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              toastr.success(response.message, 'Berhasil');

              const newOption = new Option(response.data.uraian_paket, response.data.id, true, true);
              $('#select_uraian_paket').append(newOption).trigger('change');

              $('#modal_add_paket').modal('hide');
              $('#form_add_paket')[0].reset();
            } else {
              toastr.error(response.message, 'Error');
            }
          },
          error: function(xhr) {
            const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan';
            toastr.error(message, 'Error');
          },
          complete: function() {
            btn.find('.indicator-label').show();
            btn.find('.indicator-progress').hide();
            btn.prop('disabled', false);
          }
        });
      });

      // ============================================================
      // RESET MODAL PAKET
      // ============================================================
      $('#modal_add_paket').on('hidden.bs.modal', function() {
        $('#form_add_paket')[0].reset();
        $('#modal_add_rincian').css('z-index', '');
      });

      // ============================================================
      // EVENT: KETIKA PAKET DIPILIH - LOAD MINTAG
      // ============================================================
      $('#select_uraian_paket').on('change', function() {
        const idPaketBelanja = $(this).val();

        if (!idPaketBelanja) {
          $('#wrapper_kategori_belanja').hide();
          $('#select_kategori_belanja').prop('disabled', true).html('<option value="">Pilih Kategori Belanja...</option>');
          return;
        }

        loadMintagList(idPaketBelanja);
      });

      // ============================================================
      // FUNCTION: LOAD MINTAG LIST
      // ============================================================
      function loadMintagList(idPaketBelanja) {
        $('#select_kategori_belanja').prop('disabled', true).html('<option value="">⏳ Memuat kategori...</option>');
        $('#wrapper_kategori_belanja').show();

        $.ajax({
          url: '{{ route('mintag.list') }}',
          type: 'GET',
          data: {
            id_paket_belanja: idPaketBelanja
          },
          success: function(response) {
            $('#select_kategori_belanja').empty().append('<option value="">Pilih Kategori Belanja...</option>');

            if (response.success && response.data && response.data.length > 0) {
              $.each(response.data, function(index, mintag) {
                $('#select_kategori_belanja').append(
                  $('<option></option>')
                  .val(mintag.value) // Dengan [-]
                  .text(mintag.text) // Tanpa [-]
                );
              });

              toastr.success(`✓ ${response.data.length} kategori tersedia`, 'Berhasil');
            } else {
              toastr.info('Belum ada kategori, silakan tambahkan', 'Info');
            }

            $('#select_kategori_belanja').prop('disabled', false);

            // Re-init Select2
            if ($('#select_kategori_belanja').hasClass('select2-hidden-accessible')) {
              $('#select_kategori_belanja').select2('destroy');
            }

            $('#select_kategori_belanja').select2({
              dropdownParent: $('#modal_add_rincian'),
              placeholder: 'Pilih Kategori Belanja...',
              allowClear: true,
              width: '100%'
            });
          },
          error: function(xhr) {
            $('#select_kategori_belanja').html('<option value="">❌ Error memuat data</option>').prop('disabled', false);
            toastr.error('Gagal memuat kategori belanja', 'Error');
          }
        });
      }

      // ============================================================
      // BUTTON: OPEN MODAL MINTAG
      // ============================================================
      $('#btn_open_modal_mintag').on('click', function() {
        const idPaketBelanja = $('#select_uraian_paket').val();
        const namaPaket = $('#select_uraian_paket option:selected').text();

        if (!idPaketBelanja) {
          toastr.error('Pilih paket belanja terlebih dahulu', 'Validasi');
          return;
        }

        $('#id_paket_belanja_mintag').val(idPaketBelanja);
        $('#info_paket_mintag').html('<strong>' + namaPaket + '</strong>');

        // ✅ FIX: Langsung show modal tanpa manipulasi z-index
        // Bootstrap 5 akan handle modal stacking secara otomatis
        $('#modal_add_mintag').modal('show');
      });

      // ============================================================
      // MODAL MINTAG SHOWN
      // ============================================================
      $('#modal_add_mintag').on('shown.bs.modal', function() {
        $('textarea[name="nama_mintag"]').focus();
      });

      // ============================================================
      // SAVE MINTAG
      // ============================================================
      $('#btn_save_mintag').on('click', function() {
        const form = $('#form_add_mintag');
        const btn = $(this);

        if (!form[0].checkValidity()) {
          form[0].reportValidity();
          return;
        }

        const formData = {
          id_paket_belanja: $('#id_paket_belanja_mintag').val(),
          nama_mintag: $('textarea[name="nama_mintag"]').val().trim()
        };

        if (!formData.id_paket_belanja || !formData.nama_mintag) {
          toastr.error('Lengkapi semua field', 'Validasi');
          return;
        }

        btn.find('.indicator-label').hide();
        btn.find('.indicator-progress').show();
        btn.prop('disabled', true);

        $.ajax({
          url: '{{ route('mintag.store') }}',
          type: 'POST',
          data: formData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              toastr.success(response.message, 'Berhasil');

              // Tambahkan ke dropdown dan select otomatis
              const newOption = new Option(response.data.text, response.data.value, true, true);
              $('#select_kategori_belanja').append(newOption).trigger('change');

              $('#modal_add_mintag').modal('hide');
            } else {
              toastr.error(response.message, 'Error');
            }
          },
          error: function(xhr) {
            const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan';
            toastr.error(message, 'Error');
          },
          complete: function() {
            btn.find('.indicator-label').show();
            btn.find('.indicator-progress').hide();
            btn.prop('disabled', false);
          }
        });
      });


      // ============================================================
      // RESET MODAL MINTAG
      // ============================================================
      $('#modal_add_mintag').on('hidden.bs.modal', function() {
        // Reset form
        $('#form_add_mintag')[0].reset();

        // ✅ FIX: Blur semua element di modal untuk menghindari aria-hidden warning
        $(this).find('button, input, textarea, select').blur();

        // ✅ FIX: Optional - Return focus ke parent modal jika masih terbuka
        if ($('#modal_add_rincian').hasClass('show')) {
          setTimeout(function() {
            // Focus ke element yang aman (tidak akan trigger warning)
            $('#select_kategori_belanja').focus();
          }, 150);
        }
      });

    });
  </script>
@endsection
