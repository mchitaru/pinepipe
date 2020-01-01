@extends('layouts.admin')
@php
    $profile=asset(Storage::url('avatar/'));
@endphp
@section('page-title')
    {{__('Profile')}}
@endsection
@push('css-page')
    <style>
        input[type="password"] {
            width: 100%;
            height: 34px;
            padding: 6px 12px;
            background-color: #fff;
            border: 1px solid #c2cad8;
        }
    </style>
@endpush
@section('breadcrumb')
    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="{{ route('dashboard') }}">{{__('Home')}}</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>{{__('Profile')}}</span>
        </li>
    </ul>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="profile-sidebar">
                <div class="portlet light profile-sidebar-portlet ">
                    <div class="profile-userpic">
                        <img alt="image" src="{{(!empty($userDetail->avatar))? $profile.'/'.$userDetail->avatar : $profile.'/avatar.png'}}" class="img-responsive user-profile">
                    </div>
                    <div class="profile-usertitle">
                        <div class="profile-usertitle-name font-style"> {{$userDetail->name}}</div>
                        <div class="profile-usertitle-job font-style"> {{$userDetail->type}}</div>
                        <div class="profile-usertitle-job"> {{$userDetail->email}}</div>
                    </div>
                    <div class="profile-usermenu">
                    </div>
                </div>
            </div>
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="portlet light ">
                            <div class="portlet-title tabbable-line">
                                <div class="caption">
                                    <i class="fa fa-user font-blue"></i>
                                    <span class="caption-subject font-blue sbold uppercase">{{__('Profile Account')}}</span>
                                </div>
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1_1" data-toggle="tab">{{__('Personal Info')}}</a>
                                    </li>
                                    @can('change password account')
                                        <li>
                                            <a href="#tab_1_2" data-toggle="tab">{{__('Change Password')}}</a>
                                        </li>
                                    @endcan
                                </ul>
                            </div>
                            <div class="portlet-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1_1">
                                        {{Form::model($userDetail,array('route' => array('update.account'), 'method' => 'put', 'enctype' => "multipart/form-data"))}}
                                        <div class="form-group">
                                            {{Form::label('name',__('Name'))}}
                                            {{Form::text('name',null,array('class'=>'form-control font-style','placeholder'=>_('Enter User Name')))}}
                                            @error('name')
                                            <span class="invalid-name" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            {{Form::label('email',__('Email'))}}
                                            {{Form::text('email',null,array('class'=>'form-control','placeholder'=>__('Enter User Email')))}}
                                            @error('email')
                                            <span class="invalid-email" role="alert">
                                                    <strong class="text-danger">{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                                    <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""/></div>
                                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                                                <div>
                                                    <span class="btn default btn-file">
                                                        <span class="fileinput-new"> {{__('Select Image')}} </span>
                                                        <span class="fileinput-exists"> {{__('Change')}} </span>
                                                        <input type="file" name="profile" id="profile">
                                                    </span>
                                                    <a href="javascript:;" class="btn default fileinput-exists" data-dismiss="fileinput"> Remove </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer text-right">
                                            <a href="{{ route('dashboard') }}" class="btn dark btn-outline">{{__('Cancel')}}</a>
                                            @can('edit account')
                                                {{Form::submit('Save Change',array('class'=>'btn blue'))}}
                                            @endcan
                                        </div>
                                        {{Form::close()}}
                                    </div>
                                    @can('change password account')
                                        <div class="tab-pane" id="tab_1_2">
                                            {{Form::model($userDetail,array('route' => array('update.password',$userDetail->id), 'method' => 'put'))}}
                                            <div class="form-group">
                                                {{Form::label('current_password',__('Current Password'))}}
                                                {{Form::password('current_password',null,array('class'=>'form-control','placeholder'=>_('Enter Current Password')))}}
                                                @error('current_password')
                                                <span class="invalid-current_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                {{Form::label('new_password',__('New Password'))}}
                                                {{Form::password('new_password',null,array('class'=>'form-control','placeholder'=>_('Enter New Password')))}}
                                                @error('new_password')
                                                <span class="invalid-new_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                {{Form::label('confirm_password',__('Re-type New Password'))}}
                                                {{Form::password('confirm_password',null,array('class'=>'form-control','placeholder'=>_('Enter Re-type New Password')))}}
                                                @error('confirm_password')
                                                <span class="invalid-confirm_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                                @enderror
                                            </div>

                                            <div class="card-footer text-right">
                                                <a href="{{ route('dashboard') }}" class="btn dark btn-outline">{{__('Cancel')}}</a>
                                                {{Form::submit('Save Change',array('class'=>'btn blue'))}}
                                            </div>
                                            {{Form::close()}}
                                        </div>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
