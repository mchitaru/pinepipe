@php clock()->startEvent('expenses.index', "Display expenses"); @endphp

@php
    use Carbon\Carbon;
    use App\Http\Helpers;
@endphp

@foreach ($expenses as $expense)
<div class="card card-task mb-1" style="min-height: 77px;">
    <div class="container row align-items-center">
        <div class="pl-2 position-absolute">
        </div>
        <div class="card-body p-2">
            <div class="card-title col-sm-3">
                @can('edit expense')
                    <a href="{{ route('expenses.edit',$expense->id) }}" data-remote="true" data-type="text">
                @endcan
                        <h6 data-filter-by="text">{{  (!empty($expense->category)?$expense->category->name:'---')}}</h6>
                @can('edit expense')
                    </a>
                @endcan
                <p>
                    <span class="text-small">{{ Carbon::parse($expense->date)->diffForHumans() }}</span>
                </p>

            </div>
            <div class="card-title col-sm-4">
                <div class="container row align-items-center" data-toggle="tooltip" title="{{__('Project')}}">
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
                <div class="container row align-items-center" title="{{ $expense->description }}">
                    <i class="material-icons">note</i>
                </div>
            </div>        
            @if(!empty($expense->user))
            <div class="card-meta col">
                @if($expense->attachment)
                    <a href="{{asset(Storage::url('app/public/attachment/'. $expense->attachment))}}" download="" class="mr-2" data-toggle="tooltip" data-original-title="{{__('Download')}}">
                        <i class="material-icons" title="Projects">attachment</i>
                    </a>
                @endif
                <a href="#" data-toggle="tooltip" title={{$expense->user->name}}>
                    {!!Helpers::buildAvatar($expense->user)!!}
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
                            <span>{{'Delete'}}</span>
                        </a>
                    @endcan
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach

@php clock()->endEvent('expenses.index'); @endphp
