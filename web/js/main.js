$(function () {
    var nav = $('header nav');

    // show mobile navigation
    $('.show-mobile-nav').on('click', function () {
        nav.fadeIn(200);
    });

    $('.close-mobile-nav').on('click', function () {
        nav.fadeOut(200);
    });

    if (!$('html').hasClass('lt-ie10')) {
        enquire.register("screen and (min-width:620px)", {
            match: function () {
                nav.show();
            },
            unmatch: function () {
                nav.hide();
            }
        })
    }

    var yearsCnt = $('.years-count'),
        startPos;
    var yearList = $('.year-slider-wrapp li');

    function updateYear(value) {
        $('#view-year').html(value)
    }

    $("#year-slider").slider({
        min: 0,
        max: yearList.length - 1,
        value: 0,
        step: 1,
        range: 'min',
        start: function (event, ui) {
            startPos = $(yearList[ui.value]).data('val');
        },
        change: function (event, ui) {
            counter(startPos, $(yearList[ui.value]).data('val'));
            updateYear($(yearList[ui.value]).html());
        }
    });
    $('#year-slider').draggable(); // Enable toush dragging

    function counter(start, end) {
        var i = start;
        var t = setInterval(function () {
            if (i == end) {
                clearInterval(t);
            } else if (i > end) {
                i--
            } else {
                i++
            }
            yearsCnt.html(i);
        }, 20);
    }
});

var filter;
var teamList = $('.avatar'),
    drinks = $('.drinks');

$('.interests a').hover(function () {
    filter = $(this).data('filter');
    if (filter === "drinks") {
        $(teamList).stop(true, true).animate({opacity: '0.2'}, 400);
        drinks.fadeIn(400);
    }
    teamList.each(function (index, value) {
        var interests = eval($(value).data('interests'));
        if ($.inArray(filter, interests) < 0) {
            $(value).stop(true, true).animate({opacity: '0.2'}, 200);
        }
    });

}, function () {
    teamList.stop(true, true).animate({opacity: '1'}, 200);
    if (filter === "drinks") {
        drinks.stop(true, true).fadeOut(200);
    }
});
