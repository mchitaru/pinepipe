@php clock()->startEvent('notes.index', "Display notes"); @endphp

@foreach($notes as $note)
@can('view', $note)
<div class="card card-note">
    <div class="card-header p-1">
        <div class="media align-items-center">
            <a href="{{route('collaborators')}}"  title={{$note->user->name}}>
                {!!Helpers::buildUserAvatar($note->user)!!}
            </a>
            <div class="media-body">
            </div>
        </div>
        <div class="d-flex align-items-center">
        <span data-filter-by="text">{{$note->created_at->diffForHumans()}}</span>
        @can('update', $note)
            <div class="ml-1 dropdown card-options">
                <button class="btn-options" type="button" id="note-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="material-icons">more_vert</i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    @can('update', $note)
                        <a href="{{route('notes.edit', $note->id)}}" class="dropdown-item" data-remote="true">
                            {{__('Edit')}}
                        </a>
                    @endcan
                    @can('delete', $note)
                        <div class="dropdown-divider"></div>
                        <a href="{{route('notes.destroy', $note->id)}}" class="dropdown-item text-danger" data-method="delete" data-remote="true">
                            {{__('Delete')}}
                        </a>
                    @endcan
                </div>
            </div>
        @endcan
        </div>
    </div>
    <div class="card-body p-1" data-filter-by="text">
        {!! nl2br(Helpers::purify($note->text)) !!}
    </div>
</div>
@endcan
@endforeach

@if(method_exists($notes,'links'))
{{ $notes->links() }}
@endif

@php clock()->endEvent('notes.index'); @endphp
