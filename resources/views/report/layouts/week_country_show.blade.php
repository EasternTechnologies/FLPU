@if(!empty($report->articles))
    @if($report->types->slug=='countrycatalog')
    <div class="row padl_sub2 out_list_title">
        <div class="pdf_box">
            <div class="vpor_box">
                <p class="vpor_title">Военно-политическая обстановка в регионе
                    @if( $role != 'user' && $role !='employee' )
                        @if($report->status == 0)
                            <span class="status st-line st-0">| Не утверждено</span>
                        @elseif($report->status == 1)
                            <span class="status st-line st-1">| Ожидает утверждения</span>
                        @elseif($report->status == 2)
                            <span class="status st-line st-2">| Утверждено</span>
                        @endif
                    @endif
                </p>

                <div class="vpor_desc" style="display:none;">
                    {!! $report->categories[$n1-1]->description !!}

                </div>
            </div>
        </div>
    </div>
    @endif

    @foreach($subcats as  $posts)
        @foreach($posts as $post)
        <div class="row @if($report->types->slug=='various') padl_sub2  @else padl_sub1 @endif out_list_title">
            <div class="search_block">
            <p class="pdf_box">

                <a href="/report/{{$report->types->slug}}/article/{{ $post->id }}">
                    <span>
                    {{ $post->title }}
                    </span>
                </a>
                <label class="pdf-checkbox">
                    <input type="checkbox" value="{{$post->id}}"><span class="pdf"></span>
                </label>

                <a target="_blank" href="/pdf_article/{{ $post->id }}" class="pdf"></a>

            <?php
            $description = explode(' ', ltrim(html_entity_decode(strip_tags($post->description))));
            count($description) <40 ? $count = count($description): $count = 40;
            $descrurtion_short = implode(' ', array_slice($description,0, $count));
            ?>
            <p style="text-align: justify">
                <span>{{$descrurtion_short}}...</span>
            </p>
            @if( $role != 'user' && $role !='employee' )
                @if($post->status == 0 && $report->status!=2)
                    <span class="status st-line st-0">| Не утверждено</span>
                @elseif($post->status == 1 && $report->status!=2)
                    <span class="status st-line st-1">| Ожидает утверждения</span>
                @elseif($post->status == 2 && $report->status!=2)
                    <span class="status st-line st-2">| Утверждено</span>
                    @endif
                @endif
            </p>
            </div>

        </div>
        @endforeach
    @endforeach
@endif
