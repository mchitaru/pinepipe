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

    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();

        $('.paginate-container a').not('.pagination a').css('color', '#dfecf6');

        var currentURL = new URL(window.location.href);
        sort = currentURL.searchParams.get("sort");
        dir = currentURL.searchParams.get("dir");
        filter = currentURL.searchParams.get("filter");

        var newURL = new URL($(this).attr('href'));        
        
        if(sort){
            newURL.searchParams.set("sort", sort);
            newURL.searchParams.set("dir", dir);
        }

        if(filter){
            newURL.searchParams.set("filter", filter);
        }
    
        $.ajax({
            url : newURL.href  
        }).done(function (data) 
        {
            $('.paginate-container').html(data);  
            LetterAvatar.transform();

        }).fail(function () 
        {
            toastrs('Data could not be loaded!', 'error');            
        });

        window.history.replaceState(null, null, newURL.href);        
    });

    $('.filter-controls a').on('click',function(e){
        e.preventDefault();

        $('.paginate-container a').not('.pagination a').css('color', '#dfecf6');
        
        var sort = $(this).data('sort');
        var dir = $(this).hasClass('asc')?'desc':'asc';
        
        var url = new URL(window.location.href);
        url.searchParams.set("sort", sort);
        url.searchParams.set("dir", dir);

        $.ajax({
            url : url.href,
        }).done(function (data) 
        {
            $('.paginate-container').html(data);  
            LetterAvatar.transform();

        }).fail(function () 
        {
            toastrs('Data could not be loaded!', 'error');            
        });

        window.history.replaceState(null, null, url.href);

        updateFilters();
    });

    $('.filter-tags div').on('click',function(e){
        e.preventDefault();

        $('.paginate-container a').not('.pagination a').css('color', '#dfecf6');
        
        var tag = $(this).data('filter');

        var url = new URL(window.location.href);
        
        if(url.searchParams.get("tag") == tag){
            url.searchParams.delete("tag");
        }else{
            url.searchParams.set("tag", tag);
        }

        $.ajax({
            url : url.href,
        }).done(function (data) 
        {
            $('.paginate-container').html(data);  
            LetterAvatar.transform();

        }).fail(function () 
        {
            toastrs('Data could not be loaded!', 'error');            
        });

        window.history.replaceState(null, null, url.href);

        updateFilters();
    });

    $('.filter-input').on('change',function(e){
    // $('.filter-input').on('keyup',function(e){
            e.preventDefault();

        $('.paginate-container a').not('.pagination a').css('color', '#dfecf6');

        var filter = $(this).val();

        var url = new URL(window.location.href);
        
        if(filter){
            url.searchParams.set("filter", filter);
        }else{
            url.searchParams.delete("filter");
        }

        $.ajax({
            url : url.href,
        }).done(function (data) 
        {
            $('.paginate-container').html(data);  
            LetterAvatar.transform();

        }).fail(function () 
        {
            toastrs('Data could not be loaded!', 'error');            
        });

        window.history.replaceState(null, null, url.href);
    });
});
