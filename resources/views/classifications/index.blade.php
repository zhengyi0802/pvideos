@extends('adminlte::page')

@section('title', __('classifications.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('classifications.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('classifications.create') }}">{{ __('tables.new') }}</a>
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
            <th>{{ __('classifications.id') }}</th>
            <th>{{ __('classifications.name') }}</th>
            <th>{{ __('classifications.description') }}</th>
            <th width="280px">{{ __('tables.action') }}</th>
        </tr>
        @foreach ($classifications as $classification)
        <tr>
            <td>{{ $classification->id }}</td>
            <td>{{ $classification->name }}</td>
            <td>{{ $classification->description }}</td>
            <td>
                <form action="{{ route('classifications.destroy', $classification->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('classifications.show', $classification->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('classifications.edit', $classification->id) }}">{{ __('tables.edit') }}</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('tables.delete') }}</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    {!! $classifications->links() !!}
@endsection

