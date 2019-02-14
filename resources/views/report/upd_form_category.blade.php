@extends('layouts.app')
<?php isset($category->category) ? $slug = $category->category->report->types->slug : $category->report->types->slug;?>
@section('content')
123
    <div class="container page_create_post">
        <form id="form" action="/report/{{ $slug }}/upd_category/{{$category->id}}" method="post" enctype="multipart/form-data">
            <div class="row justify-content-center posr">
                <h3>Редактирование категории</h3>

                @csrf
                @method('PUT')

            </div>
            <div class="row justify-content-start mb-3">
                <div class="coll_left">
                    <span class="name">Категория: </span>
                </div>
                <div class="coll_right">
                    <input type="text" class="title_post" name="title" placeholder="Введите регион" value="{{ $category->title }}"/>
                </div>
            </div>
            @if( $slug == 'countrycatalog' )
                <div class="row justify-content-start mb_3">
                    <div class="coll_left">
                        <span class="name">Описание: </span>
                    </div>
                    <div class="coll_right">
                        <textarea name="editor1">{{ $category->description }}</textarea>
                    </div>
                </div>
            @endif
            
            <div class="row box_save_article mt30">



                        <a href="{{ URL::previous() }}" class="button butt_back">Назад</a>

                @if($category->category)

                        <button onclick="jQuery('#form').attr('action','/report/{{ $slug }}/upd_subcategory/{{$category->id}}'); jQuery('#form').submit(); return false;" class="button_save butt butt_def">Сохранить</button>


                    @else

                        <button onclick="jQuery('#form').attr('action','/report/{{ $slug }}/upd_category/{{$category->id}}'); jQuery('#form').submit(); return false;" class="button_save butt butt_def">Сохранить</button>


                @endif
                
            </div>
            
        </form>

    </div>
@endsection