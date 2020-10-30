@extends('layouts.master')
@section('title','Approval Flow List')
@section('content')
 <!-- Content Header (Page header) -->
    <section class="content-header mb-0">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-lg-6">
            <h1>Approval Flow List</h1>
          </div>
        </div>
      </div>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="button_wrap">
        <a href="{{ route('admin.flow.create') }}" class="btn btn-danger pt-1 pb-1 pl-5 pr-5 mb-2"><i class="nav-icon fa fa-plus-circle"></i> Add</a>
      </div>
      <div class="card">
        <div class="card-body p-0 ">
    <div class="wrap_tbl_ad">
      <div class="content_roll">
          <table class="table table-bordered " style="min-width: 500px;">
            <thead>
            <tr>
              <th style="width: 10px;">No</th>
              <th>Flow Name</th>
              <th>Step</th>
              <th>Final Approver</th>
              <th style="width: 150px;">Actions</th>
            </tr>
            </thead>
            <tbody>
              <tr>
                <td>01</td>
                <td>ACB HN</td>
                <td>1</td>
                <td>TEXT TEXT</td>
                <td><a href="/admin/flow-setting/edit/1" class="text-primary"><u>Edit</u></a> <a href="" class="text-danger"><u>Delete</u></a></td>
              </tr>
              <tr>
                <td>02</td>
                <td>ACB HCM</td>
                <td>2</td>
                <td>TEXT TEXT</td>
                <td><a href="" class="text-primary"><u>Edit</u></a> <a href="" class="text-danger"><u>Delete</u></a></td>
              </tr>
              <tr>
                <td>01</td>
                <td>ACB HN</td>
                <td>1</td>
                <td>TEXT TEXT</td>
                <td><a href="" class="text-primary"><u>Edit</u></a> <a href="" class="text-danger"><u>Delete</u></a></td>
              </tr>
              <tr>
                <td>02</td>
                <td>ACB HCM</td>
                <td>2</td>
                <td>TEXT TEXT</td>
                <td><a href="" class="text-primary"><u>Edit</u></a> <a href="" class="text-danger"><u>Delete</u></a></td>
              </tr>
              <tr>
                <td>01</td>
                <td>ACB HN</td>
                <td>1</td>
                <td>TEXT TEXT</td>
                <td><a href="" class="text-primary"><u>Edit</u></a> <a href="" class="text-danger"><u>Delete</u></a></td>
              </tr>
              <tr>
                <td>02</td>
                <td>ACB HCM</td>
                <td>2</td>
                <td>TEXT TEXT</td>
                <td><a href="" class="text-primary"><u>Edit</u></a> <a href="" class="text-danger"><u>Delete</u></a></td>
              </tr>
              <tr>
                <td>01</td>
                <td>ACB HN</td>
                <td>1</td>
                <td>TEXT TEXT</td>
                <td><a href="" class="text-primary"><u>Edit</u></a> <a href="" class="text-danger"><u>Delete</u></a></td>
              </tr>
              <tr>
                <td>02</td>
                <td>ACB HCM</td>
                <td>2</td>
                <td>TEXT TEXT</td>
                <td><a href="" class="text-primary"><u>Edit</u></a> <a href="" class="text-danger"><u>Delete</u></a></td>
              </tr>
              <tr>
                <td>01</td>
                <td>ACB HN</td>
                <td>1</td>
                <td>TEXT TEXT</td>
                <td><a href="" class="text-primary"><u>Edit</u></a> <a href="" class="text-danger"><u>Delete</u></a></td>
              </tr>
              <tr>
                <td>02</td>
                <td>ACB HCM</td>
                <td>2</td>
                <td>TEXT TEXT</td>
                <td><a href="" class="text-primary"><u>Edit</u></a> <a href="" class="text-danger"><u>Delete</u></a></td>
              </tr>
            
            </tbody>
          </table>
        </div>
          <div class="clearfix p-0 text-center pb-3">
            <div class="pager-wrap">
              <ul class="pagination pagination-sm pager_custom ">
                <li class="page-item"><a class="page-link" href="#">«</a></li>
                <li class="page-item"><a class="page-link active_page" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">5</a></li>
                <li class="page-item"><a class="page-link" href="#">6</a></li>
                <li class="page-item"><a class="page-link" href="#">7</a></li>
                <li class="page-item"><a class="page-link" href="#">8</a></li>
                <li class="page-item"><a class="page-link" href="#">9</a></li>
                <li class="page-item"><a class="page-link" href="#">»</a></li>
              </ul>
              <div class="bt_direct">
                <button type="button" class="bt_left float-left btn"><i class="fa fa-long-arrow-alt-left " ></i> Back</button>
                <button type="button" class="bt_right float-right btn">Next <i class="nav-icon fas fa-long-arrow-alt-right" ></i></button>
              </div>
            </div>
          </div>
      </div>

        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->
@endsection