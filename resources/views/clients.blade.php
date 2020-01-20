@extends('layouts.app')

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">CRM</li>
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
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="page-header">
            </div>
            <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#clients" role="tab" aria-controls="clients" aria-selected="true">Clients
                    <span class="badge badge-secondary">5</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#contacts" role="tab" aria-controls="contacts" aria-selected="false">Contacts
                    <span class="badge badge-secondary">20</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#activity" role="tab" aria-controls="activity" aria-selected="false">Activity</a>
            </li>
            </ul>
            <div class="tab-content">
            <div class="tab-pane fade show active" id="clients" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>Clients</h3>
                    <button class="btn btn-round" data-toggle="modal" data-target="#task-add-modal">
                    <i class="material-icons">add</i>
                    </button>
                </div>
                <form class="col-md-auto">
                    <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="material-icons">filter_list</i>
                        </span>
                    </div>
                    <input type="search" class="form-control filter-list-input" placeholder="Filter clients" aria-label="Filter Clients">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">
                    <div class="card card-contact">
                        <div class="card-body">
                        <div class="row card-title ">
                            <div class="col-1">
                                <ul class="avatars">
                                    <li>
                                        <a href="#" data-toggle="tooltip" title="Kenny">
                                        <img alt="Kenny Tran" class="avatar" src="assets/img/avatar-male-6.jpg" />
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <a href="#">
                                    <h4 data-filter-by="text">Kenny Tran</h4>
                                    </a>
                                </div>
                                <div class="row">
                                    <span class="text-small">2 contact(s):</span>
                                    <a class="text-small" href="#">Steven Garcia</a>
                                    <span class="text-small">,</span>
                                    <a class="text-small" href="#">Steven Garcia</a>
                                    </div>
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <span class="text-small">
                                        <i class="material-icons">email</i>
                                    </span>
                                    <a href="mailto:kenny.tran@example.com">
                                        <h6 data-filter-by="text">kenny.tran@example.com</h6>
                                    </a>
                                </div>
                                <div class="row">
                                    <i class="material-icons">phone</i>
                                    <span class="text-small">(237)555-2319</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-meta">
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
                    <div class="card card-contact">
                        <div class="card-body">
                        <div class="row card-title ">
                            <div class="col-1">
                                <ul class="avatars">
                                    <li>
                                        <a href="#" data-toggle="tooltip" title="Kenny">
                                        <img alt="Kenny Tran" class="avatar" src="assets/img/avatar-male-6.jpg" />
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <a href="#">
                                    <h4 data-filter-by="text">Kenny Tran</h4>
                                    </a>
                                </div>
                                <div class="row">
                                    <span class="text-small">2 contact(s):</span>
                                    <a class="text-small" href="#">Steven Garcia</a>
                                    <span class="text-small">,</span>
                                    <a class="text-small" href="#">Steven Garcia</a>
                                    </div>
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <span class="text-small">
                                        <i class="material-icons">email</i>
                                    </span>
                                    <a href="mailto:kenny.tran@example.com">
                                        <h6 data-filter-by="text">kenny.tran@example.com</h6>
                                    </a>
                                </div>
                                <div class="row">
                                    <i class="material-icons">phone</i>
                                    <span class="text-small">(237)555-2319</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-meta">
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
                    <div class="card card-contact">
                        <div class="card-body">
                        <div class="row card-title ">
                            <div class="col-1">
                                <ul class="avatars">
                                    <li>
                                        <a href="#" data-toggle="tooltip" title="Kenny">
                                        <img alt="Kenny Tran" class="avatar" src="assets/img/avatar-male-6.jpg" />
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <a href="#">
                                    <h4 data-filter-by="text">Kenny Tran</h4>
                                    </a>
                                </div>
                                <div class="row">
                                    <span class="text-small">2 contact(s):</span>
                                    <a class="text-small" href="#">Steven Garcia</a>
                                    <span class="text-small">,</span>
                                    <a class="text-small" href="#">Steven Garcia</a>
                                    </div>
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <span class="text-small">
                                        <i class="material-icons">email</i>
                                    </span>
                                    <a href="mailto:kenny.tran@example.com">
                                        <h6 data-filter-by="text">kenny.tran@example.com</h6>
                                    </a>
                                </div>
                                <div class="row">
                                    <i class="material-icons">phone</i>
                                    <span class="text-small">(237)555-2319</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-meta">
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


                <!--end of content list body-->
                </div>
                <!--end of content list-->
            </div>
            <!--end of tab-->
            <div class="tab-pane fade show" id="contacts" role="tabpanel" data-filter-list="content-list-body">
                <div class="row content-list-head">
                <div class="col-auto">
                    <h3>Clients</h3>
                    <button class="btn btn-round" data-toggle="modal" data-target="#task-add-modal">
                    <i class="material-icons">add</i>
                    </button>
                </div>
                <form class="col-md-auto">
                    <div class="input-group input-group-round">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                        <i class="material-icons">filter_list</i>
                        </span>
                    </div>
                    <input type="search" class="form-control filter-list-input" placeholder="Filter clients" aria-label="Filter Clients">
                    </div>
                </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">
                    <div class="card card-contact">
                        <div class="card-body">
                        <div class="row card-title ">
                            <div class="col-1">
                                <ul class="avatars">
                                    <li>
                                        <a href="#" data-toggle="tooltip" title="Kenny">
                                        <img alt="Kenny Tran" class="avatar" src="assets/img/avatar-male-6.jpg" />
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <a href="#">
                                    <h4 data-filter-by="text">Kenny Tran</h4>
                                    </a>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <span class="text-small">
                                        <i class="material-icons">email</i>
                                    </span>
                                    <a href="mailto:kenny.tran@example.com">
                                        <h6 data-filter-by="text">kenny.tran@example.com</h6>
                                    </a>
                                </div>
                                <div class="row">
                                    <i class="material-icons">phone</i>
                                    <span class="text-small">(237)555-2319</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-meta">
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
                    <div class="card card-contact">
                        <div class="card-body">
                        <div class="row card-title ">
                            <div class="col-1">
                                <ul class="avatars">
                                    <li>
                                        <a href="#" data-toggle="tooltip" title="Kenny">
                                        <img alt="Kenny Tran" class="avatar" src="assets/img/avatar-male-6.jpg" />
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <a href="#">
                                    <h4 data-filter-by="text">Kenny Tran</h4>
                                    </a>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <span class="text-small">
                                        <i class="material-icons">email</i>
                                    </span>
                                    <a href="mailto:kenny.tran@example.com">
                                        <h6 data-filter-by="text">kenny.tran@example.com</h6>
                                    </a>
                                </div>
                                <div class="row">
                                    <i class="material-icons">phone</i>
                                    <span class="text-small">(237)555-2319</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-meta">
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
                    <div class="card card-contact">
                        <div class="card-body">
                        <div class="row card-title ">
                            <div class="col-1">
                                <ul class="avatars">
                                    <li>
                                        <a href="#" data-toggle="tooltip" title="Kenny">
                                        <img alt="Kenny Tran" class="avatar" src="assets/img/avatar-male-6.jpg" />
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <a href="#">
                                    <h4 data-filter-by="text">Kenny Tran</h4>
                                    </a>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <span class="text-small">
                                        <i class="material-icons">email</i>
                                    </span>
                                    <a href="mailto:kenny.tran@example.com">
                                        <h6 data-filter-by="text">kenny.tran@example.com</h6>
                                    </a>
                                </div>
                                <div class="row">
                                    <i class="material-icons">phone</i>
                                    <span class="text-small">(237)555-2319</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-meta">
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
                <!--end of content list body-->
                </div>
                <!--end of content list-->
            </div>
            <!--end of tab-->
            <div class="tab-pane fade" id="activity" role="tabpanel" data-filter-list="list-group-activity">
                <div class="content-list">
                <div class="row content-list-head">
                    <div class="col-auto">
                    <h3>Activity</h3>
                    </div>
                    <form class="col-md-auto">
                    <div class="input-group input-group-round">
                        <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="material-icons">filter_list</i>
                        </span>
                        </div>
                        <input type="search" class="form-control filter-list-input" placeholder="Filter activity" aria-label="Filter activity">
                    </div>
                    </form>
                </div>
                <!--end of content list head-->
                <div class="content-list-body">
                    <ol class="list-group list-group-activity">

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">playlist_add_check</i>
                            </div>
                            </li>
                            <li>
                            <img alt="Claire" src="assets/img/avatar-female-1.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Claire</span>
                            <span data-filter-by="text">completed the task</span><a href="#" data-filter-by="text">Set up client chat channel</a>
                            </div>
                            <span class="text-small" data-filter-by="text">Just now</span>
                        </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">person_add</i>
                            </div>
                            </li>
                            <li>
                            <img alt="Ravi" src="assets/img/avatar-male-3.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Ravi</span>
                            <span data-filter-by="text">joined the project</span>
                            </div>
                            <span class="text-small" data-filter-by="text">5 hours ago</span>
                        </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">playlist_add</i>
                            </div>
                            </li>
                            <li>
                            <img alt="Kristina" src="assets/img/avatar-female-4.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Kristina</span>
                            <span data-filter-by="text">added the task</span><a href="#" data-filter-by="text">Produce broad concept directions</a>
                            </div>
                            <span class="text-small" data-filter-by="text">Yesterday</span>
                        </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">playlist_add</i>
                            </div>
                            </li>
                            <li>
                            <img alt="Marcus" src="assets/img/avatar-male-1.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Marcus</span>
                            <span data-filter-by="text">added the task</span><a href="#" data-filter-by="text">Present concepts and establish direction</a>
                            </div>
                            <span class="text-small" data-filter-by="text">Yesterday</span>
                        </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">person_add</i>
                            </div>
                            </li>
                            <li>
                            <img alt="Sally" src="assets/img/avatar-female-3.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Sally</span>
                            <span data-filter-by="text">joined the project</span>
                            </div>
                            <span class="text-small" data-filter-by="text">2 days ago</span>
                        </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">date_range</i>
                            </div>
                            </li>
                            <li>
                            <img alt="Claire" src="assets/img/avatar-female-1.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">Claire</span>
                            <span data-filter-by="text">rescheduled the task</span><a href="#" data-filter-by="text">Target market trend analysis</a>
                            </div>
                            <span class="text-small" data-filter-by="text">2 days ago</span>
                        </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="media align-items-center">
                        <ul class="avatars">
                            <li>
                            <div class="avatar bg-primary">
                                <i class="material-icons">add</i>
                            </div>
                            </li>
                            <li>
                            <img alt="David" src="assets/img/avatar-male-4.jpg" class="avatar" data-filter-by="alt" />
                            </li>
                        </ul>
                        <div class="media-body">
                            <div>
                            <span class="h6" data-filter-by="text">David</span>
                            <span data-filter-by="text">started the project</span>
                            </div>
                            <span class="text-small" data-filter-by="text">12 days ago</span>
                        </div>
                        </div>
                    </li>

                    </ol>
                </div>
                </div>
                <!--end of content list-->
            </div>
            </div>
        </div>
    </div>
</div>
@endsection
