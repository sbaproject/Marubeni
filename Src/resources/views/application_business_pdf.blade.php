<!DOCTYPE html>
<html lang="vi">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Document</title>
	<style type="text/css">
		@font-face {
			font-family: 'notosans-bold';
			src: url({{ storage_path('fonts\NotoSans-Bold.ttf')}}) format("truetype");
			font-weight: 700;
			font-style: normal;
		}

		@font-face {
			font-family: 'notosans-bolditalic';
			src: url({{ storage_path('fonts\NotoSans-BoldItalic.ttf')}}) format("truetype");
			font-weight: 700;
			font-style: italic;
		}

		@font-face {
			font-family: 'notosans-italic';
			src: url({{ storage_path('fonts\NotoSans-Italic.ttf')}}) format("truetype");
			font-weight: 400;
			font-style: italic;
		}

		@font-face {
			font-family: 'notosans-regular';
			src: url({{ storage_path('fonts\NotoSans-Regular.ttf')}}) format("truetype");
			font-weight: 400;
			font-style: normal;
		}

		body {
			font-family: "notosans-regular, notosans-bold, notosans-bolditalic,notosans-italic";
			font-size: 10px;
		}

		table {
			width: 100%;
			border-collapse: collapse;
			border-spacing: 0;
		}

		table td {
			border: 1px solid #000;
			vertical-align: baseline;
		}

		#tb-top td {
			border: none;
		}

		#tb-top td.logo .main {
			font-weight: bold;
			font-size: 28px;
		}

		#tb-top td.logo .sub {
			margin-top: -10px;
			font-weight: bold;
		}

		#title-header div {
			font-size: 20px;
			text-align: center;
			font-weight: bold;
		}

		#title-header .en {
			text-decoration: underline;
		}

		#title-header .vi {
			margin-top: -8px;
		}

		#info-header {
			/* float: right; */
			/* text-align: right; */
			margin-bottom: 10px;
		}

		#info-header table {
			/* width: 350px; */
		}

		#info-header table td {}

		#info-header table td:nth-child(1) {
			width: 70%;
			border: none;
			text-align: right;
			padding-right: 40px
		}

		#info-header table tr td:nth-child(2) {
			border-top: 0px;
			border-left: 0px;
			border-right: 0px
		}

		table tr td.f {
			width: 20%;
		}

		td.days {
			text-align: right;
			vertical-align: middle;
			padding-right: 10px;
		}

		td.hours {
			text-align: left;
			vertical-align: middle;
		}

		.tb-note {
			border-collapse: unset;
			border-spacing: 0;
		}

		.tb-note td {
			border: none;
		}

		.note-bold {
			font-weight: bold;
			margin-left: 5px;
		}

		.un {
			text-decoration: underline;
		}
		td.data{
			vertical-align: middle;
			padding-left: 5px;
		}
	</style>
</head>

<body>
	<!-- TOP HEADER -->
	<table id="tb-top">
		<tr>
			<td class="logo">
				<div class="main">Marubeni</div>
				<div class="sub">Marubeni Vietname Company Limited</div>
			</td>
			<td style="text-align: right;text-decoration: underline;">
				Ver. MVN-1601
			</td>
		</tr>
	</table>

	<!-- TITLE HEADER -->
	<div id="title-header">
		<div class="en">BUSINESS TRIP ASSIGNMENT</div>
		<div class="vi">GIẤY ĐIỀU ĐỘNG ĐI CÔNG TÁC</div>
	</div>

	<!-- INFO HEADER -->
	<div id="info-header">
		<table>
			<tr>
				<td>Applied Date/ Ngày:</td>
				<td>
					@if (empty($application))
					{{ Carbon\Carbon::now()->format('d/m/Y') }}
					@else
					{{ date('d/m/Y', strtotime($application->created_at)) }}
					@endif
				</td>
			</tr>
			<tr>
				<td>Full Name/ Họ tên:</td>
				<td>{{ $inputs['applicant']->name }}</td>
			</tr>
			<tr>
				<td>Sec Code/ Mã bộ phận:</td>
				<td>{{ $inputs['applicant']->department->name }}</td>
			</tr>
		</table>
	</div>

	<table style="clear: both;">
		<tbody>
			<tr>
				<td class="f">
					<div>Trip Destination</div>
					<div>Nơi công tác</div>
				</td>
				<td colspan="4" class="data">
					{{ $inputs['destinations'] }}
				</td>
			</tr>
			<tr>
				<td class="f">
					<div>Subject</div>
					<div>Nội dung công tác</div>
				</td>
				<td colspan="4" class="data">
					{{ $inputs['comment'] }}
				</td>
			</tr>
			<tr>
				<td class="f">
					<div>Period</div>
					<div>Thời gian</div>
				</td>
				<td class="data" style="width: 8%;">
					<div>From:</div>
					<div>Từ</div>
				</td>
				<td style="width: 32%;" class="data">
					@if ($inputs['trip_dt_from'] !== null)
						{{ date('d/m/Y', strtotime($inputs['trip_dt_from'])) }}
					@endif
				</td>
				<td class="data"style="width: 8%;">
					<div>To:</div>
					<div>Đến</div>
				</td>
				<td style="width: 32%;" class="data">
					@if ($inputs['trip_dt_to'] !== null)
						{{ date('d/m/Y', strtotime($inputs['trip_dt_to'])) }}
					@endif
				</td>
			</tr>
			{{-- {{ dd(count($inputs['trans'])) }} --}}
			<tr>
				<td class="f" rowspan="{{ count($inputs['trans']) + 1 }}" style="vertical-align: middle">
					<div>Itinerary &#38; Transportation</div>
					<div>Hành trình &#38; phương tiện</div>
				</td>
				<td colspan="2" style="vertical-align: middle;text-align: center;">
					<div>Departure - Arrival</div>
					<div>Hành trình</div>
				</td>
				<td colspan="2" style="vertical-align: middle;text-align: center;">
					<div>Flight No. &#38; Schedule</div>
					<div>Chuyến bay & Lịch trình</div>
				</td>
			</tr>
			@foreach ($inputs['trans'] as $item)
			<tr>
				<td colspan="2" class="data" style="height: 40px">
					{{-- at least one empty row --}}
					@if (empty($item['departure']) && empty($item['arrive']) && empty($item['method']))
					<div style="height: 20px"></div>
					@else
					{{ $item['departure'] }} - {{ $item['arrive'] }}
					@endif
				</td>
				<td colspan="2" class="data">
					{{ $item['method'] }}
				</td>
			</tr>
			@endforeach
			<tr>
				<td class="f">
					<div>Accommodation</div>
					<div>Chỗ ở</div>
				</td>
				<td colspan="4" class="data">
					{{ $inputs['accommodation'] }}
				</td>
			</tr>
			<tr>
				<td class="f">
					<div>Accompany</div>
					<div>Người đi cùng</div>
				</td>
				<td colspan="4" class="data">
					{{ $inputs['accompany'] }}
				</td>
			</tr>
			<tr>
				<td class="f">
					<div>Expenses to be borne by</div>
					<div>Chi phí chịu bởi</div>
				</td>
				<td colspan="4" class="data">
					{{ $inputs['borne_by'] }}
				</td>
			</tr>
		</tbody>
	</table>
	<p></p>
	<table>
		<tr>
			<td class="f" rowspan="2" style="text-align: center;height: 160px;">
				<div>President or Executive VP</div>
				<div>TGD hay PTGD</div>
			</td>
			<td style="text-align: center; vertical-align: middle;width:8% ;">BA</td>
			<td style="text-align: center;width:24% ;">GM/ Giám đốc</td>
			<td style="text-align: center;width:24% ;">Manager/ Trưởng phòng</td>
			<td style="text-align: center;width:24% ;">ADMI. IC/ NV Hành chính</td>
		</tr>
		<tr>
			<td style="text-align: center; vertical-align: middle;width:8%;">Dept.</td>
			<td style="text-align: center;width:24% ;">GM/ Giám đốc</td>
			<td style="text-align: center;width:24% ;">Manager/ Trưởng phòng</td>
			<td style="text-align: center;width:24% ;">Applicant/ Người đề nghị</td>
		</tr>
	</table>
	@isset($inputs['lastApproval'])
	<div style="margin-top:10px">
			<div style="display: inline-block;vertical-align: middle;">
				<div>Approved by : {{ $inputs['lastApproval']->user_name }}</div>
				<div>Approved at : {{ date('d/m/Y H:i', strtotime($inputs['lastApproval']->created_at)) }}</div>
			</div>
			<div style="
			    display: inline-block;
			    border: 2px solid red;
				border-radius:10px;
			    vertical-align: middle;
			    padding: 10px;
				margin-top:1px;
			    color: red;
			    height: inherit;
			    font-weight: bold;
			">
				APPROVED
			</div>
		</div>
	@endisset
</body>
</html>