<div class="modal-dialog @yield('size')" role="document">
    <div class="modal-content">
        @yield('form-start')
        <div class="modal-header">
            <h5 class="modal-title">@yield('title')</h5>
            <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">
            <i class="material-icons">close</i>
            </button>        
        </div>
        <div class="modal-body">@yield('content')</div>
        <div class="modal-footer">@yield('footer')</div>
        @yield('form-end')
    </div>
</div>

@stack('scripts')