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

    <!-- Styles -->

    <!-- fancybox -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css"/>

    <!-- app -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Styles for analyst -->
    <link href="{{ asset('css/user.css') }}" rel="stylesheet">

    <!-- Styles MEDIA -->
    <link href="{{ asset('css/media.css') }}" rel="stylesheet">

    <!-- Styles AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- Styles MEDIA -->
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    
</head>
<body>
<div id="app" class="analyst">

    <header>
        <div class="container row_top">
            <div class="user_rolles">
                {{ Auth::user()->roles()->first()->name }} : {{ Auth::user()->surname }} {{ Auth::user()->name }}
            </div>
            @include('partials.cabinets')
            <div id="menu-mob1" class="menu-mob">
            	<span></span>
            	<span></span>
            	<span></span>
            </div>
        </div>
        <div class="container row_2">
            @include('partials.row_with_search')
        </div>

    </header>

  
    <main>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('status'))
            <ul class="alert alert-success">
                {{ session('status') }}
            </ul>
        @endif
        @if (session('error'))
            <ul class="alert alert-danger">
                {{ session('error') }}
            </ul>
        @endif
        @yield('content')
    </main>

    <footer>
        <div class="row">
            <div class="container">
                <div class="flex_box">
                    <div class="col-md-4 copyright">
                        © Copyright 2018. Все права защищены
                    </div>
                    <div class="col-md-4 footer_doc">
                        <a href="/reglament">Правила и регламент регистрации</a>
                    </div>
                    <div class="col-md-4 portfolio_box">
                        Разработка сайта<span class="logo_east_tech"></span><a href="http://east-tech.by/">“Восточные технологии”</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- del modal -->
    <div class="popup popup_del" style="display: none;">
        <div class="bg_popup"></div>
    
        <div class="popup_form" style="display: none;">
            <h4 class="mb10">Вы действительно хотите удалить?</h4>
            <p class="mb30 del_text_out"></p>
            <div class="box_save_article">
                <span class="button butt_dell" onclick="dellContinue();">Удалить</span><span class="button butt_close" onclick="dellClose();">Отмена</span>
            </div>
        </div>
    
    </div>
</div>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>

{{-- CKEDITOR settings --}}

<script type="text/javascript" charset="utf-8">
    jQuery(document).ready(function () {

        /* menu-mob */
		jQuery('#menu-mob1').click(function () {
			
			if(jQuery(this).hasClass('active')) {
				jQuery(this).removeClass('active');
				jQuery('.menu_auth').removeClass('mob-active');
			} else {
				jQuery(this).addClass('active');
				jQuery('.menu_auth').addClass('mob-active');
			}
			
		});
		
		//close
		jQuery('header .menu_auth .close-mob').click(function () {
			
			jQuery('#menu-mob1').removeClass('active');
			jQuery('.menu_auth').removeClass('mob-active');
			
		});
		
		/*end menu-mob */

    });
</script>
@yield('scripts')
<script type="text/javascript" charset="utf-8">
    var form;


    function deleteName(f, text) {
        form = f;
        jQuery('.popup_del').fadeIn(250);
        jQuery('.popup_del .popup_form').show(500);
        jQuery('.del_text_out').text(text);

    }

    function dellContinue() {

        jQuery('.del_text_out').text('');
        jQuery('.popup_del .popup_form').hide(500);
        jQuery('.popup_del').fadeOut(250);

        if (form) form.submit();
    }

    function dellClose() {
        jQuery('.del_text_out').text('');
        jQuery('.popup_del .popup_form').hide(500);
        jQuery('.popup_del').fadeOut(250);
        form = null;
    }
</script>
</body>
</html>
