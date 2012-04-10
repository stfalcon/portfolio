/**
 * Copyright (c) 2010, Nathan Bubna
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * This plugin provides simple effects to "pulse" an element,
 * to indicate that background work is occuring and yada yada yada.
 * To use, call:
 *
 *   $('#foo').pulse()
 *
 * If you want to ensure that a call doesn't errantly toggle on when
 * you meant to toggle off (or vice versa), then put a boolean value
 * as your first argument.  true is on, false is off.
 *
 *   $.pulse(false); // will only ever toggle off
 *   $.pulse(true, 'fade');  // will only ever toggle on
 *
 * You can change any of the default options by altering the $.pulse
 * properties (or sub-properties), like this:
 *
 *  $.pulse.effect = 'ellipsis update';
 *  $.pulse.update.texts = 'Working...';
 *
 * All options can also be overridden per-call by passing in
 * an options object with the overriding properties, like so:
 *
 *  $('#foo').pulse(true, 'fade', {fade:{speed:'fast'}});
 *
 * And if that isn't enough, this plugin supports the metadata plugin as a
 * way to specify options directly in your markup.
 *
 * To employ multiple pulse effects at once, just separate with spaces:
 *
 *  $.pulse('working fade');
 *
 * Of particular note here is that it is easy to plug in additional
 * effects.  Just add an object with a 'run' function to $.pulse
 * under the desired effects name, like this:
 *
 *  $.pulse.blink = {
 *      speed: 800,
 *      slowPeriod: 5000,
 *      run: function(pulseOpts, blinkOpts) {
 *          var el = this,
 *              speed = blinkOpts.speed,
 *              blink = function(){ el.toggle(); };
 *          blinkOpts.interval = setInterval(blink, speed);
 *          blinkOpts.timeout = setTimeout(function() {
 *              // stop slow blink and speed it up
 *              clearInterval(blinkOpts.interval);
 *              blinkOpts.interval = setInterval(blink, speed / 2);
 *          }, blinkOpts.slowPeriod);
 *      },
 *      end: function(pulseOpts, blinkOpts) {
 *          this.show();
 *      }
 *  }
 *
 * and use it like so:
 *
 *  $('#foo').pulse('blink');
 *
 * Notice that the the interval and timeout ids are saved under those
 * particular keys in the blinkOpts. Using those particular keys allows
 * the parent plugin to recognize them and automatically clear them for you
 * when the pulse is stopped.  Otherwise, you would need to clear them yourself
 * in the end function, which is called when the pulse is stopped to allow
 * you to restore the element or do other such cleanuo.
 *
 * Contributions, bug reports and general feedback on this is welcome.
 *
 * @version 1.0
 * @name pulse
 * @author Nathan Bubna
 */
;(function($) {

    // the main interface...
    $.pulse = function(show, effect, opts) {
        $($.pulse.select).pulse(show, effect, opts);
        return $.pulse;
    };
    var P = $.fn.pulse = function(show, effect, opts) {
        if (!this.length) return this;//abort on empty selections
        opts = P.toOpts(show, effect, opts);
        return this.each(function() {
            var $el = $(this),
                meta = $.metadata ? $el.metadata() : null,// support metadata
                o = $.extend(true, {}, $.pulse, meta, opts);
            P.toggle.call($el, o);
        });
    };

    // all that's configurable...
    $.extend($.pulse, {
        version: "1.0",
        select: '.pulse',
        effect: 'type',
        updateEvent: 'textUpdate',

        // effect definitions
        update: {
            isText: true,
            texts: ['Still working...', 'Task may have failed.'],
            times: [10000, 50000],
            classes: ['pulse-working', 'pulse-error'],
            autoSize: true,
            run: function(o, e) {
                fix('text'); fix('time'); fix('classes'); set(0);
                function fix(prop) {// force prop to array >= e.texts
                    var a = e[prop];
                    if (!$.isArray(a)) a = e[prop] = [a];
                    while (e.texts.length > a.length) a.push(a[0]);
                }
                function set(i) {
                    e.timeout = setTimeout(function() {
                        e.change.call(o.self, o, e, i);
                        if (++i < e.texts.length) set(i);
                    }, e.times[i]);
                }
            },
            change: function(o, e, i) {
                if (e.autoSize) this.height('auto').width('auto');
                if (i > 0) this.removeClass(e.classes[i-1]);
                this.addClass(e.classes[i]);
                e.self.text(e.text = e.texts[i]).trigger(o.updateEvent, [e.text, o]);
            },
            end: function(o, e) {
                var a = e.classes;
                for (var i=0; i<a.length; i++) this.removeClass(a[i]);
            }
        },
        fade: {
            time: 1200,
            speed: 600,
            run: function(o, e) {
                var el = this, s = e.speed,
                    fade = function(){ el.fadeOut(s, function(){ el.fadeIn(s); }); };
                e.interval = setInterval(fade, e.time);
                fade();
            }
        },
        ellipsis: {
            isText: true,
            time: 300,
            dot: '.',
            re: /\.$/,
            run: function(o, e) {
                var el = this, c = 0;
                if (e.dot != '.') e.re = new RegExp(e.dot+'$');
                e.interval = setInterval(function() {
                    el.text(e.text = c++ > 0 ? e.text + e.dot : e.trim(e.text, e));
                    if (c > 3) c = 0;
                }, e.time);
            },
            trim: function(t, e) {
                return e.re.test(t) ? e.trim(t.substring(0, t.length - e.dot.length), e) : t;
            },
            update: function(ev, txt, o, e) {
                e.text = e.trim(txt, e);
            }
        },
        type: {
            isText: true,
            time: 100,
            ns: '.pulseType',
            run: function(o, e) {
                var el = this;
                e.txt = e.text;
                e.interval = setInterval(function() {
                    var t = e.txt, l = e.text.length;
                    el.text(e.text = l == t.length ? t.charAt(0) : t.substring(0, l+1));
                }, e.time);
            },
            update: function(ev, txt, o, e) {
                e.txt = e.text = txt+' ';
            }
        }
    });

    // all that's extensible...
    $.extend($.fn.pulse, {
        toggle: function(o) {
            var old = this.data('pulse');
            if (old) {
                if (o.show !== true) P.stop.call(this, old, o);
            } else {
                if (o.show !== false) P.start.call(this, o);
            }
        },
        start: function(o) {
            this.data('pulse', o);
            o.self = this;
            o.fx = [];
            $.each(o.effect.split(' '), function() {
                var e = o[this];
                if (e === undefined) throw "Pulse effect called '"+this+"' is unknown";
                if (e.isText) {
                    // for $.place, apply text fx only to text content
                    e.self = o.self.find('.place-text-content');
                    if (e.self.length == 0) e.self = o.self;
                    e.original = e.text = o.text || $.trim(e.self.text());
                    if (e.update) {
                        e.self.bind(o.updateEvent, e.updater = function(ev, txt) {
                            e.update.call(this, ev, txt, o, e);
                        });
                    };
                } else {
                    e.self = o.self;
                }
                e.run.call(e.self, o, e);
            });
        },
        stop: function(o, newO) {
            this.data('pulse', null);
            $.each(o.effect.split(' '), function() {
                var e = o[this];
                if (e.end) e.end.call(o.self, o, e, newO);
                if (e.interval) clearInterval(e.interval);
                if (e.timeout) clearTimeout(e.timeout);
                if (e.text && e.text != e.original) e.self.text(e.original);
                if (e.updater) e.self.unbind(o.updateEvent, e.updater);
            });
        },
        toOpts: function(show, effect, o) {
            if (typeof show != "boolean") { o = effect; effect = show; show = undefined; }
            if ($.isPlainObject(effect)) { o = effect; effect = null; }
            if (!o) o = {};
            if (show !== undefined) o.show = show;
            if (effect) o.effect = effect;
            return o;
        }
    });

})(jQuery);
