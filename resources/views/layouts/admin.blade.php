<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ env('APP_NAME') }}</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css?v=3.2.0') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
@if (Auth::check())

<body class="hold-transition sidebar-mini text-sm layout-navbar-fixed sidebar-mini-md sidebar-mini-xs">
    @else

    <body class="layout-top-nav text-sm">
        @endif
        <div class="wrapper">

            @include('components.navbar')
            @if (Auth::check())
            @include('components.sidebar')
            @endif

            <div class="content-wrapper">
                <div class="content-header">
                    @if (Auth::check())
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">@yield('title')</h1>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="container">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">@yield('title')</h1>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="content">
                    @if (Auth::check())
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                    @else
                    <div class="container">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h5 class="card-title m-0">Featured</h5>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title">Special title treatment</h6>
                                <p class="card-text">With supporting text below as a natural lead-in to additional
                                    content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

            </div>

            @include('components.control-sidebar')

            @include('components.footer')
        </div>



        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('js/adminlte.min.js?v=3.2.0') }}"></script>
        <script src="{{ asset('js/master.js') }}"></script>
        <script>
            $('a[data-widget=control-sidebar]').on('click', function(){
                // alert('Clicked');
            });
        </script>
        @stack('js')
    </body>

</html>