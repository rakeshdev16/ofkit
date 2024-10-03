@extends('layouts.app')
@section('content')
    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
        <div class="col mx-auto">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="alert alert-success" role="alert" style="display: block">שלחנו לך קוד אימות חד-פעמי למספר הטלפון הרשום במערכת</div>
                    <div class="p-4">
                        <div class="mb-3 text-center">
                            <img src="{{ asset('assets/images/3.png') }}" width="100" alt="" />
                        </div>
                        <div class="text-center mb-4">
                            <h5 class="">{{ __('login.enterOtp') }}</h5>
                            <h5 class="mb-0">{{ __('login.signInMsg') }}</h5>
                        </div>
                        <div class="form-body">
                            <form class="row" method="POST" action="{{ route('otp.verify.submit') }}">
                                @csrf
                                <div class="col-12">
                                    <label for="otp" class="form-label">{{ __('login.enterOtp') }}</label>
                                    <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" placeholder="{{ __('login.enterOtp') }}" value="{{ old('otp') }}" required autocomplete="email" autofocus>
                                    @error('otp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="d-grid">
                                        <button type="submit" class="btn button">{{ __('login.verifyOtp') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
