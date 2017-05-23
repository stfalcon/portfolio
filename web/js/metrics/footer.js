$(document).ready(function () {
    $("a[href^='tel:']").on('click', function () {
        ga('send', 'event', 'click', 'phone', this.href);
    });

    $("a[href^='skype:stfalcon']").on('click', function () {
        ga('send', 'event', 'click', 'skype', this.href);
    });

    $("a[href='mailto:info@stfalcon.com']").on('click', function () {
        ga('send', 'event', 'click', 'mail', this.href);
    });
});
