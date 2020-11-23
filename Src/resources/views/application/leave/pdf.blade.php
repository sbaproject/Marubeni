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
			/* border-spacing: 0; */
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
			margin-top: -10px;
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
			width: 24%;
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
				Ver. MVN-160
			</td>
		</tr>
	</table>

	<!-- TITLE HEADER -->
	<div id="title-header">
		<div class="en">Application for leave</div>
		<div class="vi">Đơn xin nghỉ phép</div>
	</div>

	<!-- INFO HEADER -->
	<div id="info-header">
		<table>
			<tr>
				<td>Applied Date/ Ngày:</td>
				<td></td>
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

	<table style="clear: both">
		<tbody>
			<tr>
				<td class="f" style="font-weight: bold">
					<div>Code of Leave (*)</div>
					<div>Mã xin nghỉ phép (*)</div>
				</td>
				<td colspan="6" class="data">
					@php
						if($inputs['code_leave'] !== 'empty'){
							$codeLeave = array_search($inputs['code_leave'], config('const.code_leave'));
							echo $codeLeave .': '.__('label.leave.code_leave.'.$codeLeave);
						}
					@endphp
				</td>
			</tr>
			<tr>
				<td class="f" style="font-weight: bold">
					<div>Reason for leave</div>
					<div>Lý do xin nghỉ phép</div>
				</td>
				<td colspan="6" class="data" style="word-wrap: break-word;">{{ $inputs['reason_leave'] }}</td>
			</tr>
			<tr>
				<td class="f">
					<div>&#60;In case of code SL&#62;</div>
					<div style="font-weight: bold">Choose the type of leave to switch to</div>
					<div>&#60;Trường hợp sử dụng mã SL&#62;</div>
					<div style="font-weight: bold">Chọn hình thức xin nghỉ phép</div>
				</td>
				<td colspan="6">
					<div style="font-weight: bold;text-decoration: underline">
						For code "SL" only/ Dùng cho mã "SL"
					</div>
					<div style="margin-left: 50px; margin-top: 10px">
						<input type="checkbox" style=""
							@if($inputs['paid_type'] !== null && $inputs['paid_type'] == config('const.paid_type.AL')) checked @endif>
						<div style="margin-top:-20px;margin-left:20px">
							<div>Annual leave</div>
							<div>Nghỉ phép theo chế độ</div>
						</div>
					</div>
					<div style="float: right; margin-top:-45px; margin-right:80px">
						<input type="checkbox" style="display: inline;"
							@if($inputs['paid_type'] !== null && $inputs['paid_type'] == config('const.paid_type.UL')) checked @endif>
						<div style="margin-top:-20px;margin-left:20px">
							<div>Unpaid leave</div>
							<div>Nghỉ phép không hưởng lương</div>
						</div>
					</div>
					<div style="clear: both"></div>
				</td>
			</tr>
			<tr>
				<td class="f" rowspan="2">
					<div>&#60;In case of day-leave&#62;</div>
					<div style="font-weight: bold">Date of Leave</div>
					<div>&#60;Trường hợp nghỉ theo ngày&#62;</div>
					<div style="font-weight: bold">Ngày nghỉ</div>
				</td>
				<td rowspan="2" style="text-align: center;vertical-align: middle">
					<div>From-To</div>
					<div>Từ-Đến</div>
				</td>
				<td colspan="3" class="data">
					@if ($inputs['date_from'] !== null)
						{{ date('d/m/Y', strtotime($inputs['date_from'])) }}
					@endif
				</td>
				<td rowspan="2" style=" text-align: center;vertical-align: middle">
					<div>No. of Days</div>
					<div>Số ngày nghỉ</div>
				</td>
				<td rowspan="2"></td>
			</tr>
			<tr>
				<td colspan="3" class="data">
					@if ($inputs['date_to'] !== null)
						{{ date('d/m/Y', strtotime($inputs['date_to'])) }}
					@endif
				</td>
			</tr>
			<tr>
				<td class="f" rowspan="2">
					<div>&#60;In case of hour-leave&#62;</div>
					<div style="font-weight: bold">Time of Leave</div>
					<div>&#60;Trường hợp nghỉ theo giờ&#62;</div>
					<div style="font-weight: bold">Thời gian nghỉ</div>
				</td>
				<td rowspan="2" style="width:8%; text-align: center;vertical-align: middle">
					<div>Date</div>
					<div>Ngày</div>
				</td>
				<td rowspan="2" style="width:18%" class="data">
					@if ($inputs['time_day'] !== null)
						{{ date('d/m/Y', strtotime($inputs['time_day'])) }}
					@endif
				</td>
				<td rowspan="2" style="width: 8%;text-align: center;vertical-align: middle">
					<div>From-To</div>
					<div>Từ-Đến</div>
				</td>
				<td rowspan="2" style="width:18%" class="data">
					@if ($inputs['time_from'] !== null && $inputs['time_to'] !== null)
						{{ date('H:i', strtotime($inputs['time_from'])) }}
						-
						{{ date('H:i', strtotime($inputs['time_to'])) }}
					@endif
					@if ($inputs['time_from'] !== null && $inputs['time_to'] === null)
						{{ date('H:i', strtotime($inputs['time_from'])) }}
						-
						{{ date('H:i', strtotime($inputs['time_from'])) }}
					@endif
					@if ($inputs['time_from'] === null && $inputs['time_to'] !== null)
						{{ date('H:i', strtotime($inputs['time_to'])) }}
						-
						{{ date('H:i', strtotime($inputs['time_to'])) }}
					@endif
				</td>
				<td rowspan="2" style="width:12%;text-align: center;vertical-align: middle">
					<div>No. of Hours</div>
					<div>Số giờ nghỉ</div>
				</td>
				<td rowspan="2" style="width:12%"></td>
			</tr>
			<tr></tr>
			<tr>
				<td>
					<div>In case of maternity leave</div>
					<div>(tentative)</div>
				</td>
				<td style="text-align: center">
					<div>From-To</div>
					<div>Từ-Đến</div>
				</td>
				<td colspan="3" class="data">
					@if ($inputs['maternity_from'] !== null && $inputs['maternity_to'] !== null)
						{{ date('d/m/Y', strtotime($inputs['maternity_from'])) }}
						-
						{{ date('d/m/Y', strtotime($inputs['maternity_to'])) }}
					@endif
					@if ($inputs['maternity_from'] !== null && $inputs['maternity_to'] === null)
						{{ date('d/m/Y', strtotime($inputs['maternity_from'])) }}
						-
						{{ date('d/m/Y', strtotime($inputs['maternity_from'])) }}
					@endif
					@if ($inputs['maternity_from'] === null && $inputs['maternity_to'] !== null)
						{{ date('d/m/Y', strtotime($inputs['maternity_to'])) }}
						-
						{{ date('d/m/Y', strtotime($inputs['maternity_to'])) }}
					@endif
				</td>
				<td style="text-align: center">
					<div>No. of</div>
					<div>Months</div>
				</td>
				<td></td>
			</tr>
			<tr>
				<td class="f" rowspan="4" style="vertical-align: middle">
					<div>&#60;For AL application only&#62;</div>
					<div style="font-weight: bold">Annual Leave Status</div>
					<div>&#60;Dùng xin nghỉ phép&#62;</div>
					<div style="font-weight: bold">Tình trạng nghỉ phép</div>
				</td>
				<td colspan="3">
					<div>AL entitled this year</div>
					<div>Số ngày phép được nghỉ trong năm</div>
				</td>
				<td class="days" style="border-right: 0px">
					{{ $inputs['entitled_days'] }} days/ ngày
				</td>
				<td style="border-left: 0px;border-right: 0px"></td>
				<td class="hours" style="border-left: 0px"></td>
			</tr>
			<tr>
				<td colspan="3">
					<div>AL used this year</div>
					<div>Số ngày phép đã sử dụng trong năm</div>
				</td>
				<td class="days" style="border-right: 0px">
					{{ $inputs['used_days'] }} days/ ngày
				</td>
				<td style="border-left: 0px;border-right: 0px"></td>
				<td class="hours" style="border-left: 0px">
					{{ $inputs['used_hours'] }} hours/ giờ
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<div>AL to take this time</div>
					<div>Số ngày phép xin nghỉ</div>
				</td>
				<td class="days" style="border-right: 0px">
					{{ $inputs['days_use'] }} days/ ngày
				</td>
				<td style="border-left: 0px;border-right: 0px"></td>
				<td class="hours" style="border-left: 0px">
					{{ $inputs['times_use'] }} hours/ giờ
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<div>Remaining AL</div>
					<div>Số ngày phép chưa sử dụng</div>
				</td>
				<td class="days" style="border-right: 0px">
					{{ $inputs['remaining_days'] }} days/ ngày
				</td>
				<td style="border-left: 0px;border-right: 0px"></td>
				<td class="hours" style="border-left: 0px">
					{{ $inputs['remaining_hours'] }} hours/ giờ
				</td>
			</tr>
		</tbody>
	</table>
	<div style="margin-top: 10px">
		<span class="note-bold" style="margin-left: 0px">(*) <span class="un">AL</span>:</span>Annual Leave
		<span class="note-bold"><span class="un" style="margin-left: 5px">UL</span>:</span>Unpaid Leave
		<span class="note-bold"><span class="un" style="margin-left: 20px">CL</span>:</span>Compassionate Leave
		<span class="note-bold"><span class="un" style="margin-left: 10px">WL</span>:</span>Wedding Leave
		<span class="note-bold"><span class="un" style="margin-left: 10px">PL</span>:</span>Periodic Leave
		<span class="note-bold"><span class="un" style="margin-left: 40px">ML</span>:</span>Maternity Leave
	</div>
	<div>
		<span class="note-bold" style="margin-left: 0px">(*) <span class="un">AL</span>:</span>Nghỉ phép
		<span class="note-bold"><span class="un" style="margin-left: 10px">UL</span>:</span>Nghỉ không hưởng lương
		<span class="note-bold"><span class="un" style="margin-left: 10px">CL</span>:</span>Nghỉ bù
		<span class="note-bold"><span class="un" style="margin-left: 35px">WL</span>:</span>Nghỉ cưới
		<span class="note-bold"><span class="un" style="margin-left: 40px">PL</span>:</span>Nghỉ CK kinh nguyệt
		<span class="note-bold"><span class="un" style="margin-left: 10px">ML</span>:</span>Nghỉ sinh con
	</div>
	<div>
		<span class="un" style="font-weight:bold">SL:</span> Sick Leave (applied for the case of providing a medical
		certificate <span class="un" style="font-weight: bold">Form C65-HD</span> for Social Insurance claim only)
	</div>
	<div>
		<span class="un" style="font-weight:bold">SL:</span> Nghỉ ốm (áp dụng trong trường hợp có giấy chứng nhận y tế
		<span class="un" style="font-weight: bold">Mẫu C65-HD</span> để hưởng lương BHXH)
	</div>
	<p></p>
	<table>
		<tr>
			<td style="text-align: center;width: 20%;height: 80px;border-bottom:0px;">
				<div>President or Executive VP</div>
				<div style="border-bottom:1px dotted ">TGD hay PTGD</div>
			</td>
			<td style="text-align: center; vertical-align: middle;width: 4%;">BA</td>
			<td style="text-align: center">GM/ Giám đốc</td>
			<td style="text-align: center">Manager/ Trưởng phòng</td>
			<td style="text-align: center">HR. IC/ NV Nhân sự</td>
		</tr>
		<tr>
			<td style="text-align: center;height: 80px;vertical-align: middle;border-top:0px;">
				<div style="border-bottom:1px dotted">(HR) Internally Recorded by</div>
			</td>
			<td style="text-align: center; vertical-align: middle">Dept.</td>
			<td style="text-align: center">GM/ Giám đốc</td>
			<td style="text-align: center">Manager/ Trưởng phòng</td>
			<td style="text-align: center">Applicant/ Người đề nghị</td>
		</tr>
	</table>
</body>
</html>