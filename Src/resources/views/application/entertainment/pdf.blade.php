<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>

	<style>
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
			border-collapse: collapse;
			border-spacing: 0;
			width: 100%
		}

		td {
			border: 1px solid;
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
			font-size: 14px;
		}

		#title-header .vi {
			margin-top: -8px;
			font-size: 12px;
		}
		.center{
			text-align: center;
		}
		#tb-2 {

		}
		#tb-2 td {
			font-size: 8px;
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
		<div class="en">Pre-Approval & Settlement for Entertainment Fee</div>
		<div class="vi">ĐƠN ĐỀ NGHỊ CHẤP THUẬN TRƯỚC & THANH TOÁN CHI PHÍ TIẾP KHÁCH</div>
	</div>

	<table>
		<tr>
			<td class="center">Date & Time/ Thời gian</td>
			<td colspan="3"></td>
		</tr>
		<tr>
			<td class="center" style="width: 25%">Place/ Địa điểm</td>
			<td style="width: 58%"></td>
			<td class="center" style="width: 12%">During biz trip</td>
			<td class="center" style="width: 5%">Yes</td>
		</tr>
	</table>

	<table id="tb-2">
		<tr>
			<td class="center" style="width: 17%">
				<div>Company Name</div>
				<div>Tên công ty</div>
			</td>
			<td class="center" style="width: 8%;">
				<div>Country</div>
				<div>Quốc gia</div>
			</td>
			<td class="center" style="width: 10%;background-color: #cccccc">
				<div>Area/City※</div>
				<div>Khu vực, T.Phố</div>
			</td>
			<td class="center" style="width: 12%">
				<div>Dept. Name</div>
				<div>Tên bộ phận</div>
			</td>
			<td class="center" style="width: 21%">
				<div>Title</div>
				<div>Chức danh</div>
			</td>
			<td class="center" style="width: 15%">
				<div>Name of Attendants</div>
				<div>Thành phần tham dự</div>
			</td>
			<td class="center" style="width: 12%;background-color: #cccccc">
				<div>Details of duties※</div>
				<div>Chi tiết nhiệm vụ</div>
			</td>
			<td class="center" style="width: 5%">PO</td>
		</tr>
		<tr>
			<td>Chi tiết nhiệm vụ</td>
			<td>Chi tiết nhiệm vụ</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td>Chi tiết nhiệm vụChi tiết nhiệm vụChi tiết nhiệm vụ</td>
			<td></td>
		</tr>
	</table>

</body>

</html>