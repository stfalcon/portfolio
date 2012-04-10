/**
 * Copyright (c) 2010, Nathan Bubna
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * This plugin primarily serves as a reusable set of base functionality
 * for other plugins of mine ($.place, $.mask and $.loading). To use, call:
 *
 *   $.measure();
 *
 * to measure the page, or use:
 *
 *   $('#foo').measure()
 *
 * to measure any specific element(s).
 *
 * Both methods will return an object with 4 properties: top, left, height and width.
 * This returned "box" definition can be directly applied to any child element you wish
 * to position at the top-left corner of the element you just measured. Or they can
 * be used to calculate other positions within that "box".
 *
 * If you call this on a jQuery selection with multiple elements, it will return an
 * array of results.
 *
 * Options may be set directly on the element also (uses the metadata plugin).
 *
 * Contributions, bug reports and general feedback on this is welcome.
 *
 * @version 1.0
 * @name measure
 * @author Nathan Bubna
 */
;(function($) {

    // the main interface...
    $.measure = function(opts) {
        return $('body').measure(opts, true);
    };
    var M = $.fn.measure = function(opts) {
        var results = M.plugin.call(this, $.measure, opts, M.measure);
        return results.length == 1 ? results[0] : results;
    };

    // position CSS for page opts //TODO: better support test...
    var fixedCss = { position: $.browser.msie ? 'absolute' : 'fixed' };

    // all that's configurable...
    $.extend(true, $.measure, {
        version: "1.0",
        css: { position:'absolute' },
        fixedCss: fixedCss,
        pageOptions: { page:true, css:fixedCss }
    });

    // all that's extensible...
    $.extend($.fn.measure, {
        // functions
        plugin: function(P, opts, fn) {//P is for plugin
            if (this.length == 0) return [];//abort plugin on empty selections
            var results = [];
            this.each(function() {
                var $el = $(this),
                    page = this == document.body ? P.pageOptions : null,
                    meta = $.metadata ? $el.metadata() : null,//metadata support
                    o = $.extend(true, {}, P, page, meta, opts),
                    ret = fn.call($el, o);
                    //console.log(o, page, meta, opts);
                if (ret === false) return ret;//abort each on false
                if (ret !== undefined) results.push(ret);
            });
            return results;
        },
        measure: function(o) {
            return this.box || (this.box = o.page ? M.pageBox(o) : M.elementBox(this, o));
        },
        elementBox: function(el, o) {
            if (el.css('position') == 'absolute') {
                var box = { top: 0, left: 0 };
            } else {
                var box = el.position();
                box.top += M.getCss(el, 'marginTop');
                box.left += M.getCss(el, 'marginLeft');
            }
            box.height = el.outerHeight();
            box.width = el.outerWidth();
            return box;
        },
        pageBox: function(o) {
            var d = document, b = d.body, full = $.boxModel && o.css.position != 'fixed',
                h = full ? $(d).height() : b.clientHeight,
                w = full ? $(d).width() : b.clientWidth;
            return { top: 0, left: 0, height: h, width: w };
        },
        getCss: function(el, prop) {
            var val = el.css(prop);
            return val == 'auto' ? 0 : parseFloat(val, 10);
        }
    });

})(jQuery);
