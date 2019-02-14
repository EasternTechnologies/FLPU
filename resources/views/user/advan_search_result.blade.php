@extends('layouts.app')

@section('content')
    <div class="pagination">{{ $articles->links() }}</div>
    <h3 class="full_row_center title">Результаты поиска</h3>

    <div class="container border search_result_box">
        <p>
            <strong>Тип отчета:</strong>
            @if(is_object($report_type))
                {{$report_type->title}}
            @else
                Все отчеты
            @endif
        </p>
        <p>
            <strong>Период: </strong>
            <span class="italic_14">c {{date("d.m.Y",$start_period)}} по {{date("d.m.Y",$end_period)}}</span>
        </p>
        @if($countries->count()!=0)
            <p>
                <strong>Страны:</strong>
                @foreach($countries as $key => $country )
                    <?php if( $key == 0) {
                        echo $country->title;
                    } else{
                        echo ', '.$country->title;
                    }?>
                @endforeach
            </p>
        @endif
        @if($vvt_types->count()!=0)
            <p>Тип ВВТ:
                @foreach($vvt_types as $key => $vvt_type )
                    <?php if( $key == 0) {
                        echo $vvt_type->title;
                    } else{
                        echo ', '.$vvt_type->title;
                    }?>
                @endforeach
            </p>
        @endif
        @if($companies->count()!=0)
            <p>Компании: @foreach($companies as $key => $company )
                    <?php if( $key == 0) {
                        echo $company->title;
                    } else{
                        echo ', '.$company->title;
                    }?>
                @endforeach
            </p>
        @endif
        @if($personalities->count()!=0)
            <p>Персоналии: @foreach($personalities as $key => $personality )
                   <?php if( $key == 0) {
                        echo $personality->title;
                   } else{
                        echo ', '.$personality->title;
                   }?>
                @endforeach
            </p>
        @endif
    </div>

    <div class="container search_result_box_items">
        <hr>
        @if(isset($articles))
            @foreach($articles as $item)
                <div class="search_block">
                    <a href="/report/{{ $item->reports->types->slug }}/article/{{$item->id}}" target="_blank" class="title_link text_decor">

                        <?php echo
	                    strip_tags ($item->title, "<p><a><h1><h2><h3><h4><h5><h6>");
	                    ?></a>
                    <label class="pdf-checkbox">
                        <input type="checkbox" value="{{$item->id}}"><span class="pdf"></span>
                    </label>
                    <p><!--strong>Анонс:</strong-->
                     {{ mb_substr(ltrim(html_entity_decode(strip_tags($item->description))),0,200) }}

                    </p>
                    <p>
                        <strong>Отчет: </strong> <a class="report" href="/report/{{ $item->reports->types->slug }}/show/{{$item->report_id}}"
                                                    target="_blank">Еженедельный дайжест "Еженедельный обзор ВПО и ВТИ" за период от {{date("d.m.Y",$item->reports->date_start)}} по {{date("d.m.Y",$item->reports->date_end)}}</a>
                    </p>
                    @if($item->category_id != 0)<p><strong>Раздел: </strong> {{\App\Category::find($item->category_id)->title}}</p>@endif
                    @if(isset($item->subcategory_id))
                        <p><strong>Позраздел:</strong> {{\App\Subcategory::find($item->subcategory_id)->title}}</p>
                    @endif
                </div>
            @endforeach
                <div class="pagination">{{ $articles->links() }}</div>
        @endif





        @if(isset($articles['plannedexhibition']))
            <div class="exhibitions">Выставки</div>
            @foreach($articles['plannedexhibition'] as $item)
                <div class="search_block search_block--exhibition">
                    <div class="search-block__item search-block__item-title">
                        <a href="/plannedexhibition/article/{{$item->id}}" target="_blank" class="title_link
                        text_decor">
                            <?php echo
                                strip_tags ($item->title, "<p><a><h1><h2><h3><h4><h5><h6>");
                            ?>
                        </a>
                    </div>

                    <!--strong>Анонс:</strong-->
                    <div class="search-block__item search-block__item-content">
                        <div class="search-block__item-cont">
                           <?php echo
                               strip_tags ($item->theme, "<p><a><h1><h2><h3><h4><h5><h6>");
                           ?>
                        </div>

                       <div class="search-block__item-descr">
                           <p>
                               <strong>Отчет: </strong><a class="report"
                                                          href="/plannedexhibition/show/{{$item->plannedexhibition_id}}">Выставка за период от {{date("d.m.Y",$item->plannedexhibitionyear->start_date)}} по {{date("d.m.Y",$item->plannedexhibitionyear->end_date)}}</a>
                           </p>

                           <p><strong>Раздел: </strong> Выставки за {{ $item->plannedexhibitionyear->year }} год</p>
                       </div>
                    </div>

                </div>
            @endforeach

        @endif




        <div class="row box_save_article mt30">
            <a href="{{ URL::previous() }}" class="button butt_back">Назад</a> <button class="button butt_def show_pdf_search">Показать в PDF</button>
        </div>

    </div>

@endsection


