@extends('layouts.app')

@php

use App\Project;
use Carbon\Carbon;

@endphp

@push('stylesheets')
@endpush

@push('scripts')

<script>
    var ctx1 = document.getElementById('task-area-chart').getContext('2d');
    var myChart1 = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: {!! json_encode($taskData['label']) !!},
            datasets: {!! json_encode($taskData['dataset']) !!}
        },
        options: {
                // maintainAspectRatio: false,
                scales: {
                    xAxes: [{reverse: !0, gridLines: {color: "rgba(0,0,0,0.05)"}}],
                    yAxes: [{
                        ticks: {stepSize: 10, display: !1},
                        min: 10,
                        max: 100,
                        display: !0,
                        borderDash: [5, 5],
                        gridLines: {color: "rgba(0,0,0,0)", fontColor: "#fff"}
                    }]
                },
                responsive: true,
                title: {
                    display: false,
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                legend: {
                    display: false
                }
            }
    });
    var ctx2 = document.getElementById('project-status-chart').getContext('2d');
    var myChart2 = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($project_status) !!},
            datasets: [
                        {
                            data: {!! json_encode(array_values($projectData)) !!},
                            backgroundColor: ["#40c5d2", "#f36a5b", "#67b7dc"],
                            borderColor: "transparent",
                            borderWidth: "3"
                        }
                    ]
        },
        options: {
            responsive: true,
                    legend: {
                        display: false,
                    },
                    title: {
                        display: false,
                        text: 'Chart.js Doughnut Chart'
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
        }
    });
</script>

@endpush

@section('breadcrumb')
<div class="breadcrumb-bar navbar bg-white sticky-top">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Workspace</li>
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
@php
$lead_percentage = $lead['lead_percentage'];
$project_percentage = $project['project_percentage'];
$client_project_budget_due_per = @$project['client_project_budget_due_per'];
$invoice_percentage = @$invoice['invoice_percentage'];

$label='';
if(($lead_percentage<=15)){
    $label='bg-danger';
}else if ($lead_percentage > 15 && $lead_percentage <= 33) {
    $label='bg-warning';
} else if ($lead_percentage > 33 && $lead_percentage <= 70) {
    $label='bg-primary';
} else {
    $label='bg-success';
}

 $label1='';
if($project_percentage<=15){
    $label1='bg-danger';
}else if ($project_percentage > 15 && $project_percentage <= 33) {
    $label1='bg-warning';
} else if ($project_percentage > 33 && $project_percentage <= 70) {
    $label1='bg-primary';
} else {
    $label1='bg-success';
}

$label2='';
if($invoice_percentage<=15){
    $label2='bg-danger';
}else if ($invoice_percentage > 15 && $invoice_percentage <= 33) {
    $label2='bg-warning';
} else if ($invoice_percentage > 33 && $invoice_percentage <= 70) {
    $label2='bg-primary';
} else {
    $label2='bg-success';
}

 $label3='';
if($client_project_budget_due_per<=15){
    $label3='bg-danger';
}else if ($client_project_budget_due_per > 15 && $client_project_budget_due_per <= 33) {
    $label3='bg-warning';
} else if ($client_project_budget_due_per > 33 && $client_project_budget_due_per <= 70) {
    $label3='bg-primary';
} else {
    $label3='bg-success';
}

@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="container-fluid">
            <div class="row pt-5">
                <div class="col-xs-6 col-sm-9">
                    <div class="row">
                        <div class="col">
                            <div class="card card-info">
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width:{{$lead_percentage}}%;"></div>
                                </div>
                                <div class="card-body">
                                    <div class="number">
                                        <h3 class="card-title">{{$lead['total_lead']}}</h3>
                                        <small class="card-text">{{__('LEADS')}}</small>
                                    </div>
                                    <div class="icon">
                                        <i class="material-icons">phone</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card card-info">
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width:{{$project_percentage}}%;"></div>
                                </div>
                                <div class="card-body">
                                    <div class="number">
                                        <h3 class="card-title">{{$project['total_project']}}</h3>
                                        <small class="card-text">{{__('PROJECTS')}}</small>
                                    </div>
                                    <div class="icon">
                                        <i class="material-icons">folder</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->type =='company' || Auth::user()->type =='client')
                        <div class="col">
                            <div class="card card-info">
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width:{{$invoice_percentage}}%;"></div>
                                </div>
                                <div class="card-body">
                                    <div class="number">
                                        <h3 class="card-title">{{$invoice['total_invoice']}}</h3>
                                        <small class="card-text">{{__('INVOICES')}}</small>
                                    </div>
                                    <div class="icon">
                                        <i class="material-icons">description</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(Auth::user()->type =='company')
                        <div class="col">
                            <div class="card card-info">
                                <div class="progress">
                                </div>
                                <div class="card-body">
                                    <div class="number">
                                        <h3 class="card-title">{{$users['staff']}}</h3>
                                        <small class="card-text">{{__('STAFF')}}</small>
                                    </div>
                                    <div class="icon">
                                        <i class="material-icons">people</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(Auth::user()->type =='client')
                        <div class="col">
                            <div class="card card-info">
                                <div class="progress">
                                    <div class="progress-bar bg-success" style="width: {{$client_project_budget_due_per}}%;"></div>
                                </div>
                                <div class="card-body">
                                    <div class="number">
                                        <h3 class="card-title">{{ Auth::user()->priceFormat($project['project_budget']) }}</h3>
                                        <small class="card-text">{{__('PPROJECTS BUDGET')}}</small>
                                    </div>
                                    <div class="icon">
                                        <i class="material-icons">attach_money</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card card-info">
                                <div class="card-body">
                                    <h5 class="card-title">Tasks overview</h5>
                                    <canvas id="task-area-chart" width="800" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-xs-12 col-sm-12">
                            <div class="card-list">
                                <div class="card-list-head">
                                <h6>{{__('Top Due Project')}}</h6>
                                <button class="btn-options" type="button" data-toggle="collapse" data-target="#projects">
                                    <i class="material-icons">more_horiz</i>
                                </button>
                                </div>
                                <div class="card-list-body collapse show" id="projects">
                                    @foreach($project['projects'] as $project)
                                    @php
                                        $datetime1 = new DateTime($project->due_date);
                                        $datetime2 = new DateTime(date('Y-m-d'));
                                        $interval = $datetime1->diff($datetime2);
                                        $days = $interval->format('%a');

                                        $project_last_stage = ($project->project_last_stage($project->id))?$project->project_last_stage($project->id)->id:'';
                                        $total_task = $project->project_total_task($project->id);
                                        $completed_task=$project->project_complete_task($project->id,$project_last_stage);
                                        $remain_task=$total_task-$completed_task;

                                        $project_percentage=0;
                                        if($total_task!=0){
                                            $project_percentage = intval(($completed_task / $total_task) * 100);
                                        }

                                        $label = $project->getProgressColor($project_percentage);

                                    @endphp
                                    <div class="card card-task">
                                        <div class="progress">
                                            <div class="progress-bar {{$label}}" role="progressbar" style="width: {{$project_percentage}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="card-body">
                                        <div class="card-title">
                                            <a href="{{ route('projects.show',$project->id) }}">
                                            <h6 data-filter-by="text">{{$project->name}}</h6>
                                            </a>
                                            <span class="text-small">{{ Carbon::parse($project->due_date)->diffForHumans() }}</span>
                                        </div>
                                        <div class="card-meta">
                                            <div class="d-flex align-items-center">
                                                <i class="material-icons">playlist_add_check</i>
                                                <span>{{$completed_task}}/{{$total_task}}</span>
                                            </div>
                                            <div class="dropdown card-options">
                                            <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="material-icons">more_vert</i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#">Mark as paid</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#">Archive</a>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-xs-12 col-sm-12">
                            <div class="card-list">
                                <div class="card-list-head">
                                <h6>{{__('Top Due Task')}}</h6>
                                <button class="btn-options" type="button" data-toggle="collapse" data-target="#tasks">
                                    <i class="material-icons">more_horiz</i>
                                </button>
                                </div>
                                <div class="card-list-body collapse show" id="tasks">
                                    @foreach($top_tasks as $top_task)

                                    @php
                                        $total_subtask = $top_task->taskTotalCheckListCount();
                                        $completed_subtask = $top_task->taskCompleteCheckListCount();

                                        $task_percentage=0;
                                        if($total_subtask!=0){
                                            $task_percentage = intval(($completed_subtask / $total_subtask) * 100);
                                        }

                                        $label = Project::getProgressColor($task_percentage);
                                    @endphp

                                    <div class="card card-task">
                                        <div class="progress">
                                            <div class="progress-bar {{$label}}" role="progressbar" style="width: {{$task_percentage}}%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <div class="card-body">
                                            <div class="card-title">
                                                <a href="{{ route('tasks.show',$top_task->id) }}" data-remote="true">
                                                    <h6 data-filter-by="text">{{$top_task->title}}</h6>
                                                </a>
                                                <span class="text-small">{{ Carbon::parse($top_task->due_date)->diffForHumans() }}</span>

                                                @if(\Auth::user()->type != 'client' && \Auth::user()->type != 'company')
                                                    <span class="text-small">{{$top_task->project_name}}</span>
                                                @else
                                                    <ul class="avatars">

                                                        @foreach($top_task->users as $user)
                                                        <li>
                                                            <a href="{{ route('users.index',$user->id) }}" data-toggle="tooltip" data-original-title="{{$user->name}}">
                                                                <img alt="{{$user->name}}" {!! empty($user->avatar) ? "avatar='".$user->name."'" : "" !!} class="avatar" src="{{asset(Storage::url("avatar/".$user->avatar))}}" data-filter-by="alt"/>
                                                            </a>
                                                        </li>
                                                        @endforeach
                                                    </ul>

                                                @endif

                                            </div>
                                            <div class="card-meta float-right">
                                                <div class="d-flex">
                                                    <span class="badge badge-secondary">{{ $top_task->stage_name }}</span>
                                                </div>

                                            <div class="dropdown card-options">
                                                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="material-icons">more_vert</i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#">Mark as done</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="#">Archive</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->type =='company' || Auth::user()->type =='client')
                        <div class="col-lg-6 col-xs-12 col-sm-12">
                            <div class="card-list">
                                <div class="card-list-head">
                                <h6>{{__('Top Due Payment')}}</h6>
                                <button class="btn-options" type="button" data-toggle="collapse" data-target="#payments">
                                    <i class="material-icons">more_horiz</i>
                                </button>
                                </div>
                                <div class="card-list-body collapse show" id="payments">
                                    @foreach($top_due_invoice as $invoice)
                                    <div class="card card-task">
                                        <div class="card-body">
                                        <div class="card-title">
                                            <a href="{{route('invoices.show',$invoice->id)}}">
                                            <h6 data-filter-by="text">{{ AUth::user()->invoiceNumberFormat($invoice->id) }}</h6>
                                            </a>
                                            <span class="text-small">{{__('Due Amount '). Auth::user()->priceFormat($invoice->getDue()) }}</span>
                                        </div>
                                        <div class="card-meta">
                                            <div class="d-flex align-items-center">
                                                <span class="text-small">{{ Auth::user()->dateFormat($invoice->due_date) }}</span>
                                            </div>
                                            <div class="dropdown card-options">
                                            <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="material-icons">more_vert</i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item" href="#">Mark as paid</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#">Archive</a>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(\Auth::user()->type != 'super admin')
                        <div class="col-lg-6 col-xs-12 col-sm-12">
                            <div class="card-list">
                                <div class="card-list-head">
                                <h6>{{__('Projects Status')}}</h6>
                                <button class="btn-options" type="button" data-toggle="collapse" data-target="#project-status">
                                    <i class="material-icons">more_horiz</i>
                                </button>
                                </div>
                                <div class="card-list-body collapse show" id="project-status">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <canvas id="project-status-chart"></canvas>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 graph-div"><span class="graph-font">{{ number_format($projectData['on_going'],2) }} % </span><br> On Going</div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 graph-div"><span class="graph-font">{{ number_format($projectData['on_hold'],2) }} % </span> <br>On Hold</div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 graph-div"><span class="graph-font">{{ number_format($projectData['completed'],2) }} % </span><br> Completed</div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @if(\Auth::user()->type!='client')
                <div class="col-xs-6 col-sm-3">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{__('Latest Activity')}} </h5>
                                        <ol class="timeline small">
                                        @foreach($activities as $activity)
                                        <li>
                                            <div class="media align-items-center">
                                                <div class="media-body">
                                                    <div>
                                                        <span data-filter-by="text">{!! $activity->remark !!}</span>
                                                    </div>
                                                    <span class="text-small" data-filter-by="text">{{$activity->created_at->diffforhumans()}}</span>
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ol>
                                </div>
                            </div>
                        </div>
                  </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
