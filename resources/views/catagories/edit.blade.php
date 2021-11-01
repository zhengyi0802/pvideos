@extends('adminlte::page')

@section('title', __('catagories.title'))

@section('content_header')
    <h1 class="m-0 text-dark">{{ __('catagories.header') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h1>{{ __('tables.new') }}</h1>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('catagories.index') }}">{{ __('tables.back') }}</a>
        </div>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('catagories.update', $catagory->id) }}" method="POST">
     @csrf
     <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('catagories.parent') }} :</strong>
                <select name="parent_id">
                    <option value="0" {{ ($catagory->id == 0) ? "selected" : null }}>{{ __('catagories.root') }}</option>
                    @foreach($parents as $parent)
                      <option value="{{ $parent->id }}" {{ ($catagory->id == $parent->id) ? "selected" : null }}>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('catagories.directory') }} :</strong>
                <input type="text" name="directory" class="form-control" value="{{ $catagory->directory }}" >
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('catagories.name') }} :</strong>
                <input type="text" name="name" class="form-control" value="{{ $catagory->name }}" >
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('catagories.description') }} :</strong>
                <textarea class="form-control" style="height:150px" name="description" >{{ $catagory->description }}</textarea>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>{{ __('catagories.status') }} :</strong>
                <input type="radio" name="status" value="1" {{ ($catagory->status) ? "checked" : null }}>{{ __('tables.status_on') }}
                <input type="radio" name="status" value="0" {{ (!$catagory->status) ? "checked" : null }}>{{ __('tables.status_off') }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">{{ __('tables.submit') }}</button>
        </div>
    </div>
</form>
@endsection
