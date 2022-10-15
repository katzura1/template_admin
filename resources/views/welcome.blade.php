@extends('layouts.admin')

@section('title')
Welcome to Laravel
@endsection

@section('content')
@if (Auth::check() && Auth::user()->email_verified_at == null)
<div class="card card-danger card-inline">
    <div class="card-header">
        <h5 class="card-title m-0">Verify your account</h5>
    </div>
    <div class="card-body">
        <h4 class="card-title mb-2">Hello There!</h4>
        <p class="card-text mt-1">
            We have sent verification to your email, please check <b>inbox / spam</b> folder with sender <i>{{
                env('MAIL_FROM_ADDRESS')??'example@gmail.com'}} </i>, before you verified all menu are disabled to
            access.
            We are doing this to prevent spam user.</br>
            </br>
            Sincerely,</br>
            Admin.
        </p>
        <a href="https://gmail.com" class="btn btn-danger">Go to Gmail</a>
    </div>
</div>
@else
<div class="card">
    <div class="card-header">
        Testing
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">

            </div>
        </div>
    </div>
</div>
@endif
@stop