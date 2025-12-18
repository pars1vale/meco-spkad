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
            @if($sumberDana->count() > 0)
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
                    @foreach($sumberDana as $dana)
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
    @if($indikator->count() > 0)
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
              @foreach($indikator as $index => $ind)
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
        @if($rincianBelanja->count() > 0)
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
                @foreach($totalPerObjek as $objekId => $objek)
                  <tr class="bg-light-primary">
                    <td class="fw-bold">{{ $no++ }}</td>
                    <td class="fw-bold">{{ $objek['kode_rekening'] }}</td>
                    <td class="fw-bold" colspan="4">{{ $objek['nama_rekening'] }}</td>
                    <td class="text-end fw-bold text-primary">Rp {{ number_format($objek['total'], 0, ',', '.') }}</td>
                    <td></td>
                  </tr>
                  
                  @foreach($objek['items'] as $index => $item)
                  <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td class="ps-5">{{ $item->kode_komponen ?? '-' }}</td>
                    <td>{{ $item->uraian }}</td>
                    <td class="text-center">{{ number_format($item->volume ?? 0, 2, ',', '.') }}</td>
                    <td class="text-center">{{ $item->satuan }}</td>
                    <td class="text-end">Rp {{ number_format($item->harga_satuan ?? 0, 0, ',', '.') }}</td>
                    <td class="text-end fw-bold">Rp {{ number_format(($item->volume ?? 0) * ($item->harga_satuan ?? 0), 0, ',', '.') }}</td>
                    <td class="text-center">
                      <button class="btn btn-icon btn-sm btn-light-primary btn-edit" data-id="{{ $item->id }}">
                        <i class="ki-outline ki-pencil fs-4"></i>
                      </button>
                      <button class="btn btn-icon btn-sm btn-light-danger btn-delete" data-id="{{ $item->id }}">
                        <i class="ki-outline ki-trash fs-4"></i>
                      </button>
                    </td>
                  </tr>
                  @endforeach
                @endforeach
              </tbody>
              <tfoot>
                <tr class="bg-light">
                  <td colspan="6" class="text-end fw-bold fs-5">TOTAL KESELURUHAN:</td>
                  <td class="text-end fw-bold fs-4 text-primary">
                    Rp {{ number_format($rincianBelanja->sum(function($item) {
                      return ($item->volume ?? 0) * ($item->harga_satuan ?? 0);
                    }), 0, ',', '.') }}
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

<!-- Modal Tambah Rincian -->
<div class="modal fade" id="modal_add_rincian" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-900px">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="fw-bold">Tambah Rincian Belanja</h2>
        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
          <i class="ki-outline ki-cross fs-1"></i>
        </div>
      </div>
      <div class="modal-body">
        <form id="form_add_rincian">
          <input type="hidden" name="id_rinci_sub_bl" value="{{ $subKegiatan->id }}">
          <input type="hidden" name="tahun_anggaran" value="{{ $subKegiatan->tahun_anggaran }}">
          
          <!-- Step 1: Pilih Objek Belanja -->
          <div class="mb-7">
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
            <div class="form-text">Pilih jenis objek belanja terlebih dahulu</div>
          </div>

          <!-- Step 2: Pilih Rekening -->
          <div class="mb-7" id="wrapper_akun_rekening" style="display: none;">
            <label class="required form-label fw-bold fs-6">Rekening Belanja</label>
            <select class="form-select form-select-solid" id="select_akun_rekening" name="id_akun" required disabled>
              <option value="">Pilih rekening belanja...</option>
            </select>
            <div class="form-text">Pilih rekening belanja yang sesuai</div>
            <input type="hidden" name="kode_rekening" id="kode_rekening">
            <input type="hidden" name="nama_rekening" id="nama_rekening">
          </div>

          <!-- Step 3: Pilih Tipe Paket -->
          <div class="mb-7">
            <label class="required form-label fw-bold fs-6">Pengelompokan Belanja / Paket Pekerjaan</label>
            <select class="form-select form-select-solid" id="select_tipe_paket" name="tipe_paket" required>
              <option value="">Pilih Paket/Kelompok...</option>
              <option value="1">Pemaketan Kerja</option>
              <option value="2">Pengelompokan Belanja</option>
            </select>
            <div class="form-text">Pilih jenis pemaketan atau pengelompokan</div>
          </div>

          <!-- Step 4: Pilih/Tambah Uraian Paket -->
          <div class="mb-7" id="wrapper_uraian_paket" style="display: none;">
            <label class="required form-label fw-bold fs-6">Uraian Pengelompokan Belanja / Paket Pekerjaan</label>
            <div class="input-group">
              <select class="form-select form-select-solid" id="select_uraian_paket" name="id_paket_belanja" required disabled>
                <option value="">Pilih Paket Belanja...</option>
              </select>
              <button class="btn btn-primary" type="button" id="btn_open_modal_paket">
                <i class="ki-outline ki-add-files fs-4"></i>
                Tambah Paket Belanja
              </button>
            </div>
            <div class="form-text">Pilih paket belanja yang sudah ada atau tambah baru</div>
          </div>

          <div class="separator separator-dashed my-7"></div>

          <!-- Detail Rincian -->
          <div class="mb-5">
            <label class="required form-label fw-bold">Uraian</label>
            <textarea class="form-control form-control-solid" name="uraian" rows="3" placeholder="Masukkan uraian rincian belanja..." required></textarea>
          </div>

          <div class="row g-5">
            <div class="col-md-4">
              <label class="required form-label fw-bold">Volume</label>
              <input type="text" class="form-control form-control-solid" name="volume" placeholder="0" required>
            </div>
            <div class="col-md-4">
              <label class="required form-label fw-bold">Satuan</label>
              <input type="text" class="form-control form-control-solid" name="satuan" placeholder="Unit, Orang, Bulan, dll" required>
            </div>
            <div class="col-md-4">
              <label class="required form-label fw-bold">Harga Satuan</label>
              <input type="text" class="form-control form-control-solid input-currency" name="harga_satuan" placeholder="0" required>
            </div>
          </div>

          <div class="separator separator-dashed my-7"></div>

          <!-- Total -->
          <div class="d-flex justify-content-between align-items-center p-5 bg-light-primary rounded">
            <span class="fw-bold fs-5 text-gray-800">Total Belanja:</span>
            <span class="fw-bold fs-3 text-primary" id="total_display">Rp 0</span>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn_save_rincian">
          <span class="indicator-label">Simpan</span>
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
            <textarea class="form-control form-control-solid" name="uraian_paket" rows="4" placeholder="Masukkan uraian pemaketan kerja atau pengelompokan belanja..." required></textarea>
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

<script>
// Update info saat modal paket dibuka
$('#modal_add_paket').on('shown.bs.modal', function () {
  // Ambil info dari form rincian
  const jenisBelanja = $('#select_jenis_bl option:selected').text();
  const rekening = $('#select_akun_rekening option:selected').text();
  const tipePaket = $('#select_tipe_paket option:selected').text();
  
  // Update display info
  $('#info_jenis_belanja').html('<strong>Objek Belanja:</strong> ' + jenisBelanja);
  $('#info_rekening').html('<strong>Rekening:</strong> ' + rekening);
});
</script>

<style>
@media print {
  .app-toolbar, .card-toolbar, .btn-edit, .btn-delete, .modal {
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

.select2-dropdown-above-modal {
  z-index: 10001 !important;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
  console.log('=== PAGE LOADED ===');

  // ============================================================
  // INITIALIZE SELECT2
  // ============================================================
  $('#modal_add_rincian').on('shown.bs.modal', function () {
    console.log('Modal Rincian opened, initializing Select2...');
    
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
    
    console.log('=== JENIS BELANJA CHANGED ===');
    console.log('Jenis Belanja:', jenisBelanja);
    
    if (!jenisBelanja) {
      $('#wrapper_akun_rekening').hide();
      $('#select_akun_rekening')
        .prop('disabled', true)
        .html('<option value="">Pilih rekening belanja...</option>');
      return;
    }

    $('#select_akun_rekening')
      .prop('disabled', true)
      .html('<option value="">⏳ Memuat data rekening...</option>');
    $('#wrapper_akun_rekening').show();

    $.ajax({
      url: '{{ route("rincian.get-akun") }}',
      type: 'GET',
      data: {
        jenis_bl: jenisBelanja,
        tahun_anggaran: tahunAnggaran
      },
      dataType: 'json',
      success: function(response) {
        console.log('AJAX SUCCESS:', response);
        
        if (response.success) {
          $('#select_akun_rekening').empty();
          $('#select_akun_rekening').append('<option value="">Pilih rekening belanja...</option>');
          
          if (response.data && response.data.length > 0) {
            $.each(response.data, function(index, akun) {
              const option = $('<option></option>')
                .val(akun.id)
                .text(akun.text)
                .attr('data-kode', akun.kode_akun)
                .attr('data-nama', akun.nama_akun);
              
              $('#select_akun_rekening').append(option);
            });
            
            $('#select_akun_rekening').prop('disabled', false);
            
            if ($('#select_akun_rekening').hasClass('select2-hidden-accessible')) {
              $('#select_akun_rekening').select2('destroy');
              $('#select_akun_rekening').select2({
                dropdownParent: $('#modal_add_rincian'),
                placeholder: 'Pilih rekening belanja...',
                allowClear: true,
                width: '100%'
              });
            }
            
            toastr.success(`✓ ${response.data.length} rekening tersedia`, 'Berhasil');
          } else {
            $('#select_akun_rekening').html('<option value="">❌ Tidak ada data rekening</option>');
            toastr.warning('Tidak ada rekening untuk objek belanja ini', 'Perhatian');
          }
        }
      },
      error: function(xhr) {
        console.error('AJAX ERROR:', xhr);
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
    const kodeAkun = selectedOption.attr('data-kode');
    const namaAkun = selectedOption.attr('data-nama');
    
    $('#kode_rekening').val(kodeAkun || '');
    $('#nama_rekening').val(namaAkun || '');
    
    console.log('Rekening selected:', { id: $(this).val(), kode: kodeAkun, nama: namaAkun });
    
    // RELOAD paket list jika tipe paket sudah dipilih
    const tipePaket = $('#select_tipe_paket').val();
    if (tipePaket) {
      loadPaketList();
    }
  });

  // ============================================================
  // EVENT: KETIKA TIPE PAKET DIPILIH
  // ============================================================
  $('#select_tipe_paket').on('change', function() {
    const tipePaket = $(this).val();
    
    console.log('=== TIPE PAKET CHANGED ===');
    console.log('Tipe Paket:', tipePaket);
    
    if (!tipePaket) {
      $('#wrapper_uraian_paket').hide();
      $('#select_uraian_paket')
        .prop('disabled', true)
        .html('<option value="">Pilih Paket Belanja...</option>');
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
    
    if (!tipePaket || !idRinciSubBl) {
      return;
    }

    $('#select_uraian_paket')
      .prop('disabled', true)
      .html('<option value="">⏳ Memuat data paket...</option>');
    $('#wrapper_uraian_paket').show();

    $.ajax({
      url: '{{ route("paket.list") }}',
      type: 'GET',
      data: {
        id_rinci_sub_bl: idRinciSubBl,
        tipe_paket: tipePaket,
        jenis_bl: jenisBelanja
      },
      success: function(response) {
        console.log('Load Paket Response:', response);
        
        $('#select_uraian_paket').empty();
        $('#select_uraian_paket').append('<option value="">Pilih Paket Belanja...</option>');
        
        if (response.success && response.data && response.data.length > 0) {
          $.each(response.data, function(index, paket) {
            $('#select_uraian_paket').append(
              $('<option></option>')
                .val(paket.id)
                .text(paket.uraian_paket)
            );
          });
          
          toastr.success(`✓ ${response.data.length} paket tersedia`, 'Berhasil');
        } else {
          toastr.info('Belum ada paket, silakan tambahkan', 'Info');
        }
        
        $('#select_uraian_paket').prop('disabled', false);
        
        if ($('#select_uraian_paket').hasClass('select2-hidden-accessible')) {
          $('#select_uraian_paket').select2('destroy');
          $('#select_uraian_paket').select2({
            dropdownParent: $('#modal_add_rincian'),
            placeholder: 'Pilih Paket Belanja...',
            allowClear: true,
            width: '100%'
          });
        }
      },
      error: function(xhr) {
        console.error('Load Paket Error:', xhr);
        $('#select_uraian_paket').html('<option value="">❌ Error memuat data</option>');
        $('#select_uraian_paket').prop('disabled', false);
        toastr.error('Gagal memuat data paket', 'Error');
      }
    });
  }

  // ============================================================
  // BUTTON: OPEN MODAL PAKET
  // ============================================================
  $('#btn_open_modal_paket').on('click', function() {
    const tipePaket = $('#select_tipe_paket').val();
    const jenisBelanja = $('#select_jenis_bl').val();
    const idAkun = $('#select_akun_rekening').val();
    
    // Validasi
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

    console.log('Opening modal paket with:', { tipePaket, jenisBelanja, idAkun });
    
    // Set hidden fields
    $('#tipe_paket_new').val(tipePaket);
    $('#jenis_bl_new').val(jenisBelanja);
    $('#id_akun_new').val(idAkun);
    
    $('#modal_add_rincian').css('z-index', 1050);
    $('#modal_add_paket').modal('show');
  });

  // ============================================================
  // MODAL PAKET SHOWN
  // ============================================================
  $('#modal_add_paket').on('shown.bs.modal', function () {
    console.log('Modal Paket opened');
    $('#modal_add_paket').css('z-index', 1060);
    $('.modal-backdrop').not(':first').css('z-index', 1055);
    
    // Update info display
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

    const tipePaket = $('#tipe_paket_new').val();
    const jenisBelanja = $('#jenis_bl_new').val();
    const idAkun = $('#id_akun_new').val();
    const uraianPaket = $('textarea[name="uraian_paket"]').val();

    if (!tipePaket || !jenisBelanja || !idAkun || !uraianPaket) {
      toastr.error('Lengkapi semua field', 'Validasi');
      return;
    }

    btn.find('.indicator-label').hide();
    btn.find('.indicator-progress').show();
    btn.prop('disabled', true);

    const formData = {
      id_rinci_sub_bl: $('input[name="id_rinci_sub_bl"]').val(),
      tipe_paket: tipePaket,
      jenis_bl: jenisBelanja,
      id_akun: idAkun,
      uraian_paket: uraianPaket
    };

    console.log('Saving paket:', formData);

    $.ajax({
      url: '{{ route("paket.store") }}',
      type: 'POST',
      data: formData,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        console.log('Save paket response:', response);
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
        console.error('Save paket error:', xhr);
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
    console.log('Modal paket hidden, resetting...');
    $('#form_add_paket')[0].reset();
    $('#modal_add_rincian').css('z-index', '');
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
    const volume = parseFloat($('input[name="volume"]').val()) || 0;
    const harga = parseFloat($('input[name="harga_satuan"]').val().replace(/[^\d]/g, '')) || 0;
    const total = volume * harga;
    $('#total_display').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
  }

  $(document).on('input', 'input[name="volume"], input[name="harga_satuan"]', calculateTotal);

  // ============================================================
  // SAVE RINCIAN BELANJA
  // ============================================================
  $('#btn_save_rincian').on('click', function() {
    const form = $('#form_add_rincian');
    const btn = $(this);
    
    if (!form[0].checkValidity()) {
      form[0].reportValidity();
      return;
    }

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

    btn.find('.indicator-label').hide();
    btn.find('.indicator-progress').show();
    btn.prop('disabled', true);

    const volumeVal = parseFloat($('input[name="volume"]').val());
    const hargaVal = parseFloat($('input[name="harga_satuan"]').val().replace(/[^\d]/g, ''));
    
    const formData = {
      id_rinci_sub_bl: $('input[name="id_rinci_sub_bl"]').val(),
      jenis_bl: $('#select_jenis_bl').val(),
      id_akun: $('#select_akun_rekening').val(),
      kode_rekening: $('#kode_rekening').val(),
      nama_rekening: $('#nama_rekening').val(),
      tipe_paket: $('#select_tipe_paket').val(),
      id_paket_belanja: $('#select_uraian_paket').val(),
      uraian: $('textarea[name="uraian"]').val(),
      volume: volumeVal,
      satuan: $('input[name="satuan"]').val(),
      harga_satuan: hargaVal
    };

    console.log('Saving rincian:', formData);

    $.ajax({
      url: '{{ route("rincian.store") }}',
      type: 'POST',
      data: formData,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        console.log('Save response:', response);
        if (response.success) {
          toastr.success(response.message, 'Berhasil');
          $('#modal_add_rincian').modal('hide');
          
          setTimeout(function() {
            location.reload();
          }, 1000);
        } else {
          toastr.error(response.message, 'Error');
        }
      },
      error: function(xhr) {
        console.error('Save error:', xhr);
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
  // RESET MODAL RINCIAN
  // ============================================================
  $('#modal_add_rincian').on('hidden.bs.modal', function() {
    console.log('Modal rincian hidden, resetting...');
    $('#form_add_rincian')[0].reset();
    
    ['#select_jenis_bl', '#select_akun_rekening', '#select_tipe_paket', '#select_uraian_paket'].forEach(function(selector) {
      if ($(selector).hasClass('select2-hidden-accessible')) {
        $(selector).val(null).trigger('change');
      }
    });
    
    $('#wrapper_akun_rekening').hide();
    $('#wrapper_uraian_paket').hide();
    $('#total_display').text('Rp 0');
    $('#kode_rekening, #nama_rekening').val('');
  });

});
</script>
@endsection