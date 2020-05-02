@php clock()->startEvent('invoices.index', "Display invoices"); @endphp

@php
use Carbon\Carbon;
$can_show_invoice = Gate::check('view invoice');
@endphp

@foreach ($invoices as $invoice)
@include('invoices.invoice')
@endforeach

@if(method_exists($invoices,'links'))
{{ $invoices->links() }}
@endif

@php clock()->endEvent('invoices.index'); @endphp
