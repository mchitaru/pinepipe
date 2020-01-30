@extends('layouts.app')

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('page-title')
    {{__('User')}}
@endsection

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{__('User')}}</li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#team-manage-modal">Edit Team</a>
            <a class="dropdown-item" href="#">Share</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" href="#">Leave</a>

        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-xl-10 col-lg-11">
        <div class="page-header">
        </div>
        <div class="tab-content">
        <div class="tab-pane fade show active" id="users" role="tabpanel" data-filter-list="content-list-body">
            <div class="row content-list-head">
            <div class="col-auto">
                <h3>{{__('Users')}}</h3>
                @can('create user')
                    <button class="btn btn-round" data-url="{{ route('users.create') }}" data-ajax-popup="true" data-title="{{__('Create New User')}}">
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
                @foreach($users as $user)
                <div class="card card-task mb-1" style="min-height: 67px;">
                    <div class="container row align-items-center">
                        <div class="pl-2 position-absolute">
                            <a href="#" data-toggle="tooltip" title={{$user->name}}>
                                <img alt="{{$user->name}}" {!! empty($user->avatar) ? "avatar='".$user->name."'" : "" !!} class="avatar" src="{{Storage::url($user->avatar)}}" data-filter-by="alt"/>
                            </a>
                        </div>
                        <div class="card-body p-2 pl-5">
                            <div class="card-title col-xs-12 col-sm-4">
                                <a href="#">
                                <h6 data-filter-by="text">{{$user->name}}</h6>
                                </a>
                                <span class="text-small">{{$user->type}}</span>
                            </div>
                            <div class="card-title col-xs-12 col-sm-4">
                                <span class="d-flex align-items-center">
                                    <i class="material-icons">email</i>
                                    <a href="mailto:kenny.tran@example.com">
                                        <span data-filter-by="text" class="text-small">
                                            {{$user->email}}
                                        </span>
                                    </a>
                                </span>
                                <span class="text-small">
                                    {{(!$user->delete_status)?'Soft deleted':''}}
                                </span>
                            </div>
                            <div class="card-meta col-2">
                                <div class="d-flex align-items-center justify-content-end">
                                    @if(\Auth::user()->type=='super admin')
                                    <span class="badge badge-secondary mr-2">
                                        <i class="material-icons" title="Users">people</i>
                                        {{$user->total_company_user($user->id)}}
                                    </span>
                                    <span class="badge badge-secondary mr-2">
                                        <i class="material-icons" title="Projects">folder</i>
                                        {{$user->total_company_project($user->id)}}
                                    </span>
                                    <span class="badge badge-secondary mr-2">
                                        <i class="material-icons" title="Clients">storefront</i>
                                        {{$user->total_company_client($user->id)}}
                                    </span>
                                    @else
                                    <span class="badge badge-secondary mr-2">
                                        <i class="material-icons" title="Projects">folder</i>
                                        {{$user->user_projects_count()}}
                                    </span>
                                    <span class="badge badge-secondary mr-2">
                                        <i class="material-icons" title="Tasks">playlist_add_check</i>
                                        {{$user->user_tasks_count()}}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="dropdown card-options">
                                    @if($user->is_active)
                                        <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="material-icons">more_vert</i>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right">
                                            @can('edit user')
                                            <a class="dropdown-item" href="#" data-url="{{ route('users.edit',$user->id) }}" data-ajax-popup="true" data-title="{{__('Update User')}}">
                                                <span>{{__('Edit')}}</span>
                                            </a>
                                            @endcan
                                            <div class="dropdown-divider"></div>
                                            @can('delete user')
                                                <a class="dropdown-item text-danger" href="#" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$user['id']}}').submit();">
                                                    <span>{{($user->delete_status)?'Delete':'Remove Soft Delete'  }}</span>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user['id']],'id'=>'delete-form-'.$user['id']]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </div>
                                    @else
                                    <i class="material-icons">lock</i>
                                    @endif
                                </div>
                            </div>
                    </div>
                </div>
                @endforeach
            </div>
            </div>
            <!--end of modal body-->
        </div>
    </div>
</div>
</div>
@endsection
