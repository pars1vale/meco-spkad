<div class="modal fade" id="kt_modal_add_sub_kegiatan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-650px">
    <div class="modal-content">
      <form class="form" action="{{ route('sub-kegiatan.store') }}" method="POST" id="kt_modal_add_sub_kegiatan_form">
        @csrf
        <div class="modal-header" id="kt_modal_add_sub_kegiatan_header">
          <h2 class="fw-bold">Tambah Sub Kegiatan</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>

        <div class="modal-body py-10 px-lg-17">
          <div class="scroll-y me-n7 pe-7" id="kt_modal_add_sub_kegiatan_scroll">

            <!-- Kegiatan -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Kegiatan</label>
              <select name="id_kegiatan" data-control="select2" data-dropdown-parent="#kt_modal_add_sub_kegiatan"
                class="form-select form-select-solid @error('id_kegiatan') is-invalid @enderror" data-placeholder="Pilih Kegiatan"
                data-allow-clear="true" required>
                <option></option> {{-- penting untuk menampilkan placeholder di Select2 --}}
                @php
                  $currentUrusan = null;
                  $currentBidang = null;
                  $currentProgram = null;
                @endphp

                @foreach ($listKegiatan as $kegiatan)
                  @if ($currentUrusan !== $kegiatan->nama_urusan)
                    @if ($currentUrusan !== null)
                      </optgroup>
                    @endif
                    <optgroup label="{{ $kegiatan->nama_urusan }}">
                      @php
                        $currentUrusan = $kegiatan->nama_urusan;
                        $currentBidang = null;
                        $currentProgram = null;
                      @endphp
                  @endif

                  @if ($currentBidang !== $kegiatan->nama_bidang_urusan)
                    @if ($currentBidang !== null)
                      </optgroup>
                    @endif
                    <optgroup label="&nbsp;&nbsp;{{ $kegiatan->nama_bidang_urusan }}">
                      @php
                        $currentBidang = $kegiatan->nama_bidang_urusan;
                        $currentProgram = null;
                      @endphp
                  @endif

                  @if ($currentProgram !== $kegiatan->nama_program)
                    @if ($currentProgram !== null)
                      </optgroup>
                    @endif
                    <optgroup label="&nbsp;&nbsp;&nbsp;&nbsp;{{ $kegiatan->kode_program }} - {{ $kegiatan->nama_program }}">
                      @php $currentProgram = $kegiatan->nama_program; @endphp
                  @endif

                  <option value="{{ $kegiatan->id }}"
                    {{ old('id_kegiatan', isset($subKegiatan) ? $subKegiatan->id_kegiatan : '') == $kegiatan->id ? 'selected' : '' }}>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $kegiatan->kode_kegiatan }} - {{ $kegiatan->nama_kegiatan }}
                  </option>
                @endforeach

                @if ($currentProgram !== null)
                  </optgroup>
                @endif
                @if ($currentBidang !== null)
                  </optgroup>
                @endif
                @if ($currentUrusan !== null)
                  </optgroup>
                @endif
              </select>

              @error('id_kegiatan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Kode Sub Kegiatan -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Kode Sub Kegiatan</label>
              <input type="text" class="form-control form-control-solid @error('kode_sub_kegiatan') is-invalid @enderror"
                placeholder="Masukkan kode sub kegiatan" name="kode_sub_kegiatan" value="{{ old('kode_sub_kegiatan') }}" maxlength="20" required />
              @error('kode_sub_kegiatan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">Maksimal 20 karakter</div>
            </div>

            <!-- Nama Sub Kegiatan -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Sub Kegiatan</label>
              <textarea class="form-control form-control-solid @error('nama_sub_kegiatan') is-invalid @enderror" rows="3"
                placeholder="Masukkan nama sub kegiatan" name="nama_sub_kegiatan" maxlength="500" required>{{ old('nama_sub_kegiatan') }}</textarea>
              @error('nama_sub_kegiatan')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <div class="form-text">Maksimal 500 karakter</div>
            </div>

          </div>
        </div>

        <div class="modal-footer flex-center">
          <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" id="kt_modal_add_sub_kegiatan_submit" class="btn btn-primary">
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


{{-- @include('referensi.sub-kegiatan.partials.scripts-modal') --}}
