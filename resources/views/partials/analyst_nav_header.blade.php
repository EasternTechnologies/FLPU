<div class="nav_header_other">
    <div class="container">
        <span class="close-mob">
        		x
        	</span>
        <ul class="">
            
            @foreach(\App\ReportType::$data as $link => $title)
                <li class="@if(Request::is($link) || Request::is('analyst/'.$link) || Request::is('analyst/'.$link.'/*')) {{'active'}} @endif">
                    <a href="/analyst/{{ $link }}" class="nav-link">{{ $title }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</div>