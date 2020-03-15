$.getScript('../assets/js/easytimer.min.js', function()
{
    window.timerInstance = new easytimer.Timer();
});

function timer(instance, offset)
{
    instance.stop();
    instance.start({precision: 'seconds', startValues: {seconds: offset}});
    instance.addEventListener('secondsUpdated', function (e) {
        $('#active-timer').html(instance.getTimeValues().toString());
    });
}

$(function() {

    $(document).on("click", ".timer-entry", function (e) {    

        e.preventDefault();

        var timesheet_id = $(this).attr('data-id');
        
        $.ajax({
            url: $(this).attr('href'),
            type: 'POST',
            data: {timesheet_id: timesheet_id, "_token": $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {

                if(data.start){
                    timer(window.timerInstance, data.offset);
                }else{
                    window.timerInstance.stop();

                    $.ajax({
                        url: data.url,
                        type: 'get',
                        dataType: 'text',
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

                }
                
                $('#timer-popup').replaceWith(data.html);
            },
            error: function (data) {
            }
        });
    });
});