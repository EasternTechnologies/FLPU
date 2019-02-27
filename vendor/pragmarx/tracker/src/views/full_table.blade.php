<div class="statistics-table">
    <div class="close_stats close_cross">&times;</div>
    <h3>Статистика пользователя {{$results[0]['name']?$results[0]['name']:'Гость'}}</h3>
    <table>
        <thead>
        <tr>
            <th>Дата</th>
            <th>Пользователь</th>
            <th>IP Адрес</th>
            <th>Страна</th>
            <th>Устройство</th>
            <th>Браузер</th>
            <th>Разделы</th>
            <th>Кол-во материалов</th>
            <th>Общее время</th>
            <th>Среднее время</th>
        </tr>
        </thead>
        <tbody>
        @foreach($results as $result)
            <tr>
                <td>{{$result['date']}}</td>
                <td class="stats_more_info">
                    @if(empty($result['name']))
                        Гость
                    @else
                        {!! $result['name'] !!}
                    @endif
                </td>
                <td>{{$result['ip']}}</td>
                <td>{!! $result['country'] !!}</td>
                <td>{{$result['device']}}</td>
                <td>{{$result['browser']}}</td>
                <td>
                    @forelse($result['categories'] as $cat)
                        {{$cat}} <br>
                    @empty
                        Нету
                    @endforelse
                </td>
                <td class="stats_paths">{{$result['count']}} <br>
                    <div class="">
                        @foreach($result['paths'] as $path)
                            <a href="{{$path}}">{{$path}}</a> <br>
                        @endforeach
                    </div>
                </td>
                <td>{{$result['sum']}}</td>
                <td>{{$result['average']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<span class="button butt_close_stats close_stats">Закрыть</span>