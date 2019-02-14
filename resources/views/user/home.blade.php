<?php $role = Auth::user()->roles[0]->title;?>
@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                @foreach($total_array as $pare)
                    <div class="row co_l2">
                        @foreach($pare as $row)
                            <div class="col-md-6 item_card">
                                <h5>{{$row[0]}}</h5>
                                <p>
                                    @if($row[1]=='weekly')
                                        Еженедельный отчет
                                        @elseif($row[1]=='monthly')
                                        Ежемесячный бюллетень
                                        @elseif($row[1]=='countrycatalog')
                                        Ежегодный обзор
                                        @elseif($row[1]=='yearly')
                                        Ежегодный справочник
                                        @elseif($row[1]=='plannedexhibition')
                                        Ежегодный календарь
                                    @endif
                                </p>
                                @if( $role == 'user' || $role =='employee' )
                                    <ul class="ul_1_user">
                                        @foreach($row[2] as $report)

                                            @if($row[1]=='weekly')
                                                <li><a href="/report/weekly/show/{{ $report->id }}">Отчет с {{ date("d",$report->date_start)  }}.{{ date("m",$report->date_start) }}.{{date("y",$report->date_start)}} по {{ date("d",$report->date_end) }}.{{ date("m",$report->date_end)}}.{{date("y",$report->date_end)}}</a></li>
                                            @elseif($row[1]=='monthly')
                                                <li><a href="/report/monthly/show/{{ $report->id }}">Отчет за {{ Helper::getMonthText(date('m', $report->date_start)) }} {{ date('Y', $report->date_start) }}</a></li>
                                            @elseif($row[1]=='countrycatalog' || $row[1]=='yearly' || $row[1]=='plannedexhibition')
                                                <li><a href="/report/{{$row[1]}}/show/{{ $report->id }}">Отчет за {{date("Y",$report->date_start)}} год.</a></li>
                                            @elseif($row[1]=='various')
                                                <li><a href="/various/show/{{ $report->id }}">{{ $report->title }}</a></li>

                                            @endif
                                        @endforeach
                                    </ul>
                                    <a href="/report/{{$row[1]}}"><button class="butt no_wrap">Все материалы раздела</button></a>
                                @else
                                <a href="/report/{{$row[1]}}/add1"><button class="butt margin_b">Создать отчет</button></a>
                                <a href="/report/{{$row[1]}}"><button class="butt">Управление отчетами</button></a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
