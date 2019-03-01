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

		    <div class="navbar-default sidebar">       
			    <div class="sidebar-nav navbar-collapse">
            <div class="logo-box">
              <a class="logo-text" href="{{ url('/') }}"> 
                <img src="{{asset('images/logo.png')}}" alt=""/>
              </a>
            </div>

				    <ul class="nav" id="side-menu">
              <li>
                <a href="{{route('tracker.stats.index')}}?pages=visits" class="{{ Session::get('tracker.stats.page') =='visits' ? 'active' : '' }}" ><i class="fa fa-dashboard fa-fw"></i> @lang("tracker::tracker.visits")</a>
              </li>
              <li>
                <a href="{{route('tracker.stats.index')}}?pages=summary" class="{{ Session::get('tracker.stats.page') =='summary' ? 'active' : '' }}"><i class="fa fa-bar-chart-o fa-fw"></i> @lang("tracker::tracker.summary")</a>
              </li>
              <li>
                <a href="{{route('tracker.stats.index')}}?pages=users" class="{{ Session::get('tracker.stats.page') =='users' ? 'active' : '' }}"><i class="fa fa-user fa-fw"></i> @lang("tracker::tracker.users")</a>
              </li>
              <li>
                <a href="{{route('tracker.stats.index')}}?pages=events" class="{{ Session::get('tracker.stats.page') =='events' ? 'active' : '' }}"><i class="fa fa-bolt fa-fw"></i> @lang("tracker::tracker.events")</a>
              </li>
          {{--<li>--}}
            {{--<a href="{{route('tracker.stats.index')}}?page=errors" class="{{ Session::get('tracker.stats.page') =='errors' ? 'active' : '' }}">@lang("tracker::tracker.errors")</a>--}}
          {{--</li>--}}
            </ul>

            <div class="side-menu__buttons">
              <a class="button stats_back" href="#">Назад</a>
              <a class="button excel_link" href="/stats/excel">Экспорт в Excel</a>
              <a class="button change_table_stats" href="#">Общая статистика</a>
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
