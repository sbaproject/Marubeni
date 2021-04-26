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
		font-size: 16px;
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
		/* width: 20%; */
		vertical-align: middle;
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
		vertical-align: top;
		text-align: left;
		padding-left: 3px;
		word-wrap: break-word;
		}
		.caption {
		margin-top: -5px;
		}

		h3{
			margin:0;
			margin-bottom: 3px;
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
		<div class="en">Settlement For Entertainment Fee</div>
		<div class="vi">ĐƠN ĐỀ NGHỊ THANH TOÁN CHI PHÍ TIẾP KHÁCH</div>
	</div>

	<h3>
		Pre-approval/Chấp thuận trước
	</h3>
	<table>
		<tr>
			<td class="f">
				<div class="caption">Apply Date</div>
				<div class="caption">Ngày nộp đơn</div>
			</td>
			<td colspan="3" class="data">
				{{ date('d/m/Y', strtotime($application->created_at)) }}
			</td>
		</tr>
		<tr>
			<td class="f">
				<div class="caption">Approver / Date</div>
				<div class="caption">Người duyệt đơn / Ngày</div>
			</td>
			<td class="data jp">
				@isset($application->lastapprovalstep1)
				{{ $application->lastapprovalstep1->approver_name }}
				@endisset
			</td>
			<td colspan="2" class="data">
				@isset($application->lastapprovalstep1)
				{{ date('d/m/Y', strtotime($application->lastapprovalstep1->created_at)) }}
				@endisset
			</td>
		</tr>
		<tr>
			<td class="f">
				<div class="caption">Apply No</div>
				<div class="caption">Mã số đơn xin</div>
			</td>
			<td colspan="3" class="data">{{ $application->application_no }}</td>
		</tr>
		<tr>
			<td class="f">
				<div class="caption">Scheduled Execution Date</div>
				<div class="caption">Ngày dự kiến tiếp khách</div>
			</td>
			<td colspan="3" class="data">
				{{ date('d/m/Y', strtotime($application->entertainment->entertainment_dt)) }}
			</td>
		</tr>
		<tr>
			@php
				$rowSpan = count($application->entertainment->entertainmentinfos) + 1;
			@endphp
			<td rowspan="{{ $rowSpan }}" class="f" style="width: 20%">
				<div class="caption">Entertainment Information</div>
				<div class="caption">Thông tin tiếp khách</div>
			</td>
			<td class="f" style="width: 22%; text-align: center;">
				<div class="caption">Company Name</div>
				<div class="caption">Tên công ty</div>
			</td>
			<td class="f" style="width: 18%; text-align: center;">
				<div class="caption">Title</div>
				<div class="caption">Chức danh</div>
			</td>
			<td class="f" style="width: 40%; text-align: center;">
				<div class="caption">Name of Attendants</div>
				<div class="caption">Tên người tham gia</div>
			</td>
		</tr>
		@foreach ($application->entertainment->entertainmentinfos as $item)
			<tr>
				<td class="data jp">{{ $item['cp_name'] }}</td>
				<td class="data jp">{{ $item['title'] }}</td>
				<td class="data jp">{{ $item['name_attendants'] }}</td>
			</tr>
		@endforeach
		<tr>
			<td class="f">
				<div class="caption">Type for the Entertainment</div>
				<div class="caption">Loại tiếp khách</div>
			</td>
			<td colspan="3" class="data jp">
				{{ __('label.entertainment.reason.'.$application->entertainment->entertainment_reason) }}
			</td>
		</tr>
		<tr>
			<td class="f">
				<div class="caption">Estimated Amount</div>
				<div class="caption">Tổng số tiền dự kiến</div>
			</td>
			<td class="data jp">{{ number_format($application->entertainment->est_amount) }}</td>
			<td colspan="2" class="f">
				<div class="caption">Per Person(Excluding VAT)</div>
				<div class="caption">Số tiền 1 người ( Không gồm VAT)</div>
			</td>
		</tr>
		<tr>
			<td class="f">
				<div class="caption">Applicant /Department</div>
				<div class="caption">Người xin đơn / Phòng ban</div>
			</td>
			<td class="data jp">
				{{ $application->applicant->name }}
			</td>
			<td colspan="2" class="data jp">
				{{ $application->applicant->department->name }}
			</td>
		</tr>
	</table>


	{{-- ------ --}}


	<h3>
		Settlement / Thanh toán
	</h3>
	<table>
		<tr>
			<td class="f">
				<div class="caption">Execution Date</div>
				<div class="caption">Ngày tiếp khách</div>
			</td>
			<td colspan="3" class="data">
				@isset($inputs['entertainment_dt'])
					{{ date('d/m/Y', strtotime($inputs['entertainment_dt'])) }}
				@endisset
			</td>
		</tr>
		<tr>
			@php
			$rowSpan = count($inputs['entertainmentinfos']) + 1;
			@endphp
			<td rowspan="{{ $rowSpan }}" class="f" style="width: 20%">
				<div class="caption">Entertainment Information</div>
				<div class="caption">Thông tin tiếp khách</div>
			</td>
			<td class="f" style="width: 22%; text-align: center;">
				<div class="caption">Company Name</div>
				<div class="caption">Tên công ty</div>
			</td>
			<td class="f" style="width: 18%; text-align: center;">
				<div class="caption">Title</div>
				<div class="caption">Chức danh</div>
			</td>
			<td class="f" style="width: 40%; text-align: center;">
				<div class="caption">Name of Attendants</div>
				<div class="caption">Tên người tham gia</div>
			</td>
		</tr>
		@foreach ($inputs['entertainmentinfos'] as $item)
		@if (empty($item['cp_name']) && empty($item['title']) && empty($item['name_attendants']))
			<tr>
				<td><div style="height: 20px"></div></td>
				<td></td>
				<td></td>
			</tr>
		@else
			<tr>
				<td class="data jp">{{ $item['cp_name'] ?? '' }}</td>
				<td class="data jp">{{ $item['title'] ?? '' }}</td>
				<td class="data jp">{{ $item['name_attendants'] ?? '' }}</td>
			</tr>
		@endif
		@endforeach
		<tr>
			<td rowspan="3" class="f">
				<div class="caption">Amount</div>
				<div class="caption">Số tiền thực tế</div>
			</td>
			<td class="data">
				{{ $inputs['entertainment_person'] ?? '' }}
			</td>
			<td colspan="2" class="f">
				<div class="caption">No. of Persons</div>
				<div class="caption">Số tiền 1 người ( Không gồm VAT)</div>
			</td>
		</tr>
		<tr>
			<td class="data">
				@isset($inputs['est_amount'])
					{{ number_format($inputs['est_amount']) }}
				@endisset
			</td>
			<td colspan="2" class="f">
				<div class="caption">Per Person(Excluding VAT)</div>
				<div class="caption">Số tiền 1 người ( Không gồm VAT)</div>
			</td>
		</tr>
		<tr>
			<td class="data">
				@php
					$numPerson = $inputs['entertainment_person'] ?? 0;
					$amountPerson = $inputs['est_amount'] ?? 0;
					$total = $numPerson * $amountPerson;
				@endphp
				{{ number_format($total) }}
			</td>
			<td colspan="2" class="f">
				<div class="caption">Total</div>
				<div class="caption">Tổng số tiền</div>
			</td>
		</tr>
		@php
		$rowSpan = count($inputs['chargedbys']);
		@endphp
		@foreach ($inputs['chargedbys'] as $index => $item)
			<tr>
				@if($index === 0) 
				<td rowspan="{{ $rowSpan }}" class="f">
					<div class="caption">Cost to be charged to (Sec Code)</div>
					<div class="caption">Phòng ban chịu chi phí</div>
				</td>
				@endif
				<td class="data jp">
					@php
					$department = App\Models\Department::find($item['department']);
					@endphp
					@isset($department)
					{{ $department->name }}
					@endisset
				</td>
				<td class="data jp">
					{{ $item['percent'] }}
				</td>
				<td class="data">%</td>
			</tr>
		@endforeach
		<tr>
			<td class="f">
				<div class="caption">Payment Information</div>
				<div class="caption">Thông tin thanh toán</div>
			</td>
			<td colspan="3" class="data jp">
				{{ $inputs['pay_info'] ?? '' }}
			</td>
		</tr>
	</table>

	<p></p>
	<table>
		<tr>
			<td style="width: 25%;border:none"></td>
			<td style="width: 25%;text-align:center">ACCG GM</td>
			<td style="width: 25%;text-align:center">ACCG Manager</td>
			<td style="width: 25%;text-align:center">ACCG PIC</td>
		</tr>
		<tr>
			<td style="height:70px;border:none">
				@isset($inputs['lastApproval'])
				<div style="margin-top:-26px">
					<div>Approved by : {{ $inputs['lastApproval']->approver_name }}</div>
					<div>Approved at : {{ date('d/m/Y H:i', strtotime($inputs['lastApproval']->created_at)) }}</div>
					<div style="
							display:inline-block;
							border: 2px solid red;
							/* border-radius:10px; */
							vertical-align: middle;
							padding: 10px;
							margin-top:1px;
							color: red;
							font-weight: bold;
						">
						APPROVED
					</div>
				</div>
				@endisset
			</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</table>
</body>
</html>