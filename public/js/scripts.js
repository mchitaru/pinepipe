async function attachPlugins() {

    $('select').each(function() {
        $(this).select2({
            tags: $(this).hasClass('tags'),
            createTag: function (params) {
                var term = $.trim(params.term);

                if (term === '') {
                  return null;
                }

                return {
                  id: term,
                  text: '\u271A '+term,
                  newTag: true // add additional parameters
                }
            }
        });
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

    $(".summernote").each(function()
    {
        $(this).summernote({
            height: 400,
        });
    });
}

$(document).on('ajax:success', function(e, data, status, xhr){

    if(xhr.status == 207)
    {
        $('#modal').modal('hide');

        if(data.url){

            window.location = data.url;
        }else{

            window.location.reload();
        }

    }else if(xhr.responseText)
    {
        if(xhr.responseText.includes('modal')){

            if(!$('#modal').length)
            {
                $('body').append($('<div class="modal show" id="modal" data-keyboard="true" tabindex="-1"></div>'))
            }

            $('#modal').html(xhr.responseText).modal('show');

            attachPlugins();

        }else{

            window.location.reload();
        }

    }

});

$(document).on('ajax:error', function(e, xhr, status, error){

    toastrs(error, 'danger')
});

/*
     * LetterAvatar
     * 
     * Artur Heinze
     * Create Letter avatar based on Initials
     * based on https://gist.github.com/leecrossley/6027780
     */
    (function(w, d){


        function LetterAvatar (name, size) {

            name  = name || '';
            size  = size || 60;

            var colours = [
                "#92dacb", "#e7afa9",  "#acd6f1", "#e4c695", "#93b4d8", "#a3e4d7", "#93d6af", "#7fb2d4", "#dab7e9", "#a2cbf3",
                "#dfce8c", "#dfb999", "#9fdfb9", "#90c8db", "#7db1b4", "#dcb5eb", "#e0b699", "#e4a9a1", "#83c1ee", "#66b6db", "#92dacb"
            ],

                nameSplit = String(name).toUpperCase().split(' '),
                initials, charIndex, colourIndex, canvas, context, dataURI;


            if (nameSplit.length == 1) {
                initials = nameSplit[0] ? nameSplit[0].charAt(0):'?';
            } else {
                initials = nameSplit[0].charAt(0) + nameSplit[1].charAt(0);
            }

            if (w.devicePixelRatio) {
                size = (size * w.devicePixelRatio);
            }
                
            charIndex     = (initials == '?' ? 72 : initials.charCodeAt(0)) - 64;
            colourIndex   = charIndex % 20;
            canvas        = d.createElement('canvas');
            canvas.width  = size;
            canvas.height = size;
            context       = canvas.getContext("2d");
             
            context.fillStyle = colours[colourIndex];
            context.fillRect (0, 0, canvas.width, canvas.height);
            context.font = Math.round(canvas.width/2)+"px Arial";
            context.textAlign = "center";
            context.fillStyle = "#FFF";
            context.fillText(initials, size / 2, size / 1.5);

            dataURI = canvas.toDataURL();
            canvas  = null;

            return dataURI;
        }

        LetterAvatar.transform = function() {

            Array.prototype.forEach.call(d.querySelectorAll('img[avatar]'), function(img, name) {
                name = img.getAttribute('avatar');
                img.src = LetterAvatar(name, img.getAttribute('width'));
                img.removeAttribute('avatar');
                img.setAttribute('alt', name);
            });
        };


        // AMD support
        if (typeof define === 'function' && define.amd) {
            
            define(function () { return LetterAvatar; });
        
        // CommonJS and Node.js module support.
        } else if (typeof exports !== 'undefined') {
            
            // Support Node.js specific `module.exports` (which can be a function)
            if (typeof module != 'undefined' && module.exports) {
                exports = module.exports = LetterAvatar;
            }

            // But always support CommonJS module 1.1.1 spec (`exports` cannot be a function)
            exports.LetterAvatar = LetterAvatar;

        } else {
            
            window.LetterAvatar = LetterAvatar;

            d.addEventListener('DOMContentLoaded', function(event) {
                LetterAvatar.transform();
            });
        }
    
    })(window, document);

    function PreviewAvatarImage (input, size, type) 
    {
        if (input.files && input.files[0]) 
        {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(input).closest('.avatar-container').children('.avatar-preview').html('<img class="'+type+'" src="'+e.target.result+'" style="max-width:'+size+'px; max-height:'+size+'px;"/>');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }    

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
                // toastrs(lang.get('paginate.load'), 'danger');            
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
                // toastrs(lang.get('paginate.load'), 'danger');            
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
                // toastrs(lang.get('paginate.load'), 'danger');            
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
                // toastrs(lang.get('paginate.load'), 'danger');            
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
            cache: false,
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

function initDropzoneLinks(file, response) 
{
    $( ".dropzone-file", $(".dz-preview").last() ).each(function() {
        $(this).attr("href", response.download);
    });

    $( ".dropzone-delete", $(".dz-preview").last() ).each(function() {
        $(this).attr("href", response.delete);
        $(this).removeClass("disabled");
    });
}

async function initDropzone(selector, url, model_id, files)
{
    dropzone = $(selector).dropzone({
    previewTemplate: document.querySelector('.dz-template').innerHTML,
    createImageThumbnails: false,
    previewsContainer: selector + '-previews',
    maxFiles: 20,
    maxFilesize: 10,
    parallelUploads: 1,
    acceptedFiles: '.jpeg,.jpg,.png,.gif,.svg,.pdf,.txt,.doc,.docx,.zip,.rar,.xls,.xlsx',
    url: url,

    success: function (file, response) {
        if (response.is_success) {
            toastrs(lang.get('messages.dropzone.upload'), 'success');
            initDropzoneLinks(file, response);
            LetterAvatar.transform();
        } else {
            this.removeFile(file);
            toastrs(lang.get('errors.dropzone.upload'), 'danger');
        }
    },
    error: function (file, response) {
        this.removeFile(file);
        toastrs(lang.get('errors.dropzone.upload'), 'danger');
    },
    sending: function(file, xhr, formData) {
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    },
    init: function () {

        if(files) {
            for (var i in files) {

                var file = files[i];

                var mockFile = {name: file['file_name'], size: file['size'] };

                this.options.addedfile.call(this, mockFile);
                this.options.processing.call(this, mockFile);
                this.options.complete.call(this, mockFile);                 

                initDropzoneLinks(mockFile, {download: file['download'], delete: file['delete']});
            }
        }
    }
    })[0];
}

$.getScript('../assets/js/easytimer.min.js', function()
{
    window.timerInstance = new easytimer.Timer();
});

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