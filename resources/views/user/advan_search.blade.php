<?php
$d = date("d");
$m = date("m");
$y = date("Y");
?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="full_row_center title">Расширенный поиск</h3>
        <form action="/search" method="get">
            @csrf
            <div class="col-md-12">
                <div class="row row_panel">
                    <div class="col-md-6 pl0 item_panel">
                        <span>Тип отчета</span>
                        <span class="select_wrap w100">
				        	<select class="report_type" name="report_type">
                                <option value="all_reports">Все отчеты</option>
                                @foreach($report_types as $slug =>$report_type)
                                    <option value="{{ $slug }}">{{ $report_type }}</option>
                                @endforeach
		                    </select>
			        	</span>
                    </div>
                    <div class="col-md-3 item_panel">
                        <span>Период с</span>
                        <span class="select_wrap calendar_wrap" style="display: none;">
				        	<input name="start_period_picker" value="" class="calendar_start_3"/>
				        	<input type="hidden" value="<?php echo mktime(0, 0, 0, 1, 1, 2015); ?>" name="start_period">
			        	</span>
                    </div>
                    <div class="col-md-3 item_panel">
                        <span>Период по</span>
                        <span class="select_wrap calendar_wrap" style="display: none;">
				        	<input name="end_period_picker" value="" class="calendar_end_3"/>
				        	<input type="hidden" value="<?php echo mktime(0, 0, 0, $m, $d, $y); ?>" name="end_period">
				        </span>
                    </div>
                    <div class="col-md-6 mt30 pl0 item_panel weekly_block">
                        <span>Категории</span>
                        <span class="select_wrap w100">
				        	<select class="new_field" name="new_weekly">
                                <option value="0">Все отчеты</option>

                                {{-- ТУТ НОВЫЙ КОД --}}
                                @foreach($weeklycategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
		                    </select>
			        	</span>
                    </div>
                    <div class="col-md-6 mt30 pl0 item_panel monthly_block">
                        <span>Категории</span>
                        <span class="select_wrap w100">
				        	<select class="new_field" name="new_monthly">
                                <option value="0">Все отчеты</option>

                                {{-- ТУТ НОВЫЙ КОД --}}
                                @foreach($monthlycategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                @endforeach
		                    </select>
			        	</span>
                    </div>
                </div>
            </div>
            {{--<div class="container tags_form mt30">
                <div class="row mb30">
                    <div class="form-group">
                        <h4 class="mb_1">
                          Страны и регионы 
                          <!-- <span class="button button_small_small sel_all">Выбрать все</span> -->
                        </h4>
                        <div class="form-check">
                            <div id="form-check-countries" class="form-check-label grid-col-check-5">
                                @foreach($countries as $country)
                                    <label class="d-flex flex-row align-items-start check_box">
                                        <input name="countries[]" type="checkbox" value="{{$country->id}}"><span>{{ $country->title }}</span></label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb30">
                    <div class="form-group">
                        <h4 class="mb_1">
                        	Тип ВВТ
                        	 <!-- <span class="button button_small_small sel_all">Выбрать все</span> -->
                        	
                        </h4>
                        <div class="form-check ">
                            <div id="form-check-vvt_types" class="form-check-label grid-col-check-6">
                                @foreach($vvt_types as $vvt_type)
                                    <label class="d-flex flex-row align-items-start check_box">
                                        <input name="vvt_types[]" type="checkbox" value="{{ $vvt_type->id }}"><span>{{ $vvt_type->title }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb30">
                    <div class="form-group">
                        <h4 class="mb_1">Компании и организации
                            <!-- <span class="button button_small_small sel_all">Выбрать все</span> -->
                        </h4>
                        <div class="form-check ">
                            <div id="form-check-companies" class="form-check-label grid-col-check-2">
                                @foreach($companies as $company)
                                    <label class="d-flex flex-row align-items-start check_box">
                                        <input name="companies[]" type="checkbox" value="{{ $company->id }}"><span>{{ $company->title }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb30">
                    <div class="form-group">
                        <h4 class="mb_1">Персоналии
                            <!-- <span class="button button_small_small sel_all">Выбрать все</span> -->
                        </h4>
                        <div class="form-check ">
                            <div id="form-check-personalities" class="form-check-label grid-col-check-6">
                                @foreach($personalities as $personality)
                                    <label class="d-flex flex-row align-items-start check_box">
                                        <input name="personalities[]" type="checkbox" value="{{ $personality->id }}">
                                        <span>{{ $personality->title }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}

            <div class="row box_save_article">
                <button class="button_save butt butt_def pdf-reset">Поиск</button>
            </div>

            <tagsforsearch-component></tagsforsearch-component>

            <div class="row box_save_article">
                <button class="button_save butt butt_def pdf-reset">Поиск</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" charset="utf-8">

        jQuery(document).ready(function () {

            jQuery('.report_type').change(function() {

                var val = $(this).find(':selected').val();
                console.log(val);

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

                jQuery('.calendar_wrap').removeClass('error');

                var data_change = jQuery(this).val();
                var arr = data_change.split('.');
                var d = Number(arr[0]) + 1;
                var m = Number(arr[1]) - 1;
                var y = Number(arr[2]);
                var date = new Date(y, m, d).getTime() / 1000;
                jQuery('[name=start_period]').val(date);
            });

            jQuery('.calendar_end_3').change(function () {

                jQuery('.calendar_wrap').removeClass('error');

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