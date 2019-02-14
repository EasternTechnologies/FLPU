<?php
$d = date("d");
$m = date("m");
$y = date("Y");

$count = cal_days_in_month(CAL_GREGORIAN, $m, $y);

switch ( $m ) {
    case 1:
        $m_name = 'января';
        break;
    case 2:
        $m_name = 'февраля';
        break;
    case 3:
        $m_name = 'марта';
        break;
    case 4:
        $m_name = 'апреля';
        break;
    case 5:
        $m_name = 'мая';
        break;
    case 6:
        $m_name = 'июня';
        break;
    case 7:
        $m_name = 'июля';
        break;
    case 8:
        $m_name = 'августа';
        break;
    case 9:
        $m_name = 'сентября';
        break;
    case 10:
        $m_name = 'октября';
        break;
    case 11:
        $m_name = 'ноября';
        break;
    case 12:
        $m_name = 'декабря';
        break;
    default:
        $m      = "Ошибка даты";
        $m_name = "";
        break;

}
?>
@extends('layouts.app')

@section('content')

    <div class="container page_create_post">
        <form id="form" action="/report/{{ $report->types->slug }}/addcategory" method="post" >
            <div class="row justify-content-center">
                <h3>Добавление региона</h3>
                @csrf
                <div class="col-md-12 box_info">

                    <input type="text" hidden name="report" value="{{ $report->id }}">
                    <input type="text" hidden name="year" value="<?= $y?>">
                    <input type="text" hidden name="month" value="<?= $m?>">
                </div>

            </div>
            <div class="row">
	            <p class="d-flex justify-content-start">
		            <span class="name">Отчет: </span><span class="text">{{ $report->types->title }} <!--№ {{ $report->number }}--> за период от {{ date("d.m.Y",$report->date_start)}} до {{ date("d.m.Y",$report->date_end)}}</span>
		        </p>
	        </div>
            <div class="row d-flex flex-column mb-3">
            	<h4>Введите назване региона:</h4>
                <input type="text" class="title_post" name="title" placeholder=""/>
            </div>
            <div class="row d-flex flex-column mb-3">
            	<h4>Военно-политическая обстановка в регионе:</h4>
                <textarea name="editor1"></textarea>
            </div>
            
            </br>
            </br>
            </br>
            <div class="row box_save_article mt30">
              <a href="{{ URL::previous() }}" class="button butt_back">Назад</a>
              <button class="button_save butt butt_def">Сохранить</button>
              <button onclick="jQuery('#form').attr('action','/report/{{ $report->types->slug }}/addcategory/1'); jQuery('#form').submit(); return false;" class="button_save butt butt_def">Сохранить и отправить на утверждение</button>
            </div>
        </form>
    </div>
@endsection