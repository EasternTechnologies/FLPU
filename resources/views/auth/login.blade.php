@extends('layouts.auth')

@section('content')
<div class="sign_in_box">
	<div class="logo_box_form">
		<img src="{{asset('images/auth/logo.png')}}" alt="" />
		<div>
			БСВТ АНАЛИТИКА
		</div>
	</div>
	<div class="form_default sing_in_form box_center_form">
		<h3>Вход</h3>
		<form method="POST" action="{{ route('login') }}">
			@csrf

			<div class="form-group row">
				<div class="col-md-12">
					<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="" placeholder="Email" required autofocus>

					@if ($errors->has('email'))
					<span class="invalid-feedback"> <strong>{{ $errors->first('email') }}</strong> </span>
					@endif
				</div>
			</div>

			<div class="form-group row">
				<div class="col-md-12">
					<input id="password" type="password" placeholder="Пароль" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

					@if ($errors->has('password'))
					<span class="invalid-feedback"> <strong>{{ $errors->first('password') }}</strong> </span>
					@endif
				</div>
			</div>

			<div class="form-group row mb-0">
				<div class="col-md-12 d-flex justify-content-center">
					<button type="submit" class="butt">
						Войти
					</button>
				</div>
				<div class="col-md-12 additionally">
					<a class="butt_link" href="{{ route('password.request') }}"> Забыли пароль? </a>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection