@extends('layouts.modal')

@section('form-start')
    {{ Form::open(array('url' => 'expenses', 'enctype' => "multipart/form-data", 'data-remote' => 'true')) }}
@endsection

@push('scripts')
<script>
    $(".avatar-input").change(function () {
        PreviewAvatarImage(this, 60, 'rounded');
    });
</script>
@endpush

@section('title')
    {{__('Create Expense')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row align-items-center required">
        {{ Form::label('amount', __('Amount'), array('class'=>'col-3')) }}
        <div class="input-group col p-0">
            <div class="input-group-prepend">
                <span class="input-group-text">{{Helpers::getCurrencySymbol(\Auth::user()->getCurrency())}}</span>
            </div>
            {{ Form::number('amount', '', array('class' => 'form-control col','required'=>'required', 'step'=>'0.01', 'placeholder'=>500)) }}
        </div>
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('date', __('Date'), array('class'=>'col-3')) }}
        {{ Form::text('date', '', array('class' => 'form-control col','required'=>'required', 'placeholder'=>'...',
                                        'data-flatpickr', 'data-locale'=> \Auth::user()->locale, 'data-default-date'=> date('Y-m-d'), 'data-week-numbers'=>'true', 'data-alt-input'=>'true')) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('project_id', __('Project'), array('class'=>'col-3')) }}
        {{ Form::select('project_id', $projects, $project_id, array('class' => 'form-control col',
                        'placeholder'=>'...', 'lang'=>\Auth::user()->locale)) }}
    </div>
    <div class="form-group row align-items-center">
        {{ Form::label('category_id', __('Category'), array('class'=>'col-3')) }}
        {{ Form::select('category_id', $categories, null, array('class' => 'tags form-control col', 'placeholder'=>'...', 'lang'=>\Auth::user()->locale)) }}
    </div>
    <div class="form-group row">
        {{ Form::label('description', __('Description'), array('class'=>'col-3')) }}
        {!! Form::textarea('description', null, ['class'=>'form-control col','rows'=>'2']) !!}
    </div>
    @if(\Auth::user()->type=='company')
    <div class="form-group row align-items-center">
        {{ Form::label('user_id', __('Owner'), array('class'=>'col-3')) }}
        {{ Form::select('user_id', $owners, \Auth::user()->id, array('class' => 'form-control col', 'lang'=>\Auth::user()->locale)) }}
    </div>
    @endif
    <div class="form-group row avatar-container">
        <div class="d-flex flex-column avatar-preview">
            <img data-filter-by='alt' width=60 height=60 class="rounded" avatar="?">
        </div>
        <div class="media-body ml-3">
            <div class="custom-file custom-file-naked d-block mb-1">
                <input type="file" class="custom-file-input avatar-input d-none" name="attachment" id="attachment" accept="image/*,application/pdf">
                <label class="custom-file-label position-relative" for="attachment">
                <span class="btn btn-primary">
                    {{__('Upload receipt')}}
                </span>
                </label>
                <label class="file-label position-relative"></label>
            </div>
            <div class="alert alert-warning text-small" role="alert">
                <small>{{__('For best results, use an image at least 256px by 256px in either .jpg or .png format')}}</small>
            </div>
        </div>
    </div>
</div>
@include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Create'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@endsection

@section('form-end')
{{ Form::close() }}
@endsection
