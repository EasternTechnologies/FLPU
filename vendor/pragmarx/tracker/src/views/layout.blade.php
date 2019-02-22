@extends('pragmarx/tracker::html')

@section('body')
    <div id="wrapper">

	    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">@lang("tracker::tracker.toggle_navigation")</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{route('tracker.stats.index')}}">@lang("tracker::tracker.tracker_title")</a>
        </div>
        <!-- /.navbar-header -->

		    <!-- <ul class="nav navbar-top-links navbar-right navbar-nav">
          <li {{ Session::get('tracker.stats.days') == '0' ? 'class="active"' : '' }}>
            <a href="{{route('tracker.stats.index')}}?days=0">@lang("tracker::tracker.today")</a>
          </li>

          <li {{ Session::get('tracker.stats.days') == '1' ? 'class="active"' : '' }}>
            <a href="{{route('tracker.stats.index')}}?days=1">@choice("tracker::tracker.no_days",1, ["count" => 1])</a>
          </li>

          <li {{ Session::get('tracker.stats.days') == '7' ? 'class="active"' : '' }}>
            <a href="{{route('tracker.stats.index')}}?days=7">@choice("tracker::tracker.no_days",7, ["count" => 7])</a>
          </li>

          <li {{ Session::get('tracker.stats.days') == '30' ? 'class="active"' : '' }}>
            <a href="{{route('tracker.stats.index')}}?days=30">@choice("tracker::tracker.no_days",30, ["count" => 30])</a>
          </li>

          <li {{ Session::get('tracker.stats.days') == '365' ? 'class="active"' : '' }}>
            <a href="{{route('tracker.stats.index')}}?days=365">@choice("tracker::tracker.no_years",1, ["count" => 1])</a>
          </li>
        </ul> -->
            <!-- /.navbar-top-links -->

		    <div class="navbar-default sidebar" role="navigation">
			    <div class="sidebar-nav navbar-collapse">
            <div class="logo-box">
              <a class="logo-text" href="{{ url('/') }}"> 
                <img src="{{asset('images/logo.png')}}" alt=""/>
              </a>
            </div>

				    <ul class="nav" id="side-menu">
              <li>
                <a href="{{route('tracker.stats.index')}}?page=visits" class="{{ Session::get('tracker.stats.page') =='visits' ? 'active' : '' }}" ><i class="fa fa-dashboard fa-fw"></i> @lang("tracker::tracker.visits")</a>
              </li>
              <li>
                <a href="{{route('tracker.stats.index')}}?page=summary" class="{{ Session::get('tracker.stats.page') =='summary' ? 'active' : '' }}"><i class="fa fa-bar-chart-o fa-fw"></i> @lang("tracker::tracker.summary")</a>
              </li>
              <li>
                <a href="{{route('tracker.stats.index')}}?page=users" class="{{ Session::get('tracker.stats.page') =='users' ? 'active' : '' }}"><i class="fa fa-user fa-fw"></i> @lang("tracker::tracker.users")</a>
              </li>
              <li>
                <a href="{{route('tracker.stats.index')}}?page=events" class="{{ Session::get('tracker.stats.page') =='events' ? 'active' : '' }}"><i class="fa fa-bolt fa-fw"></i> @lang("tracker::tracker.events")</a>
              </li>
          {{--<li>--}}
            {{--<a href="{{route('tracker.stats.index')}}?page=errors" class="{{ Session::get('tracker.stats.page') =='errors' ? 'active' : '' }}">@lang("tracker::tracker.errors")</a>--}}
          {{--</li>--}}
            </ul>

            <div class="side-menu__buttons">
              <a class="button" href="#">Назад</a>
              <a class="button" href="#">Экспорт в Excel</a>
              <a class="button" href="#">Общая статистика</a>
            </div>
            <!-- /#side-menu -->
            </div>
            <!-- /.sidebar-collapse -->
          </div>
          <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <div class="row">
              <div class="col-lg-12">
                  <h2 class="page-header">{{$title}}</h2>
              </div>

	            <div class="col-lg-12">
                <div class="statistics-filter">
                  <form class="statistics-form">
                    <p class="statistics-form__block">
                      <label>
                        Тип пользователя
                        <select class="statistics-form__field" name="status">
                          <option value="1">Все пользователи</option>
                          <option value="2">Админ</option>
                          <option value="3">Пользователь</option>
                        </select>
                      </label>
                    </p>

                    <p class="statistics-form__block statistics-form__block--date">
                      <label>
                        Период с
                        <input class="statistics-form__field" type="date" name="dateFrom">
                      </label>
                      <label>
                        по
                        <input class="statistics-form__field" type="date" name="dateTo">
                      </label>
                    </p>

                    <p class="statistics-form__block">
                      <label>
                        Показать
                        <select class="statistics-form__field" name="count">
                          <option value="100">100</option>
                          <option value="50">50</option>
                          <option value="25">25</option>
                        </select>
                        записей
                      </label>
                    </p>
                    
                    <p class="statistics-form__block statistics-form__block--search">
                      <label>
                        <input class="statistics-form__field" type="search" name="search" placeholder="Поиск по пользователям">
                      </label>
                      <button type="submit" aria-label="Отправить форму"></button>
                    </p>
                  </form>
                </div>
		            @yield('page-contents')
	            </div>
            </div>

            <div class="row">
              <div class="col-lg-12">
                @yield('page-secondary-contents')
              </div>
            </div>

            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
@stop
