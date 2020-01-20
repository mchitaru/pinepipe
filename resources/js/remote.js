$(document).on('ajax:success', function(e, data, status, xhr){

    if(!$('#modal').length){
        $('body').append($('<div class="modal show" id="modal"></div>'))
    }
   $('#modal').html(xhr.responseText).modal('show');
});