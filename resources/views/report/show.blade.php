<?php $role = Auth::user()->roles[0]->title;?>

@extends('layouts.app')

@section('content')

	<div class="container add_form_step2 posr">
		<h3 class="title">{{ $report->types->description }}
			@if( $report->types->slug == 'weekly' || $report->types->slug == 'monthly' )
				№ {{ $report->number }} за период от {{date("d",$report->date_start)}} {{Helper::getMonthText(date("m",$report->date_start))}} {{date("Y",$report->date_start)}} года по {{date("d",$report->date_end)}} {{m_name(date("m",$report->date_end))}} {{date("Y",$report->date_end)}} года
			@else
				за {{ date("Y",$report->date_start) }} год.
			@endif
			<span>
            		<a target="_blank" href="/pdf_item/{{ $report->id }}" class="pdf"></a>
            	</span>
		</h3>
		@if( $role != 'user' && $role !='employee' )
			<span class="pos_tr_article_out status st-{{10 + $report->status}}">
        	@if($report->status == 2)
					<span class="status st_inherit">Статус:</span> Опубликован
				@elseif($report->status == 1)
					<span class="status st_inherit">Статус:</span> Все материалы утверждены
				@elseif($report->status == 0)
					<span class="status st_inherit">Статус:</span> Не опубликован

				@endif
        </span>
		@endif
		@if( $report->types->slug == 'plannedexhibition' )
			<div class="container">
				<div class="row justify-content-center">
					<div class="col-md-12 out_table analyst_report">
						<table style="border: 1px solid">
							<thead>
							<tr style="border: 1px solid">
								<td style="width: 3%;">
									№ n/n
								</td>
								<td style="width: 30%;">Название выставки</td>
								<td style="width: 10%;">Дата</td>
								<td style="width: 20%;">Место</td>
								<td style="width: 27%;">Тематика выставки</td>
								<td style="width: 10%;">Скачать материалы к выставке</td>
							</tr>
							</thead>

							<tbody>
							@foreach($report->articles as $item)
								<tr style="border: 1px solid">
									<td>
										{{ $item->id }}
									</td>
									<td style="color: black !important">
										<a href="/report/{{ $report->types->slug }}/article/{{ $item->id }}">
											<?php echo
											strip_tags ( $item->title , "<p><a><h1><h2><h3><h4><h5><h6><b>");
											?>
										</a>
									</td>
									<td style="border: 1px solid;" class="center">
										{{ date("d",$item->date_start) }} - {{ date("d",$item->date_end) }}  {{ m_name(date("m",$item->date_end)) }}
									</td>
									<td style="border: 1px solid;">
										<?php echo strip_tags ($item->place , "<p><a><h1><h2><h3><h4><h5><h6><b>")?>
									</td>
									<td>
										<?php echo strip_tags ($item->description , "<p><a><h1><h2><h3><h4><h5><h6><b>")?>
									</td>
									<td style="border: 1px solid; text-align:center;">
										<div class="file_wrap">
											@foreach( $item['images'] as $image )
												<a target="_blank" href="/images/{{ $image->image }}" class="file_img exhibition"></a>
											@endforeach
										</div>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>

			</div>
		@else
		@if( $report->types->slug == 'yearly' || $report->types->slug == 'various' )

			@if(!empty($report))

				@if(!empty($report->articles))
					@foreach($report->articles as  $article)
						@if(!isset($article->category) && !isset($article->subcategory))
							<div class="row padl_sub2 out_list_title">

								<a href="/report/{{ $report->types->slug }}/article/{{ $article->id }}">
									<p>{{ $article->title }}</p>
								</a>
								<a target="_blank" href="/pdf_article/{{ $article->id }}" class="pdf"></a>
								<?php
								$description = explode(' ', ltrim(html_entity_decode(strip_tags($article->description))));
								count($description) <40 ? $count = count($description): $count = 40;
								$descrurtion_short = implode(' ', array_slice($description,0, $count));
								?>
								<p style="text-align: justify">
									<span>{{$descrurtion_short}}...</span>
								</p>
								@if( $role != 'user' && $role !='employee' )
									@if($article->status == 0)
										<p class="status st-line st-0">| Не утверждено</p>
									@elseif($article->status == 1)
										<p class="status st-line st-1">| Ожидает утверждения</p>
									@elseif($article->status == 2)
										<p class="status st-line st-2">| Утверждено</p>
									@endif
								@endif
							</div>
						@endif
					@endforeach
				@endif

			@endif
		@endif
		@foreach($categories as $category)
			@if(count($category->article_reports) != 0  && $category->article_reports->contains('report_id', $report->id) )
				<div class="row">
					<p class="title">{{ $category->title }}</p>
				</div>
				@if( $report->types->slug == 'countrycatalog')
					<div class="row padl_sub2 out_list_title">
						<div class="vpor_box">
							<p class="vpor_title">Военно-политическая обстановка в регионе

							</p>
							<div class="vpor_desc" style="display:none;">
								{!!$category->description !!}
							</div>
						</div>
					</div>
				@endif
			@endif
			@if( $report->types->slug != 'various')
				@if(!empty($report->categories))

						@if(isset($category->article_reports))
							@foreach($category->article_reports as  $article)
								@if( $article->subcategory_id == null && $article->category_id == $category->id )
									<div class="row padl1 out_list_title">
										<a href="/report/{{ $report->types->slug }}/article/{{ $article->id }}"><p>{{ $article->title }}</p>
										</a>
										<a target="_blank" href="/pdf_article/{{ $article->id }}" class="pdf"></a>
										@if( $role != 'user' && $role !='employee' )
										@if($article->status == 0)
											<p class="status st-line st-0">| Не утверждено</p>
										@elseif($article->status == 1)
											<p class="status st-line st-1">| Ожидает утверждения</p>
										@elseif($article->status == 2)
											<p class="status st-line st-2">| Утверждено</p>
										@endif
										@endif
										<?php
										$description = explode(' ', ltrim(html_entity_decode(strip_tags($article->description))));
										count($description) <40 ? $count = count($description): $count = 40;
										$descrurtion_short = implode(' ', array_slice($description,0, $count));
										?>
										<p style="text-align: justify">
											<span>{{$descrurtion_short}}...</span>
										</p>
									</div>
								@endif
							@endforeach
						@endif

				@endif
			@endif
			@if(!empty($category->subcategories))
				@foreach($category->subcategories as  $subcategory)
						@if(count($subcategory->article_reports) != 0)
					<div class="row padl_sub1 out_list_title">

						<p class="title">{{ $subcategory->title }}</p>

						@if(!empty($subcategory->article_reports))
							@foreach($subcategory->article_reports as  $article)
								@if($article->subcategory && $article->report_id == $report->id)
									<div class="row padl_sub2 out_list_title">

										<a href="/report/{{ $report->types->slug }}/article/{{ $article->id }}">
											<p>{{ $article->title }}</p>
										</a>
										<a target="_blank" href="/pdf_article/{{ $article->id }}" class="pdf"></a>

										@if( $role != 'user' && $role !='employee' )
										@if($article->status == 0)
											<p class="status st-line st-0">| Не утверждено</p>
										@elseif($article->status == 1)
											<p class="status st-line st-1">| Ожидает утверждения</p>
										@elseif($article->status == 2)
											<p class="status st-line st-2">| Утверждено</p>
										@endif
										@endif
										<?php
										$description = explode(' ', ltrim(html_entity_decode(strip_tags($article->description))));
										count($description) <40 ? $count = count($description): $count = 40;
										$descrurtion_short = implode(' ', array_slice($description,0, $count));
										?>
										<p style="text-align: justify">
											<span>{{$descrurtion_short}}...</span>
										</p>

									</div>
								@endif
							@endforeach
						@endif
					</div>
						@endif
				@endforeach
			@endif
		@endforeach
	@endif
	</div>

	<div class="row box_save_article mt30">
		@if(Request::url() == URL::previous())
			<a href="/{{ $report->types->slug }}/" class="button butt_back">Все отчеты</a>
		@else
			<a href="{{ URL::previous() }}" class="button butt_back">Назад</a>
		@endif
		@if( $role != 'user' && $role !='employee' )
			<a class="button butt_def" href="/report/{{ $report->types->slug }}/add2/{{ $report->id }}">Редактировать</a>
		@endif
	</div>
@endsection
@section('scripts')
<script type="text/javascript" charset="utf-8">
    jQuery(document).ready(function () {
        jQuery('.vpor_title').on('click', function () {

            if (jQuery(this).parent('.vpor_box').hasClass('active')) {
                jQuery(this).parent('.vpor_box').removeClass('active');
                jQuery('.vpor_box .vpor_desc').fadeOut(500);
            } else {
                jQuery(this).parent('.vpor_box').addClass('active');
                jQuery('.vpor_box.active .vpor_desc').fadeIn(500);
            }

        })
    })
</script>
@endsection