@extends('layouts.manager')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <h3> Управление пользователями типа "{{$role->name}}"</h3>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-12 out_table table_manager">
                <ul class="t_head">
                    <li class="title">ФИО</li>
                    <li>Контакты</li>
                    <li>E-mail</li>
                    <li>Редактирование</li>
                </ul>
                @foreach($users as $user)
                    <ul>
                        <li>
                            <a href="/manager/user/{{ $user->id}}">{{ $user->surname }} {{ $user->name }}</a>
                        </li>
                        <li>{{ $user->tel1 }} {{ $user->tel2 }}</li>
                        <li>{{ $user->email }}</li>
                        <li>


                                <a href="/manager/users/{{$user->id}}/update" class="text_decor link">Редактировать</a>
                            <span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                            <form onsubmit="deleteName(this,'{{ $user->surname }} {{ $user->name }}');return false;" action="/manager/users/{{ $user->id }}/delete" method="post">
                                {{ method_field('delete') }}
                                @csrf
                                <button class="text_decor link">Удалить</button>
                            </form>
                        </li>
                    </ul>

                @endforeach
            </div>
        </div>
    </div>
    <div class="pagination">{{$users->links()}}</div>
    <div class="row justify-content-center">
        <a href="/analyst">
        	<button class="butt butt_mt_2 butt_def">Вернуться на главную</button>
        </a>
    </div>
@endsection
