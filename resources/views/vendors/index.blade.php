@extends('adminlte::page')

@section('title', __('vendors.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('vendors.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('vendors.create') }}">{{ __('tables.new') }}</a>
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
            <th>{{ __('vendors.id') }}</th>
            <th>{{ __('vendors.name') }}</th>
            <th>{{ __('vendors.description') }}</th>
            <th width="280px">{{ __('tables.action') }}</th>
        </tr>
        @foreach ($vendors as $vendor)
        <tr>
            <td>{{ $vendor->id }}</td>
            <td>{{ $vendor->name }}</td>
            <td>{{ $vendor->description }}</td>
            <td>
                <form action="{{ route('vendors.destroy', $vendor->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('vendors.show', $vendor->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('vendors.edit', $vendor->id) }}">{{ __('tables.edit') }}</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('tables.delete') }}</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    {!! $vendors->links() !!}
@endsection

