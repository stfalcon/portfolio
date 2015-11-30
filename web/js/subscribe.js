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
                    $form.closest('.subscribe-form-wrap').replaceWith(response.view);
                    inputChange();
                } else {
                    if (window.ga) {
                        ga('send', 'event', 'subscribe', 'success');
                    }
                    $form.find('input[type="email"]').val('');
                    $form.find('.error-list').remove();
                    inputChange();
                }
            }
        });
    });
    function inputChange(){
        $('#subscribe_email').keyup(function () {
            $('.error-list').empty();
        });
    }
});
