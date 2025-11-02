@extends('layouts.master')
@section('content')
  <div class="d-flex flex-column flex-column-fluid">
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pt-6 pb-2">
      <!--begin::Toolbar container-->
      <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex align-items-stretch">
        <!--begin::Toolbar wrapper-->
        <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
          <!--begin::Page title-->
          <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
              Projects Dashboard</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
              <!--begin::Item-->
              <li class="breadcrumb-item text-muted">
                <a href="index.html" class="text-muted text-hover-primary">Home</a>
              </li>
              <!--end::Item-->
              <!--begin::Item-->
              <li class="breadcrumb-item">
                <span class="bullet bg-gray-500 w-5px h-2px"></span>
              </li>
              <!--end::Item-->
              <!--begin::Item-->
              <li class="breadcrumb-item text-muted">Dashboards</li>
              <!--end::Item-->
            </ul>
            <!--end::Breadcrumb-->
          </div>
          <!--end::Page title-->
          <!--begin::Actions-->
          <div class="d-flex align-items-center gap-2 gap-lg-3">
            <a href="#" class="btn btn-flex btn-outline btn-color-gray-700 btn-active-color-primary bg-body h-40px fs-7 fw-bold"
              data-bs-toggle="modal" data-bs-target="#kt_modal_view_users">Add Member</a>
            <a href="#" class="btn btn-flex btn-primary h-40px fs-7 fw-bold" data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">New
              Campaign</a>
          </div>
          <!--end::Actions-->
        </div>
        <!--end::Toolbar wrapper-->
      </div>
      <!--end::Toolbar container-->
    </div>
    <!--end::Toolbar-->
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
      <!--begin::Content container-->
      <div id="kt_app_content_container" class="app-container container-fluid">
        <!--begin::Row-->
        <div class="row gx-5 gx-xl-10 mb-xl-10">
          <!--begin::Col-->
          <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-10">
            <!--begin::Card widget 16-->
            <div class="card card-flush bgi-no-repeat bgi-size-contain bgi-position-x-center border-0 h-md-50 mb-5 mb-xl-10"
              style="background-color: #080655">
              <!--begin::Header-->
              <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                  <!--begin::Amount-->
                  <span class="fs-2hx fw-bold text-white me-2 lh-1 ls-n2">69</span>
                  <!--end::Amount-->
                  <!--begin::Subtitle-->
                  <span class="text-white opacity-50 pt-1 fw-semibold fs-6">Active Projects</span>
                  <!--end::Subtitle-->
                </div>
                <!--end::Title-->
              </div>
              <!--end::Header-->
              <!--begin::Card body-->
              <div class="card-body d-flex align-items-end pt-0">
                <!--begin::Progress-->
                <div class="d-flex align-items-center flex-column mt-3 w-100">
                  <div class="d-flex justify-content-between fw-bold fs-6 text-white opacity-50 w-100 mt-auto mb-2">
                    <span>43 Pending</span>
                    <span>72%</span>
                  </div>
                  <div class="h-8px mx-3 w-100 bg-light-danger rounded">
                    <div class="bg-danger rounded h-8px" role="progressbar" style="width: 72%;" aria-valuenow="50" aria-valuemin="0"
                      aria-valuemax="100"></div>
                  </div>
                </div>
                <!--end::Progress-->
              </div>
              <!--end::Card body-->
            </div>
            <!--end::Card widget 16-->
            <!--begin::Card widget 7-->
            <div class="card card-flush h-md-50 mb-5 mb-xl-10">
              <!--begin::Header-->
              <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                  <!--begin::Amount-->
                  <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">357</span>
                  <!--end::Amount-->
                  <!--begin::Subtitle-->
                  <span class="text-gray-500 pt-1 fw-semibold fs-6">Professionals</span>
                  <!--end::Subtitle-->
                </div>
                <!--end::Title-->
              </div>
              <!--end::Header-->
              <!--begin::Card body-->
              <div class="card-body d-flex flex-column justify-content-end pe-0">
                <!--begin::Title-->
                <span class="fs-6 fw-bolder text-gray-800 d-block mb-2">Today’s Heroes</span>
                <!--end::Title-->
                <!--begin::Users group-->
                <div class="symbol-group symbol-hover flex-nowrap">
                  <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Alan Warden">
                    <span class="symbol-label bg-warning text-inverse-warning fw-bold">A</span>
                  </div>
                  <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Michael Eberon">
                    {{-- <img alt="Pic" src="assets/media/avatars/300-11.jpg" /> --}}
                  </div>
                  <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Susan Redwood">
                    <span class="symbol-label bg-primary text-inverse-primary fw-bold">S</span>
                  </div>
                  <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Melody Macy">
                    {{-- <img alt="Pic" src="assets/media/avatars/300-2.jpg" /> --}}
                  </div>
                  <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Perry Matthew">
                    <span class="symbol-label bg-danger text-inverse-danger fw-bold">P</span>
                  </div>
                  <div class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip" title="Barry Walter">
                    {{-- <img alt="Pic" src="assets/media/avatars/300-12.jpg" /> --}}
                  </div>
                  <a href="#" class="symbol symbol-35px symbol-circle" data-bs-toggle="modal" data-bs-target="#kt_modal_view_users">
                    <span class="symbol-label bg-dark text-gray-300 fs-8 fw-bold">+42</span>
                  </a>
                </div>
                <!--end::Users group-->
              </div>
              <!--end::Card body-->
            </div>
            <!--end::Card widget 7-->
          </div>
          <!--end::Col-->
          <!--begin::Col-->
          <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-3 mb-10">
            <!--begin::Card widget 17-->
            <div class="card card-flush h-md-50 mb-5 mb-xl-10">
              <!--begin::Header-->
              <div class="card-header pt-5">
                <!--begin::Title-->
                <div class="card-title d-flex flex-column">
                  <!--begin::Info-->
                  <div class="d-flex align-items-center">
                    <!--begin::Currency-->
                    <span class="fs-4 fw-semibold text-gray-500 me-1 align-self-start">$</span>
                    <!--end::Currency-->
                    <!--begin::Amount-->
                    <span class="fs-2hx fw-bold text-gray-900 me-2 lh-1 ls-n2">69,700</span>
                    <!--end::Amount-->
                    <!--begin::Badge-->
                    <span class="badge badge-light-success fs-base">
                      <i class="ki-outline ki-arrow-up fs-5 text-success ms-n1"></i>2.2%</span>
                    <!--end::Badge-->
                  </div>
                  <!--end::Info-->
                  <!--begin::Subtitle-->
                  <span class="text-gray-500 pt-1 fw-semibold fs-6">Projects Earnings in April</span>
                  <!--end::Subtitle-->
                </div>
                <!--end::Title-->
              </div>
              <!--end::Header-->
              <!--begin::Card body-->
              <div class="card-body pt-2 pb-4 d-flex flex-wrap align-items-center">
                <!--begin::Chart-->
                <div class="d-flex flex-center me-5 pt-2">
                  <div id="kt_card_widget_17_chart" style="min-width: 70px; min-height: 70px" data-kt-size="70" data-kt-line="11"></div>
                </div>
                <!--end::Chart-->
                <!--begin::Labels-->
                <div class="d-flex flex-column content-justify-center flex-row-fluid">
                  <!--begin::Label-->
                  <div class="d-flex fw-semibold align-items-center">
                    <!--begin::Bullet-->
                    <div class="bullet w-8px h-3px rounded-2 bg-success me-3"></div>
                    <!--end::Bullet-->
                    <!--begin::Label-->
                    <div class="text-gray-500 flex-grow-1 me-4">Leaf CRM</div>
                    <!--end::Label-->
                    <!--begin::Stats-->
                    <div class="fw-bolder text-gray-700 text-xxl-end">$7,660</div>
                    <!--end::Stats-->
                  </div>
                  <!--end::Label-->
                  <!--begin::Label-->
                  <div class="d-flex fw-semibold align-items-center my-3">
                    <!--begin::Bullet-->
                    <div class="bullet w-8px h-3px rounded-2 bg-primary me-3"></div>
                    <!--end::Bullet-->
                    <!--begin::Label-->
                    <div class="text-gray-500 flex-grow-1 me-4">Mivy App</div>
                    <!--end::Label-->
                    <!--begin::Stats-->
                    <div class="fw-bolder text-gray-700 text-xxl-end">$2,820</div>
                    <!--end::Stats-->
                  </div>
                  <!--end::Label-->
                  <!--begin::Label-->
                  <div class="d-flex fw-semibold align-items-center">
                    <!--begin::Bullet-->
                    <div class="bullet w-8px h-3px rounded-2 me-3" style="background-color: #E4E6EF">
                    </div>
                    <!--end::Bullet-->
                    <!--begin::Label-->
                    <div class="text-gray-500 flex-grow-1 me-4">Others</div>
                    <!--end::Label-->
                    <!--begin::Stats-->
                    <div class="fw-bolder text-gray-700 text-xxl-end">$45,257</div>
                    <!--end::Stats-->
                  </div>
                  <!--end::Label-->
                </div>
                <!--end::Labels-->
              </div>
              <!--end::Card body-->
            </div>
            <!--end::Card widget 17-->
            <!--begin::List widget 25-->
            <div class="card card-flush h-lg-50">
              <!--begin::Header-->
              <div class="card-header pt-5">
                <!--begin::Title-->
                <h3 class="card-title text-gray-800">Highlights</h3>
                <!--end::Title-->
                <!--begin::Toolbar-->
                <div class="card-toolbar d-none">
                  <!--begin::Daterangepicker(defined in src/js/layout/app.js)-->
                  <div data-kt-daterangepicker="true" data-kt-daterangepicker-opens="left"
                    class="btn btn-sm btn-light d-flex align-items-center px-4">
                    <!--begin::Display range-->
                    <div class="text-gray-600 fw-bold">Loading date range...</div>
                    <!--end::Display range-->
                    <i class="ki-outline ki-calendar-8 fs-1 ms-2 me-0"></i>
                  </div>
                  <!--end::Daterangepicker-->
                </div>
                <!--end::Toolbar-->
              </div>
              <!--end::Header-->
              <!--begin::Body-->
              <div class="card-body pt-5">
                <!--begin::Item-->
                <div class="d-flex flex-stack">
                  <!--begin::Section-->
                  <div class="text-gray-700 fw-semibold fs-6 me-2">Avg. Client Rating</div>
                  <!--end::Section-->
                  <!--begin::Statistics-->
                  <div class="d-flex align-items-senter">
                    <i class="ki-outline ki-arrow-up-right fs-2 text-success me-2"></i>
                    <!--begin::Number-->
                    <span class="text-gray-900 fw-bolder fs-6">7.8</span>
                    <!--end::Number-->
                    <span class="text-gray-500 fw-bold fs-6">/10</span>
                  </div>
                  <!--end::Statistics-->
                </div>
                <!--end::Item-->
                <!--begin::Separator-->
                <div class="separator separator-dashed my-3"></div>
                <!--end::Separator-->
                <!--begin::Item-->
                <div class="d-flex flex-stack">
                  <!--begin::Section-->
                  <div class="text-gray-700 fw-semibold fs-6 me-2">Avg. Quotes</div>
                  <!--end::Section-->
                  <!--begin::Statistics-->
                  <div class="d-flex align-items-senter">
                    <i class="ki-outline ki-arrow-down-right fs-2 text-danger me-2"></i>
                    <!--begin::Number-->
                    <span class="text-gray-900 fw-bolder fs-6">730</span>
                    <!--end::Number-->
                  </div>
                  <!--end::Statistics-->
                </div>
                <!--end::Item-->
                <!--begin::Separator-->
                <div class="separator separator-dashed my-3"></div>
                <!--end::Separator-->
                <!--begin::Item-->
                <div class="d-flex flex-stack">
                  <!--begin::Section-->
                  <div class="text-gray-700 fw-semibold fs-6 me-2">Avg. Agent Earnings</div>
                  <!--end::Section-->
                  <!--begin::Statistics-->
                  <div class="d-flex align-items-senter">
                    <i class="ki-outline ki-arrow-up-right fs-2 text-success me-2"></i>
                    <!--begin::Number-->
                    <span class="text-gray-900 fw-bolder fs-6">$2,309</span>
                    <!--end::Number-->
                  </div>
                  <!--end::Statistics-->
                </div>
                <!--end::Item-->
              </div>
              <!--end::Body-->
            </div>
            <!--end::LIst widget 25-->
          </div>
          <!--end::Col-->
          <!--begin::Col-->
          <div class="col-lg-12 col-xl-12 col-xxl-6 mb-10 mb-xl-0">
            <!--begin::Timeline widget 3-->
            <div class="card h-md-100">
              <!--begin::Header-->
              <div class="card-header border-0 pt-5">
                <h3 class="card-title align-items-start flex-column">
                  <span class="card-label fw-bold text-gray-900">What’s up Today</span>
                  <span class="text-muted mt-1 fw-semibold fs-7">Total 424,567 deliveries</span>
                </h3>
                <!--begin::Toolbar-->
                <div class="card-toolbar">
                  <a href="#" class="btn btn-sm btn-light">Report Cecnter</a>
                </div>
                <!--end::Toolbar-->
              </div>
              <!--end::Header-->
              <!--begin::Body-->
              <div class="card-body pt-7 px-0">
                <!--begin::Nav-->
                <ul class="nav nav-stretch nav-pills nav-pills-custom nav-pills-active-custom d-flex justify-content-between mb-8 px-5">
                  <!--begin::Nav item-->
                  <li class="nav-item p-0 ms-0">
                    <!--begin::Date-->
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px py-4 px-3 btn-active-danger" data-bs-toggle="tab"
                      href="#kt_timeline_widget_3_tab_content_1">
                      <span class="fs-7 fw-semibold">Fr</span>
                      <span class="fs-6 fw-bold">20</span>
                    </a>
                    <!--end::Date-->
                  </li>
                  <!--end::Nav item-->
                  <!--begin::Nav item-->
                  <li class="nav-item p-0 ms-0">
                    <!--begin::Date-->
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px py-4 px-3 btn-active-danger" data-bs-toggle="tab"
                      href="#kt_timeline_widget_3_tab_content_2">
                      <span class="fs-7 fw-semibold">Sa</span>
                      <span class="fs-6 fw-bold">21</span>
                    </a>
                    <!--end::Date-->
                  </li>
                  <!--end::Nav item-->
                  <!--begin::Nav item-->
                  <li class="nav-item p-0 ms-0">
                    <!--begin::Date-->
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px py-4 px-3 btn-active-danger" data-bs-toggle="tab"
                      href="#kt_timeline_widget_3_tab_content_3">
                      <span class="fs-7 fw-semibold">Su</span>
                      <span class="fs-6 fw-bold">22</span>
                    </a>
                    <!--end::Date-->
                  </li>
                  <!--end::Nav item-->
                  <!--begin::Nav item-->
                  <li class="nav-item p-0 ms-0">
                    <!--begin::Date-->
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px py-4 px-3 btn-active-danger active"
                      data-bs-toggle="tab" href="#kt_timeline_widget_3_tab_content_4">
                      <span class="fs-7 fw-semibold">Tu</span>
                      <span class="fs-6 fw-bold">23</span>
                    </a>
                    <!--end::Date-->
                  </li>
                  <!--end::Nav item-->
                  <!--begin::Nav item-->
                  <li class="nav-item p-0 ms-0">
                    <!--begin::Date-->
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px py-4 px-3 btn-active-danger" data-bs-toggle="tab"
                      href="#kt_timeline_widget_3_tab_content_5">
                      <span class="fs-7 fw-semibold">Tu</span>
                      <span class="fs-6 fw-bold">24</span>
                    </a>
                    <!--end::Date-->
                  </li>
                  <!--end::Nav item-->
                  <!--begin::Nav item-->
                  <li class="nav-item p-0 ms-0">
                    <!--begin::Date-->
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px py-4 px-3 btn-active-danger" data-bs-toggle="tab"
                      href="#kt_timeline_widget_3_tab_content_6">
                      <span class="fs-7 fw-semibold">We</span>
                      <span class="fs-6 fw-bold">25</span>
                    </a>
                    <!--end::Date-->
                  </li>
                  <!--end::Nav item-->
                  <!--begin::Nav item-->
                  <li class="nav-item p-0 ms-0">
                    <!--begin::Date-->
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px py-4 px-3 btn-active-danger" data-bs-toggle="tab"
                      href="#kt_timeline_widget_3_tab_content_7">
                      <span class="fs-7 fw-semibold">Th</span>
                      <span class="fs-6 fw-bold">26</span>
                    </a>
                    <!--end::Date-->
                  </li>
                  <!--end::Nav item-->
                  <!--begin::Nav item-->
                  <li class="nav-item p-0 ms-0">
                    <!--begin::Date-->
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px py-4 px-3 btn-active-danger" data-bs-toggle="tab"
                      href="#kt_timeline_widget_3_tab_content_8">
                      <span class="fs-7 fw-semibold">Fri</span>
                      <span class="fs-6 fw-bold">27</span>
                    </a>
                    <!--end::Date-->
                  </li>
                  <!--end::Nav item-->
                  <!--begin::Nav item-->
                  <li class="nav-item p-0 ms-0">
                    <!--begin::Date-->
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px py-4 px-3 btn-active-danger" data-bs-toggle="tab"
                      href="#kt_timeline_widget_3_tab_content_9">
                      <span class="fs-7 fw-semibold">Sa</span>
                      <span class="fs-6 fw-bold">28</span>
                    </a>
                    <!--end::Date-->
                  </li>
                  <!--end::Nav item-->
                  <!--begin::Nav item-->
                  <li class="nav-item p-0 ms-0">
                    <!--begin::Date-->
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px py-4 px-3 btn-active-danger" data-bs-toggle="tab"
                      href="#kt_timeline_widget_3_tab_content_10">
                      <span class="fs-7 fw-semibold">Su</span>
                      <span class="fs-6 fw-bold">29</span>
                    </a>
                    <!--end::Date-->
                  </li>
                  <!--end::Nav item-->
                  <!--begin::Nav item-->
                  <li class="nav-item p-0 ms-0">
                    <!--begin::Date-->
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px py-4 px-3 btn-active-danger" data-bs-toggle="tab"
                      href="#kt_timeline_widget_3_tab_content_11">
                      <span class="fs-7 fw-semibold">Mo</span>
                      <span class="fs-6 fw-bold">30</span>
                    </a>
                    <!--end::Date-->
                  </li>
                  <!--end::Nav item-->
                </ul>
                <!--end::Nav-->
                <!--begin::Tab Content (ishlamayabdi)-->
                <div class="tab-content mb-2 px-9">
                  <!--begin::Tap pane-->
                  <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_1">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-success"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">10:20 - 11:00
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Degree Project Estimation
                          Meeting</div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Peter
                            Marcus</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-warning"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">12:00 - 13:40
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">Dashboard UI/UX Design Review
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Bob</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-info"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">16:30 - 17:00
                          <span class="text-gray-500 fw-semibold fs-7">PM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">Marketing Campaign Discussion
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Mark Morris</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                  </div>
                  <!--end::Tap pane-->
                  <!--begin::Tap pane-->
                  <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_2">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-warning"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">16:30 - 17:00
                          <span class="text-gray-500 fw-semibold fs-7">PM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">Marketing Campaign Discussion
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Mark Morris</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-info"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">12:00 - 13:40
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Degree Project Estimation
                          Meeting</div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Peter
                            Marcus</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-success"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">10:20 - 11:00
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Degree Project Estimation
                          Meeting</div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Peter
                            Marcus</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                  </div>
                  <!--end::Tap pane-->
                  <!--begin::Tap pane-->
                  <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_3">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-primary"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">10:20 - 11:00
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Degree Project Estimation
                          Meeting</div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Peter
                            Marcus</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-warning"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">12:00 - 13:40
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">Marketing Campaign Discussion
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Bob</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-info"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">16:30 - 17:00
                          <span class="text-gray-500 fw-semibold fs-7">PM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">Marketing Campaign Discussion
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Mark Morris</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                  </div>
                  <!--end::Tap pane-->
                  <!--begin::Tap pane-->
                  <div class="tab-pane fade show active" id="kt_timeline_widget_3_tab_content_4">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-info"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">10:20 - 11:00
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Degree Project Estimation
                          Meeting</div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Peter
                            Marcus</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-warning"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">16:30 - 17:00
                          <span class="text-gray-500 fw-semibold fs-7">PM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">Dashboard UI/UX Design Review
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Bob</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-success"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">12:00 - 13:40
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">Marketing Campaign Discussion
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Mark Morris</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                  </div>
                  <!--end::Tap pane-->
                  <!--begin::Tap pane-->
                  <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_5">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-danger"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">12:00 - 13:40
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Dashboard UI/UX Design Review
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Bob</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-warning"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">10:20 - 11:00
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Degree Project Estimation
                          Meeting</div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Mark Morris</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-info"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">16:30 - 17:00
                          <span class="text-gray-500 fw-semibold fs-7">PM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">Marketing Campaign Discussion
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Peter
                            Marcus</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                  </div>
                  <!--end::Tap pane-->
                  <!--begin::Tap pane-->
                  <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_6">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-info"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">10:20 - 11:00
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">Marketing Campaign Discussion
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Mark Morris</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-primary"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">16:30 - 17:00
                          <span class="text-gray-500 fw-semibold fs-7">PM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Degree Project Estimation
                          Meeting</div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Peter
                            Marcus</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-warning"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">12:00 - 13:40
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Dashboard UI/UX Design Review
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Bob</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                  </div>
                  <!--end::Tap pane-->
                  <!--begin::Tap pane-->
                  <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_7">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-warning"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">12:00 - 13:40
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Degree Project Estimation
                          Meeting</div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Bob</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-danger"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">10:20 - 11:00
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Dashboard UI/UX Design Review
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Peter
                            Marcus</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-success"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">16:30 - 17:00
                          <span class="text-gray-500 fw-semibold fs-7">PM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">Marketing Campaign Discussion
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Mark Morris</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                  </div>
                  <!--end::Tap pane-->
                  <!--begin::Tap pane-->
                  <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_8">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-success"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">16:30 - 17:00
                          <span class="text-gray-500 fw-semibold fs-7">PM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">Marketing Campaign Discussion
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Peter
                            Marcus</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-info"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">10:20 - 11:00
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Degree Project Estimation
                          Meeting</div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Mark Morris</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-danger"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">12:00 - 13:40
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Dashboard UI/UX Design Review
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Bob</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                  </div>
                  <!--end::Tap pane-->
                  <!--begin::Tap pane-->
                  <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_9">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-info"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">12:00 - 13:40
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Degree Project Estimation
                          Meeting</div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Bob</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-primary"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">16:30 - 17:00
                          <span class="text-gray-500 fw-semibold fs-7">PM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">Marketing Campaign Discussion
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Mark Morris</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-success"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">10:20 - 11:00
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Dashboard UI/UX Design Review
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Peter
                            Marcus</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                  </div>
                  <!--end::Tap pane-->
                  <!--begin::Tap pane-->
                  <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_10">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-danger"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">12:00 - 13:40
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">Marketing Campaign Discussion
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Peter
                            Marcus</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-warning"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">10:20 - 11:00
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Dashboard UI/UX Design Review
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Bob</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-info"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">16:30 - 17:00
                          <span class="text-gray-500 fw-semibold fs-7">PM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Degree Project Estimation
                          Meeting</div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Mark Morris</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                  </div>
                  <!--end::Tap pane-->
                  <!--begin::Tap pane-->
                  <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_11">
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-info"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">16:30 - 17:00
                          <span class="text-gray-500 fw-semibold fs-7">PM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Dashboard UI/UX Design Review
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Mark Morris</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet" class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-danger"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">10:20 - 11:00
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">Marketing Campaign Discussion
                        </div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Peter
                            Marcus</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Wrapper-->
                    <div class="d-flex align-items-center mb-6">
                      <!--begin::Bullet-->
                      <span data-kt-element="bullet"
                        class="bullet bullet-vertical d-flex align-items-center min-h-70px mh-100 me-4 bg-primary"></span>
                      <!--end::Bullet-->
                      <!--begin::Info-->
                      <div class="flex-grow-1 me-5">
                        <!--begin::Time-->
                        <div class="text-gray-800 fw-semibold fs-2">12:00 - 13:40
                          <span class="text-gray-500 fw-semibold fs-7">AM</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Description-->
                        <div class="text-gray-700 fw-semibold fs-6">9 Degree Project Estimation
                          Meeting</div>
                        <!--end::Description-->
                        <!--begin::Link-->
                        <div class="text-gray-500 fw-semibold fs-7">Lead by
                          <!--begin::Name-->
                          <a href="#" class="text-primary opacity-75-hover fw-semibold">Lead by
                            Bob</a>
                          <!--end::Name-->
                        </div>
                        <!--end::Link-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Wrapper-->
                  </div>
                  <!--end::Tap pane-->
                </div>
                <!--end::Tab Content-->
                <!--begin::Action-->
                <div class="float-end d-none">
                  <a href="#" class="btn btn-sm btn-light me-2" data-bs-toggle="modal" data-bs-target="#kt_modal_create_project">Add
                    Lesson</a>
                  <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app">Call Sick for
                    Today</a>
                </div>
                <!--end::Action-->
              </div>
              <!--end: Card Body-->
            </div>
            <!--end::Timeline widget 3-->
            <!--begin::Timeline widget 3-->
            <div class="card card-flush d-none h-md-100">
              <!--begin::Card header-->
              <div class="card-header mt-6">
                <!--begin::Card title-->
                <div class="card-title flex-column">
                  <h3 class="fw-bold mb-1">What's on the road?</h3>
                  <div class="fs-6 text-gray-500">Total 482 participants</div>
                </div>
                <!--end::Card title-->
                <!--begin::Card toolbar-->
                <div class="card-toolbar">
                  <!--begin::Select-->
                  <select name="status" data-control="select2" data-hide-search="true"
                    class="form-select form-select-solid form-select-sm fw-bold w-100px">
                    <option value="1" selected="selected">Options</option>
                    <option value="2">Option 1</option>
                    <option value="3">Option 2</option>
                    <option value="4">Option 3</option>
                  </select>
                  <!--end::Select-->
                </div>
                <!--end::Card toolbar-->
              </div>
              <!--end::Card header-->
              <!--begin::Card body-->
              <div class="card-body p-0">
                <!--begin::Dates-->
                <ul class="nav nav-pills d-flex flex-nowrap hover-scroll-x py-2 ms-4">
                  <!--begin::Date-->
                  <li class="nav-item me-1">
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px me-2 py-4 px-3 btn-color-active-white btn-active-danger"
                      data-bs-toggle="tab" href="#kt_schedule_day_0">
                      <span class="text-gray-500 fs-7 fw-semibold">Fr</span>
                      <span class="fs-6 text-gray-800 fw-bold">20</span>
                    </a>
                  </li>
                  <!--end::Date-->
                  <!--begin::Date-->
                  <li class="nav-item me-1">
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px me-2 py-4 px-3 btn-color-active-white btn-active-danger"
                      data-bs-toggle="tab" href="#kt_schedule_day_1">
                      <span class="text-gray-500 fs-7 fw-semibold">Sa</span>
                      <span class="fs-6 text-gray-800 fw-bold">21</span>
                    </a>
                  </li>
                  <!--end::Date-->
                  <!--begin::Date-->
                  <li class="nav-item me-1">
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px me-2 py-4 px-3 btn-color-active-white btn-active-danger"
                      data-bs-toggle="tab" href="#kt_schedule_day_2">
                      <span class="text-gray-500 fs-7 fw-semibold">Su</span>
                      <span class="fs-6 text-gray-800 fw-bold">22</span>
                    </a>
                  </li>
                  <!--end::Date-->
                  <!--begin::Date-->
                  <li class="nav-item me-1">
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px me-2 py-4 px-3 btn-color-active-white btn-active-danger active"
                      data-bs-toggle="tab" href="#kt_schedule_day_3">
                      <span class="text-gray-500 fs-7 fw-semibold">Mo</span>
                      <span class="fs-6 text-gray-800 fw-bold">23</span>
                    </a>
                  </li>
                  <!--end::Date-->
                  <!--begin::Date-->
                  <li class="nav-item me-1">
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px me-2 py-4 px-3 btn-color-active-white btn-active-danger"
                      data-bs-toggle="tab" href="#kt_schedule_day_4">
                      <span class="text-gray-500 fs-7 fw-semibold">Tu</span>
                      <span class="fs-6 text-gray-800 fw-bold">24</span>
                    </a>
                  </li>
                  <!--end::Date-->
                  <!--begin::Date-->
                  <li class="nav-item me-1">
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px me-2 py-4 px-3 btn-color-active-white btn-active-danger"
                      data-bs-toggle="tab" href="#kt_schedule_day_5">
                      <span class="text-gray-500 fs-7 fw-semibold">We</span>
                      <span class="fs-6 text-gray-800 fw-bold">25</span>
                    </a>
                  </li>
                  <!--end::Date-->
                  <!--begin::Date-->
                  <li class="nav-item me-1">
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px me-2 py-4 px-3 btn-color-active-white btn-active-danger"
                      data-bs-toggle="tab" href="#kt_schedule_day_6">
                      <span class="text-gray-500 fs-7 fw-semibold">Th</span>
                      <span class="fs-6 text-gray-800 fw-bold">26</span>
                    </a>
                  </li>
                  <!--end::Date-->
                  <!--begin::Date-->
                  <li class="nav-item me-1">
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px me-2 py-4 px-3 btn-color-active-white btn-active-danger"
                      data-bs-toggle="tab" href="#kt_schedule_day_7">
                      <span class="text-gray-500 fs-7 fw-semibold">Fr</span>
                      <span class="fs-6 text-gray-800 fw-bold">27</span>
                    </a>
                  </li>
                  <!--end::Date-->
                  <!--begin::Date-->
                  <li class="nav-item me-1">
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px me-2 py-4 px-3 btn-color-active-white btn-active-danger"
                      data-bs-toggle="tab" href="#kt_schedule_day_8">
                      <span class="text-gray-500 fs-7 fw-semibold">Sa</span>
                      <span class="fs-6 text-gray-800 fw-bold">28</span>
                    </a>
                  </li>
                  <!--end::Date-->
                  <!--begin::Date-->
                  <li class="nav-item me-1">
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px me-2 py-4 px-3 btn-color-active-white btn-active-danger"
                      data-bs-toggle="tab" href="#kt_schedule_day_9">
                      <span class="text-gray-500 fs-7 fw-semibold">Su</span>
                      <span class="fs-6 text-gray-800 fw-bold">29</span>
                    </a>
                  </li>
                  <!--end::Date-->
                  <!--begin::Date-->
                  <li class="nav-item me-1">
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px me-2 py-4 px-3 btn-color-active-white btn-active-danger"
                      data-bs-toggle="tab" href="#kt_schedule_day_10">
                      <span class="text-gray-500 fs-7 fw-semibold">Mo</span>
                      <span class="fs-6 text-gray-800 fw-bold">30</span>
                    </a>
                  </li>
                  <!--end::Date-->
                  <!--begin::Date-->
                  <li class="nav-item me-1">
                    <a class="nav-link btn d-flex flex-column flex-center rounded-pill min-w-45px me-2 py-4 px-3 btn-color-active-white btn-active-danger"
                      data-bs-toggle="tab" href="#kt_schedule_day_11">
                      <span class="text-gray-500 fs-7 fw-semibold">Tu</span>
                      <span class="fs-6 text-gray-800 fw-bold">31</span>
                    </a>
                  </li>
                  <!--end::Date-->
                </ul>
                <!--end::Dates-->
                <!--begin::Tab Content-->
                <div class="tab-content px-9">
                  <!--begin::Day-->
                  <div id="kt_schedule_day_0" class="tab-pane fade show">
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">11:00 - 11:45
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Development
                          Team Capacity Review</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Michael Walters</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">16:30 - 17:30
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Marketing
                          Campaign Discussion</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Bob Harris</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">14:30 - 15:30
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Lunch
                          & Learn Catch Up</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Peter Marcus</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                  </div>
                  <!--end::Day-->
                  <!--begin::Day-->
                  <div id="kt_schedule_day_1" class="tab-pane fade show active">
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">13:00 - 14:00
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Committee
                          Review Approvals</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">David Stevenson</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">10:00 - 11:00
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Weekly Team
                          Stand-Up</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Mark Randall</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">10:00 - 11:00
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Development
                          Team Capacity Review</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Michael Walters</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                  </div>
                  <!--end::Day-->
                  <!--begin::Day-->
                  <div id="kt_schedule_day_2" class="tab-pane fade show">
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">11:00 - 11:45
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Lunch
                          & Learn Catch Up</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Michael Walters</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">11:00 - 11:45
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Project
                          Review & Testing</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Karina Clarke</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">14:30 - 15:30
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Team
                          Backlog Grooming Session</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">David Stevenson</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                  </div>
                  <!--end::Day-->
                  <!--begin::Day-->
                  <div id="kt_schedule_day_3" class="tab-pane fade show">
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">14:30 - 15:30
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Sales
                          Pitch Proposal</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Caleb Donaldson</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">16:30 - 17:30
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Sales
                          Pitch Proposal</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Kendell Trevor</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">13:00 - 14:00
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Weekly Team
                          Stand-Up</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Yannis Gloverson</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                  </div>
                  <!--end::Day-->
                  <!--begin::Day-->
                  <div id="kt_schedule_day_4" class="tab-pane fade show">
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">14:30 - 15:30
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Creative
                          Content Initiative</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Caleb Donaldson</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">14:30 - 15:30
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Team
                          Backlog Grooming Session</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Michael Walters</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">10:00 - 11:00
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">9
                          Degree Project Estimation Meeting</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Yannis Gloverson</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                  </div>
                  <!--end::Day-->
                  <!--begin::Day-->
                  <div id="kt_schedule_day_5" class="tab-pane fade show">
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">14:30 - 15:30
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Sales
                          Pitch Proposal</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Sean Bean</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">16:30 - 17:30
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Team
                          Backlog Grooming Session</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Peter Marcus</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">14:30 - 15:30
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Sales
                          Pitch Proposal</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Terry Robins</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                  </div>
                  <!--end::Day-->
                  <!--begin::Day-->
                  <div id="kt_schedule_day_6" class="tab-pane fade show">
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">10:00 - 11:00
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Development
                          Team Capacity Review</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Mark Randall</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">12:00 - 13:00
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Committee
                          Review Approvals</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Sean Bean</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">12:00 - 13:00
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Committee
                          Review Approvals</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Sean Bean</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                  </div>
                  <!--end::Day-->
                  <!--begin::Day-->
                  <div id="kt_schedule_day_7" class="tab-pane fade show">
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">9:00 - 10:00
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">9
                          Degree Project Estimation Meeting</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Kendell Trevor</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">10:00 - 11:00
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Weekly Team
                          Stand-Up</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Yannis Gloverson</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">16:30 - 17:30
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">9
                          Degree Project Estimation Meeting</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Bob Harris</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                  </div>
                  <!--end::Day-->
                  <!--begin::Day-->
                  <div id="kt_schedule_day_8" class="tab-pane fade show">
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">9:00 - 10:00
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Lunch
                          & Learn Catch Up</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Caleb Donaldson</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">11:00 - 11:45
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Lunch
                          & Learn Catch Up</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Sean Bean</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">10:00 - 11:00
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Creative
                          Content Initiative</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">David Stevenson</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                  </div>
                  <!--end::Day-->
                  <!--begin::Day-->
                  <div id="kt_schedule_day_9" class="tab-pane fade show">
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">14:30 - 15:30
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">9
                          Degree Project Estimation Meeting</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Bob Harris</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">9:00 - 10:00
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Creative
                          Content Initiative</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Naomi Hayabusa</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">13:00 - 14:00
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Dashboard
                          UI/UX Design Review</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Naomi Hayabusa</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                  </div>
                  <!--end::Day-->
                  <!--begin::Day-->
                  <div id="kt_schedule_day_10" class="tab-pane fade show">
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">11:00 - 11:45
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Project
                          Review & Testing</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Sean Bean</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">14:30 - 15:30
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Dashboard
                          UI/UX Design Review</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Naomi Hayabusa</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">14:30 - 15:30
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Team
                          Backlog Grooming Session</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Kendell Trevor</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                  </div>
                  <!--end::Day-->
                  <!--begin::Day-->
                  <div id="kt_schedule_day_11" class="tab-pane fade show">
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">13:00 - 14:00
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Sales
                          Pitch Proposal</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Bob Harris</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">9:00 - 10:00
                          <span class="fs-7 text-gray-500 text-uppercase">am</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">9
                          Degree Project Estimation Meeting</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Mark Randall</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                    <!--begin::Time-->
                    <div class="d-flex flex-stack position-relative mt-8">
                      <!--begin::Bar-->
                      <div class="position-absolute h-100 w-4px bg-secondary rounded top-0 start-0">
                      </div>
                      <!--end::Bar-->
                      <!--begin::Info-->
                      <div class="fw-semibold ms-5 text-gray-600">
                        <!--begin::Time-->
                        <div class="fs-5">12:00 - 13:00
                          <span class="fs-7 text-gray-500 text-uppercase">pm</span>
                        </div>
                        <!--end::Time-->
                        <!--begin::Title-->
                        <a href="#" class="fs-5 fw-bold text-gray-800 text-hover-primary mb-2">Dashboard
                          UI/UX Design Review</a>
                        <!--end::Title-->
                        <!--begin::User-->
                        <div class="text-gray-500">Lead by
                          <a href="#">Karina Clarke</a>
                        </div>
                        <!--end::User-->
                      </div>
                      <!--end::Info-->
                      <!--begin::Action-->
                      <a href="#" class="btn btn-bg-light btn-active-color-primary btn-sm">View</a>
                      <!--end::Action-->
                    </div>
                    <!--end::Time-->
                  </div>
                  <!--end::Day-->
                </div>
                <!--end::Tab Content-->
              </div>
              <!--end::Card body-->
            </div>
            <!--end::Timeline widget-3-->
          </div>
          <!--end::Col-->
        </div>
        <!--end::Row-->
      </div>
      <!--end::Content container-->
    </div>
    <!--end::Content-->
  </div>
@endsection
