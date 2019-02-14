@extends('layouts.app')
@section('content')
    <div class="container">
		<div class="row">
			<div class="col-md-12">
			    <table class="table">
			        <tr>
			            <th><h4>Путь</h4></th>
			            <th><h4>Количество просмоторов</h4></th>
			        </tr>
			        @foreach($arr as $path=>$visits)
			            <tr>
			            <td><a target="_blank" href="/{{$path}}">{{$path}}</a><td>{{$visits}}</td></td>
			            </tr>
			        @endforeach
			    </table>
			</div>
		</div>
	</div>
@endsection