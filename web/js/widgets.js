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
$(document).on('click', '.hire_us, .hire_us_land, .hire_us_main' , function (e) {
    e.preventDefault()
    $('body').addClass('open-hire_us');
});

$.validator.addMethod(
    "phone",
    function(value, element, regexp) {
        var re = new RegExp(regexp);
        return this.optional(element) || re.test(value);
    }
);

$(function () {

    var formDelay = 3000;
    var $hireUsForm = $('#hire-us-form');


    $.validator.methods.email = function( value, element ) {
        return this.optional( element ) || /^\w([\-\.]{0,1}\w)*\@\w+([\-\.]{0,1}\w)*\.\w{2,4}$/.test( value );
    };

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
                maxlength: 72,
                email: true
            },
            'order_promotion[company]': {
                required: false,
                minlength: 3,
                maxlength: 72
            },
            'order_promotion[position]': {
                required: false,
                minlength: 3,
                maxlength: 72
            },
            'order_promotion[phone]': {
                required: true,
                phone: '[0-9\\-\\(\\)\\s]+$'
            },
            'order_promotion[message]': {
                required: true,
                minlength: 30,
                maxlength: 5000
            }
        },
        errorPlacement: function (label, element) {
            label.addClass('error-pad');
            var parent_elem = element.parent();
            if (parent_elem.hasClass('line__radio')) {
                parent_elem = parent_elem.parent();
            }
            label.insertAfter(parent_elem);
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
                        dataLayer.push({'event': 'submit_popupform'});
                    } else{
                        $(form).find("button").prop('disabled', false);
                    }

                    grecaptcha.reset();
                }
            });
            return false;
        }
    });
    var $getPostData = $('#get-post-data');

    $getPostData.validate({
        rules: {
            'client_info[name]': {
                required: true,
                minlength: 3,
                maxlength: 64
            },
            'client_info[email]': {
                required: true,
                minlength: 3,
                maxlength: 72,
                email: true
            },
            'client_info[company]': {
                required: false,
                minlength: 3,
                maxlength: 72
            },
            'client_info[position]': {
                required: false,
                minlength: 3,
                maxlength: 72
            },
            'client_info[phone]': {
                required: true,
                phone: '[0-9\\-\\(\\)\\s]+$'
            }
        },
        errorPlacement: function (label, element) {
            label.addClass('error-pad');
            var parent_elem = element.parent();
            if (parent_elem.hasClass('line__radio')) {
                parent_elem = parent_elem.parent();
            }
            label.insertAfter(parent_elem);
        },
        wrapper: 'div',
        debug: false
    });

    var $leadForm = $('#lead-form');

    $leadForm.validate({
        rules: {
            'person_form[name]': {
                required: true,
                minlength: 3,
                maxlength: 64
            },
            'person_form[email]': {
                required: true,
                minlength: 3,
                maxlength: 72,
                email: true
            },
            'person_form[company]': {
                required: false,
                minlength: 3,
                maxlength: 72
            },
            'person_form[position]': {
                required: false,
                minlength: 3,
                maxlength: 72
            }
        },
        errorPlacement: function (label, element) {
            label.addClass('error-pad');
            var parent_elem = element.parent();
            if (parent_elem.hasClass('line__radio')) {
                parent_elem = parent_elem.parent();
            }
            label.insertAfter(parent_elem);
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
                        $('.close-hire_us').trigger('click');
                        Cookies.set('lead-data-send', '1', { expires: 365 });
                        dataLayer.push({'event': 'submit_leadform'});
                    } else{
                        $(form).find("button").prop('disabled', false);
                    }
                }
            });
            return false;
        }
    });
});
