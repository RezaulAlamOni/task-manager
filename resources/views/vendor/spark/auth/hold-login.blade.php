@extends('spark::layouts.layout_2')
@section("styles")
    <link href="/css/themify-icons.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/css/login.css">
@endsection
@section('content')
    <div class="container-fluid main-container">
        <div class="row justify-content-center">
            <div class="col-lg-4">
                <div class="card card-default">
                    <div class="card-header">
                        <h2 class="text-center">
                            <img src="/img/mono-logo.png" alt="Logo">
                        </h2>
                    </div>
                    <div class="card-body">
                        @include('spark::shared.errors')

                        <form class="form-horizontal" role="form" method="POST" action="/login">
                        {{ csrf_field() }}

                        <!-- E-Mail Address -->
                            <div class="form-group ">
                                {{--<label class="col-md-4 col-form-label text-md-right">{{__('E-Mail')}}</label>--}}

                                <div class="col-md-12">
                                    <input type="email" placeholder="Email" class="form-control" name="email"
                                           value="{{ old('email') }}" autofocus>
                                </div>
                            </div>

                            <!-- Password -->
                            <div class="form-group ">
                                {{--<label class="col-md-4 col-form-label text-md-right">{{__('Password')}}</label>--}}

                                <div class="col-md-12">
                                    <input type="password" placeholder="Password" class="form-control" name="password">
                                </div>
                            </div>

                            <!-- Remember Me -->
                            <div class="form-group ">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <label class="form-check-label login-check-label">
                                            <input type="checkbox" name="remember" class="form-check-input"> &nbsp;&nbsp;{{__('Remember Me')}}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Login Button -->
                            <div class="form-group  mb-0">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        {{__('Sign In')}}
                                    </button>
                                    <div class="mt-3">
                                        <a href="{{ url('/password/reset') }}">{{__('Forgot Your Password?')}}</a>
                                        {{--<a class="mt-3 text-primary" href="{{ url('/password/reset') }}">{{__('Forgot Your Password?')}}</a>--}}
                                        <span class="float-right sign-up">New ? <a
                                                    href="{{ url('/register') }}">Signup</a></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row social">

                            <div class="col-12">
                                <p class="alter text-center">Sign in with</p>

                                <div class="row">
                                    <div class="col-4 text-center">
                                        <a href="#" class="btn btn-lg btn-facebook">
                                            <i class="ti-facebook"></i>
                                        </a>
                                    </div>
                                    <div class="col-4 text-center">
                                        <a href="#" class="btn btn-lg btn-twitter">
                                            <i class="ti-twitter-alt"></i>
                                        </a>
                                    </div>
                                    <div class="col-4 text-center">
                                        <a href="#" class="btn btn-lg btn-google">
                                            <i class="ti-google"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
