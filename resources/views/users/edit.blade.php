@extends('layouts.app')

@section('title', 'Edit User')

@section('content_header')
<h1>Edit User</h1>
@stop

@section('page-content')
<div class="col-md-6">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit User</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route('users.update', $user->id) }}" method="POST">
            @method('PUT')
            @csrf

            <div class="card-body">
                <div class="form-group">
                    <label for="name">Name</label>

                    <input type="text" name="name" class="form-control"
                        id="name" placeholder="Enter name" value="{{ old('name', $user->name) }}">

                    @error('name')
                        <div class="mt-1 mb-2 text-red">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="permission">{{ __('Permission') }}</label>

                    <select name="permissions[]" multiple class="form-control" id="permission">
                        @foreach ($permissions as $permission)
                            <option value="{{ $permission->id }}"
                                @selected(in_array($permission->id, old('permissions', $user->permissions->pluck('id')->toArray())))>
                                {{ $permission->label }}
                            </option>
                        @endforeach
                    </select>

                    @error('permissions*')
                        <div class="mt-1 mb-2 text-red">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email address</label>

                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter email"
                        value="{{ old('email', $user->email) }}">

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
