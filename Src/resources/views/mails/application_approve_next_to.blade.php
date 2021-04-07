<div>You are the next approver of a new application form with the following information:</div>
<br>
<div>Application type: {{ $form_type }}</div>
<div>Applicant name: {{ $applicant_name }}</div>
<div>Branch/ Department: {{ trans('label.' . array_search($applicant_location, config('const.location')), [], 'en') }} / {{ $department_name }}</div>
<div>Application URL: {{ $url }}</div>