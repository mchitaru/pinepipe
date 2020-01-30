@foreach ($roles as $role)
<div class="card card-contact">
    <div class="pl-3 row align-items-center">
        <div class="card-body">
            <div class="card-title mr-3 col-xs">
                <a href="#">
                <h6 data-filter-by="text">{{ $role->name }}</h6>
                </a>
            </div>
            <div class="card-meta col-xl">
                <div class="d-flex flex-wrap">
                    @for($j=0;$j<count($role->permissions()->pluck('name'));$j++)
                        <span class="badge badge-secondary">{{$role->permissions()->pluck('name')[$j]}}</span>
                    @endfor
                </div>
            </div>
            <div class="dropdown card-options">
                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    @can('edit role')
                    <a class="dropdown-item" href="{{ route('roles.edit',$role->id) }}" data-remote="true" data-type="text">
                        <span>{{__('Edit')}}</span>
                    </a>
                    @endcan
                    <div class="dropdown-divider"></div>
                    @can('delete role')
                        <a class="dropdown-item text-danger" href="{{ route('roles.destroy', $role->id) }}" data-method="delete" data-remote="true" data-type="text">
                            <span>{{__('Delete')}}</span>
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
