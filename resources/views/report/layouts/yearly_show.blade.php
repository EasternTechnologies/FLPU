@foreach($subcats as $subcat=>$posts)
    <div class="@if($subcat!==0) row  padl_sub1 out_list_title @endif">

        @if($subcat!==0)
        <p class="pdf_box">
                {{ $subcat}}
                <span>
                   <a target="_blank" href="/pdf_subcategory/{{$report->id}}/{{ $categories->where('title',$cat)->first()->id }}/{{$subcategories->where('title',$subcat)->first()->id}}" class="pdf"></a>
                   </span>
            </p>

        @endif
        @foreach($posts as $post)
                <div class="row padl_sub2 out_list_title">
                    <div class="search_block">
                <p class="pdf_box">
                    <a href="/report/yearly/article/{{ $post->id }}">
                        <strong>{{ $post->title }}</strong>
                    </a>
                    <a target="_blank" href="/pdf_article/{{ $post->id }}" class="pdf"></a>
                    <label class="pdf-checkbox">
                        <input type="checkbox" value="{{$post->id}}"><span class="pdf"></span>
                    </label>
                </p>
                <?php
                $description = explode(' ', ltrim(html_entity_decode(strip_tags($post->description))));
                count($description) < 40 ? $count = count($description): $count = 40;
                $descrurtion_short = implode(' ', array_slice($description,0, $count));
                ?>
                <p style="text-align: justify">
                    <span>{{$descrurtion_short}}...</span>
                </p>
                </div>
                </div>
        @endforeach

    </div>
@endforeach


