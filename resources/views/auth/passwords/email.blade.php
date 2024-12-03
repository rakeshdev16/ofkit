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
                        <h5 class="mb-0">{{ __('login.enterEmail') }}</h5>
                    </div>
                    <div class="form-body">
                        <form class="row" method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="col-12">
                                <label for="inputEmailAddress" class="form-label">{{ __('login.email') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('login.emailPlaceholder') }}" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 mt-2">
                                <div class="d-grid">
                                    <button type="submit" class="button btn btn-primary">
                                        {{ __('login.sendResetLink') }}
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
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send Password Reset Link') }}
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
