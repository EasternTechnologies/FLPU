@extends('layouts.manager')

@section('content')
    <div class="container">
        <div class="row justify-content-center manager_home">
            <div class="col-md-12">
                <div class="row co_l2">
                    <div class="col-md-6 item_card">
                        <h5>Менеджер</h5>
                        <a href="/manager/addform/{{$manager->id}}">
	                        <button class="butt margin_b">
	                            Зарегистрировать пользователя
	                        </button>
	                    </a>
	                    <a href="/manager/users/{{$manager->id}}">
	                        <button class="butt">
	                            Управление пользователями
	                        </button>
	                    </a>
                    </div>
                    <div class="col-md-6 item_card">
                        <h5>Аналитик</h5>
                        <a href="/manager/addform/{{ $analyst->id }}">
	                        <button class="butt margin_b">
	                            Зарегистрировать пользователя
	                        </button>
                        </a>
                        <a href="/manager/users/{{$analyst->id}}">
	                        <button class="butt">
	                            Управление пользователями
	                        </button>
                        </a>
                    </div>
                </div>
                <div class="row co_l2">
                    <div class="col-md-6 item_card">
                        <h5>Сотрудник</h5>
                        <a href="/manager/addform/{{$employee->id}}">
	                        <button class="butt margin_b">
	                            Зарегистрировать пользователя
	                        </button>
                        </a>
                        <a href="/manager/users/{{$employee->id}}">
	                        <button class="butt">
	                            Управление пользователями
	                        </button>
                        </a>
                    </div>
                    <div class="col-md-6 item_card">
                        <h5>Пользователь</h5>
                        <a href="/manager/addform/{{$user->id}}">
	                        <button class="butt margin_b">
	                            Зарегистрировать пользователя
	                        </button>
                        </a>
                        <a href="/manager/users/{{$user->id}}">
	                        <button class="butt">
	                            Управление пользователями
	                        </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
