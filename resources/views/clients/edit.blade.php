@extends('layouts.modal')

@section('form-start')
    {{Form::model($client,array('route' => array('clients.update', $client->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'data-remote' => 'true')) }}
@endsection

@push('scripts')

<script>

    $(".avatar-input").change(function () {
        PreviewAvatarImage(this, 60, 'rounded');
    });

</script>

@endpush

@section('title')
    {{__('Edit Client')}}
@endsection

@section('content')
<div class="tab-content">
    <div class="form-group row avatar-container">
        <div class="d-flex flex-column avatar-preview">
            {!!Helpers::buildClientAvatar($client, 60, 'rounded')!!}
        </div>
        <div class="media-body ml-3">
            <div class="custom-file custom-file-naked d-block mb-1">
                <input type="file" class="custom-file-input avatar-input d-none" name="avatar" id="avatar" accept="image/*">
                <label class="custom-file-label position-relative" for="avatar">
                <span class="btn btn-primary">
                    {{__('Upload logo')}}
                </span>
                </label>
                <label class="file-label position-relative"></label>
            </div>
            <div class="alert alert-warning text-small" role="alert">
                <small>{{__('For best results, use an image at least 256px by 256px in either .jpg or .png format')}}</small>
            </div>
        </div>
    </div>
    <div class="form-group row align-items-center required">
        {{Form::label('name',__('Name'), array('class'=>'col-3')) }}
        {{Form::text('name',null,array('class'=>'form-control col','placeholder'=>__('Pinepipe'),'required'=>'required'))}}
    </div>
    <div class="form-group row">
        {{Form::label('email',__('Email'), array('class'=>'col-3'))}}
        {{Form::text('email',null,array('class'=>'form-control col','placeholder'=>__('team@pinepipe.com')))}}
    </div>
    <div class="form-group row">
        {{Form::label('phone',__('Phone Number'), array('class'=>'col-3'))}}
        {{Form::text('phone',null,array('class'=>'form-control col','placeholder'=>__('(800) 613-1303')))}}
    </div>
    <div class="form-group row">
        {{ Form::label('address', __('Address'), array('class'=>'col-3')) }}
        {!!Form::textarea('address', null, ['class'=>'form-control col','rows'=>'2', 'placeholder'=>'101 California Street']) !!}
    </div>
    <div class="form-group row">
        {{Form::label('website',__('Website'), array('class'=>'col-3'))}}
        {{Form::text('website',null,array('class'=>'form-control col','placeholder'=>__('https://pinepipe.com/')))}}
    </div>
    <div class="form-group row">
        {{Form::label('tax',__('Tax ID'), array('class'=>'col-3'))}}
        {{Form::text('tax',null,array('class'=>'form-control col','placeholder'=>'#'))}}
    </div>
</div>
@include('partials.errors')
@endsection

@section('footer')
    {{Form::submit(__('Update'), array('class'=>'btn btn-primary', 'data-disable' => 'true'))}}
@endsection

@section('form-end')
    {{ Form::close() }}
@endsection

