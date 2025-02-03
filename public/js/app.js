$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

jQuery.validator.setDefaults({
    debug: false,
    validClass: "is-valid",
    errorClass: "is-invalid",
    errorElement: "span",
    errorPlacement: function(error, element) {
        error.addClass('invalid-feedback');

        if($(element).parent().is('.dropify-wrapper')) {
            error.insertAfter($(element).parent());
        } else if($(element).parent().is('.input-group')) {
            error.insertAfter($(element).parent());
        } else if($(element).parent().is('.intl-tel-input')) {
            error.insertAfter($(element).parent());
        } else if($(element).parent().is('.validator-group')) {
            error.insertAfter($(element).parent());
        } else {
            error.appendTo($(element).parent());
        }
    },
    highlight: function(element, errorClass, validClass) {
        $(element).addClass(errorClass).removeClass(validClass);
        $(element).closest('.form-group').addClass(errorClass).removeClass(validClass);
    },
    unhighlight: function(element, errorClass, validClass) {
        $(element).removeClass(errorClass).addClass(validClass);
        $(element).closest('.form-group').removeClass(errorClass).addClass(validClass);
    }
});

$(function() {
    'use strict';

    $('.select2').change(function() {
        $(this).valid();
    });

});
