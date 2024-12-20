@extends('layouts.app')

@section('title', __('Permissions'))

@section('content_header')
<h1>{{ __('Permissions') }}</h1>
@stop

@section('page-content')
<div>
    <div class="card">
        <div class="card-header">
            <a href="{{ route('permissions.create') }}" class="btn btn-block btn-primary" style="width: 150px">{{
                __('Create Permission') }}</a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Label') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th style="width: 10px">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $permission->id }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->label }}</td>
                        <td>{{ $permission->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('permissions.edit', $permission->id) }}"
                                class="btn btn-link p-0 text-primary"><i class="fas fa-solid fa-pen"></i></a>

                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0 text-danger ml-2"
                                    title="Delete Permission"
                                    onclick="return confirm('Are you sure you want to delete this permission?')">
                                    <i class="fas fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
            {{ $permissions->links() }}
        </div>
    </div>
    <!-- /.card -->
</div>
@stop
