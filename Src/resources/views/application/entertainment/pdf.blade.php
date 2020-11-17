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
		.start{
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
			<td class="center bg" style="width: 10%;">
				<div class="note">Area/City<span class="start">※</span></div>
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
			<td class="center bg" style="width: 12%">
				<div class="note">Details of duties<span class="start">※</span></div>
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

	<table id="tb-3">
		<tr>
			<td class="bg header center" style="width: 30%">
				<div class="note">Confirmation of Compliance with Laws<span class="start">※</span></div>
				<div>Xác nhận việc tuân thủ luật pháp</div>
			</td>
			<td class="bg header center" style="width: 25%">
				<div class="note">No. of Entertainment for past 1 year<span class="start">※</span></div>
				<div>Số lần tiếp khách trong năm vừa qua</div>
			</td>
			<td class="bg header center" style="width: 15%">
				<div class="note">Existence of projects<span class="start">※</span></div>
				<div>Dự án đang tồn tại</div>
			</td>
			<td class="bg header center" style="width: 30%">
				<div class="note">Attendant includes its family/friend<span class="start">※</span></div>
				<div>Thành phần tham dự bao gồm cả gia đình/ bạn bè</div>
			</td>
		</tr>
		<tr>
			<td class="center">YES</td>
			<td class="center">YES 5 TIMES</td>
			<td class="center">YES</td>
			<td class="center">NO</td>
		</tr>
		<tr>
			<td class="bg" style="font-size: 9px">Project Name (under negotiation and/or in near future) (if any)</td>
			<td colspan="3"></td>
		</tr>
		<tr>
			<td colspan="4" class="center bg">
				If Public Officials (PO), fill in '<b>O</b>' in <b>PO</b> and describe in "<span class="start">※</span>" fields / Nếu chính quyền, đánh dấu 'X' vào ô PO và diễn
				giải hợp lý vào ô "<span class="start">※</span>"
			</td>
		</tr>
	</table>

	<table id="tb-4">
		<tr>
			<td class="center" style="width: 17%">
				<div>Reason for the Entertainment</div>
				<div>Lý do tiếp khách</div>
			</td>
			<td>Lý do tiếp khách</td>
		</tr>
	</table>

	<table id="tb-5">
		<tr>
			<td class="center" rowspan="2" style="width: 30%;border-bottom:0px">
				<span class="bold">Estimated</span> Total Number of Persons
			</td>
			<td class="center bold" rowspan="3" style="width: 7%">
				100
			</td>
			<td class="center" rowspan="3" style="width: 7%">
				<div>Persons</div>
				<div>Người</div>
			</td>
			<td class="bg-y" style="width: 44%;border-bottom:0px;border-right:0px">
				<span class="bold">Estimated</span> Amount (VND) / Chi phí dự tính 
				<span class="bold under">excluding VAT</span>
			</td>
			<td class="right bg-y" style="width: 12%;border-bottom:0px;border-left:1px dotted">
				VND 99,999,999
			</td>
		</tr>
		<tr>
			<td class="right bold bg-y" style="border-top:1px dotted;border-bottom:0px;border-right:0px">
				(Per Person/ Mỗi người) <span class="bold under">excluding VAT</span>
			</td>
			<td class="right bold bg-y" style="border-top:1px dotted;border-bottom:0px;border-left:1px dotted">
				VND 50,000,000
			</td>
		</tr>
		<tr>
			<td class="center" style="border-top:1px dotted">
				Tổng số người tham gia dự tính
			</td>
			<td class="right bold bg-y" style="border-top:1px dotted;border-right:0px">
				(Per Person/ Mỗi người) <span class="bold under">including VAT</span>
			</td>
			<td class="right bold bg-y" style="border-top:1px dotted;border-left:1px dotted">
				VND 50,000,000
			</td>
		</tr>
	</table>

	<table id="tb-6">
		<tr>
			<td class="center bg-y" style="font-size: 8px">
				<div>
					Describe If the amount per person exceeds VND 4mil
					<span style="color: red">(in case of PO, VND 2mil)</span>
					, or any special situation occur
				</div>
				<div>
					Diễn giải nếu số tiền cho mỗi người vượt quá ngân sách hoặc các trường hợp đặc biệt khác
				</div>
			</td>
			<td colspan="3"></td>
		</tr>
		<tr>
			<td class="center" style="width: 30%">
				<div>For Account (Sec code)</div>
				<div>Chi phí chịu bởi (Mã bộ phận)</div>
			</td>
			<td style="width: 25%"></td>
			<td class="center" style="width: 25%">
				<div>Approval Number</div>
				<div>Số phê duyệt</div>
			</td>
			<td style="width: 20%"></td>
		</tr>
	</table>

	<table id="tb-7">
		<tr>
			<td class="center bold">
				<div>Decision-Making Authority</div>
				<div>Người ra quyết định</div>
			</td>
			<td class="center" rowspan="2" style="width: 5%">BA</td>
			<td class="center">GM/Giám đốc</td>
			<td class="center under bold" colspan="2">ADMI. IC/NV Hành chính</td>
		</tr>
		<tr>
			<td class="bold" style="width: 25%;vertical-align: top;border-bottom:0px">
				<div style="font-size: 8px;background-color: #cccccc">
					<span class="start">※</span>
					In case of "PO", it is required to get pre-approval from President.
				</div>
			</td>
			<td style="width: 25%;height:50px"></td>
			<td style="width: 25%;height:50px"></td>
			<td style="width: 20%;height:50px"></td>
		</tr>
		<tr>
			<td class="bold" style="width: 25%;vertical-align: top;border-top:0px;border-bottom:0px">
				{{-- <div style="font-size: 8px;background-color: #cccccc">
					<span class="start">※</span>
					In case of "PO", it is required to get pre-approval from President.
				</div> --}}
			</td>
			<td rowspan="2" class="center">Dept</td>
			<td class="center">GM/Giám đốc</td>
			<td class="center">Manager/ Trưởng phòng</td>
			<td class="center">Applicant/ Người đề nghị</td>
		</tr>
		<tr>
			<td style="border-top:0px">
				<div style="border-top:1px dotted">
					(ADMI) Internally Recorded by
				</div>
			</td>
			<td style="width: 25%;height:50px"></td>
			<td style="width: 25%;height:50px"></td>
			<td style="width: 20%;height:50px"></td>
		</tr>
	</table>

</body>

</html>