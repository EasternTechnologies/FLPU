/**
 * Created by aleksey on 05.02.19.
 */

function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}

let drop_cookie = document.getElementById('drop_cookie');
if (drop_cookie) {
    setCookie('pdfitems',JSON.stringify([]),{'path':'/'});
}

if(getCookie('pdfitems')) {
    var ids = JSON.parse(getCookie('pdfitems'));
}

$(document).on('click','.pdf-checkbox input', function (eo) {
    if(getCookie('pdfitems')) {
        var ids = JSON.parse(getCookie('pdfitems'));
    }
    else {
        var ids = [];
    }

    var val = $(this).val();
    var index = ids.indexOf(val);

    if(index!=-1) {
        ids.splice(index,1);
        if(!ids.length)  $('.show_pdf_search, .show_pdf_search_choose,.show_pdf_for_search').prop('disabled',true);
    }
    else {
        ids.push($(this).val());
        $('.show_pdf_search, .show_pdf_search_choose,.show_pdf_for_search').prop('disabled',false);
    }
    setCookie('pdfitems',JSON.stringify(ids),{'path':'/'});
});


if(ids) {
    if(ids.length)
    for(var i=0;i<ids.length;i++) {
        $('.pdf-checkbox input[value='+ids[i]+']').attr('checked',true);
    }
    else
    {
        $('.show_pdf_search, .show_pdf_search_choose,.show_pdf_for_search').prop('disabled',true);
    }
}
else $('.show_pdf_search, .show_pdf_search_choose,.show_pdf_for_search').prop('disabled',true);


$(document).on('click','.pdf-reset',function () {
    document.cookie = 'pdfitems=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    setCookie('pdfitems',JSON.stringify([]),{'path':'/'});
    var ids = JSON.parse(getCookie('pdfitems'));
});


function make_form(url) {

    if(getCookie('pdfitems')) {
        var ids = JSON.parse(getCookie('pdfitems'));
    }
    else {
        var ids = [];
    }
    if(ids.length) {
        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", url);
        form.setAttribute("target", '_blank');

        for (var i in ids) {
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id[]';
            input.value = ids[i];
            form.appendChild(input);
        }

        var token = $('meta[name=csrf-token]').attr("content");

        input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_token';
        input.value = token;
        form.appendChild(input);

        input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_method';
        input.value = 'POST';
        form.appendChild(input);

        document.body.appendChild(form);
        form.submit();
    }
}

$(document).on('click','.show_pdf_search_choose',function () {
        make_form(document.URL + '/1');
});

$(document).on('click','.show_pdf_search',function () {
        make_form('/pdf_search')
});

$(document).on('click','.show_pdf_for_search',function () {
    make_form('/search/choose');
});

$( document ).ready(function () {
    $('input[data-result="1"]').click();
});


