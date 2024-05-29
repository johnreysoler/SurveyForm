<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $page_title }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <style src="vue-multiselect/dist/vue-multiselect.css"></style>
    {{-- <link rel="shortcut icon" href="css/images/logos/favicon.ico" /> --}}
    <link rel="icon" href="logo/logo-transparent-colored.png">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @guest
        <main>
            @yield('content')
        </main>
        @endguest
        
        @auth()
        @hasSection('form')
        <main style="height:100vh">
            @yield('form')
        </main>
        @else
            <div id="kt_body" style="background-image: url(css/images/background/bg-10.jpg)" class="quick-panel-right demo-panel-right offcanvas-right header-fixed subheader-enabled page-loading">
                <!--begin::Main-->
                    <!--end::Header Mobile-->
                    <div class="d-flex flex-column flex-root">
                        <!--begin::Page-->
                        <div class="d-flex flex-row flex-column-fluid page">
                            <!--begin::Wrapper-->
                            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                                <!--begin::Header-->
                                <div id="kt_header" class="header header-fixed">
                                    <!--begin::Container-->
                                    <div class="container d-flex align-items-stretch justify-content-between">
                                        <!--begin::Left-->
                                        <div class="d-flex align-items-stretch mr-3">
                                            <!--begin::Header Logo-->
                                            <div class="header-logo">
                                                <a href="index.html">
                                                    <img alt="Logo" src="logo/logo-transparent-colored.png" class="logo-default max-h-40px" />
                                                    <img alt="Logo" src="logo/logo-transparent-colored.png" class="logo-sticky max-h-40px" />
                                                </a>
                                            </div>
                                            <!--end::Header Logo-->
                                            <!--begin::Header Menu Wrapper-->
                                            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                                                <!--begin::Header Menu-->
                                                <div id="kt_header_menu" class="header-menu header-menu-left header-menu-mobile header-menu-layout-default">
                                                    <!--begin::Header Nav-->
                                                    <ul class="menu-nav">
                                                        <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                                            <a href="{{ route('home') }}" class="menu-link menu-toggle">
                                                                <span class="menu-text">Dashboard</span>
                                                                <i class="menu-arrow"></i>
                                                            </a>
                                                        </li>
                                                        <li class="menu-item menu-item-submenu menu-item-rel" data-menu-toggle="click" aria-haspopup="true">
                                                            <a class="menu-link menu-toggle" href="{{ route('survey-form') }}">
                                                                <span class="menu-text">Survey</span>
                                                                <span class="menu-desc"></span>
                                                                <i class="menu-arrow"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                    <!--end::Header Nav-->
                                                </div>
                                                <!--end::Header Menu-->
                                            </div>
                                            <!--end::Header Menu Wrapper-->
                                        </div>
                                        <!--end::Left-->
                                        <!--begin::Topbar-->
                                        <div class="topbar">
                                            <!--end::Quick panel-->
                                            <!--begin::Languages-->
                                            <div class="dropdown">
                                                <!--begin::Toggle-->
                                                {{-- <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px">
                                                    <div class="btn btn-icon btn-hover-transparent-white btn-dropdown btn-lg mr-1">
                                                        <img class="h-20px w-20px rounded-sm" src="css/images/svg/flags/226-united-states.svg" alt="" />
                                                    </div>
                                                </div> --}}
                                                <!--end::Toggle-->
                                                <!--begin::Dropdown-->
                                                <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-right">
                                                    <!--begin::Nav-->
                                                    <ul class="navi navi-hover py-4">
                                                        <!--begin::Item-->
                                                        <li class="navi-item">
                                                            <a href="#" class="navi-link">
                                                                <span class="symbol symbol-20 mr-3">
                                                                    <img src="css/images/svg/flags/226-united-states.svg" alt="" />
                                                                </span>
                                                                <span class="navi-text">English</span>
                                                            </a>
                                                        </li>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <li class="navi-item active">
                                                            <a href="#" class="navi-link">
                                                                <span class="symbol symbol-20 mr-3">
                                                                    <img src="css/images/svg/flags/128-spain.svg" alt="" />
                                                                </span>
                                                                <span class="navi-text">Spanish</span>
                                                            </a>
                                                        </li>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <li class="navi-item">
                                                            <a href="#" class="navi-link">
                                                                <span class="symbol symbol-20 mr-3">
                                                                    <img src="css/images/svg/flags/162-germany.svg" alt="" />
                                                                </span>
                                                                <span class="navi-text">German</span>
                                                            </a>
                                                        </li>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <li class="navi-item">
                                                            <a href="#" class="navi-link">
                                                                <span class="symbol symbol-20 mr-3">
                                                                    <img src="css/images/svg/flags/063-japan.svg" alt="" />
                                                                </span>
                                                                <span class="navi-text">Japanese</span>
                                                            </a>
                                                        </li>
                                                        <!--end::Item-->
                                                        <!--begin::Item-->
                                                        <li class="navi-item">
                                                            <a href="#" class="navi-link">
                                                                <span class="symbol symbol-20 mr-3">
                                                                    <img src="css/images/svg/flags/195-france.svg" alt="" />
                                                                </span>
                                                                <span class="navi-text">French</span>
                                                            </a>
                                                        </li>
                                                        <!--end::Item-->
                                                    </ul>
                                                    <!--end::Nav-->
                                                </div>
                                                <!--end::Dropdown-->
                                            </div>
                                            <!--end::Languages-->
                                            <!--begin::User-->
                                            <div class="dropdown">
                                                <!--begin::Toggle-->
                                                <div class="topbar-item">
                                                    <div class="btn btn-icon btn-hover-transparent-white d-flex align-items-center btn-lg px-md-2 w-md-auto" id="kt_quick_user_toggle">
                                                        <span class="text-white opacity-70 font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
                                                        <span class="dropdown-toggle text-white opacity-90 font-weight-bolder font-size-base d-none d-md-inline mr-4"
                                                        role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>{{ Auth::user()->name }}</span>
                                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                                            onclick="event.preventDefault();
                                                                            document.getElementById('logout-form').submit();">
                                                                {{ __('Logout') }}
                                                            </a>
                        
                                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                                @csrf
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--end::Toggle-->
                                            </div>
                                            <!--end::User-->
                                        </div>
                                        <!--end::Topbar-->
                                    </div>
                                    <!--end::Container-->
                                </div>
                                <!--end::Header-->
                                <!--begin::Content-->
                                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                                    <!--begin::Subheader-->
                                    <div class="subheader py-2 py-lg-12 subheader-transparent" id="kt_subheader">
                                        <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                                            <!--begin::Info-->
                                            <div class="d-flex align-items-center flex-wrap mr-1">
                                                <!--begin::Heading-->
                                                <div class="d-flex flex-column">
                                                    <!--begin::Title-->
                                                    <h2 class="text-white font-weight-bold my-2 mr-5">{{ $page_title }}</h2>
                                                    <!--end::Title-->
                                                </div>
                                                <!--end::Heading-->
                                            </div>
                                            <!--end::Info-->
                                        </div>
                                    </div>
                                    <!--end::Subheader-->
                                    <!--begin::Entry-->
                                    <div class="d-flex flex-column-fluid">
                                        <!--begin::Container-->
                                        <div class="container">
                                            <main style="height:100vh">
                                                @yield('content')
                                            </main>
                                        </div>
                                        <!--end::Container-->
                                    </div>
                                    <!--end::Entry-->
                                </div>
                                <!--end::Content-->
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Page-->
                    </div>
                    <!--end::Main-->
            </div>
        @endif
        @endauth
    </div>
</body>
</html>
