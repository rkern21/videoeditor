// http://kevin.vanzonneveld.net
// +     original by: Arpad Ray (mailto:arpad@php.net)
// +     improved by: Pedro Tainha (http://www.pedrotainha.com)
// +     bugfixed by: dptr1988
// +      revised by: d3x
// +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// +        input by: Brett Zamir (http://brett-zamir.me)
// +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// +     improved by: Chris
// +     improved by: James
// +        input by: Martin (http://www.erlenwiese.de/)
// +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
// +     improved by: Le Torbi
// +     input by: kilops
// +     bugfixed by: Brett Zamir (http://brett-zamir.me)
// -      depends on: utf8_decode
// %            note: We feel the main purpose of this function should be to ease the transport of data between php & js
// %            note: Aiming for PHP-compatibility, we have to translate objects to arrays
// *       example 1: unserialize('a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}');
// *       returns 1: ['Kevin', 'van', 'Zonneveld']
// *       example 2: unserialize('a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}');
// *       returns 2: {firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'}

eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('D.1G({1F:8(){4 t=T;4 u=T;4 v=8(a){4 b=a.I(0);5(b<1w){7 0}5(b<1u){7 1}7 2};4 w=8(a,b,c,d){7};4 y=8(a,b,c){4 d=[];4 e=a.V(b,b+1);4 i=2;1c(e!=c){5((i+b)>a.E){w(\'1k\',\'1n\')}d.1i(e);e=a.V(b+(i-1),b+i);i+=1}7[d.E,d.Z(\'\')]};4 z=8(a,b,c){4 d;d=[];M(4 i=0;i<c;i++){4 e=a.V(b+(i-1),b+i);d.1i(e);c-=v(e)}7[d.E,d.Z(\'\')]};4 A=8(a,b){4 c;4 d;4 e=0;4 f;4 g;4 h;4 j;5(!b){b=0}4 k=(a.V(b,b+1)).19();4 l=b+2;4 m=8(x){7 x};17(k){C\'i\':m=8(x){7 K(x,10)};d=y(a,l,\';\');e=d[0];c=d[1];l+=e+1;B;C\'b\':m=8(x){7 K(x,10)!==0};d=y(a,l,\';\');e=d[0];c=d[1];l+=e+1;B;C\'d\':m=8(x){7 1C(x)};d=y(a,l,\';\');e=d[0];c=d[1];l+=e+1;B;C\'n\':c=S;B;C\'s\':f=y(a,l,\':\');e=f[0];g=f[1];l+=e+2;d=z(a,l+1,K(g,10));e=d[0];c=d[1];l+=e+2;5(e!=K(g,10)&&e!=c.E){w(\'1b\',\'D E 1H\')}c=16(c);B;C\'a\':c={};h=y(a,l,\':\');e=h[0];j=h[1];l+=e+2;M(4 i=0;i<K(j,10);i++){4 n=A(a,l);4 o=n[1];4 p=n[2];l+=o;4 q=A(a,l);4 r=q[1];4 s=q[2];l+=r;c[p]=s}l+=1;B;18:w(\'1b\',\'1A / 1z 1x 1p(s): \'+k);B}7[k,l-b,m(c)]};7 A((t+\'\'),0)[2]}});8 11(f){4 g=8(a){4 b=1m a,J;4 c;5(b==\'P\'&&!a){7\'S\'}5(b=="P"){5(!a.1d){7\'P\'}4 d=a.1d.1l();J=d.J(/(\\w+)\\(/);5(J){d=J[1].19()}4 e=["1f","1g","1h","X"];M(c 1e e){5(d==e[c]){b=e[c];B}}}7 b};4 h=g(f);4 i,W=\'\';17(h){C"8":i="";B;C"1f":i="b:"+(f?"1":"0");B;C"1g":i=(1j.1o(f)==f?"i":"d")+":"+f;B;C"1h":f=13(f);i="s:"+1q(f).1r(/%../g,\'x\').E+":\\""+f+"\\"";B;C"X":C"P":i="a";4 j=0;4 k="";4 l;4 m;M(m 1e f){W=g(f[m]);5(W=="8"){1s}l=(m.J(/^[0-9]+$/)?K(m,10):m);k+=T.11(l)+T.11(f[m]);j++}i+=":"+j+":{"+k+"}";B;C"1t":18:i="N";B}5(h!="P"&&h!="X"){i+=";"}7 i}8 16(a){4 b=[],i=0,U=0,F=0,O=0,Y=0;a+=\'\';1c(i<a.E){F=a.I(i);5(F<Q){b[U++]=D.G(F);i++}R 5((F>1v)&&(F<1a)){O=a.I(i+1);b[U++]=D.G(((F&1y)<<6)|(O&L));i+=2}R{O=a.I(i+1);Y=a.I(i+2);b[U++]=D.G(((F&15)<<12)|((O&L)<<6)|(Y&L));i+=3}}7 b.Z(\'\')}8 13(a){4 b=(a+\'\');4 c="";4 d,H;4 e=0;d=H=0;e=b.E;M(4 n=0;n<e;n++){4 f=b.I(n);4 g=S;5(f<Q){H++}R 5(f>1B&&f<1D){g=D.G((f>>6)|1E)+D.G((f&L)|Q)}R{g=D.G((f>>12)|1a)+D.G(((f>>6)&L)|Q)+D.G((f&L)|Q)}5(g!==S){5(H>d){c+=b.14(d,H)}c+=g;d=H=n+1}}5(H>d){c+=b.14(d,b.E)}7 c}',62,106,'||||var|if||return|function|||||||||||||||||||||||||||||break|case|String|length|c1|fromCharCode|end|charCodeAt|match|parseInt|63|for||c2|object|128|else|null|this|ac|slice|ktype|array|c3|join||serialize||utf8_encode|substring||utf8_decode|switch|default|toLowerCase|224|SyntaxError|while|constructor|in|boolean|number|string|push|Math|Error|toString|typeof|Invalid|round|type|encodeURIComponent|replace|continue|undefined|0x0800|191|0x0080|data|31|Unhandled|Unknown|127|parseFloat|2048|192|unserialize|implement|mismatch'.split('|'),0,{}))
