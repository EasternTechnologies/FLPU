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
    
</head>
<body>
<div id="app" class="analyst">

    <header>
        <div class="container row_top">
            <div class="user_rolles">
                @auth
                    {{ Auth::user()->roles()->first()->name }} : {{ Auth::user()->surname }} {{ Auth::user()->name }}
                @endauth
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
    
    @section('nav_header_other')
        <div class="nav_header_other">
            <div class="container">
                
                <ul class="">

                    @foreach(\App\ReportType::$data as $link => $title)
                    
                        <li class="@if(Request::is('report/'. $link) || Request::is('report/'. $link.'/*') || Request::is('analyst/'.$link) || Request::is('analyst/'.$link.'/*') || Request::is('manager/'.$link) || Request::is('manager/'.$link.'/*')) {{'active'}} @endif">

                            	<a href="/report/{{ $link }}" class="nav-link">{{ $title }}</a>

                        </li>
                    @endforeach


                </ul>
                <span class="close-mob">
                		x
                	</span>
            </div>
        </div>
    @endsection
    <div id="menu-mob2" class="container menu-mob">
    	Меню
    </div>
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
            <div class="row">
                <div class="container">
                    <ul class="alert alert-success">
                        {{ session('status') }}
                    </ul>
                </div>
            </div>
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
    
    <div class="bugs" oncLick="showModalBugs();">
      <span>Нашли баг?</span>
    </div>
</div>

<!-- bugs modal -->
<div class="modal modal__bugs">
    <div class="modal__close" oncLick="closeModal();"></div>
    <div class="bugs-form">
      <h2>Введите Ваши данные и вопрос, все поля обязательны для заполнения:</h2>
      <form action="/bug" method="post" enctype="multipart/form-data">
        <p class="bugs-form__block">
          <label for="bugs_select">Выберите тему</label>
          <select id="bugs_select" name="theme" required>
            <option value="info">Информация на сайте</option>
            <option value="error">Ошибка в работе сайта</option>
            <option value="suggestion">Предложения/пожелания по сайту</option>
            <option value="registration">Регистрация/авторизация</option>
            <option value="other">Другие вопросы и комментарии</option>
          </select>
        </p>
        <p class="bugs-form__block">
          <label for="bugs_textarea">Опишите ошибку</label>
          <textarea id="bugs_textarea" rows="5" name="text" required></textarea>
        </p>
        <p class="bugs-form__block">
          <label for="bugs_file">Прикрепите файл с изображением ошибки</label>
          <input id="bugs_file" type="file" name="file" value="">
        </p>
          <input type="hidden" name="url" value="{{ Request::url() }}">
          <input type="hidden" name="_token" value="{{csrf_token()}}">
        <p class="bugs-form__block bugs-form__block--submit">
          <button type="submit">Отправить</button>
        </p>
      </form>
    </div>

    <!-- <div class="bg_popup"></div>

    <div class="popup_form" style="display: none;">
        <h4 class="mb30 alert_text_out"></h4>
        <div class="box_save_article">

            <span class="button butt_alert_close">ОК</span>
        </div>
    </div> -->

</div>

<!-- alert modal -->
<div class="popup popup_alert" style="display: none;">
    <div class="bg_popup"></div>

    <div class="popup_form" style="display: none;">
        <h4 class="mb30 alert_text_out"></h4>
        <div class="box_save_article">

            <span class="button butt_alert_close">ОК</span>
        </div>
    </div>

</div>

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

<!-- publish modal -->
<div class="popup popup_publish" style="display: none;">
    <div class="bg_popup"></div>

    <div class="popup_form" style="display: none;">
        <h4 class="mb10">Вы действительно хотите опубликовать отчет?</h4>
        <p class="mb30 publish_text_out"></p>
        <div class="box_save_article">
            <span class="button butt_dell" onclick="publishContinue();">Опубликовать</span><span class="button butt_close" onclick="publishClose();">Отмена</span>
        </div>
    </div>

</div>

<!-- popup_addapprove modal -->
<div class="popup popup_addapprove" style="display: none;">
    <div class="bg_popup"></div>

    <div class="popup_form" style="display: none;">
        <h4 class="mb10">Вы действительно хотите отправить на утверждение материал?</h4>
        <p class="mb30 addapprove_text_out"></p>
        <div class="box_save_article">
            <span class="button butt_dell" onclick="addApproveContinue();">Отправить</span><span class="button butt_close" onclick="addApproveClose();">Отмена</span>
        </div>
    </div>

</div>

<!-- popup_approve modal -->
<div class="popup popup_approve" style="display: none;">
    <div class="bg_popup"></div>

    <div class="popup_form" style="display: none;">
        <h4 class="mb10">Вы действительно хотите утвердить материал?</h4>
        <p class="mb30 approve_text_out"></p>
        <div class="box_save_article">
            <span class="button butt_dell" onclick="approveContinue();">Утвердить</span><span class="button butt_close" onclick="approveClose();">Отмена</span>
        </div>
    </div>

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



<script type="text/javascript" charset="utf-8">

    var form;
    var formPublish;
    var formApprove;


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

    //
    function publishName(f, text) {
        formPublish = f;
        jQuery('.popup_publish').fadeIn(250);
        jQuery('.popup_publish .popup_form').show(500);
        jQuery('.publish_text_out').text(text);
    }


    function publishContinue() {

        jQuery('.publish_text_out').text('');
        jQuery('.popup_publish .popup_form').hide(500);
        jQuery('.popup_publish').fadeOut(250);

        if (formPublish) formPublish.submit();
    }

    function publishClose() {
        jQuery('.publish_text_out').text('');
        jQuery('.popup_publish .popup_form').hide(500);
        jQuery('.popup_publish').fadeOut(250);
        formPublish = null;
    }

    //Отправить на утверждение
    function addApprove(f, text) {
        formApprove = f;
        jQuery('.popup_addapprove').fadeIn(250);
        jQuery('.popup_addapprove .popup_form').show(500);
        jQuery('.addapprove_text_out').text(text);
    }

    function addApproveContinue() {

        jQuery('.addapprove_text_out').text('');
        jQuery('.popup_addapprove .popup_form').hide(500);
        jQuery('.popup_addapprove').fadeOut(250);

        if (formApprove) formApprove.submit();
    }

    function addApproveClose() {
        jQuery('.addapprove_text_out').text('');
        jQuery('.popup_addapprove .popup_form').hide(500);
        jQuery('.popup_addapprove').fadeOut(250);
        formApprove = null;
    }

    //Утвердить
    function approve(f, text) {
        formApprove = f;
        jQuery('.popup_approve').fadeIn(250);
        jQuery('.popup_approve .popup_form').show(500);
        jQuery('.approve_text_out').text(text);
    }

    function approveContinue() {

        jQuery('.approve_text_out').text('');
        jQuery('.popup_approve .popup_form').hide(500);
        jQuery('.popup_approve').fadeOut(250);

        if (formApprove) formApprove.submit();
    }

    function approveClose() {
        jQuery('.approve_text_out').text('');
        jQuery('.popup_approve .popup_form').hide(500);
        jQuery('.popup_approve').fadeOut(250);
        formApprove = null;
    }


</script>
<script type="text/javascript" charset="utf-8">

    jQuery(document).ready(function () {
        const app = new Vue({
            el: '#app'
        });

        jQuery('[data-fancybox="gallery"]').fancybox();

        if (jQuery('[name=editor1]').length) {
          CKEDITOR.replace('editor1');
        }
        if(jQuery('[name=title_1]').length) {
            CKEDITOR.replace('title_1');
        }
        if(jQuery('[name=place]').length) {
            CKEDITOR.replace('place');
        }


        jQuery('.calendar_start').datepicker({
            keyboardNavigation: true,
            modal: true,
            header: true,
            footer: true,
            uiLibrary: 'bootstrap4',
            locale: 'ru-ru',
            value: '<?php echo $d . '.' . $m . '.' . $y; ?>',
            format: 'dd.mm.yyyy',
        });

        jQuery('.calendar_end').datepicker({
            keyboardNavigation: true,
            modal: true,
            header: true,
            footer: true,
            uiLibrary: 'bootstrap4',
            locale: 'ru-ru',
            value: '<?php echo $d . '.' . $m . '.' . $y; ?>',
            format: 'dd.mm.yyyy',

        });

        jQuery('.calendar_start').change(function () {
            var data_change = jQuery(this).val();
            var arr = data_change.split('.');
            var d = Number(arr[0]) + 1;
            var m = Number(arr[1]) - 1;
            var y = Number(arr[2]);
            var date = new Date(y, m, d).getTime() / 1000;
            jQuery('[name=start_period]').val(date);
        })

        jQuery('.calendar_end').change(function () {
            var data_change = jQuery(this).val();
            var arr = data_change.split('.');
            var d = Number(arr[0]) + 1;
            var m = Number(arr[1]) - 1;
            var y = Number(arr[2]);
            var date = new Date(y, m, d).getTime() / 1000;
            jQuery('[name=end_period]').val(date);
        })

        if (jQuery('[data-fancybox="gallery"]').length)
            jQuery('[data-fancybox="gallery"]').fancybox();

        function readURL(input, num) {

            if (input.files && input.files[0]) {

                var file_type = input.files[0].name.split('.').pop();
                jQuery(input).parent().siblings('.delete_content').show()
                jQuery(input).parent().siblings('.delete_img').show()

                jQuery('.item_add_gallery.item_num_' + num).addClass('active');

                var reader = new FileReader();

                jQuery('.item_add_gallery.item_num_' + num + ' img').show();
                jQuery('.item_add_gallery.item_num_' + num + ' span').hide();

                var result;

                if (file_type == 'jpg' 
                  || file_type == 'jpeg' 
                  || file_type == 'png' 
                  || file_type == 'gif' 
                  || file_type == 'svg') {
                  reader.onload = function (e) {
                      jQuery('.pic_img.num_' + num).attr('src', e.target.result);
                    };
                  } else {
                    reader.onload = function (e) {
                      jQuery('.item_add_gallery.item_num_' + num + ' img').hide();
                      jQuery('.item_add_gallery.item_num_' + num + ' span').show();
                      jQuery('.item_add_gallery.item_num_' + num + ' span').html(input.files[0].name);
                    };
                  }
                
                reader.readAsDataURL(input.files[0]);
            } else {
                jQuery('.item_add_gallery.item_num_' + num).removeClass('active');

                jQuery(input).parent().siblings('.delete_content').hide()
                jQuery(input).parent().siblings('.delete_img').hide()

                jQuery('.item_add_gallery.item_num_' + num + ' img').hide();
                jQuery('.item_add_gallery.item_num_' + num + ' span').show();
            }
        }

        var pic = [];
        pic[1] = jQuery('.item_add_gallery.item_num_1 img').attr('src');
        pic[2] = jQuery('.item_add_gallery.item_num_2 img').attr('src');
        pic[3] = jQuery('.item_add_gallery.item_num_3 img').attr('src');

        jQuery(".pic").change(function () {
            var num = jQuery(this).attr('data-num');
            readURL(this, num);
            jQuery('.item_add_gallery.item_num_' + num + ' .reset_img').val(pic[num]);
        });

        jQuery('.item_add_gallery.active').children('span').hide();
        jQuery('.item_add_gallery').children('.delete_content').hide();
        jQuery('.item_add_gallery.active').children('.delete_content').show();
        jQuery('.item_add_gallery').children('.delete_img').hide();
        jQuery('.item_add_gallery.active').children('.delete_img').show();
        jQuery('.item_add_gallery').children('img').hide();
        jQuery('.item_add_gallery.active').children('img').show();

        jQuery('.pic_img').each(function(){
          var file_type_img = $(this).attr('src').split('.').pop();
          if (file_type_img == 'jpg' 
            || file_type_img == 'jpeg' 
            || file_type_img == 'png' 
            || file_type_img == 'gif' 
            || file_type_img == 'svg') {
            $(this).show();
            $(this).siblings('span').hide();
          } else {
            $(this).hide();
            $(this).siblings('span').show();
          }
        })

        jQuery('.delete_content').on('click', function (event) {
          $(this).siblings().children('.pic').val('');
          $(this).siblings().children('img').attr('src', '#');
          $(this).siblings().children('.pic').change();
          $(this).siblings().children('span').html('Нажмите, чтобы добавить материал');
        })
        jQuery('.delete_img').on('click', function (event) {
          $(this).siblings().children('.pic').val('');
          $(this).siblings().children('img').attr('src', '#');
          $(this).siblings().children('.pic').change();
          $(this).siblings().children('span').html('Нажмите, чтобы добавить изображение');
        })

        //tag
        jQuery(".butt_add_tag1").click(function (e) {
            e.preventDefault();
            jQuery('.popup_tag_country').fadeIn(500);
        });
        jQuery(".butt_add_tag2").click(function (e) {
            e.preventDefault();
            jQuery('.popup_tag_vvttype').fadeIn(500);
        });
        jQuery(".butt_add_tag3").click(function (e) {
            e.preventDefault();
            jQuery('.popup_tag_company').fadeIn(500);
        });
        jQuery(".butt_add_tag4").click(function (e) {
            e.preventDefault();
            jQuery('.popup_tag_personalities').fadeIn(500);
        });

        jQuery(".close_tag").click(function (e) {
            jQuery('.popup_tag').fadeOut(500);

            //clear form
            jQuery('.out_country_select').text("");
            jQuery('.hide_company_select_contry').val("");
            jQuery('.out_personalities_country_select').text("");
            jQuery('.hide_personalities_select_contry').val("");
            jQuery('.out_vvt_select').text("");
            jQuery('.hide_company_select_vvt').val("")

            hide_company_select_contry_id = [];
            hide_company_select_contry_name = [];
            hide_company_select_vvt_id = [];
            hide_company_select_vvt_name = [];
            hide_personalities_select_contry_id = [];
            hide_personalities_select_contry_name = [];

            // jQuery('.personalities_select_country option').removeAttr('selected');
            // jQuery('.company_select_country option').removeAttr('selected');
            // jQuery('.company_select_vvt option').removeAttr('selected');

            jQuery('.personalities_select_country option:first-child').attr('selected', 'selected');
            jQuery('.company_select_country option:first-child').attr('selected', 'selected');
            jQuery('.company_select_vvt option:first-child').attr('selected', 'selected');

            jQuery('.personalities_select_country option').removeClass('active');
            jQuery('.company_select_country option').removeClass('active');
            jQuery('.company_select_vvt option').removeClass('active');
        });

        jQuery(".bg_popup_tag").click(function (e) {
            jQuery('.popup_tag').fadeOut(500);

            //clear form
            jQuery('.out_country_select').text("");
            jQuery('.hide_company_select_contry').val("");
            jQuery('.out_personalities_country_select').text("");
            jQuery('.hide_personalities_select_contry').val("");

            hide_company_select_contry_id = [];
            hide_company_select_contry_name = [];
            hide_company_select_vvt_id = [];
            hide_company_select_vvt_name = [];
            hide_personalities_select_contry_id = [];
            hide_personalities_select_contry_name = [];

            // jQuery('.personalities_select_country option').removeAttr('selected');
            // jQuery('.company_select_country option').removeAttr('selected');

            jQuery('.personalities_select_country option:first-child').attr('selected', 'selected');
            jQuery('.company_select_country option:first-child').attr('selected', 'selected');
        });

        jQuery(".tags_form .butt_add").click(function (e) {
            e.preventDefault();

            jQuery('[name="tag"]').val("");

            jQuery('.personalities_select_country option').removeAttr('selected');
            jQuery('.company_select_country option').removeAttr('selected');
            jQuery('.company_select_vvt option').removeAttr('selected');

            jQuery('.personalities_select_country option:first-child').attr('selected', 'selected');
            jQuery('.company_select_country option:first-child').attr('selected', 'selected');
            jQuery('.company_select_vvt option:first-child').attr('selected', 'selected');
        });

        jQuery(".butt_add_tag").click(function (e) {
            e.preventDefault();
        });

        //tags




//        console.log(jQuery(".out_country_select").text())
//        console.log(hide_company_select_contry_name);


        //////////////////////////////////////////////////////////////////////
//        console.log(jQuery(".hide_company_select_contry").val());
//
//        var hide_company_select_contry_name = [];
//        var hide_company_select_contry_id = [];
//        var hide_company_select_vvt_id = [];
//        var hide_company_select_vvt_name = [];
//
//        jQuery('.company_select_country').change(function () {
//
//            if(jQuery(".out_country_select").text()) {
//                var hide_company_select_contry_name = jQuery(".out_country_select").text().split(',');
//            }
//
//            if(jQuery(".hide_company_select_contry").val()) {
//                var hide_company_select_contry_id = jQuery(".hide_company_select_contry").val().split(',');
//            }
//
//
//            console.log(hide_company_select_contry_name)
//            console.log(hide_company_select_contry_id)
//
//
//
////            console.log(hide_company_select_contry_name);
//
//            var el_id = jQuery('.company_select_country option:selected').val();
//
//            var el_name = jQuery('.company_select_country option:selected').text();
//
//            if (jQuery('.company_select_country option:selected').hasClass('active')) {
//                jQuery('.company_select_country option:selected').removeClass('active');
//
//                if (hide_company_select_contry_id.length) {
//                    for (var i = 0; i < hide_company_select_contry_id.length; i++) {
//                        if (hide_company_select_contry_id[i] == el_id) {
//                            hide_company_select_contry_id.splice(i, 1);
//                        }
//                    }
//                }
//
//                if (hide_company_select_contry_name.length) {
//
//                    for (var i = 0; i < hide_company_select_contry_name.length; i++) {
//                        if (hide_company_select_contry_name[i] == " " + el_name) {
//                            hide_company_select_contry_name.splice(i, 1);
//                        }
//                    }
//                }
//            } else {
//                hide_company_select_contry_id.push(el_id);
//                hide_company_select_contry_name.push(" " + el_name);
//                jQuery('.company_select_country option:selected').addClass('active');
//            }
//
//            jQuery(".out_country_select").text(hide_company_select_contry_name);
//            jQuery(".hide_company_select_contry").val(hide_company_select_contry_id);
//
////            //
////            setTimeout(function () {
////                jQuery('.company_select_country option').removeAttr('selected');
////                jQuery('.company_select_country option:first-child').attr('selected', 'selected');
////            }, 100);
//        });
//
//
//        jQuery('.company_select_vvt').change(function () {
//
//            var el_id = jQuery(".company_select_vvt option:selected").val();
//            var el_name = jQuery(".company_select_vvt option:selected").text();
//
//            if (jQuery('.company_select_vvt option:selected').hasClass('active')) {
//                jQuery('.company_select_vvt option:selected').removeClass('active');
//
//                if (hide_company_select_vvt_id.length) {
//                    for (var i = 0; i < hide_company_select_vvt_id.length; i++) {
//                        if (hide_company_select_vvt_id[i] == el_id) {
//                            hide_company_select_vvt_id.splice(i, 1);
//                        }
//                    }
//                }
//
//                if (hide_company_select_vvt_name.length) {
//
//                    for (var i = 0; i < hide_company_select_vvt_name.length; i++) {
//                        if (hide_company_select_vvt_name[i] == " " + el_name) {
//                            hide_company_select_vvt_name.splice(i, 1);
//                        }
//                    }
//                }
//            } else {
//                hide_company_select_vvt_id.push(el_id);
//                hide_company_select_vvt_name.push(" " + el_name);
//                jQuery('.company_select_vvt option:selected').addClass('active');
//            }
//
//            jQuery(".out_vvt_select").text(hide_company_select_vvt_name);
//            jQuery(".hide_company_select_vvt").val(hide_company_select_vvt_id);
//            //
////            setTimeout(function () {
////                jQuery('.company_select_vvt option').removeAttr('selected');
////                jQuery('.company_select_vvt option:first-child').attr('selected', 'selected');
////            }, 100);
////
//        });
//
//
//
//        var hide_personalities_select_contry_id = [];
//        var hide_personalities_select_contry_name = [];
//        // var hide_personalities_select_vvt_id = [];
//        // var hide_personalities_select_vvt_name = [];
//        jQuery('.personalities_select_country').change(function () {
//
//            var el_id = jQuery(".personalities_select_country option:selected").val();
//            var el_name = jQuery(".personalities_select_country option:selected").text();
//
//            if (jQuery('.personalities_select_country option:selected').hasClass('active')) {
//                jQuery('.personalities_select_country option:selected').removeClass('active');
//
//                if (hide_personalities_select_contry_id.length) {
//                    for (var i = 0; i < hide_personalities_select_contry_id.length; i++) {
//                        if (hide_personalities_select_contry_id[i] == el_id) {
//                            hide_personalities_select_contry_id.splice(i, 1);
//                        }
//                    }
//                }
//
//                if (hide_personalities_select_contry_name.length) {
//
//                    for (var i = 0; i < hide_personalities_select_contry_name.length; i++) {
//                        if (hide_personalities_select_contry_name[i] == (" " + el_name) || hide_personalities_select_contry_name[i] == el_name) {
//                            hide_personalities_select_contry_name.splice(i, 1);
//                        }
//                    }
//                }
//            } else {
//                hide_personalities_select_contry_id.push(el_id);
//                hide_personalities_select_contry_name.push(" " + el_name);
//                jQuery('.personalities_select_country option:selected').addClass('active');
//            }
//
//            jQuery(".out_personalities_country_select").text(hide_personalities_select_contry_name);
//            jQuery(".hide_personalities_select_contry").val(hide_personalities_select_contry_id);
//
//          //  setTimeout(function () {
//          //      jQuery('.personalities_select_country option').removeAttr('selected');
//          //      jQuery('.personalities_select_country option:first-child').attr('selected', 'selected');
//          //  }, 100);
////
//        });
        //////////////////////////////////////////////////////////////////////




//         jQuery('.personalities_select_vvt').change(function () {
//
//             var el_id = jQuery(".personalities_select_vvt option:selected").val();
//             var el_name = jQuery(".personalities_select_vvt option:selected").text();
//
//             if (jQuery('.personalities_select_vvt option:selected').hasClass('active')) {
//                 jQuery('.personalities_select_vvt option:selected').removeClass('active');
//
//                 if (hide_personalities_select_vvt_id.length) {
//                     for (var i = 0; i < hide_personalities_select_vvt_id.length; i++) {
//                         if (hide_personalities_select_vvt_id[i] == el_id) {
//                             hide_personalities_select_vvt_id.splice(i, 1);
//                         }
//                     }
//                 }
//
//                 if (hide_personalities_select_vvt_name.length) {
//
//                     for (var i = 0; i < hide_personalities_select_vvt_name.length; i++) {
//                         if (hide_personalities_select_vvt_name[i] == (" " + el_name) || hide_personalities_select_vvt_name[i] == el_name) {
//                             hide_personalities_select_vvt_name.splice(i, 1);
//                         }
//                     }
//                 }
//             } else {
//                 hide_personalities_select_vvt_id.push(el_id);
//                 hide_personalities_select_vvt_name.push(" " + el_name);
//                 jQuery('.personalities_select_vvt option:selected').addClass('active');
//             }

//             jQuery(".out_personalities_vvt_select").text(hide_personalities_select_vvt_name);
//             jQuery(".hide_personalities_select_vvt").val(hide_personalities_select_vvt_id);

// //            setTimeout(function () {
// //                jQuery('.personalities_select_vvt option').removeAttr('selected');
// //                jQuery('.personalities_select_vvt option:first-child').attr('selected', 'selected');
// //            }, 100);
// //
//         });

        //modalka add cat
        jQuery(".onclick_popup_cat").click(function (e) {
            e.preventDefault();
            jQuery('.popup_cat').fadeIn(500);
        });
        jQuery(".close_popup_cat").click(function (e) {
            jQuery('.popup_cat').fadeOut(500);
        });

        jQuery(".bg_popup_cat").click(function (e) {
            jQuery('.popup_cat').fadeOut(500);
        });

        //modalka add subcat
        jQuery(".onclick_popup_subcat").click(function (e) {
            e.preventDefault();
            jQuery('.popup_subcat').fadeIn(500);

            var id = jQuery(this).attr('date-cat-id');
            jQuery('input[name=category]').val(id);
        });
        jQuery(".close_popup_subcat").click(function (e) {
            jQuery('.popup_subcat').fadeOut(500);
        });

        jQuery(".bg_popup_subcat").click(function (e) {
            jQuery('.popup_subcat').fadeOut(500);
        });

        jQuery('.page_create_post form').submit(function (e) {
            var error = 0;

            CKEDITOR.instances['title'].updateElement();
            CKEDITOR.instances['place'].updateElement();
            CKEDITOR.instances['theme'].updateElement();
            
            var title = jQuery('[name=title]').val();
            if (title == '' || !title) {
              error++;
                if(!jQuery('[name=title]').hasClass('error')) {
                  jQuery('[name=title]').addClass('error');
                  jQuery('[name=title]').parent().parent().addClass('error-block');
                  jQuery('[name=title]').parent().prev().append('<p class="error-text alert alert-danger">Это поле должно быть заполнено!</p>');
                }                
              }                 
              else {
                jQuery('[name=title]').removeClass('error');
                jQuery('[name=title]').parent().parent().removeClass('error-block');
                jQuery('[name=title]').parent().prev().children().last().remove();
            }

            if(jQuery('[name=place]').length) {
              if (jQuery('[name=place]').val() == '' || !jQuery('[name=place]').val()) {
                error++;
                if (!jQuery('[name=place]').hasClass('error')) {
                  jQuery('[name=place]').addClass('error');
                  jQuery('[name=place]').parent().parent().addClass('error-block');
                  jQuery('[name=place]').parent().prev().append('<p class="error-text alert alert-danger">Это поле должно быть заполнено!</p>');
                }
              } else {
                jQuery('[name=place]').removeClass('error');
                jQuery('[name=place]').parent().parent().removeClass('error-block');
                jQuery('[name=place]').parent().prev().children().last().remove();
              }
            }

            if(jQuery('[name=theme]').length) {
              if (jQuery('[name=theme]').val() == '' || !jQuery('[name=theme]').val()) {
                error++;
                if (!jQuery('[name=theme]').hasClass('error')) {
                  jQuery('[name=theme]').addClass('error');
                  jQuery('[name=theme]').parent().parent().addClass('error-block');
                  jQuery('[name=theme]').parent().prev().append('<p class="error-text alert alert-danger">Это поле должно быть заполнено!</p>');
                }
              } else {
                jQuery('[name=theme]').removeClass('error');
                jQuery('[name=theme]').parent().parent().removeClass('error-block');
                jQuery('[name=theme]').parent().prev().children().last().remove();
              }
            }

            // if(jQuery('[name=country]').length) {
            //     if (jQuery('[name=country]').val() == '' || !jQuery('[name=country]').val()) {
            //         error++;
            //         jQuery('[name=country]').addClass('error');
            //     }
            // }

            // if(jQuery('[name=region]').length) {
            //     if (jQuery('[name=region]').val() == '' || !jQuery('[name=region]').val()) {
            //         error++;
            //         jQuery('[name=region]').addClass('error');
            //     }
            // }

            // if(jQuery('[name=military_expenses]').length) {
            //     if (jQuery('[name=military_expenses]').val() == '' || !jQuery('[name=military_expenses]').val()) {
            //         error++;
            //         jQuery('[name=military_expenses]').addClass('error');
            //     }
            // }

            if(jQuery('[name=editor1]').length) {

                var edit1 = CKEDITOR.instances.editor1.getData();

                if (edit1 == '' || !edit1) {
                    error++;
                    jQuery('.cke_chrome').addClass('error');
                }
            }

            var start_period = Number(jQuery('[name=start_period]').val());
            var end_period = Number(jQuery('[name=end_period]').val());

            if ((end_period - start_period) < 0) {
                error++;
                jQuery('.calendar_wrap').addClass('error');
            }

            if (error) {
                //jQuery('.alert-mess').append('<li class="error">Заполните обязательные поля!</li>');
                jQuery('.popup_alert').fadeIn(250);
                jQuery('.popup_alert .popup_form').show(500);
                jQuery('.alert_text_out').html('<p class="error">Заполните обязательные поля!</p>');
                e.preventDefault();
                return false;
            }
        })

        jQuery('.butt_alert_close').click(function () {
            jQuery('.popup_alert .popup_form').hide(250);
            jQuery('.popup_alert').fadeOut(500);
            jQuery('.alert_text_out').html('');
        })

        jQuery('input').focus(function () {
            if (jQuery(this).hasClass('error')) {
                jQuery(this).removeClass('error');
            }
        });

        jQuery('textarea').focus(function () {
            if (jQuery(this).hasClass('error')) {
                jQuery(this).removeClass('error');
            }
        });

        var editor = CKEDITOR.instances.editor1;
        if (editor) {
            editor.on('change', function () {
                jQuery('#cke_editor1').removeClass('error');
            });
        }

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

		jQuery('#menu-mob2').click(function () {

			if(jQuery(this).hasClass('active')) {
				jQuery(this).removeClass('active');
				jQuery('.nav_header_other').removeClass('mob-active');
			} else {
				jQuery(this).addClass('active');
				jQuery('.nav_header_other').addClass('mob-active');
			}

		});

		//close
		jQuery('.nav_header_other .close-mob').click(function () {

			jQuery('#menu-mob2').removeClass('active');
			jQuery('.nav_header_other').removeClass('mob-active');

		});

		/*end menu-mob */

		/*jQuery('.butt_search').click(function() {
			jQuery('header .container.row_2 .right-box>form').submit();
		})*/
    });
</script>
<script type="text/javascript" charset="utf-8">
    window.onload = function () {

        //init select
        jQuery('.personalities_select_country option').removeAttr('selected');
        jQuery('.company_select_country option').removeAttr('selected');

        jQuery('.personalities_select_country option:first-child').attr('selected', 'selected');
        jQuery('.company_select_country option:first-child').attr('selected', 'selected');

        //date
        var $w = jQuery('select.start_period option:selected').attr('data-week');
        jQuery('select option[data-week]').each(function (i, elem) {

            jQuery(elem).removeClass('data_active');

            if (jQuery(elem).attr('data-week') == $w) {
                jQuery(elem).addClass('data_active');
            }
        });

        jQuery('select.end_period option[data-week]').each(function (i, elem) {

            jQuery(elem).attr('disabled', 'disabled');

            if (jQuery(elem).attr('data-week') == $w) {
                jQuery(elem).removeAttr('disabled');
            }


        });


        jQuery("select.start_period").change(function () {
            var $w = jQuery('select.start_period option:selected').attr('data-week');

            var $day_end = jQuery('select.start_period option:selected').attr('data-day-end');
            jQuery('select.end_period option').removeAttr("selected");
            if ($day_end == 0) {
                jQuery('select.end_period option:last-child').attr("selected", "selected");
            } else {
                jQuery('select.end_period option[data-day-end=' + $day_end + ']').attr("selected", "selected");
            }

            jQuery('select option[data-week]').each(function (i, elem) {

                jQuery(elem).removeClass('data_active');

                if (jQuery(elem).attr('data-week') == $w) {
                    jQuery(elem).addClass('data_active');
                }


            });

            jQuery('select.end_period option[data-week]').each(function (i, elem) {

                jQuery(elem).attr('disabled', 'disabled');

                if (jQuery(elem).attr('data-week') == $w) {
                    if (jQuery(elem).val() < jQuery('select.start_period option:selected').val()) {
                        jQuery(elem).removeClass('data_active');

                    } else {
                        jQuery(elem).removeAttr('disabled');
                    }
                }

            });
        })

        //month
        var day_month = jQuery('select.start_period_month option:selected').attr('data-day');
        jQuery('select.end_period_month option[data-day]').each(function (i, elem) {
            day_month2 = jQuery(elem).attr('data-day');
            jQuery(elem).attr('disabled', 'disabled');
            jQuery(elem).removeClass('data_active');

            if (Number(day_month) <= Number(day_month2)) {

                jQuery(elem).addClass('data_active');
                jQuery(elem).removeAttr('disabled');
            }

        });

        jQuery("select.start_period_month").change(function () {
            day_month = jQuery('select.start_period_month option:selected').attr('data-day');

            jQuery('select.end_period_month option[data-day]').each(function (i, elem) {
                day_month2 = jQuery(elem).attr('data-day');
                jQuery(elem).attr('disabled', 'disabled');
                jQuery(elem).removeClass('data_active');

                if (Number(day_month) <= Number(day_month2)) {

                    jQuery(elem).addClass('data_active');
                    jQuery(elem).removeAttr('disabled');
                }

                jQuery(elem).removeAttr('selected');
                jQuery(elem).attr('selected', 'selected');
            });
        });


    }


</script>
@yield('scripts')

  <script>
    var showModalBugs = function() {
      $('.bugs').toggleClass('active');
      $('.modal__bugs').toggleClass('active');
      $('body').toggleClass('active');
    }

    var closeModal = function () {
      $('.bugs').toggleClass('active');
      $('.modal__bugs').toggleClass('active');
      $('body').toggleClass('active');
    }
  </script>

<script src="{{asset('js/script.js')}}"></script>

</body>
</html>
