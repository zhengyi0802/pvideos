@extends('adminlte::page')

@section('title', __('actresses.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('actresses.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('tables.details') }}</h1>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('actresses.index') }}">{{ __('tables.back') }}</a>
            </div>
        </div>
    </div>
    <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('actresses.name') }} :</strong>
                {{ ($actress->amatuer) ? __('actresses.amatuer') : __('actresses.job') }} : {{ $actress->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('actresses.description') }} : </strong><br>
                {{ $actress->description }}
            </div>
         </div>
     </div>

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('videos.create2', $actress->id) }}">{{ __('actresses.new_movie') }}</a>
            </div>
        </div>
    </div>

    <div class="row">
      <div class='list-group gallery'>
            @if(count($videos))
                @foreach($videos as $video)
                <div class="box-tools with-border" style="width: 660px;" >
                    <div class="img-responsive box-body" >
                        <video width="640" height="360" poster="{{ $video->thumbnail }}" controls>
                           <source src="{{ $video->video_url }}" type="video/mp4" >
                        </video>
                    </div>
                    <div class="text-center box-footer" style="width: 660px;" >
                        <h2><a href="{{ '/videos/'.$video->id }}" }}>{{ $video->title }}</a></h2>
                    </div> <!-- text-center / end -->
                </div> <!-- col-6 / end -->
                @endforeach
            @endif
        </div> <!-- list-group / end -->
    </div> <!-- row / end -->
@endsection
