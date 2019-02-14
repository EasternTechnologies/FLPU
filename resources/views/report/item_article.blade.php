<?php $role = Auth::user()->roles[0]->title;?>
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex align-items-start article_box">
            <div class="col_270 pr16 border_r1 fll">
                <p><strong>Тип отчета: </strong><span class="italic_14">{{ $article->reports->types->title }}</span></p>
                <p><strong>Период: </strong>
                    <span class="italic_14">c {{date("d.m.Y",$article->date_start)}} по {{date("d.m.Y",$article->date_end)}}</span>
               </p>

                <p><strong>Страны: </strong><span class="italic_14">@if(isset($article->countries))
                        @foreach($article->countries as $country )
                            {{ $country->title }},
                        @endforeach
                    @endif
                    </span>
                </p>

                <p><strong>Тип ВВТ: </strong> <span class="italic_14"> @if(isset($article->vvttypes))
                            @foreach($article->vvttypes as $type )
                                {{ $type->title }},
                            @endforeach
                        @endif
                   </span>
                </p>

                <p><strong>Компании: </strong> <span class="italic_14"> @if(isset($article->companies))
                            @foreach($article->companies as $item )
                                {{ $item->title }},
                            @endforeach
                        @endif
                    </span>
                </p>

                <p><strong>Персоналии: </strong> <span class="italic_14"> @if(isset($article->personalities))
                            @foreach($article->personalities as $item )
                                {{ $item->title }},
                            @endforeach
                        @endif
                    </span>
                </p>

            </div>
            <div class="col_calc_270 pl30 posr">
                @if( $role != 'user' && $role !='employee' )
                <span class="pos_tr_article_out status st-{{$article->status}}">
                	@if($article->status == 0)
                		<span class="status st_inherit">Статус:</span> Не утвержден
                	@elseif($article->status == 1)
                		<span class="status st_inherit">Статус:</span> Ожидает утверждения
                	@else
                		<span class="status st_inherit">Статус:</span> Утвержден
                	@endif
                </span>
                @endif
                @if(isset($article->title))
                    @if($article->reports->types->slug == 'plannedexhibition')
                            <div class="article_title">
			                <?php echo strip_tags
			                ($article->title, "<p><a><h1><h2><h3><h4><h5><h6><b>");
			                ?>
                            </div>
                        @else
                    <h3 class="title padlr30">{{ $article->title }}</h3>
                     @endif
                @endif


                @if($article->reports->types->slug == 'plannedexhibition')
                        <p><strong>Отчет: </strong> <span class="italic_14"><a class="text_decor" href="/report/{{ $article->reports->types->slug }}/show/{{ $article->report_id }}">{{ $article->reports->types->title }}</a></span></p>
                    <p class="mb10 w100 fll"><strong>Место:&nbsp</strong>
                        <span class="italic_14">{!! strip_tags($article->place) !!}</span>
                    </p>
                    <p class="mb10 w100 fll"><strong>Тематика:&nbsp</strong>
                        <span class="italic_14">{!! strip_tags($article->description) !!}</span>
                    </p>
                        <div class="mb10">
                            @if(count($article->images) != 0)
                                <strong>Скачать материалы:&nbsp</strong>
                                @foreach($article->images as $image)
                                    <a target="_blank" href="/images/{{$image->image}}" class="file_img file_img--article exhibition"></a>
                                @endforeach
                            @endif
                        </div>

                @else
                
                <a target="_blank" href="/report/{{ $article->reports->types->slug }}/pdf_article/{{ $article->id }}" class="pdf pos_tr_article_out"></a>
                <p><strong>Отчет: </strong> <span class="italic_14"><a class="text_decor" href="/report/{{ $article->reports->types->slug }}/show/{{ $article->report_id }}">{{ $article->reports->types->title }}</a></span></p>
                    @if(isset($article->category))
                    <p class="mb30"><strong>Раздел: </strong>
                    <span class="italic_14">{{$article->category->title }} </span></p>
                    @endif
                <div class="content_text">{!!   $article->description !!}</div>
                <div class="gallery_img_content mb30">
                    @if(isset($article->images))
                        @foreach($article->images as $image)
                            <a data-fancybox="gallery" href="/images/{{$image->image}}"><img height="200" src="/images/{{$image->image}}"></a>
                        @endforeach
                    @endif
                </div>
				@endif
				
            </div>
        </div>
        
        <div class="row box_save_article mt30">
        	
        	@if(Request::url() == URL::previous())
			    <a href="/report/{{ $article->reports->types->slug }}/show/{{ $article->report_id}}" class="button butt_back">Вернуться к отчету</a>
			@else
				<a onclick="(function(){window.history.back()})()" class="button butt_back back-button">Назад</a>
			@endif

                @if( $role != 'user' && $role !='employee' )
                    @if($article->status == 0 || $article->status == 1)
                        <form onsubmit="approve(this,'{{$article->reports->types->title}}');return false;" action="/report/{{ $article->reports->types->slug }}/article_publish/{{ $article->id }}" method="post">
                            {{ method_field('put') }}
                            @csrf
                            <button class="butt butt_def">Утвердить</button>
                        </form>
                    @endif

                    <a class="text_decor" href="/report/{{ $article->reports->types->slug }}/upd/{{ $article->id }}">
                        <button class="butt butt_def">Редактировать</button>
                    </a>
                @endif
        </div>
        
    </div>
@endsection