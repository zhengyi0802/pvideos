@extends('adminlte::page')

@section('title', __('vendors.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('vendors.header') }}</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h1>{{ __('tables.details') }}</h1>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('vendors.index') }}">{{ __('tables.back') }}</a>
            </div>
        </div>
    </div>
    <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('vendors.name') }} :</strong>
                {{ $vendor->name }}
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('vendors.description') }} : </strong></br>
                {{ $vendor->description }}
            </div>
         </div>
     </div>

    <div class="row">
      <div class='list-group gallery'>
            @if(count($videos))
                @foreach($videos as $video)
                <div class='col-sm-4 col-xs-6 col-md-3 col-lg-3' style="width: 320px;" >
                    <a class="thumbnail fancybox" rel="ligthbox" href="{{ "/videos/".$video->id }}">
                        <img class="img-responsive" width="320" height="180" alt="" src="{{ $video->thumbnail }}" />
                        <div class='text-center' style="width: 320px;" >
                            <small class='text-muted'>{{ $video->title }}</small>
                        </div> <!-- text-center / end -->
                    </a>
                    <form action="{{ route('videos.destroy', $video->id) }}" method="POST">
                    <input type="hidden" name="_method" value="delete">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="close-icon btn btn-danger">{{ __('tables.delete') }}</button>
                    </form>
                </div> <!-- col-6 / end -->
                @endforeach
            @endif
        </div> <!-- list-group / end -->
    </div> <!-- row / end -->

@endsection
