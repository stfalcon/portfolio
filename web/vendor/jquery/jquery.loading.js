/**
 * Copyright (c) 2010, Nathan Bubna
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpo.html
 *
 * This plugin makes it trivial to notify your users that
 * things are in progress.  The typical case is waiting for an
 * AJAX call to finish loading.  Just call:
 *
 *   $.loading();
 *
 * to toggle a page-wide message on and off, or you can call:
 *
 *   $('#foo').loading()
 *
 * to do the same, but locate the message within a specific element(s).
 *
 * If you want to ensure that a call doesn't errantly toggle on when
 * you meant to toggle off (or vice versa), then put a boolean value
 * as your first argument.  true is on, false is off.
 *
 *   $.loading(false); // will only ever toggle off
 *   $.loading(true, {align: 'bottom-right'});  // will only ever toggle on
 *
 * If you want a loading message to automatically go on while your
 * AJAX stuff is happening (and off when done), there's a convenient option
 * to set that up properly for you. Just do:
 *
 *   $.loading({onAjax:true, text: 'Waiting...'});
 *
 * If you want to avoid a too-quick-to-see flash of the loading message
 * in situations where the "loading" happens in a flash, then you can set
 * a delay value (in milliseconds) to block the display of the loading
 * message on all but "long" loads:
 *
 *   $.loading({onAjax:true, delay: 100});
 *
 * You can change any of the default options by altering the $.loading
 * properties (or sub-properties), like this:
 *
 *  $.loading.classname = 'loadMsg';
 *  $.loading.css.border = '1px solid #000';
 *  $.loading.working.time = 5000;
 *
 * All options can also be overridden per-call by passing in
 * an options object with the overriding properties, like so:
 *
 *  $.loading({ element:'#cancelButton', mask:true });
 *  $('#foo').loading(true, { img:'loading.gif', align:'center'});
 *
 * And if that isn't enough, this plugin supports the metadata plugin as a
 * way to specify options directly in your markup.
 *
 * Be sure to check out the provided demo for an easy overview of the most
 * commonly used options!! Of course, everything in this plugin is easy to
 * configure and/or override with those same techniques.
 *
 * To employ multiple pulse effects at once, just separate with spaces:
 *
 *  $.loading({ pulse: 'working fade', mask:true });
 *
 * Of particular note here is that it is easy to plug in additional
 * "pulse" effects.  Just add an object with a 'run' function to $.loading
 * under the desired effects name, like this:
 *
 *  $.loading.moveLeft = {
 *      time: 500,
 *      run: function(opts) {
 *          var self = this, box = opts.box;
 *          // save interval like this and it will be cleared for you
 *          // when this loading call is toggled off
 *          opts.moveLeft.interval = setInterval(function() {
 *              box.left += 1;
 *              self.animate(box);
 *          }, opts.moveLeft.time);
 *      }
 *  }
 *
 * then use it by doing something like:
 *
 *  $.loading({ pulse: 'moveLeft', align:{top:0,left:0} });
 *
 * If you add an 'end' function to that same object, then the end function
 * will be called when the loading message is turned off.
 *
 * Speaking of turning things on and off, this plugin will trigger 'loadingStart'
 * and 'loadingEnd' events when loading is turned on and off, respectively.
 * The options will, of course, be available as a second argument to functions
 * that are bound to these events.  See the demo source for an example. In
 * future versions, this plugin itself may use those events, but for now they
 * are merely notifications.
 *
 * If you are certain you only want the loading message displayed for a limited
 * period of time, you may set the 'max' option to have it automatically end
 * after the specified number of milliseconds:
 *
 *  $.loading({ text: 'Wait!', pulse: false, mask: true, max: 30000 });
 *
 * Contributions, bug reports and general feedback on this is welcome.
 *
 * @version 2.0
 * @requires $.measure
 * @requires $.place
 * @supports $.mask
 * @supports $.pulse
 * @supports $.txt
 * @name loading
 * @author Nathan Bubna
 */
;(function($) {
    // enforce requirement(s)
    if (!$.place) throw '$.loading plugin requires $.place plugin to be present';

    // the main interface...
    $.loading = function(show, opts) {
        $('body').loading(show, opts, true);
        return L;
    };
    var L = $.fn.loading = function(show, opts) {
        opts = L.toOpts(show, opts);
        L.plugin.call(this, $.loading, opts, function(o) {
            if (typeof o.onAjax == "boolean") {
                L.setAjax.call(this, o);
            } else {
                L.toggle.call(this, o);
            }
        });
        return this;
    };

    // all that's configurable...
    $.extend(true, $.loading, $.place, {
        version: "2.0",
        // common
        effect: 'update',
        mask: false,
        text: 'Loading...',
        onAjax: undefined,
        delay: 0,
        // less common
        loadingClass: 'loading'
    });

    // all that's extensible...
    $.extend(true, $.fn.loading, $.fn.place, {
        toggle: function(o) {
            var old = this.data('loading');
            if (old) {
                if (o.show !== true) L.off.call(this, old, o);
            } else {
                if (o.show !== false) L.on.call(this, o);
            }
        },
        setAjax: function(o) {
            if (o.onAjax) {
                var el = this, A = o.ajax = {
                    start: function() { L.on.call(el, o); },
                    stop: function() { L.off.call(el, o); }
                };
                this.bind('ajaxStart.loading', A.start).bind('ajaxStop.loading', A.stop);
            } else {
                this.unbind('ajaxStart.loading ajaxStop.loading');
            }
        },
        on: function(o, force) {
            var p = this.data('loading', o);
            if (o.delay && !force) {
                return o.delayId = setTimeout(function() {// break
                    delete o.delayId;
                    L.on.call(p, o, true);
                }, o.delay);
            }
            if (o.mask) o.mask = p.mask(o);
            p.place(o);
            o.self = p.data('place').self.addClass(o.loadingClass);
            p.trigger('loadingStart', [o]);
        },
        off: function(o, newO) {
            this.data('loading', null);
            if (o.delayId) return clearTimeout(o.delayId);// break
            if (o.mask) this.mask(false, o);
            this.place(false, o);
            if (o.parent) o.parent.trigger('loadingEnd', [o]);
        },
        toOpts: function(s, o) {
            if (o === undefined) {
                o = (typeof s == "boolean") ? { show: s } : s;
            } else {
                o.show = s;
            }
            // default pulse to off if doing an img
            if (o && (o.img || o.element) && !o.effect) o.effect = false;
            // if onAjax and they didn't specify show, default to false
            if (o && o.onAjax !== undefined && o.show === undefined) o.show = false;
            return o;
        }
    });

    // on doc-ready
    $(function() {
        if ($.txt) {
            $.loading.text = $.txt($.loading.text);
        }
    });

})(jQuery);
