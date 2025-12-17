<div class="modal fade" id="kt_modal_add_bidang_urusan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-650px">
    <div class="modal-content">
      <form class="form" action="{{ route('referensi.bidang-urusan.store') }}" method="POST" id="kt_modal_add_bidang_urusan_form">
        @csrf
        <div class="modal-header" id="kt_modal_add_bidang_urusan_header">
          <h2 class="fw-bold">Tambah Bidang Urusan</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>

        <div class="modal-body py-10 px-lg-17">
          <div class="scroll-y me-n7 pe-7" id="kt_modal_add_bidang_urusan_scroll">

            <!-- Urusan -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Urusan</label>
              <select class="form-select form-select-solid @error('id_urusan') is-invalid @enderror" data-control="select2"
                data-dropdown-parent="#kt_modal_add_bidang_urusan" name="id_urusan" required>
                <option value="">Pilih Urusan</option>
                @foreach ($listUrusan as $urusan)
                  <option value="{{ $urusan->id }}" {{ old('id_urusan') == $urusan->id ? 'selected' : '' }}>
                    {{ $urusan->kode_urusan }} - {{ $urusan->nama_urusan }}
                  </option>
                @endforeach
              </select>
              @error('id_urusan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Kode Bidang Urusan -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Kode Bidang Urusan</label>
              <input type="text" class="form-control form-control-solid @error('kode_bidang_urusan') is-invalid @enderror"
                placeholder="Masukkan kode bidang urusan" name="kode_bidang_urusan" value="{{ old('kode_bidang_urusan') }}" maxlength="20" required />
              @error('kode_bidang_urusan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">Maksimal 20 karakter</div>
            </div>

            <!-- Nama Bidang Urusan -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Bidang Urusan</label>
              <input type="text" class="form-control form-control-solid @error('nama_bidang_urusan') is-invalid @enderror"
                placeholder="Masukkan nama bidang urusan" name="nama_bidang_urusan" value="{{ old('nama_bidang_urusan') }}" maxlength="255"
                required />
              @error('nama_bidang_urusan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">Maksimal 255 karakter</div>
            </div>

          </div>
        </div>

        <div class="modal-footer flex-center">
          <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" id="kt_modal_add_bidang_urusan_submit" class="btn btn-primary">
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

@include('referensi.bidang-urusan.partials.scripts-modal')
