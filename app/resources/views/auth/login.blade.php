@extends('layouts.app',[
    'page_title' => 'Login',
])

@section('content')
<div class="d-flex flex-column flex-root" style="height: 100vh">
    <!--begin::Login-->
    <div class="login login-2 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
        <!--begin::Aside-->
        <div class="login-aside order-2 order-lg-1 d-flex flex-column-fluid flex-lg-row-auto bgi-size-cover bgi-no-repeat p-7 p-lg-10">
            <!--begin: Aside Container-->
            <div class="d-flex flex-row-fluid flex-column justify-content-between">
                <!--begin::Aside body-->
                <div class="d-flex flex-column-fluid flex-column flex-center mt-5 mt-lg-0">
                    <a href="#" class="mb-15 text-center">
                        <img src="logo/lfuggoc_logo.png" class="max-h-75px" alt="" />
                    </a>
                    <!--begin::Signin-->
                    <div class="login-form login-signin">
                        <div class="text-center mb-10 mb-lg-20">
                            <h2 class="font-weight-bold">Sign In</h2>
                            <p class="text-muted font-weight-bold">Enter your username and password</p>
                        </div>
                        <!--begin::Form-->
                        <form class="form" novalidate="novalidate" id="kt_login_signin_form" method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group py-3 m-0">
                                <input id="email" type="email" placeholder="Email" class="form-control h-auto border-0 px-0 placeholder-dark-75 form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus/>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group py-3 border-top m-0">
                                <input id="password" type="password" placeholder="Password" class="form-control h-auto border-0 px-0 placeholder-dark-75 form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group d-flex flex-wrap justify-content-between align-items-center mt-3">
                                <div class="checkbox-inline">
                                    <label class="checkbox checkbox-outline m-0 text-muted">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <span></span>Remember me</label>
                                </div>
                                {{-- <a href="javascript:;" id="kt_login_forgot" class="text-muted text-hover-primary">Forgot Password ?</a> --}}
                            </div>
                            <div class="form-group d-flex flex-wrap justify-content-between align-items-center mt-2">
                                <button id="kt_login_signin_submit" class="btn btn-primary font-weight-bold ">Sign In</button>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Signin-->
                </div>
            </div>
            <!--end: Aside Container-->
        </div>
        <!--begin::Aside-->
        <!--begin::Content-->
        <div class="order-1 order-lg-2 flex-column-auto flex-lg-row-fluid d-flex flex-column p-7" style="background-image: url(css/images/background/bg-4.jpg);">
            <!--begin::Content body-->
            <div class="d-flex flex-column-fluid flex-lg-center">
                <div class="d-flex flex-column justify-content-center">
                    {{-- <h3 class="display-3 font-weight-bold my-7 text-white">Welcome to Metronic!</h3>
                    <p class="font-weight-bold font-size-lg text-white opacity-80">The ultimate Bootstrap, Angular 8, React &amp; VueJS admin theme
                    <br />framework for next generation web apps.</p> --}}
                </div>
            </div>
            <!--end::Content body-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Login-->
</div>

@endsection
