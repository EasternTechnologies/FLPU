@foreach($subcats as  $subcat =>$posts)
    @if($subcat != false)

        <?php $n2++; ?>
        <div class="row">
            <p class="title padl_sub1 title_sub_cat">
                {{ $n1 }}.{{ $n2 }}. {{ $subcat }}
                <span><a target="_blank" href="/pdf_subcategory/{{ $report->id }}/{{ $categories->where('title',$cat)->first()->id }}/{{ $subcategories->where('title',$subcat)->first()->id }}" class="pdf"></a></span>
            </p>
        </div>
    @endif
    @foreach($posts as  $k =>$post)
        @if(isset($post))
            <?php $n3++; ?>
            <div class="row padl_sub2 out_list_title">
                <div class="search_block">
                <p class="pdf_box">
                <span>
                <a href="/report/{{ $report->types->slug }}/article/{{ $post->id }}">

                <p>  {{ $n1 }}.{{ $n2 }}.{{ $n3 }}. {{ $post->title }}</p>
                </a>
                </span>
                                            <span>
                                                <a target="_blank" href="/pdf_article/{{ $post->id }}" class="pdf"></a>
                                            </span>
                <label class="pdf-checkbox">
                    <input type="checkbox" value="{{$post->id}}"><span class="pdf"></span>
                </label>
                <?php
                $description = explode(' ', ltrim(html_entity_decode(strip_tags($post->description))));
                count($description) <40 ? $count = count($description): $count = 40;
                $descrurtion_short = implode(' ', array_slice($description,0, $count));
                ?>
                <p style="text-align: justify">
                    <span>{{$descrurtion_short}}...</span>
                </p>
                @if( $role != 'user' && $role !='employee' )
                    @if($post->status == 0 && $report->published!=2)
                        <p class="status st-line st-0">| Не утверждено</p>
                    @elseif($post->status == 1 && $report->status !=2)
                        <p class="status st-line st-1">| Ожидает утверждения</p>
                    @elseif($post->status == 2 && $report->status !=2)
                        <p class="status st-line st-2">| Утверждено</p>
                    @endif
                @endif
                </div>
            </div>
        @endif
    @endforeach

@endforeach