@extends('adminlte::page')

@section('title', __('Users'))

@section('content_header')
<h1>{{ __('Users') }}</h1>
@stop

@section('content')
<div>
    <div class="card">
        <div class="card-header">
            <a href="{{ route('users.create') }}" class="btn btn-block btn-primary" style="width: 150px">{{ __('Create User') }}</a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 10px">{{ __('ID') }}</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Created') }}</th>
                        <th style="width: 10px">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->diffForHumans() }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-link p-0 text-primary"><i class="fas fa-solid fa-pen"></i></a>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link p-0 text-danger ml-2" title="Delete User" onclick="return confirm('Are you sure you want to delete this user?')">
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
            {{ $users->links() }}
        </div>
    </div>
    <!-- /.card -->
</div>
@stop
