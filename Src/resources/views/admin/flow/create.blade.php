@extends('layouts.master')
@section('title','Approval Flow Setting')
@section('css')
<link rel="stylesheet" href="css/admin/admin_102_shain_ichiran.css">
@endsection
@section('js')
<script src="js/admin/flow-setting.js"></script>
@endsection
@section('content')
  <!-- Content Header (Page header) -->
    <section class="content-header mb-2">
      <h1>Approval Flow Setting</h1>
  </section>

  <!-- Main content -->
  <section class="content">
  <form method="POST" action="{{ route('admin.flow.create') }}"> 
    @csrf 
    <div class="row">
        <div class="col-12 col-lg-10">
            <div class="form-group row">
                <label class="col-lg-3 col-form-label text-center">Approval No</label>
                <div class="col-lg-9">
                <input type="text" name="approval-no" value="{{ $flowNo }}" disabled="disabled" class="form-control" placeholder="Approval No">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label text-center">Approval Flow Name</label>
                <div class="col-lg-9">
                <input type="text" class="form-control" name="approval-flow-name" placeholder="Approval Flow Name">
                </div>
            </div>
            <div class="form-group row">
              <label class="col-lg-3 col-form-label text-center">Application Form</label>
              <div class="col-lg-9">
              <select id="cbxForm" class="form-control select2" name="application-form">
                <option value='0' selected>{{ __('label.select') }}</option>
                @foreach ($forms as $item)
                <option value=" {{ $item->id }}"> {{ $item->name }}</option>
                @endforeach
              </select>
              </div>
            </div>
            <div class="form-group row form-trip" style="display: none"> 
              <label class="col-lg-3 col-form-label text-center">Type</label>
              <div class="col-lg-9">
                <div class="form-check-inline"><label class="form-check-label"><input type="radio" checked="checked" value="Economy" name="trip">Economy Class</label></div>            
                <div class="form-check-inline"><label class="form-check-label"><input type="radio" value="Business"  name="trip">Business Class</label></div>
              </div>
            </div>
            <div class="form-group row form-entertaiment" style="display: none">
              <label class="col-lg-3 col-form-label text-center">Type</label>
              <div class="col-lg-9">
                <div class="form-check-inline"><label class="form-check-label"><input type="radio" value="PO" checked="checked"  name="PO">PO</label></div>            
                <div class="form-check-inline"><label class="form-check-label"><input type="radio" value="Not_PO" name="PO">Not PO</label></div>
              </div>
            </div>            
            <div class="form-group row">
              <label class="col-lg-3 col-form-label text-center">Applicant Role</label>
              <div class="col-lg-9">
              <select id="cbxApplicantRole" name="applicant" class="form-control select2">
                <option value='0' selected>{{ __('label.select') }}</option>
                 @foreach ($applicantRoles as $item)  
                 <option value=" {{ $item['id'] }}"> {{ $item['name'] }}</option>
                 @endforeach
              </select>
              </div>
            </div>

            <h5 class="mt-5">STEP 1</h5>
            <div class="border border-secondary p-3 wrap-step-1 section-step-1">
                <div class="text-muted">Approver 1</div>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td style="width: 20%;">Destination</td>
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
                        </td>
                    </tr>
                    <tr>
                        <td class="align-middle">Approver</td>
                        <td class="p-0 text-left">
                          <select id="cbxApprover-0" name="approver[1][0]" class="form-control select2 cbx-approver">
                            <option value='0' selected>{{ __('label.select') }}</option>
                             @foreach ($users as $item)  
                             <option value=" {{ $item->id }}"> {{ $item->name }} ({{ $item->email }})</option>
                             @endforeach
                          </select>
                        </td>
                    </tr>
                </table>
                    
                <div class="block-add-approver-1">
                  <button type="button" data-step="1" data-index="1" class="btn-add-approver btn btn-outline-dark pt-0 pb-0 pl-3 pr-3">+ Add</button>
                </div>
            </div>

            <div class="mt-3 block-add-step"><button type="button" data-step="1" data-index="0" class="btn-add-step btn btn-outline-dark pt-1 pb-1 pl-3 pr-3">+ Step</button></div>      

            <div class="mt-5 mb-5 text-center">
                <button type="submit" class="btn btn-danger pt-1 pb-1 mr-4 col-5 col-sm-2 col-md-4 col-lg-2"><i class="nav-icon far fa-check-circle"></i> Submit</button>
                <a href="{{ route('admin.flow.list') }}" class="btn btn-outline-dark pt-1 pb-1 col-5 col-sm-2 col-md-4 col-lg-2"><i class="nav-icon far fa-times-circle"></i> Cancel</a>
            </div>
        </div>
    </div>
    <input type="hidden" name="index-idx" id="index-idx" value="1">
    @foreach ($budgets as $item)
    <input type="hidden" name="budget-form-{{ $item->position }}-step-{{ $item->step_type }}" id="budget-form-{{ $item->position }}-step-{{ $item->step_type }}" data-amount="{{ $item->amount }}" value="{{ $item->id }}">
    @endforeach
  </form>
  </section>
  <!-- /.content -->
@endsection