@extends($stats_layout)

@section('page-contents')
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
          <input class="statistics-form__field" name="dateFrom" type="date">
        </label>
        <label>
          по
          <input class="statistics-form__field" name="dateTo" type="date">
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
          <input class="statistics-form__field" name="search" type="search" placeholder="Поиск по пользователям">
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
        <tr>
          <td>22.02.2019</td>
          <td>Павел Лученок lutchin@gmail.com</td>
          <td>Еженедельный обзор ВПО и ВВИ</td>
          <td>10 старниц</td>
          <td>1 час</td>
          <td>1 час</td>
        </tr>
        <tr>
          <td>22.02.2019</td>
          <td>Павел Лученок lutchin@gmail.com</td>
          <td>Еженедельный обзор ВПО и ВВИ</td>
          <td>10 старниц</td>
          <td>1 час</td>
          <td>1 час</td>
        </tr>
        <tr>
          <td>22.02.2019</td>
          <td>Павел Лученок lutchin@gmail.com</td>
          <td>Еженедельный обзор ВПО и ВВИ</td>
          <td>10 старниц</td>
          <td>1 час</td>
          <td>1 час</td>
        </tr>
        <tr>
          <td>22.02.2019</td>
          <td>Павел Лученок lutchin@gmail.com</td>
          <td>Еженедельный обзор ВПО и ВВИ</td>
          <td>10 старниц</td>
          <td>1 час</td>
          <td>1 час</td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="statistics-pagination pagination">
    <ul class="pagination__list" role="navigation">
      <li class="pagination__item">
        <a class="pagination__link" href="#" aria-label="Назад">‹</a>
      </li> 
      <li class="pagination__item active" aria-current="page">
        <span class="pagination__link">1</span>
      </li> 
      <li class="pagination__item">
        <a class="pagination__link" href="#">2</a>
      </li> 
      <li class="pagination__item">
        <a class="pagination__link" href="#">3</a>
      </li> 
      <li class="pagination__item">
        <a class="pagination__link" href="#" aria-label="Вперёд">›</a>
      </li>
    </ul>
  </div>

	<!-- <table id="table_div" class="display" cellspacing="0" width="100%"></table> -->
@stop

<!-- @section('inline-javascript')
    @include('pragmarx/tracker::_datatables', $datatables_data)
@stop -->
