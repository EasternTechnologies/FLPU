$(function() {
    $('#side-menu').metisMenu();
});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        var topOffset = 50;
        var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    // var element = $('ul.nav a').filter(function() {
    //     return this.href == url;
    // }).addClass('active').parent().parent().addClass('in').parent();
    var element = $('ul.nav a').filter(function() {
        return this.href == url;
    }).addClass('active').parent();

    while (true) {
        if (element.is('li')) {
            element = element.parent().addClass('in').parent();
        } else {
            break;
        }
    }


    $('.stats_more_info').click(function () {

        // console.log($('.ds_stats').val());

        var id = $(this).attr('data-id');
        var data = {
          ajax:true,
            id:id,
            start_date: $('.ds_stats').val(),
            end_date: $('.de_stats').val()
        };

       $.ajax({
           url:'/stats',
           method:'get',
           data:data,
           success:function (result) {
               //console.log(result);
               $('.popup_stats_form').html(result);
               $('.popup_stats').show();
           }
       })
    });
    
    $(document).on("click", ".close_stats", function(){
        $('.popup_stats').hide();
    });

    $(document).on("click", ".stats_paths", function(event){
      $(this).toggleClass('active');
    });


    $(".change_table_stats").click(function () {
        if($(this).hasClass('active')){
                        $('.change_table_stats').removeClass('active').text('Общая статистика').blur();
                        $('.hidden_column').removeClass('active');
                        $('.excel_link').attr('href','/stats/excel');
        }
        else {
                        $('.change_table_stats').addClass('active').text('Назад').blur();
                        $('.hidden_column').addClass('active');
                        $('.excel_link').attr('href','/stats/excelv2');
       }
    })


});
