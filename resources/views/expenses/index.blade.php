@foreach ($expenses as $expense)
<div class="card card-task mb-1" style="min-height: 77px;">
    <div class="container row align-items-center">
        <div class="pl-2 position-absolute">
        </div>
        <div class="card-body p-2">
            <div class="card-title col-sm-3">
                @can('edit expense')
                    <a href="#" data-url="{{ route('expenses.edit',$expense->id) }}" data-ajax-popup="true" data-title="{{__('Edit Expense')}}">
                @endcan
                    <h6 data-filter-by="text">{{  (!empty($expense->category)?$expense->category->name:'')}}
                    </h6>
                @can('edit expense')
                </a>
                @endcan
                <p>
                    <span class="text-small">{{ Carbon::parse($expense->date)->diffForHumans() }}</span>
                </p>

            </div>
            <div class="card-title col-sm-2">
                <div class="container row align-items-center">
                    <i class="material-icons">folder</i>
                    <span data-filter-by="text" class="text-small">{{ $expense->projects->name }}</span>
                </div>
            </div>
            <div class="card-title col-sm-2">
                <div class="container row align-items-center">
                    <span data-filter-by="text" class="text-small">{{ Auth::user()->priceFormat($expense->amount) }}</span>
                </div>
            </div>
            <div class="card-title col-sm-2">
                <div class="container row align-items-center">
                    <span data-filter-by="text" title="{{ $expense->description }}" class="text-small text-truncate" style="max-width: 150px;">{{ $expense->description }}</span>
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
                    <img alt="{{$expense->user->name}}" {!! empty($expense->user->avatar) ? "avatar='".$expense->user->name."'" : "" !!} class="avatar" src="{{asset(Storage::url("avatar/".$expense->user->avatar))}}" data-filter-by="alt"/>
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
                    <a class="dropdown-item" href="#" data-url="{{ route('expenses.edit',$expense->id) }}" data-ajax-popup="true" data-title="{{__('Edit Expense')}}">
                        <span>{{__('Edit')}}</span>
                    </a>
                    @endcan
                    <div class="dropdown-divider"></div>
                    @can('delete expense')
                        <a class="dropdown-item text-danger" href="#" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('expense-delete-form-{{$expense->id}}').submit();">
                            <span>{{'Delete'}}</span>
                        </a>
                        {!! Form::open(['method' => 'DELETE', 'route' => ['expenses.destroy', $expense->id],'id'=>'expense-delete-form-'.$expense->id]) !!}
                        {!! Form::close() !!}
                    @endcan
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endforeach
