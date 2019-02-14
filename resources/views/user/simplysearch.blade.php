@extends('layouts.app')

@section('content')
    <h3 class="full_row_center title">Результаты поиска</h3>
    <div class="container search_result_box_items">
        <hr>
        @foreach($results as $article)
            <div class="search_block">
                <a href="/report/{{$article->reports->types->slug}}/article/{{$article->id}}" target="_blank" class="title_link text_decor"> <?php echo $article->title;?></a>

                <label class="pdf-checkbox">
                    <input type="checkbox" value="{{$article->id}}"><span class="pdf"></span>
                </label>
                <p><!--strong>Анонс:</strong-->
                    {{   mb_substr(ltrim(html_entity_decode(strip_tags($article->description))), 0, 400) }}
                </p>
                <p>
                    <strong>Отчет:</strong> Еженедельный дайжест "Еженедельный обзор ВПО и ВТИ" за период от {{date("d.m.Y",$article->reports->date_start)}} по {{date("d.m.Y",$article->reports->date_end)}}
                </p>
                <p><strong>Раздел:</strong>
                    @if(isset($article->subcategory))
                        <span>{{ $article->subcategory->category->title }}</span></p>
                @elseif(isset($article->category))
                    <span>{{ $article->category->title }}</span></p>
                @elseif($article->reports->types->slug == 'plannedexhibition')
                    <span> Планируемые выставки {{ date("Y",$article->reports->date_start) }}</span></p>
                @endif

            </div>


                <!-- {{ $article->report }}
                <p><strong>Раздел:</strong>
                    @if(isset($article->subcategory))
                        <span>{{ $article->subcategory->category->title }}</span></p>
                @elseif(isset($article->category))
                    <span>{{ $article->category->title }}</span></p>
                @elseif(isset($article->plannedexhibitionyear))
                    <span> Планируемые выставки {{ $article->plannedexhibitionyear->year }}</span></p>
                @elseif(isset($article->exhibitionyear))
                    <span> Планируемые выставки {{ $article->exhibitionyear->year }}</span></p>
                    @endif
                    </p> -->
                    <!-- <hr> -->

                    @endforeach
        <div class="pagination">{{ $results->links() }}
        </div>
                    <div class="row box_save_article mt30">
                        <a href="{{ URL::previous() }}" class="button butt_back">Назад</a> <button class="button butt_def show_pdf_search">Показать в PDF</button>
                    </div>
    </div>

@endsection