<?php $role = Auth::user()->roles[0]->title;

$d = date("d");
$m = date("m");
$y = date("Y");
?>

@extends('layouts.app')

@section('content')

    <div class="container add_form_step2 posr">

        <input type="hidden" name="random_key" value="{{empty($q)?'000':$q}}">

        @if($q)
            <h3 class="title">Выбранные документы</h3>

            <div class="row fixed_bottom box_save_article mt30">
                <button id="drop_cookie" class="button butt_def show_pdf_search">Показать в PDF</button>
            </div>
        @endif

        <h3 class="title">
            <span>
                @if($report->types->slug=='weekly')
                            {{ $report->types->title }} № {{ $report->number }} за период от {{date("d.m.Y",$report->date_start)}} до {{date("d.m.Y",$report->date_end)}}
                @elseif($report->types->slug=='monthly')
                        {{$report->types->title}} № {{ $report->number }} ({{ Helper::getMonthText(date('m', $report->date_start)) }} {{ date('Y', $report->date_start) }})
                @elseif($report->types->slug=='countrycatalog')
                        Ежегодный справочник "{{ $report->types->title }}" за {{date("Y",$report->date_start)}} год
                    @elseif($report->types->slug=='yearly')
                    Ежегодный справочник "{{$report->types->title}}" <!--№ {{ $report->number }}--> за {{date("Y",$report->date_start)}} год
                    @elseif($report->types->slug=='various')
                    {{$report->title}}
                @endif
                @if(!$q)
                    <a target="_blank" href="/pdf_item/{{ $report->id }}" class="pdf"></a>
                @endif
           </span>
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
            @if(!empty($items))

                <?php $n1 = 0; $n2 = 0; $n3 = 0; ?>
                @foreach($items as  $cat => $subcats)
                <?php $n1++; ?>
                            @if($cat!==0)
                                <div class="row">
                                    @if($report->types->slug=='monthly')
                                    <p class="title title_cat">
                                        {{ $n1 }}. {{ $cat }}
                                    @else
                                    <p class="title title_cat pdf_box">
                                        <span>{{ $cat }}</span>
                                    @endif
                                    @if(!$q)
                                    <span >
                                        <a target="_blank" href="/pdf_category/{{$report->id}}/{{ $categories->where('title',$cat)->first()->id }}" class="pdf"></a>
                                    </span>
                                    @endif
                                    </p>
                                </div>
                            @endif

                            @if($report->types->slug=='weekly' || $report->types->slug=='countrycatalog' || $report->types->slug=='various')
                                @include('report.layouts.week_country_show')
                            @elseif($report->types->slug=='yearly')
                            @include('report.layouts.yearly_show')
                            @else
                            @include('report.layouts.'.$report->types->slug.'_show')
                            @endif
                @endforeach
            @endif
            <div class="row fixed_bottom box_save_article mt30">
                @if(Request::url() == URL::previous())
                    <a href="/{{ $report->types->slug }}/" class="button butt_back">Все отчеты</a>
                @else
                    <a href="{{ URL::previous() }}" class="button butt_back">Назад</a>
                @endif
                @if( $role != 'user' && $role !='employee' && empty($q))
                    <a class="button butt_def" href="/report/{{$report->types->slug}}/add2/{{ $report->id }}">Редактировать</a>
                @endif
                @if(!$q)
                    <button id="drop_cookie" class="button butt_def show_pdf_search_choose">Показать выборку</button>
                    <button id="drop_cookie" class="button butt_def show_pdf_search">Выборка в PDF</button>
                @else
                    <button id="drop_cookie" class="button butt_def show_pdf_search">Показать в PDF</button>
                @endif
            </div>
    </div>
@endsection


@if($report->types->slug=='countrycatalog')
    @section('scripts')
        <script type="text/javascript" charset="utf-8">
            jQuery(document).ready(function() {
                jQuery('.vpor_title').on('click',function() {

                    if(jQuery(this).parent('.vpor_box').hasClass('active')) {
                        jQuery(this).parent('.vpor_box').removeClass('active');
                        jQuery('.vpor_box .vpor_desc').fadeOut(500);
                    } else {
                        jQuery(this).parent('.vpor_box').addClass('active');
                        jQuery('.vpor_box.active .vpor_desc').fadeIn(500);
                    }

                })
            })
        </script>
    @endsection
@endif


