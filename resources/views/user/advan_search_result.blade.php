@extends('layouts.app')

@section('content')

    <input type="hidden" name="random_key" value="{{$random_key}}">
    @if(empty($choose))
    <div class="pagination">{{ $articles->links() }}</div>
    <h3 class="full_row_center title">Результаты поиска</h3>
    @else
        <h3 class="full_row_center title">Выборка из поиска</h3>
    @endif

    @if(!empty($isadvantage))
    <div class="container search_result_box">
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
    @endif
    <div class="container search_result_box_items">
        <hr>
        @if(isset($articles))
            @foreach($articles as $item)
                <div class="search_block">
                    <a href="/report/{{ $item->reports->types->slug }}/article/{{$item->id}}" target="_blank" class="title_link text_decor">
                        <?php echo
	                    strip_tags ($item->title, "<p><a><h1><h2><h3><h4><h5><h6>");
	                    ?>
                    </a>
                    <label class="pdf-checkbox">
                        <span class="span-checkbox">Выбрать</span>
                        <input type="checkbox" value="{{$item->id}}" @if(!empty($choose_array) && in_array($item->id,$choose_array) || !empty($choose)) checked @endif></span>
                    </label>
                    <p><!--strong>Анонс:</strong-->
                        {{ mb_substr(ltrim(html_entity_decode(strip_tags($item->description))),0,200) }}
                    </p>
                    <p>
                        <strong>Отчет: </strong>
                        <a class="report" href="/report/{{ $item->reports->types->slug }}/show/{{$item->report_id}}" target="_blank">
                            @if( $item->reports->types->slug == 'various' )
                            {{ $item->reports->title }}
                            @else
                            {{ $item->reports->types->description }}
                            @endif
                            @if ( $item->reports->types->slug == 'weekly' || $item->reports->types->slug == 'monthly' )
                             за период от {{date("d.m.Y",$item->reports->date_start)}} по {{date("d.m.Y",$item->reports->date_end)}}
                            @elseif( $item->reports->types->slug == 'plannedexhibition' || $item->reports->types->slug == 'countrycatalog' )
                                за {{date("Y",$item->reports->date_start)}} год.
                            @endif
                        </a>
                    </p>
                    @if($item->category_id != 0)
                        <p>
                            <strong>Раздел: </strong>
                            {{\App\Category::find($item->category_id)->title}}
                        </p>
                    @endif

                    @if(isset($item->subcategory_id))
                        <p>
                            <strong>Позраздел:</strong>
                            {{\App\Subcategory::find($item->subcategory_id)->title}}
                        </p>
                    @endif

                </div>
                    @endforeach
                        @if(empty($choose))
                        <div class="pagination">{{ $articles->links() }}</div>
                    @endif
            @endif


        <div class="row fixed_bottom box_save_article mt30">
            <a href="{{ URL::previous() }}" class="button butt_back">Назад</a>
            @if(empty($choose))
            <button class="button butt_def show_pdf_for_search" @if(empty($choose_array)) disabled @endif>Показать выборку</button>
            @endif
            <button class="button butt_def show_pdf_search" @if(empty($choose_array) && empty($choose)) disabled @endif>Выборка в PDF</button>
        </div>
    </div>

@endsection


