<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')</title>
    <meta name="description" content="@yield('meta')">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('template/css/icons.min.css')}}">

    <!-- Livewire Styles -->
    @livewireStyles
    <!-- App css -->
    <link href="{{asset('vendor/bootstrap/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('template/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendor/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('vendor/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('template/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin.js'])
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
    <script src="{{asset('vendor/jquery.signaturepad.min.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOIopVJmkbjQFH8B9Sy3RpZLJzUQGjHnY&libraries=places" async defer></script>

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

<body class="custom-body loading bg-white" data-layout-color="light" data-leftbar-theme="dark" data-layout-mode="fluid" data-rightbar-onstart="true">
    <!-- @yield('content') -->
     {{ $slot }}

    @yield('modal')
    @yield('library')
    @yield('script')
    @livewireScripts
    <script src="{{asset('template/js/app.min.js')}}"></script>
    <!-- <script src="{{asset('build/js/app2.js')}}" data-navigate-once></script> -->
</body>

</html>