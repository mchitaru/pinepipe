@extends('layouts.app')

@php clock()->startEvent('dashboard.index', "Display dash"); @endphp

@php
use App\Project;
use Carbon\Carbon;
@endphp

@push('stylesheets')
@endpush

@push('scripts')

<!-- Charting library -->
<script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
<!-- Chartisan -->
<script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>
<!-- Your application script -->
<script>
    const pnlChart = new Chartisan({
        el: '#pnl_chart',
        url: "@chart('pnl_chart')",
        hooks: new ChartisanHooks()
            .colors(['#28a745', '#dc3545', '#007bff'])
            .tooltip(true)
            .legend(true)
            .datasets(['bar', 'bar', { type: 'line', fill: false }]),
        loader: {
            color: '#007bff',
            size: [30, 30],
            type: 'bar',
            textColor: '#007bff',
            text: '',
        }
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        Livewire.hook('message.processed', (message, component) => {
            LetterAvatar.transform();
        })
    });
</script>
<script>
    $('.card-list .dropdown').on('show.bs.dropdown', function() {
        $('body').append($(this).children('.dropdown-menu').css({
            position: 'absolute',
            left: $('.dropdown-menu').offset().left,
            top: $('.dropdown-menu').offset().top
        }).detach());
    });
</script>
@endpush

@section('content')
@php
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="page-header">
                    </div>
                    <h3> {{__('Hey')}} {{\Auth::user()->name}}! {{__('After')}} &#x2615;, {{__("here is what's coming up")}}:</h3>
                    <div class="row pt-1 pb-3">
                        @livewire('upcoming', ['type' => 'today'])
                        @livewire('upcoming', ['type' => 'this week'])
                        @livewire('upcoming', ['type' => 'next week'])
                    </div>
                    <h3> {{__("Let's take on the day!")}} </h3>
                    <div class="row pt-1 pb-3">
                        @livewire('pnl')
                        @livewire('projects')
                        @livewire('tasks')
                        @livewire('invoices')
                        @livewire('leads')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@php clock()->endEvent('dashboard.index'); @endphp
