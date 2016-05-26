// Google analytics
(function (i, s, o, g, r, a, m) {
    i['GoogleAnalyticsObject'] = r;
    i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
    a = s.createElement(o), m = s.getElementsByTagName(o)[0];
    a.async = 1;
    a.src = g;
    m.parentNode.insertBefore(a, m)
})(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

ga('create', 'UA-5635962-2', 'auto');
ga('require', 'displayfeatures');
ga('send', 'pageview');


// Yandex.Metrika counter
(function (d, w, c) {
    (w[c] = w[c] || []).push(function () {
        try {
            w.yaCounter27048220 = new Ya.Metrika({
                id: 27048220, webvisor: true, clickmap: true, trackLinks: true, accurateTrackBounce: true
            });
        } catch (e) {
        }
    });

    var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () {
        n.parentNode.insertBefore(s, n);
    };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else {
        f();
    }
})(document, window, "yandex_metrika_callbacks");

// Facebook Conversion Code for request
(function () {
    var _fbq = window._fbq || (window._fbq = []);
    if (!_fbq.loaded) {
        var fbds = document.createElement('script');
        fbds.async = true;
        fbds.src = '//connect.facebook.net/en_US/fbds.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(fbds, s);
        _fbq.loaded = true;
    }
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', '6020145370099', {'value': '0.00', 'currency': 'USD'}]);
