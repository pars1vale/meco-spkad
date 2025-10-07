{{-- Modal Detail Standar Harga --}}
@foreach ($data as $item)
  <div class="modal fade" id="modal_detail_{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="fw-bold">Detail Standar Harga</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>
        <div class="modal-body py-10 px-lg-17">
          {{-- Info Standar Harga --}}
          <div class="mb-8">
            <h4 class="mb-5">Informasi Standar Harga</h4>
            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Kode:</div>
              <div class="col-md-8">{{ $item->kode_standar_harga }}</div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Tipe:</div>
              <div class="col-md-8"><span class="badge badge-light-info">{{ $item->tipe_standar_harga }}</span></div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Nama:</div>
              <div class="col-md-8">{{ $item->nama_standar_harga }}</div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Kelompok:</div>
              <div class="col-md-8">{{ $item->kelompokStandarHarga->nama_kelompok_standar_harga }}</div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Satuan:</div>
              <div class="col-md-8">{{ $item->satuan->nama_satuan }}</div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Harga:</div>
              <div class="col-md-8">Rp {{ number_format($item->harga, 2, ',', '.') }}</div>
            </div>
            @if ($item->spesifikasi)
              <div class="row mb-3">
                <div class="col-md-4 fw-bold">Spesifikasi:</div>
                <div class="col-md-8">{{ $item->spesifikasi }}</div>
              </div>
            @endif
            <div class="row mb-3">
              <div class="col-md-4 fw-bold">TKDN:</div>
              <div class="col-md-8">{{ $item->nilai_tkdn }}%</div>
            </div>
            <div class="row mb-3">
              <div class="col-md-4 fw-bold">PDN:</div>
              <div class="col-md-8">
                @if ($item->is_pdn)
                  <span class="badge badge-light-success">Ya</span>
                @else
                  <span class="badge badge-light-danger">Tidak</span>
                @endif
              </div>
            </div>
          </div>

          <div class="separator my-7"></div>

          {{-- Rekening Belanja Section --}}
          <div class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-5">
              <h4 class="mb-0">Rekening Belanja ({{ $item->rekeningBelanja->count() }})</h4>
              <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modal_add_rekening_{{ $item->id }}"
                data-bs-dismiss="modal">
                <i class="ki-outline ki-plus fs-2"></i>
                Tambah Rekening
              </button>
            </div>

            @if ($item->rekeningBelanja->isEmpty())
              <div class="alert alert-warning">
                <i class="ki-outline ki-information-5 fs-2 me-2"></i>
                Belum ada rekening belanja untuk standar harga ini
              </div>
            @else
              <div class="table-responsive">
                <table class="table table-row-bordered table-row-gray-300">
                  <thead>
                    <tr class="fw-bold fs-6 text-gray-800">
                      <th>Kode Akun</th>
                      <th>Nama Akun</th>
                      <th class="text-end">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($item->rekeningBelanja as $rekening)
                      <tr>
                        <td>{{ $rekening->kode_akun }}</td>
                        <td>{{ $rekening->nama_akun }}</td>
                        <td class="text-end">
                          <form action="{{ route('standar_harga.remove-rekening', $item->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="id_akun" value="{{ $rekening->id }}">
                            <button type="submit" class="btn btn-sm btn-light-danger btn-remove-rekening"
                              data-rekening="{{ $rekening->nama_akun }}">
                              <i class="ki-outline ki-trash fs-3"></i>
                            </button>
                          </form>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endforeach
