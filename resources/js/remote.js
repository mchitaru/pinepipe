$(document).on('ajax:success', function(e, xhr){
    if(!$('#modal').length){
        $('body').append($('<div class="modal fade" id="modal"></div>'))
    }
   $('#modal').html(xhr).modal('show');
});
