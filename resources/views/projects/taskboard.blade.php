@extends('layouts.app')

@php
    $profile=asset(Storage::url('avatar/'));
    $permissions=$project->client_project_permission();
    $perArr=(!empty($permissions)? explode(',',$permissions->permissions):[]);
@endphp

@push('stylesheets')
    <link rel="stylesheet" href="{{asset('assets/module/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/module/css/selectric.css')}}">
@endpush

@push('scripts')
    <script src="{{asset('assets/module/js/select2.full.min.js')}}"></script>
    <script src="{{asset('assets/module/js/jquery.selectric.min.js')}}"></script>

    <script src="{{asset('assets/module/js/dragula.min.js')}}"></script>
    @can('move task')
        @if(\Auth::user()->type!='client' || (\Auth::user()->type=='client' && in_array('move task',$perArr)))
            <script>
                !function (a) {
                    "use strict";
                    var t = function () {
                        this.$body = a("body")
                    };
                    t.prototype.init = function () {
                        a('[data-plugin="dragula"]').each(function () {
                            var t = a(this).data("containers"), n = [];
                            if (t) for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]); else n = [a(this)[0]];
                            var r = a(this).data("handleclass");
                            r ? dragula(n, {
                                moves: function (a, t, n) {
                                    return n.classList.contains(r)
                                }
                            }) : dragula(n).on('drop', function (el, target, source, sibling) {

                                var order = [];
                                $("#" + target.id + " > div").each(function () {
                                    order[$(this).index()] = $(this).attr('data-id');
                                });

                                var id = $(el).attr('data-id');
                                var stage_id = $(target).attr('data-id');

                                $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div").length);
                                $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div").length);

                                $.ajax({
                                    url: '{{route('taskboard.order')}}',
                                    type: 'POST',
                                    data: {task_id: id, stage_id: stage_id, order: order, "_token": $('meta[name="csrf-token"]').attr('content')},
                                    success: function (data) {
                                        toastrs('Success', 'task successfully updated', 'success');
                                    },
                                    error: function (data) {
                                        data = data.responseJSON;
                                        toastrs('Error', data.error, 'error')
                                    }
                                });
                            });
                        })
                    }, a.Dragula = new t, a.Dragula.Constructor = t
                }(window.jQuery), function (a) {
                    "use strict";

                    a.Dragula.init()

                }(window.jQuery);
            </script>
        @endif
    @endcan
    <script>
        $(document).on('click', '#form-comment button', function (e) {
            var comment = $.trim($("#form-comment textarea[name='comment']").val());
            var name='{{\Auth::user()->name}}';
            if (comment != '') {
                $.ajax({
                    url: $("#form-comment").data('action'),
                    data: {comment: comment, "_token": $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    success: function (data) {
                        data = JSON.parse(data);

                        var html = "<li class='media'>" +
                            "                    <div class='media-body'>" +
                            "                        <h5 class='mt-0'>"+name+"</h5>" +
                            "                        " + data.comment +
                            "                           <div class='comment-trash' style=\"float: right\">" +
                            "                               <a class='btn btn-outline btn-sm red delete-comment' data-url='" + data.deleteUrl + "' >" +
                            "                                   <i class='fa fa-trash'></i>" +
                            "                               </a>" +

                            "                           </div>" +
                            "                    </div>" +
                            "                </li>";


                        $("#comments").prepend(html);
                        $("#form-comment textarea[name='comment']").val('');
                        toastrs('Success', '{{ __("Comment Added Successfully!")}}', 'success');
                    },
                    error: function (data) {
                        toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                    }
                });
            } else {
                toastrs('Error', '{{ __("Please write comment!")}}', 'error');
            }
        });

        $(document).on("click", ".delete-comment", function () {
            if (confirm('Are You Sure ?')) {
                var btn = $(this);
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'JSON',
                    success: function (data) {
                        toastrs('Success', '{{ __("Comment Deleted Successfully!")}}', 'success');
                        btn.closest('.media').remove();
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            toastrs('Error', data.message, 'error');
                        } else {
                            toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    }
                });
            }
        });

        $(document).on('submit', '#form-file', function (e) {

            e.preventDefault();
            $.ajax({
                url: $("#form-file").data('action'),
                type: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    toastrs('Success', '{{ __("Comment Added Successfully!")}}', 'success');
                    // console.log(data);
                    var delLink = '';

                    if (data.deleteUrl.length > 0) {
                        delLink = "<a href='#' class='text-danger text-muted delete-comment-file'  data-url='" + data.deleteUrl + "'>" +
                            "                                        <i class='dripicons-trash'></i>" +
                            "                                    </a>";
                    }

                    var html = '<li class="media">\n' +
                        '                                                <div class="media-body">\n' +
                        '                                                    <h5 class="mt-0 mb-1 font-weight-bold"> ' + data.name + '</h5>\n' +
                        '                                                   ' + data.file_size + '' +
                        '                                                    <div class="comment-trash" style="float: right">\n' +
                        '                                                        <a download href="{{asset('storage/tasks/')}}' + data.file + '" class="btn btn-outline btn-sm blue-madison">\n' +
                        '                                                            <i class="fa fa-download"></i>\n' +
                        '                                                        </a>' +
                        '<a href=\'#\' class="btn btn-outline btn-sm red delete-comment-file"  data-url="' + data.deleteUrl + '"><i class="fa fa-trash"></i></a>' +

                        '                                                    </div>\n' +
                        '                                                </div>\n' +
                        '                                            </li>';
                    $("#comments-file").prepend(html);
                },
                error: function (data) {
                    data = data.responseJSON;
                    if (data.message) {
                        toastrs('Error', data.message, 'error');
                        $('#file-error').text(data.errors.file[0]).show();
                    } else {
                        toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                    }
                }
            });
        });

        $(document).on("click", ".delete-comment-file", function () {
            if (confirm('Are You Sure ?')) {
                var btn = $(this);
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'JSON',
                    success: function (data) {
                        toastrs('Success', '{{ __("File Deleted Successfully!")}}', 'success');
                        btn.closest('.media').remove();
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            toastrs('Error', data.message, 'error');
                        } else {
                            toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    }
                });
            }
        });

        $(document).on('submit', '#form-checklist', function (e) {
            e.preventDefault();

            $.ajax({
                url: $("#form-checklist").data('action'),
                type: 'POST',
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    toastrs('Success', '{{ __("Checklist Added Successfully!")}}', 'success');

                    var html = '<li class="media">' +
                        '<div class="media-body">' +
                        '<h5 class="mt-0 mb-1 font-weight-bold"> </h5> ' +
                        '<div class=" custom-control custom-checkbox checklist-checkbox"> ' +
                        '<input type="checkbox" id="checklist-' + data.id + '" class="custom-control-input"  data-url="' + data.updateUrl + '">' +
                        '<label for="checklist-' + data.id + '" class="custom-control-label"></label> ' +
                        '' + data.name + ' </div>' +
                        '<div class="comment-trash" style="float: right"> ' +
                        '<a href="#" class="btn btn-outline btn-sm red text-muted delete-checklist" data-url="' + data.deleteUrl + '">\n' +
                        '                                                            <i class="fa fa-trash"></i>' +
                        '</a>' +
                        '</div>' +
                        '</div>' +
                        ' </li>';


                    $("#check-list").prepend(html);
                    $("#form-checklist input[name=name]").val('');
                    $("#form-checklist").collapse('toggle');
                },
            });
        });

        $(document).on("click", ".delete-checklist", function () {
            if (confirm('Are You Sure ?')) {
                var btn = $(this);
                $.ajax({
                    url: $(this).attr('data-url'),
                    type: 'DELETE',
                    data: {_token: $('meta[name="csrf-token"]').attr('content')},
                    dataType: 'JSON',
                    success: function (data) {
                        toastrs('Success', '{{ __("Checklist Deleted Successfully!")}}', 'success');
                        btn.closest('.media').remove();
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        if (data.message) {
                            toastrs('Error', data.message, 'error');
                        } else {
                            toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    }
                });
            }
        });

        var checked = 0;
        var count = 0;
        var percentage = 0;

        $(document).on("change", "#check-list input[type=checkbox]", function () {
            $.ajax({
                url: $(this).attr('data-url'),
                type: 'PUT',
                data: {_token: $('meta[name="csrf-token"]').attr('content')},
                // dataType: 'JSON',
                success: function (data) {
                    toastrs('Success', '{{ __("Checklist Updated Successfully!")}}', 'success');
                    // console.log(data);
                },
                error: function (data) {
                    data = data.responseJSON;
                    toastrs('Error', '{{ __("Some Thing Is Wrong!")}}', 'error');
                }
            });
            taskCheckbox();
        });


    </script>
@endpush

@section('page-title')
    {{__('Task')}}
@endsection

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">{{__('Home')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                <a href="{{ route('projects.index') }}">{{__('Projects')}}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{$project->name}}
            </li>
            <li>
                <span>{{__('Task')}}</span>
            </li>
        </ol>
    </nav>

    <div class="dropdown">
        <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
        <i class="material-icons">bookmarks</i>
        </button>
        <div class="dropdown-menu dropdown-menu-right">

        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#project-edit-modal">Edit Project</a>
        <a class="dropdown-item" href="#">Share</a>
        <a class="dropdown-item" href="#">Mark as Complete</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item text-danger" href="#">Archive</a>

        </div>
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="portlet light portlet-fit portlet-datatable ">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-tasks font-blue"></i>
                        <span class="caption-subject font-blue sbold uppercase">{{__('Manage Task')}}</span>
                    </div>
                    @can('create task')
                        <span class="create-btn">
                        <a href="#" data-url="{{ route('task.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Add New Task')}}" class="btn btn-circle btn-outline btn-sm blue-madison">
                        <i class="fa fa-plus"></i> &nbsp;&nbsp; {{__('Create')}}
                    </a>
                    </span>
                    @endcan
                </div>
                <div class="portlet-body font-style">
                    <div class="col-12">
                        @php
                            $json = [];
                            foreach ($stages as $stage){
                                $json[] = 'lead-list-'.$stage->id;
                            }
                        @endphp
                        <div class="board" data-plugin="dragula" data-containers='{!! json_encode($json) !!}'>
                            @foreach($stages as $stage)

                                @if(\Auth::user()->type =='client' || \Auth::user()->type =='company')
                                    @php $tasks =$stage->tasks($project->id) @endphp
                                @else
                                    @php $tasks =$stage->tasks($project->id)     @endphp
                                @endif
                                <div class="leads">
                                    <h5 class="mt-0 mb-0 lead-header">{{$stage->name}} (<span class="count">
                                                {{ count($tasks) }}</span>)
                                    </h5>
                                    <div id="lead-list-{{$stage->id}}" data-id="{{$stage->id}}" class="lead-list-items">
                                        @foreach($tasks as $task)
                                            <div class="card mb-2 mt-0" data-id="{{$task->id}}">
                                                <div class="card-body">
                                                    @if(Gate::check('edit task') || Gate::check('delete task'))
                                                        <div class="lead-action">
                                                            <div class="dropdown">
                                                                <a href="#" class="dropdown-toggle btn" data-toggle="dropdown" aria-expanded="false"> <i class="fa fa-ellipsis-h"></i></a>
                                                                <div class="dropdown-menu dropdown-menu-right">
                                                                    @can('edit task')
                                                                        <a href="#" data-url="{{ route('task.edit',$task->id) }}" data-ajax-popup="true" data-title="{{__('Edit Task')}}" class="dropdown-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                            <i class="fa fa-edit"></i> <span>{{__('Edit')}}</span>
                                                                        </a>
                                                                    @endcan
                                                                    @can('delete task')
                                                                        <a href="#" class="dropdown-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$task->id}}').submit();">
                                                                            <i class="fa fa-trash"></i> <span>{{__('Delete')}}</span>
                                                                        </a>
                                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['task.destroy', $task->id],'id'=>'delete-form-'.$task->id]) !!}
                                                                        {!! Form::close() !!}
                                                                    @endcan
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h5>
                                                            <a data-url="{{ route('task.show',$task->id) }}" data-ajax-popup="true" data-title="{{__('Task Board')}}" class="text-body">{{$task->title}}</a>

                                                            <span class="card-text small text-muted">
                                                            @if($task->priority =='low')
                                                                    <div class="label label-soft-success"> {{ $task->priority }}</div>
                                                                @elseif($task->priority =='medium')
                                                                    <div class="label label-soft-warning"> {{ $task->priority }}</div>
                                                                @elseif($task->priority =='high')
                                                                    <div class="label label-soft-danger"> {{ $task->priority }}</div>
                                                                @endif
                                                         </span>
                                                        </h5>
                                                        <div class="row align-items-center mb-10">
                                                            <div class="col">
                                                                <p class="card-text small text-muted label @if($task->taskCompleteCheckListCount()==$task->taskTotalCheckListCount() && $task->taskCompleteCheckListCount()!=0) label-soft-success @else label-soft-warning @endif">
                                                                    {{$task->taskCompleteCheckListCount()}}/{{$task->taskTotalCheckListCount()}}
                                                                </p>
                                                            </div>
                                                            <div class="col-auto">
                                                            </div>
                                                        </div>
                                                        <div class="row align-items-center">
                                                            <div class="col">
                                                                <p class="card-text small text-muted">
                                                                    <i class="fa fa-clock-o"></i> {{ \Auth::user()->dateFormat($task->due_date) }}
                                                                </p>
                                                            </div>
                                                            <div class="col-auto">
                                                                <div class="avatar-group">
                                                                    <a href="#" class="avatar avatar-xs" data-toggle="tooltip" title="" data-original-title="{{(!empty($task->task_user)?$task->task_user->name:'')}}">
                                                                        <img src="{{(!empty($task->task_user->avatar)?$profile.'/'.$task->task_user->avatar:$profile.'/avatar.png')}}" class="avatar-img rounded-circle">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




