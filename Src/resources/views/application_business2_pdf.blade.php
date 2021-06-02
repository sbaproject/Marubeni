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

		@font-face {
			font-family: 'japanese';
			src: url({{ storage_path('fonts\SawarabiGothic-Regular.ttf')}}) format("truetype");
			font-weight: 400;
			font-style: normal;
		}

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
		.note{
			width: 100px;
			display: inline-block;
			white-space: nowrap;
			overflow: hidden !important;
			text-overflow: ellipsis;
		}
		.item1{
			width: 13%;
		}
		.item2{
			width: 3%;
		}
		.item3{
			width: 16%;
		}
		.item4{
			width: 30%;
		}
		.item5{
			width: 39%;
		}

		.br {
			margin-top: 3px;
		}
		.txt-right {
			text-align: right !important;
		}
		.txt-center {
			text-align: center !important;
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
			<td style="text-align: right;color: #ff0000; font-size: 15px;">
				{{ isset($inputs['unfinish']) ? $inputs['unfinish'] : '' }}
			</td>
		</tr>
	</table>

	<!-- TITLE HEADER -->
	<div id="title-header">
		<div class="en">SETTLEMENT FOR BUSINESS TRIP</div>
		<div class="vi">QUYẾT TOÁN CHI PHÍ CÔNG TÁC</div>
	</div>

	<!-- INFO HEADER -->
	<div id="info-header">
		<table>
			<tr>
				<td>Application No/ Mã đơn:</td>
				<td class="jp">
					@if(!empty($application)) {{ $application->application_no }} @endif
				</td>
			</tr>
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
				<td class="jp">{{ $inputs['applicant']->name }}</td>
			</tr>
			<tr>
				<td>Sec Code/ Mã bộ phận:</td>
				<td>{{ $inputs['applicant']->department->name }}</td>
			</tr>
		</table>
	</div>

	<div style="font-weight: bold;margin: 0">Pre-approval/Chấp thuận trước</div>
	<div class="caption">
		Approver / Người duyệt : 
		@isset($application->lastapprovalstep1)
		{{ $application->lastapprovalstep1->approver_name }}
		@endisset
	</div>
	<div class="caption" style="margin-bottom:5px;">
		Approved time / Ngày duyệt : 
		@isset($application->lastapprovalstep1)
		{{ \Carbon\Carbon::parse($application->lastapprovalstep1->created_at)->format('d/m/Y H:i') }}
		@endisset
	</div>

	<table style="clear: both;">
		<tbody>
			<tr>
				<td class="f">
					<div class="caption">Trip Destination</div>
					<div class="caption">Nơi công tác</div>
				</td>
				<td colspan="6" class="data jp">
					{{ $inputs['destinations'] }}
				</td>
			</tr>
			<tr>
				@php
				$rowSpan = 2;
				if(isset($inputs['itineraries']) && count($inputs['itineraries']) > 2){
					$rowSpan = 3;
				}
				@endphp
				<td rowspan="{{ $rowSpan }}" class="f" style="width: 13%">
					<div class="caption">Itinerary</div>
					<div class="caption">Hành trình</div>
				</td>
				<td class="f" style="width: 9%;text-align: center;">
					<div class="caption">Date</div>
					<div class="caption">Ngày</div>
				</td>
				<td class="f" style="width: 17.25%;text-align: center;">
					<div class="caption">Departure</div>
					<div class="caption">Điểm đi</div>
				</td>
				<td class="f" style="width: 17.25%;text-align: center;">
					<div class="caption">Arrival</div>
					<div class="caption">Điểm đến</div>
				</td>
				<td class="f" style="width:9%; text-align: center;">
					<div class="caption">Date</div>
					<div class="caption">Ngày</div>
				</td>
				<td class="f" style="width: 17.25%;text-align: center;">
					<div class="caption">Departure</div>
					<div class="caption">Điểm đi</div>
				</td>
				<td class="f" style="width: 17.25%;text-align: center;">
					<div class="caption">Arrival</div>
					<div class="caption">Điểm đến</div>
				</td>
			</tr>
			<tr>
				<td class="data jp">
					@if (empty($inputs['itineraries']))
						<div style="height: 20px"></div>
					@else
						@isset($inputs['itineraries'][1]['trans_date'])
							{{ date('d/m/Y', strtotime($inputs['itineraries'][0]['trans_date'])) ?? '' }}
						@endisset
					@endif
				</td>
				<td class="data jp">{{ $inputs['itineraries'][0]['departure'] ?? '' }}</td>
				<td class="data jp">{{ $inputs['itineraries'][0]['arrive'] ?? '' }}</td>
				<td class="data jp">
					@isset($inputs['itineraries'][1]['trans_date'])
						{{ date('d/m/Y', strtotime($inputs['itineraries'][1]['trans_date'] ?? null)) }}
					@endisset
				</td>
				<td class="data jp">{{ $inputs['itineraries'][1]['departure'] ?? '' }}</td>
				<td class="data jp">{{ $inputs['itineraries'][1]['arrive'] ?? '' }}</td>
			</tr>
			@if (isset($inputs['itineraries']) && count($inputs['itineraries']) > 2)
			<tr>
				<td class="data jp">
					@isset($inputs['itineraries'][2]['trans_date'])
					{{ date('d/m/Y', strtotime($inputs['itineraries'][2]['trans_date'] ?? null)) }}
					@endisset
				</td>
				<td class="data jp">{{ $inputs['itineraries'][2]['departure'] ?? '' }}</td>
				<td class="data jp">{{ $inputs['itineraries'][2]['arrive'] ?? '' }}</td>
				<td class="data jp">
					@isset($inputs['itineraries'][3]['trans_date'])
					{{ date('d/m/Y', strtotime($inputs['itineraries'][3]['trans_date'] ?? null)) }}
					@endisset
				</td>
				<td class="data jp">{{ $inputs['itineraries'][3]['departure'] ?? '' }}</td>
				<td class="data jp">{{ $inputs['itineraries'][3]['arrive'] ?? '' }}</td>
			</tr>
			@endif
			<tr>
				<td class="f">
					<div class="caption">Number of days</div>
					<div class="caption">Số ngày</div>
				</td>
				<td colspan="6" class="data jp">
					{{ $inputs['number_of_days'] }}
				</td>
			</tr>
		</tbody>
	</table>
	<div class="br"></div>
	<table style="clear: both;">
		@php
			if((!isset($inputs['transportations']) || count($inputs['transportations']) == 0)
				&& (!isset($inputs['accomodations']) || count($inputs['accomodations']) == 0)
				&& (!isset($inputs['communications']) || count($inputs['communications']) == 0)
				&& (!isset($inputs['otherfees']) || count($inputs['otherfees']) == 0)
				){
					$noTripFees = true;
				}
		@endphp
		<tbody>
			<tr>
				<td colspan="3" class="f" style="text-align: center;">
					<div class="caption">Item</div>
					<div class="caption">Hạng mục</div>
				</td>
				<td class="f" style="text-align: center; width:6%">
					<div class="caption">Currency</div>
					<div class="caption">Tiền tệ</div>
				</td>
				<td class="f" style="text-align: center; width:10%">
					<div class="caption">Amount</div>
					<div class="caption">Số tiền</div>
				</td>
				<td class="f" style="text-align: center; width:10%">
					<div class="caption">Exchange rate</div>
					<div class="caption">Tỷ giá</div>
				</td>
				<td class="f" style="text-align: center; width:10%">
					<div class="caption">VND(SUB)</div>
					<div class="caption">Tạm tính</div>
				</td>
				<td class="f" style="text-align: center; width:32%">
					<div class="caption">Remarks</div>
					<div class="caption">Ghi chú</div>
				</td>
			</tr>
			{{-- TripFees - Transportations --}}
			@if (isset($inputs['transportations']) && count($inputs['transportations']) > 0)
				@php
					$rowsSpan = count($inputs['transportations']);
				@endphp
				@foreach ($inputs['transportations'] as $index => $item)
					<tr>
						@if ($index == 0)
							<td rowspan="{{ $rowsSpan }}" class="f item1">
								<div class="caption">{{ trans('label.business_trans',[],'en') }}</div>
								<div class="caption">{{ trans('label.business_trans',[],'vi') }}</div>
							</td>
						@endif
						<td class="f item2">
							{{ config('const.trip_fee_type.transportation').($index + 1) }}
						</td>
						<td class="f item3">
							@isset($item['method'])
							<div class="caption">{{ trans('label.business_transportations.'.$item['method'],[],'en') }}</div>
							<div class="caption">{{ trans('label.business_transportations.'.$item['method'],[],'vi') }}</div>
							@endisset
						</td>
						<td class="data" style="width:6%;text-align: center">
							{{ $item['unit'] }}
						</td>
						<td class="data" style="width:10%;text-align: right">
							{{ isset($item['amount']) ? Common::formatNumeralWithoutZeroDecimal($item['amount']) : '' }}
						</td>
						<td class="data" style="width:10%;text-align: right">
							{{ isset($item['exchange_rate']) ? number_format($item['exchange_rate']) : '' }}
						</td>
						<td class="data" style="width:10%;text-align: right">
							@php
								$amount = $item['amount'] ?? 0;
								$rate = $item['exchange_rate'] ?? 1;
								$sub = $amount * $rate;
							@endphp
							{{ number_format($sub) }}
						</td>
						<td class="data jp" style="width:32%">
							{{ mb_strlen($item['note']) > 40 ? mb_substr($item['note'], 0, 40).'...' : $item['note'] }}
						</td>
					</tr>
				@endforeach
			@else
				<tr>
					<td class="f item1">
						<div class="caption">{{ trans('label.business_trans',[],'en') }}</div>
						<div class="caption">{{ trans('label.business_trans',[],'vi') }}</div>
					</td>
					<td class="item2"></td>
					<td class="item3"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			@endif

			{{-- TripFees - accomodations --}}
			@if (isset($inputs['accomodations']) && count($inputs['accomodations']) > 0)
			@php
			$rowsSpan = count($inputs['accomodations']);
			@endphp
			@foreach ($inputs['accomodations'] as $index => $item)
			<tr>
				@if ($index == 0)
				<td rowspan="{{ $rowsSpan }}" class="f item1">
					<div class="caption">{{ trans('label.business_accommodation_fee',[],'en') }}</div>
					<div class="caption">{{ trans('label.business_accommodation_fee',[],'vi') }}</div>
				</td>
				@endif
				<td class="f item2">
					{{ config('const.trip_fee_type.accomodation').($index + 1) }}
				</td>
				<td class="f item3">
					@isset($item['method'])
					<div class="caption">{{ trans('label.business_accomodations.'.$item['method'],[],'en') }}</div>
					<div class="caption">{{ trans('label.business_accomodations.'.$item['method'],[],'vi') }}</div>
					@endisset
				</td>
				<td class="data" style="width:6%;text-align: center">
					{{ $item['unit'] }}
				</td>
				<td class="data" style="width:10%;text-align: right">
					{{ isset($item['amount']) ? Common::formatNumeralWithoutZeroDecimal($item['amount']) : '' }}
				</td>
				<td class="data" style="width:10%;text-align: right">
					{{ isset($item['exchange_rate']) ? number_format($item['exchange_rate']) : '' }}
				</td>
				<td class="data" style="width:10%;text-align: right">
					@php
					$amount = $item['amount'] ?? 0;
					$rate = $item['exchange_rate'] ?? 1;
					$sub = $amount * $rate;
					@endphp
					{{ number_format($sub) }}
				</td>
				<td class="data jp" style="width:32%">
					{{ mb_strlen($item['note']) > 40 ? mb_substr($item['note'], 0, 40).'...' : $item['note'] }}
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td class="f item1">
					<div class="caption">{{ trans('label.business_accommodation_fee',[],'en') }}</div>
					<div class="caption">{{ trans('label.business_accommodation_fee',[],'vi') }}</div>
				</td>
				<td class="item2"></td>
				<td class="item3"></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			@endif

			{{-- TripFees - communications --}}
			@if (isset($inputs['communications']) && count($inputs['communications']) > 0)
			@php
			$rowsSpan = count($inputs['communications']);
			@endphp
			@foreach ($inputs['communications'] as $index => $item)
			<tr>
				@if ($index == 0)
				<td rowspan="{{ $rowsSpan }}" class="f item1">
					<div class="caption">{{ trans('label.business_communication',[],'en') }}</div>
					<div class="caption">{{ trans('label.business_communication',[],'vi') }}</div>
				</td>
				@endif
				<td class="f item2">
					{{ config('const.trip_fee_type.communication').($index + 1) }}
				</td>
				<td class="f item3">
					@isset($item['method'])
					<div class="caption">{{ trans('label.business_communications.'.$item['method'],[],'en') }}</div>
					<div class="caption">{{ trans('label.business_communications.'.$item['method'],[],'vi') }}</div>
					@endisset
				</td>
				<td class="data" style="width:6%;text-align: center">
					{{ $item['unit'] }}
				</td>
				<td class="data" style="width:10%;text-align: right">
					{{ isset($item['amount']) ? Common::formatNumeralWithoutZeroDecimal($item['amount']) : '' }}
				</td>
				<td class="data" style="width:10%;text-align: right">
					{{ isset($item['exchange_rate']) ? number_format($item['exchange_rate']) : '' }}
				</td>
				<td class="data" style="width:10%;text-align: right">
					@php
					$amount = $item['amount'] ?? 0;
					$rate = $item['exchange_rate'] ?? 1;
					$sub = $amount * $rate;
					@endphp
					{{ number_format($sub) }}
				</td>
				<td class="data jp" style="width:32%">
					{{ mb_strlen($item['note']) > 40 ? mb_substr($item['note'], 0, 40).'...' : $item['note'] }}
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td class="f item1">
					<div class="caption">{{ trans('label.business_communication',[],'en') }}</div>
					<div class="caption">{{ trans('label.business_communication',[],'vi') }}</div>
				</td>
				<td class="item2"></td>
				<td class="item3"></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			@endif

			{{-- TripFees - otherfees --}}
			@if (isset($inputs['otherfees']) && count($inputs['otherfees']) > 0)
			@php
			$rowsSpan = count($inputs['otherfees']);
			@endphp
			@foreach ($inputs['otherfees'] as $index => $item)
			<tr>
				@if ($index == 0)
				<td rowspan="{{ $rowsSpan }}" class="f item1">
					<div class="caption">{{ trans('label.business_other_fees',[],'en') }}</div>
					<div class="caption">{{ trans('label.business_other_fees',[],'vi') }}</div>
				</td>
				@endif
				<td class="f item2">
					{{ config('const.trip_fee_type.otherfees').($index + 1) }}
				</td>
				<td class="f item3"></td>
				<td class="data" style="width:6%;text-align: center">
					{{ $item['unit'] }}
				</td>
				<td class="data" style="width:10%;text-align: right">
					{{ isset($item['amount']) ? Common::formatNumeralWithoutZeroDecimal($item['amount']) : '' }}
				</td>
				<td class="data" style="width:10%;text-align: right">
					{{ isset($item['exchange_rate']) ? number_format($item['exchange_rate']) : '' }}
				</td>
				<td class="data" style="width:10%;text-align: right">
					@php
					$amount = $item['amount'] ?? 0;
					$rate = $item['exchange_rate'] ?? 1;
					$sub = $amount * $rate;
					@endphp
					{{ number_format($sub) }}
				</td>
				<td class="data jp" style="width:32%">
					{{ mb_strlen($item['note']) > 40 ? mb_substr($item['note'], 0, 40).'...' : $item['note'] }}
				</td>
			</tr>
			@endforeach
			@else
			<tr>
				<td class="f item1">
					<div class="caption">{{ trans('label.business_other_fees',[],'en') }}</div>
					<div class="caption">{{ trans('label.business_other_fees',[],'vi') }}</div>
				</td>
				<td class="item2"></td>
				<td class="item3"></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			@endif
		</tbody>
	</table>
	<div class="br"></div>
	{{-- Daily allowances --}}
	<table style="clear: both;">
		<tbody>
			<tr>
				<td rowspan="4" class="f" style="width: 32%">
					<div class="caption">{{ trans('label.business_daily_allowance',[],'en') }}</div>
					<div class="caption">{{ trans('label.business_daily_allowance',[],'vi') }}</div>
				</td>
				<td class="f" style="text-align: center;width:6%;">
					<div class="caption">Currency</div>
					<div class="caption">Tiền tệ</div>
				</td>
				<td class="f" style="text-align: center;width:10%;">
					<div class="caption">Amount</div>
					<div class="caption">Số tiền</div>
				</td>
				<td class="f" style="text-align: center;width:10%;">
					<div class="caption">Exchange rate</div>
					<div class="caption">Tỷ giá</div>
				</td>
				<td class="f" style="text-align: center;width:10%;">
					<div class="caption">Days</div>
					<div class="caption">Số ngày</div>
				</td>
				<td class="f" style="text-align: center;width:10%;">
					<div class="caption">VND(SUB)</div>
					<div class="caption">Tạm tính</div>
				</td>
				<td style="width: 22%"></td>
			</tr>
			<tr>
				<td class="data txt-center">VND</td>
				<td class="data txt-right">{{ isset($inputs['daily1_amount']) ? Common::formatNumeralWithoutZeroDecimal($inputs['daily1_amount']) : '' }}</td>
				<td class="data txt-right"></td>
				<td class="data txt-right">{{ $inputs['daily1_days'] }}</td>
				<td class="data txt-right">
					@php
						$totalDaily = 0;
						$amount = $inputs['daily1_amount'] ?? 0;
						$days = $inputs['daily1_days'] ?? 0;
						$sub = $amount * $days;
						$totalDaily += $sub;
					@endphp
					{{ number_format($sub) }}
				</td>
				<td></td>
			</tr>
			<tr>
				<td class="data txt-center">USD</td>
				<td class="data txt-right">
					{{ isset($inputs['daily2_amount']) ? Common::formatNumeralWithoutZeroDecimal($inputs['daily2_amount']) : '' }}
				</td>
				<td class="data txt-right">
					{{ isset($inputs['daily2_rate']) ? Common::formatNumeralWithoutZeroDecimal($inputs['daily2_rate']) : '' }}
				</td>
				<td class="data txt-right">{{ $inputs['daily2_days'] }}</td>
				<td class="data txt-right">
					@php
						$amount = $inputs['daily2_amount'] ?? 0;
						$rate = $inputs['daily2_rate'] ?? 1;
						$days = $inputs['daily2_days'] ?? 0;
						$sub = $amount * $rate * $days;
						$totalDaily += $sub;
					@endphp
					{{ number_format($sub) }}
				</td>
				<td></td>
			</tr>
			<tr>
				<td colspan="4" class="txt-right">Total / Tổng</td>
				<td class="data txt-right">{{ number_format($totalDaily) }}</td>
				<td></td>
			</tr>
			{{-- Total --}}
			<tr>
				<td class="f">
					<div class="caption">{{ trans('label.business_total_expenses',[],'en') }}</div>
					<div class="caption">{{ trans('label.business_total_expenses',[],'vi') }}</div>
				</td>
				<td colspan="2" class="data txt-right" style="font-weight: bold;vertical-align: middle">
					@php
						$totalExpenses = App\Models\Businesstrip2::calculateTotalExpenses($inputs);
					@endphp
					{{ Common::formatNumeralWithoutZeroDecimal($totalExpenses).' VND' }}
				</td>
				<td colspan="4" class="f">
					<div class="caption">Please attach invoices, receipts or any evidence to each expense listed above.</div>
					<div class="caption">Các chi phí liệt kê phải đính kèm hóa đơn, chứng từ.</div>
				</td>
			</tr>
		</tbody>
	</table>
	{{-- Cost to be charged --}}
	@isset($inputs['chargedbys'])
		<div class="br"></div>
		<table style="clear: both;">
			<tbody>
				@php
				$rowSpan = count($inputs['chargedbys']);
				@endphp
				@foreach ($inputs['chargedbys'] as $index => $item)
				<tr>
					@if ($index == 0)
					<td rowspan="{{ $rowSpan }}" class="f" style="width: 32%">
						<div class="caption">{{ trans('label.business_charged_to',[],'en') }}</div>
						<div class="caption">{{ trans('label.business_charged_to',[],'vi') }}</div>
					</td>
					@endif
					<td style="width: 26%">
						@php
						$department = App\Models\Department::find($item['department']);
						@endphp
						@if(isset($department))
						{{ $department->name }}
						@else
						<div style="height: 10px"></div>
						@endif
					</td>
					<td style="width: 20%">{{ $item['percent'] }}</td>
					@if ($index == 0)
					<td rowspan="{{ $rowSpan }}" class="f" style="text-align: center">%</td>
					@endif
				</tr>
				@endforeach
			</tbody>
		</table>
	@endisset

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