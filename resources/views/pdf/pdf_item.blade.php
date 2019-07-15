@extends('layouts.pdf')
@section('content')
    <h3 class="title" style="text-align: center">
        @if($report_slug=='weekly')
            Бюллетень военно-политической и военно-технической информации № {{ $report->number }} за период с {{date("d.m.y",$report->date_start)}} по {{date("d.m.y",$report->date_end)}}
        @elseif($report_slug=='monthly')
            {{ $report->types->title}} № {{$report->number}} ({{ Helper::getMonthText(date('m', $report->date_start)) }} {{ date('Y', $report->date_start) }})
        @elseif($report_slug=='countrycatalog')
            Ежегодный справочник {{ $report->types->title}} за {{ date("Y",$report->date_start) }} год.
            @elseif($report_slug=='yearly')
            {{ $report->types->title }} за {{ date('Y', $report->date_start) }} год
            @elseif($report_slug=='various')
            {{$report->title}}
            @elseif($report_slug=='search')
            Результаты поиска
        @endif
    </h3>
    @if(!empty($items))
        @foreach($items as  $category =>$sub_cats)
            @if($category!==0)
            <h3 class="title title_cat" style="text-align: center">{{ $category}}</h3>
            @endif
            @if($report_slug == 'countrycatalog' && !empty($descriptions[$loop->index]))
            <h3 style="text-align: center">
                Военно-политическая обстановка в регионе
            </h3>
            <div style="font-size: 15px; text-align: justify">
                {!! preg_replace('~style="[^"]*"~i', '',  $descriptions[$loop->index]) !!}
            </div>
            @endif
            @foreach($sub_cats as  $sub_cat =>$posts)
            @if($sub_cat!==0)
                <h3 class="title padl_sub1 title_sub_cat" style="text-align: center">
                    <strong>{{ $sub_cat  }}</strong>
                </h3>
            @endif
            @foreach($posts as $post)
                @if(isset($post))
                    @if(isset($post->title))
                        <h3 class="title" style="text-align: center">{!! $post->title !!}  </h3>
                    @endif
                    @if(!empty($post->place))
                        <div style="font-size: 15px; text-align: justify">
                            Место:{!! preg_replace('~style="[^"]*"~i', '',  $post->place) !!}
                        </div>
                        @endif
                    <div style="font-size: 15px; text-align: justify">
                        @if(!empty($post->place))
                            Тематика:
                        @endif
                        {!! preg_replace('~style="[^"]*"~i', '',  $post->description) !!}
                    </div>
                    <p class="pdf_gallery">
                        @if(isset($post->images) && empty($post->place))
                            @foreach($post->images as $image)
                                <img style="margin-bottom: 30px; max-height: 940px" src="images/{{$image->image}}">
                            @endforeach
                        @endif
                    </p>
                @endif
                <div class="more"></div>
            @endforeach
            @endforeach
        @endforeach
    @endif
@endsection
