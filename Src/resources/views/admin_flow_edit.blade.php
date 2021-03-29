@extends('layouts.master')
@section('title','Approval Flow Setting')
@section('css')
<link rel="stylesheet" href="css/admin/admin_102_shain_ichiran.css">
@endsection
@section('js')
<!-- Select2 -->
<script src="css/lib/select2/js/select2.full.min.js"></script>
<!-- jquery-validation -->
<script src="js/jquery-validation/jquery.validate.min.js"></script>
<script src="js/jquery-validation/additional-methods.min.js"></script>
<script src="js/admin/const_{{ config('app.locale') }}.js"></script>
<script src="js/admin/flow-setting.js"></script>
@endsection

@section('content-header')
{{ __('label.flow.approval_flow_setting') }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.flow.index') }}">{{ __('label.flow.approval_flow_list') }}</a></li>
<li class="breadcrumb-item active">{{ __('label.flow.approval_flow_setting') }}</li>
@endsection

@section('content')
  @if(!$canEdit)
  <div class="alert alert-danger alert-dismissible fade show">
        <h6 class="alert-msg"><i class="icon fas fa-ban"></i>{{ __('label.flow.can_not_update') }}</h6>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  @endif
  <!-- Main content -->
  <section class="content">
  <form id="frmFlowSetting" class="frm-flow-setting" name="frmFlowSetting" method="POST" action="{{ route('admin.flow.update', $flow->id) }}"> 
    @csrf 
    <div class="row">
        <div class="col-12 col-lg-10">
            <div class="form-group row">
                <label class="col-lg-3 col-form-label text-center">{{ __('label.flow.approval_no') }}</label>
                <div class="col-lg-9">
                <input type="text" name="approval-no" value="{{ $flow->id }}" disabled="disabled" class="form-control" placeholder="Approval No">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label text-center">{{ __('label.flow.approval_flow_name') }}</label>
                <div class="col-lg-9 block-item">
                <input type="text" class="form-control" value="{{ $flow->flow_name }}" @if(!$canEdit) disabled="disabled" @endif name="approval_flow_name" placeholder="{{ __('label.flow.approval_flow_name') }}">
                </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-3 col-form-label text-center">{{ __('label.flow.application_form') }}</label>
              <div class="col-lg-9 block-item">
              <select id="cbxForm" class="form-control select2" name="application_form" @if(!$canEdit) disabled="disabled" @endif>
                <option value='' selected>{{ __('label.select') }}</option>
                @foreach ($forms as $item)
                <option value=" {{ $item->id }}" @if(strval($flow->form_id) === strval($item->id)) selected @endif>{{ __('label.form.'.$item->id) }}</option>              
                @endforeach
              </select>
              </div>
            </div>
            <div class="form-group row form-trip" @if(strval($flow->form_id) !== '2') style="display: none" @endif> 
              <label class="col-lg-3 col-form-label text-center">{{ __('label.flow.type') }}</label>
              <div class="col-lg-9">
                <div class="form-check-inline"><label class="form-check-label"><input type="radio" value="3" @if(!$canEdit) disabled="disabled" @endif name="trip" @if(strval($flow->position) === '3') checked="checked" @endif>{{ __('label.budget.economy_class') }}</label></div>
                <div class="form-check-inline"><label class="form-check-label"><input type="radio" value="4" @if(!$canEdit) disabled="disabled" @endif  name="trip" @if(strval($flow->position) === '4') checked="checked" @endif>{{ __('label.budget.business_class') }}</label></div>
              </div>
            </div>
            <div class="form-group row form-entertaiment" @if(strval($flow->form_id) !== '3') style="display: none" @endif>
              <label class="col-lg-3 col-form-label text-center">{{ __('label.flow.type') }}</label>
              <div class="col-lg-9">
                <div class="form-check-inline"><label class="form-check-label"><input @if(!$canEdit) disabled="disabled" @endif class="typePosition" id="positionPO" type="radio" value="1" @if(strval($flow->position) === '1') checked="checked" @endif name="PO">{{ __('label.budget.po') }}</label></div>
                <div class="form-check-inline"><label class="form-check-label"><input @if(!$canEdit) disabled="disabled" @endif class="typePosition" id="positionNotPO" type="radio" value="2" name="PO" @if(strval($flow->position) === '2') checked="checked" @endif>{{ __('label.budget.not_po') }}</label></div>
              </div>
            </div> 
            <div class="form-group row form-entertaiment form-po" @if(strval($flow->form_id) !== '3' || strval($flow->position) === '2') style="display: none" @endif>
              <label class="col-lg-3 col-form-label text-center">{{ __('label.flow.budget_for_per_person') }}</label>
              <div class="col-lg-9">
              <select id="cbxBudgetTypePO" name="budget_type_po" class="form-control select2" @if(!$canEdit) disabled="disabled" @endif>
                 <option value="0" @if(strval($flow->budget_type_compare) === '0') selected @endif>{{ __('label.flow.less_or_equal_than') }} {{ $budgetPO }}</option>
                 <option value="1" @if(strval($flow->budget_type_compare) === '1') selected @endif>{{ __('label.flow.greater_than') }} {{ $budgetPO }}</option>
              </select>
              </div>
            </div> 
            <div class="form-group row form-entertaiment form-not-po" @if(strval($flow->form_id) !== '3' || strval($flow->position) === '1') style="display: none" @endif>
              <label class="col-lg-3 col-form-label text-center">{{ __('label.flow.budget_for_per_person') }}</label>
              <div class="col-lg-9">
              <select id="cbxBudgetTypeNotPO" name="budget_type_not_po" class="form-control select2" @if(!$canEdit) disabled="disabled" @endif>
                 <option value="0" @if(strval($flow->budget_type_compare) === '0') selected @endif>{{ __('label.flow.less_or_equal_than') }} {{ $budgetNotPO }}</option>
                 <option value="1" @if(strval($flow->budget_type_compare) === '1') selected @endif>{{ __('label.flow.greater_than') }} {{ $budgetNotPO }}</option>
              </select>
              </div>
            </div>                      
            <div class="form-group row">
              <label class="col-lg-3 col-form-label text-center">{{ __('label.flow.applicant_role') }}</label>
              <div class="col-lg-9 block-item">
              <select id="cbxApplicantRole" name="applicant" class="form-control select2" @if(!$canEdit) disabled="disabled" @endif>
                <option value='' selected>{{ __('label.select') }}</option>
                 @foreach ($applicantRoles as $item)
                 <option value=" {{ $item['id'] }}" @if(strval($flow->applicant_id) === strval($item['id'])) selected @endif> {{ $item['name'] }}</option>
                 @endforeach
              </select>
              </div>
            </div>

            
            @php
            $start_step = -1;
            $step_index = 0;
            @endphp
            @foreach ($steps as $step)
                @php
                    if(strval($flow->form_id) === '1'){
                      $step->step_type = 1;
                    }
                @endphp
                @if($step->step_type != $start_step)
                   
                    @if($start_step === -1)
            <h5 class="mt-5">{{ __('label.flow.step') }} {{$step->step_type}}</h5>        
            <div class="border border-secondary p-3 wrap-step-1 section-step-{{ $step->step_type }}">
                    @else                      
                    <div class="block-add-approver-{{ $start_step }}">
                          <button @if(!$canEdit) disabled="disabled" @endif type="button" data-step="{{ $start_step }}" data-index="{{$step_index + 1}}" class="btn-add-approver btn btn-outline-dark pt-0 pb-0 pl-3 pr-3">+ {{ __('label.flow.add') }}</button>
                    </div>
                    </div>
                    <div class="section-step section-step-{{ $step->step_type }}">
                      <div class="d-flex justify-between mt-5">
                        <h5>{{ __('label.flow.step') }} <span class="title-step">{{$step->step_type}}</span></h5>
                        <div><button @if(!$canEdit) disabled="disabled" @endif type="button" data-step="'+step+'" class="btn-del-step btn btn-danger pt-1 pb-1 pl-3 pr-3 mb-1">{{ __('label.flow.delete') }}</button></div>
                      </div>
                      <div class="approver-{{ $step->step_type }}-{{$step_index + 1}}">
                        <div class="border border-secondary p-3 wrap-step-1">
                      @php                  
                      $step_index = 0;
                      @endphp      
                    @endif
                @endif
                @if($step_index > 0)
                <div class="section-approver approver-{{ $step->step_type }}-{{$step_index + 1}}">    
                  <div class="d-flex justify-between">
                    <div class="text-muted">{{ __('label.flow.approver') }} <span class="title-approver">{{ $step_index + 1}}</span></div>
                    <div>
                      <button @if(!$canEdit) disabled="disabled" @endif type="button" data-step="{{ $step->step_type }}" data-index="{{$step_index + 1}}" class="btn-del-approver btn btn-danger btn-sm pt-0 pb-0 pl-3 pr-3 mb-1">{{ __('label.flow.delete') }}</button>
                    </div>
                         
                  </div>
                @else
                  <div class="text-muted">{{ __('label.flow.approver') }} {{$step_index + 1}}</div>
                @endif
                  <table class="table table-bordered table-sm">
                      <tr>
                          <td style="width: 20%;">{{ __('label.flow.destination') }}</td>
                          <td class="text-left">
                              <div class="form-check-inline">
                                  <label class="form-check-label">
                                    <input @if(!$canEdit) disabled="disabled" @endif type="radio" value="0" name="destination[{{ $step->step_type }}][{{ $step_index }}]" @if(strval($step->approver_type) === '0') checked="checked" @endif>To
                                  </label>
                              </div>
                              <div class="form-check-inline">
                                  <label class="form-check-label">
                                    <input @if(!$canEdit) disabled="disabled" @endif type="radio" value="1" name="destination[{{ $step->step_type }}][{{ $step_index }}]" @if(strval($step->approver_type) === '1') checked="checked" @endif>CC
                                  </label>
                              </div>
                              <div>
                              <span id="destination-{{ $step_index }}-error" class="invalid-feedback destination-{{ $step_index }}-error destination-error">
                              @if($step_index > 0)  
                               {{ __('label.flow.destination_invalid_end') }}
                              @else 
                               {{ __('label.flow.destination_invalid_begin') }}
                              @endif
                              </span>
                            </div>
                          </td>
                      </tr>
                      <tr>
                          <td class="align-middle">{{ __('label.flow.approver') }}</td>
                          <td class="p-0 text-left block-item">
                            <select id="cbxApprover-{{ $step_index }}" name="approver[{{ $step->step_type }}][{{ $step_index }}]" class="form-control select2 cbx-approver" @if(!$canEdit) disabled="disabled" @endif>
                              <option value='' selected>{{ __('label.select') }}</option>
                               @foreach ($users as $item)  
                               <option value=" {{ $item->id }}" @if(strval($step->approver_id) === strval($item->id)) selected @endif> {{ $item->name }} ({{ $item->email }})</option>
                               @endforeach
                            </select>
                            <span id="cbxApprover-0-error" class="invalid-feedback">{{ __('label.flow.approver_required') }}</span>
                          </td>
                      </tr>
                  </table>
                @if($step_index > 0) 
                </div>
                @endif
            @php
            $start_step = $step->step_type;
            $step_index++;
            @endphp
            @endforeach   
              <div class="block-add-approver-{{ $start_step }}">
                    <button @if(!$canEdit) disabled="disabled" @endif type="button" data-step="{{  $start_step }}" data-index="{{ $step_index }}" class="btn-add-approver btn btn-outline-dark pt-0 pb-0 pl-3 pr-3">+ {{ __('label.flow.add') }}</button>
              </div>
            </div>
            </div>
            </div>
            <div class="mt-3 block-add-step" style="display: none"><button @if(!$canEdit) disabled="disabled" @endif type="button" data-step="1" data-index="0" class="btn-add-step btn btn-outline-dark pt-1 pb-1 pl-3 pr-3">+ {{ __('label.flow.step') }}</button></div>


            <div class="mt-5 mb-5 text-center">
                <button @if(!$canEdit) disabled="disabled" @endif type="button" class="btn-submit-flow btn btn-danger pt-1 pb-1 mr-4 col-5 col-sm-2 col-md-4 col-lg-2"><i class="nav-icon far fa-check-circle"></i> {{ __('label.flow.update') }}</button>
                <a href="{{ route('admin.flow.index') }}" class="btn btn-outline-dark pt-1 pb-1 col-5 col-sm-2 col-md-4 col-lg-2"><i class="nav-icon far fa-times-circle"></i> {{ __('label.flow.cancel') }}</a>
            </div>
        </div>
    </div>
    <input type="hidden" name="index-idx" id="index-idx" value="{{ count($steps) }}">
    <input type="hidden" name="flow-id" id="flow-id" value="{{ $flow->id }}">
    @foreach ($budgets as $item)
    <input type="hidden" name="budget_form_{{ $item->position }}_step_{{ $item->step_type }}" id="budget-form-{{ $item->position }}-step-{{ $item->step_type }}" data-amount="{{ $item->amount }}" value="{{ $item->id }}">
    @endforeach
  </form>
  </section>
  <!-- /.content -->
@endsection

