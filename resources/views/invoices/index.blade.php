@php clock()->startEvent('invoices.index', "Display invoices"); @endphp

@php
use Carbon\Carbon;
$can_show_invoice = Gate::check('viewAny', 'App\Invoice');
@endphp

@foreach ($invoices as $invoice)
@include('invoices.item')
@endforeach

@if(method_exists($invoices,'links'))
{{ $invoices->links() }}
@endif

@php clock()->endEvent('invoices.index'); @endphp
