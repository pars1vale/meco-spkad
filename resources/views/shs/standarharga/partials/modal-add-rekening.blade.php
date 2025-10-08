{{-- Modal Tambah Rekening dengan Repeater --}}
@foreach ($data as $item)
  <div class="modal fade" id="modal_add_rekening_{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-700px">
      <div class="modal-content">
        <form action="{{ route('standar_harga.add-rekening', $item->id) }}" method="POST" class="form-add-rekening"
          data-standar-id="{{ $item->id }}">
          @csrf
          <div class="modal-header">
            <h2 class="fw-bold">Tambah Rekening Belanja</h2>
            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
              <i class="ki-outline ki-cross fs-1"></i>
            </div>
          </div>

          <div class="modal-body py-10 px-lg-17">
            <div class="mb-7">
              <h5>{{ $item->nama_standar_harga }}</h5>
              <p class="text-muted mb-1">Kode: {{ $item->kode_standar_harga }}</p>
              <p class="text-muted">Rekening saat ini: <strong>{{ $item->rekeningBelanja->count() }}</strong></p>
            </div>

            <div class="separator my-5"></div>

            @php
              $existingRekeningIds = $item->rekeningBelanja->pluck('id')->toArray();
              $availableAkun = $akun->whereNotIn('id', $existingRekeningIds);
            @endphp

            @if ($availableAkun->isEmpty())
              <div class="alert alert-info d-flex align-items-center">
                <i class="ki-outline ki-information-5 fs-2 me-3"></i>
                <div>
                  <h5 class="mb-1">Semua rekening sudah ditambahkan</h5>
                  <span>Tidak ada rekening belanja yang tersedia untuk ditambahkan.</span>
                </div>
              </div>
            @else
              <div class="fv-row">
                <label class="required fs-6 fw-semibold mb-2">Rekening Belanja</label>
                <div class="form-text mb-3">Tambahkan satu atau lebih rekening belanja baru</div>

                <!--begin::Repeater-->
                <div id="kt_rekening_repeater_add_{{ $item->id }}">
                  <div class="form-group">
                    <div data-repeater-list="rekening_belanja">
                      <div data-repeater-item>
                        <div class="form-group row align-items-center mb-5">
                          <div class="col-md-10">
                            <select class="form-select form-select-solid" data-kt-repeater="select2" data-placeholder="Pilih rekening belanja"
                              name="id_akun" required>
                              <option value="">Pilih Rekening</option>
                              @foreach ($availableAkun as $ak)
                                <option value="{{ $ak->id }}">{{ $ak->kode_akun }} - {{ $ak->nama_akun }}</option>
                              @endforeach
                            </select>
                          </div>
                          <div class="col-md-2">
                            <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-icon btn-light-danger">
                              <i class="ki-outline ki-trash fs-3"></i>
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                      <i class="ki-outline ki-plus fs-3"></i>
                      Tambah Rekening
                    </a>
                  </div>
                </div>
                <!--end::Repeater-->
                <div class="invalid-feedback d-none" id="rekening-add-error-{{ $item->id }}">
                  Minimal satu rekening belanja harus dipilih
                </div>
              </div>
            @endif
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modal_detail_{{ $item->id }}">
              Kembali
            </button>
            <button type="submit" class="btn btn-primary btn-submit-add-rekening" {{ $availableAkun->isEmpty() ? 'disabled' : '' }}>
              <span class="indicator-label">
                <i class="ki-outline ki-check fs-2"></i>
                Simpan
              </span>
              <span class="indicator-progress">
                Menyimpan...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
              </span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
@endforeach

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Initialize repeater untuk setiap modal add rekening
    @foreach ($data as $item)
      @php
        $existingRekeningIds = $item->rekeningBelanja->pluck('id')->toArray();
        $availableAkun = $akun->whereNotIn('id', $existingRekeningIds);
      @endphp

      @if (!$availableAkun->isEmpty())
        $('#kt_rekening_repeater_add_{{ $item->id }}').repeater({
          initEmpty: false,

          defaultValues: {
            'id_akun': ''
          },

          show: function() {
            $(this).slideDown();

            $(this).find('[data-kt-repeater="select2"]').select2({
              placeholder: "Pilih rekening belanja",
              allowClear: true,
              dropdownParent: $('#modal_add_rekening_{{ $item->id }}')
            });
          },

          hide: function(deleteElement) {
            const rowCount = $('#kt_rekening_repeater_add_{{ $item->id }} [data-repeater-item]').length;

            if (rowCount <= 1) {
              Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: 'Anda harus menambahkan minimal satu rekening',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                  confirmButton: "btn btn-primary"
                }
              });
              return;
            }

            $(this).slideUp(deleteElement);
          },

          ready: function() {
            $('[data-kt-repeater="select2"]').select2({
              placeholder: "Pilih rekening belanja",
              allowClear: true,
              dropdownParent: $('#modal_add_rekening_{{ $item->id }}')
            });
          }
        });

        // Form validation untuk modal {{ $item->id }}
        const form_{{ $item->id }} = document.querySelector('[data-standar-id="{{ $item->id }}"]');
        const submitBtn_{{ $item->id }} = form_{{ $item->id }}.querySelector('.btn-submit-add-rekening');
        const errorDiv_{{ $item->id }} = document.getElementById('rekening-add-error-{{ $item->id }}');

        form_{{ $item->id }}.addEventListener('submit', function(e) {
          e.preventDefault();

          // Validasi: cek apakah ada rekening yang dipilih
          const selects = this.querySelectorAll('[data-kt-repeater="select2"]');
          let hasValidRekening = false;

          selects.forEach(select => {
            if (select.value && select.value !== '') {
              hasValidRekening = true;
            }
          });

          if (!hasValidRekening) {
            errorDiv_{{ $item->id }}?.classList.remove('d-none');

            Swal.fire({
              icon: 'error',
              title: 'Validasi gagal',
              text: 'Minimal satu rekening belanja harus dipilih!',
              confirmButtonText: 'OK',
              buttonsStyling: false,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            });
            return;
          }

          errorDiv_{{ $item->id }}?.classList.add('d-none');

          // Show loading
          submitBtn_{{ $item->id }}.setAttribute('data-kt-indicator', 'on');
          submitBtn_{{ $item->id }}.disabled = true;

          // Submit form
          this.submit();
        });
      @endif
    @endforeach
  });
</script>
