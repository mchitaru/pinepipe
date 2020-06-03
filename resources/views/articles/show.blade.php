@extends('layouts.knowledgebase')

@php clock()->startEvent('articlessection.show', "Display article"); @endphp

@push('stylesheets')
@endpush

@push('scripts')
@endpush

@section('page-title')
    {{__('Article')}}
@endsection

@section('content')
<div class="bg-primary py-5">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <h1 class="text-light mb-4">{{$article->title}}</h1>
      </div>
    </div>
  </div>
</div>
  
<div class="bg-light py-4 border-bottom">
<div class="container">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb p-0 m-0 bg-transparent">
      <li class="breadcrumb-item {{ $breadcrumbs->isEmpty() ? 'active' : '' }}"><a href="{{$home}}">Home</a></li>
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
<div class="bg-white py-5">
<div class="container">
<div class="row justify-content-between">
<div class="col-12 col-md-8 col-lg-8 mb-5 mb-md-0">
  <p class="text-muted text-monospace small">{{__('Updated')}}: {{$article->updated_at->diffForHumans()}}</p>
  {!!$article->content!!}
</div>
<div class="col-12 col-md-4 col-lg-3">
<div class="mb-5">
<h5>On This Page</h5>
<div class="list-group">
 <a href="#" class="list-group-item list-group-item-action">Overview</a>
<a href="#" class="list-group-item list-group-item-action">Requirements</a>
<a href="#" class="list-group-item list-group-item-action">Examples</a>
<a href="#" class="list-group-item list-group-item-action">Related</a>
</div>
</div>
<div class="mb-5">
<h5>Categories</h5>
<div class="list-group">
<a href="#" class="list-group-item list-group-item-action">Configuration</a>
<a href="#" class="list-group-item list-group-item-action">Developer API</a>
<a href="#" class="list-group-item list-group-item-action">General</a>
<a href="#" class="list-group-item list-group-item-action">Image Compression</a>
<a href="#" class="list-group-item list-group-item-action">Troubleshooting</a>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="bg-dark text-light py-4">
<div class="container">
<div class="row">
<div class="col-12 text-center small text-monospace">
© 2018 Overflow Design Group • All Rights Reserved
</div>
</div>
</div>
</div>
@endsection

@php clock()->endEvent('articlessection.show'); @endphp
