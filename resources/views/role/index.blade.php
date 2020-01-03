@extends('layouts.admin')

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('page-title')
    {{__('Role')}}
@endsection

@section('breadcrumb')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="breadcrumb-bar navbar bg-white sticky-top">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">{{__('User Roles')}}</li>
            </ol>
        </nav>    
    </div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="page-header">
        </div>
        <div class="tab-content">
        <div class="tab-pane fade show active" id="users" role="tabpanel" data-filter-list="content-list-body">
            <div class="row content-list-head">
            <div class="col-auto">
                <h3>{{__('User Roles')}}</h3>
                @can('create user')
                    <button class="btn btn-round" data-url="{{ route('roles.create') }}" data-ajax-popup="true" data-title="{{__('Create New Role')}}">
                        <i class="material-icons">add</i>
                    </button>
                @endcan
            </div>
            <form class="col-md-auto">
                <div class="input-group input-group-round">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                    <i class="material-icons">filter_list</i>
                    </span>
                </div>
                <input type="search" class="form-control filter-list-input" placeholder="Filter users" aria-label="Filter Users">
                </div>
            </form>
            </div>
            <!--end of content list head-->
            <div class="content-list-body">
                <div class="container-fluid">
                    <table  class="mdl-data-table" style="width:100%" id="dataTable">
                        <thead>
                        <tr>
                            <th class="col-sm-3">{{__('Role')}} </th>
                            <th class="col-sm-5">{{__('Permissions')}} </th>
                            <th class="text-right" width="200px">{{__('Action')}} </th>
                        </tr>
                        </thead>
                        <tbody class="font-style">
                        @foreach ($roles as $role)
                            <tr>
                                <td>{{ $role->name }}</td>
                                <td>
                                    @for($j=0;$j<count($role->permissions()->pluck('name'));$j++)
                                        <span class="badge badge-secondary">{{$role->permissions()->pluck('name')[$j]}}</span>
                                    @endfor
                                </td>
                                <td class="action">
                                    <div class="dropdown card-options">
                                        <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </button>
        
                                        <div class="dropdown-menu dropdown-menu-right">
                                            @can('edit role')
                                            <a class="dropdown-item" href="#" data-url="{{  route('roles.edit',$role->id)}}" data-size="lg" data-ajax-popup="true" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-title="{{__('Update Role')}}">
                                                <span>{{__('Edit')}}</span>
                                            </a>
                                            @endcan
                                            <div class="dropdown-divider"></div>
                                            @can('delete role')
                                                <a class="dropdown-item text-danger" href="#" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$role->id}}').submit();">
                                                    <span>{{__('Delete')}}</span>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id],'id'=>'delete-form-'.$role->id]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

