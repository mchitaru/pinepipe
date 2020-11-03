@php
use App\Project;
use Carbon\Carbon;
@endphp

<div class="scrollable-list col" style="max-height:90vh">
    <div class="card-list">
        <div class="card-list-head">
            <div class="d-flex align-items-center">
                <div class="icon pr-2">
                    <i class="material-icons">{{$icon}}</i>
                </div>
                <button class="btn-options" type="button" data-toggle="collapse" data-target="#pnl">
                    <span class="d-none d-lg-block">{{__('You collected')}}</span> 
                    <span title="{{__('Income')}}" class="text-success mx-1">{{\Auth::user()->priceFormat($income)}}</span> 
                    <span class="d-none d-lg-block">{{__('and spent')}}</span> 
                    <span class="d-lg-none d-block">/</span> 
                    <span title="{{__('Expenses')}}" class="text-danger mx-1">{{\Auth::user()->priceFormat($expenses)}}</span>  
                    <span class="d-none d-lg-block">{{__('this month')}}</span>
                </button>
            </div>
            <button class="btn-options" type="button" data-toggle="collapse" data-target="#pnl">
                <i class="material-icons">expand_more</i>
            </button>
        </div>
        <div class="card-list-body collapse" id="pnl">
            <div class="card card-info">
                <div class="card-body">
                    <div id="pnl_chart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
