<script>
  const DOM = {
    table: null,
    searchInput: null,
    addForm: null,
    addSubmitButton: null,
    masterCheckbox: null,
    checkboxes: null,
    selectedToolbar: null,
    baseToolbar: null,
    selectedCount: null,
    bulkDeleteBtn: null,
    rekeningError: null,
    tipeRadios: null,
    kelompokSelect: null
  };

  document.addEventListener("DOMContentLoaded", function() {
    // Initialize cached DOM elements
    DOM.table = $('#kt_standar_harga_table');
    DOM.searchInput = document.getElementById('kt_datatable_search_input');
    DOM.addForm = document.getElementById('kt_modal_add_standar_harga_form');
    DOM.addSubmitButton = document.getElementById('kt_modal_add_standar_harga_submit');
    DOM.masterCheckbox = document.querySelector('#kt_standar_harga_table thead input[type="checkbox"]');
    DOM.checkboxes = document.querySelectorAll('#kt_standar_harga_table tbody input[type="checkbox"]');
    DOM.selectedToolbar = document.querySelector('[data-kt-customer-table-toolbar="selected"]');
    DOM.baseToolbar = document.querySelector('[data-kt-customer-table-toolbar="base"]');
    DOM.selectedCount = document.querySelector('[data-kt-customer-table-select="selected_count"]');
    DOM.bulkDeleteBtn = document.getElementById('bulk_delete_btn');
    DOM.rekeningError = document.getElementById('rekening-error');
    DOM.tipeRadios = document.querySelectorAll('.tipe-standar-harga-radio');
    DOM.kelompokSelect = document.getElementById('kelompok_select');

    // Initialize Repeater
    $('#kt_rekening_repeater').repeater({
      initEmpty: false,

      defaultValues: {
        'id_akun': ''
      },

      show: function() {
        $(this).slideDown();

        // Re-init select2 untuk row baru
        $(this).find('[data-kt-repeater="select2"]').select2({
          placeholder: "Pilih rekening belanja",
          allowClear: true
        });
      },

      hide: function(deleteElement) {
        // Cek jika hanya ada 1 row tersisa
        const rowCount = $('#kt_rekening_repeater [data-repeater-item]').length;

        if (rowCount <= 1) {
          Swal.fire({
            icon: 'warning',
            title: 'Tidak dapat menghapus',
            text: 'Minimal harus ada satu rekening belanja',
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
        // Init select2 untuk row pertama
        $('[data-kt-repeater="select2"]').select2({
          placeholder: "Pilih rekening belanja",
          allowClear: true
        });
      }
    });

    // Initialize DataTable
    var tableInstance = DOM.table.DataTable({
      responsive: true,
      searchDelay: 500,
      processing: true,
      serverSide: false,
      columnDefs: [{
          targets: [0],
          orderable: false,
          className: 'text-center'
        },
        {
          targets: [7],
          orderable: false,
          className: 'text-end'
        }
      ]
    });

    // Search functionality
    if (DOM.searchInput) {
      DOM.searchInput.addEventListener('keyup', function() {
        tableInstance.search(this.value).draw();
      });
    }

    // AJAX load kelompok berdasarkan tipe
    DOM.tipeRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        const tipe = this.value;

        if (tipe) {
          DOM.kelompokSelect.disabled = true;
          DOM.kelompokSelect.innerHTML = '<option value="">Loading...</option>';

          $.ajax({
            url: "{{ route('kelompok_satuan_harga.get-by-tipe') }}",
            method: 'GET',
            data: {
              tipe: tipe
            },
            success: function(response) {
              if (response.success && response.data) {
                DOM.kelompokSelect.innerHTML = '<option value="">Pilih Kelompok</option>';

                response.data.forEach(function(kelompok) {
                  const option = document.createElement('option');
                  option.value = kelompok.id;
                  option.textContent = kelompok.kode_kelompok_standar_harga + ' - ' + kelompok.nama_kelompok_standar_harga;
                  DOM.kelompokSelect.appendChild(option);
                });

                DOM.kelompokSelect.disabled = false;

                if (response.data.length === 0) {
                  DOM.kelompokSelect.innerHTML = '<option value="">Tidak ada kelompok untuk tipe ' + tipe + '</option>';

                  Swal.fire({
                    icon: 'info',
                    title: 'Tidak ada kelompok',
                    text: 'Belum ada kelompok standar harga untuk tipe ' + tipe +
                      '. Silakan tambahkan kelompok terlebih dahulu.',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: {
                      confirmButton: "btn btn-primary"
                    }
                  });
                }
              }
            },
            error: function() {
              DOM.kelompokSelect.innerHTML = '<option value="">Gagal memuat data</option>';

              Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal memuat data kelompok',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                  confirmButton: "btn btn-primary"
                }
              });
            }
          });
        }
      });
    });

    // Session Messages
    @if (session('success'))
      Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('success') }}",
        confirmButtonText: 'OK',
        buttonsStyling: false,
        customClass: {
          confirmButton: "btn btn-primary"
        }
      });
    @endif

    @if (session('error'))
      Swal.fire({
        icon: 'error',
        title: 'Gagal',
        text: "{{ session('error') }}",
        confirmButtonText: 'OK',
        buttonsStyling: false,
        customClass: {
          confirmButton: "btn btn-primary"
        }
      });
    @endif

    // Event delegation untuk delete buttons
    DOM.table.on('click', '.delete-btn', function(e) {
      e.preventDefault();
      const form = $(this).closest('form');
      const name = $(this).data('name');

      Swal.fire({
        title: 'Apakah Anda yakin?',
        html: `Data standar harga <strong>"${name}"</strong> akan dihapus!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        buttonsStyling: false,
        customClass: {
          confirmButton: "btn btn-danger",
          cancelButton: "btn btn-secondary"
        }
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });

    // Event delegation untuk remove rekening buttons
    $(document).on('click', '.btn-remove-rekening', function(e) {
      e.preventDefault();
      const form = $(this).closest('form');
      const rekening = $(this).data('rekening');

      Swal.fire({
        title: 'Hapus Rekening?',
        html: `Rekening <strong>"${rekening}"</strong> akan dihapus dari standar harga ini.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        buttonsStyling: false,
        customClass: {
          confirmButton: "btn btn-danger",
          cancelButton: "btn btn-secondary"
        }
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });

    // Bulk selection functionality
    function updateToolbar() {
      const checkedBoxes = document.querySelectorAll('#kt_standar_harga_table tbody input[type="checkbox"]:checked');

      if (checkedBoxes.length > 0) {
        DOM.selectedCount.textContent = checkedBoxes.length;
        DOM.baseToolbar?.classList.add('d-none');
        DOM.selectedToolbar?.classList.remove('d-none');
      } else {
        DOM.baseToolbar?.classList.remove('d-none');
        DOM.selectedToolbar?.classList.add('d-none');
      }
    }

    // Master checkbox
    if (DOM.masterCheckbox) {
      DOM.masterCheckbox.addEventListener('change', function() {
        DOM.checkboxes.forEach(checkbox => {
          checkbox.checked = this.checked;
        });
        updateToolbar();
      });
    }

    // Individual checkboxes
    DOM.checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        updateToolbar();
        const checkedBoxes = document.querySelectorAll(
          '#kt_standar_harga_table tbody input[type="checkbox"]:checked');
        if (DOM.masterCheckbox) {
          DOM.masterCheckbox.checked = checkedBoxes.length === DOM.checkboxes.length;
          DOM.masterCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < DOM.checkboxes
            .length;
        }
      });
    });

    // Bulk delete
    DOM.bulkDeleteBtn?.addEventListener('click', function() {
      const checkedBoxes = document.querySelectorAll(
        '#kt_standar_harga_table tbody input[type="checkbox"]:checked');

      if (checkedBoxes.length === 0) {
        Swal.fire({
          icon: 'info',
          title: 'Tidak ada data dipilih',
          text: 'Pilih minimal satu standar harga untuk dihapus.',
          confirmButtonText: 'OK',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
        return;
      }

      Swal.fire({
        title: 'Apakah Anda yakin?',
        html: `Anda akan menghapus <strong>${checkedBoxes.length}</strong> data standar harga terpilih!`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        buttonsStyling: false,
        customClass: {
          confirmButton: "btn btn-danger",
          cancelButton: "btn btn-secondary"
        }
      }).then((result) => {
        if (result.isConfirmed) {
          const ids = Array.from(checkedBoxes).map(cb => cb.value);

          const form = document.createElement('form');
          form.method = 'POST';
          form.action = '{{ route('standar_harga.bulk-delete') }}';

          const csrfToken = document.createElement('input');
          csrfToken.type = 'hidden';
          csrfToken.name = '_token';
          csrfToken.value = '{{ csrf_token() }}';
          form.appendChild(csrfToken);

          ids.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
          });

          document.body.appendChild(form);
          form.submit();
        }
      });
    });

    // AJAX Form Submission
    if (DOM.addForm && DOM.addSubmitButton) {
      DOM.addForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Check tipe standar harga
        const tipeChecked = document.querySelector('.tipe-standar-harga-radio:checked');
        if (!tipeChecked) {
          document.getElementById('tipe-standar-harga-error')?.classList.remove('d-none');
          Swal.fire({
            icon: 'error',
            title: 'Validasi gagal',
            text: 'Pilih tipe standar harga terlebih dahulu!',
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: {
              confirmButton: "btn btn-primary"
            }
          });
          return;
        }

        // Check if at least one rekening is selected from repeater
        const rekeningSelects = document.querySelectorAll('[data-kt-repeater="select2"]');
        let hasValidRekening = false;

        rekeningSelects.forEach(select => {
          if (select.value && select.value !== '') {
            hasValidRekening = true;
          }
        });

        if (!hasValidRekening) {
          DOM.rekeningError?.classList.remove('d-none');
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

        // Show loading state
        DOM.addSubmitButton.setAttribute('data-kt-indicator', 'on');
        DOM.addSubmitButton.disabled = true;

        // Clear previous errors
        DOM.addForm.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        DOM.addForm.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

        // Submit via AJAX
        $.ajax({
          url: "{{ route('standar_harga.store') }}",
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            $('#kt_modal_add_standar_harga').modal('hide');
            DOM.addForm.reset();
            DOM.kelompokSelect.disabled = true;
            DOM.kelompokSelect.innerHTML = '<option value="">Pilih tipe terlebih dahulu</option>';

            // Reset repeater ke 1 row
            $('#kt_rekening_repeater').repeater('setList', [{
              id_akun: ''
            }]);

            Swal.fire({
              icon: 'success',
              title: 'Berhasil',
              text: response.message || 'Data berhasil disimpan!',
              confirmButtonText: 'OK',
              buttonsStyling: false,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            }).then(() => {
              location.reload();
            });
          },
          error: function(xhr) {
            if (xhr.status === 422) {
              const errors = xhr.responseJSON.errors;
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

              Swal.fire({
                icon: 'error',
                title: 'Validasi gagal',
                text: 'Periksa kembali form Anda',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                  confirmButton: "btn btn-primary"
                }
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                  confirmButton: "btn btn-primary"
                }
              });
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
      $('#kt_modal_add_standar_harga').modal('show');
    @endif
  });
</script>
