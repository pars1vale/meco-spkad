@extends('layouts.master')
@section('content')
  <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
    <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
      <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
        <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
          <h1 class="page-heading d-flex flex-column justify-content-center text-dark fw-bold fs-3 m-0">Edit Kegiatan</h1>
          <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
            <li class="breadcrumb-item text-muted">
              <a href="{{ url('/') }}" class="text-muted text-hover-primary">Home</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Referensi</li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">
              <a href="{{ route('referensi.kegiatan.index') }}" class="text-muted text-hover-primary">Kegiatan</a>
            </li>
            <li class="breadcrumb-item">
              <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li>
            <li class="breadcrumb-item text-muted">Edit</li>
          </ul>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
          <a href="{{ route('referensi.kegiatan.index') }}" class="btn btn-sm fw-bold btn-secondary">
            <i class="ki-outline ki-arrow-left fs-2"></i>Kembali
          </a>
        </div>
      </div>
    </div>
  </div>

  <div id="kt_app_content" class="app-content flex-column-fluid">
    <div id="kt_app_content_container" class="app-container container-fluid">
      <div class="card">
        <div class="card-header border-0 pt-6">
          <div class="card-title">
            <h2>Form Edit Kegiatan</h2>
          </div>
        </div>

        <div class="card-body pt-0">
          <form id="kt_kegiatan_edit_form" class="form" action="{{ route('referensi.kegiatan.update', $kegiatan->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-7">
              <div class="col-md-3">
                <label class="fs-6 fw-semibold form-label mt-3">
                  <span class="required">Program</span>
                </label>
              </div>
              <div class="col-md-9">
                <select name="id_program" class="form-select form-select-solid @error('id_program') is-invalid @enderror" data-control="select2"
                  data-placeholder="Pilih Program">
                  <option></option>
                  @php
                    $currentUrusan = null;
                    $currentBidang = null;
                  @endphp
                  @foreach ($listProgram as $program)
                    @if ($currentUrusan !== $program->nama_urusan)
                      @if ($currentUrusan !== null)
                        </optgroup>
                      @endif
                      <optgroup label="{{ $program->nama_urusan }}">
                        @php
                          $currentUrusan = $program->nama_urusan;
                          $currentBidang = null;
                        @endphp
                    @endif
                    @if ($currentBidang !== $program->nama_bidang_urusan)
                      @if ($currentBidang !== null)
                        </optgroup>
                      @endif
                      <optgroup label="&nbsp;&nbsp;{{ $program->nama_bidang_urusan }}">
                        @php $currentBidang = $program->nama_bidang_urusan; @endphp
                    @endif
                    <option value="{{ $program->id }}" {{ old('id_program', $kegiatan->id_program) == $program->id ? 'selected' : '' }}>
                      &nbsp;&nbsp;&nbsp;&nbsp;{{ $program->kode_program }} - {{ $program->nama_program }}
                    </option>
                  @endforeach
                  @if ($currentBidang !== null)
                    </optgroup>
                  @endif
                  @if ($currentUrusan !== null)
                    </optgroup>
                  @endif
                </select>
                @error('id_program')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row mb-7">
              <div class="col-md-3">
                <label class="fs-6 fw-semibold form-label mt-3">
                  <span class="required">Kode Kegiatan</span>
                </label>
              </div>
              <div class="col-md-9">
                <input type="text" name="kode_kegiatan" class="form-control form-control-solid @error('kode_kegiatan') is-invalid @enderror"
                  value="{{ old('kode_kegiatan', $kegiatan->kode_kegiatan) }}" placeholder="Masukkan kode kegiatan" />
                @error('kode_kegiatan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row mb-7">
              <div class="col-md-3">
                <label class="fs-6 fw-semibold form-label mt-3">
                  <span class="required">Nama Kegiatan</span>
                </label>
              </div>
              <div class="col-md-9">
                <textarea name="nama_kegiatan" class="form-control form-control-solid @error('nama_kegiatan') is-invalid @enderror" rows="4"
                  placeholder="Masukkan nama kegiatan">{{ old('nama_kegiatan', $kegiatan->nama_kegiatan) }}</textarea>
                @error('nama_kegiatan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row mb-7">
              <div class="col-md-3">
                <label class="fs-6 fw-semibold form-label mt-3">Informasi</label>
              </div>
              <div class="col-md-9">
                <div class="bg-light-info rounded p-5">
                  <div class="d-flex align-items-center">
                    <i class="ki-outline ki-information-5 fs-2 text-info me-4"></i>
                    <div class="flex-grow-1">
                      <div class="fw-bold text-gray-800 fs-6 mb-1">ID Kegiatan: {{ $kegiatan->id }}</div>
                      <div class="text-muted fs-7">
                        Program: {{ $kegiatan->program->kode_program }} - {{ $kegiatan->program->nama_program }}<br>
                        Bidang Urusan: {{ $kegiatan->program->bidangUrusan->kode_bidang_urusan }} -
                        {{ $kegiatan->program->bidangUrusan->nama_bidang_urusan }}<br>
                        Urusan: {{ $kegiatan->program->bidangUrusan->urusan->kode_urusan }} -
                        {{ $kegiatan->program->bidangUrusan->urusan->nama_urusan }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="separator mb-6"></div>

            <div class="d-flex justify-content-end">
              <button type="button" class="btn btn-light me-5" onclick="window.history.back()">
                Batal
              </button>
              <button type="submit" class="btn btn-primary" data-kt-kegiatan-edit-action="submit">
                <span class="indicator-label">Simpan Perubahan</span>
                <span class="indicator-progress">Menyimpan...
                  <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const form = document.querySelector('#kt_kegiatan_edit_form');
      const submitButton = form.querySelector('[data-kt-kegiatan-edit-action="submit"]');

      // Initialize Select2
      $('[data-control="select2"]').select2();

      // Form validation
      const validator = FormValidation.formValidation(form, {
        fields: {
          id_program: {
            validators: {
              notEmpty: {
                message: 'Program wajib dipilih'
              }
            }
          },
          kode_kegiatan: {
            validators: {
              notEmpty: {
                message: 'Kode kegiatan wajib diisi'
              },
              stringLength: {
                max: 20,
                message: 'Kode kegiatan maksimal 20 karakter'
              }
            }
          },
          nama_kegiatan: {
            validators: {
              notEmpty: {
                message: 'Nama kegiatan wajib diisi'
              },
              stringLength: {
                max: 500,
                message: 'Nama kegiatan maksimal 500 karakter'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap: new FormValidation.plugins.Bootstrap5({
            rowSelector: '.row',
            eleInvalidClass: '',
            eleValidClass: ''
          })
        }
      });

      // Handle form submission
      submitButton.addEventListener('click', function(e) {
        e.preventDefault();

        if (validator) {
          validator.validate().then(function(status) {
            if (status == 'Valid') {
              Swal.fire({
                text: "Anda yakin ingin menyimpan perubahan?",
                icon: "question",
                showCancelButton: true,
                buttonsStyling: false,
                confirmButtonText: "Ya, simpan!",
                cancelButtonText: "Tidak, batalkan",
                customClass: {
                  confirmButton: "btn fw-bold btn-primary",
                  cancelButton: "btn fw-bold btn-active-light-primary"
                }
              }).then(function(result) {
                if (result.value) {
                  submitButton.setAttribute('data-kt-indicator', 'on');
                  submitButton.disabled = true;

                  setTimeout(function() {
                    form.submit();
                  }, 1000);
                }
              });
            }
          });
        }
      });

      // SweetAlert for flash messages
      @if (session('success'))
        Swal.fire({
          text: "{{ session('success') }}",
          icon: "success",
          buttonsStyling: false,
          confirmButtonText: "OK",
          customClass: {
            confirmButton: "btn fw-bold btn-primary",
          }
        });
      @endif

      @if (session('error'))
        Swal.fire({
          text: "{{ session('error') }}",
          icon: "error",
          buttonsStyling: false,
          confirmButtonText: "OK",
          customClass: {
            confirmButton: "btn fw-bold btn-primary",
          }
        });
      @endif

      @if ($errors->any())
        Swal.fire({
          title: "Terdapat kesalahan!",
          html: "@foreach ($errors->all() as $error)<div>{{ $error }}</div>@endforeach",
          icon: "error",
          buttonsStyling: false,
          confirmButtonText: "OK",
          customClass: {
            confirmButton: "btn fw-bold btn-primary",
          }
        });
      @endif
    });
  </script>
@endsection
