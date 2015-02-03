var orderApplicationFormMessages = {
    'order_promotion[name]': {
        required: "Пожалуйста, введите ваше имя",
        maxlength: "Пожалуйста, введите не более 64 символов.",
        minlength: jQuery.validator.format("Введите имя не меньше 3 символов")
    },
    'order_promotion[email]': {
        required: "Пожалуйста, введите адрес вашей эл.почты",
        maxlength: "Пожалуйста, введите не более 72 символов.",
        email: "Ваш эл.адрес должен быть формата name@domain.com"
    },
    'order_promotion[message]': {
        required: "Пожалуйста, введите сообщение",
        maxlength: "Пожалуйста, введите не более 5000 символов.",
        minlength: "Пожалуйста, введите не менее 30 символов."
    }
};



