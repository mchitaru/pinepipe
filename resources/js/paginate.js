function updateFilters(sort, direction)
{
    var currentURL = new URL(window.location.href);

    if(currentURL.searchParams.has("sort"))
        sort = currentURL.searchParams.get("sort");

    if(currentURL.searchParams.has("direction"))
        direction = currentURL.searchParams.get("direction");

    $('.filter-controls a').each(function(e){

        $(this).removeClass('asc desc');

        if($(this).data('sort') == sort)
        {
            $(this).addClass(direction);
        }
    });
}

$(function() {

    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();

        $('.paginate-container a').not('.pagination a').css('color', '#dfecf6');

        var currentURL = new URL(window.location.href);
        sort = currentURL.searchParams.get("sort");
        direction = currentURL.searchParams.get("direction");

        var newURL = new URL($(this).attr('href'));        
        
        if(sort)
        {
            newURL.searchParams.set("sort", sort); // setting your param
            newURL.searchParams.set("direction", direction); // setting your param
        }
    
        $.ajax({
            url : newURL.href  
        }).done(function (data) 
        {
            $('.paginate-container').html(data);  

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
            data: {sort: sort, direction: direction},
        }).done(function (data) 
        {
            $('.paginate-container').html(data);  

        }).fail(function () 
        {
            toastrs('Data could not be loaded!', 'error');            
        });

        window.history.replaceState(null, null, url.href);
        updateFilters();
    });

});
