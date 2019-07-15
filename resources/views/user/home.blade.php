<?php $role = Auth::user()->roles[0]->title;?>
@extends('layouts.app')
@section('content')
    <div class="container materials">
        <div class="materials__list">
            @foreach($total_array as $pare)            
                @foreach($pare as $row)
                    <div class="materials__item">
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
                            <ul class="materials-for__list">
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
                            <a class="button" href="/report/{{$row[1]}}">Все материалы раздела</a>
                        @else
                        <a class="button" href="/report/{{$row[1]}}/add1">Создать отчет</a>
                        <a class="button" href="/report/{{$row[1]}}">Управление отчетами</a>
                        @endif
                    </div>
                @endforeach                   
            @endforeach
        </div>
    </div>
@endsection
