<?php $role = Auth::user()->roles[0]->title;?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="row co_l2">
                    @foreach($report_types as $type)
                    <div class="col-md-6 item_card">
                        <h5>{{ $type->title }}</h5>
                        <p>Еженедельный отчет</p>
                        @if( $role == 'user' )
                        <ul class="ul_1_user">
                            @foreach($reports as $report)
                                <li><a href="/report/{{ $report->id }}">Отчет ( {{ date("d, m",$report['date_start'])  }} - {{ date("d, m, y",$report['date_end']) }})</a></li>
                            @endforeach
                        </ul>
                        <a href="/show/{{ $type->id }}"><button class="butt no_wrap">Все материалы раздела</button></a>
                        @else
                            <a href="/report/add1"><button class="butt margin_b">Создать отчет</button></a>
                            <a href="/show/{{ $type->id }}"><button class="butt">Управление отчетами</button></a>
                        @endif
                    </div>
                    @endforeach

            </div>
        </div>
    </div>
@endsection
