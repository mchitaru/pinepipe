@php clock()->startEvent('expenses.index', "Display expenses"); @endphp

@php
use Carbon\Carbon;
@endphp

@foreach ($expenses as $expense)
<div class="card card-task mb-1" style="min-height: 77px;">
    <div class="container row align-items-center">
        <div class="pl-2 position-absolute">
        </div>
        <div class="card-body p-2">
            <div class="card-title col-sm-3">
                @if(Gate::check('edit expense'))
                    <a href="{{ route('expenses.edit',$expense->id) }}" data-remote="true" data-type="text">
                        <h6 data-filter-by="text">{{  ($expense->category?$expense->category->name:'---')}}</h6>
                    </a>
                @else
                    <h6 data-filter-by="text">{{  ($expense->category?$expense->category->name:'---')}}</h6>
                @endif
                <p>
                    {!!\Helpers::showDateForHumans($expense->date)!!}
                </p>

            </div>
            <div class="card-title col-sm-4">
                <div class="container row align-items-center"  title="{{__('Project')}}">
                    <i class="material-icons">folder</i>
                    <span data-filter-by="text" class="text-small text-truncate">{{ !empty($expense->project)?$expense->project->name:'---' }}</span>
                </div>
            </div>
            <div class="card-title col-sm-2">
                <div class="container row align-items-center">
                    <span data-filter-by="text" class="text-small">{{ Auth::user()->priceFormat($expense->amount) }}</span>
                </div>
            </div>
            <div class="card-title col-sm-1">
                @if($expense->hasMedia('attachments'))
                    <a href="{{route('expenses.attachment', [$expense, $expense->media('attachments')->first()->file_name])}}" download="" class="mr-2"  data-original-title="{{__('Download')}}">
                        <i class="material-icons" title="attachment">attachment</i>
                    </a>
                @endif
            </div>
            @if(!empty($expense->user))
            <div class="card-meta col">
                <a href="#"  title={{$expense->user->name}}>
                    {!!Helpers::buildUserAvatar($expense->user)!!}
                </a>
            </div>
            @endif
            @if(Gate::check('edit expense') || Gate::check('delete expense'))
            <div class="dropdown card-options">
                <button class="btn-options" type="button" id="task-dropdown-button-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="material-icons">more_vert</i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    @can('edit expense')
                    <a href="{{ route('expenses.edit',$expense->id) }}" class="dropdown-item" data-remote="true" data-type="text">
                        <span>{{__('Edit')}}</span>
                    </a>
                    @endcan
                    <div class="dropdown-divider"></div>
                    @can('delete expense')
                        <a href="{{route('expenses.destroy',$expense->id)}}" class="dropdown-item text-danger" data-method="delete" data-remote="true" data-type="text">
                            <span>{{__('Delete')}}</span>
                        </a>
                    @endcan
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach

@if(method_exists($expenses,'links'))
{{ $expenses->links() }}
@endif

@php clock()->endEvent('expenses.index'); @endphp
