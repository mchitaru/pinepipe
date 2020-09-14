@php clock()->startEvent('files.index', "Display files"); @endphp

<div class="col">
    <ul class="d-none dz-template">
        <li class="list-group-item dz-preview dz-file-preview">
        <div class="media align-items-center dz-details">
            <ul class="avatars">
            <li>
                <div class="avatar bg-primary dz-file-representation">
                <i class="material-icons">attach_file</i>
                </div>
            </li>
            <li>
                {{-- <a href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> --}}
                    {!!Helpers::buildUserAvatar(\Auth::user())!!}
                {{-- </a> --}}
            </li>
            </ul>
            <div class="media-body d-flex justify-content-between align-items-center">
            <div class="dz-file-details">
                <a href="#" class="dropzone-file dz-filename">
                    <span data-dz-name></span>
                </a>
                <br>
                <span class="text-small dz-size" data-dz-size></span>
            </div>
            <img alt="Loader" src="{{ asset('assets/img/loader.svg') }}" class="dz-loading" />
            <div class="dropdown">
                <button class="btn-options" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="material-icons">more_vert</i>
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                <a class="dropzone-file dropdown-item" href="#">{{__('Download')}}</a>
                <a class="dropzone-file dropdown-item disabled" href="#">{{__('Share')}}</a>
                <div class="dropdown-divider"></div>
                <a class="dropzone-delete dropdown-item text-danger disabled" href="#" data-method="delete" data-remote="true" data-type="text">{{__('Delete')}}</a>

                </div>
            </div>
            <button class="btn btn-danger btn-sm dz-remove" data-dz-remove>
                {{__('Cancel')}}
            </button>
            </div>
        </div>
        <div class="progress dz-progress">
            <div class="progress-bar dz-upload" data-dz-uploadprogress></div>
        </div>
        </li>
    </ul>

    <form class="dropzone" id="{{$dz_id}}">
        <span class="dz-message">{{__('Drop images, documents & archives here or click to upload.')}}</span>
    </form>

    <ul id="{{$dz_id}}-previews" class="list-group list-group-activity dropzone-previews flex-column-reverse">
    </ul>
</div>

@php clock()->endEvent('files.index'); @endphp
