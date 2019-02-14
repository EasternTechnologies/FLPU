
<div class="nav_header_other">
    <div class="container">
        <ul class="">
            
            @foreach(\App\ReportType::$data as $link => $title)
                <li class="@if(Request::is($link) || Request::is('analyst/'.$link) || Request::is('analyst/'.$link.'/*')) {{'active'}} @endif">
                    <a href="/report/{{ $link }}" class="nav-link">{{ $title }}</a>
                </li>
            @endforeach
        </ul>
        <span class="close-mob">
        		x
        	</span>
    </div>
</div>
