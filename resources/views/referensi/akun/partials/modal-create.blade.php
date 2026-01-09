<!-- Modal Tambah Akun -->
<div class="modal fade" id="kt_modal_add_akun" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-900px">
    <div class="modal-content">
      <form class="form" id="kt_modal_add_akun_form">
        @csrf
        <div class="modal-header" id="kt_modal_add_akun_header">
          <h2 class="fw-bold">Tambah Akun</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>

        <div class="modal-body py-10 px-lg-17">
          <div class="scroll-y me-n7 pe-7" id="kt_modal_add_akun_scroll" style="max-height: 500px;">

            <!-- Kode Akun -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Kode Akun</label>
              <input type="text" class="form-control form-control-solid" placeholder="Masukkan kode akun" name="kode_akun" maxlength="50"
                required />
              <div class="invalid-feedback"></div>
              <div class="form-text">Kode akun harus unik</div>
            </div>

            <!-- Nama Akun -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Akun</label>
              <textarea class="form-control form-control-solid" rows="3" placeholder="Masukkan nama akun" name="nama_akun" required></textarea>
              <div class="invalid-feedback"></div>
            </div>

            <!-- Keterangan Akun -->
            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Keterangan Akun</label>
              <textarea class="form-control form-control-solid" rows="3" placeholder="Masukkan keterangan akun (opsional)" name="keterangan_akun"></textarea>
              <div class="invalid-feedback"></div>
            </div>

            <!-- Separator -->
            <div class="separator separator-dashed my-7"></div>

            <!-- Tipe Akun Utama (Mutual Exclusive) -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Tipe Akun Utama</label>
              <div class="form-text mb-3">Pilih salah satu tipe akun utama</div>

              <div class="row">
                <!-- Pendapatan Switch -->
                <div class="col-md-4 mb-3">
                  <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="pendapatanSwitch" name="is_pendapatan" />
                    <label class="form-check-label fw-bold" for="pendapatanSwitch">
                      Pendapatan
                    </label>
                  </div>
                </div>

                <!-- Belanja Switch -->
                <div class="col-md-4 mb-3">
                  <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="belanjaSwitch" name="is_bl" />
                    <label class="form-check-label fw-bold" for="belanjaSwitch">
                      Belanja
                    </label>
                  </div>
                </div>

                <!-- Pembiayaan Switch -->
                <div class="col-md-4 mb-3">
                  <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input akun-type-switch" type="checkbox" value="1" id="pembiayaanSwitch" name="is_pembiayaan" />
                    <label class="form-check-label fw-bold" for="pembiayaanSwitch">
                      Pembiayaan
                    </label>
                  </div>
                </div>
              </div>

              <div class="invalid-feedback d-none" id="tipe-akun-error">
                Anda harus memilih salah satu tipe akun utama
              </div>
            </div>

            <!-- Separator -->
            <div class="separator separator-dashed my-7"></div>

            <!-- Kategori Khusus (Multiple Selection) -->
            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-3">Kategori Khusus (Opsional)</label>
              <div class="form-text mb-4">Pilih satu atau lebih kategori khusus yang sesuai</div>

              <div class="d-flex flex-wrap gap-2">
                <!-- BOS -->
                <button type="button" class="btn btn-sm btn-light-primary kategori-toggle" data-field="is_bos">
                  <i class="ki-duotone ki-check-circle fs-3 d-none">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  BOS
                </button>
                <input type="hidden" name="is_bos" value="0" class="kategori-input" data-field="is_bos">

                <!-- Gaji ASN -->
                <button type="button" class="btn btn-sm btn-light-primary kategori-toggle" data-field="is_gaji_asn">
                  <i class="ki-duotone ki-check-circle fs-3 d-none">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  Gaji ASN
                </button>
                <input type="hidden" name="is_gaji_asn" value="0" class="kategori-input" data-field="is_gaji_asn">

                <!-- Barang & Jasa -->
                <button type="button" class="btn btn-sm btn-light-primary kategori-toggle" data-field="is_barjas">
                  <i class="ki-duotone ki-check-circle fs-3 d-none">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  Barang & Jasa
                </button>
                <input type="hidden" name="is_barjas" value="0" class="kategori-input" data-field="is_barjas">

                <!-- BTT -->
                <button type="button" class="btn btn-sm btn-light-primary kategori-toggle" data-field="is_btt">
                  <i class="ki-duotone ki-check-circle fs-3 d-none">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  BTT
                </button>
                <input type="hidden" name="is_btt" value="0" class="kategori-input" data-field="is_btt">

                <!-- Hibah Uang -->
                <button type="button" class="btn btn-sm btn-light-warning kategori-toggle" data-field="is_hibah_uang">
                  <i class="ki-duotone ki-check-circle fs-3 d-none">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  Hibah Uang
                </button>
                <input type="hidden" name="is_hibah_uang" value="0" class="kategori-input" data-field="is_hibah_uang">

                <!-- Hibah Barang -->
                <button type="button" class="btn btn-sm btn-light-warning kategori-toggle" data-field="is_hibah_brg">
                  <i class="ki-duotone ki-check-circle fs-3 d-none">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  Hibah Barang
                </button>
                <input type="hidden" name="is_hibah_brg" value="0" class="kategori-input" data-field="is_hibah_brg">

                <!-- Bansos Uang -->
                <button type="button" class="btn btn-sm btn-light-success kategori-toggle" data-field="is_sosial_uang">
                  <i class="ki-duotone ki-check-circle fs-3 d-none">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  Bansos Uang
                </button>
                <input type="hidden" name="is_sosial_uang" value="0" class="kategori-input" data-field="is_sosial_uang">

                <!-- Bansos Barang -->
                <button type="button" class="btn btn-sm btn-light-success kategori-toggle" data-field="is_sosial_brg">
                  <i class="ki-duotone ki-check-circle fs-3 d-none">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  Bansos Barang
                </button>
                <input type="hidden" name="is_sosial_brg" value="0" class="kategori-input" data-field="is_sosial_brg">

                <!-- Subsidi -->
                <button type="button" class="btn btn-sm btn-light-info kategori-toggle" data-field="is_subsidi">
                  <i class="ki-duotone ki-check-circle fs-3 d-none">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  Subsidi
                </button>
                <input type="hidden" name="is_subsidi" value="0" class="kategori-input" data-field="is_subsidi">

                <!-- Bagi Hasil -->
                <button type="button" class="btn btn-sm btn-light-info kategori-toggle" data-field="is_bagi_hasil">
                  <i class="ki-duotone ki-check-circle fs-3 d-none">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  Bagi Hasil
                </button>
                <input type="hidden" name="is_bagi_hasil" value="0" class="kategori-input" data-field="is_bagi_hasil">

                <!-- Bunga -->
                <button type="button" class="btn btn-sm btn-light-secondary kategori-toggle" data-field="is_bunga">
                  <i class="ki-duotone ki-check-circle fs-3 d-none">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  Bunga
                </button>
                <input type="hidden" name="is_bunga" value="0" class="kategori-input" data-field="is_bunga">

                <!-- Modal Tanah -->
                <button type="button" class="btn btn-sm btn-light-danger kategori-toggle" data-field="is_modal_tanah">
                  <i class="ki-duotone ki-check-circle fs-3 d-none">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  Modal Tanah
                </button>
                <input type="hidden" name="is_modal_tanah" value="0" class="kategori-input" data-field="is_modal_tanah">

                <!-- Bankeu Umum -->
                <button type="button" class="btn btn-sm btn-light-primary kategori-toggle" data-field="is_bankeu_umum">
                  <i class="ki-duotone ki-check-circle fs-3 d-none">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  Bankeu Umum
                </button>
                <input type="hidden" name="is_bankeu_umum" value="0" class="kategori-input" data-field="is_bankeu_umum">

                <!-- Bankeu Khusus -->
                <button type="button" class="btn btn-sm btn-light-primary kategori-toggle" data-field="is_bankeu_khusus">
                  <i class="ki-duotone ki-check-circle fs-3 d-none">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  Bankeu Khusus
                </button>
                <input type="hidden" name="is_bankeu_khusus" value="0" class="kategori-input" data-field="is_bankeu_khusus">
              </div>
            </div>

          </div>
        </div>

        <div class="modal-footer flex-center">
          <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" id="kt_modal_add_akun_submit" class="btn btn-primary">
            <span class="indicator-label">Simpan</span>
            <span class="indicator-progress">Menyimpan...
              <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Cache DOM elements for modal
    const DOM = {
      akunTypeSwitches: document.querySelectorAll('.akun-type-switch'),
      tipeAkunError: document.getElementById('tipe-akun-error'),
      addForm: document.getElementById('kt_modal_add_akun_form'),
      addSubmitButton: document.getElementById('kt_modal_add_akun_submit'),
      kategoriToggles: document.querySelectorAll('.kategori-toggle')
    };

    // Switch logic untuk tipe akun utama (mutual exclusive)
    DOM.akunTypeSwitches.forEach(switchEl => {
      switchEl.addEventListener('change', function() {
        if (this.checked) {
          DOM.akunTypeSwitches.forEach(otherSwitch => {
            if (otherSwitch !== this) {
              otherSwitch.checked = false;
            }
          });
        }
        DOM.tipeAkunError?.classList.add('d-none');
      });
    });

    // Toggle buttons logic untuk kategori khusus (multiple selection)
    DOM.kategoriToggles.forEach(button => {
      button.addEventListener('click', function() {
        const field = this.getAttribute('data-field');
        const input = document.querySelector(`.kategori-input[data-field="${field}"]`);
        const icon = this.querySelector('i');

        // Toggle state
        if (input.value === '0') {
          input.value = '1';
          this.classList.remove('btn-light-primary', 'btn-light-warning', 'btn-light-success', 'btn-light-info', 'btn-light-secondary',
            'btn-light-danger');
          this.classList.add('btn-primary', 'btn-active-primary');
          icon.classList.remove('d-none');
        } else {
          input.value = '0';
          this.classList.remove('btn-primary', 'btn-active-primary');

          // Restore original color
          if (field.includes('hibah')) {
            this.classList.add('btn-light-warning');
          } else if (field.includes('sosial')) {
            this.classList.add('btn-light-success');
          } else if (field.includes('subsidi') || field.includes('bagi')) {
            this.classList.add('btn-light-info');
          } else if (field.includes('modal')) {
            this.classList.add('btn-light-danger');
          } else if (field.includes('bunga')) {
            this.classList.add('btn-light-secondary');
          } else {
            this.classList.add('btn-light-primary');
          }

          icon.classList.add('d-none');
        }
      });
    });

    // AJAX Form Submission
    if (DOM.addForm && DOM.addSubmitButton) {
      DOM.addForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Check if at least one switch is selected
        const hasTypeSelected = formData.get('is_pendapatan') || formData.get('is_bl') || formData.get('is_pembiayaan');

        if (!hasTypeSelected) {
          DOM.tipeAkunError?.classList.remove('d-none');
          toastr.error('Anda harus memilih salah satu tipe akun utama!', 'VALIDASI GAGAL');
          return;
        }

        // Show loading state
        DOM.addSubmitButton.setAttribute('data-kt-indicator', 'on');
        DOM.addSubmitButton.disabled = true;

        // Clear previous errors
        DOM.addForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        DOM.addForm.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

        // Submit via AJAX
        $.ajax({
          url: "{{ route('referensi.akun.store') }}",
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            $('#kt_modal_add_akun').modal('hide');
            DOM.addForm.reset();

            // Reset kategori buttons
            DOM.kategoriToggles.forEach(button => {
              const field = button.getAttribute('data-field');
              const input = document.querySelector(`.kategori-input[data-field="${field}"]`);
              const icon = button.querySelector('i');
              input.value = '0';
              button.classList.remove('btn-primary', 'btn-active-primary');
              icon.classList.add('d-none');
            });

            toastr.success(response.message || 'Data berhasil disimpan!', 'BERHASIL');

            setTimeout(() => {
              location.reload();
            }, 1000);
          },
          error: function(xhr) {
            if (xhr.status === 422) {
              const errors = xhr.responseJSON.errors;
              if (errors) {
                Object.keys(errors).forEach(field => {
                  const input = DOM.addForm.querySelector(`[name="${field}"]`);
                  if (input) {
                    input.classList.add('is-invalid');
                    const feedback = input.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                      feedback.textContent = errors[field][0];
                    }
                  }
                });
              }
              toastr.error(xhr.responseJSON.message || 'Periksa kembali form Anda', 'VALIDASI GAGAL');
            } else {
              toastr.error('Terjadi kesalahan saat menyimpan data', 'GAGAL');
            }
          },
          complete: function() {
            DOM.addSubmitButton.removeAttribute('data-kt-indicator');
            DOM.addSubmitButton.disabled = false;
          }
        });
      });
    }

    // Auto show modal if validation errors exist
    @if ($errors->any() && old('_token'))
      $('#kt_modal_add_akun').modal('show');
    @endif
  });
</script>
