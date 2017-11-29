$(function () {
    var vacancy_form = $('#direct-vacancy-form');
    if (vacancy_form.length) {
        vacancy_form.validate({
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
                    minlength: 10,
                    pattern: /\d+$/i
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
                    pattern: directVacancyFormMessage.phone.pattern
                }
            },
            errorPlacement: function (label, element) {
                label.addClass('error-pad');
                label.insertAfter(element);
            },
            wrapper: 'div',
            debug: true
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
                minlength: 10,
                pattern: /\d+$/i
            }
        });
    }

    jQuery.extend(jQuery.validator.messages, {
        email: directVacancyFormMessage.email.defaultMessage
    });
});
