@extends('layouts.admin')

@push('css-page')
@endpush

@push('script-page')
@endpush

@section('page-title')
    {{__('Manage Language')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('Languages')}}</li>
        </ol>
    </nav>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="container-fluid">
        <div class="row pt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between w-100">
                            <h4>{{__('Languages')}}</h4>
                            <button class="btn btn-round" data-url="{{ route('create.language') }}" data-ajax-popup="true" data-title="{{__('Create New Language')}}" class="btn btn-circle btn-outline btn-sm blue-madison">
                                <i class="material-icons">add</i>
                            </button>        
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="language-wrap">
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-12 language-list-wrap">
                                    <div class="language-list">
                                        <ul class="nav nav-pills flex-column" id="myTab4" role="tablist">

                                            @foreach($languages as $lang)
                                                <li class="nav-item">
                                                    <a  href="{{route('manage.language',[$lang])}}" class="nav-link {{($currantLang == $lang)?'active':''}}">{{Str::upper($lang)}}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-lg-9 col-md-9 col-sm-12 language-form-wrap">
                                    <div class="language=form">
                                        <div class="tab-content no-padding" id="myTab2Content">
                                            <div class="tab-pane fade show active" id="lang1" role="tabpanel" aria-labelledby="home-tab4">


                                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">{{ __('Labels')}}</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">{{ __('Messages')}}</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content" id="myTabContent">
                                                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                                        <form method="post" action="{{route('store.language.data',[$currantLang])}}">
                                                            @csrf
                                                            <div class="row">
                                                                @foreach($arrLabel as $label => $value)
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="form-control-label" for="example3cols1Input">{{$label}} </label>
                                                                            <input type="text" class="form-control" name="label[{{$label}}]" value="{{$value}}">
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                <div class="col-lg-12 text-right">
                                                                    <button class="btn btn-primary" type="submit">{{ __('Save Changes')}}</button>
                                                                </div>

                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                                        <form method="post" action="{{route('store.language.data',[$currantLang])}}">
                                                            @csrf
                                                            <div class="row">
                                                                @foreach($arrMessage as $fileName => $fileValue)
                                                                    <div class="col-lg-12">
                                                                        <h3>{{ucfirst($fileName)}}</h3>
                                                                    </div>
                                                                    @foreach($fileValue as $label => $value)
                                                                        @if(is_array($value))
                                                                            @foreach($value as $label2 => $value2)
                                                                                @if(is_array($value2))
                                                                                    @foreach($value2 as $label3 => $value3)
                                                                                        @if(is_array($value3))
                                                                                            @foreach($value3 as $label4 => $value4)
                                                                                                @if(is_array($value4))
                                                                                                    @foreach($value4 as $label5 => $value5)
                                                                                                        <div class="col-md-6">
                                                                                                            <div class="form-group">
                                                                                                                <label>{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}.{{$label4}}.{{$label5}}</label>
                                                                                                                <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}][{{$label4}}][{{$label5}}]" value="{{$value5}}">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    @endforeach
                                                                                                @else
                                                                                                    <div class="col-lg-6">
                                                                                                        <div class="form-group">
                                                                                                            <label>{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}.{{$label4}}</label>
                                                                                                            <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}][{{$label4}}]" value="{{$value4}}">
                                                                                                        </div>
                                                                                                    </div>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @else
                                                                                            <div class="col-lg-6">
                                                                                                <div class="form-group">
                                                                                                    <label>{{$fileName}}.{{$label}}.{{$label2}}.{{$label3}}</label>
                                                                                                    <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}][{{$label3}}]" value="{{$value3}}">
                                                                                                </div>
                                                                                            </div>
                                                                                        @endif
                                                                                    @endforeach
                                                                                @else
                                                                                    <div class="col-lg-6">
                                                                                        <div class="form-group">
                                                                                            <label>{{$fileName}}.{{$label}}.{{$label2}}</label>
                                                                                            <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}][{{$label2}}]" value="{{$value2}}">
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            @endforeach
                                                                        @else
                                                                            <div class="col-lg-6">
                                                                                <div class="form-group">
                                                                                    <label>{{$fileName}}.{{$label}}</label>
                                                                                    <input type="text" class="form-control" name="message[{{$fileName}}][{{$label}}]" value="{{$value}}">
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                @endforeach
                                                            </div>
                                                            <div class="col-lg-12 text-right">
                                                                <button class="btn btn-primary" type="submit">{{ __('Save Changes')}}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

