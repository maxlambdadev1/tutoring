/*!
 * Pintura Image Editor Sandbox 8.19.3
 * (c) 2018-2021 PQINA Inc. - All Rights Reserved
 * This version of Pintura Image Editor is for use on pqina.nl only
 * License: https://pqina.nl/pintura/license/
 */
/* eslint-disable */

const t = {
    65505: "exif",
    65504: "jfif",
    65498: "sos"
};
var e = e=>{
    if (65496 !== e.getUint16(0))
        return;
    const o = Object.keys(t).map((t=>parseInt(t, 10)))
      , i = e.byteLength;
    let n, r = 2, a = void 0;
    for (; r < i && 255 === e.getUint8(r); ) {
        if (n = e.getUint16(r),
        o.includes(n)) {
            const o = t[n];
            a || (a = {}),
            a[o] || (a[o] = {
                offset: r,
                size: e.getUint16(r + 2)
            })
        }
        if (65498 === n)
            break;
        r += 2 + e.getUint16(r + 2)
    }
    return a
}
;
var o = (t,o,i)=>{
    if (!t)
        return;
    const n = new DataView(t)
      , r = e(n);
    if (!r || !r.exif)
        return;
    const a = ((t,e)=>{
        if (65505 !== t.getUint16(e))
            return;
        const o = t.getUint16(e + 2);
        if (e += 4,
        1165519206 !== t.getUint32(e))
            return;
        e += 6;
        const i = t.getUint16(e);
        if (18761 !== i && 19789 !== i)
            return;
        const n = 18761 === i;
        if (e += 2,
        42 !== t.getUint16(e, n))
            return;
        e += t.getUint32(e + 2, n);
        const r = i=>{
            const r = [];
            let a = e;
            const s = e + o - 16;
            for (; a < s; a += 12) {
                const e = a;
                t.getUint16(e, n) === i && r.push(e)
            }
            return r
        }
        ;
        return {
            read: e=>{
                const o = r(e);
                if (o.length)
                    return t.getUint16(o[0] + 8, n)
            }
            ,
            write: (e,o)=>{
                const i = r(e);
                return !!i.length && (i.forEach((e=>t.setUint16(e + 8, o, n))),
                !0)
            }
        }
    }
    )(n, r.exif.offset);
    return a ? void 0 === i ? a.read(o) : a.write(o, i) : void 0
}
;
var i = t=>window.__pqina_webapi__ ? window.__pqina_webapi__[t] : window[t]
  , n = (...t)=>{}
;
const r = {
    ArrayBuffer: "readAsArrayBuffer"
};
var a = async(t,e=[0, t.size],o)=>await ((t,e=n,o={})=>new Promise(((n,a)=>{
    const {dataFormat: s=r.ArrayBuffer} = o
      , l = new (i("FileReader"));
    l.onload = ()=>n(l.result),
    l.onerror = a,
    l.onprogress = e,
    l[s](t)
}
)))(t.slice(...e), o)
  , s = async(t,e)=>{
    const i = await a(t, [0, 131072], e);
    return o(i, 274) || 1
}
;
let l = null;
var c = ()=>(null === l && (l = "undefined" != typeof window && void 0 !== window.document),
l);
let d = null;
var u = ()=>new Promise((t=>{
    if (null === d) {
        const e = "data:image/jpg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/4QA6RXhpZgAATU0AKgAAAAgAAwESAAMAAAABAAYAAAEoAAMAAAABAAIAAAITAAMAAAABAAEAAAAAAAD/2wBDAP//////////////////////////////////////////////////////////////////////////////////////wAALCAABAAIBASIA/8QAJgABAAAAAAAAAAAAAAAAAAAAAxABAAAAAAAAAAAAAAAAAAAAAP/aAAgBAQAAPwBH/9k=";
        let o = c() ? new Image : {};
        return o.onload = ()=>{
            d = 1 === o.naturalWidth,
            o = void 0,
            t(d)
        }
        ,
        void (o.src = e)
    }
    return t(d)
}
))
  , h = t=>t.getContext("2d").getImageData(0, 0, t.width, t.height)
  , p = (t,e,o=[])=>{
    const i = document.createElement(t)
      , n = Object.getOwnPropertyDescriptors(i.__proto__);
    for (const t in e)
        "style" === t ? i.style.cssText = e[t] : n[t] && n[t].set || /textContent|innerHTML/.test(t) || "function" == typeof e[t] ? i[t] = e[t] : i.setAttribute(t, e[t]);
    return o.forEach((t=>i.appendChild(t))),
    i
}
;
const m = {
    1: ()=>[1, 0, 0, 1, 0, 0],
    2: t=>[-1, 0, 0, 1, t, 0],
    3: (t,e)=>[-1, 0, 0, -1, t, e],
    4: (t,e)=>[1, 0, 0, -1, 0, e],
    5: ()=>[0, 1, 1, 0, 0, 0],
    6: (t,e)=>[0, 1, -1, 0, e, 0],
    7: (t,e)=>[0, -1, -1, 0, e, t],
    8: t=>[0, -1, 1, 0, 0, t]
};
var g = t=>{
    t.width = 1,
    t.height = 1;
    const e = t.getContext("2d");
    e && e.clearRect(0, 0, 1, 1)
}
  , f = t=>"data"in t
  , $ = async(t,e=1)=>{
    const [o,i] = await u() || e < 5 ? [t.width, t.height] : [t.height, t.width]
      , n = p("canvas", {
        width: o,
        height: i
    })
      , r = n.getContext("2d");
    if (f(t) && !await u() && e > 1) {
        const e = p("canvas", {
            width: t.width,
            height: t.height
        });
        e.getContext("2d").putImageData(t, 0, 0),
        t = e
    }
    return !await u() && e > 1 && r.transform.apply(r, ((t,e,o=-1)=>(-1 === o && (o = 1),
    m[o](t, e)))(t.width, t.height, e)),
    f(t) ? r.putImageData(t, 0, 0) : r.drawImage(t, 0, 0),
    t instanceof HTMLCanvasElement && g(t),
    n
}
  , y = async(t,e=1)=>1 === e || await u() ? t : h(await $(t, e))
  , x = t=>"object" == typeof t;
const b = t=>x(t) ? v(t) : t
  , v = t=>{
    let e;
    return Array.isArray(t) ? (e = [],
    t.forEach(((t,o)=>{
        e[o] = b(t)
    }
    ))) : (e = {},
    Object.keys(t).forEach((o=>{
        const i = t[o];
        e[o] = b(i)
    }
    ))),
    e
}
;
var w = t=>"string" == typeof t
  , S = t=>"function" == typeof t
  , C = (t,e)=>new Promise(((o,i)=>{
    const n = ()=>o(((t,{width: e, height: o, canvasMemoryLimit: i})=>{
        let n = e || t.naturalWidth
          , r = o || t.naturalHeight;
        n || r || (n = 300,
        r = 150);
        const a = n * r;
        if (i && a > i) {
            const t = Math.sqrt(i) / Math.sqrt(a);
            n = Math.floor(n * t),
            r = Math.floor(r * t)
        }
        const s = p("canvas");
        return s.width = n,
        s.height = r,
        s.getContext("2d").drawImage(t, 0, 0, n, r),
        s
    }
    )(t, e));
    t.complete && t.width ? n() : (t.onload = n,
    t.onerror = i)
}
))
  , k = ()=>"createImageBitmap"in window
  , M = t=>/svg/.test(t.type)
  , T = ()=>Math.random().toString(36).substr(2, 9);
const R = new Map;
var P = (t,e,o)=>new Promise(((i,n)=>{
    const r = t.toString();
    let a = R.get(r);
    if (!a) {
        const e = (t=>`function () {self.onmessage = function (message) {(${t.toString()}).apply(null, message.data.content.concat([function (err, response) {\n    response = response || {};\n    const transfer = 'data' in response ? [response.data.buffer] : 'width' in response ? [response] : [];\n    return self.postMessage({ id: message.data.id, content: response, error: err }, transfer);\n}]))}}`)(t)
          , o = URL.createObjectURL((t=>new Blob(["(", "function" == typeof t ? t.toString() : t, ")()"],{
            type: "application/javascript"
        }))(e))
          , i = new Map
          , n = new Worker(o);
        a = {
            url: o,
            worker: n,
            messages: i,
            terminationTimeout: void 0,
            terminate: ()=>{
                clearTimeout(a.terminationTimeout),
                a.worker.terminate(),
                URL.revokeObjectURL(o),
                R.delete(r)
            }
        },
        n.onmessage = function(t) {
            const {id: e, content: o, error: n} = t.data;
            if (clearTimeout(a.terminationTimeout),
            a.terminationTimeout = setTimeout((()=>{
                i.size > 0 || a.terminate()
            }
            ), 500),
            !i.has(e))
                return;
            const r = i.get(e);
            i.delete(e),
            null != n ? r.reject(n) : r.resolve(o)
        }
        ,
        R.set(r, a)
    }
    const s = T();
    a.messages.set(s, {
        resolve: i,
        reject: n
    }),
    a.worker.postMessage({
        id: s,
        content: e
    }, o)
}
))
  , I = async(t,e)=>{
    let o;
    if (k() && !M(t) && "OffscreenCanvas"in window)
        try {
            o = await P(((t,e,o)=>{
                createImageBitmap(t).then((t=>{
                    let i = t.width
                      , n = t.height;
                    const r = i * n;
                    if (e && r > e) {
                        const t = Math.sqrt(e) / Math.sqrt(r);
                        i = Math.floor(i * t),
                        n = Math.floor(n * t)
                    }
                    const a = new OffscreenCanvas(i,n)
                      , s = a.getContext("2d");
                    s.drawImage(t, 0, 0, i, n);
                    const l = s.getImageData(0, 0, a.width, a.height);
                    o(null, l)
                }
                )).catch((t=>{
                    o(t)
                }
                ))
            }
            ), [t, e])
        } catch (t) {}
    if (!o || !o.width) {
        const i = await (async(t,e)=>{
            const o = p("img", {
                src: URL.createObjectURL(t)
            })
              , i = await C(o, {
                canvasMemoryLimit: e
            });
            return URL.revokeObjectURL(o.src),
            i
        }
        )(t, e);
        o = h(i),
        g(i)
    }
    return o
}
  , A = (t,e,o)=>new Promise(((i,n)=>{
    try {
        t.toBlob((t=>{
            i(t)
        }
        ), e, o)
    } catch (t) {
        n(t)
    }
}
))
  , E = async(t,e,o)=>{
    const i = await $(t)
      , n = await A(i, e, o);
    return g(i),
    n
}
  , L = t=>(t.match(/\/([a-z]+)/) || [])[1]
  , F = t=>t.substr(0, t.lastIndexOf(".")) || t;
const z = /avif|bmp|gif|jpg|jpeg|jpe|jif|jfif|png|svg|tiff|webp/;
var B = t=>{
    return t && (e = (o = t,
    o.split(".").pop()).toLowerCase(),
    z.test(e) ? "image/" + (/jfif|jif|jpe|jpg/.test(e) ? "jpeg" : "svg" === e ? "svg+xml" : e) : "");
    var e, o
}
  , O = (t,e,o)=>{
    const n = (new Date).getTime()
      , r = t.type.length && !/null|text/.test(t.type)
      , a = r ? t.type : o
      , s = ((t,e)=>{
        const o = B(t);
        if (o === e)
            return t;
        const i = L(e) || o;
        return `${F(t)}.${i}`
    }
    )(e, a);
    try {
        return new (i("File"))([t],s,{
            lastModified: n,
            type: r ? t.type : a
        })
    } catch (e) {
        const o = r ? t.slice() : t.slice(0, t.size, a);
        return o.lastModified = n,
        o.name = s,
        o
    }
}
  , D = (t,e)=>t / e
  , _ = t=>t;
const W = Math.PI
  , V = Math.PI / 2
  , H = V / 2;
var N = t=>{
    const e = Math.abs(t) % Math.PI;
    return e > H && e < Math.PI - H
}
;
const U = (t,e,o)=>o + (t - o) * e
  , j = t=>({
    x: t.x + .5 * t.width,
    y: t.y + .5 * t.height,
    rx: .5 * t.width,
    ry: .5 * t.height
})
  , X = ()=>Y(0, 0)
  , Y = (t,e)=>({
    x: t,
    y: e
})
  , G = t=>Y(t.x, t.y)
  , q = t=>Y(t.pageX, t.pageY)
  , Z = t=>Y(t.x, t.y)
  , K = t=>(t.x = -t.x,
t.y = -t.y,
t)
  , J = (t,e,o=X())=>{
    const i = Math.cos(e)
      , n = Math.sin(e)
      , r = t.x - o.x
      , a = t.y - o.y;
    return t.x = o.x + i * r - n * a,
    t.y = o.y + n * r + i * a,
    t
}
  , Q = t=>Math.sqrt(t.x * t.x + t.y * t.y)
  , tt = t=>{
    const e = Math.sqrt(t.x * t.x + t.y * t.y);
    return 0 === e ? X() : (t.x /= e,
    t.y /= e,
    t)
}
  , et = (t,e)=>Math.atan2(e.y - t.y, e.x - t.x)
  , ot = (t,e)=>t.x === e.x && t.y === e.y
  , it = (t,e)=>(t.x = e(t.x),
t.y = e(t.y),
t)
  , nt = (t,e)=>(t.x += e.x,
t.y += e.y,
t)
  , rt = (t,e)=>(t.x -= e.x,
t.y -= e.y,
t)
  , at = (t,e)=>(t.x *= e,
t.y *= e,
t)
  , st = (t,e)=>t.x * e.x + t.y * e.y
  , lt = (t,e=X())=>{
    const o = t.x - e.x
      , i = t.y - e.y;
    return o * o + i * i
}
  , ct = (t,e=X())=>Math.sqrt(lt(t, e))
  , dt = t=>{
    let e = 0
      , o = 0;
    return t.forEach((t=>{
        e += t.x,
        o += t.y
    }
    )),
    Y(e / t.length, o / t.length)
}
  , ut = (t,e,o,i,n)=>(t.forEach((t=>{
    t.x = e ? i - (t.x - i) : t.x,
    t.y = o ? n - (t.y - n) : t.y
}
)),
t)
  , ht = (t,e,o,i)=>{
    const n = Math.sin(e)
      , r = Math.cos(e);
    return t.forEach((t=>{
        t.x -= o,
        t.y -= i;
        const e = t.x * r - t.y * n
          , a = t.x * n + t.y * r;
        t.x = o + e,
        t.y = i + a
    }
    )),
    t
}
  , pt = (t,e)=>({
    width: t,
    height: e
})
  , mt = t=>pt(t.width, t.height)
  , gt = t=>pt(t.width, t.height)
  , ft = t=>pt(t.width, t.height)
  , $t = t=>pt(t[0], t[1])
  , yt = t=>{
    return /img/i.test(t.nodeName) ? pt((e = t).naturalWidth, e.naturalHeight) : gt(t);
    var e
}
  , xt = (t,e)=>pt(t, e)
  , bt = (t,e,o=_)=>o(t.width) === o(e.width) && o(t.height) === o(e.height)
  , vt = (t,e)=>(t.width *= e,
t.height *= e,
t)
  , wt = t=>Y(.5 * t.width, .5 * t.height)
  , St = (t,e)=>{
    const o = Math.abs(e)
      , i = Math.cos(o)
      , n = Math.sin(o)
      , r = i * t.width + n * t.height
      , a = n * t.width + i * t.height;
    return t.width = r,
    t.height = a,
    t
}
  , Ct = (t,e)=>t.width >= e.width && t.height >= e.height
  , kt = (t,e)=>(t.width = e(t.width),
t.height = e(t.height),
t)
  , Mt = (t,e)=>({
    start: t,
    end: e
})
  , Tt = t=>Mt(Z(t.start), Z(t.end))
  , Rt = (t,e)=>{
    if (0 === e)
        return t;
    const o = Y(t.start.x - t.end.x, t.start.y - t.end.y)
      , i = tt(o)
      , n = at(i, e);
    return t.start.x += n.x,
    t.start.y += n.y,
    t.end.x -= n.x,
    t.end.y -= n.y,
    t
}
  , Pt = [Y(-1, -1), Y(-1, 1), Y(1, 1), Y(1, -1)]
  , It = (t,e,o,i)=>({
    x: t,
    y: e,
    width: o,
    height: i
})
  , At = t=>It(t.x, t.y, t.width, t.height)
  , Et = ()=>It(0, 0, 0, 0)
  , Lt = t=>It(0, 0, t.width, t.height)
  , Ft = t=>It(t.x || 0, t.y || 0, t.width || 0, t.height || 0)
  , zt = (...t)=>{
    const e = Array.isArray(t[0]) ? t[0] : t;
    let o = e[0].x
      , i = e[0].x
      , n = e[0].y
      , r = e[0].y;
    return e.forEach((t=>{
        o = Math.min(o, t.x),
        i = Math.max(i, t.x),
        n = Math.min(n, t.y),
        r = Math.max(r, t.y)
    }
    )),
    It(o, n, i - o, r - n)
}
  , Bt = t=>Dt(t.x - t.rx, t.y - t.ry, 2 * t.rx, 2 * t.ry)
  , Ot = (t,e)=>It(t.x - .5 * e.width, t.y - .5 * e.height, e.width, e.height)
  , Dt = (t,e,o,i)=>It(t, e, o, i)
  , _t = t=>Y(t.x + .5 * t.width, t.y + .5 * t.height)
  , Wt = (t,e)=>(t.x += e.x,
t.y += e.y,
t)
  , Vt = (t,e,o)=>(o = o || _t(t),
t.x = e * (t.x - o.x) + o.x,
t.y = e * (t.y - o.y) + o.y,
t.width = e * t.width,
t.height = e * t.height,
t)
  , Ht = (t,e)=>(t.x *= e,
t.y *= e,
t.width *= e,
t.height *= e,
t)
  , Nt = (t,e)=>(t.x /= e,
t.y /= e,
t.width /= e,
t.height /= e,
t)
  , Ut = (t,e,o=_)=>o(t.x) === o(e.x) && o(t.y) === o(e.y) && o(t.width) === o(e.width) && o(t.height) === o(e.height)
  , jt = t=>D(t.width, t.height)
  , Xt = (t,e,o,i,n)=>(t.x = e,
t.y = o,
t.width = i,
t.height = n,
t)
  , Yt = (t,e)=>(t.x = e.x,
t.y = e.y,
t.width = e.width,
t.height = e.height,
t)
  , Gt = (t,e,o)=>(o || (o = _t(t)),
te(t).map((t=>J(t, e, o))))
  , qt = (t,e)=>It(.5 * t.width - .5 * e.width, .5 * t.height - .5 * e.height, e.width, e.height)
  , Zt = (t,e)=>!(e.x < t.x) && (!(e.y < t.y) && (!(e.x > t.x + t.width) && !(e.y > t.y + t.height)))
  , Kt = (t,e,o=X())=>{
    if (0 === t.width || 0 === t.height)
        return Et();
    const i = jt(t);
    e || (e = i);
    let n = t.width
      , r = t.height;
    return e > i ? n = r * e : r = n / e,
    It(o.x + .5 * (t.width - n), o.y + .5 * (t.height - r), n, r)
}
  , Jt = (t,e=jt(t),o=X())=>{
    if (0 === t.width || 0 === t.height)
        return Et();
    let i = t.width
      , n = i / e;
    return n > t.height && (n = t.height,
    i = n * e),
    It(o.x + .5 * (t.width - i), o.y + .5 * (t.height - n), i, n)
}
  , Qt = t=>[Math.min(t.y, t.y + t.height), Math.max(t.x, t.x + t.width), Math.max(t.y, t.y + t.height), Math.min(t.x, t.x + t.width)]
  , te = t=>[Y(t.x, t.y), Y(t.x + t.width, t.y), Y(t.x + t.width, t.y + t.height), Y(t.x, t.y + t.height)]
  , ee = (t,e)=>{
    if (t)
        return t.x = e(t.x),
        t.y = e(t.y),
        t.width = e(t.width),
        t.height = e(t.height),
        t
}
  , oe = (t,e,o=_t(t))=>te(t).map(((t,i)=>{
    const n = Pt[i];
    return Y(U(t.x, 1 + n.x * e.x, o.x), U(t.y, 1 + n.y * e.y, o.y))
}
))
  , ie = t=>(t.x = 0,
t.y = 0,
t)
  , ne = t=>{
    const e = t[0]
      , o = t[t.length - 1];
    t = ot(e, o) ? t : [...t, e];
    let i, n, r, a = 0, s = 0, l = 0, c = 0, d = e.x, u = e.y;
    const h = t.length;
    for (; s < h; s++)
        i = t[s],
        n = t[s + 1 > h - 1 ? 0 : s + 1],
        r = (i.y - u) * (n.x - d) - (n.y - u) * (i.x - d),
        a += r,
        l += (i.x + n.x - 2 * d) * r,
        c += (i.y + n.y - 2 * u) * r;
    return r = 3 * a,
    Y(d + l / r, u + c / r)
}
  , re = (t,e)=>ae(t.start, t.end, e.start, e.end)
  , ae = (t,e,o,i)=>{
    const n = (i.y - o.y) * (e.x - t.x) - (i.x - o.x) * (e.y - t.y);
    if (0 === n)
        return;
    const r = ((i.x - o.x) * (t.y - o.y) - (i.y - o.y) * (t.x - o.x)) / n
      , a = ((e.x - t.x) * (t.y - o.y) - (e.y - t.y) * (t.x - o.x)) / n;
    return r < 0 || r > 1 || a < 0 || a > 1 ? void 0 : Y(t.x + r * (e.x - t.x), t.y + r * (e.y - t.y))
}
  , se = (t,e)=>{
    let o = 0
      , i = 0
      , n = !1;
    const r = e.length;
    for (o = 0,
    i = r - 1; o < r; i = o++)
        e[o].y > t.y != e[i].y > t.y && t.x < (e[i].x - e[o].x) * (t.y - e[o].y) / (e[i].y - e[o].y) + e[o].x && (n = !n);
    return n
}
  , le = t=>{
    const e = [];
    for (let o = 0; o < t.length; o++) {
        let i = o + 1;
        i === t.length && (i = 0),
        e.push(Mt(Z(t[o]), Z(t[i])))
    }
    return e
}
  , ce = (t,e,o,i=0,n=!1,r=!1,a=12)=>{
    const s = [];
    for (let i = 0; i < a; i++)
        s.push(Y(t.x + e * Math.cos(i * (2 * Math.PI) / a), t.y + o * Math.sin(i * (2 * Math.PI) / a)));
    return (n || r) && ut(s, n, r, t.x, t.y),
    i && ht(s, i, t.x, t.y),
    s
}
;
var de = (t,e)=>t instanceof HTMLElement && (!e || new RegExp(`^${e}$`,"i").test(t.nodeName))
  , ue = t=>t instanceof File
  , he = t=>t.split("/").pop().split(/\?|\#/).shift();
const pe = c() && !!Node.prototype.replaceChildren ? (t,e)=>t.replaceChildren(e) : (t,e)=>{
    for (; t.lastChild; )
        t.removeChild(t.lastChild);
    void 0 !== e && t.append(e)
}
  , me = c() && p("div", {
    class: "PinturaMeasure",
    style: "pointer-events:none;left:0;top:0;width:0;height:0;contain:strict;overflow:hidden;position:absolute;"
});
let ge;
var fe = t=>(pe(me, t),
me.parentNode || document.body.append(me),
clearTimeout(ge),
ge = setTimeout((()=>{
    me.remove()
}
), 500),
t);
let $e = null;
var ye = ()=>(null === $e && ($e = $e = /^((?!chrome|android).)*safari/i.test(navigator.userAgent)),
$e)
  , xe = t=>new Promise(((e,o)=>{
    let i = !1;
    !t.parentNode && ye() && (i = !0,
    t.style.cssText = "position:absolute;visibility:hidden;pointer-events:none;left:0;top:0;width:0;height:0;",
    fe(t));
    const n = ()=>{
        const o = t.naturalWidth
          , n = t.naturalHeight;
        o && n && (i && t.remove(),
        clearInterval(r),
        e({
            width: o,
            height: n
        }))
    }
    ;
    t.onerror = t=>{
        clearInterval(r),
        o(t)
    }
    ;
    const r = setInterval(n, 1);
    n()
}
))
  , be = async t=>{
    let e, o = t;
    o.src || (o = new Image,
    o.src = w(t) ? t : URL.createObjectURL(t));
    try {
        e = await xe(o)
    } finally {
        ue(t) && URL.revokeObjectURL(o.src)
    }
    return e
}
;
var ve = async t=>{
    try {
        const e = await be(t)
          , o = await (t=>new Promise(((e,o)=>{
            if (t.complete)
                return e(t);
            t.onload = ()=>e(t),
            t.onerror = o
        }
        )))(t)
          , i = document.createElement("canvas");
        i.width = e.width,
        i.height = e.height;
        i.getContext("2d").drawImage(o, 0, 0);
        const n = await A(i);
        return O(n, he(o.src))
    } catch (t) {
        throw t
    }
}
  , we = (t=0,e=!0)=>new (i("ProgressEvent"))("progress",{
    loaded: 100 * t,
    total: 100,
    lengthComputable: e
})
  , Se = t=>/^image/.test(t.type)
  , Ce = (t,e,o=(t=>t))=>t.getAllResponseHeaders().indexOf(e) >= 0 ? o(t.getResponseHeader(e)) : void 0
  , ke = t=>{
    if (!t)
        return null;
    const e = t.split(/filename=|filename\*=.+''/).splice(1).map((t=>t.trim().replace(/^["']|[;"']{0,2}$/g, ""))).filter((t=>t.length));
    return e.length ? decodeURI(e[e.length - 1]) : null
}
;
const Me = "URL_REQUEST";
class Te extends Error {
    constructor(t, e, o) {
        super(t),
        this.name = "EditorError",
        this.code = e,
        this.metadata = o
    }
}
var Re = (t,e)=>/^data:/.test(t) ? (async(t,e="data-uri",o=n)=>{
    o(we(0));
    const i = await fetch(t);
    o(we(.33));
    const r = await i.blob();
    let a;
    Se(r) || (a = "image/" + (t.includes(",/9j/") ? "jpeg" : "png")),
    o(we(.66));
    const s = O(r, e, a);
    return o(we(1)),
    s
}
)(t, void 0, e) : ((t,e)=>new Promise(((o,i)=>{
    const n = ()=>i(new Te("Error fetching image",Me,r))
      , r = new XMLHttpRequest;
    r.onprogress = e,
    r.onerror = n,
    r.onload = ()=>{
        if (!r.response || r.status >= 300 || r.status < 200)
            return n();
        const e = Ce(r, "Content-Type")
          , i = Ce(r, "Content-Disposition", ke) || he(t);
        o(O(r.response, i, e || B(i)))
    }
    ,
    r.open("GET", t),
    r.responseType = "blob",
    r.send()
}
)))(t, e)
  , Pe = async(t,e)=>{
    if (ue(t) || (o = t)instanceof Blob && !(o instanceof File))
        return t;
    if (w(t))
        return await Re(t, e);
    if (de(t, "canvas"))
        return await (async(t,e,o)=>{
            const i = await A(t, e, o);
            return O(i, "canvas")
        }
        )(t);
    if (de(t, "img"))
        return await ve(t);
    throw new Te("Invalid image source","invalid-image-source");
    var o
}
;
let Ie = null;
var Ae = ()=>(null === Ie && (Ie = c() && /^mac/i.test(navigator.platform)),
Ie)
  , Ee = t=>c() ? RegExp(t).test(window.navigator.userAgent) : void 0;
let Le = null;
var Fe = ()=>(null === Le && (Le = c() && (Ee(/iPhone|iPad|iPod/) || Ae() && navigator.maxTouchPoints >= 1)),
Le)
  , ze = async(t,e=1)=>await u() || Fe() || e < 5 ? t : xt(t.height, t.width)
  , Be = t=>/jpeg/.test(t.type)
  , Oe = t=>{
    return "object" != typeof (e = t) || e.constructor != Object ? t : JSON.stringify(t);
    var e
}
  , De = (t,e=0,o)=>(0 === e || (t.translate(o.x, o.y),
t.rotate(e),
t.translate(-o.x, -o.y)),
t)
  , _e = async(t,e={})=>{
    const {flipX: o, flipY: i, rotation: n, crop: r} = e
      , a = gt(t)
      , s = o || i
      , l = !!n
      , c = r && (r.x || r.y || r.width || r.height)
      , d = c && Ut(r, Lt(a))
      , u = c && !d;
    if (!s && !l && !u)
        return t;
    let h, m = p("canvas", {
        width: t.width,
        height: t.height
    });
    if (m.getContext("2d").putImageData(t, 0, 0),
    s) {
        const t = p("canvas", {
            width: m.width,
            height: m.height
        }).getContext("2d");
        ((t,e,o)=>{
            t.scale(e, o)
        }
        )(t, o ? -1 : 1, i ? -1 : 1),
        t.drawImage(m, o ? -m.width : 0, i ? -m.height : 0),
        t.restore(),
        g(m),
        m = t.canvas
    }
    if (l) {
        const t = kt(ft(zt(Gt(Ft(m), n))), Math.floor)
          , e = p("canvas", {
            width: r.width,
            height: r.height
        }).getContext("2d");
        ((t,e,o)=>{
            t.translate(e, o)
        }
        )(e, -r.x, -r.y),
        De(e, n, wt(t)),
        e.drawImage(m, .5 * (t.width - m.width), .5 * (t.height - m.height)),
        e.restore(),
        g(m),
        m = e.canvas
    } else if (u) {
        return h = m.getContext("2d").getImageData(r.x, r.y, r.width, r.height),
        g(m),
        h
    }
    return h = m.getContext("2d").getImageData(0, 0, m.width, m.height),
    g(m),
    h
}
  , We = (t,e)=>{
    const {imageData: o, width: i, height: n} = t
      , r = o.width
      , a = o.height
      , s = Math.round(i)
      , l = Math.round(n)
      , c = o.data
      , d = new Uint8ClampedArray(s * l * 4)
      , u = r / s
      , h = a / l
      , p = Math.ceil(.5 * u)
      , m = Math.ceil(.5 * h);
    for (let t = 0; t < l; t++)
        for (let e = 0; e < s; e++) {
            const o = 4 * (e + t * s);
            let i = 0
              , n = 0
              , a = 0
              , l = 0
              , g = 0
              , f = 0
              , $ = 0;
            const y = (t + .5) * h;
            for (let o = Math.floor(t * h); o < (t + 1) * h; o++) {
                const t = Math.abs(y - (o + .5)) / m
                  , s = (e + .5) * u
                  , d = t * t;
                for (let t = Math.floor(e * u); t < (e + 1) * u; t++) {
                    let e = Math.abs(s - (t + .5)) / p;
                    const u = Math.sqrt(d + e * e);
                    if (u < -1 || u > 1)
                        continue;
                    if (i = 2 * u * u * u - 3 * u * u + 1,
                    i <= 0)
                        continue;
                    e = 4 * (t + o * r);
                    const h = c[e + 3];
                    $ += i * h,
                    a += i,
                    h < 255 && (i = i * h / 250),
                    l += i * c[e],
                    g += i * c[e + 1],
                    f += i * c[e + 2],
                    n += i
                }
            }
            d[o] = l / n,
            d[o + 1] = g / n,
            d[o + 2] = f / n,
            d[o + 3] = $ / a
        }
    e(null, {
        data: d,
        width: s,
        height: l
    })
}
  , Ve = t=>{
    if (t instanceof ImageData)
        return t;
    let e;
    try {
        e = new ImageData(t.width,t.height)
    } catch (o) {
        e = p("canvas").getContext("2d").createImageData(t.width, t.height)
    }
    return e.data.set(t.data),
    e
}
  , He = async(t,e={},o)=>{
    const {width: i, height: n, fit: r, upscale: a} = e;
    if (!i && !n)
        return t;
    let s = i
      , l = n;
    if (i ? n || (l = i) : s = n,
    "force" !== r) {
        const e = s / t.width
          , o = l / t.height;
        let i = 1;
        if ("cover" === r ? i = Math.max(e, o) : "contain" === r && (i = Math.min(e, o)),
        i > 1 && !1 === a)
            return t;
        s = Math.round(t.width * i),
        l = Math.round(t.height * i)
    }
    return t.width === s && t.height === l ? t : o ? o(t, s, l) : (t = await P(We, [{
        imageData: t,
        width: s,
        height: l
    }], [t.data.buffer]),
    Ve(t))
}
  , Ne = (t,e)=>{
    const {imageData: o, matrix: i} = t;
    if (!i)
        return e(null, o);
    const n = new Uint8ClampedArray(o.width * o.height * 4)
      , r = o.data
      , a = r.length
      , s = i[0]
      , l = i[1]
      , c = i[2]
      , d = i[3]
      , u = i[4]
      , h = i[5]
      , p = i[6]
      , m = i[7]
      , g = i[8]
      , f = i[9]
      , $ = i[10]
      , y = i[11]
      , x = i[12]
      , b = i[13]
      , v = i[14]
      , w = i[15]
      , S = i[16]
      , C = i[17]
      , k = i[18]
      , M = i[19];
    let T = 0
      , R = 0
      , P = 0
      , I = 0
      , A = 0
      , E = 0
      , L = 0
      , F = 0
      , z = 0
      , B = 0
      , O = 0
      , D = 0;
    for (; T < a; T += 4)
        R = r[T] / 255,
        P = r[T + 1] / 255,
        I = r[T + 2] / 255,
        A = r[T + 3] / 255,
        E = R * s + P * l + I * c + A * d + u,
        L = R * h + P * p + I * m + A * g + f,
        F = R * $ + P * y + I * x + A * b + v,
        z = R * w + P * S + I * C + A * k + M,
        B = Math.max(0, E * z) + (1 - z),
        O = Math.max(0, L * z) + (1 - z),
        D = Math.max(0, F * z) + (1 - z),
        n[T] = 255 * Math.max(0, Math.min(1, B)),
        n[T + 1] = 255 * Math.max(0, Math.min(1, O)),
        n[T + 2] = 255 * Math.max(0, Math.min(1, D)),
        n[T + 3] = 255 * A;
    e(null, {
        data: n,
        width: o.width,
        height: o.height
    })
}
  , Ue = (t,e)=>{
    const {imageData: o, matrix: i} = t;
    if (!i)
        return e(null, o);
    let n = i.reduce(((t,e)=>t + e));
    n = n <= 0 ? 1 : n;
    const r = o.width
      , a = o.height
      , s = o.data;
    let l = 0
      , c = 0
      , d = 0;
    const u = Math.round(Math.sqrt(i.length))
      , h = Math.floor(u / 2);
    let p = 0
      , m = 0
      , g = 0
      , f = 0
      , $ = 0
      , y = 0
      , x = 0
      , b = 0
      , v = 0
      , w = 0;
    const S = new Uint8ClampedArray(r * a * 4);
    for (d = 0; d < a; d++)
        for (c = 0; c < r; c++) {
            for (p = 0,
            m = 0,
            g = 0,
            f = 0,
            y = 0; y < u; y++)
                for ($ = 0; $ < u; $++)
                    x = d + y - h,
                    b = c + $ - h,
                    x < 0 && (x = a - 1),
                    x >= a && (x = 0),
                    b < 0 && (b = r - 1),
                    b >= r && (b = 0),
                    v = 4 * (x * r + b),
                    w = i[y * u + $],
                    p += s[v] * w,
                    m += s[v + 1] * w,
                    g += s[v + 2] * w,
                    f += s[v + 3] * w;
            S[l] = p / n,
            S[l + 1] = m / n,
            S[l + 2] = g / n,
            S[l + 3] = f / n,
            l += 4
        }
    e(null, {
        data: S,
        width: r,
        height: a
    })
}
  , je = (t,e)=>{
    let {imageData: o, strength: i} = t;
    if (!i)
        return e(null, o);
    const n = new Uint8ClampedArray(o.width * o.height * 4)
      , r = o.width
      , a = o.height
      , s = o.data
      , l = (t,e)=>(c = t - w,
    d = e - S,
    Math.sqrt(c * c + d * d));
    let c, d, u, h, p, m, g, f, $, y, x, b = 0, v = 0, w = .5 * r, S = .5 * a, C = l(0, 0);
    for (i > 0 ? (u = 0,
    h = 0,
    p = 0) : (i = Math.abs(i),
    u = 1,
    h = 1,
    p = 1),
    v = 0; v < a; v++)
        for (b = 0; b < r; b++)
            k = 4 * (b + v * r),
            M = s,
            T = n,
            R = l(b, v) * i / C,
            m = M[k] / 255,
            g = M[k + 1] / 255,
            f = M[k + 2] / 255,
            $ = M[k + 3] / 255,
            y = 1 - R,
            x = y * $ + R,
            T[k] = (y * $ * m + R * u) / x * 255,
            T[k + 1] = (y * $ * g + R * h) / x * 255,
            T[k + 2] = (y * $ * f + R * p) / x * 255,
            T[k + 3] = 255 * x;
    var k, M, T, R;
    e(null, {
        data: n,
        width: o.width,
        height: o.height
    })
}
  , Xe = (t,e)=>{
    const {imageData: o, level: i, monochrome: n=!1} = t;
    if (!i)
        return e(null, o);
    const r = new Uint8ClampedArray(o.width * o.height * 4)
      , a = o.data
      , s = a.length;
    let l, c, d, u = 0;
    const h = ()=>255 * (2 * Math.random() - 1) * i
      , p = n ? ()=>{
        const t = h();
        return [t, t, t]
    }
    : ()=>[h(), h(), h()];
    for (; u < s; u += 4)
        [l,c,d] = p(),
        r[u] = a[u] + l,
        r[u + 1] = a[u + 1] + c,
        r[u + 2] = a[u + 2] + d,
        r[u + 3] = a[u + 3];
    e(null, {
        data: r,
        width: o.width,
        height: o.height
    })
}
  , Ye = (t,e)=>{
    const {imageData: o, level: i} = t;
    if (!i)
        return e(null, o);
    const n = new Uint8ClampedArray(o.width * o.height * 4)
      , r = o.data
      , a = r.length;
    let s, l, c, d = 0;
    for (; d < a; d += 4)
        s = r[d] / 255,
        l = r[d + 1] / 255,
        c = r[d + 2] / 255,
        n[d] = 255 * Math.pow(s, i),
        n[d + 1] = 255 * Math.pow(l, i),
        n[d + 2] = 255 * Math.pow(c, i),
        n[d + 3] = r[d + 3];
    e(null, {
        data: n,
        width: o.width,
        height: o.height
    })
}
  , Ge = async(t,e={})=>{
    const {colorMatrix: o, convolutionMatrix: i, gamma: n, noise: r, vignette: a} = e
      , s = [];
    if (i && s.push([Ue, {
        matrix: i.clarity
    }]),
    n > 0 && s.push([Ye, {
        level: 1 / n
    }]),
    o && !(t=>{
        const e = t.length;
        let o, i = e >= 20 ? 6 : e >= 16 ? 5 : 3;
        for (let n = 0; n < e; n++) {
            if (o = t[n],
            1 === o && n % i != 0)
                return !1;
            if (0 !== o && 1 !== o)
                return !1
        }
        return !0
    }
    )(o) && s.push([Ne, {
        matrix: o
    }]),
    (r > 0 || r < 0) && s.push([Xe, {
        level: r
    }]),
    (a > 0 || a < 0) && s.push([je, {
        strength: a
    }]),
    !s.length)
        return t;
    const l = (t,e)=>`(err, imageData) => {\n            (${t[e][0].toString()})(Object.assign({ imageData: imageData }, filterInstructions[${e}]), \n                ${t[e + 1] ? l(t, e + 1) : "done"})\n        }`
      , c = `function (options, done) {\n        const filterInstructions = options.filterInstructions;\n        const imageData = options.imageData;\n        (${l(s, 0)})(null, imageData)\n    }`;
    return t = await P(c, [{
        imageData: t,
        filterInstructions: s.map((t=>t[1]))
    }], [t.data.buffer]),
    Ve(t)
}
  , qe = t=>"number" == typeof t
  , Ze = t=>w(t) && null !== t.match(/(?:[\u2700-\u27bf]|(?:\ud83c[\udde6-\uddff]){2}|[\ud800-\udbff][\udc00-\udfff]|[\u0023-\u0039]\ufe0f?\u20e3|\u3299|\u3297|\u303d|\u3030|\u24c2|\ud83c[\udd70-\udd71]|\ud83c[\udd7e-\udd7f]|\ud83c\udd8e|\ud83c[\udd91-\udd9a]|\ud83c[\udde6-\uddff]|\ud83c[\ude01-\ude02]|\ud83c\ude1a|\ud83c\ude2f|\ud83c[\ude32-\ude3a]|\ud83c[\ude50-\ude51]|\u203c|\u2049|[\u25aa-\u25ab]|\u25b6|\u25c0|[\u25fb-\u25fe]|\u00a9|\u00ae|\u2122|\u2139|\ud83c\udc04|[\u2600-\u26FF]|\u2b05|\u2b06|\u2b07|\u2b1b|\u2b1c|\u2b50|\u2b55|\u231a|\u231b|\u2328|\u23cf|[\u23e9-\u23f3]|[\u23f8-\u23fa]|\ud83c\udccf|\u2934|\u2935|[\u2190-\u21ff])/g)
  , Ke = (t,e)=>t.hasOwnProperty(e)
  , Je = t=>Array.isArray(t);
let Qe = 64
  , to = 102
  , eo = 112
  , oo = !1;
var io = (t,e)=>(!oo && c() && (/^win/i.test(navigator.platform) && (to = 103),
(Fe() || Ae()) && (Qe = 63.5,
to = 110,
eo = 123),
oo = !0),
`<svg${e ? ` aria-label="${e}"` : ""} width="128" height="128" viewBox="0 0 128 128" preserveAspectRatio="xMinYMin meet" xmlns="http://www.w3.org/2000/svg"><text x="${Qe}" y="${to}" alignment-baseline="text-top" dominant-baseline="text-top" text-anchor="middle" font-size="${eo}px">${t}</text></svg>`)
  , no = t=>t instanceof Blob
  , ro = (t,e)=>t / e * 100 + "%"
  , ao = t=>`rgba(${Math.round(255 * t[0])}, ${Math.round(255 * t[1])}, ${Math.round(255 * t[2])}, ${qe(t[3]) ? t[3] : 1})`
  , so = t=>Object.values(t).join("_");
const lo = async(t,e=0)=>{
    const o = p("canvas", {
        width: 80,
        height: 80
    }).getContext("2d");
    return await ((t=0)=>new Promise((e=>{
        setTimeout(e, t)
    }
    )))(e),
    o.drawImage(t, 0, 0, 80, 80),
    !((t=>!new Uint32Array(t.getImageData(0, 0, t.canvas.width, t.canvas.height).data.buffer).some((t=>0 !== t)))(o) && e <= 256) || await lo(t, e + 16)
}
  , co = new Map;
var uo = t=>new Promise(((e,o)=>{
    const i = new FileReader;
    i.onerror = o,
    i.onload = ()=>e(i.result),
    i.readAsDataURL(t)
}
))
  , ho = ()=>{
    let t = [];
    return {
        sub: (e,o)=>(t.push({
            event: e,
            callback: o
        }),
        ()=>t = t.filter((t=>t.event !== e || t.callback !== o))),
        pub: (e,o)=>{
            t.filter((t=>t.event === e)).forEach((t=>t.callback(o)))
        }
    }
}
;
const po = 32
  , mo = ({color: t=[0, 0, 0], fontSize: e=16, fontFamily: o="sans-serif", fontVariant: i="normal", fontWeight: n="normal", fontStyle: r="normal", textAlign: a="left", lineHeight: s=20})=>`font-size:${e}px;font-style:${r};font-weight:${n};font-family:${o};font-variant:${i};line-height:${s}px;text-align:${a};color:${ao(t)};`
  , go = t=>{
    const {width: e, height: o} = t
      , i = !e
      , n = i ? "normal" : "break-word"
      , r = i ? "nowrap" : "pre-line";
    return `max-width:none;min-width:auto;width:${i ? "auto" : e + "px"};height:${o ? o + "px" : "auto"};margin-top:0;margin-bottom:0;padding-top:${(({fontSize: t=16, lineHeight: e=20}={})=>.5 * Math.max(0, t - e))(t)}px;word-break:${n};word-wrap:normal;white-space:${r};overflow:visible;`
}
  , fo = new Map
  , $o = new Map
  , yo = (t="",e)=>{
    const {width: o=0, height: i=0} = e;
    if (o && i)
        return xt(o, i);
    const {fontSize: n, fontFamily: r, lineHeight: a, fontWeight: s, fontStyle: l, fontVariant: c} = e
      , d = so({
        text: t,
        fontFamily: r,
        fontWeight: s,
        fontStyle: l,
        fontVariant: c,
        fontSize: n,
        lineHeight: a,
        width: o
    });
    let u = $o.get(d);
    if (u)
        return u;
    const h = fe(p("pre", {
        contenteditable: "true",
        spellcheck: "false",
        style: `pointer-events:none;visibility:hidden;position:absolute;${mo(e)};${go(e)}"`,
        innerHTML: t
    }, [p("span")])).getBoundingClientRect();
    return u = gt(h),
    u.height += Math.max(0, n - a),
    $o.set(d, u),
    u
}
  , xo = new Map
  , bo = t=>new Promise(((e,o)=>{
    let i = xo.get(t);
    i || (i = (t=>{
        const {sub: e, pub: o} = ho();
        let i, n;
        return fetch(t).then((t=>t.text())).then((t=>{
            i = t,
            o("load", i)
        }
        )).catch((t=>{
            n = t,
            o("error", n)
        }
        )),
        {
            sub: (t,o)=>"load" === t && i ? o(i) : "error" === t && n ? o(n) : void e(t, o)
        }
    }
    )(t),
    xo.set(t, i)),
    i.sub("load", e),
    i.sub("error", o)
}
))
  , vo = new Map
  , wo = t=>t.filter((t=>t instanceof CSSFontFaceRule))
  , So = async(t,e=(()=>!0))=>{
    if (vo.has(t.href))
        return vo.get(t.href);
    let o;
    try {
        o = wo(Array.from(t.cssRules))
    } catch (i) {
        const n = t.href;
        if (!e(n))
            return vo.set(n, []),
            [];
        o = wo(await (async t=>{
            let e;
            try {
                e = await bo(t)
            } catch (t) {
                return []
            }
            const o = p("style", {
                innerHTML: e,
                id: T()
            });
            document.head.append(o);
            const i = Array.from(document.styleSheets).find((t=>t.ownerNode.id === o.id));
            return o.remove(),
            Array.from(i.cssRules)
        }
        )(n)),
        vo.set(n, o)
    }
    return o
}
  , Co = (t,e)=>t.style.getPropertyValue(e)
  , ko = (t,e)=>Co(t, "font-family").replace(/^"|"$/g, "") == e
  , Mo = async(t,e)=>{
    const o = ((t,e)=>{
        const o = [];
        for (const i of t)
            ko(i, e) && o.push(i);
        return o
    }
    )(await (async t=>{
        const e = Array.from(document.styleSheets).map((e=>So(e, t)))
          , o = await Promise.all(e)
          , i = [];
        return o.forEach((t=>i.push(...t))),
        i
    }
    )(e), t);
    return o.length ? o.map((t=>{
        const e = t.parentStyleSheet.href && new URL(t.parentStyleSheet.href)
          , o = e ? e.origin + (t=>t.pathname.split("/").slice(0, -1).join("/"))(e) + "/" : ""
          , i = t.style.getPropertyValue("src").match(/url\("?(.*?)"?\)/)[1]
          , n = Array.from(t.style).filter((t=>"src" != t)).reduce(((e,o)=>e += o + ":" + Co(t, o) + ";"), "");
        return [/^http/.test(i) ? i : o + i, n]
    }
    )) : []
}
  , To = new Map
  , Ro = new Map;
var Po = async(t="",e)=>{
    if (!t.length)
        return;
    const {imageWidth: o=300, imageHeight: i=150, paddingLeft: n=po, paddingRight: r=po, fontFamily: a, willRequestResource: s} = e
      , l = 1 * (o + n + r)
      , c = 1 * i
      , d = mo(e)
      , u = go(e)
      , h = await (async(t,e)=>{
        if (To.get(t))
            return;
        let o = Ro.get(t);
        if (!o) {
            const i = await Mo(t, e);
            if (!i.length)
                return void To.set(t, !0);
            const n = [];
            for (const [t,e] of i) {
                const o = await fetch(t).then((t=>t.blob()))
                  , i = o.type.split("/")[1] || "woff2"
                  , r = await uo(o);
                n.push(`@font-face { src:url(${r}) format('${i}');${e};font-display:block; }`)
            }
            o = n.join(""),
            Ro.set(t, o)
        }
        return o
    }
    )(a, s);
    return ((t,{safariCacheKey: e="*"}={})=>new Promise(((o,i)=>{
        const n = new Image;
        n.onerror = i,
        n.onload = ()=>{
            if (!ye() || !t.includes("@font-face") || co.has(e))
                return o(n);
            lo(n).then((()=>{
                co.set(e, !0),
                o(n)
            }
            ))
        }
        ,
        n.src = "data:image/svg+xml," + t
    }
    )))(`<svg xmlns="http://www.w3.org/2000/svg" width="${l}" height="${c}" viewBox="0 0 ${l} ${c}"><foreignObject x="0" y="0" width="${o + n + r}" height="${i}" transform="scale(1)"><div xmlns="http://www.w3.org/1999/xhtml">${h ? `<style>${h}</style>` : ""}<pre contenteditable="true" spellcheck="false" style="position:absolute;padding-right:${r}px;padding-left:${n}px;${d};${u}">${t.replace(/&/g, "&amp;").replace(/#/g, "%23").replace(/<br>/g, "<br/>").replace(/\n/g, "<br/>")}</pre></div></foreignObject></svg>`, {
        safariCacheKey: a
    })
}
  , Io = (t,e=12)=>parseFloat(t.toFixed(e));
const Ao = t=>({
    ...t
})
  , Eo = t=>{
    const e = {
        ...t
    };
    return v(e)
}
  , Lo = (t,e={})=>{
    const o = jt(t);
    let i, n;
    const r = e.width || e.rx
      , a = e.height || e.ry;
    if (r && a)
        return mt(e);
    if (r || a) {
        i = parseFloat(r || Number.MAX_SAFE_INTEGER),
        n = parseFloat(a || Number.MAX_SAFE_INTEGER);
        const t = Math.min(i, n);
        w(r) || w(a) ? (i = t + "%",
        n = t * o + "%") : (i = t,
        n = t)
    } else {
        const t = 10;
        i = t + "%",
        n = t * o + "%"
    }
    return {
        [(e.width ? "width" : e.rx ? "rx" : void 0) || "width"]: i,
        [(e.width ? "height" : e.rx ? "ry" : void 0) || "height"]: n
    }
}
  , Fo = (t,e={})=>{
    return {
        width: void 0,
        height: void 0,
        ...e,
        aspectRatio: 1,
        backgroundImage: (o = io(t),
        "data:image/svg+xml," + o.replace("<", "%3C").replace(">", "%3E"))
    };
    var o
}
  , zo = (t,e={})=>({
    backgroundColor: [0, 0, 0, 0],
    ...No(e) ? {} : {
        width: void 0,
        height: void 0,
        aspectRatio: void 0
    },
    ...e,
    backgroundImage: w(t) ? t : no(t) ? URL.createObjectURL(t) : t
})
  , Bo = (t,e)=>{
    let o;
    if (w(t) || no(t)) {
        const i = {
            ...Lo(e),
            backgroundSize: "contain"
        };
        o = Ze(t) ? Fo(t, i) : zo(t, i)
    } else if (t.src) {
        const i = Lo(e, t.shape || t)
          , n = {
            ...t.shape,
            ...i
        };
        if (t.width && t.height && !Ke(n, "aspectRatio")) {
            const t = Mi(i, "width", e)
              , o = Mi(i, "height", e);
            n.aspectRatio = D(t, o)
        }
        n.backgroundSize || t.shape || t.width && t.height || (n.backgroundSize = "contain"),
        o = Ze(t.src) ? Fo(t.src, n) : zo(t.src, n)
    } else
        t.shape && (o = Eo(t.shape));
    return Ke(o, "backgroundImage") && (Ke(o, "backgroundColor") || (o.backgroundColor = [0, 0, 0, 0]),
    Ke(o, "disableStyle") || (o.disableStyle = ["backgroundColor", "strokeColor", "strokeWidth"]),
    Ke(o, "disableFlip") || (o.disableFlip = !0)),
    e ? Ci(o, e) : o
}
  , Oo = t=>Y(t.x1, t.y1)
  , Do = t=>Y(t.x2, t.y2)
  , _o = t=>Ke(t, "text")
  , Wo = t=>_o(t) && !(Qo(t) || Ke(t, "width"))
  , Vo = t=>_o(t) && (Qo(t) || Ke(t, "width"))
  , Ho = t=>!_o(t) && ti(t)
  , No = t=>Ke(t, "rx")
  , Uo = t=>Ke(t, "x1") && !jo(t)
  , jo = t=>Ke(t, "x3")
  , Xo = t=>Ke(t, "points")
  , Yo = t=>_o(t) && t.isEditing
  , Go = t=>!Ke(t, "opacity") || t.opacity > 0
  , qo = t=>t.isSelected
  , Zo = t=>t._isDraft
  , Ko = t=>Ke(t, "width") && Ke(t, "height")
  , Jo = t=>{
    const e = Ke(t, "right")
      , o = Ke(t, "bottom");
    return e || o
}
  , Qo = t=>(Ke(t, "x") || Ke(t, "left")) && Ke(t, "right") || (Ke(t, "y") || Ke(t, "top")) && Ke(t, "bottom")
  , ti = t=>Ko(t) || Qo(t)
  , ei = t=>(t._isDraft = !0,
t)
  , oi = (t,e)=>!0 !== t.disableStyle && (!Je(t.disableStyle) || !e || !t.disableStyle.includes(e))
  , ii = t=>!0 !== t.disableSelect && !jo(t)
  , ni = t=>!0 !== t.disableRemove
  , ri = t=>!t.disableFlip && (!Zo(t) && !Jo(t) && (t=>Ke(t, "backgroundImage") || Ke(t, "text"))(t))
  , ai = (t,e)=>!!_o(t) && (!0 !== t.disableInput && (S(t.disableInput) ? t.disableInput(null != e ? e : t.text) : e || !0))
  , si = (t,e)=>!0 !== t.disableTextLayout && (!Je(t.disableTextLayout) || !e || !t.disableTextLayout.includes(e))
  , li = t=>!0 !== t.disableManipulate && !Zo(t) && !Jo(t)
  , ci = t=>li(t) && !0 !== t.disableMove
  , di = t=>(delete t.left,
delete t.right,
delete t.top,
delete t.bottom,
t)
  , ui = t=>(delete t.rotation,
t)
  , hi = t=>(t.strokeWidth = t.strokeWidth || 1,
t.strokeColor = t.strokeColor || [0, 0, 0],
t)
  , pi = t=>(t.backgroundColor = t.backgroundColor ? t.backgroundColor : t.strokeWidth || t.backgroundImage ? void 0 : [0, 0, 0],
t)
  , mi = t=>(delete t.textAlign,
di(t))
  , gi = t=>(t.textAlign = t.textAlign || "left",
t)
  , fi = t=>((t=>{
    w(t.id) || (t.id = T()),
    Ke(t, "rotation") || (t.rotation = 0),
    Ke(t, "opacity") || (t.opacity = 1),
    Ke(t, "disableErase") || (t.disableErase = !0)
}
)(t),
_o(t) ? (t=>{
    t.fontSize = t.fontSize || "4%",
    t.fontFamily = t.fontFamily || "sans-serif",
    t.fontWeight = t.fontWeight || "normal",
    t.fontStyle = t.fontStyle || "normal",
    t.fontVariant = t.fontVariant || "normal",
    t.lineHeight = t.lineHeight || "120%",
    t.color = t.color || [0, 0, 0],
    Wo(t) ? mi(t) : gi(t)
}
)(t) : Ho(t) ? (t=>{
    t.cornerRadius = t.cornerRadius || 0,
    t.strokeWidth = t.strokeWidth || 0,
    t.strokeColor = t.strokeColor || [0, 0, 0],
    pi(t)
}
)(t) : Xo(t) ? (t=>{
    hi(t),
    ui(t),
    di(t)
}
)(t) : Uo(t) ? (t=>{
    hi(t),
    t.lineStart = t.lineStart || void 0,
    t.lineEnd = t.lineEnd || void 0,
    ui(t),
    di(t)
}
)(t) : No(t) ? (t=>{
    t.strokeWidth = t.strokeWidth || 0,
    t.strokeColor = t.strokeColor || [0, 0, 0],
    pi(t)
}
)(t) : jo(t) && (t=>{
    t.strokeWidth = t.strokeWidth || 0,
    t.strokeColor = t.strokeColor || [0, 0, 0],
    pi(t),
    di(t)
}
)(t),
t)
  , $i = t=>_o(t) ? "text" : Ho(t) ? "rectangle" : Xo(t) ? "path" : Uo(t) ? "line" : No(t) ? "ellipse" : jo(t) ? "triangle" : void 0
  , yi = (t,e)=>parseFloat(t) / 100 * e
  , xi = new RegExp(/^x|left|^width|rx|fontSize|cornerRadius|strokeWidth/,"i")
  , bi = new RegExp(/^y|top|^height|ry/,"i")
  , vi = new RegExp(/right/,"i")
  , wi = new RegExp(/bottom/,"i")
  , Si = (t,e)=>{
    Object.entries(t).map((([o,i])=>{
        t[o] = ((t,e,{width: o, height: i})=>{
            if (Array.isArray(e))
                return e.map((t=>(x(t) && Si(t, {
                    width: o,
                    height: i
                }),
                t)));
            if ("string" != typeof e)
                return e;
            if (!e.endsWith("%"))
                return e;
            const n = parseFloat(e) / 100;
            return xi.test(t) ? Io(o * n, 6) : bi.test(t) ? Io(i * n, 6) : vi.test(t) ? Io(o - o * n, 6) : wi.test(t) ? Io(i - i * n, 6) : e
        }
        )(o, i, e)
    }
    ));
    const o = t.lineHeight;
    w(o) && (t.lineHeight = Math.round(t.fontSize * (parseFloat(o) / 100)))
}
  , Ci = (t,e)=>(Si(t, e),
Ai(t, e),
t)
  , ki = (t,e)=>{
    let o;
    return /^x|width|rx|fontSize|strokeWidth|cornerRadius/.test(t) ? o = e.width : /^y|height|ry/.test(t) && (o = e.height),
    o
}
  , Mi = (t,e,o)=>w(t[e]) ? yi(t[e], ki(e, o)) : t[e]
  , Ti = (t,e,o)=>e.reduce(((e,i)=>{
    const n = Mi(t, i, o);
    return e[i] = n,
    e
}
), {})
  , Ri = (t,e,o)=>(Object.keys(e).forEach((i=>((t,e,o,i)=>{
    if (!w(t[e]))
        return t[e] = o,
        t;
    const n = ki(e, i);
    return t[e] = void 0 === n ? o : ro(o, n),
    t
}
)(t, i, e[i], o))),
t)
  , Pi = (t,e)=>{
    const o = t.filter((t=>t.x < 0 || t.y < 0 || t.x1 < 0 || t.y1 < 0)).reduce(((t,e)=>{
        const [o,i,n,r] = (t=>{
            const e = Et()
              , o = t.strokeWidth || 0;
            if (Ho(t))
                e.x = t.x - .5 * o,
                e.y = t.y - .5 * o,
                e.width = t.width + o,
                e.height = t.height + o;
            else if (Uo(t)) {
                const {x1: i, y1: n, x2: r, y2: a} = t
                  , s = Math.abs(Math.min(i, r))
                  , l = Math.abs(Math.max(i, r))
                  , c = Math.abs(Math.min(n, a))
                  , d = Math.abs(Math.min(n, a));
                e.x = s + .5 * o,
                e.y = l + .5 * o,
                e.width = l - s + o,
                e.height = d - c + o
            } else
                No(t) && (e.x = t.x - t.rx + .5 * o,
                e.y = t.y - t.ry + .5 * o,
                e.width = 2 * t.rx + o,
                e.height = 2 * t.ry + o);
            return e && Ke(t, "rotation") && Gt(e, t.rotation),
            Qt(e)
        }
        )(e);
        return t.top = Math.min(o, t.top),
        t.left = Math.min(r, t.left),
        t.bottom = Math.max(n, t.bottom),
        t.right = Math.max(i, t.right),
        t
    }
    ), {
        top: 0,
        right: 0,
        bottom: 0,
        left: 0
    });
    return o.right > 0 && (o.right -= e.width),
    o.bottom > 0 && (o.bottom -= e.height),
    o
}
  , Ii = (t,e,o)=>{
    const i = Eo(t);
    return Ci(i, e),
    o(i)
}
  , Ai = (t,e)=>{
    if (Ke(t, "left") && (t.x = t.left),
    Ke(t, "right")) {
        const o = e.width - t.right;
        Ke(t, "left") ? (t.x = t.left,
        t.width = Math.max(0, o - t.left)) : Ke(t, "width") && (t.x = o - t.width)
    }
    if (Ke(t, "top") && (t.y = t.top),
    Ke(t, "bottom")) {
        const o = e.height - t.bottom;
        Ke(t, "top") ? (t.y = t.top,
        t.height = Math.max(0, o - t.top)) : Ke(t, "height") && (t.y = o - t.height)
    }
    return t
}
  , Ei = (t,e,o)=>(Xo(t) && t.points.filter((t=>qe(t.x))).forEach((t=>{
    t.x *= o,
    t.y *= o,
    t.x += e.x,
    t.y += e.y
}
)),
jo(t) && qe(t.x1) && (t.x1 *= o,
t.y1 *= o,
t.x2 *= o,
t.y2 *= o,
t.x3 *= o,
t.y3 *= o,
t.x1 += e.x,
t.y1 += e.y,
t.x2 += e.x,
t.y2 += e.y,
t.x3 += e.x,
t.y3 += e.y),
Uo(t) && qe(t.x1) && (t.x1 *= o,
t.y1 *= o,
t.x2 *= o,
t.y2 *= o,
t.x1 += e.x,
t.y1 += e.y,
t.x2 += e.x,
t.y2 += e.y),
qe(t.x) && qe(t.y) && (t.x *= o,
t.y *= o,
t.x += e.x,
t.y += e.y),
qe(t.width) && qe(t.height) && (t.width *= o,
t.height *= o),
qe(t.rx) && qe(t.ry) && (t.rx *= o,
t.ry *= o),
(t=>qe(t.strokeWidth) && t.strokeWidth > 0)(t) && (t.strokeWidth *= o),
_o(t) && (t._scale = o,
qe(t.fontSize) && (t.fontSize *= o),
qe(t.lineHeight) && (t.lineHeight *= o),
qe(t.width) && !qe(t.height) && (t.width *= o)),
Ke(t, "cornerRadius") && qe(t.cornerRadius) && (t.cornerRadius *= o),
t);
var Li = t=>/canvas/i.test(t.nodeName)
  , Fi = (t,e)=>new Promise(((o,i)=>{
    let n = t
      , r = !1;
    const a = ()=>{
        r || (r = !0,
        S(e) && Promise.resolve().then((()=>e(xt(n.naturalWidth, n.naturalHeight)))))
    }
    ;
    if (n.src || (n = new Image,
    w(t) && new URL(t,location.href).origin !== location.origin && (n.crossOrigin = "anonymous"),
    n.src = w(t) ? t : URL.createObjectURL(t)),
    n.complete)
        return a(),
        o(n);
    S(e) && xe(n).then(a).catch(i),
    n.onload = ()=>{
        a(),
        o(n)
    }
    ,
    n.onerror = i
}
));
const zi = new Map([])
  , Bi = (t,e={})=>new Promise(((o,i)=>{
    const {onMetadata: r=n, onLoad: a=o, onError: s=i, onComplete: l=n} = e;
    let c = zi.get(t);
    if (c || (c = {
        loading: !1,
        complete: !1,
        error: !1,
        image: void 0,
        size: void 0,
        bus: ho()
    },
    zi.set(t, c)),
    c.bus.sub("meta", r),
    c.bus.sub("load", a),
    c.bus.sub("error", s),
    c.bus.sub("complete", l),
    Li(t)) {
        const e = t
          , o = e.cloneNode();
        c.complete = !0,
        c.image = o,
        c.size = yt(e)
    }
    if (c.complete)
        return c.bus.pub("meta", {
            size: c.size
        }),
        c.error ? c.bus.pub("error", c.error) : c.bus.pub("load", c.image),
        c.bus.pub("complete"),
        void (c.bus = ho());
    c.loading || (c.loading = !0,
    Fi(t, (t=>{
        c.size = t,
        c.bus.pub("meta", {
            size: t
        })
    }
    )).then((t=>{
        c.image = t,
        c.bus.pub("load", t)
    }
    )).catch((t=>{
        c.error = t,
        c.bus.pub("error", t)
    }
    )).finally((()=>{
        c.complete = !0,
        c.loading = !1,
        c.bus.pub("complete"),
        c.bus = ho()
    }
    )))
}
))
  , Oi = (t,e,o,i)=>t.drawImage(e, o.x, o.x, o.width, o.height, i.x, i.y, i.width, i.height);
var Di = async(t,e,o,i,n=Oi)=>{
    t.save(),
    t.clip(),
    await n(t, e, o, i),
    t.restore()
}
;
const _i = (t,e,o,i)=>{
    let n = Dt(0, 0, o.width, o.height);
    const r = At(t);
    if (i)
        n = ee(zt(i), Io),
        n.x *= o.width,
        n.width *= o.width,
        n.y *= o.height,
        n.height *= o.height;
    else if ("contain" === e) {
        const e = Jt(t, jt(n));
        r.width = e.width,
        r.height = e.height,
        r.x += e.x,
        r.y += e.y
    } else
        "cover" === e && (n = Jt(Dt(0, 0, n.width, n.height), jt(r)));
    return {
        srcRect: n,
        destRect: r
    }
}
  , Wi = (t,e)=>(e.cornerRadius > 0 ? ((t,e,o,i,n,r)=>{
    i < 2 * r && (r = i / 2),
    n < 2 * r && (r = n / 2),
    t.beginPath(),
    t.moveTo(e + r, o),
    t.arcTo(e + i, o, e + i, o + n, r),
    t.arcTo(e + i, o + n, e, o + n, r),
    t.arcTo(e, o + n, e, o, r),
    t.arcTo(e, o, e + i, o, r),
    t.closePath()
}
)(t, e.x, e.y, e.width, e.height, e.cornerRadius) : t.rect(e.x, e.y, e.width, e.height),
t)
  , Vi = (t,e)=>(e.backgroundColor && t.fill(),
t)
  , Hi = (t,e)=>(e.strokeWidth && t.stroke(),
t);
var Ni = async(t,e,o={})=>new Promise((async(i,n)=>{
    const {drawImage: r} = o;
    if (t.lineWidth = e.strokeWidth ? e.strokeWidth : 1,
    t.strokeStyle = e.strokeColor ? ao(e.strokeColor) : "none",
    t.fillStyle = e.backgroundColor ? ao(e.backgroundColor) : "none",
    t.globalAlpha = e.opacity,
    e.backgroundImage) {
        let o;
        try {
            o = Li(e.backgroundImage) ? e.backgroundImage : await Bi(e.backgroundImage)
        } catch (t) {
            n(t)
        }
        Wi(t, e),
        Vi(t, e);
        const {srcRect: a, destRect: s} = _i(e, e.backgroundSize, yt(o), e.backgroundCorners);
        await Di(t, o, a, s, r),
        Hi(t, e),
        i([])
    } else
        Wi(t, e),
        Vi(t, e),
        Hi(t, e),
        i([])
}
))
  , Ui = async(t,e,o={})=>new Promise((async(i,n)=>{
    const {drawImage: r} = o;
    if (t.lineWidth = e.strokeWidth || 1,
    t.strokeStyle = e.strokeColor ? ao(e.strokeColor) : "none",
    t.fillStyle = e.backgroundColor ? ao(e.backgroundColor) : "none",
    t.globalAlpha = e.opacity,
    t.ellipse(e.x, e.y, e.rx, e.ry, 0, 0, 2 * Math.PI),
    e.backgroundColor && t.fill(),
    e.backgroundImage) {
        let o;
        try {
            o = await Bi(e.backgroundImage)
        } catch (t) {
            n(t)
        }
        const a = Dt(e.x - e.rx, e.y - e.ry, 2 * e.rx, 2 * e.ry)
          , {srcRect: s, destRect: l} = _i(a, e.backgroundSize, yt(o));
        await Di(t, o, s, l, r),
        e.strokeWidth && t.stroke(),
        i([])
    } else
        e.strokeWidth && t.stroke(),
        i([])
}
))
  , ji = async(t,e,o)=>{
    const i = e.width && e.height ? gt(e) : yo(e.text, e)
      , n = {
        x: e.x,
        y: e.y,
        width: i.width,
        height: i.height
    };
    if (Ni(t, {
        ...e,
        ...n,
        options: o
    }),
    !e.text.length)
        return [];
    const {willRequestResource: r} = o
      , a = await Po(e.text, {
        ...e,
        ...n,
        imageWidth: n.width,
        imageHeight: n.height,
        willRequestResource: r
    });
    return t.drawImage(a, e.x - po, e.y, a.width / 1, a.height / 1),
    []
}
  , Xi = async(t,e)=>new Promise((async o=>{
    t.lineWidth = e.strokeWidth || 1,
    t.strokeStyle = e.strokeColor ? ao(e.strokeColor) : "none",
    t.globalAlpha = e.opacity;
    let i = Oo(e)
      , n = Do(e);
    t.moveTo(i.x, i.y),
    t.lineTo(n.x, n.y),
    e.strokeWidth && t.stroke(),
    o([])
}
))
  , Yi = async(t,e)=>new Promise(((o,i)=>{
    t.lineWidth = e.strokeWidth || 1,
    t.strokeStyle = e.strokeColor ? ao(e.strokeColor) : "none",
    t.fillStyle = e.backgroundColor ? ao(e.backgroundColor) : "none",
    t.globalAlpha = e.opacity;
    const {points: n} = e;
    e.pathClose && t.beginPath(),
    t.moveTo(n[0].x, n[0].y);
    const r = n.length;
    for (let e = 1; e < r; e++)
        t.lineTo(n[e].x, n[e].y);
    e.pathClose && t.closePath(),
    e.strokeWidth && t.stroke(),
    e.backgroundColor && t.fill(),
    o([])
}
));
const Gi = async(t,e,o)=>{
    const i = (t=>{
        if (Ho(t))
            return Y(t.x + .5 * t.width, t.y + .5 * t.height);
        if (No(t))
            return Y(t.x, t.y);
        if (Vo(t)) {
            const e = t.height || yo(t.text, t).height;
            return Y(t.x + .5 * t.width, t.y + .5 * e)
        }
        if (Wo(t)) {
            const e = yo(t.text, t);
            return Y(t.x + .5 * e.width, t.y + .5 * e.height)
        }
        return Xo(t) ? dt(t.points) : Uo(t) ? dt([Oo(t), Do(t)]) : void 0
    }
    )(e);
    let n;
    return De(t, e.rotation, i),
    ((t,e,o,i)=>{
        (e || o) && (t.translate(i.x, i.y),
        t.scale(e ? -1 : 1, o ? -1 : 1),
        t.translate(-i.x, -i.y))
    }
    )(t, e.flipX, e.flipY, i),
    Ho(e) ? n = Ni : No(e) ? n = Ui : Uo(e) ? n = Xi : Xo(e) ? n = Yi : _o(e) && (n = ji),
    n ? [e, ...await qi(t, await n(t, e, o), o)] : []
}
;
var qi = async(t,e,o)=>{
    let i = [];
    for (const n of e)
        t.save(),
        t.beginPath(),
        i = [...i, ...await Gi(t, n, o)],
        t.restore();
    return i
}
  , Zi = async(t,e={})=>{
    const {shapes: o=[], context: i=t, contextBounds: r=t, transform: a=n, drawImage: s, willRequestResource: l, preprocessShape: c=_} = e;
    if (!o.length)
        return t;
    const d = p("canvas");
    d.width = r.width,
    d.height = r.height;
    const u = d.getContext("2d");
    u.putImageData(t, r.x || 0, r.y || 0);
    const h = o.map(Eo).map((t=>Ci(t, {
        width: i.width,
        height: i.height
    }))).map(c).flat();
    a(u),
    await qi(u, h, {
        drawImage: s,
        willRequestResource: l
    });
    const m = u.getImageData(0, 0, d.width, d.height);
    return g(d),
    m
}
  , Ki = async(t,e={})=>{
    const {backgroundColor: o} = e;
    if (!o || o && 0 === o[3])
        return t;
    const i = p("canvas");
    i.width = t.width,
    i.height = t.height;
    const n = i.getContext("2d");
    n.putImageData(t, 0, 0),
    n.globalCompositeOperation = "destination-over",
    n.fillStyle = ao(o),
    n.fillRect(0, 0, i.width, i.height);
    const r = n.getImageData(0, 0, i.width, i.height);
    return g(i),
    r
}
  , Ji = t=>t.length ? t.reduce(((t,e)=>((t,e)=>{
    const o = new Array(20);
    return o[0] = t[0] * e[0] + t[1] * e[5] + t[2] * e[10] + t[3] * e[15],
    o[1] = t[0] * e[1] + t[1] * e[6] + t[2] * e[11] + t[3] * e[16],
    o[2] = t[0] * e[2] + t[1] * e[7] + t[2] * e[12] + t[3] * e[17],
    o[3] = t[0] * e[3] + t[1] * e[8] + t[2] * e[13] + t[3] * e[18],
    o[4] = t[0] * e[4] + t[1] * e[9] + t[2] * e[14] + t[3] * e[19] + t[4],
    o[5] = t[5] * e[0] + t[6] * e[5] + t[7] * e[10] + t[8] * e[15],
    o[6] = t[5] * e[1] + t[6] * e[6] + t[7] * e[11] + t[8] * e[16],
    o[7] = t[5] * e[2] + t[6] * e[7] + t[7] * e[12] + t[8] * e[17],
    o[8] = t[5] * e[3] + t[6] * e[8] + t[7] * e[13] + t[8] * e[18],
    o[9] = t[5] * e[4] + t[6] * e[9] + t[7] * e[14] + t[8] * e[19] + t[9],
    o[10] = t[10] * e[0] + t[11] * e[5] + t[12] * e[10] + t[13] * e[15],
    o[11] = t[10] * e[1] + t[11] * e[6] + t[12] * e[11] + t[13] * e[16],
    o[12] = t[10] * e[2] + t[11] * e[7] + t[12] * e[12] + t[13] * e[17],
    o[13] = t[10] * e[3] + t[11] * e[8] + t[12] * e[13] + t[13] * e[18],
    o[14] = t[10] * e[4] + t[11] * e[9] + t[12] * e[14] + t[13] * e[19] + t[14],
    o[15] = t[15] * e[0] + t[16] * e[5] + t[17] * e[10] + t[18] * e[15],
    o[16] = t[15] * e[1] + t[16] * e[6] + t[17] * e[11] + t[18] * e[16],
    o[17] = t[15] * e[2] + t[16] * e[7] + t[17] * e[12] + t[18] * e[17],
    o[18] = t[15] * e[3] + t[16] * e[8] + t[17] * e[13] + t[18] * e[18],
    o[19] = t[15] * e[4] + t[16] * e[9] + t[17] * e[14] + t[18] * e[19] + t[19],
    o
}
)([...t], e)), t.shift()) : []
  , Qi = (t,e)=>{
    const o = t.width * t.height
      , i = e.reduce(((t,e)=>(e.width > t.width && e.height > t.height && (t.width = e.width,
    t.height = e.height),
    t)), {
        width: 0,
        height: 0
    })
      , n = i.width * i.height;
    return ((t,e=2)=>Math.round(t * e) / e)(Math.max(.5, .5 + (1 - n / o) / 2), 5)
}
;
function tn() {}
const en = t=>t;
function on(t, e) {
    for (const o in e)
        t[o] = e[o];
    return t
}
function nn(t) {
    return t()
}
function rn() {
    return Object.create(null)
}
function an(t) {
    t.forEach(nn)
}
function sn(t) {
    return "function" == typeof t
}
function ln(t, e) {
    return t != t ? e == e : t !== e || t && "object" == typeof t || "function" == typeof t
}
function cn(t, ...e) {
    if (null == t)
        return tn;
    const o = t.subscribe(...e);
    return o.unsubscribe ? ()=>o.unsubscribe() : o
}
function dn(t) {
    let e;
    return cn(t, (t=>e = t))(),
    e
}
function un(t, e, o) {
    t.$$.on_destroy.push(cn(e, o))
}
function hn(t, e, o, i) {
    if (t) {
        const n = pn(t, e, o, i);
        return t[0](n)
    }
}
function pn(t, e, o, i) {
    return t[1] && i ? on(o.ctx.slice(), t[1](i(e))) : o.ctx
}
function mn(t, e, o, i, n, r, a) {
    const s = function(t, e, o, i) {
        if (t[2] && i) {
            const n = t[2](i(o));
            if (void 0 === e.dirty)
                return n;
            if ("object" == typeof n) {
                const t = []
                  , o = Math.max(e.dirty.length, n.length);
                for (let i = 0; i < o; i += 1)
                    t[i] = e.dirty[i] | n[i];
                return t
            }
            return e.dirty | n
        }
        return e.dirty
    }(e, i, n, r);
    if (s) {
        const n = pn(e, o, i, a);
        t.p(n, s)
    }
}
function gn(t) {
    const e = {};
    for (const o in t)
        "$" !== o[0] && (e[o] = t[o]);
    return e
}
function fn(t, e) {
    const o = {};
    e = new Set(e);
    for (const i in t)
        e.has(i) || "$" === i[0] || (o[i] = t[i]);
    return o
}
function $n(t, e, o=e) {
    return t.set(o),
    e
}
function yn(t) {
    return t && sn(t.destroy) ? t.destroy : tn
}
const xn = "undefined" != typeof window;
let bn = xn ? ()=>window.performance.now() : ()=>Date.now()
  , vn = xn ? t=>requestAnimationFrame(t) : tn;
const wn = new Set;
function Sn(t) {
    wn.forEach((e=>{
        e.c(t) || (wn.delete(e),
        e.f())
    }
    )),
    0 !== wn.size && vn(Sn)
}
function Cn(t) {
    let e;
    return 0 === wn.size && vn(Sn),
    {
        promise: new Promise((o=>{
            wn.add(e = {
                c: t,
                f: o
            })
        }
        )),
        abort() {
            wn.delete(e)
        }
    }
}
function kn(t, e) {
    t.appendChild(e)
}
function Mn(t, e, o) {
    t.insertBefore(e, o || null)
}
function Tn(t) {
    t.parentNode.removeChild(t)
}
function Rn(t) {
    return document.createElement(t)
}
function Pn(t) {
    return document.createElementNS("http://www.w3.org/2000/svg", t)
}
function In(t) {
    return document.createTextNode(t)
}
function An() {
    return In(" ")
}
function En() {
    return In("")
}
function Ln(t, e, o, i) {
    return t.addEventListener(e, o, i),
    ()=>t.removeEventListener(e, o, i)
}
function Fn(t) {
    return function(e) {
        return e.preventDefault(),
        t.call(this, e)
    }
}
function zn(t) {
    return function(e) {
        return e.stopPropagation(),
        t.call(this, e)
    }
}
function Bn(t, e, o) {
    null == o ? t.removeAttribute(e) : t.getAttribute(e) !== o && t.setAttribute(e, o)
}
function On(t, e) {
    const o = Object.getOwnPropertyDescriptors(t.__proto__);
    for (const i in e)
        null == e[i] ? t.removeAttribute(i) : "style" === i ? t.style.cssText = e[i] : "__value" === i ? t.value = t[i] = e[i] : o[i] && o[i].set ? t[i] = e[i] : Bn(t, i, e[i])
}
function Dn(t, e) {
    e = "" + e,
    t.wholeText !== e && (t.data = e)
}
function _n(t, e) {
    t.value = null == e ? "" : e
}
function Wn(t, e) {
    const o = document.createEvent("CustomEvent");
    return o.initCustomEvent(t, !1, !1, e),
    o
}
class Vn {
    constructor(t=null) {
        this.a = t,
        this.e = this.n = null
    }
    m(t, e, o=null) {
        this.e || (this.e = Rn(e.nodeName),
        this.t = e,
        this.h(t)),
        this.i(o)
    }
    h(t) {
        this.e.innerHTML = t,
        this.n = Array.from(this.e.childNodes)
    }
    i(t) {
        for (let e = 0; e < this.n.length; e += 1)
            Mn(this.t, this.n[e], t)
    }
    p(t) {
        this.d(),
        this.h(t),
        this.i(this.a)
    }
    d() {
        this.n.forEach(Tn)
    }
}
const Hn = new Set;
let Nn, Un = 0;
function jn(t, e, o, i, n, r, a, s=0) {
    const l = 16.666 / i;
    let c = "{\n";
    for (let t = 0; t <= 1; t += l) {
        const i = e + (o - e) * r(t);
        c += 100 * t + `%{${a(i, 1 - i)}}\n`
    }
    const d = c + `100% {${a(o, 1 - o)}}\n}`
      , u = `__svelte_${function(t) {
        let e = 5381
          , o = t.length;
        for (; o--; )
            e = (e << 5) - e ^ t.charCodeAt(o);
        return e >>> 0
    }(d)}_${s}`
      , h = t.ownerDocument;
    Hn.add(h);
    const p = h.__svelte_stylesheet || (h.__svelte_stylesheet = h.head.appendChild(Rn("style")).sheet)
      , m = h.__svelte_rules || (h.__svelte_rules = {});
    m[u] || (m[u] = !0,
    p.insertRule(`@keyframes ${u} ${d}`, p.cssRules.length));
    const g = t.style.animation || "";
    return t.style.animation = `${g ? g + ", " : ""}${u} ${i}ms linear ${n}ms 1 both`,
    Un += 1,
    u
}
function Xn(t, e) {
    const o = (t.style.animation || "").split(", ")
      , i = o.filter(e ? t=>t.indexOf(e) < 0 : t=>-1 === t.indexOf("__svelte"))
      , n = o.length - i.length;
    n && (t.style.animation = i.join(", "),
    Un -= n,
    Un || vn((()=>{
        Un || (Hn.forEach((t=>{
            const e = t.__svelte_stylesheet;
            let o = e.cssRules.length;
            for (; o--; )
                e.deleteRule(o);
            t.__svelte_rules = {}
        }
        )),
        Hn.clear())
    }
    )))
}
function Yn(t) {
    Nn = t
}
function Gn() {
    if (!Nn)
        throw new Error("Function called outside component initialization");
    return Nn
}
function qn(t) {
    Gn().$$.on_mount.push(t)
}
function Zn(t) {
    Gn().$$.after_update.push(t)
}
function Kn(t) {
    Gn().$$.on_destroy.push(t)
}
function Jn() {
    const t = Gn();
    return (e,o)=>{
        const i = t.$$.callbacks[e];
        if (i) {
            const n = Wn(e, o);
            i.slice().forEach((e=>{
                e.call(t, n)
            }
            ))
        }
    }
}
function Qn(t, e) {
    Gn().$$.context.set(t, e)
}
function tr(t) {
    return Gn().$$.context.get(t)
}
function er(t, e) {
    const o = t.$$.callbacks[e.type];
    o && o.slice().forEach((t=>t(e)))
}
const or = []
  , ir = []
  , nr = []
  , rr = []
  , ar = Promise.resolve();
let sr = !1;
function lr(t) {
    nr.push(t)
}
function cr(t) {
    rr.push(t)
}
let dr = !1;
const ur = new Set;
function hr() {
    if (!dr) {
        dr = !0;
        do {
            for (let t = 0; t < or.length; t += 1) {
                const e = or[t];
                Yn(e),
                pr(e.$$)
            }
            for (Yn(null),
            or.length = 0; ir.length; )
                ir.pop()();
            for (let t = 0; t < nr.length; t += 1) {
                const e = nr[t];
                ur.has(e) || (ur.add(e),
                e())
            }
            nr.length = 0
        } while (or.length);
        for (; rr.length; )
            rr.pop()();
        sr = !1,
        dr = !1,
        ur.clear()
    }
}
function pr(t) {
    if (null !== t.fragment) {
        t.update(),
        an(t.before_update);
        const e = t.dirty;
        t.dirty = [-1],
        t.fragment && t.fragment.p(t.ctx, e),
        t.after_update.forEach(lr)
    }
}
let mr;
function gr(t, e, o) {
    t.dispatchEvent(Wn(`${e ? "intro" : "outro"}${o}`))
}
const fr = new Set;
let $r;
function yr() {
    $r = {
        r: 0,
        c: [],
        p: $r
    }
}
function xr() {
    $r.r || an($r.c),
    $r = $r.p
}
function br(t, e) {
    t && t.i && (fr.delete(t),
    t.i(e))
}
function vr(t, e, o, i) {
    if (t && t.o) {
        if (fr.has(t))
            return;
        fr.add(t),
        $r.c.push((()=>{
            fr.delete(t),
            i && (o && t.d(1),
            i())
        }
        )),
        t.o(e)
    }
}
const wr = {
    duration: 0
};
function Sr(t, e, o, i) {
    let n = e(t, o)
      , r = i ? 0 : 1
      , a = null
      , s = null
      , l = null;
    function c() {
        l && Xn(t, l)
    }
    function d(t, e) {
        const o = t.b - r;
        return e *= Math.abs(o),
        {
            a: r,
            b: t.b,
            d: o,
            duration: e,
            start: t.start,
            end: t.start + e,
            group: t.group
        }
    }
    function u(e) {
        const {delay: o=0, duration: i=300, easing: u=en, tick: h=tn, css: p} = n || wr
          , m = {
            start: bn() + o,
            b: e
        };
        e || (m.group = $r,
        $r.r += 1),
        a || s ? s = m : (p && (c(),
        l = jn(t, r, e, i, o, u, p)),
        e && h(0, 1),
        a = d(m, i),
        lr((()=>gr(t, e, "start"))),
        Cn((e=>{
            if (s && e > s.start && (a = d(s, i),
            s = null,
            gr(t, a.b, "start"),
            p && (c(),
            l = jn(t, r, a.b, a.duration, 0, u, n.css))),
            a)
                if (e >= a.end)
                    h(r = a.b, 1 - r),
                    gr(t, a.b, "end"),
                    s || (a.b ? c() : --a.group.r || an(a.group.c)),
                    a = null;
                else if (e >= a.start) {
                    const t = e - a.start;
                    r = a.a + a.d * u(t / a.duration),
                    h(r, 1 - r)
                }
            return !(!a && !s)
        }
        )))
    }
    return {
        run(t) {
            sn(n) ? (mr || (mr = Promise.resolve(),
            mr.then((()=>{
                mr = null
            }
            ))),
            mr).then((()=>{
                n = n(),
                u(t)
            }
            )) : u(t)
        },
        end() {
            c(),
            a = s = null
        }
    }
}
const Cr = "undefined" != typeof window ? window : "undefined" != typeof globalThis ? globalThis : global;
function kr(t, e) {
    t.d(1),
    e.delete(t.key)
}
function Mr(t, e) {
    vr(t, 1, 1, (()=>{
        e.delete(t.key)
    }
    ))
}
function Tr(t, e, o, i, n, r, a, s, l, c, d, u) {
    let h = t.length
      , p = r.length
      , m = h;
    const g = {};
    for (; m--; )
        g[t[m].key] = m;
    const f = []
      , $ = new Map
      , y = new Map;
    for (m = p; m--; ) {
        const t = u(n, r, m)
          , s = o(t);
        let l = a.get(s);
        l ? i && l.p(t, e) : (l = c(s, t),
        l.c()),
        $.set(s, f[m] = l),
        s in g && y.set(s, Math.abs(m - g[s]))
    }
    const x = new Set
      , b = new Set;
    function v(t) {
        br(t, 1),
        t.m(s, d),
        a.set(t.key, t),
        d = t.first,
        p--
    }
    for (; h && p; ) {
        const e = f[p - 1]
          , o = t[h - 1]
          , i = e.key
          , n = o.key;
        e === o ? (d = e.first,
        h--,
        p--) : $.has(n) ? !a.has(i) || x.has(i) ? v(e) : b.has(n) ? h-- : y.get(i) > y.get(n) ? (b.add(i),
        v(e)) : (x.add(n),
        h--) : (l(o, a),
        h--)
    }
    for (; h--; ) {
        const e = t[h];
        $.has(e.key) || l(e, a)
    }
    for (; p; )
        v(f[p - 1]);
    return f
}
function Rr(t, e) {
    const o = {}
      , i = {}
      , n = {
        $$scope: 1
    };
    let r = t.length;
    for (; r--; ) {
        const a = t[r]
          , s = e[r];
        if (s) {
            for (const t in a)
                t in s || (i[t] = 1);
            for (const t in s)
                n[t] || (o[t] = s[t],
                n[t] = 1);
            t[r] = s
        } else
            for (const t in a)
                n[t] = 1
    }
    for (const t in i)
        t in o || (o[t] = void 0);
    return o
}
function Pr(t) {
    return "object" == typeof t && null !== t ? t : {}
}
function Ir(t, e, o) {
    const i = t.$$.props[e];
    void 0 !== i && (t.$$.bound[i] = o,
    o(t.$$.ctx[i]))
}
function Ar(t) {
    t && t.c()
}
function Er(t, e, o, i) {
    const {fragment: n, on_mount: r, on_destroy: a, after_update: s} = t.$$;
    n && n.m(e, o),
    i || lr((()=>{
        const e = r.map(nn).filter(sn);
        a ? a.push(...e) : an(e),
        t.$$.on_mount = []
    }
    )),
    s.forEach(lr)
}
function Lr(t, e) {
    const o = t.$$;
    null !== o.fragment && (an(o.on_destroy),
    o.fragment && o.fragment.d(e),
    o.on_destroy = o.fragment = null,
    o.ctx = [])
}
function Fr(t, e) {
    -1 === t.$$.dirty[0] && (or.push(t),
    sr || (sr = !0,
    ar.then(hr)),
    t.$$.dirty.fill(0)),
    t.$$.dirty[e / 31 | 0] |= 1 << e % 31
}
function zr(t, e, o, i, n, r, a=[-1]) {
    const s = Nn;
    Yn(t);
    const l = t.$$ = {
        fragment: null,
        ctx: null,
        props: r,
        update: tn,
        not_equal: n,
        bound: rn(),
        on_mount: [],
        on_destroy: [],
        on_disconnect: [],
        before_update: [],
        after_update: [],
        context: new Map(s ? s.$$.context : e.context || []),
        callbacks: rn(),
        dirty: a,
        skip_bound: !1
    };
    let c = !1;
    if (l.ctx = o ? o(t, e.props || {}, ((e,o,...i)=>{
        const r = i.length ? i[0] : o;
        return l.ctx && n(l.ctx[e], l.ctx[e] = r) && (!l.skip_bound && l.bound[e] && l.bound[e](r),
        c && Fr(t, e)),
        o
    }
    )) : [],
    l.update(),
    c = !0,
    an(l.before_update),
    l.fragment = !!i && i(l.ctx),
    e.target) {
        if (e.hydrate) {
            const t = function(t) {
                return Array.from(t.childNodes)
            }(e.target);
            l.fragment && l.fragment.l(t),
            t.forEach(Tn)
        } else
            l.fragment && l.fragment.c();
        e.intro && br(t.$$.fragment),
        Er(t, e.target, e.anchor, e.customElement),
        hr()
    }
    Yn(s)
}
class Br {
    $destroy() {
        Lr(this, 1),
        this.$destroy = tn
    }
    $on(t, e) {
        const o = this.$$.callbacks[t] || (this.$$.callbacks[t] = []);
        return o.push(e),
        ()=>{
            const t = o.indexOf(e);
            -1 !== t && o.splice(t, 1)
        }
    }
    $set(t) {
        var e;
        this.$$set && (e = t,
        0 !== Object.keys(e).length) && (this.$$.skip_bound = !0,
        this.$$set(t),
        this.$$.skip_bound = !1)
    }
}
const Or = [];
function Dr(t, e) {
    return {
        subscribe: _r(t, e).subscribe
    }
}
function _r(t, e=tn) {
    let o;
    const i = [];
    function n(e) {
        if (ln(t, e) && (t = e,
        o)) {
            const e = !Or.length;
            for (let e = 0; e < i.length; e += 1) {
                const o = i[e];
                o[1](),
                Or.push(o, t)
            }
            if (e) {
                for (let t = 0; t < Or.length; t += 2)
                    Or[t][0](Or[t + 1]);
                Or.length = 0
            }
        }
    }
    return {
        set: n,
        update: function(e) {
            n(e(t))
        },
        subscribe: function(r, a=tn) {
            const s = [r, a];
            return i.push(s),
            1 === i.length && (o = e(n) || tn),
            r(t),
            ()=>{
                const t = i.indexOf(s);
                -1 !== t && i.splice(t, 1),
                0 === i.length && (o(),
                o = null)
            }
        }
    }
}
function Wr(t, e, o) {
    const i = !Array.isArray(t)
      , n = i ? [t] : t
      , r = e.length < 2;
    return Dr(o, (t=>{
        let o = !1;
        const a = [];
        let s = 0
          , l = tn;
        const c = ()=>{
            if (s)
                return;
            l();
            const o = e(i ? a[0] : a, t);
            r ? t(o) : l = sn(o) ? o : tn
        }
          , d = n.map(((t,e)=>cn(t, (t=>{
            a[e] = t,
            s &= ~(1 << e),
            o && c()
        }
        ), (()=>{
            s |= 1 << e
        }
        ))));
        return o = !0,
        c(),
        function() {
            an(d),
            l()
        }
    }
    ))
}
var Vr = t=>t.reduce(((t,e)=>Object.assign(t, e)), {});
const Hr = t=>({
    updateValue: t
})
  , Nr = t=>({
    defaultValue: t
})
  , Ur = t=>({
    store: (e,o)=>Wr(...t(o))
})
  , jr = t=>({
    store: (e,o)=>{
        const [i,n,r=(()=>!1)] = t(o);
        let a, s = !0;
        return Wr(i, ((t,e)=>{
            n(t, (t=>{
                !s && r(a, t) || (a = t,
                s = !1,
                e(t))
            }
            ))
        }
        ))
    }
})
  , Xr = t=>({
    store: (e,o)=>{
        const [i,n={},r] = t(o);
        let a = [];
        const s = {}
          , l = t=>i(t, s)
          , c = t=>{
            (a.length || t.length) && (a = t,
            d())
        }
          , d = ()=>{
            const t = a.map(l);
            r && t.sort(r),
            a = [...t],
            h(t)
        }
        ;
        Object.entries(n).map((([t,e])=>e.subscribe((e=>{
            s[t] = e,
            e && d()
        }
        ))));
        const {subscribe: u, set: h} = _r(e || []);
        return {
            set: c,
            update: t=>c(t(a)),
            subscribe: u
        }
    }
});
var Yr = t=>{
    const e = {}
      , o = {};
    return t.forEach((([t,...i])=>{
        const r = Vr(i)
          , a = e[t] = ((t,e,o)=>{
            const {store: i=(t=>_r(t)), defaultValue: r=n, updateValue: a} = o
              , s = i(r(), e, t)
              , {subscribe: l, update: c=n} = s;
            let d;
            const u = t=>{
                let e = !0;
                d && d(),
                d = l((o=>{
                    if (e)
                        return e = !1;
                    t(o),
                    d(),
                    d = void 0
                }
                ))
            }
              , h = a ? a(t) : _;
            return s.set = t=>c((e=>h(t, e, u))),
            s.defaultValue = r,
            s
        }
        )(o, e, r)
          , s = {
            get: ()=>dn(a),
            set: a.set
        };
        Object.defineProperty(o, t, s)
    }
    )),
    {
        stores: e,
        accessors: o
    }
}
  , Gr = [["src"], ["imageReader"], ["imageWriter"], ["imageScrambler"], ["images", Nr((()=>[]))], ["shapePreprocessor"], ["willRequestResource"]]
  , qr = t=>t.charAt(0).toUpperCase() + t.slice(1)
  , Zr = (t,e)=>{
    Object.keys(e).forEach((o=>{
        const i = S(e[o]) ? {
            value: e[o],
            writable: !1
        } : e[o];
        Object.defineProperty(t, o, i)
    }
    ))
}
;
const Kr = (t,e)=>{
    let o, i, n, r, a, s, l, c, d, u;
    const h = e.length;
    for (o = 0; o < h; o++)
        if (i = e[o],
        n = e[o + 1 > h - 1 ? 0 : o + 1],
        r = i.x - t.x,
        a = i.y - t.y,
        s = n.x - t.x,
        l = n.y - t.y,
        c = r - s,
        d = a - l,
        u = c * a - d * r,
        u < -1e-5)
            return !1;
    return !0
}
;
var Jr = (t,e)=>{
    const o = le(e)
      , i = X();
    te(t).forEach((t=>{
        nt(t, i),
        Kr(t, e) || o.forEach((e=>{
            const o = Math.atan2(e.start.y - e.end.y, e.start.x - e.end.x)
              , n = 1e4 * Math.sin(Math.PI - o)
              , r = 1e4 * Math.cos(Math.PI - o)
              , a = Y(t.x + n, t.y + r)
              , s = Rt(Tt(e), 1e4)
              , l = re(Mt(t, a), s);
            l && nt(i, rt(Z(l), t))
        }
        ))
    }
    ));
    const n = At(t);
    nt(n, i);
    return !!te(n).every((t=>Kr(t, e))) && (Yt(t, n),
    !0)
}
  , Qr = (t,e)=>{
    const o = te(t)
      , i = le(e).map((t=>Rt(t, 5)))
      , n = _t(t)
      , r = [];
    o.forEach((t=>{
        const e = ((t,e)=>{
            if (0 === e)
                return t;
            const o = Y(t.start.x - t.end.x, t.start.y - t.end.y)
              , i = tt(o)
              , n = at(i, e);
            return t.end.x += n.x,
            t.end.y += n.y,
            t
        }
        )(Mt(Z(n), Z(t)), 1e6);
        let o = !1;
        i.map(Tt).forEach((t=>{
            const i = re(e, t);
            i && !o && (r.push(i),
            o = !0)
        }
        ))
    }
    ));
    const a = ct(r[0], r[2]) < ct(r[1], r[3]) ? [r[0], r[2]] : [r[1], r[3]]
      , s = zt(a);
    return s.width < t.width && (Yt(t, s),
    !0)
}
  , ta = (t,e,o={
    x: 0,
    y: 0
})=>{
    const i = Lt(t)
      , n = _t(i)
      , r = oe(i, o, n).map((t=>J(t, e, n)))
      , a = zt(r);
    return r.map((t=>rt(t, a)))
}
  , ea = (t,e=0,o=jt(t))=>{
    let i, n;
    if (0 !== e) {
        const r = Math.atan2(1, o)
          , a = Math.sign(e) * e
          , s = a % Math.PI
          , l = a % V;
        let c, d;
        d = s > H && s < V + H ? l > H ? a : V - l : l > H ? V - l : a,
        c = Math.min(Math.abs(t.height / Math.sin(r + d)), Math.abs(t.width / Math.cos(r - d))),
        i = Math.cos(r) * c,
        n = i / o
    } else
        i = t.width,
        n = i / o,
        n > t.height && (n = t.height,
        i = n * o);
    return xt(i, n)
}
  , oa = (t,e,o,i,n,r,a,s)=>{
    const l = mt(a)
      , c = mt(s)
      , d = Io(Math.max(e.width / c.width, e.height / c.height))
      , u = Io(Math.min(e.width / l.width, e.height / l.height))
      , h = At(e);
    if (u < 1 || d > 1) {
        const o = _t(t)
          , i = _t(e)
          , n = u < 1 ? u : d
          , r = (i.x + o.x) / 2
          , a = (i.y + o.y) / 2
          , s = h.width / n
          , l = h.height / n;
        Xt(h, r - .5 * s, a - .5 * l, s, l)
    }
    return r ? (((t,e,o=0,i=X(),n)=>{
        if (qe(o) && 0 !== o || i.x || i.y) {
            const n = jt(t)
              , r = ta(e, o, i)
              , a = ea(e, o, n);
            if (!(t.width < a.width && t.height < a.height)) {
                const e = .5 * t.width - .5 * a.width
                  , o = .5 * t.height - .5 * a.height;
                t.width > a.width && (t.width = a.width,
                t.x += e),
                t.height > a.height && (t.height = a.height,
                t.y += o)
            }
            Jr(t, r),
            Qr(t, r) && Jr(t, r)
        } else {
            let o = jt(t);
            t.width = Math.min(t.width, e.width),
            t.height = Math.min(t.height, e.height),
            t.x = Math.max(t.x, 0),
            t.x + t.width > e.width && (t.x -= t.x + t.width - e.width),
            t.y = Math.max(t.y, 0),
            t.y + t.height > e.height && (t.y -= t.y + t.height - e.height);
            const i = _t(t)
              , r = Jt(t, o);
            r.width = Math.max(n.width, r.width),
            r.height = Math.max(n.height, r.height),
            r.x = i.x - .5 * r.width,
            r.y = i.y - .5 * r.height,
            Yt(t, r)
        }
    }
    )(h, o, i, n, l),
    {
        crop: h
    }) : {
        crop: h
    }
}
  , ia = (t,e,o)=>{
    const i = Lt(t)
      , n = _t(i)
      , r = Gt(i, o, n)
      , a = _t(ie(zt(r)))
      , s = _t(e)
      , l = J(s, -o, a)
      , c = rt(l, a)
      , d = it(nt(n, c), Io);
    return Dt(d.x - .5 * e.width, d.y - .5 * e.height, e.width, e.height)
}
  , na = (t,e,o)=>Math.max(e, Math.min(t, o));
const ra = ["cropLimitToImage", "cropMinSize", "cropMaxSize", "cropAspectRatio", "flipX", "flipY", "rotation", "crop", "colorMatrix", "convolutionMatrix", "gamma", "vignette", "redaction", "annotation", "decoration", "frame", "backgroundColor", "targetSize", "metadata"]
  , aa = t=>Je(t) ? t.map(aa) : x(t) ? {
    ...t
} : t
  , sa = t=>t.map((t=>Object.entries(t).reduce(((t,[e,o])=>(e.startsWith("_") || (t[e] = o),
t)), {})));
var la = (t,e)=>{
    if (t.length !== e.length)
        return !1;
    for (let o = 0; o < t.length; o++)
        if (t[o] !== e[o])
            return !1;
    return !0
}
;
const ca = -H
  , da = H
  , ua = (t,e,o)=>{
    const i = it(_t(t), (t=>Io(t, 8)))
      , n = Lt(e)
      , r = _t(n)
      , a = Gt(n, o, r)
      , s = it(wt(zt(a)), (t=>Io(t, 8)))
      , l = Math.abs(s.x - i.x)
      , c = Math.abs(s.y - i.y);
    return l < 1 && c < 1
}
  , ha = (t,e,o,i,n)=>{
    if (!n)
        return [ca, da];
    const r = Math.max(o.width / i.width, o.height / i.height)
      , a = xt(i.width * r, i.height * r)
      , s = (l = a,
    Math.sqrt(l.width * l.width + l.height * l.height));
    var l;
    if (s < Math.min(t.width, t.height))
        return [ca, da];
    const c = e ? t.height : t.width
      , d = e ? t.width : t.height
      , u = Math.acos(a.height / s)
      , h = u - Math.acos(d / s)
      , p = Math.asin(c / s) - u;
    if (Number.isNaN(h) && Number.isNaN(p))
        return [ca, da];
    const m = Number.isNaN(h) ? p : Number.isNaN(p) ? h : Math.min(h, p);
    return [Math.max(-m, ca), Math.min(m, da)]
}
  , pa = (t,e)=>{
    const {context: o, props: i} = e;
    return t._isFormatted || ((t = fi(t))._isFormatted = !0,
    Object.assign(t, i)),
    t._isDraft || !Qo(t) || t._context && Ut(o, t._context) || ((t = Ai(t, o))._context = {
        ...o
    }),
    t
}
;
var ma = [["file"], ["size"], ["loadState"], ["processState"], ["aspectRatio", Ur((({size: t})=>[t, t=>t ? jt(t) : void 0]))], ["perspectiveX", Nr((()=>0))], ["perspectiveY", Nr((()=>0))], ["perspective", Ur((({perspectiveX: t, perspectiveY: e})=>[[t, e], ([t,e])=>({
    x: t,
    y: e
})]))], ["rotation", Nr((()=>0)), Hr((t=>(e,o,i)=>{
    if (e === o)
        return e;
    const {loadState: n, size: r, rotationRange: a, cropMinSize: s, cropMaxSize: l, crop: c, perspective: d, cropLimitToImage: u, cropOrigin: h} = t;
    if (!c || !n || !n.beforeComplete)
        return e;
    const p = ((t,e,o)=>{
        const i = ea(e, o, jt(t));
        return bt(kt(i, Math.round), kt(mt(t), Math.round))
    }
    )(c, r, o)
      , m = ua(c, r, o)
      , g = ((t,e,o,i,n,r,a,s,l,c)=>{
        const d = mt(l)
          , u = mt(c);
        a && (u.width = Math.min(c.width, n.width),
        u.height = Math.min(c.height, n.height));
        let h = !1;
        const p = (e,o)=>{
            const l = ia(n, i, e)
              , c = Lt(n)
              , m = _t(c)
              , g = oe(c, r, m)
              , f = rt(Z(m), ne(g))
              , $ = J(_t(l), o, m)
              , y = rt(Z(m), $);
            g.forEach((t=>J(t, o, m)));
            const x = zt(g)
              , b = ne(g)
              , v = nt(rt(rt(b, y), x), f)
              , w = Dt(v.x - .5 * l.width, v.y - .5 * l.height, l.width, l.height);
            if (s && Vt(w, s.width / w.width),
            a) {
                const t = ta(n, o, r);
                Qr(w, t)
            }
            const S = Io(Math.min(w.width / d.width, w.height / d.height), 8)
              , C = Io(Math.max(w.width / u.width, w.height / u.height), 8);
            return (S < 1 || C > 1) && Io(Math.abs(o - e)) === Io(Math.PI / 2) && !h ? (h = !0,
            p(t, t + Math.sign(o - e) * Math.PI)) : {
                rotation: o,
                crop: ee(w, (t=>Io(t, 8)))
            }
        }
          , m = Math.sign(e) * Math.round(Math.abs(e) / V) * V
          , g = na(e, m + o[0], m + o[1]);
        return p(t, g)
    }
    )(o, e, a, c, r, d, u, h, s, l);
    if (p && m) {
        const t = ea(r, e, jt(g.crop));
        g.crop.x += .5 * g.crop.width,
        g.crop.y += .5 * g.crop.height,
        g.crop.x -= .5 * t.width,
        g.crop.y -= .5 * t.height,
        g.crop.width = t.width,
        g.crop.height = t.height
    }
    return i((()=>{
        t.crop = ee(g.crop, (t=>Io(t, 8)))
    }
    )),
    g.rotation
}
))], ["flipX", Nr((()=>!1))], ["flipY", Nr((()=>!1))], ["flip", Ur((({flipX: t, flipY: e})=>[[t, e], ([t,e])=>({
    x: t,
    y: e
})]))], ["isRotatedSideways", jr((({rotation: t})=>[[t], ([t],e)=>e(N(t)), (t,e)=>t !== e]))], ["crop", Hr((t=>(e,o=e)=>{
    const {loadState: i, size: n, cropMinSize: r, cropMaxSize: a, cropLimitToImage: s, cropAspectRatio: l, rotation: c, perspective: d} = t;
    if (!e && !o || !i || !i.beforeComplete)
        return e;
    e || (e = Lt(ea(n, c, l || jt(n))));
    const u = oa(o, e, n, c, d, s, r, a);
    return ee(u.crop, (t=>Io(t, 8)))
}
))], ["cropAspectRatio", Hr((t=>(e,o)=>{
    const {loadState: i, crop: n, size: r, rotation: a, cropLimitToImage: s} = t
      , l = (t=>{
        if (t) {
            if (/:/.test(t)) {
                const [e,o] = t.split(":");
                return e / o
            }
            return parseFloat(t)
        }
    }
    )(e);
    if (!l)
        return;
    if (!n || !i || !i.beforeComplete)
        return l;
    const c = o ? Math.abs(e - o) : 1;
    if (ua(n, r, a) && s && c >= .1) {
        const o = ((t,e)=>{
            const o = t.width
              , i = t.height;
            return N(e) && (t.width = i,
            t.height = o),
            t
        }
        )(mt(r), a);
        t.crop = ee(Jt(Lt(o), e), Io)
    } else {
        const e = {
            width: n.height * l,
            height: n.height
        }
          , o = .5 * (n.width - e.width)
          , i = .5 * (n.height - e.height);
        t.crop = ee(Dt(n.x + o, n.y + i, e.width, e.height), Io)
    }
    return l
}
))], ["cropOrigin"], ["cropMinSize", Nr((()=>({
    width: 1,
    height: 1
})))], ["cropMaxSize", Nr((()=>({
    width: 32768,
    height: 32768
})))], ["cropLimitToImage", Nr((()=>!0)), Hr((t=>(e,o,i)=>{
    const {crop: n} = t;
    return n ? (!o && e && i((()=>t.crop = At(t.crop))),
    e) : e
}
))], ["cropSize", jr((({crop: t})=>[[t], ([t],e)=>{
    t && e(xt(t.width, t.height))
}
, (t,e)=>bt(t, e)]))], ["cropRectAspectRatio", Ur((({cropSize: t})=>[[t], ([t],e)=>{
    t && e(Io(jt(t), 5))
}
]))], ["cropRange", jr((({size: t, rotation: e, cropRectAspectRatio: o, cropMinSize: i, cropMaxSize: n, cropLimitToImage: r})=>[[t, e, o, i, n, r], ([t,e,o,i,n,r],a)=>{
    if (!t)
        return;
    a(((t,e,o,i,n,r)=>{
        const a = mt(i)
          , s = mt(n);
        return r ? [a, kt(ea(t, e, o), Math.round)] : [a, s]
    }
    )(t, e, o, i, n, r))
}
, (t,e)=>la(t, e)]))], ["rotationRange", jr((({size: t, isRotatedSideways: e, cropMinSize: o, cropSize: i, cropLimitToImage: n})=>[[t, e, o, i, n], ([t,e,o,i,n],r)=>{
    if (!t || !i)
        return;
    r(ha(t, e, o, i, n))
}
, (t,e)=>la(t, e)]))], ["backgroundColor", Hr((()=>t=>((t=[0, 0, 0, 0],e=1)=>4 === t.length ? t : [...t, e])(t)))], ["targetSize"], ["colorMatrix"], ["convolutionMatrix"], ["gamma"], ["noise"], ["vignette"], ["redaction", Xr((({size: t})=>[pa, {
    context: t
}]))], ["annotation", Xr((({size: t})=>[pa, {
    context: t
}]))], ["decoration", Xr((({crop: t})=>[pa, {
    context: t
}]))], ["frame", Hr((()=>t=>{
    if (!t)
        return;
    const e = {
        frameStyle: void 0,
        x: 0,
        y: 0,
        width: "100%",
        height: "100%",
        disableStyle: ["backgroundColor", "strokeColor", "strokeWidth"]
    };
    return w(t) ? e.frameStyle = t : Object.assign(e, t),
    e
}
))], ["metadata"], ["state", (t=>({
    store: t
}))(((t,e,o)=>{
    const i = ra.map((t=>e[t]))
      , {subscribe: n} = Wr(i, ((t,e)=>{
        const o = ra.reduce(((e,o,i)=>(e[o] = aa(t[i]),
        e)), {});
        o.crop && ee(o.crop, Math.round),
        o.redaction = o.redaction && sa(o.redaction),
        o.annotation = o.annotation && sa(o.annotation),
        o.decoration = o.decoration && sa(o.decoration),
        e(o)
    }
    ))
      , r = t=>{
        t && (o.cropOrigin = void 0,
        ra.filter((e=>Ke(t, e))).forEach((e=>{
            o[e] = aa(t[e])
        }
        )))
    }
    ;
    return {
        set: r,
        update: t=>r(t(null)),
        subscribe: n
    }
}
))]]
  , ga = async(t,e,o={},i)=>{
    const {ontaskstart: n, ontaskprogress: r, ontaskend: a, token: s} = i;
    let l = !1;
    s.cancel = ()=>{
        l = !0
    }
    ;
    for (const [i,s] of e.entries()) {
        if (l)
            return;
        const [e,c] = s;
        n(i, c);
        try {
            t = await e(t, {
                ...o
            }, (t=>r(i, c, t)))
        } catch (t) {
            throw l = !0,
            t
        }
        a(i, c)
    }
    return t
}
;
const fa = ["loadstart", "loadabort", "loaderror", "loadprogress", "load", "processstart", "processabort", "processerror", "processprogress", "process"]
  , $a = ["flip", "cropOrigin", "isRotatedSideways", "perspective", "perspectiveX", "perspectiveY", "cropRange"]
  , ya = ["images"]
  , xa = ma.map((([t])=>t)).filter((t=>!$a.includes(t)))
  , ba = t=>"image" + qr(t)
  , va = t=>Ke(t, "crop");
var wa = ()=>{
    const {stores: t, accessors: e} = Yr(Gr)
      , {sub: o, pub: i} = ho()
      , r = ()=>e.images ? e.images[0] : {};
    let a = {};
    xa.forEach((t=>{
        Object.defineProperty(e, ba(t), {
            get: ()=>{
                const e = r();
                if (e)
                    return e.accessors[t]
            }
            ,
            set: e=>{
                a[ba(t)] = e;
                const o = r();
                o && (o.accessors[t] = e)
            }
        })
    }
    ));
    const s = ()=>e.images && e.images[0]
      , l = t.src.subscribe((t=>{
        if (!t)
            return e.images = [];
        e.imageReader && (e.images.length && (a = {}),
        d(t))
    }
    ))
      , c = t.imageReader.subscribe((t=>{
        t && (e.images.length || e.src && d(e.src))
    }
    ))
      , d = t=>{
        Promise.resolve().then((()=>h(t, a))).catch((()=>{}
        ))
    }
    ;
    let u;
    const h = (t,o={})=>new Promise(((r,l)=>{
        let c = s();
        const d = !(!1 === o.cropLimitToImage || !1 === o.imageCropLimitToImage)
          , h = o.cropMinSize || o.imageCropMinSize
          , p = d ? h : c && c.accessors.cropMinSize;
        c && m(),
        c = (({minSize: t={
            width: 1,
            height: 1
        }}={})=>{
            const {stores: e, accessors: o} = Yr(ma)
              , {pub: i, sub: r} = ho()
              , a = (t,e)=>{
                const n = ()=>o[t] || {}
                  , r = e=>o[t] = {
                    ...n(),
                    ...e,
                    timeStamp: Date.now()
                }
                  , a = ()=>n().error
                  , s = t=>{
                    a() || (r({
                        error: t
                    }),
                    i(e + "error", {
                        ...n()
                    }))
                }
                ;
                return {
                    start() {
                        i(e + "start")
                    },
                    onabort() {
                        r({
                            abort: !0
                        }),
                        i(e + "abort", {
                            ...n()
                        })
                    },
                    ontaskstart(t, o) {
                        a() || (r({
                            index: t,
                            task: o,
                            taskProgress: void 0,
                            taskLengthComputable: void 0
                        }),
                        i(e + "taskstart", {
                            ...n()
                        }))
                    },
                    ontaskprogress(t, o, s) {
                        a() || (r({
                            index: t,
                            task: o,
                            taskProgress: s.loaded / s.total,
                            taskLengthComputable: s.lengthComputable
                        }),
                        i(e + "taskprogress", {
                            ...n()
                        }),
                        i(e + "progress", {
                            ...n()
                        }))
                    },
                    ontaskend(t, o) {
                        a() || (r({
                            index: t,
                            task: o
                        }),
                        i(e + "taskend", {
                            ...n()
                        }))
                    },
                    ontaskerror(t) {
                        s(t)
                    },
                    error(t) {
                        s(t)
                    },
                    beforeComplete(t) {
                        a() || (r({
                            beforeComplete: !0
                        }),
                        i("before" + e, t))
                    },
                    complete(t) {
                        a() || (r({
                            complete: !0
                        }),
                        i(e, t))
                    }
                }
            }
            ;
            return Zr(o, {
                read: (e,{reader: i})=>{
                    if (!i)
                        return;
                    Object.assign(o, {
                        file: void 0,
                        size: void 0,
                        loadState: void 0
                    });
                    let r = {
                        cancel: n
                    }
                      , s = !1;
                    const l = a("loadState", "load")
                      , c = {
                        token: r,
                        ...l
                    }
                      , d = {
                        src: e,
                        size: void 0,
                        dest: void 0
                    }
                      , u = {};
                    return Promise.resolve().then((async()=>{
                        try {
                            if (l.start(),
                            s)
                                return l.onabort();
                            const e = await ga(d, i, u, c);
                            if (s)
                                return l.onabort();
                            const {size: n, dest: a} = e || {};
                            if (!n || !n.width || !n.height)
                                throw new Te("Image size missing","IMAGE_SIZE_MISSING",e);
                            if (n.width < t.width || n.height < t.height)
                                throw new Te("Image too small","IMAGE_TOO_SMALL",{
                                    ...e,
                                    minWidth: t.width,
                                    minHeight: t.height
                                });
                            Object.assign(o, {
                                size: n,
                                file: a
                            }),
                            l.beforeComplete(e),
                            l.complete(e)
                        } catch (t) {
                            l.error(t)
                        } finally {
                            r = void 0
                        }
                    }
                    )),
                    ()=>{
                        s = !0,
                        r && r.cancel(),
                        l.onabort()
                    }
                }
                ,
                write: (t,e)=>{
                    if (!o.loadState.complete)
                        return;
                    o.processState = void 0;
                    const i = a("processState", "process")
                      , r = {
                        src: o.file,
                        imageState: o.state,
                        dest: void 0
                    };
                    if (!t)
                        return i.start(),
                        void i.complete(r);
                    let s = {
                        cancel: n
                    }
                      , l = !1;
                    const c = e
                      , d = {
                        token: s,
                        ...i
                    };
                    return Promise.resolve().then((async()=>{
                        try {
                            if (i.start(),
                            l)
                                return i.onabort();
                            const e = await ga(r, t, c, d);
                            i.complete(e)
                        } catch (t) {
                            i.error(t)
                        } finally {
                            s = void 0
                        }
                    }
                    )),
                    ()=>{
                        l = !0,
                        s && s.cancel()
                    }
                }
                ,
                on: r
            }),
            {
                accessors: o,
                stores: e
            }
        }
        )({
            minSize: p
        }),
        fa.map((t=>{
            return c.accessors.on(t, (e = t,
            t=>i(e, t)));
            var e
        }
        ));
        const g = ()=>{
            a = {},
            f.forEach((t=>t()))
        }
          , f = [];
        f.push(c.accessors.on("loaderror", (t=>{
            g(),
            l(t)
        }
        ))),
        f.push(c.accessors.on("loadabort", (()=>{
            g(),
            l({
                name: "AbortError"
            })
        }
        ))),
        f.push(c.accessors.on("load", (t=>{
            u = void 0,
            g(),
            r(t)
        }
        ))),
        f.push(c.accessors.on("beforeload", (()=>((t,o)=>{
            if (va(o))
                return void (e.imageState = o);
            if (!o.imageCrop) {
                const e = t.accessors.size
                  , i = o.imageRotation || 0
                  , n = Lt(St(mt(e), i))
                  , r = o.imageCropAspectRatio || (o.imageCropLimitToImage ? jt(e) : jt(n))
                  , a = Jt(n, r);
                o.imageCropLimitToImage || (a.x = (e.width - a.width) / 2,
                a.y = (e.height - a.height) / 2),
                o.imageCrop = a
            }
            ["imageCropLimitToImage", "imageCrop", "imageCropAspectRatio", "imageRotation"].filter((t=>Ke(o, t))).forEach((t=>{
                e[t] = o[t],
                delete o[t]
            }
            ));
            const {imageCropLimitToImage: i, imageCrop: n, imageCropAspectRatio: r, imageRotation: a, ...s} = o;
            Object.assign(e, s)
        }
        )(c, o)))),
        e.images = [c],
        o.imageReader && (e.imageReader = o.imageReader),
        o.imageWriter && (e.imageWriter = o.imageWriter),
        u = c.accessors.read(t, {
            reader: e.imageReader
        })
    }
    ));
    let p;
    const m = ()=>{
        const t = s();
        t && (u && u(),
        t.accessors.loadState = void 0,
        e.images = [])
    }
    ;
    return Object.defineProperty(e, "stores", {
        get: ()=>t
    }),
    Zr(e, {
        on: o,
        loadImage: h,
        abortLoadImage: ()=>{
            u && u(),
            e.images = []
        }
        ,
        editImage: (t,o)=>new Promise(((i,n)=>{
            h(t, o).then((()=>{
                const {images: t} = e
                  , o = t[0]
                  , r = ()=>{
                    a(),
                    s()
                }
                  , a = o.accessors.on("processerror", (t=>{
                    r(),
                    n(t)
                }
                ))
                  , s = o.accessors.on("process", (t=>{
                    r(),
                    i(t)
                }
                ))
            }
            )).catch(n)
        }
        )),
        removeImage: m,
        processImage: (t,o)=>new Promise((async(i,n)=>{
            (t=>w(t) || no(t) || de(t))(t) ? await h(t, o) : t && (va(t) ? e.imageState = t : Object.assign(e, t));
            const r = s();
            if (!r)
                return n("no image");
            const a = ()=>{
                p = void 0,
                l.forEach((t=>t()))
            }
              , l = [];
            l.push(r.accessors.on("processerror", (t=>{
                a(),
                n(t)
            }
            ))),
            l.push(r.accessors.on("processabort", (()=>{
                a(),
                n({
                    name: "AbortError"
                })
            }
            ))),
            l.push(r.accessors.on("process", (t=>{
                a(),
                i(t)
            }
            ))),
            p = r.accessors.write(e.imageWriter, {
                shapePreprocessor: e.shapePreprocessor || _,
                imageScrambler: e.imageScrambler,
                willRequestResource: e.willRequestResource
            })
        }
        )),
        abortProcessImage: ()=>{
            const t = s();
            t && (p && p(),
            t.accessors.processState = void 0)
        }
        ,
        destroy: ()=>{
            l(),
            c()
        }
    }),
    e
}
;
const Sa = (t,e)=>{
    const {processImage: o} = wa();
    return o(t, e)
}
;
var Ca = ()=>{
    if (!ye())
        return 1 / 0;
    const t = /15_/.test(navigator.userAgent);
    return Fe() ? t ? 14745600 : 16777216 : t ? 16777216 : 1 / 0
}
;
const ka = ({imageDataResizer: t}={})=>async(e,o,i,n)=>{
    const {dest: r} = await Sa(o, {
        imageReader: Ja(),
        imageWriter: Qa({
            format: "canvas",
            targetSize: {
                ...n,
                upscale: !0
            },
            imageDataResizer: t
        }),
        imageCrop: i
    });
    e.drawImage(r, n.x, n.y, n.width, n.height),
    g(r)
}
  , Ma = (t,e=((...t)=>t),o)=>async(i,n,r)=>{
    r(we(0, !1));
    let a = !1;
    const s = await t(...e(i, n, (t=>{
        a = !0,
        r(t)
    }
    )));
    return o && o(i, s),
    a || r(we(1, !1)),
    i
}
  , Ta = ({srcProp: t="src", destProp: e="dest"}={})=>[Ma(Pe, ((e,o,i)=>[e[t], i]), ((t,o)=>t[e] = o)), "any-to-file"]
  , Ra = ({srcProp: t="src", destProp: e="size"}={})=>[Ma(be, ((e,o)=>[e[t]]), ((t,o)=>t[e] = o)), "read-image-size"]
  , Pa = ({srcSize: t="size", srcOrientation: e="orientation", destSize: o="size"}={})=>[Ma(ze, (o=>[o[t], o[e]]), ((t,e)=>t[o] = e)), "image-size-match-orientation"]
  , Ia = ({srcProp: t="src", destProp: e="head"}={})=>[Ma(((t,e)=>Be(t) ? a(t, e) : void 0), (e=>[e[t], [0, 131072], onprogress]), ((t,o)=>t[e] = o)), "read-image-head"]
  , Aa = ({srcProp: t="head", destProp: e="orientation"}={})=>[Ma(o, (e=>[e[t], 274]), ((t,o=1)=>t[e] = o)), "read-exif-orientation-tag"]
  , Ea = ({srcProp: t="head"}={})=>[Ma(o, (e=>[e[t], 274, 1])), "clear-exif-orientation-tag"]
  , La = ({srcImageSize: t="size", srcCanvasSize: e="imageData", srcImageState: o="imageState", destImageSize: i="size", destScalar: n="scalar"}={})=>[Ma(((t,e)=>[Math.min(e.width / t.width, e.height / t.height), gt(e)]), (i=>[i[t], i[e], i[o]]), ((t,[e,o])=>{
    t[n] = e,
    t[i] = o
}
)), "calculate-canvas-scalar"]
  , Fa = ({srcProp: t="src", destProp: e="imageData", canvasMemoryLimit: o})=>[Ma(I, (e=>[e[t], o]), ((t,o)=>t[e] = o)), "blob-to-image-data"]
  , za = ({srcImageData: t="imageData", srcOrientation: e="orientation"}={})=>[Ma(y, (o=>[o[t], o[e]]), ((t,e)=>t.imageData = e)), "image-data-match-orientation"]
  , Ba = ({srcImageData: t="imageData", srcImageState: e="imageState"}={})=>[Ma(Ki, (o=>[o[t], {
    backgroundColor: o[e].backgroundColor
}]), ((t,e)=>t.imageData = e)), "image-data-fill"]
  , Oa = ({srcImageData: t="imageData", srcImageState: e="imageState", destScalar: o="scalar"}={})=>[Ma(_e, (i=>{
    const n = i[o];
    let {crop: r} = i[e];
    return r && 1 !== n && (r = Vt(At(r), n, X())),
    [i[t], {
        crop: r,
        rotation: i[e].rotation,
        flipX: i[e].flipX,
        flipY: i[e].flipY
    }]
}
), ((t,e)=>t.imageData = e)), "image-data-crop"]
  , Da = ({targetSize: t={
    width: void 0,
    height: void 0,
    fit: void 0,
    upscale: void 0
}, imageDataResizer: e, srcProp: o="imageData", srcImageState: i="imageState", destImageScaledSize: n="imageScaledSize"})=>[Ma(He, (n=>{
    return [n[o], {
        width: Math.min(t.width || Number.MAX_SAFE_INTEGER, n[i].targetSize && n[i].targetSize.width || Number.MAX_SAFE_INTEGER),
        height: Math.min(t.height || Number.MAX_SAFE_INTEGER, n[i].targetSize && n[i].targetSize.height || Number.MAX_SAFE_INTEGER),
        fit: t.fit || "contain",
        upscale: (r = n[i],
        !!(r.targetSize && r.targetSize.width || r.targetSize && r.targetSize.height) || (t.upscale || !1))
    }, e];
    var r
}
), ((t,e)=>{
    bt(t.imageData, e) || (t[n] = gt(e)),
    t.imageData = e
}
)), "image-data-resize"]
  , _a = ({srcImageData: t="imageData", srcImageState: e="imageState", destImageData: o="imageData"}={})=>[Ma(Ge, (o=>{
    const {colorMatrix: i} = o[e]
      , n = i && Object.keys(i).map((t=>i[t])).filter(Boolean);
    return [o[t], {
        colorMatrix: n && Ji(n),
        convolutionMatrix: o[e].convolutionMatrix,
        gamma: o[e].gamma,
        noise: o[e].noise,
        vignette: o[e].vignette
    }]
}
), ((t,e)=>t[o] = e)), "image-data-filter"]
  , Wa = (t,{srcSize: e, srcImageState: o, destImageScaledSize: i, destScalar: n})=>r=>{
    const a = t[e]
      , {rotation: s=0, flipX: l, flipY: c} = t[o]
      , d = t[n];
    let {crop: u=Lt(a)} = t[o];
    1 !== d && (u = Vt(At(u), d, X()));
    const h = ((t,e)=>{
        const o = Lt(t)
          , i = _t(o)
          , n = Gt(o, e, i);
        return ie(zt(n))
    }
    )(a, s)
      , p = h.width
      , m = h.height
      , g = t[i]
      , f = g ? Math.min(g.width / u.width, g.height / u.height) : 1
      , $ = .5 * a.width - .5 * p
      , y = .5 * a.height - .5 * m
      , x = wt(a);
    r.scale(f, f),
    r.translate(-$, -y),
    r.translate(-u.x, -u.y),
    r.translate(x.x, x.y),
    r.rotate(s),
    r.translate(-x.x, -x.y),
    r.scale(l ? -1 : 1, c ? -1 : 1),
    r.translate(l ? -a.width : 0, c ? -a.height : 0),
    r.rect(0, 0, a.width, a.height),
    r.clip()
}
  , Va = ({srcImageData: t="imageData", srcImageState: e="imageState", destImageData: o="imageData", destScalar: i="scalar"}={})=>[Ma((async(t,e,o,i,n)=>{
    if (!e)
        return t;
    let r;
    try {
        const n = {
            dataSizeScalar: Qi(t, i)
        };
        o && o[3] > 0 && (n.backgroundColor = [...o]),
        r = await e(t, n)
    } catch (t) {}
    const a = p("canvas");
    a.width = t.width,
    a.height = t.height;
    const s = a.getContext("2d");
    s.putImageData(t, 0, 0);
    const l = new Path2D;
    i.forEach((t=>{
        const e = Dt(t.x, t.y, t.width, t.height);
        Ht(e, n);
        const o = Gt(At(e), t.rotation)
          , i = new Path2D;
        o.forEach(((t,e)=>{
            if (0 === e)
                return i.moveTo(t.x, t.y);
            i.lineTo(t.x, t.y)
        }
        )),
        l.addPath(i)
    }
    )),
    s.clip(l, "nonzero"),
    s.imageSmoothingEnabled = !1,
    s.drawImage(r, 0, 0, a.width, a.height),
    g(r);
    const c = s.getImageData(0, 0, a.width, a.height);
    return g(a),
    c
}
), ((o,{imageScrambler: n})=>[o[t], n, o[e].backgroundColor, o[e].redaction, o[i]]), ((t,e)=>t[o] = e)), "image-data-redact"]
  , Ha = ({srcImageData: t="imageData", srcSize: e="size", srcImageState: o="imageState", destImageData: i="imageData", destImageScaledSize: n="imageScaledSize", destScalar: r="scalar", imageDataResizer: a}={})=>[Ma(Zi, ((i,{shapePreprocessor: s, willRequestResource: l})=>{
    const c = i[r];
    let {annotation: d} = i[o];
    if (1 !== c) {
        const t = X();
        d = d.map((e=>Ei(Ao(e), t, c)))
    }
    return [i[t], {
        shapes: d,
        context: i[e],
        transform: Wa(i, {
            srcSize: e,
            srcImageState: o,
            destImageScaledSize: n,
            destScalar: r
        }),
        drawImage: ka({
            imageDataResizer: a
        }),
        preprocessShape: t=>s(t, {
            isPreview: !1
        }),
        willRequestResource: l
    }]
}
), ((t,e)=>t[i] = e)), "image-data-annotate"]
  , Na = ({srcImageData: t="imageData", srcImageState: e="imageState", destImageData: o="imageData", destImageScaledSize: i="imageScaledSize", imageDataResizer: n, destScalar: r="scalar"}={})=>[Ma(Zi, ((o,{shapePreprocessor: a, willRequestResource: s})=>{
    const l = o[r];
    let {decoration: c, crop: d} = o[e];
    if (1 !== l) {
        const t = X();
        c = c.map((e=>Ei(Ao(e), t, l))),
        d && (d = Vt(At(d), l, X()))
    }
    return [o[t], {
        shapes: c,
        context: d,
        transform: t=>{
            const e = o[i]
              , n = e ? Math.min(e.width / d.width, e.height / d.height) : 1;
            t.scale(n, n)
        }
        ,
        drawImage: ka({
            imageDataResizer: n
        }),
        preprocessShape: t=>a(t, {
            isPreview: !1
        }),
        willRequestResource: s
    }]
}
), ((t,e)=>t[o] = e)), "image-data-decorate"]
  , Ua = ({srcImageData: t="imageData", srcImageState: e="imageState", destImageData: o="imageData", destImageScaledSize: i="imageScaledSize", imageDataResizer: n, destScalar: r="scalar"}={})=>[Ma(Zi, ((o,{shapePreprocessor: a, willRequestResource: s})=>{
    const l = o[e].frame;
    if (!l)
        return [o[t]];
    const c = o[r];
    let {crop: d} = o[e];
    d && 1 !== c && (d = Vt(At(d), c, X()));
    const u = {
        ...d
    }
      , h = Pi(Ii(l, u, a), u);
    u.x = Math.abs(h.left),
    u.y = Math.abs(h.top),
    u.width += Math.abs(h.left) + Math.abs(h.right),
    u.height += Math.abs(h.top) + Math.abs(h.bottom);
    const p = o[i]
      , m = p ? Math.min(p.width / d.width, p.height / d.height) : 1;
    return Ht(u, m),
    u.x = Math.floor(u.x),
    u.y = Math.floor(u.y),
    u.width = Math.floor(u.width),
    u.height = Math.floor(u.height),
    [o[t], {
        shapes: [l],
        contextBounds: u,
        transform: t=>{
            t.translate(u.x, u.y)
        }
        ,
        drawImage: ka({
            imageDataResizer: n
        }),
        preprocessShape: t=>a(t, {
            isPreview: !1
        }),
        willRequestResource: s
    }]
}
), ((t,e)=>t[o] = e)), "image-data-frame"]
  , ja = ({mimeType: t, quality: e, srcImageData: o="imageData", srcFile: i="src", destBlob: n="blob"}={})=>[Ma(E, (n=>[n[o], t || B(n[i].name) || n[i].type, e]), ((t,e)=>t[n] = e)), "image-data-to-blob"]
  , Xa = ({srcImageData: t="imageData", srcOrientation: e="orientation", destCanvas: o="dest"}={})=>[Ma($, (o=>[o[t], o[e]]), ((t,e)=>t[o] = e)), "image-data-to-canvas"]
  , Ya = async(t,o)=>{
    if (!Be(t) || !o)
        return t;
    const i = new DataView(o)
      , n = e(i);
    if (!n || !n.exif)
        return t;
    const {exif: r} = n;
    return ((t,e,o=[0, t.size])=>e ? new Blob([e, t.slice(...o)],{
        type: t.type
    }) : t)(t, o.slice(0, r.offset + r.size + 2), [20])
}
  , Ga = (t="blob",e="head",o="blob")=>[Ma(Ya, (o=>[o[t], o[e]]), ((t,e)=>t[o] = e)), "blob-write-image-head"]
  , qa = ({renameFile: t, srcBlob: e="blob", srcFile: o="src", destFile: i="dest", defaultFilename: n}={})=>[Ma(O, (i=>[i[e], t ? t(i[o]) : i[o].name || `${n}.${L(i[e].type)}`]), ((t,e)=>t[i] = e)), "blob-to-file"]
  , Za = ({url: t="./", dataset: e=(t=>[["dest", t.dest, t.dest.name], ["imageState", t.imageState]]), destStore: o="store"})=>[Ma((async(e,o)=>await ((t,e,o)=>new Promise(((i,r)=>{
    const {token: a={}, beforeSend: s=n, onprogress: l=n} = o;
    a.cancel = ()=>c.abort();
    const c = new XMLHttpRequest;
    c.upload.onprogress = l,
    c.onload = ()=>c.status >= 200 && c.status < 300 ? i(c) : r(c),
    c.onerror = ()=>r(c),
    c.ontimeout = ()=>r(c),
    c.open("POST", encodeURI(t)),
    s(c),
    c.send(e.reduce(((t,e)=>(t.append(...e.map(Oe)),
    t)), new FormData))
}
)))(t, e, {
    onprogress: o
})), ((t,o,i)=>[e(t), i]), ((t,e)=>t[o] = e)), "store"]
  , Ka = t=>[Ma((e=>t && t.length ? (Object.keys(e).forEach((o=>{
    t.includes(o) || delete e[o]
}
)),
e) : e)), "prop-filter"]
  , Ja = (t={})=>{
    const {orientImage: e=!0, outputProps: o=["src", "dest", "size"], preprocessImageFile: i} = t;
    return [Ta(), i && [Ma(i, ((t,e,o)=>[t.dest, e, o]), ((t,e)=>t.dest = e)), "preprocess-image-file"], Ra({
        srcProp: "dest"
    }), e && Ia({
        srcProp: "dest"
    }), e && Aa(), e && Pa(), Ka(o)].filter(Boolean)
}
  , Qa = (t={})=>{
    const {canvasMemoryLimit: e=Ca(), orientImage: o=!0, copyImageHead: i=!0, mimeType: n, quality: r, renameFile: a, targetSize: s, imageDataResizer: l, store: c, format: d="file", outputProps: u=["src", "dest", "imageState", "store"], preprocessImageSource: h, preprocessImageState: p, postprocessImageData: m, postprocessImageBlob: g} = t;
    return [h && [Ma(h, ((t,e,o)=>[t.src, e, o]), ((t,e)=>t.src = e)), "preprocess-image-source"], (o || i) && Ia(), o && Aa(), Ra(), p && [Ma(p, ((t,e,o)=>[t.imageState, e, o]), ((t,e)=>t.imageState = e)), "preprocess-image-state"], Fa({
        canvasMemoryLimit: e
    }), o && Pa(), o && za(), La(), Va(), Oa(), Da({
        targetSize: s,
        imageDataResizer: l
    }), _a(), Ba(), Ha({
        imageDataResizer: l
    }), Na({
        imageDataResizer: l
    }), Ua({
        imageDataResizer: l
    }), m && [Ma(m, ((t,e,o)=>[t.imageData, e, o]), ((t,e)=>t.imageData = e)), "postprocess-image-data"], "file" === d ? ja({
        mimeType: n,
        quality: r
    }) : "canvas" === d ? Xa() : [t=>(t.dest = t.imageData,
    t)], "file" === d && o && Ea(), "file" === d && i && Ga(), g && [Ma(g, (({blob: t, imageData: e, src: o},i,n)=>[{
        blob: t,
        imageData: e,
        src: o
    }, i, n]), ((t,e)=>t.blob = e)), "postprocess-image-file"], "file" === d && qa({
        defaultFilename: "image",
        renameFile: a
    }), "file" === d ? c && (w(c) ? Za({
        url: c
    }) : S(c) ? [c, "store"] : Za(c)) : S(c) && [c, "store"], Ka(u)].filter(Boolean)
}
;
var ts = (t,e)=>{
    const {imageData: o, amount: i=1} = t
      , n = Math.round(2 * Math.max(1, i))
      , r = Math.round(.5 * n)
      , a = o.width
      , s = o.height
      , l = new Uint8ClampedArray(a * s * 4)
      , c = o.data;
    let d, u, h, p, m, g = 0, f = 0, $ = 0;
    const y = a * s * 4 - 4;
    for (h = 0; h < s; h++)
        for (d = crypto.getRandomValues(new Uint8ClampedArray(s)),
        u = 0; u < a; u++)
            p = d[h] / 255,
            f = 0,
            $ = 0,
            p < .5 && (f = 4 * (-r + Math.round(Math.random() * n))),
            p > .5 && ($ = (-r + Math.round(Math.random() * n)) * (4 * a)),
            m = Math.min(Math.max(0, g + f + $), y),
            l[g] = c[m],
            l[g + 1] = c[m + 1],
            l[g + 2] = c[m + 2],
            l[g + 3] = c[m + 3],
            g += 4;
    e(null, {
        data: l,
        width: o.width,
        height: o.height
    })
}
;
const es = [.0625, .125, .0625, .125, .25, .125, .0625, .125, .0625];
var os = t=>{
    const e = Object.getOwnPropertyDescriptors(t.prototype);
    return Object.keys(e).filter((t=>!!e[t].get))
}
;
function is(t) {
    return Math.sqrt(1 - --t * t)
}
function ns(t) {
    return "[object Date]" === Object.prototype.toString.call(t)
}
function rs(t, e) {
    if (t === e || t != t)
        return ()=>t;
    const o = typeof t;
    if (o !== typeof e || Array.isArray(t) !== Array.isArray(e))
        throw new Error("Cannot interpolate values of different type");
    if (Array.isArray(t)) {
        const o = e.map(((e,o)=>rs(t[o], e)));
        return t=>o.map((e=>e(t)))
    }
    if ("object" === o) {
        if (!t || !e)
            throw new Error("Object cannot be null");
        if (ns(t) && ns(e)) {
            t = t.getTime();
            const o = (e = e.getTime()) - t;
            return e=>new Date(t + e * o)
        }
        const o = Object.keys(e)
          , i = {};
        return o.forEach((o=>{
            i[o] = rs(t[o], e[o])
        }
        )),
        t=>{
            const e = {};
            return o.forEach((o=>{
                e[o] = i[o](t)
            }
            )),
            e
        }
    }
    if ("number" === o) {
        const o = e - t;
        return e=>t + e * o
    }
    throw new Error(`Cannot interpolate ${o} values`)
}
function as(t, e={}) {
    const o = _r(t);
    let i, n = t;
    function r(r, a) {
        if (null == t)
            return o.set(t = r),
            Promise.resolve();
        n = r;
        let s = i
          , l = !1
          , {delay: c=0, duration: d=400, easing: u=en, interpolate: h=rs} = on(on({}, e), a);
        if (0 === d)
            return s && (s.abort(),
            s = null),
            o.set(t = n),
            Promise.resolve();
        const p = bn() + c;
        let m;
        return i = Cn((e=>{
            if (e < p)
                return !0;
            l || (m = h(t, r),
            "function" == typeof d && (d = d(t, r)),
            l = !0),
            s && (s.abort(),
            s = null);
            const i = e - p;
            return i > d ? (o.set(t = r),
            !1) : (o.set(t = m(u(i / d))),
            !0)
        }
        )),
        i.promise
    }
    return {
        set: r,
        update: (e,o)=>r(e(n, t), o),
        subscribe: o.subscribe
    }
}
function ss(t, e, o, i) {
    if ("number" == typeof o) {
        const n = i - o
          , r = (o - e) / (t.dt || 1 / 60)
          , a = (r + (t.opts.stiffness * n - t.opts.damping * r) * t.inv_mass) * t.dt;
        return Math.abs(a) < t.opts.precision && Math.abs(n) < t.opts.precision ? i : (t.settled = !1,
        o + a)
    }
    if (Je(o))
        return o.map(((n,r)=>ss(t, e[r], o[r], i[r])));
    if ("object" == typeof o) {
        const n = {};
        for (const r in o)
            n[r] = ss(t, e[r], o[r], i[r]);
        return n
    }
    throw new Error(`Cannot spring ${typeof o} values`)
}
function ls(t, e={}) {
    const o = _r(t)
      , {stiffness: i=.15, damping: n=.8, precision: r=.01} = e;
    let a, s, l, c = t, d = t, u = 1, h = 0, p = !1;
    function m(e, i={}) {
        d = e;
        const n = l = {};
        if (null == t || i.hard || g.stiffness >= 1 && g.damping >= 1)
            return p = !0,
            a = null,
            c = e,
            o.set(t = d),
            Promise.resolve();
        if (i.soft) {
            const t = !0 === i.soft ? .5 : +i.soft;
            h = 1 / (60 * t),
            u = 0
        }
        if (!s) {
            a = null,
            p = !1;
            const e = {
                inv_mass: void 0,
                opts: g,
                settled: !0,
                dt: void 0
            };
            s = Cn((i=>{
                if (null === a && (a = i),
                p)
                    return p = !1,
                    s = null,
                    !1;
                u = Math.min(u + h, 1),
                e.inv_mass = u,
                e.opts = g,
                e.settled = !0,
                e.dt = 60 * (i - a) / 1e3;
                const n = ss(e, c, t, d);
                return a = i,
                c = t,
                o.set(t = n),
                e.settled && (s = null),
                !e.settled
            }
            ))
        }
        return new Promise((t=>{
            s.promise.then((()=>{
                n === l && t()
            }
            ))
        }
        ))
    }
    const g = {
        set: m,
        update: (e,o)=>m(e(d, t), o),
        subscribe: o.subscribe,
        stiffness: i,
        damping: n,
        precision: r
    };
    return g
}
var cs = Dr(!1, (t=>{
    const e = window.matchMedia("(prefers-reduced-motion:reduce)");
    t(e.matches),
    e.onchange = ()=>t(e.matches)
}
));
const ds = Et()
  , us = (t,e,o,i,n)=>{
    t.rect || (t.rect = Et());
    const r = t.rect;
    Xt(ds, e, o, i, n),
    Ut(r, ds) || (Yt(r, ds),
    t.dispatchEvent(new CustomEvent("measure",{
        detail: r
    })))
}
  , hs = Math.round
  , ps = t=>{
    const e = t.getBoundingClientRect();
    us(t, hs(e.x), hs(e.y), hs(e.width), hs(e.height))
}
  , ms = t=>us(t, t.offsetLeft, t.offsetTop, t.offsetWidth, t.offsetHeight)
  , gs = [];
let fs, $s = null;
function ys() {
    gs.length ? (gs.forEach((t=>t.measure(t))),
    $s = requestAnimationFrame(ys)) : $s = null
}
let xs = 0;
var bs = (t,e={})=>{
    const {observePosition: o=!1, observeViewRect: i=!1, once: n=!1, disabled: r=!1} = e;
    if (!r)
        return !("ResizeObserver"in window) || o || i ? (t.measure = i ? ps : ms,
        gs.push(t),
        $s || ($s = requestAnimationFrame(ys)),
        t.measure(t),
        {
            destroy() {
                const e = gs.indexOf(t);
                gs.splice(e, 1),
                delete t.measure
            }
        }) : (fs || (fs = new ResizeObserver((t=>{
            t.forEach((t=>ms(t.target)))
        }
        ))),
        fs.observe(t),
        ms(t),
        n ? fs.unobserve(t) : xs++,
        {
            destroy() {
                n || (fs.unobserve(t),
                xs--,
                0 === xs && (fs.disconnect(),
                fs = void 0))
            }
        })
}
  , vs = t=>{
    let e = !1;
    const o = {
        pointerdown: ()=>{
            e = !1
        }
        ,
        keydown: ()=>{
            e = !0
        }
        ,
        keyup: ()=>{
            e = !1
        }
        ,
        focus: t=>{
            e && (t.target.dataset.focusVisible = "")
        }
        ,
        blur: t=>{
            delete t.target.dataset.focusVisible
        }
    };
    return Object.keys(o).forEach((e=>t.addEventListener(e, o[e], !0))),
    {
        destroy() {
            Object.keys(o).forEach((e=>t.removeEventListener(e, o[e], !0)))
        }
    }
}
;
const ws = async t=>new Promise((e=>{
    if ("file" === t.kind)
        return e(t.getAsFile());
    t.getAsString(e)
}
));
var Ss = (t,e={})=>{
    const o = t=>{
        t.preventDefault()
    }
      , i = async o=>{
        o.preventDefault(),
        o.stopPropagation();
        try {
            const i = await (t=>new Promise(((e,o)=>{
                const {items: i} = t.dataTransfer;
                if (!i)
                    return e([]);
                Promise.all(Array.from(i).map(ws)).then((t=>{
                    e(t.filter((t=>no(t) && Se(t) || /^http/.test(t))))
                }
                )).catch(o)
            }
            )))(o);
            t.dispatchEvent(new CustomEvent("dropfiles",{
                detail: {
                    event: o,
                    resources: i
                },
                ...e
            }))
        } catch (t) {}
    }
    ;
    return t.addEventListener("drop", i),
    t.addEventListener("dragover", o),
    {
        destroy() {
            t.removeEventListener("drop", i),
            t.removeEventListener("dragover", o)
        }
    }
}
;
let Cs = null;
var ks = ()=>{
    if (null === Cs)
        if ("WebGL2RenderingContext"in window) {
            let t;
            try {
                t = p("canvas"),
                Cs = !!t.getContext("webgl2")
            } catch (t) {
                Cs = !1
            }
            t && g(t),
            t = void 0
        } else
            Cs = !1;
    return Cs
}
  , Ms = (t,e)=>ks() ? t.getContext("webgl2", e) : t.getContext("webgl", e) || t.getContext("experimental-webgl", e);
let Ts = null;
var Rs = ()=>{
    if (null === Ts)
        if (c()) {
            const t = p("canvas");
            Ts = !Ms(t, {
                failIfMajorPerformanceCaveat: !0
            }),
            g(t)
        } else
            Ts = !1;
    return Ts
}
  , Ps = t=>0 == (t & t - 1)
  , Is = (t,e={},o="",i="")=>Object.keys(e).filter((t=>!x(e[t]))).reduce(((t,n)=>t.replace(new RegExp(o + n + i), e[n])), t);
const As = {
    head: "#version 300 es\n\nin vec4 aPosition;uniform mat4 uMatrix;",
    text: "\nin vec2 aTexCoord;out vec2 vTexCoord;",
    matrix: "\ngl_Position=uMatrix*vec4(aPosition.x,aPosition.y,0,1);"
}
  , Es = {
    head: "#version 300 es\nprecision highp float;\n\nout vec4 fragColor;",
    mask: "\nuniform float uMaskFeather[8];uniform float uMaskBounds[4];uniform float uMaskOpacity;float mask(float x,float y,float bounds[4],float opacity){return 1.0-(1.0-(smoothstep(bounds[3],bounds[3]+1.0,x)*(1.0-smoothstep(bounds[1]-1.0,bounds[1],x))*(1.0-step(bounds[0],y))*step(bounds[2],y)))*(1.0-opacity);}",
    init: "\nfloat a=1.0;vec4 fillColor=uColor;vec4 textureColor=texture(uTexture,vTexCoord);textureColor*=(1.0-step(1.0,vTexCoord.y))*step(0.0,vTexCoord.y)*(1.0-step(1.0,vTexCoord.x))*step(0.0,vTexCoord.x);",
    colorize: "\nif(uTextureColor.a!=0.0&&textureColor.a>0.0){vec3 colorFlattened=textureColor.rgb/textureColor.a;if(colorFlattened.r>=.9999&&colorFlattened.g==0.0&&colorFlattened.b>=.9999){textureColor.rgb=uTextureColor.rgb*textureColor.a;}textureColor*=uTextureColor.a;}",
    maskapply: "\nfloat m=mask(gl_FragCoord.x,gl_FragCoord.y,uMaskBounds,uMaskOpacity);",
    maskfeatherapply: "\nfloat leftFeatherOpacity=step(uMaskFeather[1],gl_FragCoord.x)*uMaskFeather[0]+((1.0-uMaskFeather[0])*smoothstep(uMaskFeather[1],uMaskFeather[3],gl_FragCoord.x));float rightFeatherOpacity=(1.0-step(uMaskFeather[7],gl_FragCoord.x))*uMaskFeather[4]+((1.0-uMaskFeather[4])*smoothstep(uMaskFeather[7],uMaskFeather[5],gl_FragCoord.x));a*=leftFeatherOpacity*rightFeatherOpacity;",
    edgeaa: "\nvec2 scaledPoint=vec2(vRectCoord.x*uSize.x,vRectCoord.y*uSize.y);a*=smoothstep(0.0,1.0,uSize.x-scaledPoint.x);a*=smoothstep(0.0,1.0,uSize.y-scaledPoint.y);a*=smoothstep(0.0,1.0,scaledPoint.x);a*=smoothstep(0.0,1.0,scaledPoint.y);",
    cornerradius: "\nvec2 s=(uSize-2.0)*.5;vec2 r=(vRectCoord*uSize);vec2 p=r-(uSize*.5);float cornerRadius=uCornerRadius[0];bool left=r.x<s.x;bool top=r.y<s.x;if(!left&&top){cornerRadius=uCornerRadius[1];}if(!left&&!top){cornerRadius=uCornerRadius[3];}if(left&&!top){cornerRadius=uCornerRadius[2];}a*=1.0-clamp(length(max(abs(p)-(s-cornerRadius),0.0))-cornerRadius,0.0,1.0);",
    fragcolor: "\nif(m<=0.0)discard;fillColor.a*=a;fillColor.rgb*=fillColor.a;fillColor.rgb*=m;fillColor.rgb+=(1.0-m)*(uCanvasColor.rgb*fillColor.a);textureColor*=uTextureOpacity;textureColor.a*=a;textureColor.rgb*=m*a;textureColor.rgb+=(1.0-m)*(uCanvasColor.rgb*textureColor.a);fragColor=textureColor+(fillColor*(1.0-textureColor.a));"
}
  , Ls = (t,e,o)=>{
    const i = t.createShader(o)
      , n = ((t,e,o)=>(e = Is(e, o === t.VERTEX_SHADER ? As : Es, "##").trim(),
    ks() ? e : (e = (e = e.replace(/#version.+/gm, "").trim()).replace(/^\/\/\#/gm, "#"),
    o === t.VERTEX_SHADER && (e = e.replace(/in /gm, "attribute ").replace(/out /g, "varying ")),
    o === t.FRAGMENT_SHADER && (e = e.replace(/in /gm, "varying ").replace(/out.*?;/gm, "").replace(/texture\(/g, "texture2D(").replace(/fragColor/g, "gl_FragColor")),
    "" + e)))(t, e, o);
    return t.shaderSource(i, n),
    t.compileShader(i),
    t.getShaderParameter(i, t.COMPILE_STATUS) || console.error(t.getShaderInfoLog(i)),
    i
}
  , Fs = (t,e,o,i,n)=>{
    const r = Ls(t, e, t.VERTEX_SHADER)
      , a = Ls(t, o, t.FRAGMENT_SHADER)
      , s = t.createProgram();
    t.attachShader(s, r),
    t.attachShader(s, a),
    t.linkProgram(s);
    const l = {};
    return i.forEach((e=>{
        l[e] = t.getAttribLocation(s, e)
    }
    )),
    n.forEach((e=>{
        l[e] = t.getUniformLocation(s, e)
    }
    )),
    {
        program: s,
        locations: l,
        destroy() {
            t.detachShader(s, r),
            t.detachShader(s, a),
            t.deleteShader(r),
            t.deleteShader(a),
            t.deleteProgram(s)
        }
    }
}
  , zs = t=>!!ks() || Ps(t.width) && Ps(t.height)
  , Bs = (t,e,o,i)=>(t.bindTexture(t.TEXTURE_2D, e),
t.texImage2D(t.TEXTURE_2D, 0, t.RGBA, t.RGBA, t.UNSIGNED_BYTE, o),
((t,e,o)=>{
    t.texParameteri(t.TEXTURE_2D, t.TEXTURE_MIN_FILTER, zs(e) ? t.LINEAR_MIPMAP_LINEAR : t.LINEAR),
    t.texParameteri(t.TEXTURE_2D, t.TEXTURE_MAG_FILTER, o.filter),
    t.texParameteri(t.TEXTURE_2D, t.TEXTURE_WRAP_S, t.CLAMP_TO_EDGE),
    t.texParameteri(t.TEXTURE_2D, t.TEXTURE_WRAP_T, t.CLAMP_TO_EDGE),
    zs(e) && t.generateMipmap(t.TEXTURE_2D)
}
)(t, o, i),
t.bindTexture(t.TEXTURE_2D, null),
e)
  , Os = (t,e=1)=>t ? [t[0], t[1], t[2], qe(t[3]) ? e * t[3] : e] : [0, 0, 0, 0]
  , Ds = ()=>{
    const t = new Float32Array(16);
    return t[0] = 1,
    t[5] = 1,
    t[10] = 1,
    t[15] = 1,
    t
}
  , _s = (t,e,o,i,n,r,a)=>{
    const s = 1 / (e - o)
      , l = 1 / (i - n)
      , c = 1 / (r - a);
    t[0] = -2 * s,
    t[1] = 0,
    t[2] = 0,
    t[3] = 0,
    t[4] = 0,
    t[5] = -2 * l,
    t[6] = 0,
    t[7] = 0,
    t[8] = 0,
    t[9] = 0,
    t[10] = 2 * c,
    t[11] = 0,
    t[12] = (e + o) * s,
    t[13] = (n + i) * l,
    t[14] = (a + r) * c,
    t[15] = 1
}
  , Ws = (t,e,o,i)=>{
    t[12] = t[0] * e + t[4] * o + t[8] * i + t[12],
    t[13] = t[1] * e + t[5] * o + t[9] * i + t[13],
    t[14] = t[2] * e + t[6] * o + t[10] * i + t[14],
    t[15] = t[3] * e + t[7] * o + t[11] * i + t[15]
}
;
var Vs = t=>t * Math.PI / 180;
const Hs = (t,e,o,i,n)=>{
    const r = tt(Y(i.x - o.x, i.y - o.y))
      , a = tt(Y(n.x - i.x, n.y - i.y))
      , s = tt(Y(r.x + a.x, r.y + a.y))
      , l = Y(-s.y, s.x)
      , c = Y(-r.y, r.x)
      , d = Math.min(1 / st(l, c), 5);
    t[e] = i.x,
    t[e + 1] = i.y,
    t[e + 2] = l.x * d,
    t[e + 3] = l.y * d,
    t[e + 4] = -1,
    t[e + 5] = i.x,
    t[e + 6] = i.y,
    t[e + 7] = l.x * d,
    t[e + 8] = l.y * d,
    t[e + 9] = 1
}
  , Ns = t=>{
    const e = new Float32Array(8);
    return e[0] = t[3].x,
    e[1] = t[3].y,
    e[2] = t[0].x,
    e[3] = t[0].y,
    e[4] = t[2].x,
    e[5] = t[2].y,
    e[6] = t[1].x,
    e[7] = t[1].y,
    e
}
  , Us = (t,e=0,o,i)=>{
    const n = te(t)
      , r = t.x + .5 * t.width
      , a = t.y + .5 * t.height;
    return (o || i) && ut(n, o, i, r, a),
    0 !== e && ht(n, e, r, a),
    n
}
  , js = (t,e,o,i,n)=>{
    const r = Math.min(20, Math.max(4, Math.round(i / 2)));
    let a = 0
      , s = 0
      , l = 0
      , c = 0
      , d = 0;
    for (; d < r; d++)
        a = d / r,
        s = n * V + a * V,
        l = i * Math.cos(s),
        c = i * Math.sin(s),
        t.push(Y(e + l, o + c))
}
;
let Xs = null;
var Ys = ()=>{
    if (null !== Xs)
        return Xs;
    let t = p("canvas");
    const e = Ms(t);
    return Xs = e ? e.getParameter(e.MAX_TEXTURE_SIZE) : void 0,
    g(t),
    t = void 0,
    Xs
}
;
let Gs = null;
const qs = new Float32Array([0, 1, 0, 0, 1, 1, 1, 0])
  , Zs = [0, 0, 0, 0, 1, 0, 0, 0, 0]
  , Ks = [1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0]
  , Js = [0, 0, 0, 0]
  , Qs = [0, 0, 0, 0]
  , tl = (t,e,o,i,n)=>{
    if (!o || !i)
        return qs;
    const r = i.x / o.width
      , a = i.y / o.height;
    let s = t / o.width / n
      , l = e / o.height / n;
    s -= r,
    l -= a;
    return new Float32Array([-r, l, -r, -a, s, l, s, -a])
}
;
var el = t=>{
    const e = {
        width: 0,
        height: 0
    }
      , o = {
        width: 0,
        height: 0
    }
      , i = Ys() || 1024;
    let n, r;
    const a = Ds()
      , s = Ds();
    let l, c, d, u, h, p, m, f, $, y = 0, x = 0, b = 0;
    const v = new Map([])
      , w = Vs(30)
      , S = Math.tan(w / 2)
      , C = Ms(t, {
        antialias: !1,
        alpha: !1,
        premultipliedAlpha: !0
    });
    if (!C)
        return;
    C.getExtension("OES_standard_derivatives"),
    C.disable(C.DEPTH_TEST),
    C.enable(C.BLEND),
    C.blendFunc(C.ONE, C.ONE_MINUS_SRC_ALPHA),
    C.pixelStorei(C.UNPACK_PREMULTIPLY_ALPHA_WEBGL, (null === Gs && (Gs = Ee(/Firefox/)),
    !Gs));
    const k = C.createTexture();
    C.bindTexture(C.TEXTURE_2D, k),
    C.texImage2D(C.TEXTURE_2D, 0, C.RGBA, 1, 1, 0, C.RGBA, C.UNSIGNED_BYTE, new Uint8Array(Js)),
    v.set(0, k);
    const M = C.createTexture();
    v.set(2, M);
    const T = C.createFramebuffer()
      , R = C.createTexture();
    v.set(1, R);
    const P = C.createFramebuffer()
      , I = Fs(C, "\n##head\n##text\nvoid main(){vTexCoord=aTexCoord;gl_Position=uMatrix*aPosition;}", "\n##head\nin vec2 vTexCoord;uniform sampler2D uTexture;uniform sampler2D uTextureMarkup;uniform sampler2D uTextureBlend;uniform vec2 uTextureSize;uniform float uOpacity;uniform vec4 uFillColor;uniform vec4 uOverlayColor;uniform mat4 uColorMatrix;uniform vec4 uColorOffset;uniform float uClarityKernel[9];uniform float uClarityKernelWeight;uniform float uColorGamma;uniform float uColorVignette;uniform float uMaskClip;uniform float uMaskOpacity;uniform float uMaskBounds[4];uniform float uMaskCornerRadius[4];uniform float uMaskFeather[8];vec4 applyGamma(vec4 c,float g){c.r=pow(c.r,g);c.g=pow(c.g,g);c.b=pow(c.b,g);return c;}vec4 applyColorMatrix(vec4 c,mat4 m,vec4 o){vec4 cM=(c*m)+o;cM*=cM.a;return cM;}vec4 applyConvolutionMatrix(vec4 c,float k0,float k1,float k2,float k3,float k4,float k5,float k6,float k7,float k8,float w){vec2 pixel=vec2(1)/uTextureSize;vec4 colorSum=texture(uTexture,vTexCoord-pixel)*k0+texture(uTexture,vTexCoord+pixel*vec2(0.0,-1.0))*k1+texture(uTexture,vTexCoord+pixel*vec2(1.0,-1.0))*k2+texture(uTexture,vTexCoord+pixel*vec2(-1.0,0.0))*k3+texture(uTexture,vTexCoord)*k4+texture(uTexture,vTexCoord+pixel*vec2(1.0,0.0))*k5+texture(uTexture,vTexCoord+pixel*vec2(-1.0,1.0))*k6+texture(uTexture,vTexCoord+pixel*vec2(0.0,1.0))*k7+texture(uTexture,vTexCoord+pixel)*k8;vec4 color=vec4((colorSum/w).rgb,c.a);color.rgb=clamp(color.rgb,0.0,1.0);return color;}vec4 applyVignette(vec4 c,vec2 pos,vec2 center,float v){float d=distance(pos,center)/length(center);float f=1.0-(d*abs(v));if(v>0.0){c.rgb*=f;}else if(v<0.0){c.rgb+=(1.0-f)*(1.0-c.rgb);}return c;}vec4 blendPremultipliedAlpha(vec4 back,vec4 front){return front+(back*(1.0-front.a));}void main(){float x=gl_FragCoord.x;float y=gl_FragCoord.y;float a=1.0;float maskTop=uMaskBounds[0];float maskRight=uMaskBounds[1];float maskBottom=uMaskBounds[2];float maskLeft=uMaskBounds[3];float leftFeatherOpacity=step(uMaskFeather[1],x)*uMaskFeather[0]+((1.0-uMaskFeather[0])*smoothstep(uMaskFeather[1],uMaskFeather[3],x));float rightFeatherOpacity=(1.0-step(uMaskFeather[7],x))*uMaskFeather[4]+((1.0-uMaskFeather[4])*smoothstep(uMaskFeather[7],uMaskFeather[5],x));a*=leftFeatherOpacity*rightFeatherOpacity;float overlayColorAlpha=(smoothstep(maskLeft,maskLeft+1.0,x)*(1.0-smoothstep(maskRight-1.0,maskRight,x))*(1.0-step(maskTop,y))*step(maskBottom,y));if(uOverlayColor.a==0.0){a*=overlayColorAlpha;}vec2 offset=vec2(maskLeft,maskBottom);vec2 size=vec2(maskRight-maskLeft,maskTop-maskBottom)*.5;vec2 center=offset.xy+size.xy;int pixelX=int(step(center.x,x));int pixelY=int(step(y,center.y));float cornerRadius=0.0;if(pixelX==0&&pixelY==0)cornerRadius=uMaskCornerRadius[0];if(pixelX==1&&pixelY==0)cornerRadius=uMaskCornerRadius[1];if(pixelX==0&&pixelY==1)cornerRadius=uMaskCornerRadius[2];if(pixelX==1&&pixelY==1)cornerRadius=uMaskCornerRadius[3];float cornerOffset=sign(cornerRadius)*length(max(abs(gl_FragCoord.xy-size-offset)-size+cornerRadius,0.0))-cornerRadius;float cornerOpacity=1.0-smoothstep(0.0,1.0,cornerOffset);a*=cornerOpacity;vec2 scaledPoint=vec2(vTexCoord.x*uTextureSize.x,vTexCoord.y*uTextureSize.y);a*=smoothstep(0.0,1.0,uTextureSize.x-scaledPoint.x);a*=smoothstep(0.0,1.0,uTextureSize.y-scaledPoint.y);a*=smoothstep(0.0,1.0,scaledPoint.x);a*=smoothstep(0.0,1.0,scaledPoint.y);vec4 color=texture(uTexture,vTexCoord);color=blendPremultipliedAlpha(color,texture(uTextureBlend,vTexCoord));if(uClarityKernelWeight!=-1.0){color=applyConvolutionMatrix(color,uClarityKernel[0],uClarityKernel[1],uClarityKernel[2],uClarityKernel[3],uClarityKernel[4],uClarityKernel[5],uClarityKernel[6],uClarityKernel[7],uClarityKernel[8],uClarityKernelWeight);}color=applyGamma(color,uColorGamma);color=applyColorMatrix(color,uColorMatrix,uColorOffset);color=blendPremultipliedAlpha(uFillColor,color);color*=a;if(uColorVignette!=0.0){vec2 pos=gl_FragCoord.xy-offset;color=applyVignette(color,pos,center-offset,uColorVignette);}color=blendPremultipliedAlpha(color,texture(uTextureMarkup,vTexCoord));vec4 overlayColor=uOverlayColor*(1.0-overlayColorAlpha);overlayColor.rgb*=overlayColor.a;color=blendPremultipliedAlpha(color,overlayColor);if(uOverlayColor.a>0.0&&color.a<1.0&&uFillColor.a>0.0){color=blendPremultipliedAlpha(uFillColor,overlayColor);}color*=uOpacity;fragColor=color;}", ["aPosition", "aTexCoord"], ["uMatrix", "uTexture", "uTextureBlend", "uTextureMarkup", "uTextureSize", "uColorGamma", "uColorVignette", "uColorOffset", "uColorMatrix", "uClarityKernel", "uClarityKernelWeight", "uOpacity", "uMaskOpacity", "uMaskBounds", "uMaskCornerRadius", "uMaskFeather", "uFillColor", "uOverlayColor"])
      , A = C.createBuffer()
      , E = C.createBuffer();
    C.bindBuffer(C.ARRAY_BUFFER, E),
    C.bufferData(C.ARRAY_BUFFER, qs, C.STATIC_DRAW);
    const L = Fs(C, "#version 300 es\n\nin vec4 aPosition;in vec2 aNormal;in float aMiter;out vec2 vNormal;out float vMiter;out float vWidth;uniform float uWidth;uniform mat4 uMatrix;void main(){vMiter=aMiter;vNormal=aNormal;vWidth=(uWidth*.5)+1.0;gl_Position=uMatrix*vec4(aPosition.x+(aNormal.x*vWidth*aMiter),aPosition.y+(aNormal.y*vWidth*aMiter),0,1);}", "\n##head\n##mask\nin vec2 vNormal;in float vMiter;in float vWidth;uniform float uWidth;uniform vec4 uColor;uniform vec4 uCanvasColor;void main(){vec4 fillColor=uColor;float m=mask(gl_FragCoord.x,gl_FragCoord.y,uMaskBounds,uMaskOpacity);if(m<=0.0)discard;fillColor.a*=clamp(smoothstep(vWidth-.5,vWidth-1.0,abs(vMiter)*vWidth),0.0,1.0);fillColor.rgb*=fillColor.a;fillColor.rgb*=m;fillColor.rgb+=(1.0-m)*(uCanvasColor.rgb*fillColor.a);fragColor=fillColor;}", ["aPosition", "aNormal", "aMiter"], ["uColor", "uCanvasColor", "uMatrix", "uWidth", "uMaskBounds", "uMaskOpacity"])
      , F = C.createBuffer()
      , z = (t,e,o,i=!1)=>{
        const {program: n, locations: r} = L;
        C.useProgram(n),
        C.enableVertexAttribArray(r.aPosition),
        C.enableVertexAttribArray(r.aNormal),
        C.enableVertexAttribArray(r.aMiter);
        const a = ((t,e)=>{
            let o, i, n, r = 0;
            const a = t.length
              , s = new Float32Array(10 * (e ? a + 1 : a))
              , l = t[0]
              , c = t[a - 1];
            for (r = 0; r < a; r++)
                o = t[r - 1],
                i = t[r],
                n = t[r + 1],
                o || (o = e ? c : Y(i.x + (i.x - n.x), i.y + (i.y - n.y))),
                n || (n = e ? l : Y(i.x + (i.x - o.x), i.y + (i.y - o.y))),
                Hs(s, 10 * r, o, i, n);
            return e && Hs(s, 10 * a, c, l, t[1]),
            s
        }
        )(t, i)
          , s = 5 * Float32Array.BYTES_PER_ELEMENT
          , c = 2 * Float32Array.BYTES_PER_ELEMENT
          , d = 4 * Float32Array.BYTES_PER_ELEMENT;
        C.uniform1f(r.uWidth, e),
        C.uniform4fv(r.uColor, o),
        C.uniformMatrix4fv(r.uMatrix, !1, l),
        C.uniform4f(r.uCanvasColor, y, x, b, 1),
        C.uniform1fv(r.uMaskBounds, m),
        C.uniform1f(r.uMaskOpacity, p),
        C.bindBuffer(C.ARRAY_BUFFER, F),
        C.bufferData(C.ARRAY_BUFFER, a, C.STATIC_DRAW),
        C.vertexAttribPointer(r.aPosition, 2, C.FLOAT, !1, s, 0),
        C.vertexAttribPointer(r.aNormal, 2, C.FLOAT, !1, s, c),
        C.vertexAttribPointer(r.aMiter, 1, C.FLOAT, !1, s, d),
        C.drawArrays(C.TRIANGLE_STRIP, 0, a.length / 5),
        C.disableVertexAttribArray(r.aPosition),
        C.disableVertexAttribArray(r.aNormal),
        C.disableVertexAttribArray(r.aMiter)
    }
      , B = Fs(C, "\n##head\nvoid main(){\n##matrix\n}", "\n##head\n##mask\nuniform vec4 uColor;uniform vec4 uCanvasColor;void main(){vec4 fillColor=uColor;\n##maskapply\nfillColor.rgb*=fillColor.a;fillColor.rgb*=m;fillColor.rgb+=(1.0-m)*(uCanvasColor.rgb*fillColor.a);fragColor=fillColor;}", ["aPosition"], ["uColor", "uCanvasColor", "uMatrix", "uMaskBounds", "uMaskOpacity"])
      , O = C.createBuffer()
      , _ = Fs(C, "\n##head\n##text\nin vec2 aRectCoord;out vec2 vRectCoord;void main(){vTexCoord=aTexCoord;vRectCoord=aRectCoord;\n##matrix\n}", "\n##head\n##mask\nin vec2 vTexCoord;in vec2 vRectCoord;uniform sampler2D uTexture;uniform vec4 uTextureColor;uniform float uTextureOpacity;uniform vec4 uColor;uniform float uCornerRadius[4];uniform vec2 uSize;uniform vec2 uPosition;uniform vec4 uCanvasColor;uniform int uInverted;void main(){\n##init\n##colorize\n##edgeaa\n##cornerradius\n##maskfeatherapply\nif(uInverted==1)a=1.0-a;\n##maskapply\n##fragcolor\n}", ["aPosition", "aTexCoord", "aRectCoord"], ["uTexture", "uColor", "uMatrix", "uCanvasColor", "uTextureColor", "uTextureOpacity", "uPosition", "uSize", "uMaskBounds", "uMaskOpacity", "uMaskFeather", "uCornerRadius", "uInverted"])
      , W = C.createBuffer()
      , V = C.createBuffer()
      , H = C.createBuffer()
      , N = Fs(C, "\n##head\n##text\nout vec2 vTexCoordDouble;void main(){vTexCoordDouble=vec2(aTexCoord.x*2.0-1.0,aTexCoord.y*2.0-1.0);vTexCoord=aTexCoord;\n##matrix\n}", "\n##head\n##mask\nin vec2 vTexCoord;in vec2 vTexCoordDouble;uniform sampler2D uTexture;uniform float uTextureOpacity;uniform vec2 uRadius;uniform vec4 uColor;uniform int uInverted;uniform vec4 uCanvasColor;void main(){\n##init\nfloat ar=uRadius.x/uRadius.y;vec2 rAA=vec2(uRadius.x-1.0,uRadius.y-(1.0/ar));vec2 scaledPointSq=vec2((vTexCoordDouble.x*uRadius.x)*(vTexCoordDouble.x*uRadius.x),(vTexCoordDouble.y*uRadius.y)*(vTexCoordDouble.y*uRadius.y));float p=(scaledPointSq.x/(uRadius.x*uRadius.x))+(scaledPointSq.y/(uRadius.y*uRadius.y));float pAA=(scaledPointSq.x/(rAA.x*rAA.x))+(scaledPointSq.y/(rAA.y*rAA.y));a=smoothstep(1.0,p/pAA,p);if(uInverted==1)a=1.0-a;\n##maskapply\n##fragcolor\n}", ["aPosition", "aTexCoord"], ["uTexture", "uTextureOpacity", "uColor", "uCanvasColor", "uMatrix", "uRadius", "uInverted", "uMaskBounds", "uMaskOpacity"])
      , U = C.createBuffer()
      , j = C.createBuffer()
      , X = new Map
      , G = {
        2: {
            width: 0,
            height: 0
        },
        1: {
            width: 0,
            height: 0
        }
    }
      , q = (t,o,n)=>{
        const a = Math.min(i / n.width, i / n.height, 1)
          , c = Math.floor(a * n.width)
          , d = Math.floor(a * n.height);
        bt(n, G[t]) ? C.bindFramebuffer(C.FRAMEBUFFER, o) : (C.bindTexture(C.TEXTURE_2D, v.get(t)),
        C.texImage2D(C.TEXTURE_2D, 0, C.RGBA, c, d, 0, C.RGBA, C.UNSIGNED_BYTE, null),
        C.texParameteri(C.TEXTURE_2D, C.TEXTURE_MIN_FILTER, C.LINEAR),
        C.texParameteri(C.TEXTURE_2D, C.TEXTURE_WRAP_S, C.CLAMP_TO_EDGE),
        C.texParameteri(C.TEXTURE_2D, C.TEXTURE_WRAP_T, C.CLAMP_TO_EDGE),
        C.bindFramebuffer(C.FRAMEBUFFER, o),
        C.framebufferTexture2D(C.FRAMEBUFFER, C.COLOR_ATTACHMENT0, C.TEXTURE_2D, v.get(t), 0),
        G[t] = n);
        const u = n.width * r
          , h = n.height * r;
        var p, m;
        _s(s, 0, u, h, 0, -1, 1),
        Ws(s, 0, h, 0),
        m = 1,
        (p = s)[0] = p[0] * m,
        p[1] = p[1] * m,
        p[2] = p[2] * m,
        p[3] = p[3] * m,
        ((t,e)=>{
            t[4] = t[4] * e,
            t[5] = t[5] * e,
            t[6] = t[6] * e,
            t[7] = t[7] * e
        }
        )(s, -1),
        l = s,
        C.viewport(0, 0, c, d),
        C.colorMask(!0, !0, !0, !0),
        C.clearColor(0, 0, 0, 0),
        C.clear(C.COLOR_BUFFER_BIT),
        $ = [1, 0, 1, 0, 1, Math.max(e.width, n.width), 1, Math.max(e.width, n.width)]
    }
      , Z = (t,e=!1)=>{
        const o = X.get(t);
        o instanceof HTMLCanvasElement && (e || o.dataset.retain || g(o)),
        X.delete(t),
        C.deleteTexture(t)
    }
    ;
    return {
        drawPath: (t,e,o,i,n)=>{
            t.length < 2 || z(t.map((t=>({
                x: t.x * r,
                y: t.y * r
            }))), e * r, Os(o, n), i)
        }
        ,
        drawTriangle: (t,e=0,o=!1,i=!1,n,a)=>{
            if (!n)
                return;
            const s = t.map((t=>({
                x: t.x * r,
                y: t.y * r
            })))
              , c = ne(s);
            (o || i) && ut(s, o, i, c.x, c.y),
            ht(s, e, c.x, c.y);
            ((t,e)=>{
                const {program: o, locations: i} = B;
                C.useProgram(o),
                C.enableVertexAttribArray(i.aPosition),
                C.uniform4fv(i.uColor, e),
                C.uniformMatrix4fv(i.uMatrix, !1, l),
                C.uniform1fv(i.uMaskBounds, m),
                C.uniform1f(i.uMaskOpacity, p),
                C.uniform4f(i.uCanvasColor, y, x, b, 1),
                C.bindBuffer(C.ARRAY_BUFFER, O),
                C.bufferData(C.ARRAY_BUFFER, t, C.STATIC_DRAW),
                C.vertexAttribPointer(i.aPosition, 2, C.FLOAT, !1, 0, 0),
                C.drawArrays(C.TRIANGLE_STRIP, 0, t.length / 2),
                C.disableVertexAttribArray(i.aPosition)
            }
            )((t=>{
                const e = new Float32Array(6);
                return e[0] = t[0].x,
                e[1] = t[0].y,
                e[2] = t[1].x,
                e[3] = t[1].y,
                e[4] = t[2].x,
                e[5] = t[2].y,
                e
            }
            )(s), Os(n, a))
        }
        ,
        drawRect: (t,e=0,o=!1,i=!1,n,a,s,c,d,u,h,g,f,v,w,S)=>{
            const M = Ht(At(t), r)
              , T = n.map((e=>((t,e)=>Math.floor(na(t, 0, Math.min(.5 * (e.width - 1), .5 * (e.height - 1)))))(e || 0, t))).map((t=>t * r));
            if (a || s) {
                const t = At(M);
                t.x -= .5,
                t.y -= .5,
                t.width += 1,
                t.height += 1;
                const n = Us(t, e, o, i)
                  , h = Ns(n);
                let g;
                w && (g = Os(w),
                0 === g[3] && (g[3] = .001)),
                ((t,e,o,i,n,a=k,s=1,c=Js,d=qs,u=$,h)=>{
                    const {program: g, locations: f} = _;
                    C.useProgram(g),
                    C.enableVertexAttribArray(f.aPosition),
                    C.enableVertexAttribArray(f.aTexCoord),
                    C.enableVertexAttribArray(f.aRectCoord),
                    C.uniform4fv(f.uColor, n),
                    C.uniform2fv(f.uSize, [e, o]),
                    C.uniform2fv(f.uPosition, [t[2], t[3]]),
                    C.uniform1i(f.uInverted, h ? 1 : 0),
                    C.uniform1fv(f.uCornerRadius, i),
                    C.uniform4f(f.uCanvasColor, y, x, b, 1),
                    C.uniform1fv(f.uMaskFeather, u.map(((t,e)=>e % 2 == 0 ? t : t * r))),
                    C.uniform1fv(f.uMaskBounds, m),
                    C.uniform1f(f.uMaskOpacity, p),
                    C.uniformMatrix4fv(f.uMatrix, !1, l),
                    C.uniform1i(f.uTexture, 4),
                    C.uniform4fv(f.uTextureColor, c),
                    C.uniform1f(f.uTextureOpacity, s),
                    C.activeTexture(C.TEXTURE0 + 4),
                    C.bindTexture(C.TEXTURE_2D, a),
                    C.bindBuffer(C.ARRAY_BUFFER, V),
                    C.bufferData(C.ARRAY_BUFFER, d, C.STATIC_DRAW),
                    C.vertexAttribPointer(f.aTexCoord, 2, C.FLOAT, !1, 0, 0),
                    C.bindBuffer(C.ARRAY_BUFFER, H),
                    C.bufferData(C.ARRAY_BUFFER, qs, C.STATIC_DRAW),
                    C.vertexAttribPointer(f.aRectCoord, 2, C.FLOAT, !1, 0, 0),
                    C.bindBuffer(C.ARRAY_BUFFER, W),
                    C.bufferData(C.ARRAY_BUFFER, t, C.STATIC_DRAW),
                    C.vertexAttribPointer(f.aPosition, 2, C.FLOAT, !1, 0, 0),
                    C.drawArrays(C.TRIANGLE_STRIP, 0, t.length / 2),
                    C.disableVertexAttribArray(f.aPosition),
                    C.disableVertexAttribArray(f.aTexCoord),
                    C.disableVertexAttribArray(f.aRectCoord)
                }
                )(h, t.width, t.height, T, Os(a, f), s, f, g, u ? new Float32Array(u) : tl(t.width, t.height, c, d, r), v, S)
            }
            h && (h = Math.min(h, M.width, M.height),
            z(((t,e,o,i,n,r,a,s)=>{
                const l = [];
                if (r.every((t=>0 === t)))
                    l.push(Y(t, e), Y(t + o, e), Y(t + o, e + i), Y(t, e + i));
                else {
                    const [n,a,s,c] = r
                      , d = t
                      , u = t + o
                      , h = e
                      , p = e + i;
                    l.push(Y(d + n, h)),
                    js(l, u - a, h + a, a, -1),
                    l.push(Y(u, h + a)),
                    js(l, u - c, p - c, c, 0),
                    l.push(Y(u - c, p)),
                    js(l, d + s, p - s, s, 1),
                    l.push(Y(d, p - s)),
                    js(l, d + n, h + n, n, 2)
                }
                return (a || s) && ut(l, a, s, t + .5 * o, e + .5 * i),
                n && ht(l, n, t + .5 * o, e + .5 * i),
                l
            }
            )(M.x, M.y, M.width, M.height, e, T, o, i), h * r, Os(g, f), !0))
        }
        ,
        drawEllipse: (t,e,o,i,n,a,s,c,d,u,h,g,f,$,v)=>{
            const w = Ht(Dt(t.x - e, t.y - o, 2 * e, 2 * o), r);
            if (s || c) {
                const t = At(w);
                t.x -= .5,
                t.y -= .5,
                t.width += 1,
                t.height += 1;
                const e = Us(t, i, n, a);
                ((t,e,o,i,n=k,r=qs,a=1,s=!1)=>{
                    const {program: c, locations: d} = N;
                    C.useProgram(c),
                    C.enableVertexAttribArray(d.aPosition),
                    C.enableVertexAttribArray(d.aTexCoord),
                    C.uniformMatrix4fv(d.uMatrix, !1, l),
                    C.uniform2fv(d.uRadius, [.5 * e, .5 * o]),
                    C.uniform1i(d.uInverted, s ? 1 : 0),
                    C.uniform4fv(d.uColor, i),
                    C.uniform4f(d.uCanvasColor, y, x, b, 1),
                    C.uniform1fv(d.uMaskBounds, m),
                    C.uniform1f(d.uMaskOpacity, p),
                    C.uniform1i(d.uTexture, 4),
                    C.uniform1f(d.uTextureOpacity, a),
                    C.activeTexture(C.TEXTURE0 + 4),
                    C.bindTexture(C.TEXTURE_2D, n),
                    C.bindBuffer(C.ARRAY_BUFFER, j),
                    C.bufferData(C.ARRAY_BUFFER, r, C.STATIC_DRAW),
                    C.vertexAttribPointer(d.aTexCoord, 2, C.FLOAT, !1, 0, 0),
                    C.bindBuffer(C.ARRAY_BUFFER, U),
                    C.bufferData(C.ARRAY_BUFFER, t, C.STATIC_DRAW),
                    C.vertexAttribPointer(d.aPosition, 2, C.FLOAT, !1, 0, 0),
                    C.drawArrays(C.TRIANGLE_STRIP, 0, t.length / 2),
                    C.disableVertexAttribArray(d.aPosition),
                    C.disableVertexAttribArray(d.aTexCoord)
                }
                )(Ns(e), t.width, t.height, Os(s, $), c, h ? new Float32Array(h) : tl(t.width, t.height, d, u, r), $, v)
            }
            g && z(((t,e,o,i,n,r,a)=>{
                const s = .5 * Math.abs(o)
                  , l = .5 * Math.abs(i)
                  , c = Math.abs(o) + Math.abs(i)
                  , d = Math.max(20, Math.round(c / 6));
                return ce(Y(t + s, e + l), s, l, n, r, a, d)
            }
            )(w.x, w.y, w.width, w.height, i, n, a), g * r, Os(f, $), !0)
        }
        ,
        drawImage: (t,o,i,a,s,l,c,d,u,h,g=Ks,$=1,y,x=1,b=0,k=f,M=Qs,T=Js,R=Js,P=!1,L=!1)=>{
            const F = o.width * r
              , z = o.height * r
              , B = -.5 * F
              , O = .5 * z
              , D = .5 * F
              , _ = -.5 * z
              , W = new Float32Array([B, _, 0, B, O, 0, D, _, 0, D, O, 0]);
            C.bindBuffer(C.ARRAY_BUFFER, A),
            C.bufferData(C.ARRAY_BUFFER, W, C.STATIC_DRAW);
            const V = o.height / 2 / S * (e.height / o.height) * -1;
            s *= r,
            l *= r,
            i *= r,
            a *= r;
            const {program: H, locations: N} = I
              , U = Ds();
            var j, X;
            ((t,e,o,i,n)=>{
                const r = 1 / Math.tan(e / 2)
                  , a = 1 / (i - n);
                t[0] = r / o,
                t[1] = 0,
                t[2] = 0,
                t[3] = 0,
                t[4] = 0,
                t[5] = r,
                t[6] = 0,
                t[7] = 0,
                t[8] = 0,
                t[9] = 0,
                t[10] = (n + i) * a,
                t[11] = -1,
                t[12] = 0,
                t[13] = 0,
                t[14] = 2 * n * i * a,
                t[15] = 0
            }
            )(U, w, n, 1, 2 * -V),
            Ws(U, s, -l, V),
            Ws(U, i, -a, 0),
            ((t,e)=>{
                const o = Math.sin(e)
                  , i = Math.cos(e)
                  , n = t[0]
                  , r = t[1]
                  , a = t[2]
                  , s = t[3]
                  , l = t[4]
                  , c = t[5]
                  , d = t[6]
                  , u = t[7];
                t[0] = n * i + l * o,
                t[1] = r * i + c * o,
                t[2] = a * i + d * o,
                t[3] = s * i + u * o,
                t[4] = l * i - n * o,
                t[5] = c * i - r * o,
                t[6] = d * i - a * o,
                t[7] = u * i - s * o
            }
            )(U, -u),
            X = h,
            (j = U)[0] = j[0] * X,
            j[1] = j[1] * X,
            j[2] = j[2] * X,
            j[3] = j[3] * X,
            j[4] = j[4] * X,
            j[5] = j[5] * X,
            j[6] = j[6] * X,
            j[7] = j[7] * X,
            j[8] = j[8] * X,
            j[9] = j[9] * X,
            j[10] = j[10] * X,
            j[11] = j[11] * X,
            Ws(U, -i, a, 0),
            ((t,e)=>{
                const o = Math.sin(e)
                  , i = Math.cos(e)
                  , n = t[0]
                  , r = t[1]
                  , a = t[2]
                  , s = t[3]
                  , l = t[8]
                  , c = t[9]
                  , d = t[10]
                  , u = t[11];
                t[0] = n * i - l * o,
                t[1] = r * i - c * o,
                t[2] = a * i - d * o,
                t[3] = s * i - u * o,
                t[8] = n * o + l * i,
                t[9] = r * o + c * i,
                t[10] = a * o + d * i,
                t[11] = s * o + u * i
            }
            )(U, d),
            ((t,e)=>{
                const o = Math.sin(e)
                  , i = Math.cos(e)
                  , n = t[4]
                  , r = t[5]
                  , a = t[6]
                  , s = t[7]
                  , l = t[8]
                  , c = t[9]
                  , d = t[10]
                  , u = t[11];
                t[4] = n * i + l * o,
                t[5] = r * i + c * o,
                t[6] = a * i + d * o,
                t[7] = s * i + u * o,
                t[8] = l * i - n * o,
                t[9] = c * i - r * o,
                t[10] = d * i - a * o,
                t[11] = u * i - s * o
            }
            )(U, c),
            C.useProgram(H),
            C.enableVertexAttribArray(N.aPosition),
            C.enableVertexAttribArray(N.aTexCoord),
            C.uniform1i(N.uTexture, 3),
            C.uniform2f(N.uTextureSize, o.width, o.height),
            C.activeTexture(C.TEXTURE0 + 3),
            C.bindTexture(C.TEXTURE_2D, t);
            const Y = L ? 1 : 0
              , G = v.get(Y);
            C.uniform1i(N.uTextureBlend, Y),
            C.activeTexture(C.TEXTURE0 + Y),
            C.bindTexture(C.TEXTURE_2D, G);
            const q = P ? 2 : 0
              , Z = v.get(q);
            let K;
            C.uniform1i(N.uTextureMarkup, q),
            C.activeTexture(C.TEXTURE0 + q),
            C.bindTexture(C.TEXTURE_2D, Z),
            C.bindBuffer(C.ARRAY_BUFFER, A),
            C.vertexAttribPointer(N.aPosition, 3, C.FLOAT, !1, 0, 0),
            C.bindBuffer(C.ARRAY_BUFFER, E),
            C.vertexAttribPointer(N.aTexCoord, 2, C.FLOAT, !1, 0, 0),
            C.uniformMatrix4fv(N.uMatrix, !1, U),
            C.uniform4fv(N.uOverlayColor, R),
            C.uniform4fv(N.uFillColor, T),
            !y || la(y, Zs) ? (y = Zs,
            K = -1) : (K = y.reduce(((t,e)=>t + e), 0),
            K = K <= 0 ? 1 : K),
            C.uniform1fv(N.uClarityKernel, y),
            C.uniform1f(N.uClarityKernelWeight, K),
            C.uniform1f(N.uColorGamma, 1 / x),
            C.uniform1f(N.uColorVignette, b),
            C.uniform4f(N.uColorOffset, g[4], g[9], g[14], g[19]),
            C.uniformMatrix4fv(N.uColorMatrix, !1, [g[0], g[1], g[2], g[3], g[5], g[6], g[7], g[8], g[10], g[11], g[12], g[13], g[15], g[16], g[17], g[18]]),
            C.uniform1f(N.uOpacity, $),
            C.uniform1f(N.uMaskOpacity, p),
            C.uniform1fv(N.uMaskBounds, m),
            C.uniform1fv(N.uMaskCornerRadius, M.map((t=>t * r))),
            C.uniform1fv(N.uMaskFeather, k.map(((t,e)=>e % 2 == 0 ? t : t * r))),
            C.drawArrays(C.TRIANGLE_STRIP, 0, 4),
            C.disableVertexAttribArray(N.aPosition),
            C.disableVertexAttribArray(N.aTexCoord)
        }
        ,
        textureFilterNearest: C.NEAREST,
        textureFilterLinear: C.LINEAR,
        textureCreate: ()=>C.createTexture(),
        textureUpdate: (t,e,o)=>(X.set(t, e),
        Bs(C, t, e, o)),
        textureSize: t=>{
            const e = X.get(t);
            return gt(e)
        }
        ,
        textureDelete: Z,
        setCanvasColor(t) {
            y = t[0],
            x = t[1],
            b = t[2],
            C.clear(C.COLOR_BUFFER_BIT)
        },
        drawToCanvas() {
            C.bindFramebuffer(C.FRAMEBUFFER, null),
            l = a,
            C.viewport(0, 0, C.drawingBufferWidth, C.drawingBufferHeight),
            C.colorMask(!0, !0, !0, !1),
            C.clearColor(y, x, b, 1),
            C.clear(C.COLOR_BUFFER_BIT),
            $ = [1, 0, 1, 0, 1, e.width, 1, e.width]
        },
        drawToImageBlendBuffer(t) {
            q(1, P, t)
        },
        drawToImageOverlayBuffer(t) {
            q(2, T, t)
        },
        enableMask(t, o) {
            const i = t.x * r
              , n = t.y * r
              , a = t.width * r
              , s = t.height * r;
            h = i,
            d = h + a,
            c = e.height - n,
            u = e.height - (n + s),
            p = 1 - o,
            m = [c, d, u, h]
        },
        disableMask() {
            h = 0,
            d = e.width,
            c = e.height,
            u = 0,
            p = 1,
            m = [c, d, u, h]
        },
        resize: (i,s,l)=>{
            r = l,
            o.width = i,
            o.height = s,
            e.width = i * r,
            e.height = s * r,
            n = D(e.width, e.height),
            t.width = e.width,
            t.height = e.height,
            _s(a, 0, e.width, e.height, 0, -1, 1),
            f = [1, 0, 1, 0, 1, o.width, 1, o.width]
        }
        ,
        release() {
            Array.from(X.keys()).forEach((t=>Z(t, !0))),
            X.clear(),
            v.forEach((t=>{
                C.deleteTexture(t)
            }
            )),
            v.clear(),
            I.destroy(),
            L.destroy(),
            B.destroy(),
            _.destroy(),
            N.destroy(),
            t.width = 1,
            t.height = 1,
            t = void 0
        }
    }
}
;
function ol(t) {
    let e, o, i, n;
    return {
        c() {
            e = Rn("div"),
            o = Rn("canvas"),
            Bn(e, "class", "PinturaCanvas")
        },
        m(r, a) {
            Mn(r, e, a),
            kn(e, o),
            t[25](o),
            i || (n = [Ln(o, "measure", t[26]), yn(bs.call(null, o))],
            i = !0)
        },
        p: tn,
        i: tn,
        o: tn,
        d(o) {
            o && Tn(e),
            t[25](null),
            i = !1,
            an(n)
        }
    }
}
function il(t, e, o) {
    let i, r, a, s, l, c, d;
    const u = (t,e)=>{
        const [o,i,n] = t
          , [r,a,s,l] = e;
        return [r * l + o * (1 - l), a * l + i * (1 - l), s * l + n * (1 - l), 1]
    }
      , h = Jn();
    let m, {animate: g} = e, {maskRect: $} = e, {maskOpacity: y=1} = e, {maskFrameOpacity: x=.95} = e, {pixelRatio: b=1} = e, {backgroundColor: v} = e, {willRender: S=_} = e, {willRequestResource: C=(()=>!0)} = e, {loadImageData: k=_} = e, {images: M=[]} = e, {interfaceImages: T=[]} = e, R = null, P = null, I = null;
    const A = (t,e)=>t.set(e, {
        hard: !g
    })
      , E = {
        precision: 1e-4 * .01
    }
      , L = as(void 0, {
        duration: 250
    });
    un(t, L, (t=>o(21, a = t)));
    const F = ls(1, E);
    un(t, F, (t=>o(22, s = t)));
    const z = ls(1, E);
    un(t, z, (t=>o(31, d = t)));
    const B = _r();
    un(t, B, (t=>o(29, l = t)));
    const O = _r();
    un(t, O, (t=>o(30, c = t)));
    const W = new Map([])
      , V = new Map([])
      , H = (t,e)=>{
        if (!W.has(t)) {
            W.set(t, t);
            const o = "pixelated" === e ? R.textureFilterNearest : R.textureFilterLinear;
            if (!w(t) && ("close"in t || f(t) || Li(t))) {
                const e = R.textureCreate();
                R.textureUpdate(e, t, {
                    filter: o
                }),
                W.set(t, e)
            } else
                k(t).then((e=>{
                    const i = R.textureCreate();
                    R.textureUpdate(i, e, {
                        filter: o
                    }),
                    W.set(t, i),
                    requestAnimationFrame(r)
                }
                )).catch((t=>{
                    console.error(t)
                }
                ))
        }
        return W.get(t)
    }
      , N = t=>{
        if (!t.text.length)
            return void V.delete(t.id);
        let {text: e, textAlign: o, fontFamily: i, fontSize: n, fontWeight: a, fontVariant: s, fontStyle: l, lineHeight: c, width: d, height: u} = t;
        const {lastCharPosition: h, size: m} = ((t="",e)=>{
            const {width: o=0, fontSize: i, fontFamily: n, lineHeight: r, fontWeight: a, fontStyle: s, fontVariant: l} = e
              , c = so({
                text: t,
                fontFamily: n,
                fontWeight: a,
                fontStyle: s,
                fontVariant: l,
                fontSize: i,
                lineHeight: r,
                width: o
            });
            let d = fo.get(c);
            if (d)
                return d;
            const u = p("span")
              , h = fe(p("pre", {
                contenteditable: "true",
                spellcheck: "false",
                style: `pointer-events:none;visibility:hidden;position:absolute;left:0;top:0;${mo({
                    fontFamily: n,
                    fontWeight: a,
                    fontStyle: s,
                    fontVariant: l,
                    fontSize: i,
                    lineHeight: r
                })};${go(e)}"`,
                innerHTML: t
            }, [u]))
              , m = h.getBoundingClientRect()
              , g = u.getBoundingClientRect();
            return d = {
                size: gt(m),
                lastCharPosition: it(G(g), Math.round)
            },
            fo.set(c, d),
            h.remove(),
            d
        }
        )(e, t)
          , g = so({
            text: e,
            textAlign: o,
            fontFamily: i,
            fontSize: n,
            fontWeight: a,
            fontVariant: s,
            fontStyle: l,
            lineHeight: c,
            height: u ? Math.min(h.y, Math.ceil(u / c) * c) : "auto",
            xOffset: h.x,
            yOffset: h.y
        });
        if (!W.has(g)) {
            W.set(g, e);
            const h = d && Math.round(d)
              , p = u && Math.round(u)
              , f = kt(mt(m), (t=>Math.min(Math.round(t), Ys())));
            Po(e, {
                fontSize: n,
                fontFamily: i,
                fontWeight: a,
                fontVariant: s,
                fontStyle: l,
                textAlign: o,
                lineHeight: c,
                color: [1, 0, 1],
                width: h,
                height: p,
                imageWidth: f.width,
                imageHeight: p ? Math.ceil(p / c) * c : f.height,
                willRequestResource: C
            }).then((e=>{
                const o = R.textureCreate();
                R.textureUpdate(o, e, {
                    filter: R.textureFilterLinear
                }),
                W.set(g, o),
                V.set(t.id, o),
                requestAnimationFrame(r)
            }
            )).catch(console.error)
        }
        const f = W.get(g);
        return U(f) ? f : V.get(t.id)
    }
      , U = t=>t instanceof WebGLTexture
      , j = ({data: t, size: e, origin: o, translation: i, rotation: n, scale: r, colorMatrix: s, opacity: l, convolutionMatrix: c, gamma: d, vignette: h, maskFeather: p, maskCornerRadius: m, backgroundColor: g, overlayColor: f, enableShapes: $, enableBlend: y})=>{
        g && g[3] < 1 && g[3] > 0 && (g = u(a, g));
        const x = H(t);
        return R.drawImage(x, e, o.x, o.y, i.x, i.y, n.x, n.y, n.z, r, s, na(l, 0, 1), c, d, h, p, m, g, f, $, y),
        x
    }
      , q = ([t,e,o,i])=>[i.x, i.y, t.x, t.y, o.x, o.y, e.x, e.y]
      , Z = X()
      , K = (t=[],e)=>t.map((t=>{
        let e = !t._isLoading && (t=>{
            let e;
            if (t.backgroundImage)
                e = H(t.backgroundImage, t.backgroundImageRendering);
            else if (w(t.text)) {
                if (t.width && t.width < 1 || t.height && t.height < 1)
                    return;
                e = N(t)
            }
            return e
        }
        )(t)
          , o = U(e) ? e : void 0;
        const i = t._scale || 1
          , n = t._translate || Z
          , r = t.strokeWidth && t.strokeWidth * i;
        if (Je(t.points))
            t.points.forEach((t=>{
                t.x *= i,
                t.y *= i,
                t.x += n.x,
                t.y += n.y
            }
            )),
            3 === t.points.length && t.backgroundColor ? R.drawTriangle(t.points, t.rotation, t.flipX, t.flipY, t.backgroundColor, r, t.strokeColor, t.opacity) : R.drawPath(t.points, r, t.strokeColor, t.pathClose, t.opacity);
        else if (qe(t.rx) && qe(t.ry)) {
            let e = t.x
              , a = t.y;
            e *= i,
            a *= i,
            e += n.x,
            a += n.y,
            R.drawEllipse(Y(e, a), t.rx * i, t.ry * i, t.rotation, t.flipX, t.flipY, t.backgroundColor, o, void 0, void 0, t.backgroundCorners && q(t.backgroundCorners), r, t.strokeColor, t.opacity, t.inverted)
        } else if (w(t.text) && o || t.width) {
            const e = o && R.textureSize(o);
            let a, s, l, c = void 0, d = [t.cornerRadius, t.cornerRadius, t.cornerRadius, t.cornerRadius].map((t=>t * i));
            if (a = t.width ? Ft(t) : {
                x: t.x,
                y: t.y,
                ...e
            },
            i && n && (a.x *= i,
            a.y *= i,
            a.x += n.x,
            a.y += n.y,
            a.width *= i,
            a.height *= i),
            e)
                if (t.backgroundImage && t.backgroundSize) {
                    const o = D(e.width, e.height);
                    if ("contain" === t.backgroundSize) {
                        const t = Jt(a, o, a);
                        s = ft(t),
                        l = Y(.5 * (a.width - s.width), .5 * (a.height - s.height))
                    } else if ("cover" === t.backgroundSize) {
                        const t = Kt(a, o, a);
                        s = ft(t),
                        l = Y(t.x, t.y),
                        l = Y(.5 * (a.width - s.width), .5 * (a.height - s.height))
                    } else
                        s = t.backgroundSize,
                        l = t.backgroundPosition
                } else if (t.text) {
                    const o = {
                        width: e.width / 1,
                        height: e.height / 1
                    };
                    l = Y(0, 0),
                    s = {
                        width: o.width * i,
                        height: o.height * i
                    },
                    t.backgroundColor && R.drawRect({
                        ...a,
                        height: a.height || e.height * i
                    }, t.rotation, t.flipX, t.flipY, d, t.backgroundColor, void 0, void 0, void 0, void 0, r, t.strokeColor, t.opacity, void 0, void 0, t.inverted),
                    t.backgroundColor = void 0,
                    a.x -= po * i,
                    c = t.color,
                    t.width && (a.height = a.height || o.height * i,
                    a.width += 2 * po * i,
                    "center" === t.textAlign ? l.x = .5 * (a.width - s.width) : "right" === t.textAlign && (l.x = a.width - s.width)),
                    t._prerender && (c[3] = 0)
                }
            R.drawRect(a, t.rotation, t.flipX, t.flipY, d, t.backgroundColor, o, s, l, t.backgroundCorners && q(t.backgroundCorners), r, t.strokeColor, t.opacity, void 0, c, t.inverted)
        }
        return e
    }
    )).filter(Boolean)
      , J = []
      , Q = ()=>{
        J.length = 0;
        const t = M[0]
          , {blendShapes: e, annotationShapes: o, interfaceShapes: i, decorationShapes: n, frameShapes: r} = S({
            opacity: t.opacity,
            rotation: t.rotation,
            scale: t.scale,
            images: M,
            size: xt(P, I),
            backgroundColor: [...a]
        })
          , h = [...a]
          , p = l
          , m = na(s, 0, 1)
          , g = c
          , f = t.size
          , $ = t.backgroundColor
          , y = e.length > 0
          , x = o.length > 0
          , b = $[3] > 0;
        if (m < 1 && b) {
            const t = h[0]
              , e = h[1]
              , o = h[2]
              , i = 1 - m
              , n = $[0] * i
              , r = $[1] * i
              , a = $[2] * i
              , s = 1 - i;
            h[0] = n + t * s,
            h[1] = r + e * s,
            h[2] = a + o * s,
            h[3] = 1
        }
        if (R.setCanvasColor(h),
        y && (R.disableMask(),
        R.drawToImageBlendBuffer(f),
        J.push(...K(e))),
        x && (R.disableMask(),
        R.drawToImageOverlayBuffer(f),
        J.push(...K(o))),
        R.drawToCanvas(),
        R.enableMask(p, m),
        b && R.drawRect(p, 0, !1, !1, [0, 0, 0, 0], u(a, $)),
        J.push(...[...M].reverse().map((t=>j({
            ...t,
            enableShapes: x,
            enableBlend: y,
            mask: p,
            maskOpacity: m,
            overlayColor: g
        })))),
        R.enableMask(p, 1),
        J.push(...K(n)),
        r.length) {
            const t = r.filter((t=>!t.expandsCanvas))
              , e = r.filter((t=>t.expandsCanvas));
            t.length && J.push(...K(t)),
            e.length && (R.enableMask({
                x: p.x + .5,
                y: p.y + .5,
                width: p.width - 1,
                height: p.height - 1
            }, d),
            J.push(...K(e)))
        }
        R.disableMask(),
        J.push(...K(i)),
        T.forEach((t=>{
            R.enableMask(t.mask, t.maskOpacity),
            t.backgroundColor && R.drawRect(t.mask, 0, !1, !1, t.maskCornerRadius, t.backgroundColor, void 0, void 0, void 0, void 0, void 0, t.opacity, t.maskFeather),
            j({
                ...t,
                translation: {
                    x: t.translation.x + t.offset.x - .5 * P,
                    y: t.translation.y + t.offset.y - .5 * I
                }
            })
        }
        )),
        R.disableMask(),
        (t=>{
            W.forEach(((e,o)=>{
                !t.find((t=>t === e)) && U(e) && (Array.from(V.values()).includes(e) || (W.delete(o),
                R.textureDelete(e)))
            }
            ))
        }
        )(J)
    }
    ;
    let tt = Date.now();
    const et = ()=>{
        const t = Date.now();
        t - tt < 48 || (tt = t,
        Q())
    }
    ;
    Zn((()=>r())),
    qn((()=>o(20, R = el(m)))),
    Kn((()=>{
        R && (R.release(),
        o(20, R = void 0),
        o(2, m = void 0))
    }
    ));
    return t.$$set = t=>{
        "animate"in t && o(9, g = t.animate),
        "maskRect"in t && o(10, $ = t.maskRect),
        "maskOpacity"in t && o(11, y = t.maskOpacity),
        "maskFrameOpacity"in t && o(12, x = t.maskFrameOpacity),
        "pixelRatio"in t && o(13, b = t.pixelRatio),
        "backgroundColor"in t && o(14, v = t.backgroundColor),
        "willRender"in t && o(15, S = t.willRender),
        "willRequestResource"in t && o(16, C = t.willRequestResource),
        "loadImageData"in t && o(17, k = t.loadImageData),
        "images"in t && o(18, M = t.images),
        "interfaceImages"in t && o(19, T = t.interfaceImages)
    }
    ,
    t.$$.update = ()=>{
        16384 & t.$$.dirty[0] && v && A(L, v),
        2048 & t.$$.dirty[0] && A(F, qe(y) ? y : 1),
        4096 & t.$$.dirty[0] && A(z, qe(x) ? x : 1),
        1024 & t.$$.dirty[0] && $ && B.set($),
        6291456 & t.$$.dirty[0] && a && O.set([a[0], a[1], a[2], na(s, 0, 1)]),
        1310723 & t.$$.dirty[0] && o(24, i = !!(R && P && I && M.length)),
        1056771 & t.$$.dirty[0] && P && I && R && R.resize(P, I, b),
        16777216 & t.$$.dirty[0] && o(23, r = i ? Rs() ? et : Q : n),
        25165824 & t.$$.dirty[0] && i && r && r()
    }
    ,
    [P, I, m, h, L, F, z, B, O, g, $, y, x, b, v, S, C, k, M, T, R, a, s, r, i, function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            m = t,
            o(2, m)
        }
        ))
    }
    , t=>{
        o(0, P = t.detail.width),
        o(1, I = t.detail.height),
        h("measure", {
            width: P,
            height: I
        })
    }
    ]
}
class nl extends Br {
    constructor(t) {
        super(),
        zr(this, t, il, ol, ln, {
            animate: 9,
            maskRect: 10,
            maskOpacity: 11,
            maskFrameOpacity: 12,
            pixelRatio: 13,
            backgroundColor: 14,
            willRender: 15,
            willRequestResource: 16,
            loadImageData: 17,
            images: 18,
            interfaceImages: 19
        }, [-1, -1])
    }
}
var rl = (t,e=Boolean,o=" ")=>t.filter(e).join(o);
function al(t, e, o) {
    const i = t.slice();
    return i[17] = e[o],
    i
}
const sl = t=>({
    tab: 4 & t
})
  , ll = t=>({
    tab: t[17]
});
function cl(t) {
    let e, o, i, n = [], r = new Map, a = t[2];
    const s = t=>t[17].id;
    for (let e = 0; e < a.length; e += 1) {
        let o = al(t, a, e)
          , i = s(o);
        r.set(i, n[e] = dl(i, o))
    }
    return {
        c() {
            e = Rn("ul");
            for (let t = 0; t < n.length; t += 1)
                n[t].c();
            Bn(e, "class", o = rl(["PinturaTabList", t[0]])),
            Bn(e, "role", "tablist"),
            Bn(e, "data-layout", t[1])
        },
        m(o, r) {
            Mn(o, e, r);
            for (let t = 0; t < n.length; t += 1)
                n[t].m(e, null);
            t[14](e),
            i = !0
        },
        p(t, l) {
            1124 & l && (a = t[2],
            yr(),
            n = Tr(n, l, s, 1, t, a, r, e, Mr, dl, null, al),
            xr()),
            (!i || 1 & l && o !== (o = rl(["PinturaTabList", t[0]]))) && Bn(e, "class", o),
            (!i || 2 & l) && Bn(e, "data-layout", t[1])
        },
        i(t) {
            if (!i) {
                for (let t = 0; t < a.length; t += 1)
                    br(n[t]);
                i = !0
            }
        },
        o(t) {
            for (let t = 0; t < n.length; t += 1)
                vr(n[t]);
            i = !1
        },
        d(o) {
            o && Tn(e);
            for (let t = 0; t < n.length; t += 1)
                n[t].d();
            t[14](null)
        }
    }
}
function dl(t, e) {
    let o, i, n, r, a, s, l, c, d, u;
    const h = e[11].default
      , p = hn(h, e, e[10], ll);
    function m(...t) {
        return e[12](e[17], ...t)
    }
    function g(...t) {
        return e[13](e[17], ...t)
    }
    return {
        key: t,
        first: null,
        c() {
            o = Rn("li"),
            i = Rn("button"),
            p && p.c(),
            r = An(),
            i.disabled = n = e[17].disabled,
            Bn(o, "role", "tab"),
            Bn(o, "aria-controls", a = e[17].href.substr(1)),
            Bn(o, "id", s = e[17].tabId),
            Bn(o, "aria-selected", l = e[17].selected),
            this.first = o
        },
        m(t, e) {
            Mn(t, o, e),
            kn(o, i),
            p && p.m(i, null),
            kn(o, r),
            c = !0,
            d || (u = [Ln(i, "keydown", m), Ln(i, "click", g)],
            d = !0)
        },
        p(t, r) {
            e = t,
            p && p.p && 1028 & r && mn(p, h, e, e[10], r, sl, ll),
            (!c || 4 & r && n !== (n = e[17].disabled)) && (i.disabled = n),
            (!c || 4 & r && a !== (a = e[17].href.substr(1))) && Bn(o, "aria-controls", a),
            (!c || 4 & r && s !== (s = e[17].tabId)) && Bn(o, "id", s),
            (!c || 4 & r && l !== (l = e[17].selected)) && Bn(o, "aria-selected", l)
        },
        i(t) {
            c || (br(p, t),
            c = !0)
        },
        o(t) {
            vr(p, t),
            c = !1
        },
        d(t) {
            t && Tn(o),
            p && p.d(t),
            d = !1,
            an(u)
        }
    }
}
function ul(t) {
    let e, o, i = t[4] && cl(t);
    return {
        c() {
            i && i.c(),
            e = En()
        },
        m(t, n) {
            i && i.m(t, n),
            Mn(t, e, n),
            o = !0
        },
        p(t, [o]) {
            t[4] ? i ? (i.p(t, o),
            16 & o && br(i, 1)) : (i = cl(t),
            i.c(),
            br(i, 1),
            i.m(e.parentNode, e)) : i && (yr(),
            vr(i, 1, 1, (()=>{
                i = null
            }
            )),
            xr())
        },
        i(t) {
            o || (br(i),
            o = !0)
        },
        o(t) {
            vr(i),
            o = !1
        },
        d(t) {
            i && i.d(t),
            t && Tn(e)
        }
    }
}
function hl(t, e, o) {
    let i, n, r, {$$slots: a={}, $$scope: s} = e, {class: l} = e, {name: c} = e, {selected: d} = e, {tabs: u=[]} = e, {layout: h} = e;
    const p = Jn()
      , m = t=>{
        const e = r.querySelectorAll('[role="tab"] button')[t];
        e && e.focus()
    }
      , g = (t,e)=>{
        t.preventDefault(),
        t.stopPropagation(),
        p("select", e)
    }
      , f = ({key: t},e)=>{
        if (!/arrow/i.test(t))
            return;
        const o = u.findIndex((t=>t.id === e));
        return /right|down/i.test(t) ? m(o < u.length - 1 ? o + 1 : 0) : /left|up/i.test(t) ? m(o > 0 ? o - 1 : u.length - 1) : void 0
    }
    ;
    return t.$$set = t=>{
        "class"in t && o(0, l = t.class),
        "name"in t && o(7, c = t.name),
        "selected"in t && o(8, d = t.selected),
        "tabs"in t && o(9, u = t.tabs),
        "layout"in t && o(1, h = t.layout),
        "$$scope"in t && o(10, s = t.$$scope)
    }
    ,
    t.$$.update = ()=>{
        896 & t.$$.dirty && o(2, i = u.map((t=>{
            const e = t.id === d;
            return {
                ...t,
                tabId: `tab-${c}-${t.id}`,
                href: `#panel-${c}-${t.id}`,
                selected: e
            }
        }
        ))),
        4 & t.$$.dirty && o(4, n = i.length > 1)
    }
    ,
    [l, h, i, r, n, g, f, c, d, u, s, a, (t,e)=>f(e, t.id), (t,e)=>g(e, t.id), function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            r = t,
            o(3, r)
        }
        ))
    }
    ]
}
class pl extends Br {
    constructor(t) {
        super(),
        zr(this, t, hl, ul, ln, {
            class: 0,
            name: 7,
            selected: 8,
            tabs: 9,
            layout: 1
        })
    }
}
const ml = t=>({
    panel: 16 & t
})
  , gl = t=>({
    panel: t[4][0].id,
    panelIsActive: !0
});
function fl(t, e, o) {
    const i = t.slice();
    return i[14] = e[o].id,
    i[15] = e[o].draw,
    i[16] = e[o].panelId,
    i[17] = e[o].tabindex,
    i[18] = e[o].labelledBy,
    i[19] = e[o].hidden,
    i[3] = e[o].visible,
    i
}
const $l = t=>({
    panel: 16 & t,
    panelIsActive: 16 & t
})
  , yl = t=>({
    panel: t[14],
    panelIsActive: !t[19]
});
function xl(t) {
    let e, o, i, n, r, a;
    const s = t[11].default
      , l = hn(s, t, t[10], gl);
    return {
        c() {
            e = Rn("div"),
            o = Rn("div"),
            l && l.c(),
            Bn(o, "class", i = rl([t[1]])),
            Bn(e, "class", t[0]),
            Bn(e, "style", t[2])
        },
        m(i, s) {
            Mn(i, e, s),
            kn(e, o),
            l && l.m(o, null),
            n = !0,
            r || (a = [Ln(e, "measure", t[13]), yn(bs.call(null, e))],
            r = !0)
        },
        p(t, r) {
            l && l.p && 1040 & r && mn(l, s, t, t[10], r, ml, gl),
            (!n || 2 & r && i !== (i = rl([t[1]]))) && Bn(o, "class", i),
            (!n || 1 & r) && Bn(e, "class", t[0]),
            (!n || 4 & r) && Bn(e, "style", t[2])
        },
        i(t) {
            n || (br(l, t),
            n = !0)
        },
        o(t) {
            vr(l, t),
            n = !1
        },
        d(t) {
            t && Tn(e),
            l && l.d(t),
            r = !1,
            an(a)
        }
    }
}
function bl(t) {
    let e, o, i, n, r, a = [], s = new Map, l = t[4];
    const c = t=>t[14];
    for (let e = 0; e < l.length; e += 1) {
        let o = fl(t, l, e)
          , i = c(o);
        s.set(i, a[e] = wl(i, o))
    }
    return {
        c() {
            e = Rn("div");
            for (let t = 0; t < a.length; t += 1)
                a[t].c();
            Bn(e, "class", o = rl(["PinturaTabPanels", t[0]])),
            Bn(e, "style", t[2])
        },
        m(o, s) {
            Mn(o, e, s);
            for (let t = 0; t < a.length; t += 1)
                a[t].m(e, null);
            i = !0,
            n || (r = [Ln(e, "measure", t[12]), yn(bs.call(null, e, {
                observePosition: !0
            }))],
            n = !0)
        },
        p(t, n) {
            1042 & n && (l = t[4],
            yr(),
            a = Tr(a, n, c, 1, t, l, s, e, Mr, wl, null, fl),
            xr()),
            (!i || 1 & n && o !== (o = rl(["PinturaTabPanels", t[0]]))) && Bn(e, "class", o),
            (!i || 4 & n) && Bn(e, "style", t[2])
        },
        i(t) {
            if (!i) {
                for (let t = 0; t < l.length; t += 1)
                    br(a[t]);
                i = !0
            }
        },
        o(t) {
            for (let t = 0; t < a.length; t += 1)
                vr(a[t]);
            i = !1
        },
        d(t) {
            t && Tn(e);
            for (let t = 0; t < a.length; t += 1)
                a[t].d();
            n = !1,
            an(r)
        }
    }
}
function vl(t) {
    let e;
    const o = t[11].default
      , i = hn(o, t, t[10], yl);
    return {
        c() {
            i && i.c()
        },
        m(t, o) {
            i && i.m(t, o),
            e = !0
        },
        p(t, e) {
            i && i.p && 1040 & e && mn(i, o, t, t[10], e, $l, yl)
        },
        i(t) {
            e || (br(i, t),
            e = !0)
        },
        o(t) {
            vr(i, t),
            e = !1
        },
        d(t) {
            i && i.d(t)
        }
    }
}
function wl(t, e) {
    let o, i, n, r, a, s, l, c, d, u = e[15] && vl(e);
    return {
        key: t,
        first: null,
        c() {
            o = Rn("div"),
            u && u.c(),
            i = An(),
            Bn(o, "class", n = rl(["PinturaTabPanel", e[1]])),
            o.hidden = r = e[19],
            Bn(o, "id", a = e[16]),
            Bn(o, "tabindex", s = e[17]),
            Bn(o, "aria-labelledby", l = e[18]),
            Bn(o, "data-inert", c = !e[3]),
            this.first = o
        },
        m(t, e) {
            Mn(t, o, e),
            u && u.m(o, null),
            kn(o, i),
            d = !0
        },
        p(t, h) {
            (e = t)[15] ? u ? (u.p(e, h),
            16 & h && br(u, 1)) : (u = vl(e),
            u.c(),
            br(u, 1),
            u.m(o, i)) : u && (yr(),
            vr(u, 1, 1, (()=>{
                u = null
            }
            )),
            xr()),
            (!d || 2 & h && n !== (n = rl(["PinturaTabPanel", e[1]]))) && Bn(o, "class", n),
            (!d || 16 & h && r !== (r = e[19])) && (o.hidden = r),
            (!d || 16 & h && a !== (a = e[16])) && Bn(o, "id", a),
            (!d || 16 & h && s !== (s = e[17])) && Bn(o, "tabindex", s),
            (!d || 16 & h && l !== (l = e[18])) && Bn(o, "aria-labelledby", l),
            (!d || 16 & h && c !== (c = !e[3])) && Bn(o, "data-inert", c)
        },
        i(t) {
            d || (br(u),
            d = !0)
        },
        o(t) {
            vr(u),
            d = !1
        },
        d(t) {
            t && Tn(o),
            u && u.d()
        }
    }
}
function Sl(t) {
    let e, o, i, n;
    const r = [bl, xl]
      , a = [];
    function s(t, e) {
        return t[5] ? 0 : 1
    }
    return e = s(t),
    o = a[e] = r[e](t),
    {
        c() {
            o.c(),
            i = En()
        },
        m(t, o) {
            a[e].m(t, o),
            Mn(t, i, o),
            n = !0
        },
        p(t, [n]) {
            let l = e;
            e = s(t),
            e === l ? a[e].p(t, n) : (yr(),
            vr(a[l], 1, 1, (()=>{
                a[l] = null
            }
            )),
            xr(),
            o = a[e],
            o ? o.p(t, n) : (o = a[e] = r[e](t),
            o.c()),
            br(o, 1),
            o.m(i.parentNode, i))
        },
        i(t) {
            n || (br(o),
            n = !0)
        },
        o(t) {
            vr(o),
            n = !1
        },
        d(t) {
            a[e].d(t),
            t && Tn(i)
        }
    }
}
function Cl(t, e, o) {
    let i, n, {$$slots: r={}, $$scope: a} = e, {class: s} = e, {name: l} = e, {selected: c} = e, {visible: d} = e, {panelClass: u} = e, {panels: h=[]} = e, {style: p} = e;
    const m = {};
    return t.$$set = t=>{
        "class"in t && o(0, s = t.class),
        "name"in t && o(6, l = t.name),
        "selected"in t && o(7, c = t.selected),
        "visible"in t && o(3, d = t.visible),
        "panelClass"in t && o(1, u = t.panelClass),
        "panels"in t && o(8, h = t.panels),
        "style"in t && o(2, p = t.style),
        "$$scope"in t && o(10, a = t.$$scope)
    }
    ,
    t.$$.update = ()=>{
        968 & t.$$.dirty && o(4, i = h.map((t=>{
            const e = t === c
              , i = !d || -1 !== d.indexOf(t);
            return e && o(9, m[t] = !0, m),
            {
                id: t,
                panelId: `panel-${l}-${t}`,
                labelledBy: `tab-${l}-${t}`,
                hidden: !e,
                visible: i,
                tabindex: e ? 0 : -1,
                draw: e || m[t]
            }
        }
        ))),
        16 & t.$$.dirty && o(5, n = i.length > 1)
    }
    ,
    [s, u, p, d, i, n, l, c, h, m, a, r, function(e) {
        er(t, e)
    }
    , function(e) {
        er(t, e)
    }
    ]
}
class kl extends Br {
    constructor(t) {
        super(),
        zr(this, t, Cl, Sl, ln, {
            class: 0,
            name: 6,
            selected: 7,
            visible: 3,
            panelClass: 1,
            panels: 8,
            style: 2
        })
    }
}
function Ml(t) {
    let e, o, i, n, r;
    const a = [t[7]];
    function s(e) {
        t[19](e)
    }
    var l = t[11];
    function c(t) {
        let e = {};
        for (let t = 0; t < a.length; t += 1)
            e = on(e, a[t]);
        return void 0 !== t[5] && (e.name = t[5]),
        {
            props: e
        }
    }
    return l && (o = new l(c(t)),
    ir.push((()=>Ir(o, "name", s))),
    t[20](o),
    o.$on("measure", t[21])),
    {
        c() {
            e = Rn("div"),
            o && Ar(o.$$.fragment),
            Bn(e, "data-util", t[5]),
            Bn(e, "class", n = rl(["PinturaPanel", t[2]])),
            Bn(e, "style", t[6])
        },
        m(t, i) {
            Mn(t, e, i),
            o && Er(o, e, null),
            r = !0
        },
        p(t, [d]) {
            const u = 128 & d ? Rr(a, [Pr(t[7])]) : {};
            if (!i && 32 & d && (i = !0,
            u.name = t[5],
            cr((()=>i = !1))),
            l !== (l = t[11])) {
                if (o) {
                    yr();
                    const t = o;
                    vr(t.$$.fragment, 1, 0, (()=>{
                        Lr(t, 1)
                    }
                    )),
                    xr()
                }
                l ? (o = new l(c(t)),
                ir.push((()=>Ir(o, "name", s))),
                t[20](o),
                o.$on("measure", t[21]),
                Ar(o.$$.fragment),
                br(o.$$.fragment, 1),
                Er(o, e, null)) : o = null
            } else
                l && o.$set(u);
            (!r || 32 & d) && Bn(e, "data-util", t[5]),
            (!r || 4 & d && n !== (n = rl(["PinturaPanel", t[2]]))) && Bn(e, "class", n),
            (!r || 64 & d) && Bn(e, "style", t[6])
        },
        i(t) {
            r || (o && br(o.$$.fragment, t),
            r = !0)
        },
        o(t) {
            o && vr(o.$$.fragment, t),
            r = !1
        },
        d(i) {
            i && Tn(e),
            t[20](null),
            o && Lr(o)
        }
    }
}
function Tl(t, e, o) {
    let i, n, r, a;
    const s = Jn();
    let l, {isActive: c=!0} = e, {isAnimated: d=!0} = e, {stores: u} = e, {content: h} = e, {component: p} = e, {locale: m} = e, {class: g} = e;
    const f = ls(0)
      , $ = Wr(f, (t=>na(t, 0, 1)));
    un(t, $, (t=>o(18, r = t)));
    let y = !c;
    const x = _r(c);
    un(t, x, (t=>o(22, a = t)));
    const b = {
        isActive: Wr(x, (t=>t)),
        isActiveFraction: Wr($, (t=>t)),
        isVisible: Wr($, (t=>t > 0))
    }
      , v = h.view
      , w = os(v)
      , S = Object.keys(h.props || {}).reduce(((t,e)=>w.includes(e) ? (t[e] = h.props[e],
    t) : t), {})
      , C = Object.keys(b).reduce(((t,e)=>w.includes(e) ? (t[e] = b[e],
    t) : t), {});
    let k, M = !1;
    qn((()=>{
        o(4, M = !0)
    }
    ));
    return t.$$set = t=>{
        "isActive"in t && o(1, c = t.isActive),
        "isAnimated"in t && o(12, d = t.isAnimated),
        "stores"in t && o(13, u = t.stores),
        "content"in t && o(14, h = t.content),
        "component"in t && o(0, p = t.component),
        "locale"in t && o(15, m = t.locale),
        "class"in t && o(2, g = t.class)
    }
    ,
    t.$$.update = ()=>{
        11 & t.$$.dirty && l && c && p && s("measure", l),
        4098 & t.$$.dirty && f.set(c ? 1 : 0, {
            hard: !d
        }),
        393216 & t.$$.dirty && (r <= 0 && !y ? o(17, y = !0) : r > 0 && y && o(17, y = !1)),
        131088 & t.$$.dirty && M && s(y ? "hide" : "show"),
        262144 & t.$$.dirty && s("fade", r),
        262144 & t.$$.dirty && o(6, i = r < 1 ? "opacity: " + r : void 0),
        2 & t.$$.dirty && $n(x, a = c, a),
        40960 & t.$$.dirty && o(7, n = {
            ...S,
            ...C,
            stores: u,
            locale: m
        })
    }
    ,
    [p, c, g, l, M, k, i, n, s, $, x, v, d, u, h, m, f, y, r, function(t) {
        k = t,
        o(5, k)
    }
    , function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            p = t,
            o(0, p)
        }
        ))
    }
    , t=>{
        M && c && (o(3, l = t.detail),
        s("measure", {
            ...l
        }))
    }
    ]
}
class Rl extends Br {
    constructor(t) {
        super(),
        zr(this, t, Tl, Ml, ln, {
            isActive: 1,
            isAnimated: 12,
            stores: 13,
            content: 14,
            component: 0,
            locale: 15,
            class: 2,
            opacity: 16
        })
    }
    get opacity() {
        return this.$$.ctx[16]
    }
}
function Pl(t) {
    let e, o, i;
    const n = t[5].default
      , r = hn(n, t, t[4], null);
    return {
        c() {
            e = Pn("svg"),
            r && r.c(),
            Bn(e, "class", t[3]),
            Bn(e, "style", t[2]),
            Bn(e, "width", t[0]),
            Bn(e, "height", t[1]),
            Bn(e, "viewBox", o = "0 0 " + t[0] + "\n    " + t[1]),
            Bn(e, "xmlns", "http://www.w3.org/2000/svg"),
            Bn(e, "aria-hidden", "true"),
            Bn(e, "focusable", "false"),
            Bn(e, "stroke-linecap", "round"),
            Bn(e, "stroke-linejoin", "round")
        },
        m(t, o) {
            Mn(t, e, o),
            r && r.m(e, null),
            i = !0
        },
        p(t, [a]) {
            r && r.p && 16 & a && mn(r, n, t, t[4], a, null, null),
            (!i || 8 & a) && Bn(e, "class", t[3]),
            (!i || 4 & a) && Bn(e, "style", t[2]),
            (!i || 1 & a) && Bn(e, "width", t[0]),
            (!i || 2 & a) && Bn(e, "height", t[1]),
            (!i || 3 & a && o !== (o = "0 0 " + t[0] + "\n    " + t[1])) && Bn(e, "viewBox", o)
        },
        i(t) {
            i || (br(r, t),
            i = !0)
        },
        o(t) {
            vr(r, t),
            i = !1
        },
        d(t) {
            t && Tn(e),
            r && r.d(t)
        }
    }
}
function Il(t, e, o) {
    let {$$slots: i={}, $$scope: n} = e
      , {width: r=24} = e
      , {height: a=24} = e
      , {style: s} = e
      , {class: l} = e;
    return t.$$set = t=>{
        "width"in t && o(0, r = t.width),
        "height"in t && o(1, a = t.height),
        "style"in t && o(2, s = t.style),
        "class"in t && o(3, l = t.class),
        "$$scope"in t && o(4, n = t.$$scope)
    }
    ,
    [r, a, s, l, n, i]
}
class Al extends Br {
    constructor(t) {
        super(),
        zr(this, t, Il, Pl, ln, {
            width: 0,
            height: 1,
            style: 2,
            class: 3
        })
    }
}
var El = (t,e)=>e === t.target || e.contains(t.target);
function Ll(t) {
    let e, o;
    return e = new Al({
        props: {
            class: "PinturaButtonIcon",
            $$slots: {
                default: [Fl]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            1048578 & o && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Fl(t) {
    let e;
    return {
        c() {
            e = Pn("g")
        },
        m(o, i) {
            Mn(o, e, i),
            e.innerHTML = t[1]
        },
        p(t, o) {
            2 & o && (e.innerHTML = t[1])
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function zl(t) {
    let e, o;
    return {
        c() {
            e = Rn("span"),
            o = In(t[0]),
            Bn(e, "class", t[11])
        },
        m(t, i) {
            Mn(t, e, i),
            kn(e, o)
        },
        p(t, i) {
            1 & i && Dn(o, t[0]),
            2048 & i && Bn(e, "class", t[11])
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function Bl(t) {
    let e, o, i, n;
    const r = t[18].default
      , a = hn(r, t, t[20], null)
      , s = a || function(t) {
        let e, o, i, n = t[1] && Ll(t), r = t[0] && zl(t);
        return {
            c() {
                e = Rn("span"),
                n && n.c(),
                o = An(),
                r && r.c(),
                Bn(e, "class", t[9])
            },
            m(t, a) {
                Mn(t, e, a),
                n && n.m(e, null),
                kn(e, o),
                r && r.m(e, null),
                i = !0
            },
            p(t, a) {
                t[1] ? n ? (n.p(t, a),
                2 & a && br(n, 1)) : (n = Ll(t),
                n.c(),
                br(n, 1),
                n.m(e, o)) : n && (yr(),
                vr(n, 1, 1, (()=>{
                    n = null
                }
                )),
                xr()),
                t[0] ? r ? r.p(t, a) : (r = zl(t),
                r.c(),
                r.m(e, null)) : r && (r.d(1),
                r = null),
                (!i || 512 & a) && Bn(e, "class", t[9])
            },
            i(t) {
                i || (br(n),
                i = !0)
            },
            o(t) {
                vr(n),
                i = !1
            },
            d(t) {
                t && Tn(e),
                n && n.d(),
                r && r.d()
            }
        }
    }(t);
    return {
        c() {
            e = Rn("button"),
            s && s.c(),
            Bn(e, "type", t[4]),
            Bn(e, "style", t[2]),
            e.disabled = t[3],
            Bn(e, "class", t[10]),
            Bn(e, "title", t[0])
        },
        m(r, a) {
            Mn(r, e, a),
            s && s.m(e, null),
            t[19](e),
            o = !0,
            i || (n = [Ln(e, "keydown", (function() {
                sn(t[6]) && t[6].apply(this, arguments)
            }
            )), Ln(e, "click", (function() {
                sn(t[5]) && t[5].apply(this, arguments)
            }
            )), yn(t[7].call(null, e))],
            i = !0)
        },
        p(i, [n]) {
            t = i,
            a ? a.p && 1048576 & n && mn(a, r, t, t[20], n, null, null) : s && s.p && 2563 & n && s.p(t, n),
            (!o || 16 & n) && Bn(e, "type", t[4]),
            (!o || 4 & n) && Bn(e, "style", t[2]),
            (!o || 8 & n) && (e.disabled = t[3]),
            (!o || 1024 & n) && Bn(e, "class", t[10]),
            (!o || 1 & n) && Bn(e, "title", t[0])
        },
        i(t) {
            o || (br(s, t),
            o = !0)
        },
        o(t) {
            vr(s, t),
            o = !1
        },
        d(o) {
            o && Tn(e),
            s && s.d(o),
            t[19](null),
            i = !1,
            an(n)
        }
    }
}
function Ol(t, e, o) {
    let i, n, r, a, {$$slots: s={}, $$scope: l} = e, {class: c} = e, {label: d} = e, {labelClass: u} = e, {innerClass: h} = e, {hideLabel: p=!1} = e, {icon: m} = e, {style: g} = e, {disabled: f} = e, {type: $="button"} = e, {onclick: y} = e, {onkeydown: x} = e, {action: b=(()=>{}
    )} = e;
    return t.$$set = t=>{
        "class"in t && o(12, c = t.class),
        "label"in t && o(0, d = t.label),
        "labelClass"in t && o(13, u = t.labelClass),
        "innerClass"in t && o(14, h = t.innerClass),
        "hideLabel"in t && o(15, p = t.hideLabel),
        "icon"in t && o(1, m = t.icon),
        "style"in t && o(2, g = t.style),
        "disabled"in t && o(3, f = t.disabled),
        "type"in t && o(4, $ = t.type),
        "onclick"in t && o(5, y = t.onclick),
        "onkeydown"in t && o(6, x = t.onkeydown),
        "action"in t && o(7, b = t.action),
        "$$scope"in t && o(20, l = t.$$scope)
    }
    ,
    t.$$.update = ()=>{
        16384 & t.$$.dirty && o(9, i = rl(["PinturaButtonInner", h])),
        36864 & t.$$.dirty && o(10, n = rl(["PinturaButton", p && "PinturaButtonIconOnly", c])),
        40960 & t.$$.dirty && o(11, r = rl([p ? "implicit" : "PinturaButtonLabel", u]))
    }
    ,
    [d, m, g, f, $, y, x, b, a, i, n, r, c, u, h, p, t=>El(t, a), ()=>a, s, function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            a = t,
            o(8, a)
        }
        ))
    }
    , l]
}
class Dl extends Br {
    constructor(t) {
        super(),
        zr(this, t, Ol, Bl, ln, {
            class: 12,
            label: 0,
            labelClass: 13,
            innerClass: 14,
            hideLabel: 15,
            icon: 1,
            style: 2,
            disabled: 3,
            type: 4,
            onclick: 5,
            onkeydown: 6,
            action: 7,
            isEventTarget: 16,
            getElement: 17
        })
    }
    get isEventTarget() {
        return this.$$.ctx[16]
    }
    get getElement() {
        return this.$$.ctx[17]
    }
}
var _l = (t,e)=>{
    const o = t.findIndex(e);
    if (o >= 0)
        return t.splice(o, 1)
}
;
var Wl = (t,e={})=>{
    const {inertia: o=!1, matchTarget: i=!1, pinch: n=!1, getEventPosition: r=(t=>Y(t.clientX, t.clientY))} = e;
    function a(e, o) {
        t.dispatchEvent(new CustomEvent(e,{
            detail: o
        }))
    }
    function s() {
        f && f(),
        f = void 0
    }
    const l = []
      , c = t=>0 === t.timeStamp ? Date.now() : t.timeStamp
      , d = t=>{
        t.origin.x = t.position.x,
        t.origin.y = t.position.y,
        t.translation.x = 0,
        t.translation.y = 0
    }
      , u = t=>{
        const e = (t=>l.findIndex((e=>e.event.pointerId === t.pointerId)))(t);
        if (!(e < 0))
            return l[e]
    }
      , h = ()=>1 === l.length
      , p = ()=>2 === l.length
      , m = t=>{
        const e = dt(t.map((t=>t.position)));
        return {
            center: e,
            distance: ((t,e)=>t.reduce(((t,o)=>t + ct(e, o.position)), 0) / t.length)(t, e),
            velocity: dt(t.map((t=>t.velocity))),
            translation: dt(t.map((t=>t.translation)))
        }
    }
    ;
    let g, f, $, y, x, b, v = 0, w = void 0;
    function S(e) {
        p() || (t=>qe(t.button) && 0 !== t.button)(e) || i && e.target !== t || (s(),
        (t=>{
            const e = c(t)
              , o = {
                timeStamp: e,
                timeStampInitial: e,
                position: r(t),
                origin: r(t),
                velocity: X(),
                translation: X(),
                interactionState: void 0,
                event: t
            };
            l.push(o),
            o.interactionState = m(l)
        }
        )(e),
        h() ? (document.documentElement.addEventListener("pointermove", k),
        document.documentElement.addEventListener("pointerup", M),
        document.documentElement.addEventListener("pointercancel", M),
        b = !1,
        x = 1,
        y = X(),
        $ = void 0,
        a("interactionstart", {
            origin: Z(u(e).origin)
        })) : n && (b = !0,
        $ = ct(l[0].position, l[1].position),
        y.x += l[0].translation.x,
        y.y += l[0].translation.y,
        d(l[0])))
    }
    t.addEventListener("pointerdown", S);
    let C = Date.now();
    function k(t) {
        t.preventDefault(),
        (t=>{
            const e = u(t);
            if (!e)
                return;
            const o = c(t)
              , i = r(t)
              , n = Math.max(1, o - e.timeStamp);
            e.velocity.x = (i.x - e.position.x) / n,
            e.velocity.y = (i.y - e.position.y) / n,
            e.translation.x = i.x - e.origin.x,
            e.translation.y = i.y - e.origin.y,
            e.timeStamp = o,
            e.position.x = i.x,
            e.position.y = i.y,
            e.event = t
        }
        )(t);
        const e = Z(l[0].translation);
        let o = x;
        if (n && p()) {
            o *= ct(l[0].position, l[1].position) / $,
            nt(e, l[1].translation)
        }
        e.x += y.x,
        e.y += y.y;
        const i = Date.now();
        i - C < 16 || (C = i,
        a("interactionupdate", {
            translation: e,
            scalar: n ? o : void 0
        }))
    }
    function M(t) {
        if (!u(t))
            return;
        const e = (t=>{
            const e = _l(l, (e=>e.event.pointerId === t.pointerId));
            if (e)
                return e[0]
        }
        )(t);
        if (n && h()) {
            const t = ct(l[0].position, e.position);
            x *= t / $,
            y.x += l[0].translation.x + e.translation.x,
            y.y += l[0].translation.y + e.translation.y,
            d(l[0])
        }
        let i = !1
          , r = !1;
        if (!b && e) {
            const t = performance.now()
              , o = t - e.timeStampInitial
              , n = lt(e.translation);
            i = n < 64 && o < 300,
            r = !!(w && i && t - v < 700 && lt(w, e.position) < 128),
            i && (w = Z(e.position),
            v = t)
        }
        if (l.length > 0)
            return;
        document.documentElement.removeEventListener("pointermove", k),
        document.documentElement.removeEventListener("pointerup", M),
        document.documentElement.removeEventListener("pointercancel", M);
        const s = Z(e.translation)
          , c = Z(e.velocity);
        let p = !1;
        a("interactionrelease", {
            isTap: i,
            isDoubleTap: r,
            translation: s,
            scalar: x,
            preventInertia: ()=>p = !0
        });
        const m = ct(c);
        if (p || !o || m < .25)
            return R(s, {
                isTap: i,
                isDoubleTap: r
            });
        g = as(Z(s), {
            easing: is,
            duration: 80 * m
        }),
        g.set({
            x: s.x + 50 * c.x,
            y: s.y + 50 * c.y
        }).then((()=>{
            f && R(dn(g), {
                isTap: i,
                isDoubleTap: r
            })
        }
        )),
        f = g.subscribe(T)
    }
    function T(t) {
        t && a("interactionupdate", {
            translation: t,
            scalar: n ? x : void 0
        })
    }
    function R(t, e) {
        s(),
        a("interactionend", {
            ...e,
            translation: t,
            scalar: n ? x : void 0
        })
    }
    return {
        destroy() {
            s(),
            t.removeEventListener("pointerdown", S)
        }
    }
}
  , Vl = (t,e={})=>{
    const {direction: o, shiftMultiplier: i=10, bubbles: n=!1, stopKeydownPropagation: r=!0} = e
      , a = "horizontal" === o
      , s = "vertical" === o
      , l = e=>{
        const {key: o} = e
          , l = e.shiftKey
          , c = /up|down/i.test(o)
          , d = /left|right/i.test(o);
        if (!d && !c)
            return;
        if (a && c)
            return;
        if (s && d)
            return;
        const u = l ? i : 1;
        r && e.stopPropagation(),
        t.dispatchEvent(new CustomEvent("nudge",{
            bubbles: n,
            detail: Y((/left/i.test(o) ? -1 : /right/i.test(o) ? 1 : 0) * u, (/up/i.test(o) ? -1 : /down/i.test(o) ? 1 : 0) * u)
        }))
    }
    ;
    return t.addEventListener("keydown", l),
    {
        destroy() {
            t.removeEventListener("keydown", l)
        }
    }
}
;
function Hl(t, e) {
    return e * Math.sign(t) * Math.log10(1 + Math.abs(t) / e)
}
const Nl = (t,e,o)=>{
    if (!e)
        return At(t);
    const i = t.x + Hl(e.x - t.x, o)
      , n = t.x + t.width + Hl(e.x + e.width - (t.x + t.width), o)
      , r = t.y + Hl(e.y - t.y, o);
    return {
        x: i,
        y: r,
        width: n - i,
        height: t.y + t.height + Hl(e.y + e.height - (t.y + t.height), o) - r
    }
}
;
var Ul = (t,e)=>{
    if (t)
        return /em/.test(t) ? 16 * parseInt(t, 10) : /px/.test(t) ? parseInt(t, 10) : void 0
}
  , jl = t=>{
    let e = t.detail || 0;
    const {deltaX: o, deltaY: i, wheelDelta: n, wheelDeltaX: r, wheelDeltaY: a} = t;
    return qe(r) && Math.abs(r) > Math.abs(a) ? e = r / -120 : qe(o) && Math.abs(o) > Math.abs(i) ? e = o / 20 : (n || a) && (e = (n || a) / -120),
    e || (e = i / 20),
    e
}
  , Xl = {
    Up: 38,
    Down: 40,
    Left: 37,
    Right: 39
};
function Yl(t) {
    let e, o, i, n, r, a, s;
    const l = t[37].default
      , c = hn(l, t, t[36], null);
    return {
        c() {
            e = Rn("div"),
            o = Rn("div"),
            c && c.c(),
            Bn(o, "style", t[6]),
            Bn(e, "class", i = rl(["PinturaScrollable", t[0]])),
            Bn(e, "style", t[4]),
            Bn(e, "data-direction", t[1]),
            Bn(e, "data-state", t[5])
        },
        m(i, l) {
            Mn(i, e, l),
            kn(e, o),
            c && c.m(o, null),
            t[39](e),
            r = !0,
            a || (s = [Ln(o, "interactionstart", t[9]), Ln(o, "interactionupdate", t[11]), Ln(o, "interactionend", t[12]), Ln(o, "interactionrelease", t[10]), yn(Wl.call(null, o, {
                inertia: !0
            })), Ln(o, "measure", t[38]), yn(bs.call(null, o)), Ln(e, "wheel", t[14], {
                passive: !1
            }), Ln(e, "scroll", t[16]), Ln(e, "focusin", t[15]), Ln(e, "nudge", t[17]), Ln(e, "measure", t[13]), yn(bs.call(null, e, {
                observePosition: !0
            })), yn(n = Vl.call(null, e, {
                direction: "x" === t[1] ? "horizontal" : "vertical",
                stopKeydownPropagation: !1
            }))],
            a = !0)
        },
        p(t, a) {
            c && c.p && 32 & a[1] && mn(c, l, t, t[36], a, null, null),
            (!r || 64 & a[0]) && Bn(o, "style", t[6]),
            (!r || 1 & a[0] && i !== (i = rl(["PinturaScrollable", t[0]]))) && Bn(e, "class", i),
            (!r || 16 & a[0]) && Bn(e, "style", t[4]),
            (!r || 2 & a[0]) && Bn(e, "data-direction", t[1]),
            (!r || 32 & a[0]) && Bn(e, "data-state", t[5]),
            n && sn(n.update) && 2 & a[0] && n.update.call(null, {
                direction: "x" === t[1] ? "horizontal" : "vertical",
                stopKeydownPropagation: !1
            })
        },
        i(t) {
            r || (br(c, t),
            r = !0)
        },
        o(t) {
            vr(c, t),
            r = !1
        },
        d(o) {
            o && Tn(e),
            c && c.d(o),
            t[39](null),
            a = !1,
            an(s)
        }
    }
}
function Gl(t, e, o) {
    let i, r, a, s, l, c, d, u, h, {$$slots: p={}, $$scope: m} = e;
    const g = Jn()
      , f = Object.values(Xl)
      , $ = tr("keysPressed");
    un(t, $, (t=>o(46, h = t)));
    let y, x, b, v, w = "idle", S = ls(0);
    un(t, S, (t=>o(34, u = t)));
    let C, {class: k} = e, {scrollBlockInteractionDist: M=5} = e, {scrollStep: T=10} = e, {scrollFocusMargin: R=64} = e, {scrollDirection: P="x"} = e, {scrollAutoCancel: I=!1} = e, {elasticity: A=0} = e, {onscroll: E=n} = e, {maskFeatherSize: L} = e, {maskFeatherStartOpacity: F} = e, {maskFeatherEndOpacity: z} = e, {scroll: B} = e, O = "", D = !0;
    const _ = S.subscribe((t=>{
        const e = X();
        e[P] = t,
        E(e)
    }
    ))
      , W = t=>Math.max(Math.min(0, t), b[i] - x[i]);
    let V, H, N;
    const U = (t,e={})=>{
        const {elastic: i=!1, animate: n=!1} = e;
        Math.abs(t) > M && "idle" === w && !v && o(28, w = "scrolling");
        const r = W(t)
          , a = i && A && !v ? r + Hl(t - r, A) : r;
        let s = !0;
        n ? s = !1 : D || (s = !v),
        D = !1,
        S.set(a, {
            hard: s
        }).then((t=>{
            v && (D = !0)
        }
        ))
    }
    ;
    Kn((()=>{
        _()
    }
    ));
    return t.$$set = t=>{
        "class"in t && o(0, k = t.class),
        "scrollBlockInteractionDist"in t && o(21, M = t.scrollBlockInteractionDist),
        "scrollStep"in t && o(22, T = t.scrollStep),
        "scrollFocusMargin"in t && o(23, R = t.scrollFocusMargin),
        "scrollDirection"in t && o(1, P = t.scrollDirection),
        "scrollAutoCancel"in t && o(24, I = t.scrollAutoCancel),
        "elasticity"in t && o(25, A = t.elasticity),
        "onscroll"in t && o(26, E = t.onscroll),
        "maskFeatherSize"in t && o(20, L = t.maskFeatherSize),
        "maskFeatherStartOpacity"in t && o(18, F = t.maskFeatherStartOpacity),
        "maskFeatherEndOpacity"in t && o(19, z = t.maskFeatherEndOpacity),
        "scroll"in t && o(27, B = t.scroll),
        "$$scope"in t && o(36, m = t.$$scope)
    }
    ,
    t.$$.update = ()=>{
        if (2 & t.$$.dirty[0] && o(30, i = "x" === P ? "width" : "height"),
        2 & t.$$.dirty[0] && o(31, r = P.toUpperCase()),
        8 & t.$$.dirty[0] && o(32, a = C && getComputedStyle(C)),
        8 & t.$$.dirty[0] | 2 & t.$$.dirty[1] && o(33, s = a && Ul(a.getPropertyValue("--scrollable-feather-size"))),
        1611399172 & t.$$.dirty[0] | 12 & t.$$.dirty[1] && null != u && b && null != s && x) {
            const t = -1 * u / s
              , e = -(b[i] - x[i] - u) / s;
            o(18, F = na(1 - t, 0, 1)),
            o(19, z = na(1 - e, 0, 1)),
            o(20, L = s),
            o(4, O = `--scrollable-feather-start-opacity: ${F};--scrollable-feather-end-opacity: ${z}`)
        }
        134217736 & t.$$.dirty[0] && C && void 0 !== B && (qe(B) ? U(B) : U(B.scrollOffset, B)),
        1610612740 & t.$$.dirty[0] && o(35, l = b && x ? x[i] > b[i] : void 0),
        268435456 & t.$$.dirty[0] | 16 & t.$$.dirty[1] && o(5, c = rl([w, l ? "overflows" : void 0])),
        25 & t.$$.dirty[1] && o(6, d = l ? `transform: translate${r}(${u}px)` : void 0)
    }
    ,
    [k, P, x, C, O, c, d, $, S, ()=>{
        l && (H = !1,
        V = !0,
        N = Y(0, 0),
        v = !1,
        o(28, w = "idle"),
        y = dn(S))
    }
    , ({detail: t})=>{
        l && (v = !0,
        o(28, w = "idle"))
    }
    , ({detail: t})=>{
        l && (H || V && (V = !1,
        lt(t.translation) < .1) || (!I || "x" !== P || (t=>{
            const e = it(Y(t.x - N.x, t.y - N.y), Math.abs);
            N = Z(t);
            const o = lt(e)
              , i = e.x - e.y;
            return !(o > 1 && i < -.5)
        }
        )(t.translation) ? U(y + t.translation[P], {
            elastic: !0
        }) : H = !0))
    }
    , ({detail: t})=>{
        if (!l)
            return;
        if (H)
            return;
        const e = y + t.translation[P]
          , o = W(e);
        D = !1,
        S.set(o).then((t=>{
            v && (D = !0)
        }
        ))
    }
    , ({detail: t})=>{
        o(29, b = t),
        g("measure", {
            x: t.x,
            y: t.y,
            width: t.width,
            height: t.height
        })
    }
    , t=>{
        if (!l)
            return;
        t.preventDefault(),
        t.stopPropagation();
        const e = jl(t)
          , o = dn(S);
        U(o + e * T, {
            animate: !0
        })
    }
    , t=>{
        if (!l)
            return;
        if (!v)
            return;
        if (h.some((t=>f.includes(t))))
            return;
        let e = t.target;
        t.target.classList.contains("implicit") && (e = e.parentNode);
        const o = e["x" === P ? "offsetLeft" : "offsetTop"]
          , n = o + e["x" === P ? "offsetWidth" : "offsetHeight"]
          , r = dn(S)
          , a = R + L;
        r + o < a ? U(-o + a) : r + n > b[i] - a && U(b[i] - n - a, {
            animate: !0
        })
    }
    , ()=>{
        o(3, C["x" === P ? "scrollLeft" : "scrollTop"] = 0, C)
    }
    , ({detail: t})=>{
        const e = -2 * t[P]
          , o = dn(S);
        U(o + e * T, {
            animate: !0
        })
    }
    , F, z, L, M, T, R, I, A, E, B, w, b, i, r, a, s, u, l, m, p, t=>o(2, x = t.detail), function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            C = t,
            o(3, C)
        }
        ))
    }
    ]
}
class ql extends Br {
    constructor(t) {
        super(),
        zr(this, t, Gl, Yl, ln, {
            class: 0,
            scrollBlockInteractionDist: 21,
            scrollStep: 22,
            scrollFocusMargin: 23,
            scrollDirection: 1,
            scrollAutoCancel: 24,
            elasticity: 25,
            onscroll: 26,
            maskFeatherSize: 20,
            maskFeatherStartOpacity: 18,
            maskFeatherEndOpacity: 19,
            scroll: 27
        }, [-1, -1])
    }
}
function Zl(t, {delay: e=0, duration: o=400, easing: i=en}={}) {
    const n = +getComputedStyle(t).opacity;
    return {
        delay: e,
        duration: o,
        easing: i,
        css: t=>"opacity: " + t * n
    }
}
function Kl(t) {
    let e, o, i, n, r, a;
    return {
        c() {
            e = Rn("span"),
            o = In(t[0]),
            Bn(e, "class", "PinturaStatusMessage")
        },
        m(i, s) {
            Mn(i, e, s),
            kn(e, o),
            n = !0,
            r || (a = [Ln(e, "measure", (function() {
                sn(t[1]) && t[1].apply(this, arguments)
            }
            )), yn(bs.call(null, e))],
            r = !0)
        },
        p(e, [i]) {
            t = e,
            (!n || 1 & i) && Dn(o, t[0])
        },
        i(t) {
            n || (lr((()=>{
                i || (i = Sr(e, Zl, {}, !0)),
                i.run(1)
            }
            )),
            n = !0)
        },
        o(t) {
            i || (i = Sr(e, Zl, {}, !1)),
            i.run(0),
            n = !1
        },
        d(t) {
            t && Tn(e),
            t && i && i.end(),
            r = !1,
            an(a)
        }
    }
}
function Jl(t, e, o) {
    let {text: i} = e
      , {onmeasure: r=n} = e;
    return t.$$set = t=>{
        "text"in t && o(0, i = t.text),
        "onmeasure"in t && o(1, r = t.onmeasure)
    }
    ,
    [i, r]
}
class Ql extends Br {
    constructor(t) {
        super(),
        zr(this, t, Jl, Kl, ln, {
            text: 0,
            onmeasure: 1
        })
    }
}
function tc(t) {
    let e, o, i, n, r, a, s, l;
    return {
        c() {
            e = Rn("span"),
            o = Pn("svg"),
            i = Pn("g"),
            n = Pn("circle"),
            r = Pn("circle"),
            a = An(),
            s = Rn("span"),
            l = In(t[0]),
            Bn(n, "class", "PinturaProgressIndicatorBar"),
            Bn(n, "r", "8.5"),
            Bn(n, "cx", "10"),
            Bn(n, "cy", "10"),
            Bn(n, "stroke-linecap", "round"),
            Bn(n, "opacity", ".25"),
            Bn(r, "class", "PinturaProgressIndicatorFill"),
            Bn(r, "r", "8.5"),
            Bn(r, "stroke-dasharray", t[1]),
            Bn(r, "cx", "10"),
            Bn(r, "cy", "10"),
            Bn(r, "transform", "rotate(-90) translate(-20)"),
            Bn(i, "fill", "none"),
            Bn(i, "stroke", "currentColor"),
            Bn(i, "stroke-width", "2.5"),
            Bn(i, "stroke-linecap", "round"),
            Bn(i, "opacity", t[2]),
            Bn(o, "width", "20"),
            Bn(o, "height", "20"),
            Bn(o, "viewBox", "0 0 20 20"),
            Bn(o, "xmlns", "http://www.w3.org/2000/svg"),
            Bn(o, "aria-hidden", "true"),
            Bn(o, "focusable", "false"),
            Bn(s, "class", "implicit"),
            Bn(e, "class", "PinturaProgressIndicator"),
            Bn(e, "data-status", t[3])
        },
        m(t, c) {
            Mn(t, e, c),
            kn(e, o),
            kn(o, i),
            kn(i, n),
            kn(i, r),
            kn(e, a),
            kn(e, s),
            kn(s, l)
        },
        p(t, [o]) {
            2 & o && Bn(r, "stroke-dasharray", t[1]),
            4 & o && Bn(i, "opacity", t[2]),
            1 & o && Dn(l, t[0]),
            8 & o && Bn(e, "data-status", t[3])
        },
        i: tn,
        o: tn,
        d(t) {
            t && Tn(e)
        }
    }
}
function ec(t, e, o) {
    let i, n, r, a, s;
    const l = Jn();
    let {progress: c} = e
      , {min: d=0} = e
      , {max: u=100} = e
      , {labelBusy: h="Busy"} = e;
    const p = ls(0, {
        precision: .01
    })
      , m = Wr([p], (t=>na(t, d, u)));
    un(t, m, (t=>o(9, s = t)));
    const g = m.subscribe((t=>{
        1 === c && Math.round(t) >= 100 && l("complete")
    }
    ));
    return Kn((()=>{
        g()
    }
    )),
    t.$$set = t=>{
        "progress"in t && o(5, c = t.progress),
        "min"in t && o(6, d = t.min),
        "max"in t && o(7, u = t.max),
        "labelBusy"in t && o(8, h = t.labelBusy)
    }
    ,
    t.$$.update = ()=>{
        32 & t.$$.dirty && c && c !== 1 / 0 && p.set(100 * c),
        800 & t.$$.dirty && o(0, i = c === 1 / 0 ? h : Math.round(s) + "%"),
        544 & t.$$.dirty && o(1, n = c === 1 / 0 ? "26.5 53" : s / 100 * 53 + " 53"),
        544 & t.$$.dirty && o(2, r = Math.min(1, c === 1 / 0 ? 1 : s / 10)),
        32 & t.$$.dirty && o(3, a = c === 1 / 0 ? "busy" : "loading")
    }
    ,
    [i, n, r, a, m, c, d, u, h, s]
}
class oc extends Br {
    constructor(t) {
        super(),
        zr(this, t, ec, tc, ln, {
            progress: 5,
            min: 6,
            max: 7,
            labelBusy: 8
        })
    }
}
function ic(t) {
    let e, o, i;
    const n = t[5].default
      , r = hn(n, t, t[4], null);
    return {
        c() {
            e = Rn("span"),
            r && r.c(),
            Bn(e, "class", o = "PinturaStatusAside " + t[0]),
            Bn(e, "style", t[1])
        },
        m(t, o) {
            Mn(t, e, o),
            r && r.m(e, null),
            i = !0
        },
        p(t, [a]) {
            r && r.p && 16 & a && mn(r, n, t, t[4], a, null, null),
            (!i || 1 & a && o !== (o = "PinturaStatusAside " + t[0])) && Bn(e, "class", o),
            (!i || 2 & a) && Bn(e, "style", t[1])
        },
        i(t) {
            i || (br(r, t),
            i = !0)
        },
        o(t) {
            vr(r, t),
            i = !1
        },
        d(t) {
            t && Tn(e),
            r && r.d(t)
        }
    }
}
function nc(t, e, o) {
    let i, {$$slots: n={}, $$scope: r} = e, {offset: a=0} = e, {opacity: s=0} = e, {class: l} = e;
    return t.$$set = t=>{
        "offset"in t && o(2, a = t.offset),
        "opacity"in t && o(3, s = t.opacity),
        "class"in t && o(0, l = t.class),
        "$$scope"in t && o(4, r = t.$$scope)
    }
    ,
    t.$$.update = ()=>{
        12 & t.$$.dirty && o(1, i = `transform:translateX(${a}px);opacity:${s}`)
    }
    ,
    [l, i, a, s, r, n]
}
class rc extends Br {
    constructor(t) {
        super(),
        zr(this, t, nc, ic, ln, {
            offset: 2,
            opacity: 3,
            class: 0
        })
    }
}
function ac(t) {
    let e, o, i;
    const n = t[3].default
      , r = hn(n, t, t[2], null);
    let a = [{
        for: o = "_"
    }, t[1]]
      , s = {};
    for (let t = 0; t < a.length; t += 1)
        s = on(s, a[t]);
    return {
        c() {
            e = Rn("label"),
            r && r.c(),
            On(e, s)
        },
        m(t, o) {
            Mn(t, e, o),
            r && r.m(e, null),
            i = !0
        },
        p(t, o) {
            r && r.p && 4 & o && mn(r, n, t, t[2], o, null, null),
            On(e, s = Rr(a, [{
                for: "_"
            }, 2 & o && t[1]]))
        },
        i(t) {
            i || (br(r, t),
            i = !0)
        },
        o(t) {
            vr(r, t),
            i = !1
        },
        d(t) {
            t && Tn(e),
            r && r.d(t)
        }
    }
}
function sc(t) {
    let e, o;
    const i = t[3].default
      , n = hn(i, t, t[2], null);
    let r = [t[1]]
      , a = {};
    for (let t = 0; t < r.length; t += 1)
        a = on(a, r[t]);
    return {
        c() {
            e = Rn("div"),
            n && n.c(),
            On(e, a)
        },
        m(t, i) {
            Mn(t, e, i),
            n && n.m(e, null),
            o = !0
        },
        p(t, o) {
            n && n.p && 4 & o && mn(n, i, t, t[2], o, null, null),
            On(e, a = Rr(r, [2 & o && t[1]]))
        },
        i(t) {
            o || (br(n, t),
            o = !0)
        },
        o(t) {
            vr(n, t),
            o = !1
        },
        d(t) {
            t && Tn(e),
            n && n.d(t)
        }
    }
}
function lc(t) {
    let e, o;
    const i = t[3].default
      , n = hn(i, t, t[2], null);
    let r = [t[1]]
      , a = {};
    for (let t = 0; t < r.length; t += 1)
        a = on(a, r[t]);
    return {
        c() {
            e = Rn("div"),
            n && n.c(),
            On(e, a)
        },
        m(t, i) {
            Mn(t, e, i),
            n && n.m(e, null),
            o = !0
        },
        p(t, o) {
            n && n.p && 4 & o && mn(n, i, t, t[2], o, null, null),
            On(e, a = Rr(r, [2 & o && t[1]]))
        },
        i(t) {
            o || (br(n, t),
            o = !0)
        },
        o(t) {
            vr(n, t),
            o = !1
        },
        d(t) {
            t && Tn(e),
            n && n.d(t)
        }
    }
}
function cc(t) {
    let e, o, i, n;
    const r = [lc, sc, ac]
      , a = [];
    function s(t, e) {
        return "div" === t[0] ? 0 : "span" === t[0] ? 1 : "label" === t[0] ? 2 : -1
    }
    return ~(e = s(t)) && (o = a[e] = r[e](t)),
    {
        c() {
            o && o.c(),
            i = En()
        },
        m(t, o) {
            ~e && a[e].m(t, o),
            Mn(t, i, o),
            n = !0
        },
        p(t, [n]) {
            let l = e;
            e = s(t),
            e === l ? ~e && a[e].p(t, n) : (o && (yr(),
            vr(a[l], 1, 1, (()=>{
                a[l] = null
            }
            )),
            xr()),
            ~e ? (o = a[e],
            o ? o.p(t, n) : (o = a[e] = r[e](t),
            o.c()),
            br(o, 1),
            o.m(i.parentNode, i)) : o = null)
        },
        i(t) {
            n || (br(o),
            n = !0)
        },
        o(t) {
            vr(o),
            n = !1
        },
        d(t) {
            ~e && a[e].d(t),
            t && Tn(i)
        }
    }
}
function dc(t, e, o) {
    let {$$slots: i={}, $$scope: n} = e
      , {name: r="div"} = e
      , {attributes: a={}} = e;
    return t.$$set = t=>{
        "name"in t && o(0, r = t.name),
        "attributes"in t && o(1, a = t.attributes),
        "$$scope"in t && o(2, n = t.$$scope)
    }
    ,
    [r, a, n, i]
}
class uc extends Br {
    constructor(t) {
        super(),
        zr(this, t, dc, cc, ln, {
            name: 0,
            attributes: 1
        })
    }
}
var hc = ()=>c() && window.devicePixelRatio || 1;
let pc = null;
var mc = t=>(null === pc && (pc = 1 === hc() ? Math.round : t=>t),
pc(t));
const gc = t=>({})
  , fc = t=>({})
  , $c = t=>({})
  , yc = t=>({});
function xc(t) {
    let e;
    const o = t[35].label
      , i = hn(o, t, t[39], yc);
    return {
        c() {
            i && i.c()
        },
        m(t, o) {
            i && i.m(t, o),
            e = !0
        },
        p(t, e) {
            i && i.p && 256 & e[1] && mn(i, o, t, t[39], e, $c, yc)
        },
        i(t) {
            e || (br(i, t),
            e = !0)
        },
        o(t) {
            vr(i, t),
            e = !1
        },
        d(t) {
            i && i.d(t)
        }
    }
}
function bc(t) {
    let e, o, i, n, r, a, s;
    const l = t[35].details
      , c = hn(l, t, t[39], fc);
    return {
        c() {
            e = Rn("div"),
            c && c.c(),
            o = An(),
            i = Rn("span"),
            Bn(i, "class", "PinturaDetailsPanelTip"),
            Bn(i, "style", t[7]),
            Bn(e, "class", n = rl(["PinturaDetailsPanel", t[1]])),
            Bn(e, "tabindex", "-1"),
            Bn(e, "style", t[6])
        },
        m(n, l) {
            Mn(n, e, l),
            c && c.m(e, null),
            kn(e, o),
            kn(e, i),
            t[37](e),
            r = !0,
            a || (s = [Ln(e, "keydown", t[17]), Ln(e, "measure", t[38]), yn(bs.call(null, e))],
            a = !0)
        },
        p(t, o) {
            c && c.p && 256 & o[1] && mn(c, l, t, t[39], o, gc, fc),
            (!r || 128 & o[0]) && Bn(i, "style", t[7]),
            (!r || 2 & o[0] && n !== (n = rl(["PinturaDetailsPanel", t[1]]))) && Bn(e, "class", n),
            (!r || 64 & o[0]) && Bn(e, "style", t[6])
        },
        i(t) {
            r || (br(c, t),
            r = !0)
        },
        o(t) {
            vr(c, t),
            r = !1
        },
        d(o) {
            o && Tn(e),
            c && c.d(o),
            t[37](null),
            a = !1,
            an(s)
        }
    }
}
function vc(t) {
    let e, o, i, n, r, a, s, l, c = {
        class: rl(["PinturaDetailsButton", t[0]]),
        onkeydown: t[16],
        onclick: t[15],
        $$slots: {
            default: [xc]
        },
        $$scope: {
            ctx: t
        }
    };
    o = new Dl({
        props: c
    }),
    t[36](o);
    let d = t[5] && bc(t);
    return {
        c() {
            e = An(),
            Ar(o.$$.fragment),
            i = An(),
            d && d.c(),
            n = An(),
            r = En()
        },
        m(c, u) {
            Mn(c, e, u),
            Er(o, c, u),
            Mn(c, i, u),
            d && d.m(c, u),
            Mn(c, n, u),
            Mn(c, r, u),
            a = !0,
            s || (l = [Ln(document.body, "pointerdown", (function() {
                sn(t[8]) && t[8].apply(this, arguments)
            }
            )), Ln(document.body, "pointerup", (function() {
                sn(t[9]) && t[9].apply(this, arguments)
            }
            ))],
            s = !0)
        },
        p(e, i) {
            t = e;
            const r = {};
            1 & i[0] && (r.class = rl(["PinturaDetailsButton", t[0]])),
            256 & i[1] && (r.$$scope = {
                dirty: i,
                ctx: t
            }),
            o.$set(r),
            t[5] ? d ? (d.p(t, i),
            32 & i[0] && br(d, 1)) : (d = bc(t),
            d.c(),
            br(d, 1),
            d.m(n.parentNode, n)) : d && (yr(),
            vr(d, 1, 1, (()=>{
                d = null
            }
            )),
            xr())
        },
        i(t) {
            a || (br(o.$$.fragment, t),
            br(d),
            br(false),
            a = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            vr(d),
            vr(false),
            a = !1
        },
        d(a) {
            a && Tn(e),
            t[36](null),
            Lr(o, a),
            a && Tn(i),
            d && d.d(a),
            a && Tn(n),
            a && Tn(r),
            s = !1,
            an(l)
        }
    }
}
function wc(t, e, o) {
    let i, n, r, a, s, l, c, d, u, h, p, m, g, f, $, y, {$$slots: x={}, $$scope: b} = e, {buttonClass: v} = e, {panelClass: w} = e, {isActive: S=!1} = e, {onshow: C=(({panel: t})=>t.focus())} = e;
    const k = tr("rootPortal");
    un(t, k, (t=>o(34, y = t)));
    const M = tr("rootRect");
    let T, R, P;
    un(t, M, (t=>o(27, g = t)));
    let I = X()
      , A = ls(0);
    un(t, A, (t=>o(29, $ = t)));
    let E = X();
    const L = _r({
        x: 0,
        y: 0
    });
    un(t, L, (t=>o(28, f = t)));
    const F = ls(-5, {
        stiffness: .1,
        damping: .35,
        precision: .001
    });
    un(t, F, (t=>o(26, m = t)));
    const z = t=>El(t, y) || R.isEventTarget(t);
    let B, O, D = !1;
    Kn((()=>{
        y && B && !B.parentNode && y.removeChild(B)
    }
    ));
    return t.$$set = t=>{
        "buttonClass"in t && o(0, v = t.buttonClass),
        "panelClass"in t && o(1, w = t.panelClass),
        "isActive"in t && o(18, S = t.isActive),
        "onshow"in t && o(19, C = t.onshow),
        "$$scope"in t && o(39, b = t.$$scope)
    }
    ,
    t.$$.update = ()=>{
        if (8 & t.$$.dirty[0] && (i = R && R.getElement()),
        8650752 & t.$$.dirty[0] && o(9, p = S ? t=>{
            D && (o(23, D = !1),
            z(t) || o(18, S = !1))
        }
        : void 0),
        262144 & t.$$.dirty[0] && A.set(S ? 1 : 0),
        262144 & t.$$.dirty[0] && F.set(S ? 0 : -5),
        67108864 & t.$$.dirty[0] && o(25, n = 1 - m / -5),
        135266308 & t.$$.dirty[0] && g && T && P) {
            let t = P.x - g.x + .5 * P.width - .5 * T.width
              , e = P.y - g.y + P.height;
            const i = 12
              , n = 12
              , r = g.width - 12
              , a = g.height - 12
              , s = t
              , l = e
              , c = s + T.width
              , d = l + T.height;
            if (s < i && (o(22, E.x = s - i, E),
            t = i),
            c > r && (o(22, E.x = c - r, E),
            t = r - T.width),
            d > a) {
                o(21, I.y = -1, I);
                n < e - T.height - P.height ? (o(22, E.y = 0, E),
                e -= T.height + P.height) : (o(22, E.y = e - (d - a), E),
                e -= d - a)
            } else
                o(21, I.y = 1, I);
            $n(L, f = it(Y(t, e), mc), f)
        }
        536870912 & t.$$.dirty[0] && o(5, r = $ > 0),
        536870912 & t.$$.dirty[0] && o(30, a = $ < 1),
        337641472 & t.$$.dirty[0] && o(31, s = `translateX(${f.x + 12 * I.x}px) translateY(${f.y + 12 * I.y + I.y * m}px)`),
        1610612736 & t.$$.dirty[0] | 1 & t.$$.dirty[1] && o(6, l = a ? `opacity: ${$}; pointer-events: ${$ < 1 ? "none" : "all"}; transform: ${s};` : "transform: " + s),
        33554432 & t.$$.dirty[0] && o(32, c = .5 + .5 * n),
        33554432 & t.$$.dirty[0] && o(33, d = n),
        274726916 & t.$$.dirty[0] | 6 & t.$$.dirty[1] && o(7, u = f && T && `opacity:${d};transform:scaleX(${c})rotate(45deg);top:${I.y < 0 ? E.y + T.height : 0}px;left:${E.x + .5 * T.width}px`),
        262144 & t.$$.dirty[0] && o(8, h = S ? t=>{
            z(t) || o(23, D = !0)
        }
        : void 0),
        48 & t.$$.dirty[0] | 8 & t.$$.dirty[1] && r && y && B && B.parentNode !== y && y.appendChild(B),
        262144 & t.$$.dirty[0] && (S || o(24, O = void 0)),
        17301552 & t.$$.dirty[0] && r && B && C({
            e: O,
            panel: B
        })
    }
    ,
    [v, w, T, R, B, r, l, u, h, p, k, M, A, L, F, t=>{
        S || o(20, P = i.getBoundingClientRect()),
        o(24, O = t),
        o(18, S = !S)
    }
    , t=>{
        /down/i.test(t.key) && (o(18, S = !0),
        o(24, O = t))
    }
    , t=>{
        /esc/i.test(t.key) && (o(18, S = !1),
        i.focus())
    }
    , S, C, P, I, E, D, O, n, m, g, f, $, a, s, c, d, y, x, function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            R = t,
            o(3, R)
        }
        ))
    }
    , function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            B = t,
            o(4, B)
        }
        ))
    }
    , t=>o(2, T = gt(t.detail)), b]
}
class Sc extends Br {
    constructor(t) {
        super(),
        zr(this, t, wc, vc, ln, {
            buttonClass: 0,
            panelClass: 1,
            isActive: 18,
            onshow: 19
        }, [-1, -1])
    }
}
function Cc(t) {
    let e, o, i, n, r, a, s, l;
    const c = t[14].default
      , d = hn(c, t, t[13], null);
    return {
        c() {
            e = Rn("li"),
            o = Rn("input"),
            i = An(),
            n = Rn("label"),
            d && d.c(),
            Bn(o, "type", "radio"),
            Bn(o, "class", "implicit"),
            Bn(o, "id", t[6]),
            Bn(o, "name", t[0]),
            o.value = t[3],
            o.disabled = t[5],
            o.checked = t[4],
            Bn(n, "for", t[6]),
            Bn(n, "title", t[2]),
            Bn(e, "class", r = rl(["PinturaRadioGroupOption", t[1]])),
            Bn(e, "data-disabled", t[5]),
            Bn(e, "data-selected", t[4])
        },
        m(r, c) {
            Mn(r, e, c),
            kn(e, o),
            kn(e, i),
            kn(e, n),
            d && d.m(n, null),
            a = !0,
            s || (l = [Ln(o, "change", zn(t[15])), Ln(o, "keydown", t[8]), Ln(o, "click", t[9])],
            s = !0)
        },
        p(t, [i]) {
            (!a || 64 & i) && Bn(o, "id", t[6]),
            (!a || 1 & i) && Bn(o, "name", t[0]),
            (!a || 8 & i) && (o.value = t[3]),
            (!a || 32 & i) && (o.disabled = t[5]),
            (!a || 16 & i) && (o.checked = t[4]),
            d && d.p && 8192 & i && mn(d, c, t, t[13], i, null, null),
            (!a || 64 & i) && Bn(n, "for", t[6]),
            (!a || 4 & i) && Bn(n, "title", t[2]),
            (!a || 2 & i && r !== (r = rl(["PinturaRadioGroupOption", t[1]]))) && Bn(e, "class", r),
            (!a || 32 & i) && Bn(e, "data-disabled", t[5]),
            (!a || 16 & i) && Bn(e, "data-selected", t[4])
        },
        i(t) {
            a || (br(d, t),
            a = !0)
        },
        o(t) {
            vr(d, t),
            a = !1
        },
        d(t) {
            t && Tn(e),
            d && d.d(t),
            s = !1,
            an(l)
        }
    }
}
function kc(t, e, o) {
    let i, n, {$$slots: r={}, $$scope: a} = e, {name: s} = e, {class: l} = e, {label: c} = e, {id: d} = e, {value: u} = e, {checked: h} = e, {onkeydown: p} = e, {onclick: m} = e, {disabled: g=!1} = e;
    const f = Object.values(Xl)
      , $ = tr("keysPressed");
    un(t, $, (t=>o(16, n = t)));
    return t.$$set = t=>{
        "name"in t && o(0, s = t.name),
        "class"in t && o(1, l = t.class),
        "label"in t && o(2, c = t.label),
        "id"in t && o(10, d = t.id),
        "value"in t && o(3, u = t.value),
        "checked"in t && o(4, h = t.checked),
        "onkeydown"in t && o(11, p = t.onkeydown),
        "onclick"in t && o(12, m = t.onclick),
        "disabled"in t && o(5, g = t.disabled),
        "$$scope"in t && o(13, a = t.$$scope)
    }
    ,
    t.$$.update = ()=>{
        1025 & t.$$.dirty && o(6, i = `${s}-${d}`)
    }
    ,
    [s, l, c, u, h, g, i, $, t=>{
        p(t)
    }
    , t=>{
        n.some((t=>f.includes(t))) || m(t)
    }
    , d, p, m, a, r, function(e) {
        er(t, e)
    }
    ]
}
class Mc extends Br {
    constructor(t) {
        super(),
        zr(this, t, kc, Cc, ln, {
            name: 0,
            class: 1,
            label: 2,
            id: 10,
            value: 3,
            checked: 4,
            onkeydown: 11,
            onclick: 12,
            disabled: 5
        })
    }
}
var Tc = (t=[])=>t.reduce(((t,e)=>(Je(e) ? Je(e[1]) : !!e.options) ? t.concat(Je(e) ? e[1] : e.options) : (t.push(e),
t)), []);
const Rc = (t,e,o)=>{
    let i;
    return Je(t) ? i = {
        id: e,
        value: t[0],
        label: t[1],
        ...t[2] || {}
    } : (i = t,
    i.id = null != i.id ? i.id : e),
    o ? o(i) : i
}
;
var Pc = (t,e,o)=>S(t) ? t(e, o) : t;
const Ic = (t,e)=>t.map((([t,o,i])=>{
    if (Je(o))
        return [Pc(t, e), Ic(o, e)];
    {
        const n = [t, Pc(o, e)];
        if (i) {
            let t = {
                ...i
            };
            i.icon && (t.icon = Pc(i.icon, e)),
            n.push(t)
        }
        return n
    }
}
));
var Ac = (t,e)=>Ic(t, e)
  , Ec = t=>/enter| /i.test(t)
  , Lc = (t,e)=>Array.isArray(t) && Array.isArray(e) ? la(t, e) : t === e;
function Fc(t, e, o) {
    const i = t.slice();
    return i[27] = e[o],
    i
}
const zc = t=>({
    option: 2048 & t[0]
})
  , Bc = t=>({
    option: t[27]
});
function Oc(t, e, o) {
    const i = t.slice();
    return i[27] = e[o],
    i
}
const Dc = t=>({
    option: 2048 & t[0]
})
  , _c = t=>({
    option: t[27]
})
  , Wc = t=>({
    option: 2048 & t[0]
})
  , Vc = t=>({
    option: t[27]
});
function Hc(t) {
    let e, o, i, n, r, a = [], s = new Map, l = t[1] && Nc(t), c = t[11];
    const d = t=>t[27].id;
    for (let e = 0; e < c.length; e += 1) {
        let o = Fc(t, c, e)
          , i = d(o);
        s.set(i, a[e] = ed(i, o))
    }
    return {
        c() {
            e = Rn("fieldset"),
            l && l.c(),
            o = An(),
            i = Rn("ul");
            for (let t = 0; t < a.length; t += 1)
                a[t].c();
            Bn(i, "class", "PinturaRadioGroupOptions"),
            Bn(e, "class", n = rl(["PinturaRadioGroup", t[3]])),
            Bn(e, "data-layout", t[5]),
            Bn(e, "title", t[7])
        },
        m(t, n) {
            Mn(t, e, n),
            l && l.m(e, null),
            kn(e, o),
            kn(e, i);
            for (let t = 0; t < a.length; t += 1)
                a[t].m(i, null);
            r = !0
        },
        p(t, u) {
            t[1] ? l ? l.p(t, u) : (l = Nc(t),
            l.c(),
            l.m(e, o)) : l && (l.d(1),
            l = null),
            8420177 & u[0] && (c = t[11],
            yr(),
            a = Tr(a, u, d, 1, t, c, s, i, Mr, ed, null, Fc),
            xr()),
            (!r || 8 & u[0] && n !== (n = rl(["PinturaRadioGroup", t[3]]))) && Bn(e, "class", n),
            (!r || 32 & u[0]) && Bn(e, "data-layout", t[5]),
            (!r || 128 & u[0]) && Bn(e, "title", t[7])
        },
        i(t) {
            if (!r) {
                for (let t = 0; t < c.length; t += 1)
                    br(a[t]);
                r = !0
            }
        },
        o(t) {
            for (let t = 0; t < a.length; t += 1)
                vr(a[t]);
            r = !1
        },
        d(t) {
            t && Tn(e),
            l && l.d();
            for (let t = 0; t < a.length; t += 1)
                a[t].d()
        }
    }
}
function Nc(t) {
    let e, o, i;
    return {
        c() {
            e = Rn("legend"),
            o = In(t[1]),
            Bn(e, "class", i = t[2] && "implicit")
        },
        m(t, i) {
            Mn(t, e, i),
            kn(e, o)
        },
        p(t, n) {
            2 & n[0] && Dn(o, t[1]),
            4 & n[0] && i !== (i = t[2] && "implicit") && Bn(e, "class", i)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function Uc(t) {
    let e, o;
    return e = new Mc({
        props: {
            name: t[4],
            label: t[27].label,
            id: t[27].id,
            value: t[27].value,
            disabled: t[27].disabled,
            class: t[8],
            checked: t[12](t[27]) === t[0],
            onkeydown: t[13](t[27]),
            onclick: t[14](t[27]),
            $$slots: {
                default: [qc]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            16 & o[0] && (i.name = t[4]),
            2048 & o[0] && (i.label = t[27].label),
            2048 & o[0] && (i.id = t[27].id),
            2048 & o[0] && (i.value = t[27].value),
            2048 & o[0] && (i.disabled = t[27].disabled),
            256 & o[0] && (i.class = t[8]),
            2049 & o[0] && (i.checked = t[12](t[27]) === t[0]),
            2048 & o[0] && (i.onkeydown = t[13](t[27])),
            2048 & o[0] && (i.onclick = t[14](t[27])),
            8390720 & o[0] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function jc(t) {
    let e, o, i, n, r, a, s = [], l = new Map;
    const c = t[22].group
      , d = hn(c, t, t[23], Vc)
      , u = d || function(t) {
        let e, o, i = t[27].label + "";
        return {
            c() {
                e = Rn("span"),
                o = In(i),
                Bn(e, "class", "PinturaRadioGroupOptionGroupLabel")
            },
            m(t, i) {
                Mn(t, e, i),
                kn(e, o)
            },
            p(t, e) {
                2048 & e[0] && i !== (i = t[27].label + "") && Dn(o, i)
            },
            d(t) {
                t && Tn(e)
            }
        }
    }(t);
    let h = t[27].options;
    const p = t=>t[27].id;
    for (let e = 0; e < h.length; e += 1) {
        let o = Oc(t, h, e)
          , i = p(o);
        l.set(i, s[e] = td(i, o))
    }
    return {
        c() {
            e = Rn("li"),
            u && u.c(),
            o = An(),
            i = Rn("ul");
            for (let t = 0; t < s.length; t += 1)
                s[t].c();
            n = An(),
            Bn(i, "class", "PinturaRadioGroupOptions"),
            Bn(e, "class", r = rl(["PinturaRadioGroupOptionGroup", t[9]]))
        },
        m(t, r) {
            Mn(t, e, r),
            u && u.m(e, null),
            kn(e, o),
            kn(e, i);
            for (let t = 0; t < s.length; t += 1)
                s[t].m(i, null);
            kn(e, n),
            a = !0
        },
        p(t, o) {
            d ? d.p && 8390656 & o[0] && mn(d, c, t, t[23], o, Wc, Vc) : u && u.p && 2048 & o[0] && u.p(t, o),
            8419665 & o[0] && (h = t[27].options,
            yr(),
            s = Tr(s, o, p, 1, t, h, l, i, Mr, td, null, Oc),
            xr()),
            (!a || 512 & o[0] && r !== (r = rl(["PinturaRadioGroupOptionGroup", t[9]]))) && Bn(e, "class", r)
        },
        i(t) {
            if (!a) {
                br(u, t);
                for (let t = 0; t < h.length; t += 1)
                    br(s[t]);
                a = !0
            }
        },
        o(t) {
            vr(u, t);
            for (let t = 0; t < s.length; t += 1)
                vr(s[t]);
            a = !1
        },
        d(t) {
            t && Tn(e),
            u && u.d(t);
            for (let t = 0; t < s.length; t += 1)
                s[t].d()
        }
    }
}
function Xc(t) {
    let e, o;
    return e = new Al({
        props: {
            $$slots: {
                default: [Yc]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            8390656 & o[0] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Yc(t) {
    let e, o = t[27].icon + "";
    return {
        c() {
            e = Pn("g")
        },
        m(t, i) {
            Mn(t, e, i),
            e.innerHTML = o
        },
        p(t, i) {
            2048 & i[0] && o !== (o = t[27].icon + "") && (e.innerHTML = o)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function Gc(t) {
    let e, o, i = t[27].label + "";
    return {
        c() {
            e = Rn("span"),
            o = In(i),
            Bn(e, "class", t[6])
        },
        m(t, i) {
            Mn(t, e, i),
            kn(e, o)
        },
        p(t, n) {
            2048 & n[0] && i !== (i = t[27].label + "") && Dn(o, i),
            64 & n[0] && Bn(e, "class", t[6])
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function qc(t) {
    let e;
    const o = t[22].option
      , i = hn(o, t, t[23], Bc)
      , n = i || function(t) {
        let e, o, i, n = t[27].icon && Xc(t), r = !t[27].hideLabel && Gc(t);
        return {
            c() {
                n && n.c(),
                e = An(),
                r && r.c(),
                o = An()
            },
            m(t, a) {
                n && n.m(t, a),
                Mn(t, e, a),
                r && r.m(t, a),
                Mn(t, o, a),
                i = !0
            },
            p(t, i) {
                t[27].icon ? n ? (n.p(t, i),
                2048 & i[0] && br(n, 1)) : (n = Xc(t),
                n.c(),
                br(n, 1),
                n.m(e.parentNode, e)) : n && (yr(),
                vr(n, 1, 1, (()=>{
                    n = null
                }
                )),
                xr()),
                t[27].hideLabel ? r && (r.d(1),
                r = null) : r ? r.p(t, i) : (r = Gc(t),
                r.c(),
                r.m(o.parentNode, o))
            },
            i(t) {
                i || (br(n),
                i = !0)
            },
            o(t) {
                vr(n),
                i = !1
            },
            d(t) {
                n && n.d(t),
                t && Tn(e),
                r && r.d(t),
                t && Tn(o)
            }
        }
    }(t);
    return {
        c() {
            n && n.c()
        },
        m(t, o) {
            n && n.m(t, o),
            e = !0
        },
        p(t, e) {
            i ? i.p && 8390656 & e[0] && mn(i, o, t, t[23], e, zc, Bc) : n && n.p && 2112 & e[0] && n.p(t, e)
        },
        i(t) {
            e || (br(n, t),
            e = !0)
        },
        o(t) {
            vr(n, t),
            e = !1
        },
        d(t) {
            n && n.d(t)
        }
    }
}
function Zc(t) {
    let e, o;
    return e = new Al({
        props: {
            $$slots: {
                default: [Kc]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            8390656 & o[0] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Kc(t) {
    let e, o = t[27].icon + "";
    return {
        c() {
            e = Pn("g")
        },
        m(t, i) {
            Mn(t, e, i),
            e.innerHTML = o
        },
        p(t, i) {
            2048 & i[0] && o !== (o = t[27].icon + "") && (e.innerHTML = o)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function Jc(t) {
    let e, o, i = t[27].label + "";
    return {
        c() {
            e = Rn("span"),
            o = In(i),
            Bn(e, "class", t[6])
        },
        m(t, i) {
            Mn(t, e, i),
            kn(e, o)
        },
        p(t, n) {
            2048 & n[0] && i !== (i = t[27].label + "") && Dn(o, i),
            64 & n[0] && Bn(e, "class", t[6])
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function Qc(t) {
    let e;
    const o = t[22].option
      , i = hn(o, t, t[23], _c)
      , n = i || function(t) {
        let e, o, i, n = t[27].icon && Zc(t), r = !t[27].hideLabel && Jc(t);
        return {
            c() {
                n && n.c(),
                e = An(),
                r && r.c(),
                o = An()
            },
            m(t, a) {
                n && n.m(t, a),
                Mn(t, e, a),
                r && r.m(t, a),
                Mn(t, o, a),
                i = !0
            },
            p(t, i) {
                t[27].icon ? n ? (n.p(t, i),
                2048 & i[0] && br(n, 1)) : (n = Zc(t),
                n.c(),
                br(n, 1),
                n.m(e.parentNode, e)) : n && (yr(),
                vr(n, 1, 1, (()=>{
                    n = null
                }
                )),
                xr()),
                t[27].hideLabel ? r && (r.d(1),
                r = null) : r ? r.p(t, i) : (r = Jc(t),
                r.c(),
                r.m(o.parentNode, o))
            },
            i(t) {
                i || (br(n),
                i = !0)
            },
            o(t) {
                vr(n),
                i = !1
            },
            d(t) {
                n && n.d(t),
                t && Tn(e),
                r && r.d(t),
                t && Tn(o)
            }
        }
    }(t);
    return {
        c() {
            n && n.c()
        },
        m(t, o) {
            n && n.m(t, o),
            e = !0
        },
        p(t, e) {
            i ? i.p && 8390656 & e[0] && mn(i, o, t, t[23], e, Dc, _c) : n && n.p && 2112 & e[0] && n.p(t, e)
        },
        i(t) {
            e || (br(n, t),
            e = !0)
        },
        o(t) {
            vr(n, t),
            e = !1
        },
        d(t) {
            n && n.d(t)
        }
    }
}
function td(t, e) {
    let o, i, n;
    return i = new Mc({
        props: {
            name: e[4],
            label: e[27].label,
            id: e[27].id,
            value: e[27].value,
            disabled: e[27].disabled,
            class: e[8],
            checked: e[12](e[27]) === e[0],
            onkeydown: e[13](e[27]),
            onclick: e[14](e[27]),
            $$slots: {
                default: [Qc]
            },
            $$scope: {
                ctx: e
            }
        }
    }),
    {
        key: t,
        first: null,
        c() {
            o = En(),
            Ar(i.$$.fragment),
            this.first = o
        },
        m(t, e) {
            Mn(t, o, e),
            Er(i, t, e),
            n = !0
        },
        p(t, o) {
            e = t;
            const n = {};
            16 & o[0] && (n.name = e[4]),
            2048 & o[0] && (n.label = e[27].label),
            2048 & o[0] && (n.id = e[27].id),
            2048 & o[0] && (n.value = e[27].value),
            2048 & o[0] && (n.disabled = e[27].disabled),
            256 & o[0] && (n.class = e[8]),
            2049 & o[0] && (n.checked = e[12](e[27]) === e[0]),
            2048 & o[0] && (n.onkeydown = e[13](e[27])),
            2048 & o[0] && (n.onclick = e[14](e[27])),
            8390720 & o[0] && (n.$$scope = {
                dirty: o,
                ctx: e
            }),
            i.$set(n)
        },
        i(t) {
            n || (br(i.$$.fragment, t),
            n = !0)
        },
        o(t) {
            vr(i.$$.fragment, t),
            n = !1
        },
        d(t) {
            t && Tn(o),
            Lr(i, t)
        }
    }
}
function ed(t, e) {
    let o, i, n, r, a;
    const s = [jc, Uc]
      , l = [];
    function c(t, e) {
        return t[27].options ? 0 : 1
    }
    return i = c(e),
    n = l[i] = s[i](e),
    {
        key: t,
        first: null,
        c() {
            o = En(),
            n.c(),
            r = En(),
            this.first = o
        },
        m(t, e) {
            Mn(t, o, e),
            l[i].m(t, e),
            Mn(t, r, e),
            a = !0
        },
        p(t, o) {
            let a = i;
            i = c(e = t),
            i === a ? l[i].p(e, o) : (yr(),
            vr(l[a], 1, 1, (()=>{
                l[a] = null
            }
            )),
            xr(),
            n = l[i],
            n ? n.p(e, o) : (n = l[i] = s[i](e),
            n.c()),
            br(n, 1),
            n.m(r.parentNode, r))
        },
        i(t) {
            a || (br(n),
            a = !0)
        },
        o(t) {
            vr(n),
            a = !1
        },
        d(t) {
            t && Tn(o),
            l[i].d(t),
            t && Tn(r)
        }
    }
}
function od(t) {
    let e, o, i, n = t[10].length && Hc(t);
    return {
        c() {
            n && n.c(),
            e = An(),
            o = En()
        },
        m(t, r) {
            n && n.m(t, r),
            Mn(t, e, r),
            Mn(t, o, r),
            i = !0
        },
        p(t, o) {
            t[10].length ? n ? (n.p(t, o),
            1024 & o[0] && br(n, 1)) : (n = Hc(t),
            n.c(),
            br(n, 1),
            n.m(e.parentNode, e)) : n && (yr(),
            vr(n, 1, 1, (()=>{
                n = null
            }
            )),
            xr())
        },
        i(t) {
            i || (br(n),
            br(false),
            i = !0)
        },
        o(t) {
            vr(n),
            vr(false),
            i = !1
        },
        d(t) {
            n && n.d(t),
            t && Tn(e),
            t && Tn(o)
        }
    }
}
function id(t, e, o) {
    let i, n, r, {$$slots: a={}, $$scope: s} = e;
    const l = Jn();
    let {label: c} = e
      , {hideLabel: d=!0} = e
      , {class: u} = e
      , {name: h="radio-group-" + T()} = e
      , {selectedIndex: p=-1} = e
      , {options: m=[]} = e
      , {onchange: g} = e
      , {layout: f} = e
      , {optionMapper: $} = e
      , {optionFilter: y} = e
      , {value: x} = e
      , {optionLabelClass: b} = e
      , {title: v} = e
      , {locale: w} = e
      , {optionClass: S} = e
      , {optionGroupClass: C} = e;
    const k = t=>r.findIndex((e=>e.id === t.id))
      , M = (t,e)=>{
        o(0, p = k(t));
        const i = {
            index: p,
            ...t
        };
        ((t,...e)=>{
            t && t(...e)
        }
        )(g, i, e),
        l("change", i)
    }
    ;
    return t.$$set = t=>{
        "label"in t && o(1, c = t.label),
        "hideLabel"in t && o(2, d = t.hideLabel),
        "class"in t && o(3, u = t.class),
        "name"in t && o(4, h = t.name),
        "selectedIndex"in t && o(0, p = t.selectedIndex),
        "options"in t && o(15, m = t.options),
        "onchange"in t && o(16, g = t.onchange),
        "layout"in t && o(5, f = t.layout),
        "optionMapper"in t && o(17, $ = t.optionMapper),
        "optionFilter"in t && o(18, y = t.optionFilter),
        "value"in t && o(19, x = t.value),
        "optionLabelClass"in t && o(6, b = t.optionLabelClass),
        "title"in t && o(7, v = t.title),
        "locale"in t && o(20, w = t.locale),
        "optionClass"in t && o(8, S = t.optionClass),
        "optionGroupClass"in t && o(9, C = t.optionGroupClass),
        "$$scope"in t && o(23, s = t.$$scope)
    }
    ,
    t.$$.update = ()=>{
        1343488 & t.$$.dirty[0] && o(10, i = Ac(y ? m.filter(y) : m, w)),
        132096 & t.$$.dirty[0] && o(11, n = ((t=[],e)=>{
            let o = 0;
            return t.map((t=>(o++,
            Je(t) ? Je(t[1]) ? {
                id: o,
                label: t[0],
                options: t[1].map((t=>Rc(t, ++o, e)))
            } : Rc(t, o, e) : t.options ? {
                id: t.id || o,
                label: t.label,
                options: t.options.map((t=>Rc(t, ++o, e)))
            } : Rc(t, o, e))))
        }
        )(i, $)),
        2048 & t.$$.dirty[0] && o(21, r = Tc(n)),
        2654209 & t.$$.dirty[0] && p < 0 && (o(0, p = r.findIndex((t=>Lc(t.value, x)))),
        p < 0 && o(0, p = (t=>t.findIndex((t=>void 0 === t[0])))(m)))
    }
    ,
    [p, c, d, u, h, f, b, v, S, C, i, n, k, t=>e=>{
        Ec(e.key) && M(t, e)
    }
    , t=>e=>{
        M(t, e)
    }
    , m, g, $, y, x, w, r, a, s]
}
class nd extends Br {
    constructor(t) {
        super(),
        zr(this, t, id, od, ln, {
            label: 1,
            hideLabel: 2,
            class: 3,
            name: 4,
            selectedIndex: 0,
            options: 15,
            onchange: 16,
            layout: 5,
            optionMapper: 17,
            optionFilter: 18,
            value: 19,
            optionLabelClass: 6,
            title: 7,
            locale: 20,
            optionClass: 8,
            optionGroupClass: 9
        }, [-1, -1])
    }
}
function rd(t) {
    let e, o;
    return e = new Al({
        props: {
            class: "PinturaButtonIcon",
            $$slots: {
                default: [ad]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            536870976 & o && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function ad(t) {
    let e;
    return {
        c() {
            e = Pn("g")
        },
        m(o, i) {
            Mn(o, e, i),
            e.innerHTML = t[6]
        },
        p(t, o) {
            64 & o && (e.innerHTML = t[6])
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function sd(t) {
    let e, o, i, n, r, a, s, l, c = (t[2] || t[18]) + "", d = t[6] && rd(t);
    return {
        c() {
            e = Rn("span"),
            d && d.c(),
            o = An(),
            i = Rn("span"),
            n = In(c),
            Bn(i, "class", r = rl(["PinturaButtonLabel", t[3], t[5] && "implicit"])),
            Bn(e, "slot", "label"),
            Bn(e, "title", a = Pc(t[1], t[15])),
            Bn(e, "class", s = rl(["PinturaButtonInner", t[4]]))
        },
        m(t, r) {
            Mn(t, e, r),
            d && d.m(e, null),
            kn(e, o),
            kn(e, i),
            kn(i, n),
            l = !0
        },
        p(t, u) {
            t[6] ? d ? (d.p(t, u),
            64 & u && br(d, 1)) : (d = rd(t),
            d.c(),
            br(d, 1),
            d.m(e, o)) : d && (yr(),
            vr(d, 1, 1, (()=>{
                d = null
            }
            )),
            xr()),
            (!l || 262148 & u) && c !== (c = (t[2] || t[18]) + "") && Dn(n, c),
            (!l || 40 & u && r !== (r = rl(["PinturaButtonLabel", t[3], t[5] && "implicit"]))) && Bn(i, "class", r),
            (!l || 32770 & u && a !== (a = Pc(t[1], t[15]))) && Bn(e, "title", a),
            (!l || 16 & u && s !== (s = rl(["PinturaButtonInner", t[4]]))) && Bn(e, "class", s)
        },
        i(t) {
            l || (br(d),
            l = !0)
        },
        o(t) {
            vr(d),
            l = !1
        },
        d(t) {
            t && Tn(e),
            d && d.d()
        }
    }
}
function ld(t) {
    let e, o, i = t[28].label + "";
    return {
        c() {
            e = Rn("span"),
            o = In(i),
            Bn(e, "slot", "group")
        },
        m(t, i) {
            Mn(t, e, i),
            kn(e, o)
        },
        p(t, e) {
            268435456 & e && i !== (i = t[28].label + "") && Dn(o, i)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function cd(t) {
    let e, o;
    return e = new Al({
        props: {
            style: S(t[13]) ? t[13](t[28].value) : t[13],
            $$slots: {
                default: [dd]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            268443648 & o && (i.style = S(t[13]) ? t[13](t[28].value) : t[13]),
            805306368 & o && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function dd(t) {
    let e, o = t[28].icon + "";
    return {
        c() {
            e = Pn("g")
        },
        m(t, i) {
            Mn(t, e, i),
            e.innerHTML = o
        },
        p(t, i) {
            268435456 & i && o !== (o = t[28].icon + "") && (e.innerHTML = o)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function ud(t) {
    let e, o, i, n, r, a, s, l = t[28].label + "", c = t[28].icon && cd(t);
    return {
        c() {
            e = Rn("span"),
            c && c.c(),
            o = An(),
            i = Rn("span"),
            n = In(l),
            Bn(i, "style", r = S(t[14]) ? t[14](t[28].value) : t[14]),
            Bn(i, "class", a = rl(["PinturaDropdownOptionLabel", t[10]])),
            Bn(e, "slot", "option")
        },
        m(t, r) {
            Mn(t, e, r),
            c && c.m(e, null),
            kn(e, o),
            kn(e, i),
            kn(i, n),
            s = !0
        },
        p(t, d) {
            t[28].icon ? c ? (c.p(t, d),
            268435456 & d && br(c, 1)) : (c = cd(t),
            c.c(),
            br(c, 1),
            c.m(e, o)) : c && (yr(),
            vr(c, 1, 1, (()=>{
                c = null
            }
            )),
            xr()),
            (!s || 268435456 & d) && l !== (l = t[28].label + "") && Dn(n, l),
            (!s || 268451840 & d && r !== (r = S(t[14]) ? t[14](t[28].value) : t[14])) && Bn(i, "style", r),
            (!s || 1024 & d && a !== (a = rl(["PinturaDropdownOptionLabel", t[10]]))) && Bn(i, "class", a)
        },
        i(t) {
            s || (br(c),
            s = !0)
        },
        o(t) {
            vr(c),
            s = !1
        },
        d(t) {
            t && Tn(e),
            c && c.d()
        }
    }
}
function hd(t) {
    let e, o, i, n, r;
    return o = new nd({
        props: {
            name: t[7],
            value: t[9],
            selectedIndex: t[8],
            optionFilter: t[11],
            optionMapper: t[12],
            optionLabelClass: rl(["PinturaDropdownOptionLabel", t[10]]),
            optionGroupClass: "PinturaDropdownOptionGroup",
            optionClass: "PinturaDropdownOption",
            options: t[16],
            onchange: t[19],
            $$slots: {
                option: [ud, ({option: t})=>({
                    28: t
                }), ({option: t})=>t ? 268435456 : 0],
                group: [ld, ({option: t})=>({
                    28: t
                }), ({option: t})=>t ? 268435456 : 0]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            e = Rn("div"),
            Ar(o.$$.fragment),
            Bn(e, "class", "PinturaDropdownPanel"),
            Bn(e, "slot", "details")
        },
        m(a, s) {
            Mn(a, e, s),
            Er(o, e, null),
            i = !0,
            n || (r = Ln(e, "keydown", t[21]),
            n = !0)
        },
        p(t, e) {
            const i = {};
            128 & e && (i.name = t[7]),
            512 & e && (i.value = t[9]),
            256 & e && (i.selectedIndex = t[8]),
            2048 & e && (i.optionFilter = t[11]),
            4096 & e && (i.optionMapper = t[12]),
            1024 & e && (i.optionLabelClass = rl(["PinturaDropdownOptionLabel", t[10]])),
            65536 & e && (i.options = t[16]),
            805331968 & e && (i.$$scope = {
                dirty: e,
                ctx: t
            }),
            o.$set(i)
        },
        i(t) {
            i || (br(o.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            i = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o),
            n = !1,
            r()
        }
    }
}
function pd(t) {
    let e, o, i;
    function n(e) {
        t[26](e)
    }
    let r = {
        onshow: t[20],
        buttonClass: rl(["PinturaDropdownButton", t[0], t[5] && "PinturaDropdownIconOnly"]),
        $$slots: {
            details: [hd],
            label: [sd]
        },
        $$scope: {
            ctx: t
        }
    };
    return void 0 !== t[17] && (r.isActive = t[17]),
    e = new Sc({
        props: r
    }),
    ir.push((()=>Ir(e, "isActive", n))),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, o) {
            Er(e, t, o),
            i = !0
        },
        p(t, [i]) {
            const n = {};
            33 & i && (n.buttonClass = rl(["PinturaDropdownButton", t[0], t[5] && "PinturaDropdownIconOnly"])),
            537264126 & i && (n.$$scope = {
                dirty: i,
                ctx: t
            }),
            !o && 131072 & i && (o = !0,
            n.isActive = t[17],
            cr((()=>o = !1))),
            e.$set(n)
        },
        i(t) {
            i || (br(e.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            i = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function md(t, e, o) {
    let i, r, {class: a} = e, {title: s} = e, {label: l} = e, {labelClass: c} = e, {innerClass: d} = e, {hideLabel: u=!1} = e, {icon: h} = e, {name: p} = e, {options: m=[]} = e, {selectedIndex: g=-1} = e, {value: f} = e, {optionLabelClass: $} = e, {optionFilter: y} = e, {optionMapper: x} = e, {optionIconStyle: b} = e, {optionLabelStyle: v} = e, {locale: w} = e, {onchange: S=n} = e, {onload: C=n} = e, {ondestroy: k=n} = e;
    let M;
    return qn((()=>C({
        options: m
    }))),
    Kn((()=>k({
        options: m
    }))),
    t.$$set = t=>{
        "class"in t && o(0, a = t.class),
        "title"in t && o(1, s = t.title),
        "label"in t && o(2, l = t.label),
        "labelClass"in t && o(3, c = t.labelClass),
        "innerClass"in t && o(4, d = t.innerClass),
        "hideLabel"in t && o(5, u = t.hideLabel),
        "icon"in t && o(6, h = t.icon),
        "name"in t && o(7, p = t.name),
        "options"in t && o(22, m = t.options),
        "selectedIndex"in t && o(8, g = t.selectedIndex),
        "value"in t && o(9, f = t.value),
        "optionLabelClass"in t && o(10, $ = t.optionLabelClass),
        "optionFilter"in t && o(11, y = t.optionFilter),
        "optionMapper"in t && o(12, x = t.optionMapper),
        "optionIconStyle"in t && o(13, b = t.optionIconStyle),
        "optionLabelStyle"in t && o(14, v = t.optionLabelStyle),
        "locale"in t && o(15, w = t.locale),
        "onchange"in t && o(23, S = t.onchange),
        "onload"in t && o(24, C = t.onload),
        "ondestroy"in t && o(25, k = t.ondestroy)
    }
    ,
    t.$$.update = ()=>{
        4227072 & t.$$.dirty && o(16, i = w ? Ac(m, w) : m),
        66048 & t.$$.dirty && o(18, r = i.reduce(((t,e)=>{
            if (t)
                return t;
            const o = Array.isArray(e) ? e : [e, e]
              , [i,n] = o;
            return Lc(i, f) ? n : void 0
        }
        ), void 0) || (t=>{
            const e = t.find((t=>void 0 === t[0]));
            if (e)
                return e[1]
        }
        )(i) || f)
    }
    ,
    [a, s, l, c, d, u, h, p, g, f, $, y, x, b, v, w, i, M, r, t=>{
        o(18, r = t.value),
        S(t),
        o(17, M = !1)
    }
    , ({e: t, panel: e})=>{
        if (t && t.key && /up|down/i.test(t.key))
            return e.querySelector("input:not([disabled])").focus();
        e.querySelector("fieldset").focus()
    }
    , t=>{
        /tab/i.test(t.key) && t.preventDefault()
    }
    , m, S, C, k, function(t) {
        M = t,
        o(17, M)
    }
    ]
}
class gd extends Br {
    constructor(t) {
        super(),
        zr(this, t, md, pd, ln, {
            class: 0,
            title: 1,
            label: 2,
            labelClass: 3,
            innerClass: 4,
            hideLabel: 5,
            icon: 6,
            name: 7,
            options: 22,
            selectedIndex: 8,
            value: 9,
            optionLabelClass: 10,
            optionFilter: 11,
            optionMapper: 12,
            optionIconStyle: 13,
            optionLabelStyle: 14,
            locale: 15,
            onchange: 23,
            onload: 24,
            ondestroy: 25
        })
    }
}
var fd = (t,e,o)=>(t - e) / (o - e);
function $d(t) {
    let e;
    return {
        c() {
            e = Pn("path"),
            Bn(e, "d", "M8 12 h8 M12 8 v8")
        },
        m(t, o) {
            Mn(t, e, o)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function yd(t) {
    let e;
    return {
        c() {
            e = Pn("path"),
            Bn(e, "d", "M9 12 h6")
        },
        m(t, o) {
            Mn(t, e, o)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function xd(t) {
    let e, o, i, n, r, a, s, l, c, d, u, h, p, m, g, f, $, y;
    return u = new Al({
        props: {
            $$slots: {
                default: [$d]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    m = new Al({
        props: {
            $$slots: {
                default: [yd]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            e = Rn("div"),
            o = Rn("div"),
            i = Rn("input"),
            n = An(),
            r = Rn("div"),
            a = An(),
            s = Rn("div"),
            l = Rn("div"),
            c = An(),
            d = Rn("button"),
            Ar(u.$$.fragment),
            h = An(),
            p = Rn("button"),
            Ar(m.$$.fragment),
            Bn(i, "type", "range"),
            Bn(i, "id", t[3]),
            Bn(i, "min", t[0]),
            Bn(i, "max", t[1]),
            Bn(i, "step", t[2]),
            i.value = t[8],
            Bn(r, "class", "PinturaSliderTrack"),
            Bn(r, "style", t[4]),
            Bn(l, "class", "PinturaSliderKnob"),
            Bn(l, "style", t[5]),
            Bn(s, "class", "PinturaSliderKnobController"),
            Bn(s, "style", t[10]),
            Bn(o, "class", "PinturaSliderControl"),
            Bn(d, "type", "button"),
            Bn(d, "aria-label", "Increase"),
            Bn(p, "type", "button"),
            Bn(p, "aria-label", "Decrease"),
            Bn(e, "class", g = rl(["PinturaSlider", t[7]])),
            Bn(e, "data-direction", t[6])
        },
        m(g, x) {
            Mn(g, e, x),
            kn(e, o),
            kn(o, i),
            t[22](i),
            kn(o, n),
            kn(o, r),
            kn(o, a),
            kn(o, s),
            kn(s, l),
            kn(e, c),
            kn(e, d),
            Er(u, d, null),
            kn(e, h),
            kn(e, p),
            Er(m, p, null),
            f = !0,
            $ || (y = [Ln(i, "pointerdown", t[13]), Ln(i, "input", t[11]), Ln(i, "nudge", t[12]), yn(Vl.call(null, i)), Ln(d, "pointerdown", t[14](1)), Ln(p, "pointerdown", t[14](-1))],
            $ = !0)
        },
        p(t, o) {
            (!f || 8 & o[0]) && Bn(i, "id", t[3]),
            (!f || 1 & o[0]) && Bn(i, "min", t[0]),
            (!f || 2 & o[0]) && Bn(i, "max", t[1]),
            (!f || 4 & o[0]) && Bn(i, "step", t[2]),
            (!f || 256 & o[0]) && (i.value = t[8]),
            (!f || 16 & o[0]) && Bn(r, "style", t[4]),
            (!f || 32 & o[0]) && Bn(l, "style", t[5]),
            (!f || 1024 & o[0]) && Bn(s, "style", t[10]);
            const n = {};
            512 & o[1] && (n.$$scope = {
                dirty: o,
                ctx: t
            }),
            u.$set(n);
            const a = {};
            512 & o[1] && (a.$$scope = {
                dirty: o,
                ctx: t
            }),
            m.$set(a),
            (!f || 128 & o[0] && g !== (g = rl(["PinturaSlider", t[7]]))) && Bn(e, "class", g),
            (!f || 64 & o[0]) && Bn(e, "data-direction", t[6])
        },
        i(t) {
            f || (br(u.$$.fragment, t),
            br(m.$$.fragment, t),
            f = !0)
        },
        o(t) {
            vr(u.$$.fragment, t),
            vr(m.$$.fragment, t),
            f = !1
        },
        d(o) {
            o && Tn(e),
            t[22](null),
            Lr(u),
            Lr(m),
            $ = !1,
            an(y)
        }
    }
}
function bd(t, e, o) {
    let i, n, r, a, s, l, c, d, u, h, p, m, g, f, {min: $=0} = e, {max: y=100} = e, {step: x=1} = e, {id: b} = e, {value: v=0} = e, {trackStyle: w} = e, {knobStyle: S} = e, {onchange: C} = e, {direction: k="x"} = e, {getValue: M=_} = e, {setValue: T=_} = e, {class: R} = e;
    const P = t=>T(((t,e)=>(e = 1 / e,
    Math.round(t * e) / e))(na(t, $, y), x))
      , I = (t,e)=>{
        o(15, v = P($ + t / e * n)),
        v !== f && (f = v,
        C(v))
    }
      , A = t=>{
        const e = t[d] - g;
        I(m + e, p)
    }
      , E = t=>{
        p = void 0,
        document.documentElement.removeEventListener("pointermove", A),
        document.documentElement.removeEventListener("pointerup", E),
        C(v)
    }
      , L = ()=>{
        o(15, v = P(i + z * x)),
        C(v)
    }
    ;
    let F, z = 1, B = !1;
    const O = t=>{
        clearTimeout(F),
        B || L(),
        document.removeEventListener("pointerup", O)
    }
    ;
    return t.$$set = t=>{
        "min"in t && o(0, $ = t.min),
        "max"in t && o(1, y = t.max),
        "step"in t && o(2, x = t.step),
        "id"in t && o(3, b = t.id),
        "value"in t && o(15, v = t.value),
        "trackStyle"in t && o(4, w = t.trackStyle),
        "knobStyle"in t && o(5, S = t.knobStyle),
        "onchange"in t && o(16, C = t.onchange),
        "direction"in t && o(6, k = t.direction),
        "getValue"in t && o(17, M = t.getValue),
        "setValue"in t && o(18, T = t.setValue),
        "class"in t && o(7, R = t.class)
    }
    ,
    t.$$.update = ()=>{
        163840 & t.$$.dirty[0] && o(8, i = void 0 !== v ? M(v) : 0),
        3 & t.$$.dirty[0] && (n = y - $),
        259 & t.$$.dirty[0] && o(19, r = 100 * fd(i, $, y)),
        64 & t.$$.dirty[0] && o(20, a = k.toUpperCase()),
        64 & t.$$.dirty[0] && o(21, s = "x" === k ? "Width" : "Height"),
        2097152 & t.$$.dirty[0] && (l = "offset" + s),
        1048576 & t.$$.dirty[0] && (c = "offset" + a),
        1048576 & t.$$.dirty[0] && (d = "page" + a),
        1572864 & t.$$.dirty[0] && o(10, u = `transform: translate${a}(${r}%)`)
    }
    ,
    [$, y, x, b, w, S, k, R, i, h, u, t=>{
        p || (o(15, v = T(parseFloat(t.target.value))),
        v !== f && (f = v,
        C(v)))
    }
    , t=>{
        const e = h[l];
        I(i / n * e + t.detail[k], e)
    }
    , t=>{
        t.stopPropagation(),
        p = h[l],
        m = t[c],
        g = t[d],
        I(m, p),
        document.documentElement.addEventListener("pointermove", A),
        document.documentElement.addEventListener("pointerup", E)
    }
    , t=>e=>{
        z = t,
        B = !1,
        F = setInterval((()=>{
            B = !0,
            L()
        }
        ), 100),
        document.addEventListener("pointercancel", O),
        document.addEventListener("pointerup", O)
    }
    , v, C, M, T, r, a, s, function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            h = t,
            o(9, h)
        }
        ))
    }
    ]
}
class vd extends Br {
    constructor(t) {
        super(),
        zr(this, t, bd, xd, ln, {
            min: 0,
            max: 1,
            step: 2,
            id: 3,
            value: 15,
            trackStyle: 4,
            knobStyle: 5,
            onchange: 16,
            direction: 6,
            getValue: 17,
            setValue: 18,
            class: 7
        }, [-1, -1])
    }
}
function wd(t) {
    let e, o;
    return e = new Al({
        props: {
            class: "PinturaButtonIcon",
            $$slots: {
                default: [Sd]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            262148 & o && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Sd(t) {
    let e;
    return {
        c() {
            e = Pn("g")
        },
        m(o, i) {
            Mn(o, e, i),
            e.innerHTML = t[2]
        },
        p(t, o) {
            4 & o && (e.innerHTML = t[2])
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function Cd(t) {
    let e, o, i, n, r, a, s, l, c = t[2] && wd(t);
    return {
        c() {
            e = Rn("span"),
            c && c.c(),
            o = An(),
            i = Rn("span"),
            n = In(t[8]),
            Bn(i, "class", r = rl(["PinturaButtonLabel", t[3], t[5] && "implicit"])),
            Bn(e, "slot", "label"),
            Bn(e, "title", a = Pc(t[1], t[6])),
            Bn(e, "class", s = rl(["PinturaButtonInner", t[4]]))
        },
        m(t, r) {
            Mn(t, e, r),
            c && c.m(e, null),
            kn(e, o),
            kn(e, i),
            kn(i, n),
            l = !0
        },
        p(t, d) {
            t[2] ? c ? (c.p(t, d),
            4 & d && br(c, 1)) : (c = wd(t),
            c.c(),
            br(c, 1),
            c.m(e, o)) : c && (yr(),
            vr(c, 1, 1, (()=>{
                c = null
            }
            )),
            xr()),
            (!l || 256 & d) && Dn(n, t[8]),
            (!l || 40 & d && r !== (r = rl(["PinturaButtonLabel", t[3], t[5] && "implicit"]))) && Bn(i, "class", r),
            (!l || 66 & d && a !== (a = Pc(t[1], t[6]))) && Bn(e, "title", a),
            (!l || 16 & d && s !== (s = rl(["PinturaButtonInner", t[4]]))) && Bn(e, "class", s)
        },
        i(t) {
            l || (br(c),
            l = !0)
        },
        o(t) {
            vr(c),
            l = !1
        },
        d(t) {
            t && Tn(e),
            c && c.d()
        }
    }
}
function kd(t) {
    let e, o, i, n, r;
    const a = [t[11], {
        value: t[7]
    }, {
        onchange: t[10]
    }];
    let s = {};
    for (let t = 0; t < a.length; t += 1)
        s = on(s, a[t]);
    return o = new vd({
        props: s
    }),
    {
        c() {
            e = Rn("div"),
            Ar(o.$$.fragment),
            Bn(e, "slot", "details")
        },
        m(a, s) {
            Mn(a, e, s),
            Er(o, e, null),
            i = !0,
            n || (r = Ln(e, "keydown", t[9]),
            n = !0)
        },
        p(t, e) {
            const i = 3200 & e ? Rr(a, [2048 & e && Pr(t[11]), 128 & e && {
                value: t[7]
            }, 1024 & e && {
                onchange: t[10]
            }]) : {};
            o.$set(i)
        },
        i(t) {
            i || (br(o.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            i = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o),
            n = !1,
            r()
        }
    }
}
function Md(t) {
    let e, o;
    return e = new Sc({
        props: {
            panelClass: "PinturaSliderPanel",
            buttonClass: rl(["PinturaSliderButton", t[0], t[5] && "PinturaSliderIconOnly"]),
            $$slots: {
                details: [kd],
                label: [Cd]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, [o]) {
            const i = {};
            33 & o && (i.buttonClass = rl(["PinturaSliderButton", t[0], t[5] && "PinturaSliderIconOnly"])),
            264702 & o && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Td(t, e, o) {
    const i = ["class", "title", "label", "icon", "labelClass", "innerClass", "hideLabel", "locale", "value", "onchange"];
    let r = fn(e, i)
      , {class: a} = e
      , {title: s} = e
      , {label: l=Math.round} = e
      , {icon: c} = e
      , {labelClass: d} = e
      , {innerClass: u} = e
      , {hideLabel: h=!1} = e
      , {locale: p} = e
      , {value: m} = e
      , {onchange: g=n} = e;
    const {min: f, max: $, getValue: y=_} = r
      , x = t=>S(l) ? l(y(t), f, $) : l;
    let b = x(m);
    return t.$$set = t=>{
        e = on(on({}, e), gn(t)),
        o(11, r = fn(e, i)),
        "class"in t && o(0, a = t.class),
        "title"in t && o(1, s = t.title),
        "label"in t && o(12, l = t.label),
        "icon"in t && o(2, c = t.icon),
        "labelClass"in t && o(3, d = t.labelClass),
        "innerClass"in t && o(4, u = t.innerClass),
        "hideLabel"in t && o(5, h = t.hideLabel),
        "locale"in t && o(6, p = t.locale),
        "value"in t && o(7, m = t.value),
        "onchange"in t && o(13, g = t.onchange)
    }
    ,
    [a, s, c, d, u, h, p, m, b, t=>{
        /tab/i.test(t.key) && t.preventDefault()
    }
    , t=>{
        o(8, b = x(t)),
        g(t)
    }
    , r, l, g]
}
class Rd extends Br {
    constructor(t) {
        super(),
        zr(this, t, Td, Md, ln, {
            class: 0,
            title: 1,
            label: 12,
            icon: 2,
            labelClass: 3,
            innerClass: 4,
            hideLabel: 5,
            locale: 6,
            value: 7,
            onchange: 13
        })
    }
}
function Pd(t, e, o) {
    const i = t.slice();
    return i[7] = e[o][0],
    i[8] = e[o][1],
    i[9] = e[o][2],
    i[0] = e[o][3],
    i
}
function Id(t) {
    let e, o, i;
    const n = [t[9]];
    var r = t[1][t[7]] || t[7];
    function a(t) {
        let e = {};
        for (let t = 0; t < n.length; t += 1)
            e = on(e, n[t]);
        return {
            props: e
        }
    }
    return r && (e = new r(a())),
    {
        c() {
            e && Ar(e.$$.fragment),
            o = En()
        },
        m(t, n) {
            e && Er(e, t, n),
            Mn(t, o, n),
            i = !0
        },
        p(t, i) {
            const s = 1 & i ? Rr(n, [Pr(t[9])]) : {};
            if (r !== (r = t[1][t[7]] || t[7])) {
                if (e) {
                    yr();
                    const t = e;
                    vr(t.$$.fragment, 1, 0, (()=>{
                        Lr(t, 1)
                    }
                    )),
                    xr()
                }
                r ? (e = new r(a()),
                Ar(e.$$.fragment),
                br(e.$$.fragment, 1),
                Er(e, o.parentNode, o)) : e = null
            } else
                r && e.$set(s)
        },
        i(t) {
            i || (e && br(e.$$.fragment, t),
            i = !0)
        },
        o(t) {
            e && vr(e.$$.fragment, t),
            i = !1
        },
        d(t) {
            t && Tn(o),
            e && Lr(e, t)
        }
    }
}
function Ad(t) {
    let e, o;
    return e = new uc({
        props: {
            name: t[7],
            attributes: t[2](t[9]),
            $$slots: {
                default: [zd]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            1 & o && (i.name = t[7]),
            1 & o && (i.attributes = t[2](t[9])),
            4097 & o && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Ed(t) {
    let e, o, i = t[9].innerHTML + "";
    return {
        c() {
            o = En(),
            e = new Vn(o)
        },
        m(t, n) {
            e.m(i, t, n),
            Mn(t, o, n)
        },
        p(t, o) {
            1 & o && i !== (i = t[9].innerHTML + "") && e.p(i)
        },
        i: tn,
        o: tn,
        d(t) {
            t && Tn(o),
            t && e.d()
        }
    }
}
function Ld(t) {
    let e, o = t[9].textContent + "";
    return {
        c() {
            e = In(o)
        },
        m(t, o) {
            Mn(t, e, o)
        },
        p(t, i) {
            1 & i && o !== (o = t[9].textContent + "") && Dn(e, o)
        },
        i: tn,
        o: tn,
        d(t) {
            t && Tn(e)
        }
    }
}
function Fd(t) {
    let e, o;
    return e = new _d({
        props: {
            items: t[0],
            discardEmptyItems: !0
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            1 & o && (i.items = t[0]),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function zd(t) {
    let e, o, i, n;
    const r = [Fd, Ld, Ed]
      , a = [];
    function s(t, e) {
        return t[0] && t[0].length ? 0 : t[9].textContent ? 1 : t[9].innerHTML ? 2 : -1
    }
    return ~(e = s(t)) && (o = a[e] = r[e](t)),
    {
        c() {
            o && o.c(),
            i = An()
        },
        m(t, o) {
            ~e && a[e].m(t, o),
            Mn(t, i, o),
            n = !0
        },
        p(t, n) {
            let l = e;
            e = s(t),
            e === l ? ~e && a[e].p(t, n) : (o && (yr(),
            vr(a[l], 1, 1, (()=>{
                a[l] = null
            }
            )),
            xr()),
            ~e ? (o = a[e],
            o ? o.p(t, n) : (o = a[e] = r[e](t),
            o.c()),
            br(o, 1),
            o.m(i.parentNode, i)) : o = null)
        },
        i(t) {
            n || (br(o),
            n = !0)
        },
        o(t) {
            vr(o),
            n = !1
        },
        d(t) {
            ~e && a[e].d(t),
            t && Tn(i)
        }
    }
}
function Bd(t, e) {
    let o, i, n, r, a, s;
    const l = [Ad, Id]
      , c = [];
    function d(t, e) {
        return 1 & e && (i = !t[3](t[7])),
        i ? 0 : 1
    }
    return n = d(e, -1),
    r = c[n] = l[n](e),
    {
        key: t,
        first: null,
        c() {
            o = En(),
            r.c(),
            a = En(),
            this.first = o
        },
        m(t, e) {
            Mn(t, o, e),
            c[n].m(t, e),
            Mn(t, a, e),
            s = !0
        },
        p(t, o) {
            let i = n;
            n = d(e = t, o),
            n === i ? c[n].p(e, o) : (yr(),
            vr(c[i], 1, 1, (()=>{
                c[i] = null
            }
            )),
            xr(),
            r = c[n],
            r ? r.p(e, o) : (r = c[n] = l[n](e),
            r.c()),
            br(r, 1),
            r.m(a.parentNode, a))
        },
        i(t) {
            s || (br(r),
            s = !0)
        },
        o(t) {
            vr(r),
            s = !1
        },
        d(t) {
            t && Tn(o),
            c[n].d(t),
            t && Tn(a)
        }
    }
}
function Od(t) {
    let e, o, i = [], n = new Map, r = t[0];
    const a = t=>t[8];
    for (let e = 0; e < r.length; e += 1) {
        let o = Pd(t, r, e)
          , s = a(o);
        n.set(s, i[e] = Bd(s, o))
    }
    return {
        c() {
            for (let t = 0; t < i.length; t += 1)
                i[t].c();
            e = En()
        },
        m(t, n) {
            for (let e = 0; e < i.length; e += 1)
                i[e].m(t, n);
            Mn(t, e, n),
            o = !0
        },
        p(t, [o]) {
            15 & o && (r = t[0],
            yr(),
            i = Tr(i, o, a, 1, t, r, n, e.parentNode, Mr, Bd, e, Pd),
            xr())
        },
        i(t) {
            if (!o) {
                for (let t = 0; t < r.length; t += 1)
                    br(i[t]);
                o = !0
            }
        },
        o(t) {
            for (let t = 0; t < i.length; t += 1)
                vr(i[t]);
            o = !1
        },
        d(t) {
            for (let e = 0; e < i.length; e += 1)
                i[e].d(t);
            t && Tn(e)
        }
    }
}
function Dd(t, e, o) {
    let i, {items: n} = e, {discardEmptyItems: r=!0} = e;
    const a = {
        Button: Dl,
        Dropdown: gd,
        Slider: Rd
    }
      , s = t=>!w(t) || !!a[t]
      , l = t=>{
        if (!t)
            return !1;
        const [e,,o,i=[]] = t;
        return !!s(e) || (i.some(l) || o.textContent || o.innerHTML)
    }
    ;
    return t.$$set = t=>{
        "items"in t && o(4, n = t.items),
        "discardEmptyItems"in t && o(5, r = t.discardEmptyItems)
    }
    ,
    t.$$.update = ()=>{
        48 & t.$$.dirty && o(0, i = (n && r ? n.filter(l) : n) || [])
    }
    ,
    [i, a, (t={})=>{
        const {textContent: e, innerHTML: o, ...i} = t;
        return i
    }
    , s, n, r]
}
class _d extends Br {
    constructor(t) {
        super(),
        zr(this, t, Dd, Od, ln, {
            items: 4,
            discardEmptyItems: 5
        })
    }
}
var Wd = (t,e,o,i=(t=>t * t * (3 - 2 * t)))=>i(Math.max(0, Math.min(1, (o - t) / (e - t))));
var Vd = (t,e)=>new CustomEvent("ping",{
    detail: {
        type: t,
        data: e
    },
    cancelable: !0,
    bubbles: !0
})
  , Hd = (t,e)=>(e ? Is(t, e) : t).replace(/([a-z])([A-Z])/g, "$1-$2").replace(/\s+/g, "-").toLowerCase()
  , Nd = (t,e=_)=>{
    const {subscribe: o, set: i} = _r(void 0);
    return {
        subscribe: o,
        destroy: ((t,e)=>{
            const o = matchMedia(t);
            return o.addListener(e),
            e(o),
            {
                get matches() {
                    return o.matches
                },
                destroy: ()=>o.removeListener(e)
            }
        }
        )(t, (({matches: t})=>i(e(t)))).destroy
    }
}
  , Ud = (t,e,o)=>new Promise(((i,n)=>{
    (async()=>{
        const r = await e.read(t)
          , a = t=>I(t, o).then((t=>e.apply(t, r))).then(i).catch(n);
        if (M(t) || !k() || ye() || Fe())
            return a(t);
        let s;
        try {
            s = await P(((t,e)=>createImageBitmap(t).then((t=>e(null, t))).catch(e)), [t])
        } catch (t) {}
        s && s.width ? await u() ? c() && window.chrome && r > 1 ? i(await (async t=>h(await $(t)))(s)) : i(s) : i(e.apply(s, r)) : a(t)
    }
    )()
}
))
  , jd = (t,e)=>new Promise((async o=>{
    if (t.width < e.width && t.height < e.height)
        return o(t);
    const i = Math.min(e.width / t.width, e.height / t.height)
      , n = i * t.width
      , r = i * t.height
      , a = p("canvas", {
        width: n,
        height: r
    })
      , s = a.getContext("2d")
      , l = f(t) ? await $(t) : t;
    s.drawImage(l, 0, 0, n, r),
    o(h(a))
}
))
  , Xd = t=>(t = t.trim(),
/^rgba/.test(t) ? t.substr(5).split(",").map(parseFloat).map(((t,e)=>t / (3 === e ? 1 : 255))) : /^rgb/.test(t) ? t.substr(4).split(",").map(parseFloat).map((t=>t / 255)) : /^#/.test(t) ? (t=>{
    const [,e,o,i] = t.split("");
    t = 4 === t.length ? `#${e}${e}${o}${o}${i}${i}` : t;
    const [,n,r,a] = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(t);
    return [n, r, a].map((t=>parseInt(t, 16) / 255))
}
)(t) : /[0-9]{1,3}\s?,\s?[0-9]{1,3}\s?,\s?[0-9]{1,3}/.test(t) ? t.split(",").map((t=>parseInt(t, 10))).map((t=>t / 255)) : void 0);
let Yd = null;
var Gd = ()=>{
    if (null === Yd) {
        let t = p("canvas");
        Yd = !!Ms(t),
        g(t),
        t = void 0
    }
    return Yd
}
;
const qd = [1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0]
  , Zd = {
    precision: 1e-4
}
  , Kd = {
    precision: .01 * Zd.precision
};
var Jd = ()=>{
    const t = []
      , e = []
      , o = []
      , i = ()=>{
        e.forEach((t=>t(o)))
    }
      , n = e=>{
        e.unsub = e.subscribe((n=>((e,n)=>{
            const r = t.indexOf(e);
            r < 0 || (o[r] = n,
            i())
        }
        )(e, n))),
        i()
    }
    ;
    return {
        get length() {
            return t.length
        },
        clear: ()=>{
            t.forEach((t=>t.unsub())),
            t.length = 0,
            o.length = 0
        }
        ,
        unshift: e=>{
            t.unshift(e),
            n(e)
        }
        ,
        get: e=>t[e],
        push: e=>{
            t.push(e),
            n(e)
        }
        ,
        remove: e=>{
            e.unsub();
            const i = t.indexOf(e);
            t.splice(i, 1),
            o.splice(i, 1)
        }
        ,
        forEach: e=>t.forEach(e),
        filter: e=>t.filter(e),
        subscribe: t=>(e.push(t),
        ()=>{
            e.splice(e.indexOf(t), 1)
        }
        )
    }
}
  , Qd = t=>t[0] < .25 && t[1] < .25 && t[2] < .25
  , tu = ()=>new Promise((t=>{
    const e = p("input", {
        type: "file",
        accept: "image/*",
        onchange: ()=>{
            const [o] = e.files;
            if (!o)
                return t(void 0);
            t(o)
        }
    });
    e.click()
}
));
const {window: eu} = Cr;
function ou(t) {
    let e, o, i, n = t[26] && iu(t), r = t[27] && hu(t);
    return {
        c() {
            n && n.c(),
            e = An(),
            r && r.c(),
            o = En()
        },
        m(t, a) {
            n && n.m(t, a),
            Mn(t, e, a),
            r && r.m(t, a),
            Mn(t, o, a),
            i = !0
        },
        p(t, i) {
            t[26] ? n ? (n.p(t, i),
            67108864 & i[0] && br(n, 1)) : (n = iu(t),
            n.c(),
            br(n, 1),
            n.m(e.parentNode, e)) : n && (yr(),
            vr(n, 1, 1, (()=>{
                n = null
            }
            )),
            xr()),
            t[27] ? r ? (r.p(t, i),
            134217728 & i[0] && br(r, 1)) : (r = hu(t),
            r.c(),
            br(r, 1),
            r.m(o.parentNode, o)) : r && (yr(),
            vr(r, 1, 1, (()=>{
                r = null
            }
            )),
            xr())
        },
        i(t) {
            i || (br(n),
            br(r),
            i = !0)
        },
        o(t) {
            vr(n),
            vr(r),
            i = !1
        },
        d(t) {
            n && n.d(t),
            t && Tn(e),
            r && r.d(t),
            t && Tn(o)
        }
    }
}
function iu(t) {
    let e, o, i, n, r, a;
    const s = [ru, nu]
      , l = [];
    function c(t, e) {
        return t[23] ? 0 : t[13] ? 1 : -1
    }
    return ~(i = c(t)) && (n = l[i] = s[i](t)),
    {
        c() {
            e = Rn("div"),
            o = Rn("p"),
            n && n.c(),
            Bn(o, "style", t[39]),
            Bn(e, "class", "PinturaStatus"),
            Bn(e, "style", r = "opacity: " + t[25])
        },
        m(t, n) {
            Mn(t, e, n),
            kn(e, o),
            ~i && l[i].m(o, null),
            a = !0
        },
        p(t, d) {
            let u = i;
            i = c(t),
            i === u ? ~i && l[i].p(t, d) : (n && (yr(),
            vr(l[u], 1, 1, (()=>{
                l[u] = null
            }
            )),
            xr()),
            ~i ? (n = l[i],
            n ? n.p(t, d) : (n = l[i] = s[i](t),
            n.c()),
            br(n, 1),
            n.m(o, null)) : n = null),
            (!a || 256 & d[1]) && Bn(o, "style", t[39]),
            (!a || 33554432 & d[0] && r !== (r = "opacity: " + t[25])) && Bn(e, "style", r)
        },
        i(t) {
            a || (br(n),
            a = !0)
        },
        o(t) {
            vr(n),
            a = !1
        },
        d(t) {
            t && Tn(e),
            ~i && l[i].d()
        }
    }
}
function nu(t) {
    let e, o, i, n;
    e = new Ql({
        props: {
            text: t[13].text || "",
            onmeasure: t[129]
        }
    });
    let r = t[13].aside && au(t);
    return {
        c() {
            Ar(e.$$.fragment),
            o = An(),
            r && r.c(),
            i = En()
        },
        m(t, a) {
            Er(e, t, a),
            Mn(t, o, a),
            r && r.m(t, a),
            Mn(t, i, a),
            n = !0
        },
        p(t, o) {
            const n = {};
            8192 & o[0] && (n.text = t[13].text || ""),
            e.$set(n),
            t[13].aside ? r ? (r.p(t, o),
            8192 & o[0] && br(r, 1)) : (r = au(t),
            r.c(),
            br(r, 1),
            r.m(i.parentNode, i)) : r && (yr(),
            vr(r, 1, 1, (()=>{
                r = null
            }
            )),
            xr())
        },
        i(t) {
            n || (br(e.$$.fragment, t),
            br(r),
            n = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            vr(r),
            n = !1
        },
        d(t) {
            Lr(e, t),
            t && Tn(o),
            r && r.d(t),
            t && Tn(i)
        }
    }
}
function ru(t) {
    let e, o, i, n;
    return e = new Ql({
        props: {
            text: t[23],
            onmeasure: t[129]
        }
    }),
    i = new rc({
        props: {
            class: "PinturaStatusIcon",
            offset: t[43],
            opacity: t[44],
            $$slots: {
                default: [uu]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment),
            o = An(),
            Ar(i.$$.fragment)
        },
        m(t, r) {
            Er(e, t, r),
            Mn(t, o, r),
            Er(i, t, r),
            n = !0
        },
        p(t, o) {
            const n = {};
            8388608 & o[0] && (n.text = t[23]),
            e.$set(n);
            const r = {};
            4096 & o[1] && (r.offset = t[43]),
            8192 & o[1] && (r.opacity = t[44]),
            4 & o[0] | 2048 & o[12] && (r.$$scope = {
                dirty: o,
                ctx: t
            }),
            i.$set(r)
        },
        i(t) {
            n || (br(e.$$.fragment, t),
            br(i.$$.fragment, t),
            n = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            vr(i.$$.fragment, t),
            n = !1
        },
        d(t) {
            Lr(e, t),
            t && Tn(o),
            Lr(i, t)
        }
    }
}
function au(t) {
    let e, o;
    return e = new rc({
        props: {
            class: "PinturaStatusButton",
            offset: t[43],
            opacity: t[44],
            $$slots: {
                default: [cu]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            4096 & o[1] && (i.offset = t[43]),
            8192 & o[1] && (i.opacity = t[44]),
            8192 & o[0] | 2048 & o[12] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function su(t) {
    let e, o;
    return e = new oc({
        props: {
            progress: t[13].progressIndicator.progress
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            8192 & o[0] && (i.progress = t[13].progressIndicator.progress),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function lu(t) {
    let e, o;
    const i = [t[13].closeButton, {
        hideLabel: !0
    }];
    let n = {};
    for (let t = 0; t < i.length; t += 1)
        n = on(n, i[t]);
    return e = new Dl({
        props: n
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const n = 8192 & o[0] ? Rr(i, [Pr(t[13].closeButton), i[1]]) : {};
            e.$set(n)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function cu(t) {
    let e, o, i, n = t[13].progressIndicator.visible && su(t), r = t[13].closeButton && t[13].text && lu(t);
    return {
        c() {
            n && n.c(),
            e = An(),
            r && r.c(),
            o = En()
        },
        m(t, a) {
            n && n.m(t, a),
            Mn(t, e, a),
            r && r.m(t, a),
            Mn(t, o, a),
            i = !0
        },
        p(t, i) {
            t[13].progressIndicator.visible ? n ? (n.p(t, i),
            8192 & i[0] && br(n, 1)) : (n = su(t),
            n.c(),
            br(n, 1),
            n.m(e.parentNode, e)) : n && (yr(),
            vr(n, 1, 1, (()=>{
                n = null
            }
            )),
            xr()),
            t[13].closeButton && t[13].text ? r ? (r.p(t, i),
            8192 & i[0] && br(r, 1)) : (r = lu(t),
            r.c(),
            br(r, 1),
            r.m(o.parentNode, o)) : r && (yr(),
            vr(r, 1, 1, (()=>{
                r = null
            }
            )),
            xr())
        },
        i(t) {
            i || (br(n),
            br(r),
            i = !0)
        },
        o(t) {
            vr(n),
            vr(r),
            i = !1
        },
        d(t) {
            n && n.d(t),
            t && Tn(e),
            r && r.d(t),
            t && Tn(o)
        }
    }
}
function du(t) {
    let e, o = t[2].iconSupportError + "";
    return {
        c() {
            e = Pn("g")
        },
        m(t, i) {
            Mn(t, e, i),
            e.innerHTML = o
        },
        p(t, i) {
            4 & i[0] && o !== (o = t[2].iconSupportError + "") && (e.innerHTML = o)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function uu(t) {
    let e, o;
    return e = new Al({
        props: {
            $$slots: {
                default: [du]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            4 & o[0] | 2048 & o[12] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function hu(t) {
    let e, o, i, n, r, a, s, l, c, d = t[6] && pu(t), u = t[17] && t[15] && mu(t);
    const h = [xu, yu]
      , p = [];
    function m(t, e) {
        return t[17] ? 0 : 1
    }
    return i = m(t),
    n = p[i] = h[i](t),
    a = new nl({
        props: {
            animate: t[18],
            pixelRatio: t[47],
            backgroundColor: t[48],
            maskRect: t[38],
            maskOpacity: t[37] ? t[37].maskOpacity : 1,
            maskFrameOpacity: "frame" === t[49] && t[50] ? 0 : .95,
            images: t[22],
            interfaceImages: t[51],
            loadImageData: t[8],
            willRequestResource: t[52],
            willRender: t[286]
        }
    }),
    {
        c() {
            d && d.c(),
            e = An(),
            u && u.c(),
            o = An(),
            n.c(),
            r = An(),
            Ar(a.$$.fragment),
            s = An(),
            l = Rn("div"),
            Bn(l, "class", "PinturaRootPortal")
        },
        m(n, h) {
            d && d.m(n, h),
            Mn(n, e, h),
            u && u.m(n, h),
            Mn(n, o, h),
            p[i].m(n, h),
            Mn(n, r, h),
            Er(a, n, h),
            Mn(n, s, h),
            Mn(n, l, h),
            t[287](l),
            c = !0
        },
        p(t, s) {
            t[6] ? d ? (d.p(t, s),
            64 & s[0] && br(d, 1)) : (d = pu(t),
            d.c(),
            br(d, 1),
            d.m(e.parentNode, e)) : d && (yr(),
            vr(d, 1, 1, (()=>{
                d = null
            }
            )),
            xr()),
            t[17] && t[15] ? u ? (u.p(t, s),
            163840 & s[0] && br(u, 1)) : (u = mu(t),
            u.c(),
            br(u, 1),
            u.m(o.parentNode, o)) : u && (yr(),
            vr(u, 1, 1, (()=>{
                u = null
            }
            )),
            xr());
            let l = i;
            i = m(t),
            i === l ? p[i].p(t, s) : (yr(),
            vr(p[l], 1, 1, (()=>{
                p[l] = null
            }
            )),
            xr(),
            n = p[i],
            n ? n.p(t, s) : (n = p[i] = h[i](t),
            n.c()),
            br(n, 1),
            n.m(r.parentNode, r));
            const c = {};
            262144 & s[0] && (c.animate = t[18]),
            65536 & s[1] && (c.pixelRatio = t[47]),
            131072 & s[1] && (c.backgroundColor = t[48]),
            128 & s[1] && (c.maskRect = t[38]),
            64 & s[1] && (c.maskOpacity = t[37] ? t[37].maskOpacity : 1),
            786432 & s[1] && (c.maskFrameOpacity = "frame" === t[49] && t[50] ? 0 : .95),
            4194304 & s[0] && (c.images = t[22]),
            1048576 & s[1] && (c.interfaceImages = t[51]),
            256 & s[0] && (c.loadImageData = t[8]),
            2097152 & s[1] && (c.willRequestResource = t[52]),
            536870944 & s[0] | 62914560 & s[1] && (c.willRender = t[286]),
            a.$set(c)
        },
        i(t) {
            c || (br(d),
            br(u),
            br(n),
            br(a.$$.fragment, t),
            c = !0)
        },
        o(t) {
            vr(d),
            vr(u),
            vr(n),
            vr(a.$$.fragment, t),
            c = !1
        },
        d(n) {
            d && d.d(n),
            n && Tn(e),
            u && u.d(n),
            n && Tn(o),
            p[i].d(n),
            n && Tn(r),
            Lr(a, n),
            n && Tn(s),
            n && Tn(l),
            t[287](null)
        }
    }
}
function pu(t) {
    let e, o, i, n, r;
    return o = new _d({
        props: {
            items: t[40]
        }
    }),
    {
        c() {
            e = Rn("div"),
            Ar(o.$$.fragment),
            Bn(e, "class", "PinturaNav PinturaNavTools")
        },
        m(a, s) {
            Mn(a, e, s),
            Er(o, e, null),
            i = !0,
            n || (r = [Ln(e, "measure", t[271]), yn(bs.call(null, e))],
            n = !0)
        },
        p(t, e) {
            const i = {};
            512 & e[1] && (i.items = t[40]),
            o.$set(i)
        },
        i(t) {
            i || (br(o.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            i = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o),
            n = !1,
            an(r)
        }
    }
}
function mu(t) {
    let e, o, i;
    return o = new ql({
        props: {
            elasticity: t[4] * Su,
            scrollDirection: t[35] ? "y" : "x",
            $$slots: {
                default: [$u]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            e = Rn("div"),
            Ar(o.$$.fragment),
            Bn(e, "class", "PinturaNav PinturaNavMain")
        },
        m(t, n) {
            Mn(t, e, n),
            Er(o, e, null),
            i = !0
        },
        p(t, e) {
            const i = {};
            16 & e[0] && (i.elasticity = t[4] * Su),
            16 & e[1] && (i.scrollDirection = t[35] ? "y" : "x"),
            524288 & e[0] | 3 & e[1] | 2048 & e[12] && (i.$$scope = {
                dirty: e,
                ctx: t
            }),
            o.$set(i)
        },
        i(t) {
            i || (br(o.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            i = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o)
        }
    }
}
function gu(t) {
    let e, o = t[382].icon + "";
    return {
        c() {
            e = Pn("g")
        },
        m(t, i) {
            Mn(t, e, i),
            e.innerHTML = o
        },
        p(t, i) {
            1024 & i[12] && o !== (o = t[382].icon + "") && (e.innerHTML = o)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function fu(t) {
    let e, o, i, n, r, a = t[382].label + "";
    return e = new Al({
        props: {
            $$slots: {
                default: [gu]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment),
            o = An(),
            i = Rn("span"),
            n = In(a)
        },
        m(t, a) {
            Er(e, t, a),
            Mn(t, o, a),
            Mn(t, i, a),
            kn(i, n),
            r = !0
        },
        p(t, o) {
            const i = {};
            3072 & o[12] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i),
            (!r || 1024 & o[12]) && a !== (a = t[382].label + "") && Dn(n, a)
        },
        i(t) {
            r || (br(e.$$.fragment, t),
            r = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            r = !1
        },
        d(t) {
            Lr(e, t),
            t && Tn(o),
            t && Tn(i)
        }
    }
}
function $u(t) {
    let e, o;
    const i = [t[31], {
        tabs: t[32]
    }];
    let n = {
        $$slots: {
            default: [fu, ({tab: t})=>({
                382: t
            }), ({tab: t})=>[0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, t ? 1024 : 0]]
        },
        $$scope: {
            ctx: t
        }
    };
    for (let t = 0; t < i.length; t += 1)
        n = on(n, i[t]);
    return e = new pl({
        props: n
    }),
    e.$on("select", t[272]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const n = 3 & o[1] ? Rr(i, [1 & o[1] && Pr(t[31]), 2 & o[1] && {
                tabs: t[32]
            }]) : {};
            3072 & o[12] && (n.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(n)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function yu(t) {
    let e, o, i;
    function n(e) {
        t[281](e)
    }
    let r = {
        class: "PinturaMain",
        content: {
            ...t[20].find(t[280]),
            props: t[7][t[19]]
        },
        locale: t[2],
        isAnimated: t[18],
        stores: t[118]
    };
    return void 0 !== t[0][t[19]] && (r.component = t[0][t[19]]),
    e = new Rl({
        props: r
    }),
    ir.push((()=>Ir(e, "component", n))),
    e.$on("measure", t[282]),
    e.$on("show", t[283]),
    e.$on("hide", t[284]),
    e.$on("fade", t[285]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, o) {
            Er(e, t, o),
            i = !0
        },
        p(t, i) {
            const n = {};
            1572992 & i[0] && (n.content = {
                ...t[20].find(t[280]),
                props: t[7][t[19]]
            }),
            4 & i[0] && (n.locale = t[2]),
            262144 & i[0] && (n.isAnimated = t[18]),
            !o && 524289 & i[0] && (o = !0,
            n.component = t[0][t[19]],
            cr((()=>o = !1))),
            e.$set(n)
        },
        i(t) {
            i || (br(e.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            i = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function xu(t) {
    let e, o;
    const i = [{
        class: "PinturaMain"
    }, {
        visible: t[28]
    }, t[31], {
        panels: t[33]
    }];
    let n = {
        $$slots: {
            default: [bu, ({panel: t})=>({
                381: t
            }), ({panel: t})=>[0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, t ? 512 : 0]]
        },
        $$scope: {
            ctx: t
        }
    };
    for (let t = 0; t < i.length; t += 1)
        n = on(n, i[t]);
    return e = new kl({
        props: n
    }),
    e.$on("measure", t[279]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const n = 268435456 & o[0] | 5 & o[1] ? Rr(i, [i[0], 268435456 & o[0] && {
                visible: t[28]
            }, 1 & o[1] && Pr(t[31]), 4 & o[1] && {
                panels: t[33]
            }]) : {};
            272367749 & o[0] | 32768 & o[1] | 2560 & o[12] && (n.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(n)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function bu(t) {
    let e, o, i;
    function n(...e) {
        return t[273](t[381], ...e)
    }
    function r(e) {
        t[274](e, t[381])
    }
    let a = {
        content: {
            ...t[20].find(n),
            props: t[7][t[381]]
        },
        locale: t[2],
        isActive: t[381] === t[19],
        isAnimated: t[18],
        stores: t[118]
    };
    return void 0 !== t[0][t[381]] && (a.component = t[0][t[381]]),
    e = new Rl({
        props: a
    }),
    ir.push((()=>Ir(e, "component", r))),
    e.$on("measure", t[275]),
    e.$on("show", (function() {
        return t[276](t[381])
    }
    )),
    e.$on("hide", (function() {
        return t[277](t[381])
    }
    )),
    e.$on("fade", (function(...e) {
        return t[278](t[381], ...e)
    }
    )),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, o) {
            Er(e, t, o),
            i = !0
        },
        p(i, r) {
            t = i;
            const a = {};
            1048704 & r[0] | 512 & r[12] && (a.content = {
                ...t[20].find(n),
                props: t[7][t[381]]
            }),
            4 & r[0] && (a.locale = t[2]),
            524288 & r[0] | 512 & r[12] && (a.isActive = t[381] === t[19]),
            262144 & r[0] && (a.isAnimated = t[18]),
            !o && 1 & r[0] | 512 & r[12] && (o = !0,
            a.component = t[0][t[381]],
            cr((()=>o = !1))),
            e.$set(a)
        },
        i(t) {
            i || (br(e.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            i = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function vu(t) {
    let e, o;
    return {
        c() {
            e = Rn("span"),
            Bn(e, "class", "PinturaEditorOverlay"),
            Bn(e, "style", o = "opacity:" + t[57])
        },
        m(t, o) {
            Mn(t, e, o)
        },
        p(t, i) {
            67108864 & i[1] && o !== (o = "opacity:" + t[57]) && Bn(e, "style", o)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function wu(t) {
    let e, o, i, r, a;
    lr(t[270]);
    let s = t[41] && ou(t)
      , l = t[57] > 0 && vu(t);
    return {
        c() {
            e = Rn("div"),
            s && s.c(),
            o = An(),
            l && l.c(),
            Bn(e, "id", t[3]),
            Bn(e, "class", t[34]),
            Bn(e, "data-env", t[36])
        },
        m(c, d) {
            Mn(c, e, d),
            s && s.m(e, null),
            kn(e, o),
            l && l.m(e, null),
            t[288](e),
            i = !0,
            r || (a = [Ln(eu, "keydown", t[134]), Ln(eu, "keyup", t[135]), Ln(eu, "blur", t[136]), Ln(eu, "paste", t[140]), Ln(eu, "resize", t[270]), Ln(e, "ping", (function() {
                sn(t[42]) && t[42].apply(this, arguments)
            }
            )), Ln(e, "contextmenu", t[137]), Ln(e, "touchstart", t[130], {
                passive: !1
            }), Ln(e, "touchmove", t[131]), Ln(e, "pointermove", t[132]), Ln(e, "transitionend", t[119]), Ln(e, "dropfiles", t[138]), Ln(e, "measure", t[289]), Ln(e, "click", (function() {
                sn(t[24] ? t[139] : n) && (t[24] ? t[139] : n).apply(this, arguments)
            }
            )), yn(bs.call(null, e, {
                observeViewRect: !0
            })), yn(vs.call(null, e)), yn(Ss.call(null, e))],
            r = !0)
        },
        p(n, r) {
            (t = n)[41] ? s ? (s.p(t, r),
            1024 & r[1] && br(s, 1)) : (s = ou(t),
            s.c(),
            br(s, 1),
            s.m(e, o)) : s && (yr(),
            vr(s, 1, 1, (()=>{
                s = null
            }
            )),
            xr()),
            t[57] > 0 ? l ? l.p(t, r) : (l = vu(t),
            l.c(),
            l.m(e, null)) : l && (l.d(1),
            l = null),
            (!i || 8 & r[0]) && Bn(e, "id", t[3]),
            (!i || 8 & r[1]) && Bn(e, "class", t[34]),
            (!i || 32 & r[1]) && Bn(e, "data-env", t[36])
        },
        i(t) {
            i || (br(s),
            i = !0)
        },
        o(t) {
            vr(s),
            i = !1
        },
        d(o) {
            o && Tn(e),
            s && s.d(),
            l && l.d(),
            t[288](null),
            r = !1,
            an(a)
        }
    }
}
const Su = 10;
function Cu(t, e, o) {
    let i, r, a, s, l, c, d, u, m, f, $, y, x, b, v, S, C, k, M, R, P, I, A, E, L, F, z, B, O, D, W, V, H, N, U, j, X, q, J, Q, tt, et, ot, it, at, st, lt, ct, dt, ut, ht, pt, mt, gt, ft, $t, yt, bt, vt, wt, St, Ct, Mt, Tt, Rt, Pt, It, Ft, zt, Bt, Ot, Xt, Yt, Zt, Kt, Qt, te, oe, ne, re, ae, se, le, ce, ue, he, pe, me, ge, fe, $e, ye, xe, be, ve, we, Se, Ce, ke, Me, Te, Re, Pe, Ie, Ae, Ee, Le, ze, Be, Oe, De, _e, We, Ve, He, Ne, Ue, je, Xe, Ye, Ge, Ze, Ke, Je, Qe, to, eo, oo, io, ro, ao, so, lo, co, uo, po, mo, go, fo, $o, yo, xo, bo, vo, wo, So, Co = tn, ko = tn;
    un(t, cs, (t=>o(211, Ae = t))),
    t.$$.on_destroy.push((()=>Co())),
    t.$$.on_destroy.push((()=>ko()));
    const Mo = ho()
      , To = Jn();
    let {class: Ro} = e
      , {layout: Po} = e
      , {stores: Ao} = e
      , {locale: Lo} = e
      , {id: Fo} = e
      , {util: zo} = e
      , {utils: Bo} = e
      , {animations: Oo="auto"} = e
      , {disabled: Do=!1} = e
      , {status: Vo} = e
      , {previewUpscale: Ho=!1} = e
      , {elasticityMultiplier: No=10} = e
      , {willRevert: Xo=(()=>Promise.resolve(!0))} = e
      , {willProcessImage: Yo=(()=>Promise.resolve(!0))} = e
      , {willRenderCanvas: qo=_} = e
      , {willRenderToolbar: Zo=_} = e
      , {willSetHistoryInitialState: Ko=_} = e
      , {enableButtonExport: Jo=!0} = e
      , {enableButtonRevert: Qo=!0} = e
      , {enableNavigateHistory: ti=!0} = e
      , {enableToolbar: ei=!0} = e
      , {enableUtils: oi=!0} = e
      , {enableButtonClose: ii=!1} = e
      , {enableDropImage: ni=!1} = e
      , {enablePasteImage: ri=!1} = e
      , {enableBrowseImage: ai=!1} = e
      , {previewImageDataMaxSize: si} = e
      , {layoutDirectionPreference: li="auto"} = e
      , {layoutHorizontalUtilsPreference: ci="left"} = e
      , {layoutVerticalUtilsPreference: di="bottom"} = e
      , {imagePreviewSrc: ui} = e
      , {imageOrienter: hi={
        read: ()=>1,
        apply: t=>t
    }} = e
      , {pluginComponents: pi} = e
      , {pluginOptions: mi={}} = e;
    const gi = Mo.sub
      , fi = {};
    let {root: $i} = e
      , yi = [];
    const xi = ls();
    un(t, xi, (t=>o(57, So = t)));
    const bi = Ys() || 1024
      , vi = xt(bi, bi)
      , wi = Ca();
    let {imageSourceToImageData: Si=(t=>w(t) ? fetch(t).then((t=>{
        if (200 !== t.status)
            throw `${t.status} (${t.statusText})`;
        return t.blob()
    }
    )).then((t=>Ud(t, hi, wi))).then((t=>jd(t, a))) : de(t) ? new Promise((e=>e(h(t)))) : Ud(t, hi, wi).then((t=>jd(t, a))))} = e;
    const ki = (()=>{
        let t, e;
        const o = ["file", "size", "loadState", "processState", "cropAspectRatio", "cropLimitToImage", "crop", "cropMinSize", "cropMaxSize", "cropRange", "cropOrigin", "cropRectAspectRatio", "rotation", "rotationRange", "targetSize", "flipX", "flipY", "perspectiveX", "perspectiveY", "perspective", "colorMatrix", "convolutionMatrix", "gamma", "vignette", "noise", "redaction", "decoration", "annotation", "frame", "backgroundColor", "state"]
          , i = o.reduce(((t,o)=>(t[o] = function(t, e, o) {
            let i = [];
            return {
                set: e,
                update: o,
                publish: t=>{
                    i.forEach((e=>e(t)))
                }
                ,
                subscribe: e=>(i.push(e),
                t(e),
                ()=>{
                    i = i.filter((t=>t !== e))
                }
                )
            }
        }((t=>{
            if (!e)
                return t();
            e.stores[o].subscribe(t)()
        }
        ), (t=>{
            e && e.stores[o].set(t)
        }
        ), (t=>{
            e && e.stores[o].update(t)
        }
        )),
        t)), {});
        return {
            update: n=>{
                if (e = n,
                t && (t.forEach((t=>t())),
                t = void 0),
                !n)
                    return i.file.publish(void 0),
                    void i.loadState.publish(void 0);
                t = o.map((t=>n.stores[t].subscribe((e=>{
                    i[t].publish(e)
                }
                ))))
            }
            ,
            stores: i,
            destroy: ()=>{
                t && t.forEach((t=>t()))
            }
        }
    }
    )()
      , {file: Mi, size: Ti, loadState: Ri, processState: Ai, cropAspectRatio: Ei, cropLimitToImage: Fi, crop: zi, cropMinSize: Bi, cropMaxSize: Oi, cropRange: Di, cropOrigin: _i, cropRectAspectRatio: Wi, rotation: Vi, rotationRange: Hi, targetSize: Ni, flipX: Ui, flipY: ji, backgroundColor: Xi, colorMatrix: Yi, convolutionMatrix: Gi, gamma: qi, vignette: Zi, noise: Ki, decoration: en, annotation: on, redaction: nn, frame: rn, state: an} = ki.stores;
    un(t, Mi, (t=>o(206, Re = t))),
    un(t, Ti, (t=>o(191, $e = t))),
    un(t, Ri, (t=>o(185, ne = t))),
    un(t, Ai, (t=>o(249, Xe = t))),
    un(t, Ei, (t=>o(297, te = t))),
    un(t, zi, (t=>o(186, re = t))),
    un(t, Ni, (t=>o(190, ge = t))),
    un(t, Xi, (t=>o(269, oo = t))),
    un(t, en, (t=>o(54, bo = t))),
    un(t, on, (t=>o(53, xo = t))),
    un(t, nn, (t=>o(266, to = t))),
    un(t, rn, (t=>o(55, vo = t))),
    un(t, an, (t=>o(306, Ee = t)));
    const {images: sn, shapePreprocessor: ln, imageScrambler: hn, willRequestResource: pn} = Ao;
    un(t, sn, (t=>o(182, Zt = t))),
    un(t, ln, (t=>o(183, Kt = t))),
    un(t, hn, (t=>o(268, eo = t))),
    un(t, pn, (t=>o(52, yo = t)));
    const mn = an.subscribe((t=>Mo.pub("update", t)))
      , gn = _r();
    un(t, gn, (t=>o(49, go = t)));
    const fn = _r([0, 0, 0]);
    un(t, fn, (t=>o(48, mo = t)));
    const yn = _r([1, 1, 1]);
    un(t, yn, (t=>o(308, io = t)));
    const xn = ls();
    un(t, xn, (t=>o(309, ro = t)));
    const bn = _r()
      , vn = _r();
    un(t, vn, (t=>o(16, Qt = t)));
    const wn = _r();
    un(t, wn, (t=>o(184, oe = t)));
    const Sn = _r(Et());
    un(t, Sn, (t=>o(30, Le = t)));
    const Cn = _r(Et());
    un(t, Cn, (t=>o(45, co = t)));
    const kn = _r();
    un(t, kn, (t=>o(46, uo = t)));
    const Mn = Nd("(pointer: fine)", (t=>t ? "pointer-fine" : "pointer-coarse"));
    un(t, Mn, (t=>o(235, De = t)));
    const Tn = Nd("(hover: hover)", (t=>t ? "pointer-hover" : "pointer-no-hover"));
    un(t, Tn, (t=>o(236, _e = t)));
    const Rn = _r(!1);
    un(t, Rn, (t=>o(187, se = t)));
    const Pn = Dr(void 0, (t=>{
        const e = ls(0)
          , o = [Rn.subscribe((t=>{
            e.set(t ? 1 : 0)
        }
        )), e.subscribe(t)];
        return ()=>o.forEach((t=>t()))
    }
    ));
    un(t, Pn, (t=>o(310, ao = t)));
    const In = _r(Ho);
    un(t, In, (t=>o(300, ce = t)));
    const An = _r();
    un(t, An, (t=>o(299, le = t)));
    const En = _r();
    un(t, En, (t=>o(298, ae = t)));
    const Ln = Dr(void 0, (t=>{
        const e = ls(void 0, {
            precision: 1e-4
        })
          , o = [zi.subscribe((()=>{
            if (!re)
                return;
            const t = void 0 === ae || se
              , o = Nl(re, ae, 5 * No);
            e.set(o, {
                hard: t
            })
        }
        )), e.subscribe(t)];
        return ()=>o.forEach((t=>t()))
    }
    ))
      , Fn = _r();
    un(t, Fn, (t=>o(301, ue = t)));
    const zn = _r();
    un(t, zn, (t=>o(305, xe = t)));
    const Bn = _r(void 0);
    un(t, Bn, (t=>o(302, pe = t)));
    let On = {
        left: 0,
        right: 0,
        top: 0,
        bottom: 0
    };
    const Dn = Wr([rn, Fn], (([t,e],o)=>{
        e || o(On);
        let i = jn(e, t);
        Io(On.top, 4) === Io(i.top, 4) && Io(On.bottom, 4) === Io(i.bottom, 4) && Io(On.right, 4) === Io(i.right, 4) && Io(On.left, 4) === Io(i.left, 4) || (On = i,
        o(i))
    }
    ))
      , _n = Wr([Dn], (([t],e)=>{
        e(Object.values(t).some((t=>t > 0)))
    }
    ));
    let Wn = {
        left: 0,
        right: 0,
        top: 0,
        bottom: 0
    };
    const Vn = Wr([gn, rn, Fn], (([t,e,o],i)=>{
        let n;
        o || i(Wn),
        n = "frame" === t ? jn(o, e) : {
            left: 0,
            right: 0,
            top: 0,
            bottom: 0
        },
        Io(Wn.top, 4) === Io(n.top, 4) && Io(Wn.bottom, 4) === Io(n.bottom, 4) && Io(Wn.right, 4) === Io(n.right, 4) && Io(Wn.left, 4) === Io(n.left, 4) || (Wn = n,
        i(n))
    }
    ))
      , Hn = Wr([Vn], (([t],e)=>{
        e(Object.values(t).some((t=>t > 0)))
    }
    ));
    un(t, Hn, (t=>o(50, fo = t)));
    const Nn = Wr([kn, Sn, Cn, Vn], (([t,e,o,n],r)=>{
        if (!t)
            return r(void 0);
        let a = 0;
        1 !== v.length || i || (a = o.y + o.height),
        r(Dt(t.x + e.x + n.top, t.y + e.y + a + n.top, t.width - (n.left + n.right), t.height - (n.top + n.bottom)))
    }
    ));
    un(t, Nn, (t=>o(189, me = t)));
    const Un = Wr([Nn, zi], (([t,e],o)=>{
        if (!t || !e || !(!le && !ae))
            return;
        const i = Math.min(t.width / e.width, t.height / e.height);
        o(ce ? i : Math.min(1, i))
    }
    ));
    un(t, Un, (t=>o(303, fe = t)));
    const jn = (t,e)=>{
        if (!e || !t)
            return {
                top: 0,
                right: 0,
                bottom: 0,
                left: 0
            };
        const o = Ii(e, t, s)
          , i = Pi(o, t);
        return {
            top: Math.abs(i.top),
            right: Math.abs(i.right),
            bottom: Math.abs(i.bottom),
            left: Math.abs(i.left)
        }
    }
      , Xn = Dr(void 0, (t=>{
        const e = ls(void 0, {
            precision: 1e-4
        })
          , o = ()=>{
            if (!ue)
                return;
            const t = se || !he
              , o = Nl(ue, pe, 1 * No);
            if (o.width < 0 && (o.width = 0,
            o.x = ue.x),
            o.height < 0 && (o.height = 0,
            o.y = ue.y),
            Wt(o, me),
            re && "resize" === M) {
                const t = ge || re;
                Vt(o, t.width / ue.width || t.height / ue.height)
            }
            e.set(o, {
                hard: t
            })
        }
          , i = [Nn.subscribe(o), Fn.subscribe(o), Ni.subscribe(o), rn.subscribe(o), e.subscribe(t)];
        return ()=>i.forEach((t=>t()))
    }
    ));
    let Yn;
    un(t, Xn, (t=>o(38, Ue = t)));
    const Gn = t=>{
        if (i && Yn && Ut(Yn, t))
            return;
        Yn = t;
        const e = re.width <= t.width && re.height <= t.height ? qt(t, Ht(At(re), fe || 1)) : Jt(t, jt(re || $e));
        Fn.set(e)
    }
    ;
    let qn = !1;
    const Zn = Un.subscribe((t=>{
        !qn && void 0 !== t && re && (Gn(me),
        qn = !0)
    }
    ))
      , tr = Nn.subscribe((t=>{
        t && void 0 !== fe && re && Gn(t)
    }
    ));
    let er;
    const or = zn.subscribe((t=>{
        if (!t)
            return er = void 0,
            void $n(An, le = void 0, le);
        er = ye;
        const e = At(re);
        An.set(e)
    }
    ))
      , nr = Fn.subscribe((t=>{
        if (!t || !xe)
            return;
        const e = (o = At(t),
        i = xe,
        o.x -= i.x,
        o.y -= i.y,
        o.width -= i.width,
        o.height -= i.height,
        o);
        var o, i;
        Nt(e, er);
        const n = ((t,e)=>(t.x += e.x,
        t.y += e.y,
        t.width += e.width,
        t.height += e.height,
        t))(At(le), e);
        zi.set(n)
    }
    ))
      , rr = zi.subscribe((t=>{
        if (se || xe || ae)
            return;
        if (!t || !ue)
            return;
        const e = jt(ue)
          , o = jt(t);
        if (Io(e, 6) === Io(o, 6))
            return;
        const i = Math.min(me.width / re.width, me.height / re.height)
          , n = xt(t.width * i, t.height * i)
          , r = .5 * (ue.width - n.width)
          , a = .5 * (ue.height - n.height)
          , s = Dt(ue.x + r, ue.y + a, n.width, n.height);
        Fn.set(s)
    }
    ))
      , ar = Wr([Un, zi, Fn], (([t,e,o],i)=>{
        if (!t || !e || !o)
            return;
        const n = o.width / e.width
          , r = o.height / e.height;
        i(Math.max(n, r) / t)
    }
    ))
      , sr = Wr([Un, ar], (([t,e],o)=>{
        if (!e)
            return;
        o(t * e)
    }
    ));
    un(t, sr, (t=>o(304, ye = t)));
    const lr = ls(.075, {
        stiffness: .03,
        damping: .4,
        precision: .001
    })
      , cr = Wr([Xn, Dn], (([t,e],o)=>{
        if (!t)
            return;
        let {x: i, y: n, width: r, height: a} = t
          , {left: s, right: l, top: c, bottom: d} = e;
        if ("resize" === M) {
            const t = ge || re
              , e = t.width / ue.width || t.height / ue.height;
            s *= e,
            l *= e,
            c *= e,
            d *= e
        }
        o({
            x: i - s,
            y: n - l,
            width: r + s + l,
            height: a + c + d
        })
    }
    ));
    un(t, cr, (t=>o(194, ve = t)));
    const dr = Wr([xn, lr, Xn, rn, _n, Dn], (([t,e,o,n,r,a],s)=>{
        if (!o || i)
            return s([]);
        let {x: l, y: c, width: d, height: u} = o;
        l += .5,
        c += .5,
        d -= .5,
        u -= .5;
        const h = [];
        if (r) {
            e > .1 && h.push({
                x: l,
                y: c,
                width: d - .5,
                height: u - .5,
                strokeWidth: 1,
                strokeColor: t,
                opacity: e
            });
            let {left: o, right: i, top: n, bottom: r} = a;
            if ("resize" === M) {
                const t = ge || re
                  , e = t.width / ue.width || t.height / ue.height;
                o *= e,
                i *= e,
                n *= e,
                r *= e
            }
            return void s([...h, {
                x: l - o,
                y: c - i,
                width: d + o + i,
                height: u + n + r,
                strokeWidth: 1,
                strokeColor: t,
                opacity: .05
            }])
        }
        const p = Qd(t)
          , m = n && n.frameColor && Qd(n.frameColor);
        if (p && m || !p && !p) {
            const t = p ? [1, 1, 1, .3] : [0, 0, 0, .075];
            h.push({
                x: l,
                y: c,
                width: d,
                height: u,
                strokeWidth: 3.5,
                strokeColor: t,
                opacity: e
            })
        }
        s([...h, {
            x: l,
            y: c,
            width: d,
            height: u,
            strokeWidth: 1,
            strokeColor: t,
            opacity: e
        }])
    }
    ))
      , ur = _r([]);
    un(t, ur, (t=>o(205, Te = t)));
    const hr = Wr([dr, ur], (([t,e],o)=>{
        o([...t, ...e])
    }
    ));
    un(t, hr, (t=>o(56, wo = t)));
    const pr = (t,e,o)=>{
        const i = p("canvas", {
            width: Math.max(1, t),
            height: Math.max(1, e)
        }).getContext("2d");
        let n = i.createLinearGradient(0, 0, t, e);
        return [[0, 0], [.013, .081], [.049, .155], [.104, .225], [.175, .29], [.259, .353], [.352, .412], [.45, .471], [.55, .529], [.648, .588], [.741, .647], [.825, .71], [.896, .775], [.951, .845], [.987, .919], [1, 1]].forEach((([t,e])=>n.addColorStop(e, `rgba(${255 * o[0]}, ${255 * o[1]}, ${255 * o[2]}, ${t})`))),
        i.fillStyle = n,
        i.fillRect(0, 0, i.canvas.width, i.canvas.height),
        n = void 0,
        i.canvas
    }
      , mr = ls(40);
    un(t, mr, (t=>o(193, be = t)));
    const gr = ls(70);
    un(t, gr, (t=>o(196, we = t)));
    const fr = ls(0);
    un(t, fr, (t=>o(201, ke = t)));
    const $r = ls(0);
    un(t, $r, (t=>o(203, Me = t)));
    const yr = ls(0);
    un(t, yr, (t=>o(197, Se = t)));
    const xr = ls(0);
    let br, vr;
    un(t, xr, (t=>o(199, Ce = t)));
    const wr = fn.subscribe((t=>{
        t && (br && g(br),
        vr && g(vr),
        o(174, br = pr(16, 0, t)),
        o(174, br.dataset.retain = 1, br),
        o(175, vr = pr(0, 16, t)),
        o(175, vr.dataset.retain = 1, vr))
    }
    ))
      , Sr = _r(!1);
    un(t, Sr, (t=>o(246, je = t)));
    const Cr = _r();
    un(t, Cr, (t=>o(208, Ie = t)));
    let kr;
    const Mr = Wr([Sr, Cr], (([t,e],i)=>{
        if (t && e) {
            if (kr && (kr.cancel(),
            o(176, kr = void 0)),
            Li(e))
                return i((t=>{
                    const e = p("canvas", {
                        width: t.width,
                        height: t.height
                    });
                    return e.getContext("2d").drawImage(t, 0, 0),
                    e
                }
                )(e));
            var r, a;
            o(176, kr = {
                cancel: n
            }),
            (r = e,
            a = kr,
            new Promise(((t,e)=>{
                const o = oa.length ? 0 : 250;
                let i, n = !1;
                a.cancel = ()=>n = !0;
                const s = Date.now();
                Si(r).then((e=>{
                    const r = Date.now() - s;
                    clearTimeout(i),
                    i = setTimeout((()=>{
                        n || t(e)
                    }
                    ), Math.max(0, o - r))
                }
                )).catch(e)
            }
            ))).then(i).catch((t=>{
                $n(Ri, ne.error = t, ne)
            }
            )).finally((()=>{
                o(176, kr = void 0)
            }
            ))
        } else
            i(void 0)
    }
    ));
    Co(),
    Co = cn(Mr, (t=>o(207, Pe = t)));
    let {imagePreviewCurrent: Tr} = e;
    const Rr = _r({});
    un(t, Rr, (t=>o(238, Ve = t)));
    const Pr = _r([]);
    un(t, Pr, (t=>o(51, $o = t)));
    const Ir = Wr([Nn, wn, Ti, Ln, Fn, sr, Vi, Ui, ji, Ni], (([t,e,o,i,n,r,a,s,l,c],d)=>{
        if (t) {
            if ("resize" === M) {
                const t = c || i;
                r = t.width / i.width || t.height / i.height
            }
            d(((t,e,o,i,n,r,a,s,l,c,d)=>{
                if (!(t && e && o && i && r))
                    return;
                const u = ie(At(e))
                  , h = _t(u)
                  , p = _t(t)
                  , m = Lt(o)
                  , g = _t(m)
                  , f = Y(a, s)
                  , $ = ia(o, i, l)
                  , y = _t($)
                  , x = rt(Z(g), y)
                  , b = rt(Z(p), h);
                x.x += b.x,
                x.y += b.y;
                const v = K(Z(x));
                v.x += b.x,
                v.y += b.y;
                const w = _t(Wt(At(n), t))
                  , S = rt(w, p);
                return nt(x, S),
                {
                    origin: v,
                    translation: x,
                    rotation: {
                        x: d ? Math.PI : 0,
                        y: c ? Math.PI : 0,
                        z: l
                    },
                    perspective: f,
                    scale: r
                }
            }
            )(t, e, o, i, n, r, 0, 0, a, s, l))
        }
    }
    ));
    un(t, Ir, (t=>o(237, We = t)));
    const Ar = Wr([Yi, Gi, qi, Zi, Ki], (([t,e,o,i,n],r)=>{
        const a = t && Object.keys(t).map((e=>t[e])).filter(Boolean);
        r({
            gamma: o || void 0,
            vignette: i || void 0,
            noise: n || void 0,
            convolutionMatrix: e || void 0,
            colorMatrix: a && a.length && Ji(a)
        })
    }
    ));
    let Er, Lr;
    const Fr = (()=>{
        if (!Fe())
            return !1;
        const t = navigator.userAgent.match(/OS (\d+)_(\d+)_?(\d+)?/i) || []
          , [,e,o] = t.map((t=>parseInt(t, 10) || 0));
        return e > 13 || 13 === e && o >= 4
    }
    )()
      , zr = _r({});
    un(t, zr, (t=>o(234, Oe = t)));
    const Br = hc()
      , Or = Dr(Br, (t=>{
        const e = ()=>t(hc())
          , o = matchMedia(`(resolution: ${Br}dppx)`);
        return o.addListener(e),
        ()=>o.removeListener(e)
    }
    ));
    un(t, Or, (t=>o(47, po = t)));
    const Vr = _r();
    un(t, Vr, (t=>o(18, he = t)));
    const Hr = ((t,e)=>{
        const {sub: o, pub: i} = ho()
          , n = []
          , r = _r(0)
          , a = []
          , s = ()=>a.forEach((t=>t({
            index: dn(r),
            length: n.length
        })))
          , l = {
            get index() {
                return dn(r)
            },
            set index(t) {
                t = Number.isInteger(t) ? t : 0,
                t = na(t, 0, n.length - 1),
                r.set(t),
                e(n[l.index]),
                s()
            },
            get state() {
                return n[n.length - 1]
            },
            length: ()=>n.length,
            undo() {
                const t = l.index--;
                return i("undo", t),
                t
            },
            redo() {
                const t = l.index++;
                return i("redo", l.index),
                t
            },
            revert() {
                n.length = 1,
                l.index = 0,
                i("revert")
            },
            write(o) {
                o && e({
                    ...t(),
                    ...o
                });
                const i = t()
                  , a = n[n.length - 1];
                JSON.stringify(i) !== JSON.stringify(a) && (n.length = l.index + 1,
                n.push(i),
                r.set(n.length - 1),
                s())
            },
            set(t={}) {
                n.length = 0,
                l.index = 0;
                const e = Array.isArray(t) ? t : [t];
                n.push(...e),
                l.index = n.length - 1
            },
            get: ()=>[...n],
            subscribe: t=>(a.push(t),
            t({
                index: l.index,
                length: n.length
            }),
            ()=>a.splice(a.indexOf(t), 1)),
            on: o
        };
        return l
    }
    )((()=>Ee), (t=>{
        $n(an, Ee = t, Ee),
        Sn.set(Le)
    }
    ));
    ko(),
    ko = cn(Hr, (t=>o(213, ze = t)));
    const Nr = ()=>{
        const t = {
            x: 0,
            y: 0,
            ...$e
        }
          , e = ee(Jt(t, Ee.cropAspectRatio), Math.round)
          , o = Ko({
            ...Ee,
            rotation: 0,
            crop: e
        }, Ee)
          , i = [o];
        JSON.stringify(o) !== JSON.stringify(Ee) && i.push({
            ...Ee
        }),
        Hr.set(i)
    }
      , Ur = Ri.subscribe((t=>{
        t && t.complete && Nr()
    }
    ))
      , jr = ()=>Xo().then((t=>t && Hr.revert()))
      , Xr = _r(!1);
    un(t, Xr, (t=>o(215, Be = t)));
    const Yr = ()=>{
        $n(Xr, Be = !0, Be),
        Yo().then((t=>{
            if (!t)
                return void $n(Xr, Be = !1, Be);
            let e;
            e = ha.subscribe((t=>{
                1 === t && (e && e(),
                To("processImage"))
            }
            ))
        }
        ))
    }
      , Gr = Ai.subscribe((t=>{
        if (!t)
            return;
        $n(Xr, Be = !0, Be);
        const {complete: e, abort: o} = t;
        (e || o) && $n(Xr, Be = !1, Be)
    }
    ))
      , qr = {
        ...Ao,
        imageFile: Mi,
        imageSize: Ti,
        imageBackgroundColor: Xi,
        imageCropAspectRatio: Ei,
        imageCropMinSize: Bi,
        imageCropMaxSize: Oi,
        imageCropLimitToImage: Fi,
        imageCropRect: zi,
        imageCropRectOrigin: _i,
        imageCropRectSnapshot: An,
        imageCropRectAspectRatio: Wi,
        imageCropRange: Di,
        imageRotation: Vi,
        imageRotationRange: Hi,
        imageFlipX: Ui,
        imageFlipY: ji,
        imageOutputSize: Ni,
        imageColorMatrix: Yi,
        imageConvolutionMatrix: Gi,
        imageGamma: qi,
        imageVignette: Zi,
        imageNoise: Ki,
        imageDecoration: en,
        imageAnnotation: on,
        imageRedaction: nn,
        imageFrame: rn,
        imagePreview: Mr,
        imagePreviewSource: Cr,
        imageTransforms: Ir,
        imagePreviewModifiers: Rr,
        history: Hr,
        animation: Vr,
        pixelRatio: Or,
        elasticityMultiplier: No,
        scrollElasticity: Su,
        rangeInputElasticity: 5,
        pointerAccuracy: Mn,
        pointerHoverable: Tn,
        env: zr,
        rootRect: wn,
        stageRect: Nn,
        stageScalar: Un,
        framePadded: _n,
        utilRect: kn,
        presentationScalar: sr,
        rootBackgroundColor: fn,
        rootForegroundColor: yn,
        rootLineColor: xn,
        rootColorSecondary: bn,
        imageOutlineOpacity: lr,
        imageOverlayMarkup: ur,
        interfaceImages: Pr,
        isInteracting: Rn,
        isInteractingFraction: Pn,
        imageCropRectIntent: En,
        imageCropRectPresentation: Ln,
        imageSelectionRect: Fn,
        imageSelectionRectIntent: Bn,
        imageSelectionRectPresentation: Xn,
        imageSelectionRectSnapshot: zn,
        imageScalar: ar
    };
    delete qr.image;
    const Zr = "util-" + T();
    let Kr = []
      , Jr = Fe();
    const Qr = (t,e)=>{
        const o = (t=>{
            const e = et.getPropertyValue(t);
            return Xd(e)
        }
        )(t);
        o && 0 !== o[3] && (o.length = 3,
        e.set(o))
    }
      , ta = ()=>{
        Qr("color", yn),
        Qr("background-color", fn),
        Qr("outline-color", xn),
        Qr("--color-secondary", bn)
    }
      , ea = Wr([Ir, Ar, Xi], (([t,e,o])=>t && {
        ...t,
        ...e,
        backgroundColor: o
    }));
    un(t, ea, (t=>o(240, He = t)));
    const oa = Jd();
    un(t, oa, (t=>o(22, Ne = t)));
    const ra = ()=>{
        const t = oa.length ? void 0 : {
            resize: 1.05
        }
          , e = ((t,e,o={})=>{
            const {resize: i=1, opacity: n=0} = o
              , r = {
                opacity: [ls(n, {
                    ...Zd,
                    stiffness: .1
                }), _],
                resize: [ls(i, {
                    ...Zd,
                    stiffness: .1
                }), _],
                translation: [ls(void 0, Zd), _],
                rotation: [ls(void 0, Kd), _],
                origin: [ls(void 0, Zd), _],
                scale: [ls(void 0, Kd), _],
                gamma: [ls(void 0, Kd), t=>t || 1],
                vignette: [ls(void 0, Kd), t=>t || 0],
                colorMatrix: [ls([...qd], Zd), t=>t || [...qd]],
                convolutionMatrix: [_r(void 0), t=>t && t.clarity || void 0],
                backgroundColor: [ls(void 0, Zd), _]
            }
              , a = Object.entries(r).map((([t,e])=>[t, e[0]]))
              , s = a.map((([,t])=>t))
              , l = Object.entries(r).reduce(((t,[e,o])=>{
                const [i,n] = o;
                return t[e] = (t,e)=>i.set(n(t), e),
                t
            }
            ), {});
            let c;
            const d = Wr(s, (o=>(c = o.reduce(((t,e,o)=>(t[a[o][0]] = e,
            t)), {}),
            c.data = t,
            c.size = e,
            c.scale *= o[1],
            c)));
            return d.get = ()=>c,
            d.set = (t,e)=>{
                const o = {
                    hard: !e
                };
                Object.entries(t).forEach((([t,e])=>{
                    l[t] && l[t](e, o)
                }
                ))
            }
            ,
            d
        }
        )(Pe, $e, t);
        oa.unshift(e),
        aa(He)
    }
      , aa = t=>{
        oa.forEach(((e,o)=>{
            const i = 0 === o ? 1 : 0;
            e.set({
                ...t,
                opacity: i,
                resize: 1
            }, he)
        }
        ))
    }
    ;
    let sa;
    const la = (t,e)=>{
        const o = {
            width: Ue.width / e.scale,
            height: Ue.height / e.scale
        };
        return Ci(t, o)
    }
      , ca = (t,e)=>(t._translate = G(Ue),
    t._scale = e.scale,
    t)
      , da = t=>{
        const e = [];
        return t.forEach((t=>e.push(ua(t)))),
        e.filter(Boolean)
    }
      , ua = t=>Uo(t) ? (t.points = [Y(t.x1, t.y1), Y(t.x2, t.y2)],
    t) : jo(t) ? (t.points = [Y(t.x1, t.y1), Y(t.x2, t.y2), Y(t.x3, t.y3)],
    t) : (t=>_o(t) && !t.text.length)(t) ? (Wo(t) && (t.width = 5,
    t.height = t.lineHeight),
    t.strokeWidth = 1,
    t.strokeColor = [1, 1, 1, .5],
    t.backgroundColor = [0, 0, 0, .1],
    t) : t
      , ha = as(void 0, {
        duration: 500
    });
    let pa;
    un(t, ha, (t=>o(25, Ge = t)));
    const ma = _r(!1);
    let ga;
    un(t, ma, (t=>o(258, Ye = t)));
    const fa = ls(void 0, {
        stiffness: .1,
        damping: .7,
        precision: .25
    });
    un(t, fa, (t=>o(43, so = t)));
    const $a = ls(0, {
        stiffness: .1,
        precision: .05
    });
    un(t, $a, (t=>o(44, lo = t)));
    const ya = ls(0, {
        stiffness: .02,
        damping: .5,
        precision: .25
    });
    un(t, ya, (t=>o(262, Ke = t)));
    const xa = ls(void 0, {
        stiffness: .02,
        damping: .5,
        precision: .25
    });
    un(t, xa, (t=>o(260, Ze = t)));
    const ba = ls(void 0, {
        stiffness: .02,
        damping: .5,
        precision: .25
    });
    let va;
    un(t, ba, (t=>o(263, Je = t)));
    const wa = ()=>{
        To("abortLoadImage")
    }
      , Sa = ()=>{
        To("abortProcessImage"),
        $n(Xr, Be = !1, Be)
    }
      , ka = t=>t.preventDefault()
      , Ma = Fr ? t=>{
        const e = t.touches ? t.touches[0] : t;
        e.pageX > 10 && e.pageX < Er - 10 || ka(t)
    }
    : n
      , Ta = Fe() ? ka : n
      , Ra = Fe() ? ka : n
      , Pa = _r([]);
    un(t, Pa, (t=>o(307, Qe = t))),
    Qn("keysPressed", Pa);
    const Ia = t=>{
        !t || no(t) && !(t=>/^image/.test(t.type) && !/svg/.test(t.type))(t) || !no(t) && !/^http/.test(t) || To("loadImage", t)
    }
      , Aa = t=>{
        t && Ia(t)
    }
    ;
    let Ea = void 0;
    let La = [];
    const Fa = ()=>({
        foregroundColor: [...io],
        lineColor: [...ro],
        utilVisibility: {
            ...P
        },
        isInteracting: se,
        isInteractingFraction: ao,
        rootRect: At(oe),
        stageRect: At(me),
        selectionRect: At(Ue)
    })
      , za = (t,e,o,i,n,r)=>({
        blendShapes: e.filter(Go).map((t=>Ci(t, $e))),
        annotationShapes: da(o.filter(Go).map(Eo).map((t=>Ci(t, $e))).map(s).flat()),
        decorationShapes: da(i.filter(Go).map(Eo).map((e=>la(e, t))).map(s).flat().map((e=>ca(e, t)))),
        interfaceShapes: da(n.filter(Go)),
        frameShapes: da(r.map(Eo).map((e=>la(e, t))).map(s).flat().map((e=>ca(e, t))))
    });
    let Ba;
    const Oa = _r();
    Qn("rootPortal", Oa),
    Qn("rootRect", wn),
    Kn((()=>{
        mn(),
        tr(),
        Zn(),
        or(),
        nr(),
        rr(),
        wr(),
        Ur(),
        Gr(),
        Mn.destroy(),
        Tn.destroy(),
        ki.destroy(),
        oa.clear(),
        o(144, Tr = void 0),
        o(177, sa = void 0),
        br && (g(br),
        o(174, br = void 0)),
        vr && (g(vr),
        o(175, vr = void 0))
    }
    ));
    return t.$$set = t=>{
        "class"in t && o(145, Ro = t.class),
        "layout"in t && o(146, Po = t.layout),
        "stores"in t && o(147, Ao = t.stores),
        "locale"in t && o(2, Lo = t.locale),
        "id"in t && o(3, Fo = t.id),
        "util"in t && o(148, zo = t.util),
        "utils"in t && o(149, Bo = t.utils),
        "animations"in t && o(150, Oo = t.animations),
        "disabled"in t && o(151, Do = t.disabled),
        "status"in t && o(143, Vo = t.status),
        "previewUpscale"in t && o(152, Ho = t.previewUpscale),
        "elasticityMultiplier"in t && o(4, No = t.elasticityMultiplier),
        "willRevert"in t && o(153, Xo = t.willRevert),
        "willProcessImage"in t && o(154, Yo = t.willProcessImage),
        "willRenderCanvas"in t && o(5, qo = t.willRenderCanvas),
        "willRenderToolbar"in t && o(155, Zo = t.willRenderToolbar),
        "willSetHistoryInitialState"in t && o(156, Ko = t.willSetHistoryInitialState),
        "enableButtonExport"in t && o(157, Jo = t.enableButtonExport),
        "enableButtonRevert"in t && o(158, Qo = t.enableButtonRevert),
        "enableNavigateHistory"in t && o(159, ti = t.enableNavigateHistory),
        "enableToolbar"in t && o(6, ei = t.enableToolbar),
        "enableUtils"in t && o(160, oi = t.enableUtils),
        "enableButtonClose"in t && o(161, ii = t.enableButtonClose),
        "enableDropImage"in t && o(162, ni = t.enableDropImage),
        "enablePasteImage"in t && o(163, ri = t.enablePasteImage),
        "enableBrowseImage"in t && o(164, ai = t.enableBrowseImage),
        "previewImageDataMaxSize"in t && o(165, si = t.previewImageDataMaxSize),
        "layoutDirectionPreference"in t && o(166, li = t.layoutDirectionPreference),
        "layoutHorizontalUtilsPreference"in t && o(167, ci = t.layoutHorizontalUtilsPreference),
        "layoutVerticalUtilsPreference"in t && o(168, di = t.layoutVerticalUtilsPreference),
        "imagePreviewSrc"in t && o(169, ui = t.imagePreviewSrc),
        "imageOrienter"in t && o(170, hi = t.imageOrienter),
        "pluginComponents"in t && o(171, pi = t.pluginComponents),
        "pluginOptions"in t && o(7, mi = t.pluginOptions),
        "root"in t && o(1, $i = t.root),
        "imageSourceToImageData"in t && o(8, Si = t.imageSourceToImageData),
        "imagePreviewCurrent"in t && o(144, Tr = t.imagePreviewCurrent)
    }
    ,
    t.$$.update = ()=>{
        if (4194304 & t.$$.dirty[4] && o(181, i = "overlay" === Po),
        67108896 & t.$$.dirty[5] && o(15, r = oi && !i),
        129 & t.$$.dirty[0] && mi && Object.entries(mi).forEach((([t,e])=>{
            Object.entries(e).forEach((([e,i])=>{
                fi[t] && o(0, fi[t][e] = i, fi)
            }
            ))
        }
        )),
        1 & t.$$.dirty[0] | 65536 & t.$$.dirty[5]) {
            let t = !1;
            pi.forEach((([e])=>{
                fi[e] || (o(0, fi[e] = {}, fi),
                t = !0)
            }
            )),
            t && o(173, yi = [...pi])
        }
        var e, n, h, p;
        if (134217728 & t.$$.dirty[4] && xi.set(Do ? 1 : 0),
        1024 & t.$$.dirty[5] && (a = si ? (e = si,
        n = vi,
        xt(Math.min(e.width, n.width), Math.min(e.height, n.height))) : vi),
        134217728 & t.$$.dirty[5] && ki.update(Zt[0]),
        268435456 & t.$$.dirty[5] && (s = Kt ? t=>Kt(t, {
            isPreview: !0
        }) : _),
        65536 & t.$$.dirty[0] && Qt && wn.set(Dt(Qt.x, Qt.y, Qt.width, Qt.height)),
        1677721600 & t.$$.dirty[5] && oe && i && ne && ne.complete && (()=>{
            const t = te
              , e = jt(oe);
            t && t === e || (Ei.set(jt(oe)),
            Nr())
        }
        )(),
        4 & t.$$.dirty[0] | 33554432 & t.$$.dirty[4] | 262144 & t.$$.dirty[5] && o(188, v = Lo && yi.length ? Bo || yi.map((([t])=>t)) : []),
        4 & t.$$.dirty[6] && o(17, L = v.length > 1),
        131072 & t.$$.dirty[0] && (L || Sn.set(Et())),
        64 & t.$$.dirty[0] && (ei || Cn.set(Et())),
        268435456 & t.$$.dirty[4] | 67108864 & t.$$.dirty[5] && In.set(Ho || i),
        262144 & t.$$.dirty[5] | 4 & t.$$.dirty[6] && o(216, S = yi.filter((([t])=>v.includes(t)))),
        1073741824 & t.$$.dirty[6] && o(217, C = S.length),
        16777216 & t.$$.dirty[4] | 4 & t.$$.dirty[6] | 1 & t.$$.dirty[7] && o(19, M = zo && "string" == typeof zo && v.includes(zo) ? zo : C > 0 ? v[0] : void 0),
        524288 & t.$$.dirty[0] && M && lr.set(.075),
        524288 & t.$$.dirty[0] && mr.set("resize" === M ? 40 : 30),
        524288 & t.$$.dirty[0] && gr.set("resize" === M ? 140 : 70),
        17 & t.$$.dirty[6] && o(192, l = re && ((t,e)=>{
            let {width: o, height: i} = t;
            const n = jt(e);
            return o && i ? t : (o && !i && (i = o / n),
            i && !o && (o = i * n),
            o || i || (o = e.width,
            i = e.height),
            kt(xt(o, i), Math.round))
        }
        )(ge || {}, re)),
        456 & t.$$.dirty[6] && l && me && yr.set(Wd(me.y, me.y - be, ve.y)),
        456 & t.$$.dirty[6] && l && me && $r.set(Wd(me.x + me.width, me.x + me.width + be, ve.x + ve.width)),
        456 & t.$$.dirty[6] && l && me && xr.set(Wd(me.y + me.height, me.y + me.height + be, ve.y + ve.height)),
        456 & t.$$.dirty[6] && l && me && fr.set(Wd(me.x, me.x - be, ve.x)),
        537919488 & t.$$.dirty[5] | 3072 & t.$$.dirty[6] && o(195, c = oe && {
            id: "stage-overlay",
            x: 0,
            y: 0,
            width: oe.width,
            height: we,
            rotation: Math.PI,
            opacity: .85 * Se,
            backgroundImage: vr
        }),
        537919488 & t.$$.dirty[5] | 9216 & t.$$.dirty[6] && o(198, d = oe && {
            id: "stage-overlay",
            x: 0,
            y: oe.height - we,
            width: oe.width,
            height: we,
            opacity: .85 * Ce,
            backgroundImage: vr
        }),
        537395200 & t.$$.dirty[5] | 33792 & t.$$.dirty[6] && o(200, u = oe && {
            id: "stage-overlay",
            x: 0,
            y: 0,
            height: oe.height,
            width: we,
            rotation: Math.PI,
            opacity: .85 * ke,
            backgroundImage: br
        }),
        537395200 & t.$$.dirty[5] | 132096 & t.$$.dirty[6] && o(202, m = oe && {
            id: "stage-overlay",
            x: oe.width - we,
            y: 0,
            height: oe.height,
            width: we,
            opacity: .85 * Me,
            backgroundImage: br
        }),
        86528 & t.$$.dirty[6] && o(204, f = [c, m, d, u].filter(Boolean)),
        786432 & t.$$.dirty[6] && f && Te) {
            const t = Te.filter((t=>"stage-overlay" !== t.id));
            $n(ur, Te = [...t, ...f], Te)
        }
        if (16384 & t.$$.dirty[5] | 1048576 & t.$$.dirty[6] && Cr.set(ui || (Re || void 0)),
        2 & t.$$.dirty[0] | 1048576 & t.$$.dirty[4] | 2097152 & t.$$.dirty[6] && (o(144, Tr = Pe),
        Pe && $i.dispatchEvent(Vd("loadpreview", Tr))),
        4194304 & t.$$.dirty[6] && Ie && Pr.set([]),
        2 & t.$$.dirty[6] && o(209, $ = !se && !Rs()),
        33554432 & t.$$.dirty[6] && o(210, y = !Ae),
        67108864 & t.$$.dirty[4] | 25165824 & t.$$.dirty[6] && $n(Vr, he = "always" === Oo ? $ : "never" !== Oo && ($ && y), he),
        134217728 & t.$$.dirty[6] && o(212, x = ze.index > 0),
        134217728 & t.$$.dirty[6] && o(214, b = ze.index < ze.length - 1),
        4 & t.$$.dirty[0] | 1073741828 & t.$$.dirty[6] && o(20, k = v.map((t=>{
            const e = S.find((([e])=>t === e));
            if (e)
                return {
                    id: t,
                    view: e[1],
                    tabIcon: Lo[t + "Icon"],
                    tabLabel: Lo[t + "Label"]
                }
        }
        )).filter(Boolean) || []),
        524288 & t.$$.dirty[0] && gn.set(M),
        524289 & t.$$.dirty[0] && o(218, R = M && fi[M].tools || []),
        3145728 & t.$$.dirty[0] && o(21, P = k.reduce(((t,e)=>(t[e.id] = P && P[e.id] || 0,
        t)), {})),
        524288 & t.$$.dirty[0] && o(31, I = {
            name: Zr,
            selected: M
        }),
        1048576 & t.$$.dirty[0] && o(32, A = k.map((t=>({
            id: t.id,
            icon: t.tabIcon,
            label: t.tabLabel
        })))),
        1048576 & t.$$.dirty[0] && o(33, E = k.map((t=>t.id))),
        2097152 & t.$$.dirty[4] && o(34, F = rl(["PinturaRoot", "PinturaRootComponent", Ro])),
        536870912 & t.$$.dirty[5] && o(219, z = oe && (oe.width > 1e3 ? "wide" : oe.width < 600 ? "narrow" : void 0)),
        536870912 & t.$$.dirty[5] && o(220, B = oe && (oe.width <= 320 || oe.height <= 460)),
        536870912 & t.$$.dirty[5] && o(221, O = oe && (oe.height > 1e3 ? "tall" : oe.height < 600 ? "short" : void 0)),
        2 & t.$$.dirty[0] && o(222, D = $i && $i.parentNode && $i.parentNode.classList.contains("PinturaModal")),
        2048 & t.$$.dirty[0] | 536870912 & t.$$.dirty[5] | 32 & t.$$.dirty[7] && o(223, W = D && oe && Er > oe.width),
        4096 & t.$$.dirty[0] | 536870912 & t.$$.dirty[5] | 32 & t.$$.dirty[7] && o(224, V = D && oe && Lr > oe.height),
        192 & t.$$.dirty[7] && o(225, H = W && V),
        4 & t.$$.dirty[7] && o(226, N = "narrow" === z),
        536872960 & t.$$.dirty[5] && o(227, (h = oe,
        p = li,
        U = oe ? "auto" === p ? h.width > h.height ? "landscape" : "portrait" : "horizontal" === p ? h.width < 500 ? "portrait" : "landscape" : "vertical" === p ? h.height < 400 ? "landscape" : "portrait" : void 0 : "landscape")),
        1024 & t.$$.dirty[7] && o(35, j = "landscape" === U),
        528 & t.$$.dirty[7] && o(228, X = N || "short" === O),
        2048 & t.$$.dirty[0] | 536870912 & t.$$.dirty[5] && o(229, q = Jr && oe && Er === oe.width && !Fr),
        67108864 & t.$$.dirty[5] | 18 & t.$$.dirty[7] && o(230, J = R.length && ("short" === O || i)),
        4096 & t.$$.dirty[5] && o(231, Q = "has-navigation-preference-" + ci),
        8192 & t.$$.dirty[5] && o(232, tt = "has-navigation-preference-" + di),
        2 & t.$$.dirty[0] && o(233, et = $i && getComputedStyle($i)),
        65536 & t.$$.dirty[7] && et && ta(),
        426048 & t.$$.dirty[0] | 138412032 & t.$$.dirty[4] | 974332 & t.$$.dirty[7] && zr.set({
            ...Oe,
            layoutMode: Po,
            orientation: U,
            horizontalSpace: z,
            verticalSpace: O,
            navigationHorizontalPreference: Q,
            navigationVerticalPreference: tt,
            isModal: D,
            isDisabled: Do,
            isCentered: H,
            isCenteredHorizontally: W,
            isCenteredVertically: V,
            isAnimated: he,
            pointerAccuracy: De,
            pointerHoverable: _e,
            isCompact: X,
            hasSwipeNavigation: q,
            hasLimitedSpace: B,
            hasToolbar: ei,
            hasNavigation: L && r,
            isIOS: Jr
        }),
        131072 & t.$$.dirty[7] && o(36, ot = Object.entries(Oe).map((([t,e])=>/^is|has/.test(t) ? e ? Hd(t) : void 0 : e)).filter(Boolean).join(" ")),
        3145728 & t.$$.dirty[7] && o(37, it = We && Object.entries(Ve).filter((([,t])=>null != t)).reduce(((t,[,e])=>t = {
            ...t,
            ...e
        }), {})),
        1073741824 & t.$$.dirty[5] && o(239, ct = ne && "any-to-file" === ne.task),
        4194304 & t.$$.dirty[7] && ct && oa && oa.clear(),
        8388608 & t.$$.dirty[7] && o(241, at = !!He && !!He.translation),
        4194304 & t.$$.dirty[5] | 2097152 & t.$$.dirty[6] | 16777216 & t.$$.dirty[7] && at && Pe && Pe !== sa && (o(177, sa = Pe),
        ra()),
        25165824 & t.$$.dirty[7] && at && aa(He),
        4194304 & t.$$.dirty[0] && Ne && Ne.length > 1) {
            let t = [];
            oa.forEach(((e,o)=>{
                0 !== o && e.get().opacity <= 0 && t.push(e)
            }
            )),
            t.forEach((t=>oa.remove(t)))
        }
        if (4 & t.$$.dirty[0] | 33554432 & t.$$.dirty[7] && o(23, lt = Lo && st.length && Lo.labelSupportError(st)),
        1073741824 & t.$$.dirty[5] && o(243, dt = ne && !!ne.error),
        1073741824 & t.$$.dirty[5] && o(24, ut = !ne || !ne.complete && void 0 === ne.task),
        1073741824 & t.$$.dirty[5] && o(244, ht = ne && (ne.taskLengthComputable ? ne.taskProgress : 1 / 0)),
        4194304 & t.$$.dirty[7] && ct && $n(Sr, je = !1, je),
        1082130432 & t.$$.dirty[5] && ne && ne.complete) {
            const t = 500;
            clearTimeout(pa),
            o(178, pa = setTimeout((()=>{
                $n(Sr, je = !0, je)
            }
            ), t))
        }
        if (16777216 & t.$$.dirty[0] | 1073741824 & t.$$.dirty[5] | 603979776 & t.$$.dirty[7] && o(245, pt = ne && !dt && !ut && !je),
        2097152 & t.$$.dirty[5] | 6291456 & t.$$.dirty[6] && o(247, mt = !(!Ie || Pe && !kr)),
        536870912 & t.$$.dirty[6] | 2 & t.$$.dirty[8] && o(248, gt = Be || Xe && void 0 !== Xe.progress && !Xe.complete),
        16777216 & t.$$.dirty[0] | 1073741824 & t.$$.dirty[5] && o(250, ft = ne && !(ne.error || ut)),
        4 & t.$$.dirty[0] | 1073741824 & t.$$.dirty[5] && o(251, $t = Lo && (ne ? !ne.complete || ne.error ? Is(Lo.statusLabelLoadImage(ne), ne.error && ne.error.metadata, "{", "}") : Lo.statusLabelLoadImage({
            progress: 1 / 0,
            task: "blob-to-bitmap"
        }) : Lo.statusLabelLoadImage(ne))),
        4 & t.$$.dirty[0] | 2 & t.$$.dirty[8] && o(252, yt = Xe && Lo && Lo.statusLabelProcessImage(Xe)),
        2 & t.$$.dirty[8] && o(253, bt = Xe && (Xe.taskLengthComputable ? Xe.taskProgress : 1 / 0)),
        2 & t.$$.dirty[8] && o(254, vt = Xe && !Xe.error),
        2 & t.$$.dirty[8] && o(255, wt = Xe && Xe.error),
        16777220 & t.$$.dirty[0] | 524288 & t.$$.dirty[4] | 1543503872 & t.$$.dirty[7] | 253 & t.$$.dirty[8])
            if (Vo) {
                let t, e, i, n, r;
                w(Vo) && (t = Vo),
                qe(Vo) ? e = Vo : Array.isArray(Vo) && ([t,e,r] = Vo,
                !1 === e && (n = !0),
                qe(e) && (i = !0)),
                o(13, ga = (t || e) && {
                    text: t,
                    aside: n || i,
                    progressIndicator: {
                        visible: i,
                        progress: e
                    },
                    closeButton: n && {
                        label: Lo.statusLabelButtonClose,
                        icon: Lo.statusIconButtonClose,
                        onclick: r || (()=>o(143, Vo = void 0))
                    }
                })
            } else
                o(13, ga = Lo && ut || dt || pt || mt ? {
                    text: $t,
                    aside: dt || ft,
                    progressIndicator: {
                        visible: ft,
                        progress: ht
                    },
                    closeButton: dt && {
                        label: Lo.statusLabelButtonClose,
                        icon: Lo.statusIconButtonClose,
                        onclick: wa
                    }
                } : Lo && gt && yt ? {
                    text: yt,
                    aside: wt || vt,
                    progressIndicator: {
                        visible: vt,
                        progress: bt
                    },
                    closeButton: wt && {
                        label: Lo.statusLabelButtonClose,
                        icon: Lo.statusIconButtonClose,
                        onclick: Sa
                    }
                } : void 0);
        if (524288 & t.$$.dirty[4] && o(256, St = void 0 !== Vo),
        32 & t.$$.dirty[7] | 2 & t.$$.dirty[8] && D && Xe && Xe.complete && (ma.set(!0),
        setTimeout((()=>ma.set(!1)), 100)),
        25165824 & t.$$.dirty[0] | 1409286144 & t.$$.dirty[7] | 1281 & t.$$.dirty[8] && o(257, Ct = Ye || lt || ut || dt || pt || mt || gt || St),
        512 & t.$$.dirty[8] && $n(ha, Ge = Ct ? 1 : 0, Ge),
        33554432 & t.$$.dirty[0] && o(26, Mt = Ge > 0),
        8192 & t.$$.dirty[0] && o(259, Tt = !(!ga || !ga.aside)),
        67117056 & t.$$.dirty[0] | 16777216 & t.$$.dirty[5] | 6144 & t.$$.dirty[8] && Mt && ga)
            if (clearTimeout(va),
            Tt) {
                const t = !!ga.error;
                $a.set(1),
                fa.set(Ze, {
                    hard: t
                }),
                o(179, va = setTimeout((()=>{
                    ya.set(16)
                }
                ), 1))
            } else
                $a.set(0),
                o(179, va = setTimeout((()=>{
                    ya.set(0)
                }
                ), 1));
        if (67108864 & t.$$.dirty[0] && (Mt || (ba.set(void 0, {
            hard: !0
        }),
        fa.set(void 0, {
            hard: !0
        }),
        ya.set(0, {
            hard: !0
        }))),
        16384 & t.$$.dirty[8] && o(261, Rt = .5 * Ke),
        40960 & t.$$.dirty[8] && o(39, Pt = `transform: translateX(${Je - Rt}px)`),
        4 & t.$$.dirty[0] | 93 & t.$$.dirty[5] | 335544320 & t.$$.dirty[6] | 139778 & t.$$.dirty[7] && o(40, It = Lo && Zo([["div", "alpha", {
            class: "PinturaNavGroup"
        }, [["div", "alpha-set", {
            class: "PinturaNavSet"
        }, [ii && ["Button", "close", {
            label: Lo.labelClose,
            icon: Lo.iconButtonClose,
            onclick: ()=>To("close"),
            hideLabel: !0
        }], Qo && ["Button", "revert", {
            label: Lo.labelButtonRevert,
            icon: Lo.iconButtonRevert,
            disabled: !x,
            onclick: jr,
            hideLabel: !0
        }]]]]], ["div", "beta", {
            class: "PinturaNavGroup PinturaNavGroupFloat"
        }, [ti && ["div", "history", {
            class: "PinturaNavSet"
        }, [["Button", "undo", {
            label: Lo.labelButtonUndo,
            icon: Lo.iconButtonUndo,
            disabled: !x,
            onclick: Hr.undo,
            hideLabel: !0
        }], ["Button", "redo", {
            label: Lo.labelButtonRedo,
            icon: Lo.iconButtonRedo,
            disabled: !b,
            onclick: Hr.redo,
            hideLabel: !0
        }]]], J && ["div", "plugin-tools", {
            class: "PinturaNavSet"
        }, R.filter(Boolean).map((([t,e,o])=>[t, e, {
            ...o,
            hideLabel: !0
        }]))]]], ["div", "gamma", {
            class: "PinturaNavGroup"
        }, [Jo && ["Button", "export", {
            label: Lo.labelButtonExport,
            icon: N && Lo.iconButtonExport,
            class: "PinturaButtonExport",
            onclick: Yr,
            hideLabel: N
        }]]]], {
            ...Oe
        })),
        65536 & t.$$.dirty[0] && o(264, Ft = Qt && Qt.width > 0 && Qt.height > 0),
        4 & t.$$.dirty[0] | 1 & t.$$.dirty[7] | 65536 & t.$$.dirty[8] && o(41, zt = Ft && Lo && C),
        262144 & t.$$.dirty[8] && o(265, Bt = to && !!to.length),
        32 & t.$$.dirty[6] | 393216 & t.$$.dirty[8] && o(267, Ot = Bt && Qi($e, to)),
        2097152 & t.$$.dirty[6] | 3801088 & t.$$.dirty[8] && Bt && ((t,e,i,n)=>{
            if (!e)
                return;
            const r = {
                dataSizeScalar: i
            };
            n && n[3] > 0 && (r.backgroundColor = [...n]),
            e(t, r).then((t=>{
                Ea && g(Ea),
                o(180, Ea = t)
            }
            ))
        }
        )(Pe, eo, Ot, oo),
        33554432 & t.$$.dirty[5] | 32 & t.$$.dirty[6] | 262144 & t.$$.dirty[8] && to && Ea && $e) {
            const {width: t, height: e} = $e;
            o(29, La = to.map((o=>{
                const i = Dt(o.x, o.y, o.width, o.height)
                  , n = Gt(At(i), o.rotation).map((o=>Y(o.x / t, o.y / e)));
                return {
                    ...o,
                    id: "redaction",
                    flipX: !1,
                    flipY: !1,
                    cornerRadius: 0,
                    strokeWidth: 0,
                    strokeColor: void 0,
                    backgroundColor: [0, 0, 0],
                    backgroundImage: Ea,
                    backgroundImageRendering: "pixelated",
                    backgroundCorners: n
                }
            }
            )))
        }
        16384 & t.$$.dirty[0] && Ba && Oa.set(Ba),
        8388608 & t.$$.dirty[0] | 2097152 & t.$$.dirty[6] && o(27, Yt = Pe && !lt),
        134217730 & t.$$.dirty[0] && Yt && $i.dispatchEvent(Vd("ready"))
    }
    ,
    o(242, st = [!Gd() && "WebGL"].filter(Boolean)),
    o(42, Xt = ((t,e=!0)=>o=>{
        "ping" === o.type && (e && o.stopPropagation(),
        t(o.detail.type, o.detail.data))
    }
    )(Mo.pub)),
    [fi, $i, Lo, Fo, No, qo, ei, mi, Si, Mr, Hr, Er, Lr, ga, Ba, r, Qt, L, he, M, k, P, Ne, lt, ut, Ge, Mt, Yt, Kr, La, Le, I, A, E, F, j, ot, it, Ue, Pt, It, zt, Xt, so, lo, co, uo, po, mo, go, fo, $o, yo, xo, bo, vo, wo, So, xi, Mi, Ti, Ri, Ai, Ei, zi, Ni, Xi, en, on, nn, rn, an, sn, ln, hn, pn, gn, fn, yn, xn, vn, wn, Sn, Cn, kn, Mn, Tn, Rn, Pn, In, An, En, Fn, zn, Bn, Hn, Nn, Un, Xn, sr, cr, ur, hr, mr, gr, fr, $r, yr, xr, Sr, Cr, Rr, Pr, Ir, zr, Or, Vr, Xr, qr, ({target: t, propertyName: e})=>{
        t === $i && /background|outline/.test(e) && ta()
    }
    , ea, oa, ha, ma, fa, $a, ya, xa, ba, t=>{
        const e = !(!ga || !ga.closeButton);
        xa.set(t.detail.width, {
            hard: e
        }),
        ba.set(Math.round(.5 * -t.detail.width), {
            hard: e
        })
    }
    , Ma, Ta, Ra, Pa, t=>{
        const {keyCode: e, metaKey: o, ctrlKey: i, shiftKey: n} = t;
        if (9 === e && Do)
            return void t.preventDefault();
        if (90 === e && (o || i))
            return void (n && o ? Hr.redo() : Hr.undo());
        if (89 === e && i)
            return void Hr.redo();
        if (229 === e)
            return;
        if (o || i)
            return;
        const r = new Set([...Qe, e]);
        Pa.set(Array.from(r))
    }
    , ({keyCode: t})=>{
        Pa.set(Qe.filter((e=>e !== t)))
    }
    , ()=>{
        Pa.set([])
    }
    , t=>{
        var e;
        (t=>/textarea/i.test(t.nodeName))(e = t.target) || (t=>/date|email|number|search|text|url/.test(t.type))(e) || t.preventDefault()
    }
    , t=>{
        ni && Ia(t.detail.resources[0])
    }
    , ()=>{
        ai && tu().then(Aa)
    }
    , t=>{
        if (!ri)
            return;
        const e = na((Er - Math.abs(oe.x)) / oe.width, 0, 1)
          , o = na((Lr - Math.abs(oe.y)) / oe.height, 0, 1);
        e < .75 && o < .75 || Ia((t.clipboardData || window.clipboardData).files[0])
    }
    , Fa, za, Vo, Tr, Ro, Po, Ao, zo, Bo, Oo, Do, Ho, Xo, Yo, Zo, Ko, Jo, Qo, ti, oi, ii, ni, ri, ai, si, li, ci, di, ui, hi, pi, gi, yi, br, vr, kr, sa, pa, va, Ea, i, Zt, Kt, oe, ne, re, se, v, me, ge, $e, l, be, ve, c, we, Se, d, Ce, u, ke, m, Me, f, Te, Re, Pe, Ie, $, y, Ae, x, ze, b, Be, S, C, R, z, B, O, D, W, V, H, N, U, X, q, J, Q, tt, et, Oe, De, _e, We, Ve, ct, He, at, st, dt, ht, pt, je, mt, gt, Xe, ft, $t, yt, bt, vt, wt, St, Ct, Ye, Tt, Ze, Rt, Ke, Je, Ft, Bt, to, Ot, eo, oo, function() {
        o(11, Er = eu.innerWidth),
        o(12, Lr = eu.innerHeight)
    }
    , t=>$n(Cn, co = t.detail, co), ({detail: t})=>o(19, M = t), (t,e)=>e.id === t, function(e, i) {
        t.$$.not_equal(fi[i], e) && (fi[i] = e,
        o(0, fi),
        o(7, mi),
        o(171, pi))
    }
    , t=>$n(kn, uo = t.detail, uo), t=>o(28, Kr = Kr.concat(t)), t=>o(28, Kr = Kr.filter((e=>e !== t))), (t,{detail: e})=>o(21, P[t] = e, P), t=>$n(Sn, Le = t.detail, Le), t=>t.id === M, function(e) {
        t.$$.not_equal(fi[M], e) && (fi[M] = e,
        o(0, fi),
        o(7, mi),
        o(171, pi))
    }
    , t=>$n(kn, uo = t.detail, uo), ()=>o(28, Kr = Kr.concat(M)), ()=>o(28, Kr = Kr.filter((t=>t !== M))), ({detail: t})=>o(21, P[M] = t, P), t=>{
        const e = {
            ...t,
            ...Fa()
        }
          , {annotationShapes: o, decorationShapes: i, interfaceShapes: n, frameShapes: r} = qo({
            annotationShapes: xo,
            decorationShapes: bo,
            frameShapes: vo ? [vo] : [],
            interfaceShapes: wo
        }, e);
        return za(e, La, o, i, n, r)
    }
    , function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            Ba = t,
            o(14, Ba)
        }
        ))
    }
    , function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            $i = t,
            o(1, $i)
        }
        ))
    }
    , t=>$n(vn, Qt = t.detail, Qt)]
}
class ku extends Br {
    constructor(t) {
        super(),
        zr(this, t, Cu, wu, ln, {
            class: 145,
            layout: 146,
            stores: 147,
            locale: 2,
            id: 3,
            util: 148,
            utils: 149,
            animations: 150,
            disabled: 151,
            status: 143,
            previewUpscale: 152,
            elasticityMultiplier: 4,
            willRevert: 153,
            willProcessImage: 154,
            willRenderCanvas: 5,
            willRenderToolbar: 155,
            willSetHistoryInitialState: 156,
            enableButtonExport: 157,
            enableButtonRevert: 158,
            enableNavigateHistory: 159,
            enableToolbar: 6,
            enableUtils: 160,
            enableButtonClose: 161,
            enableDropImage: 162,
            enablePasteImage: 163,
            enableBrowseImage: 164,
            previewImageDataMaxSize: 165,
            layoutDirectionPreference: 166,
            layoutHorizontalUtilsPreference: 167,
            layoutVerticalUtilsPreference: 168,
            imagePreviewSrc: 169,
            imageOrienter: 170,
            pluginComponents: 171,
            pluginOptions: 7,
            sub: 172,
            pluginInterface: 0,
            root: 1,
            imageSourceToImageData: 8,
            imagePreview: 9,
            imagePreviewCurrent: 144,
            history: 10
        }, [-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1])
    }
    get class() {
        return this.$$.ctx[145]
    }
    set class(t) {
        this.$set({
            class: t
        }),
        hr()
    }
    get layout() {
        return this.$$.ctx[146]
    }
    set layout(t) {
        this.$set({
            layout: t
        }),
        hr()
    }
    get stores() {
        return this.$$.ctx[147]
    }
    set stores(t) {
        this.$set({
            stores: t
        }),
        hr()
    }
    get locale() {
        return this.$$.ctx[2]
    }
    set locale(t) {
        this.$set({
            locale: t
        }),
        hr()
    }
    get id() {
        return this.$$.ctx[3]
    }
    set id(t) {
        this.$set({
            id: t
        }),
        hr()
    }
    get util() {
        return this.$$.ctx[148]
    }
    set util(t) {
        this.$set({
            util: t
        }),
        hr()
    }
    get utils() {
        return this.$$.ctx[149]
    }
    set utils(t) {
        this.$set({
            utils: t
        }),
        hr()
    }
    get animations() {
        return this.$$.ctx[150]
    }
    set animations(t) {
        this.$set({
            animations: t
        }),
        hr()
    }
    get disabled() {
        return this.$$.ctx[151]
    }
    set disabled(t) {
        this.$set({
            disabled: t
        }),
        hr()
    }
    get status() {
        return this.$$.ctx[143]
    }
    set status(t) {
        this.$set({
            status: t
        }),
        hr()
    }
    get previewUpscale() {
        return this.$$.ctx[152]
    }
    set previewUpscale(t) {
        this.$set({
            previewUpscale: t
        }),
        hr()
    }
    get elasticityMultiplier() {
        return this.$$.ctx[4]
    }
    set elasticityMultiplier(t) {
        this.$set({
            elasticityMultiplier: t
        }),
        hr()
    }
    get willRevert() {
        return this.$$.ctx[153]
    }
    set willRevert(t) {
        this.$set({
            willRevert: t
        }),
        hr()
    }
    get willProcessImage() {
        return this.$$.ctx[154]
    }
    set willProcessImage(t) {
        this.$set({
            willProcessImage: t
        }),
        hr()
    }
    get willRenderCanvas() {
        return this.$$.ctx[5]
    }
    set willRenderCanvas(t) {
        this.$set({
            willRenderCanvas: t
        }),
        hr()
    }
    get willRenderToolbar() {
        return this.$$.ctx[155]
    }
    set willRenderToolbar(t) {
        this.$set({
            willRenderToolbar: t
        }),
        hr()
    }
    get willSetHistoryInitialState() {
        return this.$$.ctx[156]
    }
    set willSetHistoryInitialState(t) {
        this.$set({
            willSetHistoryInitialState: t
        }),
        hr()
    }
    get enableButtonExport() {
        return this.$$.ctx[157]
    }
    set enableButtonExport(t) {
        this.$set({
            enableButtonExport: t
        }),
        hr()
    }
    get enableButtonRevert() {
        return this.$$.ctx[158]
    }
    set enableButtonRevert(t) {
        this.$set({
            enableButtonRevert: t
        }),
        hr()
    }
    get enableNavigateHistory() {
        return this.$$.ctx[159]
    }
    set enableNavigateHistory(t) {
        this.$set({
            enableNavigateHistory: t
        }),
        hr()
    }
    get enableToolbar() {
        return this.$$.ctx[6]
    }
    set enableToolbar(t) {
        this.$set({
            enableToolbar: t
        }),
        hr()
    }
    get enableUtils() {
        return this.$$.ctx[160]
    }
    set enableUtils(t) {
        this.$set({
            enableUtils: t
        }),
        hr()
    }
    get enableButtonClose() {
        return this.$$.ctx[161]
    }
    set enableButtonClose(t) {
        this.$set({
            enableButtonClose: t
        }),
        hr()
    }
    get enableDropImage() {
        return this.$$.ctx[162]
    }
    set enableDropImage(t) {
        this.$set({
            enableDropImage: t
        }),
        hr()
    }
    get enablePasteImage() {
        return this.$$.ctx[163]
    }
    set enablePasteImage(t) {
        this.$set({
            enablePasteImage: t
        }),
        hr()
    }
    get enableBrowseImage() {
        return this.$$.ctx[164]
    }
    set enableBrowseImage(t) {
        this.$set({
            enableBrowseImage: t
        }),
        hr()
    }
    get previewImageDataMaxSize() {
        return this.$$.ctx[165]
    }
    set previewImageDataMaxSize(t) {
        this.$set({
            previewImageDataMaxSize: t
        }),
        hr()
    }
    get layoutDirectionPreference() {
        return this.$$.ctx[166]
    }
    set layoutDirectionPreference(t) {
        this.$set({
            layoutDirectionPreference: t
        }),
        hr()
    }
    get layoutHorizontalUtilsPreference() {
        return this.$$.ctx[167]
    }
    set layoutHorizontalUtilsPreference(t) {
        this.$set({
            layoutHorizontalUtilsPreference: t
        }),
        hr()
    }
    get layoutVerticalUtilsPreference() {
        return this.$$.ctx[168]
    }
    set layoutVerticalUtilsPreference(t) {
        this.$set({
            layoutVerticalUtilsPreference: t
        }),
        hr()
    }
    get imagePreviewSrc() {
        return this.$$.ctx[169]
    }
    set imagePreviewSrc(t) {
        this.$set({
            imagePreviewSrc: t
        }),
        hr()
    }
    get imageOrienter() {
        return this.$$.ctx[170]
    }
    set imageOrienter(t) {
        this.$set({
            imageOrienter: t
        }),
        hr()
    }
    get pluginComponents() {
        return this.$$.ctx[171]
    }
    set pluginComponents(t) {
        this.$set({
            pluginComponents: t
        }),
        hr()
    }
    get pluginOptions() {
        return this.$$.ctx[7]
    }
    set pluginOptions(t) {
        this.$set({
            pluginOptions: t
        }),
        hr()
    }
    get sub() {
        return this.$$.ctx[172]
    }
    get pluginInterface() {
        return this.$$.ctx[0]
    }
    get root() {
        return this.$$.ctx[1]
    }
    set root(t) {
        this.$set({
            root: t
        }),
        hr()
    }
    get imageSourceToImageData() {
        return this.$$.ctx[8]
    }
    set imageSourceToImageData(t) {
        this.$set({
            imageSourceToImageData: t
        }),
        hr()
    }
    get imagePreview() {
        return this.$$.ctx[9]
    }
    get imagePreviewCurrent() {
        return this.$$.ctx[144]
    }
    set imagePreviewCurrent(t) {
        this.$set({
            imagePreviewCurrent: t
        }),
        hr()
    }
    get history() {
        return this.$$.ctx[10]
    }
}
(t=>{
    const [e,o,i,n,r,a,s] = ["UmVnRXhw", "dGVzdA==", "cHFpbmFcLm5sfGxvY2FsaG9zdA==", "bG9jYXRpb24=", "Y29uc29sZQ==", "bG9n", "VGhpcyB2ZXJzaW9uIG9mIFBpbnR1cmEgaXMgZm9yIHRlc3RpbmcgcHVycG9zZXMgb25seS4gVmlzaXQgaHR0cHM6Ly9wcWluYS5ubC9waW50dXJhLyB0byBvYnRhaW4gYSBjb21tZXJjaWFsIGxpY2Vuc2Uu"].map(t[[(!1 + "")[1], (!0 + "")[0], (1 + {})[2], (1 + {})[3]].join("")]);
    new t[e](i)[o](t[n]) || t[r][a](s)
}
)(window);
const Mu = ["klass", "stores", "isVisible", "isActive", "isActiveFraction", "locale"]
  , Tu = ["history", "klass", "stores", "navButtons", "pluginComponents", "pluginInterface", "pluginOptions", "sub", "imagePreviewSrc", "imagePreview", "imagePreviewCurrent"];
let Ru;
const Pu = new Set([])
  , Iu = {}
  , Au = new Map
  , Eu = (...t)=>{
    t.filter((t=>!!t.util)).forEach((t=>{
        const [e,o] = t.util;
        Au.has(e) || (Au.set(e, o),
        os(o).filter((t=>!Mu.includes(t))).forEach((t=>{
            Pu.add(t),
            Iu[t] ? Iu[t].push(e) : Iu[t] = [e]
        }
        )))
    }
    ))
}
;
var Lu = ()=>{}
;
const Fu = t=>w(t[0])
  , zu = t=>!Fu(t)
  , Bu = t=>t[1]
  , Ou = t=>t[3] || [];
function Du(t, e, o, i) {
    return Array.isArray(o) && (i = o,
    o = {}),
    [t, e, o || {}, i || []]
}
const _u = (t,e,o,i=(t=>t))=>{
    const n = ju(e, o)
      , r = n.findIndex((t=>Bu(t) === e));
    var a, s, l;
    a = n,
    s = i(r),
    l = t,
    a.splice(s, 0, l)
}
  , Wu = (t,e,o)=>_u(t, e, o)
  , Vu = (t,e,o)=>_u(t, e, o, (t=>t + 1))
  , Hu = (t,e)=>{
    if (zu(e))
        return e.push(t);
    e[3] = [...Ou(e), t]
}
  , Nu = (t,e)=>{
    const o = ju(t, e);
    return _l(o, (e=>Bu(e) === t)),
    o
}
  , Uu = (t,e)=>Fu(e) ? Bu(e) === t ? e : Uu(t, Ou(e)) : e.find((e=>Uu(t, e)))
  , ju = (t,e)=>zu(e) ? e.find((e=>Bu(e) === t)) ? e : e.find((e=>ju(t, Ou(e)))) : ju(t, Ou(e));
let Xu = null;
var Yu = ()=>(null === Xu && (Xu = c() && !("[object OperaMini]" === Object.prototype.toString.call(window.operamini)) && "visibilityState"in document && "Promise"in window && "File"in window && "URL"in window && "createObjectURL"in window.URL && "performance"in window),
Xu)
  , Gu = t=>Math.round(100 * t);
const qu = {
    base: 0,
    min: -.25,
    max: .25,
    getLabel: t=>Gu(t / .25),
    getStore: ({imageColorMatrix: t})=>t,
    getValue: t=>{
        if (t.brightness)
            return t.brightness[4]
    }
    ,
    setValue: (t,e)=>t.update((t=>({
        ...t,
        brightness: [1, 0, 0, 0, e, 0, 1, 0, 0, e, 0, 0, 1, 0, e, 0, 0, 0, 1, 0]
    })))
}
  , Zu = {
    base: 1,
    min: .5,
    max: 1.5,
    getLabel: t=>Gu(2 * (t - .5) - 1),
    getStore: ({imageColorMatrix: t})=>t,
    getValue: t=>{
        if (t.contrast)
            return t.contrast[0]
    }
    ,
    setValue: (t,e)=>t.update((t=>({
        ...t,
        contrast: [e, 0, 0, 0, .5 * (1 - e), 0, e, 0, 0, .5 * (1 - e), 0, 0, e, 0, .5 * (1 - e), 0, 0, 0, 1, 0]
    })))
}
  , Ku = {
    base: 1,
    min: 0,
    max: 2,
    getLabel: t=>Gu(t - 1),
    getStore: ({imageColorMatrix: t})=>t,
    getValue: t=>{
        if (t.saturation)
            return (t.saturation[0] - .213) / .787
    }
    ,
    setValue: (t,e)=>t.update((t=>({
        ...t,
        saturation: [.213 + .787 * e, .715 - .715 * e, .072 - .072 * e, 0, 0, .213 - .213 * e, .715 + .285 * e, .072 - .072 * e, 0, 0, .213 - .213 * e, .715 - .715 * e, .072 + .928 * e, 0, 0, 0, 0, 0, 1, 0]
    })))
}
  , Ju = {
    base: 1,
    min: .5,
    max: 1.5,
    getLabel: t=>Gu(2 * (t - .5) - 1),
    getStore: ({imageColorMatrix: t})=>t,
    getValue: t=>{
        if (t.exposure)
            return t.exposure[0]
    }
    ,
    setValue: (t,e)=>t.update((t=>({
        ...t,
        exposure: [e, 0, 0, 0, 0, 0, e, 0, 0, 0, 0, 0, e, 0, 0, 0, 0, 0, 1, 0]
    })))
}
  , Qu = {
    base: 1,
    min: .15,
    max: 4,
    getLabel: t=>Gu(t < 1 ? (t - .15) / .85 - 1 : (t - 1) / 3),
    getStore: ({imageGamma: t})=>t
}
  , th = {
    base: 0,
    min: -1,
    max: 1,
    getStore: ({imageVignette: t})=>t
}
  , eh = {
    base: 0,
    min: -1,
    max: 1,
    getStore: ({imageConvolutionMatrix: t})=>t,
    getValue: t=>{
        if (t.clarity)
            return 0 === t.clarity[0] ? t.clarity[1] / -1 : t.clarity[1] / -2
    }
    ,
    setValue: (t,e)=>{
        t.update((t=>({
            ...t,
            clarity: e >= 0 ? [0, -1 * e, 0, -1 * e, 1 + 4 * e, -1 * e, 0, -1 * e, 0] : [-1 * e, -2 * e, -1 * e, -2 * e, 1 + -3 * e, -2 * e, -1 * e, -2 * e, -1 * e]
        })))
    }
}
  , oh = {
    base: 0,
    min: -1,
    max: 1,
    getStore: ({imageColorMatrix: t})=>t,
    getValue: t=>{
        if (!t.temperature)
            return;
        const e = t.temperature[0];
        return e >= 1 ? (e - 1) / .1 : (1 - e) / -.15
    }
    ,
    setValue: (t,e)=>t.update((t=>({
        ...t,
        temperature: e > 0 ? [1 + .1 * e, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1 + .1 * -e, 0, 0, 0, 0, 0, 1, 0] : [1 + .15 * e, 0, 0, 0, 0, 0, 1 + .05 * e, 0, 0, 0, 0, 0, 1 + .15 * -e, 0, 0, 0, 0, 0, 1, 0]
    })))
};
var ih = {
    finetuneControlConfiguration: {
        gamma: Qu,
        brightness: qu,
        contrast: Zu,
        saturation: Ku,
        exposure: Ju,
        temperature: oh,
        clarity: eh,
        vignette: th
    },
    finetuneOptions: [["brightness", t=>t.finetuneLabelBrightness], ["contrast", t=>t.finetuneLabelContrast], ["saturation", t=>t.finetuneLabelSaturation], ["exposure", t=>t.finetuneLabelExposure], ["temperature", t=>t.finetuneLabelTemperature], ["gamma", t=>t.finetuneLabelGamma], !Rs() && ["clarity", t=>t.finetuneLabelClarity], ["vignette", t=>t.finetuneLabelVignette]].filter(Boolean)
};
const nh = ()=>[.75, .25, .25, 0, 0, .25, .75, .25, 0, 0, .25, .25, .75, 0, 0, 0, 0, 0, 1, 0]
  , rh = ()=>[1.398, -.316, .065, -.273, .201, -.051, 1.278, -.08, -.273, .201, -.051, .119, 1.151, -.29, .215, 0, 0, 0, 1, 0]
  , ah = ()=>[1.073, -.015, .092, -.115, -.017, .107, .859, .184, -.115, -.017, .015, .077, 1.104, -.115, -.017, 0, 0, 0, 1, 0]
  , sh = ()=>[1.06, 0, 0, 0, 0, 0, 1.01, 0, 0, 0, 0, 0, .93, 0, 0, 0, 0, 0, 1, 0]
  , lh = ()=>[1.1, 0, 0, 0, -.1, 0, 1.1, 0, 0, -.1, 0, 0, 1.2, 0, -.1, 0, 0, 0, 1, 0]
  , ch = ()=>[-1, 0, 0, 1, 0, 0, -1, 0, 1, 0, 0, 0, -1, 1, 0, 0, 0, 0, 1, 0]
  , dh = ()=>[.212, .715, .114, 0, 0, .212, .715, .114, 0, 0, .212, .715, .114, 0, 0, 0, 0, 0, 1, 0]
  , uh = ()=>[.15, 1.3, -.25, .1, -.2, .15, 1.3, -.25, .1, -.2, .15, 1.3, -.25, .1, -.2, 0, 0, 0, 1, 0]
  , hh = ()=>[.163, .518, .084, -.01, .208, .163, .529, .082, -.02, .21, .171, .529, .084, 0, .214, 0, 0, 0, 1, 0]
  , ph = ()=>[.338, .991, .117, .093, -.196, .302, 1.049, .096, .078, -.196, .286, 1.016, .146, .101, -.196, 0, 0, 0, 1, 0]
  , mh = ()=>[.393, .768, .188, 0, 0, .349, .685, .167, 0, 0, .272, .533, .13, 0, 0, 0, 0, 0, 1, 0]
  , gh = ()=>[.289, .62, .185, 0, .077, .257, .566, .163, 0, .115, .2, .43, .128, 0, .188, 0, 0, 0, 1, 0]
  , fh = ()=>[.269, .764, .172, .05, .1, .239, .527, .152, 0, .176, .186, .4, .119, 0, .159, 0, 0, 0, 1, 0]
  , $h = ()=>[.547, .764, .134, 0, -.147, .281, .925, .12, 0, -.135, .225, .558, .33, 0, -.113, 0, 0, 0, 1, 0];
var yh = {
    filterFunctions: {
        chrome: rh,
        fade: ah,
        pastel: nh,
        cold: lh,
        warm: sh,
        monoDefault: dh,
        monoWash: hh,
        monoNoir: uh,
        monoStark: ph,
        sepiaDefault: mh,
        sepiaRust: fh,
        sepiaBlues: gh,
        sepiaColor: $h
    },
    filterOptions: [["Default", [[void 0, t=>t.labelDefault]]], ["Classic", [["chrome", t=>t.filterLabelChrome], ["fade", t=>t.filterLabelFade], ["cold", t=>t.filterLabelCold], ["warm", t=>t.filterLabelWarm], ["pastel", t=>t.filterLabelPastel]]], ["Monochrome", [["monoDefault", t=>t.filterLabelMonoDefault], ["monoNoir", t=>t.filterLabelMonoNoir], ["monoStark", t=>t.filterLabelMonoStark], ["monoWash", t=>t.filterLabelMonoWash]]], ["Sepia", [["sepiaDefault", t=>t.filterLabelSepiaDefault], ["sepiaRust", t=>t.filterLabelSepiaRust], ["sepiaBlues", t=>t.filterLabelSepiaBlues], ["sepiaColor", t=>t.filterLabelSepiaColor]]]]
};
const xh = {
    shape: {
        frameStyle: "solid",
        frameSize: "2.5%"
    },
    thumb: '<rect stroke-width="5" x="0" y="0" width="100%" height="100%"/>'
}
  , bh = {
    shape: {
        frameStyle: "solid",
        frameSize: "2.5%",
        frameRound: !0
    },
    thumb: '<rect stroke-width="5" x="0" y="0" width="100%" height="100%" rx="12%"/>'
}
  , vh = {
    shape: {
        frameStyle: "line",
        frameInset: "2.5%",
        frameSize: ".3125%",
        frameRadius: 0
    },
    thumb: '<div style="top:.5em;left:.5em;right:.5em;bottom:.5em;box-shadow:inset 0 0 0 1px currentColor"></div>'
}
  , wh = {
    shape: {
        frameStyle: "line",
        frameAmount: 2,
        frameInset: "2.5%",
        frameSize: ".3125%",
        frameOffset: "1.25%",
        frameRadius: 0
    },
    thumb: '<div style="top:.75em;left:.75em;right:.75em;bottom:.75em; outline: 3px double"></div>'
}
  , Sh = {
    shape: {
        frameStyle: "edge",
        frameInset: "2.5%",
        frameOffset: "5%",
        frameSize: ".3125%"
    },
    thumb: '<div style="top:.75em;left:.5em;bottom:.75em;border-left:1px solid"></div><div style="top:.75em;right:.5em;bottom:.75em;border-right:1px solid"></div><div style="top:.5em;left:.75em;right:.75em;border-top:1px solid"></div><div style="bottom:.5em;left:.75em;right:.75em;border-bottom:1px solid"></div>'
}
  , Ch = {
    shape: {
        frameStyle: "edge",
        frameInset: "2.5%",
        frameSize: ".3125%"
    },
    thumb: '<div style="top:-.5em;left:.5em;right:.5em;bottom:-.5em; box-shadow: inset 0 0 0 1px currentColor"></div><div style="top:.5em;left:-.5em;right:-.5em;bottom:.5em;box-shadow:inset 0 0 0 1px currentColor"></div>'
}
  , kh = {
    shape: {
        frameStyle: "edge",
        frameOffset: "1.5%",
        frameSize: ".3125%"
    },
    thumb: '<div style="top:.3125em;left:.5em;bottom:.3125em;border-left:1px solid"></div><div style="top:.3125em;right:.5em;bottom:.3125em;border-right:1px solid"></div><div style="top:.5em;left:.3125em;right:.3125em;border-top:1px solid"></div><div style="bottom:.5em;left:.3125em;right:.3125em;border-bottom:1px solid"></div>'
}
  , Mh = {
    shape: {
        frameStyle: "hook",
        frameInset: "2.5%",
        frameSize: ".3125%",
        frameLength: "5%"
    },
    thumb: '<div style="top:.5em;left:.5em;width:.75em;height:.75em; border-left: 1px solid;border-top: 1px solid;"></div><div style="top:.5em;right:.5em;width:.75em;height:.75em; border-right: 1px solid;border-top: 1px solid;"></div><div style="bottom:.5em;left:.5em;width:.75em;height:.75em; border-left: 1px solid;border-bottom: 1px solid;"></div><div style="bottom:.5em;right:.5em;width:.75em;height:.75em; border-right: 1px solid;border-bottom: 1px solid;"></div>'
}
  , Th = {
    shape: {
        frameStyle: "polaroid"
    },
    thumb: '<rect stroke-width="20%" x="-5%" y="-5%" width="110%" height="96%"/>'
};
var Rh = {
    frameStyles: {
        solidSharp: xh,
        solidRound: bh,
        lineSingle: vh,
        lineMultiple: wh,
        edgeSeparate: Sh,
        edgeCross: Ch,
        edgeOverlap: kh,
        hook: Mh,
        polaroid: Th
    },
    frameOptions: [[void 0, t=>t.labelNone], ["solidSharp", t=>t.frameLabelMatSharp], ["solidRound", t=>t.frameLabelMatRound], ["lineSingle", t=>t.frameLabelLineSingle], ["lineMultiple", t=>t.frameLabelLineMultiple], ["edgeCross", t=>t.frameLabelEdgeCross], ["edgeSeparate", t=>t.frameLabelEdgeSeparate], ["edgeOverlap", t=>t.frameLabelEdgeOverlap], ["hook", t=>t.frameLabelCornerHooks], ["polaroid", t=>t.frameLabelPolaroid]]
}
  , Ph = (t,e,o)=>{
    let i, n, r;
    const a = Math.floor(6 * t)
      , s = 6 * t - a
      , l = o * (1 - e)
      , c = o * (1 - s * e)
      , d = o * (1 - (1 - s) * e);
    switch (a % 6) {
    case 0:
        i = o,
        n = d,
        r = l;
        break;
    case 1:
        i = c,
        n = o,
        r = l;
        break;
    case 2:
        i = l,
        n = o,
        r = d;
        break;
    case 3:
        i = l,
        n = c,
        r = o;
        break;
    case 4:
        i = d,
        n = l,
        r = o;
        break;
    case 5:
        i = o,
        n = l,
        r = c
    }
    return [i, n, r]
}
;
function Ih(t) {
    let e, o, i;
    return {
        c() {
            e = Rn("div"),
            o = Rn("span"),
            Bn(e, "class", "PinturaColorPreview"),
            Bn(e, "title", t[0]),
            Bn(e, "style", i = "--color:" + t[1])
        },
        m(t, i) {
            Mn(t, e, i),
            kn(e, o)
        },
        p(t, [o]) {
            1 & o && Bn(e, "title", t[0]),
            2 & o && i !== (i = "--color:" + t[1]) && Bn(e, "style", i)
        },
        i: tn,
        o: tn,
        d(t) {
            t && Tn(e)
        }
    }
}
function Ah(t, e, o) {
    let i, {color: n} = e, {title: r} = e;
    return t.$$set = t=>{
        "color"in t && o(2, n = t.color),
        "title"in t && o(0, r = t.title)
    }
    ,
    t.$$.update = ()=>{
        4 & t.$$.dirty && o(1, i = n ? ao(n) : "transparent")
    }
    ,
    [r, i, n]
}
class Eh extends Br {
    constructor(t) {
        super(),
        zr(this, t, Ah, Ih, ln, {
            color: 2,
            title: 0
        })
    }
}
function Lh(t) {
    let e, o;
    return {
        c() {
            e = Rn("span"),
            o = In(t[0])
        },
        m(t, i) {
            Mn(t, e, i),
            kn(e, o)
        },
        p(t, e) {
            1 & e[0] && Dn(o, t[0])
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function Fh(t) {
    let e, o, i, n;
    o = new Eh({
        props: {
            color: t[4],
            title: Pc(t[8], t[10])
        }
    });
    let r = !t[9] && Lh(t);
    return {
        c() {
            e = Rn("span"),
            Ar(o.$$.fragment),
            i = An(),
            r && r.c(),
            Bn(e, "slot", "label"),
            Bn(e, "class", "PinturaButtonLabel")
        },
        m(t, a) {
            Mn(t, e, a),
            Er(o, e, null),
            kn(e, i),
            r && r.m(e, null),
            n = !0
        },
        p(t, i) {
            const n = {};
            16 & i[0] && (n.color = t[4]),
            1280 & i[0] && (n.title = Pc(t[8], t[10])),
            o.$set(n),
            t[9] ? r && (r.d(1),
            r = null) : r ? r.p(t, i) : (r = Lh(t),
            r.c(),
            r.m(e, null))
        },
        i(t) {
            n || (br(o.$$.fragment, t),
            n = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            n = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o),
            r && r.d()
        }
    }
}
function zh(t) {
    let e, o, i, n, r, a, s, l, c, d, u, h, p;
    c = new vd({
        props: {
            class: "PinturaHuePicker",
            knobStyle: "background-color:" + t[19],
            onchange: t[24],
            value: t[14],
            min: 0,
            max: 1,
            step: .01
        }
    });
    let m = t[11] && Bh(t);
    return {
        c() {
            e = Rn("div"),
            o = Rn("div"),
            i = Rn("div"),
            n = Rn("div"),
            l = An(),
            Ar(c.$$.fragment),
            d = An(),
            m && m.c(),
            Bn(n, "role", "button"),
            Bn(n, "aria-label", "Saturation slider"),
            Bn(n, "class", "PinturaPickerKnob"),
            Bn(n, "tabindex", "0"),
            Bn(n, "style", r = `background-color:${t[18]};`),
            Bn(i, "class", "PinturaPickerKnobController"),
            Bn(i, "style", a = `transform:translate(${t[21]}%,${t[22]}%)`),
            Bn(o, "class", "PinturaSaturationPicker"),
            Bn(o, "style", s = "background-color: " + t[19]),
            Bn(e, "class", "PinturaPicker")
        },
        m(r, a) {
            Mn(r, e, a),
            kn(e, o),
            kn(o, i),
            kn(i, n),
            t[31](o),
            kn(e, l),
            Er(c, e, null),
            kn(e, d),
            m && m.m(e, null),
            u = !0,
            h || (p = [Ln(n, "nudge", t[27]), yn(Vl.call(null, n)), Ln(o, "pointerdown", t[26])],
            h = !0)
        },
        p(t, l) {
            (!u || 262144 & l[0] && r !== (r = `background-color:${t[18]};`)) && Bn(n, "style", r),
            (!u || 6291456 & l[0] && a !== (a = `transform:translate(${t[21]}%,${t[22]}%)`)) && Bn(i, "style", a),
            (!u || 524288 & l[0] && s !== (s = "background-color: " + t[19])) && Bn(o, "style", s);
            const d = {};
            524288 & l[0] && (d.knobStyle = "background-color:" + t[19]),
            16384 & l[0] && (d.value = t[14]),
            c.$set(d),
            t[11] ? m ? (m.p(t, l),
            2048 & l[0] && br(m, 1)) : (m = Bh(t),
            m.c(),
            br(m, 1),
            m.m(e, null)) : m && (yr(),
            vr(m, 1, 1, (()=>{
                m = null
            }
            )),
            xr())
        },
        i(t) {
            u || (br(c.$$.fragment, t),
            br(m),
            u = !0)
        },
        o(t) {
            vr(c.$$.fragment, t),
            vr(m),
            u = !1
        },
        d(o) {
            o && Tn(e),
            t[31](null),
            Lr(c),
            m && m.d(),
            h = !1,
            an(p)
        }
    }
}
function Bh(t) {
    let e, o;
    return e = new vd({
        props: {
            class: "PinturaOpacityPicker",
            knobStyle: "background-color: " + t[16],
            trackStyle: `background-image: linear-gradient(to right, ${t[17]}, ${t[18]})`,
            onchange: t[25],
            value: t[15],
            min: 0,
            max: 1,
            step: .01
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            65536 & o[0] && (i.knobStyle = "background-color: " + t[16]),
            393216 & o[0] && (i.trackStyle = `background-image: linear-gradient(to right, ${t[17]}, ${t[18]})`),
            32768 & o[0] && (i.value = t[15]),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Oh(t) {
    let e, o;
    return e = new nd({
        props: {
            label: "Presets",
            class: rl(["PinturaColorPresets", t[9] ? "PinturaColorPresetsGrid" : "PinturaColorPresetsList"]),
            hideLabel: !1,
            name: t[1],
            value: t[4],
            optionGroupClass: "PinturaDropdownOptionGroup",
            optionClass: "PinturaDropdownOption",
            options: t[2].map(t[32]),
            selectedIndex: t[3],
            optionMapper: t[7],
            optionLabelClass: t[6],
            onchange: t[33],
            $$slots: {
                option: [Wh, ({option: t})=>({
                    44: t
                }), ({option: t})=>[0, t ? 8192 : 0]],
                group: [Dh, ({option: t})=>({
                    44: t
                }), ({option: t})=>[0, t ? 8192 : 0]]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            512 & o[0] && (i.class = rl(["PinturaColorPresets", t[9] ? "PinturaColorPresetsGrid" : "PinturaColorPresetsList"])),
            2 & o[0] && (i.name = t[1]),
            16 & o[0] && (i.value = t[4]),
            1028 & o[0] && (i.options = t[2].map(t[32])),
            8 & o[0] && (i.selectedIndex = t[3]),
            128 & o[0] && (i.optionMapper = t[7]),
            64 & o[0] && (i.optionLabelClass = t[6]),
            512 & o[0] | 24576 & o[1] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Dh(t) {
    let e, o, i = t[44].label + "";
    return {
        c() {
            e = Rn("span"),
            o = In(i),
            Bn(e, "slot", "group")
        },
        m(t, i) {
            Mn(t, e, i),
            kn(e, o)
        },
        p(t, e) {
            8192 & e[1] && i !== (i = t[44].label + "") && Dn(o, i)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function _h(t) {
    let e, o, i = t[44].label + "";
    return {
        c() {
            e = Rn("span"),
            o = In(i),
            Bn(e, "class", "PinturaButtonLabel")
        },
        m(t, i) {
            Mn(t, e, i),
            kn(e, o)
        },
        p(t, e) {
            8192 & e[1] && i !== (i = t[44].label + "") && Dn(o, i)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function Wh(t) {
    let e, o, i, n;
    o = new Eh({
        props: {
            title: t[44].label,
            color: t[44].value
        }
    });
    let r = !t[9] && _h(t);
    return {
        c() {
            e = Rn("span"),
            Ar(o.$$.fragment),
            i = An(),
            r && r.c(),
            Bn(e, "slot", "option")
        },
        m(t, a) {
            Mn(t, e, a),
            Er(o, e, null),
            kn(e, i),
            r && r.m(e, null),
            n = !0
        },
        p(t, i) {
            const n = {};
            8192 & i[1] && (n.title = t[44].label),
            8192 & i[1] && (n.color = t[44].value),
            o.$set(n),
            t[9] ? r && (r.d(1),
            r = null) : r ? r.p(t, i) : (r = _h(t),
            r.c(),
            r.m(e, null))
        },
        i(t) {
            n || (br(o.$$.fragment, t),
            n = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            n = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o),
            r && r.d()
        }
    }
}
function Vh(t) {
    let e, o, i, n = t[13] && zh(t), r = t[12] && Oh(t);
    return {
        c() {
            e = Rn("div"),
            n && n.c(),
            o = An(),
            r && r.c(),
            Bn(e, "slot", "details"),
            Bn(e, "class", "PinturaColorPickerPanel")
        },
        m(t, a) {
            Mn(t, e, a),
            n && n.m(e, null),
            kn(e, o),
            r && r.m(e, null),
            i = !0
        },
        p(t, i) {
            t[13] ? n ? (n.p(t, i),
            8192 & i[0] && br(n, 1)) : (n = zh(t),
            n.c(),
            br(n, 1),
            n.m(e, o)) : n && (yr(),
            vr(n, 1, 1, (()=>{
                n = null
            }
            )),
            xr()),
            t[12] ? r ? (r.p(t, i),
            4096 & i[0] && br(r, 1)) : (r = Oh(t),
            r.c(),
            br(r, 1),
            r.m(e, null)) : r && (yr(),
            vr(r, 1, 1, (()=>{
                r = null
            }
            )),
            xr())
        },
        i(t) {
            i || (br(n),
            br(r),
            i = !0)
        },
        o(t) {
            vr(n),
            vr(r),
            i = !1
        },
        d(t) {
            t && Tn(e),
            n && n.d(),
            r && r.d()
        }
    }
}
function Hh(t) {
    let e, o;
    return e = new Sc({
        props: {
            buttonClass: rl(["PinturaColorPickerButton", t[5]]),
            $$slots: {
                details: [Vh],
                label: [Fh]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            32 & o[0] && (i.buttonClass = rl(["PinturaColorPickerButton", t[5]])),
            8388575 & o[0] | 16384 & o[1] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Nh(t, e, o) {
    let i, n, r, a, s, l, c, d, u, h, p, {label: m} = e, {name: g} = e, {options: f=[]} = e, {selectedIndex: $=-1} = e, {value: y} = e, {buttonClass: x} = e, {optionLabelClass: b} = e, {optionMapper: v} = e, {onchange: w} = e, {title: C} = e, {hidePresetLabel: k=!0} = e, {locale: M} = e, {enableOpacity: T=!0} = e, {enablePresets: R=!0} = e, {enablePicker: P=!0} = e;
    const I = (t,e)=>{
        if (c = [t[0], t[1], t[2]],
        e) {
            let e = ((t,e,o)=>{
                let i = Math.max(t, e, o)
                  , n = i - Math.min(t, e, o)
                  , r = n && (i == t ? (e - o) / n : i == e ? 2 + (o - t) / n : 4 + (t - e) / n);
                return [60 * (r < 0 ? r + 6 : r) / 360, i && n / i, i]
            }
            )(...c);
            o(14, r = e[0]),
            o(29, a = e[1]),
            o(30, s = e[2]),
            o(15, l = qe(t[3]) ? t[3] : 1)
        }
        o(16, d = ao(t)),
        o(17, u = ao([...c, 0])),
        o(18, h = ao([...c, 1])),
        o(19, p = ao(Ph(r, 1, 1)))
    }
    ;
    y && I(y, !0);
    const A = ()=>{
        const t = [...Ph(r, a, s), l];
        I(t),
        w(t)
    }
      , E = t=>{
        const e = 3 === t.length ? [...t, 1] : t;
        I(e, !0),
        w(e)
    }
      , L = (t,e)=>{
        const i = na(t.x / e.width, 0, 1)
          , n = na(t.y / e.height, 0, 1);
        var r;
        r = 1 - n,
        o(29, a = i),
        o(30, s = r),
        0 === l && o(15, l = 1),
        A()
    }
    ;
    let F, z, B, O;
    const D = t=>{
        const e = rt(q(t), O);
        L(nt(Z(B), e), z)
    }
      , _ = t=>{
        z = void 0,
        document.documentElement.removeEventListener("pointermove", D),
        document.documentElement.removeEventListener("pointerup", _)
    }
    ;
    return t.$$set = t=>{
        "label"in t && o(0, m = t.label),
        "name"in t && o(1, g = t.name),
        "options"in t && o(2, f = t.options),
        "selectedIndex"in t && o(3, $ = t.selectedIndex),
        "value"in t && o(4, y = t.value),
        "buttonClass"in t && o(5, x = t.buttonClass),
        "optionLabelClass"in t && o(6, b = t.optionLabelClass),
        "optionMapper"in t && o(7, v = t.optionMapper),
        "onchange"in t && o(28, w = t.onchange),
        "title"in t && o(8, C = t.title),
        "hidePresetLabel"in t && o(9, k = t.hidePresetLabel),
        "locale"in t && o(10, M = t.locale),
        "enableOpacity"in t && o(11, T = t.enableOpacity),
        "enablePresets"in t && o(12, R = t.enablePresets),
        "enablePicker"in t && o(13, P = t.enablePicker)
    }
    ,
    t.$$.update = ()=>{
        536870912 & t.$$.dirty[0] && o(21, i = 100 * a),
        1073741824 & t.$$.dirty[0] && o(22, n = 100 - 100 * s)
    }
    ,
    [m, g, f, $, y, x, b, v, C, k, M, T, R, P, r, l, d, u, h, p, F, i, n, E, t=>{
        o(14, r = t),
        0 === l && o(15, l = 1),
        A()
    }
    , t=>{
        o(15, l = t),
        A()
    }
    , t=>{
        t.stopPropagation(),
        z = xt(F.offsetWidth, F.offsetHeight),
        B = (t=>Y(t.offsetX, t.offsetY))(t),
        O = q(t),
        L(B, z),
        document.documentElement.addEventListener("pointermove", D),
        document.documentElement.addEventListener("pointerup", _)
    }
    , t=>{
        z = xt(F.offsetWidth, F.offsetHeight);
        const e = i / 100 * z.width
          , o = n / 100 * z.height;
        L({
            x: e + t.detail.x,
            y: o + t.detail.y
        }, z)
    }
    , w, a, s, function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            F = t,
            o(20, F)
        }
        ))
    }
    , ([t,e])=>[t, S(e) ? e(M) : e], t=>E(t.value)]
}
class Uh extends Br {
    constructor(t) {
        super(),
        zr(this, t, Nh, Hh, ln, {
            label: 0,
            name: 1,
            options: 2,
            selectedIndex: 3,
            value: 4,
            buttonClass: 5,
            optionLabelClass: 6,
            optionMapper: 7,
            onchange: 28,
            title: 8,
            hidePresetLabel: 9,
            locale: 10,
            enableOpacity: 11,
            enablePresets: 12,
            enablePicker: 13
        }, [-1, -1])
    }
}
var jh = t=>t.charAt(0).toUpperCase() + t.slice(1);
let Xh = null;
var Yh = ()=>{
    if (null === Xh)
        if (c())
            try {
                Xh = !1 === document.fonts.check("16px TestNonExistingFont")
            } catch (t) {
                Xh = !1
            }
        else
            Xh = !1;
    return Xh
}
;
const Gh = (t,e)=>o=>o[e ? `${e}${jh(t)}` : t]
  , qh = t=>[t, "" + t]
  , Zh = (t,e)=>o=>[t[o], Gh(o, e)]
  , Kh = [1, .2549, .2118]
  , Jh = [1, 1, 1, 0]
  , Qh = {
    path: ()=>({
        points: [],
        disableErase: !1
    }),
    eraser: ()=>({
        eraseRadius: 0
    }),
    line: ()=>({
        x1: 0,
        y1: 0,
        x2: 0,
        y2: 0,
        disableErase: !1
    }),
    rectangle: ()=>({
        x: 0,
        y: 0,
        width: 0,
        height: 0
    }),
    ellipse: ()=>({
        x: 0,
        y: 0,
        rx: 0,
        ry: 0
    }),
    text: ()=>({
        x: 0,
        y: 0,
        text: "Text"
    })
}
  , tp = (t,e={},o={
    position: "relative"
})=>{
    if (!Qh[t])
        return;
    return [{
        ...Qh[t](),
        ...e
    }, o]
}
  , ep = t=>({
    sharpie: tp("path", {
        strokeWidth: "0.5%",
        strokeColor: [...Kh],
        disableMove: !0
    }),
    eraser: tp("eraser"),
    line: tp("line", {
        strokeColor: [...Kh],
        strokeWidth: "0.5%"
    }),
    arrow: tp("line", {
        lineStart: "none",
        lineEnd: "arrow-solid",
        strokeColor: [...Kh],
        strokeWidth: "0.5%"
    }),
    rectangle: tp("rectangle", {
        strokeColor: [...Jh],
        backgroundColor: [...Kh]
    }),
    ellipse: tp("ellipse", {
        strokeColor: [...Jh],
        backgroundColor: [...Kh]
    }),
    text: tp("text", {
        color: [...Kh],
        fontSize: "2%"
    }),
    ...t
})
  , op = (t,e,o)=>[t, e || Gh(t, "shapeLabelTool"), {
    icon: Gh(t, "shapeIconTool"),
    ...o
}]
  , ip = (t=["sharpie", "eraser", "line", "arrow", "rectangle", "ellipse", "text", "preset"])=>t.map((t=>w(t) ? op(t) : Array.isArray(t) ? x(t[1]) ? op(t[0], void 0, t[1]) : op(t[0], t[1], t[2]) : void 0)).filter(Boolean)
  , np = ()=>({
    transparent: [1, 1, 1, 0],
    white: [1, 1, 1],
    silver: [.8667, .8667, .8667],
    gray: [.6667, .6667, .6667],
    black: [0, 0, 0],
    navy: [0, .1216, .2471],
    blue: [0, .4549, .851],
    aqua: [.498, .8588, 1],
    teal: [.2235, .8, .8],
    olive: [.2392, .6, .4392],
    green: [.1804, .8, .251],
    yellow: [1, .8627, 0],
    orange: [1, .5216, .1059],
    red: [1, .2549, .2118],
    maroon: [.5216, .0784, .2941],
    fuchsia: [.9412, .0706, .7451],
    purple: [.6941, .051, .7882]
})
  , rp = ()=>[16, 18, 20, 24, 30, 36, 48, 64, 72, 96, 128, 144]
  , ap = rp
  , sp = ()=>({
    extraSmall: "2%",
    small: "4%",
    mediumSmall: "8%",
    medium: "10%",
    mediumLarge: "15%",
    large: "20%",
    extraLarge: "25%"
})
  , lp = ()=>({
    extraSmall: "40%",
    small: "60%",
    mediumSmall: "100%",
    medium: "120%",
    mediumLarge: "140%",
    large: "180%",
    extraLarge: "220%"
})
  , cp = ()=>[1, 2, 3, 4, 6, 8, 12, 16, 20, 24, 32, 48, 64]
  , dp = ()=>({
    extraSmall: "0.25%",
    small: "0.5%",
    mediumSmall: "1%",
    medium: "1.75%",
    mediumLarge: "2.5%",
    large: "3.5%",
    extraLarge: "5%"
})
  , up = ()=>["bar", "arrow", "arrowSolid", "circle", "circleSolid", "square", "squareSolid"]
  , hp = ()=>[["Helvetica, Arial, Verdana, 'Droid Sans', sans-serif", "Sans Serif"], ["'Arial Black', 'Avenir-Black', 'Arial Bold'", "Black"], ["'Arial Narrow', 'Futura-CondensedMedium'", "Narrow"], ["'Trebuchet MS'", "Humanist"], ["Georgia, 'Avenir-Black', 'Times New Roman', 'Droid Serif', serif", "Serif"], ["Palatino", "Old-Style"], ["'Times New Roman', 'TimesNewRomanPSMT'", "Transitional"], ["Menlo, Monaco, 'Lucida Console', monospace", "Monospaced"], ["'Courier New', monospace", "Slab Serif"]]
  , pp = ()=>["left", "center", "right"]
  , mp = ()=>[["normal", "bold"], ["italic", "normal"], ["italic", "bold"]]
  , gp = t=>Object.keys(t).map(Zh(t, "shapeTitleColor"))
  , fp = t=>t.map(qh)
  , $p = t=>Object.keys(t).map(Zh(t, "labelSize"))
  , yp = t=>t.map(qh)
  , xp = t=>Object.keys(t).map(Zh(t, "labelSize"))
  , bp = t=>t.map(qh)
  , vp = t=>Object.keys(t).map(Zh(t, "labelSize"))
  , wp = t=>[...t]
  , Sp = t=>t.map((t=>[t, e=>e["shapeLabelFontStyle" + t.filter((t=>"normal" !== t)).map(jh).join("")]]))
  , Cp = t=>t.map((t=>[Hd(t), e=>e["shapeTitleLineDecoration" + jh(t)], {
    icon: e=>e["shapeIconLineDecoration" + jh(t)]
}]))
  , kp = t=>t.map((t=>[t, e=>e["shapeTitleTextAlign" + jh(t)], {
    hideLabel: !0,
    icon: e=>e["shapeIconTextAlign" + jh(t)]
}]))
  , Mp = (t,e)=>{
    const {defaultKey: o, defaultValue: i, defaultOptions: n} = e || {}
      , r = [];
    return o && (r[0] = [i, t=>t[o], {
        ...n
    }]),
    [...r, ...t]
}
  , Tp = t=>t.split(",").map((t=>t.trim())).some((t=>document.fonts.check("16px " + t)))
  , Rp = t=>[Uh, {
    title: t=>t.labelColor,
    options: Mp(t)
}]
  , Pp = (t={})=>[Rd, {
    ...t
}]
  , Ip = t=>[gd, {
    title: t=>t.shapeTitleFontFamily,
    onload: ({options: t=[]})=>{
        Yh() && t.map((([t])=>t)).filter(Boolean).filter((t=>!Tp(t))).forEach((t=>{
            const e = "PinturaFontTest-" + t.replace(/[^a-zA-Z0-9]+/g, "").toLowerCase();
            document.getElementById(e) || fe(p("span", {
                textContent: " ",
                id: e,
                style: `font-family:${t};font-size:0;color:transparent;`
            }))
        }
        ))
    }
    ,
    optionLabelStyle: t=>"font-family: " + t,
    options: Mp(t, {
        defaultKey: "labelDefault"
    }),
    optionFilter: t=>{
        if (!Yh())
            return !0;
        const [e] = t;
        if (!e)
            return !0;
        return Tp(e)
    }
}]
  , Ap = t=>[Uh, {
    title: t=>t.shapeTitleBackgroundColor,
    options: Mp(t)
}]
  , Ep = (t,e={})=>[Uh, {
    title: t=>t.shapeTitleStrokeColor,
    options: Mp(t),
    buttonClass: "PinturaColorPickerButtonStroke",
    onchange: (t,o)=>{
        const i = o.strokeWidth;
        (qe(i) || w(i) ? parseFloat(i) : 0) > 0 || (o.strokeWidth = e && e.defaultStrokeWidth || "0.5%")
    }
}]
  , Lp = t=>[gd, {
    title: t=>t.shapeTitleStrokeWidth,
    options: e=>Ke(e, "backgroundColor") ? Mp(t, {
        defaultKey: "shapeLabelStrokeNone"
    }) : Mp(t)
}]
  , Fp = (t,e,o)=>[gd, {
    title: t=>t[e],
    options: Mp(t, {
        defaultKey: "labelNone",
        defaultOptions: {
            icon: '<g stroke="currentColor" stroke-linecap="round" stroke-width=".125em"><path d="M5,12 H14"/></g>'
        }
    }),
    optionIconStyle: o
}]
  , zp = t=>Fp(t, "shapeTitleLineStart", "transform: scaleX(-1)")
  , Bp = t=>Fp(t, "shapeTitleLineEnd")
  , Op = t=>[Uh, {
    title: t=>t.shapeTitleTextColor,
    options: Mp(t)
}]
  , Dp = t=>[gd, {
    title: t=>t.shapeTitleFontStyle,
    optionLabelStyle: t=>t && `font-style:${t[0]};font-weight:${t[1]}`,
    options: Mp(t, {
        defaultKey: "shapeLabelFontStyleNormal"
    })
}]
  , _p = t=>{
    let e;
    return t.find((([t])=>"4%" === t)) || (e = {
        defaultKey: "labelAuto",
        defaultValue: "4%"
    }),
    [gd, {
        title: t=>t.shapeTitleFontSize,
        options: Mp(t, e)
    }]
}
  , Wp = t=>[nd, {
    title: t=>t.shapeTitleTextAlign,
    options: Mp(t)
}]
  , Vp = t=>{
    let e;
    return t.find((([t])=>"120%" === t)) || (e = {
        defaultKey: "labelAuto",
        defaultValue: "120%"
    }),
    [gd, {
        title: t=>t.shapeTitleLineHeight,
        options: Mp(t, e)
    }]
}
  , Hp = (t={})=>{
    const {colorOptions: e=gp(np()), lineEndStyleOptions: o=Cp(up()), fontFamilyOptions: i=wp(hp()), fontStyleOptions: n=Sp(mp()), textAlignOptions: r=kp(pp())} = t;
    let {strokeWidthOptions: a=vp(dp()), fontSizeOptions: s=$p(sp()), lineHeightOptions: l=xp(lp())} = t;
    [s,l,a] = [s, l, a].map((t=>Array.isArray(t) && t.every(qe) ? t.map(qh) : t));
    const c = {
        defaultColor: e && Rp(e),
        defaultNumber: Pp(),
        defaultPercentage: Pp({
            getValue: t=>parseFloat(t),
            setValue: t=>t + "%",
            step: .05,
            label: (t,e,o)=>Math.round(t / o * 100) + "%",
            labelClass: "PinturaPercentageLabel"
        }),
        backgroundColor: e && Ap(e),
        strokeColor: e && Ep(e),
        strokeWidth: a && Lp(a),
        lineStart: o && zp(o),
        lineEnd: o && Bp(o),
        color: e && Op(e),
        fontFamily: i && Ip(i),
        fontStyle_fontWeight: n && Dp(n),
        fontSize: s && _p(s),
        lineHeight: l && Vp(l),
        textAlign: r && Wp(r),
        frameColor: ["defaultColor"],
        frameSize: ["defaultPercentage", {
            min: .2,
            max: 10,
            title: t=>t.labelSize
        }],
        frameInset: ["defaultPercentage", {
            min: .5,
            max: 10,
            title: t=>t.labelInset
        }],
        frameOffset: ["defaultPercentage", {
            min: .5,
            max: 10,
            title: t=>t.labelOffset
        }],
        frameRadius: ["defaultPercentage", {
            min: .5,
            max: 10,
            title: t=>t.labelRadius
        }],
        frameAmount: ["defaultNumber", {
            min: 1,
            max: 5,
            step: 1,
            title: t=>t.labelAmount
        }]
    };
    return Object.entries(t).forEach((([t,e])=>{
        c[t] || (c[t] = e)
    }
    )),
    c
}
;
function Np(t) {
    let e, o, i, n;
    const r = t[3].default
      , a = hn(r, t, t[2], null);
    return {
        c() {
            e = Rn("div"),
            a && a.c(),
            Bn(e, "class", t[0])
        },
        m(r, s) {
            Mn(r, e, s),
            a && a.m(e, null),
            o = !0,
            i || (n = [Ln(e, "measure", t[1]), yn(bs.call(null, e))],
            i = !0)
        },
        p(t, [i]) {
            a && a.p && 4 & i && mn(a, r, t, t[2], i, null, null),
            (!o || 1 & i) && Bn(e, "class", t[0])
        },
        i(t) {
            o || (br(a, t),
            o = !0)
        },
        o(t) {
            vr(a, t),
            o = !1
        },
        d(t) {
            t && Tn(e),
            a && a.d(t),
            i = !1,
            an(n)
        }
    }
}
function Up(t, e, o) {
    let {$$slots: i={}, $$scope: n} = e;
    const r = Jn();
    let {class: a=null} = e;
    return t.$$set = t=>{
        "class"in t && o(0, a = t.class),
        "$$scope"in t && o(2, n = t.$$scope)
    }
    ,
    [a, ({detail: t})=>r("measure", t), n, i]
}
class jp extends Br {
    constructor(t) {
        super(),
        zr(this, t, Up, Np, ln, {
            class: 0
        })
    }
}
const Xp = t=>({})
  , Yp = t=>({})
  , Gp = t=>({})
  , qp = t=>({})
  , Zp = t=>({})
  , Kp = t=>({});
function Jp(t) {
    let e, o;
    const i = t[4].header
      , n = hn(i, t, t[3], Kp);
    return {
        c() {
            e = Rn("div"),
            n && n.c(),
            Bn(e, "class", "PinturaUtilHeader")
        },
        m(t, i) {
            Mn(t, e, i),
            n && n.m(e, null),
            o = !0
        },
        p(t, e) {
            n && n.p && 8 & e && mn(n, i, t, t[3], e, Zp, Kp)
        },
        i(t) {
            o || (br(n, t),
            o = !0)
        },
        o(t) {
            vr(n, t),
            o = !1
        },
        d(t) {
            t && Tn(e),
            n && n.d(t)
        }
    }
}
function Qp(t) {
    let e, o;
    const i = t[4].footer
      , n = hn(i, t, t[3], Yp);
    return {
        c() {
            e = Rn("div"),
            n && n.c(),
            Bn(e, "class", "PinturaUtilFooter")
        },
        m(t, i) {
            Mn(t, e, i),
            n && n.m(e, null),
            o = !0
        },
        p(t, e) {
            n && n.p && 8 & e && mn(n, i, t, t[3], e, Xp, Yp)
        },
        i(t) {
            o || (br(n, t),
            o = !0)
        },
        o(t) {
            vr(n, t),
            o = !1
        },
        d(t) {
            t && Tn(e),
            n && n.d(t)
        }
    }
}
function tm(t) {
    let e, o, i, n, r, a, s = t[1] && Jp(t);
    const l = t[4].main
      , c = hn(l, t, t[3], qp)
      , d = c || function(t) {
        let e, o;
        return e = new jp({
            props: {
                class: "PinturaStage"
            }
        }),
        e.$on("measure", t[5]),
        {
            c() {
                Ar(e.$$.fragment)
            },
            m(t, i) {
                Er(e, t, i),
                o = !0
            },
            p: tn,
            i(t) {
                o || (br(e.$$.fragment, t),
                o = !0)
            },
            o(t) {
                vr(e.$$.fragment, t),
                o = !1
            },
            d(t) {
                Lr(e, t)
            }
        }
    }(t);
    let u = t[2] && Qp(t);
    return {
        c() {
            s && s.c(),
            e = An(),
            o = Rn("div"),
            d && d.c(),
            i = An(),
            u && u.c(),
            n = An(),
            r = En(),
            Bn(o, "class", "PinturaUtilMain")
        },
        m(l, c) {
            s && s.m(l, c),
            Mn(l, e, c),
            Mn(l, o, c),
            d && d.m(o, null),
            t[6](o),
            Mn(l, i, c),
            u && u.m(l, c),
            Mn(l, n, c),
            Mn(l, r, c),
            a = !0
        },
        p(t, [o]) {
            t[1] ? s ? (s.p(t, o),
            2 & o && br(s, 1)) : (s = Jp(t),
            s.c(),
            br(s, 1),
            s.m(e.parentNode, e)) : s && (yr(),
            vr(s, 1, 1, (()=>{
                s = null
            }
            )),
            xr()),
            c && c.p && 8 & o && mn(c, l, t, t[3], o, Gp, qp),
            t[2] ? u ? (u.p(t, o),
            4 & o && br(u, 1)) : (u = Qp(t),
            u.c(),
            br(u, 1),
            u.m(n.parentNode, n)) : u && (yr(),
            vr(u, 1, 1, (()=>{
                u = null
            }
            )),
            xr())
        },
        i(t) {
            a || (br(s),
            br(d, t),
            br(u),
            br(false),
            a = !0)
        },
        o(t) {
            vr(s),
            vr(d, t),
            vr(u),
            vr(false),
            a = !1
        },
        d(a) {
            s && s.d(a),
            a && Tn(e),
            a && Tn(o),
            d && d.d(a),
            t[6](null),
            a && Tn(i),
            u && u.d(a),
            a && Tn(n),
            a && Tn(r)
        }
    }
}
function em(t, e, o) {
    let {$$slots: i={}, $$scope: n} = e
      , {hasHeader: r=!!e.$$slots.header} = e
      , {hasFooter: a=!!e.$$slots.footer} = e
      , {root: s} = e;
    return t.$$set = t=>{
        o(7, e = on(on({}, e), gn(t))),
        "hasHeader"in t && o(1, r = t.hasHeader),
        "hasFooter"in t && o(2, a = t.hasFooter),
        "root"in t && o(0, s = t.root),
        "$$scope"in t && o(3, n = t.$$scope)
    }
    ,
    e = gn(e),
    [s, r, a, n, i, function(e) {
        er(t, e)
    }
    , function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            s = t,
            o(0, s)
        }
        ))
    }
    ]
}
class om extends Br {
    constructor(t) {
        super(),
        zr(this, t, em, tm, ln, {
            hasHeader: 1,
            hasFooter: 2,
            root: 0
        })
    }
}
function im(t) {
    let e, o;
    return {
        c() {
            e = Rn("div"),
            Bn(e, "class", "PinturaRangeInputMeter"),
            Bn(e, "style", o = `transform: translateX(${t[8].x - t[9].x}px) translateY(${t[8].y - t[9].y}px)`)
        },
        m(o, i) {
            Mn(o, e, i),
            e.innerHTML = t[6]
        },
        p(t, i) {
            64 & i[0] && (e.innerHTML = t[6]),
            256 & i[0] && o !== (o = `transform: translateX(${t[8].x - t[9].x}px) translateY(${t[8].y - t[9].y}px)`) && Bn(e, "style", o)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function nm(t) {
    let e, o, i, n, r, a, s, l, c, d, u, h = t[8] && im(t);
    return {
        c() {
            e = Rn("div"),
            o = Rn("span"),
            i = In(t[3]),
            n = An(),
            r = Rn("button"),
            a = In(t[1]),
            l = An(),
            c = Rn("div"),
            h && h.c(),
            Bn(o, "class", "PinturaRangeInputValue"),
            Bn(r, "class", "PinturaRangeInputReset"),
            Bn(r, "type", "button"),
            r.disabled = s = t[0] === t[2],
            Bn(c, "class", "PinturaRangeInputInner"),
            Bn(c, "style", t[7]),
            Bn(c, "data-value-limited", t[5]),
            Bn(e, "class", "PinturaRangeInput"),
            Bn(e, "tabindex", "0")
        },
        m(s, p) {
            Mn(s, e, p),
            kn(e, o),
            kn(o, i),
            kn(e, n),
            kn(e, r),
            kn(r, a),
            kn(e, l),
            kn(e, c),
            h && h.m(c, null),
            d || (u = [Ln(r, "click", t[14]), Ln(c, "interactionstart", t[10]), Ln(c, "interactionupdate", t[12]), Ln(c, "interactionend", t[13]), Ln(c, "interactionrelease", t[11]), yn(Wl.call(null, c, {
                inertia: !0
            })), Ln(c, "measure", t[32]), yn(bs.call(null, c)), Ln(e, "wheel", t[16], {
                passive: !1
            }), Ln(e, "nudge", t[17]), yn(Vl.call(null, e, {
                direction: "horizontal"
            }))],
            d = !0)
        },
        p(t, e) {
            8 & e[0] && Dn(i, t[3]),
            2 & e[0] && Dn(a, t[1]),
            5 & e[0] && s !== (s = t[0] === t[2]) && (r.disabled = s),
            t[8] ? h ? h.p(t, e) : (h = im(t),
            h.c(),
            h.m(c, null)) : h && (h.d(1),
            h = null),
            128 & e[0] && Bn(c, "style", t[7]),
            32 & e[0] && Bn(c, "data-value-limited", t[5])
        },
        i: tn,
        o: tn,
        d(t) {
            t && Tn(e),
            h && h.d(),
            d = !1,
            an(u)
        }
    }
}
function rm(t, e, o) {
    let i, r, a, s, l, c, d, u, {labelReset: h="Reset"} = e, {direction: p="x"} = e, {min: m=0} = e, {max: g=1} = e, {base: f=m} = e, {value: $=0} = e, {valueLabel: y=0} = e, {valueMin: x} = e, {valueMax: b} = e, {oninputstart: v=n} = e, {oninputmove: w=n} = e, {oninputend: S=n} = e, {elasticity: C=0} = e;
    const k = (t,e,o)=>Math.ceil((t - o) / e) * e + o;
    let M, T, R;
    const P = {
        x: 2,
        y: 0
    }
      , I = (t,e,o)=>`M ${t - o} ${e} a ${o} ${o} 0 1 0 0 -1`;
    let A, E = void 0, L = !1, F = {
        snap: !1,
        elastic: !1
    };
    const z = (t,e,o)=>{
        const i = t[p] + e[p]
          , n = na(i, A[1][p], A[0][p])
          , r = C ? n + Hl(i - n, C) : n
          , a = o.elastic ? r : n
          , s = Y(0, 0);
        return s[p] = a,
        B.set(s, {
            hard: o.snap
        }),
        na(D(s, p), m, g)
    }
      , B = ls();
    un(t, B, (t=>o(8, u = t)));
    const O = (t,e)=>{
        const o = .5 * (M[e] - s[e]) - (fd(t, m, g) * s[e] - .5 * s[e]);
        return {
            x: "x" === e ? o : 0,
            y: "y" === e ? o : 0
        }
    }
      , D = (t,e)=>{
        const o = -(t[e] - .5 * M[e]) / s[e];
        return m + o * i
    }
      , _ = B.subscribe((t=>{
        t && E && w(na(D(t, p), m, g))
    }
    ))
      , W = t=>{
        const e = [O(null != x ? x : m, p), O(null != b ? b : g, p)]
          , o = {
            x: "x" === p ? u.x + t : 0,
            y: "y" === p ? u.y + t : 0
        }
          , i = na(o[p], e[1][p], e[0][p])
          , n = {
            ...u,
            [p]: i
        };
        $n(B, u = n, u);
        const r = na(D(n, p), m, g);
        v(),
        w(r),
        S(r)
    }
    ;
    Kn((()=>{
        _()
    }
    ));
    return t.$$set = t=>{
        "labelReset"in t && o(1, h = t.labelReset),
        "direction"in t && o(18, p = t.direction),
        "min"in t && o(19, m = t.min),
        "max"in t && o(20, g = t.max),
        "base"in t && o(2, f = t.base),
        "value"in t && o(0, $ = t.value),
        "valueLabel"in t && o(3, y = t.valueLabel),
        "valueMin"in t && o(21, x = t.valueMin),
        "valueMax"in t && o(22, b = t.valueMax),
        "oninputstart"in t && o(23, v = t.oninputstart),
        "oninputmove"in t && o(24, w = t.oninputmove),
        "oninputend"in t && o(25, S = t.oninputend),
        "elasticity"in t && o(26, C = t.elasticity)
    }
    ,
    t.$$.update = ()=>{
        if (1572864 & t.$$.dirty[0] && o(28, i = g - m),
        2621440 & t.$$.dirty[0] && o(29, r = null != x ? Math.max(x, m) : m),
        5242880 & t.$$.dirty[0] && o(30, a = null != b ? Math.min(b, g) : g),
        1572868 & t.$$.dirty[0] && o(31, l = fd(f, m, g)),
        16 & t.$$.dirty[0] | 1 & t.$$.dirty[1] && M) {
            const t = .5 * M.y;
            let e, i = 40 * l, n = "", r = M.y, a = "";
            for (let o = 0; o <= 40; o++) {
                const r = P.x + 10 * o
                  , s = t;
                n += I(r, s, o % 5 == 0 ? 2 : .75) + " ",
                e = r + P.x,
                o === i && (a = `<path d="M${r} ${s - 4} l2 3 l-2 -1 l-2 1 z"/>`)
            }
            o(6, T = `<svg width="${e}" height="${r}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${e} ${r}" aria-hidden="true" focusable="false">\n        ${a}\n        <rect rx="4" ry="4" y="${t - 4}"" height="8"/>\n        <path fill-rule="evenodd" d="${n.trim()}"/></svg>`),
            o(27, R = {
                x: e - 2 * P.x,
                y: r
            })
        }
        134217744 & t.$$.dirty[0] && (s = M && R),
        1612185600 & t.$$.dirty[0] && o(5, c = r !== m || a !== g),
        1610612768 & t.$$.dirty[0] && o(7, d = c ? function(t, e) {
            const o = 1 / 40
              , i = fd(t, m, g)
              , n = fd(e, m, g);
            return `--range-mask-from:${100 * Io(k(i, o, 0) - .0125)}%;--range-mask-to:${100 * Io(k(n, o, 0) - .0125)}%`
        }(r, a) : ""),
        268697617 & t.$$.dirty[0] && i && M && M.x && M.y && B.set(O($, p))
    }
    ,
    [$, h, f, y, M, c, T, d, u, P, ()=>{
        L = !1,
        E = dn(B),
        A = [O(null != x ? x : m, p), O(null != b ? b : g, p)],
        v()
    }
    , ()=>{
        L = !0
    }
    , ({detail: t})=>{
        F.snap = !L,
        F.elastic = !L,
        z(E, t.translation, F)
    }
    , ({detail: t})=>{
        F.snap = !1,
        F.elastic = !1;
        const e = z(E, t.translation, F);
        if (E = void 0,
        A = void 0,
        Math.abs(e - f) < .01)
            return S(f);
        S(e)
    }
    , ()=>{
        o(0, $ = na(f, r, a)),
        v(),
        S($)
    }
    , B, t=>{
        t.preventDefault(),
        t.stopPropagation();
        const e = 8 * jl(t);
        W(e)
    }
    , ({detail: t})=>{
        W(8 * t[p])
    }
    , p, m, g, x, b, v, w, S, C, R, i, r, a, l, t=>o(4, M = (t=>Y(t.width, t.height))(t.detail))]
}
class am extends Br {
    constructor(t) {
        super(),
        zr(this, t, rm, nm, ln, {
            labelReset: 1,
            direction: 18,
            min: 19,
            max: 20,
            base: 2,
            value: 0,
            valueLabel: 3,
            valueMin: 21,
            valueMax: 22,
            oninputstart: 23,
            oninputmove: 24,
            oninputend: 25,
            elasticity: 26
        }, [-1, -1])
    }
}
function sm(t) {
    let e, o, i, n, r;
    const a = t[7].default
      , s = hn(a, t, t[6], null);
    return {
        c() {
            e = Rn("div"),
            o = Rn("div"),
            s && s.c(),
            Bn(o, "class", "PinturaToolbarInner"),
            Bn(e, "class", "PinturaToolbar"),
            Bn(e, "data-layout", t[1]),
            Bn(e, "data-overflow", t[0])
        },
        m(a, l) {
            Mn(a, e, l),
            kn(e, o),
            s && s.m(o, null),
            i = !0,
            n || (r = [Ln(o, "measure", t[3]), yn(bs.call(null, o)), Ln(e, "measure", t[2]), yn(bs.call(null, e))],
            n = !0)
        },
        p(t, [o]) {
            s && s.p && 64 & o && mn(s, a, t, t[6], o, null, null),
            (!i || 2 & o) && Bn(e, "data-layout", t[1]),
            (!i || 1 & o) && Bn(e, "data-overflow", t[0])
        },
        i(t) {
            i || (br(s, t),
            i = !0)
        },
        o(t) {
            vr(s, t),
            i = !1
        },
        d(t) {
            t && Tn(e),
            s && s.d(t),
            n = !1,
            an(r)
        }
    }
}
function lm(t, e, o) {
    let i, n, {$$slots: r={}, $$scope: a} = e, s = 0, l = 0, c = 0;
    const d = ()=>{
        o(0, n = "compact" === i && s > c ? "overflow" : void 0)
    }
    ;
    return t.$$set = t=>{
        "$$scope"in t && o(6, a = t.$$scope)
    }
    ,
    t.$$.update = ()=>{
        48 & t.$$.dirty && o(1, i = l > c ? "compact" : "default")
    }
    ,
    [n, i, ({detail: t})=>{
        const {width: e} = t;
        o(5, c = e),
        d()
    }
    , ({detail: t})=>{
        const {width: e} = t;
        e > l && o(4, l = e),
        s = e,
        n || d()
    }
    , l, c, a, r]
}
class cm extends Br {
    constructor(t) {
        super(),
        zr(this, t, lm, sm, ln, {})
    }
}
const dm = {
    Top: "t",
    Right: "r",
    Bottom: "b",
    Left: "l",
    TopLeft: "tl",
    TopRight: "tr",
    BottomRight: "br",
    BottomLeft: "bl"
}
  , {Top: um, Right: hm, Bottom: pm, Left: mm, TopLeft: gm, TopRight: fm, BottomRight: $m, BottomLeft: ym} = dm;
var xm = {
    [um]: t=>({
        x: t.x,
        y: t.y
    }),
    [fm]: t=>({
        x: t.x + t.width,
        y: t.y
    }),
    [hm]: t=>({
        x: t.x + t.width,
        y: t.y
    }),
    [$m]: t=>({
        x: t.x + t.width,
        y: t.y + t.height
    }),
    [pm]: t=>({
        x: t.x,
        y: t.y + t.height
    }),
    [ym]: t=>({
        x: t.x,
        y: t.y + t.height
    }),
    [mm]: t=>({
        x: t.x,
        y: t.y
    }),
    [gm]: t=>({
        x: t.x,
        y: t.y
    })
};
function bm(t, e, o) {
    const i = t.slice();
    return i[12] = e[o].key,
    i[13] = e[o].translate,
    i[14] = e[o].scale,
    i[15] = e[o].type,
    i[16] = e[o].opacity,
    i
}
function vm(t, e) {
    let o, i, n, r, a, s, l, c;
    return {
        key: t,
        first: null,
        c() {
            o = Rn("div"),
            Bn(o, "role", "button"),
            Bn(o, "aria-label", i = `Drag ${e[15]} ${e[12]}`),
            Bn(o, "tabindex", n = "edge" === e[15] ? -1 : 0),
            Bn(o, "class", "PinturaRectManipulator"),
            Bn(o, "data-direction", r = e[12]),
            Bn(o, "data-shape", a = "" + ("edge" === e[15] ? "edge" : "" + e[0])),
            Bn(o, "style", s = `transform: translate3d(${e[13].x}px, ${e[13].y}px, 0) scale(${e[14].x}, ${e[14].y}); opacity: ${e[16]}`),
            this.first = o
        },
        m(t, i) {
            Mn(t, o, i),
            l || (c = [Ln(o, "nudge", (function() {
                sn(e[5](e[12])) && e[5](e[12]).apply(this, arguments)
            }
            )), yn(Vl.call(null, o)), Ln(o, "interactionstart", (function() {
                sn(e[4]("resizestart", e[12])) && e[4]("resizestart", e[12]).apply(this, arguments)
            }
            )), Ln(o, "interactionupdate", (function() {
                sn(e[4]("resizemove", e[12])) && e[4]("resizemove", e[12]).apply(this, arguments)
            }
            )), Ln(o, "interactionend", (function() {
                sn(e[4]("resizeend", e[12])) && e[4]("resizeend", e[12]).apply(this, arguments)
            }
            )), yn(Wl.call(null, o))],
            l = !0)
        },
        p(t, l) {
            e = t,
            2 & l && i !== (i = `Drag ${e[15]} ${e[12]}`) && Bn(o, "aria-label", i),
            2 & l && n !== (n = "edge" === e[15] ? -1 : 0) && Bn(o, "tabindex", n),
            2 & l && r !== (r = e[12]) && Bn(o, "data-direction", r),
            3 & l && a !== (a = "" + ("edge" === e[15] ? "edge" : "" + e[0])) && Bn(o, "data-shape", a),
            2 & l && s !== (s = `transform: translate3d(${e[13].x}px, ${e[13].y}px, 0) scale(${e[14].x}, ${e[14].y}); opacity: ${e[16]}`) && Bn(o, "style", s)
        },
        d(t) {
            t && Tn(o),
            l = !1,
            an(c)
        }
    }
}
function wm(t) {
    let e, o = [], i = new Map, n = t[1];
    const r = t=>t[12];
    for (let e = 0; e < n.length; e += 1) {
        let a = bm(t, n, e)
          , s = r(a);
        i.set(s, o[e] = vm(s, a))
    }
    return {
        c() {
            for (let t = 0; t < o.length; t += 1)
                o[t].c();
            e = En()
        },
        m(t, i) {
            for (let e = 0; e < o.length; e += 1)
                o[e].m(t, i);
            Mn(t, e, i)
        },
        p(t, [a]) {
            51 & a && (n = t[1],
            o = Tr(o, a, r, 1, t, n, i, e.parentNode, kr, vm, e, bm))
        },
        i: tn,
        o: tn,
        d(t) {
            for (let e = 0; e < o.length; e += 1)
                o[e].d(t);
            t && Tn(e)
        }
    }
}
function Sm(t, e, o) {
    let i, n, r, {rect: a=null} = e, {visible: s=!1} = e, {style: l} = e;
    const c = ls(void 0, {
        precision: 1e-4,
        stiffness: .2,
        damping: .4
    });
    un(t, c, (t=>o(8, n = t)));
    const d = ls(0, {
        precision: .001
    });
    let u;
    un(t, d, (t=>o(9, r = t)));
    const h = Jn();
    return t.$$set = t=>{
        "rect"in t && o(6, a = t.rect),
        "visible"in t && o(7, s = t.visible),
        "style"in t && o(0, l = t.style)
    }
    ,
    t.$$.update = ()=>{
        128 & t.$$.dirty && c.set(s ? 1 : .5),
        128 & t.$$.dirty && d.set(s ? 1 : 0),
        832 & t.$$.dirty && o(1, i = Object.keys(dm).map(((t,e)=>{
            const o = dm[t]
              , i = xm[o](a)
              , s = 1 === o.length ? "edge" : "corner"
              , l = "corner" === s;
            return {
                key: o,
                type: s,
                scale: {
                    x: /^(t|b)$/.test(o) ? a.width : l ? na(n, .5, 1.25) : 1,
                    y: /^(r|l)$/.test(o) ? a.height : l ? na(n, .5, 1.25) : 1
                },
                translate: {
                    x: i.x,
                    y: i.y
                },
                opacity: r
            }
        }
        )))
    }
    ,
    [l, i, c, d, (t,e)=>({detail: o})=>{
        u && e !== u || "resizestart" !== t && void 0 === u || ("resizestart" === t && (u = e),
        "resizeend" === t && (u = void 0),
        h(t, {
            direction: e,
            translation: o && o.translation
        }))
    }
    , t=>({detail: e})=>{
        h("resizestart", {
            direction: t,
            translation: {
                x: 0,
                y: 0
            }
        }),
        h("resizemove", {
            direction: t,
            translation: e
        }),
        h("resizeend", {
            direction: t,
            translation: {
                x: 0,
                y: 0
            }
        })
    }
    , a, s, n, r]
}
class Cm extends Br {
    constructor(t) {
        super(),
        zr(this, t, Sm, wm, ln, {
            rect: 6,
            visible: 7,
            style: 0
        })
    }
}
var km = t=>{
    function e(e, o) {
        t.dispatchEvent(new CustomEvent(e,{
            detail: o
        }))
    }
    const o = o=>{
        o.preventDefault(),
        t.addEventListener("gesturechange", i),
        t.addEventListener("gestureend", n),
        e("gesturedown")
    }
      , i = t=>{
        t.preventDefault(),
        e("gestureupdate", t.scale)
    }
      , n = t=>{
        e("gestureup", t.scale),
        t.preventDefault(),
        r()
    }
      , r = ()=>{
        t.removeEventListener("gesturechange", i),
        t.removeEventListener("gestureend", n)
    }
    ;
    return t.addEventListener("gesturestart", o),
    {
        destroy: ()=>{
            r(),
            t.removeEventListener("gesturestart", o)
        }
    }
}
  , Mm = t=>Y(t.clientX, t.clientY)
  , Tm = {
    [um]: pm,
    [hm]: mm,
    [pm]: um,
    [mm]: hm,
    [gm]: $m,
    [fm]: ym,
    [$m]: gm,
    [ym]: fm
}
  , Rm = {
    [um]: [.5, 0],
    [hm]: [1, .5],
    [pm]: [.5, 1],
    [mm]: [0, .5],
    [gm]: [0, 0],
    [fm]: [1, 0],
    [$m]: [1, 1],
    [ym]: [0, 1]
}
  , Pm = t=>{
    const e = t === mm || t === hm
      , o = t === um || t === pm;
    return [t === hm || t === fm || t === $m, t === mm || t === ym || t === gm, t === um || t === fm || t === gm, t === pm || t === $m || t === ym, e, o, e || o]
}
;
const Im = (t,e,o,i)=>{
    const {aspectRatio: n, minSize: r, maxSize: a} = i
      , s = e === hm || e === fm || e === $m
      , l = e === mm || e === ym || e === gm
      , c = e === um || e === fm || e === gm
      , d = e === pm || e === $m || e === ym
      , u = e === mm || e === hm
      , h = e === um || e === pm
      , p = At(o);
    s ? (p.x = t.x,
    p.width -= t.x) : l && (p.width = t.x),
    d ? (p.y = t.y,
    p.height -= t.y) : c && (p.height = t.y);
    const m = ((t,e)=>It(0, 0, t, e))(Math.min(p.width, a.width), Math.min(p.height, a.height));
    if (n)
        if (u) {
            const e = Math.min(t.y, o.height - t.y);
            m.height = Math.min(2 * e, m.height)
        } else if (h) {
            const e = Math.min(t.x, o.width - t.x);
            m.width = Math.min(2 * e, m.width)
        }
    const g = n ? ft(Jt(m, n)) : m
      , f = n ? ft(Kt(Lt(r), n)) : r;
    let $, y, x, b;
    s ? $ = t.x : l && (y = t.x),
    d ? x = t.y : c && (b = t.y),
    s ? y = $ + f.width : l && ($ = y - f.width),
    d ? b = x + f.height : c && (x = b - f.height),
    u ? (x = t.y - .5 * f.height,
    b = t.y + .5 * f.height) : h && ($ = t.x - .5 * f.width,
    y = t.x + .5 * f.width);
    const v = zt(Y($, x), Y(y, b));
    s ? y = $ + g.width : l && ($ = y - g.width),
    d ? b = x + g.height : c && (x = b - g.height),
    u ? (x = t.y - .5 * g.height,
    b = t.y + .5 * g.height) : h && ($ = t.x - .5 * g.width,
    y = t.x + .5 * g.width);
    return {
        inner: v,
        outer: zt(Y($, x), Y(y, b))
    }
}
;
var Am = (t,e,o={})=>{
    const {target: i, translate: n} = e
      , {aspectRatio: r} = o
      , a = Rm[Tm[i]]
      , s = nt(At(t), Y(a[0] * t.width, a[1] * t.height))
      , l = Rm[i]
      , c = nt(At(t), Y(l[0] * t.width, l[1] * t.height))
      , [d,u,h,p,m,g,f] = Pm(i);
    let $ = n.x
      , y = n.y;
    m ? y = 0 : g && ($ = 0);
    let[x,b,v,w] = Qt(t);
    if (d ? w = s.x : u && (b = s.x),
    p ? x = s.y : h && (v = s.y),
    d ? b = c.x + $ : u && (w = c.x + $),
    p ? v = c.y + y : h && (x = c.y + y),
    r)
        if (f) {
            let t = b - w
              , e = v - x;
            m ? (e = t / r,
            x = s.y - .5 * e,
            v = s.y + .5 * e) : g && (t = e * r,
            w = s.x - .5 * t,
            b = s.x + .5 * t)
        } else {
            const t = Y(c.x + $ - s.x, c.y + y - s.y);
            i === fm ? (t.x = Math.max(0, t.x),
            t.y = Math.min(0, t.y)) : i === $m ? (t.x = Math.max(0, t.x),
            t.y = Math.max(0, t.y)) : i === ym ? (t.x = Math.min(0, t.x),
            t.y = Math.max(0, t.y)) : i === gm && (t.x = Math.min(0, t.x),
            t.y = Math.min(0, t.y));
            const e = Q(t)
              , o = Y(r, 1)
              , n = at(tt(o), e);
            i === fm ? (b = s.x + n.x,
            x = s.y - n.y) : i === $m ? (b = s.x + n.x,
            v = s.y + n.y) : i === ym ? (w = s.x - n.x,
            v = s.y + n.y) : i === gm && (w = s.x - n.x,
            x = s.y - n.y)
        }
    return Dt(w, x, b - w, v - x)
}
  , Em = t=>180 * t / Math.PI;
function Lm(t) {
    let e, o, i;
    return o = new am({
        props: {
            elasticity: t[5],
            min: t[7],
            max: t[8],
            value: t[12],
            valueMin: t[0],
            valueMax: t[1],
            labelReset: t[6],
            base: t[11],
            valueLabel: Math.round(Em(t[12])) + "°",
            oninputstart: t[2],
            oninputmove: t[14],
            oninputend: t[15]
        }
    }),
    {
        c() {
            e = Rn("div"),
            Ar(o.$$.fragment),
            Bn(e, "class", "PinturaImageRotator")
        },
        m(t, n) {
            Mn(t, e, n),
            Er(o, e, null),
            i = !0
        },
        p(t, [e]) {
            const i = {};
            32 & e && (i.elasticity = t[5]),
            128 & e && (i.min = t[7]),
            256 & e && (i.max = t[8]),
            4096 & e && (i.value = t[12]),
            1 & e && (i.valueMin = t[0]),
            2 & e && (i.valueMax = t[1]),
            64 & e && (i.labelReset = t[6]),
            2048 & e && (i.base = t[11]),
            4096 & e && (i.valueLabel = Math.round(Em(t[12])) + "°"),
            4 & e && (i.oninputstart = t[2]),
            1544 & e && (i.oninputmove = t[14]),
            1552 & e && (i.oninputend = t[15]),
            o.$set(i)
        },
        i(t) {
            i || (br(o.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            i = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o)
        }
    }
}
function Fm(t, e, o) {
    let i, r, a, s, l, c;
    const d = Math.PI / 2
      , u = Math.PI / 4;
    let {rotation: h} = e
      , {valueMin: p} = e
      , {valueMax: m} = e
      , {oninputstart: g=n} = e
      , {oninputmove: f=n} = e
      , {oninputend: $=n} = e
      , {elasticity: y=0} = e
      , {labelReset: x} = e;
    return t.$$set = t=>{
        "rotation"in t && o(13, h = t.rotation),
        "valueMin"in t && o(0, p = t.valueMin),
        "valueMax"in t && o(1, m = t.valueMax),
        "oninputstart"in t && o(2, g = t.oninputstart),
        "oninputmove"in t && o(3, f = t.oninputmove),
        "oninputend"in t && o(4, $ = t.oninputend),
        "elasticity"in t && o(5, y = t.elasticity),
        "labelReset"in t && o(6, x = t.labelReset)
    }
    ,
    t.$$.update = ()=>{
        384 & t.$$.dirty && o(11, a = i + .5 * (r - i)),
        8192 & t.$$.dirty && o(9, s = Math.sign(h)),
        8192 & t.$$.dirty && o(10, l = Math.round(Math.abs(h) / d) * d),
        9728 & t.$$.dirty && o(12, c = h - s * l)
    }
    ,
    o(7, i = 1e-9 - u),
    o(8, r = u - 1e-9),
    [p, m, g, f, $, y, x, i, r, s, l, a, c, h, t=>f(s * l + t), t=>$(s * l + t)]
}
class zm extends Br {
    constructor(t) {
        super(),
        zr(this, t, Fm, Lm, ln, {
            rotation: 13,
            valueMin: 0,
            valueMax: 1,
            oninputstart: 2,
            oninputmove: 3,
            oninputend: 4,
            elasticity: 5,
            labelReset: 6
        })
    }
}
function Bm(t) {
    let e, o, i, n, r;
    return {
        c() {
            e = Rn("div"),
            o = Rn("p"),
            i = In(t[0]),
            n = In(" × "),
            r = In(t[1]),
            Bn(e, "class", "PinturaImageInfo")
        },
        m(t, a) {
            Mn(t, e, a),
            kn(e, o),
            kn(o, i),
            kn(o, n),
            kn(o, r)
        },
        p(t, [e]) {
            1 & e && Dn(i, t[0]),
            2 & e && Dn(r, t[1])
        },
        i: tn,
        o: tn,
        d(t) {
            t && Tn(e)
        }
    }
}
function Om(t, e, o) {
    let {width: i} = e
      , {height: n} = e;
    return t.$$set = t=>{
        "width"in t && o(0, i = t.width),
        "height"in t && o(1, n = t.height)
    }
    ,
    [i, n]
}
class Dm extends Br {
    constructor(t) {
        super(),
        zr(this, t, Om, Bm, ln, {
            width: 0,
            height: 1
        })
    }
}
function _m(t) {
    let e, o;
    return e = new _d({
        props: {
            items: t[0]
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            1 & o[0] && (i.items = t[0]),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Wm(t) {
    let e, o, i;
    return o = new cm({
        props: {
            $$slots: {
                default: [_m]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            e = Rn("div"),
            Ar(o.$$.fragment),
            Bn(e, "slot", "header")
        },
        m(t, n) {
            Mn(t, e, n),
            Er(o, e, null),
            i = !0
        },
        p(t, e) {
            const i = {};
            1 & e[0] | 128 & e[6] && (i.$$scope = {
                dirty: e,
                ctx: t
            }),
            o.$set(i)
        },
        i(t) {
            i || (br(o.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            i = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o)
        }
    }
}
function Vm(t) {
    let e, o;
    return e = new Dl({
        props: {
            onclick: t[80],
            label: t[4].cropLabelButtonRecenter,
            icon: t[4].cropIconButtonRecenter,
            class: "PinturaButtonCenter",
            disabled: !t[10],
            hideLabel: !0,
            style: `opacity: ${t[27]}; transform: translate3d(${t[28].x}px, ${t[28].y}px, 0)`
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            16 & o[0] && (i.label = t[4].cropLabelButtonRecenter),
            16 & o[0] && (i.icon = t[4].cropIconButtonRecenter),
            1024 & o[0] && (i.disabled = !t[10]),
            402653184 & o[0] && (i.style = `opacity: ${t[27]}; transform: translate3d(${t[28].x}px, ${t[28].y}px, 0)`),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Hm(t) {
    let e, o;
    return e = new Cm({
        props: {
            rect: t[11],
            visible: t[9],
            style: t[2]
        }
    }),
    e.$on("resizestart", t[60]),
    e.$on("resizemove", t[61]),
    e.$on("resizeend", t[62]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            2048 & o[0] && (i.rect = t[11]),
            512 & o[0] && (i.visible = t[9]),
            4 & o[0] && (i.style = t[2]),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Nm(t) {
    let e, o;
    return e = new Dm({
        props: {
            width: Math.round(t[7].width),
            height: Math.round(t[7].height)
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            128 & o[0] && (i.width = Math.round(t[7].width)),
            128 & o[0] && (i.height = Math.round(t[7].height)),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Um(t) {
    let e, o, i, n, r, a, s, l, c = t[17] && t[18] && Vm(t), d = t[17] && Hm(t), u = t[16] && Nm(t);
    return {
        c() {
            e = Rn("div"),
            o = Rn("div"),
            c && c.c(),
            i = An(),
            d && d.c(),
            r = An(),
            u && u.c(),
            Bn(o, "class", "PinturaStage"),
            Bn(e, "slot", "main")
        },
        m(h, p) {
            Mn(h, e, p),
            kn(e, o),
            c && c.m(o, null),
            kn(o, i),
            d && d.m(o, null),
            t[146](o),
            kn(e, r),
            u && u.m(e, null),
            a = !0,
            s || (l = [Ln(o, "measure", t[144]), yn(bs.call(null, o)), Ln(o, "wheel", (function() {
                sn(t[3] && t[79]) && (t[3] && t[79]).apply(this, arguments)
            }
            ), {
                passive: !1
            }), Ln(o, "interactionstart", t[66]), Ln(o, "interactionupdate", t[67]), Ln(o, "interactionrelease", t[69]), Ln(o, "interactionend", t[68]), yn(n = Wl.call(null, o, {
                drag: !0,
                pinch: t[3],
                inertia: !0,
                matchTarget: !0,
                getEventPosition: t[147]
            })), Ln(o, "gesturedown", t[76]), Ln(o, "gestureupdate", t[77]), Ln(o, "gestureup", t[78]), yn(km.call(null, o))],
            s = !0)
        },
        p(r, a) {
            (t = r)[17] && t[18] ? c ? (c.p(t, a),
            393216 & a[0] && br(c, 1)) : (c = Vm(t),
            c.c(),
            br(c, 1),
            c.m(o, i)) : c && (yr(),
            vr(c, 1, 1, (()=>{
                c = null
            }
            )),
            xr()),
            t[17] ? d ? (d.p(t, a),
            131072 & a[0] && br(d, 1)) : (d = Hm(t),
            d.c(),
            br(d, 1),
            d.m(o, null)) : d && (yr(),
            vr(d, 1, 1, (()=>{
                d = null
            }
            )),
            xr()),
            n && sn(n.update) && 32776 & a[0] && n.update.call(null, {
                drag: !0,
                pinch: t[3],
                inertia: !0,
                matchTarget: !0,
                getEventPosition: t[147]
            }),
            t[16] ? u ? (u.p(t, a),
            65536 & a[0] && br(u, 1)) : (u = Nm(t),
            u.c(),
            br(u, 1),
            u.m(e, null)) : u && (yr(),
            vr(u, 1, 1, (()=>{
                u = null
            }
            )),
            xr())
        },
        i(t) {
            a || (br(c),
            br(d),
            br(u),
            a = !0)
        },
        o(t) {
            vr(c),
            vr(d),
            vr(u),
            a = !1
        },
        d(o) {
            o && Tn(e),
            c && c.d(),
            d && d.d(),
            t[146](null),
            u && u.d(),
            s = !1,
            an(l)
        }
    }
}
function jm(t) {
    let e, o, i, n;
    const r = [{
        class: "PinturaControlList"
    }, {
        tabs: t[12]
    }, t[21]];
    let a = {
        $$slots: {
            default: [Xm, ({tab: t})=>({
                192: t
            }), ({tab: t})=>[0, 0, 0, 0, 0, 0, t ? 64 : 0]]
        },
        $$scope: {
            ctx: t
        }
    };
    for (let t = 0; t < r.length; t += 1)
        a = on(a, r[t]);
    e = new pl({
        props: a
    }),
    e.$on("select", t[145]);
    const s = [{
        class: "PinturaControlPanels"
    }, {
        panelClass: "PinturaControlPanel"
    }, {
        panels: t[22]
    }, t[21]];
    let l = {
        $$slots: {
            default: [qm, ({panel: t})=>({
                191: t
            }), ({panel: t})=>[0, 0, 0, 0, 0, 0, t ? 32 : 0]]
        },
        $$scope: {
            ctx: t
        }
    };
    for (let t = 0; t < s.length; t += 1)
        l = on(l, s[t]);
    return i = new kl({
        props: l
    }),
    {
        c() {
            Ar(e.$$.fragment),
            o = An(),
            Ar(i.$$.fragment)
        },
        m(t, r) {
            Er(e, t, r),
            Mn(t, o, r),
            Er(i, t, r),
            n = !0
        },
        p(t, o) {
            const n = 2101248 & o[0] ? Rr(r, [r[0], 4096 & o[0] && {
                tabs: t[12]
            }, 2097152 & o[0] && Pr(t[21])]) : {};
            192 & o[6] && (n.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(n);
            const a = 6291456 & o[0] ? Rr(s, [s[0], s[1], 4194304 & o[0] && {
                panels: t[22]
            }, 2097152 & o[0] && Pr(t[21])]) : {};
            117457168 & o[0] | 160 & o[6] && (a.$$scope = {
                dirty: o,
                ctx: t
            }),
            i.$set(a)
        },
        i(t) {
            n || (br(e.$$.fragment, t),
            br(i.$$.fragment, t),
            n = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            vr(i.$$.fragment, t),
            n = !1
        },
        d(t) {
            Lr(e, t),
            t && Tn(o),
            Lr(i, t)
        }
    }
}
function Xm(t) {
    let e, o, i = t[192].label + "";
    return {
        c() {
            e = Rn("span"),
            o = In(i)
        },
        m(t, i) {
            Mn(t, e, i),
            kn(e, o)
        },
        p(t, e) {
            64 & e[6] && i !== (i = t[192].label + "") && Dn(o, i)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function Ym(t) {
    let e, o;
    return e = new am({
        props: {
            elasticity: t[35] * t[36],
            base: Qm,
            min: t[14],
            max: Jm,
            valueMin: t[25][0],
            valueMax: t[25][1],
            value: t[26],
            labelReset: t[4].labelReset,
            valueLabel: Math.round(100 * t[26]) + "%",
            oninputstart: t[73],
            oninputmove: t[74],
            oninputend: t[75]
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            16384 & o[0] && (i.min = t[14]),
            33554432 & o[0] && (i.valueMin = t[25][0]),
            33554432 & o[0] && (i.valueMax = t[25][1]),
            67108864 & o[0] && (i.value = t[26]),
            16 & o[0] && (i.labelReset = t[4].labelReset),
            67108864 & o[0] && (i.valueLabel = Math.round(100 * t[26]) + "%"),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Gm(t) {
    let e, o;
    return e = new zm({
        props: {
            elasticity: t[35] * t[36],
            rotation: t[8],
            labelReset: t[4].labelReset,
            valueMin: t[24][0],
            valueMax: t[24][1],
            oninputstart: t[63],
            oninputmove: t[64],
            oninputend: t[65]
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            256 & o[0] && (i.rotation = t[8]),
            16 & o[0] && (i.labelReset = t[4].labelReset),
            16777216 & o[0] && (i.valueMin = t[24][0]),
            16777216 & o[0] && (i.valueMax = t[24][1]),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function qm(t) {
    let e, o, i, n;
    const r = [Gm, Ym]
      , a = [];
    function s(t, e) {
        return t[191] === t[85] + "-rotation" ? 0 : t[191] === t[85] + "-zoom" ? 1 : -1
    }
    return ~(e = s(t)) && (o = a[e] = r[e](t)),
    {
        c() {
            o && o.c(),
            i = En()
        },
        m(t, o) {
            ~e && a[e].m(t, o),
            Mn(t, i, o),
            n = !0
        },
        p(t, n) {
            let l = e;
            e = s(t),
            e === l ? ~e && a[e].p(t, n) : (o && (yr(),
            vr(a[l], 1, 1, (()=>{
                a[l] = null
            }
            )),
            xr()),
            ~e ? (o = a[e],
            o ? o.p(t, n) : (o = a[e] = r[e](t),
            o.c()),
            br(o, 1),
            o.m(i.parentNode, i)) : o = null)
        },
        i(t) {
            n || (br(o),
            n = !0)
        },
        o(t) {
            vr(o),
            n = !1
        },
        d(t) {
            ~e && a[e].d(t),
            t && Tn(i)
        }
    }
}
function Zm(t) {
    let e, o, i = t[20] && jm(t);
    return {
        c() {
            e = Rn("div"),
            i && i.c(),
            Bn(e, "slot", "footer"),
            Bn(e, "style", t[23])
        },
        m(t, n) {
            Mn(t, e, n),
            i && i.m(e, null),
            o = !0
        },
        p(t, n) {
            t[20] ? i ? (i.p(t, n),
            1048576 & n[0] && br(i, 1)) : (i = jm(t),
            i.c(),
            br(i, 1),
            i.m(e, null)) : i && (yr(),
            vr(i, 1, 1, (()=>{
                i = null
            }
            )),
            xr()),
            (!o || 8388608 & n[0]) && Bn(e, "style", t[23])
        },
        i(t) {
            o || (br(i),
            o = !0)
        },
        o(t) {
            vr(i),
            o = !1
        },
        d(t) {
            t && Tn(e),
            i && i.d()
        }
    }
}
function Km(t) {
    let e, o, i;
    function n(e) {
        t[148](e)
    }
    let r = {
        hasHeader: t[19],
        $$slots: {
            footer: [Zm],
            main: [Um],
            header: [Wm]
        },
        $$scope: {
            ctx: t
        }
    };
    return void 0 !== t[13] && (r.root = t[13]),
    e = new om({
        props: r
    }),
    ir.push((()=>Ir(e, "root", n))),
    e.$on("measure", t[149]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, o) {
            Er(e, t, o),
            i = !0
        },
        p(t, i) {
            const n = {};
            524288 & i[0] && (n.hasHeader = t[19]),
            536338429 & i[0] | 128 & i[6] && (n.$$scope = {
                dirty: i,
                ctx: t
            }),
            !o && 8192 & i[0] && (o = !0,
            n.root = t[13],
            cr((()=>o = !1))),
            e.$set(n)
        },
        i(t) {
            i || (br(e.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            i = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
const Jm = 1
  , Qm = 0;
function tg(t, e, o) {
    let i, n, r, a, s, l, c, d, u, h, p, m, g, f, $, y, x, b, v, w, S, C, k, M, R, P, I, A, E, L, F, z, B, O, W, H, U, j, X, G, q, J, et, it, st, lt, ct, dt, ut, ht, pt, gt, yt, bt, Mt, Tt, Rt, Pt, It, Et, Ft, zt, Bt, Ht = tn, Xt = ()=>(Ht(),
    Ht = cn(Yt, (t=>o(9, U = t))),
    Yt);
    t.$$.on_destroy.push((()=>Ht()));
    let {isActive: Yt} = e;
    Xt();
    let {stores: Gt} = e
      , {cropImageSelectionCornerStyle: te="circle"} = e
      , {cropWillRenderImageSelectionGuides: oe=((t,e)=>{
        const o = "rotate" == t;
        return {
            rows: o ? 5 : 3,
            cols: o ? 5 : 3,
            opacity: .25 * e
        }
    }
    )} = e
      , {cropAutoCenterImageSelectionTimeout: ie} = e
      , {cropEnableZoomMatchImageAspectRatio: ne=!0} = e
      , {cropEnableRotateMatchImageAspectRatio: re="never"} = e
      , {cropEnableRotationInput: ae=!0} = e
      , {cropEnableZoom: se=!0} = e
      , {cropEnableZoomInput: le=!0} = e
      , {cropEnableZoomAutoHide: ce=!0} = e
      , {cropEnableImageSelection: de=!0} = e
      , {cropEnableInfoIndicator: ue=!1} = e
      , {cropEnableZoomTowardsWheelPosition: he=!0} = e
      , {cropEnableLimitWheelInputToCropSelection: pe=!0} = e
      , {cropEnableCenterImageSelection: me=!0} = e
      , {cropEnableButtonRotateLeft: ge=!0} = e
      , {cropEnableButtonRotateRight: fe=!1} = e
      , {cropEnableButtonFlipHorizontal: $e=!0} = e
      , {cropEnableButtonFlipVertical: ye=!1} = e
      , {cropSelectPresetOptions: xe} = e
      , {cropEnableSelectPreset: be=!0} = e
      , {cropEnableButtonToggleCropLimit: ve=!1} = e
      , {cropWillRenderTools: we=_} = e
      , {cropActiveTransformTool: Se="rotation"} = e
      , {locale: Ce={}} = e
      , {tools: ke=[]} = e
      , Me = "idle";
    const Te = ()=>void 0 === P
      , Re = (t,e,o)=>N(o) ? e.width === Math.round(t.height) || e.height === Math.round(t.width) : e.width === Math.round(t.width) || e.height === Math.round(t.height)
      , Pe = ()=>(Te() || "always" === re && (()=>{
        if (1 === P)
            return !1;
        const t = 1 / P;
        return !!xe && !!Tc(xe).find((([e])=>e === t))
    }
    )()) && ((t,e,o)=>{
        const i = kt(St(mt(e), o), (t=>Math.abs(Math.round(t))))
          , n = wt(i)
          , r = _t(t);
        return ot(n, r)
    }
    )(I, A, E) && Re(I, A, E)
      , Ie = t=>{
        if ("never" !== re && Pe()) {
            $n(Ye, E += t, E);
            const e = N(E)
              , o = e ? A.height : A.width
              , i = e ? A.width : A.height;
            $n(io, I = Dt(0, 0, o, i), I),
            Te() || $n(so, P = D(o, i), P)
        } else
            $n(Ye, E += t, E)
    }
      , {history: Ae, env: Ee, isInteracting: Le, isInteractingFraction: Fe, rootRect: ze, stageRect: Be, utilRect: Oe, rootLineColor: De, animation: _e, elasticityMultiplier: We, rangeInputElasticity: Ve, presentationScalar: He, imagePreviewModifiers: Ne, imageOutlineOpacity: Ue, imageFlipX: je, imageFlipY: Xe, imageRotation: Ye, imageRotationRange: Ge, imageOutputSize: qe, imageSelectionRect: Ze, imageSelectionRectSnapshot: Ke, imageSelectionRectIntent: Qe, imageSelectionRectPresentation: to, imageCropRectIntent: eo, imageCropRectOrigin: oo, imageCropRect: io, imageCropMinSize: no, imageCropMaxSize: ro, imageCropRange: ao, imageCropAspectRatio: so, imageCropRectAspectRatio: lo, imageCropLimitToImage: co, imageSize: uo, imageScalar: ho, imageOverlayMarkup: po, framePadded: mo} = Gt;
    let go, fo, $o;
    un(t, Ee, (t=>o(119, H = t))),
    un(t, Le, (t=>o(120, j = t))),
    un(t, ze, (t=>o(15, dt = t))),
    un(t, Be, (t=>o(125, ut = t))),
    un(t, Oe, (t=>o(124, it = t))),
    un(t, _e, (t=>o(142, Rt = t))),
    un(t, He, (t=>o(123, q = t))),
    un(t, Ne, (t=>o(137, bt = t))),
    un(t, je, (t=>o(113, F = t))),
    un(t, Xe, (t=>o(112, L = t))),
    un(t, Ye, (t=>o(8, E = t))),
    un(t, Ge, (t=>o(24, It = t))),
    un(t, qe, (t=>o(160, B = t))),
    un(t, Ze, (t=>o(122, G = t))),
    un(t, Ke, (t=>o(121, X = t))),
    un(t, Qe, (t=>o(162, et = t))),
    un(t, to, (t=>o(128, pt = t))),
    un(t, eo, (t=>o(164, lt = t))),
    un(t, oo, (t=>o(163, st = t))),
    un(t, io, (t=>o(7, I = t))),
    un(t, no, (t=>o(117, O = t))),
    un(t, ro, (t=>o(161, J = t))),
    un(t, ao, (t=>o(165, ct = t))),
    un(t, so, (t=>o(159, P = t))),
    un(t, co, (t=>o(118, W = t))),
    un(t, uo, (t=>o(111, A = t))),
    un(t, ho, (t=>o(135, gt = t))),
    un(t, po, (t=>o(167, Mt = t))),
    un(t, mo, (t=>o(136, yt = t)));
    const yo = (t,e)=>{
        const o = {
            target: t,
            translate: e
        };
        let i, n = Am(X, o, {
            aspectRatio: P
        });
        const r = ft(Nt(At(n), q));
        if (ta(A, E),
        r.width < O.width || r.height < O.height) {
            const o = e.y < 0
              , n = e.x > 0
              , a = e.x < 0
              , s = e.y > 0
              , l = "t" === t && o || "r" === t && n || "b" === t && s || "l" === t && a || "tr" === t && (n || o) || "tl" === t && (a || o) || "br" === t && (n || s) || "bl" === t && (a || s)
              , c = jt(r)
              , d = ea(A, E, c);
            if (l && (d.width < O.width || d.height < O.height)) {
                if (0 !== E) {
                    const t = Math.sign(E)
                      , e = Math.round(Math.abs(E) / V) * V
                      , o = E - t * e
                      , i = e / V % 2 == 1
                      , n = i ? A.height : A.width
                      , a = i ? A.width : A.height
                      , s = Math.abs(o)
                      , l = Math.sin(s)
                      , c = Math.cos(s);
                    if (r.width < O.width) {
                        r.width = O.width;
                        const t = n - (c * r.width + l * r.height)
                          , e = a - (l * r.width + c * r.height);
                        t < e ? r.height = (n - c * r.width) / l : e < t && (r.height = (a - l * r.width) / c)
                    }
                    if (r.height < O.height) {
                        r.height = O.height;
                        const t = n - (c * r.width + l * r.height)
                          , e = a - (l * r.width + c * r.height);
                        t < e ? r.width = (n - l * r.height) / c : e < t && (r.width = (a - c * r.height) / l)
                    }
                } else
                    r.width < O.width && (r.width = O.width,
                    r.height = A.height),
                    r.height < O.height && (r.height = O.height,
                    r.width = A.width);
                i = jt(r)
            }
        }
        return i && (n = Am(X, o, {
            aspectRatio: i || P
        })),
        {
            boundsLimited: ((t,e,o,i={})=>{
                const {target: n, translate: r} = e
                  , {aspectRatio: a, minSize: s, maxSize: l} = i
                  , c = Rm[Tm[n]]
                  , d = nt(Y(t.x, t.y), Y(c[0] * t.width, c[1] * t.height))
                  , u = Rm[n]
                  , h = nt(At(t), Y(u[0] * t.width, u[1] * t.height))
                  , [p,m,g,f,$,y,x] = Pm(n);
                let b = r.x
                  , v = r.y;
                $ ? v = 0 : y && (b = 0);
                const w = Im(d, n, o, {
                    aspectRatio: a,
                    minSize: s,
                    maxSize: l
                });
                let[S,C,k,M] = Qt(t);
                if (p ? M = d.x : m && (C = d.x),
                f ? S = d.y : g && (k = d.y),
                p) {
                    const t = w.inner.x + w.inner.width
                      , e = w.outer.x + w.outer.width;
                    C = na(h.x + b, t, e)
                } else if (m) {
                    const t = w.outer.x
                      , e = w.inner.x;
                    M = na(h.x + b, t, e)
                }
                if (f) {
                    const t = w.inner.y + w.inner.height
                      , e = w.outer.y + w.outer.height;
                    k = na(h.y + v, t, e)
                } else if (g) {
                    const t = w.outer.y
                      , e = w.inner.y;
                    S = na(h.y + v, t, e)
                }
                if (a)
                    if (x) {
                        let t = C - M
                          , e = k - S;
                        $ ? (e = t / a,
                        S = d.y - .5 * e,
                        k = d.y + .5 * e) : y && (t = e * a,
                        M = d.x - .5 * t,
                        C = d.x + .5 * t)
                    } else {
                        const t = Y(h.x + b - d.x, h.y + v - d.y);
                        n === fm ? (t.x = Math.max(0, t.x),
                        t.y = Math.min(0, t.y)) : n === $m ? (t.x = Math.max(0, t.x),
                        t.y = Math.max(0, t.y)) : n === ym ? (t.x = Math.min(0, t.x),
                        t.y = Math.max(0, t.y)) : n === gm && (t.x = Math.min(0, t.x),
                        t.y = Math.min(0, t.y));
                        const e = Q(t)
                          , o = Q(Y(w.inner.width, w.inner.height))
                          , i = Q(Y(w.outer.width, w.outer.height))
                          , r = na(e, o, i)
                          , s = Y(a, 1)
                          , l = at(tt(s), r);
                        n === fm ? (C = d.x + l.x,
                        S = d.y - l.y) : n === $m ? (C = d.x + l.x,
                        k = d.y + l.y) : n === ym ? (M = d.x - l.x,
                        k = d.y + l.y) : n === gm && (M = d.x - l.x,
                        S = d.y - l.y)
                    }
                return Dt(M, S, C - M, k - S)
            }
            )(X, o, it, {
                aspectRatio: P || i,
                minSize: fo,
                maxSize: $o
            }),
            boundsIntent: n
        }
    }
    ;
    let xo = void 0
      , bo = void 0;
    const vo = ({translation: t, scalar: e})=>{
        const o = Math.min(G.width / I.width, G.height / I.height)
          , i = at(Z(t), 1 / o);
        let n;
        if (bo) {
            const e = rt(Z(bo), t);
            bo = t,
            n = Wt(At(I), e)
        } else
            n = Wt(At(xo), K(Z(i))),
            void 0 !== e && Vt(n, 1 / e);
        $n(eo, lt = n, lt),
        $n(io, I = n, I)
    }
      , wo = Wr([ao, io], (([t,e],o)=>{
        if (!e)
            return;
        const [i,n] = t
          , r = jt(e);
        o([ft(ee(Kt(i, r), Io)), ft(ee(Jt(n, r), Io))])
    }
    ));
    un(t, wo, (t=>o(166, ht = t)));
    const So = Wr([uo, co, no, ro, ao, Ye], (([t,e,o,i,n,r],a)=>{
        if (!t)
            return;
        const s = n[0]
          , l = n[1];
        let c, d;
        e ? (c = ((t,e,o)=>N(o) ? 1 - 1 / Math.min(t.height / e.width, t.width / e.height) : 1 - 1 / Math.min(t.width / e.width, t.height / e.height))(t, l, r),
        d = Math.min(s.width / o.width, s.height / o.height)) : (d = 1,
        c = -1);
        a([Io(c), Io(d)])
    }
    ));
    un(t, So, (t=>o(25, Et = t)));
    const Co = Wr([uo, io, ao, Ye], (([t,e,o,i],n)=>{
        if (!t || !e)
            return n(0);
        let r;
        const a = o[0]
          , s = o[1]
          , l = e.width
          , c = e.height
          , d = jt(e)
          , u = N(i) ? xt(t.height, t.width) : t
          , h = Jt(u, d);
        if (l <= h.width || c <= h.height) {
            const t = h.width - a.width
              , e = h.height - a.height;
            r = 0 === t || 0 === e ? 1 : 1 - Math.min((l - a.width) / t, (c - a.height) / e)
        } else {
            const t = s.width - h.width
              , e = s.height - h.height
              , o = Jt({
                width: t,
                height: e
            }, d);
            r = -Math.min((l - h.width) / o.width, (c - h.height) / o.height)
        }
        n(r)
    }
    ));
    un(t, Co, (t=>o(26, Ft = t)));
    const ko = t=>{
        const e = jt(xo);
        let o, i, n;
        const r = N(E) ? xt(A.height, A.width) : A
          , a = Jt(r, e);
        if (t >= 0) {
            const r = a.width - ct[0].width
              , s = a.height - ct[0].height;
            o = a.width - r * t,
            i = a.height - s * t,
            n = Kt({
                width: o,
                height: i
            }, e)
        } else {
            const r = ct[1].width - a.width
              , s = ct[1].height - a.height;
            o = a.width + r * -t,
            i = a.height + s * -t,
            n = Jt({
                width: o,
                height: i
            }, e)
        }
        o = n.width,
        i = n.height;
        const s = xo.x + .5 * xo.width - .5 * o
          , l = xo.y + .5 * xo.height - .5 * i;
        $n(io, I = {
            x: s,
            y: l,
            width: o,
            height: i
        }, I)
    }
    ;
    let Mo;
    const To = t=>{
        const e = Vt(At(Mo), 1 / t);
        $n(eo, lt = e, lt),
        $n(io, I = e, I)
    }
    ;
    let Ro;
    const Po = Jn()
      , Ao = ()=>{
        Po("measure", At(it))
    }
    ;
    let Eo;
    const Lo = ls(0, {
        precision: 1e-4
    });
    un(t, Lo, (t=>o(27, zt = t)));
    const Fo = ls();
    un(t, Fo, (t=>o(28, Bt = t)));
    const zo = Wr([so, qe], (([t,e],o)=>{
        if (!xe)
            return;
        const i = Tc(xe)
          , n = [...i].map((t=>t[0])).sort(((t,e)=>Je(t[0]) && !Je(e[0]) ? 1 : -1)).find((o=>{
            if (Je(o) && e) {
                const [i,n] = o
                  , r = e.width === i && e.height === n
                  , a = t === D(i, n);
                return r && a
            }
            return o === t
        }
        ));
        o(i.map((t=>t[0])).findIndex((t=>Je(t) ? la(t, n) : t === n)))
    }
    ));
    un(t, zo, (t=>o(115, z = t)));
    const Bo = t=>{
        if (!xe || -1 === t)
            return;
        const e = Tc(xe)[t][0];
        return e ? Je(e) ? D(e[0], e[1]) : e : void 0
    }
      , Oo = Wr([De, to, Fe], (([t,e,o],i)=>{
        const {rows: n, cols: r, opacity: a} = oe(Me, o);
        if (!e || a <= 0)
            return i([]);
        const {x: s, y: l, width: c, height: d} = e
          , u = c / r
          , h = d / n
          , p = [];
        for (let e = 1; e <= n - 1; e++) {
            const o = l + h * e;
            p.push({
                id: "image-selection-guide-row-" + e,
                points: [Y(s, o), Y(s + c, o)],
                opacity: a,
                strokeWidth: 1,
                strokeColor: t
            })
        }
        for (let e = 1; e <= r - 1; e++) {
            const o = s + u * e;
            p.push({
                id: "image-selection-guide-col-" + e,
                points: [Y(o, l), Y(o, l + d)],
                opacity: a,
                strokeWidth: 1,
                strokeColor: t
            })
        }
        i(p)
    }
    ));
    un(t, Oo, (t=>o(138, Tt = t)));
    const Do = "crop-" + T();
    let _o, Wo = Do + "-" + (ae ? Se : "zoom"), Vo = Wo, Ho = void 0;
    const No = ls(Rt ? 20 : 0);
    un(t, No, (t=>o(143, Pt = t)));
    return t.$$set = t=>{
        "isActive"in t && Xt(o(1, Yt = t.isActive)),
        "stores"in t && o(88, Gt = t.stores),
        "cropImageSelectionCornerStyle"in t && o(2, te = t.cropImageSelectionCornerStyle),
        "cropWillRenderImageSelectionGuides"in t && o(89, oe = t.cropWillRenderImageSelectionGuides),
        "cropAutoCenterImageSelectionTimeout"in t && o(90, ie = t.cropAutoCenterImageSelectionTimeout),
        "cropEnableZoomMatchImageAspectRatio"in t && o(91, ne = t.cropEnableZoomMatchImageAspectRatio),
        "cropEnableRotateMatchImageAspectRatio"in t && o(92, re = t.cropEnableRotateMatchImageAspectRatio),
        "cropEnableRotationInput"in t && o(93, ae = t.cropEnableRotationInput),
        "cropEnableZoom"in t && o(3, se = t.cropEnableZoom),
        "cropEnableZoomInput"in t && o(94, le = t.cropEnableZoomInput),
        "cropEnableZoomAutoHide"in t && o(95, ce = t.cropEnableZoomAutoHide),
        "cropEnableImageSelection"in t && o(96, de = t.cropEnableImageSelection),
        "cropEnableInfoIndicator"in t && o(97, ue = t.cropEnableInfoIndicator),
        "cropEnableZoomTowardsWheelPosition"in t && o(98, he = t.cropEnableZoomTowardsWheelPosition),
        "cropEnableLimitWheelInputToCropSelection"in t && o(99, pe = t.cropEnableLimitWheelInputToCropSelection),
        "cropEnableCenterImageSelection"in t && o(100, me = t.cropEnableCenterImageSelection),
        "cropEnableButtonRotateLeft"in t && o(101, ge = t.cropEnableButtonRotateLeft),
        "cropEnableButtonRotateRight"in t && o(102, fe = t.cropEnableButtonRotateRight),
        "cropEnableButtonFlipHorizontal"in t && o(103, $e = t.cropEnableButtonFlipHorizontal),
        "cropEnableButtonFlipVertical"in t && o(104, ye = t.cropEnableButtonFlipVertical),
        "cropSelectPresetOptions"in t && o(105, xe = t.cropSelectPresetOptions),
        "cropEnableSelectPreset"in t && o(106, be = t.cropEnableSelectPreset),
        "cropEnableButtonToggleCropLimit"in t && o(107, ve = t.cropEnableButtonToggleCropLimit),
        "cropWillRenderTools"in t && o(108, we = t.cropWillRenderTools),
        "cropActiveTransformTool"in t && o(109, Se = t.cropActiveTransformTool),
        "locale"in t && o(4, Ce = t.locale),
        "tools"in t && o(0, ke = t.tools)
    }
    ,
    t.$$.update = ()=>{
        67108864 & t.$$.dirty[3] && o(133, u = "overlay" === H.layoutMode),
        8192 & t.$$.dirty[3] | 512 & t.$$.dirty[4] && o(114, x = be && !u),
        536870912 & t.$$.dirty[3] | 1 & t.$$.dirty[4] && o(129, a = it && G && qt(it, G)),
        536870912 & t.$$.dirty[3] | 32 & t.$$.dirty[4] && o(130, s = !(!G || !a)),
        536870912 & t.$$.dirty[3] | 96 & t.$$.dirty[4] && o(116, l = s && Ut(G, a, (t=>Io(t, 5)))),
        272 & t.$$.dirty[0] | 134012672 & t.$$.dirty[3] && o(0, ke = we([ge && ["Button", "rotate-left", {
            label: Ce.cropLabelButtonRotateLeft,
            labelClass: "PinturaToolbarContentWide",
            icon: Ce.cropIconButtonRotateLeft,
            onclick: ()=>{
                Ie(-Math.PI / 2),
                Ae.write()
            }
        }], fe && ["Button", "rotate-right", {
            label: Ce.cropLabelButtonRotateRight,
            labelClass: "PinturaToolbarContentWide",
            icon: Ce.cropIconButtonRotateRight,
            onclick: ()=>{
                Ie(Math.PI / 2),
                Ae.write()
            }
        }], $e && ["Button", "flip-horizontal", {
            label: Ce.cropLabelButtonFlipHorizontal,
            labelClass: "PinturaToolbarContentWide",
            icon: Ce.cropIconButtonFlipHorizontal,
            onclick: ()=>{
                N(E) ? $n(Xe, L = !L, L) : $n(je, F = !F, F),
                Ae.write()
            }
        }], ye && ["Button", "flip-vertical", {
            label: Ce.cropLabelButtonFlipVertical,
            labelClass: "PinturaToolbarContentWide",
            icon: Ce.cropIconButtonFlipVertical,
            onclick: ()=>{
                N(E) ? $n(je, F = !F, F) : $n(Xe, L = !L, L),
                Ae.write()
            }
        }], x && xe && ["Dropdown", "select-preset", {
            icon: Pc(Ce.cropIconSelectPreset, Ce, Bo(z)),
            label: Ce.cropLabelSelectPreset,
            labelClass: "PinturaToolbarContentWide",
            options: xe,
            selectedIndex: z,
            onchange: ({value: t})=>{
                Je(t) ? ($n(so, P = D(t[0], t[1]), P),
                $n(qe, B = $t(t), B)) : $n(so, P = t, P),
                l && Ao(),
                Ae.write()
            }
            ,
            optionMapper: t=>{
                let e = !1;
                const o = Je(t.value) ? t.value[0] / t.value[1] : t.value;
                if (o) {
                    const t = ea(A, E, o);
                    e = t.width < O.width || t.height < O.height
                }
                return t.icon = ((t,e={})=>{
                    const {width: o=24, height: i=24, bounds: n=16, radius: r=3} = e;
                    let a, s, l, c, d = Je(t) ? D(t[0], t[1]) : t, u = !!d;
                    return d = u ? d : 1,
                    l = d > 1 ? n : d * n,
                    c = l / d,
                    a = Math.round(.5 * (o - l)),
                    s = Math.round(.5 * (i - c)),
                    `<rect fill="${u ? "currentColor" : "none"}" stroke="${u ? "none" : "currentColor"}" stroke-width="${o / 16}" stroke-dasharray="${[o / 12, o / 6].join(" ")}" x="${a}" y="${s}" width="${l}" height="${c}" rx="${r}"/>`
                }
                )(t.value, {
                    bounds: 14
                }),
                {
                    ...t,
                    disabled: e
                }
            }
        }], ve && ["Dropdown", "select-crop-limit", {
            icon: Pc(Ce.cropIconCropBoundary, Ce, W),
            label: Ce.cropLabelCropBoundary,
            labelClass: "PinturaToolbarContentWide",
            onchange: ({value: t})=>{
                $n(co, W = t, W),
                Ae.write()
            }
            ,
            options: [[!0, Ce.cropLabelCropBoundaryEdge, {
                icon: Pc(Ce.cropIconCropBoundary, Ce, !0)
            }], [!1, Ce.cropLabelCropBoundaryNone, {
                icon: Pc(Ce.cropIconCropBoundary, Ce, !1)
            }]]
        }]].filter(Boolean), H, (()=>({}))).filter(Boolean)),
        512 & t.$$.dirty[0] && U && Ue.set(1),
        33554432 & t.$$.dirty[3] && o(14, i = W ? 0 : -1),
        3 & t.$$.dirty[4] && o(126, n = it && Y(-(ut.x - it.x), -(ut.y - it.y))),
        20 & t.$$.dirty[4] && o(127, r = pt && Y(mc(pt.x + .5 * pt.width + n.x), mc(pt.y + .5 * pt.height + n.y))),
        268435456 & t.$$.dirty[3] && o(131, c = null != X),
        33 & t.$$.dirty[4] && o(132, d = it && a && (a.height === it.height || a.width === it.width)),
        1073741824 & t.$$.dirty[3] | 2304 & t.$$.dirty[4] && o(134, h = !d && q < 1 && gt < 1),
        8388608 & t.$$.dirty[3] | 1216 & t.$$.dirty[4] && o(10, p = s && !c && (!l || h)),
        128 & t.$$.dirty[0] | 16 & t.$$.dirty[3] | 512 & t.$$.dirty[4] && o(16, m = ue && !!I && !u),
        20 & t.$$.dirty[4] && o(11, $ = pt && n && {
            x: pt.x + n.x,
            y: pt.y + n.y,
            width: pt.width,
            height: pt.height
        }),
        2048 & t.$$.dirty[0] | 8 & t.$$.dirty[3] | 512 & t.$$.dirty[4] && o(17, g = de && !!$ && !u),
        268435456 & t.$$.dirty[2] | 128 & t.$$.dirty[3] | 8 & t.$$.dirty[4] && o(18, f = me && !!r && !ie),
        1024 & t.$$.dirty[0] | 268435456 & t.$$.dirty[2] | 134348800 & t.$$.dirty[3] && p && ie && !j && (clearTimeout(Eo),
        o(110, Eo = setTimeout(Ao, ie))),
        134348800 & t.$$.dirty[3] && j && clearTimeout(Eo),
        1024 & t.$$.dirty[0] && Lo.set(p ? 1 : 0),
        8 & t.$$.dirty[4] && Fo.set(r),
        512 & t.$$.dirty[0] | 12288 & t.$$.dirty[4] && (U && !yt ? $n(Ne, bt.crop = {
            maskOpacity: .85,
            maskMarkupOpacity: .85
        }, bt) : delete bt.crop),
        16384 & t.$$.dirty[4] && Tt && (()=>{
            const t = Mt.filter((t=>!/^image\-selection\-guide/.test(t.id)));
            $n(po, Mt = U ? [...t, ...Tt] : t, Mt)
        }
        )(),
        67108864 & t.$$.dirty[3] && o(139, y = "short" !== H.verticalSpace),
        33280 & t.$$.dirty[4] && o(19, b = y && !u),
        8 & t.$$.dirty[0] | 2 & t.$$.dirty[3] && o(140, v = se && le),
        4 & t.$$.dirty[3] | 98304 & t.$$.dirty[4] && o(141, w = ce ? y && v : v),
        1 & t.$$.dirty[3] | 131072 & t.$$.dirty[4] && o(20, S = ae || w),
        131072 & t.$$.dirty[4] && (w || o(5, Vo = Wo)),
        32 & t.$$.dirty[0] && o(21, C = {
            name: Do,
            selected: Vo
        }),
        16 & t.$$.dirty[0] | 1 & t.$$.dirty[3] | 131072 & t.$$.dirty[4] && o(12, k = [ae && {
            id: Do + "-rotation",
            label: Ce.cropLabelTabRotation
        }, w && {
            id: Do + "-zoom",
            label: Ce.cropLabelTabZoom
        }].filter(Boolean)),
        4096 & t.$$.dirty[0] && o(22, M = k.map((t=>t.id))),
        64 & t.$$.dirty[0] | 512 & t.$$.dirty[4] && _o && !_o.children.length && u && _o.dispatchEvent(new CustomEvent("measure",{
            detail: _o.rect
        })),
        512 & t.$$.dirty[0] | 262144 & t.$$.dirty[4] && Rt && No.set(U ? 0 : 20),
        524288 & t.$$.dirty[4] && o(23, R = Pt ? `transform: translateY(${Pt}px)` : void 0)
    }
    ,
    [ke, Yt, te, se, Ce, Vo, _o, I, E, U, p, $, k, Ho, i, dt, m, g, f, b, S, C, M, R, It, Et, Ft, zt, Bt, Ee, Le, ze, Be, Oe, _e, We, Ve, He, Ne, je, Xe, Ye, Ge, qe, Ze, Ke, Qe, to, eo, oo, io, no, ro, ao, so, co, uo, ho, po, mo, ()=>{
        Me = "select",
        $n(Le, j = !0, j),
        $n(Ke, X = At(G), X),
        go = q,
        fo = vt(mt(O), go),
        $o = vt(mt(J), go)
    }
    , ({detail: t})=>{
        const {boundsLimited: e, boundsIntent: o} = yo(t.direction, t.translation);
        $n(Qe, et = o, et),
        $n(Ze, G = e, G)
    }
    , ({detail: t})=>{
        const {boundsLimited: e} = yo(t.direction, t.translation);
        $n(Le, j = !1, j),
        $n(Qe, et = void 0, et),
        Q(t.translation) && ($n(Ze, G = e, G),
        Ae.write()),
        $n(Ke, X = void 0, X),
        Me = void 0
    }
    , ()=>{
        Me = "rotate",
        $n(Le, j = !0, j),
        $n(oo, st = At(I), st)
    }
    , t=>{
        $n(Ye, E = t, E)
    }
    , t=>{
        $n(Le, j = !1, j),
        $n(Ye, E = t, E),
        Ae.write(),
        $n(oo, st = void 0, st)
    }
    , ()=>{
        Me = "pan",
        bo = void 0,
        $n(Le, j = !0, j),
        xo = At(I)
    }
    , ({detail: t})=>vo(t), ({detail: t})=>{
        $n(Le, j = !1, j),
        (Q(t.translation) > 0 || 0 !== t.scalar) && (vo(t),
        Ae.write()),
        $n(eo, lt = void 0, lt),
        xo = void 0
    }
    , ({detail: t})=>{
        bo = t.translation,
        $n(Le, j = !1, j)
    }
    , wo, So, Co, ()=>{
        Me = "zoom",
        $n(Le, j = !0, j),
        xo = At(I)
    }
    , t=>{
        ko(t)
    }
    , t=>{
        ko(t),
        Ae.write(),
        $n(Le, j = !1, j),
        xo = void 0
    }
    , ()=>{
        Me = "zoom",
        xo || (Mo = At(I),
        $n(Le, j = !0, j))
    }
    , ({detail: t})=>{
        Mo && To(t)
    }
    , ({detail: t})=>{
        Mo && ($n(Le, j = !1, j),
        To(t),
        $n(eo, lt = void 0, lt),
        Mo = void 0,
        Ae.write())
    }
    , t=>{
        const e = ((t,e,o)=>{
            const i = Mm(t);
            return rt(rt(i, e), o)
        }
        )(t, dt, ut);
        if (pe && !Zt(G, e))
            return;
        Me = "zoom",
        $n(Le, j = !0, j),
        t.preventDefault(),
        t.stopPropagation();
        const o = jl(t)
          , i = 1 + o / 100
          , n = At(I)
          , r = 1 === Math.min(I.width / O.width, I.height / O.height);
        if (ne && W) {
            const t = Re(I, A, E);
            if (Te() && t && o > 0 && l) {
                $n(Le, j = !1, j);
                const t = N(E) ? Lt({
                    height: A.width,
                    width: A.height
                }) : Lt(A);
                if (Ut(n, t))
                    return;
                if (clearTimeout(Ro),
                Ut(Ae.state.crop, t))
                    return;
                return $n(io, I = t, I),
                void Ae.write()
            }
        }
        let a = _t(I);
        if (he && o < 0 && !r) {
            const t = rt(Z(e), G)
              , o = Math.min(G.width / I.width, G.height / I.height)
              , i = Vt(At(G), 1.1);
            a = Zt(i, e) ? nt(At(I), at(t, 1 / o)) : a
        }
        let s = Vt(At(I), i, a);
        Ct(ht[1], s) || (s = Ot(_t(s), ht[1])),
        Ct(s, ht[0]) || (s = Ot(_t(s), ht[0])),
        Ut(n, s, Io) ? $n(Le, j = !1, j) : ($n(io, I = ee(s, (t=>Io(t, 5))), I),
        $n(Le, j = !1, j),
        clearTimeout(Ro),
        Ro = setTimeout((()=>{
            Ae.write()
        }
        ), 500))
    }
    , Ao, Lo, Fo, zo, Oo, Do, No, "crop", Gt, oe, ie, ne, re, ae, le, ce, de, ue, he, pe, me, ge, fe, $e, ye, xe, be, ve, we, Se, Eo, A, L, F, x, z, l, O, W, H, j, X, G, q, it, ut, n, r, pt, a, s, c, d, u, h, gt, yt, bt, Tt, y, v, w, Rt, Pt, function(e) {
        er(t, e)
    }
    , ({detail: t})=>o(5, Vo = t), function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            _o = t,
            o(6, _o)
        }
        ))
    }
    , t=>Mm(t), function(t) {
        Ho = t,
        o(13, Ho)
    }
    , function(e) {
        er(t, e)
    }
    ]
}
var eg = {
    util: ["crop", class extends Br {
        constructor(t) {
            super(),
            zr(this, t, tg, Km, ln, {
                name: 87,
                isActive: 1,
                stores: 88,
                cropImageSelectionCornerStyle: 2,
                cropWillRenderImageSelectionGuides: 89,
                cropAutoCenterImageSelectionTimeout: 90,
                cropEnableZoomMatchImageAspectRatio: 91,
                cropEnableRotateMatchImageAspectRatio: 92,
                cropEnableRotationInput: 93,
                cropEnableZoom: 3,
                cropEnableZoomInput: 94,
                cropEnableZoomAutoHide: 95,
                cropEnableImageSelection: 96,
                cropEnableInfoIndicator: 97,
                cropEnableZoomTowardsWheelPosition: 98,
                cropEnableLimitWheelInputToCropSelection: 99,
                cropEnableCenterImageSelection: 100,
                cropEnableButtonRotateLeft: 101,
                cropEnableButtonRotateRight: 102,
                cropEnableButtonFlipHorizontal: 103,
                cropEnableButtonFlipVertical: 104,
                cropSelectPresetOptions: 105,
                cropEnableSelectPreset: 106,
                cropEnableButtonToggleCropLimit: 107,
                cropWillRenderTools: 108,
                cropActiveTransformTool: 109,
                locale: 4,
                tools: 0
            }, [-1, -1, -1, -1, -1, -1, -1])
        }
        get name() {
            return this.$$.ctx[87]
        }
        get isActive() {
            return this.$$.ctx[1]
        }
        set isActive(t) {
            this.$set({
                isActive: t
            }),
            hr()
        }
        get stores() {
            return this.$$.ctx[88]
        }
        set stores(t) {
            this.$set({
                stores: t
            }),
            hr()
        }
        get cropImageSelectionCornerStyle() {
            return this.$$.ctx[2]
        }
        set cropImageSelectionCornerStyle(t) {
            this.$set({
                cropImageSelectionCornerStyle: t
            }),
            hr()
        }
        get cropWillRenderImageSelectionGuides() {
            return this.$$.ctx[89]
        }
        set cropWillRenderImageSelectionGuides(t) {
            this.$set({
                cropWillRenderImageSelectionGuides: t
            }),
            hr()
        }
        get cropAutoCenterImageSelectionTimeout() {
            return this.$$.ctx[90]
        }
        set cropAutoCenterImageSelectionTimeout(t) {
            this.$set({
                cropAutoCenterImageSelectionTimeout: t
            }),
            hr()
        }
        get cropEnableZoomMatchImageAspectRatio() {
            return this.$$.ctx[91]
        }
        set cropEnableZoomMatchImageAspectRatio(t) {
            this.$set({
                cropEnableZoomMatchImageAspectRatio: t
            }),
            hr()
        }
        get cropEnableRotateMatchImageAspectRatio() {
            return this.$$.ctx[92]
        }
        set cropEnableRotateMatchImageAspectRatio(t) {
            this.$set({
                cropEnableRotateMatchImageAspectRatio: t
            }),
            hr()
        }
        get cropEnableRotationInput() {
            return this.$$.ctx[93]
        }
        set cropEnableRotationInput(t) {
            this.$set({
                cropEnableRotationInput: t
            }),
            hr()
        }
        get cropEnableZoom() {
            return this.$$.ctx[3]
        }
        set cropEnableZoom(t) {
            this.$set({
                cropEnableZoom: t
            }),
            hr()
        }
        get cropEnableZoomInput() {
            return this.$$.ctx[94]
        }
        set cropEnableZoomInput(t) {
            this.$set({
                cropEnableZoomInput: t
            }),
            hr()
        }
        get cropEnableZoomAutoHide() {
            return this.$$.ctx[95]
        }
        set cropEnableZoomAutoHide(t) {
            this.$set({
                cropEnableZoomAutoHide: t
            }),
            hr()
        }
        get cropEnableImageSelection() {
            return this.$$.ctx[96]
        }
        set cropEnableImageSelection(t) {
            this.$set({
                cropEnableImageSelection: t
            }),
            hr()
        }
        get cropEnableInfoIndicator() {
            return this.$$.ctx[97]
        }
        set cropEnableInfoIndicator(t) {
            this.$set({
                cropEnableInfoIndicator: t
            }),
            hr()
        }
        get cropEnableZoomTowardsWheelPosition() {
            return this.$$.ctx[98]
        }
        set cropEnableZoomTowardsWheelPosition(t) {
            this.$set({
                cropEnableZoomTowardsWheelPosition: t
            }),
            hr()
        }
        get cropEnableLimitWheelInputToCropSelection() {
            return this.$$.ctx[99]
        }
        set cropEnableLimitWheelInputToCropSelection(t) {
            this.$set({
                cropEnableLimitWheelInputToCropSelection: t
            }),
            hr()
        }
        get cropEnableCenterImageSelection() {
            return this.$$.ctx[100]
        }
        set cropEnableCenterImageSelection(t) {
            this.$set({
                cropEnableCenterImageSelection: t
            }),
            hr()
        }
        get cropEnableButtonRotateLeft() {
            return this.$$.ctx[101]
        }
        set cropEnableButtonRotateLeft(t) {
            this.$set({
                cropEnableButtonRotateLeft: t
            }),
            hr()
        }
        get cropEnableButtonRotateRight() {
            return this.$$.ctx[102]
        }
        set cropEnableButtonRotateRight(t) {
            this.$set({
                cropEnableButtonRotateRight: t
            }),
            hr()
        }
        get cropEnableButtonFlipHorizontal() {
            return this.$$.ctx[103]
        }
        set cropEnableButtonFlipHorizontal(t) {
            this.$set({
                cropEnableButtonFlipHorizontal: t
            }),
            hr()
        }
        get cropEnableButtonFlipVertical() {
            return this.$$.ctx[104]
        }
        set cropEnableButtonFlipVertical(t) {
            this.$set({
                cropEnableButtonFlipVertical: t
            }),
            hr()
        }
        get cropSelectPresetOptions() {
            return this.$$.ctx[105]
        }
        set cropSelectPresetOptions(t) {
            this.$set({
                cropSelectPresetOptions: t
            }),
            hr()
        }
        get cropEnableSelectPreset() {
            return this.$$.ctx[106]
        }
        set cropEnableSelectPreset(t) {
            this.$set({
                cropEnableSelectPreset: t
            }),
            hr()
        }
        get cropEnableButtonToggleCropLimit() {
            return this.$$.ctx[107]
        }
        set cropEnableButtonToggleCropLimit(t) {
            this.$set({
                cropEnableButtonToggleCropLimit: t
            }),
            hr()
        }
        get cropWillRenderTools() {
            return this.$$.ctx[108]
        }
        set cropWillRenderTools(t) {
            this.$set({
                cropWillRenderTools: t
            }),
            hr()
        }
        get cropActiveTransformTool() {
            return this.$$.ctx[109]
        }
        set cropActiveTransformTool(t) {
            this.$set({
                cropActiveTransformTool: t
            }),
            hr()
        }
        get locale() {
            return this.$$.ctx[4]
        }
        set locale(t) {
            this.$set({
                locale: t
            }),
            hr()
        }
        get tools() {
            return this.$$.ctx[0]
        }
        set tools(t) {
            this.$set({
                tools: t
            }),
            hr()
        }
    }
    ]
};
function og(t) {
    let e, o, i, n, r, a, s, l = t[68], c = (S(t[68].label) ? t[68].label(t[2]) : t[68].label) + "";
    function d(...e) {
        return t[48](t[68], ...e)
    }
    const u = ()=>t[49](o, l)
      , h = ()=>t[49](null, l);
    return {
        c() {
            e = Rn("div"),
            o = Rn("div"),
            i = An(),
            n = Rn("span"),
            r = In(c),
            Bn(o, "class", ag),
            Bn(e, "slot", "option"),
            Bn(e, "class", "PinturaFilterOption")
        },
        m(t, l) {
            Mn(t, e, l),
            kn(e, o),
            u(),
            kn(e, i),
            kn(e, n),
            kn(n, r),
            a || (s = [Ln(o, "measure", d), yn(bs.call(null, o))],
            a = !0)
        },
        p(e, o) {
            l !== (t = e)[68] && (h(),
            l = t[68],
            u()),
            4 & o[0] | 64 & o[2] && c !== (c = (S(t[68].label) ? t[68].label(t[2]) : t[68].label) + "") && Dn(r, c)
        },
        d(t) {
            t && Tn(e),
            h(),
            a = !1,
            an(s)
        }
    }
}
function ig(t) {
    let e, o;
    return e = new nd({
        props: {
            locale: t[2],
            layout: "row",
            options: t[3],
            selectedIndex: t[10],
            onchange: t[29],
            $$slots: {
                option: [og, ({option: t})=>({
                    68: t
                }), ({option: t})=>[0, 0, t ? 64 : 0]]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            4 & o[0] && (i.locale = t[2]),
            8 & o[0] && (i.options = t[3]),
            1024 & o[0] && (i.selectedIndex = t[10]),
            516 & o[0] | 192 & o[2] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function ng(t) {
    let e, o, i, n, r, a, s, l;
    function c(e) {
        t[51](e)
    }
    function d(e) {
        t[52](e)
    }
    function u(e) {
        t[53](e)
    }
    let h = {
        elasticity: t[15] * t[16],
        onscroll: t[50],
        $$slots: {
            default: [ig]
        },
        $$scope: {
            ctx: t
        }
    };
    return void 0 !== t[4] && (h.maskFeatherStartOpacity = t[4]),
    void 0 !== t[5] && (h.maskFeatherEndOpacity = t[5]),
    void 0 !== t[6] && (h.maskFeatherSize = t[6]),
    o = new ql({
        props: h
    }),
    ir.push((()=>Ir(o, "maskFeatherStartOpacity", c))),
    ir.push((()=>Ir(o, "maskFeatherEndOpacity", d))),
    ir.push((()=>Ir(o, "maskFeatherSize", u))),
    o.$on("measure", t[54]),
    {
        c() {
            e = Rn("div"),
            Ar(o.$$.fragment),
            Bn(e, "slot", "footer"),
            Bn(e, "style", t[11])
        },
        m(i, n) {
            Mn(i, e, n),
            Er(o, e, null),
            a = !0,
            s || (l = Ln(e, "transitionend", t[27]),
            s = !0)
        },
        p(t, s) {
            const l = {};
            128 & s[0] && (l.onscroll = t[50]),
            1548 & s[0] | 128 & s[2] && (l.$$scope = {
                dirty: s,
                ctx: t
            }),
            !i && 16 & s[0] && (i = !0,
            l.maskFeatherStartOpacity = t[4],
            cr((()=>i = !1))),
            !n && 32 & s[0] && (n = !0,
            l.maskFeatherEndOpacity = t[5],
            cr((()=>n = !1))),
            !r && 64 & s[0] && (r = !0,
            l.maskFeatherSize = t[6],
            cr((()=>r = !1))),
            o.$set(l),
            (!a || 2048 & s[0]) && Bn(e, "style", t[11])
        },
        i(t) {
            a || (br(o.$$.fragment, t),
            a = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            a = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o),
            s = !1,
            l()
        }
    }
}
function rg(t) {
    let e, o;
    return e = new om({
        props: {
            $$slots: {
                footer: [ng]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    e.$on("measure", t[55]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            4092 & o[0] | 128 & o[2] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
let ag = "PinturaFilterPreview";
function sg(t, e, o) {
    let i, n, r, a, s, l, c, d, u, h, p, m, g, f, $, y, x, b, v = tn, w = ()=>(v(),
    v = cn(M, (t=>o(40, u = t))),
    M), C = tn, k = ()=>(C(),
    C = cn(T, (t=>o(45, y = t))),
    T);
    t.$$.on_destroy.push((()=>v())),
    t.$$.on_destroy.push((()=>C()));
    let {isActive: M} = e;
    w();
    let {isActiveFraction: T} = e;
    k();
    let {stores: R} = e
      , {locale: P} = e
      , {filterFunctions: I} = e
      , {filterOptions: A} = e;
    const {history: E, interfaceImages: L, stageRect: F, utilRect: z, animation: B, elasticityMultiplier: O, scrollElasticity: D, imageSize: _, imagePreview: W, imageCropRect: V, imageRotation: H, imageFlipX: N, imageFlipY: U, imageBackgroundColor: j, imageGamma: G, imageColorMatrix: q} = R;
    un(t, F, (t=>o(42, p = t))),
    un(t, z, (t=>o(41, h = t))),
    un(t, B, (t=>o(39, d = t))),
    un(t, _, (t=>o(57, f = t))),
    un(t, W, (t=>o(44, g = t))),
    un(t, j, (t=>o(58, $ = t))),
    un(t, G, (t=>o(43, m = t))),
    un(t, q, (t=>o(36, a = t)));
    const J = _r({});
    un(t, J, (t=>o(37, s = t)));
    const Q = (t,e)=>$n(J, s[t.value] = e, s)
      , tt = Wr(J, (t=>{
        if (!t[void 0])
            return;
        const e = t[void 0];
        return l && bt(l, e) ? l : mt(e)
    }
    ));
    un(t, tt, (t=>o(56, l = t)));
    const et = Wr([M, tt, V, _, H, N, U], (([t,e,o,i,n,r,a],s)=>{
        if (!t || !e || !i)
            return c;
        const l = Lt(i)
          , d = _t(l)
          , u = ia(i, o, n)
          , h = _t(u)
          , p = rt(Z(d), h)
          , m = K(Z(p))
          , g = Math.max(e.width / o.width, e.height / o.height);
        s({
            origin: m,
            translation: p,
            rotation: {
                x: a ? Math.PI : 0,
                y: r ? Math.PI : 0,
                z: n
            },
            perspective: X(),
            scale: g
        })
    }
    ));
    un(t, et, (t=>o(38, c = t)));
    const ot = ls(d ? 20 : 0);
    let it;
    un(t, ot, (t=>o(46, x = t)));
    const nt = {};
    let at, st, lt, ct, dt, ut = {
        x: 0,
        y: 0
    };
    const ht = _r([]);
    un(t, ht, (t=>o(47, b = t)));
    const pt = t=>{
        const e = {
            ...t,
            data: g,
            size: f,
            offset: {
                ...t.offset
            },
            mask: {
                ...t.mask
            },
            backgroundColor: $
        };
        return e.opacity = y,
        e.offset.y += x,
        e.mask.y += x,
        e
    }
    ;
    Kn((()=>{
        L.set([])
    }
    ));
    return t.$$set = t=>{
        "isActive"in t && w(o(0, M = t.isActive)),
        "isActiveFraction"in t && k(o(1, T = t.isActiveFraction)),
        "stores"in t && o(31, R = t.stores),
        "locale"in t && o(2, P = t.locale),
        "filterFunctions"in t && o(32, I = t.filterFunctions),
        "filterOptions"in t && o(3, A = t.filterOptions)
    }
    ,
    t.$$.update = ()=>{
        if (8 & t.$$.dirty[0] && o(35, i = Tc(A)),
        48 & t.$$.dirty[1] && o(10, n = ((t,e)=>{
            if (!t || !t.filter || !e)
                return 0;
            const o = t.filter;
            return e.findIndex((([t])=>{
                if (!I[t])
                    return !1;
                const e = I[t]();
                return la(e, o)
            }
            ))
        }
        )(a, i)),
        768 & t.$$.dirty[1] && d && ot.set(u ? 0 : 20),
        3584 & t.$$.dirty[1] && u && h && p) {
            const t = p.y + p.height + h.y;
            o(34, dt = {
                x: p.x - h.x,
                y: t
            })
        }
        if (496 & t.$$.dirty[0] | 4350 & t.$$.dirty[1] && c && dt && ut && ct && it) {
            const t = dt.x + ct.x + ut.x
              , e = dt.y
              , o = ct.x + dt.x
              , n = o + ct.width;
            ht.set(i.map((([i],r)=>{
                const l = s[i]
                  , d = ut.x + l.x
                  , u = d + l.width;
                if (u < 0 || d > ct.width)
                    return !1;
                const h = t + l.x
                  , p = e + l.y
                  , g = (t=>({
                    origin: Z(t.origin),
                    translation: Z(t.translation),
                    rotation: {
                        ...t.rotation
                    },
                    perspective: Z(t.perspective),
                    scale: t.scale
                }))(c);
                g.offset = Y(.5 * l.width + h, .5 * l.height + p);
                g.maskOpacity = 1,
                g.mask = Dt(h + 0, p, l.width + 0, l.height),
                g.maskFeather = [1, 0, 1, 0, 1, n, 1, n],
                d < lt && at < 1 && (g.maskFeather[0] = at,
                g.maskFeather[1] = o,
                g.maskFeather[2] = 1,
                g.maskFeather[3] = o + lt),
                u > ct.width - lt && st < 1 && (g.maskFeather[4] = st,
                g.maskFeather[5] = n - lt,
                g.maskFeather[6] = 1,
                g.maskFeather[7] = n),
                g.maskCornerRadius = it[i];
                let f = a && Object.keys(a).filter((t=>"filter" != t)).map((t=>a[t])) || [];
                return S(I[i]) && f.push(I[i]()),
                g.colorMatrix = f.length ? Ji(f) : void 0,
                g.gamma = m,
                g
            }
            )).filter(Boolean))
        }
        122880 & t.$$.dirty[1] && (y > 0 && b ? L.set(b.map(pt)) : L.set([])),
        32768 & t.$$.dirty[1] && o(11, r = x ? `transform: translateY(${x}px)` : void 0)
    }
    ,
    [M, T, P, A, at, st, lt, ut, ct, nt, n, r, F, z, B, O, D, _, W, j, G, q, J, Q, tt, et, ot, t=>{
        t.target.className === ag && o(33, it = Object.keys(nt).reduce(((t,e)=>{
            const o = nt[e]
              , i = getComputedStyle(o)
              , n = ["top-left", "top-right", "bottom-left", "bottom-right"].map((t=>i.getPropertyValue(`border-${t}-radius`))).map(Ul).map((t=>1.25 * t));
            return t[e] = n,
            t
        }
        ), {}))
    }
    , ht, ({value: t})=>{
        $n(q, a = {
            ...a,
            filter: S(I[t]) ? I[t]() : void 0
        }, a),
        E.write()
    }
    , "filter", R, I, it, dt, i, a, s, c, d, u, h, p, m, g, y, x, b, (t,e)=>Q(t, e.detail), function(t, e) {
        ir[t ? "unshift" : "push"]((()=>{
            nt[e.value] = t,
            o(9, nt)
        }
        ))
    }
    , t=>o(7, ut = t), function(t) {
        at = t,
        o(4, at)
    }
    , function(t) {
        st = t,
        o(5, st)
    }
    , function(t) {
        lt = t,
        o(6, lt)
    }
    , t=>o(8, ct = t.detail), function(e) {
        er(t, e)
    }
    ]
}
var lg = {
    util: ["filter", class extends Br {
        constructor(t) {
            super(),
            zr(this, t, sg, rg, ln, {
                name: 30,
                isActive: 0,
                isActiveFraction: 1,
                stores: 31,
                locale: 2,
                filterFunctions: 32,
                filterOptions: 3
            }, [-1, -1, -1])
        }
        get name() {
            return this.$$.ctx[30]
        }
        get isActive() {
            return this.$$.ctx[0]
        }
        set isActive(t) {
            this.$set({
                isActive: t
            }),
            hr()
        }
        get isActiveFraction() {
            return this.$$.ctx[1]
        }
        set isActiveFraction(t) {
            this.$set({
                isActiveFraction: t
            }),
            hr()
        }
        get stores() {
            return this.$$.ctx[31]
        }
        set stores(t) {
            this.$set({
                stores: t
            }),
            hr()
        }
        get locale() {
            return this.$$.ctx[2]
        }
        set locale(t) {
            this.$set({
                locale: t
            }),
            hr()
        }
        get filterFunctions() {
            return this.$$.ctx[32]
        }
        set filterFunctions(t) {
            this.$set({
                filterFunctions: t
            }),
            hr()
        }
        get filterOptions() {
            return this.$$.ctx[3]
        }
        set filterOptions(t) {
            this.$set({
                filterOptions: t
            }),
            hr()
        }
    }
    ]
};
function cg(t) {
    let e, o, i = t[37].label + "";
    return {
        c() {
            e = Rn("span"),
            o = In(i)
        },
        m(t, i) {
            Mn(t, e, i),
            kn(e, o)
        },
        p(t, e) {
            64 & e[1] && i !== (i = t[37].label + "") && Dn(o, i)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function dg(t) {
    let e, o;
    const i = [{
        class: "PinturaControlList"
    }, {
        tabs: t[1]
    }, t[3]];
    let n = {
        $$slots: {
            default: [cg, ({tab: t})=>({
                37: t
            }), ({tab: t})=>[0, t ? 64 : 0]]
        },
        $$scope: {
            ctx: t
        }
    };
    for (let t = 0; t < i.length; t += 1)
        n = on(n, i[t]);
    return e = new pl({
        props: n
    }),
    e.$on("select", t[22]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const n = 10 & o[0] ? Rr(i, [i[0], 2 & o[0] && {
                tabs: t[1]
            }, 8 & o[0] && Pr(t[3])]) : {};
            192 & o[1] && (n.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(n)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function ug(t) {
    let e, o;
    const i = [t[5][t[36]]];
    let n = {};
    for (let t = 0; t < i.length; t += 1)
        n = on(n, i[t]);
    return e = new am({
        props: n
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const n = 32 & o[0] | 32 & o[1] ? Rr(i, [Pr(t[5][t[36]])]) : {};
            e.$set(n)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function hg(t) {
    let e, o, i, n, r;
    o = new ql({
        props: {
            elasticity: t[9] * t[8],
            class: "PinturaControlListScroller",
            $$slots: {
                default: [dg]
            },
            $$scope: {
                ctx: t
            }
        }
    });
    const a = [{
        class: "PinturaControlPanels"
    }, {
        panelClass: "PinturaControlPanel"
    }, {
        panels: t[4]
    }, t[3]];
    let s = {
        $$slots: {
            default: [ug, ({panel: t})=>({
                36: t
            }), ({panel: t})=>[0, t ? 32 : 0]]
        },
        $$scope: {
            ctx: t
        }
    };
    for (let t = 0; t < a.length; t += 1)
        s = on(s, a[t]);
    return n = new kl({
        props: s
    }),
    {
        c() {
            e = Rn("div"),
            Ar(o.$$.fragment),
            i = An(),
            Ar(n.$$.fragment),
            Bn(e, "slot", "footer"),
            Bn(e, "style", t[6])
        },
        m(t, a) {
            Mn(t, e, a),
            Er(o, e, null),
            kn(e, i),
            Er(n, e, null),
            r = !0
        },
        p(t, i) {
            const s = {};
            14 & i[0] | 128 & i[1] && (s.$$scope = {
                dirty: i,
                ctx: t
            }),
            o.$set(s);
            const l = 24 & i[0] ? Rr(a, [a[0], a[1], 16 & i[0] && {
                panels: t[4]
            }, 8 & i[0] && Pr(t[3])]) : {};
            32 & i[0] | 160 & i[1] && (l.$$scope = {
                dirty: i,
                ctx: t
            }),
            n.$set(l),
            (!r || 64 & i[0]) && Bn(e, "style", t[6])
        },
        i(t) {
            r || (br(o.$$.fragment, t),
            br(n.$$.fragment, t),
            r = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            vr(n.$$.fragment, t),
            r = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o),
            Lr(n)
        }
    }
}
function pg(t) {
    let e, o;
    return e = new om({
        props: {
            $$slots: {
                footer: [hg]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    e.$on("measure", t[23]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            126 & o[0] | 128 & o[1] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function mg(t, e, o) {
    let i, n, r, a, s, l, c, d, u, h, p = tn, m = ()=>(p(),
    p = cn(f, (t=>o(20, u = t))),
    f);
    t.$$.on_destroy.push((()=>p()));
    let {stores: g} = e
      , {isActive: f} = e;
    m();
    let {locale: $={}} = e
      , {finetuneControlConfiguration: y} = e
      , {finetuneOptions: x} = e;
    const {history: b, animation: v, scrollElasticity: w, elasticityMultiplier: C, rangeInputElasticity: k, imageColorMatrix: M, imageConvolutionMatrix: R, imageGamma: P, imageVignette: I, imageNoise: A} = g;
    un(t, v, (t=>o(19, d = t)));
    const E = {
        imageColorMatrix: M,
        imageConvolutionMatrix: R,
        imageGamma: P,
        imageVignette: I,
        imageNoise: A
    }
      , L = "finetune-" + T()
      , F = _r({});
    un(t, F, (t=>o(18, c = t)));
    const z = _r({});
    un(t, z, (t=>o(5, l = t)));
    let B = [];
    const O = ls(d ? 20 : 0);
    un(t, O, (t=>o(21, h = t)));
    return t.$$set = t=>{
        "stores"in t && o(14, g = t.stores),
        "isActive"in t && m(o(0, f = t.isActive)),
        "locale"in t && o(15, $ = t.locale),
        "finetuneControlConfiguration"in t && o(16, y = t.finetuneControlConfiguration),
        "finetuneOptions"in t && o(17, x = t.finetuneOptions)
    }
    ,
    t.$$.update = ()=>{
        var e;
        163840 & t.$$.dirty[0] && o(1, i = x ? x.map((([t,e])=>({
            id: t,
            label: S(e) ? e($) : e
        }))) : []),
        2 & t.$$.dirty[0] && o(2, n = i.length && i[0].id),
        4 & t.$$.dirty[0] && o(3, r = {
            name: L,
            selected: n
        }),
        2 & t.$$.dirty[0] && o(4, a = i.map((t=>t.id))),
        65536 & t.$$.dirty[0] && y && (e = y,
        B && B.forEach((t=>t())),
        B = a.map((t=>{
            const {getStore: o, getValue: i=_} = e[t];
            return o(E).subscribe((e=>{
                const o = null != e ? i(e) : e;
                $n(F, c = {
                    ...c,
                    [t]: o
                }, c)
            }
            ))
        }
        ))),
        327680 & t.$$.dirty[0] && y && c && $n(z, l = Object.keys(c).reduce(((t,e)=>{
            const {base: o, min: i, max: n, getLabel: r, getStore: a, setValue: s=((t,e)=>t.set(e))} = y[e]
              , l = a(E)
              , d = null != c[e] ? c[e] : o;
            return t[e] = {
                base: o,
                min: i,
                max: n,
                value: d,
                valueLabel: r ? r(d, i, n, n - i) : Math.round(100 * d),
                oninputmove: t=>{
                    s(l, t)
                }
                ,
                oninputend: t=>{
                    s(l, t),
                    b.write()
                }
                ,
                elasticity: C * k,
                labelReset: $.labelReset
            },
            t
        }
        ), {}), l),
        1572864 & t.$$.dirty[0] && d && O.set(u ? 0 : 20),
        2097152 & t.$$.dirty[0] && o(6, s = h ? `transform: translateY(${h}px)` : void 0)
    }
    ,
    [f, i, n, r, a, l, s, v, w, C, F, z, O, "finetune", g, $, y, x, c, d, u, h, ({detail: t})=>o(2, n = t), function(e) {
        er(t, e)
    }
    ]
}
var gg = {
    util: ["finetune", class extends Br {
        constructor(t) {
            super(),
            zr(this, t, mg, pg, ln, {
                name: 13,
                stores: 14,
                isActive: 0,
                locale: 15,
                finetuneControlConfiguration: 16,
                finetuneOptions: 17
            }, [-1, -1])
        }
        get name() {
            return this.$$.ctx[13]
        }
        get stores() {
            return this.$$.ctx[14]
        }
        set stores(t) {
            this.$set({
                stores: t
            }),
            hr()
        }
        get isActive() {
            return this.$$.ctx[0]
        }
        set isActive(t) {
            this.$set({
                isActive: t
            }),
            hr()
        }
        get locale() {
            return this.$$.ctx[15]
        }
        set locale(t) {
            this.$set({
                locale: t
            }),
            hr()
        }
        get finetuneControlConfiguration() {
            return this.$$.ctx[16]
        }
        set finetuneControlConfiguration(t) {
            this.$set({
                finetuneControlConfiguration: t
            }),
            hr()
        }
        get finetuneOptions() {
            return this.$$.ctx[17]
        }
        set finetuneOptions(t) {
            this.$set({
                finetuneOptions: t
            }),
            hr()
        }
    }
    ]
};
function fg(t, e, o) {
    const i = t.slice();
    return i[47] = e[o].key,
    i[48] = e[o].index,
    i[49] = e[o].translate,
    i[50] = e[o].scale,
    i[14] = e[o].rotate,
    i[51] = e[o].dir,
    i[52] = e[o].center,
    i[53] = e[o].type,
    i
}
function $g(t) {
    let e, o;
    return {
        c() {
            e = Rn("div"),
            Bn(e, "class", "PinturaShapeManipulator"),
            Bn(e, "data-control", "point"),
            Bn(e, "style", o = `pointer-events:none;transform: translate3d(${t[52].x}px, ${t[52].y}px, 0) scale(${t[5]}, ${t[5]}); opacity: ${t[6]}`)
        },
        m(t, o) {
            Mn(t, e, o)
        },
        p(t, i) {
            104 & i[0] && o !== (o = `pointer-events:none;transform: translate3d(${t[52].x}px, ${t[52].y}px, 0) scale(${t[5]}, ${t[5]}); opacity: ${t[6]}`) && Bn(e, "style", o)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function yg(t, e) {
    let o, i, n, r, a, s, l, c, d;
    function u(...t) {
        return e[18](e[48], ...t)
    }
    let h = "edge" === e[53] && "both" !== e[2] && $g(e);
    return {
        key: t,
        first: null,
        c() {
            o = Rn("div"),
            s = An(),
            h && h.c(),
            l = En(),
            Bn(o, "role", "button"),
            Bn(o, "aria-label", i = `Drag ${e[53]} ${e[47]}`),
            Bn(o, "tabindex", n = "edge" === e[53] ? -1 : 0),
            Bn(o, "class", "PinturaShapeManipulator"),
            Bn(o, "data-control", r = e[53]),
            Bn(o, "style", a = `cursor: ${e[51] ? e[51] + "-resize" : "move"}; transform: translate3d(${e[49].x}px, ${e[49].y}px, 0)${"edge" === e[53] ? ` rotate(${e[14]}rad)` : ""} scale(${"point" === e[53] ? e[5] : e[50].x}, ${"point" === e[53] ? e[5] : e[50].y}); opacity: ${e[6]}`),
            this.first = o
        },
        m(t, i) {
            Mn(t, o, i),
            Mn(t, s, i),
            h && h.m(t, i),
            Mn(t, l, i),
            c || (d = [Ln(o, "keydown", e[7]), Ln(o, "keyup", e[8]), Ln(o, "nudge", u), yn(Vl.call(null, o)), Ln(o, "interactionstart", (function() {
                sn(e[11]("start", e[48])) && e[11]("start", e[48]).apply(this, arguments)
            }
            )), Ln(o, "interactionupdate", (function() {
                sn(e[11]("move", e[48])) && e[11]("move", e[48]).apply(this, arguments)
            }
            )), Ln(o, "interactionend", (function() {
                sn(e[11]("end", e[48])) && e[11]("end", e[48]).apply(this, arguments)
            }
            )), yn(Wl.call(null, o))],
            c = !0)
        },
        p(t, s) {
            e = t,
            8 & s[0] && i !== (i = `Drag ${e[53]} ${e[47]}`) && Bn(o, "aria-label", i),
            8 & s[0] && n !== (n = "edge" === e[53] ? -1 : 0) && Bn(o, "tabindex", n),
            8 & s[0] && r !== (r = e[53]) && Bn(o, "data-control", r),
            104 & s[0] && a !== (a = `cursor: ${e[51] ? e[51] + "-resize" : "move"}; transform: translate3d(${e[49].x}px, ${e[49].y}px, 0)${"edge" === e[53] ? ` rotate(${e[14]}rad)` : ""} scale(${"point" === e[53] ? e[5] : e[50].x}, ${"point" === e[53] ? e[5] : e[50].y}); opacity: ${e[6]}`) && Bn(o, "style", a),
            "edge" === e[53] && "both" !== e[2] ? h ? h.p(e, s) : (h = $g(e),
            h.c(),
            h.m(l.parentNode, l)) : h && (h.d(1),
            h = null)
        },
        d(t) {
            t && Tn(o),
            t && Tn(s),
            h && h.d(t),
            t && Tn(l),
            c = !1,
            an(d)
        }
    }
}
function xg(t) {
    let e, o, i, n;
    return {
        c() {
            e = Rn("div"),
            Bn(e, "role", "button"),
            Bn(e, "aria-label", "Drag rotator"),
            Bn(e, "tabindex", "0"),
            Bn(e, "class", "PinturaShapeManipulator"),
            Bn(e, "data-control", "rotate"),
            Bn(e, "style", o = `transform: translate3d(${t[0].x}px, ${t[0].y}px, 0) scale(${t[5]}, ${t[5]}); opacity: ${t[6]}`)
        },
        m(o, r) {
            Mn(o, e, r),
            i || (n = [Ln(e, "keydown", t[7]), Ln(e, "keyup", t[8]), Ln(e, "nudge", t[13]), yn(Vl.call(null, e)), Ln(e, "interactionstart", t[14]("start")), Ln(e, "interactionupdate", t[14]("move")), Ln(e, "interactionend", t[14]("end")), yn(Wl.call(null, e))],
            i = !0)
        },
        p(t, i) {
            97 & i[0] && o !== (o = `transform: translate3d(${t[0].x}px, ${t[0].y}px, 0) scale(${t[5]}, ${t[5]}); opacity: ${t[6]}`) && Bn(e, "style", o)
        },
        d(t) {
            t && Tn(e),
            i = !1,
            an(n)
        }
    }
}
function bg(t) {
    let e, o, i = [], n = new Map, r = t[3];
    const a = t=>t[47];
    for (let e = 0; e < r.length; e += 1) {
        let o = fg(t, r, e)
          , s = a(o);
        n.set(s, i[e] = yg(s, o))
    }
    let s = t[1] && t[4] && xg(t);
    return {
        c() {
            for (let t = 0; t < i.length; t += 1)
                i[t].c();
            e = An(),
            s && s.c(),
            o = En()
        },
        m(t, n) {
            for (let e = 0; e < i.length; e += 1)
                i[e].m(t, n);
            Mn(t, e, n),
            s && s.m(t, n),
            Mn(t, o, n)
        },
        p(t, l) {
            6636 & l[0] && (r = t[3],
            i = Tr(i, l, a, 1, t, r, n, e.parentNode, kr, yg, e, fg)),
            t[1] && t[4] ? s ? s.p(t, l) : (s = xg(t),
            s.c(),
            s.m(o.parentNode, o)) : s && (s.d(1),
            s = null)
        },
        i: tn,
        o: tn,
        d(t) {
            for (let e = 0; e < i.length; e += 1)
                i[e].d(t);
            t && Tn(e),
            s && s.d(t),
            t && Tn(o)
        }
    }
}
function vg(t, e, o) {
    let i, n, r, a, s;
    const l = Jn()
      , c = .5 * H
      , d = V - c
      , u = V + c
      , h = -V
      , p = h - c
      , m = h + c
      , g = W - c
      , f = -W + c
      , $ = c
      , y = -c
      , x = V - H
      , b = x - c
      , v = x + c
      , S = W - H
      , C = S - c
      , k = S + c
      , M = h - H
      , T = M + c
      , R = M - c
      , P = h + H
      , I = P + c
      , A = P - c;
    let {points: E=[]} = e
      , {rotatorPoint: L} = e
      , {visible: F=!1} = e
      , {enableResizing: z=!0} = e
      , {enableRotating: B=!0} = e
      , O = !1;
    const D = ls(.5, {
        precision: 1e-4,
        stiffness: .3,
        damping: .7
    });
    un(t, D, (t=>o(5, a = t)));
    const _ = ls(0, {
        precision: .001,
        stiffness: .3,
        damping: .7
    });
    un(t, _, (t=>o(6, s = t)));
    const N = t=>{
        let e = "";
        return (t <= u && t >= d || t >= p && t <= m) && (e = "ns"),
        (t <= f || t >= g || t >= y && t <= $) && (e = "ew"),
        (t >= C && t <= k || t <= I && t >= A) && (e = "nesw"),
        (t >= b && t <= v || t <= T && t >= R) && (e = "nwse"),
        e
    }
      , U = (t,e)=>{
        l("resizestart", {
            indexes: t,
            translation: X()
        }),
        l("resizemove", {
            indexes: t,
            translation: e
        }),
        l("resizeend", {
            indexes: t,
            translation: X()
        })
    }
    ;
    return t.$$set = t=>{
        "points"in t && o(15, E = t.points),
        "rotatorPoint"in t && o(0, L = t.rotatorPoint),
        "visible"in t && o(16, F = t.visible),
        "enableResizing"in t && o(17, z = t.enableResizing),
        "enableRotating"in t && o(1, B = t.enableRotating)
    }
    ,
    t.$$.update = ()=>{
        65536 & t.$$.dirty[0] && D.set(F ? 1 : .5),
        65536 & t.$$.dirty[0] && _.set(F ? 1 : 0),
        131072 & t.$$.dirty[0] && o(2, i = !!z && (w(z) ? z : "both")),
        32772 & t.$$.dirty[0] && o(3, n = i && ((t,e)=>{
            let o = 0;
            const i = dt(t)
              , n = []
              , r = t.length
              , a = 2 === r
              , s = "both" !== e;
            for (; o < r; o++) {
                const l = t[o - 1] || t[t.length - 1]
                  , c = t[o]
                  , d = t[o + 1] || t[0]
                  , u = Math.atan2(d.y - c.y, d.x - c.x);
                if (!s) {
                    const t = tt(Y(l.x - c.x, l.y - c.y))
                      , e = tt(Y(d.x - c.x, d.y - c.y))
                      , i = Y(t.x + e.x, t.y + e.y);
                    n.push({
                        index: [o],
                        key: "point-" + o,
                        type: "point",
                        scale: {
                            x: 1,
                            y: 1
                        },
                        translate: {
                            x: c.x,
                            y: c.y
                        },
                        angle: void 0,
                        rotate: a ? 0 : u,
                        center: c,
                        dir: a ? void 0 : N(Math.atan2(i.y, i.x))
                    })
                }
                if (a)
                    continue;
                const h = Y(c.x + .5 * (d.x - c.x), c.y + .5 * (d.y - c.y));
                "horizontal" === e && o % 2 == 0 || "vertical" === e && o % 2 != 0 || n.push({
                    index: [o, o + 1 === r ? 0 : o + 1],
                    key: "edge-" + o,
                    type: "edge",
                    scale: {
                        x: ct(c, d),
                        y: 1
                    },
                    translate: {
                        x: c.x,
                        y: c.y
                    },
                    angle: u,
                    rotate: u,
                    center: h,
                    dir: N(Math.atan2(i.y - h.y, i.x - h.x))
                })
            }
            return n
        }
        )(E, i) || []),
        32768 & t.$$.dirty[0] && o(4, r = E.length > 2)
    }
    ,
    [L, B, i, n, r, a, s, t=>O = t.shiftKey, t=>O = !1, D, _, (t,e)=>({detail: o})=>{
        const i = o && o.translation ? o.translation : Y(0, 0);
        l("resize" + t, {
            indexes: e,
            translation: i,
            shiftKey: O
        })
    }
    , U, ({detail: t})=>{
        l("rotatestart", {
            translation: X()
        }),
        l("rotatemove", {
            translation: t
        }),
        l("rotateend", {
            translation: X()
        })
    }
    , t=>({detail: e})=>{
        const o = e && e.translation ? e.translation : Y(0, 0);
        l("rotate" + t, {
            translation: o,
            shiftKey: O
        })
    }
    , E, F, z, (t,{detail: e})=>U(t, e)]
}
class wg extends Br {
    constructor(t) {
        super(),
        zr(this, t, vg, bg, ln, {
            points: 15,
            rotatorPoint: 0,
            visible: 16,
            enableResizing: 17,
            enableRotating: 1
        }, [-1, -1])
    }
}
var Sg = (t,e)=>{
    const o = Mm(t);
    return rt(o, e)
}
;
let Cg = null;
let kg = null;
var Mg = t=>{
    if (null === kg && (kg = c() && "visualViewport"in window),
    !kg)
        return !1;
    const e = visualViewport.height
      , o = ()=>{
        t(visualViewport.height < e ? "visible" : "hidden")
    }
    ;
    return visualViewport.addEventListener("resize", o),
    ()=>visualViewport.removeEventListener("resize", o)
}
;
function Tg(t) {
    let e, o, i, n, r, a, s, l, c, d;
    i = new Dl({
        props: {
            onclick: t[1],
            label: t[5],
            icon: t[7],
            hideLabel: !t[6]
        }
    });
    const u = t[20].default
      , h = hn(u, t, t[19], null);
    return s = new Dl({
        props: {
            onclick: t[0],
            label: t[2],
            icon: t[4],
            hideLabel: !t[3],
            class: "PinturaInputFormButtonConfirm"
        }
    }),
    {
        c() {
            e = Rn("div"),
            o = Rn("div"),
            Ar(i.$$.fragment),
            n = An(),
            r = Rn("div"),
            h && h.c(),
            a = An(),
            Ar(s.$$.fragment),
            Bn(r, "class", "PinturaInputFormFields"),
            Bn(o, "class", "PinturaInputFormInner"),
            Bn(e, "class", "PinturaInputForm"),
            Bn(e, "style", t[9])
        },
        m(u, p) {
            Mn(u, e, p),
            kn(e, o),
            Er(i, o, null),
            kn(o, n),
            kn(o, r),
            h && h.m(r, null),
            kn(o, a),
            Er(s, o, null),
            t[21](e),
            l = !0,
            c || (d = [Ln(e, "focusin", t[10]), Ln(e, "focusout", t[11]), Ln(e, "measure", t[12]), yn(bs.call(null, e))],
            c = !0)
        },
        p(t, o) {
            const n = {};
            2 & o[0] && (n.onclick = t[1]),
            32 & o[0] && (n.label = t[5]),
            128 & o[0] && (n.icon = t[7]),
            64 & o[0] && (n.hideLabel = !t[6]),
            i.$set(n),
            h && h.p && 524288 & o[0] && mn(h, u, t, t[19], o, null, null);
            const r = {};
            1 & o[0] && (r.onclick = t[0]),
            4 & o[0] && (r.label = t[2]),
            16 & o[0] && (r.icon = t[4]),
            8 & o[0] && (r.hideLabel = !t[3]),
            s.$set(r),
            (!l || 512 & o[0]) && Bn(e, "style", t[9])
        },
        i(t) {
            l || (br(i.$$.fragment, t),
            br(h, t),
            br(s.$$.fragment, t),
            l = !0)
        },
        o(t) {
            vr(i.$$.fragment, t),
            vr(h, t),
            vr(s.$$.fragment, t),
            l = !1
        },
        d(o) {
            o && Tn(e),
            Lr(i),
            h && h.d(o),
            Lr(s),
            t[21](null),
            c = !1,
            an(d)
        }
    }
}
function Rg(t, e, o) {
    let i, n, r, a, {$$slots: s={}, $$scope: l} = e, {onconfirm: c} = e, {oncancel: d} = e, {autoFocus: u=!0} = e, {autoPositionCursor: h=!0} = e, {labelConfirm: p} = e, {labelConfirmShow: m=!0} = e, {iconConfirm: g} = e, {labelCancel: f} = e, {labelCancelShow: $=!1} = e, {iconCancel: y} = e, {panelOffset: x=X()} = e, b = !1, v = void 0, w = void 0, S = "", C = 0;
    const k = ()=>{
        const t = a.querySelector("input, textarea");
        t.focus(),
        t.select()
    }
      , M = ()=>{
        b = !0,
        R || !Fe() && (null === Cg && (Cg = Ee(/Android/)),
        !Cg) || o(16, S = "top:1em;bottom:auto;"),
        Fe() && (t=>{
            let e;
            const o = t=>e = t.touches[0].screenY
              , i = t=>{
                const o = t.touches[0].screenY
                  , i = t.target;
                /textarea/i.test(i.nodeName) ? (o > e ? 0 == i.scrollTop && t.preventDefault() : o < e ? i.scrollTop + i.offsetHeight == i.scrollHeight && t.preventDefault() : t.preventDefault(),
                e = o) : t.preventDefault()
            }
            ;
            t.addEventListener("touchstart", o),
            t.addEventListener("touchmove", i)
        }
        )(a),
        o(17, C = 1)
    }
    ;
    let T;
    const R = Mg((t=>{
        n ? "hidden" !== t || b ? (clearTimeout(w),
        w = void 0,
        o(16, S = `top:${visualViewport.height - v - x.y}px`),
        "visible" === t ? (o(8, a.dataset.layout = "stick", a),
        k(),
        M()) : (b = !1,
        o(17, C = 0))) : k() : o(16, S = "top: 4.5em; bottom: auto")
    }
    ));
    return qn((()=>{
        u && k()
    }
    )),
    Kn((()=>{
        R && R()
    }
    )),
    t.$$set = t=>{
        "onconfirm"in t && o(0, c = t.onconfirm),
        "oncancel"in t && o(1, d = t.oncancel),
        "autoFocus"in t && o(13, u = t.autoFocus),
        "autoPositionCursor"in t && o(14, h = t.autoPositionCursor),
        "labelConfirm"in t && o(2, p = t.labelConfirm),
        "labelConfirmShow"in t && o(3, m = t.labelConfirmShow),
        "iconConfirm"in t && o(4, g = t.iconConfirm),
        "labelCancel"in t && o(5, f = t.labelCancel),
        "labelCancelShow"in t && o(6, $ = t.labelCancelShow),
        "iconCancel"in t && o(7, y = t.iconCancel),
        "panelOffset"in t && o(15, x = t.panelOffset),
        "$$scope"in t && o(19, l = t.$$scope)
    }
    ,
    t.$$.update = ()=>{
        256 & t.$$.dirty[0] && o(18, i = a && getComputedStyle(a)),
        262144 & t.$$.dirty[0] && (n = i && "1" === i.getPropertyValue("--editor-modal")),
        196608 & t.$$.dirty[0] && o(9, r = `opacity:${C};${S}`)
    }
    ,
    [c, d, p, m, g, f, $, y, a, r, t=>{
        var e;
        (t=>/textarea/i.test(t))(t.target) && (T = Date.now(),
        h && ((e = t.target).selectionStart = e.selectionEnd = e.value.length),
        clearTimeout(w),
        w = setTimeout(M, 200))
    }
    , t=>{
        Date.now() - T > 50 || (t.stopPropagation(),
        k())
    }
    , ({detail: t})=>{
        v = t.height
    }
    , u, h, x, S, C, i, l, s, function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            a = t,
            o(8, a)
        }
        ))
    }
    ]
}
class Pg extends Br {
    constructor(t) {
        super(),
        zr(this, t, Rg, Tg, ln, {
            onconfirm: 0,
            oncancel: 1,
            autoFocus: 13,
            autoPositionCursor: 14,
            labelConfirm: 2,
            labelConfirmShow: 3,
            iconConfirm: 4,
            labelCancel: 5,
            labelCancelShow: 6,
            iconCancel: 7,
            panelOffset: 15
        }, [-1, -1])
    }
}
var Ig = t=>document.createTextNode(t);
function Ag(t) {
    let e, o, i, n;
    return {
        c() {
            e = Rn("pre"),
            Bn(e, "class", "PinturaContentEditable"),
            Bn(e, "data-wrap-content", o = t[3] ? "wrap" : "nowrap"),
            Bn(e, "contenteditable", ""),
            Bn(e, "spellcheck", t[0]),
            Bn(e, "autocorrect", t[1]),
            Bn(e, "autocapitalize", t[2]),
            Bn(e, "style", t[4])
        },
        m(o, r) {
            Mn(o, e, r),
            t[20](e),
            i || (n = [Ln(e, "input", t[9]), Ln(e, "paste", t[10]), Ln(e, "keydown", t[7]), Ln(e, "keyup", t[8]), Ln(e, "blur", t[6])],
            i = !0)
        },
        p(t, i) {
            8 & i[0] && o !== (o = t[3] ? "wrap" : "nowrap") && Bn(e, "data-wrap-content", o),
            1 & i[0] && Bn(e, "spellcheck", t[0]),
            2 & i[0] && Bn(e, "autocorrect", t[1]),
            4 & i[0] && Bn(e, "autocapitalize", t[2]),
            16 & i[0] && Bn(e, "style", t[4])
        },
        i: tn,
        o: tn,
        d(o) {
            o && Tn(e),
            t[20](null),
            i = !1,
            an(n)
        }
    }
}
function Eg(t, e, o) {
    let i, {spellcheck: r="false"} = e, {autocorrect: a="off"} = e, {autocapitalize: s="off"} = e, {wrapLines: l=!0} = e, {textStyles: d=!1} = e, {formatInput: u=_} = e, {formatPaste: h=_} = e, {style: m} = e, {innerHTML: g} = e, {oninput: f=n} = e;
    const $ = ()=>{
        if (!x)
            return;
        const t = document.createRange();
        t.selectNodeContents(x);
        const e = window.getSelection();
        e.removeAllRanges(),
        e.addRange(t)
    }
      , y = Jn();
    let x;
    c() && document.execCommand("defaultParagraphSeparator", !1, "br");
    const b = t=>t.replace(/<\/?(?:i|b|em|strong)>/, "")
      , v = ()=>{
        o(11, g = x.innerHTML),
        y("input", g),
        f(g),
        requestAnimationFrame((()=>x && x.scrollTo(0, 0)))
    }
      , w = ()=>{
        k(x);
        const t = d ? x.innerHTML : b(x.innerHTML);
        o(5, x.innerHTML = u(t), x),
        M(x),
        v()
    }
      , S = t=>{
        const e = p("span");
        return e.dataset.bookmark = t,
        e
    }
      , C = (t,e,o)=>{
        const i = S(o);
        if (t.nodeType === Node.TEXT_NODE) {
            const n = t.textContent;
            if ("start" === o) {
                const o = Ig(n.substr(0, e))
                  , r = Ig(n.substr(e));
                t.replaceWith(o, i, r)
            } else {
                const o = Ig(n.substr(0, e))
                  , r = Ig(n.substr(e));
                t.replaceWith(o, i, r)
            }
        } else
            t.nodeType === Node.ELEMENT_NODE && t.insertBefore(i, t.childNodes[e])
    }
      , k = t=>{
        const e = window.getSelection();
        if (!e.getRangeAt || !e.rangeCount)
            return;
        const o = e.getRangeAt(0)
          , {startOffset: i, endOffset: n, startContainer: r, endContainer: a} = o;
        if (t.contains(o.startContainer) && t.contains(o.endContainer))
            if (r.nodeType === Node.TEXT_NODE && r === a) {
                const t = r.textContent
                  , e = t.substr(0, i)
                  , o = S("start")
                  , a = n - i > 0 ? t.substr(i, n) : ""
                  , s = S("end")
                  , l = t.substr(n);
                r.replaceWith(e, o, a, s, l)
            } else
                C(r, i, "start"),
                C(a, n + (r === a ? 1 : 0), "end")
    }
      , M = t=>{
        const e = T(t, "start")
          , o = T(t, "end");
        if (!e || !o)
            return;
        const i = document.createRange();
        i.setStart(e, 0),
        i.setEnd(o, 0);
        const n = window.getSelection();
        n.removeAllRanges(),
        n.addRange(i),
        e.remove(),
        o.remove()
    }
      , T = (t,e)=>{
        const o = t.children;
        for (let t = 0; t < o.length; t++) {
            const i = o[t];
            if (i.dataset.bookmark === e)
                return i;
            if (i.children.length) {
                const t = T(i, e);
                if (t)
                    return t
            }
        }
    }
      , R = t=>{
        const e = window.getSelection().getRangeAt(0)
          , o = e.cloneRange();
        return o.selectNodeContents(t),
        o.setEnd(e.endContainer, e.endOffset),
        o.toString().length
    }
    ;
    return t.$$set = t=>{
        "spellcheck"in t && o(0, r = t.spellcheck),
        "autocorrect"in t && o(1, a = t.autocorrect),
        "autocapitalize"in t && o(2, s = t.autocapitalize),
        "wrapLines"in t && o(3, l = t.wrapLines),
        "textStyles"in t && o(12, d = t.textStyles),
        "formatInput"in t && o(13, u = t.formatInput),
        "formatPaste"in t && o(14, h = t.formatPaste),
        "style"in t && o(4, m = t.style),
        "innerHTML"in t && o(11, g = t.innerHTML),
        "oninput"in t && o(15, f = t.oninput)
    }
    ,
    t.$$.update = ()=>{
        var e;
        32 & t.$$.dirty[0] && o(19, i = !!x),
        526336 & t.$$.dirty[0] && i && g && (e = g) !== x.innerHTML && (o(5, x.innerHTML = e, x),
        x === document.activeElement && $())
    }
    ,
    [r, a, s, l, m, x, ()=>y("blur"), t=>{
        if (13 !== t.keyCode)
            return;
        const e = R(x) === x.textContent.length ? "<br><br>" : "<br>";
        l && document.execCommand("insertHTML", !1, e),
        t.preventDefault()
    }
    , ()=>{}
    , t=>{
        const {inputType: e} = t;
        "insertCompositionText" !== e && "deleteCompositionText" !== e && w()
    }
    , t=>{
        t.preventDefault();
        const e = t.clipboardData.getData("text/plain")
          , o = d ? e : b(e)
          , i = h(o);
        if (!i.length)
            return;
        const n = window.getSelection().getRangeAt(0);
        n.deleteContents(),
        n.insertNode(document.createTextNode(i)),
        v()
    }
    , g, d, u, h, f, ()=>w(), ()=>x && x.focus(), $, i, function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            x = t,
            o(5, x)
        }
        ))
    }
    ]
}
class Lg extends Br {
    constructor(t) {
        super(),
        zr(this, t, Eg, Ag, ln, {
            spellcheck: 0,
            autocorrect: 1,
            autocapitalize: 2,
            wrapLines: 3,
            textStyles: 12,
            formatInput: 13,
            formatPaste: 14,
            style: 4,
            innerHTML: 11,
            oninput: 15,
            confirm: 16,
            focus: 17,
            select: 18
        }, [-1, -1])
    }
    get spellcheck() {
        return this.$$.ctx[0]
    }
    set spellcheck(t) {
        this.$set({
            spellcheck: t
        }),
        hr()
    }
    get autocorrect() {
        return this.$$.ctx[1]
    }
    set autocorrect(t) {
        this.$set({
            autocorrect: t
        }),
        hr()
    }
    get autocapitalize() {
        return this.$$.ctx[2]
    }
    set autocapitalize(t) {
        this.$set({
            autocapitalize: t
        }),
        hr()
    }
    get wrapLines() {
        return this.$$.ctx[3]
    }
    set wrapLines(t) {
        this.$set({
            wrapLines: t
        }),
        hr()
    }
    get textStyles() {
        return this.$$.ctx[12]
    }
    set textStyles(t) {
        this.$set({
            textStyles: t
        }),
        hr()
    }
    get formatInput() {
        return this.$$.ctx[13]
    }
    set formatInput(t) {
        this.$set({
            formatInput: t
        }),
        hr()
    }
    get formatPaste() {
        return this.$$.ctx[14]
    }
    set formatPaste(t) {
        this.$set({
            formatPaste: t
        }),
        hr()
    }
    get style() {
        return this.$$.ctx[4]
    }
    set style(t) {
        this.$set({
            style: t
        }),
        hr()
    }
    get innerHTML() {
        return this.$$.ctx[11]
    }
    set innerHTML(t) {
        this.$set({
            innerHTML: t
        }),
        hr()
    }
    get oninput() {
        return this.$$.ctx[15]
    }
    set oninput(t) {
        this.$set({
            oninput: t
        }),
        hr()
    }
    get confirm() {
        return this.$$.ctx[16]
    }
    get focus() {
        return this.$$.ctx[17]
    }
    get select() {
        return this.$$.ctx[18]
    }
}
function Fg(t, e, o) {
    const i = t.slice();
    return i[197] = e[o],
    i[199] = o,
    i
}
function zg(t, e) {
    let o, i, n, r, a, s, l, c, d, u, h, p = e[197].name + "";
    function m(...t) {
        return e[131](e[199], ...t)
    }
    return n = new Eh({
        props: {
            color: e[197].color
        }
    }),
    {
        key: t,
        first: null,
        c() {
            o = Rn("li"),
            i = Rn("button"),
            Ar(n.$$.fragment),
            r = An(),
            a = Rn("span"),
            s = In(p),
            c = An(),
            Bn(i, "class", "PinturaShapeListItem"),
            Bn(i, "type", "button"),
            Bn(i, "aria-label", l = "Select shape " + e[197].name),
            this.first = o
        },
        m(t, e) {
            Mn(t, o, e),
            kn(o, i),
            Er(n, i, null),
            kn(i, r),
            kn(i, a),
            kn(a, s),
            kn(o, c),
            d = !0,
            u || (h = Ln(i, "click", m),
            u = !0)
        },
        p(t, o) {
            e = t;
            const r = {};
            4194304 & o[0] && (r.color = e[197].color),
            n.$set(r),
            (!d || 4194304 & o[0]) && p !== (p = e[197].name + "") && Dn(s, p),
            (!d || 4194304 & o[0] && l !== (l = "Select shape " + e[197].name)) && Bn(i, "aria-label", l)
        },
        i(t) {
            d || (br(n.$$.fragment, t),
            d = !0)
        },
        o(t) {
            vr(n.$$.fragment, t),
            d = !1
        },
        d(t) {
            t && Tn(o),
            Lr(n),
            u = !1,
            h()
        }
    }
}
function Bg(t) {
    let e, o;
    return e = new wg({
        props: {
            visible: !0,
            points: t[11],
            rotatorPoint: t[16],
            enableResizing: t[15],
            enableRotating: t[9]
        }
    }),
    e.$on("resizestart", t[28]),
    e.$on("resizemove", t[29]),
    e.$on("resizeend", t[30]),
    e.$on("rotatestart", t[31]),
    e.$on("rotatemove", t[32]),
    e.$on("rotateend", t[33]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            2048 & o[0] && (i.points = t[11]),
            65536 & o[0] && (i.rotatorPoint = t[16]),
            32768 & o[0] && (i.enableResizing = t[15]),
            512 & o[0] && (i.enableRotating = t[9]),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Og(t) {
    let e, o, i, n;
    const r = [_g, Dg]
      , a = [];
    function s(t, e) {
        return "modal" === t[3] ? 0 : "inline" === t[3] ? 1 : -1
    }
    return ~(e = s(t)) && (o = a[e] = r[e](t)),
    {
        c() {
            o && o.c(),
            i = En()
        },
        m(t, o) {
            ~e && a[e].m(t, o),
            Mn(t, i, o),
            n = !0
        },
        p(t, n) {
            let l = e;
            e = s(t),
            e === l ? ~e && a[e].p(t, n) : (o && (yr(),
            vr(a[l], 1, 1, (()=>{
                a[l] = null
            }
            )),
            xr()),
            ~e ? (o = a[e],
            o ? o.p(t, n) : (o = a[e] = r[e](t),
            o.c()),
            br(o, 1),
            o.m(i.parentNode, i)) : o = null)
        },
        i(t) {
            n || (br(o),
            n = !0)
        },
        o(t) {
            vr(o),
            n = !1
        },
        d(t) {
            ~e && a[e].d(t),
            t && Tn(i)
        }
    }
}
function Dg(t) {
    let e, o, i, n = {
        formatInput: t[35],
        wrapLines: !!t[8].width,
        style: t[18]
    };
    return o = new Lg({
        props: n
    }),
    t[134](o),
    o.$on("input", t[36]),
    o.$on("keyup", t[39]),
    {
        c() {
            e = Rn("div"),
            Ar(o.$$.fragment),
            Bn(e, "class", "PinturaInlineInput"),
            Bn(e, "style", t[19])
        },
        m(t, n) {
            Mn(t, e, n),
            Er(o, e, null),
            i = !0
        },
        p(t, n) {
            const r = {};
            256 & n[0] && (r.wrapLines = !!t[8].width),
            262144 & n[0] && (r.style = t[18]),
            o.$set(r),
            (!i || 524288 & n[0]) && Bn(e, "style", t[19])
        },
        i(t) {
            i || (br(o.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            i = !1
        },
        d(i) {
            i && Tn(e),
            t[134](null),
            Lr(o)
        }
    }
}
function _g(t) {
    let e, o;
    return e = new Pg({
        props: {
            panelOffset: t[1],
            onconfirm: t[40],
            oncancel: t[41],
            labelCancel: t[4].shapeLabelInputCancel,
            iconCancel: t[4].shapeIconInputCancel,
            labelConfirm: t[4].shapeLabelInputConfirm,
            iconConfirm: t[4].shapeIconInputConfirm,
            $$slots: {
                default: [Wg]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            2 & o[0] && (i.panelOffset = t[1]),
            16 & o[0] && (i.labelCancel = t[4].shapeLabelInputCancel),
            16 & o[0] && (i.iconCancel = t[4].shapeIconInputCancel),
            16 & o[0] && (i.labelConfirm = t[4].shapeLabelInputConfirm),
            16 & o[0] && (i.iconConfirm = t[4].shapeIconInputConfirm),
            393280 & o[0] | 16384 & o[6] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Wg(t) {
    let e, o, i;
    return {
        c() {
            e = Rn("textarea"),
            Bn(e, "spellcheck", "false"),
            Bn(e, "autocorrect", "off"),
            Bn(e, "autocapitalize", "off"),
            Bn(e, "style", t[18])
        },
        m(n, r) {
            Mn(n, e, r),
            t[132](e),
            _n(e, t[17]),
            o || (i = [Ln(e, "keydown", t[38]), Ln(e, "keypress", t[37]), Ln(e, "keyup", t[39]), Ln(e, "input", t[36]), Ln(e, "input", t[133])],
            o = !0)
        },
        p(t, o) {
            262144 & o[0] && Bn(e, "style", t[18]),
            131072 & o[0] && _n(e, t[17])
        },
        d(n) {
            n && Tn(e),
            t[132](null),
            o = !1,
            an(i)
        }
    }
}
function Vg(t) {
    let e, o, i, n, r;
    return o = new _d({
        props: {
            items: t[21]
        }
    }),
    {
        c() {
            e = Rn("div"),
            Ar(o.$$.fragment),
            Bn(e, "class", "PinturaShapeControls"),
            Bn(e, "style", t[20])
        },
        m(a, s) {
            Mn(a, e, s),
            Er(o, e, null),
            i = !0,
            n || (r = [Ln(e, "measure", t[135]), yn(bs.call(null, e))],
            n = !0)
        },
        p(t, n) {
            const r = {};
            2097152 & n[0] && (r.items = t[21]),
            o.$set(r),
            (!i || 1048576 & n[0]) && Bn(e, "style", t[20])
        },
        i(t) {
            i || (br(o.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            i = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o),
            n = !1,
            an(r)
        }
    }
}
function Hg(t) {
    let e, o, i, n, r, a, s, l, c, d, u = [], h = new Map, p = t[22];
    const m = t=>t[197].id;
    for (let e = 0; e < p.length; e += 1) {
        let o = Fg(t, p, e)
          , i = m(o);
        h.set(i, u[e] = zg(i, o))
    }
    let g = t[10] && Bg(t)
      , f = t[12] && Og(t)
      , $ = t[13] > 0 && Vg(t);
    return {
        c() {
            e = Rn("div"),
            o = Rn("nav"),
            i = Rn("ul");
            for (let t = 0; t < u.length; t += 1)
                u[t].c();
            n = An(),
            g && g.c(),
            r = An(),
            f && f.c(),
            a = An(),
            $ && $.c(),
            Bn(o, "class", "PinturaShapeList"),
            Bn(o, "data-visible", t[14]),
            Bn(e, "class", "PinturaShapeEditor"),
            Bn(e, "tabindex", "0")
        },
        m(h, p) {
            Mn(h, e, p),
            kn(e, o),
            kn(o, i);
            for (let t = 0; t < u.length; t += 1)
                u[t].m(i, null);
            kn(e, n),
            g && g.m(e, null),
            kn(e, r),
            f && f.m(e, null),
            kn(e, a),
            $ && $.m(e, null),
            l = !0,
            c || (d = [Ln(o, "focusin", t[44]), Ln(o, "focusout", t[45]), Ln(e, "keydown", t[34]), Ln(e, "nudge", t[43]), Ln(e, "measure", t[130]), yn(bs.call(null, e)), yn(Vl.call(null, e)), Ln(e, "pointermove", t[46]), Ln(e, "interactionstart", t[24]), Ln(e, "interactionupdate", t[25]), Ln(e, "interactionrelease", t[26]), Ln(e, "interactionend", t[27]), yn(s = Wl.call(null, e, {
                drag: !0,
                pinch: !0,
                inertia: !0,
                matchTarget: !0,
                getEventPosition: t[136]
            }))],
            c = !0)
        },
        p(t, n) {
            4194337 & n[0] && (p = t[22],
            yr(),
            u = Tr(u, n, m, 1, t, p, h, i, Mr, zg, null, Fg),
            xr()),
            (!l || 16384 & n[0]) && Bn(o, "data-visible", t[14]),
            t[10] ? g ? (g.p(t, n),
            1024 & n[0] && br(g, 1)) : (g = Bg(t),
            g.c(),
            br(g, 1),
            g.m(e, r)) : g && (yr(),
            vr(g, 1, 1, (()=>{
                g = null
            }
            )),
            xr()),
            t[12] ? f ? (f.p(t, n),
            4096 & n[0] && br(f, 1)) : (f = Og(t),
            f.c(),
            br(f, 1),
            f.m(e, a)) : f && (yr(),
            vr(f, 1, 1, (()=>{
                f = null
            }
            )),
            xr()),
            t[13] > 0 ? $ ? ($.p(t, n),
            8192 & n[0] && br($, 1)) : ($ = Vg(t),
            $.c(),
            br($, 1),
            $.m(e, null)) : $ && (yr(),
            vr($, 1, 1, (()=>{
                $ = null
            }
            )),
            xr()),
            s && sn(s.update) && 4 & n[0] && s.update.call(null, {
                drag: !0,
                pinch: !0,
                inertia: !0,
                matchTarget: !0,
                getEventPosition: t[136]
            })
        },
        i(t) {
            if (!l) {
                for (let t = 0; t < p.length; t += 1)
                    br(u[t]);
                br(g),
                br(f),
                br($),
                l = !0
            }
        },
        o(t) {
            for (let t = 0; t < u.length; t += 1)
                vr(u[t]);
            vr(g),
            vr(f),
            vr($),
            l = !1
        },
        d(t) {
            t && Tn(e);
            for (let t = 0; t < u.length; t += 1)
                u[t].d();
            g && g.d(),
            f && f.d(),
            $ && $.d(),
            c = !1,
            an(d)
        }
    }
}
function Ng(t, e, o) {
    let i, r, a, s, l, c, d, u, h, p, m, g, f, $, y, x, b, v, S, C, k, M, R, P, I, A, E, L, F, z, B, O, D, W, V, H, N, {uid: U=T()} = e, {ui: G} = e, {markup: q} = e, {offset: K} = e, {contextRotation: Q=0} = e, {contextFlipX: pt=!1} = e, {contextFlipY: mt=!1} = e, {contextScale: ft} = e, {active: $t=!1} = e, {opacity: yt=1} = e, {parentRect: bt} = e, {rootRect: vt} = e, {utilRect: wt} = e, {hoverColor: St} = e, {textInputMode: Ct="inline"} = e, {oninteractionstart: kt=n} = e, {oninteractionupdate: Tt=n} = e, {oninteractionrelease: Pt=n} = e, {oninteractionend: It=n} = e, {onaddshape: At=n} = e, {onupdateshape: Et=n} = e, {onselectshape: Lt=n} = e, {onremoveshape: Ot=n} = e, {onhovershape: Wt=n} = e, {onhovercanvas: Vt=n} = e, {beforeSelectShape: Ht=(()=>!0)} = e, {beforeDeselectShape: Nt=(()=>!0)} = e, {beforeRemoveShape: Ut=(()=>!0)} = e, {beforeUpdateShape: jt=((t,e)=>e)} = e, {willRenderShapeControls: Xt=_} = e, {mapEditorPointToImagePoint: Yt} = e, {mapImagePointToEditorPoint: qt} = e, {eraseRadius: Zt} = e, {selectRadius: Kt} = e, {enableButtonFlipVertical: ee=!1} = e, {enableTapToAddText: oe=!0} = e, {locale: ie} = e;
    const ne = (t,e,o)=>{
        let i = jt({
            ...t
        }, e, {
            ...o
        });
        Ri(t, i, o)
    }
      , re = (t,e,o,i)=>{
        const n = Y(t.x - o.x, t.y - o.y)
          , r = Y(i.x - o.x, i.y - o.y)
          , a = st(r, r);
        let s = st(n, r) / a;
        s = s < 0 ? 0 : s,
        s = s > 1 ? 1 : s;
        const l = Y(r.x * s + o.x - t.x, r.y * s + o.y - t.y);
        return st(l, l) <= e * e
    }
      , le = (t,e,o)=>{
        const i = o.length;
        for (let n = 0; n < i - 1; n++)
            if (re(t, e, o[n], o[n + 1]))
                return !0;
        return !1
    }
      , de = (t,e,o)=>!!se(t, o) || (!!le(t, e, o) || re(t, e, o[0], o[o.length - 1]))
      , ue = (t,e,o,i,n)=>de(t, e, Gt(o, i, n || _t(o)))
      , he = tr("keysPressed");
    un(t, he, (t=>o(146, H = t)));
    const pe = (t,e,o)=>0 === t || e && o ? t : e || o ? -t : t
      , me = (t,e)=>{
        const o = qt(t);
        return Yt(nt(o, e))
    }
      , ge = (t,e,o)=>{
        if (Uo(t)) {
            const i = me(Oo(e), o)
              , n = me(Do(e), o);
            ne(t, {
                x1: i.x,
                y1: i.y,
                x2: n.x,
                y2: n.y
            }, bt)
        } else if (Ho(t) || _o(t) || No(t)) {
            const i = me(e, o);
            ne(t, i, bt)
        }
        Ce()
    }
      , fe = {
        0: 1,
        1: 0,
        2: 3,
        3: 2
    }
      , $e = {
        0: 3,
        1: 2,
        2: 1,
        3: 0
    };
    let ye;
    const xe = ()=>{
        if (q.length)
            return q.find(Zo)
    }
      , be = ()=>{
        if (q.length)
            return q.findIndex(Zo)
    }
      , ve = (t,e=!0)=>{
        if (!xe())
            return ei(t),
            ke(t, e)
    }
      , we = ()=>{
        const t = xe();
        if (t)
            return t._isDraft = !1,
            Ce(),
            t
    }
      , Se = ()=>{
        xe() && (q.splice(be(), 1),
        Ce())
    }
      , Ce = ()=>o(0, q)
      , ke = (t,e=!0)=>(q.push(t),
    e && Ce(),
    t)
      , Me = (t,e=[],o=!0)=>{
        e.forEach((e=>delete t[e])),
        o && Ce()
    }
      , Te = (t,e,o=!0)=>{
        t = Object.assign(t, e),
        o && Ce()
    }
      , Re = (t,e,o,i=!0)=>{
        t[e] = o,
        i && Ce()
    }
      , Pe = (t,e=!0)=>{
        q.forEach((e=>Te(e, t, !1))),
        e && Ce()
    }
      , Ie = ()=>[...q].reverse().find(qo)
      , Ae = ()=>!!Ie()
      , Ee = t=>{
        if (!Ut(t))
            return !1;
        o(0, q = q.filter((e=>e !== t))),
        Ot(t)
    }
      , Le = ()=>{
        const t = Ie();
        if (!t)
            return;
        const e = q.filter((t=>ni(t) && ii(t)))
          , o = e.findIndex((e=>e === t));
        if (!1 === Ee(t))
            return;
        if (Fe = t,
        e.length - 1 <= 0)
            return ze();
        const i = o - 1 < 0 ? e.length - 1 : o - 1;
        Oe(e[i])
    }
    ;
    let Fe = void 0;
    const ze = ()=>{
        Object.keys(xo).forEach((t=>xo[t] = {})),
        Fe = Be(),
        Pe({
            isSelected: !1,
            isEditing: !1,
            _prerender: !1
        })
    }
      , Be = ()=>q.find(qo)
      , Oe = (t,e=!0)=>{
        if (Zo(t))
            return;
        const o = Be() || Fe;
        Fe = void 0,
        Ht(o, t) && (ze(),
        (t=>{
            t.isSelected = !0
        }
        )(t),
        Lt(t),
        e && Ce())
    }
      , De = t=>{
        co && t.isEditing && co.confirm(),
        Te(t, {
            isSelected: !1,
            isEditing: !1,
            _prerender: !1
        })
    }
      , _e = t=>{
        Te(t, {
            isSelected: !0,
            isEditing: !0,
            _prerender: "inline" === Ct
        })
    }
      , We = t=>{
        Te(t, {
            isSelected: !0,
            isEditing: !1,
            _prerender: !1
        })
    }
      , Ve = t=>{
        if (!t.length)
            return [];
        const e = t.filter(Ut);
        return o(0, q = q.filter((t=>!e.includes(t)))),
        e
    }
      , He = t=>{
        const e = yo(t.text, t);
        return Dt(t.x, t.y, t.width ? Math.min(t.width, e.width) : e.width, t.height ? Math.min(t.height, e.height) : e.height)
    }
      , Ne = t=>{
        if (Ko(t))
            return Ft(t);
        if (No(t))
            return Bt(t);
        const e = He(t);
        return e.width = Math.max(10, t.width || e.width),
        e
    }
      , Ue = (t,e=0,o=(t=>!0))=>[...q].reverse().map((t=>({
        shape: t,
        priority: 1
    }))).filter(o).filter((o=>{
        const {shape: i} = o
          , n = Ci(Eo(i), bt)
          , r = e + (n.strokeWidth || 0);
        if (Ho(n))
            return ue(t, r, n, i.rotation);
        if (_o(n)) {
            const e = Ne(n)
              , a = ue(t, r, e, i.rotation);
            let s = !1;
            if (a && !qo(i)) {
                const a = He(n);
                "right" !== i.textAlign || i.flipX || (a.x = e.x + e.width - a.width),
                "center" === i.textAlign && (a.x = e.x + .5 * e.width - .5 * a.width),
                s = ue(t, r, a, i.rotation, _t(e)),
                s || (o.priority = -1)
            }
            return a
        }
        return No(n) ? ((t,e,o,i,n,r)=>{
            const a = ce(Y(o.x, o.y), o.rx, o.ry, i, n, r, 12);
            return de(t, e, a)
        }
        )(t, r, n, i.rotation, i.flipX, i.flipY) : Uo(n) ? re(t, Math.max(16, r), Oo(n), Do(n)) : !!Xo(n) && le(t, Math.max(16, r), n.points)
    }
    )).sort(((t,e)=>t.priority < e.priority ? 1 : t.priority > e.priority ? -1 : 0)).map((t=>t.shape))
      , je = (t,e,o,i=0)=>{
        const n = Math.abs(i)
          , r = Mt(e, o)
          , a = Rt(r, n)
          , s = (({start: t, end: e},o)=>{
            if (0 === o)
                return [Y(t.x, t.y), Y(t.x, t.y), Y(e.x, e.y), Y(e.x, e.y)];
            const i = Math.atan2(e.y - t.y, e.x - t.x)
              , n = Math.sin(i) * o
              , r = Math.cos(i) * o;
            return [Y(n + t.x, -r + t.y), Y(-n + t.x, r + t.y), Y(-n + e.x, r + e.y), Y(n + e.x, -r + e.y)]
        }
        )(a, n);
        return t.filter((t=>{
            const e = Ci(Eo(t), bt);
            if (Uo(e) || Xo(e)) {
                const t = e.points ? [...e.points] : [Oo(e), Do(e)];
                return !!((t,e)=>{
                    const o = e.length
                      , i = [];
                    for (let n = 0; n < o - 1; n++) {
                        const o = ae(t.start, t.end, e[n], e[n + 1]);
                        o && i.push(o)
                    }
                    return i.length ? i : void 0
                }
                )(a, t)
            }
            return ((t,e)=>!!t.find((t=>se(t, e))) || !!e.find((e=>se(e, t))))(s, ((t,e=12)=>{
                if (Ho(t))
                    return Gt(t, t.rotation, _t(t));
                if (_o(t)) {
                    const e = Ne(t);
                    return Gt(e, t.rotation, _t(e))
                }
                return No(t) ? ce(Y(t.x, t.y), t.rx, t.ry, t.rotation, t.flipX, t.flipY, e) : []
            }
            )(e))
        }
        ))
    }
    ;
    let Xe = void 0
      , Ye = void 0
      , Ge = void 0
      , qe = void 0
      , Ze = void 0
      , Je = !1;
    const Qe = t=>{
        let e;
        if (Ho(t)) {
            const o = _t(t);
            e = te(t),
            (t.flipX || t.flipY) && ut(e, t.flipX, t.flipY, o.x, o.y),
            e = ht(e, t.rotation, o.x, o.y)
        } else if (No(t)) {
            const o = t;
            e = te(Bt(t)),
            (t.flipX || t.flipY) && ut(e, t.flipX, t.flipY, o.x, o.y),
            e = ht(e, t.rotation, o.x, o.y)
        } else if (Uo(t))
            e = [Oo(t), Do(t)];
        else if (Xo(t))
            e = [...t.points];
        else if (_o(t)) {
            const o = Ne(t);
            o.width = Math.max(10, o.width);
            const i = _t(o);
            e = te(o),
            (t.flipX || t.flipY) && ut(e, t.flipX, t.flipY, i.x, i.y),
            e = ht(e, t.rotation, i.x, i.y)
        }
        return e
    }
      , to = t=>{
        const e = Qe(t);
        let o, i;
        return t.flipY ? (o = dt([e[0], e[1]]),
        i = tt(Y(e[1].x - e[2].x, e[1].y - e[2].y))) : (o = dt([e[2], e[3]]),
        i = tt(Y(e[2].x - e[1].x, e[2].y - e[1].y))),
        at(i, 20 / ft),
        {
            origin: o,
            dir: i
        }
    }
      , eo = ()=>{
        o(47, G = G.filter((t=>"markup-hover" !== t.id)))
    }
    ;
    let oo, io = "markup-manipulator-segment";
    const no = t=>{
        const e = G.find((t=>t.id === io))
          , n = e ? Math.max(e.opacity, t) : t
          , r = []
          , a = .1 * n
          , s = n
          , l = [0, 0, 0]
          , c = [1, 1, 1]
          , d = !Xo(i) && !Uo(i);
        r.push({
            id: io,
            points: p.map((t=>Y(t.x + 1, t.y + 1))),
            pathClose: d,
            strokeColor: l,
            strokeWidth: 2,
            opacity: a,
            _group: U
        }),
        g && r.push({
            id: io,
            points: [Y(g.origin.x + 1, g.origin.y + 1), Y(g.position.x + 1, g.position.y + 1)],
            strokeColor: l,
            strokeWidth: 2,
            opacity: a,
            _group: U
        }),
        r.push({
            id: io,
            points: p,
            pathClose: d,
            strokeColor: c,
            strokeWidth: 1.5,
            opacity: s,
            _group: U
        }),
        g && r.push({
            id: io,
            points: [{
                x: g.origin.x,
                y: g.origin.y
            }, {
                x: g.position.x,
                y: g.position.y
            }],
            strokeColor: c,
            strokeWidth: 1.5,
            opacity: s,
            _group: U
        }),
        o(47, G = [...G.filter((t=>t.id !== io)), ...r])
    }
      , so = ()=>{
        o(47, G = G.filter((t=>0 === t.opacity && t.id !== io)))
    }
      , lo = t=>t.isContentEditable || /input|textarea/i.test(t.nodeName);
    let co;
    const uo = t=>o(6, co.innerHTML = (t=>t.replace(/ {2,}/g, " ").replace(/&/g, "&amp;").replace(/\u00a0/g, "&nbsp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").split("\n").join("<br>"))(t), co)
      , ho = t=>{
        let e;
        e = void 0 === t.value ? t.innerHTML.split("<br>").join("\n").replace(/&nbsp;/g, String.fromCharCode(160)).replace(/&amp;/g, "&").replace(/&lt;/g, "<").replace(/&gt;/g, ">") : t.value;
        return Wo(i) ? (t=>{
            const e = t.split(/[\n\r]/g);
            return e.length > 1 ? e.map((t=>t.trim())).filter((t=>t.length)).join(" ") : e[0]
        }
        )(e) : e
    }
      , po = ()=>{
        const t = ho(co)
          , e = ai(i, t)
          , o = !0 === e ? t : e;
        let n = b.x
          , r = b.y;
        if (!i.height) {
            const t = Gt({
                ...S
            }, i.rotation)
              , e = yo(o, a)
              , s = Gt({
                x: n,
                y: r,
                ...e
            }, i.rotation)
              , [l,,c] = t
              , [d,,u] = s;
            let h = l
              , p = d;
            i.flipX && (h = c,
            p = u);
            const m = rt(Z(h), p);
            n += m.x,
            r += m.y
        }
        Te(i, {
            x: w(x.x) ? ro(n, bt.width) : n,
            y: w(x.y) ? ro(r, bt.height) : r,
            text: o
        })
    }
      , mo = ()=>{
        let t = Zo(i);
        Zo(i) && we(),
        po(),
        We(i),
        t ? At(i) : Et(i)
    }
      , go = ()=>{
        Zo(i) ? Se() : (Te(i, {
            text: x.text,
            x: x.x,
            y: x.y
        }),
        We(i))
    }
      , fo = (t,e,{flipX: o, flipY: i, rotation: n},r="top left")=>{
        let a, s;
        const [l,c,d,u] = Gt(t, n)
          , [h,p,m,g] = Gt(e, n);
        if ("top center" === r) {
            a = dt(i ? [u, d] : [l, c]),
            s = dt(i ? [g, m] : [h, p])
        } else
            "top right" === r && !o || "top left" === r && o ? (a = i ? d : c,
            s = i ? m : p) : (a = i ? u : l,
            s = i ? g : h);
        return rt(Z(a), s)
    }
      , $o = (t,e,o)=>Y(w(t.x) ? ro(e.x + o.x, bt.width) : e.x + o.x, w(t.y) ? ro(e.y + o.y, bt.height) : e.y + o.y)
      , xo = {}
      , bo = ()=>_e(i)
      , vo = ()=>{
        const t = yo(i.text, a)
          , e = Ke(i, "height")
          , o = !e && Ke(i, "width")
          , n = i.id;
        let r = xo[n];
        r || (xo[n] = {},
        r = xo[n]);
        const s = t=>{
            const {width: e, ...o} = a
              , n = yo(i.text, o)
              , r = fo(Dt(a.x, a.y, t.width, t.height), Dt(a.x, a.y, n.width, n.height), a, "top " + i.textAlign);
            Me(i, ["width", "height", "textAlign"]),
            Te(i, {
                ...$o(i, a, r)
            })
        }
          , l = e=>{
            const o = xt(r.width || a.width || t.width, t.height)
              , n = r.textAlign || "left"
              , s = fo(Dt(a.x, a.y, e.width, e.height), Dt(a.x, a.y, o.width, o.height), a, "top " + n);
            Me(i, ["height"]),
            Te(i, {
                ...$o(i, a, s),
                width: w(i.width) ? ro(o.width, bt.width) : o.width,
                textAlign: n
            })
        }
          , c = e=>{
            const o = xt(r.width || t.width, r.height || t.height)
              , n = r.textAlign || "left"
              , s = fo(Dt(a.x, a.y, e.width, e.height), Dt(a.x, a.y, o.width, o.height), a, "top " + n);
            Te(i, {
                ...$o(i, a, s),
                width: w(i.width) ? ro(o.width, bt.width) : o.width,
                height: w(i.width) ? ro(o.height, bt.height) : o.height,
                textAlign: n
            })
        }
        ;
        if (e) {
            r.textAlign = i.textAlign,
            r.width = a.width,
            r.height = a.height;
            const t = xt(a.width, a.height);
            si(i, "auto-height") ? l(t) : si(i, "auto-width") && s(t)
        } else if (o) {
            r.textAlign = i.textAlign,
            r.width = a.width;
            const e = xt(a.width, t.height);
            si(i, "auto-width") ? s(e) : si(i, "fixed-size") && c(e)
        } else {
            const e = xt(t.width, t.height);
            si(i, "fixed-size") ? c(e) : si(i, "auto-height") && l(e)
        }
    }
      , wo = t=>{
        t.stopPropagation();
        const e = i.flipX || !1;
        Re(i, "flipX", !e),
        Et(i)
    }
      , So = t=>{
        t.stopPropagation();
        const e = i.flipY || !1;
        Re(i, "flipY", !e),
        Et(i)
    }
      , Co = t=>{
        Re(i, "opacity", t),
        Et(i)
    }
      , ko = t=>{
        t.stopPropagation(),
        t.target.blur(),
        Le()
    }
      , Mo = t=>{
        t.stopPropagation();
        q.findIndex((t=>t === i)) !== q.length - 1 && (o(0, q = q.filter((t=>t !== i)).concat([i])),
        Et(i))
    }
      , To = t=>{
        t.stopPropagation();
        const e = Eo(i);
        e.id = T();
        const o = Y(50, -50);
        if (Uo(e)) {
            const t = Ti(e, ["x1", "y1", "x2", "y2"], bt);
            t.x1 += o.x,
            t.y1 += o.y,
            t.x2 += o.x,
            t.y2 += o.y,
            Ri(e, t, bt)
        } else {
            const t = Ti(e, ["x", "y"], bt);
            t.x += 50,
            t.y -= 50,
            Ri(e, t, bt)
        }
        q.push(e),
        At(e),
        Oe(e)
    }
      , Ro = ls(0, {
        stiffness: .2,
        damping: .7
    });
    let Po;
    un(t, Ro, (t=>o(13, N = t)));
    const Ao = (t,e)=>{
        const {disableTextLayout: o=[]} = e;
        return "height"in e ? o.includes("auto-height") ? t.shapeIconButtonTextLayoutAutoWidth : t.shapeIconButtonTextLayoutAutoHeight : "width"in e ? o.includes("auto-width") ? t.shapeIconButtonTextLayoutFixedSize : t.shapeIconButtonTextLayoutAutoWidth : o.includes("fixed-size") ? t.shapeIconButtonTextLayoutAutoHeight : t.shapeIconButtonTextLayoutFixedSize
    }
      , Lo = (t,e)=>{
        const {disableTextLayout: o=[]} = e;
        return "height"in e ? o.includes("auto-height") ? t.shapeTitleButtonTextLayoutAutoWidth : t.shapeTitleButtonTextLayoutAutoHeight : "width"in e ? o.includes("auto-width") ? t.shapeTitleButtonTextLayoutFixedSize : t.shapeTitleButtonTextLayoutAutoWidth : o.includes("fixed-size") ? t.shapeTitleButtonTextLayoutAutoHeight : t.shapeTitleButtonTextLayoutFixedSize
    }
    ;
    let Fo = !1;
    let zo = X();
    const Bo = t=>{
        Wt(t),
        o(108, oo = t)
    }
    ;
    Kn((()=>{
        so()
    }
    ));
    return t.$$set = t=>{
        "uid"in t && o(48, U = t.uid),
        "ui"in t && o(47, G = t.ui),
        "markup"in t && o(0, q = t.markup),
        "offset"in t && o(1, K = t.offset),
        "contextRotation"in t && o(49, Q = t.contextRotation),
        "contextFlipX"in t && o(50, pt = t.contextFlipX),
        "contextFlipY"in t && o(51, mt = t.contextFlipY),
        "contextScale"in t && o(52, ft = t.contextScale),
        "active"in t && o(53, $t = t.active),
        "opacity"in t && o(54, yt = t.opacity),
        "parentRect"in t && o(55, bt = t.parentRect),
        "rootRect"in t && o(2, vt = t.rootRect),
        "utilRect"in t && o(56, wt = t.utilRect),
        "hoverColor"in t && o(57, St = t.hoverColor),
        "textInputMode"in t && o(3, Ct = t.textInputMode),
        "oninteractionstart"in t && o(58, kt = t.oninteractionstart),
        "oninteractionupdate"in t && o(59, Tt = t.oninteractionupdate),
        "oninteractionrelease"in t && o(60, Pt = t.oninteractionrelease),
        "oninteractionend"in t && o(61, It = t.oninteractionend),
        "onaddshape"in t && o(62, At = t.onaddshape),
        "onupdateshape"in t && o(63, Et = t.onupdateshape),
        "onselectshape"in t && o(64, Lt = t.onselectshape),
        "onremoveshape"in t && o(65, Ot = t.onremoveshape),
        "onhovershape"in t && o(66, Wt = t.onhovershape),
        "onhovercanvas"in t && o(67, Vt = t.onhovercanvas),
        "beforeSelectShape"in t && o(68, Ht = t.beforeSelectShape),
        "beforeDeselectShape"in t && o(69, Nt = t.beforeDeselectShape),
        "beforeRemoveShape"in t && o(70, Ut = t.beforeRemoveShape),
        "beforeUpdateShape"in t && o(71, jt = t.beforeUpdateShape),
        "willRenderShapeControls"in t && o(72, Xt = t.willRenderShapeControls),
        "mapEditorPointToImagePoint"in t && o(73, Yt = t.mapEditorPointToImagePoint),
        "mapImagePointToEditorPoint"in t && o(74, qt = t.mapImagePointToEditorPoint),
        "eraseRadius"in t && o(75, Zt = t.eraseRadius),
        "selectRadius"in t && o(76, Kt = t.selectRadius),
        "enableButtonFlipVertical"in t && o(77, ee = t.enableButtonFlipVertical),
        "enableTapToAddText"in t && o(78, oe = t.enableTapToAddText),
        "locale"in t && o(4, ie = t.locale)
    }
    ,
    t.$$.update = ()=>{
        var e, n;
        if (1 & t.$$.dirty[0] && o(109, i = q && (xe() || Ie())),
        65536 & t.$$.dirty[3] && o(110, r = i && !Zo(i) ? i.id : void 0),
        4 & t.$$.dirty[0] | 16777216 & t.$$.dirty[1] | 65536 & t.$$.dirty[3] && o(8, a = vt && i && Ci(Eo(i), bt)),
        65536 & t.$$.dirty[3] && o(111, s = !(!i || !Zo(i))),
        256 & t.$$.dirty[0] | 8388608 & t.$$.dirty[1] | 65536 & t.$$.dirty[3] && o(112, l = i && yt && Qe(a) || []),
        65536 & t.$$.dirty[3] && o(113, c = i && (li(e = i) && ci(e) && !0 !== e.disableResize && (Ko(e) || Vo(e) || No(e) || Uo(e))) && !Yo(i)),
        65536 & t.$$.dirty[3] && o(9, d = i && (t=>li(t) && !0 !== t.disableRotate && (Ko(t) || Ke(t, "text") || No(t)))(i) && !Yo(i)),
        1114112 & t.$$.dirty[3] && o(15, u = c && Ke(i, "text") && !i.height ? "horizontal" : c),
        589824 & t.$$.dirty[3] && o(10, h = i && l.length > 1),
        4096 & t.$$.dirty[2] | 524288 & t.$$.dirty[3] && o(114, p = l.map(qt)),
        2 & t.$$.dirty[0] | 2097152 & t.$$.dirty[3] && o(11, m = p.map((t=>Y(t.x - K.x, t.y - K.y)))),
        32768 & t.$$.dirty[3] && (oo && !qo(oo) && ii(oo) ? (t=>{
            const e = Qe(Ci(Eo(t), bt)).map(qt)
              , i = !Xo(t) && !Uo(t)
              , n = {
                id: "markup-hover",
                points: e.map((t=>Y(t.x + 1, t.y + 1))),
                strokeColor: [0, 0, 0, .1],
                strokeWidth: 2,
                pathClose: i
            }
              , r = {
                id: "markup-hover",
                points: e,
                strokeColor: St,
                strokeWidth: 2,
                pathClose: i
            };
            eo(),
            o(47, G = [...G, n, r])
        }
        )(oo) : eo()),
        3840 & t.$$.dirty[0] | 8388608 & t.$$.dirty[1] && o(115, g = h && d && yt && m && (t=>{
            const e = to(t)
              , o = qt({
                x: e.origin.x + e.dir.x,
                y: e.origin.y + e.dir.y
            });
            return {
                origin: qt(e.origin),
                position: o
            }
        }
        )(a)),
        2 & t.$$.dirty[0] | 4194304 & t.$$.dirty[3] && o(16, f = g && Y(g.position.x - K.x, g.position.y - K.y)),
        2048 & t.$$.dirty[0] | 12582912 & t.$$.dirty[1] | 65536 & t.$$.dirty[3])
            if ($t)
                if (yt > 0) {
                    o(47, G = G.map((t=>(t.id !== io || (t._group = U),
                    t))));
                    const t = i && Zo(i) && Xo(i);
                    m.length > 2 && !t ? no(yt) : so()
                } else
                    i || no(yt);
            else
                G.find((t=>t._group === U)) && no(yt);
        4194304 & t.$$.dirty[1] && (t=>{
            if (!t)
                return Pe({
                    _prerender: !1
                });
            const e = q.find((t=>t.isEditing));
            e && Te(e, {
                _prerender: !0
            })
        }
        )($t),
        72 & t.$$.dirty[0] && co && "inline" === Ct && co.focus(),
        65536 & t.$$.dirty[3] && o(116, $ = i && _o(i)),
        8454144 & t.$$.dirty[3] && o(12, y = $ && !1 !== ai(i) && Yo(i)),
        4096 & t.$$.dirty[0] && o(117, x = y ? {
            ...i
        } : void 0),
        16777216 & t.$$.dirty[1] | 16777216 & t.$$.dirty[3] && o(118, b = x && Ci({
            ...x
        }, bt)),
        33554432 & t.$$.dirty[3] && o(119, v = b && yo(b.text, b)),
        100663296 & t.$$.dirty[3] && (S = b && Dt(b.x, b.y, v.width, v.height)),
        4096 & t.$$.dirty[0] | 65536 & t.$$.dirty[3] && o(17, C = y ? i.text : ""),
        4360 & t.$$.dirty[0] && o(18, k = y && ((t,e)=>{
            const {textAlign: o="left", fontFamily: i="sans-serif", fontWeight: n="normal", fontStyle: r="normal"} = t
              , a = t.fontSize
              , s = "!important"
              , l = `text-align:${o}${s};font-family:${i}${s};font-weight:${n}${s};font-style:${r}${s};`;
            if ("modal" === e)
                return l;
            const c = ao(t.color)
              , d = t.lineHeight
              , u = .5 * Math.max(0, a - d);
            return `--bottom-inset:${u}px;padding:${u}px 0 0${s};color:${c}${s};font-size:${a}px${s};line-height:${d}px${s};${l}`
        }
        )(a, Ct)),
        4354 & t.$$.dirty[0] | 2359296 & t.$$.dirty[1] && o(19, M = y && ((t,e,o,n)=>{
            let r, s;
            t.width && t.height ? (r = _t(t),
            s = gt(t)) : (s = yo(i.text, a),
            s.width = a.width || s.width,
            r = Y(t.x + .5 * s.width, t.y + .5 * s.height));
            const l = Math.max(0, t.fontSize - t.lineHeight) + t.lineHeight
              , c = qt(r);
            let d = c.x - e.x - .5 * s.width
              , u = c.y - e.y - .5 * s.height
              , h = t.flipX
              , p = t.flipY
              , m = t.rotation;
            pt && mt ? (h = !h,
            p = !p) : pt ? (h = !h,
            m = -m) : mt && (p = !p,
            m = -m),
            m += n;
            const g = o * (h ? -1 : 1)
              , f = o * (p ? -1 : 1);
            return `--line-height:${l}px;width:${s.width}px;height:${s.height}px;transform:translate(${d}px,${u}px) rotate(${m}rad) scale(${g}, ${f})`
        }
        )(a, K, ft, Q)),
        4168 & t.$$.dirty[0] && y && co && "inline" === Ct && uo(C),
        134545408 & t.$$.dirty[3] && o(120, R = i && !s ? i : R),
        134217728 & t.$$.dirty[3] && o(121, P = R && ri(R)),
        134217728 & t.$$.dirty[3] && o(122, I = R && si(R)),
        134217728 & t.$$.dirty[3] && o(123, A = R && (t=>!0 !== t.disableDuplicate && ci(t))(R)),
        134217728 & t.$$.dirty[3] && o(124, E = R && ni(R)),
        134217728 & t.$$.dirty[3] && o(125, L = R && (t=>!0 !== t.disableReorder)(R)),
        134217728 & t.$$.dirty[3] && o(126, F = R && !1 !== ai(R)),
        134217728 & t.$$.dirty[3] && o(127, z = R && Ke(R, "backgroundImage") && oi(R, "opacity")),
        4096 & t.$$.dirty[0] | 344064 & t.$$.dirty[3] && Ro.set(!i || s || Je || y ? 0 : 1),
        2048 & t.$$.dirty[0] | 327680 & t.$$.dirty[3] | 16 & t.$$.dirty[4] && o(128, B = i && !s ? (n = zt(m),
        it(Y(n.x + .5 * n.width, n.y), mc)) : B),
        128 & t.$$.dirty[0] | 33554432 & t.$$.dirty[1] | 16 & t.$$.dirty[4] && o(129, O = B && Po && wt && (t=>{
            const e = wt.x
              , o = wt.y
              , i = e + wt.width;
            let n = Math.max(t.x - .5 * Po.width, e)
              , r = Math.max(t.y - Po.height - 16, o);
            return n + Po.width > i && (n = i - Po.width),
            Y(n, r)
        }
        )(B)),
        8192 & t.$$.dirty[0] | 32 & t.$$.dirty[4] && o(20, D = O && `transform: translate(${O.x}px, ${O.y}px);opacity:${N}`),
        16 & t.$$.dirty[0] | 33792 & t.$$.dirty[2] | 1879244800 & t.$$.dirty[3] | 15 & t.$$.dirty[4] && o(21, W = r && Xt([z && ["div", "alpha", {
            class: "PinturaShapeControlsGroup"
        }, [["Slider", "adjust-opacity", {
            onchange: Co,
            step: .01,
            value: Ke(i, "opacity") ? i.opacity : 1,
            label: (t,e,o)=>Math.round(t / o * 100) + "%",
            min: 0,
            max: 1,
            direction: "x"
        }]]], ["div", "beta", {
            class: "PinturaShapeControlsGroup"
        }, [P && ["Button", "flip-horizontal", {
            onclick: wo,
            label: ie.shapeTitleButtonFlipHorizontal,
            icon: ie.shapeIconButtonFlipHorizontal,
            hideLabel: !0
        }], P && ee && ["Button", "flip-vertical", {
            onclick: So,
            label: ie.shapeTitleButtonFlipVertical,
            icon: ie.shapeIconButtonFlipVertical,
            hideLabel: !0
        }], L && ["Button", "to-front", {
            onclick: Mo,
            label: ie.shapeTitleButtonMoveToFront,
            icon: ie.shapeIconButtonMoveToFront,
            hideLabel: !0
        }], A && ["Button", "duplicate", {
            onclick: To,
            label: ie.shapeTitleButtonDuplicate,
            icon: ie.shapeIconButtonDuplicate,
            hideLabel: !0
        }], E && ["Button", "remove", {
            onclick: ko,
            label: ie.shapeTitleButtonRemove,
            icon: ie.shapeIconButtonRemove,
            hideLabel: !0
        }]].filter(Boolean)], F && I && ["div", "gamma", {
            class: "PinturaShapeControlsGroup"
        }, [["Button", "text-layout", {
            onclick: vo,
            label: Pc(Lo, ie, i),
            icon: Pc(Ao, ie, i),
            hideLabel: !0
        }]]], F && ["div", "delta", {
            class: "PinturaShapeControlsGroup"
        }, [["Button", "edit-text", {
            label: ie.shapeLabelInputText,
            onclick: bo
        }]]]].filter(Boolean), r)),
        17 & t.$$.dirty[0] && o(22, V = q.filter(ii).filter((t=>!Zo(t))).map((t=>({
            id: t.id,
            color: _o(t) ? t.color : Uo(t) ? t.strokeColor : t.backgroundColor,
            name: t.name || ie["shapeLabelTool" + qr($i(t))]
        }))))
    }
    ,
    [q, K, vt, Ct, ie, Oe, co, Po, a, d, h, m, y, N, Fo, u, f, C, k, M, D, W, V, he, t=>{
        const {origin: e} = t.detail;
        Ge = void 0,
        qe = void 0,
        Ze = void 0,
        Ye = void 0,
        clearTimeout(Xe),
        Xe = setTimeout((()=>o(107, Je = !0)), 250);
        xe() && we();
        const n = Yt(Z(e))
          , r = Ue(n, Kt, (t=>ii(t)))
          , a = r.length && r.shift();
        if (!a && i && Yo(i) && De(i),
        a && qo(a))
            return Ge = a,
            qe = Eo(Ge),
            void (Ze = Ci(Eo(Ge), bt));
        Ye = a;
        !kt(t) && a && (Oe(a),
        Ge = a,
        qe = Eo(Ge),
        Ze = Ci(Eo(Ge), bt))
    }
    , t=>{
        if (Ge) {
            if (!ci(Ge))
                return;
            if (Yo(Ge))
                return;
            return ge(Ge, Ze, t.detail.translation)
        }
        Tt(t)
    }
    , t=>{
        clearTimeout(Xe),
        Xe = void 0,
        o(107, Je = !1),
        Ge ? Yo(Ge) ? go() : t.detail.isTap && _o(Ge) && !1 !== ai(Ge) && _e(Ge) : Pt(t)
    }
    , t=>{
        const e = Ye && t.detail.isTap;
        if (Ge)
            return o = Ge,
            i = qe,
            JSON.stringify(o) !== JSON.stringify(i) && Et(Ge),
            void (Ge = void 0);
        var o, i;
        const n = Be()
          , r = !n || Nt(n, Ye);
        r && ze(),
        It(t),
        r && e && Oe(Ye)
    }
    , t=>{
        o(107, Je = !0),
        Ge = i,
        Ze = a
    }
    , t=>{
        const {translation: e, indexes: o, shiftKey: i} = t.detail;
        ((t,e,o,i,n)=>{
            if (Uo(t)) {
                const [n] = o
                  , r = H.includes(16) ? (t,e)=>{
                    const o = ct(t, e)
                      , i = et(t, e)
                      , n = Math.PI / 4
                      , r = n * Math.round(i / n) - Q % n;
                    e.x = t.x + o * Math.cos(r),
                    e.y = t.y + o * Math.sin(r)
                }
                : (t,e)=>e;
                if (0 === n) {
                    const o = me(Oo(e), i);
                    r(Y(e.x2, e.y2), o),
                    ne(t, {
                        x1: o.x,
                        y1: o.y
                    }, bt)
                } else if (1 === n) {
                    const o = me(Do(e), i);
                    r(Y(e.x1, e.y1), o),
                    ne(t, {
                        x2: o.x,
                        y2: o.y
                    }, bt)
                }
            } else if (Ko(t) || No(t) || Vo(t)) {
                let r, a, s = !1;
                if (No(t))
                    r = Bt(e);
                else if (Ko(t))
                    r = Ft(e);
                else {
                    s = !0,
                    r = Ft(e);
                    const t = yo(e.text, e);
                    r.height = t.height
                }
                t.aspectRatio ? a = t.aspectRatio : n.shiftKey && !s && (a = r.width / r.height);
                const l = Ft(r)
                  , c = _t(l)
                  , d = t.rotation
                  , u = te(l)
                  , h = Gt(l, d);
                if (1 === o.length) {
                    let e = o[0];
                    t.flipX && (e = fe[e]),
                    t.flipY && (e = $e[e]);
                    const [n,r,s,l] = u
                      , p = qt(h[e]);
                    nt(p, i);
                    const m = Yt(p)
                      , g = Y(m.x - h[e].x, m.y - h[e].y)
                      , f = J(Z(g), -d)
                      , $ = Y(u[e].x + f.x, u[e].y + f.y);
                    let y;
                    0 === e && (y = s),
                    1 === e && (y = l),
                    2 === e && (y = n),
                    3 === e && (y = r);
                    const x = zt(y, $);
                    if (a) {
                        const {width: t, height: e} = Jt(x, a)
                          , [o,i,n,r] = Qt(x);
                        x.width = t,
                        x.height = e,
                        $.y < y.y && (x.y = n - e),
                        $.x < y.x && (x.x = i - t)
                    }
                    const b = Gt(x, d, c)
                      , v = dt(b)
                      , w = J(b[0], -d, v)
                      , S = J(b[2], -d, v)
                      , C = zt(w, S);
                    ne(t, No(t) ? j(C) : C, bt)
                } else {
                    o = o.map((e=>(t.flipX && (e = fe[e]),
                    t.flipY && (e = $e[e]),
                    e)));
                    const [e,n] = o.map((t=>h[t]))
                      , r = {
                        x: e.x + .5 * (n.x - e.x),
                        y: e.y + .5 * (n.y - e.y)
                    }
                      , [l,p] = o.map((t=>u[t]))
                      , [m,g] = o.map((t=>{
                        const e = t + 2;
                        return e < 4 ? u[e] : u[e - 4]
                    }
                    ))
                      , f = {
                        x: m.x + .5 * (g.x - m.x),
                        y: m.y + .5 * (g.y - m.y)
                    }
                      , $ = qt(r);
                    nt($, i);
                    const y = Yt($)
                      , x = Y(y.x - r.x, y.y - r.y)
                      , b = J(Z(x), -d)
                      , v = rt(Z(l), p)
                      , w = it(v, (t=>1 - Math.abs(Math.sign(t))))
                      , S = Y(b.x * w.x, b.y * w.y);
                    nt(l, S),
                    nt(p, S);
                    const C = zt(u);
                    if (a) {
                        let t = C.width
                          , e = C.height;
                        0 === w.y ? e = t / a : t = e * a,
                        C.width = t,
                        C.height = e,
                        0 === w.y ? C.y = f.y - .5 * e : C.x = f.x - .5 * t
                    }
                    const k = Gt(C, d, c)
                      , M = dt(k)
                      , T = J(k[0], -d, M)
                      , R = J(k[2], -d, M)
                      , P = zt(T, R);
                    let I;
                    No(t) ? I = j(P) : Ko(t) ? I = P : s && (I = {
                        x: P.x,
                        y: P.y,
                        width: P.width
                    }),
                    ne(t, I, bt)
                }
            }
            Ce()
        }
        )(Ge, Ze, o, e, {
            shiftKey: i
        })
    }
    , t=>{
        Oe(Ge),
        Ge = void 0,
        o(107, Je = !1),
        Et(i)
    }
    , t=>{
        ye = to(a).origin,
        o(107, Je = !0),
        Ge = i,
        Ze = a
    }
    , t=>{
        const {translation: e, shiftKey: o} = t.detail;
        ((t,e,o,i)=>{
            const n = Ne(Ci(Eo(t), bt))
              , r = _t(n)
              , a = me(ye, o);
            let s = et(a, r) + Math.PI / 2;
            if (i.shiftKey) {
                const t = Math.PI / 16;
                s = t * Math.round(s / t) - Q % t
            }
            ne(t, {
                rotation: s
            }, bt),
            Ce()
        }
        )(Ge, 0, e, {
            shiftKey: o
        })
    }
    , ()=>{
        Oe(Ge),
        Ge = void 0,
        o(107, Je = !1),
        Et(i)
    }
    , t=>{
        if (!Ae())
            return;
        const {key: e} = t;
        if (/escape/i.test(e))
            return t.preventDefault(),
            t.stopPropagation(),
            De(i);
        /backspace/i.test(e) && !lo(t.target) && (t.preventDefault(),
        Le())
    }
    , t=>{
        const e = ai(i, t);
        return !0 === e ? t : e
    }
    , po, t=>{
        const {target: e, key: o} = t
          , n = e.value || e.innerText
          , r = e.selectionStart || 0
          , a = e.selectionEnd || n.length
          , s = n.substring(0, r) + o + n.substring(a);
        if (ai(i, s) !== s)
            return t.preventDefault()
    }
    , t=>Wo(i) && /enter/i.test(t.code) ? t.preventDefault() : /arrow/i.test(t.code) ? t.stopPropagation() : /escape/i.test(t.key) ? go() : void 0, t=>{
        const {key: e, ctrlKey: o, altKey: i} = t;
        if (/enter/i.test(e) && (o || i))
            return mo()
    }
    , mo, go, Ro, t=>{
        const e = Ie();
        e && (Yo(e) || ci(e) && (Ge = e,
        Ze = Ci(Eo(Ge), bt),
        ge(Ge, Ze, t.detail)))
    }
    , t=>{
        o(14, Fo = !0)
    }
    , ({relatedTarget: t})=>{
        t && t.classList.contains("shape-selector__button") || o(14, Fo = !1)
    }
    , t=>{
        if (Je || Xe)
            return Bo(void 0);
        const e = Sg(t, vt)
          , o = it(Yt(e), (t=>Math.round(t)));
        if (ot(o, zo))
            return;
        zo = Z(o),
        Vt(e, o);
        const [i] = Ue(o, 0, (t=>ii(t)));
        Bo(i)
    }
    , G, U, Q, pt, mt, ft, $t, yt, bt, wt, St, kt, Tt, Pt, It, At, Et, Lt, Ot, Wt, Vt, Ht, Nt, Ut, jt, Xt, Yt, qt, Zt, Kt, ee, oe, (t,e={})=>{
        let o, i, n, r = No(t), a = _o(t), s = "relative" === e.position;
        return Xo(t) ? {
            start: e=>{
                const {origin: r} = e.detail;
                i = 4,
                o = Z(r),
                n = Z(r);
                const a = Yt(r);
                s && (a.x = s ? ro(a.x, bt.width) : a.x,
                a.y = s ? ro(a.y, bt.height) : a.y),
                ve({
                    ...t,
                    points: [a]
                })
            }
            ,
            update: t=>{
                const e = xe()
                  , {translation: r} = t.detail
                  , a = Y(o.x + r.x, o.y + r.y)
                  , l = ct(n, a);
                if (Io(l, 5) <= i)
                    return;
                const c = et(a, n)
                  , d = i - l;
                n.x += d * Math.cos(c),
                n.y += d * Math.sin(c);
                const u = Yt(n);
                u && (u.x = s ? ro(u.x, bt.width) : u.x,
                u.y = s ? ro(u.y, bt.height) : u.y),
                e.points = e.points.concat(u),
                Ce()
            }
            ,
            release: t=>t.detail.preventInertia(),
            end: t=>{
                if (t.detail.isTap)
                    return Se();
                const e = we();
                At(e)
            }
        } : r || a || Ho(t) ? {
            start: e=>{
                const {origin: i} = e.detail;
                o = Z(i);
                const n = Yt(o)
                  , a = {
                    ...t,
                    rotation: -1 * pe(Q, pt, mt),
                    x: s ? ro(n.x, bt.width) : n.x,
                    y: s ? ro(n.y, bt.height) : n.y
                };
                a.flipX = pt,
                a.flipY = mt,
                delete a.position,
                a.opacity = 0,
                r ? (a.rx = s ? ro(0) : 0,
                a.ry = s ? ro(0) : 0) : (a.width = s ? ro(0) : 0,
                a.height = s ? ro(0) : 0),
                ve(a)
            }
            ,
            update: t=>{
                const e = xe();
                e.opacity = 1;
                const {aspectRatio: i} = e;
                let {translation: n} = t.detail;
                if (i) {
                    const t = Math.abs(n.x) * i;
                    n.x = n.x,
                    n.y = t * Math.sign(n.y)
                }
                const a = Y(o.x + n.x, o.y + n.y)
                  , s = Yt(o)
                  , l = Yt(a)
                  , c = {
                    x: s.x + .5 * (l.x - s.x),
                    y: s.y + .5 * (l.y - s.y)
                }
                  , d = pe(Q, pt, mt);
                J(s, d, c),
                J(l, d, c);
                const u = Math.min(s.x, l.x)
                  , h = Math.min(s.y, l.y);
                let p = Math.max(s.x, l.x) - u
                  , m = Math.max(s.y, l.y) - h
                  , g = {};
                r ? (g.x = u + .5 * p,
                g.y = h + .5 * m,
                g.rx = .5 * p,
                g.ry = .5 * m) : (g.x = u,
                g.y = h,
                g.width = p,
                g.height = m),
                ne(e, g, bt),
                Ce()
            }
            ,
            release: t=>{
                t.detail.preventInertia()
            }
            ,
            end: t=>{
                const e = xe();
                if (t.detail.isTap) {
                    if (!_o(e) || !oe || Ye)
                        return Se();
                    delete e.width,
                    delete e.height,
                    delete e.textAlign;
                    const t = Ci({
                        ...e
                    }, bt)
                      , i = yo(e.text, t);
                    i.width *= ft,
                    i.height *= ft;
                    const n = Yt({
                        x: o.x,
                        y: o.y - .5 * i.height
                    })
                      , r = Yt({
                        x: o.x + i.width,
                        y: o.y + .5 * i.height
                    })
                      , a = {
                        x: n.x + .5 * (r.x - n.x),
                        y: n.y + .5 * (r.y - n.y)
                    }
                      , s = pe(Q, pt, mt);
                    J(n, s, a),
                    J(r, s, a);
                    const l = Math.min(n.x, r.x)
                      , c = Math.min(n.y, r.y);
                    e.x = w(e.x) ? ro(l, bt.width) : l,
                    e.y = w(e.y) ? ro(c, bt.height) : c
                }
                if (e.opacity = 1,
                !_o(e)) {
                    const t = we();
                    At(t)
                }
                Oe(e),
                _o(e) && _e(e)
            }
        } : Uo(t) ? {
            start: e=>{
                const {origin: i} = e.detail
                  , n = Yt(i)
                  , r = it(n, mc);
                o = Z(i),
                ve({
                    ...t,
                    x1: s ? ro(r.x, bt.width) : r.x,
                    y1: s ? ro(r.y, bt.height) : r.y,
                    x2: s ? ro(r.x, bt.width) : r.x,
                    y2: s ? ro(r.y, bt.height) : r.y,
                    opacity: 0
                })
            }
            ,
            update: t=>{
                const e = xe()
                  , {translation: i} = t.detail
                  , n = nt(Z(o), i);
                if (H.includes(16)) {
                    const t = ct(o, n)
                      , e = et(o, n)
                      , i = Math.PI / 4
                      , r = i * Math.round(e / i);
                    n.x = o.x + t * Math.cos(r),
                    n.y = o.y + t * Math.sin(r)
                }
                const r = Yt(n);
                Te(e, {
                    x2: s ? ro(r.x, bt.width) : r.x,
                    y2: s ? ro(r.y, bt.height) : r.y,
                    opacity: 1
                }),
                Ce()
            }
            ,
            release: t=>t.detail.preventInertia(),
            end: t=>{
                const e = xe();
                if (t.detail.isTap)
                    return Se();
                e.opacity = 1;
                const o = we();
                At(o),
                Oe(o)
            }
        } : void 0
    }
    , ()=>{
        let t, e;
        const o = Zt * Zt
          , i = (t,e,i=!1)=>{
            const n = lt(t, e);
            if (!i && n < 2)
                return !1;
            const r = q.filter((t=>!t.disableErase));
            let a;
            a = n < o ? Ue(Yt(e), Zt) : je(r, Yt(t), Yt(e), Zt);
            return Ve(a).forEach(Ot),
            !0
        }
        ;
        return {
            start: o=>{
                t = Y(Math.round(o.detail.origin.x), Math.round(o.detail.origin.y)),
                i(t, t, !0),
                e = t
            }
            ,
            update: o=>{
                const {translation: n} = o.detail
                  , r = Y(Math.round(t.x + n.x), Math.round(t.y + n.y));
                i(e, r) && (e = Z(r))
            }
            ,
            release: t=>t.detail.preventInertia(),
            end: ()=>{}
        }
    }
    , xe, be, ve, we, Se, (t={})=>({
        id: T(),
        ...t
    }), Ce, ke, Me, Te, Re, (t,e,o=!0)=>{
        q.forEach((o=>Re(o, t, e, !1))),
        o && Ce()
    }
    , Pe, Ie, Ae, Ee, Le, ze, De, _e, We, Ve, He, Ne, Ue, je, Je, oo, i, r, s, l, c, p, g, $, x, b, v, R, P, I, A, E, L, F, z, B, O, function(e) {
        er(t, e)
    }
    , (t,e)=>Oe(q[t]), function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            co = t,
            o(6, co)
        }
        ))
    }
    , function() {
        C = this.value,
        o(17, C),
        o(12, y),
        o(109, i),
        o(116, $),
        o(0, q)
    }
    , function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            co = t,
            o(6, co)
        }
        ))
    }
    , t=>o(7, Po = t.detail), t=>Sg(t, vt)]
}
class Ug extends Br {
    constructor(t) {
        super(),
        zr(this, t, Ng, Hg, ln, {
            uid: 48,
            ui: 47,
            markup: 0,
            offset: 1,
            contextRotation: 49,
            contextFlipX: 50,
            contextFlipY: 51,
            contextScale: 52,
            active: 53,
            opacity: 54,
            parentRect: 55,
            rootRect: 2,
            utilRect: 56,
            hoverColor: 57,
            textInputMode: 3,
            oninteractionstart: 58,
            oninteractionupdate: 59,
            oninteractionrelease: 60,
            oninteractionend: 61,
            onaddshape: 62,
            onupdateshape: 63,
            onselectshape: 64,
            onremoveshape: 65,
            onhovershape: 66,
            onhovercanvas: 67,
            beforeSelectShape: 68,
            beforeDeselectShape: 69,
            beforeRemoveShape: 70,
            beforeUpdateShape: 71,
            willRenderShapeControls: 72,
            mapEditorPointToImagePoint: 73,
            mapImagePointToEditorPoint: 74,
            eraseRadius: 75,
            selectRadius: 76,
            enableButtonFlipVertical: 77,
            enableTapToAddText: 78,
            locale: 4,
            createShape: 79,
            eraseShape: 80,
            getMarkupItemDraft: 81,
            getMarkupItemDraftIndex: 82,
            addMarkupItemDraft: 83,
            confirmMarkupItemDraft: 84,
            discardMarkupItemDraft: 85,
            createMarkupItem: 86,
            syncShapes: 87,
            addShape: 88,
            removeMarkupShapeProps: 89,
            updateMarkupShape: 90,
            updateMarkupShapeProperty: 91,
            updateMarkupItemsShapeProperty: 92,
            updateMarkupShapeItems: 93,
            getActiveMarkupItem: 94,
            hasActiveMarkupItem: 95,
            removeShape: 96,
            removeActiveMarkupItem: 97,
            blurShapes: 98,
            selectShape: 5,
            deselectMarkupItem: 99,
            editMarkupItem: 100,
            finishEditMarkupItem: 101,
            removeMarkupItems: 102,
            getTextShapeRect: 103,
            getMarkupShapeRect: 104,
            getShapesNearPosition: 105,
            getShapesBetweenPoints: 106
        }, [-1, -1, -1, -1, -1, -1, -1])
    }
    get createShape() {
        return this.$$.ctx[79]
    }
    get eraseShape() {
        return this.$$.ctx[80]
    }
    get getMarkupItemDraft() {
        return this.$$.ctx[81]
    }
    get getMarkupItemDraftIndex() {
        return this.$$.ctx[82]
    }
    get addMarkupItemDraft() {
        return this.$$.ctx[83]
    }
    get confirmMarkupItemDraft() {
        return this.$$.ctx[84]
    }
    get discardMarkupItemDraft() {
        return this.$$.ctx[85]
    }
    get createMarkupItem() {
        return this.$$.ctx[86]
    }
    get syncShapes() {
        return this.$$.ctx[87]
    }
    get addShape() {
        return this.$$.ctx[88]
    }
    get removeMarkupShapeProps() {
        return this.$$.ctx[89]
    }
    get updateMarkupShape() {
        return this.$$.ctx[90]
    }
    get updateMarkupShapeProperty() {
        return this.$$.ctx[91]
    }
    get updateMarkupItemsShapeProperty() {
        return this.$$.ctx[92]
    }
    get updateMarkupShapeItems() {
        return this.$$.ctx[93]
    }
    get getActiveMarkupItem() {
        return this.$$.ctx[94]
    }
    get hasActiveMarkupItem() {
        return this.$$.ctx[95]
    }
    get removeShape() {
        return this.$$.ctx[96]
    }
    get removeActiveMarkupItem() {
        return this.$$.ctx[97]
    }
    get blurShapes() {
        return this.$$.ctx[98]
    }
    get selectShape() {
        return this.$$.ctx[5]
    }
    get deselectMarkupItem() {
        return this.$$.ctx[99]
    }
    get editMarkupItem() {
        return this.$$.ctx[100]
    }
    get finishEditMarkupItem() {
        return this.$$.ctx[101]
    }
    get removeMarkupItems() {
        return this.$$.ctx[102]
    }
    get getTextShapeRect() {
        return this.$$.ctx[103]
    }
    get getMarkupShapeRect() {
        return this.$$.ctx[104]
    }
    get getShapesNearPosition() {
        return this.$$.ctx[105]
    }
    get getShapesBetweenPoints() {
        return this.$$.ctx[106]
    }
}
function jg(t, e, o) {
    const i = t.slice();
    return i[7] = e[o],
    i
}
function Xg(t, e) {
    let o, i, n, r, a, s, l, c = Pc(e[7].componentProps.title, e[1]) + "";
    const d = [e[7].componentProps];
    var u = e[7].component;
    function h(t) {
        let e = {};
        for (let t = 0; t < d.length; t += 1)
            e = on(e, d[t]);
        return {
            props: e
        }
    }
    return u && (a = new u(h())),
    {
        key: t,
        first: null,
        c() {
            o = Rn("li"),
            i = Rn("span"),
            n = In(c),
            r = An(),
            a && Ar(a.$$.fragment),
            s = An(),
            Bn(i, "class", "PinturaShapeStyleLabel"),
            Bn(o, "class", "PinturaShapeStyle"),
            this.first = o
        },
        m(t, e) {
            Mn(t, o, e),
            kn(o, i),
            kn(i, n),
            kn(o, r),
            a && Er(a, o, null),
            kn(o, s),
            l = !0
        },
        p(t, i) {
            e = t,
            (!l || 3 & i) && c !== (c = Pc(e[7].componentProps.title, e[1]) + "") && Dn(n, c);
            const r = 1 & i ? Rr(d, [Pr(e[7].componentProps)]) : {};
            if (u !== (u = e[7].component)) {
                if (a) {
                    yr();
                    const t = a;
                    vr(t.$$.fragment, 1, 0, (()=>{
                        Lr(t, 1)
                    }
                    )),
                    xr()
                }
                u ? (a = new u(h()),
                Ar(a.$$.fragment),
                br(a.$$.fragment, 1),
                Er(a, o, s)) : a = null
            } else
                u && a.$set(r)
        },
        i(t) {
            l || (a && br(a.$$.fragment, t),
            l = !0)
        },
        o(t) {
            a && vr(a.$$.fragment, t),
            l = !1
        },
        d(t) {
            t && Tn(o),
            a && Lr(a)
        }
    }
}
function Yg(t) {
    let e, o, i = [], n = new Map, r = t[0];
    const a = t=>t[7].id;
    for (let e = 0; e < r.length; e += 1) {
        let o = jg(t, r, e)
          , s = a(o);
        n.set(s, i[e] = Xg(s, o))
    }
    return {
        c() {
            e = Rn("ul");
            for (let t = 0; t < i.length; t += 1)
                i[t].c();
            Bn(e, "class", "PinturaShapeStyleList")
        },
        m(t, n) {
            Mn(t, e, n);
            for (let t = 0; t < i.length; t += 1)
                i[t].m(e, null);
            o = !0
        },
        p(t, o) {
            3 & o && (r = t[0],
            yr(),
            i = Tr(i, o, a, 1, t, r, n, e, Mr, Xg, null, jg),
            xr())
        },
        i(t) {
            if (!o) {
                for (let t = 0; t < r.length; t += 1)
                    br(i[t]);
                o = !0
            }
        },
        o(t) {
            for (let t = 0; t < i.length; t += 1)
                vr(i[t]);
            o = !1
        },
        d(t) {
            t && Tn(e);
            for (let t = 0; t < i.length; t += 1)
                i[t].d()
        }
    }
}
function Gg(t) {
    let e, o, i;
    return o = new ql({
        props: {
            class: "PinturaShapeStyles",
            elasticity: t[2],
            $$slots: {
                default: [Yg]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            e = Rn("div"),
            Ar(o.$$.fragment),
            Bn(e, "style", t[3])
        },
        m(t, n) {
            Mn(t, e, n),
            Er(o, e, null),
            i = !0
        },
        p(t, [n]) {
            const r = {};
            4 & n && (r.elasticity = t[2]),
            1027 & n && (r.$$scope = {
                dirty: n,
                ctx: t
            }),
            o.$set(r),
            (!i || 8 & n) && Bn(e, "style", t[3])
        },
        i(t) {
            i || (br(o.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            i = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o)
        }
    }
}
function qg(t, e, o) {
    let i, n, {isActive: r=!1} = e, {controls: a=[]} = e, {locale: s} = e, {scrollElasticity: l} = e;
    const c = ls(0);
    return un(t, c, (t=>o(6, n = t))),
    t.$$set = t=>{
        "isActive"in t && o(5, r = t.isActive),
        "controls"in t && o(0, a = t.controls),
        "locale"in t && o(1, s = t.locale),
        "scrollElasticity"in t && o(2, l = t.scrollElasticity)
    }
    ,
    t.$$.update = ()=>{
        32 & t.$$.dirty && c.set(r ? 1 : 0),
        96 & t.$$.dirty && o(3, i = `opacity:${n};${r ? "" : "pointer-events:none;"}${n <= 0 ? "visibility:hidden" : ""}`)
    }
    ,
    [a, s, l, i, c, r, n]
}
class Zg extends Br {
    constructor(t) {
        super(),
        zr(this, t, qg, Gg, ln, {
            isActive: 5,
            controls: 0,
            locale: 1,
            scrollElasticity: 2
        })
    }
}
function Kg(t, e, o) {
    const i = t.slice();
    return i[11] = e[o].key,
    i[2] = e[o].controls,
    i[12] = e[o].isActive,
    i
}
function Jg(t, e) {
    let o, i, n;
    return i = new Zg({
        props: {
            isActive: e[12],
            controls: e[2],
            locale: e[0],
            scrollElasticity: e[1]
        }
    }),
    {
        key: t,
        first: null,
        c() {
            o = En(),
            Ar(i.$$.fragment),
            this.first = o
        },
        m(t, e) {
            Mn(t, o, e),
            Er(i, t, e),
            n = !0
        },
        p(t, o) {
            e = t;
            const n = {};
            8 & o && (n.isActive = e[12]),
            8 & o && (n.controls = e[2]),
            1 & o && (n.locale = e[0]),
            2 & o && (n.scrollElasticity = e[1]),
            i.$set(n)
        },
        i(t) {
            n || (br(i.$$.fragment, t),
            n = !0)
        },
        o(t) {
            vr(i.$$.fragment, t),
            n = !1
        },
        d(t) {
            t && Tn(o),
            Lr(i, t)
        }
    }
}
function Qg(t) {
    let e, o, i = [], n = new Map, r = t[3];
    const a = t=>t[11];
    for (let e = 0; e < r.length; e += 1) {
        let o = Kg(t, r, e)
          , s = a(o);
        n.set(s, i[e] = Jg(s, o))
    }
    return {
        c() {
            e = Rn("div");
            for (let t = 0; t < i.length; t += 1)
                i[t].c();
            Bn(e, "class", "PinturaShapeStyleEditor")
        },
        m(t, n) {
            Mn(t, e, n);
            for (let t = 0; t < i.length; t += 1)
                i[t].m(e, null);
            o = !0
        },
        p(t, [o]) {
            11 & o && (r = t[3],
            yr(),
            i = Tr(i, o, a, 1, t, r, n, e, Mr, Jg, null, Kg),
            xr())
        },
        i(t) {
            if (!o) {
                for (let t = 0; t < r.length; t += 1)
                    br(i[t]);
                o = !0
            }
        },
        o(t) {
            for (let t = 0; t < i.length; t += 1)
                vr(i[t]);
            o = !1
        },
        d(t) {
            t && Tn(e);
            for (let t = 0; t < i.length; t += 1)
                i[t].d()
        }
    }
}
function tf(t, e, o) {
    let i, n, r, {controls: a={}} = e, {shape: s} = e, {onchange: l} = e, {locale: c} = e, {scrollElasticity: d} = e;
    const u = [];
    return t.$$set = t=>{
        "controls"in t && o(2, a = t.controls),
        "shape"in t && o(4, s = t.shape),
        "onchange"in t && o(5, l = t.onchange),
        "locale"in t && o(0, c = t.locale),
        "scrollElasticity"in t && o(1, d = t.scrollElasticity)
    }
    ,
    t.$$.update = ()=>{
        4 & t.$$.dirty && o(6, i = Object.keys(a).filter((t=>a[t]))),
        80 & t.$$.dirty && o(7, n = s && i && oi(s) ? (t=>i.filter((e=>e.split("_").every((e=>Ke(t, e) && oi(t, e))))).map((e=>{
            const o = e.split("_")
              , i = o.length > 1 ? o.map((e=>t[e])) : t[e];
            let[n,r] = a[e];
            if (w(n))
                if (a[n]) {
                    const t = {
                        ...r
                    };
                    [n,r] = a[n],
                    r = {
                        ...r,
                        ...t
                    }
                } else {
                    if ("Dropdown" !== n)
                        return;
                    n = gd
                }
            const s = S(r.options) ? r.options(t) : r.options;
            return {
                id: e,
                component: n,
                componentProps: {
                    ...r,
                    options: s,
                    locale: c,
                    value: i,
                    optionLabelClass: "PinturaButtonLabel",
                    onchange: i=>{
                        const n = x(i) && !Je(i) ? i.value : i;
                        r.onchange && r.onchange(n, t);
                        const a = o.length > 1 ? o.reduce(((t,e,o)=>({
                            ...t,
                            [e]: Array.isArray(n) ? n[o] : n
                        })), {}) : {
                            [e]: n
                        };
                        l(a)
                    }
                }
            }
        }
        )).filter(Boolean))(s) : []),
        144 & t.$$.dirty && o(3, r = ((t,e)=>{
            let o = u.find((e=>e.key === t));
            return o || (o = {
                key: t,
                controls: e
            },
            u.push(o)),
            u.forEach((t=>t.isActive = !1)),
            o.controls = e,
            o.isActive = !0,
            u
        }
        )(s ? Object.keys(s).join("_") : "none", n || []))
    }
    ,
    [c, d, a, r, s, l, i, n]
}
class ef extends Br {
    constructor(t) {
        super(),
        zr(this, t, tf, Qg, ln, {
            controls: 2,
            shape: 4,
            onchange: 5,
            locale: 0,
            scrollElasticity: 1
        })
    }
}
function of(t) {
    let e, o, i;
    return {
        c() {
            e = Rn("button"),
            Bn(e, "class", "PinturaDragButton"),
            Bn(e, "title", t[1]),
            e.disabled = t[2]
        },
        m(n, r) {
            Mn(n, e, r),
            e.innerHTML = t[0],
            t[9](e),
            o || (i = Ln(e, "pointerdown", t[4]),
            o = !0)
        },
        p(t, [o]) {
            1 & o && (e.innerHTML = t[0]),
            2 & o && Bn(e, "title", t[1]),
            4 & o && (e.disabled = t[2])
        },
        i: tn,
        o: tn,
        d(n) {
            n && Tn(e),
            t[9](null),
            o = !1,
            i()
        }
    }
}
function nf(t, e, o) {
    let i, {html: r} = e, {title: a} = e, {onclick: s} = e, {disabled: l=!1} = e, {ongrab: c=n} = e, {ondrag: d=n} = e, {ondrop: u=n} = e;
    const h = t=>lt(p, Y(t.pageX, t.pageY)) < 256;
    let p;
    const m = t=>{
        document.documentElement.removeEventListener("pointermove", g),
        document.documentElement.removeEventListener("pointerup", m);
        const e = Y(t.pageX, t.pageY);
        if (lt(p, e) < 32)
            return s(t);
        h(t) || u(t)
    }
      , g = t=>{
        h(t) || d(t)
    }
    ;
    return t.$$set = t=>{
        "html"in t && o(0, r = t.html),
        "title"in t && o(1, a = t.title),
        "onclick"in t && o(5, s = t.onclick),
        "disabled"in t && o(2, l = t.disabled),
        "ongrab"in t && o(6, c = t.ongrab),
        "ondrag"in t && o(7, d = t.ondrag),
        "ondrop"in t && o(8, u = t.ondrop)
    }
    ,
    [r, a, l, i, t=>{
        p = Y(t.pageX, t.pageY),
        c(t),
        document.documentElement.addEventListener("pointermove", g),
        document.documentElement.addEventListener("pointerup", m)
    }
    , s, c, d, u, function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            i = t,
            o(3, i)
        }
        ))
    }
    ]
}
class rf extends Br {
    constructor(t) {
        super(),
        zr(this, t, nf, of, ln, {
            html: 0,
            title: 1,
            onclick: 5,
            disabled: 2,
            ongrab: 6,
            ondrag: 7,
            ondrop: 8
        })
    }
}
function af(t, e, o) {
    const i = t.slice();
    return i[14] = e[o],
    i
}
function sf(t, e) {
    let o, i, n, r, a, s, l;
    function c() {
        return e[10](e[14])
    }
    function d(...t) {
        return e[11](e[14], ...t)
    }
    function u(...t) {
        return e[12](e[14], ...t)
    }
    function h(...t) {
        return e[13](e[14], ...t)
    }
    return i = new rf({
        props: {
            onclick: c,
            ongrab: d,
            ondrag: u,
            ondrop: h,
            disabled: e[1] || e[14].disabled,
            title: e[14].title,
            html: e[14].thumb
        }
    }),
    {
        key: t,
        first: null,
        c() {
            o = Rn("li"),
            Ar(i.$$.fragment),
            n = An(),
            Bn(o, "class", "PinturaShapePreset"),
            Bn(o, "style", e[6]),
            this.first = o
        },
        m(t, c) {
            Mn(t, o, c),
            Er(i, o, null),
            kn(o, n),
            a = !0,
            s || (l = yn(r = e[8].call(null, o, e[14])),
            s = !0)
        },
        p(t, n) {
            e = t;
            const s = {};
            5 & n && (s.onclick = c),
            9 & n && (s.ongrab = d),
            17 & n && (s.ondrag = u),
            33 & n && (s.ondrop = h),
            3 & n && (s.disabled = e[1] || e[14].disabled),
            1 & n && (s.title = e[14].title),
            1 & n && (s.html = e[14].thumb),
            i.$set(s),
            (!a || 64 & n) && Bn(o, "style", e[6]),
            r && sn(r.update) && 1 & n && r.update.call(null, e[14])
        },
        i(t) {
            a || (br(i.$$.fragment, t),
            a = !0)
        },
        o(t) {
            vr(i.$$.fragment, t),
            a = !1
        },
        d(t) {
            t && Tn(o),
            Lr(i),
            s = !1,
            l()
        }
    }
}
function lf(t) {
    let e, o, i = [], n = new Map, r = t[0];
    const a = t=>t[14].id;
    for (let e = 0; e < r.length; e += 1) {
        let o = af(t, r, e)
          , s = a(o);
        n.set(s, i[e] = sf(s, o))
    }
    return {
        c() {
            e = Rn("ul");
            for (let t = 0; t < i.length; t += 1)
                i[t].c();
            Bn(e, "class", "PinturaShapePresetsList")
        },
        m(t, n) {
            Mn(t, e, n);
            for (let t = 0; t < i.length; t += 1)
                i[t].m(e, null);
            o = !0
        },
        p(t, [o]) {
            127 & o && (r = t[0],
            yr(),
            i = Tr(i, o, a, 1, t, r, n, e, Mr, sf, null, af),
            xr())
        },
        i(t) {
            if (!o) {
                for (let t = 0; t < r.length; t += 1)
                    br(i[t]);
                o = !0
            }
        },
        o(t) {
            for (let t = 0; t < i.length; t += 1)
                vr(i[t]);
            o = !1
        },
        d(t) {
            t && Tn(e);
            for (let t = 0; t < i.length; t += 1)
                i[t].d()
        }
    }
}
function cf(t, e, o) {
    let i, n, {presets: r} = e, {disabled: a} = e, {onclickpreset: s} = e, {ongrabpreset: l} = e, {ondragpreset: c} = e, {ondroppreset: d} = e;
    const u = as(0, {
        duration: 300
    });
    un(t, u, (t=>o(9, n = t)));
    qn((()=>u.set(1)));
    return t.$$set = t=>{
        "presets"in t && o(0, r = t.presets),
        "disabled"in t && o(1, a = t.disabled),
        "onclickpreset"in t && o(2, s = t.onclickpreset),
        "ongrabpreset"in t && o(3, l = t.ongrabpreset),
        "ondragpreset"in t && o(4, c = t.ondragpreset),
        "ondroppreset"in t && o(5, d = t.ondroppreset)
    }
    ,
    t.$$.update = ()=>{
        512 & t.$$.dirty && o(6, i = "opacity:" + n)
    }
    ,
    [r, a, s, l, c, d, i, u, (t,e)=>e.mount && e.mount(t.firstChild, e), n, t=>s(t.id), (t,e)=>l && l(t.id, e), (t,e)=>c && c(t.id, e), (t,e)=>d && d(t.id, e)]
}
class df extends Br {
    constructor(t) {
        super(),
        zr(this, t, cf, lf, ln, {
            presets: 0,
            disabled: 1,
            onclickpreset: 2,
            ongrabpreset: 3,
            ondragpreset: 4,
            ondroppreset: 5
        })
    }
}
var uf = t=>/<svg /.test(t);
function hf(t) {
    let e, o;
    return e = new _d({
        props: {
            items: t[13]
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            8192 & o && (i.items = t[13]),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function pf(t) {
    let e, o, i, n;
    const r = [gf, mf]
      , a = [];
    function s(t, e) {
        return t[7] ? 0 : 1
    }
    return e = s(t),
    o = a[e] = r[e](t),
    {
        c() {
            o.c(),
            i = En()
        },
        m(t, o) {
            a[e].m(t, o),
            Mn(t, i, o),
            n = !0
        },
        p(t, n) {
            let l = e;
            e = s(t),
            e === l ? a[e].p(t, n) : (yr(),
            vr(a[l], 1, 1, (()=>{
                a[l] = null
            }
            )),
            xr(),
            o = a[e],
            o ? o.p(t, n) : (o = a[e] = r[e](t),
            o.c()),
            br(o, 1),
            o.m(i.parentNode, i))
        },
        i(t) {
            n || (br(o),
            n = !0)
        },
        o(t) {
            vr(o),
            n = !1
        },
        d(t) {
            a[e].d(t),
            t && Tn(i)
        }
    }
}
function mf(t) {
    let e, o, i, n, r = t[13] && ff(t);
    return i = new ql({
        props: {
            scrollAutoCancel: t[6],
            elasticity: t[0],
            $$slots: {
                default: [$f]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            e = Rn("div"),
            r && r.c(),
            o = An(),
            Ar(i.$$.fragment),
            Bn(e, "class", "PinturaShapePresetsFlat")
        },
        m(t, a) {
            Mn(t, e, a),
            r && r.m(e, null),
            kn(e, o),
            Er(i, e, null),
            n = !0
        },
        p(t, n) {
            t[13] ? r ? (r.p(t, n),
            8192 & n && br(r, 1)) : (r = ff(t),
            r.c(),
            br(r, 1),
            r.m(e, o)) : r && (yr(),
            vr(r, 1, 1, (()=>{
                r = null
            }
            )),
            xr());
            const a = {};
            64 & n && (a.scrollAutoCancel = t[6]),
            1 & n && (a.elasticity = t[0]),
            536870974 & n && (a.$$scope = {
                dirty: n,
                ctx: t
            }),
            i.$set(a)
        },
        i(t) {
            n || (br(r),
            br(i.$$.fragment, t),
            n = !0)
        },
        o(t) {
            vr(r),
            vr(i.$$.fragment, t),
            n = !1
        },
        d(t) {
            t && Tn(e),
            r && r.d(),
            Lr(i)
        }
    }
}
function gf(t) {
    let e, o, i, n, r, a, s, l = t[13] && yf(t);
    const c = [{
        class: "PinturaControlList"
    }, {
        tabs: t[8]
    }, t[11], {
        layout: "compact"
    }];
    let d = {
        $$slots: {
            default: [wf, ({tab: t})=>({
                28: t
            }), ({tab: t})=>t ? 268435456 : 0]
        },
        $$scope: {
            ctx: t
        }
    };
    for (let t = 0; t < c.length; t += 1)
        d = on(d, c[t]);
    n = new pl({
        props: d
    }),
    n.$on("select", t[18]);
    const u = [{
        class: "PinturaControlPanels"
    }, {
        panelClass: "PinturaControlPanel"
    }, {
        panels: t[12]
    }, t[11]];
    let h = {
        $$slots: {
            default: [Cf, ({panel: t, panelIsActive: e})=>({
                26: t,
                27: e
            }), ({panel: t, panelIsActive: e})=>(t ? 67108864 : 0) | (e ? 134217728 : 0)]
        },
        $$scope: {
            ctx: t
        }
    };
    for (let t = 0; t < u.length; t += 1)
        h = on(h, u[t]);
    return a = new kl({
        props: h
    }),
    {
        c() {
            e = Rn("div"),
            o = Rn("div"),
            l && l.c(),
            i = An(),
            Ar(n.$$.fragment),
            r = An(),
            Ar(a.$$.fragment),
            Bn(o, "class", "PinturaShapePresetsGroups"),
            Bn(e, "class", "PinturaShapePresetsGrouped")
        },
        m(t, c) {
            Mn(t, e, c),
            kn(e, o),
            l && l.m(o, null),
            kn(o, i),
            Er(n, o, null),
            kn(e, r),
            Er(a, e, null),
            s = !0
        },
        p(t, e) {
            t[13] ? l ? (l.p(t, e),
            8192 & e && br(l, 1)) : (l = yf(t),
            l.c(),
            br(l, 1),
            l.m(o, i)) : l && (yr(),
            vr(l, 1, 1, (()=>{
                l = null
            }
            )),
            xr());
            const r = 2304 & e ? Rr(c, [c[0], 256 & e && {
                tabs: t[8]
            }, 2048 & e && Pr(t[11]), c[3]]) : {};
            805306368 & e && (r.$$scope = {
                dirty: e,
                ctx: t
            }),
            n.$set(r);
            const s = 6144 & e ? Rr(u, [u[0], u[1], 4096 & e && {
                panels: t[12]
            }, 2048 & e && Pr(t[11])]) : {};
            738198623 & e && (s.$$scope = {
                dirty: e,
                ctx: t
            }),
            a.$set(s)
        },
        i(t) {
            s || (br(l),
            br(n.$$.fragment, t),
            br(a.$$.fragment, t),
            s = !0)
        },
        o(t) {
            vr(l),
            vr(n.$$.fragment, t),
            vr(a.$$.fragment, t),
            s = !1
        },
        d(t) {
            t && Tn(e),
            l && l.d(),
            Lr(n),
            Lr(a)
        }
    }
}
function ff(t) {
    let e, o;
    return e = new _d({
        props: {
            items: t[13]
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            8192 & o && (i.items = t[13]),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function $f(t) {
    let e, o;
    return e = new df({
        props: {
            presets: t[5],
            onclickpreset: t[1],
            ongrabpreset: t[2],
            ondragpreset: t[3],
            ondroppreset: t[4]
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            32 & o && (i.presets = t[5]),
            2 & o && (i.onclickpreset = t[1]),
            4 & o && (i.ongrabpreset = t[2]),
            8 & o && (i.ondragpreset = t[3]),
            16 & o && (i.ondroppreset = t[4]),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function yf(t) {
    let e, o;
    return e = new _d({
        props: {
            items: t[13]
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            8192 & o && (i.items = t[13]),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function xf(t) {
    let e, o;
    return e = new Al({
        props: {
            $$slots: {
                default: [bf]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            805306368 & o && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function bf(t) {
    let e, o = t[28].icon + "";
    return {
        c() {
            e = Pn("g")
        },
        m(t, i) {
            Mn(t, e, i),
            e.innerHTML = o
        },
        p(t, i) {
            268435456 & i && o !== (o = t[28].icon + "") && (e.innerHTML = o)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function vf(t) {
    let e, o, i = t[28].label + "";
    return {
        c() {
            e = Rn("span"),
            o = In(i)
        },
        m(t, i) {
            Mn(t, e, i),
            kn(e, o)
        },
        p(t, e) {
            268435456 & e && i !== (i = t[28].label + "") && Dn(o, i)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function wf(t) {
    let e, o, i, n = t[28].icon && xf(t), r = !t[28].hideLabel && vf(t);
    return {
        c() {
            n && n.c(),
            e = An(),
            r && r.c(),
            o = En()
        },
        m(t, a) {
            n && n.m(t, a),
            Mn(t, e, a),
            r && r.m(t, a),
            Mn(t, o, a),
            i = !0
        },
        p(t, i) {
            t[28].icon ? n ? (n.p(t, i),
            268435456 & i && br(n, 1)) : (n = xf(t),
            n.c(),
            br(n, 1),
            n.m(e.parentNode, e)) : n && (yr(),
            vr(n, 1, 1, (()=>{
                n = null
            }
            )),
            xr()),
            t[28].hideLabel ? r && (r.d(1),
            r = null) : r ? r.p(t, i) : (r = vf(t),
            r.c(),
            r.m(o.parentNode, o))
        },
        i(t) {
            i || (br(n),
            i = !0)
        },
        o(t) {
            vr(n),
            i = !1
        },
        d(t) {
            n && n.d(t),
            t && Tn(e),
            r && r.d(t),
            t && Tn(o)
        }
    }
}
function Sf(t) {
    let e, o;
    return e = new df({
        props: {
            presets: t[10][t[26]].items,
            disabled: t[10][t[26]].disabled,
            onclickpreset: t[1],
            ongrabpreset: t[2],
            ondragpreset: t[3],
            ondroppreset: t[4]
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            67109888 & o && (i.presets = t[10][t[26]].items),
            67109888 & o && (i.disabled = t[10][t[26]].disabled),
            2 & o && (i.onclickpreset = t[1]),
            4 & o && (i.ongrabpreset = t[2]),
            8 & o && (i.ondragpreset = t[3]),
            16 & o && (i.ondroppreset = t[4]),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Cf(t) {
    let e, o;
    return e = new ql({
        props: {
            scroll: t[27] ? {
                scrollOffset: 0,
                animate: !1
            } : void 0,
            scrollAutoCancel: t[6],
            elasticity: t[0],
            $$slots: {
                default: [Sf]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            134217728 & o && (i.scroll = t[27] ? {
                scrollOffset: 0,
                animate: !1
            } : void 0),
            64 & o && (i.scrollAutoCancel = t[6]),
            1 & o && (i.elasticity = t[0]),
            603980830 & o && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function kf(t) {
    let e, o, i, n;
    const r = [pf, hf]
      , a = [];
    function s(t, e) {
        return t[6] ? 0 : t[13] ? 1 : -1
    }
    return ~(o = s(t)) && (i = a[o] = r[o](t)),
    {
        c() {
            e = Rn("div"),
            i && i.c(),
            Bn(e, "class", "PinturaShapePresetsPalette")
        },
        m(t, i) {
            Mn(t, e, i),
            ~o && a[o].m(e, null),
            n = !0
        },
        p(t, [n]) {
            let l = o;
            o = s(t),
            o === l ? ~o && a[o].p(t, n) : (i && (yr(),
            vr(a[l], 1, 1, (()=>{
                a[l] = null
            }
            )),
            xr()),
            ~o ? (i = a[o],
            i ? i.p(t, n) : (i = a[o] = r[o](t),
            i.c()),
            br(i, 1),
            i.m(e, null)) : i = null)
        },
        i(t) {
            n || (br(i),
            n = !0)
        },
        o(t) {
            vr(i),
            n = !1
        },
        d(t) {
            t && Tn(e),
            ~o && a[o].d()
        }
    }
}
function Mf(t, e, o) {
    let i, r, a, s, l, c, d, u, h, {locale: p} = e, {presets: m} = e, {scrollElasticity: g} = e, {enableSelectImage: f=!0} = e, {willRenderPresetToolbar: $=_} = e, {onaddpreset: y=n} = e, {ongrabpreset: x} = e, {ondragpreset: b} = e, {ondroppreset: v} = e;
    const S = "presets-" + T()
      , C = (t,e="")=>uf(t) ? t : Ze(t) ? io(t, e) : `<img src="${t}" alt="${e}"/>`
      , k = t=>F(he(t))
      , M = ["src", "alt", "thumb", "shape", "id", "mount", "disabled"]
      , R = t=>t.map((t=>(t=>Je(t) && w(t[0]) && Je(t[1]))(t) ? {
        ...t[2],
        id: `${S}-${t[0].toLowerCase()}`,
        label: t[0],
        items: R(t[1])
    } : (t=>{
        let e, o, i, n, r, a, s, l = t;
        return w(t) ? Ze(t) ? (e = t,
        r = t,
        n = C(e, r)) : (e = t,
        r = k(e),
        n = C(e, r)) : (e = t.src,
        r = t.alt || (w(e) ? k(e) : w(t.thumb) ? k(t.thumb) : void 0),
        n = C(t.thumb || e, r),
        o = t.shape,
        a = t.mount,
        s = t.disabled,
        i = Object.keys(t).reduce(((e,o)=>(M.includes(o) || (e[o] = t[o]),
        e)), {})),
        {
            id: l,
            src: e,
            thumb: n,
            shape: o,
            shapeProps: i,
            alt: r,
            title: r,
            mount: a,
            disabled: s
        }
    }
    )(t)));
    return t.$$set = t=>{
        "locale"in t && o(14, p = t.locale),
        "presets"in t && o(15, m = t.presets),
        "scrollElasticity"in t && o(0, g = t.scrollElasticity),
        "enableSelectImage"in t && o(16, f = t.enableSelectImage),
        "willRenderPresetToolbar"in t && o(17, $ = t.willRenderPresetToolbar),
        "onaddpreset"in t && o(1, y = t.onaddpreset),
        "ongrabpreset"in t && o(2, x = t.ongrabpreset),
        "ondragpreset"in t && o(3, b = t.ondragpreset),
        "ondroppreset"in t && o(4, v = t.ondroppreset)
    }
    ,
    t.$$.update = ()=>{
        32768 & t.$$.dirty && o(5, i = R(m)),
        32 & t.$$.dirty && o(6, r = i.length),
        96 & t.$$.dirty && o(7, a = r && i.some((t=>!!t.items))),
        160 & t.$$.dirty && o(8, s = a && i),
        160 & t.$$.dirty && o(10, l = a && i.reduce(((t,e)=>(t[e.id] = e,
        t)), {})),
        768 & t.$$.dirty && o(9, c = c || s && (s.find((t=>!t.disabled)) || {}).id),
        512 & t.$$.dirty && o(11, d = {
            name: S,
            selected: c
        }),
        256 & t.$$.dirty && o(12, u = s && s.map((t=>t.id))),
        212994 & t.$$.dirty && o(13, h = p && $([f && ["Button", "browse", {
            label: p.shapeLabelButtonSelectSticker,
            icon: p.shapeIconButtonSelectSticker,
            onclick: ()=>{
                tu().then((t=>{
                    t && y(t)
                }
                ))
            }
        }]]))
    }
    ,
    [g, y, x, b, v, i, r, a, s, c, l, d, u, h, p, m, f, $, ({detail: t})=>o(9, c = t)]
}
class Tf extends Br {
    constructor(t) {
        super(),
        zr(this, t, Mf, kf, ln, {
            locale: 14,
            presets: 15,
            scrollElasticity: 0,
            enableSelectImage: 16,
            willRenderPresetToolbar: 17,
            onaddpreset: 1,
            ongrabpreset: 2,
            ondragpreset: 3,
            ondroppreset: 4
        })
    }
}
function Rf(t) {
    let e, o, i, n;
    const r = [{
        locale: t[4]
    }, {
        uid: t[14]
    }, {
        parentRect: t[24]
    }, {
        rootRect: t[32]
    }, {
        utilRect: t[26]
    }, {
        offset: t[34]
    }, {
        contextScale: t[44]
    }, {
        contextRotation: t[17]
    }, {
        contextFlipX: t[18]
    }, {
        contextFlipY: t[19]
    }, {
        active: t[25]
    }, {
        opacity: t[29]
    }, {
        hoverColor: t[45]
    }, {
        eraseRadius: t[35]
    }, {
        selectRadius: t[6]
    }, {
        enableButtonFlipVertical: t[9]
    }, {
        mapEditorPointToImagePoint: t[15]
    }, {
        mapImagePointToEditorPoint: t[16]
    }, {
        enableTapToAddText: t[12]
    }, {
        textInputMode: t[7]
    }, {
        oninteractionstart: t[58]
    }, {
        oninteractionupdate: t[59]
    }, {
        oninteractionrelease: t[60]
    }, {
        oninteractionend: t[61]
    }, {
        onhovershape: t[63]
    }, {
        onaddshape: t[95]
    }, {
        onselectshape: t[96]
    }, {
        onupdateshape: t[97]
    }, {
        onremoveshape: t[98]
    }, t[41]];
    function a(e) {
        t[100](e)
    }
    function s(e) {
        t[101](e)
    }
    let l = {};
    for (let t = 0; t < r.length; t += 1)
        l = on(l, r[t]);
    return void 0 !== t[27] && (l.markup = t[27]),
    void 0 !== t[43] && (l.ui = t[43]),
    e = new Ug({
        props: l
    }),
    t[99](e),
    ir.push((()=>Ir(e, "markup", a))),
    ir.push((()=>Ir(e, "ui", s))),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, o) {
            Er(e, t, o),
            n = !0
        },
        p(t, n) {
            const a = 655348432 & n[0] | 2013291674 & n[1] | 130 & n[2] ? Rr(r, [16 & n[0] && {
                locale: t[4]
            }, 16384 & n[0] && {
                uid: t[14]
            }, 16777216 & n[0] && {
                parentRect: t[24]
            }, 2 & n[1] && {
                rootRect: t[32]
            }, 67108864 & n[0] && {
                utilRect: t[26]
            }, 8 & n[1] && {
                offset: t[34]
            }, 8192 & n[1] && {
                contextScale: t[44]
            }, 131072 & n[0] && {
                contextRotation: t[17]
            }, 262144 & n[0] && {
                contextFlipX: t[18]
            }, 524288 & n[0] && {
                contextFlipY: t[19]
            }, 33554432 & n[0] && {
                active: t[25]
            }, 536870912 & n[0] && {
                opacity: t[29]
            }, 16384 & n[1] && {
                hoverColor: t[45]
            }, 16 & n[1] && {
                eraseRadius: t[35]
            }, 64 & n[0] && {
                selectRadius: t[6]
            }, 512 & n[0] && {
                enableButtonFlipVertical: t[9]
            }, 32768 & n[0] && {
                mapEditorPointToImagePoint: t[15]
            }, 65536 & n[0] && {
                mapImagePointToEditorPoint: t[16]
            }, 4096 & n[0] && {
                enableTapToAddText: t[12]
            }, 128 & n[0] && {
                textInputMode: t[7]
            }, 134217728 & n[1] && {
                oninteractionstart: t[58]
            }, 268435456 & n[1] && {
                oninteractionupdate: t[59]
            }, 536870912 & n[1] && {
                oninteractionrelease: t[60]
            }, 1073741824 & n[1] && {
                oninteractionend: t[61]
            }, 2 & n[2] && {
                onhovershape: t[63]
            }, 128 & n[1] | 128 & n[2] && {
                onaddshape: t[95]
            }, 128 & n[1] && {
                onselectshape: t[96]
            }, 128 & n[1] | 128 & n[2] && {
                onupdateshape: t[97]
            }, 128 & n[1] | 128 & n[2] && {
                onremoveshape: t[98]
            }, 1024 & n[1] && Pr(t[41])]) : {};
            !o && 134217728 & n[0] && (o = !0,
            a.markup = t[27],
            cr((()=>o = !1))),
            !i && 4096 & n[1] && (i = !0,
            a.ui = t[43],
            cr((()=>i = !1))),
            e.$set(a)
        },
        i(t) {
            n || (br(e.$$.fragment, t),
            n = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            n = !1
        },
        d(o) {
            t[99](null),
            Lr(e, o)
        }
    }
}
function Pf(t) {
    let e, o, i, r, a, s = t[33] && Rf(t);
    return {
        c() {
            e = Rn("div"),
            s && s.c(),
            Bn(e, "slot", "main"),
            Bn(e, "style", o = "cursor: " + t[36])
        },
        m(o, l) {
            Mn(o, e, l),
            s && s.m(e, null),
            t[102](e),
            i = !0,
            r || (a = [yn(Ss.call(null, e)), Ln(e, "dropfiles", (function() {
                sn(t[11] ? t[68] : n) && (t[11] ? t[68] : n).apply(this, arguments)
            }
            )), yn(bs.call(null, e)), Ln(e, "measure", t[93])],
            r = !0)
        },
        p(n, r) {
            (t = n)[33] ? s ? (s.p(t, r),
            4 & r[1] && br(s, 1)) : (s = Rf(t),
            s.c(),
            br(s, 1),
            s.m(e, null)) : s && (yr(),
            vr(s, 1, 1, (()=>{
                s = null
            }
            )),
            xr()),
            (!i || 32 & r[1] && o !== (o = "cursor: " + t[36])) && Bn(e, "style", o)
        },
        i(t) {
            i || (br(s),
            i = !0)
        },
        o(t) {
            vr(s),
            i = !1
        },
        d(o) {
            o && Tn(e),
            s && s.d(),
            t[102](null),
            r = !1,
            an(a)
        }
    }
}
function If(t) {
    let e, o;
    return e = new Tf({
        props: {
            locale: t[4],
            presets: t[13],
            enableSelectImage: t[10],
            willRenderPresetToolbar: t[40],
            onaddpreset: t[67],
            ongrabpreset: t[64],
            ondragpreset: t[65],
            ondroppreset: t[66],
            scrollElasticity: t[39]
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            16 & o[0] && (i.locale = t[4]),
            8192 & o[0] && (i.presets = t[13]),
            1024 & o[0] && (i.enableSelectImage = t[10]),
            512 & o[1] && (i.willRenderPresetToolbar = t[40]),
            256 & o[1] && (i.scrollElasticity = t[39]),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Af(t) {
    let e, o, i, n, r, a;
    const s = [Lf, Ef]
      , l = [];
    function c(t, e) {
        return t[37] ? 0 : 1
    }
    o = c(t),
    i = l[o] = s[o](t);
    let d = t[23] && Ff(t);
    return {
        c() {
            e = Rn("div"),
            i.c(),
            n = An(),
            d && d.c(),
            r = En(),
            Bn(e, "class", "PinturaControlPanels")
        },
        m(t, i) {
            Mn(t, e, i),
            l[o].m(e, null),
            Mn(t, n, i),
            d && d.m(t, i),
            Mn(t, r, i),
            a = !0
        },
        p(t, n) {
            let a = o;
            o = c(t),
            o === a ? l[o].p(t, n) : (yr(),
            vr(l[a], 1, 1, (()=>{
                l[a] = null
            }
            )),
            xr(),
            i = l[o],
            i ? i.p(t, n) : (i = l[o] = s[o](t),
            i.c()),
            br(i, 1),
            i.m(e, null)),
            t[23] ? d ? (d.p(t, n),
            8388608 & n[0] && br(d, 1)) : (d = Ff(t),
            d.c(),
            br(d, 1),
            d.m(r.parentNode, r)) : d && (yr(),
            vr(d, 1, 1, (()=>{
                d = null
            }
            )),
            xr())
        },
        i(t) {
            a || (br(i),
            br(d),
            a = !0)
        },
        o(t) {
            vr(i),
            vr(d),
            a = !1
        },
        d(t) {
            t && Tn(e),
            l[o].d(),
            t && Tn(n),
            d && d.d(t),
            t && Tn(r)
        }
    }
}
function Ef(t) {
    let e, o, i;
    return o = new ef({
        props: {
            locale: t[4],
            shape: t[28],
            onchange: t[62],
            controls: t[8],
            scrollElasticity: t[39]
        }
    }),
    {
        c() {
            e = Rn("div"),
            Ar(o.$$.fragment),
            Bn(e, "class", "PinturaControlPanel")
        },
        m(t, n) {
            Mn(t, e, n),
            Er(o, e, null),
            i = !0
        },
        p(t, e) {
            const i = {};
            16 & e[0] && (i.locale = t[4]),
            268435456 & e[0] && (i.shape = t[28]),
            256 & e[0] && (i.controls = t[8]),
            256 & e[1] && (i.scrollElasticity = t[39]),
            o.$set(i)
        },
        i(t) {
            i || (br(o.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            i = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o)
        }
    }
}
function Lf(t) {
    let e, o, i;
    return o = new Tf({
        props: {
            locale: t[4],
            presets: t[13],
            enableSelectImage: t[10],
            willRenderPresetToolbar: t[40],
            onaddpreset: t[67],
            ongrabpreset: t[64],
            ondragpreset: t[65],
            ondroppreset: t[66],
            scrollElasticity: t[39]
        }
    }),
    {
        c() {
            e = Rn("div"),
            Ar(o.$$.fragment),
            Bn(e, "class", "PinturaControlPanel")
        },
        m(t, n) {
            Mn(t, e, n),
            Er(o, e, null),
            i = !0
        },
        p(t, e) {
            const i = {};
            16 & e[0] && (i.locale = t[4]),
            8192 & e[0] && (i.presets = t[13]),
            1024 & e[0] && (i.enableSelectImage = t[10]),
            512 & e[1] && (i.willRenderPresetToolbar = t[40]),
            256 & e[1] && (i.scrollElasticity = t[39]),
            o.$set(i)
        },
        i(t) {
            i || (br(o.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            i = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o)
        }
    }
}
function Ff(t) {
    let e, o;
    return e = new ql({
        props: {
            class: "PinturaControlListScroller",
            elasticity: t[39],
            $$slots: {
                default: [Df]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            256 & o[1] && (i.elasticity = t[39]),
            4194321 & o[0] | 536870912 & o[3] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function zf(t) {
    let e, o;
    return e = new Al({
        props: {
            $$slots: {
                default: [Bf]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            16 & o[0] | 805306368 & o[3] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Bf(t) {
    let e, o = (S(t[121].icon) ? t[121].icon(t[4]) : t[121].icon) + "";
    return {
        c() {
            e = Pn("g")
        },
        m(t, i) {
            Mn(t, e, i),
            e.innerHTML = o
        },
        p(t, i) {
            16 & i[0] | 268435456 & i[3] && o !== (o = (S(t[121].icon) ? t[121].icon(t[4]) : t[121].icon) + "") && (e.innerHTML = o)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function Of(t) {
    let e, o, i, n, r, a = (S(t[121].label) ? t[121].label(t[4]) : t[121].label) + "", s = t[121].icon && zf(t);
    return {
        c() {
            e = Rn("div"),
            s && s.c(),
            o = An(),
            i = Rn("span"),
            n = In(a),
            Bn(e, "slot", "option")
        },
        m(t, a) {
            Mn(t, e, a),
            s && s.m(e, null),
            kn(e, o),
            kn(e, i),
            kn(i, n),
            r = !0
        },
        p(t, i) {
            t[121].icon ? s ? (s.p(t, i),
            268435456 & i[3] && br(s, 1)) : (s = zf(t),
            s.c(),
            br(s, 1),
            s.m(e, o)) : s && (yr(),
            vr(s, 1, 1, (()=>{
                s = null
            }
            )),
            xr()),
            (!r || 16 & i[0] | 268435456 & i[3]) && a !== (a = (S(t[121].label) ? t[121].label(t[4]) : t[121].label) + "") && Dn(n, a)
        },
        i(t) {
            r || (br(s),
            r = !0)
        },
        o(t) {
            vr(s),
            r = !1
        },
        d(t) {
            t && Tn(e),
            s && s.d()
        }
    }
}
function Df(t) {
    let e, o;
    return e = new nd({
        props: {
            locale: t[4],
            class: "PinturaControlList",
            optionClass: "PinturaControlListOption",
            layout: "row",
            options: t[22],
            selectedIndex: t[22].findIndex(t[94]),
            onchange: t[57],
            $$slots: {
                option: [Of, ({option: t})=>({
                    121: t
                }), ({option: t})=>[0, 0, 0, t ? 268435456 : 0]]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            16 & o[0] && (i.locale = t[4]),
            4194304 & o[0] && (i.options = t[22]),
            4194305 & o[0] && (i.selectedIndex = t[22].findIndex(t[94])),
            16 & o[0] | 805306368 & o[3] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function _f(t) {
    let e, o, i, n;
    const r = [Af, If]
      , a = [];
    function s(t, e) {
        return t[31] ? 0 : t[37] ? 1 : -1
    }
    return ~(o = s(t)) && (i = a[o] = r[o](t)),
    {
        c() {
            e = Rn("div"),
            i && i.c(),
            Bn(e, "slot", "footer"),
            Bn(e, "style", t[42])
        },
        m(t, i) {
            Mn(t, e, i),
            ~o && a[o].m(e, null),
            n = !0
        },
        p(t, l) {
            let c = o;
            o = s(t),
            o === c ? ~o && a[o].p(t, l) : (i && (yr(),
            vr(a[c], 1, 1, (()=>{
                a[c] = null
            }
            )),
            xr()),
            ~o ? (i = a[o],
            i ? i.p(t, l) : (i = a[o] = r[o](t),
            i.c()),
            br(i, 1),
            i.m(e, null)) : i = null),
            (!n || 2048 & l[1]) && Bn(e, "style", t[42])
        },
        i(t) {
            n || (br(i),
            n = !0)
        },
        o(t) {
            vr(i),
            n = !1
        },
        d(t) {
            t && Tn(e),
            ~o && a[o].d()
        }
    }
}
function Wf(t) {
    let e, o;
    return e = new om({
        props: {
            $$slots: {
                footer: [_f],
                main: [Pf]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    e.$on("measure", t[103]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            2146435025 & o[0] | 32767 & o[1] | 536870912 & o[3] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Vf(t, e, o) {
    let i, n, r, a, s, l, c, d, u, h, p, m, g, f, $, y, x, b, v, S, C, k, M, T, R, P, I, A, E, L, F, z, B, O, D, W, V, H, N, U = tn, j = ()=>(U(),
    U = cn(Tt, (t=>o(24, M = t))),
    Tt), X = tn, G = ()=>(X(),
    X = cn(et, (t=>o(25, R = t))),
    et), q = tn, Z = ()=>(q(),
    q = cn(it, (t=>o(84, I = t))),
    it), K = tn, J = ()=>(K(),
    K = cn(at, (t=>o(27, L = t))),
    at), Q = tn, tt = ()=>(Q(),
    Q = cn(ot, (t=>o(29, z = t))),
    ot);
    t.$$.on_destroy.push((()=>U())),
    t.$$.on_destroy.push((()=>X())),
    t.$$.on_destroy.push((()=>q())),
    t.$$.on_destroy.push((()=>K())),
    t.$$.on_destroy.push((()=>Q()));
    let {isActive: et} = e;
    G();
    let {isActiveFraction: ot} = e;
    tt();
    let {isVisible: it} = e;
    Z();
    let {stores: nt} = e
      , {locale: rt={}} = e
      , {shapes: at} = e;
    J();
    let {tools: st=[]} = e
      , {toolShapes: lt=[]} = e
      , {toolActive: dt} = e
      , {toolSelectRadius: ut} = e
      , {textInputMode: ht} = e
      , {shapeControls: pt={}} = e
      , {enableButtonFlipVertical: mt=!1} = e
      , {enablePresetSelectImage: gt=!0} = e
      , {enablePresetDropImage: ft=!0} = e
      , {enableSelectToolToAddShape: $t=!1} = e
      , {enableTapToAddText: yt=!1} = e
      , {willRenderPresetToolbar: xt} = e
      , {shapePresets: bt=[]} = e
      , {utilKey: vt} = e
      , {mapScreenPointToImagePoint: wt} = e
      , {mapImagePointToScreenPoint: St} = e
      , {imageRotation: Ct=0} = e
      , {imageFlipX: kt=!1} = e
      , {imageFlipY: Mt=!1} = e
      , {parentRect: Tt} = e;
    j();
    let {hooks: Rt={}} = e;
    const {env: Pt, animation: It, history: At, rootRect: Et, rootColorSecondary: Lt, stageRect: Ft, utilRect: zt, elasticityMultiplier: Bt, scrollElasticity: Ot, imageOverlayMarkup: Dt, imagePreviewModifiers: Wt, imageCropRect: Vt, imageSize: Ht, presentationScalar: Nt, shapePreprocessor: Ut} = nt;
    un(t, Pt, (t=>o(90, O = t))),
    un(t, It, (t=>o(91, D = t))),
    un(t, Et, (t=>o(32, T = t))),
    un(t, Lt, (t=>o(45, N = t))),
    un(t, Ft, (t=>o(85, E = t))),
    un(t, zt, (t=>o(26, A = t))),
    un(t, Dt, (t=>o(43, V = t))),
    un(t, Wt, (t=>o(82, P = t))),
    un(t, Vt, (t=>o(108, B = t))),
    un(t, Nt, (t=>o(44, H = t))),
    un(t, Ut, (t=>o(89, F = t)));
    const jt = t=>{
        const [e,o] = lt[t];
        let i, n, r = "relative" === o.position;
        const a = r ? "0%" : 0
          , s = r ? "0%" : 0;
        Ho(e) || _o(e) ? (n = r ? "20%" : .2 * M.width,
        i = Eo(e),
        i.x = a,
        i.y = s,
        Ri(i, {
            width: n,
            height: n
        }, M)) : No(e) ? (n = r ? "10%" : .1 * M.width,
        i = Eo(e),
        i.x = a,
        i.y = s,
        Ri(i, {
            rx: n,
            ry: n
        }, M)) : Uo(e) && (n = r ? "10%" : .1 * M.width,
        i = Eo(e),
        i.x1 = a,
        i.y1 = s,
        i.x2 = a,
        i.y2 = s),
        i && Promise.resolve().then((()=>{
            ee(Qt(i, void 0, n))
        }
        ))
    }
      , Xt = t=>wt(Sg(t, T));
    let Yt, Gt, qt = {};
    let Kt, Jt;
    const Qt = (t,e,o)=>{
        let i = !1;
        e || (i = !0,
        e = x ? wt(_t(E)) : _t(B)),
        e.x -= M.x || 0,
        e.y -= M.y || 0,
        (kt || Mt) && (t.flipX = kt,
        t.flipY = Mt);
        const n = Yt.getShapesNearPosition(e);
        if (i && n.length) {
            const t = .1 * Math.min(B.width, B.height);
            e.x += Math.round(-t + Math.random() * t * 2),
            e.y += Math.round(-t + Math.random() * t * 2)
        }
        if (0 !== Ct && (t.rotation = kt && Mt ? -Ct : kt || Mt ? Ct : -Ct),
        Ke(t, "width") && Ke(t, "height")) {
            const {width: o, height: i} = Ti(t, ["width", "height"], M);
            Ri(t, {
                x: e.x - .5 * o,
                y: e.y - .5 * i
            }, M)
        } else if (No(t))
            Ri(t, {
                x: e.x,
                y: e.y
            }, M);
        else if (Uo(t)) {
            const {x1: i, y1: n, x2: r, y2: a} = Ti(t, ["x1", "y1", "x2", "y2"], M)
              , s = ct(Y(i, n), Y(r, a)) || w(o) ? yi(o, M.width) : o;
            Ri(t, {
                x1: e.x - s,
                y1: e.y + s,
                x2: e.x + s,
                y2: e.y - s
            }, M)
        }
        return t
    }
      , te = (t,e)=>{
        const o = Qt(Bo(t, B), e);
        return ee(o)
    }
      , ee = t=>{
        const {beforeAddShape: e=(()=>!0)} = Rt;
        if (e(t))
            return Yt.addShape(t),
            Yt.selectShape(t),
            At.write(),
            t
    }
    ;
    let oe = !1;
    const ie = ()=>At.write();
    let ne;
    const re = ls(D ? 20 : 0);
    un(t, re, (t=>o(92, W = t)));
    return t.$$set = t=>{
        "isActive"in t && G(o(1, et = t.isActive)),
        "isActiveFraction"in t && tt(o(2, ot = t.isActiveFraction)),
        "isVisible"in t && Z(o(3, it = t.isVisible)),
        "stores"in t && o(71, nt = t.stores),
        "locale"in t && o(4, rt = t.locale),
        "shapes"in t && J(o(5, at = t.shapes)),
        "tools"in t && o(72, st = t.tools),
        "toolShapes"in t && o(73, lt = t.toolShapes),
        "toolActive"in t && o(0, dt = t.toolActive),
        "toolSelectRadius"in t && o(6, ut = t.toolSelectRadius),
        "textInputMode"in t && o(7, ht = t.textInputMode),
        "shapeControls"in t && o(8, pt = t.shapeControls),
        "enableButtonFlipVertical"in t && o(9, mt = t.enableButtonFlipVertical),
        "enablePresetSelectImage"in t && o(10, gt = t.enablePresetSelectImage),
        "enablePresetDropImage"in t && o(11, ft = t.enablePresetDropImage),
        "enableSelectToolToAddShape"in t && o(74, $t = t.enableSelectToolToAddShape),
        "enableTapToAddText"in t && o(12, yt = t.enableTapToAddText),
        "willRenderPresetToolbar"in t && o(75, xt = t.willRenderPresetToolbar),
        "shapePresets"in t && o(13, bt = t.shapePresets),
        "utilKey"in t && o(14, vt = t.utilKey),
        "mapScreenPointToImagePoint"in t && o(15, wt = t.mapScreenPointToImagePoint),
        "mapImagePointToScreenPoint"in t && o(16, St = t.mapImagePointToScreenPoint),
        "imageRotation"in t && o(17, Ct = t.imageRotation),
        "imageFlipX"in t && o(18, kt = t.imageFlipX),
        "imageFlipY"in t && o(19, Mt = t.imageFlipY),
        "parentRect"in t && j(o(20, Tt = t.parentRect)),
        "hooks"in t && o(76, Rt = t.hooks)
    }
    ,
    t.$$.update = ()=>{
        var e;
        8192 & t.$$.dirty[0] | 1024 & t.$$.dirty[2] && o(22, i = 0 === bt.length ? st.filter((t=>"preset" !== t[0])) : st),
        256 & t.$$.dirty[0] && o(79, n = Object.keys(pt).length),
        4194304 & t.$$.dirty[0] && o(23, r = i.length > 1),
        4194304 & t.$$.dirty[0] && o(80, a = !!i.length),
        12582913 & t.$$.dirty[0] && r && void 0 === dt && o(0, dt = i[0][0]),
        1 & t.$$.dirty[0] && o(81, s = void 0 !== dt),
        917504 & t.$$.dirty[2] && o(31, l = (!s || a) && n),
        33570816 & t.$$.dirty[0] | 1048576 & t.$$.dirty[2] && (R ? $n(Wt, P[vt] = {
            maskMarkupOpacity: .85
        }, P) : delete P[vt]),
        1 & t.$$.dirty[0] && dt && Yt && Yt.blurShapes(),
        4194304 & t.$$.dirty[2] && o(33, c = I),
        67108864 & t.$$.dirty[0] | 8388608 & t.$$.dirty[2] && o(34, d = A && Y(E.x - A.x, E.y - A.y)),
        256 & t.$$.dirty[0] && o(86, u = Object.keys(pt)),
        134217728 & t.$$.dirty[0] && o(87, h = L.filter(qo)[0]),
        33554433 & t.$$.dirty[0] | 2048 & t.$$.dirty[2] && o(88, p = R && (lt[dt] ? fi(Eo(lt[dt][0])) : {})),
        83918848 & t.$$.dirty[2] && o(83, m = p && Object.keys(p).reduce(((t,e)=>{
            const o = "disableStyle" === e
              , i = u.find((t=>t.split("_").includes(e)));
            return o || i ? (void 0 === p[e] || (t[e] = Ke(qt, e) ? qt[e] : p[e]),
            t) : t
        }
        ), {})),
        35651584 & t.$$.dirty[2] && o(28, g = h || m),
        268435456 & t.$$.dirty[0] | 134217728 & t.$$.dirty[2] && g && g.lineEnd && !F && console.warn("Set shapePreprocessor property to draw lineStart and lineEnd styles.\nhttps://pqina.nl/pintura/docs/v8/api/exports/#createshapepreprocessor"),
        69206016 & t.$$.dirty[2] && o(35, f = p && qe(p.eraseRadius) ? (m || p).eraseRadius : void 0),
        33619968 & t.$$.dirty[2] && o(36, $ = ((t,e)=>{
            if (!t)
                return "crosshair";
            let o = t || e;
            return qo(o) ? (t=>t.isEditing)(o) ? "modal" === ht ? "default" : "text" : ci(o) ? "move" : "default" : "crosshair"
        }
        )(Jt, h)),
        536880129 & t.$$.dirty[0] && o(37, y = z > 0 && "preset" === dt && (bt.length > 0 || gt)),
        16777216 & t.$$.dirty[0] && (x = !Ke(M, "x") && !Ke(M, "y")),
        2097152 & t.$$.dirty[0] && o(38, b = ne && (e = ne,
        (t,o)=>{
            e.dispatchEvent(Vd(t, o))
        }
        )),
        268443648 & t.$$.dirty[2] && o(40, S = xt ? t=>xt(t, te, {
            ...O
        }) : _),
        16384 & t.$$.dirty[2] && o(41, C = Object.keys(Rt).reduce(((t,e)=>("beforeAddShape" === e || (t[e] = Rt[e]),
        t)), {})),
        33554432 & t.$$.dirty[0] | 536870912 & t.$$.dirty[2] && D && re.set(R ? 0 : 20),
        1073741824 & t.$$.dirty[2] && o(42, k = W ? `transform: translateY(${W}px)` : void 0)
    }
    ,
    o(39, v = Bt * Ot),
    [dt, et, ot, it, rt, at, ut, ht, pt, mt, gt, ft, yt, bt, vt, wt, St, Ct, kt, Mt, Tt, ne, i, r, M, R, A, L, g, z, Yt, l, T, c, d, f, $, y, b, v, S, C, k, V, H, N, Pt, It, Et, Lt, Ft, zt, Dt, Wt, Vt, Nt, Ut, ({value: t},e)=>{
        o(0, dt = t),
        ($t || Ec(e.key)) && jt(t)
    }
    , t=>{
        if ("eraser" === dt)
            Gt = Yt.eraseShape();
        else if (dt && lt[dt]) {
            const [t,e] = lt[dt];
            Gt = Yt.createShape({
                ...t,
                ...m
            }, e)
        } else
            Gt = void 0;
        return !!Gt && (Gt.start(t),
        !0)
    }
    , t=>!!Gt && (Gt.update(t),
    !0), t=>!!Gt && (Gt.release(t),
    !0), t=>!!Gt && (Gt.end(t),
    Gt = void 0,
    !0), function(t) {
        Object.keys(t).forEach((e=>o(77, qt[e] = t[e], qt))),
        h && (Yt.updateMarkupShape(h, t),
        clearTimeout(Kt),
        Kt = setTimeout((()=>{
            ie()
        }
        ), 200))
    }
    , t=>o(78, Jt = t), ()=>{
        oe = !1
    }
    , (t,e)=>{
        if (oe)
            return;
        const {beforeAddShape: o=(()=>!0)} = Rt
          , i = Xt(e)
          , n = Yt.getMarkupItemDraft()
          , r = Zt(B, {
            x: i.x + (M.x || 0),
            y: i.y + (M.y || 0)
        });
        if (n && !r && Yt.discardMarkupItemDraft(),
        r) {
            if (!n) {
                const n = Qt(Bo(t, B), i);
                return o(n) ? (ei(n),
                void Yt.addShape(n)) : (oe = !0,
                void e.preventDefault())
            }
            Ho(n) && (i.x -= .5 * n.width,
            i.y -= .5 * n.height),
            Yt.updateMarkupShape(n, i)
        }
    }
    , (t,e)=>{
        if (oe)
            return;
        const o = Xt(e);
        if (!Zt(B, {
            x: o.x + (M.x || 0),
            y: o.y + (M.y || 0)
        }))
            return void Yt.discardMarkupItemDraft();
        const i = Yt.confirmMarkupItemDraft();
        Yt.selectShape(i),
        At.write()
    }
    , t=>te(t), t=>{
        return e = t.detail.resources,
        o = Xt(t.detail.event),
        e.forEach((t=>te(t, o)));
        var e, o
    }
    , ie, re, nt, st, lt, $t, xt, Rt, qt, Jt, n, a, s, P, m, I, E, u, h, p, F, O, D, W, function(e) {
        er(t, e)
    }
    , t=>t[0] === dt, t=>{
        b("addshape", t),
        ie()
    }
    , t=>{
        b("selectshape", t)
    }
    , t=>{
        b("updateshape", t),
        ie()
    }
    , t=>{
        b("removeshape", t),
        ie()
    }
    , function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            Yt = t,
            o(30, Yt)
        }
        ))
    }
    , function(t) {
        L = t,
        at.set(L)
    }
    , function(t) {
        V = t,
        Dt.set(V)
    }
    , function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            ne = t,
            o(21, ne)
        }
        ))
    }
    , function(e) {
        er(t, e)
    }
    ]
}
class Hf extends Br {
    constructor(t) {
        super(),
        zr(this, t, Vf, Wf, ln, {
            isActive: 1,
            isActiveFraction: 2,
            isVisible: 3,
            stores: 71,
            locale: 4,
            shapes: 5,
            tools: 72,
            toolShapes: 73,
            toolActive: 0,
            toolSelectRadius: 6,
            textInputMode: 7,
            shapeControls: 8,
            enableButtonFlipVertical: 9,
            enablePresetSelectImage: 10,
            enablePresetDropImage: 11,
            enableSelectToolToAddShape: 74,
            enableTapToAddText: 12,
            willRenderPresetToolbar: 75,
            shapePresets: 13,
            utilKey: 14,
            mapScreenPointToImagePoint: 15,
            mapImagePointToScreenPoint: 16,
            imageRotation: 17,
            imageFlipX: 18,
            imageFlipY: 19,
            parentRect: 20,
            hooks: 76
        }, [-1, -1, -1, -1])
    }
    get isActive() {
        return this.$$.ctx[1]
    }
    set isActive(t) {
        this.$set({
            isActive: t
        }),
        hr()
    }
    get isActiveFraction() {
        return this.$$.ctx[2]
    }
    set isActiveFraction(t) {
        this.$set({
            isActiveFraction: t
        }),
        hr()
    }
    get isVisible() {
        return this.$$.ctx[3]
    }
    set isVisible(t) {
        this.$set({
            isVisible: t
        }),
        hr()
    }
    get stores() {
        return this.$$.ctx[71]
    }
    set stores(t) {
        this.$set({
            stores: t
        }),
        hr()
    }
    get locale() {
        return this.$$.ctx[4]
    }
    set locale(t) {
        this.$set({
            locale: t
        }),
        hr()
    }
    get shapes() {
        return this.$$.ctx[5]
    }
    set shapes(t) {
        this.$set({
            shapes: t
        }),
        hr()
    }
    get tools() {
        return this.$$.ctx[72]
    }
    set tools(t) {
        this.$set({
            tools: t
        }),
        hr()
    }
    get toolShapes() {
        return this.$$.ctx[73]
    }
    set toolShapes(t) {
        this.$set({
            toolShapes: t
        }),
        hr()
    }
    get toolActive() {
        return this.$$.ctx[0]
    }
    set toolActive(t) {
        this.$set({
            toolActive: t
        }),
        hr()
    }
    get toolSelectRadius() {
        return this.$$.ctx[6]
    }
    set toolSelectRadius(t) {
        this.$set({
            toolSelectRadius: t
        }),
        hr()
    }
    get textInputMode() {
        return this.$$.ctx[7]
    }
    set textInputMode(t) {
        this.$set({
            textInputMode: t
        }),
        hr()
    }
    get shapeControls() {
        return this.$$.ctx[8]
    }
    set shapeControls(t) {
        this.$set({
            shapeControls: t
        }),
        hr()
    }
    get enableButtonFlipVertical() {
        return this.$$.ctx[9]
    }
    set enableButtonFlipVertical(t) {
        this.$set({
            enableButtonFlipVertical: t
        }),
        hr()
    }
    get enablePresetSelectImage() {
        return this.$$.ctx[10]
    }
    set enablePresetSelectImage(t) {
        this.$set({
            enablePresetSelectImage: t
        }),
        hr()
    }
    get enablePresetDropImage() {
        return this.$$.ctx[11]
    }
    set enablePresetDropImage(t) {
        this.$set({
            enablePresetDropImage: t
        }),
        hr()
    }
    get enableSelectToolToAddShape() {
        return this.$$.ctx[74]
    }
    set enableSelectToolToAddShape(t) {
        this.$set({
            enableSelectToolToAddShape: t
        }),
        hr()
    }
    get enableTapToAddText() {
        return this.$$.ctx[12]
    }
    set enableTapToAddText(t) {
        this.$set({
            enableTapToAddText: t
        }),
        hr()
    }
    get willRenderPresetToolbar() {
        return this.$$.ctx[75]
    }
    set willRenderPresetToolbar(t) {
        this.$set({
            willRenderPresetToolbar: t
        }),
        hr()
    }
    get shapePresets() {
        return this.$$.ctx[13]
    }
    set shapePresets(t) {
        this.$set({
            shapePresets: t
        }),
        hr()
    }
    get utilKey() {
        return this.$$.ctx[14]
    }
    set utilKey(t) {
        this.$set({
            utilKey: t
        }),
        hr()
    }
    get mapScreenPointToImagePoint() {
        return this.$$.ctx[15]
    }
    set mapScreenPointToImagePoint(t) {
        this.$set({
            mapScreenPointToImagePoint: t
        }),
        hr()
    }
    get mapImagePointToScreenPoint() {
        return this.$$.ctx[16]
    }
    set mapImagePointToScreenPoint(t) {
        this.$set({
            mapImagePointToScreenPoint: t
        }),
        hr()
    }
    get imageRotation() {
        return this.$$.ctx[17]
    }
    set imageRotation(t) {
        this.$set({
            imageRotation: t
        }),
        hr()
    }
    get imageFlipX() {
        return this.$$.ctx[18]
    }
    set imageFlipX(t) {
        this.$set({
            imageFlipX: t
        }),
        hr()
    }
    get imageFlipY() {
        return this.$$.ctx[19]
    }
    set imageFlipY(t) {
        this.$set({
            imageFlipY: t
        }),
        hr()
    }
    get parentRect() {
        return this.$$.ctx[20]
    }
    set parentRect(t) {
        this.$set({
            parentRect: t
        }),
        hr()
    }
    get hooks() {
        return this.$$.ctx[76]
    }
    set hooks(t) {
        this.$set({
            hooks: t
        }),
        hr()
    }
}
var Nf = (t,e,o,i,n,r,a,s,l)=>{
    const c = Z(t)
      , d = .5 * o.width
      , u = .5 * o.height
      , h = .5 * e.width
      , p = .5 * e.height
      , m = n.x + i.x
      , g = n.y + i.y;
    s && (c.x = o.width - c.x),
    l && (c.y = o.height - c.y);
    const f = Math.cos(r)
      , $ = Math.sin(r);
    c.x -= d,
    c.y -= u;
    const y = c.x * f - c.y * $
      , x = c.x * $ + c.y * f;
    c.x = d + y,
    c.y = u + x,
    c.x *= a,
    c.y *= a,
    c.x += h,
    c.y += p,
    c.x += m,
    c.y += g,
    c.x -= d * a,
    c.y -= u * a;
    const b = (n.x - m) * a
      , v = (n.y - g) * a
      , w = b * f - v * $
      , S = b * $ + v * f;
    return c.x += w,
    c.y += S,
    c
}
  , Uf = (t,e,o,i,n,r,a,s,l)=>{
    const c = Z(t)
      , d = wt(o)
      , u = wt(e)
      , h = Y(n.x + i.x, n.y + i.y)
      , p = Math.cos(r)
      , m = Math.sin(r);
    c.x -= u.x,
    c.y -= u.y;
    const g = (n.x - h.x) * a
      , f = (n.y - h.y) * a
      , $ = g * p - f * m
      , y = g * m + f * p;
    c.x -= $,
    c.y -= y,
    c.x -= h.x,
    c.y -= h.y,
    c.x /= a,
    c.y /= a;
    const x = c.x * p + c.y * m
      , b = c.x * m - c.y * p;
    return c.x = x,
    c.y = -b,
    c.x += d.x,
    c.y += d.y,
    s && (c.x = o.width - c.x),
    l && (c.y = o.height - c.y),
    c
}
;
function jf(t) {
    let e, o, i;
    function n(e) {
        t[43](e)
    }
    let r = {
        stores: t[4],
        locale: t[5],
        isActive: t[1],
        isActiveFraction: t[2],
        isVisible: t[3],
        mapScreenPointToImagePoint: t[29],
        mapImagePointToScreenPoint: t[30],
        utilKey: "annotate",
        imageRotation: t[31],
        imageFlipX: t[27],
        imageFlipY: t[28],
        shapes: t[33],
        tools: t[12] || t[6],
        toolShapes: t[13] || t[7],
        enableSelectToolToAddShape: t[19],
        enableTapToAddText: t[20],
        shapeControls: t[14] || t[8],
        shapePresets: t[17],
        enableButtonFlipVertical: t[15],
        parentRect: t[34],
        enablePresetSelectImage: t[16],
        toolSelectRadius: t[9],
        textInputMode: t[10],
        willRenderPresetToolbar: t[18] || t[11],
        hooks: {
            willRenderShapeControls: t[21],
            beforeAddShape: t[22],
            beforeRemoveShape: t[23],
            beforeDeselectShape: t[24],
            beforeSelectShape: t[25],
            beforeUpdateShape: t[26]
        }
    };
    return void 0 !== t[0] && (r.toolActive = t[0]),
    e = new Hf({
        props: r
    }),
    ir.push((()=>Ir(e, "toolActive", n))),
    e.$on("measure", t[44]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, o) {
            Er(e, t, o),
            i = !0
        },
        p(t, i) {
            const n = {};
            16 & i[0] && (n.stores = t[4]),
            32 & i[0] && (n.locale = t[5]),
            2 & i[0] && (n.isActive = t[1]),
            4 & i[0] && (n.isActiveFraction = t[2]),
            8 & i[0] && (n.isVisible = t[3]),
            536870912 & i[0] && (n.mapScreenPointToImagePoint = t[29]),
            1073741824 & i[0] && (n.mapImagePointToScreenPoint = t[30]),
            1 & i[1] && (n.imageRotation = t[31]),
            134217728 & i[0] && (n.imageFlipX = t[27]),
            268435456 & i[0] && (n.imageFlipY = t[28]),
            4160 & i[0] && (n.tools = t[12] || t[6]),
            8320 & i[0] && (n.toolShapes = t[13] || t[7]),
            524288 & i[0] && (n.enableSelectToolToAddShape = t[19]),
            1048576 & i[0] && (n.enableTapToAddText = t[20]),
            16640 & i[0] && (n.shapeControls = t[14] || t[8]),
            131072 & i[0] && (n.shapePresets = t[17]),
            32768 & i[0] && (n.enableButtonFlipVertical = t[15]),
            65536 & i[0] && (n.enablePresetSelectImage = t[16]),
            512 & i[0] && (n.toolSelectRadius = t[9]),
            1024 & i[0] && (n.textInputMode = t[10]),
            264192 & i[0] && (n.willRenderPresetToolbar = t[18] || t[11]),
            132120576 & i[0] && (n.hooks = {
                willRenderShapeControls: t[21],
                beforeAddShape: t[22],
                beforeRemoveShape: t[23],
                beforeDeselectShape: t[24],
                beforeSelectShape: t[25],
                beforeUpdateShape: t[26]
            }),
            !o && 1 & i[0] && (o = !0,
            n.toolActive = t[0],
            cr((()=>o = !1))),
            e.$set(n)
        },
        i(t) {
            i || (br(e.$$.fragment, t),
            i = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            i = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Xf(t, e, o) {
    let i, n, r, a, s, l, c, d;
    let {isActive: u} = e
      , {isActiveFraction: h} = e
      , {isVisible: p} = e
      , {stores: m} = e
      , {locale: g={}} = e
      , {markupEditorToolbar: f} = e
      , {markupEditorToolStyles: $} = e
      , {markupEditorShapeStyleControls: y} = e
      , {markupEditorToolSelectRadius: x} = e
      , {markupEditorTextInputMode: b} = e
      , {willRenderShapePresetToolbar: v} = e
      , {annotateTools: w} = e
      , {annotateToolShapes: S} = e
      , {annotateShapeControls: C} = e
      , {annotateActiveTool: k} = e
      , {annotateEnableButtonFlipVertical: M=!1} = e
      , {annotateEnableSelectImagePreset: T=!1} = e
      , {annotatePresets: R=[]} = e
      , {annotateWillRenderShapePresetToolbar: P} = e
      , {enableSelectToolToAddShape: I} = e
      , {enableTapToAddText: A} = e
      , {willRenderShapeControls: E} = e
      , {beforeAddShape: L} = e
      , {beforeRemoveShape: F} = e
      , {beforeDeselectShape: z} = e
      , {beforeSelectShape: B} = e
      , {beforeUpdateShape: O} = e;
    const {rootRect: D, imageAnnotation: _, imageSize: W, imageTransforms: V, imageRotation: H, imageFlipX: N, imageFlipY: U} = m;
    return un(t, D, (t=>o(40, r = t))),
    un(t, W, (t=>o(41, a = t))),
    un(t, V, (t=>o(42, s = t))),
    un(t, H, (t=>o(31, d = t))),
    un(t, N, (t=>o(27, l = t))),
    un(t, U, (t=>o(28, c = t))),
    t.$$set = t=>{
        "isActive"in t && o(1, u = t.isActive),
        "isActiveFraction"in t && o(2, h = t.isActiveFraction),
        "isVisible"in t && o(3, p = t.isVisible),
        "stores"in t && o(4, m = t.stores),
        "locale"in t && o(5, g = t.locale),
        "markupEditorToolbar"in t && o(6, f = t.markupEditorToolbar),
        "markupEditorToolStyles"in t && o(7, $ = t.markupEditorToolStyles),
        "markupEditorShapeStyleControls"in t && o(8, y = t.markupEditorShapeStyleControls),
        "markupEditorToolSelectRadius"in t && o(9, x = t.markupEditorToolSelectRadius),
        "markupEditorTextInputMode"in t && o(10, b = t.markupEditorTextInputMode),
        "willRenderShapePresetToolbar"in t && o(11, v = t.willRenderShapePresetToolbar),
        "annotateTools"in t && o(12, w = t.annotateTools),
        "annotateToolShapes"in t && o(13, S = t.annotateToolShapes),
        "annotateShapeControls"in t && o(14, C = t.annotateShapeControls),
        "annotateActiveTool"in t && o(0, k = t.annotateActiveTool),
        "annotateEnableButtonFlipVertical"in t && o(15, M = t.annotateEnableButtonFlipVertical),
        "annotateEnableSelectImagePreset"in t && o(16, T = t.annotateEnableSelectImagePreset),
        "annotatePresets"in t && o(17, R = t.annotatePresets),
        "annotateWillRenderShapePresetToolbar"in t && o(18, P = t.annotateWillRenderShapePresetToolbar),
        "enableSelectToolToAddShape"in t && o(19, I = t.enableSelectToolToAddShape),
        "enableTapToAddText"in t && o(20, A = t.enableTapToAddText),
        "willRenderShapeControls"in t && o(21, E = t.willRenderShapeControls),
        "beforeAddShape"in t && o(22, L = t.beforeAddShape),
        "beforeRemoveShape"in t && o(23, F = t.beforeRemoveShape),
        "beforeDeselectShape"in t && o(24, z = t.beforeDeselectShape),
        "beforeSelectShape"in t && o(25, B = t.beforeSelectShape),
        "beforeUpdateShape"in t && o(26, O = t.beforeUpdateShape)
    }
    ,
    t.$$.update = ()=>{
        402653184 & t.$$.dirty[0] | 3584 & t.$$.dirty[1] && o(29, i = t=>Uf(t, r, a, s.origin, s.translation, s.rotation.z, s.scale, l, c)),
        402653184 & t.$$.dirty[0] | 3584 & t.$$.dirty[1] && o(30, n = t=>Nf(t, r, a, s.origin, s.translation, s.rotation.z, s.scale, l, c))
    }
    ,
    [k, u, h, p, m, g, f, $, y, x, b, v, w, S, C, M, T, R, P, I, A, E, L, F, z, B, O, l, c, i, n, d, D, _, W, V, H, N, U, "annotate", r, a, s, function(t) {
        k = t,
        o(0, k)
    }
    , function(e) {
        er(t, e)
    }
    ]
}
var Yf = {
    util: ["annotate", class extends Br {
        constructor(t) {
            super(),
            zr(this, t, Xf, jf, ln, {
                name: 39,
                isActive: 1,
                isActiveFraction: 2,
                isVisible: 3,
                stores: 4,
                locale: 5,
                markupEditorToolbar: 6,
                markupEditorToolStyles: 7,
                markupEditorShapeStyleControls: 8,
                markupEditorToolSelectRadius: 9,
                markupEditorTextInputMode: 10,
                willRenderShapePresetToolbar: 11,
                annotateTools: 12,
                annotateToolShapes: 13,
                annotateShapeControls: 14,
                annotateActiveTool: 0,
                annotateEnableButtonFlipVertical: 15,
                annotateEnableSelectImagePreset: 16,
                annotatePresets: 17,
                annotateWillRenderShapePresetToolbar: 18,
                enableSelectToolToAddShape: 19,
                enableTapToAddText: 20,
                willRenderShapeControls: 21,
                beforeAddShape: 22,
                beforeRemoveShape: 23,
                beforeDeselectShape: 24,
                beforeSelectShape: 25,
                beforeUpdateShape: 26
            }, [-1, -1])
        }
        get name() {
            return this.$$.ctx[39]
        }
        get isActive() {
            return this.$$.ctx[1]
        }
        set isActive(t) {
            this.$set({
                isActive: t
            }),
            hr()
        }
        get isActiveFraction() {
            return this.$$.ctx[2]
        }
        set isActiveFraction(t) {
            this.$set({
                isActiveFraction: t
            }),
            hr()
        }
        get isVisible() {
            return this.$$.ctx[3]
        }
        set isVisible(t) {
            this.$set({
                isVisible: t
            }),
            hr()
        }
        get stores() {
            return this.$$.ctx[4]
        }
        set stores(t) {
            this.$set({
                stores: t
            }),
            hr()
        }
        get locale() {
            return this.$$.ctx[5]
        }
        set locale(t) {
            this.$set({
                locale: t
            }),
            hr()
        }
        get markupEditorToolbar() {
            return this.$$.ctx[6]
        }
        set markupEditorToolbar(t) {
            this.$set({
                markupEditorToolbar: t
            }),
            hr()
        }
        get markupEditorToolStyles() {
            return this.$$.ctx[7]
        }
        set markupEditorToolStyles(t) {
            this.$set({
                markupEditorToolStyles: t
            }),
            hr()
        }
        get markupEditorShapeStyleControls() {
            return this.$$.ctx[8]
        }
        set markupEditorShapeStyleControls(t) {
            this.$set({
                markupEditorShapeStyleControls: t
            }),
            hr()
        }
        get markupEditorToolSelectRadius() {
            return this.$$.ctx[9]
        }
        set markupEditorToolSelectRadius(t) {
            this.$set({
                markupEditorToolSelectRadius: t
            }),
            hr()
        }
        get markupEditorTextInputMode() {
            return this.$$.ctx[10]
        }
        set markupEditorTextInputMode(t) {
            this.$set({
                markupEditorTextInputMode: t
            }),
            hr()
        }
        get willRenderShapePresetToolbar() {
            return this.$$.ctx[11]
        }
        set willRenderShapePresetToolbar(t) {
            this.$set({
                willRenderShapePresetToolbar: t
            }),
            hr()
        }
        get annotateTools() {
            return this.$$.ctx[12]
        }
        set annotateTools(t) {
            this.$set({
                annotateTools: t
            }),
            hr()
        }
        get annotateToolShapes() {
            return this.$$.ctx[13]
        }
        set annotateToolShapes(t) {
            this.$set({
                annotateToolShapes: t
            }),
            hr()
        }
        get annotateShapeControls() {
            return this.$$.ctx[14]
        }
        set annotateShapeControls(t) {
            this.$set({
                annotateShapeControls: t
            }),
            hr()
        }
        get annotateActiveTool() {
            return this.$$.ctx[0]
        }
        set annotateActiveTool(t) {
            this.$set({
                annotateActiveTool: t
            }),
            hr()
        }
        get annotateEnableButtonFlipVertical() {
            return this.$$.ctx[15]
        }
        set annotateEnableButtonFlipVertical(t) {
            this.$set({
                annotateEnableButtonFlipVertical: t
            }),
            hr()
        }
        get annotateEnableSelectImagePreset() {
            return this.$$.ctx[16]
        }
        set annotateEnableSelectImagePreset(t) {
            this.$set({
                annotateEnableSelectImagePreset: t
            }),
            hr()
        }
        get annotatePresets() {
            return this.$$.ctx[17]
        }
        set annotatePresets(t) {
            this.$set({
                annotatePresets: t
            }),
            hr()
        }
        get annotateWillRenderShapePresetToolbar() {
            return this.$$.ctx[18]
        }
        set annotateWillRenderShapePresetToolbar(t) {
            this.$set({
                annotateWillRenderShapePresetToolbar: t
            }),
            hr()
        }
        get enableSelectToolToAddShape() {
            return this.$$.ctx[19]
        }
        set enableSelectToolToAddShape(t) {
            this.$set({
                enableSelectToolToAddShape: t
            }),
            hr()
        }
        get enableTapToAddText() {
            return this.$$.ctx[20]
        }
        set enableTapToAddText(t) {
            this.$set({
                enableTapToAddText: t
            }),
            hr()
        }
        get willRenderShapeControls() {
            return this.$$.ctx[21]
        }
        set willRenderShapeControls(t) {
            this.$set({
                willRenderShapeControls: t
            }),
            hr()
        }
        get beforeAddShape() {
            return this.$$.ctx[22]
        }
        set beforeAddShape(t) {
            this.$set({
                beforeAddShape: t
            }),
            hr()
        }
        get beforeRemoveShape() {
            return this.$$.ctx[23]
        }
        set beforeRemoveShape(t) {
            this.$set({
                beforeRemoveShape: t
            }),
            hr()
        }
        get beforeDeselectShape() {
            return this.$$.ctx[24]
        }
        set beforeDeselectShape(t) {
            this.$set({
                beforeDeselectShape: t
            }),
            hr()
        }
        get beforeSelectShape() {
            return this.$$.ctx[25]
        }
        set beforeSelectShape(t) {
            this.$set({
                beforeSelectShape: t
            }),
            hr()
        }
        get beforeUpdateShape() {
            return this.$$.ctx[26]
        }
        set beforeUpdateShape(t) {
            this.$set({
                beforeUpdateShape: t
            }),
            hr()
        }
    }
    ]
};
function Gf(t) {
    let e, o;
    return e = new Hf({
        props: {
            stores: t[3],
            locale: t[4],
            isActive: t[0],
            isActiveFraction: t[1],
            isVisible: t[2],
            mapScreenPointToImagePoint: t[32],
            mapImagePointToScreenPoint: t[33],
            utilKey: "sticker",
            shapePresets: t[5],
            shapes: t[6] ? t[25] : t[26],
            toolActive: "preset",
            imageFlipX: !!t[6] && t[18],
            imageFlipY: !!t[6] && t[19],
            imageRotation: t[6] ? t[20] : 0,
            parentRect: t[6] ? t[27] : t[23],
            enablePresetSelectImage: t[7],
            enableButtonFlipVertical: t[8],
            toolSelectRadius: t[11],
            willRenderPresetToolbar: t[9] || t[12],
            hooks: {
                willRenderShapeControls: t[10],
                beforeAddShape: t[13],
                beforeRemoveShape: t[14],
                beforeDeselectShape: t[15],
                beforeSelectShape: t[16],
                beforeUpdateShape: t[17]
            }
        }
    }),
    e.$on("measure", t[35]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            8 & o[0] && (i.stores = t[3]),
            16 & o[0] && (i.locale = t[4]),
            1 & o[0] && (i.isActive = t[0]),
            2 & o[0] && (i.isActiveFraction = t[1]),
            4 & o[0] && (i.isVisible = t[2]),
            32 & o[0] && (i.shapePresets = t[5]),
            64 & o[0] && (i.shapes = t[6] ? t[25] : t[26]),
            262208 & o[0] && (i.imageFlipX = !!t[6] && t[18]),
            524352 & o[0] && (i.imageFlipY = !!t[6] && t[19]),
            1048640 & o[0] && (i.imageRotation = t[6] ? t[20] : 0),
            64 & o[0] && (i.parentRect = t[6] ? t[27] : t[23]),
            128 & o[0] && (i.enablePresetSelectImage = t[7]),
            256 & o[0] && (i.enableButtonFlipVertical = t[8]),
            2048 & o[0] && (i.toolSelectRadius = t[11]),
            4608 & o[0] && (i.willRenderPresetToolbar = t[9] || t[12]),
            254976 & o[0] && (i.hooks = {
                willRenderShapeControls: t[10],
                beforeAddShape: t[13],
                beforeRemoveShape: t[14],
                beforeDeselectShape: t[15],
                beforeSelectShape: t[16],
                beforeUpdateShape: t[17]
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function qf(t, e, o) {
    let i, n, r, a, s, l, c, d;
    let {isActive: u} = e
      , {isActiveFraction: h} = e
      , {isVisible: p} = e
      , {stores: m} = e
      , {locale: g={}} = e
      , {stickers: f=[]} = e
      , {stickerStickToImage: $=!1} = e
      , {stickerEnableSelectImage: y=!0} = e
      , {stickersEnableButtonFlipVertical: x=!1} = e
      , {stickersWillRenderShapePresetToolbar: b} = e
      , {willRenderShapeControls: v} = e
      , {markupEditorToolSelectRadius: w} = e
      , {willRenderShapePresetToolbar: S} = e
      , {beforeAddShape: C} = e
      , {beforeRemoveShape: k} = e
      , {beforeDeselectShape: M} = e
      , {beforeSelectShape: T} = e
      , {beforeUpdateShape: R} = e;
    const {presentationScalar: P, rootRect: I, imageCropRect: A, imageSelectionRectPresentation: E, imageAnnotation: L, imageDecoration: F, imageSize: z, imageTransforms: B, imageRotation: O, imageFlipX: D, imageFlipY: _} = m;
    un(t, P, (t=>o(40, c = t))),
    un(t, I, (t=>o(36, i = t))),
    un(t, E, (t=>o(39, l = t))),
    un(t, z, (t=>o(37, n = t))),
    un(t, B, (t=>o(38, r = t))),
    un(t, O, (t=>o(20, d = t))),
    un(t, D, (t=>o(18, a = t))),
    un(t, _, (t=>o(19, s = t)));
    const W = $ ? t=>Uf(t, i, n, r.origin, r.translation, r.rotation.z, r.scale, a, s) : t=>{
        const e = Z(t);
        return e.x -= l.x,
        e.y -= l.y,
        e.x /= c,
        e.y /= c,
        e
    }
      , V = $ ? t=>Nf(t, i, n, r.origin, r.translation, r.rotation.z, r.scale, a, s) : t=>{
        const e = Z(t);
        return e.x *= c,
        e.y *= c,
        e.x += l.x,
        e.y += l.y,
        e
    }
    ;
    return t.$$set = t=>{
        "isActive"in t && o(0, u = t.isActive),
        "isActiveFraction"in t && o(1, h = t.isActiveFraction),
        "isVisible"in t && o(2, p = t.isVisible),
        "stores"in t && o(3, m = t.stores),
        "locale"in t && o(4, g = t.locale),
        "stickers"in t && o(5, f = t.stickers),
        "stickerStickToImage"in t && o(6, $ = t.stickerStickToImage),
        "stickerEnableSelectImage"in t && o(7, y = t.stickerEnableSelectImage),
        "stickersEnableButtonFlipVertical"in t && o(8, x = t.stickersEnableButtonFlipVertical),
        "stickersWillRenderShapePresetToolbar"in t && o(9, b = t.stickersWillRenderShapePresetToolbar),
        "willRenderShapeControls"in t && o(10, v = t.willRenderShapeControls),
        "markupEditorToolSelectRadius"in t && o(11, w = t.markupEditorToolSelectRadius),
        "willRenderShapePresetToolbar"in t && o(12, S = t.willRenderShapePresetToolbar),
        "beforeAddShape"in t && o(13, C = t.beforeAddShape),
        "beforeRemoveShape"in t && o(14, k = t.beforeRemoveShape),
        "beforeDeselectShape"in t && o(15, M = t.beforeDeselectShape),
        "beforeSelectShape"in t && o(16, T = t.beforeSelectShape),
        "beforeUpdateShape"in t && o(17, R = t.beforeUpdateShape)
    }
    ,
    [u, h, p, m, g, f, $, y, x, b, v, w, S, C, k, M, T, R, a, s, d, P, I, A, E, L, F, z, B, O, D, _, W, V, "sticker", function(e) {
        er(t, e)
    }
    ]
}
var Zf = {
    util: ["sticker", class extends Br {
        constructor(t) {
            super(),
            zr(this, t, qf, Gf, ln, {
                name: 34,
                isActive: 0,
                isActiveFraction: 1,
                isVisible: 2,
                stores: 3,
                locale: 4,
                stickers: 5,
                stickerStickToImage: 6,
                stickerEnableSelectImage: 7,
                stickersEnableButtonFlipVertical: 8,
                stickersWillRenderShapePresetToolbar: 9,
                willRenderShapeControls: 10,
                markupEditorToolSelectRadius: 11,
                willRenderShapePresetToolbar: 12,
                beforeAddShape: 13,
                beforeRemoveShape: 14,
                beforeDeselectShape: 15,
                beforeSelectShape: 16,
                beforeUpdateShape: 17
            }, [-1, -1])
        }
        get name() {
            return this.$$.ctx[34]
        }
        get isActive() {
            return this.$$.ctx[0]
        }
        set isActive(t) {
            this.$set({
                isActive: t
            }),
            hr()
        }
        get isActiveFraction() {
            return this.$$.ctx[1]
        }
        set isActiveFraction(t) {
            this.$set({
                isActiveFraction: t
            }),
            hr()
        }
        get isVisible() {
            return this.$$.ctx[2]
        }
        set isVisible(t) {
            this.$set({
                isVisible: t
            }),
            hr()
        }
        get stores() {
            return this.$$.ctx[3]
        }
        set stores(t) {
            this.$set({
                stores: t
            }),
            hr()
        }
        get locale() {
            return this.$$.ctx[4]
        }
        set locale(t) {
            this.$set({
                locale: t
            }),
            hr()
        }
        get stickers() {
            return this.$$.ctx[5]
        }
        set stickers(t) {
            this.$set({
                stickers: t
            }),
            hr()
        }
        get stickerStickToImage() {
            return this.$$.ctx[6]
        }
        set stickerStickToImage(t) {
            this.$set({
                stickerStickToImage: t
            }),
            hr()
        }
        get stickerEnableSelectImage() {
            return this.$$.ctx[7]
        }
        set stickerEnableSelectImage(t) {
            this.$set({
                stickerEnableSelectImage: t
            }),
            hr()
        }
        get stickersEnableButtonFlipVertical() {
            return this.$$.ctx[8]
        }
        set stickersEnableButtonFlipVertical(t) {
            this.$set({
                stickersEnableButtonFlipVertical: t
            }),
            hr()
        }
        get stickersWillRenderShapePresetToolbar() {
            return this.$$.ctx[9]
        }
        set stickersWillRenderShapePresetToolbar(t) {
            this.$set({
                stickersWillRenderShapePresetToolbar: t
            }),
            hr()
        }
        get willRenderShapeControls() {
            return this.$$.ctx[10]
        }
        set willRenderShapeControls(t) {
            this.$set({
                willRenderShapeControls: t
            }),
            hr()
        }
        get markupEditorToolSelectRadius() {
            return this.$$.ctx[11]
        }
        set markupEditorToolSelectRadius(t) {
            this.$set({
                markupEditorToolSelectRadius: t
            }),
            hr()
        }
        get willRenderShapePresetToolbar() {
            return this.$$.ctx[12]
        }
        set willRenderShapePresetToolbar(t) {
            this.$set({
                willRenderShapePresetToolbar: t
            }),
            hr()
        }
        get beforeAddShape() {
            return this.$$.ctx[13]
        }
        set beforeAddShape(t) {
            this.$set({
                beforeAddShape: t
            }),
            hr()
        }
        get beforeRemoveShape() {
            return this.$$.ctx[14]
        }
        set beforeRemoveShape(t) {
            this.$set({
                beforeRemoveShape: t
            }),
            hr()
        }
        get beforeDeselectShape() {
            return this.$$.ctx[15]
        }
        set beforeDeselectShape(t) {
            this.$set({
                beforeDeselectShape: t
            }),
            hr()
        }
        get beforeSelectShape() {
            return this.$$.ctx[16]
        }
        set beforeSelectShape(t) {
            this.$set({
                beforeSelectShape: t
            }),
            hr()
        }
        get beforeUpdateShape() {
            return this.$$.ctx[17]
        }
        set beforeUpdateShape(t) {
            this.$set({
                beforeUpdateShape: t
            }),
            hr()
        }
    }
    ]
};
function Kf(t) {
    let e, o, i, n, r, a = (t[13](t[29].value) || "") + "", s = (S(t[29].label) ? t[29].label(t[1]) : t[29].label) + "";
    return {
        c() {
            e = Rn("div"),
            i = An(),
            n = Rn("span"),
            r = In(s),
            o = new Vn(i),
            Bn(e, "slot", "option")
        },
        m(t, s) {
            Mn(t, e, s),
            o.m(a, e),
            kn(e, i),
            kn(e, n),
            kn(n, r)
        },
        p(t, e) {
            536870912 & e && a !== (a = (t[13](t[29].value) || "") + "") && o.p(a),
            536870914 & e && s !== (s = (S(t[29].label) ? t[29].label(t[1]) : t[29].label) + "") && Dn(r, s)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function Jf(t) {
    let e, o;
    return e = new nd({
        props: {
            locale: t[1],
            layout: "row",
            options: t[2],
            selectedIndex: t[10],
            onchange: t[11],
            $$slots: {
                option: [Kf, ({option: t})=>({
                    29: t
                }), ({option: t})=>t ? 536870912 : 0]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            2 & o && (i.locale = t[1]),
            4 & o && (i.options = t[2]),
            1610612738 & o && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function Qf(t) {
    let e, o, i, n, r;
    return o = new ef({
        props: {
            locale: t[1],
            shape: t[5],
            onchange: t[12],
            controls: t[3],
            scrollElasticity: t[4]
        }
    }),
    n = new ql({
        props: {
            elasticity: t[8],
            $$slots: {
                default: [Jf]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            e = Rn("div"),
            Ar(o.$$.fragment),
            i = An(),
            Ar(n.$$.fragment),
            Bn(e, "slot", "footer"),
            Bn(e, "style", t[6])
        },
        m(t, a) {
            Mn(t, e, a),
            Er(o, e, null),
            kn(e, i),
            Er(n, e, null),
            r = !0
        },
        p(t, i) {
            const a = {};
            2 & i && (a.locale = t[1]),
            32 & i && (a.shape = t[5]),
            8 & i && (a.controls = t[3]),
            16 & i && (a.scrollElasticity = t[4]),
            o.$set(a);
            const s = {};
            1073741830 & i && (s.$$scope = {
                dirty: i,
                ctx: t
            }),
            n.$set(s),
            (!r || 64 & i) && Bn(e, "style", t[6])
        },
        i(t) {
            r || (br(o.$$.fragment, t),
            br(n.$$.fragment, t),
            r = !0)
        },
        o(t) {
            vr(o.$$.fragment, t),
            vr(n.$$.fragment, t),
            r = !1
        },
        d(t) {
            t && Tn(e),
            Lr(o),
            Lr(n)
        }
    }
}
function t$(t) {
    let e, o;
    return e = new om({
        props: {
            $$slots: {
                footer: [Qf]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    e.$on("measure", t[21]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, [o]) {
            const i = {};
            1073741950 & o && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function e$(t, e, o) {
    let i, n, r, a, s, l, c = tn, d = ()=>(c(),
    c = cn(u, (t=>o(19, s = t))),
    u);
    t.$$.on_destroy.push((()=>c()));
    let {isActive: u} = e;
    d();
    let {stores: h} = e
      , {locale: p={}} = e
      , {frameStyles: m={}} = e
      , {frameOptions: g=[]} = e
      , {markupEditorShapeStyleControls: f} = e;
    const {history: $, animation: y, elasticityMultiplier: x, scrollElasticity: b, imageFrame: v} = h;
    un(t, y, (t=>o(18, a = t))),
    un(t, v, (t=>o(5, r = t)));
    let w = r ? g.findIndex((([t])=>t === r.id)) : 0
      , S = {
        frameColor: [1, 1, 1]
    };
    let C;
    const k = ls(a ? 20 : 0);
    return un(t, k, (t=>o(20, l = t))),
    t.$$set = t=>{
        "isActive"in t && d(o(0, u = t.isActive)),
        "stores"in t && o(16, h = t.stores),
        "locale"in t && o(1, p = t.locale),
        "frameStyles"in t && o(17, m = t.frameStyles),
        "frameOptions"in t && o(2, g = t.frameOptions),
        "markupEditorShapeStyleControls"in t && o(3, f = t.markupEditorShapeStyleControls)
    }
    ,
    t.$$.update = ()=>{
        786432 & t.$$.dirty && a && k.set(s ? 0 : 20),
        1048576 & t.$$.dirty && o(6, n = l ? `transform: translateY(${l}px)` : void 0)
    }
    ,
    o(4, i = x * b),
    [u, p, g, f, i, r, n, y, b, v, w, ({value: t})=>{
        const e = m[t];
        if (!e || !e.shape)
            return v.set(void 0),
            void $.write();
        const o = {
            id: t,
            ...S,
            ...Eo(e.shape)
        };
        v.set(o),
        $.write()
    }
    , function(t) {
        Ke(t, "frameColor") && (S.frameColor = t.frameColor),
        r && (Ri(r, t),
        v.set(r),
        clearTimeout(C),
        C = setTimeout((()=>{
            $.write()
        }
        ), 200))
    }
    , t=>{
        const e = m[t];
        var o;
        if (e && e.thumb)
            return o = e.thumb,
            /div/i.test(o) || uf(o) ? o : /rect|path|circle|line|<g>/i.test(o) ? `<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" stroke-width="1" stroke="currentColor" fill="none" aria-hidden="true" focusable="false" stroke-linecap="round" stroke-linejoin="round">${o}</svg>` : `<img src="${o}" alt=""/>`
    }
    , k, "frame", h, m, a, s, l, function(e) {
        er(t, e)
    }
    ]
}
var o$ = {
    util: ["frame", class extends Br {
        constructor(t) {
            super(),
            zr(this, t, e$, t$, ln, {
                name: 15,
                isActive: 0,
                stores: 16,
                locale: 1,
                frameStyles: 17,
                frameOptions: 2,
                markupEditorShapeStyleControls: 3
            })
        }
        get name() {
            return this.$$.ctx[15]
        }
        get isActive() {
            return this.$$.ctx[0]
        }
        set isActive(t) {
            this.$set({
                isActive: t
            }),
            hr()
        }
        get stores() {
            return this.$$.ctx[16]
        }
        set stores(t) {
            this.$set({
                stores: t
            }),
            hr()
        }
        get locale() {
            return this.$$.ctx[1]
        }
        set locale(t) {
            this.$set({
                locale: t
            }),
            hr()
        }
        get frameStyles() {
            return this.$$.ctx[17]
        }
        set frameStyles(t) {
            this.$set({
                frameStyles: t
            }),
            hr()
        }
        get frameOptions() {
            return this.$$.ctx[2]
        }
        set frameOptions(t) {
            this.$set({
                frameOptions: t
            }),
            hr()
        }
        get markupEditorShapeStyleControls() {
            return this.$$.ctx[3]
        }
        set markupEditorShapeStyleControls(t) {
            this.$set({
                markupEditorShapeStyleControls: t
            }),
            hr()
        }
    }
    ]
};
function i$(t) {
    let e, o, i, n, r, a, s, l;
    return {
        c() {
            e = Rn("div"),
            o = Rn("label"),
            i = In(t[1]),
            n = An(),
            r = Rn("input"),
            Bn(o, "for", t[0]),
            Bn(o, "title", t[2]),
            Bn(o, "aria-label", t[2]),
            Bn(r, "id", t[0]),
            Bn(r, "type", "text"),
            Bn(r, "inputmode", "numeric"),
            Bn(r, "pattern", "[0-9]*"),
            Bn(r, "data-state", t[3]),
            Bn(r, "autocomplete", "off"),
            Bn(r, "placeholder", t[4]),
            r.value = a = void 0 === t[5] ? "" : t[7](t[5] + ""),
            Bn(e, "class", "PinturaInputDimension")
        },
        m(a, c) {
            Mn(a, e, c),
            kn(e, o),
            kn(o, i),
            kn(e, n),
            kn(e, r),
            s || (l = Ln(r, "input", t[8]),
            s = !0)
        },
        p(t, [e]) {
            2 & e && Dn(i, t[1]),
            1 & e && Bn(o, "for", t[0]),
            4 & e && Bn(o, "title", t[2]),
            4 & e && Bn(o, "aria-label", t[2]),
            1 & e && Bn(r, "id", t[0]),
            8 & e && Bn(r, "data-state", t[3]),
            16 & e && Bn(r, "placeholder", t[4]),
            160 & e && a !== (a = void 0 === t[5] ? "" : t[7](t[5] + "")) && r.value !== a && (r.value = a)
        },
        i: tn,
        o: tn,
        d(t) {
            t && Tn(e),
            s = !1,
            l()
        }
    }
}
function n$(t, e, o) {
    let {id: i} = e
      , {label: n} = e
      , {title: r} = e
      , {state: a} = e
      , {placeholder: s} = e
      , {value: l} = e
      , {onchange: c} = e
      , {format: d=(t=>t.replace(/\D/g, ""))} = e;
    return t.$$set = t=>{
        "id"in t && o(0, i = t.id),
        "label"in t && o(1, n = t.label),
        "title"in t && o(2, r = t.title),
        "state"in t && o(3, a = t.state),
        "placeholder"in t && o(4, s = t.placeholder),
        "value"in t && o(5, l = t.value),
        "onchange"in t && o(6, c = t.onchange),
        "format"in t && o(7, d = t.format)
    }
    ,
    [i, n, r, a, s, l, c, d, t=>c(d(t.currentTarget.value))]
}
class r$ extends Br {
    constructor(t) {
        super(),
        zr(this, t, n$, i$, ln, {
            id: 0,
            label: 1,
            title: 2,
            state: 3,
            placeholder: 4,
            value: 5,
            onchange: 6,
            format: 7
        })
    }
}
function a$(t) {
    let e;
    return {
        c() {
            e = Pn("g")
        },
        m(o, i) {
            Mn(o, e, i),
            e.innerHTML = t[2]
        },
        p(t, o) {
            4 & o && (e.innerHTML = t[2])
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function s$(t) {
    let e, o, i, n, r, a, s, l;
    return r = new Al({
        props: {
            $$slots: {
                default: [a$]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            e = Rn("div"),
            o = Rn("input"),
            i = An(),
            n = Rn("label"),
            Ar(r.$$.fragment),
            Bn(o, "id", t[0]),
            Bn(o, "class", "implicit"),
            Bn(o, "type", "checkbox"),
            o.checked = t[1],
            Bn(n, "for", t[0]),
            Bn(n, "title", t[3])
        },
        m(c, d) {
            Mn(c, e, d),
            kn(e, o),
            kn(e, i),
            kn(e, n),
            Er(r, n, null),
            a = !0,
            s || (l = Ln(o, "change", t[5]),
            s = !0)
        },
        p(t, [e]) {
            (!a || 1 & e) && Bn(o, "id", t[0]),
            (!a || 2 & e) && (o.checked = t[1]);
            const i = {};
            68 & e && (i.$$scope = {
                dirty: e,
                ctx: t
            }),
            r.$set(i),
            (!a || 1 & e) && Bn(n, "for", t[0]),
            (!a || 8 & e) && Bn(n, "title", t[3])
        },
        i(t) {
            a || (br(r.$$.fragment, t),
            a = !0)
        },
        o(t) {
            vr(r.$$.fragment, t),
            a = !1
        },
        d(t) {
            t && Tn(e),
            Lr(r),
            s = !1,
            l()
        }
    }
}
function l$(t, e, o) {
    let {id: i} = e
      , {locked: n} = e
      , {icon: r} = e
      , {title: a} = e
      , {onchange: s} = e;
    return t.$$set = t=>{
        "id"in t && o(0, i = t.id),
        "locked"in t && o(1, n = t.locked),
        "icon"in t && o(2, r = t.icon),
        "title"in t && o(3, a = t.title),
        "onchange"in t && o(4, s = t.onchange)
    }
    ,
    [i, n, r, a, s, t=>s(t.currentTarget.checked)]
}
class c$ extends Br {
    constructor(t) {
        super(),
        zr(this, t, l$, s$, ln, {
            id: 0,
            locked: 1,
            icon: 2,
            title: 3,
            onchange: 4
        })
    }
}
function d$(t) {
    let e;
    return {
        c() {
            e = In("Save")
        },
        m(t, o) {
            Mn(t, e, o)
        },
        d(t) {
            t && Tn(e)
        }
    }
}
function u$(t) {
    let e, o, i, n, r, a, s, l, c, d, u, h, p, m = t[1].resizeLabelFormCaption + "";
    return l = new _d({
        props: {
            items: t[3]
        }
    }),
    d = new Dl({
        props: {
            type: "submit",
            class: "implicit",
            $$slots: {
                default: [d$]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    {
        c() {
            e = Rn("form"),
            o = Rn("div"),
            i = Rn("fieldset"),
            n = Rn("legend"),
            r = In(m),
            a = An(),
            s = Rn("div"),
            Ar(l.$$.fragment),
            c = An(),
            Ar(d.$$.fragment),
            Bn(n, "class", "implicit"),
            Bn(s, "class", "PinturaFieldsetInner"),
            Bn(o, "class", "PinturaFormInner"),
            Bn(e, "slot", "footer"),
            Bn(e, "style", t[4])
        },
        m(m, g) {
            Mn(m, e, g),
            kn(e, o),
            kn(o, i),
            kn(i, n),
            kn(n, r),
            kn(i, a),
            kn(i, s),
            Er(l, s, null),
            t[62](s),
            kn(o, c),
            Er(d, o, null),
            u = !0,
            h || (p = [Ln(s, "focusin", t[13]), Ln(s, "focusout", t[14]), Ln(e, "submit", Fn(t[15]))],
            h = !0)
        },
        p(t, o) {
            (!u || 2 & o[0]) && m !== (m = t[1].resizeLabelFormCaption + "") && Dn(r, m);
            const i = {};
            8 & o[0] && (i.items = t[3]),
            l.$set(i);
            const n = {};
            2097152 & o[2] && (n.$$scope = {
                dirty: o,
                ctx: t
            }),
            d.$set(n),
            (!u || 16 & o[0]) && Bn(e, "style", t[4])
        },
        i(t) {
            u || (br(l.$$.fragment, t),
            br(d.$$.fragment, t),
            u = !0)
        },
        o(t) {
            vr(l.$$.fragment, t),
            vr(d.$$.fragment, t),
            u = !1
        },
        d(o) {
            o && Tn(e),
            Lr(l),
            t[62](null),
            Lr(d),
            h = !1,
            an(p)
        }
    }
}
function h$(t) {
    let e, o;
    return e = new om({
        props: {
            $$slots: {
                footer: [u$]
            },
            $$scope: {
                ctx: t
            }
        }
    }),
    e.$on("measure", t[63]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, o) {
            const i = {};
            30 & o[0] | 2097152 & o[2] && (i.$$scope = {
                dirty: o,
                ctx: t
            }),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function p$(t, e, o) {
    let i, n, r, a, s, l, c, d, u, h, p, m, g, f, $, y, x, b, v, S, C, k, M, R, P, I, A, E, L = tn, F = ()=>(L(),
    L = cn(B, (t=>o(60, A = t))),
    B);
    t.$$.on_destroy.push((()=>L()));
    const z = (t,e=0,o=9999)=>{
        if (w(t) && !(t = t.replace(/\D/g, "")).length)
            return;
        const i = Math.round(t);
        return Number.isNaN(i) ? void 0 : na(i, e, o)
    }
    ;
    let {isActive: B} = e;
    F();
    let {stores: O} = e
      , {locale: W={}} = e
      , {resizeMinSize: V=xt(1, 1)} = e
      , {resizeMaxSize: H=xt(9999, 9999)} = e
      , {resizeSizePresetOptions: N} = e
      , {resizeWidthPresetOptions: U} = e
      , {resizeHeightPresetOptions: j} = e
      , {resizeWillRenderFooter: X=_} = e;
    const Y = ls(0, {
        stiffness: .15,
        damping: .3
    });
    un(t, Y, (t=>o(57, R = t)));
    const {animation: G, imageSize: q, imageCropRect: Z, imageCropRectAspectRatio: K, imageCropAspectRatio: J, imageOutputSize: Q, history: tt, env: et} = O;
    un(t, G, (t=>o(59, I = t))),
    un(t, q, (t=>o(69, g = t))),
    un(t, Z, (t=>o(52, M = t))),
    un(t, K, (t=>o(39, h = t))),
    un(t, J, (t=>o(68, m = t))),
    un(t, Q, (t=>o(67, p = t))),
    un(t, et, (t=>o(58, P = t)));
    const ot = T();
    let it, nt, rt, at, st, lt = !1;
    const ct = (t,e,o,i,n)=>null != t && o !== e ? t >= i[e] && t <= n[e] ? "valid" : "invalid" : "undetermined"
      , dt = (t,e,o)=>Math.round(null != t ? t / e : o.height)
      , ut = ()=>{
        lt && nt && rt && ("width" === at ? o(36, rt = Math.round(nt / h)) : "height" === at ? o(35, nt = Math.round(rt * h)) : ("width" === st ? o(36, rt = Math.round(nt / h)) : "height" === st && o(35, nt = Math.round(rt * h)),
        ht()))
    }
      , ht = t=>{
        let e = z(nt)
          , i = z(rt)
          , n = e
          , r = i
          , a = n && r
          , s = t || h;
        if (!n && !r)
            return;
        n && !r ? r = Math.round(n / s) : r && !n && (n = Math.round(r * s)),
        s = t || a ? D(n, r) : h;
        let l = xt(n, r);
        Ct(H, l) || (l = Jt(H, s)),
        Ct(l, V) || (l = Kt(V, s)),
        o(35, nt = null != e ? Math.round(l.width) : void 0),
        o(36, rt = null != i ? Math.round(l.height) : void 0)
    }
      , pt = ()=>{
        ht();
        const {width: t, height: e} = p || {};
        t === nt && e === rt || (nt || rt ? (nt && rt && $n(J, m = nt / rt, m),
        $n(Q, p = xt(nt, rt), p)) : ($n(J, m = g.width / g.height, m),
        $n(J, m = void 0, m),
        $n(Q, p = void 0, p)),
        tt.write())
    }
      , mt = Q.subscribe((t=>{
        if (!t)
            return o(35, nt = void 0),
            void o(36, rt = void 0);
        o(35, nt = t.width),
        o(36, rt = t.height),
        ht()
    }
    ))
      , gt = J.subscribe((t=>{
        (nt || rt) && t && (nt && rt && D(nt, rt) !== t ? (o(36, rt = nt / t),
        ht(t)) : ht())
    }
    ))
      , ft = t=>w(t[0]) ? (t[1] = t[1].map(ft),
    t) : qe(t) ? [t, "" + t] : t
      , yt = t=>{
        if (w(t[0]))
            return t[1] = t[1].map(yt),
            t;
        let[e,o] = t;
        if (qe(e) && qe(o)) {
            const [t,i] = [e, o];
            o = `${t} × ${i}`,
            e = [t, i]
        }
        return [e, o]
    }
      , bt = _r();
    un(t, bt, (t=>o(40, f = t)));
    const vt = _r();
    un(t, vt, (t=>o(41, $ = t)));
    const wt = _r();
    un(t, wt, (t=>o(42, y = t)));
    const St = _r();
    un(t, St, (t=>o(43, x = t)));
    const kt = _r();
    un(t, kt, (t=>o(44, b = t)));
    const Mt = _r();
    un(t, Mt, (t=>o(45, v = t)));
    const Tt = Wr([Q, vt], (([t,e],o)=>{
        if (!e)
            return o(-1);
        const i = e.findIndex((([e])=>{
            if (!e && !t)
                return !0;
            if (!e)
                return !1;
            const [o,i] = e;
            return t.width === o && t.height === i
        }
        ));
        o(i < 0 ? 0 : i)
    }
    ));
    un(t, Tt, (t=>o(47, S = t)));
    const Rt = Wr([Q, St], (([t,e],o)=>{
        if (!e)
            return o(-1);
        const i = e.findIndex((([e])=>!e && !t || !!e && t.width === e));
        o(i < 0 ? 0 : i)
    }
    ));
    un(t, Rt, (t=>o(49, C = t)));
    const Pt = Wr([Q, Mt], (([t,e],o)=>{
        if (!e)
            return o(-1);
        const i = e.findIndex((([e])=>!e && !t || !!e && t.height === e));
        o(i < 0 ? 0 : i)
    }
    ));
    un(t, Pt, (t=>o(51, k = t)));
    let It = void 0
      , At = void 0;
    let Et = {};
    const Lt = ls(I ? 20 : 0);
    return un(t, Lt, (t=>o(61, E = t))),
    Kn((()=>{
        mt(),
        gt()
    }
    )),
    t.$$set = t=>{
        "isActive"in t && F(o(0, B = t.isActive)),
        "stores"in t && o(27, O = t.stores),
        "locale"in t && o(1, W = t.locale),
        "resizeMinSize"in t && o(28, V = t.resizeMinSize),
        "resizeMaxSize"in t && o(29, H = t.resizeMaxSize),
        "resizeSizePresetOptions"in t && o(30, N = t.resizeSizePresetOptions),
        "resizeWidthPresetOptions"in t && o(31, U = t.resizeWidthPresetOptions),
        "resizeHeightPresetOptions"in t && o(32, j = t.resizeHeightPresetOptions),
        "resizeWillRenderFooter"in t && o(33, X = t.resizeWillRenderFooter)
    }
    ,
    t.$$.update = ()=>{
        var e, g, T;
        1073741824 & t.$$.dirty[0] | 512 & t.$$.dirty[1] && N && ($n(bt, f = N.map(yt), f),
        $n(vt, $ = Tc(f), $)),
        512 & t.$$.dirty[1] && o(53, a = !!f),
        66560 & t.$$.dirty[1] && o(46, i = S > -1 && $[S][1]),
        2049 & t.$$.dirty[1] && U && ($n(wt, y = U.map(ft), y),
        $n(St, x = Tc(y), x)),
        4196352 & t.$$.dirty[1] && o(54, s = !a && y),
        266240 & t.$$.dirty[1] && o(48, n = C > -1 && x[C][1]),
        8194 & t.$$.dirty[1] && j && ($n(kt, b = j.map(ft), b),
        $n(Mt, v = Tc(b), v)),
        4202496 & t.$$.dirty[1] && o(55, l = !a && b),
        1064960 & t.$$.dirty[1] && o(50, r = k > -1 && v[k][1]),
        29360128 & t.$$.dirty[1] && o(56, c = !a && !s && !l),
        805306370 & t.$$.dirty[0] | 268413948 & t.$$.dirty[1] && o(3, d = Et && X([a && ["Dropdown", "size-presets", {
            label: i,
            options: f,
            onchange: t=>{
                return (e = t.value) && !It && (It = {
                    ...M
                },
                At = m),
                e ? ($n(J, m = D(e[0], e[1]), m),
                $n(Q, p = $t(e), p)) : ($n(Z, M = It, M),
                $n(J, m = At, m),
                $n(Q, p = void 0, p),
                It = void 0,
                At = void 0),
                void tt.write();
                var e
            }
            ,
            selectedIndex: S
        }], s && ["Dropdown", "width-presets", {
            label: n,
            options: y,
            onchange: t=>{
                o(35, nt = t.value),
                pt()
            }
            ,
            selectedIndex: C
        }], s && l && ["span", "times", {
            class: "PinturaResizeLabel",
            innerHTML: "&times;"
        }], l && ["Dropdown", "height-presets", {
            label: r,
            options: b,
            onchange: t=>{
                o(36, rt = t.value),
                pt()
            }
            ,
            selectedIndex: k
        }], c && [r$, "width-input", {
            id: "width-" + ot,
            title: W.resizeTitleInputWidth,
            label: W.resizeLabelInputWidth,
            placeholder: (e = z(rt),
            g = h,
            T = M,
            Math.round(null != e ? e * g : T.width)),
            value: nt,
            state: ct(z(nt), "width", at, V, H),
            onchange: t=>{
                o(35, nt = t),
                ut()
            }
        }], c && [c$, "aspect-ratio-lock", {
            id: "aspect-ratio-lock-" + ot,
            title: W.resizeTitleButtonMaintainAspectRatio,
            icon: w(W.resizeIconButtonMaintainAspectRatio) ? W.resizeIconButtonMaintainAspectRatio : W.resizeIconButtonMaintainAspectRatio(lt, R),
            locked: lt,
            onchange: t=>{
                o(34, lt = t),
                ut()
            }
        }], c && [r$, "height-input", {
            id: "height-" + ot,
            title: W.resizeTitleInputHeight,
            label: W.resizeLabelInputHeight,
            placeholder: dt(z(nt), h, M),
            value: rt,
            state: ct(z(rt), "height", at, V, H),
            onchange: t=>{
                o(36, rt = t),
                ut()
            }
        }]].filter(Boolean), {
            ...P
        }, (()=>o(38, Et = {}))).filter(Boolean)),
        8 & t.$$.dirty[1] && Y.set(lt ? 1 : 0),
        64 & t.$$.dirty[1] && at && (st = at),
        805306368 & t.$$.dirty[1] && I && Lt.set(A ? 0 : 20),
        1073741824 & t.$$.dirty[1] && o(4, u = E ? `transform: translateY(${E}px)` : void 0)
    }
    ,
    [B, W, it, d, u, Y, G, q, Z, K, J, Q, et, t=>{
        const e = t.target.id;
        /width/.test(e) ? o(37, at = "width") : /height/.test(e) ? o(37, at = "height") : /aspectRatio/i.test(e) ? o(37, at = "lock") : o(37, at = void 0)
    }
    , t=>{
        it.contains(t.relatedTarget) || pt(),
        o(37, at = void 0)
    }
    , pt, bt, vt, wt, St, kt, Mt, Tt, Rt, Pt, Lt, "resize", O, V, H, N, U, j, X, lt, nt, rt, at, Et, h, f, $, y, x, b, v, i, S, n, C, r, k, M, a, s, l, c, R, P, I, A, E, function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            it = t,
            o(2, it)
        }
        ))
    }
    , function(e) {
        er(t, e)
    }
    ]
}
var m$ = {
    util: ["resize", class extends Br {
        constructor(t) {
            super(),
            zr(this, t, p$, h$, ln, {
                name: 26,
                isActive: 0,
                stores: 27,
                locale: 1,
                resizeMinSize: 28,
                resizeMaxSize: 29,
                resizeSizePresetOptions: 30,
                resizeWidthPresetOptions: 31,
                resizeHeightPresetOptions: 32,
                resizeWillRenderFooter: 33
            }, [-1, -1, -1])
        }
        get name() {
            return this.$$.ctx[26]
        }
        get isActive() {
            return this.$$.ctx[0]
        }
        set isActive(t) {
            this.$set({
                isActive: t
            }),
            hr()
        }
        get stores() {
            return this.$$.ctx[27]
        }
        set stores(t) {
            this.$set({
                stores: t
            }),
            hr()
        }
        get locale() {
            return this.$$.ctx[1]
        }
        set locale(t) {
            this.$set({
                locale: t
            }),
            hr()
        }
        get resizeMinSize() {
            return this.$$.ctx[28]
        }
        set resizeMinSize(t) {
            this.$set({
                resizeMinSize: t
            }),
            hr()
        }
        get resizeMaxSize() {
            return this.$$.ctx[29]
        }
        set resizeMaxSize(t) {
            this.$set({
                resizeMaxSize: t
            }),
            hr()
        }
        get resizeSizePresetOptions() {
            return this.$$.ctx[30]
        }
        set resizeSizePresetOptions(t) {
            this.$set({
                resizeSizePresetOptions: t
            }),
            hr()
        }
        get resizeWidthPresetOptions() {
            return this.$$.ctx[31]
        }
        set resizeWidthPresetOptions(t) {
            this.$set({
                resizeWidthPresetOptions: t
            }),
            hr()
        }
        get resizeHeightPresetOptions() {
            return this.$$.ctx[32]
        }
        set resizeHeightPresetOptions(t) {
            this.$set({
                resizeHeightPresetOptions: t
            }),
            hr()
        }
        get resizeWillRenderFooter() {
            return this.$$.ctx[33]
        }
        set resizeWillRenderFooter(t) {
            this.$set({
                resizeWillRenderFooter: t
            }),
            hr()
        }
    }
    ]
};
function g$(t) {
    let e, o;
    return e = new Hf({
        props: {
            stores: t[3],
            locale: t[4],
            isActive: t[0],
            isActiveFraction: t[1],
            isVisible: t[2],
            mapScreenPointToImagePoint: t[7],
            mapImagePointToScreenPoint: t[8],
            utilKey: "redact",
            imageRotation: t[9],
            imageFlipX: t[5],
            imageFlipY: t[6],
            shapes: t[10],
            tools: ["rect"],
            toolShapes: {
                rectangle: [{
                    x: 0,
                    y: 0,
                    width: 0,
                    height: 0
                }]
            },
            toolActive: "rectangle",
            parentRect: t[12],
            enablePresetDropImage: !1,
            enablePresetSelectImage: !1,
            hooks: {
                willRenderShapeControls: t[21]
            }
        }
    }),
    e.$on("measure", t[22]),
    {
        c() {
            Ar(e.$$.fragment)
        },
        m(t, i) {
            Er(e, t, i),
            o = !0
        },
        p(t, [o]) {
            const i = {};
            8 & o && (i.stores = t[3]),
            16 & o && (i.locale = t[4]),
            1 & o && (i.isActive = t[0]),
            2 & o && (i.isActiveFraction = t[1]),
            4 & o && (i.isVisible = t[2]),
            128 & o && (i.mapScreenPointToImagePoint = t[7]),
            256 & o && (i.mapImagePointToScreenPoint = t[8]),
            512 & o && (i.imageRotation = t[9]),
            32 & o && (i.imageFlipX = t[5]),
            64 & o && (i.imageFlipY = t[6]),
            e.$set(i)
        },
        i(t) {
            o || (br(e.$$.fragment, t),
            o = !0)
        },
        o(t) {
            vr(e.$$.fragment, t),
            o = !1
        },
        d(t) {
            Lr(e, t)
        }
    }
}
function f$(t, e, o) {
    let i, n, r, a, s, l, c, d;
    let {isActive: u} = e
      , {isActiveFraction: h} = e
      , {isVisible: p} = e
      , {stores: m} = e
      , {locale: g={}} = e;
    const {imageRedaction: f, rootRect: $, imageSize: y, imageTransforms: x, imageRotation: b, imageFlipX: v, imageFlipY: w} = m;
    un(t, $, (t=>o(18, r = t))),
    un(t, y, (t=>o(19, a = t))),
    un(t, x, (t=>o(20, s = t))),
    un(t, b, (t=>o(9, d = t))),
    un(t, v, (t=>o(5, l = t))),
    un(t, w, (t=>o(6, c = t)));
    return t.$$set = t=>{
        "isActive"in t && o(0, u = t.isActive),
        "isActiveFraction"in t && o(1, h = t.isActiveFraction),
        "isVisible"in t && o(2, p = t.isVisible),
        "stores"in t && o(3, m = t.stores),
        "locale"in t && o(4, g = t.locale)
    }
    ,
    t.$$.update = ()=>{
        1835104 & t.$$.dirty && o(7, i = t=>Uf(t, r, a, s.origin, s.translation, s.rotation.z, s.scale, l, c)),
        1835104 & t.$$.dirty && o(8, n = t=>Nf(t, r, a, s.origin, s.translation, s.rotation.z, s.scale, l, c))
    }
    ,
    [u, h, p, m, g, l, c, i, n, d, f, $, y, x, b, v, w, "redact", r, a, s, t=>{
        const e = Ou(t[0]);
        return Nu("to-front", e),
        t
    }
    , function(e) {
        er(t, e)
    }
    ]
}
var $$ = {
    util: ["redact", class extends Br {
        constructor(t) {
            super(),
            zr(this, t, f$, g$, ln, {
                name: 17,
                isActive: 0,
                isActiveFraction: 1,
                isVisible: 2,
                stores: 3,
                locale: 4
            })
        }
        get name() {
            return this.$$.ctx[17]
        }
        get isActive() {
            return this.$$.ctx[0]
        }
        set isActive(t) {
            this.$set({
                isActive: t
            }),
            hr()
        }
        get isActiveFraction() {
            return this.$$.ctx[1]
        }
        set isActiveFraction(t) {
            this.$set({
                isActiveFraction: t
            }),
            hr()
        }
        get isVisible() {
            return this.$$.ctx[2]
        }
        set isVisible(t) {
            this.$set({
                isVisible: t
            }),
            hr()
        }
        get stores() {
            return this.$$.ctx[3]
        }
        set stores(t) {
            this.$set({
                stores: t
            }),
            hr()
        }
        get locale() {
            return this.$$.ctx[4]
        }
        set locale(t) {
            this.$set({
                locale: t
            }),
            hr()
        }
    }
    ]
};
const y$ = '<g fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" stroke-width=".125em"><path d="M18 6L6 18M6 6l12 12"></path></path></g>'
  , x$ = '<path fill="none" d="M9 15 L12 9 L15 15 M10 13.5 h3" stroke="currentColor" stroke-width=".125em"/>';
var b$ = {
    labelReset: "Reset",
    labelDefault: "Default",
    labelAuto: "Auto",
    labelNone: "None",
    labelEdit: "Edit",
    labelClose: "Close",
    labelSupportError: t=>t.join(", ") + " not supported on this browser",
    labelColor: "Color",
    labelWidth: "Width",
    labelSize: "Size",
    labelOffset: "Offset",
    labelAmount: "Amount",
    labelInset: "Inset",
    labelRadius: "Radius",
    labelSizeExtraSmall: "Extra small",
    labelSizeSmall: "Small",
    labelSizeMediumSmall: "Medium small",
    labelSizeMedium: "Medium",
    labelSizeMediumLarge: "Medium large",
    labelSizeLarge: "Large",
    labelSizeExtraLarge: "Extra large",
    labelButtonRevert: "Revert",
    labelButtonCancel: "Cancel",
    labelButtonUndo: "Undo",
    labelButtonRedo: "Redo",
    labelButtonExport: "Done",
    iconSupportError: '<g fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><g><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></g>',
    iconButtonClose: y$,
    iconButtonRevert: '<g fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" stroke-width=".125em"><path d="M7.388 18.538a8 8 0 10-2.992-9.03"/><path fill="currentColor" d="M2.794 11.696L2.37 6.714l5.088 3.18z"/><path d="M12 8v4M12 12l4 2"/></g>',
    iconButtonUndo: '<g fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" stroke-width=".125em"><path d="M10 8h4c2.485 0 5 2 5 5s-2.515 5-5 5h-4"/><path fill="currentColor" d="M5 8l4-3v6z"/></g>',
    iconButtonRedo: '<g fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" stroke-width=".125em"><path d="M14 8h-4c-2.485 0-5 2-5 5s2.515 5 5 5h4"/><path fill="currentColor" d="M19 8l-4-3v6z"/></g>',
    iconButtonExport: '<polyline points="20 6 9 17 4 12" fill="none" stroke="currentColor" stroke-width=".125em"></polyline>',
    statusLabelButtonClose: "Close",
    statusIconButtonClose: y$,
    statusLabelLoadImage: t=>t && t.task ? t.error ? "IMAGE_TOO_SMALL" === t.error.code ? "Minimum image size is {minWidth} × {minHeight}" : "Error loading image" : "blob-to-bitmap" === t.task ? "Creating preview…" : "Loading image…" : "Waiting for image",
    statusLabelProcessImage: t=>{
        if (t && t.task)
            return "store" === t.task ? t.error ? "Error uploading image" : "Uploading image…" : t.error ? "Error processing image" : "Processing image…"
    }
};
const v$ = {
    shapeLabelButtonSelectSticker: "Select image",
    shapeIconButtonSelectSticker: '<g fill="none" stroke="currentColor" stroke-width="0.0625em"><path d="M8 21 L15 11 L19 15"/><path d="M15 2 v5 h5"/><path d="M8 2 h8 l4 4 v12 q0 4 -4 4 h-8 q-4 0 -4 -4 v-12 q0 -4 4 -4z"/></g><circle fill="currentColor" cx="10" cy="8" r="1.5"/>',
    shapeIconButtonFlipHorizontal: '<g stroke="currentColor" stroke-width=".125em"><path fill="none" d="M6 6.5h5v11H6z"/><path fill="currentColor" d="M15 6.5h3v11h-3z"/><path d="M11 4v16" fill="currentColor"/></g>',
    shapeIconButtonFlipVertical: '<g stroke="currentColor" stroke-width=".125em"><rect x="7" y="8" width="11" height="5" fill="none"/><rect x="7" y="17" width="11" height="2" fill="currentColor"/><line x1="5" y1="13" x2="20" y2="13"/></g>',
    shapeIconButtonRemove: '<g fill="none" fill-rule="evenodd"><path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M7.5 7h9z"/><path d="M7.916 9h8.168a1 1 0 01.99 1.14l-.972 6.862a2 2 0 01-1.473 1.653c-.877.23-1.753.345-2.629.345-.876 0-1.752-.115-2.628-.345a2 2 0 01-1.473-1.653l-.973-6.862A1 1 0 017.916 9z" fill="currentColor"/><rect fill="currentColor" x="10" y="5" width="4" height="3" rx="1"/></g>',
    shapeIconButtonDuplicate: '<g fill="none" fill-rule="evenodd"><path d="M15 13.994V16a2 2 0 01-2 2H8a2 2 0 01-2-2v-5a2 2 0 012-2h2.142" stroke="currentColor" stroke-width=".125em"/><path d="M15 9V8a1 1 0 00-2 0v1h-1a1 1 0 000 2h1v1a1 1 0 002 0v-1h1a1 1 0 000-2h-1zm-4-4h6a2 2 0 012 2v6a2 2 0 01-2 2h-6a2 2 0 01-2-2V7a2 2 0 012-2z" fill="currentColor"/></g>',
    shapeIconButtonMoveToFront: '<g fill="none" fill-rule="evenodd"><rect fill="currentColor" x="11" y="13" width="8" height="2" rx="1"/><rect fill="currentColor" x="9" y="17" width="10" height="2" rx="1"/><path d="M11.364 8H10a5 5 0 000 10M12 6.5L14.5 8 12 9.5z" stroke="currentColor" stroke-width=".125em" stroke-linecap="round"/></g>',
    shapeIconButtonTextLayoutAutoWidth: "" + x$,
    shapeIconButtonTextLayoutAutoHeight: '<g fill="currentColor"><circle cx="4" cy="12" r="1.5"/><circle cx="20" cy="12" r="1.5"/></g>' + x$,
    shapeIconButtonTextLayoutFixedSize: '<g fill="currentColor"><circle cx="5" cy="6" r="1.5"/><circle cx="19" cy="6" r="1.5"/><circle cx="19" cy="19" r="1.5"/><circle cx="5" cy="19" r="1.5"/></g>' + x$,
    shapeTitleButtonTextLayoutAutoWidth: "Auto width",
    shapeTitleButtonTextLayoutAutoHeight: "Auto height",
    shapeTitleButtonTextLayoutFixedSize: "Fixed size",
    shapeTitleButtonFlipHorizontal: "Flip Horizontal",
    shapeTitleButtonFlipVertical: "Flip Vertical",
    shapeTitleButtonRemove: "Remove",
    shapeTitleButtonDuplicate: "Duplicate",
    shapeTitleButtonMoveToFront: "Move to front",
    shapeLabelInputText: "Edit text",
    shapeIconInputCancel: '<g fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" stroke-width=".125em"><path d="M18 6L6 18M6 6l12 12"/></g>',
    shapeIconInputConfirm: '<g fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" stroke-width=".125em"><polyline points="20 6 9 17 4 12"/></g>',
    shapeLabelInputCancel: "Cancel",
    shapeLabelInputConfirm: "Confirm",
    shapeLabelStrokeNone: "No outline",
    shapeLabelFontStyleNormal: "Normal",
    shapeLabelFontStyleBold: "Bold",
    shapeLabelFontStyleItalic: "Italic",
    shapeLabelFontStyleItalicBold: "Bold Italic",
    shapeTitleBackgroundColor: "Fill color",
    shapeTitleFontFamily: "Font",
    shapeTitleFontSize: "Font size",
    shapeTitleFontStyle: "Font style",
    shapeTitleLineHeight: "Line height",
    shapeTitleLineStart: "Start",
    shapeTitleLineEnd: "End",
    shapeTitleStrokeWidth: "Line width",
    shapeTitleStrokeColor: "Line color",
    shapeTitleLineDecorationBar: "Bar",
    shapeTitleLineDecorationCircle: "Circle",
    shapeTitleLineDecorationSquare: "Square",
    shapeTitleLineDecorationArrow: "Arrow",
    shapeTitleLineDecorationCircleSolid: "Circle solid",
    shapeTitleLineDecorationSquareSolid: "Square solid",
    shapeTitleLineDecorationArrowSolid: "Arrow solid",
    shapeIconLineDecorationBar: '<g stroke="currentColor" stroke-linecap="round" stroke-width=".125em"><path d="M5,12 H16"/><path d="M16,8 V16"/></g>',
    shapeIconLineDecorationCircle: '<g stroke="currentColor" stroke-linecap="round"><path stroke-width=".125em" d="M5,12 H12"/><circle fill="none" stroke-width=".125em" cx="16" cy="12" r="4"/></g>',
    shapeIconLineDecorationSquare: '<g stroke="currentColor" stroke-linecap="round"><path stroke-width=".125em" d="M5,12 H12"/><rect fill="none" stroke-width=".125em" x="12" y="8" width="8" height="8"/></g>',
    shapeIconLineDecorationArrow: '<g stroke="currentColor" stroke-linecap="round" stroke-width=".125em"><path d="M5,12 H16 M13,7 l6,5 l-6,5" fill="none"/></g>',
    shapeIconLineDecorationCircleSolid: '<g stroke="currentColor" stroke-linecap="round"><path stroke-width=".125em" d="M5,12 H12"/><circle fill="currentColor" cx="16" cy="12" r="4"/></g>',
    shapeIconLineDecorationSquareSolid: '<g stroke="currentColor" stroke-linecap="round"><path stroke-width=".125em" d="M5,12 H12"/><rect fill="currentColor" x="12" y="8" width="8" height="8"/></g>',
    shapeIconLineDecorationArrowSolid: '<g stroke="currentColor" stroke-linecap="round" stroke-width=".125em"><path d="M5,12 H16"/><path d="M13,7 l6,5 l-6,5z" fill="currentColor"/></g>',
    shapeTitleColorTransparent: "Transparent",
    shapeTitleColorWhite: "White",
    shapeTitleColorSilver: "Silver",
    shapeTitleColorGray: "Gray",
    shapeTitleColorBlack: "Black",
    shapeTitleColorNavy: "Navy",
    shapeTitleColorBlue: "Blue",
    shapeTitleColorAqua: "Aqua",
    shapeTitleColorTeal: "Teal",
    shapeTitleColorOlive: "Olive",
    shapeTitleColorGreen: "Green",
    shapeTitleColorYellow: "Yellow",
    shapeTitleColorOrange: "Orange",
    shapeTitleColorRed: "Red",
    shapeTitleColorMaroon: "Maroon",
    shapeTitleColorFuchsia: "Fuchsia",
    shapeTitleColorPurple: "Purple",
    shapeTitleTextColor: "Font color",
    shapeTitleTextAlign: "Text align",
    shapeTitleTextAlignLeft: "Left align text",
    shapeTitleTextAlignCenter: "Center align text",
    shapeTitleTextAlignRight: "Right align text",
    shapeIconTextAlignLeft: '<g stroke-width=".125em" stroke="currentColor"><line x1="5" y1="8" x2="15" y2="8"/><line x1="5" y1="12" x2="19" y2="12"/><line x1="5" y1="16" x2="14" y2="16"/></g>',
    shapeIconTextAlignCenter: '<g stroke-width=".125em" stroke="currentColor"><line x1="7" y1="8" x2="17" y2="8"/><line x1="5" y1="12" x2="19" y2="12"/><line x1="8" y1="16" x2="16" y2="16"/></g>',
    shapeIconTextAlignRight: '<g stroke-width=".125em" stroke="currentColor"><line x1="9" y1="8" x2="19" y2="8"/><line x1="5" y1="12" x2="19" y2="12"/><line x1="11" y1="16" x2="19" y2="16"/></g>',
    shapeLabelToolSharpie: "Sharpie",
    shapeLabelToolEraser: "Eraser",
    shapeLabelToolRectangle: "Rectangle",
    shapeLabelToolEllipse: "Ellipse",
    shapeLabelToolArrow: "Arrow",
    shapeLabelToolLine: "Line",
    shapeLabelToolText: "Text",
    shapeLabelToolPreset: "Stickers",
    shapeIconToolSharpie: '<g stroke-width=".125em" stroke="currentColor" fill="none"><path d="M2.025 5c5.616-2.732 8.833-3.857 9.65-3.374C12.903 2.351.518 12.666 2.026 14 3.534 15.334 16.536.566 17.73 2.566 18.924 4.566 3.98 17.187 4.831 18c.851.813 9.848-6 11.643-6 1.087 0-2.53 5.11-2.92 7-.086.41 3.323-1.498 4.773-1 .494.17.64 2.317 1.319 3 .439.443 1.332.776 2.679 1" stroke="currentColor" stroke-width=".125em" fill="none" fill-rule="evenodd" stroke-linejoin="round"/></g>',
    shapeIconToolEraser: '<g stroke-width=".125em" stroke="currentColor" stroke-linecap="round" fill="none"><g transform="translate(3, 15) rotate(-45)"><rect x="0" y="0" width="18" height="10" rx="3"/></g><line x1="11" y1="21" x2="18" y2="21"/><line x1="20" y1="21" x2="22" y2="21"/></g>',
    shapeIconToolRectangle: '<g stroke-width=".125em" stroke="currentColor" fill="none"><rect x="2" y="2" width="20" height="20" rx="3"/></g>',
    shapeIconToolEllipse: '<g stroke-width=".125em" stroke="currentColor" fill="none"><circle cx="12" cy="12" r="11"/></g>',
    shapeIconToolArrow: '<g stroke-width=".125em" stroke="currentColor" fill="none"><line x1="20" y1="3" x2="6" y2="21"/><path d="m10 5 L22 1 L21 13" fill="currentColor" stroke="none"/></g>',
    shapeIconToolLine: '<g stroke-width=".125em" stroke="currentColor" fill="none"><line x1="20" y1="3" x2="6" y2="21"/></g>',
    shapeIconToolText: '<g stroke="none" fill="currentColor" transform="translate(6,0)"><path d="M8.14 20.085c.459 0 .901-.034 1.329-.102a8.597 8.597 0 001.015-.21v1.984c-.281.135-.695.247-1.242.336a9.328 9.328 0 01-1.477.133c-3.312 0-4.968-1.745-4.968-5.235V6.804H.344v-1.25l2.453-1.078L3.89.819h1.5v3.97h4.97v2.015H5.39v10.078c0 1.031.245 1.823.735 2.375s1.161.828 2.015.828z"/>',
    shapeIconToolPreset: '<g fill="none" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" stroke-width=".125em"><path d="M12 22c2.773 0 1.189-5.177 3-7 1.796-1.808 7-.25 7-3 0-5.523-4.477-10-10-10S2 6.477 2 12s4.477 10 10 10z"></path><path d="M20 17c-3 3-5 5-8 5"></path></g>'
};
var w$ = {
    cropLabel: "Crop",
    cropIcon: '<g stroke-width=".125em" stroke="currentColor" fill="none"><path d="M23 17H9a2 2 0 0 1-2-2v-5m0-3V1 M1 7h14a2 2 0 0 1 2 2v7m0 4v3"/></g>',
    cropIconButtonRecenter: '<path stroke="currentColor" fill="none" stroke-width="2" stroke-linejoin="bevel" d="M1.5 7.5v-6h6M1.5 16.5v6h6M22.5 16.5v6h-6M22.5 7.5v-6h-6"/><circle cx="12" cy="12" r="3.5" fill="currentColor" stroke="none"/>',
    cropIconButtonRotateLeft: '<g stroke="none" fill="currentColor"><path fill="none" d="M-1-1h582v402H-1z"/><rect x="3" rx="1" height="12" width="12" y="9"/><path d="M15 5h-1a5 5 0 015 5 1 1 0 002 0 7 7 0 00-7-7h-1.374l.747-.747A1 1 0 0011.958.84L9.603 3.194a1 1 0 000 1.415l2.355 2.355a1 1 0 001.415-1.414l-.55-.55H15z"/></g>',
    cropIconButtonRotateRight: '<g stroke="none" fill="currentColor"><path fill="none" d="M-1-1h582v402H-1z"/><path d="M11.177 5H10a5 5 0 00-5 5 1 1 0 01-2 0 7 7 0 017-7h1.374l-.747-.747A1 1 0 0112.042.84l2.355 2.355a1 1 0 010 1.415l-2.355 2.354a1 1 0 01-1.415-1.414l.55-.55z"/><rect rx="1" height="12" width="12" y="9" x="9"/></g>',
    cropIconButtonFlipVertical: '<g stroke="none" fill="currentColor"><path d="M19.993 12.143H7a1 1 0 0 1-1-1V5.994a1 1 0 0 1 1.368-.93l12.993 5.15a1 1 0 0 1-.368 1.93z"/><path d="M19.993 14a1 1 0 0 1 .368 1.93L7.368 21.078A1 1 0 0 1 6 20.148V15a1 1 0 0 1 1-1h12.993z" opacity=".6"/></g>',
    cropIconButtonFlipHorizontal: '<g stroke="none" fill="currentColor"><path d="M11.93 7.007V20a1 1 0 0 1-1 1H5.78a1 1 0 0 1-.93-1.368l5.15-12.993a1 1 0 0 1 1.929.368z"/><path d="M14 7.007V20a1 1 0 0 0 1 1h5.149a1 1 0 0 0 .93-1.368l-5.15-12.993A1 1 0 0 0 14 7.007z" opacity=".6"/></g>',
    cropIconSelectPreset: (t,e)=>{
        const [o,i,n] = e ? [e < 1 ? 1 : .3, 1 === e ? .85 : .5, e > 1 ? 1 : .3] : [.2, .3, .4];
        return `<g fill="currentColor">\n            <rect opacity="${o}" x="2" y="4" width="10" height="18" rx="1"/>\n            <rect opacity="${i}" x="4" y="8" width="14" height="14" rx="1"/>\n            <rect opacity="${n}" x="6" y="12" width="17" height="10" rx="1"/>\n        </g>`
    }
    ,
    cropIconCropBoundary: (t,e)=>{
        const [o,i,n,r] = e ? [.3, 1, 0, 0] : [0, 0, .3, 1];
        return `<g fill="currentColor">\n            <rect opacity="${o}" x="2" y="3" width="20" height="20" rx="1"/>\n            <rect opacity="${i}" x="7" y="8" width="10" height="10" rx="1"/>\n            <rect opacity="${n}" x="4" y="8" width="14" height="14" rx="1"/>\n            <rect opacity="${r}" x="12" y="4" width="10" height="10" rx="1"/>\n        </g>`
    }
    ,
    cropLabelButtonRecenter: "Recenter",
    cropLabelButtonRotateLeft: "Rotate left",
    cropLabelButtonRotateRight: "Rotate right",
    cropLabelButtonFlipHorizontal: "Flip horizontal",
    cropLabelButtonFlipVertical: "Flip vertical",
    cropLabelSelectPreset: "Crop shape",
    cropLabelCropBoundary: "Crop boundary",
    cropLabelCropBoundaryEdge: "Edge of image",
    cropLabelCropBoundaryNone: "None",
    cropLabelTabRotation: "Rotation",
    cropLabelTabZoom: "Zoom"
}
  , S$ = {
    frameLabel: "Frame",
    frameIcon: '<g fill="none" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" stroke-width=".125em">\n            <rect x="2" y="2" width="20" height="20" rx="4"/>\n            <rect x="6" y="6" width="12" height="12" rx="1"/>\n        </g>',
    frameLabelMatSharp: "Mat",
    frameLabelMatRound: "Bevel",
    frameLabelLineSingle: "Line",
    frameLabelLineMultiple: "Zebra",
    frameLabelEdgeSeparate: "Inset",
    frameLabelEdgeOverlap: "Plus",
    frameLabelEdgeCross: "Lumber",
    frameLabelCornerHooks: "Hook",
    frameLabelPolaroid: "Polaroid"
}
  , C$ = {
    redactLabel: "Redact",
    redactIcon: '<g fill="none" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" stroke-width=".125em">\n        <path d="M 4 5 l 1 -1"/>\n        <path d="M 4 10 l 6 -6"/>\n        <path d="M 4 15 l 11 -11"/>\n        <path d="M 4 20 l 16 -16"/>\n        <path d="M 9 20 l 11 -11"/>\n        <path d="M 14 20 l 6 -6"/>\n        <path d="M 19 20 l 1 -1"/>\n    </g>'
}
  , k$ = (t,e)=>{
    const o = Object.getOwnPropertyDescriptors(t);
    Object.keys(o).forEach((i=>{
        o[i].get ? Object.defineProperty(e, i, {
            get: ()=>t[i],
            set: e=>t[i] = e
        }) : e[i] = t[i]
    }
    ))
}
  , M$ = [...fa, "undo", "redo", "update", "revert", "destroy", "show", "hide", "close", "ready", "loadpreview", "selectshape", "updateshape", "addshape", "removeshape"]
  , T$ = t=>{
    const e = {}
      , {sub: o, pub: i} = ho();
    c() && null !== document.doctype || console.warn("Browser is in quirks mode, add <!DOCTYPE html> to page to fix render issues");
    const r = wa();
    k$(r, e);
    const a = ((t,e)=>{
        const o = {}
          , i = new ku({
            target: t,
            props: {
                stores: e,
                pluginComponents: Array.from(Au)
            }
        });
        let n = !1;
        const r = ()=>{
            n || (c() && window.removeEventListener("pagehide", r),
            i && (n = !0,
            i.$destroy()))
        }
        ;
        Ru || (Ru = new Set(os(ku).filter((t=>!Tu.includes(t))))),
        Ru.forEach((t=>{
            Object.defineProperty(o, t, {
                get: ()=>i[t],
                set: e=>i[t] = e
            })
        }
        )),
        Object.defineProperty(o, "previewImageData", {
            get: ()=>i.imagePreviewCurrent
        }),
        Pu.forEach((t=>{
            const e = Iu[t]
              , n = e[0];
            Object.defineProperty(o, t, {
                get: ()=>i.pluginInterface[n][t],
                set: o=>{
                    const n = e.reduce(((e,n)=>(e[n] = {
                        ...i.pluginOptions[n],
                        [t]: o
                    },
                    e)), {});
                    i.pluginOptions = {
                        ...i.pluginOptions,
                        ...n
                    }
                }
            })
        }
        )),
        Object.defineProperty(o, "element", {
            get: ()=>i.root,
            set: ()=>{}
        });
        const a = i.history;
        return Zr(o, {
            on: (t,e)=>{
                if (n)
                    return ()=>{}
                    ;
                if (/undo|redo|revert/.test(t))
                    return a.on(t, e);
                const o = [i.sub(t, e), i.$on(t, (t=>e(t instanceof CustomEvent && !t.detail ? void 0 : t)))].filter(Boolean);
                return ()=>o.forEach((t=>t()))
            }
            ,
            updateImagePreview: t=>{
                i.imagePreviewSrc = t
            }
            ,
            close: ()=>!n && i.pub("close"),
            destroy: r
        }),
        Object.defineProperty(o, "history", {
            get: ()=>({
                undo: ()=>a.undo(),
                redo: ()=>a.redo(),
                revert: ()=>a.revert(),
                get: ()=>a.get(),
                getCollapsed: ()=>a.get().splice(0, a.index + 1),
                set: t=>a.set(t),
                write: t=>a.write(t),
                get length() {
                    return a.length()
                },
                get index() {
                    return a.index
                }
            })
        }),
        c() && window.addEventListener("pagehide", r),
        o
    }
    )(t, r.stores);
    k$(a, e);
    const s = ["loadImage", "processImage", "abortProcessImage", "abortLoadImage"].map((t=>a.on(t, (e=>{
        const o = r[t](e && e.detail);
        o instanceof Promise && o.catch((()=>{}
        ))
    }
    ))))
      , l = (t,e)=>{
        const i = o(t, e)
          , n = r.on(t, e)
          , s = a.on(t, e);
        return ()=>{
            i(),
            n(),
            s()
        }
    }
    ;
    e.handleEvent = n;
    const d = M$.map((t=>l(t, (o=>e.handleEvent(t, o)))));
    return Zr(e, {
        on: l,
        updateImage: t=>new Promise(((o,i)=>{
            const n = e.history.get()
              , a = e.imageState;
            r.loadImage(t).then((t=>{
                e.history.set(n),
                e.imageState = a,
                o(t)
            }
            )).catch(i)
        }
        )),
        close: ()=>{
            i("close")
        }
        ,
        destroy: ()=>{
            [...s, ...d].forEach((t=>t())),
            a.destroy(),
            r.destroy(),
            i("destroy")
        }
    }),
    e
}
;
var R$ = (t,e={})=>{
    const o = w(t) ? document.querySelector(t) : t;
    if (!de(o))
        return;
    e.class = e.class ? "pintura-editor " + e.class : "pintura-editor";
    const i = T$(o);
    return Object.assign(i, e)
}
;
const {document: P$, window: I$} = Cr;
function A$(t) {
    let e, o, i, n;
    return lr(t[27]),
    {
        c() {
            e = An(),
            o = Rn("div"),
            Bn(o, "class", t[5]),
            Bn(o, "style", t[4])
        },
        m(r, a) {
            Mn(r, e, a),
            Mn(r, o, a),
            t[28](o),
            i || (n = [Ln(I$, "keydown", t[10]), Ln(I$, "orientationchange", t[11]), Ln(I$, "resize", t[27]), Ln(P$.body, "focusin", (function() {
                sn(!t[1] && t[7]) && (!t[1] && t[7]).apply(this, arguments)
            }
            )), Ln(P$.body, "focusout", (function() {
                sn(t[2] && t[8]) && (t[2] && t[8]).apply(this, arguments)
            }
            )), Ln(o, "wheel", t[9], {
                passive: !1
            })],
            i = !0)
        },
        p(e, i) {
            t = e,
            32 & i[0] && Bn(o, "class", t[5]),
            16 & i[0] && Bn(o, "style", t[4])
        },
        i: tn,
        o: tn,
        d(r) {
            r && Tn(e),
            r && Tn(o),
            t[28](null),
            i = !1,
            an(n)
        }
    }
}
function E$(t, e, o) {
    let i, n, r, a, s, l, d, u;
    const h = Jn();
    let {root: m} = e
      , {preventZoomViewport: g=!0} = e
      , {preventScrollBodyIfNeeded: f=!0} = e
      , {preventFooterOverlapIfNeeded: $=!0} = e
      , {class: y} = e
      , x = !0
      , b = !1
      , v = !1
      , w = c() && document.documentElement
      , S = c() && document.body
      , C = c() && document.head;
    const k = ls(0, {
        precision: .001,
        damping: .5
    });
    un(t, k, (t=>o(23, u = t)));
    const M = k.subscribe((t=>{
        v && t >= 1 ? (o(19, v = !1),
        o(1, x = !1),
        h("show")) : b && t <= 0 && (o(18, b = !1),
        o(1, x = !0),
        h("hide"))
    }
    ));
    let T = !1
      , R = void 0
      , P = void 0
      , I = void 0;
    const A = ()=>document.querySelector("meta[name=viewport]")
      , E = ()=>Array.from(document.querySelectorAll("meta[name=theme-color]"));
    let L;
    const F = (t,e)=>{
        const o = ()=>{
            t() ? e() : requestAnimationFrame(o)
        }
        ;
        requestAnimationFrame(o)
    }
    ;
    let z, B, O = 0, D = void 0;
    const _ = ()=>{
        B || (B = p("div", {
            style: "position:fixed;height:100vh;top:0"
        }),
        S.append(B))
    }
    ;
    qn((()=>{
        $ && Fe() && _()
    }
    )),
    Zn((()=>{
        B && (o(21, D = B.offsetHeight),
        B.remove(),
        B = void 0)
    }
    ));
    let W = void 0;
    const V = ()=>w.style.setProperty("--pintura-document-height", window.innerHeight + "px");
    return Kn((()=>{
        w.classList.remove("PinturaModalBodyLock"),
        M()
    }
    )),
    t.$$set = t=>{
        "root"in t && o(0, m = t.root),
        "preventZoomViewport"in t && o(12, g = t.preventZoomViewport),
        "preventScrollBodyIfNeeded"in t && o(13, f = t.preventScrollBodyIfNeeded),
        "preventFooterOverlapIfNeeded"in t && o(14, $ = t.preventFooterOverlapIfNeeded),
        "class"in t && o(15, y = t.class)
    }
    ,
    t.$$.update = ()=>{
        9175042 & t.$$.dirty[0] && o(22, i = v || b ? u : x ? 0 : 1),
        4096 & t.$$.dirty[0] && (n = "width=device-width,height=device-height,initial-scale=1" + (g ? ",maximum-scale=1,user-scalable=0" : "")),
        786434 & t.$$.dirty[0] && o(24, r = !v && !x && !b),
        12 & t.$$.dirty[0] && (T || o(20, z = O)),
        2097160 & t.$$.dirty[0] && o(25, a = qe(D) ? "--viewport-pad-footer:" + (D > O ? 0 : 1) : ""),
        38797312 & t.$$.dirty[0] && o(4, s = `opacity:${i};height:${z}px;--editor-modal:1;${a}`),
        32768 & t.$$.dirty[0] && o(5, l = rl(["pintura-editor", "PinturaModal", y])),
        8192 & t.$$.dirty[0] && o(26, d = f && Fe() && /15_/.test(navigator.userAgent)),
        83886080 & t.$$.dirty[0] && d && (t=>{
            t ? (W = window.scrollY,
            w.classList.add("PinturaDocumentLock"),
            V(),
            window.addEventListener("resize", V)) : (window.removeEventListener("resize", V),
            w.classList.remove("PinturaDocumentLock"),
            qe(W) && window.scrollTo(0, W),
            W = void 0)
        }
        )(r)
    }
    ,
    [m, x, T, O, s, l, k, t=>{
        /textarea/i.test(t.target) && (o(2, T = !0),
        L = O)
    }
    , t=>{
        if (/textarea/i.test(t.target))
            if (clearTimeout(undefined),
            L === O)
                o(2, T = !1);
            else {
                const t = O;
                F((()=>O !== t), (()=>o(2, T = !1)))
            }
    }
    , t=>{
        t.target && /PinturaStage/.test(t.target.className) && t.preventDefault()
    }
    , t=>{
        const {key: e} = t;
        if (!/escape/i.test(e))
            return;
        const o = t.target;
        if (o && /input|textarea/i.test(o.nodeName))
            return;
        const i = document.querySelectorAll(".PinturaModal");
        i[i.length - 1] === m && h("close")
    }
    , _, g, f, $, y, ()=>{
        if (v || !x)
            return;
        o(19, v = !0);
        const t = A() || p("meta", {
            name: "viewport"
        });
        R = !R && t.getAttribute("content"),
        t.setAttribute("content", n + (/cover/.test(R) ? ",viewport-fit=cover" : "")),
        t.parentNode || C.append(t);
        const e = getComputedStyle(m).getPropertyValue("--color-background")
          , i = E();
        if (i.length)
            P = i.map((t=>t.getAttribute("content")));
        else {
            const t = p("meta", {
                name: "theme-color"
            });
            C.append(t),
            i.push(t)
        }
        i.forEach((t=>t.setAttribute("content", `rgb(${e})`))),
        clearTimeout(I),
        I = setTimeout((()=>k.set(1)), 250)
    }
    , ()=>{
        if (b || x)
            return;
        clearTimeout(I),
        o(18, b = !0);
        const t = A();
        R ? t.setAttribute("content", R) : t.remove();
        const e = E();
        P ? e.forEach(((t,e)=>{
            t.setAttribute("content", P[e])
        }
        )) : e.forEach((t=>t.remove())),
        k.set(0)
    }
    , b, v, z, D, i, u, r, a, d, function() {
        o(3, O = I$.innerHeight)
    }
    , function(t) {
        ir[t ? "unshift" : "push"]((()=>{
            m = t,
            o(0, m)
        }
        ))
    }
    ]
}
class L$ extends Br {
    constructor(t) {
        super(),
        zr(this, t, E$, A$, ln, {
            root: 0,
            preventZoomViewport: 12,
            preventScrollBodyIfNeeded: 13,
            preventFooterOverlapIfNeeded: 14,
            class: 15,
            show: 16,
            hide: 17
        }, [-1, -1])
    }
    get root() {
        return this.$$.ctx[0]
    }
    set root(t) {
        this.$set({
            root: t
        }),
        hr()
    }
    get preventZoomViewport() {
        return this.$$.ctx[12]
    }
    set preventZoomViewport(t) {
        this.$set({
            preventZoomViewport: t
        }),
        hr()
    }
    get preventScrollBodyIfNeeded() {
        return this.$$.ctx[13]
    }
    set preventScrollBodyIfNeeded(t) {
        this.$set({
            preventScrollBodyIfNeeded: t
        }),
        hr()
    }
    get preventFooterOverlapIfNeeded() {
        return this.$$.ctx[14]
    }
    set preventFooterOverlapIfNeeded(t) {
        this.$set({
            preventFooterOverlapIfNeeded: t
        }),
        hr()
    }
    get class() {
        return this.$$.ctx[15]
    }
    set class(t) {
        this.$set({
            class: t
        }),
        hr()
    }
    get show() {
        return this.$$.ctx[16]
    }
    get hide() {
        return this.$$.ctx[17]
    }
}
const F$ = (t,e,o,i)=>{
    const n = Y(e.x - t.x, e.y - t.y)
      , r = tt(n)
      , a = 5 * o;
    let s;
    s = i ? .5 * a : Math.ceil(.5 * (a - 1));
    const l = at(Z(r), s);
    return {
        anchor: Z(t),
        offset: l,
        normal: r,
        solid: i,
        size: a,
        sizeHalf: s
    }
}
  , z$ = ({anchor: t, solid: e, normal: o, offset: i, size: n, sizeHalf: r, strokeWidth: a, strokeColor: s},l)=>{
    const c = t.x
      , d = t.y
      , u = at(Z(o), n)
      , h = Y(c + u.x, d + u.y);
    if (at(u, .55),
    e) {
        nt(l, i);
        const t = at(Z(o), .5 * r);
        return [{
            points: [Y(c - t.x, d - t.y), Y(h.x - u.y, h.y + u.x), Y(h.x + u.y, h.y - u.x)],
            backgroundColor: s
        }]
    }
    {
        const t = at((t=>{
            const e = t.x;
            return t.x = -t.y,
            t.y = e,
            t
        }
        )(Z(o)), .5)
          , e = Y(c - t.x, d - t.y)
          , i = Y(c + t.x, d + t.y);
        return [{
            points: [Y(h.x + u.y, h.y - u.x), e, Y(c, d), i, Y(h.x - u.y, h.y + u.x)],
            strokeWidth: a,
            strokeColor: s
        }]
    }
}
  , B$ = ({anchor: t, solid: e, offset: o, normal: i, sizeHalf: n, strokeWidth: r, strokeColor: a},s)=>(nt(s, o),
e && nt(s, K(Z(i))),
[{
    x: t.x,
    y: t.y,
    rx: n,
    ry: n,
    backgroundColor: e ? a : void 0,
    strokeWidth: e ? void 0 : r,
    strokeColor: e ? void 0 : a
}])
  , O$ = ({anchor: t, offset: e, strokeWidth: o, strokeColor: i})=>[{
    points: [Y(t.x - e.y, t.y + e.x), Y(t.x + e.y, t.y - e.x)],
    strokeWidth: o,
    strokeColor: i
}]
  , D$ = ({anchor: t, solid: e, offset: o, normal: i, sizeHalf: n, strokeWidth: r, strokeColor: a},s)=>{
    return nt(s, o),
    [{
        x: t.x - n,
        y: t.y - n,
        width: 2 * n,
        height: 2 * n,
        rotation: (l = i,
        Math.atan2(l.y, l.x)),
        backgroundColor: e ? a : void 0,
        strokeWidth: e ? void 0 : r,
        strokeColor: e ? void 0 : a
    }];
    var l
}
  , _$ = (t={})=>e=>{
    if (!Ke(e, "lineStart") && !Ke(e, "lineEnd"))
        return;
    const o = []
      , {lineStart: i, lineEnd: n, strokeWidth: r, strokeColor: a} = e
      , s = Y(e.x1, e.y1)
      , l = Y(e.x2, e.y2)
      , c = [s, l];
    if (i) {
        const [e,n] = i.split("-")
          , c = t[e];
        if (c) {
            const t = F$(s, l, r, !!n);
            o.push(...c({
                ...t,
                strokeColor: a,
                strokeWidth: r
            }, s))
        }
    }
    if (n) {
        const [e,i] = n.split("-")
          , c = t[e];
        if (c) {
            const t = F$(l, s, r, !!i);
            o.push(...c({
                ...t,
                strokeColor: a,
                strokeWidth: r
            }, l))
        }
    }
    return [{
        points: c,
        strokeWidth: r,
        strokeColor: a
    }, ...o]
}
  , W$ = ()=>({
    arrow: z$,
    circle: B$,
    square: D$,
    bar: O$
})
  , V$ = (t,e)=>{
    const o = parseFloat(t) * e;
    return w(t) ? o + "%" : o
}
  , H$ = (t,e)=>w(t) ? yi(t, e) : t
  , N$ = t=>[{
    ...t,
    frameStyle: "line",
    frameInset: 0,
    frameOffset: 0,
    frameSize: t.frameSize ? V$(t.frameSize, 2) : "2.5%",
    frameRadius: t.frameRound ? V$(t.frameSize, 2) : 0
}]
  , U$ = ({width: t, height: e, frameImage: o, frameSize: i="15%", frameSlices: n={
    x1: .15,
    y1: .15,
    x2: .85,
    y2: .85
}},{isPreview: r})=>{
    if (!o)
        return [];
    const a = Math.sqrt(t * e)
      , s = H$(i, a)
      , l = r ? s : Math.round(s)
      , c = l
      , {x1: d, x2: u, y1: h, y2: p} = n
      , m = {
        x0: 0,
        y0: 0,
        x1: l,
        y1: c,
        x2: t - l,
        y2: e - c,
        x3: t,
        y3: e,
        cw: l,
        ch: c,
        ew: t - l - l,
        eh: e - c - c
    }
      , g = r ? 1 : 0
      , f = 2 * g
      , $ = {
        width: m.cw,
        height: m.ch,
        backgroundImage: o
    };
    return [{
        x: m.x1 - g,
        y: m.y0,
        width: m.ew + f,
        height: m.ch,
        backgroundCorners: [{
            x: d,
            y: 0
        }, {
            x: u,
            y: 0
        }, {
            x: u,
            y: h
        }, {
            x: d,
            y: h
        }],
        backgroundImage: o
    }, {
        x: m.x1 - g,
        y: m.y2,
        width: m.ew + f,
        height: m.ch,
        backgroundCorners: [{
            x: d,
            y: p
        }, {
            x: u,
            y: p
        }, {
            x: u,
            y: 1
        }, {
            x: d,
            y: 1
        }],
        backgroundImage: o
    }, {
        x: m.x0,
        y: m.y1 - g,
        width: m.cw,
        height: m.eh + f,
        backgroundCorners: [{
            x: 0,
            y: h
        }, {
            x: d,
            y: h
        }, {
            x: d,
            y: p
        }, {
            x: 0,
            y: p
        }],
        backgroundImage: o
    }, {
        x: m.x2,
        y: m.y1 - g,
        width: m.cw,
        height: m.eh + f,
        backgroundCorners: [{
            x: u,
            y: h
        }, {
            x: 1,
            y: h
        }, {
            x: 1,
            y: p
        }, {
            x: u,
            y: p
        }],
        backgroundImage: o
    }, {
        ...$,
        x: m.x0,
        y: m.y0,
        backgroundCorners: [{
            x: 0,
            y: 0
        }, {
            x: d,
            y: 0
        }, {
            x: d,
            y: h
        }, {
            x: 0,
            y: h
        }]
    }, {
        ...$,
        x: m.x2,
        y: m.y0,
        backgroundCorners: [{
            x: u,
            y: 0
        }, {
            x: 1,
            y: 0
        }, {
            x: 1,
            y: h
        }, {
            x: u,
            y: h
        }]
    }, {
        ...$,
        x: m.x2,
        y: m.y2,
        backgroundCorners: [{
            x: u,
            y: p
        }, {
            x: 1,
            y: p
        }, {
            x: 1,
            y: 1
        }, {
            x: u,
            y: 1
        }]
    }, {
        ...$,
        x: m.x0,
        y: m.y2,
        backgroundCorners: [{
            x: 0,
            y: p
        }, {
            x: d,
            y: p
        }, {
            x: d,
            y: 1
        }, {
            x: 0,
            y: 1
        }]
    }]
}
  , j$ = ({x: t, y: e, width: o, height: i, frameInset: n="3.5%", frameSize: r=".25%", frameColor: a=[1, 1, 1], frameOffset: s="5%", frameAmount: l=1, frameRadius: c=0, expandsCanvas: d=!1},{isPreview: u})=>{
    const h = Math.sqrt(o * i);
    let p = H$(r, h);
    const m = H$(n, h)
      , g = H$(s, h);
    let f = 0;
    u || (p = Math.max(1, Math.round(p)),
    f = p % 2 == 0 ? 0 : .5);
    const $ = H$(V$(c, l), h);
    return new Array(l).fill(void 0).map(((n,r)=>{
        const s = g * r;
        let l = t + m + s
          , c = e + m + s
          , h = t + o - m - s
          , y = e + i - m - s;
        u || (l = Math.round(l),
        c = Math.round(c),
        h = Math.round(h),
        y = Math.round(y));
        return {
            x: l + f,
            y: c + f,
            width: h - l,
            height: y - c,
            cornerRadius: $ > 0 ? $ - s : 0,
            strokeWidth: p,
            strokeColor: a,
            expandsCanvas: d
        }
    }
    ))
}
  , X$ = ({x: t, y: e, width: o, height: i, frameSize: n=".25%", frameOffset: r=0, frameInset: a="2.5%", frameColor: s=[1, 1, 1]},{isPreview: l})=>{
    const c = Math.sqrt(o * i);
    let d = H$(n, c)
      , u = H$(a, c)
      , h = H$(r, c)
      , p = 0;
    l || (d = Math.max(1, Math.round(d)),
    u = Math.round(u),
    h = Math.round(h),
    p = d % 2 == 0 ? 0 : .5);
    const m = h - u
      , g = t + u + p
      , f = e + u + p
      , $ = t + o - u - p
      , y = e + i - u - p;
    return [{
        points: [Y(g + m, f), Y($ - m, f)]
    }, {
        points: [Y($, f + m), Y($, y - m)]
    }, {
        points: [Y($ - m, y), Y(g + m, y)]
    }, {
        points: [Y(g, y - m), Y(g, f + m)]
    }].map((t=>(t.strokeWidth = d,
    t.strokeColor = s,
    t)))
}
  , Y$ = ({x: t, y: e, width: o, height: i, frameSize: n=".25%", frameInset: r="2.5%", frameLength: a="2.5%", frameColor: s=[1, 1, 1]},{isPreview: l})=>{
    const c = Math.sqrt(o * i);
    let d = H$(n, c)
      , u = H$(r, c)
      , h = H$(a, c)
      , p = 0;
    l || (d = Math.max(1, Math.round(d)),
    u = Math.round(u),
    h = Math.round(h),
    p = d % 2 == 0 ? 0 : .5);
    const m = t + u + p
      , g = e + u + p
      , f = t + o - u - p
      , $ = e + i - u - p;
    return [{
        points: [Y(m, g + h), Y(m, g), Y(m + h, g)]
    }, {
        points: [Y(f - h, g), Y(f, g), Y(f, g + h)]
    }, {
        points: [Y(f, $ - h), Y(f, $), Y(f - h, $)]
    }, {
        points: [Y(m + h, $), Y(m, $), Y(m, $ - h)]
    }].map((t=>(t.strokeWidth = d,
    t.strokeColor = s,
    t)))
}
  , G$ = ({x: t, y: e, width: o, height: i, frameColor: n=[1, 1, 1]},{isPreview: r})=>{
    const a = Math.sqrt(o * i)
      , s = .1 * a;
    let l = .2 * a;
    const c = .5 * s;
    let d = .0025 * a;
    return r || (l = Math.ceil(l),
    d = Math.max(2, d)),
    n.length = 3,
    [{
        id: "border",
        x: t - c,
        y: e - c,
        width: o + s,
        height: i + l,
        frameStyle: "line",
        frameInset: 0,
        frameOffset: 0,
        frameSize: s,
        frameColor: n,
        expandsCanvas: !0
    }, {
        id: "chin",
        x: t - c,
        y: i,
        width: o + s,
        height: l,
        backgroundColor: n,
        expandsCanvas: !0
    }, r && {
        x: t,
        y: e,
        width: o,
        height: i,
        strokeWidth: d,
        strokeColor: n
    }].filter(Boolean)
}
  , q$ = (t={})=>(e,o)=>{
    if (!Ke(e, "frameStyle"))
        return;
    const i = e.frameStyle
      , n = t[i];
    if (!n)
        return;
    const {frameStyle: r, ...a} = e;
    return n(a, o)
}
  , Z$ = ()=>({
    solid: N$,
    hook: Y$,
    line: j$,
    edge: X$,
    polaroid: G$,
    nine: U$
})
  , K$ = t=>{
    const e = (o,i={
        isPreview: !0
    })=>{
        const n = t.map((t=>{
            const n = t(o, i);
            if (n)
                return n.map((t=>e(t, i)))
        }
        )).filter(Boolean).flat();
        return n.length ? n.flat() : o
    }
    ;
    return e
}
  , J$ = Ja
  , Q$ = Qa
  , ty = ()=>({
    read: s,
    apply: y
})
  , ey = (t={})=>{
    const {blurAmount: e, scrambleAmount: o, enableSmoothing: i, backgroundColor: n} = t;
    return (t,r)=>(async(t,e={})=>{
        if (!t)
            return;
        const {width: o, height: i} = t
          , {dataSize: n=96, dataSizeScalar: r=1, scrambleAmount: a=4, blurAmount: s=6, outputFormat: l="canvas", backgroundColor: c=[0, 0, 0]} = e
          , d = Math.round(n * r)
          , u = Math.min(d / o, d / i)
          , h = Math.floor(o * u)
          , m = Math.floor(i * u)
          , $ = p("canvas", {
            width: h,
            height: m
        })
          , y = $.getContext("2d");
        if (c.length = 3,
        y.fillStyle = ao(c),
        y.fillRect(0, 0, h, m),
        f(t)) {
            const e = p("canvas", {
                width: o,
                height: i
            });
            e.getContext("2d").putImageData(t, 0, 0),
            y.drawImage(e, 0, 0, h, m),
            g(e)
        } else
            y.drawImage(t, 0, 0, h, m);
        const x = y.getImageData(0, 0, h, m)
          , b = [];
        if (a > 0 && b.push([ts, {
            amount: a
        }]),
        s > 0)
            for (let t = 0; t < s; t++)
                b.push([Ue, {
                    matrix: es
                }]);
        let v;
        if (b.length) {
            const t = (e,o)=>`(err, imageData) => {\n                (${e[o][0].toString()})(Object.assign({ imageData: imageData }, filterInstructions[${o}]), \n                    ${e[o + 1] ? t(e, o + 1) : "done"})\n            }`
              , e = `function (options, done) {\n            const filterInstructions = options.filterInstructions;\n            const imageData = options.imageData;\n            (${t(b, 0)})(null, imageData)\n        }`
              , o = await P(e, [{
                imageData: x,
                filterInstructions: b.map((t=>t[1]))
            }], [x.data.buffer]);
            v = Ve(o)
        } else
            v = x;
        return "canvas" === l ? (y.putImageData(v, 0, 0),
        $) : v
    }
    )(t, {
        blurAmount: e,
        scrambleAmount: o,
        enableSmoothing: i,
        backgroundColor: n,
        ...r
    })
}
  , oy = wa
  , iy = ()=>(()=>{
    const t = xa.map(ba)
      , e = Gr.map((([t])=>t)).filter((t=>!ya.includes(t)));
    return t.concat(e)
}
)().concat((Ru = new Set(os(ku).filter((t=>!Tu.includes(t)))),
[...Ru, ...Pu]))
  , ny = ip
  , ry = ep
  , ay = Hp
  , sy = {
    markupEditorToolbar: ip(),
    markupEditorToolStyles: ep(),
    markupEditorShapeStyleControls: Hp()
}
  , ly = Eu
  , cy = eg
  , dy = lg
  , uy = gg
  , hy = Yf
  , py = Lu
  , my = Zf
  , gy = o$
  , fy = $$
  , $y = m$
  , yy = ih
  , xy = yh
  , by = Rh
  , vy = b$
  , wy = v$
  , Sy = w$
  , Cy = {
    filterLabel: "Filter",
    filterIcon: '<g stroke-width=".125em" stroke="currentColor" fill="none"><path d="M18.347 9.907a6.5 6.5 0 1 0-1.872 3.306M3.26 11.574a6.5 6.5 0 1 0 2.815-1.417 M10.15 17.897A6.503 6.503 0 0 0 16.5 23a6.5 6.5 0 1 0-6.183-8.51"/></g>',
    filterLabelChrome: "Chrome",
    filterLabelFade: "Fade",
    filterLabelCold: "Cold",
    filterLabelWarm: "Warm",
    filterLabelPastel: "Pastel",
    filterLabelMonoDefault: "Mono",
    filterLabelMonoNoir: "Noir",
    filterLabelMonoWash: "Wash",
    filterLabelMonoStark: "Stark",
    filterLabelSepiaDefault: "Sepia",
    filterLabelSepiaBlues: "Blues",
    filterLabelSepiaRust: "Rust",
    filterLabelSepiaColor: "Color"
}
  , ky = {
    finetuneLabel: "Finetune",
    finetuneIcon: '<g stroke-width=".125em" stroke="currentColor" fill="none"><path d="M4 1v5.5m0 3.503V23M12 1v10.5m0 3.5v8M20 1v15.5m0 3.5v3M2 7h4M10 12h4M18 17h4"/></g>',
    finetuneLabelBrightness: "Brightness",
    finetuneLabelContrast: "Contrast",
    finetuneLabelSaturation: "Saturation",
    finetuneLabelExposure: "Exposure",
    finetuneLabelTemperature: "Temperature",
    finetuneLabelGamma: "Gamma",
    finetuneLabelClarity: "Clarity",
    finetuneLabelVignette: "Vignette"
}
  , My = {
    resizeLabel: "Resize",
    resizeIcon: '<g stroke-width=".125em" stroke="currentColor" fill="none"><rect x="2" y="12" width="10" height="10" rx="2"/><path d="M4 11.5V4a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-5.5"/><path d="M14 10l3.365-3.365M14 6h4v4"/></g>',
    resizeLabelFormCaption: "Image output size",
    resizeLabelInputWidth: "w",
    resizeTitleInputWidth: "Width",
    resizeLabelInputHeight: "h",
    resizeTitleInputHeight: "Height",
    resizeTitleButtonMaintainAspectRatio: "Maintain aspectratio",
    resizeIconButtonMaintainAspectRatio: (t,e)=>`\n        <defs>\n            <mask id="mask1" x="0" y="0" width="24" height="24" >\n                <rect x="0" y="0" width="24" height="10" fill="#fff" stroke="none"/>\n            </mask>\n        </defs>\n        <g fill="none" fill-rule="evenodd">\n            <g  mask="url(#mask1)">\n                <path transform="translate(0 ${3 * (e - 1)})" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" d="M9.401 10.205v-.804a2.599 2.599 0 0 1 5.198 0V17"/>\n            </g>\n            <rect fill="currentColor" x="7" y="10" width="10" height="7" rx="1.5"/>\n        </g>\n    `
}
  , Ty = {
    decorateLabel: "Decorate",
    decorateIcon: '<g fill="none" fill-rule="evenodd"><path stroke="currentColor" stroke-width=".125em" stroke-linecap="round" stroke-linejoin="round" d="M12 18.5l-6.466 3.4 1.235-7.2-5.23-5.1 7.228-1.05L12 2l3.233 6.55 7.229 1.05-5.231 5.1 1.235 7.2z"/></g>'
}
  , Ry = {
    annotateLabel: "Annotate",
    annotateIcon: '<g stroke-width=".125em" stroke="currentColor" fill="none"><path d="M17.086 2.914a2.828 2.828 0 1 1 4 4l-14.5 14.5-5.5 1.5 1.5-5.5 14.5-14.5z"/></g>'
}
  , Py = {
    stickerLabel: "Sticker",
    stickerIcon: '<g fill="none" stroke-linecap="round" stroke-linejoin="round" stroke="currentColor" stroke-width=".125em"><path d="M12 22c2.773 0 1.189-5.177 3-7 1.796-1.808 7-.25 7-3 0-5.523-4.477-10-10-10S2 6.477 2 12s4.477 10 10 10z"/><path d="M20 17c-3 3-5 5-8 5"/></g>'
}
  , Iy = S$
  , Ay = C$
  , Ey = (t,e,o={})=>(w(e) ? Array.from(document.querySelectorAll(e)) : e).filter(Boolean).map((e=>t(e, v(o))))
  , Ly = R$
  , Fy = (t={},e)=>{
    const {sub: o, pub: i} = ho()
      , r = {}
      , a = ((t={},e)=>new L$({
        target: e || document.body,
        props: {
            class: t.class,
            preventZoomViewport: t.preventZoomViewport,
            preventScrollBodyIfNeeded: t.preventScrollBodyIfNeeded,
            preventFooterOverlapIfNeeded: t.preventFooterOverlapIfNeeded
        }
    }))(t, e)
      , s = ()=>{
        a.hide && a.hide()
    }
      , l = ()=>{
        a.show && a.show()
    }
      , c = T$(a.root);
    k$(c, r),
    r.handleEvent = n,
    c.handleEvent = (t,e)=>r.handleEvent(t, e),
    c.on("close", (async()=>{
        const {willClose: e} = t;
        if (!e)
            return s();
        await e() && s()
    }
    ));
    const d = (t,e)=>/show|hide/.test(t) ? o(t, e) : c.on(t, e)
      , u = ["show", "hide"].map((t=>d(t, (e=>r.handleEvent(t, e)))))
      , h = ()=>{
        u.forEach((t=>t())),
        s(),
        a.$destroy(),
        c.destroy()
    }
    ;
    return Zr(r, {
        on: d,
        destroy: h,
        hide: s,
        show: l
    }),
    Object.defineProperty(r, "modal", {
        get: ()=>a.root,
        set: ()=>{}
    }),
    a.$on("close", c.close),
    a.$on("show", (()=>i("show"))),
    a.$on("hide", (()=>{
        i("hide"),
        !1 !== t.enableAutoDestroy && h()
    }
    )),
    !1 !== t.enableAutoHide && c.on("process", s),
    c.on("loadstart", l),
    !1 !== t.enableButtonClose && (t.enableButtonClose = !0),
    delete t.class,
    Object.assign(r, t),
    r
}
  , zy = (t,e)=>R$(t, {
    ...e,
    layout: "overlay"
})
  , By = (t,e)=>Ey(Ly, t, e)
  , Oy = K$
  , Dy = ()=>K$([q$(Z$()), _$(W$())])
  , _y = (t={})=>{
    let e = void 0;
    Array.isArray(t.imageWriter) || (e = t.imageWriter,
    delete t.imageReader);
    let o = void 0;
    Array.isArray(t.imageWriter) || (o = t.imageWriter,
    delete t.imageWriter);
    let i = void 0;
    return S(t.imageScrambler) || (i = t.imageScrambler,
    delete t.imageScrambler),
    {
        imageReader: J$(e),
        imageWriter: Q$(o),
        imageOrienter: ty(),
        imageScrambler: ey(i)
    }
}
  , Wy = (t,e={})=>Sa(t, {
    ..._y(e),
    ...e
})
  , Vy = (t={})=>{
    Eu(...[cy, dy, uy, hy, py, my, gy, fy, $y].filter(Boolean));
    const e = ["crop", "filter", "finetune", "annotate", "decorate", t.stickers && "sticker", "frame", "redact", "resize"].filter(Boolean)
      , o = _y(t)
      , i = {
        ...vy,
        ...wy,
        ...Sy,
        ...Cy,
        ...ky,
        ...Iy,
        ...Ay,
        ...My,
        ...Ty,
        ...Ry,
        ...Py,
        ...t.locale
    };
    return delete t.locale,
    Vr([{
        ...o,
        shapePreprocessor: Dy(),
        utils: e,
        ...yy,
        ...xy,
        ...by,
        ...sy,
        stickerStickToImage: !0,
        locale: i
    }, t])
}
  , Hy = async(t={})=>{
    const e = await void 0;
    return e.forEach((e=>Object.assign(e, v(t)))),
    e
}
  , Ny = t=>Hy(Vy(t))
  , Uy = t=>Fy(Vy(t))
  , jy = (t,e)=>Ly(t, Vy(e))
  , Xy = (t,e)=>zy(t, Vy(e))
  , Yy = (t,e)=>Ey(jy, t, e);
(t=>{
    const [e,o,i,n,r,a,s,l,c,d,u,h] = ["bG9jYXRpb24=", "ZG9jdW1lbnQ=", "UmVnRXhw", "RWxlbWVudA==", "dGVzdA==", "PGEgaHJlZj0iaHR0cHM6Ly9wcWluYS5ubC8/dW5saWNlbnNlZCI+Zm9yIHVzZSBvbiBwcWluYS5ubCBvbmx5PC9hPg==", "aW5zZXJ0QWRqYWNlbnRIVE1M", "Y2xhc3NOYW1l", "IHBpbnR1cmEtZWRpdG9yLXZhbGlkYXRlZA==", "KD86WzAtOV17MSwzfVwuKXszfXxjc2JcLmFwcHxwcWluYVwubmx8bG9jYWxob3N0", "YmVmb3JlZW5k", "Ym9keQ=="].map(t[[(!1 + "")[1], (!0 + "")[0], (1 + {})[2], (1 + {})[3]].join("")]);
    new t[i](d)[r](t[e]) || t[o][h][s](u, a),
    t[o][o + n][l] += c
}
)(window);
export {jy as appendDefaultEditor, Yy as appendDefaultEditors, Ly as appendEditor, By as appendEditors, Hu as appendNode, O as blobToFile, Xd as colorStringToColorArray, np as createDefaultColorOptions, hp as createDefaultFontFamilyOptions, sp as createDefaultFontScaleOptions, rp as createDefaultFontSizeOptions, mp as createDefaultFontStyleOptions, Z$ as createDefaultFrameStyles, ty as createDefaultImageOrienter, J$ as createDefaultImageReader, ey as createDefaultImageScrambler, Q$ as createDefaultImageWriter, up as createDefaultLineEndStyleOptions, W$ as createDefaultLineEndStyles, ap as createDefaultLineHeightOptions, lp as createDefaultLineHeightScaleOptions, Dy as createDefaultShapePreprocessor, dp as createDefaultStrokeScaleOptions, cp as createDefaultStrokeWidthOptions, pp as createDefaultTextAlignOptions, oy as createEditor, q$ as createFrameStyleProcessor, _$ as createLineEndProcessor, Ap as createMarkupEditorBackgroundColorControl, Rp as createMarkupEditorColorControl, gp as createMarkupEditorColorOptions, Op as createMarkupEditorFontColorControl, Ip as createMarkupEditorFontFamilyControl, wp as createMarkupEditorFontFamilyOptions, $p as createMarkupEditorFontScaleOptions, _p as createMarkupEditorFontSizeControl, fp as createMarkupEditorFontSizeOptions, Dp as createMarkupEditorFontStyleControl, Sp as createMarkupEditorFontStyleOptions, Bp as createMarkupEditorLineEndStyleControl, Cp as createMarkupEditorLineEndStyleOptions, Vp as createMarkupEditorLineHeightControl, yp as createMarkupEditorLineHeightOptions, xp as createMarkupEditorLineHeightScaleOptions, zp as createMarkupEditorLineStartStyleControl, ay as createMarkupEditorShapeStyleControls, Ep as createMarkupEditorStrokeColorControl, vp as createMarkupEditorStrokeScaleOptions, Lp as createMarkupEditorStrokeWidthControl, bp as createMarkupEditorStrokeWidthOptions, Wp as createMarkupEditorTextAlignControl, tp as createMarkupEditorToolStyle, ry as createMarkupEditorToolStyles, ny as createMarkupEditorToolbar, Du as createNode, Oy as createShapePreprocessor, Hy as defineCustomElements, Ny as defineDefaultCustomElements, Vs as degToRad, Lu as dispatchEditorEvents, qu as effectBrightness, eh as effectClarity, Zu as effectContrast, Ju as effectExposure, Qu as effectGamma, Ku as effectSaturation, oh as effectTemperature, th as effectVignette, rh as filterChrome, lh as filterCold, ah as filterFade, ch as filterInvert, dh as filterMonoDefault, uh as filterMonoNoir, ph as filterMonoStark, hh as filterMonoWash, nh as filterPastel, gh as filterSepiaBlues, $h as filterSepiaColor, mh as filterSepiaDefault, fh as filterSepiaRust, sh as filterWarm, Uu as findNode, Ch as frameEdgeCross, kh as frameEdgeOverlap, Sh as frameEdgeSeparate, Mh as frameHook, wh as frameLineMultiple, vh as frameLineSingle, Th as framePolaroid, bh as frameSolidRound, xh as frameSolidSharp, Vy as getEditorDefaults, iy as getEditorProps, Vu as insertNodeAfter, Wu as insertNodeBefore, Yu as isSupported, Lu as legacyDataToImageState, vy as locale_en_gb, sy as markup_editor_defaults, wy as markup_editor_locale_en_gb, Uy as openDefaultEditor, Fy as openEditor, Xy as overlayDefaultEditor, zy as overlayEditor, hy as plugin_annotate, Ry as plugin_annotate_locale_en_gb, cy as plugin_crop, Sy as plugin_crop_locale_en_gb, py as plugin_decorate, Ty as plugin_decorate_locale_en_gb, dy as plugin_filter, xy as plugin_filter_defaults, Cy as plugin_filter_locale_en_gb, uy as plugin_finetune, yy as plugin_finetune_defaults, ky as plugin_finetune_locale_en_gb, gy as plugin_frame, by as plugin_frame_defaults, Iy as plugin_frame_locale_en_gb, fy as plugin_redact, Ay as plugin_redact_locale_en_gb, $y as plugin_resize, My as plugin_resize_locale_en_gb, my as plugin_sticker, Py as plugin_sticker_locale_en_gb, Wy as processDefaultImage, Sa as processImage, Nu as removeNode, ly as setPlugins, Gd as supportsWebGL};
