function initDropzoneLinks(file, response) 
{
    if(response.download){
        $( ".dropzone-file", $(".dz-preview").last() ).each(function() {
            $(this).attr("href", response.download);
        });
    }

    if(response.delete){
        $( ".dropzone-delete", $(".dz-preview").last() ).each(function() {
            $(this).attr("href", response.delete);
            $(this).removeClass("disabled");
        });    
    }
}

async function initDropzone(selector, url, model_id, files)
{
    dropzone = $(selector).dropzone({
    previewTemplate: document.querySelector('.dz-template').innerHTML,
    createImageThumbnails: false,
    previewsContainer: selector + '-previews',
    maxFiles: 100,
    maxFilesize: 10,
    parallelUploads: 10,
    acceptedFiles: 'image/*,text/*,font/*,application/*,.doc,.docx,.xls,.xlsx,.ppt,.pptx',
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
