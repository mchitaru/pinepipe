$.getScript('../assets/js/easytimer.min.js', function()
{
    window.timerInstance = new easytimer.Timer();
});

function timer(instance, offset)
{
    instance.stop();
    instance.start({precision: 'seconds', startValues: {seconds: offset}});
    instance.addEventListener('secondsUpdated', function (e) {

        $('.active-timer').each(function(){

            $(this).text(instance.getTimeValues().toString());
        });
    });
}

function updateUI(data)
{
    $('.timer-popup').each(function(){

        $(this).replaceWith(data.popup);
    });

    $('.timer-control').each(function(){

        $(this).replaceWith(data.control);
    });

    $('.task').each(function(){

        $(this).removeClass('glow-animation');
    });

    if(data.start && data.task_id) {

        $('.task[data-id=' + data.task_id + ']').addClass('glow-animation');
    }
}

$(function() {

    $(document).on("click", ".timer-entry", function (e) {    

        e.preventDefault();

        var task_id = $(this).attr('data-task');
        var timesheet_id = $(this).attr('data-timesheet');
        
        $.ajax({
            url: $(this).attr('href'),
            type: 'POST',
            data: {task_id: task_id, timesheet_id: timesheet_id, "_token": $('meta[name="csrf-token"]').attr('content')},
            success: function (data) {

                if(data.start){
                    timer(window.timerInstance, data.offset);
                }else{
                    window.timerInstance.stop();

                    if(data.url) {
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
                }

                updateUI(data);
                
            },
            error: function (data) {
            }
        });
    });
});