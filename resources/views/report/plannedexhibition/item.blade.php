<?php
$role = Auth::user()->roles[0]->title;
?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center posr">

            <h3>
                Перечень международных выставок вооружений и военной техники, планируемых к проведению в иностранных государствах на {{ date("Y",$report->date_start) }} год

                @if(Auth::user() && Auth::user()->isemployee())
                    <span>
    	                <a target="_blank" href="/pdf_item/{{ $report->id }}" class="pdf"></a>
    	            </span>
                @endif

            </h3>
            @if( $role != 'user' && $role !='employee' )
                <span class="pos_tr_article_out status st-{{10 + $report->status}}">
            	@if($report->status == 2)
                        <span class="status st_inherit">Статус:</span> Опубликован
                    @elseif($report->status == 1)
                        <span class="status st_inherit">Статус:</span> Все материалы утверждены
                    @elseif($report->status == 0)
                        <span class="status st_inherit">Статус:</span> Не опубликован
                    @endif
            </span>
            @endif
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 out_table analyst_report">
                <table style="border: 1px solid">
                    <thead>
                    <tr style="border: 1px solid">
                        <td style="width: 3%;">
                            № n/n
                        </td>
                        <td style="width: 30%;">Название выставки</td>
                        <td style="width: 10%;">Дата</td>
                        <td style="width: 20%;">Место</td>
                        <td style="width: 27%;">Тематика выставки</td>
                        <td style="width: 10%;">Скачать материалы к выставке</td>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($items as $item)
                        <tr style="border: 1px solid">
                            <td>
                                {{ $page?++$loop->index+($page-1)*10:++$loop->index }}
                            </td>
                            <td style="color: black !important">
                                <a href="/report/plannedexhibition/article/{{ $item['id']}}">
                                    <?php echo
                                    strip_tags ($item['title'], "<p><a><h1><h2><h3><h4><h5><h6><b>");
                                    ?>
                                </a>

                                {{--@if(Auth::user() && Auth::user()->isemployee())--}}
                                {{--<a target="_blank"  href="/pla  nnedexhibition/pdf_article/{{ $item['id']}}" class="pdf"></a>--}}
                                {{--@endif--}}

                            </td>
                            <td style="border: 1px solid;" class="center">
                                {{ date("d",$item['date_start']) }} - {{ date("d",$item['date_end']) }}  {{ Helper::getMonthText(date("m",$item['date_end'])) }}
                            </td>
                            <td style="border: 1px solid;">
                                <?php echo strip_tags ($item['place'], "<p><a><h1><h2><h3><h4><h5><h6><b>")?>
                            </td>
                            <td>
                                <?php echo strip_tags ($item->description, "<p><a><h1><h2><h3><h4><h5><h6><b>")?>
                            </td>
                            <td style="border: 1px solid; text-align:center;">
                                <div class="file_wrap">
                                    @foreach( $item['images'] as $image )
                                        <a target="_blank" href="/images/{{ $image->image }}" class="file_img exhibition"></a>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="pagination">{{$items}}</div>

    @if( $role != 'user' && $role !='employee' )
        <div class="row box_save_article mt30">

            @if(Request::url() == URL::previous())
                <a href="/analyst/plannedexhibition/" class="button butt_back">Все отчеты</a>
            @else
                <a href="{{ URL::previous() }}" class="button butt_back">Назад</a>
            @endif

            <a class="button butt_def" href="/report/plannedexhibition/add2/{{ $report->id }}">Редактировать</a>

        </div>
    @else

        <div class="row justify-content-center">
            <a href="/"><button class="butt butt_mt_2 butt_def">Вернуться на главную</button></a>
        </div>
    @endif
@endsection