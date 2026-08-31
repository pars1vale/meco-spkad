<script>
  // Dibungkus DOMContentLoaded -- jQuery baru di-load di footer master layout (pola sama RENJA).
  document.addEventListener('DOMContentLoaded', function() {
    var modalEl = document.getElementById('modal-cetak-rka-pembiayaan');
    var modal = new bootstrap.Modal(modalEl);
    var flatpickrInstance = null;
    var hasDefaultTtd = false;

    var inputTanggal = document.getElementById('cetak-tanggal-ttd');
    var inputNama = document.getElementById('cetak-nama-ttd');
    var inputNip = document.getElementById('cetak-nip-ttd');

    if (window.flatpickr) {
      flatpickr(inputTanggal, {
        dateFormat: 'd-m-Y',
        allowInput: true
      });
      flatpickrInstance = inputTanggal._flatpickr;
    }

    document.querySelectorAll('.btn-cetak-rka-pembiayaan').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var idSkpd = btn.getAttribute('data-id-skpd');
        var namaSkpd = btn.getAttribute('data-nama-skpd');
        var urlTtdDefault = btn.getAttribute('data-url-ttd-default');

        document.getElementById('cetak-id-skpd').value = idSkpd;
        document.getElementById('cetak-nama-skpd').value = namaSkpd;
        inputTanggal.value = '';
        inputNama.value = '';
        inputNip.value = '';
        inputNama.disabled = false;
        inputNip.disabled = false;
        if (flatpickrInstance) {
          flatpickrInstance.clear();
        }

        fetch(urlTtdDefault)
          .then(function(res) {
            return res.json();
          })
          .then(function(data) {
            hasDefaultTtd = !!data.hasDefault;
            if (hasDefaultTtd) {
              // Ada default -- tetap ditampilkan sebagai info, tapi disabled (tidak bisa diedit).
              inputNama.value = data.nama;
              inputNip.value = data.nip;
              inputNama.disabled = true;
              inputNip.disabled = true;
            } else {
              // Tidak ada default -- field kosong & enabled, wajib diisi manual.
              inputNama.disabled = false;
              inputNip.disabled = false;
            }
          })
          .catch(function() {
            Swal.fire('Gagal', 'Tidak bisa mengambil data default penandatangan.', 'error');
          });

        modal.show();
      });
    });

    inputNip.addEventListener('input', function() {
      inputNip.value = inputNip.value.replace(/\D/g, '').slice(0, 18);
    });

    document.getElementById('btn-submit-cetak-rka-pembiayaan').addEventListener('click', function() {
      var idSkpd = document.getElementById('cetak-id-skpd').value;
      var tanggal = inputTanggal.value;
      var namaTtd = inputNama.value;
      var nipTtd = inputNip.value;

      if (!/^\d{2}-\d{2}-\d{4}$/.test(tanggal)) {
        Swal.fire('Validasi', 'Tanggal wajib diisi format dd-mm-yyyy.', 'warning');
        return;
      }

      // Validasi manual hanya berlaku kalau tidak ada default (field enabled).
      if (!hasDefaultTtd) {
        if (!namaTtd.trim()) {
          Swal.fire('Validasi', 'Nama penandatangan wajib diisi.', 'warning');
          return;
        }
        if (!/^\d{1,18}$/.test(nipTtd)) {
          Swal.fire('Validasi', 'NIP wajib angka, maksimal 18 digit.', 'warning');
          return;
        }
      }

      var form = document.getElementById('form-cetak-rka-pembiayaan');
      var urlBase = form.getAttribute('data-url-cetak-base');
      var params = new URLSearchParams({
        tanggal_ttd: tanggal,
        nama_ttd: namaTtd,
        nip_ttd: nipTtd,
      });

      window.open(urlBase + '/' + idSkpd + '/cetak?' + params.toString(), '_blank');
      modal.hide();
    });
  });
</script>
