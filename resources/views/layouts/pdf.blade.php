<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('partials.pdf.head')
<body>
    <div class="footer">
        {{-- Page <span class="pagenum"></span> --}}
        <table class="table">
            <tfoot class="borderless gapless">
                <tr>
                    <td class="text-left"></td>
                    <td class="text-right"><span class="text-small">www.pinepipe.com</span></td>
                </tr>
            </tfoot>
        </table>        
    </div>    
    <div class="main-container">
        @include('partials.app.content')
    </div>
</body>
</html>
