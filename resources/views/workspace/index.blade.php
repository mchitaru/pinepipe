@extends('layouts.app')

@push('stylesheets')
@endpush

@push('scripts')

    <script>
        var ctx = document.getElementById('canvas').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    fill: false,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
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
    <div class="row justify-content-center">
        <div class="container">
            <div class="row pt-5">
                <div class="col">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">2</h5>
                                    <p class="card-text">Total clients</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">5</h5>
                                    <p class="card-text">Total projects</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">3</h5>
                                    <p class="card-text">Total invoices</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">8</h5>
                                    <p class="card-text">Total users</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Tasks overview</h5>
                                    <canvas id="canvas" width="800" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card-list">
                                <div class="card-list-head">
                                <h6>Top due projects</h6>
                                <div class="dropdown">
                                    <button class="btn-options" type="button" id="..." data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                    ...
                                    </div>
                                </div>
                                </div>
                                <div class="card card-task">...</div>
                                <div class="card card-task">...</div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card-list">
                                <div class="card-list-head">
                                <h6>Top due tasks</h6>
                                <div class="dropdown">
                                    <button class="btn-options" type="button" id="..." data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="material-icons">more_vert</i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                    ...
                                    </div>
                                </div>
                                </div>
                                <div class="card card-task">
                                    <div class="progress">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 75%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="card-body">
                                    <div class="card-title">
                                        <a href="#">
                                        <h6 data-filter-by="text">Client objective meeting</h6>
                                        </a>
                                        <span class="text-small">Today</span>
                                    </div>
                                    <div class="card-meta">
                                        <div class="d-flex align-items-center">
                                        <i class="material-icons">playlist_add_check</i>
                                        <span>3/4</span>
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

                                <div class="card card-task">
                                    <div class="progress">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 20%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="card-body">
                                    <div class="card-title">
                                        <a href="#">
                                        <h6 data-filter-by="text">Target market trend analysis</h6>
                                        </a>
                                        <span class="text-small">5 days</span>
                                    </div>
                                    <div class="card-meta">
                                        <div class="d-flex align-items-center">
                                        <i class="material-icons">playlist_add_check</i>
                                        <span>2/10</span>
                                        </div>
                                        <div class="dropdown card-options">
                                        <button class="btn-options" type="button" id="task-dropdown-button-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="row">
                        <div class="card">
                            <div class="card-body">
                              <h5 class="card-title">Latest activity</h5>
                                <ol class="timeline small">
                                <li>
                                    <div class="media align-items-center">
                                        <div class="media-body">
                                        <div>
                                        <span class="h6" data-filter-by="text">Peggy</span>
                                        <span data-filter-by="text">added the note</span><a href="#" data-filter-by="text">Client Meeting Notes</a>
                                        </div>
                                        <span class="text-small" data-filter-by="text">Yesterday</span>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="media align-items-center">
                                    <div class="media-body">
                                        <div>
                                        <span class="h6" data-filter-by="text">David</span>
                                        <span data-filter-by="text">added the note</span><a href="#" data-filter-by="text">Aesthetic note</a>
                                        </div>
                                        <span class="text-small" data-filter-by="text">Yesterday</span>
                                    </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="media align-items-center">
                                    <div class="media-body">
                                        <div>
                                        <span class="h6" data-filter-by="text">Marcus</span>
                                        <span data-filter-by="text">was assigned to the task</span>
                                        </div>
                                        <span class="text-small" data-filter-by="text">4 days ago</span>
                                    </div>
                                    </div>
                                </li>
                            </ol>
                        </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
@endsection
