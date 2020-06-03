@extends('layouts.modal')

@section('form-start')
    {{Form::open(array('route' => array('articles.store'), "method"=>"post", "enctype"=>"multipart/form-data", 'data-remote' => 'true'))}}
@endsection

@section('size')
modal-lg
@endsection

@section('title')
    {{__('Create Article')}}
@endsection

@push('scripts')
<script>
</script>
@endpush

@section('content')
<div class="container">
    <div class="col">
        {!! Form::hidden('path', $path) !!}
        {!! Form::hidden('category_id', $category?$category->id:null) !!}
        <div class="row p-1 required align-items-center">
            {{Form::text('title',null,array('class'=>'form-control col', 'required'=>'required', 'placeholder'=>__('Article title...')))}}
        </div>
        <div class="row p-1 required align-items-center">
            {!! Form::textarea('content', null, array('class' => 'summernote form-control col', 'required'=>'required')) !!}
        </div>
        <div class="row p-1 align-items-center">
            <div class="col-10">
            </div>
            <div class="form-group col custom-control custom-checkbox custom-checkbox-switch">
                <input type="hidden" name="published" value="1">
                {{Form::checkbox('published', 1, 0, ['class'=>'custom-control-input', 'id' =>'published'])}}
                {{Form::label('published', __('Published'), ['class'=>'custom-control-label'])}}
            </div>
        </div>
    </div>
</div>
@include('partials.errors')
@endsection

@section('footer')
    <div class="container align-items-center">
        <div class="float-left">
            {{$path}}
        </div>
        <div class="float-right">
            {{Form::submit(__('Create'), array('class'=>'btn btn-primary', 'id'=>'submit', 'data-disable' => 'true'))}}
        </div>
    </div>
@endsection

@section('form-end')
{{ Form::close() }}
@endsection

