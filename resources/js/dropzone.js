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
    maxFilesize: 2,
    parallelUploads: 1,
    acceptedFiles: '.jpeg,.jpg,.png,.gif,.svg,.pdf,.txt,.doc,.docx,.zip,.rar',
    url: url,

    success: function (file, response) {
        if (response.is_success) {
            toastrs('File uploaded', 'success');
            initDropzoneLinks(file, response);
            LetterAvatar.transform();
        } else {
            this.removeFile(file);
            toastrs(response.error, 'error');
        }
    },
    error: function (file, response) {
        this.removeFile(file);
        if (response.error) {
            toastrs(response.error, 'error');
        } else {
            toastrs(response.error, 'error');
        }
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
