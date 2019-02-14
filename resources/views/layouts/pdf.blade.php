<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @if($report_slug!='search')
    <title>{{$report->types->title}} | БСВТ АНАЛИТИКА</title>
    @else
        <title>Результаты поиска | БСВТ АНАЛИТИКА</title>
    @endif
    <style>
        /*html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video {*/
        /*font-family: "Times New Roman", serif;*/
        /*font-size: 18px;*/
        /*line-height: 1;*/
        /*}*/

        .content {
          padding-left: 100px;
        }

        .pdf_gallery {
          width: 100%;
          padding-top: 30px;
        }

        .pdf_gallery {
          text-align: center;
        }

        .pdf_gallery img {
          max-height: 940px;
          display: block;
          width: 100%;
          margin-bottom: 30px;
        }

        table {
            border-collapse: collapse;
            margin: auto;
        }

        table, th, td {
            border: 1px solid black;
            padding: 4px 4px;
        }
        
        td a {
          word-wrap: break-word;
          word-break: break-all;
        }

        .pdf_title,
        h1 {
            /*width: 43%;*/
            margin-bottom: 20px;
            /*font-weight: normal;*/
            /*line-height: 1.15;*/
            font-size: 18px;
            text-align: center;
            margin-bottom: -3px;
        }

        .title {
            font-family: "Times New Roman", serif;
            font-weight: bold;
            /*margin: 0;*/
        }

        .title_cat {
            font-size: 18px;
            text-indent: inherit;
            text-align: center;
            margin-bottom: -3px;
        }

        .title_sub_cat {
            font-size: 18px;
            text-indent: inherit;
            text-align: center;
            margin-bottom: -3px;
        }

        .out_list_title h3 {
            text-align: center !important;
            /*font-size: 18px;*/
            /*text-indent: 45px;*/
        }

        p {
            /*word-spacing: 5px;*/
            margin: 0;
            text-indent: 45px;
        }

        img {
          margin: 10px 5px;
        }

        @page {
            /*header: page-header;*/
            footer: page-footer;
        }

        .pagination {
          text-align: center;
          font-size: 12px;
        }

    </style>
</head>

<body>
@yield('content')
<htmlpagefooter content-center="{PAGENO}" name="page-footer">
    <p class="pagination">{PAGENO}</p>
</htmlpagefooter>
</body>
</html>
