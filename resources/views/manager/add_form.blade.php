@extends('layouts.manager')

@section('content')
    <div class="container page_create_post manager_create_user">
        <form action="/manager/add/{{$role->id}}" method="post" enctype="multipart/form-data">
            <div class="row justify-content-center">
                <h3>{{$role->name}}</h3>
                @csrf
            </div>
            <div class="row justify-content-start mb-3 row_mob">
                <div class="coll_left">
                    <span class="name">Имя  и отчество: </span>
                </div>
                <div class="coll_right">
                    <input type="text" class="title_post" value="{{ old('name') }}" name="name" placeholder="Введите имя  и отчество"/>
                </div>
            </div>
            <div class="row justify-content-start mb-3 row_mob">
                <div class="coll_left">
                    <span class="name">Фамилия: </span>
                </div>
                <div class="coll_right">
                    <input type="text" class="title_post" name="surname"  value="{{ old('surname') }}" placeholder="Введите Фамилию"/>
                </div>
            </div>
            @if($role->title == 'user')
                <div class="row justify-content-start mb-3 row_mob">
                    <div class="coll_left">
                        <span class="name">Организация: </span>
                    </div>
                    <div class="coll_right">
                        <input type="text" class="title_post" name="organization" value="{{ old('organization') }}"placeholder="Введите организацию"/>
                    </div>
                </div>
            @endif
            <div class="row justify-content-start mb-3 row_mob">
                <div class="coll_left">
                    <span class="name">Контактный телефон 1: </span>
                </div>
                <div class="coll_right">
                    <input type="text" class="title_post" name="tel1" value="{{ old('tel1') }}"placeholder="Введите Контактрый телефон 1"/>
                </div>
            </div>

            <div class="row justify-content-start mb-3 row_mob">
                <div class="coll_left">
                    <span class="name">Контактный телефон 2: </span>
                </div>
                <div class="coll_right">
                    <input type="text" class="title_post" name="tel2" value="{{ old('tel2') }}"placeholder="Введите Контактрый телефон 2"/>
                </div>
            </div>
            <div class="row justify-content-start mb-3 row_mob">
                <div class="coll_left">
                    <span class="name">Email: </span>
                </div>
                <div class="coll_right">
                    <input type="email" class="title_post" name="email" value="{{ old('email') }}" placeholder="Введите Email"/>
                </div>
            </div>
            <div class="row justify-content-start mb-3 row_mob">
                <div class="coll_left">
                    <span class="name">Введите пароль: </span>
                </div>
                <div class="coll_right">
                    <input type="password" class="title_post" name="password" placeholder="Введите пароль"/>
                </div>
            </div>
            <div class="row justify-content-start mb-3 row_mob">
                <div class="coll_left">
                    <span class="name">Подтвердите пароль: </span>
                </div>
                <div class="coll_right">
                    <input type="password" class="title_post" name="password_for_validate" placeholder="Подтвердите пароль"/>
                </div>
            </div>
            <div class="row box_save_article mt30">
                <button type="submit" class="button_save butt butt_def">Сохранить</button>
            </div>
        </form>
    </div>
@endsection