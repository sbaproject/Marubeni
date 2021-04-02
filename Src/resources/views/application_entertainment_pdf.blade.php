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
		/* margin page */
		@page{
			margin: 5px;
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
		#tb-0 {
			margin-bottom: 10px;
		}
		#tb-0 td {
			border:0px;
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
				<div style="border-bottom:1px solid;margin-top:-3px">
					{{ $inputs['applicant']->name }}
				</div>
			</td>
		</tr>
	</table>

	<table>
		<tr>
			<td class="center">Date & Time/ Thời gian</td>
			<td colspan="3">
				@if ($inputs['entertainment_dt'] != null)
				{{ date('d/m/Y H:i', strtotime($inputs['entertainment_dt'])) }}
				@endif
			</td>
		</tr>
		<tr>
			<td class="center" style="width: 25%">Place/ Địa điểm</td>
			<td style="width: 58%">
				{{ $inputs['place'] }}
			</td>
			<td class="center" style="width: 12%">During biz trip</td>
			<td class="center" style="width: 5%">
				@if($inputs['during_trip'] !== null)
				@php
				$arr = config('const.entertainment.during_trip');
				@endphp
				{{ Str::upper(array_search($inputs['during_trip'], $arr)) }}
				@endif
			</td>
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
		@foreach ($inputs['infos'] as $item)
		@php
			$isEmpty = empty($item['cp_name']) && empty($item['title']) && empty($item['name_attendants']) && empty($item['details_dutles']);
		@endphp
			<tr>
				<td style="@if($isEmpty) height:20px @endif">
					{{ $item['cp_name'] }}
				</td>
				<td></td>
				<td></td>
				<td></td>
				<td>{{ $item['title'] }}</td>
				<td>{{ $item['name_attendants'] }}</td>
				<td>{{ $item['details_dutles'] }}</td>
				<td></td>
			</tr>
		@endforeach
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
			@php
				$isEmpty = empty($inputs['check_row']) && empty($inputs['has_entertainment_times']) && empty($inputs['existence_projects']) && empty($inputs['includes_family']);
			@endphp
			<td class="center" style="@if($isEmpty) height:20px @endif">
				@if($inputs['check_row'] !== null)
					@php
					$arr = config('const.entertainment.check_row');
					@endphp
					{{ Str::upper(array_search($inputs['check_row'], $arr)) }}
				@endif
			</td>
			<td class="center">
				@if($inputs['has_entertainment_times'] !== null)
					@php
					if($inputs['has_entertainment_times'] == config('const.entertainment.has_et_times.yes')){
						echo 'YES ' . $inputs['entertainment_times'] . ' TIMES';
					} elseif ($inputs['has_entertainment_times'] == config('const.entertainment.has_et_times.no')) {
						echo 'NO';
					}
					@endphp
				@endif
			</td>
			<td class="center">
				@if($inputs['existence_projects'] !== null)
					@php
					$arr = config('const.entertainment.existence_projects');
					@endphp
					{{ Str::upper(array_search($inputs['existence_projects'], $arr)) }}
				@endif
			</td>
			<td class="center">
				@if($inputs['includes_family'] !== null)
					@php
					$arr = config('const.entertainment.includes_family');
					@endphp
					{{ Str::upper(array_search($inputs['includes_family'], $arr)) }}
				@endif
			</td>
		</tr>
		<tr>
			<td class="bg" style="font-size: 9px">Project Name (under negotiation and/or in near future) (if any)</td>
			<td colspan="3">
				{{ $inputs['project_name'] }}
			</td>
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
			<td>{{ $inputs['entertainment_reason'] }}</td>
		</tr>
	</table>

	<table id="tb-5">
		<tr>
			<td class="center" rowspan="2" style="width: 30%;border-bottom:0px">
				<span class="bold">Estimated</span> Total Number of Persons
			</td>
			<td class="center bold" rowspan="3" style="width: 7%;border-right:0px">
				{{ $inputs['entertainment_person'] }}
			</td>
			<td class="center" rowspan="3" style="width: 7%;border-left:0px">
				<div>Persons</div>
				<div>Người</div>
			</td>
			<td class="bg-y" style="width: 44%;border-bottom:0px;border-right:0px">
				<span class="bold">Estimated</span> Amount (VND) / Chi phí dự tính 
				<span class="bold under">excluding VAT</span>
			</td>
			<td class="right bg-y" style="width: 12%;border-bottom:0px;border-left:1px dotted">
				@if (is_numeric($inputs['est_amount']))
					{{ 'VND '.number_format($inputs['est_amount']) }}
				@endif
			</td>
		</tr>
		<tr>
			<td class="right bold bg-y" style="border-top:1px dotted;border-bottom:0px;border-right:0px">
				(Per Person/ Mỗi người) <span class="bold under">excluding VAT</span>
			</td>
			<td class="right bold bg-y" style="border-top:1px dotted;border-bottom:0px;border-left:1px dotted">
				@if (is_numeric($inputs['est_amount']) && is_numeric($inputs['entertainment_person']))
					@if ($inputs['entertainment_person'] != 0)
						@php
							$excludeVAT = $inputs['est_amount'] / $inputs['entertainment_person'];
							echo 'VND '. number_format($excludeVAT);
						@endphp
					@endif
				@endif
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
				@if (is_numeric($inputs['est_amount']) && is_numeric($inputs['entertainment_person']))
					@if ($inputs['entertainment_person'] != 0)
						@php
						$excludeVAT = $inputs['est_amount'] / $inputs['entertainment_person'];
						$includeVAT = round($excludeVAT * 1.1, 0);
						echo 'VND '. number_format($includeVAT);
						@endphp
					@endif
				@endif
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
			<td colspan="3">
				{{ $inputs['reason_budget_over'] }}
			</td>
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
	@isset($inputs['lastApproval'])
	<div>Approved by : {{ $inputs['lastApproval']->user_name }}</div>
	<div>Approved at : {{ date('d/m/Y H:i', strtotime($inputs['lastApproval']->created_at)) }}</div>
	@endisset
</body>
</html>