(window.webpackJsonp = window.webpackJsonp || []).push([
    [3], { "1Iqk": function(t, e, n) { "use strict";

            function r(t, e) { var n = Object.keys(t); if (Object.getOwnPropertySymbols) { var r = Object.getOwnPropertySymbols(t);
                    e && (r = r.filter((function(e) { return Object.getOwnPropertyDescriptor(t, e).enumerable }))), n.push.apply(n, r) } return n }

            function a(t) { for (var e = 1; e < arguments.length; e++) { var n = null != arguments[e] ? arguments[e] : {};
                    e % 2 ? r(Object(n), !0).forEach((function(e) { i(t, e, n[e]) })) : Object.getOwnPropertyDescriptors ? Object.defineProperties(t, Object.getOwnPropertyDescriptors(n)) : r(Object(n)).forEach((function(e) { Object.defineProperty(t, e, Object.getOwnPropertyDescriptor(n, e)) })) } return t }

            function i(t, e, n) { return e in t ? Object.defineProperty(t, e, { value: n, enumerable: !0, configurable: !0, writable: !0 }) : t[e] = n, t }

            function o(t, e) { if (null == t) return {}; var n, r, a = function(t, e) { if (null == t) return {}; var n, r, a = {},
                        i = Object.keys(t); for (r = 0; r < i.length; r++) n = i[r], e.indexOf(n) >= 0 || (a[n] = t[n]); return a }(t, e); if (Object.getOwnPropertySymbols) { var i = Object.getOwnPropertySymbols(t); for (r = 0; r < i.length; r++) n = i[r], e.indexOf(n) >= 0 || Object.prototype.propertyIsEnumerable.call(t, n) && (a[n] = t[n]) } return a }
            e.a = function() { var t = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {},
                    e = arguments.length > 1 ? arguments[1] : void 0,
                    n = t; if (Object.keys(t).includes("filter")) { var r = t.filter,
                        i = o(t, ["filter"]);
                    n = a(a({}, r), i) } var s = [];
                e && s.push("page=" + (n.currentPage ? n.currentPage : 1)), delete n.currentPage; var c = function(t) { if (null === n[t] || void 0 === n[t]) return "continue";
                    Array.isArray(n[t]) ? n[t].forEach((function(e) { s.push(encodeURIComponent(t) + "[]=" + encodeURIComponent(e)) })) : s.push(encodeURIComponent(t) + "=" + encodeURIComponent(n[t])) }; for (var u in n) c(u); return s.join("&") } }, "3+/6": function(t, e, n) { "use strict";
            n.d(e, "a", (function() { return i })); var r = n("1Iqk"),
                a = n("gJEe");

            function i(t) { return window.axios.get("".concat("/api/users", "?").concat(Object(r.a)(t))).then((function(t) { return t.data.data = t.data.data.map((function(t) { return new a.a(t) })), t })) } }, "KHd+": function(t, e, n) { "use strict";

            function r(t, e, n, r, a, i, o, s) { var c, u = "function" == typeof t ? t.options : t; if (e && (u.render = e, u.staticRenderFns = n, u._compiled = !0), r && (u.functional = !0), i && (u._scopeId = "data-v-" + i), o ? (c = function(t) {
                        (t = t || this.$vnode && this.$vnode.ssrContext || this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) || "undefined" == typeof __VUE_SSR_CONTEXT__ || (t = __VUE_SSR_CONTEXT__), a && a.call(this, t), t && t._registeredComponents && t._registeredComponents.add(o) }, u._ssrRegister = c) : a && (c = s ? function() { a.call(this, (u.functional ? this.parent : this).$root.$options.shadowRoot) } : a), c)
                    if (u.functional) { u._injectStyles = c; var l = u.render;
                        u.render = function(t, e) { return c.call(e), l(t, e) } } else { var p = u.beforeCreate;
                        u.beforeCreate = p ? [].concat(p, c) : [c] }
                return { exports: t, options: u } }
            n.d(e, "a", (function() { return r })) }, t2Tq: function(t, e, n) { "use strict";
            n.r(e); var r = n("o0o1"),
                a = n.n(r),
                i = n("3+/6"),
                o = n("f0Wu"),
                s = n.n(o),
                c = { props: { title: { type: String, required: !1 }, value: { required: !0 } } },
                u = n("KHd+");

            function l(t, e, n, r, a, i, o) { try { var s = t[i](o),
                        c = s.value } catch (t) { return void n(t) }
                s.done ? e(c) : Promise.resolve(c).then(r, a) }

            function p(t) { return function() { var e = this,
                        n = arguments; return new Promise((function(r, a) { var i = t.apply(e, n);

                        function o(t) { l(i, r, a, o, s, "next", t) }

                        function s(t) { l(i, r, a, o, s, "throw", t) }
                        o(void 0) })) } } var f = { components: { MetricBox: Object(u.a)(c, (function() { var t = this.$createElement,
                                e = this._self._c || t; return e("div", { staticClass: "metric-box" }, [e("h3", { attrs: { scope: "heading" } }, [this._v(this._s(this.title))]), this._v(" "), e("div", { staticClass: "metric" }, [this._v(this._s(this.value))])]) }), [], !1, null, null, null).exports }, data: function() { return { sevenDayLogins: null, thirtyDayLogins: null, neverLoggedIn: null, sevenDayApplications: null, thirtyDayApplications: null, now: new Date, aug6: new Date(2020, 8, 6), aug29: new Date(2020, 8, 29) } }, methods: { getNeverLoggedIn: function() { var t = this; return p(a.a.mark((function e() { return a.a.wrap((function(e) { for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return e.next = 2, Object(i.a)({ last_logged_in_at: 0 }).then((function(t) { return t.data.data.length }));
                                        case 2:
                                            t.neverLoggedIn = e.sent;
                                        case 3:
                                        case "end":
                                            return e.stop() } }), e) })))() }, getSevenDayLogins: function() { var t = this; return p(a.a.mark((function e() { return a.a.wrap((function(e) { for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return e.next = 2, t.getLoginsInLast(7, "day");
                                        case 2:
                                            t.sevenDayLogins = e.sent;
                                        case 3:
                                        case "end":
                                            return e.stop() } }), e) })))() }, getThirtyDayLogins: function() { var t = this; return p(a.a.mark((function e() { return a.a.wrap((function(e) { for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return e.next = 2, t.getLoginsInLast(30, "day");
                                        case 2:
                                            t.thirtyDayLogins = e.sent;
                                        case 3:
                                        case "end":
                                            return e.stop() } }), e) })))() }, getLoginsInLast: function(t, e) { return p(a.a.mark((function n() { var r; return a.a.wrap((function(n) { for (;;) switch (n.prev = n.next) {
                                        case 0:
                                            return n.next = 2, Object(i.a)({ last_logged_in_at: s()().subtract(t, e).utc().format("YYYY-MM-DD HH:mm:ss") }).then((function(t) { return t.data.data.length }));
                                        case 2:
                                            return r = n.sent, n.abrupt("return", r);
                                        case 4:
                                        case "end":
                                            return n.stop() } }), n) })))() }, getSevenDayApplications: function() { var t = this; return p(a.a.mark((function e() { return a.a.wrap((function(e) { for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return e.next = 2, t.getApplicationsInLast(7, "day");
                                        case 2:
                                            t.sevenDayApplications = e.sent;
                                        case 3:
                                        case "end":
                                            return e.stop() } }), e) })))() }, getThirtyDayApplications: function() { var t = this; return p(a.a.mark((function e() { return a.a.wrap((function(e) { for (;;) switch (e.prev = e.next) {
                                        case 0:
                                            return e.next = 2, t.getApplicationsInLast(30, "day");
                                        case 2:
                                            t.thirtyDayApplications = e.sent;
                                        case 3:
                                        case "end":
                                            return e.stop() } }), e) })))() }, getApplicationsInLast: function(t, e) { return p(a.a.mark((function n() { var r; return a.a.wrap((function(n) { for (;;) switch (n.prev = n.next) {
                                        case 0:
                                            return n.next = 2, window.axios.get("/api/applicaitons?finalized_at=".concat(s()().subtract(t, e).utc().format("YYYY-MM-DD HH:mm:ss"), "&only_count=1")).then((function(t) { return t.data.data }));
                                        case 2:
                                            return r = n.sent, n.abrupt("return", r);
                                        case 4:
                                        case "end":
                                            return n.stop() } }), n) })))() }, getMetrics: function() { this.getSevenDayLogins(), this.getThirtyDayLogins(), this.getSevenDayApplications(), this.getThirtyDayApplications(), this.getNeverLoggedIn() } }, mounted: function() { this.getMetrics() } },
                d = Object(u.a)(f, (function() { var t = this,
                        e = t.$createElement,
                        n = t._self._c || e; return n("div", [n("div", { staticClass: "activity-metrics-container d-flex" }, [n("metric-box", { attrs: { title: "New applications in last 7 days", value: t.sevenDayApplications } }), t._v(" "), n("metric-box", { attrs: { title: "New applications in last 30 days", value: t.thirtyDayApplications } }), t._v(" "), n("metric-box", { attrs: { title: "Logged in last 7 days", value: t.sevenDayLogins } }), t._v(" "), n("metric-box", { attrs: { title: "Logged in last 30 days", value: t.thirtyDayLogins } }), t._v(" "), n("metric-box", { attrs: { title: "Never logged in", value: t.neverLoggedIn } })], 1), t._v(" "), t.now < t.aug6 ? n("div", { staticClass: "note text-muted" }, [n("small", [t._v("* 7 day login numbers will not be accurate until Aug. 6")])]) : t._e(), t._v(" "), t.now < t.aug29 ? n("div", { staticClass: "note text-muted" }, [n("small", [t._v("* 30 day login numbers will not be accurate until Aug. 29")])]) : t._e()]) }), [], !1, null, null, null);
            e.default = d.exports } }
]);
//# sourceMappingURL=activity-metrics.687ea9e2f31795c804dc.js.map