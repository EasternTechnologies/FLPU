<?php
$d = date("d");
$m = date("m");
$y = date("Y");
?>
        <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>БСВТ АНАЛИТИКА</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <!-- Styles -->
    <!-- app -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Styles for analyst -->
    <link href="{{ asset('css/analyst.css') }}" rel="stylesheet">

    <!-- Styles MEDIA -->
    <link href="{{ asset('css/media.css') }}" rel="stylesheet">

    <!-- Styles AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- fancybox -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css"/>

    <!-- Styles MEDIA -->
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">

    <!-- NEW STYLES -->
    <link href="{{ asset('css/new_design.css') }}" rel="stylesheet">
<style>
    p {
        text-indent: 40px; /* Отступ первой строки в пикселах */
    }
</style>
</head>
<body>
<div id="app" data-sticky_parent>
    <header class="page-header">
        <div class="container row_top">
            <div class="user_rolles">
                @auth
                    {{ Auth::user()->roles()->first()->name }} : {{ Auth::user()->surname }} {{ Auth::user()->name }}
                @endauth
            </div>

            @include('partials.cabinets')
        </div>
    </header>

    <aside class="page-aside">
        <div class="page-aside__wrapper" data-sticky_column>
            <div class="logo-box">
                <a class="logo-text" href="{{ url('/') }}"> <img src="{{asset('images/logo.png')}}" alt=""/> </a>
            </div>
            <ul class="nav__list">
                @auth()
                    @foreach(\App\ReportType::$data as $link => $title)
                        <li class="nav__item @if(Request::is('report/'. $link) || Request::is('report/'. $link.'/*') || Request::is('analyst/'.$link) || Request::is('analyst/'.$link.'/*') || Request::is('manager/'.$link) || Request::is('manager/'.$link.'/*')) {{'active'}} @endif">

                            <a href="/report/{{ $link }}" class="nav__link">{{ $title }}</a>
                        </li>
                    @endforeach
                @endauth
            </ul>
        </div>
    </aside>

    <main class="page-main">
        <div class="page-title container">
            <h1>Аналитика</h1>
        </div>

        <div class="page-info container">
            <div class="page-subtitle">

            </div>

        </div>
        @yield('content')
    </main>

    <footer class="page-footer">
        <div class="container">
            <div class="copyright">
                © Copyright 2018. Все права защищены
            </div>
            <div class="footer_doc">
                <a href="/reglament">Правила и регламент регистрации</a>
            </div>
            <div class="portfolio_box">
                Разработка системы<span class="logo_east_tech"></span><a href="http://east-tech.by/">“Восточные технологии”</a>
            </div>
        </div>
    </footer>
</div>

<div class="bugs" oncLick="showModalBugs();">
    <span>Нашли баг?</span>
</div>

<!-- Scripts -->

<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>

<!-- calendar -->

<script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/js/gijgo.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/js/messages/messages.ru-ru.js" type="text/javascript"></script>
<link href="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/css/gijgo.min.css" rel="stylesheet" type="text/css"/>

{{-- CKEDITOR settings --}}

@yield('scripts')


<script src="{{asset('js/script.js')}}"></script>

<script src="{{asset('js/sticky-kit.js')}}"></script>

</body>
</html>