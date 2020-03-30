function attachPlugins() {
    
    $('select').select2();
    $('[data-flatpickr]').mrFlatpickr();
    LetterAvatar.transform();

    $("[data-refresh]").each(function() 
    {
        $(this).on("change", function (e) {
            e.preventDefault();

            url = $(this).data('refresh');

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'text',
                method: 'GET',
                data: $("form").serialize(),
                    success: function(data, status, xhr) {
                        $(document).trigger('ajax:success', [data, status, xhr]);
                    },
                    complete: function(xhr, status) {
                        $(document).trigger('ajax:complete', [xhr, status]);
                    },
                    error: function(xhr, status, error) {
                        $(document).trigger('ajax:error', [xhr, status, error]);
                    }
            });
        });
    });
}

$(document).on('ajax:success', function(e, data, status, xhr){

    if(xhr.responseText)
    {
        if(!$('#modal').length)
        {
            $('body').append($('<div class="modal show" id="modal" data-keyboard="false" data-backdrop="static"></div>'))
        }

        $('#modal').html(xhr.responseText).modal('show');

        attachPlugins();
    }
});

$(document).on('ajax:error', function(e, xhr, status, error){

    toastrs(error, 'error')
});
