$(function () {
    if ($('#direct-order-form').length) {
        $('#direct-order-form').validate({
            rules: {
                'direct_order[name]': {
                    required: true,
                    minlength: 3
                },
                'direct_order[email]': {
                    required: true,
                    email: true,
                    minlength: 3
                },
                'direct_order[phone]': {
                    required: true,
                    minlength: 7
                },
                'direct_order[message]': {
                    required: true,
                    minlength: 30
                }
            },
            messages: {
                'direct_order[name]': {
                    required: directOrderFormMessage.name.required,
                    minlength: jQuery.validator.format(directOrderFormMessage.name.minlength)
                },
                'direct_order[email]': {
                    required: directOrderFormMessage.email.required,
                    minlength: directOrderFormMessage.email.minlength
                },
                'direct_order[phone]': {
                    required: directOrderFormMessage.phone.required,
                    minlength: jQuery.validator.format(directOrderFormMessage.phone.minlength)
                },
                'direct_order[message]': {
                    required: directOrderFormMessage.message.required,
                    minlength: jQuery.validator.format(directOrderFormMessage.message.minlength)
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
                minlength: 3
            },
            phone: {
                required: true,
                minlength: 7
            },
            messageText: {
                required: true,
                minlength: 30
            }
        });

        $("#direct_order_phone").keydown(function (e) {
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
});
