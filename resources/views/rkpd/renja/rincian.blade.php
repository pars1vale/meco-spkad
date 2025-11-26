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
                  <!-- Header Objek Belanja -->
                  <tr class="bg-light-primary">
                    <td class="fw-bold">{{ $no++ }}</td>
                    <td class="fw-bold">{{ $objek['kode_rekening'] }}</td>
                    <td class="fw-bold" colspan="4">{{ $objek['nama_rekening'] }}</td>
                    <td class="text-end fw-bold text-primary">Rp {{ number_format($objek['total'], 0, ',', '.') }}</td>
                    <td></td>
                  </tr>
                  
                  <!-- Detail Rincian -->
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
  <div class="modal-dialog modal-dialog-centered mw-800px">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="fw-bold">Tambah Rincian Belanja</h2>
        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
          <i class="ki-outline ki-cross fs-1"></i>
        </div>
      </div>
      <div class="modal-body">
        <form id="form_add_rincian">
          <div class="mb-5">
            <label class="required form-label">Objek Belanja</label>
            <select class="form-select" name="id_objek_belanja" required>
              <option value="">Pilih Objek Belanja</option>
              <!-- Load from database -->
            </select>
          </div>
          <div class="mb-5">
            <label class="required form-label">Uraian</label>
            <textarea class="form-control" name="uraian" rows="3" required></textarea>
          </div>
          <div class="row">
            <div class="col-md-4 mb-5">
              <label class="required form-label">Volume</label>
              <input type="text" class="form-control" name="volume" required>
            </div>
            <div class="col-md-4 mb-5">
              <label class="required form-label">Satuan</label>
              <input type="text" class="form-control" name="satuan" required>
            </div>
            <div class="col-md-4 mb-5">
              <label class="required form-label">Harga Satuan</label>
              <input type="text" class="form-control input-currency" name="harga_satuan" required>
            </div>
          </div>
          <div class="mb-5">
            <label class="form-label">Total</label>
            <input type="text" class="form-control" id="total_display" readonly>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="btn_save_rincian">Simpan</button>
      </div>
    </div>
  </div>
</div>

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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Currency formatting
  document.querySelectorAll('.input-currency').forEach(input => {
    input.addEventListener('input', function(e) {
      let value = e.target.value.replace(/[^\d]/g, '');
      if (value) {
        e.target.value = new Intl.NumberFormat('id-ID').format(value);
      }
    });
  });

  // Calculate total
  const volumeInput = document.querySelector('input[name="volume"]');
  const hargaInput = document.querySelector('input[name="harga_satuan"]');
  const totalDisplay = document.getElementById('total_display');

  function calculateTotal() {
    const volume = parseFloat(volumeInput.value) || 0;
    const harga = parseFloat(hargaInput.value.replace(/[^\d]/g, '')) || 0;
    const total = volume * harga;
    totalDisplay.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
  }

  if (volumeInput && hargaInput) {
    volumeInput.addEventListener('input', calculateTotal);
    hargaInput.addEventListener('input', calculateTotal);
  }
});
</script>

@endsection