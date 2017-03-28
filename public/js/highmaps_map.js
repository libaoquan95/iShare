/*
 Highmaps JS v4.2.3 (2016-02-08)
 Highmaps as a plugin for Highcharts 4.1.x or Highstock 2.1.x (x being the patch version of this file)

 (c) 2011-2016 Torstein Honsi

 License: www.highcharts.com/license
*/
(function(i) {
    typeof module === "object" && module.exports ? module.exports = i: i(Highcharts)
})(function(i) {
    function K(a, b) {
        var c, d, e, f, g = !1,
        h = a.x,
        j = a.y;
        for (c = 0, d = b.length - 1; c < b.length; d = c++) e = b[c][1] > j,
        f = b[d][1] > j,
        e !== f && h < (b[d][0] - b[c][0]) * (j - b[c][1]) / (b[d][1] - b[c][1]) + b[c][0] && (g = !g);
        return g
    }
    function L(a, b, c, d, e, f, g, h) {
        return ["M", a + e, b, "L", a + c - f, b, "C", a + c - f / 2, b, a + c, b + f / 2, a + c, b + f, "L", a + c, b + d - g, "C", a + c, b + d - g / 2, a + c - g / 2, b + d, a + c - g, b + d, "L", a + h, b + d, "C", a + h / 2, b + d, a, b + d - h / 2, a, b + d - h, "L", a, b + e, "C", a, b + e / 2, a + e / 2, b, a + e, b, "Z"]
    }
    var p = i.Axis,
    q = i.Chart,
    x = i.Color,
    t = i.Point,
    G = i.Pointer,
    D = i.Legend,
    H = i.LegendSymbolMixin,
    R = i.Renderer,
    y = i.Series,
    I = i.SVGRenderer,
    M = i.VMLRenderer,
    A = i.win,
    N = A.document,
    J = i.addEvent,
    k = i.each,
    E = i.error,
    o = i.extend,
    u = i.extendClass,
    O = i.format,
    n = i.merge,
    l = i.pick,
    B = i.getOptions(),
    m = i.seriesTypes,
    w = B.plotOptions,
    v = i.wrap,
    s = function() {};
    v(p.prototype, "getSeriesExtremes",
    function(a) {
        var b = this.isXAxis,
        c, d, e = [],
        f;
        b && k(this.series,
        function(a, b) {
            if (a.useMapGeometry) e[b] = a.xData,
            a.xData = []
        });
        a.call(this);
        if (b && (c = l(this.dataMin, Number.MAX_VALUE), d = l(this.dataMax, -Number.MAX_VALUE), k(this.series,
        function(a, b) {
            if (a.useMapGeometry) c = Math.min(c, l(a.minX, c)),
            d = Math.max(d, l(a.maxX, c)),
            a.xData = e[b],
            f = !0
        }), f)) this.dataMin = c,
        this.dataMax = d
    });
    v(p.prototype, "setAxisTranslation",
    function(a) {
        var b = this.chart,
        c = b.plotWidth / b.plotHeight,
        b = b.xAxis[0],
        d;
        a.call(this);
        this.coll === "yAxis" && b.transA !== void 0 && k(this.series,
        function(a) {
            a.preserveAspectRatio && (d = !0)
        });
        if (d && (this.transA = b.transA = Math.min(this.transA, b.transA), a = c / ((b.max - b.min) / (this.max - this.min)), a = a < 1 ? this: b, c = (a.max - a.min) * a.transA, a.pixelPadding = a.len - c, a.minPixelPadding = a.pixelPadding / 2, c = a.fixTo)) {
            c = c[1] - a.toValue(c[0], !0);
            c *= a.transA;
            if (Math.abs(c) > a.minPixelPadding || a.min === a.dataMin && a.max === a.dataMax) c = 0;
            a.minPixelPadding -= c
        }
    });
    v(p.prototype, "render",
    function(a) {
        a.call(this);
        this.fixTo = null
    });
    var C = i.ColorAxis = function() {
        this.isColorAxis = !0;
        this.init.apply(this, arguments)
    };
    o(C.prototype, p.prototype);
    o(C.prototype, {
        defaultColorAxisOptions: {
            lineWidth: 0,
            minPadding: 0,
            maxPadding: 0,
            gridLineWidth: 1,
            tickPixelInterval: 72,
            startOnTick: !0,
            endOnTick: !0,
            offset: 0,
            marker: {
                animation: {
                    duration: 50
                },
                color: "gray",
                width: 0.01
            },
            labels: {
                overflow: "justify"
            },
            minColor: "#EFEFFF",
            maxColor: "#003875",
            tickLength: 5
        },
        init: function(a, b) {
            var c = a.options.legend.layout !== "vertical",
            d;
            d = n(this.defaultColorAxisOptions, {
                side: c ? 2 : 1,
                reversed: !c
            },
            b, {
                opposite: !c,
                showEmpty: !1,
                title: null,
                isColor: !0
            });
            p.prototype.init.call(this, a, d);
            b.dataClasses && this.initDataClasses(b);
            this.initStops(b);
            this.horiz = c;
            this.zoomEnabled = !1
        },
        tweenColors: function(a, b, c) {
            var d; ! b.rgba.length || !a.rgba.length ? a = b.input || "none": (a = a.rgba, b = b.rgba, d = b[3] !== 1 || a[3] !== 1, a = (d ? "rgba(": "rgb(") + Math.round(b[0] + (a[0] - b[0]) * (1 - c)) + "," + Math.round(b[1] + (a[1] - b[1]) * (1 - c)) + "," + Math.round(b[2] + (a[2] - b[2]) * (1 - c)) + (d ? "," + (b[3] + (a[3] - b[3]) * (1 - c)) : "") + ")");
            return a
        },
        initDataClasses: function(a) {
            var b = this,
            c = this.chart,
            d, e = 0,
            f = this.options,
            g = a.dataClasses.length;
            this.dataClasses = d = [];
            this.legendItems = [];
            k(a.dataClasses,
            function(a, j) {
                var r, a = n(a);
                d.push(a);
                if (!a.color) f.dataClassColor === "category" ? (r = c.options.colors, a.color = r[e++], e === r.length && (e = 0)) : a.color = b.tweenColors(x(f.minColor), x(f.maxColor), g < 2 ? 0.5 : j / (g - 1))
            })
        },
        initStops: function(a) {
            this.stops = a.stops || [[0, this.options.minColor], [1, this.options.maxColor]];
            k(this.stops,
            function(a) {
                a.color = x(a[1])
            })
        },
        setOptions: function(a) {
            p.prototype.setOptions.call(this, a);
            this.options.crosshair = this.options.marker;
            this.coll = "colorAxis"
        },
        setAxisSize: function() {
            var a = this.legendSymbol,
            b = this.chart,
            c, d, e;
            if (a) this.left = c = a.attr("x"),
            this.top = d = a.attr("y"),
            this.width = e = a.attr("width"),
            this.height = a = a.attr("height"),
            this.right = b.chartWidth - c - e,
            this.bottom = b.chartHeight - d - a,
            this.len = this.horiz ? e: a,
            this.pos = this.horiz ? c: d
        },
        toColor: function(a, b) {
            var c, d = this.stops,
            e, f = this.dataClasses,
            g, h;
            if (f) for (h = f.length; h--;) {
                if (g = f[h], e = g.from, d = g.to, (e === void 0 || a >= e) && (d === void 0 || a <= d)) {
                    c = g.color;
                    if (b) b.dataClass = h;
                    break
                }
            } else {
                this.isLog && (a = this.val2lin(a));
                c = 1 - (this.max - a) / (this.max - this.min || 1);
                for (h = d.length; h--;) if (c > d[h][0]) break;
                e = d[h] || d[h + 1];
                d = d[h + 1] || e;
                c = 1 - (d[0] - c) / (d[0] - e[0] || 1);
                c = this.tweenColors(e.color, d.color, c)
            }
            return c
        },
        getOffset: function() {
            var a = this.legendGroup,
            b = this.chart.axisOffset[this.side];
            if (a) {
                this.axisParent = a;
                p.prototype.getOffset.call(this);
                if (!this.added) this.added = !0,
                this.labelLeft = 0,
                this.labelRight = this.width;
                this.chart.axisOffset[this.side] = b
            }
        },
        setLegendColor: function() {
            var a, b = this.options,
            c = this.reversed;
            a = c ? 1 : 0;
            c = c ? 0 : 1;
            a = this.horiz ? [a, 0, c, 0] : [0, c, 0, a];
            this.legendColor = {
                linearGradient: {
                    x1: a[0],
                    y1: a[1],
                    x2: a[2],
                    y2: a[3]
                },
                stops: b.stops || [[0, b.minColor], [1, b.maxColor]]
            }
        },
        drawLegendSymbol: function(a, b) {
            var c = a.padding,
            d = a.options,
            e = this.horiz,
            f = l(d.symbolWidth, e ? 200 : 12),
            g = l(d.symbolHeight, e ? 12 : 200),
            h = l(d.labelPadding, e ? 16 : 30),
            d = l(d.itemDistance, 10);
            this.setLegendColor();
            b.legendSymbol = this.chart.renderer.rect(0, a.baseline - 11, f, g).attr({
                zIndex: 1
            }).add(b.legendGroup);
            this.legendItemWidth = f + c + (e ? d: h);
            this.legendItemHeight = g + c + (e ? h: 0)
        },
        setState: s,
        visible: !0,
        setVisible: s,
        getSeriesExtremes: function() {
            var a;
            if (this.series.length) a = this.series[0],
            this.dataMin = a.valueMin,
            this.dataMax = a.valueMax
        },
        drawCrosshair: function(a, b) {
            var c = b && b.plotX,
            d = b && b.plotY,
            e, f = this.pos,
            g = this.len;
            if (b) e = this.toPixels(b[b.series.colorKey]),
            e < f ? e = f - 2 : e > f + g && (e = f + g + 2),
            b.plotX = e,
            b.plotY = this.len - e,
            p.prototype.drawCrosshair.call(this, a, b),
            b.plotX = c,
            b.plotY = d,
            this.cross && this.cross.attr({
                fill: this.crosshair.color
            }).add(this.legendGroup)
        },
        getPlotLinePath: function(a, b, c, d, e) {
            return typeof e === "number" ? this.horiz ? ["M", e - 4, this.top - 6, "L", e + 4, this.top - 6, e, this.top, "Z"] : ["M", this.left, e, "L", this.left - 6, e + 6, this.left - 6, e - 6, "Z"] : p.prototype.getPlotLinePath.call(this, a, b, c, d)
        },
        update: function(a, b) {
            var c = this.chart,
            d = c.legend;
            k(this.series,
            function(a) {
                a.isDirtyData = !0
            });
            if (a.dataClasses && d.allItems) k(d.allItems,
            function(a) {
                a.isDataClass && a.legendGroup.destroy()
            }),
            c.isDirtyLegend = !0;
            c.options[this.coll] = n(this.userOptions, a);
            p.prototype.update.call(this, a, b);
            this.legendItem && (this.setLegendColor(), d.colorizeItem(this, !0))
        },
        getDataClassLegendSymbols: function() {
            var a = this,
            b = this.chart,
            c = this.legendItems,
            d = b.options.legend,
            e = d.valueDecimals,
            f = d.valueSuffix || "",
            g;
            c.length || k(this.dataClasses,
            function(d, j) {
                var r = !0,
                z = d.from,
                l = d.to;
                g = "";
                z === void 0 ? g = "< ": l === void 0 && (g = "> ");
                z !== void 0 && (g += i.numberFormat(z, e) + f);
                z !== void 0 && l !== void 0 && (g += " - ");
                l !== void 0 && (g += i.numberFormat(l, e) + f);
                c.push(o({
                    chart: b,
                    name: g,
                    options: {},
                    drawLegendSymbol: H.drawRectangle,
                    visible: !0,
                    setState: s,
                    isDataClass: !0,
                    setVisible: function() {
                        r = this.visible = !r;
                        k(a.series,
                        function(a) {
                            k(a.points,
                            function(a) {
                                a.dataClass === j && a.setVisible(r)
                            })
                        });
                        b.legend.colorizeItem(this, r)
                    }
                },
                d))
            });
            return c
        },
        name: ""
    });
    k(["fill", "stroke"],
    function(a) {
        i.Fx.prototype[a + "Setter"] = function() {
            this.elem.attr(a, C.prototype.tweenColors(x(this.start), x(this.end), this.pos))
        }
    });
    v(q.prototype, "getAxes",
    function(a) {
        var b = this.options.colorAxis;
        a.call(this);
        this.colorAxis = [];
        b && new C(this, b)
    });
    v(D.prototype, "getAllItems",
    function(a) {
        var b = [],
        c = this.chart.colorAxis[0];
        c && (c.options.dataClasses ? b = b.concat(c.getDataClassLegendSymbols()) : b.push(c), k(c.series,
        function(a) {
            a.options.showInLegend = !1
        }));
        return b.concat(a.call(this))
    });
    var D = {
        setVisible: function(a) {
            var b = this,
            c = a ? "show": "hide";
            k(["graphic", "dataLabel"],
            function(a) {
                if (b[a]) b[a][c]()
            })
        }
    },
    P = {
        pointAttrToOptions: {
            stroke: "borderColor",
            "stroke-width": "borderWidth",
            fill: "color",
            dashstyle: "dashStyle"
        },
        pointArrayMap: ["value"],
        axisTypes: ["xAxis", "yAxis", "colorAxis"],
        optionalAxis: "colorAxis",
        trackerGroups: ["group", "markerGroup", "dataLabelsGroup"],
        getSymbol: s,
        parallelArrays: ["x", "y", "value"],
        colorKey: "value",
        translateColors: function() {
            var a = this,
            b = this.options.nullColor,
            c = this.colorAxis,
            d = this.colorKey;
            k(this.data,
            function(e) {
                var f = e[d];
                if (f = e.options.color || (f === null ? b: c && f !== void 0 ? c.toColor(f, e) : e.color || a.color)) e.color = f
            })
        }
    };
    o(q.prototype, {
        renderMapNavigation: function() {
            var a = this,
            b = this.options.mapNavigation,
            c = b.buttons,
            d, e, f, g, h = function(a) {
                if (a) a.preventDefault && a.preventDefault(),
                a.stopPropagation && a.stopPropagation(),
                a.cancelBubble = !0
            },
            j = function(b) {
                this.handler.call(a, b);
                h(b)
            };
            if (l(b.enableButtons, b.enabled) && !a.renderer.forExport) for (d in c) if (c.hasOwnProperty(d)) f = n(b.buttonOptions, c[d]),
            e = f.theme,
            e.style = n(f.theme.style, f.style),
            g = e.states,
            e = a.renderer.button(f.text, 0, 0, j, e, g && g.hover, g && g.select, 0, d === "zoomIn" ? "topbutton": "bottombutton").attr({
                width: f.width,
                height: f.height,
                title: a.options.lang[d],
                zIndex: 5
            }).add(),
            e.handler = f.onclick,
            e.align(o(f, {
                width: e.width,
                height: 2 * e.height
            }), null, f.alignTo),
            J(e.element, "dblclick", h)
        },
        fitToBox: function(a, b) {
            k([["x", "width"], ["y", "height"]],
            function(c) {
                var d = c[0],
                c = c[1];
                a[d] + a[c] > b[d] + b[c] && (a[c] > b[c] ? (a[c] = b[c], a[d] = b[d]) : a[d] = b[d] + b[c] - a[c]);
                a[c] > b[c] && (a[c] = b[c]);
                a[d] < b[d] && (a[d] = b[d])
            });
            return a
        },
        mapZoom: function(a, b, c, d, e) {
            var f = this.xAxis[0],
            g = f.max - f.min,
            h = l(b, f.min + g / 2),
            j = g * a,
            g = this.yAxis[0],
            i = g.max - g.min,
            z = l(c, g.min + i / 2);
            i *= a;
            h = this.fitToBox({
                x: h - j * (d ? (d - f.pos) / f.len: 0.5),
                y: z - i * (e ? (e - g.pos) / g.len: 0.5),
                width: j,
                height: i
            },
            {
                x: f.dataMin,
                y: g.dataMin,
                width: f.dataMax - f.dataMin,
                height: g.dataMax - g.dataMin
            });
            if (d) f.fixTo = [d - f.pos, b];
            if (e) g.fixTo = [e - g.pos, c];
            a !== void 0 ? (f.setExtremes(h.x, h.x + h.width, !1), g.setExtremes(h.y, h.y + h.height, !1)) : (f.setExtremes(void 0, void 0, !1), g.setExtremes(void 0, void 0, !1));
            this.redraw()
        }
    });
    v(q.prototype, "render",
    function(a) {
        var b = this,
        c = b.options.mapNavigation;
        b.renderMapNavigation();
        a.call(b); (l(c.enableDoubleClickZoom, c.enabled) || c.enableDoubleClickZoomTo) && J(b.container, "dblclick",
        function(a) {
            b.pointer.onContainerDblClick(a)
        });
        /*l(c.enableMouseWheelZoom, c.enabled) && J(b.container, N.onmousewheel === void 0 ? "DOMMouseScroll": "mousewheel",
        function(a) {
            b.pointer.onContainerMouseWheel(a);
            return ! 1
        })*/
    });
    o(G.prototype, {
        onContainerDblClick: function(a) {
            var b = this.chart,
            a = this.normalize(a);
            b.options.mapNavigation.enableDoubleClickZoomTo ? b.pointer.inClass(a.target, "highcharts-tracker") && b.hoverPoint.zoomTo() : b.isInsidePlot(a.chartX - b.plotLeft, a.chartY - b.plotTop) && b.mapZoom(0.5, b.xAxis[0].toValue(a.chartX), b.yAxis[0].toValue(a.chartY), a.chartX, a.chartY)
        },
        onContainerMouseWheel: function(a) {
            var b = this.chart,
            c, a = this.normalize(a);
            c = a.detail || -(a.wheelDelta / 120);
            b.isInsidePlot(a.chartX - b.plotLeft, a.chartY - b.plotTop) && b.mapZoom(Math.pow(2, c), b.xAxis[0].toValue(a.chartX), b.yAxis[0].toValue(a.chartY), a.chartX, a.chartY)
        }
    });
    v(G.prototype, "init",
    function(a, b, c) {
        a.call(this, b, c);
        if (l(c.mapNavigation.enableTouchZoom, c.mapNavigation.enabled)) this.pinchX = this.pinchHor = this.pinchY = this.pinchVert = this.hasZoom = !0
    });
    v(G.prototype, "pinchTranslate",
    function(a, b, c, d, e, f, g) {
        a.call(this, b, c, d, e, f, g);
        this.chart.options.chart.type === "map" && this.hasZoom && (a = d.scaleX > d.scaleY, this.pinchTranslateDirection(!a, b, c, d, e, f, g, a ? d.scaleX: d.scaleY))
    });
    var F = N.documentElement.style.vectorEffect !== void 0;
    w.map = n(w.scatter, {
        allAreas: !0,
        animation: !1,
        nullColor: "#F8F8F8",
        borderColor: "silver",
        borderWidth: 1,
        marker: null,
        stickyTracking: !1,
        dataLabels: {
            formatter: function() {
                return this.point.value
            },
            inside: !0,
            verticalAlign: "middle",
            crop: !1,
            overflow: !1,
            padding: 0
        },
        turboThreshold: 0,
        tooltip: {
            followPointer: !0,
            pointFormat: "{point.name}: {point.value}<br/>"
        },
        states: {
            normal: {
                animation: !0
            },
            hover: {
                brightness: 0.2,
                halo: null
            }
        }
    });
    var Q = u(t, o({
        applyOptions: function(a, b) {
            var c = t.prototype.applyOptions.call(this, a, b),
            d = this.series,
            e = d.joinBy;
            if (d.mapData) if (e = c[e[1]] !== void 0 && d.mapMap[c[e[1]]]) {
                if (d.xyFromShape) c.x = e._midX,
                c.y = e._midY;
                o(c, e)
            } else c.value = c.value || null;
            return c
        },
        onMouseOver: function(a) {
            clearTimeout(this.colorInterval);
            if (this.value !== null) t.prototype.onMouseOver.call(this, a);
            else this.series.onMouseOut(a)
        },
        onMouseOut: function() {
            var a = this,
            b = +new Date,
            c = x(a.color),
            d = x(a.pointAttr.hover.fill),
            e = a.series.options.states.normal.animation,
            f = e && (e.duration || 500),
            g;
            if (f && c.rgba.length === 4 && d.rgba.length === 4 && a.state !== "select") g = a.pointAttr[""].fill,
            delete a.pointAttr[""].fill,
            clearTimeout(a.colorInterval),
            a.colorInterval = setInterval(function() {
                var e = (new Date - b) / f,
                g = a.graphic;
                e > 1 && (e = 1);
                g && g.attr("fill", C.prototype.tweenColors.call(0, d, c, e));
                e >= 1 && clearTimeout(a.colorInterval)
            },
            13);
            t.prototype.onMouseOut.call(a);
            if (g) a.pointAttr[""].fill = g
        },
        zoomTo: function() {
            var a = this.series;
            a.xAxis.setExtremes(this._minX, this._maxX, !1);
            a.yAxis.setExtremes(this._minY, this._maxY, !1);
            a.chart.redraw()
        }
    },
    D));
    m.map = u(m.scatter, n(P, {
        type: "map",
        pointClass: Q,
        supportsDrilldown: !0,
        getExtremesFromAll: !0,
        useMapGeometry: !0,
        forceDL: !0,
        searchPoint: s,
        directTouch: !0,
        preserveAspectRatio: !0,
        getBox: function(a) {
            var b = Number.MAX_VALUE,
            c = -b,
            d = b,
            e = -b,
            f = b,
            g = b,
            h = this.xAxis,
            j = this.yAxis,
            r;
            k(a || [],
            function(a) {
                if (a.path) {
                    if (typeof a.path === "string") a.path = i.splitPath(a.path);
                    var h = a.path || [],
                    j = h.length,
                    k = !1,
                    m = -b,
                    n = b,
                    o = -b,
                    p = b,
                    q = a.properties;
                    if (!a._foundBox) {
                        for (; j--;) typeof h[j] === "number" && !isNaN(h[j]) && (k ? (m = Math.max(m, h[j]), n = Math.min(n, h[j])) : (o = Math.max(o, h[j]), p = Math.min(p, h[j])), k = !k);
                        a._midX = n + (m - n) * (a.middleX || q && q["hc-middle-x"] || 0.5);
                        a._midY = p + (o - p) * (a.middleY || q && q["hc-middle-y"] || 0.5);
                        a._maxX = m;
                        a._minX = n;
                        a._maxY = o;
                        a._minY = p;
                        a.labelrank = l(a.labelrank, (m - n) * (o - p));
                        a._foundBox = !0
                    }
                    c = Math.max(c, a._maxX);
                    d = Math.min(d, a._minX);
                    e = Math.max(e, a._maxY);
                    f = Math.min(f, a._minY);
                    g = Math.min(a._maxX - a._minX, a._maxY - a._minY, g);
                    r = !0
                }
            });
            if (r) {
                this.minY = Math.min(f, l(this.minY, b));
                this.maxY = Math.max(e, l(this.maxY, -b));
                this.minX = Math.min(d, l(this.minX, b));
                this.maxX = Math.max(c, l(this.maxX, -b));
                if (h && h.options.minRange === void 0) h.minRange = Math.min(5 * g, (this.maxX - this.minX) / 5, h.minRange || b);
                if (j && j.options.minRange === void 0) j.minRange = Math.min(5 * g, (this.maxY - this.minY) / 5, j.minRange || b)
            }
        },
        getExtremes: function() {
            y.prototype.getExtremes.call(this, this.valueData);
            this.chart.hasRendered && this.isDirtyData && this.getBox(this.options.data);
            this.valueMin = this.dataMin;
            this.valueMax = this.dataMax;
            this.dataMin = this.minY;
            this.dataMax = this.maxY
        },
        translatePath: function(a) {
            var b = !1,
            c = this.xAxis,
            d = this.yAxis,
            e = c.min,
            f = c.transA,
            c = c.minPixelPadding,
            g = d.min,
            h = d.transA,
            d = d.minPixelPadding,
            j, i = [];
            if (a) for (j = a.length; j--;) typeof a[j] === "number" ? (i[j] = b ? (a[j] - e) * f + c: (a[j] - g) * h + d, b = !b) : i[j] = a[j];
            return i
        },
        setData: function(a, b) {
            var c = this.options,
            d = c.mapData,
            e = c.joinBy,
            f = e === null,
            g = [],
            h,
            j,
            l;
            f && (e = "_i");
            e = this.joinBy = i.splat(e);
            e[1] || (e[1] = e[0]);
            a && k(a,
            function(b, c) {
                typeof b === "number" && (a[c] = {
                    value: b
                });
                if (f) a[c]._i = c
            });
            this.getBox(a);
            if (d) {
                if (d.type === "FeatureCollection") {
                    if (d["hc-transform"]) for (h in this.chart.mapTransforms = j = d["hc-transform"], j) if (j.hasOwnProperty(h) && h.rotation) h.cosAngle = Math.cos(h.rotation),
                    h.sinAngle = Math.sin(h.rotation);
                    d = i.geojson(d, this.type, this)
                }
                this.getBox(d);
                this.mapData = d;
                this.mapMap = {};
                for (l = 0; l < d.length; l++) h = d[l],
                j = h.properties,
                h._i = l,
                e[0] && j && j[e[0]] && (h[e[0]] = j[e[0]]),
                this.mapMap[h[e[0]]] = h;
                c.allAreas && (a = a || [], e[1] && k(a,
                function(a) {
                    g.push(a[e[1]])
                }), g = "|" + g.join("|") + "|", k(d,
                function(b) { (!e[0] || g.indexOf("|" + b[e[0]] + "|") === -1) && a.push(n(b, {
                        value: null
                    }))
                }))
            }
            y.prototype.setData.call(this, a, b)
        },
        drawGraph: s,
        drawDataLabels: s,
        doFullTranslate: function() {
            return this.isDirtyData || this.chart.isResizing || this.chart.renderer.isVML || !this.baseTrans
        },
        translate: function() {
            var a = this,
            b = a.xAxis,
            c = a.yAxis,
            d = a.doFullTranslate();
            a.generatePoints();
            k(a.data,
            function(e) {
                e.plotX = b.toPixels(e._midX, !0);
                e.plotY = c.toPixels(e._midY, !0);
                if (d) e.shapeType = "path",
                e.shapeArgs = {
                    d: a.translatePath(e.path)
                },
                F && (e.shapeArgs["vector-effect"] = "non-scaling-stroke")
            });
            a.translateColors()
        },
        drawPoints: function() {
            var a = this,
            b = a.xAxis,
            c = a.yAxis,
            d = a.group,
            e = a.chart,
            f = e.renderer,
            g, h = this.baseTrans;
            if (!a.transformGroup) a.transformGroup = f.g().attr({
                scaleX: 1,
                scaleY: 1
            }).add(d),
            a.transformGroup.survive = !0;
            a.doFullTranslate() ? (e.hasRendered && a.pointAttrToOptions.fill === "color" && k(a.points,
            function(a) {
                if (a.shapeArgs) a.shapeArgs.fill = a.pointAttr[l(a.state, "")].fill
            }), F || k(a.points,
            function(b) {
                b = b.pointAttr[""];
                b["stroke-width"] === a.pointAttr[""]["stroke-width"] && (b["stroke-width"] = "inherit")
            }), a.group = a.transformGroup, m.column.prototype.drawPoints.apply(a), a.group = d, k(a.points,
            function(a) {
                a.graphic && (a.name && a.graphic.addClass("highcharts-name-" + a.name.replace(" ", "-").toLowerCase()), a.properties && a.properties["hc-key"] && a.graphic.addClass("highcharts-key-" + a.properties["hc-key"].toLowerCase()), F || (a.graphic["stroke-widthSetter"] = s))
            }), this.baseTrans = {
                originX: b.min - b.minPixelPadding / b.transA,
                originY: c.min - c.minPixelPadding / c.transA + (c.reversed ? 0 : c.len / c.transA),
                transAX: b.transA,
                transAY: c.transA
            },
            this.transformGroup.animate({
                translateX: 0,
                translateY: 0,
                scaleX: 1,
                scaleY: 1
            })) : (g = b.transA / h.transAX, d = c.transA / h.transAY, b = b.toPixels(h.originX, !0), c = c.toPixels(h.originY, !0), g > 0.99 && g < 1.01 && d > 0.99 && d < 1.01 && (d = g = 1, b = Math.round(b), c = Math.round(c)), this.transformGroup.animate({
                translateX: b,
                translateY: c,
                scaleX: g,
                scaleY: d
            }));
            F || a.group.element.setAttribute("stroke-width", a.options.borderWidth / (g || 1));
            this.drawMapDataLabels()
        },
        drawMapDataLabels: function() {
            y.prototype.drawDataLabels.call(this);
            this.dataLabelsGroup && this.dataLabelsGroup.clip(this.chart.clipRect)
        },
        render: function() {
            var a = this,
            b = y.prototype.render;
            a.chart.renderer.isVML && a.data.length > 3E3 ? setTimeout(function() {
                b.call(a)
            }) : b.call(a)
        },
        animate: function(a) {
            var b = this.options.animation,
            c = this.group,
            d = this.xAxis,
            e = this.yAxis,
            f = d.pos,
            g = e.pos;
            if (this.chart.renderer.isSVG) b === !0 && (b = {
                duration: 1E3
            }),
            a ? c.attr({
                translateX: f + d.len / 2,
                translateY: g + e.len / 2,
                scaleX: 0.001,
                scaleY: 0.001
            }) : (c.animate({
                translateX: f,
                translateY: g,
                scaleX: 1,
                scaleY: 1
            },
            b), this.animate = null)
        },
        animateDrilldown: function(a) {
            var b = this.chart.plotBox,
            c = this.chart.drilldownLevels[this.chart.drilldownLevels.length - 1],
            d = c.bBox,
            e = this.chart.options.drilldown.animation;
            if (!a) a = Math.min(d.width / b.width, d.height / b.height),
            c.shapeArgs = {
                scaleX: a,
                scaleY: a,
                translateX: d.x,
                translateY: d.y
            },
            k(this.points,
            function(a) {
                a.graphic && a.graphic.attr(c.shapeArgs).animate({
                    scaleX: 1,
                    scaleY: 1,
                    translateX: 0,
                    translateY: 0
                },
                e)
            }),
            this.animate = null
        },
        drawLegendSymbol: H.drawRectangle,
        animateDrillupFrom: function(a) {
            m.column.prototype.animateDrillupFrom.call(this, a)
        },
        animateDrillupTo: function(a) {
            m.column.prototype.animateDrillupTo.call(this, a)
        }
    }));
    w.mapline = n(w.map, {
        lineWidth: 1,
        fillColor: "none"
    });
    m.mapline = u(m.map, {
        type: "mapline",
        pointAttrToOptions: {
            stroke: "color",
            "stroke-width": "lineWidth",
            fill: "fillColor",
            dashstyle: "dashStyle"
        },
        drawLegendSymbol: m.line.prototype.drawLegendSymbol
    });
    w.mappoint = n(w.scatter, {
        dataLabels: {
            enabled: !0,
            formatter: function() {
                return this.point.name
            },
            crop: !1,
            defer: !1,
            overflow: !1,
            style: {
                color: "#000000"
            }
        }
    });
    m.mappoint = u(m.scatter, {
        type: "mappoint",
        forceDL: !0,
        pointClass: u(t, {
            applyOptions: function(a, b) {
                var c = t.prototype.applyOptions.call(this, a, b);
                a.lat !== void 0 && a.lon !== void 0 && (c = o(c, this.series.chart.fromLatLonToPoint(c)));
                return c
            }
        })
    });
    if (m.bubble) w.mapbubble = n(w.bubble, {
        animationLimit: 500,
        tooltip: {
            pointFormat: "{point.name}: {point.z}"
        }
    }),
    m.mapbubble = u(m.bubble, {
        pointClass: u(t, {
            applyOptions: function(a, b) {
                var c;
                a && a.lat !== void 0 && a.lon !== void 0 ? (c = t.prototype.applyOptions.call(this, a, b), c = o(c, this.series.chart.fromLatLonToPoint(c))) : c = Q.prototype.applyOptions.call(this, a, b);
                return c
            },
            ttBelow: !1
        }),
        xyFromShape: !0,
        type: "mapbubble",
        pointArrayMap: ["z"],
        getMapData: m.map.prototype.getMapData,
        getBox: m.map.prototype.getBox,
        setData: m.map.prototype.setData
    });
    B.plotOptions.heatmap = n(B.plotOptions.scatter, {
        animation: !1,
        borderWidth: 0,
        nullColor: "#F8F8F8",
        dataLabels: {
            formatter: function() {
                return this.point.value
            },
            inside: !0,
            verticalAlign: "middle",
            crop: !1,
            overflow: !1,
            padding: 0
        },
        marker: null,
        pointRange: null,
        tooltip: {
            pointFormat: "{point.x}, {point.y}: {point.value}<br/>"
        },
        states: {
            normal: {
                animation: !0
            },
            hover: {
                halo: !1,
                brightness: 0.2
            }
        }
    });
    m.heatmap = u(m.scatter, n(P, {
        type: "heatmap",
        pointArrayMap: ["y", "value"],
        hasPointSpecificOptions: !0,
        pointClass: u(t, D),
        supportsDrilldown: !0,
        getExtremesFromAll: !0,
        directTouch: !0,
        init: function() {
            var a;
            m.scatter.prototype.init.apply(this, arguments);
            a = this.options;
            a.pointRange = l(a.pointRange, a.colsize || 1);
            this.yAxis.axisPointRange = a.rowsize || 1
        },
        translate: function() {
            var a = this.options,
            b = this.xAxis,
            c = this.yAxis,
            d = function(a, b, c) {
                return Math.min(Math.max(b, a), c)
            };
            this.generatePoints();
            k(this.points,
            function(e) {
                var f = (a.colsize || 1) / 2,
                g = (a.rowsize || 1) / 2,
                h = d(Math.round(b.len - b.translate(e.x - f, 0, 1, 0, 1)), 0, b.len),
                f = d(Math.round(b.len - b.translate(e.x + f, 0, 1, 0, 1)), 0, b.len),
                j = d(Math.round(c.translate(e.y - g, 0, 1, 0, 1)), 0, c.len),
                g = d(Math.round(c.translate(e.y + g, 0, 1, 0, 1)), 0, c.len);
                e.plotX = e.clientX = (h + f) / 2;
                e.plotY = (j + g) / 2;
                e.shapeType = "rect";
                e.shapeArgs = {
                    x: Math.min(h, f),
                    y: Math.min(j, g),
                    width: Math.abs(f - h),
                    height: Math.abs(g - j)
                }
            });
            this.translateColors();
            this.chart.hasRendered && k(this.points,
            function(a) {
                a.shapeArgs.fill = a.options.color || a.color
            })
        },
        drawPoints: m.column.prototype.drawPoints,
        animate: s,
        getBox: s,
        drawLegendSymbol: H.drawRectangle,
        getExtremes: function() {
            y.prototype.getExtremes.call(this, this.valueData);
            this.valueMin = this.dataMin;
            this.valueMax = this.dataMax;
            y.prototype.getExtremes.call(this)
        }
    }));
    q.prototype.transformFromLatLon = function(a, b) {
        if (A.proj4 === void 0) return E(21),
        {
            x: 0,
            y: null
        };
        var c = A.proj4(b.crs, [a.lon, a.lat]),
        d = b.cosAngle || b.rotation && Math.cos(b.rotation),
        e = b.sinAngle || b.rotation && Math.sin(b.rotation),
        c = b.rotation ? [c[0] * d + c[1] * e, -c[0] * e + c[1] * d] : c;
        return {
            x: ((c[0] - (b.xoffset || 0)) * (b.scale || 1) + (b.xpan || 0)) * (b.jsonres || 1) + (b.jsonmarginX || 0),
            y: (((b.yoffset || 0) - c[1]) * (b.scale || 1) + (b.ypan || 0)) * (b.jsonres || 1) - (b.jsonmarginY || 0)
        }
    };
    q.prototype.transformToLatLon = function(a, b) {
        if (A.proj4 === void 0) E(21);
        else {
            var c = {
                x: ((a.x - (b.jsonmarginX || 0)) / (b.jsonres || 1) - (b.xpan || 0)) / (b.scale || 1) + (b.xoffset || 0),
                y: (( - a.y - (b.jsonmarginY || 0)) / (b.jsonres || 1) + (b.ypan || 0)) / (b.scale || 1) + (b.yoffset || 0)
            },
            d = b.cosAngle || b.rotation && Math.cos(b.rotation),
            e = b.sinAngle || b.rotation && Math.sin(b.rotation),
            c = A.proj4(b.crs, "WGS84", b.rotation ? {
                x: c.x * d + c.y * -e,
                y: c.x * e + c.y * d
            }: c);
            return {
                lat: c.y,
                lon: c.x
            }
        }
    };
    q.prototype.fromPointToLatLon = function(a) {
        var b = this.mapTransforms,
        c;
        if (b) {
            for (c in b) if (b.hasOwnProperty(c) && b[c].hitZone && K({
                x: a.x,
                y: -a.y
            },
            b[c].hitZone.coordinates[0])) return this.transformToLatLon(a, b[c]);
            return this.transformToLatLon(a, b["default"])
        } else E(22)
    };
    q.prototype.fromLatLonToPoint = function(a) {
        var b = this.mapTransforms,
        c, d;
        if (!b) return E(22),
        {
            x: 0,
            y: null
        };
        for (c in b) if (b.hasOwnProperty(c) && b[c].hitZone && (d = this.transformFromLatLon(a, b[c]), K({
            x: d.x,
            y: -d.y
        },
        b[c].hitZone.coordinates[0]))) return d;
        return this.transformFromLatLon(a, b["default"])
    };
    i.geojson = function(a, b, c) {
        var d = [],
        e = [],
        f = function(a) {
            var b, c = a.length;
            e.push("M");
            for (b = 0; b < c; b++) b === 1 && e.push("L"),
            e.push(a[b][0], -a[b][1])
        },
        b = b || "map";
        k(a.features,
        function(a) {
            var c = a.geometry,
            j = c.type,
            c = c.coordinates,
            a = a.properties,
            i;
            e = [];
            b === "map" || b === "mapbubble" ? (j === "Polygon" ? (k(c, f), e.push("Z")) : j === "MultiPolygon" && (k(c,
            function(a) {
                k(a, f)
            }), e.push("Z")), e.length && (i = {
                path: e
            })) : b === "mapline" ? (j === "LineString" ? f(c) : j === "MultiLineString" && k(c, f), e.length && (i = {
                path: e
            })) : b === "mappoint" && j === "Point" && (i = {
                x: c[0],
                y: -c[1]
            });
            i && d.push(o(i, {
                name: a.name || a.NAME,
                properties: a
            }))
        });
        if (c && a.copyrightShort) c.chart.mapCredits = O(c.chart.options.credits.mapText, {
            geojson: a
        }),
        c.chart.mapCreditsFull = O(c.chart.options.credits.mapTextFull, {
            geojson: a
        });
        return d
    };
    v(q.prototype, "showCredits",
    function(a, b) {
        if (this.mapCredits) b.href = null;
        a.call(this, i.merge(b, {
            text: b.text + (this.mapCredits || "")
        }));
        this.credits && this.mapCreditsFull && this.credits.attr({
            title: this.mapCreditsFull
        })
    });
    o(B.lang, {
        zoomIn: "Zoom in",
        zoomOut: "Zoom out"
    });
    B.mapNavigation = {
        buttonOptions: {
            alignTo: "plotBox",
            align: "left",
            verticalAlign: "top",
            x: 0,
            width: 18,
            height: 18,
            style: {
                fontSize: "15px",
                fontWeight: "bold",
                textAlign: "center"
            },
            theme: {
                "stroke-width": 1
            }
        },
        buttons: {
            zoomIn: {
                onclick: function() {
                    this.mapZoom(0.5)
                },
                text: "+",
                y: 0
            },
            zoomOut: {
                onclick: function() {
                    this.mapZoom(2)
                },
                text: "-",
                y: 28
            }
        }
    };
    i.splitPath = function(a) {
        var b, a = a.replace(/([A-Za-z])/g, " $1 "),
        a = a.replace(/^\s*/, "").replace(/\s*$/, ""),
        a = a.split(/[ ,]+/);
        for (b = 0; b < a.length; b++) / [a - zA - Z] / .test(a[b]) || (a[b] = parseFloat(a[b]));
        return a
    };
    i.maps = {};
    I.prototype.symbols.topbutton = function(a, b, c, d, e) {
        return L(a - 1, b - 1, c, d, e.r, e.r, 0, 0)
    };
    I.prototype.symbols.bottombutton = function(a, b, c, d, e) {
        return L(a - 1, b - 1, c, d, 0, 0, e.r, e.r)
    };
    R === M && k(["topbutton", "bottombutton"],
    function(a) {
        M.prototype.symbols[a] = I.prototype.symbols[a]
    });
    i.Map = i.mapChart = function(a, b, c) {
        var d = typeof a === "string" || a.nodeName,
        e = arguments[d ? 1 : 0],
        f = {
            endOnTick: !1,
            gridLineWidth: 0,
            lineWidth: 0,
            minPadding: 0,
            maxPadding: 0,
            startOnTick: !1,
            title: null,
            tickPositions: []
        },
        g,
        h = i.getOptions().credits;
        g = e.series;
        e.series = null;
        e = n({
            chart: {
                panning: "xy",
                type: "map"
            },
            credits: {
                mapText: l(h.mapText, ' \u00a9 <a href="{geojson.copyrightUrl}">{geojson.copyrightShort}</a>'),
                mapTextFull: l(h.mapTextFull, "{geojson.copyright}")
            },
            xAxis: f,
            yAxis: n(f, {
                reversed: !0
            })
        },
        e, {
            chart: {
                inverted: !1,
                alignTicks: !1
            }
        });
        e.series = g;
        return d ? new q(a, e, c) : new q(e, b)
    }
});