@extends('layouts.pdf')

@section('content')
	<style>

		table {
			font-family: "Times New Roman", serif;
			font-size: 15px;
			line-height: 1.3;
		}

		h4 {
			margin: 0;
			padding-right: 50px;
			font-size: 15px;
			font-weight: normal;
			text-align: right;
		}

		table {
			width: 100%;
			border-collapse: collapse;
		}

		th {
			background-color: lightgray;
			border: 1px solid #000000;
		}

		td {
			vertical-align: top;
			border: 1px solid #000000;
			word-break: break-all;
			word-wrap: break-word;
		}

		p {
			word-break: break-all;
			word-wrap: break-word;
			margin: 0;
		}

		a {
			word-break: break-all;
			word-wrap: break-word;
			/* display: block; */
		}

		caption {
			width: 100%;
			margin-bottom: 10px;
			caption-side: left;
			font-size: 15px;
			text-align: justify;
		}

		.center {
			text-align: center;
		}
	</style>

	<!-- <h4>Приложение</h4> -->
	<p style="width: 64%; margin-bottom: 10px; caption-side: left; text-align: justify; text-indent: 0"><strong>Основные международные выставки оборонной промышленности, вооружений и военной техники, продукции военного назначения, планируемые к проведению в {{ date("Y",$report->date_start) }} году</strong></p>
	<table>

		<thead>
		<tr>
			<th width="4%">№ п/п</th>
			<th width="37%">Название выставки</th>
			<th width="8%">Дата</th>
			<th width="15%">Место</th>
			<th width="36%">Тематика выставки</th>
		</tr>
		</thead>
		<tbody>
		@foreach($items as $item)
		<tr>
			<td>{{ ++$loop->index }}.</td>
			<td>  
        
				<?php echo strip_tags($item['title'], "<p><a>");?>
			</td>
			<td class="center">
				{{ date("d",$item['date_start']) }} - {{ date("d",$item['date_end']) }} <br> {{ Helper::getMonthText(date("m",$item['date_end'])) }}
			</td>
			<td>
        		<?php echo strip_tags($item['place'], "<p><a>");?>
			</td>
			<td>
        		<?php echo strip_tags($item['description'], "<p><a>");?>
			</td>
		</tr>
		@endforeach
		</tbody>
	</table>





@endsection