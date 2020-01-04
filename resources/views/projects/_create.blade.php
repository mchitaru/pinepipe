{{ Form::open(array('url' => 'projects')) }}
<div class="modal-header">
    <h5 class="modal-title">New Project</h5>
    <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
    <i class="material-icons">close</i>
    </button>
</div>
<!--end of modal head-->
<ul class="nav nav-tabs nav-fill" role="tablist">
    <li class="nav-item">
    <a class="nav-link active" id="project-add-details-tab" data-toggle="tab" href="#project-add-details" role="tab" aria-controls="project-add-details" aria-selected="true">Details</a>
    </li>
    <li class="nav-item">
    <a class="nav-link" id="project-add-members-tab" data-toggle="tab" href="#project-add-members" role="tab" aria-controls="project-add-members" aria-selected="false">Members</a>
    </li>
</ul>
<div class="modal-body">
    <div class="tab-content">
    <div class="tab-pane fade show active" id="project-add-details" role="tabpanel">
        <h6>General Details</h6>
        <div class="form-group row align-items-center">
            <label class="col-3" name="name">{{__('Project Name')}}</label>
            <input class="form-control col" type="text" placeholder="Project name" name="name" required="required"/>
            @error('name')
                <span class="invalid-name" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group row">
            <label class="col-3" name="description">{{__('Description')}}</label>
            <textarea class="form-control col" rows="3" placeholder="Project description" name="description"></textarea>
            @error('description')
                <span class="invalid-description" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <hr>
        <h6>Timeline</h6>
        <div class="form-group row align-items-center">
            <label class="col-3" name="start_date">{{__('Start Date')}}</label>
            <input class="mydate form-control col" type="text" name="start_date" placeholder="Select a date" data-flatpickr data-default-date="2021-04-21" data-alt-input="true" />
            @error('start_date')
                <span class="invalid-start_date" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group row align-items-center">
            <label class="col-3" name="due_date">{{__('Due Date')}}</label>
            <input class="mydate form-control col" type="text" name="due_date" placeholder="Select a date" data-flatpickr data-default-date="2021-09-15" data-alt-input="true" />
            @error('due_date')
                <span class="invalid-due_date" role="alert">
                    <strong class="text-danger">{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="alert alert-warning text-small" role="alert">
        <span>You can change due dates at any time.</span>
        </div>
        <hr>
        <h6>Visibility</h6>
        <div class="row">
        <div class="col">
            <div class="custom-control custom-radio">
            <input type="radio" id="visibility-everyone" name="visibility" class="custom-control-input" checked>
            <label class="custom-control-label" for="visibility-everyone">Everyone</label>
            </div>
        </div>
        <div class="col">
            <div class="custom-control custom-radio">
            <input type="radio" id="visibility-members" name="visibility" class="custom-control-input">
            <label class="custom-control-label" for="visibility-members">Members</label>
            </div>
        </div>
        <div class="col">
            <div class="custom-control custom-radio">
            <input type="radio" id="visibility-me" name="visibility" class="custom-control-input">
            <label class="custom-control-label" for="visibility-me">Just me</label>
            </div>
        </div>
        </div>
    </div>
    <div class="tab-pane fade" id="project-add-members" role="tabpanel">
        <div class="users-manage" data-filter-list="form-group-users">
        <div class="mb-3">
            <ul class="avatars text-center">

            <li>
                <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar" data-toggle="tooltip" data-title="Claire Connors" />
            </li>

            <li>
                <img alt="Marcus Simmons" src="assets/img/avatar-male-1.jpg" class="avatar" data-toggle="tooltip" data-title="Marcus Simmons" />
            </li>

            <li>
                <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar" data-toggle="tooltip" data-title="Peggy Brown" />
            </li>

            <li>
                <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar" data-toggle="tooltip" data-title="Harry Xai" />
            </li>

            </ul>
        </div>
        <div class="input-group input-group-round">
            <div class="input-group-prepend">
            <span class="input-group-text">
                <i class="material-icons">filter_list</i>
            </span>
            </div>
            <input type="search" class="form-control filter-list-input" placeholder="Filter members" aria-label="Filter Members">
        </div>
        <div class="form-group-users">

            <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="project-user-1" checked>
            <label class="custom-control-label" for="project-user-1">
                <span class="d-flex align-items-center">
                <img alt="Claire Connors" src="assets/img/avatar-female-1.jpg" class="avatar mr-2" />
                <span class="h6 mb-0" data-filter-by="text">Claire Connors</span>
                </span>
            </label>
            </div>

            <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="project-user-2" checked>
            <label class="custom-control-label" for="project-user-2">
                <span class="d-flex align-items-center">
                <img alt="Marcus Simmons" src="assets/img/avatar-male-1.jpg" class="avatar mr-2" />
                <span class="h6 mb-0" data-filter-by="text">Marcus Simmons</span>
                </span>
            </label>
            </div>

            <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="project-user-3" checked>
            <label class="custom-control-label" for="project-user-3">
                <span class="d-flex align-items-center">
                <img alt="Peggy Brown" src="assets/img/avatar-female-2.jpg" class="avatar mr-2" />
                <span class="h6 mb-0" data-filter-by="text">Peggy Brown</span>
                </span>
            </label>
            </div>

            <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="project-user-4" checked>
            <label class="custom-control-label" for="project-user-4">
                <span class="d-flex align-items-center">
                <img alt="Harry Xai" src="assets/img/avatar-male-2.jpg" class="avatar mr-2" />
                <span class="h6 mb-0" data-filter-by="text">Harry Xai</span>
                </span>
            </label>
            </div>

            <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="project-user-5">
            <label class="custom-control-label" for="project-user-5">
                <span class="d-flex align-items-center">
                <img alt="Sally Harper" src="assets/img/avatar-female-3.jpg" class="avatar mr-2" />
                <span class="h6 mb-0" data-filter-by="text">Sally Harper</span>
                </span>
            </label>
            </div>

            <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="project-user-6">
            <label class="custom-control-label" for="project-user-6">
                <span class="d-flex align-items-center">
                <img alt="Ravi Singh" src="assets/img/avatar-male-3.jpg" class="avatar mr-2" />
                <span class="h6 mb-0" data-filter-by="text">Ravi Singh</span>
                </span>
            </label>
            </div>

            <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="project-user-7">
            <label class="custom-control-label" for="project-user-7">
                <span class="d-flex align-items-center">
                <img alt="Kristina Van Der Stroem" src="assets/img/avatar-female-4.jpg" class="avatar mr-2" />
                <span class="h6 mb-0" data-filter-by="text">Kristina Van Der Stroem</span>
                </span>
            </label>
            </div>

            <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="project-user-8">
            <label class="custom-control-label" for="project-user-8">
                <span class="d-flex align-items-center">
                <img alt="David Whittaker" src="assets/img/avatar-male-4.jpg" class="avatar mr-2" />
                <span class="h6 mb-0" data-filter-by="text">David Whittaker</span>
                </span>
            </label>
            </div>

            <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="project-user-9">
            <label class="custom-control-label" for="project-user-9">
                <span class="d-flex align-items-center">
                <img alt="Kerri-Anne Banks" src="assets/img/avatar-female-5.jpg" class="avatar mr-2" />
                <span class="h6 mb-0" data-filter-by="text">Kerri-Anne Banks</span>
                </span>
            </label>
            </div>

            <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="project-user-10">
            <label class="custom-control-label" for="project-user-10">
                <span class="d-flex align-items-center">
                <img alt="Masimba Sibanda" src="assets/img/avatar-male-5.jpg" class="avatar mr-2" />
                <span class="h6 mb-0" data-filter-by="text">Masimba Sibanda</span>
                </span>
            </label>
            </div>

            <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="project-user-11">
            <label class="custom-control-label" for="project-user-11">
                <span class="d-flex align-items-center">
                <img alt="Krishna Bajaj" src="assets/img/avatar-female-6.jpg" class="avatar mr-2" />
                <span class="h6 mb-0" data-filter-by="text">Krishna Bajaj</span>
                </span>
            </label>
            </div>

            <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="project-user-12">
            <label class="custom-control-label" for="project-user-12">
                <span class="d-flex align-items-center">
                <img alt="Kenny Tran" src="assets/img/avatar-male-6.jpg" class="avatar mr-2" />
                <span class="h6 mb-0" data-filter-by="text">Kenny Tran</span>
                </span>
            </label>
            </div>

        </div>
        </div>
    </div>
    </div>
</div>
<!--end of modal body-->
<div class="modal-footer">
    <button role="button" class="btn btn-primary" type="submit">
    Create Project
    </button>
</div>
{{ Form::close() }}

<script>
flatpickr('.mydate');
</script>