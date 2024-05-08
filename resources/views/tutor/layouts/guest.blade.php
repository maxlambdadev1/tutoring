<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Log In | {{ config('app.name', 'Alchemy Tuition') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('template/images/favicon.ico')}}">
        <!-- App css -->
        <link href="{{asset('template/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('template/css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-style"/>
        @vite('resources/css/app.css')

    </head>
    
    <body class="authentication-bg pb-0" data-layout-config='{"darkMode":false}'>

        <div class="auth-fluid">
            <!--Auth fluid left content -->
            <div class="auth-fluid-form-box">
                <div class="align-items-center d-flex h-100">
                    <div class="card-body">

                        <!-- Logo -->
                        <div class="auth-brand text-center text-lg-start">
                            <a href="/" class="logo-dark text-center" wire:navigate>
                                <img src="template/images/logo-dark.jpg" alt="" height="50">
                            </a>
                            <a href="/" class="logo-light text-center" wire:navigate>
                                <img src="template/images/logo.png" alt="" height="50">
                            </a>
                        </div>

                        <div>
                            {{ $slot }}
                        </div>

                        <!-- Footer-->
                        <footer class="footer footer-alt">
                            <p class="text-muted">Don't have an account? <a href="#" class="text-muted ms-1"><b>Apply now!</b></a></p>
                        </footer>

                    </div> <!-- end .card-body -->
                </div> <!-- end .align-items-center.d-flex.h-100-->
            </div>
            <!-- end auth-fluid-form-box-->

            <!-- Auth fluid right content -->
            <div class="auth-fluid-right text-center">
                <div class="auth-user-testimonial">
                    <h2 class="mb-3">I love the Alchemy!</h2>
                    <p class="lead"><i class="mdi mdi-format-quote-open"></i> AlchemyTuition is a great place! I love it very much . <i class="mdi mdi-format-quote-close"></i>
                    </p>
                    <p>
                        - Nic Rothquel
                    </p>
                </div> <!-- end auth-user-testimonial-->
            </div>
            <!-- end Auth fluid right content -->
        </div>
        <!-- end auth-fluid-->

        <!-- bundle -->
        <script src="{{asset('vendor/jquery/jquery-3.6.0.min.js')}}"></script>
        {{-- <script src="{{asset('js/app.js')}}"></script> --}}
        <script src="{{asset('vendor/select2/select2.min.js')}}"></script>
        <script src="{{asset('vendor/simplebar.min.js')}}"></script>
        <script src="{{asset('vendor/moment.min.js')}}"></script>
        <script src="{{asset('template/js/app.min.js')}}"></script>

    </body>
</html>

