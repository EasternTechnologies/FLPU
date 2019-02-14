@extends('layouts.pdf')
@section('content')
    @if($report_slug=='weekly')
        <h1 style="text-align: center">Бюллетень военно-политической и военно-технической информации № {{ $report->number }} за период с {{date("d.m.y",$report->date_start)}} по {{date("d.m.y",$report->date_end)}}</h1>
    @elseif($report_slug=='monthly')
        <h3 class="title" style="text-align: center">{{ $report->types->title}} № {{$report->number}} ({{ Helper::getMonthText(date('m', $report->date_start)) }} {{ date('Y', $report->date_start) }})</h3>
    @elseif($report_slug=='countrycatalog')
        <h1 style="text-align: center">Ежегодный справочник {{ $report->types->title}} за {{ date("Y",$report->date_start) }} год. </h1>
        @elseif($report_slug=='yearly')
        <h3 class="title" style="text-align: center">{{ $report->types->title }} за {{ date('Y', $report->date_start) }} год</h3>
        @elseif($report_slug=='various')
        <h3 class="title" style="text-align: center">{{$report->types->title}}</h3>
        @elseif($report_slug=='search')
        <h3 class="title" style="text-align: center">Результаты поиска</h3>
    @endif
    @if(!empty($items))
        @foreach($items as  $category =>$sub_cats)
            @if($category!==0)
            <h3 class="title title_cat" style="text-align: center">{{ $category}}</h3>
            @endif
            @if($report_slug == 'countrycatalog' && !empty($descriptions[$loop->index]))
            <h3 style="text-align: center">Военно-политическая обстановка в регионе</h3>
            <p>{!! $descriptions[$loop->index] !!}</p>
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
                        <h3 class="title" style="text-align: center">{{ $post->title }}</h3>
                    @endif
                    <p>{!! $post->description !!}</p>
                    <p class="pdf_gallery">
                        @if(isset($post->images))
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