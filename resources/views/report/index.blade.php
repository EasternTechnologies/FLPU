<?php $role = Auth::user()->roles[0]->title;?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h3> Отчеты типа "{{ $report_type->title }}"</h3>
        </div>
        <div class="row justify-content-center">
            @if( $role != 'user' && $role != 'employee' )
            <a href="/report/{{$report_type->slug}}/add1"><button class="butt margin_b butt_def">Создать новый отчет</button></a>
            @endif
            <div class="col-md-12 out_table analyst_report">
                <table style="border: 1px solid">
                    <thead>
                    <tr style="border: 1px solid">
                        <td style="width: 3%;">
                            № n/n
                        </td>
                        <td style="width: 25%;">Название</td>
                        @if( $role != 'user' && $role !='employee' )
                            <td style="width: 15%;">Статус</td>
                        @endif
                        <td style="width: 17%;">Просмотр</td>
                        @if( $role != 'user' && $role !='employee' )
                            <td style="width: 15%;">Редактирование</td>
                            <td style="width: 15%;">Удаление</td>
                            <td style="width: 10%;">Публикация</td>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                @foreach($reports as $item)
                    <tr>
                        <td style="border: 1px solid">
                            {{ $page?++$loop->index + ($page-1)*$count:++$loop->index}}
                        </td>
                        <td class="table_name">
                            <a href="/report/{{$report_type->slug}}/show/{{ $item['id']}}">


                                @if($report_type->slug == 'weekly' || $report_type->slug == 'monthly' )
                                    {{$report_type->title}} № {{ $item['number'] }} за период от {{ date("d.m",$item['date_start'])  }} до {{ date("d.m.Y",$item['date_end']) }}
                                @elseif($report_type->slug == 'various')
                                    {{$item->title}}
                                @else
                                    {{$report_type->title}}   за {{ date("Y",$item['date_start']) }} год.
                                @endif


                            </a>
                            @if($report_type->slug == 'plannedexhibition') <a target="_blank" href="/pdf_item/{{$item->id}}" class="pdf exhibition"></a>@endif

                            @if( $role != 'user' && $role !='employee' )
                            <a class="link-edit" href="/report/{{$report_type->slug}}/updreport/{{ $item->id }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                            @endif
                        </td>
                        @if( $role != 'user' && $role !='employee' )
                            <td>
                                @if($item['status'] == 2)
                                    Опубликован
                                @elseif($item['status'] == -1)
                                    Нет материалов
                                @else
                                    Не опубликован
                                @endif
                            </td>
                        @endif
                        <td>
                            <a class="text_decor" href="/report/{{$report_type->slug}}/show/{{ $item['id']}}">Просмотреть</a>
                        </td>
                        @if( $role != 'user' && $role !='employee' )
                        <td>
                            <a class="text_decor" href="/report/{{$report_type->slug}}/add2/{{ $item['id'] }}">Редактировать</a>
                        </td>
                        <td>
                        	
                        	<form onsubmit="deleteName(this,'{{ $report_type->title }} ( {{ date("d.m",$item['date_start'])  }} - {{ date("d.m.Y",$item['date_end']) }}');return false;" action="/report/{{$report_type->slug}}/deletereport/{{ $item['id'] }}" method="post">
                                {{ method_field('delete') }}
                                @csrf
                                <button class="text_decor link">Удалить</button>
                            </form>
                            
                        </td>
                        <td>
                        	@if($item['status'] == 1)
                            	
                            	<form action="/report/{{$report_type->slug}}/publish/{{ $item['id'] }}" method="post">
	                                {{ method_field('put') }}
	                                @csrf
	                                <button class="text_decor link">Опубликовать</button>
	                            </form>

	                        @elseif($item['status'] == 2)
	                        	    Опубликован
                            @elseif($item['status'] == -1)
                            	Нет материалов
                            @else
                                <a class="text_decor red" href="/report/{{$report_type->slug}}/add2/{{ $item['id'] }}">Утвердите материалы</a>
                            @endif 
                        </td>
                        @endif
                    </tr>

                @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="pagination">{{$reports->links()}}</div>
    
    <div class="row box_save_article mt30">
        <a href="/report"><button class="butt butt_mt_2 butt_def">Вернуться на главную</button></a>
    </div>
    
@endsection
