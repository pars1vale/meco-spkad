  <script>
    document.getElementById('btnDoExportPdf').addEventListener('click', function() {
      const idSkpd = document.getElementById('export_pdf_id_skpd').value;
      const tahun = document.getElementById('export_pdf_tahun').value;

      if (!idSkpd) {
        alert('Silakan pilih SKPD terlebih dahulu');
        return;
      }

      const baseUrl = "{{ route('renja.export-pdf', ['id_skpd' => '__ID_SKPD__']) }}";
      const url = baseUrl.replace('__ID_SKPD__', idSkpd) + '?tahun_anggaran=' + encodeURIComponent(tahun);

      window.open(url, '_blank');
    });

    document.addEventListener("DOMContentLoaded", function() {
      // Store data globally
      let subKegiatanData = [];
      let sumberDanaCounter = 0;

      // Sumber Dana dari database
      const sumberDanaList = @json($sumberdana);

      // === AJAX Load Sub Kegiatan ketika SKPD dipilih ===
      $('#select_skpd').on('change', function() {
        const idSkpd = $(this).val();

        if (!idSkpd) {
          $('#sub_kegiatan_container').hide();
          $('#select_sub_kegiatan').html('<option value="">Pilih Sub Kegiatan</option>');
          $('#detail_sub_kegiatan').addClass('d-none');
          $('#indikator_section').addClass('d-none');
          $('#total_sub_kegiatan').text('0');
          return;
        }

        $('#loading_sub_kegiatan').removeClass('d-none');
        $('#sub_kegiatan_container').hide();
        $('#detail_sub_kegiatan').addClass('d-none');
        $('#indikator_section').addClass('d-none');
        $('#select_sub_kegiatan').html('<option value="">Memuat...</option>');

        $.ajax({
          url: '{{ route('sub-kegiatan') }}',
          method: 'GET',
          data: {
            id_skpd: idSkpd,
            tahun_anggaran: 2025
          },
          success: function(response) {
            $('#loading_sub_kegiatan').addClass('d-none');

            if (response.success && response.data.length > 0) {
              subKegiatanData = response.data;

              let groupedData = {};
              response.data.forEach(item => {
                const bidangKey = item.kode_bidang_urusan;
                if (!groupedData[bidangKey]) {
                  groupedData[bidangKey] = {
                    nama: item.nama_bidang_urusan,
                    items: []
                  };
                }
                groupedData[bidangKey].items.push(item);
              });

              let options = '<option value="">Pilih Sub Kegiatan</option>';
              Object.keys(groupedData).sort().forEach(key => {
                const group = groupedData[key];
                options += `<optgroup label="${key} - ${group.nama}">`;

                group.items.forEach(item => {
                  options += `<option value="${item.id_sub_kegiatan}" 
                                    data-bidang="${item.kode_bidang_urusan} - ${item.nama_bidang_urusan}"
                                    data-program="${item.kode_program} - ${item.nama_program}"
                                    data-kegiatan="${item.kode_kegiatan} - ${item.nama_kegiatan}"
                                    data-subkeg="${item.kode_sub_kegiatan} - ${item.nama_sub_kegiatan}">
                              ${item.kode_sub_kegiatan} - ${item.nama_sub_kegiatan}
                            </option>`;
                });

                options += '</optgroup>';
              });

              $('#select_sub_kegiatan').html(options);
              $('#total_sub_kegiatan').text(response.count);
              $('#sub_kegiatan_container').show();
            } else {
              $('#select_sub_kegiatan').html('<option value="">Tidak ada data</option>');
              $('#total_sub_kegiatan').text('0');

              Swal.fire({
                icon: 'info',
                title: 'Tidak ada data',
                text: 'Tidak ada sub kegiatan untuk SKPD yang dipilih',
                confirmButtonText: 'OK',
                buttonsStyling: false,
                customClass: {
                  confirmButton: "btn btn-primary"
                }
              });
            }
          },
          error: function(xhr, status, error) {
            $('#loading_sub_kegiatan').addClass('d-none');
            $('#select_sub_kegiatan').html('<option value="">Error memuat data</option>');

            Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: xhr.responseJSON?.message || 'Terjadi kesalahan saat mengambil data sub kegiatan',
              confirmButtonText: 'OK',
              buttonsStyling: false,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            });

            console.error('Error:', xhr.responseJSON || error);
          }
        });
      });

      $('#select_sub_kegiatan').on('change', function() {
        const selectedOption = $(this).find('option:selected');

        if (selectedOption.val()) {
          $('#detail_bidang_urusan').text(selectedOption.data('bidang') || '-');
          $('#detail_program').text(selectedOption.data('program') || '-');
          $('#detail_kegiatan').text(selectedOption.data('kegiatan') || '-');
          $('#detail_sub_keg').text(selectedOption.data('subkeg') || '-');

          const idSubKegiatan = selectedOption.val();
          displayIndikator(idSubKegiatan);

          $('#detail_sub_kegiatan').removeClass('d-none');
        } else {
          $('#detail_sub_kegiatan').addClass('d-none');
          $('#indikator_section').addClass('d-none');
        }
      });

      function displayIndikator(idSubKegiatan) {
        const indikatorData = subKegiatanData.filter(item =>
          item.id_sub_kegiatan == idSubKegiatan && item.indikator
        );

        if (indikatorData.length > 0) {
          let indikatorHtml = '<h6 class="mb-3 fw-bold">Indikator Kinerja</h6>';

          indikatorData.forEach((item, index) => {
            indikatorHtml += `
          <div class="row align-items-center mb-3">
            <div class="col-md-5">
              <div class="fw-semibold text-gray-800">${item.indikator}</div>
              <input type="hidden" name="indikator[${index}][id_indikator]" value="${item.id_indikator || ''}">
              <input type="hidden" name="indikator[${index}][indikator_text]" value="${item.indikator}">
              <input type="hidden" name="indikator[${index}][satuan]" value="${item.satuan}">
            </div>
            <div class="col-md-5">
              <input type="text" 
                    class="form-control form-control-solid input-target" 
                    name="indikator[${index}][target]" 
                    placeholder="0" 
                    required />
            </div>
            <div class="col-md-2">
              <div class="text-gray-600">${item.satuan}</div>
            </div>
          </div>
        `;
          });

          $('#indikator_list').html(indikatorHtml);
          $('#indikator_section').removeClass('d-none');
          initializeTargetFormat();
        } else {
          $('#indikator_section').addClass('d-none');
        }
      }

      function initializeTargetFormat() {
        $('.input-target').off('input').on('input', function() {
          let value = $(this).val().replace(/[^\d]/g, '');
          if (value) {
            const formatted = new Intl.NumberFormat('id-ID').format(value);
            $(this).val(formatted);
          }
        });

        $('.input-target').off('blur').on('blur', function() {
          let value = $(this).val().replace(/[^\d]/g, '');
          if (value) {
            const formatted = new Intl.NumberFormat('id-ID').format(value);
            $(this).val(formatted);
          }
        });
      }

      $('#btn_add_sumber_dana').on('click', function() {
        sumberDanaCounter++;
        addSumberDanaForm(sumberDanaCounter);
        updateSumberDanaInfo();
      });

      function addSumberDanaForm(id) {
        let sumberDanaOptions = '<option value="">Pilih Sumber Dana</option>';
        sumberDanaList.forEach(item => {
          sumberDanaOptions += `<option value="${item.id}">${item.kode_dana} - ${item.nama_dana}</option>`;
        });

        const formHtml = `
          <div class="card card-bordered mb-5 sumber-dana-item" data-id="${id}">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-5">
                <h5 class="card-title m-0">
                  <i class="ki-outline ki-wallet fs-2 text-primary me-2"></i>
                  <span class="sumber-dana-number">Sumber Dana #1</span>
                </h5>
                <button type="button" class="btn btn-icon btn-sm btn-light-danger btn-remove-sumber-dana" data-id="${id}">
                  <i class="ki-outline ki-trash fs-2"></i>
                </button>
              </div>

              <div class="row g-5">
                <div class="col-md-6">
                  <label class="required fs-6 fw-semibold mb-2">Pilih Sumber Dana</label>
                  <select class="form-select form-select-solid select-sumber-dana-${id}" 
                          name="sumber_dana[${id}][id_sumber_dana]" 
                          data-control="select2" 
                          data-dropdown-parent="#kt_modal_add_kegiatan"
                          data-placeholder="Pilih Sumber Dana"
                          data-allow-clear="true"
                          required>
                    ${sumberDanaOptions}
                  </select>
                </div>

                <div class="col-md-6">
                  <label class="required fs-6 fw-semibold mb-2">Pagu</label>
                  <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="text" class="form-control form-control-solid input-pagu" 
                          name="sumber_dana[${id}][pagu]" 
                          placeholder="0" 
                          required>
                  </div>
                  <div class="form-text">Format: 1.000.000 (otomatis terformat)</div>
                </div>
              </div>

              <div class="mt-5 p-4 bg-light-primary rounded">
                <div class="d-flex justify-content-between align-items-center">
                  <span class="fw-bold text-gray-800">
                    Pagu <span class="sumber-dana-number-text">Sumber Dana #1</span>:
                  </span>
                  <span class="fs-4 fw-bold text-primary pagu-display-${id}">Rp 0</span>
                </div>
              </div>
            </div>
          </div>
        `;
        $('#sumber_dana_container').append(formHtml);
        $(`.select-sumber-dana-${id}`).select2({
          dropdownParent: $('#kt_modal_add_kegiatan'),
          placeholder: "Pilih Sumber Dana",
          allowClear: true
        });

        initializeCurrencyFormat();
        reorderSumberDana();
      }

      $(document).on('click', '.btn-remove-sumber-dana', function(e) {
        e.preventDefault();

        const button = $(this);
        const id = button.data('id');

        Swal.fire({
          title: 'Hapus Sumber Dana?',
          text: "Data sumber dana ini akan dihapus!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-light"
          }
        }).then((result) => {
          if (result.isConfirmed) {
            const cardElement = button.closest('.sumber-dana-item');

            cardElement.find('select[data-control="select2"]').each(function() {
              if ($(this).data('select2')) {
                $(this).select2('destroy');
              }
            });

            cardElement.fadeOut(300, function() {
              $(this).remove();
              reorderSumberDana();
              updateSumberDanaInfo();
              updateTotalPagu();
            });

            Swal.fire({
              icon: 'success',
              title: 'Berhasil!',
              text: 'Sumber dana telah dihapus',
              timer: 1500,
              showConfirmButton: false
            });
          }
        });
      });

      function reorderSumberDana() {
        $('.sumber-dana-item').each(function(index) {
          const newNumber = index + 1;
          $(this).find('.sumber-dana-number').text(`Sumber Dana #${newNumber}`);
          $(this).find('.sumber-dana-number-text').text(`Sumber Dana #${newNumber}`);
        });
        updateSumberDanaCount();
      }

      function updateSumberDanaCount() {
        const count = $('.sumber-dana-item').length;
        let counterBadge = $('#sumber_dana_counter');
        if (counterBadge.length === 0) {
          $('label.fs-6.fw-semibold:contains("Sumber Dana")').html(`
        Sumber Dana 
        <span id="sumber_dana_counter" class="badge badge-light-primary ms-2">${count} Item</span>
      `);
        } else {
          counterBadge.text(`${count} Item`);
        }
      }

      function updateSumberDanaInfo() {
        const count = $('.sumber-dana-item').length;
        if (count > 0) {
          $('#no_sumber_dana_info').hide();
          $('#total_pagu_summary').removeClass('d-none');
        } else {
          $('#no_sumber_dana_info').show();
          $('#total_pagu_summary').addClass('d-none');
        }
      }

      function initializeCurrencyFormat() {
        $('.input-pagu').off('input').on('input', function() {
          let value = $(this).val().replace(/[^\d]/g, '');

          if (value) {
            const formatted = new Intl.NumberFormat('id-ID').format(value);
            $(this).val(formatted);
            const id = $(this).closest('.sumber-dana-item').data('id');
            $(`.pagu-display-${id}`).text('Rp ' + formatted);
          } else {
            $(this).val('');
            const id = $(this).closest('.sumber-dana-item').data('id');
            $(`.pagu-display-${id}`).text('Rp 0');
          }

          updateTotalPagu();
        });

        $('.input-pagu').off('blur').on('blur', function() {
          let value = $(this).val().replace(/[^\d]/g, '');
          if (value) {
            const formatted = new Intl.NumberFormat('id-ID').format(value);
            $(this).val(formatted);
          }
        });
      }

      function updateTotalPagu() {
        let total = 0;
        $('.input-pagu').each(function() {
          const value = $(this).val().replace(/[^\d]/g, '');
          total += parseInt(value) || 0;
        });

        const formattedTotal = new Intl.NumberFormat('id-ID').format(total);
        $('#grand_total_pagu').text('Rp ' + formattedTotal);
      }

      // === Initialize DataTable ===
      var table = $('#kt_datatable_column_rendering').DataTable({
        responsive: true,
        searchDelay: 500,
        processing: true,
        serverSide: true,
        scrollX: true,
        ajax: {
          url: '{{ route('renja.data') }}',
          type: 'GET',
          error: function(xhr, error, code) {
            console.error('DataTable Error:', error);
            Swal.fire({
              icon: 'error',
              title: 'Gagal Memuat Data',
              text: 'Terjadi kesalahan saat mengambil data',
              confirmButtonText: 'OK',
              buttonsStyling: false,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            });
          }
        },
        columns: [{
            data: 'checkbox',
            orderable: false,
            searchable: false,
            visible: false
          },
          {
            data: 'group_skpd',
            visible: false
          },
          {
            data: 'group_urusan',
            visible: false
          },
          {
            data: 'group_program',
            visible: false
          },
          {
            data: 'group_kegiatan',
            visible: false
          },
          {
            data: 'sub_kegiatan'
          },
          {
            data: 'status_sub_kegiatan'
          },
          {
            data: 'status_rincian'
          },
          {
            data: 'sebelum_perubahan',
            className: 'text-end'
          },
          {
            data: 'pagu_validasi',
            className: 'text-end'
          },
          {
            data: 'total_rincian',
            className: 'text-end'
          },
          {
            data: 'total_realisasi',
            className: 'text-end'
          },
          {
            data: 'persentase',
            className: 'text-end'
          },
          {
            data: 'aksi',
            orderable: false,
            searchable: false
          }
        ],
        order: [
          [1, 'asc']
        ],
        rowGroup: {
          dataSrc: ['group_skpd', 'group_urusan', 'group_program', 'group_kegiatan'],
          startRender: function(rows, group, level) {
            return $('<tr class="dtrg-group dtrg-level-' + level + '"/>')
              .append('<td colspan="14">' + group + '</td>');
          }
        },
        dom: "<'row'<'col-sm-12'tr>>" +
          "<'row mt-4'" +
          "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-start'li>" +
          "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-end'p>" +
          ">",
        language: {
          paginate: {
            previous: '<i class="ki-outline ki-arrow-left fs-4"></i>',
            next: '<i class="ki-outline ki-arrow-right fs-4"></i>'
          },
          processing: '<div class="d-flex justify-content-center"><div class="spinner-border text-primary" role="status"></div></div>',
          emptyTable: 'Tidak ada data yang tersedia',
          info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
          infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
          infoFiltered: '(disaring dari _MAX_ total data)',
          lengthMenu: 'Tampilkan _MENU_ data',
          zeroRecords: 'Tidak ada data yang cocok'
        }
      });

      $('#kt_datatable_search_input').keyup(function() {
        table.search(this.value).draw();
      });

      $(document).on('click', '.btn-collapse', function(e) {
        e.preventDefault();
        $(this).toggleClass('collapsed');
      });

      $(document).on('click', '.btn-lihat-sub-kegiatan', function(e) {
        e.preventDefault();
        const id = $(this).data('id');

        Swal.fire({
          title: 'Lihat Sub Kegiatan',
          html: `
            <div class="text-start">
              <p>Menampilkan detail lengkap sub kegiatan dengan ID: <strong>${id}</strong></p>
              <ul class="list-unstyled mt-3">
                <li><i class="ki-outline ki-check-circle text-success"></i> Informasi sub kegiatan</li>
                <li><i class="ki-outline ki-check-circle text-success"></i> Daftar indikator dan target</li>
                <li><i class="ki-outline ki-check-circle text-success"></i> Sumber dana</li>
                <li><i class="ki-outline ki-check-circle text-success"></i> Lokasi dan waktu pelaksanaan</li>
              </ul>
            </div>
          `,
          icon: 'info',
          confirmButtonText: 'Tutup',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
      });

      $(document).on('click', '.btn-lihat-rincian', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        window.location.href = `/rkpd/renja/${id}/rincian`;
      });

      $(document).on('click', '.btn-rka-paket', function(e) {
        e.preventDefault();
        const id = $(this).data('id');

        Swal.fire({
          title: 'RKA Paket / Kelompok',
          html: `
            <div class="text-start">
              <p>Mengelola paket/kelompok belanja untuk ID: <strong>${id}</strong></p>
              <ul class="list-unstyled mt-3">
                <li><i class="ki-outline ki-check-circle text-success"></i> Pengelompokan rincian belanja</li>
                <li><i class="ki-outline ki-check-circle text-success"></i> Manajemen paket pekerjaan</li>
                <li><i class="ki-outline ki-check-circle text-success"></i> Alokasi anggaran per paket</li>
              </ul>
            </div>
          `,
          icon: 'info',
          confirmButtonText: 'Tutup',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-success"
          }
        });
      });

      $(document).on('click', '.btn-rka-rincian', function(e) {
        e.preventDefault();
        const id = $(this).data('id');

        Swal.fire({
          title: 'RKA Rincian Belanja',
          html: `
            <div class="text-start">
              <p>Input dan edit RKA rincian belanja untuk ID: <strong>${id}</strong></p>
              <ul class="list-unstyled mt-3">
                <li><i class="ki-outline ki-check-circle text-warning"></i> Input detail belanja</li>
                <li><i class="ki-outline ki-check-circle text-warning"></i> Kode rekening dan uraian</li>
                <li><i class="ki-outline ki-check-circle text-warning"></i> Volume, satuan, dan tarif</li>
                <li><i class="ki-outline ki-check-circle text-warning"></i> Perhitungan total anggaran</li>
              </ul>
            </div>
          `,
          icon: 'info',
          confirmButtonText: 'Tutup',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-warning"
          }
        });
      });

      $(document).on('click', '.btn-delete-renja', function(e) {
        e.preventDefault();
        const id = $(this).data('id');

        Swal.fire({
          title: 'Hapus Sub Kegiatan?',
          html: `
            <div class="text-start">
                <p class="mb-3">Tindakan ini akan menghapus:</p>
                <ul class="list-unstyled">
                    <li><i class="ki-outline ki-cross-circle text-danger me-2"></i> Data sub kegiatan</li>
                    <li><i class="ki-outline ki-cross-circle text-danger me-2"></i> Sumber dana terkait</li>
                    <li><i class="ki-outline ki-cross-circle text-danger me-2"></i> Indikator kinerja</li>
                </ul>
                <div class="alert alert-warning d-flex align-items-center mt-3">
                    <i class="ki-outline ki-information-5 fs-2 me-3"></i>
                    <div>
                        <strong>Perhatian!</strong><br>
                        Sub kegiatan yang sudah memiliki rincian belanja tidak dapat dihapus.
                    </div>
                </div>
            </div>
          `,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Ya, Hapus!',
          cancelButtonText: 'Batal',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-light"
          }
        }).then((result) => {
          if (result.isConfirmed) {
            Swal.fire({
              title: 'Menghapus...',
              html: 'Mohon tunggu sebentar',
              allowOutsideClick: false,
              didOpen: () => {
                Swal.showLoading();
              }
            });

            // AJAX Delete
            $.ajax({
              url: `/rkpd/renja/${id}`,
              type: 'DELETE',
              data: {
                _token: '{{ csrf_token() }}'
              },
              success: function(response) {
                Swal.fire({
                  icon: 'success',
                  title: 'Berhasil!',
                  text: response.message || 'Sub kegiatan berhasil dihapus',
                  confirmButtonText: 'OK',
                  buttonsStyling: false,
                  customClass: {
                    confirmButton: "btn btn-primary"
                  }
                }).then(() => {
                  // Reload DataTable
                  table.ajax.reload();
                });
              },
              error: function(xhr) {
                let errorMsg = 'Terjadi kesalahan saat menghapus data';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                  errorMsg = xhr.responseJSON.message;
                }

                Swal.fire({
                  icon: 'error',
                  title: 'Gagal!',
                  text: errorMsg,
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

      // === Session Messages ===
      const sessionMessages = document.querySelectorAll('#session-messages div');
      sessionMessages.forEach(msg => {
        const type = msg.dataset.type;
        const message = msg.dataset.message;
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
        if (type === 'error') toastr.error(message, "GAGAL");
        else if (type === 'success') toastr.success(message, "BERHASIL");
        else toastr.info(message);
      });

      // === Form validation ===
      const form = document.getElementById('kt_modal_add_kegiatan_form');
      const submitButton = document.getElementById('kt_modal_add_kegiatan_submit');

      if (form && submitButton) {
        form.addEventListener('submit', function(e) {
          const idSkpd = form.querySelector('select[name="id_skpd"]').value;
          const idSubKegiatan = form.querySelector('select[name="id_sub_kegiatan"]').value;
          const sumberDanaCount = $('.sumber-dana-item').length;

          if (!idSkpd || !idSubKegiatan) {
            e.preventDefault();
            Swal.fire({
              icon: 'error',
              title: 'Validasi gagal',
              text: 'Pilih SKPD dan Sub Kegiatan terlebih dahulu!',
              confirmButtonText: 'OK',
              buttonsStyling: false,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            });
            return;
          }

          if (sumberDanaCount === 0) {
            e.preventDefault();
            Swal.fire({
              icon: 'error',
              title: 'Validasi gagal',
              text: 'Tambahkan minimal 1 sumber dana!',
              confirmButtonText: 'OK',
              buttonsStyling: false,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            });
            return;
          }

          $('.input-pagu').each(function() {
            const plainValue = $(this).val().replace(/[^\d]/g, '');
            $(this).val(plainValue);
          });

          $('.input-target').each(function() {
            const plainValue = $(this).val().replace(/[^\d]/g, '');
            $(this).val(plainValue);
          });

          submitButton.setAttribute('data-kt-indicator', 'on');
          submitButton.disabled = true;
        });
      }

      // === Reset modal when closed ===
      $('#kt_modal_add_kegiatan').on('hidden.bs.modal', function() {
        $('#sumber_dana_container select[data-control="select2"]').each(function() {
          if ($(this).hasClass("select2-hidden-accessible")) {
            $(this).select2('destroy');
          }
        });

        form.reset();
        $('#sub_kegiatan_container').hide();
        $('#detail_sub_kegiatan').addClass('d-none');
        $('#indikator_section').addClass('d-none');
        $('#select_sub_kegiatan').html('<option value="">Pilih Sub Kegiatan</option>');
        $('#total_sub_kegiatan').text('0');
        $('#sumber_dana_container').html('');
        $('#no_sumber_dana_info').show();
        $('#total_pagu_summary').addClass('d-none');
        $('#grand_total_pagu').text('Rp 0');
        sumberDanaCounter = 0;
        submitButton.removeAttribute('data-kt-indicator');
        submitButton.disabled = false;
      });

      // === Auto show modal if validation errors exist ===
      @if ($errors->any() && old('_token'))
        $('#kt_modal_add_kegiatan').modal('show');
      @endif
    });
  </script>
