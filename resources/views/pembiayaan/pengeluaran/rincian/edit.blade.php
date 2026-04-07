@extends('layouts.master')

@section('content')
  {{-- Toolbar --}}
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">
            Edit Data Pembiayaan Pengeluaran
          </h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/home') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Pembiayaan</li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('pembiayaan.pengeluaran.index') }}" class="text-muted text-hover-primary">Pengeluaran</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('pembiayaan.pengeluaran.rincian', $id_skpd) }}" class="text-muted text-hover-primary">Rincian</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Edit</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
      <div class="card">

        {{-- Card Header --}}
        <div class="card-header">
          <div class="card-title">
            <h2>Edit Pengeluaran:
              <span class="text-primary">
                {{ $pembiayaan->kode_akun ?? ($pembiayaan->rekening ?? '-') }}
              </span>
            </h2>
          </div>
          <div class="card-toolbar">
            <a href="{{ route('pembiayaan.pengeluaran.rincian', $id_skpd) }}" class="btn btn-secondary">
              <i class="ki-outline ki-arrow-left fs-2"></i>
              Kembali
            </a>
          </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('pembiayaan.pengeluaran.update', [$id_skpd, $pembiayaan->id]) }}" method="POST" class="form"
          id="form_edit_pengeluaran">
          @csrf
          @method('PUT')

          <div class="card-body">

            {{-- Session alerts --}}
            @if (session('success'))
              <div class="alert alert-success alert-dismissible fade show">
                <i class="ki-outline ki-check-circle fs-2 text-success me-3"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            @if (session('error'))
              <div class="alert alert-danger alert-dismissible fade show">
                <i class="ki-outline ki-cross-circle fs-2 text-danger me-3"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
              </div>
            @endif

            {{-- Validation errors --}}
            @if ($errors->any())
              <div class="alert alert-danger d-flex align-items-center mb-6">
                <i class="ki-outline ki-shield-cross fs-2hx text-danger me-4"></i>
                <div>
                  <div class="fw-bold">Terdapat kesalahan pada input:</div>
                  <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                      <li class="fs-7">{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              </div>
            @endif

            <div class="row">
              {{-- ── Kolom kiri ─────────────────────────────────────── --}}
              <div class="col-md-6">

                {{-- Info SKPD (read-only) --}}
                <div class="fv-row mb-7">
                  <label class="fs-6 fw-semibold mb-2">SKPD</label>
                  <input type="text" class="form-control form-control-solid"
                    value="{{ ($skpd->kode_skpd ?? ($skpd->kodeunit ?? '-')) . ' — ' . ($skpd->nama_skpd ?? ($skpd->namaunit ?? '-')) }}" readonly />
                  <div class="form-text text-muted fs-7">Data SKPD tidak dapat diubah.</div>
                </div>

                {{-- Akun / Rekening --}}
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Akun / Rekening</label>
                  <select name="id_akun" id="select_id_akun_edit" class="form-select form-select-solid @error('id_akun') is-invalid @enderror"
                    data-control="select2" data-placeholder="-- Pilih Akun --" data-allow-clear="true" required>
                    <option></option>
                    @foreach ($akunList as $akun)
                      <option value="{{ $akun->id }}" {{ old('id_akun', $pembiayaan->id_akun) == $akun->id ? 'selected' : '' }}>
                        {{ $akun->kode_akun }} - {{ $akun->nama_akun }}
                      </option>
                    @endforeach
                  </select>
                  @error('id_akun')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <div class="form-text text-muted fs-7">Pilih kode rekening pembiayaan pengeluaran yang sesuai.</div>
                </div>

                {{-- Nilai --}}
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Nilai (Rp)</label>
                  <div class="input-group input-group-solid">
                    <span class="input-group-text fw-semibold text-muted">Rp</span>
                    <input type="text" name="nilai" id="input_nilai_edit"
                      class="form-control form-control-solid @error('nilai') is-invalid @enderror" placeholder="0"
                      value="{{ old('nilai', $pembiayaan->nilaimurni) }}" inputmode="numeric" required />
                    @error('nilai')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-text text-muted fs-7 mt-1">
                    <span id="preview_nilai_edit" class="text-gray-700 fw-semibold"></span>
                  </div>
                  <div class="form-text text-muted fs-7">
                    Nilai ini akan disimpan ke kolom <code>total</code> dan <code>nilaimurni</code>.
                  </div>
                </div>

              </div>

              {{-- ── Kolom kanan ─────────────────────────────────────── --}}
              <div class="col-md-6">

                {{-- Keterangan / Uraian --}}
                <div class="fv-row mb-7">
                  <label class="fs-6 fw-semibold mb-2">Keterangan</label>
                  <textarea name="keterangan" id="input_keterangan_edit" class="form-control form-control-solid @error('keterangan') is-invalid @enderror"
                    rows="5" placeholder="Masukkan keterangan / uraian pembiayaan pengeluaran...">{{ old('keterangan', $pembiayaan->keterangan) }}</textarea>
                  @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  <div class="form-text text-muted fs-7">
                    Nilai ini akan disimpan ke kolom <code>keterangan</code> dan <code>uraian</code>.
                  </div>
                </div>

                {{-- Info audit trail (read-only) --}}
                <div class="card border border-dashed border-gray-300 bg-light-secondary">
                  <div class="card-body py-4 px-5">
                    <h6 class="fw-bold mb-3 text-gray-700">
                      <i class="ki-outline ki-information-5 fs-4 me-2"></i>
                      Informasi Data
                    </h6>
                    <div class="row g-3">
                      <div class="col-6">
                        <span class="text-muted fs-8 d-block">Rekening Saat Ini</span>
                        <span class="fw-semibold fs-7">{{ $pembiayaan->kode_akun ?? '-' }}</span>
                      </div>
                      <div class="col-6">
                        <span class="text-muted fs-8 d-block">Tahun Anggaran</span>
                        <span class="fw-semibold fs-7">{{ $pembiayaan->tahun_anggaran ?? $tahunAnggaran }}</span>
                      </div>
                      <div class="col-6">
                        <span class="text-muted fs-8 d-block">Nilai Murni (sebelum)</span>
                        <span class="fw-semibold fs-7 text-info">
                          Rp {{ number_format($pembiayaan->nilaimurni ?? 0, 0, ',', '.') }}
                        </span>
                      </div>
                      <div class="col-6">
                        <span class="text-muted fs-8 d-block">Total (setelah)</span>
                        <span class="fw-semibold fs-7 text-success">
                          Rp {{ number_format($pembiayaan->total ?? 0, 0, ',', '.') }}
                        </span>
                      </div>
                      @if (!empty($pembiayaan->updateddate))
                        <div class="col-12">
                          <span class="text-muted fs-8 d-block">Terakhir Diperbarui</span>
                          <span class="fw-semibold fs-7">
                            {{ $pembiayaan->updateddate }} {{ $pembiayaan->updatedtime }}
                          </span>
                        </div>
                      @endif
                    </div>
                  </div>
                </div>

              </div>
            </div>{{-- /row --}}

          </div>{{-- /card-body --}}

          {{-- Card Footer --}}
          <div class="card-footer bg-light">
            <div class="d-flex justify-content-end gap-2">
              <a href="{{ route('pembiayaan.pengeluaran.rincian', $id_skpd) }}" class="btn btn-light">
                <i class="ki-outline ki-cross fs-2"></i>
                Batal
              </a>
              <button type="submit" class="btn btn-primary" id="btn_update_pengeluaran">
                <i class="ki-outline ki-check fs-2"></i>
                Simpan Perubahan
              </button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      "use strict";

      // ── Helpers format rupiah ────────────────────────────────────────
      function formatRibuan(val) {
        val = val.replace(/\D/g, '');
        return val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      }

      function toNumber(val) {
        return val.replace(/\./g, '');
      }

      function updatePreview(raw) {
        var preview = document.getElementById('preview_nilai_edit');
        if (!preview) return;
        var num = parseFloat(raw) || 0;
        preview.textContent = num > 0 ? 'Terbaca: Rp ' + num.toLocaleString('id-ID') : '';
      }

      var inputNilai = document.getElementById('input_nilai_edit');

      if (inputNilai) {
        // Format nilai awal dari DB saat halaman load
        var initialRaw = inputNilai.value.replace(/\D/g, '');
        if (initialRaw) {
          inputNilai.value = formatRibuan(initialRaw);
          updatePreview(initialRaw);
        }

        // Format ribuan saat mengetik
        inputNilai.addEventListener('input', function() {
          var cursorPos = this.selectionStart;
          var rawLen = this.value.length;
          var formatted = formatRibuan(this.value);
          this.value = formatted;
          var diff = formatted.length - rawLen;
          this.setSelectionRange(cursorPos + diff, cursorPos + diff);
          updatePreview(toNumber(formatted));
        });

        // Konversi ke angka murni sebelum submit + loading indicator
        var form = document.getElementById('form_edit_pengeluaran');
        if (form) {
          form.addEventListener('submit', function() {
            inputNilai.value = toNumber(inputNilai.value);
            var btn = document.getElementById('btn_update_pengeluaran');
            if (btn) {
              btn.setAttribute('data-kt-indicator', 'on');
              btn.disabled = true;
            }
          });
        }
      }

      // Toastr config
      toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toastr-top-right',
        timeOut: '5000'
      };
    });
  </script>
@endsection
