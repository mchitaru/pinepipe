async function attachPlugins() {
    
    $('select').each(function() {
        $(this).select2({tags: $(this).hasClass('tags')});
    });

    $('[data-flatpickr]').mrFlatpickr();

    $('.start[data-flatpickr]').each(function() {
    
        if($(this).flatpickr()) {
            
            $(this).flatpickr().config.onChange.push(function(selectedDates, dateStr, instance) { 
    
                var end = $('.end[data-flatpickr]').flatpickr();

                if(end) {

                    if(end.selectedDates.length && 
                        (Date.parse(end.selectedDates[0]) < Date.parse(dateStr)))
                    {
                        end.setDate(dateStr);
                    }
            
                    end.config.minDate = dateStr;
                }
            });
        }
    });

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

    if(xhr.status == 207)
    {
        $('#modal').modal('hide');

        if(data.url){
            
            window.location=data.url;
        }else{

            window.location.reload();
        }

    }else if(xhr.responseText)
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
