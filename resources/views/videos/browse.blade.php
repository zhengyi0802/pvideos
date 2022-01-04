@extends('adminlte::page')

@section('title', __('videos.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('videos.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('videos.browse') }}</h1>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('videos.index') }}">{{ __('tables.back') }}</a>
            </div>
        </div>
    </div>

    <div class="row">
       @foreach($videos->chunk(4) as $chunk)
         <div class="card-group">
           @foreach($chunk as $video)
              <div class="card">
                   <div class="card-header">{{ $video->title }}</div>
                   <div class="card-body">
                       <video width="320" height="180" controls poster="{{ $video->thumbnail }}" >
                       <source src="{{ $video->video_url }}" type="video/mp4" >
                       </video>
                   </div>
                   <div class="card-footer btn btn-success">
                      <a href="{{ route('videos.show', $video->id) }}">{{ __('tables.details') }}</a>
                  </div>
              </div>
           @endforeach
         </div>
       @endforeach
    </div>
@endsection
