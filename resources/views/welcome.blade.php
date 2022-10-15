@extends('layouts.admin')

@section('title')
Welcome to Laravel
@endsection

@section('content')
<div class="row">
    <div class="col-md-6">
        <form class="form">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control form-control-border form-control-sm" placeholder="Your Name...">
            </div>
        </form>
    </div>
</div>
@stop