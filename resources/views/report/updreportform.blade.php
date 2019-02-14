<?php

$d = date("d");
$m = date("m");
$y = date("Y");

?>
@extends('layouts.app')

@section('content')
    <div class="container add_form_step1">

        <div class="row justify-content-center">
            <h3 class="title">Создание отчета</h3>
        </div>

        <form action="/report/{{ $report->types->title }}/updreport/{{ $report->id }}" method="post">
            @csrf
            <div class="row name_report">
                <span>{{ $report->types->description }} за период от&nbsp;</span>
                <input type="text" hidden name="year" value="<?= $y?>">
                <input type="text" hidden name="month" value="<?= $m?>">
                <span class="select_wrap calendar_wrap"  style="display: none;">
		        	<input name="start_period_picker" value="" class="calendar_start_3"/>
		        	<input type="hidden" value="<?php echo $report->date_start; ?>" name="start_period">
	        	</span>
                <span>&nbsp;до&nbsp;</span>
                <span class="select_wrap calendar_wrap" style="display: none;">
		        	<input name="end_period_picker" value="" class="calendar_end_3"/>
		        	<input type="hidden" value="<?php echo $report->date_end; ?>" name="end_period">
		        </span>
                @if ($report->types->slug == 'weekly' || $report->types->slug == 'monthly')

		        <span>&nbsp;номер отчета&nbsp;</span>
                <span>
		        	<input name="number" type="text" value="{{ $report->number }}" placeholder="№"/>
		        </span>
                @elseif ($report->types->slug == 'various')
                    <span>&nbsp;Новое название отчета&nbsp;</span>
                    <span>
		        	<input name="title" value="{{ $report->title }}"/>
		        </span>
                @endif
            </div>
            <div class="row box_save_article mt30">
                <a href="{{ URL::previous() }}" class="button butt_back">Назад</a>
                <button class="butt  butt_def">Сохранить</button>
            </div>
        </form>

    </div>

@endsection

@section('scripts')
    <script type="text/javascript" charset="utf-8">
        jQuery(document).ready(function () {
            jQuery('.calendar_start_3').datepicker({
                keyboardNavigation: true,
                modal: true,
                header: true,
                footer: true,
                uiLibrary: 'bootstrap4',
                locale: 'ru-ru',

                value: '<?php echo date("d.m.Y",$report->date_start); ?>',
                format: 'dd.mm.yyyy',
            });

            jQuery('.calendar_end_3').datepicker({
                keyboardNavigation: true,
                modal: true,
                header: true,
                footer: true,
                uiLibrary: 'bootstrap4',
                locale: 'ru-ru',

                value: '<?php echo date("d.m.Y",$report->date_end); ?>',
                format: 'dd.mm.yyyy',

            });

            jQuery('.calendar_start_3').change(function() {

                jQuery('.calendar_wrap').removeClass('error');

                var data_change = jQuery(this).val();
                var arr = data_change.split('.');
                var d = Number(arr[0]);
                var m = Number(arr[1]) - 1;
                var y = Number(arr[2]);
                var date = new Date(y,m,d).getTime()/1000;
                jQuery('[name=start_period]').val(date);
            })

            jQuery('.calendar_end_3').change(function() {

                jQuery('.calendar_wrap').removeClass('error');

                var data_change = jQuery(this).val();
                var arr = data_change.split('.');
                var d = Number(arr[0]);
                var m = Number(arr[1]) - 1;
                var y = Number(arr[2]);
                var date = new Date(y,m,d).getTime()/1000;
                jQuery('[name=end_period]').val(date);
            })

            jQuery('.calendar_wrap').show(500);
        })
    </script>
@endsection