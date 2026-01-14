{{-- Modal Detail Data SSH --}}
@foreach ($data as $item)
  <div class="modal fade" id="modal_detail_{{ $item->id_standar_harga }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered mw-800px">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="fw-bold">Detail Data SSH</h2>
          <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
            <i class="ki-outline ki-cross fs-1"></i>
          </div>
        </div>
        <div class="modal-body py-10 px-lg-17">
          {{-- Info SSH --}}
          <div class="mb-5">
            <h4 class="mb-5">Informasi Standar Harga</h4>

            <div class="row mb-3">
              <div class="col-md-4 fw-bold">ID Standar Harga:</div>
              <div class="col-md-8"><span class="badge badge-light-primary">{{ $item->id_standar_harga }}</span></div>
            </div>

            <div class="row mb-3">
              <div class="col-md-4 fw-bold">ID Unik:</div>
              <div class="col-md-8"><code>{{ $item->id_unik }}</code></div>
            </div>

            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Kode:</div>
              <div class="col-md-8">{{ $item->kode_standar_harga }}</div>
            </div>

            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Tipe:</div>
              <div class="col-md-8">
                @switch($item->tipe_standar_harga)
                  @case('SSH')
                    <span class="badge badge-light-success">{{ $item->tipe_standar_harga }}</span>
                  @break

                  @case('SBU')
                    <span class="badge badge-light-primary">{{ $item->tipe_standar_harga }}</span>
                  @break

                  @case('HSPK')
                    <span class="badge badge-light-info">{{ $item->tipe_standar_harga }}</span>
                  @break

                  @case('ASB')
                    <span class="badge badge-light-warning">{{ $item->tipe_standar_harga }}</span>
                  @break
                @endswitch
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Nama:</div>
              <div class="col-md-8">{{ $item->nama_standar_harga }}</div>
            </div>

            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Kelompok:</div>
              <div class="col-md-8">
                <div class="fw-bold text-gray-800">{{ $item->kode_kel_standar_harga }}</div>
                <div class="text-muted">{{ $item->nama_kel_standar_harga }}</div>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Satuan:</div>
              <div class="col-md-8">{{ $item->satuan }}</div>
            </div>

            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Harga:</div>
              <div class="col-md-8 fw-bold text-success">Rp {{ number_format($item->harga, 2, ',', '.') }}</div>
            </div>

            @if ($item->spek)
              <div class="row mb-3">
                <div class="col-md-4 fw-bold">Spesifikasi:</div>
                <div class="col-md-8">{{ $item->spek }}</div>
              </div>
            @endif

            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Tahun Anggaran:</div>
              <div class="col-md-8"><span class="badge badge-light-dark">{{ $item->tahun }}</span></div>
            </div>

            <div class="row mb-3">
              <div class="col-md-4 fw-bold">ID Daerah:</div>
              <div class="col-md-8">{{ $item->id_daerah }}</div>
            </div>

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

            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Status Lock:</div>
              <div class="col-md-8">
                @if ($item->is_locked)
                  <span class="badge badge-light-warning"><i class="ki-outline ki-lock fs-3"></i> Terkunci</span>
                @else
                  <span class="badge badge-light-success"><i class="ki-outline ki-lock-2 fs-3"></i> Tidak Terkunci</span>
                @endif
              </div>
            </div>

            @if ($item->ket_teks)
              <div class="separator my-5"></div>
              <div class="row mb-3">
                <div class="col-md-4 fw-bold">Keterangan:</div>
                <div class="col-md-8">{{ $item->ket_teks }}</div>
              </div>
            @endif

            <div class="separator my-5"></div>

            {{-- Rekening Belanja Section --}}
            <div class="mb-5">
              <div class="d-flex justify-content-between align-items-center mb-5">
                <h4 class="mb-0">Rekening Belanja ({{ $item->rekeningBelanja->count() }})</h4>
                @if (!$item->is_locked)
                  <a href="{{ route('data_ssh.edit', $item->id_standar_harga) }}" class="btn btn-sm btn-primary">
                    <i class="ki-outline ki-plus fs-2"></i>
                    Kelola Rekening
                  </a>
                @endif
              </div>

              @if ($item->rekeningBelanja->isEmpty())
                <div class="alert alert-warning d-flex align-items-center">
                  <i class="ki-outline ki-information-5 fs-2 me-2"></i>
                  <span>Belum ada rekening belanja untuk data SSH ini</span>
                </div>
              @else
                <div class="table-responsive">
                  <table class="table table-row-bordered table-row-gray-300">
                    <thead>
                      <tr class="fw-bold fs-6 text-gray-800">
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Tahun</th>
                        <th class="text-center">Status</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($item->rekeningBelanja as $rekening)
                        <tr>
                          <td>{{ $rekening->kode_akun }}</td>
                          <td>{{ $rekening->nama_akun }}</td>
                          <td><span class="badge badge-light-dark">{{ $rekening->tahun_anggaran }}</span></td>
                          <td class="text-center">
                            @if ($rekening->active)
                              <span class="badge badge-light-success">Aktif</span>
                            @else
                              <span class="badge badge-light-danger">Tidak Aktif</span>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
            </div>

            <div class="separator my-5"></div>

            <div class="row mb-3">
              <div class="col-md-4 fw-bold">Dibuat:</div>
              <div class="col-md-8 text-muted">{{ $item->created_at->format('d/m/Y H:i:s') }}</div>
            </div>

            @if ($item->updated_at && $item->updated_at != $item->created_at)
              <div class="row mb-3">
                <div class="col-md-4 fw-bold">Terakhir diupdate:</div>
                <div class="col-md-8 text-muted">{{ $item->updated_at->format('d/m/Y H:i:s') }}</div>
              </div>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
          @if (!$item->is_locked)
            <a href="{{ route('data_ssh.edit', $item->id_standar_harga) }}" class="btn btn-primary">
              <i class="ki-outline ki-pencil fs-2"></i>
              Edit Data
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>
@endforeach
