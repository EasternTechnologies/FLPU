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
        
        <form action="/report/{{ $report_type->slug }}/add1" method="post">
            @csrf
            <div class="row name_report">
                <span>{{ $report_type->description }} за период от&nbsp;</span>
                <input type="text" hidden name="year" value="<?= $y?>">
                <input type="text" hidden name="month" value="<?= $m?>">
                <span class="select_wrap calendar_wrap"  style="display: none;">
		        	<input name="start_period_picker" value="" class="calendar_start"/>
		        	<input type="hidden" value="<?php echo mktime(0,0,0,$m,$d,$y); ?>" name="date_start">
	        	</span>
                <span>&nbsp;до&nbsp;</span>
                <span class="select_wrap calendar_wrap" style="display: none;">
		        	<input name="end_period_picker" value="" class="calendar_end"/>
		        	<input type="hidden" value="<?php echo mktime(0,0,0,$m,$d,$y); ?>" name="date_end">
		        </span>
                @if ($report_type->slug == 'weekly' || $report_type->slug == 'monthly')
                <span>&nbsp;номер отчета&nbsp;</span>
                <span>
		        	<input name="number" value="" placeholder="№"/>
		        </span>
                @endif
                @if ($report_type->slug == 'various')
                    <span>&nbsp;Название отчета&nbsp;</span>
                    <span>
		        	<input name="title" value=""/>
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
		jQuery('.calendar_wrap').show(500);	
	});
</script>
@endsection