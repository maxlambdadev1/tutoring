<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name', 'Alchemy Tuition') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('template/css/icons.min.css')}}">

    <!-- Livewire Styles -->
    @livewireStyles
    <!-- App css -->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOIopVJmkbjQFH8B9Sy3RpZLJzUQGjHnY&libraries=marker&loading=async"></script>
    <link href="{{asset('vendor/bootstrap/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('template/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendor/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('template/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin.js', 'resources/js/tutor.js'])
    @yield('css-library')

    {{-- Script --}}
    <script src="{{asset('vendor/jquery/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('vendor/select2/select2.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap-timepicker.js')}}"></script>
    <script src="{{asset('vendor/chart.min.js')}}"></script>
    <script src="{{asset('vendor/simplebar.min.js')}}"></script>
    <script src="{{asset('vendor/moment.min.js')}}"></script>
    <script src="{{asset('vendor/toast.min.js')}}"></script>
    <script src="{{asset('vendor/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{asset('vendor/jquery.autocomplete.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{asset('vendor/jquery.bootstrap.wizard.min.js')}}"></script>
    <script src="{{asset('vendor/jquery.validate.min.js')}}"></script>
    <link href="{{asset('vendor/toastr/toastr.min.css')}}" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="{{asset('vendor/toastr/toastr.min.js')}}"></script>

    <!-- include FilePond library -->
    <!-- <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script> -->
    {{--<!-- include FilePond plugins -->
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link
        href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
        rel="stylesheet"
    />
    <link
        href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css"
        rel="stylesheet"
    />
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-encode/dist/filepond-plugin-file-encode.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.min.js"></script>
    <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.min.js"></script> --}}

    <link href="{{asset('vendor/filepond/assets/filepond.css')}}" rel="stylesheet" />
    <link href="{{asset('vendor/filepond/assets/filepond-plugin-image-preview.min.css')}}" rel="stylesheet" />
    <link href="{{asset('vendor/filepond/assets/filepond-plugin-file-poster.min.css')}}" rel="stylesheet" />
    <script src="{{asset('vendor/filepond/assets/filepond.js')}}"></script>
    <script src="{{asset('vendor/filepond/assets/filepond-plugin-file-encode.min.js')}}"></script>
    <script src="{{asset('vendor/filepond/assets/filepond-plugin-file-validate-type.min.js')}}"></script>
    <script src="{{asset('vendor/filepond/assets/filepond-plugin-file-validate-size.min.js')}}"></script>
    <script src="{{asset('vendor/filepond/assets/filepond-plugin-image-exif-orientation.min.js')}}"></script>
    <script src="{{asset('vendor/filepond/assets/filepond-plugin-image-preview.min.js')}}"></script>
    <script src="{{asset('vendor/filepond/assets/filepond-plugin-image-crop.min.js')}}"></script>
    <script src="{{asset('vendor/filepond/assets/filepond-plugin-image-resize.min.js')}}"></script>
    <script src="{{asset('vendor/filepond/assets/filepond-plugin-image-transform.min.js')}}"></script>
    <script src="{{asset('vendor/filepond/assets/filepond-plugin-file-poster.min.js')}}"></script>
    <script src="{{asset('vendor/filepond/assets/filepond-plugin-image-editor.min.js')}}"></script>
    <!-- include FilePond jQuery adapter -->
    <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
    <link href="{{asset('vendor/filepond/assets/pintura.css')}}" rel="stylesheet" />
</head>

<body class="loading" data-layout-color="light" data-leftbar-theme="dark" data-layout-mode="fluid" data-rightbar-onstart="true">
    <!-- Begin page -->
    <div class="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
        <div class="leftside-menu">

            <!-- LOGO -->
            <a href="{{route('tutor.dashboard')}}" class="logo text-center logo-light">
                <span class="logo-lg">
                    <img src="{{asset('template/images/logo.png')}}" alt="" height="35">
                </span>
                <span class="logo-sm">
                    {{-- <img src="{{asset('template/images/logo_sm.png')}}" alt="" height="40"> --}}
                </span>
            </a>

            <!-- LOGO -->
            <a href="{{route('tutor.dashboard')}}" class="logo text-center logo-dark">
                <span class="logo-lg">
                    <img src="{{asset('template/images/logo-dark.jpg')}}" alt="" height="40">
                </span>
                <span class="logo-sm">
                    {{-- <img src="{{asset('template/images/logo_sm_dark.png')}}" alt="" height="40"> --}}
                </span>
            </a>

            <div class="h-100" id="leftside-menu-container" data-simplebar>

                <!--- Sidemenu -->

                @include('tutor.layouts.sidebar')

                <!-- End Sidebar -->

                <div class="clearfix"></div>

            </div>
            <!-- Sidebar -left -->

        </div>
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">
                <!-- Topbar Start -->
                <livewire:tutor.layout.navigation>
                    <!-- end Topbar -->

                    <!-- Start Content-->
                    <div class="container-fluid mx-0">

                        <div>{{ $header }}</div>

                        <div>{{ $slot }}</div>

                    </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container mx-0">
                    <div class="row">
                        <div class="col-md-6">
                            {{date('Y')}} &copy; {{ config('app.name', 'Alehcmy Tuition') }}
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end footer-links d-none d-md-block">
                                <a href="javascript: void(0);">About</a>
                                <a href="javascript: void(0);">Support</a>
                                <a href="javascript: void(0);">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->


    <!-- Right Sidebar -->
    <div class="end-bar">

        <div class="rightbar-title">
            <a href="javascript:void(0);" class="end-bar-toggle float-end">
                <i class="dripicons-cross noti-icon"></i>
            </a>
            <h5 class="m-0">Settings</h5>
        </div>

        <div class="rightbar-content h-100" data-simplebar>

            <div class="p-3">
                <div class="alert alert-warning" role="alert">
                    <strong>Customize </strong> the overall color scheme, sidebar menu, etc.
                </div>

                <!-- Settings -->
                <h5 class="mt-3">Color Scheme</h5>
                <hr class="mt-1" />

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="color-scheme-mode" value="light" id="light-mode-check" checked>
                    <label class="form-check-label" for="light-mode-check">Light Mode</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="color-scheme-mode" value="dark" id="dark-mode-check">
                    <label class="form-check-label" for="dark-mode-check">Dark Mode</label>
                </div>


                <!-- Width -->
                <h5 class="mt-4">Width</h5>
                <hr class="mt-1" />
                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="width" value="fluid" id="fluid-check" checked>
                    <label class="form-check-label" for="fluid-check">Fluid</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="width" value="boxed" id="boxed-check">
                    <label class="form-check-label" for="boxed-check">Boxed</label>
                </div>


                <!-- Left Sidebar-->
                <h5 class="mt-4">Left Sidebar</h5>
                <hr class="mt-1" />
                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="theme" value="default" id="default-check">
                    <label class="form-check-label" for="default-check">Default</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="theme" value="light" id="light-check" checked>
                    <label class="form-check-label" for="light-check">Light</label>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="theme" value="dark" id="dark-check">
                    <label class="form-check-label" for="dark-check">Dark</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="compact" value="fixed" id="fixed-check" checked>
                    <label class="form-check-label" for="fixed-check">Fixed</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="compact" value="condensed" id="condensed-check">
                    <label class="form-check-label" for="condensed-check">Condensed</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="compact" value="scrollable" id="scrollable-check">
                    <label class="form-check-label" for="scrollable-check">Scrollable</label>
                </div>

                <div class="d-grid mt-4">
                    <button class="btn btn-primary" id="resetBtn">Reset to Default</button>
                </div>
            </div> <!-- end padding-->

        </div>
    </div>

    <div class="rightbar-overlay"></div>
    <!-- /End-bar -->

    @yield('modal')
    @yield('library')
    @yield('script')
    @livewireScripts
    <script src="{{asset('template/js/app.min.js')}}"></script>
    <!-- <script src="{{asset('build/js/app2.js')}}" data-navigate-once></script> -->
</body>

</html>