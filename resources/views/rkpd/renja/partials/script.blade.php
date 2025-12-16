<script>
  "use strict";

  // ==================== GLOBAL VARIABLES ====================
  let sumberDanaCounter = 0;
  let availableSumberDana = [];
  let subKegiatanData = null;

  // ==================== INITIALIZE ON DOCUMENT READY ====================
  $(document).ready(function() {
    initializeSelect2();
    initializeDataTable();
    bindEvents();
    showSessionMessages();
    loadAvailableSumberDana();
  });

  // ==================== SESSION MESSAGES ====================
  function showSessionMessages() {
    const sessionMessages = document.getElementById('session-messages');
    if (sessionMessages) {
      const messages = sessionMessages.querySelectorAll('div[data-type]');
      messages.forEach(function(msg) {
        const type = msg.getAttribute('data-type');
        const message = msg.getAttribute('data-message');

        if (type === 'success') {
          toastr.success(message, 'Berhasil');
        } else if (type === 'error') {
          toastr.error(message, 'Error');
        }
      });
    }
  }

  // ==================== INITIALIZE SELECT2 ====================
  function initializeSelect2() {
    $('[data-control="select2"]').each(function() {
      const $this = $(this);
      const parent = $this.data('dropdown-parent');

      $this.select2({
        placeholder: $this.attr('placeholder') || 'Pilih...',
        allowClear: true,
        dropdownParent: parent ? $(parent) : undefined,
        width: '100%'
      });
    });
  }

  // ==================== LOAD AVAILABLE SUMBER DANA ====================
  function loadAvailableSumberDana() {
    @if (isset($sumberdana))
      availableSumberDana = @json($sumberdana);
    @endif
  }

  // ==================== BIND EVENTS ====================
  function bindEvents() {
    $('#select_skpd').on('change', function() {
      handleSkpdChange($(this).val());
    });

    $('#select_sub_kegiatan').on('change', function() {
      handleSubKegiatanChange($(this).val());
    });

    $('#btn_add_sumber_dana').on('click', function() {
      addSumberDanaItem();
    });

    $(document).on('click', '.btn-remove-sumber-dana', function() {
      removeSumberDanaItem($(this));
    });

    $(document).on('input', '.input-pagu', function() {
      formatCurrency($(this));
      calculateTotalPagu();
    });

    $(document).on('input', '.input-target', function() {
      formatNumber($(this));
    });

    $(document).on('change', 'select[name*="[id_sumber_dana]"]', function() {
      updateSumberDanaLabel($(this));
    });
  }

  // ==================== HANDLE SKPD CHANGE ====================
  function handleSkpdChange(idSkpd) {
    if (!idSkpd) {
      $('#sub_kegiatan_container').hide();
      $('#detail_sub_kegiatan').addClass('d-none');
      resetSumberDanaSection();
      return;
    }

    $('#loading_sub_kegiatan').removeClass('d-none');
    $('#sub_kegiatan_container').hide();
    $('#select_sub_kegiatan').html('<option value="">Pilih Sub Kegiatan</option>');
    $('#detail_sub_kegiatan').addClass('d-none');
    resetSumberDanaSection();

    $.ajax({
      url: '{{ route('renja.get-sub-kegiatan') }}',
      type: 'GET',
      data: {
        id_skpd: idSkpd,
        tahun_anggaran: 2025
      },
      success: function(response) {
        $('#loading_sub_kegiatan').addClass('d-none');

        if (response.success && response.data.length > 0) {
          populateSubKegiatan(response.data);
          $('#sub_kegiatan_container').show();
          $('#total_sub_kegiatan').text(response.count);
        } else {
          Swal.fire({
            icon: 'info',
            title: 'Tidak Ada Data',
            text: 'Tidak ada sub kegiatan untuk SKPD yang dipilih',
            buttonsStyling: false,
            customClass: {
              confirmButton: "btn btn-primary"
            }
          });
        }
      },
      error: function(xhr) {
        $('#loading_sub_kegiatan').addClass('d-none');
        console.error('Error fetching sub kegiatan:', xhr);

        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Gagal mengambil data sub kegiatan',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
      }
    });
  }

  // ==================== POPULATE SUB KEGIATAN ====================
  function populateSubKegiatan(data) {
    const $select = $('#select_sub_kegiatan');
    $select.html('<option value="">Pilih Sub Kegiatan</option>');

    const grouped = {};

    data.forEach(function(item) {
      const bidangKey = item.kode_bidang_urusan + ' - ' + item.nama_bidang_urusan;
      const programKey = item.kode_program + ' - ' + item.nama_program;
      const kegiatanKey = item.kode_kegiatan + ' - ' + item.nama_kegiatan;

      if (!grouped[bidangKey]) grouped[bidangKey] = {};
      if (!grouped[bidangKey][programKey]) grouped[bidangKey][programKey] = {};
      if (!grouped[bidangKey][programKey][kegiatanKey]) grouped[bidangKey][programKey][kegiatanKey] = [];

      grouped[bidangKey][programKey][kegiatanKey].push(item);
    });

    Object.keys(grouped).forEach(function(bidang) {
      const $bidangGroup = $('<optgroup>').attr('label', '📁 ' + bidang);

      Object.keys(grouped[bidang]).forEach(function(program) {
        Object.keys(grouped[bidang][program]).forEach(function(kegiatan) {
          const items = grouped[bidang][program][kegiatan];

          items.forEach(function(item) {
            const hasIndicator = item.id_indikator ? '✓' : '○';
            const optionText = hasIndicator + ' ' + item.kode_sub_kegiatan + ' - ' + item.nama_sub_kegiatan;

            const $option = $('<option>')
              .val(item.id_sub_kegiatan)
              .text(optionText)
              .data('item', item);

            $bidangGroup.append($option);
          });
        });
      });

      $select.append($bidangGroup);
    });

    $select.select2({
      placeholder: 'Pilih Sub Kegiatan',
      allowClear: true,
      dropdownParent: $('#kt_modal_add_kegiatan'),
      width: '100%'
    });
  }

  // ==================== HANDLE SUB KEGIATAN CHANGE ====================
  function handleSubKegiatanChange(idSubKegiatan) {
    if (!idSubKegiatan) {
      $('#detail_sub_kegiatan').addClass('d-none');
      $('#indikator_section').addClass('d-none');
      resetSumberDanaSection();
      subKegiatanData = null;
      return;
    }

    const selectedOption = $('#select_sub_kegiatan option:selected');
    const itemData = selectedOption.data('item');

    if (!itemData) return;

    subKegiatanData = itemData;

    $('#detail_bidang_urusan').text(itemData.kode_bidang_urusan + ' - ' + itemData.nama_bidang_urusan);
    $('#detail_program').text(itemData.kode_program + ' - ' + itemData.nama_program);
    $('#detail_kegiatan').text(itemData.kode_kegiatan + ' - ' + itemData.nama_kegiatan);
    $('#detail_sub_keg').text(itemData.kode_sub_kegiatan + ' - ' + itemData.nama_sub_kegiatan);

    $('#detail_sub_kegiatan').removeClass('d-none');

    loadIndikator(idSubKegiatan);
  }

  // ==================== LOAD INDIKATOR ====================
  function loadIndikator(idSubKegiatan) {
    const $indikatorList = $('#indikator_list');
    $indikatorList.html('<div class="text-center"><span class="spinner-border spinner-border-sm"></span> Memuat indikator...</div>');

    $.ajax({
      url: '{{ route('renja.get-sub-kegiatan') }}',
      type: 'GET',
      data: {
        id_skpd: $('#select_skpd').val(),
        tahun_anggaran: 2025
      },
      success: function(response) {
        const indikators = response.data.filter(item =>
          item.id_sub_kegiatan == idSubKegiatan && item.id_indikator
        );

        if (indikators.length > 0) {
          $indikatorList.empty();

          indikators.forEach(function(indikator, index) {
            const indikatorHtml = `
            <div class="card border border-dashed border-gray-300 mb-3">
              <div class="card-body p-4">
                <div class="row align-items-center">
                  <div class="col-md-5">
                    <label class="form-label fw-bold mb-2">Indikator ${index + 1}</label>
                    <input type="hidden" name="indikator[${index}][id_indikator]" value="${indikator.id_indikator}">
                    <input type="text" class="form-control form-control-sm" 
                           name="indikator[${index}][indikator_text]" 
                           value="${indikator.indikator}" readonly>
                  </div>
                  <div class="col-md-3">
                    <label class="form-label fw-bold mb-2">Satuan</label>
                    <input type="text" class="form-control form-control-sm" 
                           name="indikator[${index}][satuan]" 
                           value="${indikator.satuan || ''}" readonly>
                  </div>
                  <div class="col-md-4">
                    <label class="form-label fw-bold mb-2 required">Target <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-sm input-target" 
                           name="indikator[${index}][target]" 
                           placeholder="0" required>
                  </div>
                </div>
              </div>
            </div>
          `;
            $indikatorList.append(indikatorHtml);
          });

          $('#indikator_section').removeClass('d-none');
        } else {
          $indikatorList.html('<div class="alert alert-light-info">Tidak ada indikator untuk sub kegiatan ini</div>');
          $('#indikator_section').removeClass('d-none');
        }
      },
      error: function() {
        $indikatorList.html('<div class="alert alert-danger">Gagal memuat indikator</div>');
      }
    });
  }

  // ==================== SUMBER DANA FUNCTIONS ====================
  function addSumberDanaItem() {
    sumberDanaCounter++;

    const itemHtml = `
    <div class="sumber-dana-item card border border-primary mb-4" data-index="${sumberDanaCounter}">
      <div class="card-body p-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h5 class="mb-0 fw-bold text-gray-800">
            <i class="ki-outline ki-wallet fs-2 text-primary me-2"></i>
            Sumber Dana #<span class="sumber-dana-number">${sumberDanaCounter}</span>
          </h5>
          <button type="button" class="btn btn-sm btn-icon btn-light-danger btn-remove-sumber-dana" data-index="${sumberDanaCounter}">
            <i class="ki-outline ki-trash fs-3"></i>
          </button>
        </div>
        
        <div class="row">
          <div class="col-md-6 mb-4">
            <label class="required form-label fw-semibold">Pilih Sumber Dana</label>
            <select class="form-select form-select-solid" 
                    name="sumber_dana[${sumberDanaCounter}][id_sumber_dana]" 
                    data-control="select2"
                    data-placeholder="Pilih Sumber Dana"
                    required>
              <option value="">Pilih Sumber Dana</option>
              @foreach ($sumberdana as $sd)
                <option value="{{ $sd->id }}">{{ $sd->kode_dana }} - {{ $sd->nama_dana }}</option>
              @endforeach
            </select>
          </div>
          
          <div class="col-md-6 mb-4">
            <label class="required form-label fw-semibold">Pagu (Rp)</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="text" 
                     class="form-control form-control-solid input-pagu" 
                     name="sumber_dana[${sumberDanaCounter}][pagu]" 
                     placeholder="0" 
                     data-index="${sumberDanaCounter}"
                     required>
            </div>
            <div class="form-text">Minimal: Rp 1</div>
          </div>
        </div>
        
        <div class="separator separator-dashed my-4"></div>
        
        <div class="d-flex justify-content-between align-items-center">
          <div class="text-muted fs-7">
            <i class="ki-outline ki-information-5 fs-5 me-1"></i>
            Total akan dihitung otomatis
          </div>
          <div class="fw-bold fs-6 text-gray-800">
            Pagu: <span class="text-primary item-total-pagu">Rp 0</span>
          </div>
        </div>
      </div>
    </div>
  `;

    $('#sumber_dana_container').append(itemHtml);

    const $newSelect = $(`select[name="sumber_dana[${sumberDanaCounter}][id_sumber_dana]"]`);
    $newSelect.select2({
      placeholder: 'Pilih Sumber Dana',
      allowClear: true,
      dropdownParent: $('#kt_modal_add_kegiatan'),
      width: '100%'
    });

    $('#no_sumber_dana_info').hide();
    $('#total_pagu_summary').removeClass('d-none');
    updateSumberDanaNumbering();

    $(`[data-index="${sumberDanaCounter}"]`)[0].scrollIntoView({
      behavior: 'smooth',
      block: 'center'
    });
  }

  function removeSumberDanaItem($button) {
    const index = $button.data('index');

    Swal.fire({
      title: 'Hapus Sumber Dana?',
      text: 'Data sumber dana ini akan dihapus dari form',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, Hapus',
      cancelButtonText: 'Batal',
      buttonsStyling: false,
      customClass: {
        confirmButton: "btn btn-danger",
        cancelButton: "btn btn-light"
      }
    }).then((result) => {
      if (result.isConfirmed) {
        $(`.sumber-dana-item[data-index="${index}"]`).fadeOut(300, function() {
          $(this).remove();
          updateSumberDanaNumbering();
          calculateTotalPagu();

          if ($('.sumber-dana-item').length === 0) {
            $('#no_sumber_dana_info').show();
            $('#total_pagu_summary').addClass('d-none');
          }
        });

        toastr.success('Sumber dana berhasil dihapus', 'Berhasil');
      }
    });
  }

  function updateSumberDanaNumbering() {
    $('.sumber-dana-item').each(function(index) {
      $(this).find('.sumber-dana-number').text(index + 1);
    });
  }

  function updateSumberDanaLabel($select) {
    const selectedText = $select.find('option:selected').text();
    console.log('Selected sumber dana:', selectedText);
  }

  function calculateTotalPagu() {
    let grandTotal = 0;

    $('.sumber-dana-item').each(function() {
      const $input = $(this).find('.input-pagu');
      const value = parseFloat($input.val().replace(/[^\d]/g, '')) || 0;

      $(this).find('.item-total-pagu').text(formatRupiah(value));
      grandTotal += value;
    });

    $('#grand_total_pagu').text(formatRupiah(grandTotal));

    $('#grand_total_pagu').addClass('text-animation');
    setTimeout(function() {
      $('#grand_total_pagu').removeClass('text-animation');
    }, 500);
  }

  function resetSumberDanaSection() {
    $('#sumber_dana_container').empty();
    $('#no_sumber_dana_info').show();
    $('#total_pagu_summary').addClass('d-none');
    $('#grand_total_pagu').text('Rp 0');
    sumberDanaCounter = 0;
  }

  function validateSumberDana() {
    const items = $('.sumber-dana-item');

    if (items.length === 0) {
      Swal.fire({
        icon: 'error',
        title: 'Validasi Gagal',
        text: 'Minimal 1 sumber dana harus ditambahkan!',
        buttonsStyling: false,
        customClass: {
          confirmButton: "btn btn-primary"
        }
      });
      return false;
    }

    let isValid = true;
    let errorMessage = '';

    items.each(function(index) {
      const $item = $(this);
      const $select = $item.find('select[name*="[id_sumber_dana]"]');
      const $input = $item.find('.input-pagu');

      const sumberDana = $select.val();
      const pagu = $input.val().replace(/[^\d]/g, '');

      if (!sumberDana) {
        isValid = false;
        errorMessage = `Pilih sumber dana pada item #${index + 1}`;
        $select.addClass('is-invalid');
        $item[0].scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });
        return false;
      } else {
        $select.removeClass('is-invalid');
      }

      if (!pagu || pagu === '0') {
        isValid = false;
        errorMessage = `Isi pagu pada item #${index + 1} (minimal Rp 1)`;
        $input.addClass('is-invalid');
        $item[0].scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });
        return false;
      } else {
        $input.removeClass('is-invalid');
      }
    });

    if (!isValid) {
      Swal.fire({
        icon: 'error',
        title: 'Validasi Gagal',
        text: errorMessage,
        buttonsStyling: false,
        customClass: {
          confirmButton: "btn btn-primary"
        }
      });
    }

    return isValid;
  }

  function prepareSumberDanaForSubmit() {
    $('.input-pagu').each(function() {
      const value = $(this).val().replace(/[^\d]/g, '');
      $(this).val(value);
    });
  }

  // ==================== FORMATTING FUNCTIONS ====================
  function formatCurrency($input) {
    let value = $input.val().replace(/[^\d]/g, '');
    if (value) {
      $input.val(formatRupiah(value));
    }
  }

  function formatNumber($input) {
    let value = $input.val().replace(/[^\d]/g, '');
    if (value) {
      $input.val(value.replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
    }
  }

  function formatRupiah(value) {
    if (!value) return 'Rp 0';
    return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  // ==================== FORM SUBMISSION ====================
  const form = document.getElementById('kt_modal_add_kegiatan_form');
  const submitButton = document.getElementById('kt_modal_add_kegiatan_submit');

  if (form && submitButton) {
    form.addEventListener('submit', function(e) {
      e.preventDefault();

      const idSkpd = form.querySelector('select[name="id_skpd"]').value;
      if (!idSkpd) {
        Swal.fire({
          icon: 'error',
          title: 'Validasi Gagal',
          text: 'Silakan pilih SKPD terlebih dahulu!',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
        return false;
      }

      const idSubKegiatan = form.querySelector('select[name="id_sub_kegiatan"]').value;
      if (!idSubKegiatan) {
        Swal.fire({
          icon: 'error',
          title: 'Validasi Gagal',
          text: 'Silakan pilih Sub Kegiatan terlebih dahulu!',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });

        $('#sub_kegiatan_container')[0]?.scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });
        return false;
      }

      if (!validateSumberDana()) {
        $('#btn_add_sumber_dana')[0]?.scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });
        return false;
      }

      const idLokasi = form.querySelector('select[name="id_lokasi"]').value;
      if (!idLokasi) {
        Swal.fire({
          icon: 'error',
          title: 'Validasi Gagal',
          text: 'Silakan pilih lokasi/daerah!',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
        return false;
      }

      const waktuAwal = form.querySelector('select[name="waktu_awal"]').value;
      const waktuAkhir = form.querySelector('select[name="waktu_akhir"]').value;

      if (!waktuAwal || !waktuAkhir) {
        Swal.fire({
          icon: 'error',
          title: 'Validasi Gagal',
          text: 'Silakan pilih waktu pelaksanaan (awal dan akhir)!',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
        return false;
      }

      if (parseInt(waktuAwal) > parseInt(waktuAkhir)) {
        Swal.fire({
          icon: 'error',
          title: 'Validasi Gagal',
          text: 'Waktu awal tidak boleh lebih besar dari waktu akhir!',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });
        return false;
      }

      let indikatorValid = true;
      $('.input-target').each(function() {
        const value = $(this).val().replace(/[^\d]/g, '');
        if (!value || value === '0') {
          indikatorValid = false;
          $(this).addClass('is-invalid');
          return false;
        } else {
          $(this).removeClass('is-invalid');
        }
      });

      if (!indikatorValid && $('.input-target').length > 0) {
        Swal.fire({
          icon: 'error',
          title: 'Validasi Gagal',
          text: 'Isi target untuk semua indikator (minimal 1)!',
          buttonsStyling: false,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });

        $('.input-target.is-invalid').first()[0]?.scrollIntoView({
          behavior: 'smooth',
          block: 'center'
        });
        return false;
      }

      const totalPagu = $('#grand_total_pagu').text();
      const jumlahSumberDana = $('.sumber-dana-item').length;
      const namaSubKegiatan = $('#select_sub_kegiatan option:selected').text();

      Swal.fire({
        title: 'Konfirmasi Penyimpanan',
        html: `
        <div class="text-start">
          <p class="mb-3"><strong>Sub Kegiatan:</strong><br>${namaSubKegiatan}</p>
          <p class="mb-3"><strong>Total Pagu:</strong><br><span class="fs-4 text-primary fw-bold">${totalPagu}</span></p>
          <p class="mb-3"><strong>Jumlah Sumber Dana:</strong> ${jumlahSumberDana}</p>
          <div class="separator my-4"></div>
          <p class="text-muted fs-7">Data akan disimpan ke database. Lanjutkan?</p>
        </div>
      `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Periksa Lagi',
        buttonsStyling: false,
        customClass: {
          confirmButton: "btn btn-primary",
          cancelButton: "btn btn-light"
        }
      }).then((result) => {
        if (result.isConfirmed) {
          prepareSumberDanaForSubmit();

          $('.input-target').each(function() {
            $(this).val($(this).val().replace(/[^\d]/g, ''));
          });

          $('.is-invalid').removeClass('is-invalid');

          submitButton.setAttribute('data-kt-indicator', 'on');
          submitButton.disabled = true;

          Swal.fire({
            title: 'Menyimpan Data...',
            html: `
            <div class="text-center">
              <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
              </div>
              <p class="text-muted">Mohon tunggu, sedang menyimpan data...</p>
            </div>
          `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            showConfirmButton: false
          });

          form.submit();
        }
      });
    });
  }

  // ==================== RESET MODAL ON CLOSE ====================
  $('#kt_modal_add_kegiatan').on('hidden.bs.modal', function() {
    resetSumberDanaSection();
    form.reset();

    $('#sub_kegiatan_container').hide();
    $('#detail_sub_kegiatan').addClass('d-none');
    $('#indikator_section').addClass('d-none');
    $('#select_sub_kegiatan').html('<option value="">Pilih Sub Kegiatan</option>');
    $('#total_sub_kegiatan').text('0');

    $('.is-invalid').removeClass('is-invalid');

    submitButton.removeAttribute('data-kt-indicator');
    submitButton.disabled = false;

    sessionStorage.removeItem('temp_sumber_dana');
    Swal.close();
  });

  // ==================== PREVENT ACCIDENTAL CLOSE ====================
  let formHasChanges = false;

  $(document).on('change input', '#kt_modal_add_kegiatan_form input, #kt_modal_add_kegiatan_form select', function() {
    formHasChanges = true;
  });

  $('#kt_modal_add_kegiatan').on('hide.bs.modal', function(e) {
    if (formHasChanges) {
      e.preventDefault();

      Swal.fire({
        title: 'Perubahan Belum Disimpan',
        text: 'Anda memiliki perubahan yang belum disimpan. Keluar tanpa menyimpan?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Keluar',
        cancelButtonText: 'Batal',
        buttonsStyling: false,
        customClass: {
          confirmButton: "btn btn-danger",
          cancelButton: "btn btn-light"
        }
      }).then((result) => {
        if (result.isConfirmed) {
          formHasChanges = false;
          $('#kt_modal_add_kegiatan').modal('hide');
        }
      });
    }
  });

  form.addEventListener('submit', function() {
    formHasChanges = false;
  });

  // ==================== KEYBOARD SHORTCUTS ====================
  $(document).on('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
      if ($('#kt_modal_add_kegiatan').hasClass('show')) {
        e.preventDefault();
        $('#kt_modal_add_kegiatan_submit').click();
      }
    }

    if (e.key === 'Escape') {
      if ($('#kt_modal_add_kegiatan').hasClass('show')) {
        Swal.close();
      }
    }
  });

  // ==================== DATATABLE INITIALIZATION ====================
  function initializeDataTable() {
    if (!$('#kt_datatable_column_rendering').length) return;

    const table = $('#kt_datatable_column_rendering').DataTable({
      processing: true,
      serverSide: true,
      ajax: {
        url: '{{ route('renja.data') }}',
        type: 'GET',
        error: function(xhr, error, code) {
          console.error('DataTable Error:', xhr.responseText);
          toastr.error('Gagal memuat data', 'Error');
        }
      },
      columns: [{
          data: 'checkbox',
          orderable: false,
          searchable: false,
          className: 'text-center',
          render: function(data, type, row) {
            return `
            <div class="form-check form-check-sm form-check-custom form-check-solid">
              <input class="form-check-input row-checkbox" type="checkbox" value="${row.id}" />
            </div>
          `;
          }
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
          data: 'sub_kegiatan',
          orderable: false
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
          searchable: false,
          className: 'text-center',
          render: function(data, type, row) {
            return `
            <div class="btn-group" role="group">
              <button type="button" class="btn btn-sm btn-light-primary btn-icon" 
                      onclick="viewDetail(${row.id})" title="Detail">
                <i class="ki-outline ki-eye fs-4"></i>
              </button>
              <button type="button" class="btn btn-sm btn-light-warning btn-icon" 
                      onclick="editData(${row.id})" title="Edit">
                <i class="ki-outline ki-pencil fs-4"></i>
              </button>
              <button type="button" class="btn btn-sm btn-light-danger btn-icon" 
                      onclick="deleteData(${row.id})" title="Hapus">
                <i class="ki-outline ki-trash fs-4"></i>
              </button>
            </div>
          `;
          }
        }
      ],
      order: [
        [5, 'asc']
      ],
      rowGroup: {
        dataSrc: ['group_skpd', 'group_urusan', 'group_program', 'group_kegiatan'],
        startRender: function(rows, group, level) {
          const levelClass = 'dtrg-level-' + level;
          return $('<tr/>')
            .addClass('dtrg-group ' + levelClass)
            .append('<td colspan="14">' + group + '</td>');
        }
      },
      pageLength: 25,
      lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "Semua"]
      ],
      language: {
        processing: "Memuat data...",
        search: "Cari:",
        lengthMenu: "Tampilkan _MENU_ data",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
        infoFiltered: "(disaring dari _MAX_ total data)",
        zeroRecords: "Tidak ada data yang cocok",
        emptyTable: "Tidak ada data tersedia",
        paginate: {
          first: "Pertama",
          last: "Terakhir",
          next: "Selanjutnya",
          previous: "Sebelumnya"
        }
      },
      drawCallback: function() {
        handleCheckboxSelection(table);
      }
    });

    $('#kt_datatable_search_input').on('keyup', function() {
      table.search(this.value).draw();
    });
  }

  // ==================== CHECKBOX SELECTION ====================
  function handleCheckboxSelection(table) {
    const $masterCheckbox = $('.master-checkbox');
    const $rowCheckboxes = $('.row-checkbox');

    $masterCheckbox.on('change', function() {
      const isChecked = $(this).is(':checked');
      $rowCheckboxes.prop('checked', isChecked);
      updateToolbar();
    });

    $rowCheckboxes.on('change', function() {
      updateToolbar();
    });
  }

  function updateToolbar() {
    const checkedCount = $('.row-checkbox:checked').length;

    if (checkedCount > 0) {
      $('[data-kt-customer-table-toolbar="base"]').addClass('d-none');
      $('[data-kt-customer-table-toolbar="selected"]').removeClass('d-none');
      $('[data-kt-customer-table-select="selected_count"]').text(checkedCount);
    } else {
      $('[data-kt-customer-table-toolbar="selected"]').addClass('d-none');
      $('[data-kt-customer-table-toolbar="base"]').removeClass('d-none');
    }
  }

  // ==================== CRUD FUNCTIONS ====================
  function viewDetail(id) {
    console.log('View detail:', id);
    toastr.info('Fitur detail akan segera tersedia', 'Info');
  }

  function editData(id) {
    console.log('Edit data:', id);
    toastr.info('Fitur edit akan segera tersedia', 'Info');
  }

  function deleteData(id) {
    Swal.fire({
      title: 'Hapus Data?',
      text: 'Data yang dihapus tidak dapat dikembalikan',
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
        $.ajax({
          url: `/rkpd/renja/${id}`,
          type: 'DELETE',
          data: {
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            if (response.success) {
              toastr.success(response.message, 'Berhasil');
              $('#kt_datatable_column_rendering').DataTable().ajax.reload();
            } else {
              toastr.error(response.message, 'Error');
            }
          },
          error: function(xhr) {
            toastr.error('Gagal menghapus data', 'Error');
          }
        });
      }
    });
  }

  // ==================== BULK DELETE ====================
  $('#bulk_delete_btn').on('click', function() {
    const selectedIds = [];
    $('.row-checkbox:checked').each(function() {
      selectedIds.push($(this).val());
    });

    if (selectedIds.length === 0) {
      toastr.warning('Pilih data yang akan dihapus', 'Perhatian');
      return;
    }

    Swal.fire({
      title: 'Hapus Data Terpilih?',
      text: `${selectedIds.length} data akan dihapus`,
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
        $.ajax({
          url: '{{ route('renja.bulk-destroy') }}',
          type: 'POST',
          data: {
            _token: '{{ csrf_token() }}',
            ids: selectedIds
          },
          success: function(response) {
            if (response.success) {
              toastr.success(response.message, 'Berhasil');
              $('#kt_datatable_column_rendering').DataTable().ajax.reload();
              $('.row-checkbox').prop('checked', false);
              updateToolbar();
            } else {
              toastr.error(response.message, 'Error');
            }
          },
          error: function(xhr) {
            toastr.error('Gagal menghapus data', 'Error');
          }
        });
      }
    });
  });
</script>
