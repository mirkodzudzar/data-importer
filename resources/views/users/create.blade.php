@extends('adminlte::page')

@section('title', 'Create User')

@section('content_header')
<h1>Create User</h1>
@stop

@section('content')
<div class="col-md-6">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Create User</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="card-body">
                <div class="form-group">
                    <label for="name">Name</label>

                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        id="name" placeholder="Enter name" value="{{ old('name') }}">

                    @error('name')
                    <div class="mt-1 mb-2 text-red">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email address</label>

                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter email"
                        value="{{ old('email') }}">

                    @error('email')
                    <div class="mt-1 mb-2 text-red">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>

                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">

                    @error('password')
                    <div class="mt-1 mb-2 text-red">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm">Password confirm</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password-confirm"
                        placeholder="Password confirmation">
                </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <!-- /.card -->
</div>
@stop
