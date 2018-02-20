$(function () {
    var vacancy_form = $('#direct-vacancy-form');
    if (vacancy_form.length) {
        vacancy_form.validate({
            submitHandler: function(form) {
                form.submit();
                $('#direct-vacancy-form').find('.btn').attr('disabled', true);
            },
            rules: {
                'vacancy_form[name]': {
                    required: true,
                    minlength: 3
                },
                'vacancy_form[email]': {
                    required: true,
                    email: true,
                    minlength: 5
                },
                'vacancy_form[phone]': {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                'vacancy_form[name]': {
                    required: directVacancyFormMessage.name.required,
                    minlength: jQuery.validator.format(directVacancyFormMessage.name.minlength)
                },
                'vacancy_form[email]': {
                    required: directVacancyFormMessage.email.required,
                    minlength: directVacancyFormMessage.email.minlength
                },
                'vacancy_form[phone]': {
                    required: directVacancyFormMessage.phone.required,
                    minlength: jQuery.validator.format(directVacancyFormMessage.phone.minlength),
                }
            },
            errorPlacement: function (label, element) {
                label.addClass('error-pad');
                label.insertAfter(element);
            },
            wrapper: 'div',
        });

        $.validator.addClassRules({
            name: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                minlength: 5
            },
            phone: {
                required: true,
                minlength: 10
            }
        });

        $("#vacancy_form_phone").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: Ctrl+C
                (e.keyCode == 67 && e.ctrlKey === true) ||
                // Allow: Ctrl+X
                (e.keyCode == 88 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }

    // jQuery.extend(jQuery.validator.messages, {
    //     email: directVacancyFormMessage.email.defaultMessage
    // });
});

$(document).ready(function () {
    if (typeof isErrors != 'undefined' && isErrors > 0) {
        var elem = $('#vacancy-form');
        $('html, body').animate({
            scrollTop: elem.offset().top-70
        }, 2000);
    }
});