@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        {{-- Modal otomatis tampil --}}
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5)">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
              <div class="modal-header">
                <h5 class="modal-title">Pilih Tahun Anggaran</h5>
              </div>
              <div class="modal-body">
                <p class="text-muted">
                  Selamat datang, <strong>{{ auth()->user()->username }}</strong>!
                  Silakan pilih tahun anggaran untuk melanjutkan.
                </p>
                <form method="POST" action="{{ route('tahun-anggaran.simpan') }}">
                  @csrf
                  <div class="mb-3">
                    <label class="form-label fw-bold">Tahun Anggaran</label>
                    <select name="tahun_anggaran" class="form-select @error('tahun_anggaran') is-invalid @enderror">
                      <option value="">-- Pilih Tahun --</option>
                      @foreach ($tahunList as $tahun)
                        <option value="{{ $tahun }}">{{ $tahun }}</option>
                      @endforeach
                    </select>
                    @error('tahun_anggaran')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                      Masuk ke Dashboard
                    </button>
                  </div>
                </form>
              </div>
              <div class="modal-footer justify-content-center">
                <form method="GET" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="btn btn-sm btn-link text-danger">
                    Logout
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
