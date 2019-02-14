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

    <!-- Styles -->
    <!-- app -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Styles for analyst -->
    <link href="{{ asset('css/analyst.css') }}" rel="stylesheet">

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
                @auth
                    {{ Auth::user()->roles()->first()->name }} : {{ Auth::user()->surname }} {{ Auth::user()->name }}
                @else
                    Гость
                @endauth
            </div>
            <ul class="menu_auth">
                @if(Request::path() === 'stat')
                    <li class="menu-current">
                @else
                    <li>
                        @endif
                        <a href="#">Статистика</a>
                    </li>
                    <span>|</span>
                    @if(Request::path() == '/')
                        <li class="menu-current">
                    @else
                        <li>
                            @endif <a href="/">Управление материалами</a>
                        </li>
                        <span>|</span>
                        @if(Request::path() === 'cabinet')
                            <li class="menu-current">
                        @else
                            <li>
                                @endif <a href="{{ route('cabinet') }}">Личный кабинет</a>
                            </li>
                            <span>|</span>
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
							document.getElementById('logout-form').submit();"> Выход </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
            </ul>
            <div id="menu-mob1" class="menu-mob">
            	<span></span>
            	<span></span>
            	<span></span>
            </div>
        </div>
        <div class="container row_2">
            <div class="logo-box">
                <a class="logo-text" href="{{ url('/') }}"> <img src="{{asset('images/logo.png')}}" alt=""/> </a>
            </div>
            <div class="right-box">
                <label class="search-box"> <a href="#">Расширенный поиск</a>
                    <input class="search" type="text" value="Введите слово"/>
                    <span class="butt_search"></span> </label>
            </div>
        </div>
    </header>

    @yield('nav_header_other')


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

</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js"></script>
<script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
<!-- calendar -->

<script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/js/gijgo.min.js" type="text/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/js/messages/messages.ru-ru.js" type="text/javascript"></script>
<link href="https://cdn.jsdelivr.net/npm/gijgo@1.9.6/css/gijgo.min.css" rel="stylesheet" type="text/css" />

{{-- CKEDITOR settings --}}

<script type="text/javascript" charset="utf-8">
    jQuery(document).ready(function () {
        const app = new Vue({
            el : '#app'
        });
        if(jQuery('[name=editor1]').length)
            CKEDITOR.replace('editor1');

        jQuery('.calendar_start').datepicker({
            keyboardNavigation: true,
            modal: true,
            header: true,
            footer: true,
            uiLibrary: 'bootstrap4',
            locale: 'ru-ru',
            minDate: '01-01-<?php echo $y; ?>',
            maxDate: '31-12-<?php echo $y; ?>',
            value: '01.01.<?php echo $y; ?>',
            format: 'dd.mm.yyyy',
        });

        jQuery('.calendar_end').datepicker({
            keyboardNavigation: true,
            modal: true,
            header: true,
            footer: true,
            uiLibrary: 'bootstrap4',
            locale: 'ru-ru',
            minDate: '01-01-<?php echo $y; ?>',
            maxDate: '31-12-<?php echo $y; ?>',
            value: '31.12.<?php echo $y; ?>',
            format: 'dd.mm.yyyy',

        });

        // if(jQuery('[data-fancybox="gallery"]').length)
            // jQuery('[data-fancybox="gallery"]').fancybox();

        // function readURL(input, num) {

            // if (input.files && input.files[0]) {
            //     var reader = new FileReader();

            //     jQuery('.item_add_gallery.item_num_' + num + ' img').show();
            //     jQuery('.item_add_gallery.item_num_' + num + ' span').hide();

            //     reader.onload = function(e) {
            //         jQuery('.pic_img.num_' + num).attr('src', e.target.result);

            //     };

            //     reader.readAsDataURL(input.files[0]);
            // } else {
            //     jQuery('.item_add_gallery.item_num_' + num + ' img').show();
            //     jQuery('.item_add_gallery.item_num_' + num + ' span').hide();
            // }
        // }

        // jQuery(".pic").change(function() {
        //     var num = jQuery(this).attr('data-num');
        //     readURL(this, num);
        // });

        // jQuery('.pic').click(function() {
        //   var num = jQuery(this).attr('data-num');
        //   // readURL(this, num);
        //   jQuery('.pic_img.num_' + num).attr('src', '#');
        // })

        //tag
        jQuery(".butt_add_tag1").click(function(e) {
            e.preventDefault();
            jQuery('.popup_tag_country').fadeIn(500);
        });
        jQuery(".butt_add_tag2").click(function(e) {
            e.preventDefault();
            jQuery('.popup_tag_vvttype').fadeIn(500);
        });
        jQuery(".butt_add_tag3").click(function(e) {
            e.preventDefault();
            jQuery('.popup_tag_company').fadeIn(500);
        });
        jQuery(".butt_add_tag4").click(function(e) {
            e.preventDefault();
            jQuery('.popup_tag_personalities').fadeIn(500);
        });
        jQuery(".close_tag").click(function(e) {
            jQuery('.popup_tag').fadeOut(500);

            //clear form
            jQuery('.out_country_select').text("");
            jQuery('.hide_company_select_contry').val("");
            jQuery('.out_personalities_country_select').text("");
            jQuery('.hide_personalities_select_contry').val("");
            hide_company_select_contry_id = [];
            hide_company_select_contry_name = [];
            hide_personalities_select_contry_id = [];
            hide_personalities_select_contry_name = [];

            jQuery('.personalities_select_country option').removeAttr('selected');
            jQuery('.company_select_country option').removeAttr('selected');

            jQuery('.personalities_select_country option:first-child').attr('selected','selected');
            jQuery('.company_select_country option:first-child').attr('selected','selected');
        });

        jQuery(".bg_popup_tag").click(function(e) {
            jQuery('.popup_tag').fadeOut(500);

            //clear form
            jQuery('.out_country_select').text("");
            jQuery('.hide_company_select_contry').val("");
            jQuery('.out_personalities_country_select').text("");
            jQuery('.hide_personalities_select_contry').val("");
            hide_company_select_contry_id = [];
            hide_company_select_contry_name = [];
            hide_personalities_select_contry_id = [];
            hide_personalities_select_contry_name = [];

            jQuery('.personalities_select_country option').removeAttr('selected');
            jQuery('.company_select_country option').removeAttr('selected');

            jQuery('.personalities_select_country option:first-child').attr('selected','selected');
            jQuery('.company_select_country option:first-child').attr('selected','selected');
        });
        jQuery(".tags_form .butt_add").click(function(e) {
            e.preventDefault();
        });
        jQuery(".butt_add_tag").click(function(e) {
            e.preventDefault();
        });

        //tags

        var hide_company_select_contry_id = [];
        var hide_company_select_contry_name = [];
        jQuery('.company_select_country').change(function() {

            var el_id = jQuery(".company_select_country option:selected").val();

            var el_name = jQuery(".company_select_country option:selected").text();

            if(jQuery('.company_select_country option:selected').hasClass('active')) {
                jQuery('.company_select_country option:selected').removeClass('active');

                if(hide_company_select_contry_id.length) {
                    for (var i = 0; i < hide_company_select_contry_id.length; i++) {
                        if(hide_company_select_contry_id[i] == el_id) {
                            hide_company_select_contry_id.splice(i, 1);
                        }
                    }
                }

                if(hide_company_select_contry_name.length) {

                    for (var i = 0; i < hide_company_select_contry_name.length; i++) {
                        if(hide_company_select_contry_name[i] == " " + el_name) {
                            hide_company_select_contry_name.splice(i,1);
                        }
                    }
                }
            } else {
                hide_company_select_contry_id.push(el_id);
                hide_company_select_contry_name.push(" " + el_name);
                jQuery('.company_select_country option:selected').addClass('active');
            }

            jQuery(".out_country_select").text(hide_company_select_contry_name);
            jQuery(".hide_company_select_contry").val(hide_company_select_contry_id);
            //
            setTimeout(function() {
                jQuery('.company_select_country option').removeAttr('selected');
                jQuery('.company_select_country option:first-child').attr('selected','selected');
            },100);
        });

        var hide_personalities_select_contry_id = [];
        var hide_personalities_select_contry_name = [];
        jQuery('.personalities_select_country').change(function() {

            var el_id = jQuery(".personalities_select_country option:selected").val();
            var el_name = jQuery(".personalities_select_country option:selected").text();

            if(jQuery('.personalities_select_country option:selected').hasClass('active')) {
                jQuery('.personalities_select_country option:selected').removeClass('active');

                if(hide_personalities_select_contry_id.length) {
                    for (var i = 0; i < hide_personalities_select_contry_id.length; i++) {
                        if(hide_personalities_select_contry_id[i] == el_id) {
                            hide_personalities_select_contry_id.splice(i, 1);
                        }
                    }
                }

                if(hide_personalities_select_contry_name.length) {

                    for (var i = 0; i < hide_personalities_select_contry_name.length; i++) {
                        if(hide_personalities_select_contry_name[i] == (" " + el_name) || hide_personalities_select_contry_name[i] == el_name) {
                            hide_personalities_select_contry_name.splice(i,1);
                        }
                    }
                }
            } else {
                hide_personalities_select_contry_id.push(el_id);
                hide_personalities_select_contry_name.push(" " + el_name);
                jQuery('.personalities_select_country option:selected').addClass('active');
            }

            jQuery(".out_personalities_country_select").text(hide_personalities_select_contry_name);
            jQuery(".hide_personalities_select_contry").val(hide_personalities_select_contry_id);

            setTimeout(function() {
                jQuery('.personalities_select_country option').removeAttr('selected');
                jQuery('.personalities_select_country option:first-child').attr('selected','selected');
            },100);

        });

        //modalka add cat
        jQuery(".onclick_popup_cat").click(function(e) {
            e.preventDefault();
            jQuery('.popup_cat').fadeIn(500);
        });
        jQuery(".close_popup_cat").click(function(e) {
            jQuery('.popup_cat').fadeOut(500);
        });

        jQuery(".bg_popup_cat").click(function(e) {
            jQuery('.popup_cat').fadeOut(500);
        });

        //modalka add subcat
        jQuery(".onclick_popup_subcat").click(function(e) {
            e.preventDefault();
            jQuery('.popup_subcat').fadeIn(500);
        });
        jQuery(".close_popup_subcat").click(function(e) {
            jQuery('.popup_subcat').fadeOut(500);
        });

        jQuery(".bg_popup_subcat").click(function(e) {
            jQuery('.popup_subcat').fadeOut(500);
        });
        
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
<script type="text/javascript" charset="utf-8">
    window.onload = function() {
        jQuery('.calendar_wrap').show(500);
        jQuery('.calendar_start').change(function() {
            var data_change = jQuery(this).val();
            var arr = data_change.split('.');
            var d = Number(arr[0]) + 1;
            var m = Number(arr[1]) - 1;
            var y = Number(arr[2]);
            var date = new Date(y,m,d).getTime()/1000;
            jQuery('[name=start_period]').val(date);
        })

        jQuery('.calendar_end').change(function() {
            var data_change = jQuery(this).val();
            var arr = data_change.split('.');
            var d = Number(arr[0]) + 1;
            var m = Number(arr[1]) - 1;
            var y = Number(arr[2]);
            var date = new Date(y,m,d).getTime()/1000;
            jQuery('[name=end_period]').val(date);
        })
        //init select
        jQuery('.personalities_select_country option').removeAttr('selected');
        jQuery('.company_select_country option').removeAttr('selected');

        jQuery('.personalities_select_country option:first-child').attr('selected','selected');
        jQuery('.company_select_country option:first-child').attr('selected','selected');

        //date
        var $w = jQuery('select.start_period option:selected').attr('data-week');
        jQuery('select option[data-week]').each(function(i,elem) {

            jQuery(elem).removeClass('data_active');

            if(jQuery(elem).attr('data-week') == $w) {
                jQuery(elem).addClass('data_active');
            }
        });

        jQuery('select.end_period option[data-week]').each(function(i,elem) {

            jQuery(elem).attr('disabled','disabled');

            if(jQuery(elem).attr('data-week') == $w) {
                jQuery(elem).removeAttr('disabled');
            }



        });



        jQuery("select.start_period").change(function() {
            var $w = jQuery('select.start_period option:selected').attr('data-week');

            var $day_end = jQuery('select.start_period option:selected').attr('data-day-end');
            jQuery('select.end_period option').removeAttr("selected");
            if($day_end == 0) {
                jQuery('select.end_period option:last-child').attr("selected", "selected");
            } else {
                jQuery('select.end_period option[data-day-end='+ $day_end + ']').attr("selected", "selected");
            }

            jQuery('select option[data-week]').each(function(i,elem) {

                jQuery(elem).removeClass('data_active');

                if(jQuery(elem).attr('data-week') == $w) {
                    jQuery(elem).addClass('data_active');
                }


            });

            jQuery('select.end_period option[data-week]').each(function(i,elem) {

                jQuery(elem).attr('disabled','disabled');

                if(jQuery(elem).attr('data-week') == $w) {
                    if(jQuery(elem).val() < jQuery('select.start_period option:selected').val()) {
                        jQuery(elem).removeClass('data_active');

                    } else {
                        jQuery(elem).removeAttr('disabled');
                    }
                }

            });
        })

        //month
        var day_month = jQuery('select.start_period_month option:selected').attr('data-day');
        jQuery('select.end_period_month option[data-day]').each(function(i,elem) {
            day_month2 = jQuery(elem).attr('data-day');
            jQuery(elem).attr('disabled','disabled');
            jQuery(elem).removeClass('data_active');

            if(Number(day_month) <= Number(day_month2)) {

                jQuery(elem).addClass('data_active');
                jQuery(elem).removeAttr('disabled');
            }

        });

        jQuery("select.start_period_month").change(function() {
            day_month = jQuery('select.start_period_month option:selected').attr('data-day');

            jQuery('select.end_period_month option[data-day]').each(function(i,elem) {
                day_month2 = jQuery(elem).attr('data-day');
                jQuery(elem).attr('disabled','disabled');
                jQuery(elem).removeClass('data_active');

                if(Number(day_month) <= Number(day_month2)) {

                    jQuery(elem).addClass('data_active');
                    jQuery(elem).removeAttr('disabled');
                }

                jQuery(elem).removeAttr('selected');
                jQuery(elem).attr('selected','selected');
            });
        });
    }
</script>
@yield('scripts')
</body>
</html>
