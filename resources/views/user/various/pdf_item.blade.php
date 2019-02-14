@extends('layouts.pdf')

@section('content')


                <h3  style="text-align: center">{{ $report->number }}{{-- от {{ date("d.m.Y",$report->start_date)}} до {{ date("d.m.Y",$report->end_date)}}--}}</h3>
                @foreach($report->articles as $article)
                {{--@if($article->subcategory)--}}
                    {{--<h3  style="text-align: center">{{ $articles->subcategory->title }}</h3>--}}
                    {{--<h3  style="text-align: center">{{ $articles->subcategory->category->title }}</h3>--}}
                {{--@else--}}
                    {{--<h3  style="text-align: center">{{ $articles->category->title }}</h3>--}}
                {{--@endif--}}
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
                
                <div class="more"></div>
            @endforeach
            






@endsection