function updateFilters(sort, direction, filter)
{
    var currentURL = new URL(window.location.href);

    if(currentURL.searchParams.has("sort"))
        sort = currentURL.searchParams.get("sort");

    if(currentURL.searchParams.has("direction"))
        direction = currentURL.searchParams.get("direction");

    if(currentURL.searchParams.has("filter"))
        filter = currentURL.searchParams.get("filter");

    $('.filter-controls a').each(function(e){

        $(this).removeClass('asc desc');

        if($(this).data('sort') == sort)
        {
            $(this).addClass(direction);
        }
    });

    $('.filter-input').each(function(e){

        $(this).val(filter);
    });
}

$(function() {

    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();

        $('.paginate-container a').not('.pagination a').css('color', '#dfecf6');

        var currentURL = new URL(window.location.href);
        sort = currentURL.searchParams.get("sort");
        direction = currentURL.searchParams.get("direction");
        filter = currentURL.searchParams.get("filter");

        var newURL = new URL($(this).attr('href'));        
        
        if(sort){
            newURL.searchParams.set("sort", sort);
            newURL.searchParams.set("direction", direction);
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
        var direction = $(this).hasClass('asc')?'desc':'asc';
        
        var url = new URL(window.location.href);
        url.searchParams.set("sort", sort);
        url.searchParams.set("direction", direction);

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
