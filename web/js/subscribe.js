$(document).ready(function () {
    $(document).on('submit', '.subscribe-form', function(e) {
        e.preventDefault();
        var $form = $(this);

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            cache: false,
            async: false,
            success: function(response) {
                if (!response.success) {
                    var $renderedForm = $(response.view);
                    $renderedForm.find('form').addClass('error-status');
                    $form.closest('.subscribe-form-wrap').replaceWith($renderedForm);
                    inputChange();
                } else {
                    if (window.ga) {
                        ga('send', 'event', 'subscribe', 'success');
                    }
                    $form.find('input[type="email"]').val('');
                    $form.find('.error-list').remove();
                    $form.addClass('success-status');

                    if (response.message) {
                        $form.find('.success-list').append('<li>' + response.message + '</li>');
                    }
                    inputChange();
                }

                setTimeout(function () {
                    var $subscribeForm = $('.subscribe-form');

                    $subscribeForm.removeClass('error-status').removeClass('success-status');
                    $subscribeForm.find('.error-list').remove();
                    $subscribeForm.find('.success-list').empty();
                }, 3000);
            }
        });
    });
    function inputChange(){
        $('#subscribe_email').keyup(function () {
            $('.error-list').empty();
        });
    }
});
