@extends('layouts.auth')

@section('title')
Reset Password
@endsection

@section('content')
<div class="login-box">

    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <a class="h1" href="#">{{ env('APP_NAME') }}</a>
        </div>
        <div class="card-body login-card-body">
            <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
            @if ($errors->any())
            <div class="alert alert-danger border-left-danger" role="alert">
                <ul class="pl-4 my-2">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            @if(session()->has('status'))
            <div class="alert alert-success border-left-danger" role="alert">
                We already sent your reset password link in email, please check inbox / spam, password reset link will
                expire in 60 minutes.
            </div>
            @endisset
            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary btn-block">Request new password</button>
                    </div>

                </div>
            </form>
            <p class="mt-3 mb-1">
                <a href="{{ route('login') }}">Login</a>
            </p>
            {{-- <p class="mb-0">
                <a href="register.html" class="text-center">Register a new membership</a>
            </p> --}}
        </div>
    </div>
</div>
@endsection