@extends('layouts.admin')

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
                <div class="card card-task">
                    <div class="card-body">
                        <a href="#" data-toggle="tooltip" title={{$user->name}}>
                        <img alt={{$user->name}} class="avatar" src="{{(!empty($user->avatar))? asset(Storage::url("avatar/".$user->avatar)): asset(Storage::url("avatar/avatar.png"))}}" />
                        </a>
                    <div class="card-title">
                        <a href="#">
                        <h4 data-filter-by="text">{{$user->name}}</h4>
                        </a>
                        <span class="text-small">{{$user->type}}</span>
                    </div>
                    <div class="card-meta">
                        <span class="d-flex">
                            <i class="material-icons">email</i>
                            <a href="mailto:kenny.tran@example.com">
                                <h6 data-filter-by="text">{{$user->email}}</h6>
                            </a>
                        </span>
                        <span class="text-small">
                            {{($user->delete_status==0)?'Soft deleted':''}}
                        </span>
                    </div>
                    <div class="card-meta">
                        <div class="d-flex align-items-center">
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
                            <span class="badge badge-secondary">
                                <i class="material-icons" title="Projects">folder</i>
                                {{$user->user_project()}}
                            </span>
                            <span class="badge badge-secondary">
                                <i class="material-icons" title="Expenses">payment</i>
                                {{\Auth::user()->priceFormat($user ->user_expense())}}
                            </span>
                            <span class="badge badge-secondary">
                                <i class="material-icons">assignement</i>
                                {{$user->user_assign_task()}}
                            </span>
                            @endif
                        </div>
                        <div class="dropdown card-options">
                            @if($user->is_active==1)
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
                                            <span>{{($user->delete_status!=0)?'Delete':'Remove Soft Delete'  }}</span>
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
        </form>

    </div>
</div>
@endsection
