(self.webpackChunktradingview=self.webpackChunktradingview||[]).push([[37939],{650151:(r,e)=>{"use strict";function t(r,e){if(void 0===r)throw new Error("".concat(null!=e?e:"Value"," is undefined"));return r}function n(r,e){if(null===r)throw new Error("".concat(null!=e?e:"Value"," is null"));return r}Object.defineProperty(e,"__esModule",{value:!0}),e.ensureNever=e.ensure=e.ensureNotNull=e.ensureDefined=e.assert=void 0,e.assert=function(r,e){if(!r)throw new Error("Assertion failed".concat(e?": ".concat(e):""))},e.ensureDefined=t,e.ensureNotNull=n,e.ensure=function(r,e){return n(t(r,e),e)},e.ensureNever=function(r){}},150335:(r,e)=>{"use strict";function t(r){return Math.round(1e10*r)/1e10}Object.defineProperty(e,"__esModule",{value:!0}),e.alignTo=e.fixComputationError=e.isNaN=e.isInteger=e.isNumber=void 0,e.isNumber=function(r){return"number"==typeof r&&isFinite(r)},e.isInteger=function(r){return"number"==typeof r&&r%1==0},e.isNaN=function(r){return!(r<=0||r>0)},e.fixComputationError=t,e.alignTo=function(r,e){var n=r/e,o=Math.floor(n),i=n-o;return i>2e-10?t(i>.5?(o+1)*e:o*e):r}},960521:function(r,e,t){var n;!function(){"use strict";var o,i=1e6,u=1e6,s="[big.js] ",c=s+"Invalid ",f=c+"decimal places",l=c+"rounding mode",a=s+"Division by zero",h={},p=void 0,d=/^-?(\d+(\.\d*)?|\.\d+)(e[+-]?\d+)?$/i;function w(r,e,t,n){var o=r.c;if(t===p&&(t=r.constructor.RM),0!==t&&1!==t&&2!==t&&3!==t)throw Error(l);if(e<1)n=3===t&&(n||!!o[0])||0===e&&(1===t&&o[0]>=5||2===t&&(o[0]>5||5===o[0]&&(n||o[1]!==p))),o.length=1,n?(r.e=r.e-e+1,o[0]=1):o[0]=r.e=0;else if(e<o.length){if(n=1===t&&o[e]>=5||2===t&&(o[e]>5||5===o[e]&&(n||o[e+1]!==p||1&o[e-1]))||3===t&&(n||!!o[0]),o.length=e--,n)for(;++o[e]>9;)o[e]=0,e--||(++r.e,o.unshift(1));for(e=o.length;!o[--e];)o.pop()}return r}function g(r,e,t){var n=r.e,o=r.c.join(""),i=o.length;if(e)o=o.charAt(0)+(i>1?"."+o.slice(1):"")+(n<0?"e":"e+")+n;else if(n<0){for(;++n;)o="0"+o;o="0."+o}else if(n>0)if(++n>i)for(n-=i;n--;)o+="0";else n<i&&(o=o.slice(0,n)+"."+o.slice(n));else i>1&&(o=o.charAt(0)+"."+o.slice(1));return r.s<0&&t?"-"+o:o}h.abs=function(){var r=new this.constructor(this);return r.s=1,r},h.cmp=function(r){var e,t=this,n=t.c,o=(r=new t.constructor(r)).c,i=t.s,u=r.s,s=t.e,c=r.e;if(!n[0]||!o[0])return n[0]?i:o[0]?-u:0;if(i!=u)return i;if(e=i<0,s!=c)return s>c^e?1:-1;for(u=(s=n.length)<(c=o.length)?s:c,i=-1;++i<u;)if(n[i]!=o[i])return n[i]>o[i]^e?1:-1;return s==c?0:s>c^e?1:-1},h.div=function(r){var e=this,t=e.constructor,n=e.c,o=(r=new t(r)).c,u=e.s==r.s?1:-1,s=t.DP;if(s!==~~s||s<0||s>i)throw Error(f);if(!o[0])throw Error(a);if(!n[0])return r.s=u,r.c=[r.e=0],r;var c,l,h,d,g,v=o.slice(),m=c=o.length,b=n.length,y=n.slice(0,c),N=y.length,E=r,j=E.c=[],A=0,O=s+(E.e=e.e-r.e)+1;for(E.s=u,u=O<0?0:O,v.unshift(0);N++<c;)y.push(0);do{for(h=0;h<10;h++){if(c!=(N=y.length))d=c>N?1:-1;else for(g=-1,d=0;++g<c;)if(o[g]!=y[g]){d=o[g]>y[g]?1:-1;break}if(!(d<0))break;for(l=N==c?o:v;N;){if(y[--N]<l[N]){for(g=N;g&&!y[--g];)y[g]=9;--y[g],y[N]+=10}y[N]-=l[N]}for(;!y[0];)y.shift()}j[A++]=d?h:++h,y[0]&&d?y[N]=n[m]||0:y=[n[m]]
}while((m++<b||y[0]!==p)&&u--);return j[0]||1==A||(j.shift(),E.e--,O--),A>O&&w(E,O,t.RM,y[0]!==p),E},h.eq=function(r){return 0===this.cmp(r)},h.gt=function(r){return this.cmp(r)>0},h.gte=function(r){return this.cmp(r)>-1},h.lt=function(r){return this.cmp(r)<0},h.lte=function(r){return this.cmp(r)<1},h.minus=h.sub=function(r){var e,t,n,o,i=this,u=i.constructor,s=i.s,c=(r=new u(r)).s;if(s!=c)return r.s=-c,i.plus(r);var f=i.c.slice(),l=i.e,a=r.c,h=r.e;if(!f[0]||!a[0])return a[0]?r.s=-c:f[0]?r=new u(i):r.s=1,r;if(s=l-h){for((o=s<0)?(s=-s,n=f):(h=l,n=a),n.reverse(),c=s;c--;)n.push(0);n.reverse()}else for(t=((o=f.length<a.length)?f:a).length,s=c=0;c<t;c++)if(f[c]!=a[c]){o=f[c]<a[c];break}if(o&&(n=f,f=a,a=n,r.s=-r.s),(c=(t=a.length)-(e=f.length))>0)for(;c--;)f[e++]=0;for(c=e;t>s;){if(f[--t]<a[t]){for(e=t;e&&!f[--e];)f[e]=9;--f[e],f[t]+=10}f[t]-=a[t]}for(;0===f[--c];)f.pop();for(;0===f[0];)f.shift(),--h;return f[0]||(r.s=1,f=[h=0]),r.c=f,r.e=h,r},h.mod=function(r){var e,t=this,n=t.constructor,o=t.s,i=(r=new n(r)).s;if(!r.c[0])throw Error(a);return t.s=r.s=1,e=1==r.cmp(t),t.s=o,r.s=i,e?new n(t):(o=n.DP,i=n.RM,n.DP=n.RM=0,t=t.div(r),n.DP=o,n.RM=i,this.minus(t.times(r)))},h.plus=h.add=function(r){var e,t,n,o=this,i=o.constructor;if(r=new i(r),o.s!=r.s)return r.s=-r.s,o.minus(r);var u=o.e,s=o.c,c=r.e,f=r.c;if(!s[0]||!f[0])return f[0]||(s[0]?r=new i(o):r.s=o.s),r;if(s=s.slice(),e=u-c){for(e>0?(c=u,n=f):(e=-e,n=s),n.reverse();e--;)n.push(0);n.reverse()}for(s.length-f.length<0&&(n=f,f=s,s=n),e=f.length,t=0;e;s[e]%=10)t=(s[--e]=s[e]+f[e]+t)/10|0;for(t&&(s.unshift(t),++c),e=s.length;0===s[--e];)s.pop();return r.c=s,r.e=c,r},h.pow=function(r){var e=this,t=new e.constructor("1"),n=t,o=r<0;if(r!==~~r||r<-1e6||r>u)throw Error(c+"exponent");for(o&&(r=-r);1&r&&(n=n.times(e)),r>>=1;)e=e.times(e);return o?t.div(n):n},h.prec=function(r,e){if(r!==~~r||r<1||r>i)throw Error(c+"precision");return w(new this.constructor(this),r,e)},h.round=function(r,e){if(r===p)r=0;else if(r!==~~r||r<-i||r>i)throw Error(f);return w(new this.constructor(this),r+this.e+1,e)},h.sqrt=function(){var r,e,t,n=this,o=n.constructor,i=n.s,u=n.e,c=new o("0.5");if(!n.c[0])return new o(n);if(i<0)throw Error(s+"No square root");0===(i=Math.sqrt(n+""))||i===1/0?((e=n.c.join("")).length+u&1||(e+="0"),u=((u+1)/2|0)-(u<0||1&u),r=new o(((i=Math.sqrt(e))==1/0?"5e":(i=i.toExponential()).slice(0,i.indexOf("e")+1))+u)):r=new o(i+""),u=r.e+(o.DP+=4);do{t=r,r=c.times(t.plus(n.div(t)))}while(t.c.slice(0,u).join("")!==r.c.slice(0,u).join(""));return w(r,(o.DP-=4)+r.e+1,o.RM)},h.times=h.mul=function(r){var e,t=this,n=t.constructor,o=t.c,i=(r=new n(r)).c,u=o.length,s=i.length,c=t.e,f=r.e;if(r.s=t.s==r.s?1:-1,!o[0]||!i[0])return r.c=[r.e=0],r;for(r.e=c+f,u<s&&(e=o,o=i,i=e,f=u,u=s,s=f),e=new Array(f=u+s);f--;)e[f]=0;for(c=s;c--;){for(s=0,f=u+c;f>c;)s=e[f]+i[c]*o[f-c-1]+s,e[f--]=s%10,s=s/10|0;e[f]=s}for(s?++r.e:e.shift(),c=e.length;!e[--c];)e.pop();return r.c=e,r},h.toExponential=function(r,e){var t=this,n=t.c[0];if(r!==p){if(r!==~~r||r<0||r>i)throw Error(f)
;for(t=w(new t.constructor(t),++r,e);t.c.length<r;)t.c.push(0)}return g(t,!0,!!n)},h.toFixed=function(r,e){var t=this,n=t.c[0];if(r!==p){if(r!==~~r||r<0||r>i)throw Error(f);for(r=r+(t=w(new t.constructor(t),r+t.e+1,e)).e+1;t.c.length<r;)t.c.push(0)}return g(t,!1,!!n)},h.toJSON=h.toString=function(){var r=this,e=r.constructor;return g(r,r.e<=e.NE||r.e>=e.PE,!!r.c[0])},h.toNumber=function(){var r=Number(g(this,!0,!0));if(!0===this.constructor.strict&&!this.eq(r.toString()))throw Error(s+"Imprecise conversion");return r},h.toPrecision=function(r,e){var t=this,n=t.constructor,o=t.c[0];if(r!==p){if(r!==~~r||r<1||r>i)throw Error(c+"precision");for(t=w(new n(t),r,e);t.c.length<r;)t.c.push(0)}return g(t,r<=t.e||t.e<=n.NE||t.e>=n.PE,!!o)},h.valueOf=function(){var r=this,e=r.constructor;if(!0===e.strict)throw Error(s+"valueOf disallowed");return g(r,r.e<=e.NE||r.e>=e.PE,!0)},o=function r(){function e(t){var n=this;if(!(n instanceof e))return t===p?r():new e(t);if(t instanceof e)n.s=t.s,n.e=t.e,n.c=t.c.slice();else{if("string"!=typeof t){if(!0===e.strict)throw TypeError(c+"number");t=0===t&&1/t<0?"-0":String(t)}!function(r,e){var t,n,o;if(!d.test(e))throw Error(c+"number");r.s="-"==e.charAt(0)?(e=e.slice(1),-1):1,(t=e.indexOf("."))>-1&&(e=e.replace(".",""));(n=e.search(/e/i))>0?(t<0&&(t=n),t+=+e.slice(n+1),e=e.substring(0,n)):t<0&&(t=e.length);for(o=e.length,n=0;n<o&&"0"==e.charAt(n);)++n;if(n==o)r.c=[r.e=0];else{for(;o>0&&"0"==e.charAt(--o););for(r.e=t-n-1,r.c=[],t=0;n<=o;)r.c[t++]=+e.charAt(n++)}}(n,t)}n.constructor=e}return e.prototype=h,e.DP=20,e.RM=1,e.NE=-7,e.PE=21,e.strict=false,e.roundDown=0,e.roundHalfUp=1,e.roundHalfEven=2,e.roundUp=3,e}(),o.default=o.Big=o,void 0===(n=function(){return o}.call(e,t,e,r))||(r.exports=n)}()},778785:(r,e,t)=>{"use strict";t.d(e,{mobiletouch:()=>o,setClasses:()=>u,touch:()=>i});var n=t(167175);const o=n.mobiletouch,i=n.touch;function u(){document.documentElement.classList.add(n.touch?"feature-touch":"feature-no-touch",n.mobiletouch?"feature-mobiletouch":"feature-no-mobiletouch")}},444372:(r,e,t)=>{"use strict";t.r(e),t.d(e,{t:()=>n.t,withTranslationContext:()=>o});t(466281);var n=t(195619);function o(r){throw new Error("Not implemented")}},124829:function(r,e,t){r=t.nmd(r);const{clone:n,merge:o}=t(440837);var i,u=Array.isArray||function(r){return"[object Array]"===Object.prototype.toString.call(r)},s=function(r){return"object"==typeof r&&null!==r};function c(r){return"number"==typeof r&&isFinite(r)}function f(r){return null!=r&&(r.constructor===Function||"[object Function]"===Object.prototype.toString.call(r))}function l(r,e){r.prototype=Object.create(e.prototype,{constructor:{value:r,enumerable:!1,writable:!0,configurable:!0}})}"undefined"!=typeof window?(i=window.TradingView=window.TradingView||{},window.isNumber=c,window.isFunction=f,window.inherit=l,window.isArray=u):i=this.TradingView=this.TradingView||{},i.isNaN=function(r){return!(r<=0||r>0)},i.isAbsent=function(r){return null==r},i.isExistent=function(r){return null!=r},Number.isNaN=Number.isNaN||function(r){return r!=r},
i.isSameType=function(r,e){return Number.isNaN(r)||Number.isNaN(e)?Number.isNaN(r)===Number.isNaN(e):{}.toString.call(r)==={}.toString.call(e)},i.isInteger=function(r){return"number"==typeof r&&r%1==0},i.isString=function(r){return null!=r&&r.constructor===String},i.isInherited=function(r,e){if(null==r||null==r.prototype)throw new TypeError("isInherited: child should be a constructor function");if(null==e||null==e.prototype)throw new TypeError("isInherited: parent should be a constructor function");return r.prototype instanceof e||r.prototype===e.prototype},i.clone=n,i.deepEquals=function(r,e,t){if(t||(t=""),r===e)return[!0,t];if(f(r)&&(r=void 0),f(e)&&(e=void 0),void 0===r&&void 0!==e)return[!1,t];if(void 0===e&&void 0!==r)return[!1,t];if(null===r&&null!==e)return[!1,t];if(null===e&&null!==r)return[!1,t];if("object"!=typeof r&&"object"!=typeof e)return[r===e,t];if(Array.isArray(r)&&Array.isArray(e)){var n=r.length;if(n!==e.length)return[!1,t];for(var o=0;o<n;o++){if(!(c=i.deepEquals(r[o],e[o],t+"["+o+"]"))[0])return c}return[!0,t]}if(u(r)||u(e))return[!1,t];if(Object.keys(r).length!==Object.keys(e).length)return[!1,t];for(var s in r){var c;if(!(c=i.deepEquals(r[s],e[s],t+"["+s+"]"))[0])return c}return[!0,t]},i.merge=o,r&&r.exports&&(r.exports={inherit:l,clone:i.clone,merge:i.merge,isNumber:c,isInteger:i.isInteger,isString:i.isString,isObject:s,isHashObject:function(r){return s(r)&&-1!==r.constructor.toString().indexOf("function Object")},isPromise:function(r){return s(r)&&r.then},isNaN:i.isNaN,isAbsent:i.isAbsent,isExistent:i.isExistent,isSameType:i.isSameType,isArray:u,isFunction:f,parseBool:i.parseBool,deepEquals:i.deepEquals,notNull:function(r){return null!==r},notUndefined:function(r){return void 0!==r},isEven:function(r){return r%2==0},declareClassAsPureInterface:function(r,e){for(var t in r.prototype)"function"==typeof r.prototype[t]&&r.prototype.hasOwnProperty(t)&&(r.prototype[t]=function(){throw new Error(e+"::"+t+" is an interface member declaration and must be overloaded in order to be called")})},requireFullInterfaceImplementation:function(r,e,t,n){for(var o in t.prototype)if("function"==typeof t.prototype[o]&&!r.prototype[o])throw new Error("Interface implementation assertion failed: "+e+" does not implement "+n+"::"+o+" function")}})},466281:(r,e,t)=>{"use strict";t.r(e);var n=t(124829);const o=/{(\w+)}/g,i=/{(\d+)}/g;String.prototype.format=function(...r){const e=(0,n.isObject)(r[0]),t=e?o:i,u=e?(e,t)=>{const n=r[0];return void 0!==n[t]?n[t]:e}:(e,t)=>{const n=parseInt(t,10),o=r[n];return void 0!==o?o:e};return this.replace(t,u)}}}]);