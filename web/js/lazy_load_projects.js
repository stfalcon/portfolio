$(function () {
    var all_item_count = next_count;
    function loadMore() {
        if (next_count > 0) {
            append_preload();
            render_next_item();
        }
        $(window).bind('scroll', bindScroll);
    }

    function bindScroll() {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - ($(document).height() * 0.1)) {
            $(window).unbind('scroll');
            loadMore();
        }
    }

    function render_next_item() {

        var route = Routing.generate('portfolio_next_projects', {limit: 4, offset: all_item_count});
        next_count = 0;
        $.get(route, function (data) {
            all_item_count += data['data'].length;
            next_count = data['nextCount'];
            var preload_items = $('.project-cell_load');
            $.each(data['data'], function( index, value ) {
                $(preload_items[index]).append(value);
                $(preload_items[index]).removeClass('project-cell_load');
                $(preload_items[index]).find('.container_load').remove();
            });
        });
    }

    $(window).scroll(bindScroll);

    function append_preload() {
        var preload_block = '<li class="project-cell project-cell_load"><div class="container_load"><div class="loader"><svg class="circular"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="1" stroke-miterlimit="10"/></svg></div></div></li>';
        for (var i = 0; i < next_count; i++) {
            $('.items_projects').append(preload_block);
        }
    }
});