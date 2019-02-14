@extends('layouts.app')
@section('content')
    <div class="container cabinet_box">
        <h3>{{$role->name}}</h3>
        <div class="row justify-content-start mb-3">
            <div class="coll_left">
                <span class="name"><strong>Имя  и отчество: &nbsp;</strong></span>
            </div>
            <div class="coll_right">
                <span>{{$user->name}}</span>
            </div>
        </div>
        <div class="row justify-content-start mb-3">
            <div class="coll_left">
                <span class="name"><strong>Фамилия: &nbsp;</strong></span>
            </div>
            <div class="coll_right">
                <span>{{$user->surname}}</span>
            </div>
        </div>
        @if($role->title == 'user')
            <div class="row justify-content-start mb-3">
                <div class="coll_left">
                    <span class="name"><strong>Организация: &nbsp;</strong></span>
                </div>
                <div class="coll_right">
                    <span>{{$user->organization}}</span>
                </div>
            </div>
        @endif
        <div class="row justify-content-start mb-3">
            <div class="coll_left">
                <span class="name"><strong>Контактный телефон 1: &nbsp;</strong></span>
            </div>
            <div class="coll_right">
                <span>{{$user->tel1}}</span>
            </div>
        </div>

        <div class="row justify-content-start mb-3">
            <div class="coll_left">
                <span class="name"><strong>Контактный телефон 2: &nbsp;</strong></span>
            </div>
            <div class="coll_right">
                <span>{{$user->tel2}}</span>
            </div>
        </div>
        <div class="row justify-content-start mb-3">
            <div class="coll_left">
                <span class="name"><strong>Email: &nbsp;</strong></span>
            </div>
            <div class="coll_right">
                <p>{{$user->email}}</p>
            </div>
        </div>
    </div>
@endsection