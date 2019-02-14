@extends('layouts.manager')

@section('content')
    <div class="container">
        <h3>{{$role->name}}</h3>
        <div class="row justify-content-start mb-3">
            <div class="coll_left">
                <span class="name">Имя  и отчество: </span>
            </div>
            <div class="coll_right">
                <p>{{$user->name}}</p>
            </div>
        </div>
        <div class="row justify-content-start mb-3">
            <div class="coll_left">
                <span class="name">Фамилия: </span>
            </div>
            <div class="coll_right">
                <p>{{$user->surname}}</p>
            </div>
        </div>
        @if($role->title == 'user')
            <div class="row justify-content-start mb-3">
                <div class="coll_left">
                    <span class="name">Организация: </span>
                </div>
                <div class="coll_right">
                    <p>{{$user->organization}}</p>
                </div>
            </div>
        @endif
        <div class="row justify-content-start mb-3">
            <div class="coll_left">
                <span class="name">Контактный телефон 1: </span>
            </div>
            <div class="coll_right">
                <p>{{$user->tel1}}</p>
            </div>
        </div>

        <div class="row justify-content-start mb-3">
            <div class="coll_left">
                <span class="name">Контактный телефон 2: </span>
            </div>
            <div class="coll_right">
                <p>{{$user->tel2}}</p>
            </div>
        </div>
        <div class="row justify-content-start mb-3">
            <div class="coll_left">
                <span class="name">Email: </span>
            </div>
            <div class="coll_right">
                <p>{{$user->email}}</p>
            </div>
        </div>
    </div>
@endsection