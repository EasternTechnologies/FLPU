@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
		    <table class="table">
		         <tr>
		        	
			        <td><h4>Пользователь</td>
			        <td><h4>Страницы</h4></td>
			     </tr> 

		        @foreach($users as $user => $paths)
		            <tr>
		                <td>{{$user}}</td>
		                <td>
		                    @foreach($paths as $path=>$visits)
		                        <li><a target="_blank" href="/{{$path}}">{{$path}}</a> - {{$visits}}</li>
		                    @endforeach
		                    <hr>
		                </td>
		            </tr>
		            @endforeach

		    </table>
		 </div>
	</div>
</div>	
@endsection