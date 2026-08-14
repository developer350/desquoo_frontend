// =======================
// 1. Smooth Scroll Polyfill 
// =======================
!(function () {
    var e,
        t,
        o,
        r,
        l = {
            frameRate: 150,
            animationTime: 400,
            stepSize: 100,
            pulseAlgorithm: !0,
            pulseScale: 4,
            pulseNormalize: 1,
            accelerationDelta: 50,
            accelerationMax: 3,
            keyboardSupport: !0,
            arrowScroll: 50,
            fixedBackground: !0,
            excluded: "",
        },
        n = l,
        a = !1,
        i = {
            x: 0,
            y: 0,
        },
        s = !1,
        c = document.documentElement,
        u = [],
        d = /^Mac/.test(navigator.platform),
        f = {
            left: 37,
            up: 38,
            right: 39,
            down: 40,
            spacebar: 32,
            pageup: 33,
            pagedown: 34,
            end: 35,
            home: 36,
        },
        h = {
            37: 1,
            38: 1,
            39: 1,
            40: 1,
        };

    function p() {
        if (!s && document.body) {
            s = !0;
            var r = document.body,
                l = document.documentElement,
                i = window.innerHeight,
                u = r.scrollHeight;
            if (
                ((c = 0 <= document.compatMode.indexOf("CSS") ? l : r),
                    (e = r),
                    n.keyboardSupport && z("keydown", w),
                    top != self)
            )
                a = !0;
            else if (
                Z &&
                i < u &&
                (r.offsetHeight <= i || l.offsetHeight <= i)
            ) {
                var d,
                    f = document.createElement("div");
                if (
                    ((f.style.cssText =
                        "position:absolute; z-index:-10000; top:0; left:0; right:0; height:" +
                        c.scrollHeight +
                        "px"),
                        document.body.appendChild(f),
                        (o = function () {
                            d =
                                d ||
                                setTimeout(function () {
                                    (f.style.height = "0"),
                                        (f.style.height = c.scrollHeight + "px"),
                                        (d = null);
                                }, 500);
                        }),
                        setTimeout(o, 10),
                        z("resize", o),
                        (t = new R(o)).observe(r, {
                            attributes: !0,
                            childList: !0,
                            characterData: !1,
                        }),
                        c.offsetHeight <= i)
                ) {
                    var h = document.createElement("div");
                    (h.style.clear = "both"), r.appendChild(h);
                }
            }
            n.fixedBackground ||
                ((r.style.backgroundAttachment = "scroll"),
                    (l.style.backgroundAttachment = "scroll"));
        }
    }
    var m = [],
        v = !1,
        _ = Date.now();

    function y(e, t, o) {
        var r, l;
        if (
            ((r = 0 < (r = t) ? 1 : -1),
                (l = 0 < (l = o) ? 1 : -1),
                (i.x === r && i.y === l) ||
                ((i.x = r), (i.y = l), (m = []), (_ = 0)),
                1 != n.accelerationMax)
        ) {
            var a = Date.now() - _;
            if (a < n.accelerationDelta) {
                var s = (1 + 50 / a) / 2;
                1 < s && ((t *= s = Math.min(s, n.accelerationMax)), (o *= s));
            }
            _ = Date.now();
        }
        if (
            (m.push({
                x: t,
                y: o,
                lastX: t < 0 ? 0.99 : -0.99,
                lastY: o < 0 ? 0.99 : -0.99,
                start: Date.now(),
            }),
                !v)
        ) {
            var c = e === q() || e === document.body;
            null == e.$scrollBehavior &&
                (function (e) {
                    var t = x(e);
                    if (null == D[t]) {
                        var o = getComputedStyle(e, "")["scroll-behavior"];
                        D[t] = "smooth" == o;
                    }
                    return D[t];
                })(e) &&
                ((e.$scrollBehavior = e.style.scrollBehavior),
                    (e.style.scrollBehavior = "auto"));
            var u = function (r) {
                for (
                    var l = Date.now(), a = 0, i = 0, s = 0;
                    s < m.length;
                    s++
                ) {
                    var d = m[s],
                        f = l - d.start,
                        h = f >= n.animationTime,
                        p = h ? 1 : f / n.animationTime;
                    n.pulseAlgorithm && (p = V(p));
                    var _ = (d.x * p - d.lastX) >> 0,
                        y = (d.y * p - d.lastY) >> 0;
                    (a += _),
                        (i += y),
                        (d.lastX += _),
                        (d.lastY += y),
                        h && (m.splice(s, 1), s--);
                }
                c
                    ? window.scrollBy(a, i)
                    : (a && (e.scrollLeft += a), i && (e.scrollTop += i)),
                    t || o || (m = []),
                    m.length
                        ? I(u, e, 1e3 / n.frameRate + 1)
                        : ((v = !1),
                            null != e.$scrollBehavior &&
                            ((e.style.scrollBehavior = e.$scrollBehavior),
                                (e.$scrollBehavior = null)));
            };
            I(u, e, 0), (v = !0);
        }
    }

    function b(t) {
        s || p();
        var o = t.target;
        if (
            t.defaultPrevented ||
            t.ctrlKey ||
            X(e, "embed") ||
            (X(o, "embed") && /\.pdf/i.test(o.src)) ||
            X(e, "object") ||
            o.shadowRoot
        )
            return !0;
        var l = -t.wheelDeltaX || t.deltaX || 0,
            i = -t.wheelDeltaY || t.deltaY || 0;
        d &&
            (t.wheelDeltaX &&
                N(t.wheelDeltaX, 120) &&
                (l = -((t.wheelDeltaX / Math.abs(t.wheelDeltaX)) * 120)),
                t.wheelDeltaY &&
                N(t.wheelDeltaY, 120) &&
                (i = -((t.wheelDeltaY / Math.abs(t.wheelDeltaY)) * 120))),
            l || i || (i = -t.wheelDelta || 0),
            1 === t.deltaMode && ((l *= 40), (i *= 40));
        var c = T(o);
        return c
            ? !!(function (e) {
                if (e) {
                    u.length || (u = [e, e, e]),
                        (e = Math.abs(e)),
                        u.push(e),
                        u.shift(),
                        clearTimeout(r),
                        (r = setTimeout(function () {
                            try {
                                localStorage.SS_deltaBuffer = u.join(",");
                            } catch (e) { }
                        }, 1e3));
                    var t = 120 < e && P(e),
                        o = !P(120) && !P(100) && !t;
                    return e < 50 || o;
                }
            })(i) ||
            (1.2 < Math.abs(l) && (l *= n.stepSize / 120),
                1.2 < Math.abs(i) && (i *= n.stepSize / 120),
                y(c, l, i),
                t.preventDefault(),
                void C())
            : !a ||
            !U ||
            (Object.defineProperty(t, "target", {
                value: window.frameElement,
            }),
                parent.wheel(t));
    }

    function w(t) {
        var o = t.target,
            r =
                t.ctrlKey ||
                t.altKey ||
                t.metaKey ||
                (t.shiftKey && t.keyCode !== f.spacebar);
        document.body.contains(e) || (e = document.activeElement);
        var l = /^(button|submit|radio|checkbox|file|color|image)$/i;
        if (
            t.defaultPrevented ||
            /^(textarea|select|embed|object)$/i.test(o.nodeName) ||
            (X(o, "input") && !l.test(o.type)) ||
            X(e, "video") ||
            (function (e) {
                var t = e.target,
                    o = !1;
                if (-1 != document.URL.indexOf("www.youtube.com/watch"))
                    do
                        if (
                            (o =
                                t.classList &&
                                t.classList.contains("html5-video-controls"))
                        )
                            break;
                    while ((t = t.parentNode));
                return o;
            })(t) ||
            o.isContentEditable ||
            r ||
            ((X(o, "button") || (X(o, "input") && l.test(o.type))) &&
                t.keyCode === f.spacebar) ||
            (X(o, "input") && "radio" == o.type && h[t.keyCode])
        )
            return !0;
        var i = 0,
            s = 0,
            c = T(e);
        if (!c) return !a || !U || parent.keydown(t);
        var u = c.clientHeight;
        switch ((c == document.body && (u = window.innerHeight), t.keyCode)) {
            case f.up:
                s = -n.arrowScroll;
                break;
            case f.down:
                s = n.arrowScroll;
                break;
            case f.spacebar:
                s = -(t.shiftKey ? 1 : -1) * u * 0.9;
                break;
            case f.pageup:
                s = -(0.9 * u);
                break;
            case f.pagedown:
                s = 0.9 * u;
                break;
            case f.home:
                c == document.body &&
                    document.scrollingElement &&
                    (c = document.scrollingElement),
                    (s = -c.scrollTop);
                break;
            case f.end:
                var d = c.scrollHeight - c.scrollTop - u;
                s = 0 < d ? 10 + d : 0;
                break;
            case f.left:
                i = -n.arrowScroll;
                break;
            case f.right:
                i = n.arrowScroll;
                break;
            default:
                return !0;
        }
        y(c, i, s), t.preventDefault(), C();
    }

    function g(t) {
        e = t.target;
    }
    var S,
        k,
        x =
            ((S = 0),
                function (e) {
                    return e.uniqueID || (e.uniqueID = S++);
                }),
        E = {},
        B = {},
        D = {};

    function C() {
        clearTimeout(k),
            (k = setInterval(function () {
                E = B = D = {};
            }, 1e3));
    }

    function H(e, t, o) {
        for (var r = o ? E : B, l = e.length; l--;) r[x(e[l])] = t;
        return t;
    }

    function T(e) {
        var t = [],
            o = document.body,
            r = c.scrollHeight;
        do {
            var l = B[x(e)];
            if (l) return H(t, l);
            if ((t.push(e), r === e.scrollHeight)) {
                var n = (M(c) && M(o)) || Y(c);
                if ((a && L(c)) || (!a && n)) return H(t, q());
            } else if (L(e) && Y(e)) return H(t, e);
        } while ((e = e.parentElement));
    }

    function L(e) {
        return e.clientHeight + 10 < e.scrollHeight;
    }

    function M(e) {
        return (
            "hidden" !== getComputedStyle(e, "").getPropertyValue("overflow-y")
        );
    }

    function Y(e) {
        var t = getComputedStyle(e, "").getPropertyValue("overflow-y");
        return "scroll" === t || "auto" === t;
    }

    function z(e, t, o) {
        window.addEventListener(e, t, o || !1);
    }

    function O(e, t, o) {
        window.removeEventListener(e, t, o || !1);
    }

    function X(e, t) {
        return e && (e.nodeName || "").toLowerCase() === t.toLowerCase();
    }
    if (window.localStorage && localStorage.SS_deltaBuffer)
        try {
            u = localStorage.SS_deltaBuffer.split(",");
        } catch (A) { }

    function N(e, t) {
        return Math.floor(e / t) == e / t;
    }

    function P(e) {
        return N(u[0], e) && N(u[1], e) && N(u[2], e);
    }
    var K,
        I =
            window.requestAnimationFrame ||
            window.webkitRequestAnimationFrame ||
            window.mozRequestAnimationFrame ||
            function (e, t, o) {
                window.setTimeout(e, o || 1e3 / 60);
            },
        R =
            window.MutationObserver ||
            window.WebKitMutationObserver ||
            window.MozMutationObserver,
        q =
            ((K = document.scrollingElement),
                function () {
                    if (!K) {
                        var e = document.createElement("div");
                        (e.style.cssText = "height:10000px;width:1px;"),
                            document.body.appendChild(e);
                        var t = document.body.scrollTop;
                        document.documentElement.scrollTop,
                            window.scrollBy(0, 3),
                            (K =
                                document.body.scrollTop != t
                                    ? document.body
                                    : document.documentElement),
                            window.scrollBy(0, -3),
                            document.body.removeChild(e);
                    }
                    return K;
                });

    function j(e) {
        var t;
        return (
            ((e *= n.pulseScale) < 1
                ? e - (1 - Math.exp(-e))
                : ((e -= 1),
                    (t = Math.exp(-1)) + (1 - Math.exp(-e)) * (1 - t))) *
            n.pulseNormalize
        );
    }

    function V(e) {
        return 1 <= e
            ? 1
            : e <= 0
                ? 0
                : (1 == n.pulseNormalize && (n.pulseNormalize /= j(1)), j(e));
    }
    var F = window.navigator.userAgent,
        W = /Edge/.test(F),
        U = /chrome/i.test(F) && !W,
        G = /safari/i.test(F) && !W,
        J = /mobile/i.test(F),
        Q = /Windows NT 6.1/i.test(F) && /rv:11/i.test(F),
        Z = G && (/Version\/8/i.test(F) || /Version\/9/i.test(F)),
        ee = !1;
    try {
        window.addEventListener(
            "test",
            null,
            Object.defineProperty({}, "passive", {
                get: function () {
                    ee = !0;
                },
            })
        );
    } catch (et) { }
    var eo = !!ee && {
        passive: !1,
    },
        er =
            "onwheel" in document.createElement("div") ? "wheel" : "mousewheel";

    function el(e) {
        for (var t in e) l.hasOwnProperty(t) && (n[t] = e[t]);
    }
    er &&
        (U || G || Q) &&
        !J &&
        (z(er, b, eo), z("mousedown", g), z("load", p)),
        (el.destroy = function () {
            t && t.disconnect(),
                O(er, b),
                O("mousedown", g),
                O("keydown", w),
                O("resize", o),
                O("load", p);
        }),
        window.SmoothScrollOptions && el(window.SmoothScrollOptions),
        "function" == typeof define && define.amd
            ? define(function () {
                return el;
            })
            : "object" == typeof exports
                ? (module.exports = el)
                : (window.SmoothScroll = el);
})();

// =======================
// 2. Sticky Header on Scroll
// =======================
window.onscroll = function () {
    myFunction();
};
var header = document.getElementById("Header");
var sticky = 100;

function myFunction() {
    if (window.pageYOffset >= sticky) {
        header.classList.add("StickY");
    } else {
        header.classList.remove("StickY");
    }
}

// =======================
// 3. Branding Message
// =======================
var str = "InterSmart Solutions https://www.intersmartsolution.com";
console.log("Powered by " + str);


// =======================
// 4. Menu open
// =======================

$("#menuOpen").on("click", function () {
    $("body").toggleClass("MenuOpen");
    $(".MenuBtn").toggleClass("open");
});

$("#menuClose").on("click", function () {
    $("body").removeClass("MenuOpen");
    $(".MenuBtn").removeClass("open");
});