"use strict";

$(document).ready(function(){
});

function toastrs(title, message, status) {
    toastr[status](message, title)
}

$(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]', function () {
    var title = $(this).data('title');
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var url = $(this).data('url');

    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        success: function (data) {
            $('#commonModal .modal-content').html(data);
            $("#commonModal .modal-title").html(title);
            $("#commonModal").modal('show');
            taskCheckbox();
            common_bind("#commonModal");
            common_bind_select("#commonModal");
        },
        error: function (data) {
            data = data.responseJSON;
            toastrs('Error', data.error, 'error')
        }
    });

});

$(document).on('click', 'a[data-ajax-popup-over="true"], button[data-ajax-popup-over="true"], div[data-ajax-popup-over="true"]', function () {

    var validate = $(this).attr('data-validate');
    var id = '';
    if (validate) {
        id = $(validate).val();
    }

    var title = $(this).data('title');
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var url = $(this).data('url');

    $("#commonModalOver .modal-dialog").addClass('modal-' + size);

    $.ajax({
        url: url + '?id=' + id,
        success: function (data) {
            $('#commonModalOver .modal-content').html(data);
            $("#commonModalOver .modal-title").html(title);
            $("#commonModalOver").modal('show');
            taskCheckbox();
        },
        error: function (data) {
            data = data.responseJSON;
            toastrs('Error', data.error, 'error')
        }
    });

});

function arrayToJson(form) {
    var data = $(form).serializeArray();
    var indexed_array = {};

    $.map(data, function (n, i) {
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

$(document).on("submit", "#commonModalOver form", function (e) {
    e.preventDefault();
    var data = arrayToJson($(this));
    data.ajax = true;

    var url = $(this).attr('action');
    $.ajax({
        url: url,
        data: data,
        type: 'POST',
        success: function (data) {
            toastrs('Success', data.success, 'success');
            $(data.target).append('<option value="' + data.record.id + '">' + data.record.name + '</option>');
            $(data.target).val(data.record.id);
            $(data.target).trigger('change');
            $("#commonModalOver").modal('hide');

            $(".selectric").selectric({
                disableOnMobile: false,
                nativeOnMobile: false
            });

        },
        error: function (data) {
            data = data.responseJSON;
            toastrs('Error', data.error, 'error')
        }
    });
});


function common_bind(selector = "body") {
  flatpickr('.datepicker');
  flatpickr('[data-flatpickr]');
  // var $datepicker = $(selector + ' .datepicker');

  // if ($(".datepicker").length) {
  //     $('.datepicker').daterangepicker({
  //         singleDatePicker: true,
  //         format: 'yyyy-mm-dd',
  //         locale: {format: 'YYYY-MM-DD'},
  //         // todayHighlight: true,
  //     });
  // }

  // function init($this) {
  //     var options = {
  //         disableTouchKeyboard: true,
  //         autoclose: false,
  //         format: 'yyyy-mm-dd'
  //     };
  //     $this.datepicker(options);
  // }
  //
  // if ($datepicker.length) {
  //     $datepicker.each(function () {
  //         init($(this));
  //     });
  // }


}

function taskCheckbox() {
    var checked = 0;
    var count = 0;
    var percentage = 0;

    count = $("#checklist input[type=checkbox]").length;
    checked = $("#checklist input[type=checkbox]:checked").length;
    percentage = parseInt(((checked / count) * 100), 10);
    if(isNaN(percentage)){
        percentage=0;
    }
    $("#taskProgressLabel").text(percentage + "%");
    $('#taskProgress').css('width', percentage + '%');


    $('#taskProgress').removeClass('bg-warning');
    $('#taskProgress').removeClass('bg-primary');
    $('#taskProgress').removeClass('bg-success');
    $('#taskProgress').removeClass('bg-danger');

    if (percentage <= 15) {
        $('#taskProgress').addClass('bg-danger');
    } else if (percentage > 15 && percentage <= 33) {
        $('#taskProgress').addClass('bg-warning');
    } else if (percentage > 33 && percentage <= 70) {
        $('#taskProgress').addClass('bg-primary');
    } else {
        $('#taskProgress').addClass('bg-success');
    }
}


(function($, window, i) {
    // Bootstrap 4 Modal
    $.fn.fireModal = function(options) {
        var options = $.extend({
            size: 'modal-md',
            center: false,
            animation: true,
            title: 'Modal Title',
            closeButton: true,
            header: true,
            bodyClass: '',
            footerClass: '',
            body: '',
            buttons: [],
            autoFocus: true,
            created: function() {},
            appended: function() {},
            onFormSubmit: function() {},
            modal: {}
        }, options);

        this.each(function() {
            i++;
            var id = 'fire-modal-' + i,
                trigger_class = 'trigger--' + id,
                trigger_button = $('.' + trigger_class);

            $(this).addClass(trigger_class);

            // Get modal body
            let body = options.body;

            if(typeof body == 'object') {
                if(body.length) {
                    let part = body;
                    body = body.removeAttr('id').clone().removeClass('modal-part');
                    part.remove();
                }else{
                    body = '<div class="text-danger">Modal part element not found!</div>';
                }
            }

            // Modal base template
            var modal_template = '   <div class="modal'+ (options.animation == true ? ' fade' : '') +'" tabindex="-1" role="dialog" id="'+ id +'">  '  +
                '     <div class="modal-dialog '+options.size+(options.center ? ' modal-dialog-centered' : '')+'" role="document">  '  +
                '       <div class="modal-content">  '  +
                ((options.header == true) ?
                    '         <div class="modal-header">  '  +
                    '           <h5 class="modal-title">'+ options.title +'</h5>  '  +
                                    ((options.closeButton == true) ?
                        '           <button type="button" class="close btn btn-round" data-dismiss="modal" aria-label="Close">  '  +
                        '           <i class="material-icons">close</i> ' +
                        '           </button>  '
                        : '') +
                    '         </div>  '
                    : '') +
                '         <div class="modal-body">  '  +
                '         </div>  '  +
                (options.buttons.length > 0 ?
                    '         <div class="modal-footer">  '  +
                    '         </div>  '
                    : '')+
                '       </div>  '  +
                '     </div>  '  +
                '  </div>  ' ;

            // Convert modal to object
            var modal_template = $(modal_template);

            // Start creating buttons from 'buttons' option
            var this_button;
            options.buttons.forEach(function(item) {
                // get option 'id'
                let id = "id" in item ? item.id : '';

                // Button template
                this_button = '<button type="'+ ("submit" in item && item.submit == true ? 'submit' : 'button') +'" class="'+ item.class +'" id="'+ id +'">'+ item.text +'</button>';

                // add click event to the button
                this_button = $(this_button).off('click').on("click", function() {
                    // execute function from 'handler' option
                    item.handler.call(this, modal_template);
                });
                // append generated buttons to the modal footer
                $(modal_template).find('.modal-footer').append(this_button);
            });

            // append a given body to the modal
            $(modal_template).find('.modal-body').append(body);

            // add additional body class
            if(options.bodyClass) $(modal_template).find('.modal-body').addClass(options.bodyClass);

            // add footer body class
            if(options.footerClass) $(modal_template).find('.modal-footer').addClass(options.footerClass);

            // execute 'created' callback
            options.created.call(this, modal_template, options);

            // modal form and submit form button
            let modal_form = $(modal_template).find('.modal-body form'),
                form_submit_btn = modal_template.find('button[type=submit]');

            // append generated modal to the body
            $("body").append(modal_template);

            // execute 'appended' callback
            options.appended.call(this, $('#' + id), modal_form, options);

            // if modal contains form elements
            if(modal_form.length) {
                // if `autoFocus` option is true
                if(options.autoFocus) {
                    // when modal is shown
                    $(modal_template).on('shown.bs.modal', function() {
                        // if type of `autoFocus` option is `boolean`
                        if(typeof options.autoFocus == 'boolean')
                            modal_form.find('input:eq(0)').focus(); // the first input element will be focused
                        // if type of `autoFocus` option is `string` and `autoFocus` option is an HTML element
                        else if(typeof options.autoFocus == 'string' && modal_form.find(options.autoFocus).length)
                            modal_form.find(options.autoFocus).focus(); // find elements and focus on that
                    });
                }

                // form object
                let form_object = {
                    startProgress: function() {
                        modal_template.addClass('modal-progress');
                    },
                    stopProgress: function() {
                        modal_template.removeClass('modal-progress');
                    }
                };

                // if form is not contains button element
                if(!modal_form.find('button').length) $(modal_form).append('<button class="d-none" id="'+ id +'-submit"></button>');

                // add click event
                form_submit_btn.click(function() {
                    modal_form.submit();
                });

                // add submit event
                modal_form.submit(function(e) {
                    // start form progress
                    form_object.startProgress();

                    // execute `onFormSubmit` callback
                    options.onFormSubmit.call(this, modal_template, e, form_object);
                });
            }

            $(document).on("click", '.' + trigger_class, function() {
                $('#' + id).modal(options.modal);

                return false;
            });
        });
    }

// Bootstrap Modal Destroyer
$.destroyModal = function(modal) {
    modal.modal('hide');
    modal.on('hidden.bs.modal', function() {
    });
}

})(jQuery, this, 0);


// Basic confirm box
$('[data-confirm]').each(function() {
    var me = $(this),
        me_data = me.data('confirm');

    me_data = me_data.split("|");
    me.fireModal({
      title: me_data[0],
      body: me_data[1],
      buttons: [
        {
          text: me.data('confirm-text-yes') || 'Yes',
          class: 'btn btn-danger btn-shadow',
          handler: function() {
            eval(me.data('confirm-yes'));
          }
        },
        {
          text: me.data('confirm-text-cancel') || 'Cancel',
          class: 'btn btn-secondary',
          handler: function(modal) {
            $.destroyModal(modal);
            eval(me.data('confirm-no'));
          }
        }
      ]
    })
});

// Basic confirm box
function customConfirm(window, title, message) {

    window.fireModal({
      title: title,
      body: message,
      buttons: [
        {
          text: 'Yes',
          class: 'btn btn-danger btn-shadow',
          handler: function() {
            $.destroyModal(modal);
            return true;
          }
        },
        {
          text: 'Cancel',
          class: 'btn btn-secondary',
          handler: function(modal) {
            $.destroyModal(modal);
            return false;
          }
        }
      ]
    })
};

//Bootstrap modal
$("#modal-1").fireModal({body: 'Modal body text goes here.'});
$("#modal-2").fireModal({body: 'Modal body text goes here.', center: true});

let modal_3_body = '<p>Object to create a button on the modal.</p><pre class="language-javascript"><code>';
modal_3_body += '[\n';
modal_3_body += ' {\n';
modal_3_body += "   text: 'Login',\n";
modal_3_body += "   submit: true,\n";
modal_3_body += "   class: 'btn btn-primary btn-shadow',\n";
modal_3_body += "   handler: function(modal) {\n";
modal_3_body += "     alert('Hello, you clicked me!');\n"
modal_3_body += "   }\n"
modal_3_body += ' }\n';
modal_3_body += ']';
modal_3_body += '</code></pre>';
$("#modal-3").fireModal({
  title: 'Modal with Buttons',
  body: modal_3_body,
  buttons: [
    {
      text: 'Click, me!',
      class: 'btn btn-primary btn-shadow',
      handler: function(modal) {
        alert('Hello, you clicked me!');
      }
    }
  ]
});

$("#modal-4").fireModal({
  footerClass: 'bg-whitesmoke',
  body: 'Add the <code>bg-whitesmoke</code> class to the <code>footerClass</code> option.',
  buttons: [
    {
      text: 'No Action!',
      class: 'btn btn-primary btn-shadow',
      handler: function(modal) {
      }
    }
  ]
});

$("#modal-5").fireModal({
  title: 'Login',
  body: $("#modal-login-part"),
  footerClass: 'bg-whitesmoke',
  autoFocus: false,
  onFormSubmit: function(modal, e, form) {
    // Form Data
    let form_data = $(e.target).serialize();
    console.log(form_data)

    // DO AJAX HERE
    let fake_ajax = setTimeout(function() {
      form.stopProgress();
      modal.find('.modal-body').prepend('<div class="alert alert-info">Please check your browser console</div>')

      clearInterval(fake_ajax);
    }, 1500);

    e.preventDefault();
  },
  shown: function(modal, form) {
    console.log(form)
  },
  buttons: [
    {
      text: 'Login',
      submit: true,
      class: 'btn btn-primary btn-shadow',
      handler: function(modal) {
      }
    }
  ]
});

$("#modal-6").fireModal({
  body: '<p>Now you can see something on the left side of the footer.</p>',
  created: function(modal) {
    modal.find('.modal-footer').prepend('<div class="mr-auto"><a href="#">I\'m a hyperlink!</a></div>');
  },
  buttons: [
    {
      text: 'No Action',
      submit: true,
      class: 'btn btn-primary btn-shadow',
      handler: function(modal) {
      }
    }
  ]
});

$('.oh-my-modal').fireModal({
  title: 'My Modal',
  body: 'This is cool plugin!'
});
