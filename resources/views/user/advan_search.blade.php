<?php
$d = date("d");
$m = date("m");
$y = date("Y");
?>
@extends('layouts.app')

@section('content')
    <div class="container">
      
        <form action="/search" class="search_form_adv" method="get">
            @csrf
            <div class="col-md-12">
                <div class="search-form__filter">
                    <p class="search-form__block">
                        <label> Тип отчета
                            <select class="search-form__field report_type" name="report_type">
                              <option value="all_reports">Все отчеты</option>

                                @foreach($report_types as $slug =>$type)
                                <option value="{{ $slug }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </label>
                    </p>

                    <p class="search-form__block weekly_block">
                        <label> Категории
                            <select class="search-form__field" name="new_weekly">
                                <option value="0">Все отчеты</option>

                                @foreach($weeklycategories as $category)
                                <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
                            </select>
                        </label>
                    </p>

                    <p class="search-form__block monthly_block">
                        <label> Категории
                          <select class="search-form__field" name="new_monthly">
                            <option value="0">Все отчеты</option>

                            @foreach($monthlycategories as $category)
                            <option value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>
                      </label>
                    </p>

                    <p class="search-form__block search-form__block--date">
                        <label class="search-form__title">
                            Период с
                            <input name="start_period_picker" value="" class="calendar_start_3 search-form__field"/>
                            <input type="hidden" value="<?php echo mktime(0, 0, 0, 1, 1, 2015); ?>" name="start_period">
                        </label>
                        <label class="search-form__title">
                            Период по
                            <input name="end_period_picker" value="" class="calendar_end_3 search-form__field"/>
                            <input type="hidden" value="<?php echo mktime(0, 0, 0, $m, $d, $y); ?>" name="end_period">
                        </label>
                    </p>

                </div>
            </div>

            <tagsforsearch-component></tagsforsearch-component>

            <input type="hidden" name="random_key_before" value="">

            <div class="row box_save_article">
                <button class="button button--search pdf-reset">Поиск</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" charset="utf-8">

        jQuery(document).ready(function () {

            jQuery('.report_type').change(function() {

              var val = $(this).find(':selected').val();

              if(val == "weekly") {
                jQuery('.weekly_block').css('position', 'relative');
                jQuery('.weekly_block').css('top', '0');
              } else {
                jQuery('.weekly_block').css('top', '-9999px');
                jQuery('.weekly_block').css('position', 'absolute');
              }

              if(val == "monthly") {
                jQuery('.monthly_block').css('position', 'relative');
                jQuery('.monthly_block').css('top', '0');
              } else {
                jQuery('.monthly_block').css('position', 'absolute');
                jQuery('.monthly_block').css('top', '-9999px');
              }
            });

            jQuery('.checked').click(function () {
                if (jQuery(this).parents('.form-group').hasClass('active')) {
                    jQuery(this).parents('.form-group').removeClass('active');
                    jQuery(this).parents('.form-group').addClass('no_active');
                    jQuery(this).text('Выбрать все');
                    jQuery('.form-group.no_active input[type=checkbox]').removeAttr('checked');
                } else {
                    jQuery(this).parents('.form-group').addClass('active');
                    jQuery(this).parents('.form-group').removeClass('no_active');
                    jQuery(this).text('Отменить все');
                    jQuery('.form-group.active input[type=checkbox]').attr('checked', 'checked');
                }
            })

            jQuery('.sel_all').click(function () {
                if (jQuery(this).parents('.form-group').hasClass('active')) {
                    jQuery(this).parents('.form-group').removeClass('active');
                    jQuery(this).parents('.form-group').addClass('no_active');
                    jQuery(this).text('Выбрать все');
                    jQuery('.form-group.no_active input[type=checkbox]').removeAttr('checked');
                } else {
                    jQuery(this).parents('.form-group').addClass('active');
                    jQuery(this).parents('.form-group').removeClass('no_active');
                    jQuery(this).text('Отменить все');
                    jQuery('.form-group.active input[type=checkbox]').attr('checked', 'checked');
                }
            })

            jQuery('.calendar_start_3').datepicker({
                keyboardNavigation: true,
                modal: true,
                header: true,
                footer: true,
                uiLibrary: 'bootstrap4',
                locale: 'ru-ru',
                value: '01.01.2015',
                format: 'dd.mm.yyyy',
            });

            jQuery('.calendar_end_3').datepicker({
                keyboardNavigation: true,
                modal: true,
                header: true,
                footer: true,
                uiLibrary: 'bootstrap4',
                locale: 'ru-ru',
                value: '{{$d}}.{{$m}}.{{$y}}',
                format: 'dd.mm.yyyy',

            });

            jQuery('.calendar_start_3').change(function () {

                jQuery('.search-form__title').removeClass('error');

                var data_change = jQuery(this).val();
                var arr = data_change.split('.');
                var d = Number(arr[0]) + 1;
                var m = Number(arr[1]) - 1;
                var y = Number(arr[2]);
                var date = new Date(y, m, d).getTime() / 1000;
                jQuery('[name=start_period]').val(date);
            });

            jQuery('.calendar_end_3').change(function () {

                jQuery('.search-form__title').removeClass('error');

                var data_change = jQuery(this).val();
                var arr = data_change.split('.');
                var d = Number(arr[0]) + 1;
                var m = Number(arr[1]) - 1;
                var y = Number(arr[2]);
                var date = new Date(y, m, d).getTime() / 1000;
                jQuery('[name=end_period]').val(date);
            });

            jQuery('.calendar_wrap').show(500);

        });
    </script>
@endsection