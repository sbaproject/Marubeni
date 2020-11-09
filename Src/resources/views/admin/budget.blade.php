@extends('layouts.master')
@section('title', 'ADMIN_BUDGET')
@section('js')

@endsection
@section('content')
    <div class="row">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body">
                    <x-alert />
                    <div class="search-content">
                        <form method="post" id="formBudget" action="">
                            @csrf
                            <h4>BUSINESS TRIP</h4>
                            <div class="card card-body card-company">
                                <div class="row">
                                    <label for=""
                                        class="col-lg-2 col-form-label text-center d-flex align-items-center justify-content-center">Assignment</label>
                                    <div class="col-lg-10">
                                        <div class="form-group row" style="margin-bottom: 4px;">
                                            <label for=""
                                                class="col-lg-2 col-form-label text-center font-weight-normal">Economy
                                                Class</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount1') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount1', $amount1->amount) }}" name='amount1' id=""
                                                    placeholder="Economy Class">
                                                @error('amount1')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for=""
                                                class="col-lg-2 col-form-label text-center font-weight-normal">Business
                                                Class</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount2') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount2', $amount2->amount) }}" name='amount2' id=""
                                                    placeholder="Business Class">
                                                @error('amount2')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for=""
                                        class="col-lg-2 col-form-label text-center d-flex align-items-center justify-content-center">Settlement</label>
                                    <div class="col-lg-10">
                                        <div class="form-group row" style="margin-bottom: 4px;">
                                            <label for=""
                                                class="col-lg-2 col-form-label text-center font-weight-normal">Economy
                                                Class</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount3') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount3', $amount3->amount) }}" name='amount3' id=""
                                                    placeholder="Economy Class">
                                                @error('amount3')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for=""
                                                class="col-lg-2 col-form-label text-center font-weight-normal">Business
                                                Class</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount4') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount4', $amount4->amount) }}" name='amount4' id=""
                                                    placeholder="Business Class">
                                                @error('amount4')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4>PRE-APPROVAL & SETTLEMENT FOR ENTERTAINMENT FEE</h4>
                            <div class="card card-body card-company">

                                <div class="row">
                                    <label for=""
                                        class="col-lg-2 col-form-label text-center d-flex align-items-center justify-content-center">Assignment</label>
                                    <div class="col-lg-10">
                                        <div class="form-group row" style="margin-bottom: 4px;">
                                            <label for="" class="col-lg-2 col-form-label text-center font-weight-normal">Not
                                                PO</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount5') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount5', $amount5->amount) }}" name='amount5' id=""
                                                    placeholder="Economy Class">
                                                @error('amount5')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for=""
                                                class="col-lg-2 col-form-label text-center font-weight-normal">PO</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount6') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount6', $amount6->amount) }}" name='amount6' id=""
                                                    placeholder="Business Class">
                                                @error('amount6')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for=""
                                        class="col-lg-2 col-form-label text-center d-flex align-items-center justify-content-center">Settlement</label>
                                    <div class="col-lg-10">
                                        <div class="form-group row" style="margin-bottom: 4px;">
                                            <label for="" class="col-lg-2 col-form-label text-center font-weight-normal">Not
                                                PO</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount7') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount7', $amount7->amount) }}" name='amount7' id=""
                                                    placeholder="Economy Class">
                                                @error('amount7')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for=""
                                                class="col-lg-2 col-form-label text-center font-weight-normal">PO</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount8') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount8', $amount8->amount) }}" name='amount8' id=""
                                                    placeholder="Business Class">
                                                @error('amount8')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="mt-5 mb-5 text-center">
                                <button class="btn btn-danger pt-1 pb-1 mr-4 col-5 col-sm-2 col-md-4 col-lg-2"><i
                                        class="nav-icon far fa-check-circle"></i> Approval</button>
                                <a role="button" href="{{ route('admin.budget.show') }}"
                                    class="btn btn-outline-dark pt-1 pb-1 col-5 col-sm-2 col-md-4 col-lg-2"><i
                                        class="nav-icon far fa-times-circle"></i> Cancel</a>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
