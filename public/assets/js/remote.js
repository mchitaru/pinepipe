$(document).on('ajax:success', function(e, data, status, xhr){

    if(!$('#modal').length){
        $('body').append($('<div class="modal show" id="modal"></div>'))
    }
   $('#modal').html(xhr.responseText).modal('show');

   $('select').select2();
   $('[data-flatpickr]').mrFlatpickr();
//    $('form.checklist, .drop-to-delete').mrChecklist();
//    $('form.checklist .custom-checkbox div input').mrAutoWidth();
   LetterAvatar.transform();
});

$(document).on('ajax:error', function(e, xhr, status, error){

    toastrs(error, 'error')
});
