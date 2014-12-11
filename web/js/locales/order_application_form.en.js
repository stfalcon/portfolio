var orderApplicationFormMessages = {
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
};