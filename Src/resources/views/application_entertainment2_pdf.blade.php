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
		
		@font-face {
		font-family: 'sawarabiGothic-regular';
		src: url({{ storage_path('fonts\SawarabiGothic-Regular.ttf')}}) format("truetype");
		font-weight: 400;
		font-style: normal;
		}

		@font-face {
		font-family: 'japanese';
		src: url({{ storage_path('fonts\SawarabiGothic-Regular.ttf')}}) format("truetype");
		font-weight: 400;
		font-style: normal;
		}

		/* margin page */
		@page{
			margin: 5px;
		}
		body {
			font-family: "notosans-regular, notosans-bold, notosans-bolditalic,notosans-italic";
			font-size: 10px;
		}

		.jp {
			font-family: 'japanese';
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
			margin-top: -3px;
			font-size: 12px;
		}
		.center{
			text-align: center;
		}
		.right{
			text-align: right;
		}
		#tb-2 {

		}
		#tb-2 td {
			font-size: 8px;
		}
		#tb-0 {
			margin-bottom: 10px;
		}
		#tb-0 td {
			border:0px;
		}
		.star{
			font-family: 'sawarabiGothic-regular';
			font-weight: 400 !important;
		}
		.note{
			font-weight: bold;
		}
		.bg{
			background-color: #cccccc
		}
		.bg-y{
			background-color: yellow;
		}
		#tb-3 .header{
			font-size: 8px;
		}
		.bold{
			font-weight: bold;
		}
		.under{
			text-decoration: underline;
		}
		/* .page-break {
			page-break-after: always;
		} */
	</style>
</head>

<body>
	<!-- TOP HEADER -->
	<table id="tb-top">
		<tr>
			<td class="logo">
				<div class="main">Marubeni</div>
				<div class="sub">Marubeni Vietnam Company Limited</div>
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

	@if(!empty($application))
		<div style="text-align: left">
			Application No/ Mã đơn: <span style="border-bottom:1px solid">{{ $application->application_no }}</span>
		</div>
	@endif

	<table id="tb-0">
		<tr>
			<td class="bold" style="width: 30%;font-size:12px">
				Pre-approval/ Chấp thuận trước
			</td>
			<td class="right" style="width: 15%;vertical-align: bottom">
				Applied date/ Ngày:
			</td>
			<td style="width: 10%;vertical-align: bottom">
				<div style="border-bottom:1px solid;margin-top:-3px">
					@if (empty($application))
						{{ Carbon\Carbon::now()->format('d/m/Y') }}
					@else
						{{ date('d/m/Y', strtotime($application->created_at)) }}
					@endif
				</div>
			</td>
			<td style="width: 6%"></td>
			<td class="right" style="width: 15%;vertical-align: bottom">Full Name/ Họ tên:</td>
			<td style="width: 24%;vertical-align: bottom">
				<div style="border-bottom:1px solid;margin-top:-3px" class="jp">
					{{ $inputs['applicant']->name }}
				</div>
			</td>
		</tr>
	</table>

	<div>
		Pre-approval/Chấp thuận trước
	</div>
	<table>
		<tr>
			<td class="f">
				<div class="caption">Apply Date</div>
				<div class="caption">Ngày nộp đơn</div>
			</td>
			<td colspan="2" class="data">12/12/2021</td>
		</tr>
		<tr>
			<td class="f">
				<div class="caption">Approver / Date</div>
				<div class="caption">Người duyệt đơn / Ngày</div>
			</td>
			<td class="data jp"></td>
			<td class="data">12/12/2021</td>
		</tr>
		<tr>
			<td class="f">
				<div class="caption">Apply No</div>
				<div class="caption">Mã số đơn xin</div>
			</td>
			<td colspan="2" class="data">BT-00000001</td>
		</tr>
		<tr>
			<td class="f">
				<div class="caption">Scheduled Execution Date</div>
				<div class="caption">Ngày dự kiến tiếp khách</div>
			</td>
			<td colspan="2" class="data">BT-00000001</td>
		</tr>
		<tr>
			<td class="f">
				<div class="caption">Entertainment Information</div>
				<div class="caption">Thông tin tiếp khách</div>
			</td>
			<td colspan="2" class="data">BT-00000001</td>
		</tr>
	</table>

	
	@isset($inputs['lastApproval'])
	<div style="margin-top:10px">
		<div style="display: inline-block;vertical-align: middle;">
			<div>Approved by : {{ $inputs['lastApproval']->approver_name }}</div>
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