@extends('layouts.pdf')

@section('content')

        @if($report->articles->count() != 0)
            @foreach($report->articles as $article)
                <h3 style="text-align: center">{{ $reporttitle }} от {{ date("d.m.Y",$article->start_period)}} до {{ date("d.m.Y",$article->end_period)}}</h3>

                @if($article->subcategory)
                    <h3 style="text-align: center">Подраздел: {{ $article->subcategory->title }}</h3>
                    <h3 style="text-align: center">Раздел: {{ $article->subcategory->category->title }}</h3>
                @else
                    <h3 style="text-align: center">{{ $article->category->title }}</h3>
                @endif
                {{--<p>Страны: @if(isset($article->countries))--}}
                        {{--@foreach($article->countries as $country )--}}
                            {{--{{ $country->title }},--}}
                        {{--@endforeach--}}
                    {{--@endif--}}
                {{--</p>--}}

                {{--<p>Тип ВВТ: @if(isset($article->vvttypes))--}}
                        {{--@foreach($article->vvttypes as $type )--}}
                            {{--{{ $type->title }},--}}
                        {{--@endforeach--}}
                    {{--@endif--}}
                {{--</p>--}}
                {{--<p>Компании: @if(isset($article->companies))--}}
                        {{--@foreach($article->companies as $item )--}}
                            {{--{{ $item->title }},--}}
                        {{--@endforeach--}}
                    {{--@endif--}}
                {{--</p>--}}

                {{--<p>Персоналии: @if(isset($article->personalities))--}}
                        {{--@foreach($article->personalities as $item )--}}
                            {{--{{ $item->title }},--}}
                        {{--@endforeach--}}
                    {{--@endif</p>--}}

                @if(isset($article->title))
                    <h3 class="title" style="text-align: center">{{ $article->title }}</h3>
                @endif
                <p>{!!   $article->body !!}</p>
                <p class="pdf_gallery">
                    @if(isset($article->images))
                        @foreach($article->images as $image)
                            <img style="margin-bottom: 30px; max-height: 940px" src="images/{{$image->image}}">
                        @endforeach
                    @endif
                </p>
            @endforeach
            <div style="page-break-after: always;"></div>

        @endif
        @if($report->subcategories->count()!=0)
            @foreach($report->subcategories as $subcategory)

                @foreach($subcategory->articles as $article)
                    <p>Тип отчета: {{ $reporttitle }}</p>
                    <p>Период: от {{ date("d.m.Y",$article->start_period)}} до {{ date("d.m.Y",$article->end_period)}}</p>
                    @if($article->subcategory)
                        <p>Подраздел: {{ $article->subcategory->title }}</p>
                        <p>Раздел: {{ $article->subcategory->category->title }}</p>
                    @else
                        <p>Раздел: {{ $article->category->title }}</p>
                    @endif
                    <p>Страны: @if(isset($article->countries))
                            @foreach($article->countries as $country )
                                {{ $country->title }},
                            @endforeach
                        @endif
                    </p>

                    <p>Тип ВВТ: @if(isset($article->vvttypes))
                            @foreach($article->vvttypes as $type )
                                {{ $type->title }},
                            @endforeach
                        @endif
                    </p>
                    <p>Компании: @if(isset($article->companies))
                            @foreach($article->companies as $item )
                                {{ $item->title }},
                            @endforeach
                        @endif
                    </p>

                    <p>Персоналии: @if(isset($article->personalities))
                            @foreach($article->personalities as $item )
                                {{ $item->title }},
                            @endforeach
                        @endif</p>

                    @if(isset($article->title))
                        <h3 style="text-align: center" class="title">{{ $article->title }}</h3>
                    @endif
                    <p>{!!   $article->body !!}</p>
                    <p class="pdf_gallery">
                        @if(isset($article->images))
                            @foreach($article->images as $image)
                                <img style="margin-bottom: 30px; max-height: 940px" src="images/{{$image->image}}">
                            @endforeach
                        @endif
                    </p>
                @endforeach
                <div style="page-break-after: always;"></div>

            @endforeach
        @endif

@endsection