<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@lang("tracker::tracker.tracker_title")</title>

	<script src="{{ $stats_template_path }}/vendor/jquery/jquery.min.js"></script>

	@yield('required-scripts-top')

    <!-- Core CSS - Include with every page -->
    <link href="{{ $stats_template_path }}/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ $stats_template_path }}/vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="{{ $stats_template_path }}/vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Page-Level Plugin CSS - Dashboard -->
    <link href="{{ $stats_template_path }}/vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="{{ $stats_template_path }}/dist/css/sb-admin-2.css" rel="stylesheet">

    <link href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.css" rel="stylesheet">

	<link
		rel="stylesheet"
		type="text/css"
		href="https://github.com/downloads/lafeber/world-flags-sprite/flags16.css"
	/>
</head>

<body>
    <div class="row_top">
      <div class="user_rolles">Аналитик : Лученок Павел</div> 
      <ul class="menu_auth">
        <li>
          <a href="/stats">Статистика</a>
        </li> 
        <span>|</span>
        <li>
          <a href="/report">Управление материалами</a>
        </li>      
        <span>|</span>
        <li>
          <a href="/cabinet/30">Личный кабинет</a>
        </li> 
        <span>|</span> 
        <li>
          <a href="https://analytics.bsvt.by/logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> Выход </a> 

          <form id="logout-form" action="https://analytics.bsvt.by/logout" method="POST" style="display: none;">
            <input name="_token" type="hidden" value="bO57tRRRTVS5iwMVBiSngjGZyC5KLjUhyHQQq35N">
          </form>
        </li>
      </ul>
    </div>

    @yield('body')

    <!-- Core Scripts - Include with every page -->
    <script src="{{ $stats_template_path }}/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{ $stats_template_path }}/vendor/metisMenu/metisMenu.min.js"></script>

    <script src="{{ $stats_template_path }}/vendor/raphael/raphael.min.js"></script>
    <script src="{{ $stats_template_path }}/vendor/morrisjs/morris.min.js"></script>

    <!-- SB Admin Scripts - Include with every page -->
    <script src="{{ $stats_template_path }}/js/sb-admin-2.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.7.0/moment.min.js"></script>

    <script src="//cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js"></script>

	@yield('required-scripts-bottom')

    <script>
	    @yield('inline-javascript')
    </script>
    <!-- <footer>
      <div class="copyright">© Copyright 2018. Все права защищены</div> 
      <div class="footer_doc">
        <a href="/reglament">Правила и регламент регистрации</a>
      </div> 
      <div class="portfolio_box">
        Разработка сайта<span class="logo_east_tech"></span>
        <a href="http://east-tech.by/">“Восточные технологии”</a>
      </div>
    </footer> -->
</body>

</html>
