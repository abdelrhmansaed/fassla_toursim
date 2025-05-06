@extends('Dashboard.layouts.master2')

@section('css')
    <!-- Sidemenu-responsive-tabs css -->
    <link href="{{ URL::asset('assets/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row no-gutter">
            <!-- Image Section -->
            <div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent">
                <div class="row wd-100p mx-auto text-center">
                    <div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
                        <img src="{{ URL::asset('assets/img/media/login.png') }}" class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="logo">
                    </div>
                </div>
            </div>

            <!-- Login Form Section -->
            <div class="col-md-6 col-lg-6 col-xl-5 bg-white">
                <div class="login d-flex align-items-center py-2">
                    <div class="container p-0">
                        <div class="row">
                            <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                <div class="card-sigin">
                                    <div class="mb-5 d-flex">
                                        <a href="{{ url('/') }}">
                                            <img src="{{ URL::asset('assets/img/brand/favicon.png') }}" class="sign-favicon ht-40" alt="logo">
                                        </a>
                                        <h1 class="main-logo1 ml-1 mr-0 my-auto tx-28">Va<span>le</span>x</h1>
                                    </div>

                                    <div class="card-sigin">
                                        <div class="main-signup-header">
                                            <h2>{{trans('login/login_trans.Welcome')}}</h2>

                                            <h5 class="font-weight-semibold mb-4">Please sign in to continue.</h5>
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <!--  Login Form -->
                                            <form method="POST" action="{{ route('auth.login') }}">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input class="form-control" name="email" placeholder="Enter your email" type="email" required>
                                                </div>

                                                <div class="form-group">
                                                    <label>Password</label>
                                                    <input class="form-control" name="password" placeholder="Enter your password" type="password" required>
                                                </div>

                                                <button class="btn btn-main-primary btn-block" type="submit">Sign In</button>
                                            </form>

                                            <!-- Forgot Password & Register Links -->
                                            <div class="main-signin-footer mt-5">
                                                <p><a href="#">Forgot password?</a></p>
                                            </div>
                                        </div>
                                    </div> <!-- End Card -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End Login Section -->
        </div>
    </div>
@endsection

@section('js')
@endsection
