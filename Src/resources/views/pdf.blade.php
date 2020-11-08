<!DOCTYPE html>
<html lang="vi">

<head>
	{{-- <meta charset="UTF-8"> --}}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<title>Document</title>
{{-- <link href='https://fonts.googleapis.com/css?family=Arial' rel='stylesheet'> --}}
	<style type="text/css">
	@font-face {
		font-family: 'notosans-bold';
		src: url({{ storage_path('fonts\NotoSans-Bold.ttf') }}) format("truetype");
		font-weight: 700;
		font-style: normal;
	}
	@font-face {
		font-family: 'notosans-bolditalic';
		src: url({{ storage_path('fonts\NotoSans-BoldItalic.ttf') }}) format("truetype");
		font-weight: 700;
		font-style: italic;
	}
	@font-face {
		font-family: 'notosans-italic';
		src: url({{ storage_path('fonts\NotoSans-Italic.ttf') }}) format("truetype");
		font-weight: 400;
		font-style: italic;
		}
	@font-face {
		font-family: 'notosans-regular';
		src: url({{ storage_path('fonts\NotoSans-Regular.ttf') }}) format("truetype");
		font-weight: 400;
		font-style: normal;
	}
		body{
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
margin-top: -13px;
}

#info-header {
/* float: right; */
/* text-align: right; */
margin-bottom: 10px;
}
#info-header table {
/* width: 350px; */
}
#info-header table td{
}
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
width: 35%;
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
				<td></td>
			</tr>
			<tr>
				<td>Sec Code/ Mã bộ phận:</td>
				<td></td>
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
				<td colspan="6"></td>
			</tr>
			<tr>
				<td class="f" style="font-weight: bold">
					<div>Reason for leave</div>
					<div>Lý do xin nghỉ phép</div>
				</td>
				<td colspan="6"></td>
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
						<input type="checkbox" style="display: inline;">
						<div style="margin-top:-20px;margin-left:20px">
							<div>Annual leave</div>
							<div>Nghỉ phép theo chế độ</div>
						</div>
					</div>
					<div style="float: right; margin-top:-45px; margin-right:80px">
						<input type="checkbox" style="display: inline;" checked>
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
				<td rowspan="2" style="width:50px;text-align: center;vertical-align: middle">
					<div>From-To</div>
					<div>Từ-Đến</div>
				</td>
				<td colspan="3"></td>
				<td rowspan="2" style="width:80px; text-align: center;vertical-align: middle">
					<div>No. of Days</div>
					<div>Số ngày nghỉ</div>
				</td>
				<td rowspan="2" style="width:15%;"></td>
			</tr>
			<tr>
				<td colspan="3"></td>
			</tr>
			<tr>
				<td class="f" rowspan="2">
					<div>&#60;In case of hour-leave&#62;</div>
					<div style="font-weight: bold">Time of Leave</div>
					<div>&#60;Trường hợp nghỉ theo giờ&#62;</div>
					<div style="font-weight: bold">Thời gian nghỉ</div>
				</td>
				<td rowspan="2" style=" text-align: center;vertical-align: middle">
					<div>Date</div>
					<div>Ngày</div>
				</td>
				<td rowspan="2" style=""></td>
				<td rowspan="2" style="width: 50px;text-align: center;vertical-align: middle">
					<div>From-To</div>
					<div>Từ-Đến</div>
				</td>
				<td rowspan="2" style=""></td>
				<td rowspan="2" style="text-align: center;vertical-align: middle">
					<div>No. of Hours</div>
					<div>Số giờ nghỉ</div>
				</td>
				<td rowspan="2"></td>
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
				<td colspan="3"></td>
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
				<td colspan="3">
					
				</td>
			</tr>
			<tr>
				<td colspan="3">
					<div>AL used this year</div>
					<div>Số ngày phép đã sử dụng trong năm</div>
				</td>
				<td colspan="3"></td>
			</tr>
			<tr>
				<td colspan="3">
					<div>AL to take this time</div>
					<div>Số ngày phép xin nghỉ</div>
				</td>
				<td colspan="3"></td>
			</tr>
			<tr>
				<td colspan="3">
					<div>Remaining AL</div>
					<div>Số ngày phép chưa sử dụng</div>
				</td>
				<td colspan="3"></td>
			</tr>
			{{-- <tr>
				<td class="f">
					<div>&#60;In case of day-leave&#62;</div>
					<div style="font-weight: bold">Date of Leave</div>
					<div>&#60;Trường hợp nghỉ theo ngày&#62;</div>
					<div style="font-weight: bold">Ngày nghỉ</div>
				</td>
				<td>
					<table style="line-height: 23px;">
						<tr>
							<td rowspan="2" style="width: 13%;text-align: center;border-left:0px;border-top:0px;border-bottom:0px">
								<div style="margin-top: 3px">From-To</div>
								<div style="margin-top: -15px">Từ-Đến</div>
							</td>
							<td style="width:60%;border:0px;border-right: 1px"></td>
							<td rowspan="2" style="width:13%;border:0px;border-right:1px;text-align: center">
								<div>From-To</div>
								<div>Từ-Đến</div>
							</td>
							<td rowspan="2" style="border:0px;">
								
							</td>
						</tr>
						<tr>
							<td style="border-bottom: 0px"></td>
						</tr>
					</table>
				</td>
			</tr> --}}
		</tbody>
	</table>
	</body>
	
	</html>