function onUpdateTimer() {
    
    $('.active-timer[data-timesheet=' + window.timesheet + ']').each(function(){

        $(this).text(window.timerInstance.getTimeValues().toString());
    });
}

function timer(instance, offset, id)
{
    if(instance) {

        instance.stop();
        instance.removeEventListener('secondsUpdated', onUpdateTimer);

        window.timesheet = id;

        instance.addEventListener('secondsUpdated', onUpdateTimer);    
        instance.start({precision: 'seconds', startValues: {seconds: offset}});
    }
}

function updateTimerUI(data)
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
                    timer(window.timerInstance, data.offset, data.timesheet_id);
                }else{
                    window.timerInstance.stop();
                    window.timerInstance.removeEventListener('secondsUpdated', onUpdateTimer);

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

                updateTimerUI(data);
                
            },
            error: function (data) {
            }
        });
    });
});