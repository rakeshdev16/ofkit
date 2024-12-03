@extends('layouts.app')
@section('content')
<style>
    .alert {
        display: block;
    }
</style>
<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
    <div class="col mx-auto">
        <div class="card mb-0">
            <div class="card-body">
                @if (Session::get('status'))
                    <div class="alert alert-success" role="alert">
                        {{ Session::get('status') }}
                    </div>
                @endif
                <div class="p-4">
                    <div class="mb-3 text-center">
                        <img src="{{ asset('assets/images/5.png') }}" class="" width="160px" height="73.5px" alt="logo icon">
                    </div>
                    <div class="text-center mb-4">
                        <h5 class="">{{ __('login.welcome') }}</h5>
                        <h5 class="mb-0">{{ __('login.resetPassword') }}</h5>
                    </div>
                    <div class="form-body">
                        <form class="row" method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="col-12">
                                <label for="inputEmailAddress" class="form-label">{{ __('login.email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required readonly autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 mt-2">
                                <label for="inputChoosePassword" class="form-label">{{ __('login.password') }}</label>
                                <div class="input-group" id="show_hide_pass">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('login.passwordPlaceholder') }}" name="password" required autocomplete="current-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <a href="javascript:void(0);" class="input-group-text bg-transparent passwordEye" data-id="show_hide_pass"><i class='bx bx-hide'></i></a>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <label for="password-confirm" class="form-label">{{ __('login.confirmPassword') }}</label>
                                <div class="input-group" id="show_hide_confirm_password">
                                    <input id="confirm_password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('login.enterConfirmPassword') }}" name="password_confirmation" required autocomplete="current-password">
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <a href="javascript:void(0);" class="input-group-text bg-transparent passwordEye" data-id="show_hide_confirm_password"><i class='bx bx-hide'></i></a>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="d-grid">
                                    <button type="submit" class="button btn btn-primary">
                                        {{ __('login.resetPassword') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> --}}
@endsection
