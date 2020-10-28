@extends('layouts.master')
@section('title','ADMIN_DASHBOARD')
@section('content')
		<script type="text/javascript">
				$(function () {  
					$('#dateFrom').datetimepicker({
					format: 'ddd, DD/MM/YYYY',
					defaultDate: moment()
					});
				});
				</script>
   <!-- Content Header (Page header) -->
   <section class="content-header">
      <div class="row ml-0">
        <div class="col-sm-4">
          <!-- <input type="text" id="datepicker" class="input-datepicker" readonly> -->
          <div class="input-group date" id="dateFrom" data-target-input="nearest">
            <div class="input-group-append" data-target="#dateFrom" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
            </div>
            <input type="text" name="dateFrom" class="form-control datetimepicker-input" data-target="#dateFrom"/>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">

      <h4 class="mb-2" style="border-bottom: 1px solid #000;font-weight: bold;margin-top: 25px;"><i class="nav-icon fas fa-file-alt" aria-hidden="true" style="margin-right: 5px;margin-bottom: 5px;"></i>List of Applications</h4>
      <div class="card">
        <div class="card-body p-0">
          <div class="wrap_tbl_ad">
            <div class="content_roll">
              <table class="table">
                <thead>
                  <tr>
                    <th>Application No</th>
                    <th>Application Name</th>
                    <th>Status</th>
                    <th>Apply Date</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>NO-TM-0000</td>
                    <td>交際費申請書</td>
                    <td>申請中</td>
                    <td>13 agu 2018</td>
                    <td>
                      <button type="button" class="btn btn-block btn-secondary">Details</button>
                    </td>
                  </tr>
                  <tr>
                    <td>NO-BT-0000</td>
                    <td>出張申請書</td>
                    <td>差戻</td>
                    <td>13 agu 2018</td>
                    <td>
                      <button type="button" class="btn btn-block btn-secondary">Details</button>
                    </td>
                  </tr>
                  <tr>
                    <td>NO-PH-0000</td>
                    <td>有給申請書</td>
                    <td>却下</td>
                    <td>13 agu 2018</td>
                    <td>
                      <button type="button" class="btn btn-block btn-secondary">Details</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>         
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection