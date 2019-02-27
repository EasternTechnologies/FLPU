@extends($stats_layout)

@section('page-contents')
  <div class="statistics-filter"> 
    <form class="statistics-form" action="/stats" method="get">
      <p class="statistics-form__block">
        <label>
          Тип пользователя

          <select class="statistics-form__field" name="sort">
              @foreach($sort_array as $eng=>$rus)
                  <option value="{{$eng}}" @if(app('request')->input('sort')==$eng) selected @endif>{{$rus}}</option>
              @endforeach
          </select>
        </label>
      </p>

      <p class="statistics-form__block statistics-form__block--date">
        <label>
          Период с
          <input class="statistics-form__field" name="start_date" type="date"  value="{{app('request')->input('start_date')}}">
        </label>
        <label>
          по
          <input class="statistics-form__field" name="end_date" type="date" value="{{app('request')->input('end_date')}}">
        </label>
      </p>

      <p class="statistics-form__block">
        <label>
          Показать
          <select class="statistics-form__field" name="show">
              @foreach($show_array as $value)
                  <option value="{{$value}}" @if(app('request')->input('show')==$value) selected @endif>{{$value}}</option>
              @endforeach
          </select>
          записей
        </label>
      </p>
      
      <p class="statistics-form__block statistics-form__block--search">
        <label>
          <input class="statistics-form__field" name="name" type="search" placeholder="Поиск по пользователям" value="{{app('request')->input('name')}}">
        </label>
        <button type="submit" aria-label="Отправить форму"></button>
      </p>
    </form>
  </div>

  <input type="hidden" class="ds_stats" value="{{app('request')->input('start_date')}}">
  <input type="hidden" class="de_stats" value="{{app('request')->input('end_date')}}">

  <div class="popup_stats">
    <div class="popup_back"></div>
    <div class="popup_stats_form">

    </div>
  </div>


  <div class="statistics-table">
    <table>
      <thead>
        <tr>
          <th>Дата</th>
          <th>Пользователь</th>
          <th class="hidden_column">IP Адрес</th>
          <th class="hidden_column">Страна</th>
          <th class="hidden_column">Устройство</th>
          <th class="hidden_column">Браузер</th>
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
            <td data-id="{{$result['id']}}" class="stats_more_info">
              @if(empty($result['name']))
                Гость
              @else
                {!! $result['name'] !!}
              @endif
            </td>

            <td class="hidden_column">{{$result['ip']}}</td>
            <td class="hidden_column">{!! $result['country'] !!}</td>
            <td class="hidden_column">{{$result['device']}}</td>
            {{--{{dump($sessions[0]->agent)}}--}}
            <td class="hidden_column">{{
            $result['browser']
            }}</td>

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

  {{$results->links()}}


  {{--@if($pages_count>1)--}}
  {{--<div class="statistics-pagination pagination">--}}
    {{--<ul class="pagination__list" role="navigation">--}}
      {{--@if($current_page>1)--}}
      {{--<li class="pagination__item">--}}
        {{--<a class="pagination__link" href="#" aria-label="Назад">‹</a>--}}
      {{--</li>--}}
      {{--@endif--}}

      {{--@for($i=0; $i<$pages_count;$i++)--}}
        {{--<li class="pagination__item @if($current_page==($i+1)) active @endif"--}}
            {{--@if($current_page==($i+1)) aria-current="page" @endif>--}}
          {{--<a class="pagination__link" href="#">{{$i+1}}</a>--}}
        {{--</li>--}}
      {{--@endfor--}}

      {{--<li class="pagination__item active" aria-current="page">--}}
        {{--<span class="pagination__link">1</span>--}}
      {{--</li>--}}
      {{--<li class="pagination__item">--}}
        {{--<a class="pagination__link" href="#">2</a>--}}
      {{--</li>--}}
      {{--<li class="pagination__item">--}}
        {{--<a class="pagination__link" href="#">3</a>--}}
      {{--</li>--}}

        {{--@if($current_page<$pages_count)--}}
      {{--<li class="pagination__item">--}}
        {{--<a class="pagination__link" href="#" aria-label="Вперёд">›</a>--}}
      {{--</li>--}}
          {{--@endif--}}
    {{--</ul>--}}
  {{--</div>--}}
{{--@endif--}}
	<!-- <table id="table_div" class="display" cellspacing="0" width="100%"></table> -->
@stop

<!-- @section('inline-javascript')
    {{--@include('pragmarx/tracker::_datatables', $datatables_data)--}}
@stop -->



