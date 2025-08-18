<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Meta tags  -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <title>SPKAD</title>
  <meta charset="utf-8" />
  <meta name="description"
    content="The most advanced Tailwind CSS & Bootstrap 5 Admin Theme with 40 unique prebuilt layouts on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel versions. Grab your copy now and get life-time updates for free." />
  <meta name="keywords"
    content="tailwind, tailwindcss, metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel starter kits, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta property="og:locale" content="en_US" />
  <meta property="og:type" content="article" />
  <meta property="og:title" content="Metronic - The World's #1 Selling Tailwind CSS & Bootstrap Admin Template by KeenThemes" />
  <meta property="og:url" content="https://keenthemes.com/metronic" />
  <meta property="og:site_name" content="Metronic by Keenthemes" />
  <link rel="canonical" href="http://preview.keenthemes.comdashboards/projects.html" />
  <link rel="shortcut icon" href="{{ URL::to('assets/media/logos/favicon.ico') }}" />
  <!--begin::Fonts(mandatory for all pages)-->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
  <!--end::Fonts-->
  <!--begin::Vendor Stylesheets(used for this page only)-->
  <link href="{{ URL::to('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::to('assets/plugins/custom/vis-timeline/vis-timeline.bundle.css') }}" rel="stylesheet" type="text/css" />
  <!--end::Vendor Stylesheets-->
  <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
  <link href="{{ URL::to('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ URL::to('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
</head>

<body id="kt_app_body" data-kt-app-header-fixed="true" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true"
  data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true"
  data-kt-app-toolbar-enabled="true" data-kt-app-aside-enabled="true" data-kt-app-aside-fixed="true" data-kt-app-aside-push-toolbar="true"
  data-kt-app-aside-push-footer="true" class="app-default">
  <!--begin::Theme mode setup on page load-->
  <script>
    var defaultThemeMode = "light";
    var themeMode;
    if (document.documentElement) {
      if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
        themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
      } else {
        if (localStorage.getItem("data-bs-theme") !== null) {
          themeMode = localStorage.getItem("data-bs-theme");
        } else {
          themeMode = defaultThemeMode;
        }
      }
      if (themeMode === "system") {
        themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
      }
      document.documentElement.setAttribute("data-bs-theme", themeMode);
    }
  </script>
  <!--end::Theme mode setup on page load-->
  <!--begin::App-->
  <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <!--begin::Page-->
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
      @include('layouts.header')
      <!--begin::Wrapper-->
      <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
        @include('layouts.sidebar')
        <!--begin::Main-->
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
          <!--begin::Content wrapper-->
          <div class="d-flex flex-column flex-column-fluid">
            @yield('content')
            @include('layouts.footer')
          </div>
        </div>
        <!--end::Main-->
        @include('layouts.aside')
      </div>
    </div>
  </div>
  <script>
    var hostUrl = "assets/";
  </script>
  <!--begin::Global Javascript Bundle(mandatory for all pages)-->
  <script src="{{ URL::to('assets/plugins/global/plugins.bundle.js') }}"></script>
  <script src="{{ URL::to('assets/js/scripts.bundle.js') }}"></script>
  <!--end::Global Javascript Bundle-->
  <!--begin::Vendors Javascript(used for this page only)-->
  <script src="{{ URL::to('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
  <script src="{{ URL::to('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
  <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
  <!--end::Vendors Javascript-->
  <!--begin::Custom Javascript(used for this page only)-->
  <script src="{{ URL::to('assets/js/widgets.bundle.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/widgets.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/apps/chat/chat.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/utilities/modals/upgrade-plan.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/utilities/modals/create-campaign.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/utilities/modals/create-project/type.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/utilities/modals/create-project/budget.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/utilities/modals/create-project/settings.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/utilities/modals/create-project/team.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/utilities/modals/create-project/targets.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/utilities/modals/create-project/files.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/utilities/modals/create-project/complete.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/utilities/modals/create-project/main.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/utilities/modals/create-app.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/utilities/modals/new-address.js') }}"></script>
  <script src="{{ URL::to('assets/js/custom/utilities/modals/users-search.js') }}"></script>
  <!--end::Custom Javascript-->
  <!--end::Javascript-->
</body>

</html>
