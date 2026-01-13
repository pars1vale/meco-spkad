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

    // ============================================
    // INITIALIZE SELECT2 IN MODAL
    // ============================================
    $('#kt_modal_add_standar_harga').on('shown.bs.modal', function() {
      console.log('Modal ditampilkan - inisialisasi Select2');

      // Init semua select2 yang belum diinisialisasi
      $(this).find('select[data-control="select2"]').each(function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
          console.log('Init Select2 untuk:', $(this).attr('name'));

          $(this).select2({
            placeholder: $(this).data('placeholder') || 'Pilih data',
            dropdownParent: $('#kt_modal_add_standar_harga'),
            allowClear: true,
            width: '100%'
          });
        }
      });
    });

    // Reset Select2 saat modal ditutup
    $('#kt_modal_add_standar_harga').on('hidden.bs.modal', function() {
      console.log('Modal ditutup - reset form');

      // Reset form
      if (DOM.addForm) {
        DOM.addForm.reset();
      }

      // Reset kelompok select
      if (DOM.kelompokSelect) {
        $(DOM.kelompokSelect).val('').trigger('change');
        DOM.kelompokSelect.disabled = true;
        DOM.kelompokSelect.innerHTML = '<option></option>';
      }

      // Reset tipe radio
      DOM.tipeRadios.forEach(radio => {
        radio.checked = false;
      });

      // Reset repeater ke 1 row
      $('#kt_rekening_repeater').repeater('setList', [{
        id_akun: ''
      }]);

      // Destroy dan reinit select2 di repeater
      $('.rekening-select').each(function() {
        if ($(this).hasClass('select2-hidden-accessible')) {
          $(this).select2('destroy');
        }
      });

      // Clear error messages
      clearFormErrors();
    });

    // ============================================
    // INITIALIZE REPEATER WITH SELECT2 FIX
    // ============================================
    $('#kt_rekening_repeater').repeater({
      initEmpty: false,

      defaultValues: {
        'id_akun': ''
      },

      show: function() {
        $(this).slideDown();

        // CRITICAL FIX: Init Select2 untuk row baru
        const newSelect = $(this).find('.rekening-select');

        // Destroy dulu jika sudah ada
        if (newSelect.hasClass('select2-hidden-accessible')) {
          newSelect.select2('destroy');
        }

        // Init dengan konfigurasi yang benar
        newSelect.select2({
          placeholder: "Pilih rekening belanja",
          allowClear: true,
          dropdownParent: $('#kt_modal_add_standar_harga'),
          width: '100%'
        });

        console.log('New row added - Select2 initialized');
      },

      hide: function(deleteElement) {
        const rowCount = $('#kt_rekening_repeater [data-repeater-item]').length;

        if (rowCount <= 1) {
          toastr.warning('Minimal harus ada satu rekening belanja', 'TIDAK DAPAT MENGHAPUS');
          return;
        }

        // Destroy Select2 sebelum remove
        $(this).find('.rekening-select').select2('destroy');
        $(this).slideUp(deleteElement);
        console.log('Row removed');
      },

      ready: function() {
        // Init Select2 untuk row pertama
        $('.rekening-select').each(function() {
          if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2({
              placeholder: "Pilih rekening belanja",
              allowClear: true,
              dropdownParent: $('#kt_modal_add_standar_harga'),
              width: '100%'
            });
          }
        });
        console.log('Repeater ready - Select2 initialized for initial rows');
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

    // Configure Toastr options
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toastr-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    };

    // Session Messages dengan Toaster
    const sessionMessages = document.querySelectorAll('#session-messages div');
    sessionMessages.forEach(msg => {
      const type = msg.dataset.type;
      const message = msg.dataset.message;

      if (type === 'error') {
        toastr.error(message, "GAGAL");
      } else if (type === 'success') {
        toastr.success(message, "BERHASIL");
      } else {
        toastr.info(message);
      }
    });

    // AJAX load kelompok berdasarkan tipe
    DOM.tipeRadios.forEach(radio => {
      radio.addEventListener('change', function() {
        const tipe = this.value;

        if (tipe) {
          DOM.kelompokSelect.disabled = true;
          $(DOM.kelompokSelect).html('<option>Loading...</option>').trigger('change');

          $.ajax({
            url: "{{ route('kelompok_satuan_harga.get-by-tipe') }}",
            method: 'GET',
            data: {
              tipe: tipe
            },
            success: function(response) {
              if (response.success && response.data) {
                let options = '<option></option>';

                response.data.forEach(function(kelompok) {
                  options +=
                    `<option value="${kelompok.id}">${kelompok.kode_kelompok_standar_harga} - ${kelompok.nama_kelompok_standar_harga}</option>`;
                });

                $(DOM.kelompokSelect).html(options).trigger('change');
                DOM.kelompokSelect.disabled = false;

                if (response.data.length === 0) {
                  $(DOM.kelompokSelect).html('<option>Tidak ada kelompok untuk tipe ' + tipe + '</option>').trigger('change');

                  toastr.info('Belum ada kelompok standar harga untuk tipe ' + tipe +
                    '. Silakan tambahkan kelompok terlebih dahulu.', 'TIDAK ADA KELOMPOK');
                }
              }
            },
            error: function() {
              $(DOM.kelompokSelect).html('<option>Gagal memuat data</option>').trigger('change');
              toastr.error('Gagal memuat data kelompok', 'GAGAL');
            }
          });
        }
      });
    });

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
        const checkedBoxes = document.querySelectorAll('#kt_standar_harga_table tbody input[type="checkbox"]:checked');
        if (DOM.masterCheckbox) {
          DOM.masterCheckbox.checked = checkedBoxes.length === DOM.checkboxes.length;
          DOM.masterCheckbox.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < DOM.checkboxes.length;
        }
      });
    });

    // Bulk delete
    DOM.bulkDeleteBtn?.addEventListener('click', function() {
      const checkedBoxes = document.querySelectorAll('#kt_standar_harga_table tbody input[type="checkbox"]:checked');

      if (checkedBoxes.length === 0) {
        toastr.info('Pilih minimal satu standar harga untuk dihapus.', 'INFORMASI');
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

    // Clear form errors function
    function clearFormErrors() {
      if (!DOM.addForm) return;

      DOM.addForm.querySelectorAll('.is-invalid').forEach(el => {
        el.classList.remove('is-invalid');
      });

      DOM.addForm.querySelectorAll('.invalid-feedback').forEach(el => {
        el.textContent = '';
        el.classList.add('d-none');
      });

      if (DOM.rekeningError) {
        DOM.rekeningError.classList.add('d-none');
      }

      const tipeError = document.getElementById('tipe-standar-harga-error');
      if (tipeError) {
        tipeError.classList.add('d-none');
      }
    }

    // AJAX Form Submission
    if (DOM.addForm && DOM.addSubmitButton) {
      DOM.addForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        // Check tipe standar harga
        const tipeChecked = document.querySelector('.tipe-standar-harga-radio:checked');
        if (!tipeChecked) {
          document.getElementById('tipe-standar-harga-error')?.classList.remove('d-none');
          toastr.error('Pilih tipe standar harga terlebih dahulu!', 'VALIDASI GAGAL');
          return;
        }

        // Check rekening dari Select2
        const rekeningSelects = document.querySelectorAll('.rekening-select');
        let hasValidRekening = false;

        rekeningSelects.forEach(select => {
          const value = $(select).val(); // Gunakan jQuery untuk get value Select2
          if (value && value !== '') {
            hasValidRekening = true;
          }
        });

        if (!hasValidRekening) {
          DOM.rekeningError?.classList.remove('d-none');
          toastr.error('Minimal satu rekening belanja harus dipilih!', 'VALIDASI GAGAL');
          return;
        }

        // Show loading state
        DOM.addSubmitButton.setAttribute('data-kt-indicator', 'on');
        DOM.addSubmitButton.disabled = true;

        // Clear previous errors
        clearFormErrors();

        // Submit via AJAX
        $.ajax({
          url: "{{ route('standar_harga.store') }}",
          method: 'POST',
          data: formData,
          processData: false,
          contentType: false,
          success: function(response) {
            $('#kt_modal_add_standar_harga').modal('hide');

            toastr.success(response.message || 'Data berhasil disimpan!', 'BERHASIL', {
              timeOut: 2000,
              onHidden: function() {
                location.reload();
              }
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
                    feedback.classList.remove('d-none');
                  }
                }
              });

              toastr.error('Periksa kembali form Anda', 'VALIDASI GAGAL');
            } else {
              toastr.error(xhr.responseJSON?.message || 'Terjadi kesalahan saat menyimpan data', 'GAGAL');
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
