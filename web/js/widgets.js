//Hide hire us
var windowCloseTimerId = false;
$(document).on('click', '.close-hire_us', function(){
    $('#hire-us-form').find('.form-pad').css({opacity: "1"});
    $('body').removeClass('open-hire_us');
    $('#hire-us-form').trigger('reset');
    $('#hire-us-form').find("button").prop('disabled', false);
    $('#hire-us-form').find('.form-success').css('display','none');
    $('#hire-us-form').find('.form-pad').stop(true, true).css('opacity','1');
    clearTimeout(windowCloseTimerId);
    $('#order_promotion_message').css('height','auto');
});

//Show hire us
$(document).on('click', '.hire_us, .hire_us_land, .hire_us_main' , function () {
    $('body').addClass('open-hire_us');
});

$(function () {

    var formDelay = 3000;
    var $hireUsForm = $('#hire-us-form');

    $hireUsForm.validate({
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
                url: $(form).data('url'),
                type: "POST",
                dataType: "json",
                data: $(form).serialize(),
                beforeSend: function () {
                    $(form).find("button").prop('disabled', true);
                },
                cache: false,
                async: false,
                success: function (response) {
                    if ('success' === response.status) {
                        $(form).find('.form-pad').animate({opacity: 0}, 300);
                        $(form).find('.form-success').fadeIn(300);
                        windowCloseTimerId = setTimeout(function () {
                            $('.close-hire_us').trigger('click');
                        }, 4000);
                    } else{
                        $(form).find("button").prop('disabled', false);
                    }

                    grecaptcha.reset();
                }
            });
            return false;
        }
    });
});
