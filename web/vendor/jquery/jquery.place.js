/**
 * Copyright (c) 2010, Nathan Bubna
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 *
 * This plugin exists to make it trivial to place a "message" (any image,
 * text or arbitrary element) at any corner, edge or center of either
 * the whole page or any other element.  The default placement is
 * 'top-center' for page-level messages or 'top-left' for messages
 * placed relative to a specific element.
 *
 * To place a message at page-level, call:
 *
 *   $.place('Hello World!');
 *
 * or call:
 *
 *   $('#foo').place({img:'workingIcon.gif', text:'Working...'})
 *
 * to do the same, but locate the message within a specific element(s).
 *
 * You can change any of the default options by altering the $.place
 * properties (or sub-properties), like this:
 *
 *  $.place.img = 'spinner.png';
 *  $.place.at = 'bottom-center';
 *  $.place.classname = 'myPlaceClass';
 *  $.place.css.border = '1px solid #000';
 *
 * All options can also be overridden per-call by passing in
 * an options object with the overriding properties, like so:
 *
 *  $.place({ element:'#cancelButton', css:{border:'1px dotted'} });
 *  $('#foo').place({ img:'place.gif', at:'center'});
 *
 * Also, this plugin supports the metadata plugin as a
 * way to specify options directly in your markup.
 *
 * When a message is about to be placed,
 *
 * Be sure to check out the provided demo for an easy overview of the most
 * commonly used options!! Of course, everything in this plugin is easy to
 * configure and/or override with those same techniques.
 *
 * Contributions, bug reports and general feedback on this is welcome.
 *
 * @version 1.0
 * @requires $.measure
 * @supports $.pulse
 * @supports $.txt
 * @name place
 * @author Nathan Bubna
 */
;(function($) {
    // enforce requirement(s)
    if (!$.measure) throw '$.place plugin requires $.measure plugin to be present';

    // the main interface...
    var O = $.place = function(show, el, opts) {
        $('body').place(show, el, opts);
        return O;
    },
    F = $.fn.place = function(show, el, opts) {
        opts = F.toOpts(show, el, opts);
        F.plugin.call(this, $.place, opts, function(o) {
            if (o.show === false) {
                o = this.data('place');//find old opts
                if (o) F.unplace.call(this, o);
            } else {
                this.data('place', o);
                F.init.call(this, o);
            }
        });
        return this;
    };

    // all that's configurable...
    $.extend(true, $.place, $.measure, {//include $.measure properties
        // global properties (config per call is irrelevant)
        version: "1.0",
        initEvent: 'place',
        endEvent: 'unplace',
        // common
        img: null,
        element: null,
        text: null,
        at: 'top left',
        offset: null,
        time: null,
        effect: null,
        // occasional
        classname: 'place',
        imgClass: 'place-img',
        elementClass: 'place-element',
        textClass: 'place-text',
        css: { position:'absolute', whiteSpace:'nowrap', zIndex:1001 },
        cloneEvents: true,
        elementCss: { position:'relative', left:0, top:0 },
        // rare
        html: '<div/>',
        imgHtml: '<img class="place-img-content"/>',
        textHtml: '<span class="place-text-content"></span>',
        resizeEvents: 'resize',
        pageOptions: { at:'top center' }
    });

    // all that's extensible...
    $.extend(true, $.fn.place, $.fn.measure, {//include $.fn.measure functions
        // functions
        init: function(o) {
            F.initSelf.call(this, o);
            if (o.img) F.initImg.call(this, o);
            if (o.text) F.initText.call(this, o);
            if (o.element) F.initElement.call(this, o);
            if (o.time) o.timeout = setTimeout(function(){ o.self.trigger(o.endEvent, [o]); }, o.time);
            if (o.effect && $.pulse) {
                this.bind($.pulse.updateEvent, o.replacer = function(){ F.replace(o); });
            }
            $(window).bind(o.resizeEvents, o.resizer = function() { F.resize(o); });
            o.ready = function(){ o.self.trigger(o.initEvent, [o]); };
            if (!o.imgLoading) o.ready();
        },
        initSelf: function(o) {
            if (o.img || o.text || o.element) {
                o.parent = this;
            } else {
                o.parent = o.into ? $(o.into) : this.parent();
                o.placeholder = $('<div/>');
                o.element = this.replaceWith(o.placeholder);
            }
            o.self = $(o.html).hide().addClass(o.classname).css(o.css).appendTo(o.parent);
        },
        initImg: function(o) {
            // be sure to wait for img to load
            o.imgContent = $(o.imgHtml).one('load', o.imgLoading = function() {
                o.imgLoading = null;
                if (o.ready) o.ready();
            }).attr('src', o.img);
            o.self.addClass(o.imgClass).append(o.imgContent);
        },
        initText: function(o) {
            var txt = $.txt ? $.txt(o.text) : o.text;
            //TODO: support txt filling
            o.textContent = $(o.textHtml).text(txt);
            o.self.addClass(o.textClass).append(o.textContent);
        },
        initElement: function(o) {
            o.elementContent = $(o.element).clone(o.cloneEvents).css(o.elementCss).show();
            o.self.addClass(o.elementClass).append(o.elementContent);
        },
        resize: function(o) {
            o.parent.box = null;
            F.place.call(o.self.hide(), o);
        },
        replace: function(o) {
            o.self.box = null;
            F.place.call(o.self, o, true);
        },
        remove: function(o) {
            this.remove();
        },
        place: function(o, recalc) {
            var at = F.split(o.at), v = 'top', h = 'left';
            if (at) {
                v = at[0] || v;
                h = at[1] || h;
            }
            if (!this.hasClass(v)) this.addClass(v);
            if (!this.hasClass(h)) this.addClass(h);
            o.box = F.calc.call(this, v, h, o);
            this.show().css(o.box);
            if (!recalc && o.effect) this.pulse(o.effect, o);
        },
        unplace: function(o) {
            this.data('place', null);
            if (o.effect && $.pulse) {
                this.unbind($.pulse.updateEvent, o.replacer);
                this.pulse(false);
            }
            if (o.timeout) clearTimeout(o.timeout);
            $(window).unbind(o.resizeEvents, o.resizer);
            F.remove.call(this, o);
            if (o.placeholder) o.placeholder.replaceWith(o.element);
        },
        split: function(s) {
            if (!s) return s;
            if (typeof s === "string") s = $.trim(s).split(' ');
            if (s.length > 1 && !s[1]) s.splice(1, 1);
            if (s.length > 0 && !s[0]) s.splice(0, 1);
            if (s.length == 1) s = [s[0], s[0]];
            return s.length > 1 ? s : null;
        },
        sides:{//before      start     middle      end         after        length measure
            v: { b:'above',  s:'top',  m:'center', e:'bottom', a:'below', l:'height', il:'innerHeight' },
            h: { b:'before', s:'left', m:'center', e:'right',  a:'after', l:'width',  il:'innerWidth' }
        },
        calcSide: function(pbox, box, off, S, p, o) {//parent box, self box, offset side, place, opts
            var l, d;//length, delta
            if ($.boxModel) {
                l = box[S.l] = this[S.l]();
                l += css(this, 'padding-'+S.s) + css(this, 'padding-'+S.e);
            } else {
                l = box[S.l] = this[S.il]();//use inner length
            }
            if (p == S.s) {
                d = 0;//start
            } else if (p == S.b) {
                d = -1 * l;//precede
            } else if (p == S.a) {
                d = pbox[S.l];//follow
            } else if (p == S.m) {
                d = (pbox[S.l] - l)/2;//center
            } else if (p == S.e) {
                d = pbox[S.l] - l;//end
            } else {
                d = F.parse(p, pbox[S.l], l);//percent of parent or specific value
            }
            if (off) {
                d += F.parse(off, l, 0);//adjust by percent of self or specific value
            }
            box[S.s] += d;//adjust start point for self in parent
        },
        calc: function(v, h, o) {
            var pbox = F.measure.call(o.parent, o),
                box = $.extend({}, pbox),
                off = F.split(o.offset) || [0,0];
            F.calcSide.call(this, pbox, box, off[0], F.sides.v, v, o);
            F.calcSide.call(this, pbox, box, off[1], F.sides.h, h, o);
            if (!box.height) delete box.height;
            if (!box.width) delete box.width;
            return box;
        },
        parse: function(p, L, l) {
            if (typeof p !== "string") return p;
            try {
                if (p.charAt(p.length-1) === '%') {
                    // primary length times percent minus half of self length
                    return Math.round(L * (parseFloat(p, 10)/100) - l / 2);//percentage
                }
                return parseInt(p, 10);//specific value
            } catch (e) {
                return 0;
            }
        },
        toOpts: function(show, el, o) {
            if (typeof show != "boolean") { o = el; el = show; show = undefined; }
            if ($.isPlainObject(el)) {
                o = el; el = null;
            }
            if (!o) o = {};
            if (show !== undefined) o.show = show;
            if (el) {
                if (el.nodeType || el.jquery) {
                    o.element = el;
                } else if (typeof el == "string") {
                    var $el = $(el);
                    if ($el.length > 0) {
                        o.element = $el;
                    } else {
                        o.text = el;
                    }
                }
            }
            return o;
        }
    });
    css = F.getCss;//convenience alias

    // bind global event handlers
    $(document).bind(O.initEvent, function(e, o) {
        if (o.show === false) {// allow event to be cancelled
            o.self.trigger(O.endEvent, [o]);
        } else {
            F.place.call(o.self, o);
        }
    }).bind(O.endEvent, function(e, o) {
        if (!o) o = $(e.target).data('place');
        if (o && o.show !== true) {// allow event to be cancelled
            F.unplace.call(o.self, o);
        }
    });

})(jQuery);

