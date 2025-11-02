<div class="modal fade" id="kt_modal_add_program" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-650px">
    <div class="modal-content">
      <form class="form" action="{{ route('program.store') }}" method="POST" id="kt_modal_add_program_form">
        @csrf
        <div class="modal-header" id="kt_modal_add_program_header">
          <h2 class="fw-bold">Tambah Program</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>

        <div class="modal-body py-10 px-lg-17">
          <div class="scroll-y me-n7 pe-7" id="kt_modal_add_program_scroll">

            <!-- Bidang Urusan -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Bidang Urusan</label>
              <select class="form-select form-select-solid @error('id_bidang_urusan') is-invalid @enderror" data-control="select2"
                data-dropdown-parent="#kt_modal_add_program" name="id_bidang_urusan" required>
                <option value="">Pilih Bidang Urusan</option>
                @foreach ($listBidangUrusan->groupBy('nama_urusan') as $namaUrusan => $bidangUrusanGroup)
                  <optgroup label="{{ $namaUrusan }}">
                    @foreach ($bidangUrusanGroup as $bidangUrusan)
                      <option value="{{ $bidangUrusan->id }}" {{ old('id_bidang_urusan') == $bidangUrusan->id ? 'selected' : '' }}>
                        {{ $bidangUrusan->kode_bidang_urusan }} - {{ $bidangUrusan->nama_bidang_urusan }}
                      </option>
                    @endforeach
                  </optgroup>
                @endforeach
              </select>
              @error('id_bidang_urusan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Kode Program -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Kode Program</label>
              <input type="text" class="form-control form-control-solid @error('kode_program') is-invalid @enderror"
                placeholder="Masukkan kode program" name="kode_program" value="{{ old('kode_program') }}" maxlength="20" required />
              @error('kode_program')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">Maksimal 20 karakter</div>
            </div>

            <!-- Nama Program -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Program</label>
              <input type="text" class="form-control form-control-solid @error('nama_program') is-invalid @enderror"
                placeholder="Masukkan nama program" name="nama_program" value="{{ old('nama_program') }}" maxlength="255" required />
              @error('nama_program')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">Maksimal 255 karakter</div>
            </div>

          </div>
        </div>

        <div class="modal-footer flex-center">
          <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" id="kt_modal_add_program_submit" class="btn btn-primary">
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

@include('referensi.program.partials.scripts-modal')
