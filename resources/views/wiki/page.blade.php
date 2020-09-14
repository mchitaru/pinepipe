@extends('layouts.wiki')

@php clock()->startEvent('wikisection.index', "Display wiki section"); @endphp

@push('stylesheets')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script>
    $(function() {
        loadContent($('.paginate-container:visible'));        
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
@endpush

@section('page-title')
    {{__('Articles')}}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">
            <div class="page-header">
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="articles" role="tabpanel">
                    <div class="row content-list-head">
                        <div class="col-auto">
                            <h3>{{__('Articles')}}</h3>
                            @if(\Auth::user() && (\Auth::user()->created_by == $user->id) && Gate::check('create article'))
                            <a href="{{ route('articles.create') }}" class="btn btn-primary btn-round" data-params="path={{Request::url()}}" data-remote="true" data-type="text" >
                                <i class="material-icons">add</i>
                            </a>
                            @endif
                        </div>                            
                        <div class="d-flex align-items-center">
                            <div class="col-md-auto">
                                <div class="input-group input-group-round">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                        <i class="material-icons">filter_list</i>
                                        </span>
                                    </div>
                                    <input type="search" class="form-control filter-input" placeholder="{{__('Filter Articles')}}" aria-label="{{__('Filter Articles')}}">
                                </div>
                            </div>
                            {{-- <div class="dropdown">
                                <button class="btn btn-round" role="button" data-toggle="dropdown" aria-expanded="false">
                                <i class="material-icons">expand_more</i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    @can('create article'  && (\Auth::user()->created_by == $user->id))
                                    <a href="{{ route('articles.create') }}" class="dropdown-item" data-params="category_id={{$category?$category->id:0}}" data-remote="true" data-type="text" >
                                        {{__('New article')}}
                                    </a>
                                    @endcan
                                </div>
                            </div>                     --}}
                        </div>
                    </div>
                    <!--end of content list head-->
                    <div class="bg-light py-4 border-bottom">
                        <div class="container">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb p-0 m-0 bg-transparent">
                                    <li class="breadcrumb-item {{ $breadcrumbs->isEmpty() ? 'active' : '' }}"><a href="{{$home}}">{{__('Home')}}</a></li>
                                    @foreach ($breadcrumbs as $key => $url)
                                        @if($loop->index > 1)
                                        <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}" {{ $loop->last ? 'aria-current=page' : '' }}>
                                            @if(!$loop->last)
                                            <a href="{{ url($url) }}">
                                                {{ ucfirst($key) }}
                                            </a>
                                            @else
                                                {{ ucfirst($key) }}
                                            @endif
                                        </li>
                                        @endif
                                    @endforeach                
                                </ol>
                            </nav>
                        </div>
                    </div>                                        
                    <div class="content-list-body filter-list paginate-container">
                    </div>
                    <!--end of content list body-->
                </div>
            <!--end of tab-->
            </div>
        </div>
    </div>
</div>
@endsection

@php clock()->endEvent('wikisection.index'); @endphp
