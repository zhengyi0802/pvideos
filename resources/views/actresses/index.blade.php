@extends('adminlte::page')

@section('title', __('actresses.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('actresses.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('actresses.create') }}">{{ __('tables.new') }}</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <form action="{{ route('actresses.search') }}" method="GET" >
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('actresses.name') }} :</strong>
                    <input type="text" name="name" class="form-control">
                    <button type="submit" class="btn btn-primary">{{ __('tables.search') }}</button>
                </div>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <tr>
            <th>{{ __('actresses.id') }}</th>
            <th>{{ __('actresses.name') }}</th>
            <th>{{ __('actresses.description') }}</th>
            <th width="280px">{{ __('tables.action') }}</th>
        </tr>
        @foreach ($actresses as $actress)
        <tr>
            <td>{{ $actress->id }}</td>
            <td>{{ $actress->name }}</td>
            <td>{{ $actress->description }}</td>
            <td>
                <form action="{{ route('actresses.destroy', $actress->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('actresses.show', $actress->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('actresses.edit', $actress->id) }}">{{ __('tables.edit') }}</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('tables.delete') }}</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    {!! $actresses->links() !!}
@endsection

