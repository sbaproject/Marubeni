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
{{ __('label.flow_approval_flow_setting') }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.flow.index') }}">{{ __('label.flow_approval_flow_list') }}</a></li>
<li class="breadcrumb-item active">{{ __('label.flow_approval_flow_setting') }}</li>
@endsection

@section('content')
  <!-- Content Header (Page header) -->
    {{-- <section class="content-header mb-2">
      <h1>{{ __('label.flow_approval_flow_setting') }}</h1>
  </section> --}}

  <!-- Main content -->
  <section class="content">
  <form id="frmFlowSetting" class="frm-flow-setting" name="frmFlowSetting" method="POST" action="{{ route('admin.flow.create') }}"> 
    @csrf 
    <div class="row">
        <div class="col-12 col-lg-10">
            <div class="form-group row">
                <label class="col-lg-3 col-form-label text-center">{{ __('label.flow_approval_no') }}</label>
                <div class="col-lg-9">
                <input type="text" name="approval-no" value="{{ $flowNo }}" disabled="disabled" class="form-control" placeholder="Approval No">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label text-center">{{ __('label.flow_approval_flow_name') }}</label>
                <div class="col-lg-9 block-item">
                <input type="text" class="form-control" name="approval_flow_name" placeholder="{{ __('label.flow_approval_flow_name') }}">
                </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-3 col-form-label text-center">{{ __('label.flow_application_form') }}</label>
              <div class="col-lg-9 block-item">
              <select id="cbxForm" class="form-control select2" name="application_form">
                <option value='' selected>{{ __('label.select') }}</option>
                @foreach ($forms as $item)
                <option value=" {{ $item->id }}"> {{ __('label.form.'.$item->id) }}</option>
                @endforeach
              </select>
              </div>
            </div>
            <div class="form-group row form-trip" style="display: none"> 
              <label class="col-lg-3 col-form-label text-center">{{ __('label.flow_type') }}</label>
              <div class="col-lg-9">
                <div class="form-check-inline"><label class="form-check-label"><input type="radio" checked="checked" value="3" name="trip">{{ __('label.budget_economy_class') }}</label></div>
                <div class="form-check-inline"><label class="form-check-label"><input type="radio" value="4"  name="trip">{{ __('label.budget_business_class') }}</label></div>
              </div>
            </div>
            <div class="form-group row form-entertaiment" style="display: none">
              <label class="col-lg-3 col-form-label text-center">{{ __('label.flow_type') }}</label>
              <div class="col-lg-9">
                <div class="form-check-inline"><label class="form-check-label"><input class="typePosition" id="positionPO" type="radio" value="1" checked="checked" name="PO">{{ __('label.budget_po') }}</label></div>
                <div class="form-check-inline"><label class="form-check-label"><input class="typePosition" id="positionNotPO" type="radio" value="2" name="PO">{{ __('label.budget_not_po') }}</label></div>
              </div>
            </div> 
            <div class="form-group row form-entertaiment form-po" style="display: none">
              <label class="col-lg-3 col-form-label text-center">{{ __('label.flow_budget_for_per_person') }}</label>
              <div class="col-lg-9">
              <select id="cbxBudgetTypePO" name="budget_type_po" class="form-control select2">
                 <option value="0">{{ __('label.flow_less_or_equal_than') }} {{ $budgetPO }}</option>
                 <option value="1">{{ __('label.flow_greater_than') }} {{ $budgetPO }}</option>
              </select>
              </div>
            </div> 
            <div class="form-group row form-entertaiment form-not-po" style="display: none">
              <label class="col-lg-3 col-form-label text-center">{{ __('label.flow_budget_for_per_person') }}</label>
              <div class="col-lg-9">
              <select id="cbxBudgetTypeNotPO" name="budget_type_not_po" class="form-control select2">
                 <option value="0">{{ __('label.flow_less_or_equal_than') }} {{ $budgetNotPO }}</option>
                 <option value="1">{{ __('label.flow_greater_than') }} {{ $budgetNotPO }}</option>
              </select>
              </div>
            </div>                      
            <div class="form-group row">
              <label class="col-lg-3 col-form-label text-center">{{ __('label.flow_applicant_role') }}</label>
              <div class="col-lg-9 block-item">
              <select id="cbxApplicantRole" name="applicant" class="form-control select2">
                <option value='' selected>{{ __('label.select') }}</option>
                 @foreach ($applicantRoles as $item)  
                 <option value=" {{ $item['id'] }}"> {{ $item['name'] }}</option>
                 @endforeach
              </select>
              </div>
            </div>

            <h5 class="mt-5">{{ __('label.flow_step_1') }}</h5>
            <div class="border border-secondary p-3 wrap-step-1 section-step-1">
                <div class="text-muted">{{ __('label.flow_approver') }} 1</div>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td style="width: 20%;">{{ __('label.flow_destination') }}</td>
                        <td class="text-left">
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" checked="checked" value="0" name="destination[1][0]">To
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" value="1" name="destination[1][0]">CC
                                </label>
                            </div>
                            <div>
                              <span id="destination-0-error" class="invalid-feedback destination-0-error destination-error">
                              {{ __('label.flow_destination_invalid_begin') }}
                              </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-middle">{{ __('label.flow_approver') }}</td>
                        <td class="p-0 text-left block-item">
                          <select id="cbxApprover-0" name="approver[1][0]" class="form-control select2 cbx-approver">
                            <option value='' selected>{{ __('label.select') }}</option>
                             @foreach ($users as $item)  
                             <option value=" {{ $item->id }}"> {{ $item->name }} ({{ $item->email }})</option>
                             @endforeach
                          </select>
                          <span id="cbxApprover-0-error" class="invalid-feedback">{{ __('label.flow_approver_required') }}</span>
                        </td>
                    </tr>
                </table>
                    
                <div class="block-add-approver-1">
                  <button type="button" data-step="1" data-index="1" class="btn-add-approver btn btn-outline-dark pt-0 pb-0 pl-3 pr-3">+ {{ __('label.flow_add') }}</button>
                </div>
            </div>

            <div class="mt-3 block-add-step">
              <button type="button" data-step="1" data-index="0" class="btn-add-step btn btn-outline-dark pt-1 pb-1 pl-3 pr-3">
              + {{ __('label.flow_step') }}</button>
            </div>      

            <div class="mt-5 mb-5 text-center">
                <button type="button" class="btn-submit-flow btn btn-danger pt-1 pb-1 mr-4 col-5 col-sm-2 col-md-4 col-lg-2"><i class="nav-icon far fa-check-circle"></i> {{ __('label.flow_submit') }}</button>
                <a href="{{ route('admin.flow.index') }}" class="btn btn-outline-dark pt-1 pb-1 col-5 col-sm-2 col-md-4 col-lg-2"><i class="nav-icon far fa-times-circle"></i> {{ __('label.flow_cancel') }}</a>
            </div>
        </div>
    </div>
    <input type="hidden" name="index-idx" id="index-idx" value="1">
    <input type="hidden" name="flow-id" id="flow-id" value="0">
    @foreach ($budgets as $item)
    <input type="hidden" name="budget_form_{{ $item->position }}_step_{{ $item->step_type }}" id="budget-form-{{ $item->position }}-step-{{ $item->step_type }}" data-amount="{{ $item->amount }}" value="{{ $item->id }}">
    @endforeach
  </form>
  </section>
  <!-- /.content -->
@endsection

