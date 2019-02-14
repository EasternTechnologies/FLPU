<?php
$d = date("d");
$m = date("m");
$y = date("Y");

$count = cal_days_in_month(CAL_GREGORIAN, $m, $y);
?>
@extends('layouts.app')

@section('content')

    <div class="container page_create_post">
        @if( $report->types->slug == 'plannedexhibition')
            <form id="form" action="/report/{{ $report->types->slug }}/upd" method="post" enctype="multipart/form-data">
                <div class="row justify-content-center posr">

                    <h3>Информационно-справочные материалы к выставкам вооружений</h3>

                    <span class="pos_tr_article_out status st-{{ $article->status }}">
                	@if($article->status == 0)
                            <span class="status st_inherit">Статус:</span> Не утвержден
                        @elseif( $article->status == 1 )
                            <span class="status st_inherit">Статус:</span> Ожидает утверждения
                        @else
                            <span class="status st_inherit">Статус:</span> Утвержден
                        @endif
                </span>

                    @csrf
                    @method('PUT')
                    <input type="text" hidden name="year" value="<?= $y?>">
                    <input type="text" hidden name="month" value="<?= $m?>">
                    <input type="text" hidden name="article" value="{{ $article->id }}">
                </div>
                <div class="row justify-content-start mb-3">
                    <div class="coll_left">
                        <span class="name">Название: </span>
                    </div>
                    <div class="coll_right">
                        <textarea class="title_post" name="title_1" placeholder="Введите название материала">{{$article->title}}</textarea>
                    </div>
                </div>
                <div class="row justify-content-start mb-3">

                    <p class="name date_name">
                        <span>Дата: с</span>

                        <span class="select_wrap calendar_wrap" style="display: none;">
			        	<input name="start_period_picker" value="" class="calendar_start_3"/>
			        	<input type="hidden" value="<?php echo $report->date_start; ?>" name="start_period">
		        	</span>
			        </span>
                        <span> по </span>

                        <span class="select_wrap calendar_wrap" style="display: none;">
				        	<input name="end_period_picker" value="" class="calendar_end_3"/>
				        	<input type="hidden" value="<?php echo $report->date_end; ?>" name="end_period">
				        </span>
                    </p>

                </div>
                <div class="row justify-content-start mb-3">
                    <div class="coll_left">
                        <span class="name">Место: </span>
                    </div>
                    <div class="coll_right">
                        <textarea class="title_post" name="place" placeholder="Введите место проведения">{{$article->place}}</textarea>
                    </div>
                </div>
                <br>
                <div class="row justify-content-start mb-3">
                    <div class="coll_left">
                        <span class="name">Тематика: </span>
                    </div>
                    <div class="coll_right">
                        <textarea name="editor1">{{$article->description}}</textarea>
                    </div>
                </div>

                <div class="row justify-content-start mb_3">
                    <div class="coll_left">
                        <span class="name">Материалы: </span>
                    </div>
                    <div class="coll_right d-flex justify-content-between box_add_gallery">
					    <?php $count_images = 0; ?>
                        @if(isset($article->images))
                            @foreach($article->images as $image)
							    <?php $count_images++; ?>
                                <div class="item_add_gallery item_num_<?php echo $count_images; ?> active">
                                    <label class="file_label" for="input_<?php echo $count_images; ?>">
                                        <input type="file" id="input_<?php echo $count_images; ?>" value="" class="pic" name="pic[]" data-num="<?php echo $count_images; ?>" placeholder="Нажмите чтобы добавить"/>
                                        <input class="reset_img reset_img_<?php echo $count_images; ?>" type="hidden" name="reset_img[]" value=""/>
                                        <span><?php echo str_replace( 'article_images/', '', $image->image); ?></span>
                                        <img src="/images/{{$image->image}}" class="pic_img num_<?php echo $count_images; ?>" alt=""/>
                                        <!-- <b class="del_img_gal">Нажмите, чтобы удалить материал!</b> -->
                                    </label>
                                    <b class="delete_content" tabindex="0">Удалить</b>
                                </div>
                            @endforeach
                        @endif
					    <?php $count_for = $count_images; ?>
					    <?php for($i=0; $i< (3 - $count_for); $i++) {
					    $count_images++;
					    ?>
                        <div class="item_add_gallery item_num_<?php echo $count_images; ?>">
                            <label class="file_label" for="input_<?php echo $count_images; ?>">
                                <input type="file" id="input_<?php echo $count_images; ?>" class="pic" name="pic[]" data-num="<?php echo $count_images; ?>" placeholder="Нажмите чтобы добавить"/>
                                <span>Нажмите, чтобы добавить материал</span>
                                <img src="#" class="pic_img num_<?php echo $count_images; ?>" alt=""/>
                            </label>
                            <b class="delete_content" tabindex="0">Удалить</b>
                        </div>
					    <?php
					    }
					    ?>
                    </div>
                </div>
                <div class="row box_save_article mt30">

                    @if($article->status == 0)
                        <a href="{{ URL::previous() }}" class="button butt_back">Назад</a>
                        {{--<button class="button_save butt butt_def">Сохранить</button>--}}
                        <button onclick="jQuery('#form').attr('action','/report/{{ $report->types->slug }}/upd/2'); jQuery('#form').submit(); return false;" class="button_save butt butt_def">Сохранить и отправить на утверждение</button>
                    @elseif($article->status == 1)
                        <a href="/report/{{ $report->types->slug }}add2/{{$article->reports->id}}" class="button butt_back">Вернуться к отчету</a>
                        {{--<button class="button_save butt butt_def">Сохранить</button>--}}
                        <button onclick="jQuery('#form').attr('action','/report/{{ $report->types->slug }}/upd/2'); jQuery('#form').submit(); return false;" class="button_save butt butt_def">Сохранить и утвердить</button>
                    @else
                        <a href="/report/{{ $report->types->slug }}/add2/{{$article->reports->id}}" class="button butt_back">Вернуться к отчету</a>
                        <button class="button_save butt butt_def">Сохранить</button>
                    @endif

                </div>
                <div class="row justify-content-center">
                    <h3 class="mb_0">Добавление поисковых меток</h3>
                </div>
                <tags-component :selectedtags="{{json_encode($tags)}}"></tags-component>

                <div class="row box_save_article mt30">

                    @if($article->status == 0)
                        <a href="{{ URL::previous() }}" class="button butt_back">Назад</a>
                        {{--<button class="button_save butt butt_def">Сохранить</button>--}}
                        <button onclick="jQuery('#form').attr('action','/report/{{ $report->types->slug }}/upd/1'); jQuery('#form').submit(); return false;" class="button_save butt butt_def">Сохранить и отправить на утверждение</button>
                    @elseif($article->status == 1)
                        <a href="/report/{{ $report->types->slug }}/add2/{{$article->reports->id}}" class="button butt_back">Вернуться к отчету</a>
                        {{--<button class="button_save butt butt_def">Сохранить</button>--}}
                        <button onclick="jQuery('#form').attr('action','/report/{{ $report->types->slug }}/upd/2'); jQuery('#form').submit(); return false;" class="button_save butt butt_def">Сохранить и утвердить</button>
                    @else
                        <a href="/report/{{ $report->types->slug }}/add2/{{$article->reports->id}}" class="button butt_back">Вернуться к отчету</a>
                        <button class="button_save butt butt_def">Сохранить</button>
                    @endif

                </div>

            </form>
        @else
        <form id="form" action="/report/{{ $report->types->slug }}/upd" method="post" enctype="multipart/form-data">
            <div class="row justify-content-center posr">
                <h3>Редактирование материала для отчета</h3>
                
                <span class="pos_tr_article_out status st-{{$article->status}}">
                	@if($article->status == 0)
                		<span class="status st_inherit">Статус:</span> Не утвержден
                	@elseif($article->status == 1)
                		<span class="status st_inherit">Статус:</span> Ожидает утверждения
                	@else
                		<span class="status st_inherit">Статус:</span> Утвержден
                	@endif
                </span>
                
                @csrf
                @method('PUT')

                <div class="col-md-12 box_info">
                    <p class="d-flex justify-content-start">
                        <span class="name">Название: </span><span class="text">{{ $report->types->title }} № {{ $report->number }} за период от {{ date("d.m.Y",$report->date_start)}} до {{ date("d.m.Y",$report->date_end)}}</span>
                    </p>
                    @if($report->types->slug != 'various')
                    <p class="d-flex justify-content-start">
                        <span class="name">Раздел: </span><span class="text">@if(isset($article->category)){{ $article->category->title }} @else {{ $article->subcategory->title }} @endif</span>
                    </p>
                    @endif
                    <input type="text" hidden name="year" value="<?= $y?>">
                    <input type="text" hidden name="month" value="<?= $m?>">
                    <input type="text" hidden name="article" value="{{ $article->id }}">
                </div>

            </div>
            <div class="row justify-content-start mb-3">
                <div class="coll_left">
                    <span class="name">Заголовок: </span>
                </div>
                <div class="coll_right">
                    <input type="text" class="title_post" name="title" placeholder="Введите заголовок" value="{{$article->title}}"/>
                </div>
            </div>
            <div class="row justify-content-start mb_3">
                <div class="coll_left">
                    <span class="name">Материал: </span>
                </div>
                <div class="coll_right">
                    <textarea name="editor1">{{ $article->description }}</textarea>
                </div>
            </div>
            <div class="row justify-content-start mb_3">
                <div class="coll_left">
                    <span class="name">Галерея: </span>
                </div>
                <div class="coll_right d-flex justify-content-between box_add_gallery">
					<?php $count_images = 0; ?>
                    @if(isset($article->images))
                            @foreach($article->images as $image)
								<?php $count_images++; ?>
                                <div class="item_add_gallery item_num_<?php echo $count_images; ?> active">
                                    <label class="file_label" for="input_<?php echo $count_images; ?>">
                                        <input type="file" id="input_<?php echo $count_images; ?>" value="" class="pic" name="pic[]" data-num="<?php echo $count_images; ?>" placeholder="Нажмите чтобы добавить"/>
                                        <input class="reset_img reset_img_<?php echo $count_images; ?>" type="hidden" name="reset_img[]" value=""/>
                                        <span><?php echo str_replace( 'article_images/', '', $image->image); ?></span>
                                        <img src="/images/{{$image->image}}" class="pic_img num_<?php echo $count_images; ?>" alt=""/>
                                        <!-- <b class="del_img_gal">Нажмите, чтобы удалить материал!</b> -->
                                    </label>
                                    <b class="delete_img" tabindex="0">Удалить</b>
                                </div>
                            @endforeach

                    @endif
                    <?php $count_for = $count_images; ?>
                    <?php for($i=0; $i< (3 - $count_for); $i++) {
                    	$count_images++;
                     ?>
                        <div class="item_add_gallery item_num_<?php echo $count_images; ?>">
                            <label class="file_label" for="input_<?php echo $count_images; ?>">
                                <input type="file" id="input_<?php echo $count_images; ?>" class="pic" name="pic[]" data-num="<?php echo $count_images; ?>" placeholder="Нажмите чтобы добавить"/>
                                <span>Нажмите, чтобы добавить изображение</span>
                                <img src="#" class="pic_img num_<?php echo $count_images; ?>" alt=""/>
                            </label>
                            <b class="delete_img" tabindex="0">Удалить</b>
                        </div>
                    <?php
                    }
					?>
                </div>
            </div>
            <div class="row justify-content-center">
                <h3 class="mb_0">Добавление поисковых меток</h3>
            </div>
            <div class="row name_report name_report_step3 d-flex justify-content-center">
            	<div class="col-md-4 float_left d-flex  flex-column justify-content-center align-items-start box_left">
					<span>Период с</span>
		            <span class="select_wrap calendar_wrap" style="display: none;">
			        	<input name="start_period_picker" value="" class="calendar_start_3"/>
			        	<input type="hidden" value="<?php echo $report->date_start; ?>" name="start_period">
		        	</span>
		        	</div>
		        	<div class="col-md-4 float_left d-flex  flex-column justify-content-center align-items-start box_right">
		                <span>Период по</span>
		                <span class="select_wrap calendar_wrap" style="display: none;">
				        	<input name="end_period_picker" value="" class="calendar_end_3"/>
				        	<input type="hidden" value="<?php echo $report->date_end; ?>" name="end_period">
				        </span>
			       </div>
            </div>
            </br>
            </br>
            </br>

            <tags-component :selectedtags="{{json_encode($tags)}}"></tags-component>
            
            <div class="row box_save_article mt30">

                
                @if($article->status == 0)
                	<a href="{{ URL::previous() }}" class="button butt_back">Назад</a>
                	<button class="button_save butt butt_def">Сохранить</button>
	            	<button onclick="jQuery('#form').attr('action','/report/{{ $report->types->slug }}/upd/1'); jQuery('#form').submit(); return false;" class="button_save butt butt_def">Сохранить и отправить на утверждение</button>
	        	@elseif($article->status == 1 || $article->status == 2)
	        		<a href="report/{{$report->types->slug}}/add2/{{$report->id}}" class="button butt_back">Вернуться к отчету</a>
	                <button onclick="jQuery('#form').attr('action','/report/{{ $report->types->slug }}/upd/2'); jQuery('#form').submit(); return false;" class="button_save butt butt_def">Сохранить и утвердить</button>
	        	@else
	        		<a href="report/{{$report->types->slug}}/add2/{{$report->id}}" class="button butt_back">Вернуться к отчету</a>
	        		<button class="button_save butt butt_def">Сохранить</button>
	        	@endif
                
            </div>
        </form>
        @endif
        @if($article->status == 0)
            <form name="form_1" onsubmit="addApprove(this,'{{$article->title}}');return false;" action="/article_for_approval/{{ $article->id }}" method="post">
                {{ method_field('put') }}
                @csrf
            </form>
        @elseif($article->status == 1)
            <form name="form_2" onsubmit="approve(this,'{{$article->title}}');return false;" action="/article_publish/{{ $article->id }}" method="post">
                {{ method_field('put') }}
                @csrf
            </form>
        @endif
        
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" charset="utf-8">
        jQuery(document).ready(function () {
            jQuery('.calendar_start_3').datepicker({
                keyboardNavigation: true,
                modal: true,
                header: true,
                footer: true,
                uiLibrary: 'bootstrap4',
                locale: 'ru-ru',
                minDate: '<?php echo date("d.m.Y",$report->date_start); ?>',
                maxDate: '<?php echo date("d.m.Y",$report->date_end); ?>',
                value: '<?php echo date("d.m.Y",$article->date_start); ?>',
                format: 'dd.mm.yyyy',
            });

            jQuery('.calendar_end_3').datepicker({
                keyboardNavigation: true,
                modal: true,
                header: true,
                footer: true,
                uiLibrary: 'bootstrap4',
                locale: 'ru-ru',
                minDate: '<?php echo date("d.m.Y",$report->date_start); ?>',
                maxDate: '<?php echo date("d.m.Y",$report->date_end); ?>',
                value: '<?php echo date("d.m.Y",$article->date_start); ?>',
                format: 'dd.mm.yyyy',

            });
			
			jQuery('.calendar_start_3').change(function() {	
				
				jQuery('.calendar_wrap').removeClass('error');
				
				var data_change = jQuery(this).val();
				var arr = data_change.split('.');
        		var d = Number(arr[0]);
				var m = Number(arr[1]) - 1;
				var y = Number(arr[2]);
        		var date = new Date(y,m,d).getTime()/1000;
        		jQuery('[name=start_period]').val(date);
        	})
        	
			jQuery('.calendar_end_3').change(function() {	
				
				jQuery('.calendar_wrap').removeClass('error');
				
				var data_change = jQuery(this).val();
				var arr = data_change.split('.');
				var d = Number(arr[0]);
				var m = Number(arr[1]) - 1;
				var y = Number(arr[2]);
        		var date = new Date(y,m,d).getTime()/1000;
        		jQuery('[name=end_period]').val(date);
        	})
        	

            jQuery('.calendar_wrap').show(500);
        });
    </script>
@endsection