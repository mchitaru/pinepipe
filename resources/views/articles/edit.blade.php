@extends('layouts.modal')

@section('form-start')
    {{ Form::model($article, array('route' => array('articles.update', $article->id), 'method' => 'PUT', 'data-remote' => 'true')) }}
@endsection

@section('size')
modal-lg
@endsection

@section('title')
    {{__('Edit Article')}}
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
                <input type="hidden" name="published" value="0">
                {{Form::checkbox('published', 1, null, ['class'=>'custom-control-input', 'id' =>'published'])}}
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
            {{Form::submit(__('Update'), array('class'=>'btn btn-primary', 'id'=>'submit', 'data-disable' => 'true'))}}
        </div>
    </div>
@endsection

@section('form-end')
{{ Form::close() }}
@endsection

