/*! modernizr 3.3.1 (Custom Build) | MIT *
 * https://modernizr.com/download/?-boxsizing-cssanimations-csscalc-cssgradients-csstransforms-csstransforms3d-csstransitions-preserve3d-svg-svgasimg-svgclippaths-svgfilters-touchevents-setclasses !*/
! function(e, t, n) {
    function r(e, t) {
        return typeof e === t
    }

    function o() {
        var e, t, n, o, i, s, a;
        for (var l in x)
            if (x.hasOwnProperty(l)) {
                if (e = [], t = x[l], t.name && (e.push(t.name.toLowerCase()), t.options && t.options.aliases && t.options.aliases.length))
                    for (n = 0; n < t.options.aliases.length; n++) e.push(t.options.aliases[n].toLowerCase());
                for (o = r(t.fn, "function") ? t.fn() : t.fn, i = 0; i < e.length; i++) s = e[i], a = s.split("."), 1 === a.length ? Modernizr[a[0]] = o : (!Modernizr[a[0]] || Modernizr[a[0]] instanceof Boolean || (Modernizr[a[0]] = new Boolean(Modernizr[a[0]])), Modernizr[a[0]][a[1]] = o), w.push((o ? "" : "no-") + a.join("-"))
            }
    }

    function i(e) {
        var t = T.className,
            n = Modernizr._config.classPrefix || "";
        if (_ && (t = t.baseVal), Modernizr._config.enableJSClass) {
            var r = new RegExp("(^|\\s)" + n + "no-js(\\s|$)");
            t = t.replace(r, "$1" + n + "js$2")
        }
        Modernizr._config.enableClasses && (t += " " + n + e.join(" " + n), _ ? T.className.baseVal = t : T.className = t)
    }

    function s() {
        return "function" != typeof t.createElement ? t.createElement(arguments[0]) : _ ? t.createElementNS.call(t, "http://www.w3.org/2000/svg", arguments[0]) : t.createElement.apply(t, arguments)
    }

    function a(e, t) {
        if ("object" == typeof e)
            for (var n in e) z(e, n) && a(n, e[n]);
        else {
            e = e.toLowerCase();
            var r = e.split("."),
                o = Modernizr[r[0]];
            if (2 == r.length && (o = o[r[1]]), "undefined" != typeof o) return Modernizr;
            t = "function" == typeof t ? t() : t, 1 == r.length ? Modernizr[r[0]] = t : (!Modernizr[r[0]] || Modernizr[r[0]] instanceof Boolean || (Modernizr[r[0]] = new Boolean(Modernizr[r[0]])), Modernizr[r[0]][r[1]] = t), i([(t && 0 != t ? "" : "no-") + r.join("-")]), Modernizr._trigger(e, t)
        }
        return Modernizr
    }

    function l() {
        var e = t.body;
        return e || (e = s(_ ? "svg" : "body"), e.fake = !0), e
    }

    function f(e, n, r, o) {
        var i, a, f, u, d = "modernizr",
            c = s("div"),
            p = l();
        if (parseInt(r, 10))
            for (; r--;) f = s("div"), f.id = o ? o[r] : d + (r + 1), c.appendChild(f);
        return i = s("style"), i.type = "text/css", i.id = "s" + d, (p.fake ? p : c).appendChild(i), p.appendChild(c), i.styleSheet ? i.styleSheet.cssText = e : i.appendChild(t.createTextNode(e)), c.id = d, p.fake && (p.style.background = "", p.style.overflow = "hidden", u = T.style.overflow, T.style.overflow = "hidden", T.appendChild(p)), a = n(c, e), p.fake ? (p.parentNode.removeChild(p), T.style.overflow = u, T.offsetHeight) : c.parentNode.removeChild(c), !!a
    }

    function u(e, t) {
        return !!~("" + e).indexOf(t)
    }

    function d(e) {
        return e.replace(/([a-z])-([a-z])/g, function(e, t, n) {
            return t + n.toUpperCase()
        }).replace(/^-/, "")
    }

    function c(e, t) {
        return function() {
            return e.apply(t, arguments)
        }
    }

    function p(e, t, n) {
        var o;
        for (var i in e)
            if (e[i] in t) return n === !1 ? e[i] : (o = t[e[i]], r(o, "function") ? c(o, n || t) : o);
        return !1
    }

    function h(e) {
        return e.replace(/([A-Z])/g, function(e, t) {
            return "-" + t.toLowerCase()
        }).replace(/^ms-/, "-ms-")
    }

    function m(t, r) {
        var o = t.length;
        if ("CSS" in e && "supports" in e.CSS) {
            for (; o--;)
                if (e.CSS.supports(h(t[o]), r)) return !0;
            return !1
        }
        if ("CSSSupportsRule" in e) {
            for (var i = []; o--;) i.push("(" + h(t[o]) + ":" + r + ")");
            return i = i.join(" or "), f("@supports (" + i + ") { #modernizr { position: absolute; } }", function(e) {
                return "absolute" == getComputedStyle(e, null).position
            })
        }
        return n
    }

    function g(e, t, o, i) {
        function a() {
            f && (delete R.style, delete R.modElem)
        }
        if (i = r(i, "undefined") ? !1 : i, !r(o, "undefined")) {
            var l = m(e, o);
            if (!r(l, "undefined")) return l
        }
        for (var f, c, p, h, g, v = ["modernizr", "tspan", "samp"]; !R.style && v.length;) f = !0, R.modElem = s(v.shift()), R.style = R.modElem.style;
        for (p = e.length, c = 0; p > c; c++)
            if (h = e[c], g = R.style[h], u(h, "-") && (h = d(h)), R.style[h] !== n) {
                if (i || r(o, "undefined")) return a(), "pfx" == t ? h : !0;
                try {
                    R.style[h] = o
                } catch (y) {}
                if (R.style[h] != g) return a(), "pfx" == t ? h : !0
            }
        return a(), !1
    }

    function v(e, t, n, o, i) {
        var s = e.charAt(0).toUpperCase() + e.slice(1),
            a = (e + " " + j.join(s + " ") + s).split(" ");
        return r(t, "string") || r(t, "undefined") ? g(a, t, o, i) : (a = (e + " " + A.join(s + " ") + s).split(" "), p(a, t, n))
    }

    function y(e, t, r) {
        return v(e, n, n, t, r)
    }
    var w = [],
        x = [],
        S = {
            _version: "3.3.1",
            _config: {
                classPrefix: "",
                enableClasses: !0,
                enableJSClass: !0,
                usePrefixes: !0
            },
            _q: [],
            on: function(e, t) {
                var n = this;
                setTimeout(function() {
                    t(n[e])
                }, 0)
            },
            addTest: function(e, t, n) {
                x.push({
                    name: e,
                    fn: t,
                    options: n
                })
            },
            addAsyncTest: function(e) {
                x.push({
                    name: null,
                    fn: e
                })
            }
        },
        Modernizr = function() {};
    Modernizr.prototype = S, Modernizr = new Modernizr, Modernizr.addTest("svgfilters", function() {
        var t = !1;
        try {
            t = "SVGFEColorMatrixElement" in e && 2 == SVGFEColorMatrixElement.SVG_FECOLORMATRIX_TYPE_SATURATE
        } catch (n) {}
        return t
    });
    var T = t.documentElement,
        _ = "svg" === T.nodeName.toLowerCase(),
        C = S._config.usePrefixes ? " -webkit- -moz- -o- -ms- ".split(" ") : ["", ""];
    S._prefixes = C, Modernizr.addTest("csscalc", function() {
        var e = "width:",
            t = "calc(10px);",
            n = s("a");
        return n.style.cssText = e + C.join(t + e), !!n.style.length
    }), Modernizr.addTest("cssgradients", function() {
        for (var e, t = "background-image:", n = "gradient(linear,left top,right bottom,from(#9f9),to(white));", r = "", o = 0, i = C.length - 1; i > o; o++) e = 0 === o ? "to " : "", r += t + C[o] + "linear-gradient(" + e + "left top, #9f9, white);";
        Modernizr._config.usePrefixes && (r += t + "-webkit-" + n);
        var a = s("a"),
            l = a.style;
        return l.cssText = r, ("" + l.backgroundImage).indexOf("gradient") > -1
    }), Modernizr.addTest("preserve3d", function() {
        var e = s("a"),
            t = s("a");
        e.style.cssText = "display: block; transform-style: preserve-3d; transform-origin: right; transform: rotateY(40deg);", t.style.cssText = "display: block; width: 9px; height: 1px; background: #000; transform-origin: right; transform: rotateY(40deg);", e.appendChild(t), T.appendChild(e);
        var n = t.getBoundingClientRect();
        return T.removeChild(e), n.width && n.width < 4
    });
    var b = "CSS" in e && "supports" in e.CSS,
        E = "supportsCSS" in e;
    Modernizr.addTest("supports", b || E);
    var P = {}.toString;
    Modernizr.addTest("svgclippaths", function() {
        return !!t.createElementNS && /SVGClipPath/.test(P.call(t.createElementNS("http://www.w3.org/2000/svg", "clipPath")))
    });
    var z;
    ! function() {
        var e = {}.hasOwnProperty;
        z = r(e, "undefined") || r(e.call, "undefined") ? function(e, t) {
            return t in e && r(e.constructor.prototype[t], "undefined")
        } : function(t, n) {
            return e.call(t, n)
        }
    }(), S._l = {}, S.on = function(e, t) {
        this._l[e] || (this._l[e] = []), this._l[e].push(t), Modernizr.hasOwnProperty(e) && setTimeout(function() {
            Modernizr._trigger(e, Modernizr[e])
        }, 0)
    }, S._trigger = function(e, t) {
        if (this._l[e]) {
            var n = this._l[e];
            setTimeout(function() {
                var e, r;
                for (e = 0; e < n.length; e++)(r = n[e])(t)
            }, 0), delete this._l[e]
        }
    }, Modernizr._q.push(function() {
        S.addTest = a
    }), Modernizr.addTest("svgasimg", t.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image", "1.1"));
    var k = S.testStyles = f;
    Modernizr.addTest("touchevents", function() {
        var n;
        if ("ontouchstart" in e || e.DocumentTouch && t instanceof DocumentTouch) n = !0;
        else {
            var r = ["@media (", C.join("touch-enabled),("), "heartz", ")", "{#modernizr{top:9px;position:absolute}}"].join("");
            k(r, function(e) {
                n = 9 === e.offsetTop
            })
        }
        return n
    });
    var N = "Moz O ms Webkit",
        j = S._config.usePrefixes ? N.split(" ") : [];
    S._cssomPrefixes = j;
    var A = S._config.usePrefixes ? N.toLowerCase().split(" ") : [];
    S._domPrefixes = A;
    var O = {
        elem: s("modernizr")
    };
    Modernizr._q.push(function() {
        delete O.elem
    });
    var R = {
        style: O.elem.style
    };
    Modernizr._q.unshift(function() {
        delete R.style
    }), S.testAllProps = v, S.testAllProps = y, Modernizr.addTest("cssanimations", y("animationName", "a", !0)), Modernizr.addTest("boxsizing", y("boxSizing", "border-box", !0) && (t.documentMode === n || t.documentMode > 7)), Modernizr.addTest("csstransforms", function() {
        return -1 === navigator.userAgent.indexOf("Android 2.") && y("transform", "scale(1)", !0)
    }), Modernizr.addTest("csstransforms3d", function() {
        var e = !!y("perspective", "1px", !0),
            t = Modernizr._config.usePrefixes;
        if (e && (!t || "webkitPerspective" in T.style)) {
            var n, r = "#modernizr{width:0;height:0}";
            Modernizr.supports ? n = "@supports (perspective: 1px)" : (n = "@media (transform-3d)", t && (n += ",(-webkit-transform-3d)")), n += "{#modernizr{width:7px;height:18px;margin:0;padding:0;border:0}}", k(r + n, function(t) {
                e = 7 === t.offsetWidth && 18 === t.offsetHeight
            })
        }
        return e
    }), Modernizr.addTest("csstransitions", y("transition", "all", !0)), Modernizr.addTest("svg", !!t.createElementNS && !!t.createElementNS("http://www.w3.org/2000/svg", "svg").createSVGRect), o(), i(w), delete S.addTest, delete S.addAsyncTest;
    for (var V = 0; V < Modernizr._q.length; V++) Modernizr._q[V]();
    e.Modernizr = Modernizr
}(window, document); 