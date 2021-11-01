@extends('adminlte::page')

@section('title', __('catagories.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('catagories.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('catagories.create') }}">{{ __('tables.new') }}</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>{{ __('catagories.id') }}</th>
            <th>{{ __('catagories.user') }}</th>
            <th>{{ __('catagories.parent') }}</th>
            <th>{{ __('catagories.directory') }}</th>
            <th>{{ __('catagories.name') }}</th>
            <th>{{ __('catagories.status') }}</th>
            <th width="280px">{{ __('tables.action') }}</th>
        </tr>
        @foreach ($catagories as $catagory)
        <tr>
            <td>{{ $catagory->id }}</td>
            <td>{{ $catagory->user }}</td>
            <td>{{ $catagory->parent ? $catagory->parent : __('catagories.root') }}</td>
            <td>{{ $catagory->directory }}</td>
            <td>{{ $catagory->name }}</td>
            <td>{{ ($catagory->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('catagories.destroy', $catagory->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('catagories.show', $catagory->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('catagories.edit', $catagory->id) }}">{{ __('tables.edit') }}</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('tables.delete') }}</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    {!! $catagories->links() !!}
@endsection

