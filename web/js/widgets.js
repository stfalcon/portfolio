$(function () {
    var formDelay = 3000;

    $('#hire-us-form').validate({
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
        errorPlacement: function (label, element) {
            label.addClass('error-pad');
            label.insertAfter(element);
        },
        wrapper: 'div',
        debug: false,
        submitHandler: function (form, e) {
            e.preventDefault();

            $.ajax({
                url: $(form).attr('action'),
                type: "POST",
                dataType: "json",
                data: $(form).serialize(),
                beforeSend: function () {
                    $(form).find("button").prop('disabled', true);
                },
                cache: false,
                async: false,
                success: function (response) {
                    $(form).find('.form-pad').animate({opacity: 0}, 300).delay(formDelay).animate({opacity: 1}, 300);
                    $(form).find('.form-success').fadeIn(300).delay(formDelay).fadeOut(300);
                    setTimeout(function() {
                        $('body').removeClass('open-hire_us');
                    }, 4000);
                    $(form).trigger('reset');
                    $(form).find("button").prop('disabled', false);
                }
            });

            return false;
        }
    });
});
