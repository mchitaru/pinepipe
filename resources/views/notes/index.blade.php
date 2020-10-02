@php clock()->startEvent('notes.index', "Display notes"); @endphp

@foreach($notes as $note)
<div class="card card-note">
    <div class="card-header p-1">
        <div class="media align-items-center">
            <a href="{{route('users.index',$note->user->id)}}"  title={{$note->user->name}}>
                {!!Helpers::buildUserAvatar($note->user)!!}
            </a>
            <div class="media-body">
                <h6 class="mb-0" data-filter-by="text">{{$note->title}}</h6>
            </div>
        </div>
        <div class="d-flex align-items-center">
        <span data-filter-by="text">{{$note->created_at->diffForHumans()}}</span>
        <div class="ml-1 dropdown card-options">
            <button class="btn-options" type="button" id="note-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="material-icons">more_vert</i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="{{route('notes.edit', $note->id)}}" class="dropdown-item" data-remote="true">
                    {{__('Edit')}}
                </a>
                <a href="{{route('notes.destroy', $note->id)}}" class="dropdown-item text-danger" data-method="delete" data-remote="true">
                    {{__('Delete')}}
                </a>
            </div>
        </div>
        </div>
    </div>
    <div class="card-body p-1" data-filter-by="text">
        {!! nl2br(e($note->text)) !!}
    </div>
</div>
@endforeach

@if(method_exists($notes,'links'))
{{ $notes->links() }}
@endif

@php clock()->endEvent('notes.index'); @endphp
