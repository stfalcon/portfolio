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
            required: "Пожалуйста, введите ваше имя",
            maxlength: "Пожалуйста, введите не более 64 символов.",
            minlength: jQuery.validator.format("Введите имя не меньше 3 символов")
        },
        'order_promotion[email]': {
            required: "Пожалуйста, введите адрес вашей эл.почты",
            maxlength: "Пожалуйста, введите не более 72 символов.",
            email: "Ваша эл.адрес должен быть формата name@domain.com"
        },
        'order_promotion[message]': {
            required: "Пожалуйста, введите сообщение",
            maxlength: "Пожалуйста, введите не более 5000 символов.",
            minlength: "Пожалуйста, введите не менее 30 символов."
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
                    ga('send', 'pageview', 'order_sent');

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



