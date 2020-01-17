<?php
$d = date("d");
$m = date("m");
$y = date("Y");
?>
@extends('layouts.app')
@section('content')
    @if(empty($choose) and isset($isadvantage) )
    <div class="container" id="myModalId">

        <button class="butt_tag_click button_small" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            Фасетный поиск
        </button>
    </div>
    <div class="container" >
        <div class="collapse" id="collapseExample">
        <div class="card card-body">
            <form action="/search" class="search_form_adv" method="post">
                @csrf
                <div class="col-md-12">
                    <div class="search-form__filter row">

                        <p class="search-form__block">
                            <label> Тип отчета
                                <select class="search-form__field report_type" name="report_type">
                                    <option value="all_reports">Все отчеты</option>

                                    @foreach($report_types as $slug =>$type)
                                        <option value="{{ $slug }}" @if ($slug == $request->old('report_type')){{ 'selected' }} @endif >{{ $type }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </p>
                        <p class="search-form__block weekly_block">
                            <label> Категории
                                <select class="search-form__field" name="new_weekly">
                                    <option value="0">Все отчеты</option>

                                    @foreach($weeklycategories as $category)
                                        <option value="{{ $category->id }}" @if ($category->id == $request->old('new_weekly')){{ 'selected' }} @endif>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </p>
                        <p class="search-form__block monthly_block">
                            <label> Категории

                                <select class="search-form__field" name="new_monthly">
                                    <option value="0">Все отчеты</option>
                                    @foreach($monthlycategories as $category)
                                        <option value="{{ $category->id }}" @if ($category->id == (int)$request->old('new_monthly')){{ 'selected' }} @endif>{{ $category->title }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </p>
                        <p class="search-form__block search-form__block--date" id="mymodalexample">
                            <label class="search-form__title">
                                Период с
                                <input name="date_start" value="{{date("d.m.Y",$start_period)}}" class="calendar_start_3 search-form__field"/>
                                <input type="hidden" value="{{$request->old('start_period') }}" name="start_period">
                            </label>
                            <label class="search-form__title">
                                Период по
                                <input name="date_end" value="{{date("d.m.Y",$end_period)}}" class="calendar_end_3 search-form__field"/>
                                <input type="hidden" value="{{$request->old('end_period') }}" name="end_period">
                            </label>
                        </p>
                    </div>
                    <div class="search-form__filter row" style="padding-left: 84px;">
                        <input name="q" class="search" value="{{$request->old('q') }}" type="text" placeholder="Ключевая фраза" style="color: rgb(120, 120, 120);max-width: 336px;"/>
                    </div>
                </div>
                <tagsforsearch-component :selectedtags="{{ json_encode($tags) }}"></tagsforsearch-component>

                <input type="hidden" name="random_key_before" value="">

                <div class="row box_save_article">
                    <button class="button button--search pdf-reset">Поиск</button>
                </div>
            </form>
        </div>
    </div>
    </div>
    @endif
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
            @if(isset($q))
                <p>
                    <strong>Ключевая фраза:</strong>
                    {{$q}}
                </p>
            @endif
            @if($countries->count()!=0)
                <p>
                    <strong>Страны:</strong>
                    @foreach($countries as $key => $country )
                        <?php if ( $key == 0 ) {
                            echo $country->title;
                        }
                        else {
                            echo ', ' . $country->title;
                        }?>
                    @endforeach
                </p>
            @endif
            @if($vvt_types->count()!=0)
                <p>Тип ВВТ:
                    @foreach($vvt_types as $key => $vvt_type )
                        <?php if ( $key == 0 ) {
                            echo $vvt_type->title;
                        }
                        else {
                            echo ', ' . $vvt_type->title;
                        }?>
                    @endforeach
                </p>
            @endif
            @if($companies->count()!=0)
                <p>Компании: @foreach($companies as $key => $company )
                        <?php if ( $key == 0 ) {
                            echo $company->title;
                        }
                        else {
                            echo ', ' . $company->title;
                        }?>
                    @endforeach
                </p>
            @endif
            @if($personalities->count()!=0)
                <p>Персоналии: @foreach($personalities as $key => $personality )
                        <?php if ( $key == 0 ) {
                            echo $personality->title;
                        }
                        else {
                            echo ', ' . $personality->title;
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
                    <a href="/report/{{ $item->reports->types->slug }}/article/{{$item->id}}@if(isset($needle_tourl)){{"?needles=$needle_tourl"}}@endif @if(isset($q)){{"&q=$q"}} @endif" target="_blank" class="title_link text_decor">
                        <?php echo strip_tags($item->title, "<p><a><h1><h2><h3><h4><h5><h6>");
                        ?>
                    </a>
                    <label class="pdf-checkbox">
                        <span class="span-checkbox">Выбрать</span>
                        <input type="checkbox" value="{{$item->id}}" @if(!empty($choose_array) && in_array($item->id,$choose_array) || !empty($choose)) checked @endif></span>
                    </label>

                    <p><!--strong>Анонс:</strong-->

                        <?php
                        if ( isset ($q) ) {
                            preg_match("/$q/ui", mb_substr(ltrim(html_entity_decode(strip_tags($item->description))), 0, 200), $q_repl);
                            //dump($q_repl);
                            if ( isset($q_repl[ 0 ]) ) {

                                $desc = preg_replace("/$q/iu", "<b class=\"highlight\">$q_repl[0]</b>", mb_substr(ltrim(html_entity_decode(strip_tags($item->description))), 0, 200));
                            }
                        }
                        ?>
                        @if(isset ($desc))

                            {!!
                                !empty($patterns) ?
                                preg_replace($patterns,$replacements,$desc)
                                : $desc;
                            !!}
                            <?php unset($desc) ?>
                        @else

                            {!!
                                !empty($patterns) ?
                                preg_replace($patterns,$replacements,mb_substr(ltrim(html_entity_decode(strip_tags($item->description))),0,200))
                                : mb_substr(ltrim(html_entity_decode(strip_tags($item->description))),0,200);
                            !!}
                        @endif
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

@section('scripts')
    <script type="text/javascript" charset="utf-8">

        jQuery(document).ready(function () {

                var val = $('.report_type').find(':selected').val();

                if (val == "weekly") {
                    jQuery('.weekly_block').css('position', 'relative');
                    jQuery('.weekly_block').css('top', '0');
                } else {
                    jQuery('.weekly_block').css('top', '-9999px');
                    jQuery('.weekly_block').css('position', 'absolute');
                }

                if (val == "monthly") {
                    jQuery('.monthly_block').css('position', 'relative');
                    jQuery('.monthly_block').css('top', '0');
                } else {
                    jQuery('.monthly_block').css('position', 'absolute');
                    jQuery('.monthly_block').css('top', '-9999px');
                };
            jQuery('.report_type').change(function () {

                var val = $(this).find(':selected').val();

                if (val == "weekly") {
                    jQuery('.weekly_block').css('position', 'relative');
                    jQuery('.weekly_block').css('top', '0');
                } else {
                    jQuery('.weekly_block').css('top', '-9999px');
                    jQuery('.weekly_block').css('position', 'absolute');
                }

                if (val == "monthly") {
                    jQuery('.monthly_block').css('position', 'relative');
                    jQuery('.monthly_block').css('top', '0');
                } else {
                    jQuery('.monthly_block').css('position', 'absolute');
                    jQuery('.monthly_block').css('top', '-9999px');
                }
            });

            jQuery('.calendar_start_3').datepicker({
                container:'#mymodalexample',
                keyboardNavigation: true,
                modal: true,
                header: true,
                footer: true,
                uiLibrary: 'bootstrap4',
                locale: 'ru-ru',
                //value: '01.01.{{$y}}',
                format: 'dd.mm.yyyy',
            });

            jQuery('.calendar_end_3').datepicker({
                keyboardNavigation: true,
                modal: true,
                header: true,
                footer: true,
                uiLibrary: 'bootstrap4',
                locale: 'ru-ru',
                //value: '{{$d}}.{{$m}}.{{$y}}',
                format: 'dd.mm.yyyy',

            });

            jQuery('.calendar_start_3').change(function () {

                jQuery('.search-form__title').removeClass('error');

                var data_change = jQuery(this).val();
                var arr = data_change.split('.');
                var d = Number(arr[0]);
                var m = Number(arr[1]) - 1;
                var y = Number(arr[2]);
                var date = new Date(y, m, d).getTime() / 1000;
                jQuery('[name=start_period]').val(date);
            });

            jQuery('.calendar_end_3').change(function () {

                jQuery('.search-form__title').removeClass('error');

                var data_change = jQuery(this).val();
                var arr = data_change.split('.');
                var d = Number(arr[0]);
                var m = Number(arr[1]) - 1;
                var y = Number(arr[2]);
                var date = new Date(y, m, d).getTime() / 1000;
                jQuery('[name=end_period]').val(date);
            });

            jQuery('.calendar_wrap').show(500);

        });
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@voerro/vue-tagsinput@2.0.2/dist/style.css">

@endsection
