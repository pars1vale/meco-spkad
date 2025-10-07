<div class="modal fade" id="kt_modal_add_standar_harga" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered mw-900px">
    <div class="modal-content">
      <form class="form" id="kt_modal_add_standar_harga_form">
        @csrf
        <div class="modal-header">
          <h2 class="fw-bold">Tambah Standar Harga</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>

        <div class="modal-body py-10 px-lg-17">
          <div class="scroll-y me-n7 pe-7" style="max-height: 70vh">

            <div class="row">
              <div class="col-md-6">
                <!-- Kode Standar Harga -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Kode Standar Harga</label>
                  <input type="text" class="form-control form-control-solid" placeholder="Contoh: 1.1.12.01.01.0001" name="kode_standar_harga"
                    maxlength="50" required />
                  <div class="invalid-feedback"></div>
                </div>

                <!-- Tipe Standar Harga -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Tipe Standar Harga</label>
                  <div class="form-text mb-3">Pilih salah satu tipe standar harga</div>

                  <div class="d-flex flex-wrap gap-3">
                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input tipe-standar-harga-radio" type="radio" value="SSH" id="tipeSSH" name="tipe_standar_harga"
                        required />
                      <label class="form-check-label fw-bold" for="tipeSSH">SSH</label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input tipe-standar-harga-radio" type="radio" value="HSPK" id="tipeHSPK"
                        name="tipe_standar_harga" />
                      <label class="form-check-label fw-bold" for="tipeHSPK">HSPK</label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input tipe-standar-harga-radio" type="radio" value="ASB" id="tipeASB"
                        name="tipe_standar_harga" />
                      <label class="form-check-label fw-bold" for="tipeASB">ASB</label>
                    </div>

                    <div class="form-check form-check-custom form-check-solid">
                      <input class="form-check-input tipe-standar-harga-radio" type="radio" value="SBU" id="tipeSBU"
                        name="tipe_standar_harga" />
                      <label class="form-check-label fw-bold" for="tipeSBU">SBU</label>
                    </div>
                  </div>

                  <div class="invalid-feedback d-none" id="tipe-standar-harga-error">
                    Anda harus memilih salah satu tipe standar harga
                  </div>
                </div>

                <!-- Kelompok Standar Harga -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Kelompok Standar Harga</label>
                  <select class="form-select form-select-solid" name="id_kelompok_standar_harga" id="kelompok_select" required disabled>
                    <option value="">Pilih tipe terlebih dahulu</option>
                  </select>
                  <div class="invalid-feedback"></div>
                  <div class="form-text">Kelompok akan ditampilkan sesuai tipe yang dipilih</div>
                </div>

                <!-- Satuan -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Satuan</label>
                  <select class="form-select form-select-solid" name="id_satuan" required>
                    <option value="">Pilih Satuan</option>
                    @foreach ($satuan as $sat)
                      <option value="{{ $sat->id }}">{{ $sat->nama_satuan }}</option>
                    @endforeach
                  </select>
                  <div class="invalid-feedback"></div>
                </div>
              </div>

              <div class="col-md-6">
                <!-- Harga -->
                <div class="fv-row mb-7">
                  <label class="required fs-6 fw-semibold mb-2">Harga</label>
                  <input type="number" class="form-control form-control-solid" placeholder="Masukkan harga" name="harga" step="0.01"
                    min="0" required />
                  <div class="invalid-feedback"></div>
                </div>

                <!-- Nilai TKDN -->
                <div class="fv-row mb-7">
                  <label class="fs-6 fw-semibold mb-2">Nilai TKDN (%)</label>
                  <input type="number" class="form-control form-control-solid" placeholder="0-100" name="nilai_tkdn" step="0.01" min="0"
                    max="100" value="0" />
                  <div class="invalid-feedback"></div>
                </div>

                <!-- Is PDN -->
                <div class="fv-row mb-7">
                  <div class="form-check form-switch form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" id="isPdnSwitch" name="is_pdn" />
                    <label class="form-check-label" for="isPdnSwitch">
                      Produk Dalam Negeri (PDN)
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Nama Standar Harga -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Nama Standar Harga</label>
              <textarea class="form-control form-control-solid" rows="3" placeholder="Masukkan nama standar harga" name="nama_standar_harga" required></textarea>
              <div class="invalid-feedback"></div>
            </div>

            <!-- Spesifikasi -->
            <div class="fv-row mb-7">
              <label class="fs-6 fw-semibold mb-2">Spesifikasi</label>
              <textarea class="form-control form-control-solid" rows="3" placeholder="Masukkan spesifikasi (opsional)" name="spesifikasi"></textarea>
              <div class="invalid-feedback"></div>
            </div>

            <div class="separator my-7"></div>

            <!-- Rekening Belanja dengan Repeater -->
            <div class="fv-row mb-7">
              <label class="required fs-6 fw-semibold mb-2">Rekening Belanja (Akun)</label>
              <div class="form-text mb-3">Tambahkan minimal satu rekening belanja</div>

              <!--begin::Repeater-->
              <div id="kt_rekening_repeater">
                <!--begin::Form group-->
                <div class="form-group">
                  <div data-repeater-list="rekening_belanja">
                    <div data-repeater-item>
                      <div class="form-group row align-items-center mb-5">
                        <div class="col-md-10">
                          <select class="form-select form-select-solid" data-kt-repeater="select2" data-placeholder="Pilih rekening belanja"
                            name="id_akun" required>
                            <option value="">Pilih Rekening</option>
                            @foreach ($akun as $ak)
                              <option value="{{ $ak->id }}">{{ $ak->kode_akun }} - {{ $ak->nama_akun }}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-md-2">
                          <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-icon btn-light-danger">
                            <i class="ki-outline ki-trash fs-3"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--end::Form group-->

                <!--begin::Form group-->
                <div class="form-group">
                  <a href="javascript:;" data-repeater-create class="btn btn-sm btn-light-primary">
                    <i class="ki-outline ki-plus fs-3"></i>
                    Tambah Rekening
                  </a>
                </div>
                <!--end::Form group-->
              </div>
              <!--end::Repeater-->

              <div class="invalid-feedback d-none" id="rekening-error">
                Minimal satu rekening belanja harus dipilih
              </div>
            </div>

          </div>
        </div>

        <div class="modal-footer flex-center">
          <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" id="kt_modal_add_standar_harga_submit" class="btn btn-primary">
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
