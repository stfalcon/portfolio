adroll_adv_id = "4D2635Z5I5HHPLZFLSLGPF";
adroll_pix_id = "QCTMESTTRVHSZENSLNPNCG";
(function () {
    var oldonload = window.onload;
    window.onload = function(){
        __adroll_loaded=true;
        var scr = document.createElement("script");
        var host = (("https:" == document.location.protocol) ? "https://s.adroll.com" : "http://a.adroll.com");
        scr.setAttribute('async', 'true');
        scr.type = "text/javascript";
        scr.src = host + "/j/roundtrip.js";
        ((document.getElementsByTagName('head') || [null])[0] ||
         document.getElementsByTagName('script')[0].parentNode).appendChild(scr);
        if(oldonload){oldonload()}};
}());

(function () {
    var a = document.createElement("iframe");
    a.style.cssText = "width: 0; height: 0; border: 0";
    a.src = "javascript:false";
    var b = function () {
        setTimeout(function () {
            var b = a.contentDocument || a.contentWindow.document, c = b.createElement("script");
            c.src = "//static-trackers.adtarget.me/javascripts/pixel.min.js";
            c.id = "GIHhtQfW-atm-pixel";
            c["data-pixel"] = "6b426f21b81c3aad68c1d763c1d156e4";
            c["allow-flash"] = false;
            b.body.appendChild(c);
        }, 0);
    };
    a.addEventListener ? a.addEventListener("load", b, !1) : a.attachEvent ? a.attachEvent("onload", b) : a.onload = b;
    document.body.appendChild(a);
})();

$(document).ready(function() {
    $( "a[href^='tel:']" ).on( 'click', function() {
        ga('send', 'event', 'click', 'phone', this.href);
    });

    $( "a[href^='skype:stfalcon']" ).on( 'click', function() {
        ga('send', 'event', 'click', 'skype', this.href);
    });

    $( "a[href='mailto:info@stfalcon.com']" ).on( 'click', function() {
        ga('send', 'event', 'click', 'mail', this.href);
    });
});
