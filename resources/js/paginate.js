function updateFilters(sort, dir, filter, tag)
{
    sort = localStorage.getItem('sort');
    dir = localStorage.getItem('dir');
    filter = localStorage.getItem('filter');
    tag = localStorage.getItem('tag');

    var currentURL = new URL(window.location.href);

    if(currentURL.searchParams.has("sort"))
        sort = currentURL.searchParams.get("sort");

    if(currentURL.searchParams.has("dir"))
        dir = currentURL.searchParams.get("dir");

    if(currentURL.searchParams.has("filter"))
        filter = currentURL.searchParams.get("filter");

    if(currentURL.searchParams.has("tag"))
        tag = currentURL.searchParams.get("tag");

    $('.filter-controls a').each(function(e){

        $(this).removeClass('asc desc');

        if($(this).data('sort') == sort)
        {
            $(this).addClass(dir);
        }
    });

    $('.filter-tags div').each(function(e){

        $(this).removeClass('active');

        if($(this).data('filter') == tag)
        {
            $(this).addClass('active');
        }
    });

    if(filter){
        $('.filter-input').each(function(e){

            $(this).val(filter);
        });
    }
}

$(function() {

    let timeout = null;

    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();

        $('.paginate-container').html(`<div class="h-100 w-100 row align-items-center justify-content-center">
            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>
            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>
            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>
            </div>`);

        clearTimeout(timeout);

        btn = $(this);

        // Make a new timeout set to go off in 1000ms (1 second)
        timeout = setTimeout(function () {

            var currentURL = new URL(window.location.href);
            sort = currentURL.searchParams.get("sort");
            dir = currentURL.searchParams.get("dir");
            filter = currentURL.searchParams.get("filter");

            var newURL = new URL(btn.attr('href'));        
            
            if(sort){
                newURL.searchParams.set("sort", sort);
                newURL.searchParams.set("dir", dir);
            }

            if(filter){
                newURL.searchParams.set("filter", filter);
            }
        
            $.ajax({
                url : newURL.href,
                type: 'get',
                dataType: 'text',
            }).done(function (data) 
            {
                $('.paginate-container').html(data);  
                LetterAvatar.transform();

                // Create the event
                var event = new CustomEvent("paginate-click");
                // Dispatch/Trigger/Fire the event
                document.dispatchEvent(event);                        

            }).fail(function () 
            {
                toastrs('Data could not be loaded!', 'danger');            
            });

            window.history.replaceState(null, null, newURL.href);        

        }, 100);
    });

    $('.filter-controls a').on('click',function(e){
        e.preventDefault();

        $('.paginate-container').html(`<div class="h-100 w-100 row align-items-center justify-content-center">
            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>
            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>
            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>
            </div>`);
        
        clearTimeout(timeout);

        btn = $(this);

        // Make a new timeout set to go off in 1000ms (1 second)
        timeout = setTimeout(function () {

            var sort = btn.data('sort');
            var dir = btn.hasClass('asc')?'desc':'asc';
            
            var url = new URL(window.location.href);
            url.searchParams.set("sort", sort);
            url.searchParams.set("dir", dir);

            $.ajax({
                url : url.href,
                type: 'get',
                dataType: 'text',
            }).done(function (data) 
            {
                $('.paginate-container').html(data);  
                LetterAvatar.transform();

                // Create the event
                var event = new CustomEvent("paginate-sort");
                // Dispatch/Trigger/Fire the event
                document.dispatchEvent(event);            

            }).fail(function () 
            {
                toastrs('Data could not be loaded!', 'danger');            
            });

            window.history.replaceState(null, null, url.href);

            updateFilters();

        }, 100);
    });

    $('.filter-tags div').on('click',function(e){
        e.preventDefault();

        $('.paginate-container').html(`<div class="h-100 w-100 row align-items-center justify-content-center">
            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>
            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>
            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>
            </div>`);
        
        clearTimeout(timeout);

        btn = $(this);

        // Make a new timeout set to go off in 1000ms (1 second)
        timeout = setTimeout(function () {

            var tag = btn.data('filter');

            var url = new URL(window.location.href);
            
            if(url.searchParams.get("tag") == tag){
                url.searchParams.delete("tag");
            }else{
                url.searchParams.set("tag", tag);
            }

            $.ajax({
                url : url.href,
                type: 'get',
                dataType: 'text',
            }).done(function (data) 
            {
                $('.paginate-container').html(data);
                LetterAvatar.transform();

                // Create the event
                var event = new CustomEvent("paginate-tag");
                // Dispatch/Trigger/Fire the event
                document.dispatchEvent(event);            

            }).fail(function () 
            {
                toastrs('Data could not be loaded!', 'danger');            
            });

            window.history.replaceState(null, null, url.href);

            updateFilters();

        }, 100);

    });

    $('.filter-input').on('input',function(e){
    // $('.filter-input').on('change',function(e){
    // $('.filter-input').on('keyup',function(e){
            e.preventDefault();

        $('.paginate-container').html(`<div class="h-100 w-100 row align-items-center justify-content-center">
            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>
            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>
            <div class="spinner-grow" role="status"><span class="sr-only">Loading...</span></div>
            </div>`);

        clearTimeout(timeout);

        textInput = $(this);

        // Make a new timeout set to go off in 1000ms (1 second)
        timeout = setTimeout(function () {

            var filter = textInput.val();

            var url = new URL(window.location.href);
            
            if(filter){
                url.searchParams.set("filter", filter);
            }else{
                url.searchParams.delete("filter");
            }

            $.ajax({
                url : url.href,
                type: 'get',
                dataType: 'text',
            }).done(function (data) 
            {
                $('.paginate-container').html(data);  
                LetterAvatar.transform();

                // Create the event
                var event = new CustomEvent("paginate-filter");
                // Dispatch/Trigger/Fire the event
                document.dispatchEvent(event);            

            }).fail(function () 
            {
                toastrs('Data could not be loaded!', 'danger');            
            });

            window.history.replaceState(null, null, url.href);

        }, 500);
    });

    function loadContent(container) {

        var url = new URL(window.location.href);

        $.ajax({
            url : url.href,
            type: 'get',
            dataType: 'text',
        }).done(function (data) 
        {
            $('.paginate-container').html(data);  
            LetterAvatar.transform();

            // Create the event
            var event = new CustomEvent("paginate-load");
            // Dispatch/Trigger/Fire the event
            document.dispatchEvent(event);            

        }).fail(function () 
        {
            // toastrs('Data could not be loaded!', 'danger');            
        });
    }

    $('.paginate-container').each(function(e){

        loadContent($(this));

        //stop at first, for now
        return false;
    });

});
