<script>
  document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('kt_modal_cetak_rka_pendapatan');
    var modal = new bootstrap.Modal(modalEl);

    var currentIdSkpd = null;
    var currentCetakUrlTemplate = null; // diisi dari data-url-cetak tombol yang diklik
    var hasDefault = false;

    var $tanggal = $('#input_tanggal_ttd_rka_pendapatan');
    var $nama = $('#input_nama_ttd_rka_pendapatan');
    var $nip = $('#input_nip_ttd_rka_pendapatan');
    var $noteNama = $('#note_nama_ttd_rka_pendapatan');
    var $noteNip = $('#note_nip_ttd_rka_pendapatan');
    var $errorNip = $('#error_nip_ttd_rka_pendapatan');

    // Flatpickr — instance via _flatpickr, bukan return value binding jQuery (standing rule proyek)
    $tanggal.flatpickr({
      dateFormat: 'd-m-Y'
    });

    $(document).on('click', '.btn-cetak-rka-pendapatan', function(e) {
      e.preventDefault();

      currentIdSkpd = $(this).data('id-skpd');
      var namaSkpd = $(this).data('nama-skpd');
      var urlTtdDefault = $(this).data('url-ttd-default');
      currentCetakUrlTemplate = $(this).data('url-cetak');

      document.getElementById('modal_cetak_rka_pendapatan_nama_skpd').innerText = 'Cetak RKA Pendapatan — ' + namaSkpd;

      // reset form
      $tanggal.val('');
      var fp = $tanggal[0]._flatpickr;
      if (fp) {
        fp.clear();
      }
      $nama.val('').prop('disabled', false);
      $nip.val('').prop('disabled', false);
      $noteNama.text('');
      $noteNip.text('');
      $errorNip.hide();
      hasDefault = false;

      $.ajax({
        url: urlTtdDefault,
        method: 'GET',
        success: function(res) {
          hasDefault = !!res.hasDefault;
          if (hasDefault) {
            $nama.val(res.nama).prop('disabled', true);
            $nip.val(res.nip).prop('disabled', true);
            $noteNama.text('Terisi otomatis dari data unit (tidak dapat diubah).');
            $noteNip.text('Terisi otomatis dari data unit (tidak dapat diubah).');
          } else {
            $noteNama.text('Data kepala unit belum tersedia — isi manual.');
            $noteNip.text('Data kepala unit belum tersedia — isi manual.');
          }
        },
        error: function() {
          hasDefault = false;
          $noteNama.text('Gagal memuat data default — isi manual.');
          $noteNip.text('Gagal memuat data default — isi manual.');
        }
      });

      modal.show();
    });

    $('#btn_submit_cetak_rka_pendapatan').on('click', function() {
      var tanggalTtd = $tanggal.val();

      if (!/^\d{2}-\d{2}-\d{4}$/.test(tanggalTtd)) {
        alert('Tanggal pengesahan wajib diisi dengan format dd-mm-yyyy.');
        return;
      }

      var namaTtd = $nama.val();
      var nipTtd = $nip.val();

      // Validasi manual nama/NIP hanya berlaku kalau tidak ada default (standing rule proyek)
      if (!hasDefault) {
        if (!namaTtd || !namaTtd.trim()) {
          alert('Nama penandatangan wajib diisi.');
          return;
        }
        if (!/^\d{1,18}$/.test(nipTtd)) {
          $errorNip.show();
          return;
        }
      }
      $errorNip.hide();

      if (!currentCetakUrlTemplate) {
        alert('URL cetak tidak ditemukan pada tombol yang diklik.');
        return;
      }

      var params = $.param({
        tanggal_ttd: tanggalTtd,
        nama_ttd: namaTtd,
        nip_ttd: nipTtd
      });

      window.location.href = currentCetakUrlTemplate + '?' + params;
    });
  });
</script>
