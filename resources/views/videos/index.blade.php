@extends('adminlte::page')

@section('title', __('videos.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('videos.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('videos.create') }}">{{ __('tables.new') }}</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <form action="{{ route('videos.search') }}" method="GET" >
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('videos.video_title') }} :</strong>
                    <input type="text" name="title" class="form-control">
                    <button type="submit" class="btn btn-primary">{{ __('tables.search') }}</button>
                </div>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <tr>
            <th>{{ __('videos.id') }}</th>
            <th>{{ __('videos.vendor') }}</th>
            <th>{{ __('videos.article_number') }}</th>
            <th>{{ __('videos.video_title') }}</th>
            <th>{{ __('videos.status') }}</th>
            <th width="280px">{{ __('tables.action') }}</th>
        </tr>
        @foreach ($videos as $video)
        <tr>
            <td>{{ $video->id }}</td>
            <td>{{ $video->vendor ? $video->vendor : __('vendors.unknown') }}</td>
            <td>{{ $video->article_number }}</td>
            <td>{{ $video->title }}</td>
            <td>{{ ($video->status==1) ? __('tables.status_on'):__('tables.status_off') }}</td>
            <td>
                <form action="{{ route('videos.destroy', $video->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('videos.show', $video->id) }}">{{ __('tables.details') }}</a>
                    <a class="btn btn-primary" href="{{ route('videos.edit', $video->id) }}">{{ __('tables.edit') }}</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('tables.delete') }}</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
    {!! $videos->links() !!}
@endsection

