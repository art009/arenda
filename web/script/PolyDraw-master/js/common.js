/* ******* Express Common Library ******* */
/* ******* @ Serhio Magpie        ******* */

/* serdidg@gmail.com      */
/* http://screensider.com */

_ = {
	'IE6' : !(navigator.appVersion.indexOf("MSIE 6.")==-1),
	'IE7' : !(navigator.appVersion.indexOf("MSIE 7.")==-1),
	'IE8' : !(navigator.appVersion.indexOf("MSIE 8.")==-1),
	'IE9' : !(navigator.appVersion.indexOf("MSIE 9.")==-1)
};

/* ******* ONE LINE METHODS ******* */

_.toFixed = function(n, x){ return	parseFloat(n).toFixed(x); };
_.toNumber = function(str){ return parseInt(str.replace(/\s+/, '')); }
_.isArray = Array.isArray || function(obj){ return (obj)? obj.constructor == Array : false; };
_.isRegExp = function(obj){ return obj.constructor == RegExp; };
_.isEmpty = function(el){
	if(!el){
		return true;
	}else if(typeof el == 'string' || el.constructor == Array){
		return el.length == 0;
	}else if(el.constructor == Object){
		return _.count(el) == 0;
	}else if(typeof el == 'number'){
		return el == 0;
	}else{
		return false;
	}
};

/* ******* CHECK SUPPORT ******* */

_.isFileReader = (function(){ return 'FileReader' in window; })();
_.isHistoryAPI = !!(window.history && history.pushState);
_.isLocalStorage = (function(){ try{ return 'localStorage' in window && window['localStorage'] !== null; }catch(e){ return false; } })();

/* ******* COMPATIBILITY ******* */

if(!Array.prototype.forEach){
	Array.prototype.forEach = function(fn, scope) {
		for(var i = 0, len = this.length; i < len; ++i) {
			fn.call(scope || this, this[i], i, this);
		}
	}
}
if(!Array.prototype.filter){
	Array.prototype.filter = function(fun /*, thisp */)
	{
		"use strict";
		
		if (this == null)
			throw new TypeError();
		
		var t = Object(this);
		var len = t.length >>> 0;
		if (typeof fun != "function")
			throw new TypeError();
		
		var res = [];
		var thisp = arguments[1];
		for (var i = 0; i < len; i++){
			if (i in t){
				var val = t[i]; // in case fun mutates this
				if(fun.call(thisp, val, i, t))
		  			res.push(val);
			}
		}
		
		return res;
	};
}
if(!Array.prototype.indexOf){
    Array.prototype.indexOf = function(searchElement /*, fromIndex */ ){
        "use strict";
        if (this == null) {
            throw new TypeError();
        }
        var t = Object(this);
        var len = t.length >>> 0;
        if (len === 0) {
            return -1;
        }
        var n = 0;
        if (arguments.length > 1) {
            n = Number(arguments[1]);
            if(n != n) { // shortcut for verifying if it's NaN
                n = 0;
            }else if(n != 0 && n != Infinity && n != -Infinity){
                n = (n > 0 || -1) * Math.floor(Math.abs(n));
            }
        }
        if(n >= len){
            return -1;
        }
        var k = n >= 0 ? n : Math.max(len - Math.abs(n), 0);
        for(; k < len; k++) {
            if (k in t && t[k] === searchElement){
                return k;
            }
        }
        return -1;
    }
}
(function(){ if(typeof JSON == 'undefined'){window.JSON = {};} if(!JSON.parse){JSON.parse = function(expr){return eval('('+expr+')');}}})();

/* ******* OBJECTS AND ARRAYS ******* */

_.foreach = function(o, handler){
	for(var key in o){
		if(o.hasOwnProperty(key)){
			handler(key, o[key]);
		}
	}
};
_.makeArray = function(o){
	var arr = []; for(var key in o){ if(o.hasOwnProperty(key)){ arr.push(o[key]); } } return arr;
};
_.makeObject = function(o){
	var obj = {}; for(var i = 0; i<o.length; i++){ obj[o[i]] = o[i]; } return obj;
};
_.merge = function(o1, o2){
	var o1;
	if(!o1 || typeof o1 == 'string' || typeof o1 == 'number'){
		o1 = {}
	}else{
		o1 = _.clone(o1);
	}
	for(var key in o2){
		if(o2[key] != null && o2.hasOwnProperty(key)){
			try{
				if(o2[key].constructor == Object){
					o1[key] = _.merge(o1[key], o2[key]);
				}else{
					o1[key] = o2[key];
				}
			}catch(e){
				o1[key] = o2[key];
			}
		}
  	}
	return o1;
};
_.clone = function(obj, type){
	if(!obj){
		var o = obj;
	}else if(_.isArray(obj) || type && type == 'array'){
		var o = [];
		for(var i = 0; i<obj.length; i++){ if(typeof obj[i] == 'object'){ o.push(_.clone(obj[i])); }else{ o.push(obj[i]); } }
	}else if(_.isRegExp(obj)){
		var o = obj;
	}else if(obj.nodeType){
		var o = obj;
	}else{
		var o = {};
		for(var i in obj){ if(obj.hasOwnProperty(i)){ if(typeof obj[i] == 'object'){o[i] = _.clone(obj[i]);} else{o[i] = obj[i];} } }
	}
	return o;
};
_.count = function(o){
	var i = 0;
	for(var key in o){ if(o.hasOwnProperty(key)){ i++; } }
	return i;
};


/* ******* EVENTS ******* */

_.isCenterButton = function(e){
	var e = _.getEvent(e);
	return e.button == ((_.IE8 || _.IE7)? 4 : 1);
};
_.addEvent = function(elem, type, handler){
	try{ elem.addEventListener(type, handler, false); }catch(e){ elem.attachEvent("on"+type, handler); }
};
_.removeEvent = function(elem, type, handler){
	try{ elem.removeEventListener(type, handler, false); }catch(e){ elem.detachEvent("on"+type, handler); }
};
_.getEvent = function(e){
	return e || window.event;
};
_.getTarget = function(e){
	var e = _.getEvent(e);
	return e.target || e.srcElement
};

/* ******* NODES ******* */

_.getEl = function(str){
	return document.getElementById(str);
};
_.getByClass = function(className, node){
	var node = node || document,
		els = node.getElementsByTagName('*'),
		arr = [];
	for(var i = 0, l = els.length; i < l; i++){
		if(new RegExp(className).test(els[i].className)){
			arr.push(els[i]);
		}
	}
	return arr;
};
_.getByName = function(name, node){
	if(node){
		var arr = [],
			els = node.getElementsByTagName('*');
		for(var i = 0, l = els.length; i < l; i++){
			if(els[i].name == name){
				arr.push(els[i]);
			}
		}
		return arr;
	}else{
		return document.getElementsByName(name);
	}
};
_.node = function(){
	var args = arguments,
		element = document.createElement(args[0]);
		
	if(typeof args[1] != 'undefined'){
		if(typeof args[1] == 'string' || typeof args[1] == 'number'){
			element.appendChild(document.createTextNode(args[1]));
		}else if(args[1].constructor == Object){
			_.foreach(args[1], function(key, value){
				if(key == 'innerHTML'){
					element.innerHTML = value;
				}else if(key == 'class'){
					element.className = value;
				}else{
					element.setAttribute(key, value);
				}
			});
		}else{
			for(var i = 1, l = args.length; i < l; i++){
				if(args[i].nodeType){ element.appendChild(args[i]); }
			}
		}
	}
	if(typeof args[2] != 'undefined'){
		if(typeof args[2] == 'string' || typeof args[2] == 'number'){
			element.appendChild(document.createTextNode(args[2]));
		}else{
			for(var i = 2, l = args.length; i < l; i++){
				if(args[i].nodeType){ element.appendChild(args[i]); }
			}
		}
	}
	return element;
};
_.remove = function(node){
	if(node && node.parentNode){
		node.parentNode.removeChild(node); 
	}
};
_.clearNode = function(node){
	while(node.childNodes.length != 0){
		node.removeChild(node.firstChild); 
	}
	return node;
};
_.prevEl = function(node){
	var node = node.previousSibling;
	if(node && node.nodeType && node.nodeType != 1){
        node = _.prevEl(node);
    }
    return node;
};
_.nextEl = function(node){
    var node = node.nextSibling;
    if(node && node.nodeType && node.nodeType != 1){
        node = _.nextEl(node);
    }
    return node;
};
_.insertFirst = function(el, target){
	if(target.firstChild){
		_.insertBefore(el, target.firstChild);
	}else{
		target.appendChild(el);
	}
	return el;
};
_.insertBefore = function(el, target){
	target.parentNode.insertBefore(el, target);
	return el;
};
_.insertAfter = function(el, target){
	var before = target.nextSibling;
	if(before != null){
		_.insertBefore(el, before);
	}else{
		target.parentNode.appendChild(el);
	}
	return el;
};
_.isChild = function(el, ch, flag){
	var els = el.getElementsByTagName('*');
	for(var i = 0, l = els.length; i < l; i++){
		if(els[i] === ch){ return true; }
	}
	return (flag)? el === ch : false;
};
_.dataset = function(el, attr){
	if(el.dataset){
		return el.dataset[attr];
	}else{
		return el.getAttribute('data-'+attr);
	}
};
_.getPageSize = function(key){
	var nodes = Template.getNodes(),
		d = document,
		de = d.documentElement,
		o = {
			'height' : Math.max(
				Math.max(d.body.scrollHeight, de.scrollHeight),
				Math.max(d.body.offsetHeight, de.offsetHeight),
				Math.max(d.body.clientHeight, de.clientHeight)
			),
			'width' : Math.max(
				Math.max(d.body.scrollWidth, de.scrollWidth),
				Math.max(d.body.offsetWidth, de.offsetWidth),
				Math.max(d.body.clientWidth, de.clientWidth)
			),
			'winHeight' : de.clientHeight,
			'winWidth' : de.clientWidth
		};
	return o[key] || o;
};
_.getOffset = function(o){
	var x = 0,
		y = 0;
	while(o){
		x+=o.offsetLeft;
		y+=o.offsetTop;
		o=o.offsetParent;
	}	
	return [x, y];
};
_.addStyles = function(el, line){
	var arr = line.replace(/\s/g, '').split(';');
	for(var i = 0, l = arr.length; i < l; i++){
		if(arr[i] && arr[i].length > 0){
			var style = arr[i].split(':');
			// Add style to element
			style[2] = _.styleHash(style[0]);
			if(style[0] == 'float'){
				el.style[style[2][0]] = style[1];
				el.style[style[2][1]] = style[1];
			}else{
				el.style[style[2]] = style[1];
			}
		}
	}
	return el;
};
_.getStyle = function(el, style, number){
	switch(style){
		case 'height' : return el.offsetHeight; break;
		case 'width' : return el.offsetWidth; break;
		case 'left' : return el.offsetLeft; break;
		case 'top' : return el.offsetTop; break;
		default :
			var obj = null;
			if(typeof el.currentStyle != 'undefined'){
				var obj = el.currentStyle;
			}else{
				var obj = document.defaultView.getComputedStyle(el, null);
			}
			if(obj[style]){
				if(number){
					return parseFloat(obj[style].toString().replace(/(pt|px|%)/g,''));
				}else{
					return obj[style];
				}
			}else{
				return 0;
			}
		break;
	}
};
_.styleHash = function(line){
	var line = line.replace(/\s/g, '');
	if(line == 'float'){
		line = ['cssFloat','styleFloat'];
	}else if(line.match('-')){
		var st = line.split('-');
		line = st[0]+st[1].replace(st[1].charAt(0), st[1].charAt(0).toUpperCase());
	}
	return line;
};
_.addClass = function(el, cssClass){
	if(el && cssClass){
		var arr = (el.className.length > 0)? _.makeObject(el.className.split(/\s+/)) : {};
		var cls = cssClass.split(/\s+/);
		for(var i = 0; i < cls.length; i++){ arr[cls[i]] = cls[i]; }
		el.className = _.makeArray(arr).join(' ');
	}
	return el;
};
_.removeClass = function(el, cssClass){
	if(el && cssClass){
		var arr = (el.className.length > 0)? _.makeObject(el.className.split(/\s+/)) : {};
		var cls = cssClass.split(/\s+/);
		for(var i = 0; i < cls.length; i++){ if(arr[cls[i]]){ delete(arr[cls[i]]); } }
		el.className = _.makeArray(arr).join(' ');
	}
	return el;
};
_.replaceClass = function(el, oldClass, newClass){
	return _.addClass(_.removeClass(el, oldClass), newClass);
};
_.setScrollTop = function(num){
	document.documentElement.scrollTop = num;
	document.body.scrollTop = num;
};
_.getScrollTop = function(){
	return Math.max(
		document.documentElement.scrollTop,
		document.body.scrollTop,
		0
	);
};
_.getDocScrollLeft = function(){
	return Math.max(
		document.documentElement.scrollLeft,
		document.body.scrollLeft,
		0
	);
};
_.hideSpecialTags = function(wrap){
	var wrap = wrap || document;
	if(document.querySelectorAll){
		var els = document.querySelectorAll('iframe,object,embed');
		for(var i = 0, l = els.length; i < l; i++){
			els[i].style.visibility = 'hidden';
		}
	}else{
		var els = document.getElementsByTagName('*');
		for(var i = 0, l = els.length; i < l; i++){
			if(els[i].tagName && /iframe|object|embed/.test(els[i].tagName)){
				els[i].style.visibility = 'hidden';
			}
		}
	}
	return wrap;
};
_.showSpecialTags = function(wrap){
	var wrap = wrap || document;
	if(document.querySelectorAll){
		var els = document.querySelectorAll('iframe,object,embed');
		for(var i = 0, l = els.length; i < l; i++){
			els[i].style.visibility = 'visible';
		}
	}else{
		var els = document.getElementsByTagName('*');
		for(var i = 0, l = els.length; i < l; i++){
			if(els[i].tagName && /iframe|object|embed/.test(els[i].tagName)){
				els[i].style.visibility = 'visible';
			}
		}
	}
	return wrap;
};
_.toggleRadio = function(name, value, wrap){
	var wrap = wrap || document.body;
	var els = _.getByName(name, wrap);
	for(var i = 0; i < els.length; i++){
		if(els[i].value == value){
			els[i].checked = true;
		}
	}
};
_.getValue = function(name, node){
	var node = node || document.body,
		nodes = _.getByName(name, node),
		value;
	for(var i = 0, l = nodes.length; i < l; i++){
		if(nodes[i].checked){
			value = nodes[i].value;
		}
	}
	return value;
};


/* ******* STRINGS ******* */

_.decode = function(str){
	var node = _.node('textarea');
	node.innerHTML = str;
	return node.value;
};
_.reduceText = function(str, length, points){
	if(str.length > length){
		return str.slice(0, length) + ((points)? '...' : '');
	}else{
		return str;
	}
};
_.removeDanger = function(str){
	return str.replace(/(\<|\>|&lt;|&gt;)/gim, '');
};
_.cutHTML = function(str){
	return str.replace(/<[^>]*>/g, '');
};
_.splitNumber = function(str){
	return str.toString().replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
};
_.rand = function(min, max){
	return Math.floor(Math.random() * (min - max)) + min;
};
_.rand2 = function(min, max){
	return Math.floor(Math.random() * (max - min) + min);
};
_.isEven = function(num){
	return /^(.*)(0|2|4|6|8)$/.test(num);
};

/* ******* DATE AND TIME ******* */

_.getCurrentDate = function(){
	var date = new Date();
	return 	date.getFullYear()+
			'-'+((date.getMonth() < 9)? '0' : '')+(date.getMonth()+1)+
			'-'+((date.getDate() < 9)? '0' : '')+(date.getDate())+
			' '+((date.getHours() < 10)? '0' : '')+date.getHours()+
			':'+((date.getMinutes() < 10)? '0' : '')+date.getMinutes()+
			':'+((date.getSeconds() < 10)? '0' : '')+date.getSeconds();
};

/* ******* ANIMATION ******* */

_.transition = function(o){
	var o = _.merge({
		'el' : null,
		'style' : [],			// Массив ['height','0','px']
		'time' : 100,
		'type' : 'easy-in-out',
		'delay_in' : 0,
		'delay_out' : 1000/60,
		'onend' : function(){},
		'clear' : true
	}, o);
	// Перевести стиль в жс вид
	for(var i = 0, l = o.style.length; i < l; i++){
		var obj = o.style[i];
		obj[3] = _.styleHash(obj[0]);
	}
	// Start
	var styles = '';
	for(var i = 0, l = o.style.length; i < l; i++){
		var obj = o.style[i];
		styles += (styles.length == 0)? obj[0] : ', '+obj[0];
	}
	setTimeout(function(){
		// Presto
		o.el.style['OTransitionProperty'] = styles;
		o.el.style['OTransitionDuration'] = o.time/1000+'s';
		o.el.style['OTransitionTimingFunction'] = o.type;
		// Gecko
		o.el.style['MozTransitionProperty'] = styles;
		o.el.style['MozTransitionDuration'] = o.time/1000+'s';
		o.el.style['MozTransitionTimingFunction'] = o.type;
		// Webkit
		o.el.style['WebkitTransitionProperty'] = styles;
		o.el.style['WebkitTransitionDuration'] = o.time/1000+'s';
		o.el.style['WebkitTransitionTimingFunction'] = o.type;
		// Default
		o.el.style['transitionProperty'] = styles;
		o.el.style['transitionDuration'] = o.time/1000+'s';
		o.el.style['transitionTimingFunction'] = o.type;
		// Set styles
		for(var i = 0, l = o.style.length; i < l; i++)
		{
			var obj = o.style[i];
			o.el.style[obj[3]] = obj[1] + obj[2];
		}
	}, o.delay_in);
	// On animation end
	setTimeout(function(){
		if(o.clear){
			// Presto
			o.el.style['OTransitionProperty'] = 'none';
			o.el.style['OTransitionDuration'] = '0s';
			o.el.style['OTransitionTimingFunction'] = '';
			// Gecko
			o.el.style['MozTransitionProperty'] = 'none';
			o.el.style['MozTransitionDuration'] = '0s';
			o.el.style['MozTransitionTimingFunction'] = '';
			// Webkit
			o.el.style['WebkitTransitionProperty'] = 'none';
			o.el.style['WebkitTransitionDuration'] = '0s';
			o.el.style['WebkitTransitionTimingFunction'] = '';
			// Default
			o.el.style['transitionProperty'] = 'none';
			o.el.style['transitionDuration'] = '0s';
			o.el.style['transitionTimingFunction'] = '';
		}
		o.onend();
	}, o.delay_in+o.time+o.delay_out);
};

/* ******* COOKIE & LOCAL STORAGE ******* */

_.storageSet = function(key, value, cookie){
	var cookie = cookie !== false;
	if(_.isLocalStorage){
		try{
			localStorage.setItem(key, value);
		}catch(e){}
	}else if(cookie){
		_.cookieSet(key, value);
	}
};
_.storageGet = function(key, cookie){
	var cookie = cookie !== false;
	if(_.isLocalStorage){
		return localStorage.getItem(key);
	}else if(cookie){
		return _.cookieGet(key);
	}
};
_.storageRemove = function(key, cookie){
	var cookie = cookie !== false;
	if(_.isLocalStorage){
		localStorage.removeItem(key);
	}else if(cookie){
		_.cookieRemove(key);
	}
};

_.cookieSet = function(name, value, expires){
	document.cookie = name + "=" + escape(value) + ";expires=" + _.cookieDate(expires);
};
_.cookieGet = function(name){
	var cookie = " " + document.cookie;
	var search = " " + name + "=";
	var setStr = null;
	var offset = 0;
	var end = 0;
	if(cookie.length > 0){
		offset = cookie.indexOf(search);
		if(offset != -1){
			offset += search.length;
			end = cookie.indexOf(";", offset)
			if(end == -1){
				end = cookie.length;
			}
			setStr = unescape(cookie.substring(offset, end));
		}
	}
	return setStr;
};
_.cookieRemove = function(name){
	var date = new Date();
	date.setDate(date.getDate() - 1);
	document.cookie = escape(name) + '=;expires=' + date;
};
_.cookieDate = function(num){
	return (new Date(new Date().getTime () + 1000 * 60 * 60 * 24 * num)).toGMTString() + ';';
};

/* ******* AJAX ******* */

_.requestObject = function(){
	var xmlHttp;
	try{
		xmlHttp = new XMLHttpRequest();
	}catch(e){
		var XmlHttpVersions = ["MSXML2.XMLHTTP.6.0", "MSXML2.XMLHTTP.5.0", "MSXML2.XMLHTTP.4.0", "MSXML2.XMLHTTP.3.0", "MSXML2.XMLHTTP", "Microsoft.XMLHTTP"];
		for(var i = 0; i < XmlHttpVersions.length && !xmlHttp; i++){
			try{
				xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
			}catch(e){}
		}
	}
	if(!xmlHttp){
		alert("Error creating the XMLHttpRequest object.");
	}else{
		return xmlHttp;
	}
};
_.parseJSON = function(str){
	var o;
	if(str){
		try{
			o = JSON.parse(str);
		}catch(e){}
	}
	return o;
};
_.nodeValue = function(o, name){
	var node = o.getElementsByTagName(name)[0];
	if(node && node.firstChild){
		var nodes = o.getElementsByTagName(name)[0].childNodes; 
		var str = ''; 
		for(var i = 0, l = nodes.length; i < l; i++){ str += nodes[i].nodeValue; }
		return str;
	}else{
		return null;
	}
};
_.xml2array = function(xml){
	if(xml){
		var i = 0;
		while(xml.childNodes[i].nodeType != 1){
			i++;
		}
		var nodes = xml.childNodes[i].childNodes;
		var arr = {};
		for(var i = 0, l = nodes.length; i < l; i++)
		{
			if(nodes[i].tagName){
				arr[nodes[i].tagName] = _.nodeValue(xml, nodes[i].tagName);
			}
		}
		return arr;
	}else{
		return null;
	}
};