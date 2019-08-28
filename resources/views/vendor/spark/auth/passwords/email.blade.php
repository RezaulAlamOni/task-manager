@extends('spark::layouts.app')

@section("styles")
    <link rel="stylesheet" type="text/css" href="/css/login.css">
@endsection

<!-- Main Content -->
@section('content')
<div class="container-fluid main-container">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card card-default">
                <div class="card-header">
                    <h2 class="text-center">
                        <img src="/img/mono-logo.png" alt="Logo">
                    </h2>
                    <h3 class="text-center">Forgot Password
                    </h3>
                    <p class="text-center enter_email">
                        Enter your Registered email
                    </p>
                    <p class="text-center check_email d-none">
                        Check your email for Reset link
                        <br><br>
                        <u><a href="javascript:void(0)" class="reset-link">Resend the link</a></u>
                    </p>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form role="form" method="POST" action="{{ url('/password/email') }}">
                        {!! csrf_field() !!}

                        <!-- E-Mail Address -->
                        <div class="form-group row{{ $errors->has('email') ? ' is-invalid' : '' }}">
                            <label class="col-md-4 col-form-label text-md-right">{{__('E-Mail Address')}}</label>

                            <div class="col-md-6">
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Send Password Reset Link Button -->
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-envelope"></i> {{__('Send Password Reset Link')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
