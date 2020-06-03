@php clock()->startEvent('wiki.index', "Display articles"); @endphp

@if(!isset($article))
    <div class="list-group py-4">
        @foreach($categories as $category)
            <a href="{{ Request::url().'/'.$category->slug }}" class="text-muted list-group-item list-group-item-action d-flex">
                <i class="material-icons pr-1">folder</i>
                {{$category->name}}
            </a>
        @endforeach
        @foreach($articles as $article)
            <a href="{{ Request::url().'/'.$article->slug  }}" class="list-group-item list-group-item-action d-flex">
                <i class="material-icons pr-1">article</i>
                {{$article->title}}
            </a>
        @endforeach
        @if($categories->isEmpty() && $articles->isEmpty())
            <p>{{__('No articles published yet.')}}</p>
        @endif    
    </div>

    @if(method_exists($articles,'links'))
    {{ $articles->links() }}
    @endif
@else
    <div class="py-4">
        @if(\Auth::user() && (\Auth::user()->creatorId() == $user->id))
        <div class="dropdown float-right">
            <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="material-icons">expand_more</i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
                @can('edit article')
                <a href="{{ route('articles.edit', $article->id) }}" class="dropdown-item" data-params="path={{Request::url()}}" data-remote="true" data-type="text" >
                    {{__('Edit')}}
                </a>
                @endcan
                <div class="dropdown-divider"></div>
                @can('delete article')
                <a href="{{ route('articles.destroy', $article->id) }}" class="dropdown-item text-danger" data-method="delete" data-remote="true" data-type="text">
                    {{__('Delete')}}
                </a>
                @endcan
            </div>
        </div>                    
        @endif
        <h1 class="mb-4">{{$article->title}}</h1>
        <p class="mb-2 text-muted text-monospace small">{{__('Updated')}}: {{$article->updated_at->diffForHumans()}}</p>
        {!!$article->content!!}
    </div>
@endif

@php clock()->endEvent('wiki.index'); @endphp
