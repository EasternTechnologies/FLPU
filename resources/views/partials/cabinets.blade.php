<ul class="menu_auth">
	<span class="close-mob">
		x
	</span>
    @if(Auth::user() && Auth::user()->isanalyst())
        @if(Request::path() === 'analyst')
            <li class="menu-current">
        @else
            <li>
                @endif
                <a href="/analyst">Управление материалами</a>
            </li>
            <span>|</span>
        @endif

        @if(Auth::user() && Auth::user()->ismanager())
            @if(Request::path() === 'manager')
                <li class="menu-current">
            @else
                <li>
                    @endif
                    <a href="/manager">Управление пользователями</a>
                </li>
                <span>|</span>
            @endif


            @if(Auth::user() && Auth::user()->isanalyst())
                @if(Request::path() === 'stats')
                    <li class="menu-current">
                @else
                    <li>
                        @endif
                        <a href="/stats">Статистика пользователей</a>
                    </li>
                    <span>|</span>
                @endif

                @if(Request::path() === 'cabinet')
                    <li class="menu-current">
                @else
                    <li>
                        @endif
                        @if(Auth::user())
                            <a href="/cabinet/{{\Illuminate\Support\Facades\Auth::user()->id}}">Личный кабинет</a>
                        @endif
                    </li>
                    <span>|</span>
                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
							document.getElementById('logout-form').submit();"> Выход </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
</ul>
