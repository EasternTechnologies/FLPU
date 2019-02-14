<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<!-- CSRF Token -->
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<title>Авторизация | БСВТ АНАЛИТИКА</title>

		<!-- Scripts -->
		<script src="{{ asset('build/js/app.js') }}" defer></script>

		<!-- Styles -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		
		<!-- Styles MEDIA -->
    	<link href="{{ asset('css/media.css') }}" rel="stylesheet">
    	
		<!-- Styles MEDIA -->
    	<link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
	</head> 
	<body>
		<div id="app">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						@if ($errors->any())
				            <div class="alert alert-danger">
				                <ul>
				                    @foreach ($errors->all() as $error)
				                        <li>{{ $error }}</li>
				                    @endforeach
				                </ul>
				            </div>
				        @endif
				        @if (session('status'))
				            <div class="row">
				                <div class="container">
				                    <ul class="alert alert-success">
				                        {{ session('status') }}
				                    </ul>
				                </div>
				            </div>
				        @endif
					</div> 
				</div>
			</div>
			 
			@yield('content')
			
			<footer>
				<div class="row">
					<div class="container">
						<div class="flex_box">
							<div class="col-md-4 copyright">
								© Copyright 2018. Все права защищены
							</div>
							<div class="col-md-4 footer_doc">
								<a href="/reglament">Правила и регламент регистрации</a>
							</div>
							<div class="col-md-4 portfolio_box">
								Разработка сайта<span class="logo_east_tech"></span><a href="http://east-tech.by/">“Восточные технологии”</a>
							</div>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</body>
</html>
