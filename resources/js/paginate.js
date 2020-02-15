$(function() {

    $('body').on('click', '.pagination a', function(e) {
        e.preventDefault();

        $('.paginate-container a').not('.pagination a').css('color', '#dfecf6');

        var url = $(this).attr('href');  

        $.ajax({
            url : url  
        }).done(function (data) 
        {
            $('.paginate-container').html(data);  

        }).fail(function () 
        {
            toastrs('Data could not be loaded!', 'error');            
        });

        window.history.pushState("", "", url);
    });

});
