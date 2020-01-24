$(document).on('ajax:success', function(e, data, status, xhr){

    if(!$('#modal').length){
        $('body').append($('<div class="modal show" id="modal"></div>'))
    }
   $('#modal').html(xhr.responseText).modal('show');

   flatpickr('[data-flatpickr]');
   LetterAvatar.transform();
});

$(document).on('ajax:error', function(e, xhr, status, error){

    toastrs('Error', error, 'error')
});
