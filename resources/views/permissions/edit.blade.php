@extends('adminlte::page')

@section('title', 'Edit Permission')

@section('content_header')
<h1>Edit Permission</h1>
@stop

@section('content')
<div class="col-md-6">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Edit Permission</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
            @method('PUT')
            @csrf

            <div class="card-body">
                <div class="form-group">
                    <label for="name">Name</label>

                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                        id="name" placeholder="Enter name" value="{{ old('name', $permission->name) }}">

                    <div class="mt-1 mb-2 sm">
                        {{ __('e.g. new-permission') }}
                        </div>

                    @error('name')
                    <div class="mt-1 mb-2 text-red">
                        {{ $message }}
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Label</label>

                    <input type="text" name="label" class="form-control" id="label" placeholder="Enter label"
                        value="{{ old('label', $permission->label) }}">

                    @error('label')
                    <div class="mt-1 mb-2 text-red">
                        {{ $message }}
                    </div>
                    @enderror
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
