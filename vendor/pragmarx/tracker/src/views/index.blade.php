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

  <div class="statistics-table">
    <table>
      <thead>
        <tr>
          <th>Дата</th>
          <th>Пользователь</th>
          <th>Разделы</th>
          <th>Кол-во материалов</th>
          <th>Общее время</th>
          <th>Среднее время</th>
        </tr>
      </thead>
      <tbody>
      @foreach($results as $date=>$users)
        @foreach($users as $user_id=>$sessions)
          <tr>
              <td>{{$date}}</td>
              <td>
                @if(empty($user_id))
                  Гость
                @else
                      {{$users_array->where('id',$user_id)->first()->name}} {{$users_array->where('id',$user_id)->first()->surname}}
                  <br>{{$users_array->where('id',$user_id)->first()->email}}
                @endif
              </td>
            <td>

            @forelse(Helper::logsInfo($sessions)[0] as $cat)
              {{$cat}} <br>
              @empty
                Нету
              @endforelse
            </td>
              <td>{{Helper::logsCount($sessions)}}</td>
              <td>{{gmdate('H:i:s',Helper::logsInfo($sessions)[1])}}</td>
              <td>{{gmdate('H:i:s',Helper::logsInfo($sessions)[2])}}</td>
          </tr>
        @endforeach
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



