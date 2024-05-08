<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Log In | {{ config('app.name', 'Alchemy Tuition') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('template/images/favicon.ico')}}">
        @vite('resources/css/app.css')
        <!-- App css -->
        <link href="{{asset('template/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('template/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style"/>

    </head>
    
    <body class="loading authentication-bg" data-layout-config='{"darkMode":false}'>
        <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-4 col-lg-5">
                        <div class="card">

                            <!-- Logo -->
                            <div class="card-header pt-4 pb-4 text-center bg-primary">
                                <a href="/" wire:navigate>
                                    <img src="template/images/logo.png" alt="" height="40">
                                </a>
                            </div>

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center pb-0 fw-bold">{{__('Sign In')}}</h4>
                                    <p class="text-muted mb-4">{{__('Enter your email address and password to access admin panel.')}}</p>
                                </div>
                                <div>
                                    {{ $slot }}
                                </div>
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        <footer class="footer footer-alt">
            2006 - {{date('Y')}} &copy; {{ config('app.name', 'AlchemyTuition') }}
        </footer>

        <!-- template bundle -->
        <script src="{{asset('vendor/jquery/jquery-3.6.0.min.js')}}"></script>
        @vite('resources/js/app.js')
        <script src="{{asset('vendor/select2/select2.min.js')}}"></script>
        <script src="{{asset('vendor/simplebar.min.js')}}"></script>
        <script src="{{asset('vendor/moment.min.js')}}"></script>
        <script src="{{asset('template/js/app.min.js')}}"></script>
        
    </body>
</html>

