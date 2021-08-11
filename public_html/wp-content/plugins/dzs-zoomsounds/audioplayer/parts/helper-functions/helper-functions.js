window.MD5 = function (e) {
  function g(a, d) {
    var b = a & 2147483648;
    var c = d & 2147483648;
    var e = a & 1073741824;
    var f = d & 1073741824;
    var p = (a & 1073741823) + (d & 1073741823);
    return e & f ? p ^ 2147483648 ^ b ^ c : e | f ? p & 1073741824 ? p ^ 3221225472 ^ b ^ c : p ^ 1073741824 ^ b ^ c : p ^ b ^ c
  }

  function h(b, c, a, d, e, f, n) {
    b = g(b, g(g(c & a | ~c & d, e), n));
    return g(b << f | b >>> 32 - f, c)
  }

  function k(b, c, a, d, e, f, n) {
    b = g(b, g(g(c & d | a & ~d, e), n));
    return g(b << f | b >>> 32 - f, c)
  }

  function l(b, c, d, a, e, f, n) {
    b = g(b, g(g(c ^ d ^ a, e), n));
    return g(b << f | b >>> 32 - f, c)
  }

  function m(b, c, d, a, e, f, n) {
    b = g(b, g(g(d ^
      (c | ~a), e), n));
    return g(b << f | b >>> 32 - f, c)
  }

  function q(b) {
    var c = "", d;
    for (d = 0; 3 >= d; d++) {
      var a = b >>> 8 * d & 255;
      a = "0" + a.toString(16);
      c += a.substr(a.length - 2, 2)
    }
    return c
  }

  var f = [];
  e = function (b) {
    b = b.replace(/\r\n/g, "\n");
    for (var c = "", d = 0; d < b.length; d++) {
      var a = b.charCodeAt(d);
      128 > a ? c += String.fromCharCode(a) : (127 < a && 2048 > a ? c += String.fromCharCode(a >> 6 | 192) : (c += String.fromCharCode(a >> 12 | 224), c += String.fromCharCode(a >> 6 & 63 | 128)), c += String.fromCharCode(a & 63 | 128))
    }
    return c
  }(e);
  f = function (b) {
    var c = b.length;
    var a = c + 8;
    for (var d =
      16 * ((a - a % 64) / 64 + 1), e = Array(d - 1), f, g = 0; g < c;) a = (g - g % 4) / 4, f = g % 4 * 8, e[a] |= b.charCodeAt(g) << f, g++;
    a = (g - g % 4) / 4;
    e[a] |= 128 << g % 4 * 8;
    e[d - 2] = c << 3;
    e[d - 1] = c >>> 29;
    return e
  }(e);
  var a = 1732584193;
  var d = 4023233417;
  var b = 2562383102;
  var c = 271733878;
  for (e = 0; e < f.length; e += 16) {
    var r = a;
    var t = d;
    var u = b;
    var v = c;
    a = h(a, d, b, c, f[e + 0], 7, 3614090360);
    c = h(c, a, d, b, f[e + 1], 12, 3905402710);
    b = h(b, c, a, d, f[e + 2], 17, 606105819);
    d = h(d, b, c, a, f[e + 3], 22, 3250441966);
    a = h(a, d, b, c, f[e + 4], 7, 4118548399);
    c = h(c, a, d, b, f[e + 5], 12, 1200080426);
    b = h(b, c, a, d, f[e +
    6], 17, 2821735955);
    d = h(d, b, c, a, f[e + 7], 22, 4249261313);
    a = h(a, d, b, c, f[e + 8], 7, 1770035416);
    c = h(c, a, d, b, f[e + 9], 12, 2336552879);
    b = h(b, c, a, d, f[e + 10], 17, 4294925233);
    d = h(d, b, c, a, f[e + 11], 22, 2304563134);
    a = h(a, d, b, c, f[e + 12], 7, 1804603682);
    c = h(c, a, d, b, f[e + 13], 12, 4254626195);
    b = h(b, c, a, d, f[e + 14], 17, 2792965006);
    d = h(d, b, c, a, f[e + 15], 22, 1236535329);
    a = k(a, d, b, c, f[e + 1], 5, 4129170786);
    c = k(c, a, d, b, f[e + 6], 9, 3225465664);
    b = k(b, c, a, d, f[e + 11], 14, 643717713);
    d = k(d, b, c, a, f[e + 0], 20, 3921069994);
    a = k(a, d, b, c, f[e + 5], 5, 3593408605);
    c = k(c,
      a, d, b, f[e + 10], 9, 38016083);
    b = k(b, c, a, d, f[e + 15], 14, 3634488961);
    d = k(d, b, c, a, f[e + 4], 20, 3889429448);
    a = k(a, d, b, c, f[e + 9], 5, 568446438);
    c = k(c, a, d, b, f[e + 14], 9, 3275163606);
    b = k(b, c, a, d, f[e + 3], 14, 4107603335);
    d = k(d, b, c, a, f[e + 8], 20, 1163531501);
    a = k(a, d, b, c, f[e + 13], 5, 2850285829);
    c = k(c, a, d, b, f[e + 2], 9, 4243563512);
    b = k(b, c, a, d, f[e + 7], 14, 1735328473);
    d = k(d, b, c, a, f[e + 12], 20, 2368359562);
    a = l(a, d, b, c, f[e + 5], 4, 4294588738);
    c = l(c, a, d, b, f[e + 8], 11, 2272392833);
    b = l(b, c, a, d, f[e + 11], 16, 1839030562);
    d = l(d, b, c, a, f[e + 14], 23, 4259657740);
    a = l(a, d, b, c, f[e + 1], 4, 2763975236);
    c = l(c, a, d, b, f[e + 4], 11, 1272893353);
    b = l(b, c, a, d, f[e + 7], 16, 4139469664);
    d = l(d, b, c, a, f[e + 10], 23, 3200236656);
    a = l(a, d, b, c, f[e + 13], 4, 681279174);
    c = l(c, a, d, b, f[e + 0], 11, 3936430074);
    b = l(b, c, a, d, f[e + 3], 16, 3572445317);
    d = l(d, b, c, a, f[e + 6], 23, 76029189);
    a = l(a, d, b, c, f[e + 9], 4, 3654602809);
    c = l(c, a, d, b, f[e + 12], 11, 3873151461);
    b = l(b, c, a, d, f[e + 15], 16, 530742520);
    d = l(d, b, c, a, f[e + 2], 23, 3299628645);
    a = m(a, d, b, c, f[e + 0], 6, 4096336452);
    c = m(c, a, d, b, f[e + 7], 10, 1126891415);
    b = m(b, c, a, d, f[e + 14], 15, 2878612391);
    d = m(d, b, c, a, f[e + 5], 21, 4237533241);
    a = m(a, d, b, c, f[e + 12], 6, 1700485571);
    c = m(c, a, d, b, f[e + 3], 10, 2399980690);
    b = m(b, c, a, d, f[e + 10], 15, 4293915773);
    d = m(d, b, c, a, f[e + 1], 21, 2240044497);
    a = m(a, d, b, c, f[e + 8], 6, 1873313359);
    c = m(c, a, d, b, f[e + 15], 10, 4264355552);
    b = m(b, c, a, d, f[e + 6], 15, 2734768916);
    d = m(d, b, c, a, f[e + 13], 21, 1309151649);
    a = m(a, d, b, c, f[e + 4], 6, 4149444226);
    c = m(c, a, d, b, f[e + 11], 10, 3174756917);
    b = m(b, c, a, d, f[e + 2], 15, 718787259);
    d = m(d, b, c, a, f[e + 9], 21, 3951481745);
    a = g(a, r);
    d = g(d, t);
    b = g(b, u);
    c = g(c, v)
  }
  return (q(a) + q(d) + q(b) +
    q(c)).toLowerCase()
};
