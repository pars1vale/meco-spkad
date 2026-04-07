<div class="modal fade" id="modal_tambah_pengeluaran" tabindex="-1" aria-labelledby="modal_tambah_pengeluaran_label" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">

      {{-- Header --}}
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="modal_tambah_pengeluaran_label">
          <i class="ki-outline ki-plus-circle fs-3 text-primary me-2"></i>
          Tambah Data Pembiayaan Pengeluaran
        </h5>
        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
          <i class="ki-outline ki-cross fs-2"></i>
        </div>
      </div>

      {{-- Body --}}
      <form id="form_tambah_pengeluaran" method="POST" action="{{ route('pembiayaan.pengeluaran.store', $id_skpd) }}">
        @csrf

        <div class="modal-body">

          {{-- Info SKPD (read-only) --}}
          <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed mb-6 p-4">
            <i class="ki-outline ki-information-5 fs-2tx text-primary me-4"></i>
            <div class="d-flex flex-stack flex-grow-1">
              <div class="fw-semibold">
                <span class="fs-7 text-muted">SKPD</span>
                <div class="fs-6 text-gray-700 fw-bold">
                  {{ $skpd->kode_skpd ?? ($skpd->kodeunit ?? '-') }} &mdash;
                  {{ $skpd->nama_skpd ?? ($skpd->namaunit ?? '-') }}
                </div>
              </div>
            </div>
          </div>

          {{-- Akun (Rekening) --}}
          <div class="fv-row mb-6">
            <label class="required fw-semibold fs-6 mb-2">Akun / Rekening</label>
            <select name="id_akun" id="select_id_akun_pengeluaran" class="form-select form-select-solid @error('id_akun') is-invalid @enderror"
              data-control="select2" data-placeholder="-- Pilih Akun --" data-allow-clear="true" required>
              <option></option>
              @foreach ($akunList as $akun)
                <option value="{{ $akun->id }}" {{ old('id_akun') == $akun->id ? 'selected' : '' }}>
                  {{ $akun->kode_akun }} &mdash; {{ $akun->nama_akun }}
                </option>
              @endforeach
            </select>
            @error('id_akun')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text text-muted fs-7">Pilih kode rekening pembiayaan pengeluaran yang sesuai.</div>
          </div>

          {{-- Keterangan / Uraian --}}
          <div class="fv-row mb-6">
            <label class="fw-semibold fs-6 mb-2">Keterangan</label>
            <textarea name="keterangan" id="input_keterangan_pengeluaran" class="form-control form-control-solid @error('keterangan') is-invalid @enderror"
              rows="3" placeholder="Masukkan keterangan / uraian pembiayaan pengeluaran...">{{ old('keterangan') }}</textarea>
            @error('keterangan')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text text-muted fs-7">
              Nilai ini akan disimpan ke kolom <code>keterangan</code> dan <code>uraian</code>.
            </div>
          </div>

          {{-- Nilai --}}
          <div class="fv-row mb-2">
            <label class="required fw-semibold fs-6 mb-2">Nilai (Rp)</label>
            <div class="input-group input-group-solid">
              <span class="input-group-text fw-semibold text-muted">Rp</span>
              <input type="text" name="nilai" id="input_nilai_pengeluaran"
                class="form-control form-control-solid @error('nilai') is-invalid @enderror" placeholder="0" value="{{ old('nilai') }}"
                inputmode="numeric" required />
              @error('nilai')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            {{-- Preview format rupiah --}}
            <div class="form-text text-muted fs-7 mt-1">
              <span id="preview_nilai_pengeluaran" class="text-gray-700 fw-semibold"></span>
            </div>
            <div class="form-text text-muted fs-7">
              Nilai ini akan disimpan ke kolom <code>total</code> dan <code>nilaimurni</code>.
            </div>
          </div>

        </div>{{-- /modal-body --}}

        {{-- Footer --}}
        <div class="modal-footer">
          <button type="button" class="btn btn-light fw-semibold" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-6 me-1"></i>Batal
          </button>
          <button type="submit" id="btn_simpan_pengeluaran" class="btn btn-primary fw-semibold">
            <span class="indicator-label">
              <i class="ki-outline ki-check fs-6 me-1"></i>Simpan
            </span>
            <span class="indicator-progress" style="display:none;">
              <span class="spinner-border spinner-border-sm align-middle me-2"></span>Menyimpan...
            </span>
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

{{-- Script modal (format rupiah, reset, auto-open on error) --}}
<script>
  "use strict";

  var KTModalTambahPengeluaran = function() {

    function formatRibuan(val) {
      val = val.replace(/\D/g, '');
      return val.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function toNumber(val) {
      return val.replace(/\./g, '');
    }

    function updatePreview(raw) {
      var preview = document.getElementById('preview_nilai_pengeluaran');
      if (!preview) return;
      var num = parseFloat(raw) || 0;
      preview.textContent = num > 0 ? 'Terbaca: Rp ' + num.toLocaleString('id-ID') : '';
    }

    return {
      init: function() {
        var inputNilai = document.getElementById('input_nilai_pengeluaran');

        if (inputNilai) {
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
          var form = document.getElementById('form_tambah_pengeluaran');
          if (form) {
            form.addEventListener('submit', function() {
              inputNilai.value = toNumber(inputNilai.value);
              var btn = document.getElementById('btn_simpan_pengeluaran');
              if (btn) {
                btn.querySelector('.indicator-label').style.display = 'none';
                btn.querySelector('.indicator-progress').style.display = 'inline-block';
                btn.setAttribute('disabled', 'disabled');
              }
            });
          }
        }

        // Reset form & Select2 saat modal ditutup
        var modalEl = document.getElementById('modal_tambah_pengeluaran');
        if (modalEl) {
          modalEl.addEventListener('hidden.bs.modal', function() {
            var form = document.getElementById('form_tambah_pengeluaran');
            if (form) form.reset();

            var sel = $('#select_id_akun_pengeluaran');
            if (sel.length && sel.data('select2')) sel.val(null).trigger('change');

            var preview = document.getElementById('preview_nilai_pengeluaran');
            if (preview) preview.textContent = '';

            var btn = document.getElementById('btn_simpan_pengeluaran');
            if (btn) {
              btn.querySelector('.indicator-label').style.display = 'inline-block';
              btn.querySelector('.indicator-progress').style.display = 'none';
              btn.removeAttribute('disabled');
            }
          });

          // Auto-buka modal jika ada error validasi dari server
          @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
              new bootstrap.Modal(modalEl).show();
            });
          @endif
        }
      }
    };
  }();

  document.addEventListener('DOMContentLoaded', function() {
    KTModalTambahPengeluaran.init();
  });
</script>
