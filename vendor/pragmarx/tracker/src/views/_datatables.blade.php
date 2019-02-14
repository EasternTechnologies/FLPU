$(document).ready(function() {
    $('#table_div').dataTable( {
        "pageLength": 25,
        "processing": true,
        "serverSide": true,
        "bFilter": false,
        "ajax": "{{$datatables_ajax_route}}",
        "columnDefs": [ {
            "targets": "_all",
            "defaultContent": ""
        } ],
        "language": {
            "processing": "Подождите...",
            "search": "Поиск:",
            "lengthMenu": "Показать _MENU_ записей",
            "info": "Записи с _START_ до _END_ из _TOTAL_ записей",
            "infoEmpty": "Записи с 0 до 0 из 0 записей",
            "infoFiltered": "(отфильтровано из _MAX_ записей)",
            "infoPostFix": "",
            "loadingRecords": "Загрузка записей...",
            "zeroRecords": "Записи отсутствуют.",
            "emptyTable": "В таблице отсутствуют данные",
            "paginate": {
            "first": "Первая",
            "previous": "Предыдущая",
            "next": "Следующая",
            "last": "Последняя"
            },
            "aria": {
            "sortAscending": ": активировать для сортировки столбца по возрастанию",
            "sortDescending": ": активировать для сортировки столбца по убыванию"
            }
            },
        "columns": [
            <?php echo $datatables_columns; ?>
        ]
    } );
} );
