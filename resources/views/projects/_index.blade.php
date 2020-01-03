@extends('layouts.admin')
@php
    $profile=asset(Storage::url('avatar/'));
@endphp
@push('css-page')
    <link href="{{ asset('assets/module/css/custom_project.css') }}" rel="stylesheet" type="text/css"/>
@endpush
@push('script-page')
    <script>

        var count = document.getElementById('testID').innerHTML.split(' ').length;
        var html = $("#testID").html();
        var remain = html.substring(count);
        if (count > 100) {
            html = html.substring(0, 500) + '<a id="read-more-btn"  onclick="read_more()" >Read more</a> <p id="read_more">' + remain + '</p>';

        }
        $("#testID").html(html);
        $('#read_more').hide();

        function read_more() {

            var x = document.getElementById("read_more");
            if (x.style.display === "none") {
                x.style.display = "block";
                $('#read-more-btn').css('display', 'none');
            } else {
                x.style.display = "none";
                $('#read-more-btn').css('display', 'block');
            }
        }
    </script>
@endpush
@section('page-title')
    {{__('Project')}}
@endsection
@section('breadcrumb')

    <ul class="page-breadcrumb">
        <li>
            <i class="fa fa-home"></i>
            <a href="{{ route('dashboard') }}">{{__('Home')}}</a>
            <i class="fa fa-angle-right"></i>
        </li>
        <li>
            <span>{{__('Project')}}</span>
        </li>
    </ul>
    <ul class="page-breadcrumb-1">
        <li>
            @can('create project')
                <span class="create-btn">
                <a href="#" data-url="{{ route('projects.create') }}" data-ajax-popup="true" data-title="{{__('Create New Project')}}" class="btn btn-circle btn-outline btn-sm blue-madison">
                <i class="fa fa-plus"></i>  {{__('Create')}}
            </a>
            </span>
            @endcan
        </li>
    </ul>
@endsection
@section('content')

    <div class="custom_project">
        <div class="row">

            @foreach ($projects as $project)

                @php
                    $permissions=$project->client_project_permission();
                    $perArr=(!empty($permissions)? explode(',',$permissions->permissions):[]);

                    $project_last_stage = ($project->project_last_stage($project->id)? $project->project_last_stage($project->id)->id:'');

                    $total_task = $project->project_total_task($project->id);
                    $completed_task=$project->project_complete_task($project->id,$project_last_stage);
                    $percentage=0;
                    if($total_task!=0){
                        $percentage = intval(($completed_task / $total_task) * 100);
                    }

                    $label='';
                    if($percentage<=15){
                        $label='bg-danger';
                    }else if ($percentage > 15 && $percentage <= 33) {
                        $label='bg-warning';
                    } else if ($percentage > 33 && $percentage <= 70) {
                        $label='bg-primary';
                    } else {
                        $label='bg-success';
                    }

                @endphp

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 ">
                    <div class="custom-portlet custom-portlet--height-fluid project-box">
                        <div class="custom-portlet__body custom-portlet__body--fit">
                            <div class="custom-widget custom-widget--project">
                                <div class="custom-widget__head">
                                    <div class="custom-widget__label">
                                        <div class="custom-widget__media">
                                            <span class="custom-media custom-media--lg custom-media--circle">
                                            </span>
                                        </div>
                                        <div class="custom-widget__info custom-margin-t-5">
                                            @can('show project')
                                                @if($project->is_active==1)
                                                    <a href="{{ route('projects.show',$project->id) }}" class="custom-widget__title uppercase">
                                                        {{ $project->name }}
                                                    </a>
                                                @else
                                                    <a href="#" class="custom-widget__title uppercase">
                                                        {{ $project->name }}
                                                    </a>
                                                @endif
                                            @else
                                                <a href="#" class="custom-widget__title uppercase">
                                                    {{ $project->name }}
                                                </a>
                                            @endcan
                                            <br>
                                            @foreach($project_status as $key => $status)
                                                @if($key== $project->status)
                                                    <div class="badge badge-info">
                                                        {{ $status}}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @if(Gate::check('edit project') || Gate::check('delete project') || Gate::check('create user'))
                                        @if($project->is_active==1)
                                            <div class="block block-2">
                                                <div class="input-group-btn">
                                                    <a href="#" class="dropdown-toggle btn" data-toggle="dropdown" aria-expanded="false"> <i class="fa fa-ellipsis-h"></i></a>
                                                    <ul class="dropdown-menu">
                                                        @can('edit project')
                                                            <li>
                                                                <a href="#" data-url="{{ route('projects.edit',$project->id) }}" data-ajax-popup="true" data-title="{{__('Edit Project')}}">
                                                                    <i class="fa fa-edit"></i> <span>{{__('Edit')}}</span>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                        @can('delete project')
                                                            <li>
                                                                <a href="#" class="" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$project->id}}').submit();">
                                                                    <i class="fa fa-trash"></i> <span>{{__('Delete')}}</span>
                                                                </a>
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['projects.destroy', $project->id],'id'=>'delete-form-'.$project->id]) !!}
                                                                {!! Form::close() !!}
                                                            </li>
                                                        @endcan
                                                        @can('manage invite user')
                                                            <li>
                                                                <a href="#" data-url="{{ route('project.invite',$project->id) }}" data-ajax-popup="true" data-title="{{__('Add User')}}" class="" data-toggle="tooltip" data-original-title="{{__('Add User')}}">
                                                                    <i class="fa fa-envelope"></i> <span>{{__('Add User')}}</span>
                                                                </a>
                                                            </li>
                                                        @endcan
                                                    </ul>
                                                </div>
                                            </div>

                                        @endif
                                    @endif
                                </div>

                                <div class="custom-widget__body">
                                    <div class="custom-widget__stats">
                                        <div class="custom-widget__item">
                                        <span class="custom-widget__date">
                                            {{__('Start Date')}}
                                        </span>
                                            <div class="custom-widget__label">
                                                <span class="btn btn-label-brand btn-md btn-bold btn-upper">{{ \Auth::user()->dateFormat($project->start_date) }} </span>
                                            </div>
                                        </div>

                                        <div class="custom-widget__item">
                                        <span class="custom-widget__date">
                                            {{__('Due Date')}}
                                        </span>
                                            <div class="custom-widget__label">
                                                <span class="btn btn-label-danger btn-md btn-bold btn-upper">{{ \Auth::user()->dateFormat($project->due_date) }}</span>
                                            </div>
                                        </div>

                                        <div class="custom-widget__item flex-fill">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <span class="custom-widget__subtitel bold">{{__('Progress')}}  </span>
                                                </div>
                                                <div class="col-md-6 text-right">
                                                    <b>{{$percentage}}%</b>
                                                </div>
                                            </div>


                                            <div class="custom-widget__progress d-flex  align-items-center">
                                                <div class="progress" style="height: 5px;width: 100%;">
                                                    <div class="progress-bar {{$label}}" role="progressbar" style="width: {{$percentage}}%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="custom-widget__content font-style">
                                        <div class="custom-widget__details">
                                            <span class="custom-widget__subtitle">{{__('Budget')}}</span>
                                            <span class="custom-widget__value"> {{ \Auth::user()->priceFormat($project->price) }}</span>
                                        </div>

                                        <div class="custom-widget__details">
                                            <span class="custom-widget__subtitle">{{__('Expencese')}}</span>
                                            <span class="custom-widget__value">{{ \Auth::user()->priceFormat($project->project_expenses()) }}</span>
                                        </div>
                                        <div class="custom-widget__details">
                                            <span class="custom-widget__subtitle">{{__('Client')}}</span>
                                            <span class="custom-widget__value">{{(!empty($project->client())?$project->client()->name:'')}}</span>
                                        </div>
                                        <div class="custom-widget__details">
                                            <span class="custom-widget__subtitle">{{__('Members')}}</span>
                                            <div class="custom-media-group">
                                                @foreach($project->project_user() as $project_user)
                                                    <a class="custom-media custom-media--sm custom-media--circle font-style" data-original-title="{{(!empty($project_user)?$project_user->name:'')}}" data-toggle="tooltip">
                                                        <img class="avatar" src="{{(!empty($project_user->avatar)? $profile.'/'.$project_user->avatar:$profile.'/avatar.png')}}">
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="custom-widget__footer">
                                    <div class="custom-widget__wrapper">
                                        <div class="custom-widget__section">
                                            <div class="custom-widget__blog">
                                                <i class="fa fa-list-ul"></i>
                                                @if($project->is_active==1)
                                                    <a href="{{ route('project.taskboard',$project->id) }}" class="custom-widget__value custom-font-brand">{{$project->countTask()}} {{__('Tasks')}}</a>
                                                @else
                                                    <a href="#" class="custom-widget__value custom-font-brand">{{$project->countTask()}} {{__('Tasks')}}</a>
                                                @endif
                                            </div>

                                            <div class="custom-widget__blog">
                                                <i class="fa fa-comment"></i>
                                                @if($project->is_active==1)
                                                    <a href="{{ route('project.taskboard',$project->id) }}" class="custom-widget__value custom-font-brand">{{$project->countTaskComments()}} {{__('Comments')}} </a>
                                                @else
                                                    <a href="#" class="custom-widget__value custom-font-brand">{{$project->countTaskComments()}} {{__('Comments')}} </a>
                                                @endif

                                            </div>
                                        </div>

                                        @can('show project')
                                            <div class="custom-widget__section">
                                                @if($project->is_active==1)
                                                    <a href="{{ route('projects.show',$project->id) }}" class="btn btn btn-outline btn-sm blue-madison">
                                                        {{__('Detail')}}
                                                    </a>
                                                @else
                                                    <i class="fa fa-lock"></i>
                                                @endif
                                            </div>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

@endsection


