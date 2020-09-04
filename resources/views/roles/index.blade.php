@foreach ($roles as $role)
<div class="card card-contact">
    <div class="pl-3 row align-items-center">
        <div class="card-body">
            <div class="card-title d-flex align-items-center mr-3 col-xs">
                @if(Gate::check('edit permission'))
                    <a href="{{ route('roles.edit',$role->id) }}" data-remote="true" data-type="text">
                        <h6 data-filter-by="text">{{ $role->name }}</h6>
                    </a>
                @else
                    <h6 data-filter-by="text">{{ $role->name }}</h6>
                @endif
                @if($role->created_by == 1)
                    <span class="ml-3 badge badge-warning">{{__('default')}}</span>
                @endif
            </div>
            <div class="card-meta col-xl">
                <div class="d-flex flex-wrap">
                    @for($j=0;$j<count($role->permissions()->pluck('name'));$j++)
                        <span class="badge badge-light">{{$role->permissions()->pluck('name')[$j]}}</span>
                    @endfor
                </div>
            </div>
            <div class="dropdown card-options">
                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    @can('edit permission')
                    <a class="dropdown-item" href="{{ route('roles.edit',$role->id) }}" data-remote="true" data-type="text">
                        <span>{{__('Edit')}}</span>
                    </a>
                    @endcan
                    @if($role->created_by != 1 && Gate::check('delete permission'))
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="{{ route('roles.destroy', $role->id) }}" data-method="delete" data-remote="true" data-type="text">
                            <span>{{__('Delete')}}</span>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
