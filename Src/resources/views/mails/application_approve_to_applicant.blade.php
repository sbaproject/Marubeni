
<div>Your {{ $form_type }} Application with the following information has been {{ Str::lower($status) }} !</div>
<br>
<div>Application type: {{ $form_type }}</div>
<div>{{ $status }} approver: {{ $approver }}</div>
@if (!empty($comment))
	<div>Reason/Comment: <div style="white-space: pre-wrap;">{{ $comment }}</div></div>
@endif
<div>Application URL: {{ $url }}</div>