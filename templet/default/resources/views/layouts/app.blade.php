<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">

        @include('layouts.partials.nav')

        <main class="{{ auth()->guest() ? 'col-10 offset-2 px-0' : 'col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3 px-0' }}">
            @include('layouts.partials.top-nav')
            <div class="main-content-container container-fluid p-5 h-100">
                @auth
                    <div class="page-header row no-gutters py-4">
                        <div class="col-12 col-sm-4 text-center text-sm-left mb-0">
                            <span class="text-uppercase page-subtitle">{{ $subTitle ?? '' }}</span>
                            <h3 class="page-title">{{ $pageTitle ?? '' }}</h3>
                        </div>
                    </div>
                @endauth

                @yield('content')
            </div>
        </main>
    </div>

    <!-- Scripts -->
    {{--<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>--}}
    <!-- jQuery -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    @stack('scripts')
</body>
</html>
