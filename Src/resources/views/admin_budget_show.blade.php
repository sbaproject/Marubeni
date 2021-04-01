@extends('layouts.master')
@section('title')
{{ __('label.menu_budget_setting') }}
@endsection
@section('content')

@section('content-header')
{{ __('label.menu_budget_setting') }}
@endsection

@section('content-breadcrumb')
<li class="breadcrumb-item active">{{ __('label.menu_settings') }}</li>
<li class="breadcrumb-item active">{{ __('label.menu_budget_setting') }}</li>
@endsection

{{-- <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-lg-6">
                <h1>{{ __('label.menu_budget_setting') }}</h1>
            </div>
        </div>
    </div>
</section> --}}
    <div class="row">
        <div class="col-lg-9">
            <div class="invoice">
                <div class="card-body">
                    {{-- <x-alert /> --}}
                    <div class="search-content">
                        <form method="post" id="formBudget" action="">
                            @csrf
                            <h4>{{ __('label.budget_business_trip') }}</h4>
                            <div class="card card-body card-company">
                                <div class="row">
                                    <label for=""
                                        class="col-lg-2 col-form-label text-center d-flex align-items-center justify-content-center">{{ __('label.budget_assignment') }}</label>
                                    <div class="col-lg-10">
                                        <div class="form-group row" style="margin-bottom: 4px;">
                                            <label for=""
                                                class="col-lg-2 col-form-label text-center font-weight-normal">{{ __('label.budget_economy_class') }}</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount1') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount1', !empty($amount1->amount) ? $amount1->amount : ($amount1->amount == 0 ? 0 : '')) }}"
                                                    name='amount1' id=""
                                                    placeholder="{{ __('label.budget_economy_class') }}" maxlength="20">
                                                @error('amount1')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for=""
                                                class="col-lg-2 col-form-label text-center font-weight-normal">{{ __('label.budget_business_class') }}</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount2') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount2', !empty($amount2->amount) ? $amount2->amount : ($amount2->amount == 0 ? 0 : '')) }}"
                                                    name='amount2' id=""
                                                    placeholder="{{ __('label.budget_business_class') }}" maxlength="20">
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
                                        class="col-lg-2 col-form-label text-center d-flex align-items-center justify-content-center">{{ __('label.budget_settlement') }}</label>
                                    <div class="col-lg-10">
                                        <div class="form-group row" style="margin-bottom: 4px;">
                                            <label for=""
                                                class="col-lg-2 col-form-label text-center font-weight-normal">{{ __('label.budget_economy_class') }}</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount3') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount3', !empty($amount3->amount) ? $amount3->amount : ($amount3->amount == 0 ? 0 : '')) }}"
                                                    name='amount3' id=""
                                                    placeholder="{{ __('label.budget_economy_class') }}" maxlength="20">
                                                @error('amount3')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for=""
                                                class="col-lg-2 col-form-label text-center font-weight-normal">{{ __('label.budget_business_class') }}</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount4') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount4', !empty($amount4->amount) ? $amount4->amount : ($amount4->amount == 0 ? 0 : '')) }}"
                                                    name='amount4' id=""
                                                    placeholder="{{ __('label.budget_business_class') }}" maxlength="20">
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

                            <h4>{{ __('label.budget_pre_approvel_settlement_for_entertainment_free') }}</h4>
                            <div class="card card-body card-company">

                                <div class="row">
                                    <label for=""
                                        class="col-lg-2 col-form-label text-center d-flex align-items-center justify-content-center">{{ __('label.budget_assignment') }}</label>
                                    <div class="col-lg-10">
                                        <div class="form-group row" style="margin-bottom: 4px;">
                                            <label for=""
                                                class="col-lg-2 col-form-label text-center font-weight-normal">{{ __('label.budget_not_po') }}</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount5') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount5', !empty($amount5->amount) ? $amount5->amount : ($amount5->amount == 0 ? 0 : '')) }}"
                                                    name='amount5' id="" placeholder="{{ __('label.budget_not_po') }}" maxlength="20">
                                                @error('amount5')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for=""
                                                class="col-lg-2 col-form-label text-center font-weight-normal">{{ __('label.budget_po') }}</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount6') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount6', !empty($amount6->amount) ? $amount6->amount : ($amount6->amount == 0 ? 0 : '')) }}"
                                                    name='amount6' id="" placeholder="{{ __('label.budget_po') }}" maxlength="20">
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
                                        class="col-lg-2 col-form-label text-center d-flex align-items-center justify-content-center">{{ __('label.budget_settlement') }}</label>
                                    <div class="col-lg-10">
                                        <div class="form-group row" style="margin-bottom: 4px;">
                                            <label for=""
                                                class="col-lg-2 col-form-label text-center font-weight-normal">{{ __('label.budget_not_po') }}</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount7') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount7', !empty($amount7->amount) ? $amount7->amount : ($amount7->amount == 0 ? 0 : '')) }}"
                                                    name='amount7' id="" placeholder="{{ __('label.budget_not_po') }}" maxlength="20">
                                                @error('amount7')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for=""
                                                class="col-lg-2 col-form-label text-center font-weight-normal">{{ __('label.budget_po') }}</label>
                                            <div class="col-lg-10">
                                                <input type="text"
                                                    class="form-control {{ $errors->first('amount8') ? 'is-invalid' : '' }}"
                                                    value="{{ old('amount8', !empty($amount8->amount) ? $amount8->amount : ($amount8->amount == 0 ? 0 : '')) }}"
                                                    name='amount8' id="" placeholder="{{ __('label.budget_po') }}" maxlength="20">
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
                                <button class="btn bg-gradient-success">
                                    <i class="nav-icon far fa-check-circle" style="margin-right: 5px"></i>
                                    {{ __('label.button_update') }}
                                </button>
                                <a role="button" href="{{ route('admin.dashboard') }}"
                                    class="btn bg-gradient-secondary">
                                    <i class="nav-icon far fa-times-circle" style="margin-right: 5px"></i>
                                    {{ __('label.button_cancel') }}
                                </a>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
