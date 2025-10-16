<div class="modal fade" id="kt_modal_add_kegiatan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-650px">
    <div class="modal-content">
      <form class="form" action="{{ route('kegiatan.store') }}" method="POST" id="kt_modal_add_kegiatan_form">
        @csrf
        <div class="modal-header" id="kt_modal_add_kegiatan_header">
          <h2 class="fw-bold">Tambah Kegiatan</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>

        <div class="modal-body py-10 px-lg-17">
          <div class="scroll-y me-n7 pe-7" id="kt_modal_add_kegiatan_scroll">

            <!-- Program -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Program</label>
              <select class="form-select form-select-solid @error('id_program') is-invalid @enderror" name="id_program" required>
                <option value="">Pilih Program</option>
                @php
                  $currentUrusan = null;
                  $currentBidang = null;
                @endphp
                @foreach ($listProgram as $program)
                  @if ($currentUrusan !== $program->nama_urusan)
                    @if ($currentUrusan !== null)
                      </optgroup>
                    @endif
                    <optgroup label="{{ $program->nama_urusan }}">
                      @php
                        $currentUrusan = $program->nama_urusan;
                        $currentBidang = null;
                      @endphp
                  @endif
                  @if ($currentBidang !== $program->nama_bidang_urusan)
                    @if ($currentBidang !== null)
                      </optgroup>
                    @endif
                    <optgroup label="&nbsp;&nbsp;{{ $program->nama_bidang_urusan }}">
                      @php $currentBidang = $program->nama_bidang_urusan; @endphp
                  @endif
                  <option value="{{ $program->id }}" {{ old('id_program') == $program->id ? 'selected' : '' }}>
                    &nbsp;&nbsp;&nbsp;&nbsp;{{ $program->kode_program }} - {{ $program->nama_program }}
                  </option>
                @endforeach
                @if ($currentBidang !== null)
                  </optgroup>
                @endif
                @if ($currentUrusan !== null)
                  </optgroup>
                @endif
              </select>
              @error('id_program')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Kode Kegiatan -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Kode Kegiatan</label>
              <input type="text" class="form-control form-control-solid @error('kode_kegiatan') is-invalid @enderror"
                placeholder="Masukkan kode kegiatan" name="kode_kegiatan" value="{{ old('kode_kegiatan') }}" maxlength="20" required />
              @error('kode_kegiatan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">Maksimal 20 karakter</div>
            </div>

            <!-- Nama Kegiatan -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Kegiatan</label>
              <textarea class="form-control form-control-solid @error('nama_kegiatan') is-invalid @enderror" rows="3" placeholder="Masukkan nama kegiatan"
                name="nama_kegiatan" maxlength="500" required>{{ old('nama_kegiatan') }}</textarea>
              @error('nama_kegiatan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">Maksimal 500 karakter</div>
            </div>

          </div>
        </div>

        <div class="modal-footer flex-center">
          <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" id="kt_modal_add_kegiatan_submit" class="btn btn-primary">
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
