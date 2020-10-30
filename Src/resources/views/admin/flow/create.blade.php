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
  <form method="POST" action="">  
    <div class="row">
        <div class="col-12 col-lg-10">
            <div class="form-group row">
                <label class="col-lg-3 col-form-label text-center">Approval No</label>
                <div class="col-lg-9">
                <input type="text" name="approval-no" class="form-control" placeholder="Approval No">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-lg-3 col-form-label text-center">Approval Flow Name</label>
                <div class="col-lg-9">
                <input type="text" class="form-control" placeholder="Approval Flow Name">
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
                <div class="form-check-inline"><label class="form-check-label"><input type="radio" checked="checked"  name="trip">Economy Class</label></div>            
                <div class="form-check-inline"><label class="form-check-label"><input type="radio"  name="trip">Business Class</label></div>
              </div>
            </div>
            <div class="form-group row form-entertaiment" style="display: none">
              <label class="col-lg-3 col-form-label text-center">Type</label>
              <div class="col-lg-9">
                <div class="form-check-inline"><label class="form-check-label"><input type="radio" checked="checked"  name="PO">PO</label></div>            
                <div class="form-check-inline"><label class="form-check-label"><input type="radio"  name="PO">Not PO</label></div>
              </div>
            </div>            
            <div class="form-group row">
              <label class="col-lg-3 col-form-label text-center">Applicant Role</label>
              <div class="col-lg-9">
              <select id="place" class="form-control select2">
                <option value='0' selected>{{ __('label.select') }}</option>
                 @foreach ($applicantRoles as $item)  
                 <option value=" {{ $item['id'] }}"> {{ $item['name'] }}</option>
                 @endforeach          
              </select>
              </div>
            </div>

            <h5 class="mt-5">STEP 1 <span class="type-not-leave" style="display: none">(Budget for per person : 4.000.000)</span></h5>
            <div class="border border-secondary p-3 wrap-step-1">
                <div class="text-muted">Approver</div>
                <table class="table table-bordered table-sm">
                    <tr>
                        <td style="width: 20%;">Destination</td>
                        <td class="text-left">
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio" checked="checked" name="step-1-dest-1">To
                                </label>
                            </div>
                            <div class="form-check-inline">
                                <label class="form-check-label">
                                  <input type="radio"  name="step-1-dest-1">CC
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-middle">Approver</td>
                        <td class="p-0"><input type="text" class="form-control" placeholder="Approver"></td>
                    </tr>
                </table>

                      
                <div class="block-add-approver-1">
                  <button type="button" data-step="1" data-index="1" class="btn-add-approver btn btn-outline-dark pt-0 pb-0 pl-3 pr-3">+ Add</button>
                </div>
            </div>

            <div class="mt-3 block-add-step"><button type="button" data-step="1" data-index="0" class="btn-add-step btn btn-outline-dark pt-1 pb-1 pl-3 pr-3">+ Step</button></div>      

            <div class="mt-5 mb-5 text-center">
                <button type="button" class="btn btn-danger pt-1 pb-1 mr-4 col-5 col-sm-2 col-md-4 col-lg-2"><i class="nav-icon far fa-check-circle"></i> Submit</button>
                <a href="/admin/flow-setting/list" class="btn btn-outline-dark pt-1 pb-1 col-5 col-sm-2 col-md-4 col-lg-2"><i class="nav-icon far fa-times-circle"></i> Cancel</a>
            </div>
        </div>
    </div>
  </form>
  </section>
  <!-- /.content -->
@endsection