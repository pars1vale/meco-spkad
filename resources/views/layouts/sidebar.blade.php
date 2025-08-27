<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
  data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="start"
  data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
  <div class="app-sidebar-wrapper">
    <div id="kt_app_sidebar_wrapper" class="hover-scroll-y my-5 my-lg-2 mx-4" data-kt-scroll="true"
      data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header"
      data-kt-scroll-wrappers="#kt_app_sidebar_wrapper" data-kt-scroll-offset="5px">
      <div id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false"
        class="app-sidebar-menu-primary menu menu-column menu-rounded menu-sub-indention menu-state-bullet-primary px-3 mb-5">
        <div class="menu-item">
          <a href="{{ route('home') }}" class="menu-link {{ Request::is('home') ? 'active' : '' }}">
            <span class="menu-icon">
              <i class="ki-outline ki-home-2 fs-2"></i>
            </span>
            <span class="menu-title">Dashboards</span>
          </a>
        </div>
        {{-- refersni --}}
        <div data-kt-menu-trigger="click" class="menu-item {{ Request::routeIs('referensi.*') ? 'show' : '' }} menu-accordion">
          <span class="menu-link">
            <span class="menu-icon">
              <i class="ki-outline ki-pin fs-2"></i>
            </span>
            <span class="menu-title">Referensi</span>
            <span class="menu-arrow"></span>
          </span>
          <div class="menu-sub menu-sub-accordion">
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('referensi.urusan.index') ? 'active' : '' }}" href="{{ route('referensi.urusan.index') }}"
                title="Seluruh Data Urusan" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Urusan</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('referensi.bidang-urusan.index') ? 'active' : '' }}"
                href="{{ route('referensi.bidang-urusan.index') }}" title="Seluruh Data Bidang Urusan" data-bs-toggle="tooltip"
                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Bidang Urusan</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('referensi.program.index') ? 'active' : '' }}" href="{{ route('referensi.program.index') }}"
                title="Seluruh Data Program" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Program</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('referensi.kegiatan.index') ? 'active' : '' }}" href="{{ route('referensi.kegiatan.index') }}"
                title="Seluruh Data Kegiatan" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Kegiatan</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('referensi.sub-kegiatan.index') ? 'active' : '' }}"
                href="{{ route('referensi.sub-kegiatan.index') }}" title="Seluruh Data Sub Kegiatan" data-bs-toggle="tooltip" data-bs-trigger="hover"
                data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Sub Kegiatan</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('referensi.akun.index') ? 'active' : '' }}" href="{{ route('referensi.akun.index') }}"
                title="Seluruh Data Akun" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Akun</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{ Request::routeIs('referensi.sumber-dana.index') ? 'active' : '' }}"
                href="{{ route('referensi.sumber-dana.index') }}" title="Seluruh Data Sumber Dana" data-bs-toggle="tooltip"
                data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Sumber Dana</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link" href="https://preview.keenthemes.com/html/metronic/docs/getting-started/changelog" target="_blank"
                title="berikan tooltip di attrubute 'title'" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click"
                data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Mapping Sub Kegiatan - SPM</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link" href="https://preview.keenthemes.com/html/metronic/docs/getting-started/changelog" target="_blank"
                title="berikan tooltip di attrubute 'title'" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click"
                data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Mapping Sub Kegiatan - Kemiskinan Ekstrim</span>
              </a>
            </div>
          </div>
        </div>
        {{-- pengaturan --}}
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
          <span class="menu-link">
            <span class="menu-icon">
              <i class="ki-outline ki-gear fs-2"></i>
            </span>
            <span class="menu-title">Pengaturan</span>
            <span class="menu-arrow"></span>
          </span>
          <div class="menu-sub menu-sub-accordion">
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
              <span class="menu-link">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Profil</span>
                <span class="menu-arrow"></span>
              </span>
              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">

                  <a class="menu-link" href="pages/user-profile/overview.html">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Perangkat Daerah</span>
                  </a>

                </div>
              </div>
            </div>
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
              <span class="menu-link">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">User</span>
                <span class="menu-arrow"></span>
              </span>
              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                  <a class="menu-link" href="account/overview.html">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Penyelia Keuangan</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{-- analisis dan rekap datas --}}
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
          <span class="menu-link">
            <span class="menu-icon">
              <i class="ki-outline ki-book-open fs-2"></i>
            </span>
            <span class="menu-title">Analisis dan Rekap Data</span>
            <span class="menu-arrow"></span>
          </span>
          <div class="menu-sub menu-sub-accordion">
            <div class="menu-item">
              <a class="menu-link {{-- {{ Request::routeIs('bidang-urusan.index') ? 'active' : '' }} --}}" href="{{-- {{ route('bidang-urusan.index') }} --}}" title="Check out over 200 in-house components"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Rekap Data</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link" {{-- {{ Request::routeIs('referensi.program.index') ? 'active' : '' }}
                                    --}} href="{{-- {{ route('referensi.program.index') }} --}}" title="Check out the complete documentation"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Ringkasan</span>
              </a>
            </div>
          </div>
        </div>
        {{-- RKPD --}}
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
          <span class="menu-link">
            <span class="menu-icon">
              <i class="ki-outline ki-element-8 fs-2"></i>
            </span>
            <span class="menu-title">RKPD</span>
            <span class="menu-arrow"></span>
          </span>
          <div class="menu-sub menu-sub-accordion">
            <div class="menu-item">
              <a class="menu-link {{-- {{ Request::routeIs('bidang-urusan.index') ? 'active' : '' }} --}}" href="{{-- {{ route('bidang-urusan.index') }} --}}" title="Check out over 200 in-house components"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Jadwal RKPD</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link" {{-- {{ Request::routeIs('referensi.program.index') ? 'active' : '' }}
                                    --}} href="{{-- {{ route('referensi.program.index') }} --}}" title="Check out the complete documentation"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Renja</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link" {{-- {{ Request::routeIs('referensi.program.index') ? 'active' : '' }}
                                    --}} href="{{-- {{ route('referensi.program.index') }} --}}" title="Check out the complete documentation"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Laporan</span>
              </a>
            </div>
          </div>
        </div>
        {{-- Penganggaran --}}
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
          <span class="menu-link">
            <span class="menu-icon">
              <i class="ki-outline ki-handcart fs-2"></i>
            </span>
            <span class="menu-title">Penganggaran</span>
            <span class="menu-arrow"></span>
          </span>
          <div class="menu-sub menu-sub-accordion">
            <div class="menu-item">
              <a class="menu-link {{-- {{ Request::routeIs('bidang-urusan.index') ? 'active' : '' }} --}}" href="{{-- {{ route('bidang-urusan.index') }} --}}" title="Check out over 200 in-house components"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Jadwal Penganggaran</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{-- {{ Request::routeIs('bidang-urusan.index') ? 'active' : '' }} --}}" href="{{-- {{ route('bidang-urusan.index') }} --}}" title="Check out over 200 in-house components"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Sub Kegiatan Belanja</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link {{-- {{ Request::routeIs('bidang-urusan.index') ? 'active' : '' }} --}}" href="{{-- {{ route('bidang-urusan.index') }} --}}" title="Check out over 200 in-house components"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Berita Acara Kesepakatan Penambahan Sub Kegiatan</span>
              </a>
            </div>
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
              <span class="menu-link">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Data Pendukung APBD</span>
                <span class="menu-arrow"></span>
              </span>
              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                  <a class="menu-link" href="pages/user-profile/overview.html">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Pegawai</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link" href="pages/user-profile/overview.html">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Piutang</span>
                  </a>
                </div>
              </div>
            </div>
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
              <span class="menu-link">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Laporan</span>
                <span class="menu-arrow"></span>
              </span>
              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                  <a class="menu-link" href="account/overview.html">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">KUA dan PPAS</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link" href="account/overview.html">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">APBD(Perda)</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link" href="account/overview.html">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">APBD(Penjabaran)</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link" href="account/overview.html">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">APBD(Perkada)</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link" href="account/overview.html">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Lampiran Tematik</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link" href="account/overview.html">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Lampiran Efisiensi</span>
                  </a>
                </div>
              </div>
            </div>
            <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
              <span class="menu-link">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Dokumen Anggaran</span>
                <span class="menu-arrow"></span>
              </span>
              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                  <a class="menu-link" href="account/overview.html">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">RKA SKPD</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link" href="account/overview.html">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">RKA Pendapatan</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link" href="account/overview.html">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">RKA Rekap Belanja</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link" href="account/overview.html">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">RKA Pembiayaan</span>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
        {{-- pendapatan --}}
        <div class="menu-item">
          <a href="#" class="menu-link {{-- {{ Request::is('home') ? 'active' : '' }} --}}">
            <span class="menu-icon">
              <i class="ki-outline ki-wallet fs-2"></i>
            </span>
            <span class="menu-title">Pendapatan</span>
          </a>
        </div>
        {{-- Pembiayaan --}}
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
          <span class="menu-link">
            <span class="menu-icon">
              <i class="ki-outline ki-two-credit-cart fs-2"></i>
            </span>
            <span class="menu-title">Pembiayaan</span>
            <span class="menu-arrow"></span>
          </span>
          <div class="menu-sub menu-sub-accordion">
            <div class="menu-item">
              <a class="menu-link {{-- {{ Request::routeIs('bidang-urusan.index') ? 'active' : '' }} --}}" href="{{-- {{ route('bidang-urusan.index') }} --}}" title="Check out over 200 in-house components"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Penerimaan</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link" {{-- {{ Request::routeIs('referensi.program.index') ? 'active' : '' }}
                                    --}} href="{{-- {{ route('referensi.program.index') }} --}}" title="Check out the complete documentation"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">Pengeluaran</span>
              </a>
            </div>
          </div>
        </div>
        {{-- Standar Harga Satuan --}}
        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
          <span class="menu-link">
            <span class="menu-icon">
              <i class="ki-outline ki-price-tag fs-2"></i>
            </span>
            <span class="menu-title">Standar Harga Satuan</span>
            <span class="menu-arrow"></span>
          </span>
          <div class="menu-sub menu-sub-accordion">
            <div class="menu-item">
              <a class="menu-link {{-- {{ Request::routeIs('bidang-urusan.index') ? 'active' : '' }} --}}" href="{{-- {{ route('bidang-urusan.index') }} --}}" title="Check out over 200 in-house components"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">SSH</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link" {{-- {{ Request::routeIs('referensi.program.index') ? 'active' : '' }}
                                    --}} href="{{-- {{ route('referensi.program.index') }} --}}" title="Check out the complete documentation"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">HSPK</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link" {{-- {{ Request::routeIs('referensi.program.index') ? 'active' : '' }}
                                    --}} href="{{-- {{ route('referensi.program.index') }} --}}" title="Check out the complete documentation"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">ASB</span>
              </a>
            </div>
            <div class="menu-item">
              <a class="menu-link" {{-- {{ Request::routeIs('referensi.program.index') ? 'active' : '' }}
                                    --}} href="{{-- {{ route('referensi.program.index') }} --}}" title="Check out the complete documentation"
                data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                <span class="menu-bullet">
                  <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">SBU</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
