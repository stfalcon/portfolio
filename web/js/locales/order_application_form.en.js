/*
 * form animation
 */
var formDelay = 3000;

/*
 * form validation
 */

var validator = $('#feedback-form').validate({
    rules: {
        'order_promotion[name]': {
            required: true,
            minlength: 3,
            maxlength: 64
        },
        'order_promotion[email]': {
            required: true,
            minlength: 3,
            maxlength: 72
        },
        'order_promotion[message]': {
            required: true,
            minlength: 30,
            maxlength: 5000
        }
    },
    messages: {
        'order_promotion[name]': {
            required: "Please, enter your name",
            maxlength: "Please, enter less than 64 symbols",
            minlength: jQuery.validator.format("Enter your name, more than 3 symbols")
        },
        'order_promotion[email]': {
            required: "Please, enter your e-mail",
            maxlength: "Please, enter less than 72 symbols",
            email: "Your e-mail should be in form name@domain.com"
        },
        'order_promotion[message]': {
            required: "Please, enter your message",
            maxlength: "Please, enter no more than 5000 symbols",
            minlength: "Please, enter more than 30 symbols"
        }
    },
    errorPlacement: function (label, element) {
        label.addClass('error-pad');
        label.insertAfter(element);
    },
    wrapper: 'div',
    debug: false,
    submitHandler: function (form) {

        $.ajax({
            url: $(form).attr('action'),
            type: "POST",
            dataType: "json",
            data: $(form).serialize(),
            beforeSend: function () {
                $(form).find("button").prop('disabled', true);
            },
            success: function (response) {
                if (response.status == "success") {
                    if (window.ga) {
                        ga('send', 'event', 'order', 'landing');
                    }

                    $('#feedback-form').find('.form-pad').animate({opacity: 0}, 300).delay(formDelay).animate({opacity: 1}, 300);
                    $('#feedback-form').find('.form-success').fadeIn(300).delay(formDelay).fadeOut(300);
                    $(form).trigger('reset');
                    $(form).find("button").prop('disabled', false);
                } else {
                    alert('Error!');
                }
            }
        });

        return false;
    }
});