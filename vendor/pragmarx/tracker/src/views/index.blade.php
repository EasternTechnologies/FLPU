@extends($stats_layout)

@section('page-contents')



    <form action="/stats" method="get">

        <select name="sort" id="">
        @foreach($sort_array as $eng=>$rus)

            <option value="{{$eng}}" @if($sort==$eng) selected @endif>{{$rus}}</option>
        @endforeach
        </select>

        от <input type="date" name="start_date">
        до <input type="date" name="end_date">


        показать <select name="show" id="">
            @foreach($show_array as $value)
                <option value="{{$value}}" >{{$value}}</option>
                @endforeach
                </select>

        <input type="text" name="name" placeholder="Иван Иванов" value="{{$name}}">
        <input type="submit" value="Поиск">
</form>

    @foreach($results as $result)
        @if(empty($result->user)) Гость @else
        {{$result->user['name']}} {{$result->user['surname']}}
        @endif
        ||
    @endforeach


    {{----}}
    {{--<a class="btn  @if($sort=='all')  btn-success @else  btn-primary @endif "   href="/stats?sort=all">ALL</a>--}}
    {{--<a @if($sort=='logged')  class="btn btn-success" @else class="btn btn-primary" @endif href="/stats?sort=logged">LOGGED</a>--}}
    {{--<a @if($sort=='unlogged') class="btn btn-success"  @else class="btn btn-primary" @endif href="/stats?sort=unlogged">UNLOGGED</a>--}}

	{{--<table id="table_div" class="display" cellspacing="0" width="100%"></table>--}}
@stop

@section('inline-javascript')
    {{--@include('pragmarx/tracker::_datatables', $datatables_data)--}}
@stop
